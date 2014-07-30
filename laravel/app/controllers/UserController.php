<?php

class UserController extends BaseController {

    public function getBilling()
    {
    }

    public function getForgot()
    {
    }

    public function getLessons()
    {
    }

    public function getLogin()
    {
        return View::make('user.login');
    }

    public function postLogin()
    {
        Event::fire('Controller.User.beforeLogin', $this);

        // we need to handle either old ways of passing in data
        // or our new view setup

        if(Input::has('data.User.username')) {
            // old setup
            $rules = array(
                'data.User.username'      => array('required', 'min:2', 'max:255'),
                'data.User.password'      => array('required', 'min:6', 'max:255'),
            );

            $username = Input::get('data.User.username');
            $password = Input::get('data.User.password');
        } else {
            // new setup (no array nesting needed with Laravel)
            $rules = array(
                'username'      => array('required', 'min:2', 'max:255'),
                'password'      => array('required', 'min:6', 'max:255'),
            );

            $username = Input::get('username');
            $password = Input::get('password');
        }

        $validator = Validator::make(Input::all(), $rules);
        $validationFailed = $validator->fails();

        $user = User::where('username', $username)->first();
        if ($user){
            $loginSuccess = $user->isPasswordCorrect($password);
        } else {
            $loginSuccess = false;
        }

        // @TODO: attempt only logins for active users
        if ($validationFailed || !$loginSuccess) {
            Event::fire('Controller.User.loginFailure', $this);

//            if($this->RequestHandler->isXml()) {
//                return $this->sendXmlError(1, "The password you entered is incorrect");
//            } else {
//            }

            return Redirect::route('login')
                ->with('flash_error', 'The password you entered is incorrect.')
                ->withErrors($validator)
                ->withInput();
        }

        Event::fire('Controller.User.loginSuccessful', $this);

        // register that this user is online now
        Auth::user()->setOnlineStatus(true);

        // @TODO: do we want to send them a welcome back message?
        return Redirect::intended(URL::route('user.my-account'));

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
            'new_password'      => array('required', 'min:6', 'max:255', 'confirmed'),
        );
        $validator = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()){
            return Redirect::route('user.my-account')
                ->withErrors($validator)
                ->with('flash_error', trans("Password change failed."));
        }

        if ($user->updatePassword(Input::get('old_password'), Input::get('new_password'))){
            return Redirect::route('user.my-account')
                ->with('flash_success', trans("You have changed your password."));
        } else {
            return Redirect::route('user.my-account')
                ->with('flash_error', trans("There was a problem updating your password."));
        }
    }

    public function getMessages()
    {
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

        $userLessonStats = Lesson::where('tutor', $model->id)
            ->where('is_confirmed', true)
            ->select(array(
                DB::raw('COUNT(lessons.id) as lessons_count'),
                DB::raw('SUM(duration) as total_duration')
            ))
            ->groupBy('tutor')
            ->first();

        return View::make('user.view', array(
                'model' => $model,
                'isOwnTutorProfile' => $isOwnTutorProfile,
                'userStatuses'      => $userStatuses,
                'userLessonStats'   => $userLessonStats,
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

        if (UserStatus::create(array(
                'created_by_id' => $user->id,
                'status_text'   => Input::get('status_text'),
                'status'        => 1,
            ))){
            return Redirect::to($profileUrl)
                ->with('flash_success', trans("You have updated your status."));
        } else {
            return Redirect::to($profileUrl)
                ->with('flash_error', trans("There was a problem updating your status."));
        }
    }
}
