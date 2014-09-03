<?php

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as localAdapter;
use Aws\S3\S3Client;
use League\Flysystem\Adapter\AwsS3 as S3Adapter;

use Transit\Transformer\Image\CropTransformer;
use Transit\Transformer\Image\ResizeTransformer;


class UserController extends BaseController {

    public function __construct(){
        $this->beforeFilter('csrf', array('only' => 'postCreate, postDelete, postEdit, postRegister, postRemind, postVerify, postSetPassword'));
        $this->beforeFilter('ajax', array('only' => 'getCalendarEvents'));

        $this->beforeFilter('auth', array(
                'except' => array('getLogin', 'postLogin', 'search', 'getForgot', 'getView')
            ));

    }

    public function getBilling()
    {
        // TODO: complete the billing view
        return View::make('user.billing');
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
            App::abort('404', "You are not authorized to update another user's status.");
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

        $startDate = new DateTime();
        $startDate->setDate($year, $month, 1);
        $startDate->setTime(0, 0, 0);
        $endDate = new DateTime();
        $endDate->setDate($year, $month+1, 1);
        $endDate->setTime(0, 0, 0);

        // Get lessons where the passed in user is either the tutor or the student
        $lessons = Lesson::active()
            ->where(function($query) use($user){
                $query->where('tutor', $user->id)
                    ->orWhere('student', $user->id);
            })
            ->where('lesson_date', '>=', $startDate)
            ->where('lesson_date', '<', $endDate)
            ->orderBy('lesson_date', 'asc')
            ->orderBy('lesson_time', 'asc')
            ->get();

        // Show all lessons (student and mentor) for $user but only show details where
        //   the current logged in user is the corresponding mentor/student
        // Build all lessons for a given day into a single calendar event
        $daysArray = array();
        $calendarDate = null;
        foreach($lessons as $lesson){
            if ($calendarDate != $lesson->lesson_date){
                $calendarDate = $lesson->lesson_date;
                $dayType = $lesson->getDayType($user->id, $loggedInUserId);
                $lessonDate = DateTime::createFromFormat('Y-m-d', $lesson->lesson_date);
                $day = array();
                $day['date']    = $lessonDate->format('j/n/Y'); // d/m/Y doesn't work with bic_calendar
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

    public function search()
    {

    }

    public function getLessons()
    {
        return View::make('user.lessons', array(
                'proposals' => Lesson::active()->proposals()->involvingUser(Auth::user())
                        ->orderBy('lesson_date')->orderBy('lesson_time')->get(),
                'upcomingLessons'  => Lesson::active()->upcoming()->involvingUser(Auth::user())
                        ->orderBy('lesson_date')->orderBy('lesson_time')->get(),
                'pastLessons'  => Lesson::active()->past()->involvingUser(Auth::user())
                        ->orderBy('lesson_date', 'desc')->orderBy('lesson_time', 'desc')->get(),
            ));
    }

    public function getForgot()
    {
        return View::make('user.forgot');
    }

    public function postForgot()
    {
        try {
            $user = Sentry::findUserByLogin(Input::get('email'));
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e){
            return Redirect::back()
                ->withInput(Input::all())
                ->with('flash_error', trans('User not found.'));
        }

        $email = $user->email;
        $emailViewData = array(
            'resetCode'         => $user->getResetPasswordCode(),
        );

        // Send an email with a link that incorporates the reset code.
        try{
            Mail::send('emails.auth.reset-password', $emailViewData, function($message) use ($email){
                    $message->to($email)
                        ->subject(trans('ModernIcons - Password Reset'));
                });
        } catch(Exception $e){
            return Redirect::back()
                ->withInput(Input::all())
                ->with('flash_error', trans("There was a problem sending the password reset to") ." ". $email);
        }

        return Redirect::back()
            ->with('flash_success', trans("You have been sent an email with instructions on how to reset your password."));
    }

    public function getPasswordReset($resetCode)
    {
        try {
            $user = Sentry::findUserByResetPasswordCode($resetCode);
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e){
            // The provided password reset code is Invalid
            return Redirect::action('LoginController@getForgot')
                ->with('flash_error', trans("Reset code invalid."));
        }

        // Check if the reset password code is valid
        if ($user->checkResetPasswordCode($resetCode)){

            // Display a form for resetting their password
            return View::make('login.reset', array(
                    'resetCode' => $resetCode,
                ));
        }
        else
        {
            // The provided password reset code is Invalid
            return Redirect::action('LoginController@getForgot')
                ->with('flash_error', trans("Reset code invalid."));
        }

    }

    public function postPasswordReset()
    {
        // TODO: determine best location for this validator extension
        Validator::extend('password_strong', function($attribute, $value, $parameters ){
                return User::isPasswordStrong($value);
            });
        // Build the custom messages array.
        $messages = array(
            'password_strong' => trans('The :attribute must contain at least 8 characters. 1 must be a capital letter and 1 a non-alphanumeric character.'),
            'confirmed'        => trans('New Password and Confirm Password did not match.'),
        );

        // Validate old and new passwords for correct field format
        $rules = array(
            'new_password'      => array('required', 'min:8', 'max:255', 'confirmed', 'password_strong'),
        );
        $validator = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()){
            return Redirect::back()
                ->with('flash_error', trans('Password reset failed'))
                ->withErrors($validator);
        }

        // Attempt to reset the user password
        try {
            $user = Sentry::findUserByResetPasswordCode(Input::get('resetCode'));
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e){
            // The provided password reset code is Invalid
            return Redirect::action('LoginController@getForgot')
                ->with('flash_error', trans("Reset code invalid."));
        }
        if ($user->attemptResetPassword(Input::get('resetCode'), Input::get('new_password'))){
            Sentry::login($user);
            return Redirect::route('dashboard')
                ->with('flash_success', trans('Password reset successfully'));

        } else {
            // Password reset failed
            return Redirect::back()
                ->with('flash_error', trans('Password reset failed'));
        }
    }
}
