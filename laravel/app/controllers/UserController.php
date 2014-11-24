<?php

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as localAdapter;
use Aws\S3\S3Client;
use League\Flysystem\Adapter\AwsS3 as S3Adapter;

use Transit\Transformer\Image\CropTransformer;
use Transit\Transformer\Image\ResizeTransformer;
use Carbon\Carbon;

class UserController extends BaseController {

    public function __construct(){
        $this->beforeFilter('csrf', array(
                'only' => 'postCreate, postDelete, postEdit, postRegister, postRemind, postVerify, postSetPassword,
                postValidateUsername'
            ));
        $this->beforeFilter('ajax', array('only' => 'getCalendarEvents, postValidateUsername'));

        $this->beforeFilter('auth', array(
                'except' => array('getLogin', 'postLogin', 'search', 'getForgot', 'getView', 'postValidateUsername')
            ));

    }

    public function getLogin()
    {
        return View::make('user.login');
    }

    public function postLogin()
    {
        // we need to handle either old ways of passing in data
        // or our new view setup

        if(Input::has('data.User.username')) {
            $username = Input::get('data.User.username');
            $password = Input::get('data.User.password');
        } else {
            $username = Input::get('username');
            $password = Input::get('password');
        }

        if (Input::has('remember_me')){
            $rememberMe = Input::get('remember_me');
        } else {
            $rememberMe = false;
        }

        $user = User::where('username', $username)->first();
        if ($user){
            $loggedIn = Auth::attempt(array(
                    'username' => $username,
                    'password' => $password,
                ), $rememberMe);
        }
        if (!$user || !$loggedIn){
            if ($user){
                Event::fire('user.login-attempt-failed', array($user));
            }
//            if($this->RequestHandler->isXml()) {
//                return $this->sendXmlError(1, "The password you entered is incorrect");
//            } else {
//            }

            return Redirect::route('login')
                ->with('flash_error', trans('Your username or password is incorrect.'))
                ->withInput();
        }

        Event::fire('user.login', array(Auth::user()));

        if (Input::get('timezone') != '' && Input::get('timezone') != $user->timezone){
            switch($user->timezone_update){
                case User::TIMEZONE_UPDATE_AUTO:
                    $user->timezone = Input::get('timezone');
                    $user->save();
                    break;

                case User::TIMEZONE_UPDATE_ASK:
                    return View::make('user.timezone-check', array(
                            'browserTimezone'   => Input::get('timezone'),
                            'user'              => $user,
                        ));

                case User::TIMEZONE_UPDATE_NEVER:
                    if (empty($user->timezone)){
                        return Redirect::route('user.timezone')
                            ->with('flash_error', trans("Please confirm that your timezone is correct"));
                    }
                    break;

            }
        }

        return Redirect::intended(URL::route('user.my-account'))
            ->with('flash_success', trans('Logged in successfully. Welcome back to Botangle.'));

            // API: handle our API info and send back info
//            if($this->RequestHandler->isXml()) {
//                $user = $this->Session->read('Auth.User');
//
//                // we'll translate a bit between what we've got and our system
//                $this->set('id', $user['id']);
//                $this->set('role', $user['Role']['alias']);
//                $this->set('firstname', $user['name']);
//                $this->set('lastname', $user['lname']);
//                $this->set('profilepic', $user['profilepic']);
//                $this->set('_rootNode', 'user');
//                $this->set('_serialize', array('id', 'role', 'firstname', 'lastname', 'profilepic'));
//            } else {
//            }
    }

    public function getMyAccount()
    {
        if(Auth::user()->isTutor() || Auth::user()->isAdmin()) {
            $mode = 'expert';
        } else {
            $mode = 'student';
        }
        return View::make('user.account.my-account', array(
                'user'  => Auth::user(),
                'mode'  => $mode,
            ));
    }

