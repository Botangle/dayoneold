<?php

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

    public function getForgot()
    {
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

        // Set the base context (the student account is a subset of the tutor)
        $user->addContext('student-save');

        // If the user is a tutor, there are more fields to be validated and saved
        if ($user->isTutor() || $user->isAdmin()){
            $user->addContext('tutor-save');
        }

        /* TODO: Implement profilepic upload and fetching with Amazon S3
        if (Input::hasFile('profilepic')){
            $file = Input::file('file');
            $filename = $file->getClientOriginalName();
            $profileDir = url('/upload');
            if (!$file->move($profileDir, $filename)){
                return Redirect::route('user.my-account')
                    ->with('flash_error', trans("Profile Picture could not be saved. Please, try again."));
            }
            $user->profilepic = $filename;
        }*/

        if ($user->save(Input::all())) {
            Event::fire('user.account-updated', array(Auth::user()));
            return Redirect::back()
                ->withInput(Input::all())
                ->with('flash_success', trans("Your information has been updated"));
        } else {
            return Redirect::route('user.my-account')
                ->withErrors($user->errors())
                ->with('flash_error', trans("Your information could not be updated. Please try again."));
        }
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
}