    public function postMyAccount()
    {
        $userId = Input::get('id');
        if (Auth::user()->id != $userId && !Auth::user()->isAdmin()){
            App::abort('404', 'You are not authorized to update this user account.');
        }
        $user = User::findOrFail($userId);

        // Copy the inputs to $inputs for later use if file upload occurs
        $inputs = Input::all();

        // Set the base context (the student account is a subset of the tutor)
        $user->addContext('student-save');

        // If the user is a tutor, there are more fields to be validated and saved
        if ($user->isTutor() || $user->isAdmin()){
            $user->addContext('tutor-save');
        }
        // Only validate profilepic if a new file has been uploaded
        if (Input::hasFile('profilepic')){
            $user->addContext('profile-pic-upload');
        }

        $user->fill($inputs);
        if (!$user->validate()){
            if (isset($inputs['profilepic'])) unset($inputs['profilepic']);
            return Redirect::route('user.my-account')
                ->withInput($inputs)
                ->withErrors($user->errors())
                ->with('flash_error', trans("Your information could not be updated. Please try again."));
        }

        /* Profilepic upload to Amazon S3 */
        if (Input::hasFile('profilepic')){
            $user->profilepic = $this->uploadProfilePicToS3();

            // Need to stop save validating the S3 filename, which will fail Laravel's image validation.
            //  Note: the original uploaded file has already passed Laravel's validation above
            $user->removeContext('profile-pic-upload');
        }

        if ($user->save()) {
            Event::fire('user.account-updated', array(Auth::user()));
            return Redirect::route('user.my-account')
                ->with('flash_success', trans("Your information has been updated"));
        } else {
            return Redirect::route('user.my-account')
                ->withErrors($user->errors())
                ->with('flash_error', trans("Your information could not be updated. Please try again."));
        }
    }

    /**
     * This uploads the profilepic to S3.
     * This is a quick and perhaps dirty implementation combining the original Botangle's use of Transit
     * and the rather nice FlySystem. Transit is only being used for image cropping and resizing
     * for consistency with the original system.
     *
     * @return \Illuminate\Http\RedirectResponse|string
     */
    private function uploadProfilePicToS3()
    {
        $file = Input::file('profilepic');
        $extension = $file->guessClientExtension();
        $tmpPath = $file->getPath();
        $transitFile = new \Transit\File($file->getPathname());
        $transitFile = (new CropTransformer(array('width' => 250, 'height' => 250, 'aspect' => true)))->transform($transitFile, true);
        $transitFile = (new ResizeTransformer(array('width' => 250, 'height' => 250, 'aspect' => true)))->transform($transitFile, true);
        $transitFilename = basename($transitFile->path());
        // Need to get the transformed filename

        $local = new Filesystem(new localAdapter($tmpPath));
        $remote = new Filesystem(new S3Adapter(
            S3Client::factory(array(
                    'key'    => Config::get('services.s3.accessKey'),
                    'secret' => Config::get('services.s3.secretKey'),
                )),
            Config::get('services.s3.bucket'),
            Config::get('services.s3.profilepicFolder'),
            ['region'   => Config::get('services.s3.region')]
        ));

        if ($local->has($transitFilename)) {
            $contents = $local->read($transitFilename);
            $randomFilename = str_random(15).'.'.$extension;
            if (!$remote->write($randomFilename, $contents, [
                    'visibility'    => 'public',
                ]))
            {
                return Redirect::route('user.my-account')
                    ->with('flash_error', trans("Profile Picture could not be uploaded. Please try again."));
            }
        }
        Event::fire('user.profilepic-uploaded', array(Auth::user()));

        return Config::get('services.s3.url') .'/'. Config::get('services.s3.bucket') .'/'.
            Config::get('services.s3.profilepicFolder') . '/'.$randomFilename;
    }

    public function postChangePassword()
    {
        $userId = Input::get('id');
        if (Auth::user()->id != $userId && !Auth::user()->isAdmin()){
            App::abort('404', 'You are not authorized to update this user account.');
        }
        $user = User::findOrFail($userId);

        // TODO: determine best location for this validator extension
        Validator::extend('password_correct', function($attribute, $value, $parameters ){
                $user = User::findOrFail($parameters[0]);
                return $user->isPasswordCorrect($value);
            });
        // Build the custom messages array.
        $messages = array(
            'password_correct' => trans('The :attribute is incorrect.'),
            'confirmed'        => trans('New Password and Confirm Password did not match.'),
        );

        // Validate old and new passwords for correct field format
        $rules = array(
            'old_password'      => array('required', 'min:6', 'max:255', 'password_correct:'.$user->id),
            'new_password'      => array('required', 'min:6', 'max:100', 'confirmed'),
        );
        $validator = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()){
            return Redirect::route('user.my-account')
                ->withErrors($validator)
                ->with('flash_error', trans("Password change failed."));
        }

        if (Input::get('old_password') == Input::get('new_password')){
            return Redirect::route('user.my-account')
                ->with('flash_error', trans("Your new password must be different from your old password."));

        } elseif ($user->updatePassword(Input::get('old_password'), Input::get('new_password'))){
            Event::fire('user.password-change', array(Auth::user()));
            return Redirect::route('user.my-account')
                ->with('flash_success', trans("You have changed your password."));

        } else {
            return Redirect::route('user.my-account')
                ->with('flash_error', trans("There was a problem updating your password."));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function getView($id)
    {
        //TODO: verify that users can't add a purely numeric username otherwise this is a bug
        if (is_numeric($id)) {
            $model = User::where('id', $id)->averageRating()->first();
            if (!$model){
                App::abort('404', 'You are not authorized to view this page.');
            }
        }
        else {
            $model = User::where('username' , '=', $id)->averageRating()->first();
        }

        $activeUser = Auth::user();
        if ($activeUser){
            $isOwnTutorProfile = ($activeUser->id == $model->id && $model->isTutor());
        } else {
            $isOwnTutorProfile = false;
        }

        $userStatuses = $model->statuses;

        $userLessons = Lesson::active()->involvingUser($model)
            ->where('is_confirmed', true);

        /*    ->select(array(
                DB::raw('COUNT(lessons.id) as lessons_count'),
                DB::raw('SUM(duration) as total_duration')
            ))
            ->groupBy('tutor')
            ->first(); */
        $lessonsCount = $userLessons->count();
        $totalDuration = $userLessons->sum('duration');

        return View::make('user.view', array(
                'model' => $model,
                'isOwnTutorProfile' => $isOwnTutorProfile,
                'userStatuses'      => $userStatuses,
                'lessonsCount'      => $lessonsCount,
                'totalDuration'     => $totalDuration,
                'subjects' => explode(",", $model->subject),
            ));
    }

    /**
     * Add a new status update for the current user
     * @return mixed
     */
    public function postStatus()
    {
        $userId = Input::get('id');
        if (Auth::user()->id != $userId){
            App::abort('401', "You are not authorized to update another user's status.");
        }
        $user = User::findOrFail($userId);

        $profileUrl = 'user/'.$user->username;

        $userStatus = UserStatus::create(array(
                'created_by_id' => $user->id,
                'status_text'   => Input::get('status_text'),
                'status'        => 1,
            ));

        if ($userStatus->id){
            Event::fire('user.new-status', array($userStatus));
            return Redirect::to($profileUrl)
                ->with('flash_success', trans("You have updated your status."));
        } else {
            return Redirect::to($profileUrl)
                ->with('flash_error', trans("There was a problem updating your status."));
        }
    }

    public function getCalendarEvents($id)
    {
        $user = User::findOrFail($id);
        if (Auth::user()){
            $loggedInUserId = Auth::user()->id;
        } else {
            // Guest users should not be able to see any calendar info
            return Response::json(array());
        }

        $month = (int) Input::get('mes');
        $year = (int) Input::get('ano');

        $startDate = Carbon::createFromDate($year, $month, 1, Auth::user()->timezone);
        $endDate = Carbon::createFromDate($year, $month+1, 1, Auth::user()->timezone);

        // Get lessons where the passed in user is either the tutor or the student
        $lessons = Lesson::active()
            ->where(function($query) use($user){
                $query->where('tutor', $user->id)
                    ->orWhere('student', $user->id);
            })
            ->where('lesson_at', '>=', $startDate)
            ->where('lesson_at', '<', $endDate)
            ->orderBy('lesson_at', 'asc')
            ->get();

        // Show all lessons (student and mentor) for $user but only show details where
        //   the current logged in user is the corresponding mentor/student
        // Build all lessons for a given day into a single calendar event
        $daysArray = array();
        $calendarDate = null;
        foreach($lessons as $lesson){
            if ($calendarDate != $lesson->lesson_at->format('Y-m-d')){
                $calendarDate = $lesson->lesson_at->format('Y-m-d');
                $dayType = $lesson->getDayType($user->id, $loggedInUserId);
                $day = array();
                $day['date']    = $lesson->lesson_at->format('j/n/Y'); // d/m/Y doesn't work with bic_calendar
                $day['title']   = $lesson->getCalendarEventTitle($loggedInUserId, $user->id);
                $day['link']    = "#";
                $day['color']   = Lesson::getCalendarEventColor($dayType);
                $day['class']   = "miclasse";
                $day['content'] = "";
                $daysArray[] = $day;
            } else {
                $dayType = $lesson->getDayType($user->id, $loggedInUserId, $dayType);
                // Add this lesson to the existing day
                $day = $daysArray[count($daysArray)-1];
                $day['title'] .= "\n\n" . $lesson->getCalendarEventTitle($loggedInUserId, $user->id);
                $day['color'] = Lesson::getCalendarEventColor($dayType);
                $daysArray[count($daysArray)-1] = $day;
            }

        }
        return Response::json($daysArray);
    }

    public function getBilling()
    {
        return View::make('user.billing', array(
                'user'  => Auth::user(),
                'rate'  => Auth::user()->getActiveUserRateObject(),
            ));
    }

    public function postRateChange()
    {
        $inputs = Input::all();
        $user = User::findOrFail($inputs['userid']);
        $previousRate = UserRate::find($inputs['current_rate_id']);

        // Only create a new UserRate if the rate has been changed (or there's no previous rate)
        if (!$previousRate || ($previousRate->rate != $inputs['rate'] || $previousRate->type != $inputs['price_type'])){
            $userRate = new UserRate;
            $userRate->fill($inputs);

            if ($userRate->save()){
                return Redirect::back()
                    ->with('flash_success', trans('You have successfully updated your rate.'));
            } else {
                return Redirect::back()
                    ->with('flash_error', trans('Unable to save rate changes'))
                    ->withErrors($userRate->errors())
                    ->withInput();
            }

        }
    }

    public function getLessons()
    {
        return View::make('user.lessons', array(
                'proposals' => Lesson::active()->proposals()->involvingUser(Auth::user())
                        ->orderBy('lesson_at')->get(),
                'upcomingLessons'  => Lesson::active()->upcoming()->involvingUser(Auth::user())
                        ->orderBy('lesson_at')->get(),
                'pastLessons'  => Lesson::active()->past()->involvingUser(Auth::user())
                        ->orderBy('lesson_at', 'desc')->get(),
            ));
    }

    public function getForgot()
    {
        return View::make('user.forgot');
    }

    public function getTimezoneChange()
    {
        return View::make('user.timezone', array(
                'user' => Auth::user(),
            ));
    }

    public function postTimezoneChange()
    {
        $user = Auth::user();
        $timezone = Input::get('timezone');
        try {
            new DateTimeZone($timezone);
        } catch(Exception $e){
            return Redirect::back()
                ->with('flash_error', $e->getMessage());
        }
        $user->timezone = $timezone;
        if (Input::has('timezone_update')){
            $user->timezone_update = Input::get('timezone_update');
        }
        $user->save();
        if (Input::has('ChangeTimezone')){
            return Redirect::route('user.timezone');
        } else {
            return Redirect::intended(URL::route('user.my-account'));
        }
    }

    public function postValidateUsername()
    {
        if (!Input::has('username')){
            return App::abort('400', 'Username missing');
        }
        $user = User::where('username', Input::get('username'))->first();
        if ($user){
            $responseArray = [
                'text'  => 'Username not available. Try another',
                'class' => 'alert alert-danger',
            ];
        } else {
            $responseArray = [
                'text'  => 'Username available',
                'class' => 'alert alert-info',
            ];
        }
        return Response::json($responseArray);
    }
}
