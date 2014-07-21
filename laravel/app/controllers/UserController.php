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
            return View::make('user.account.expert', array(
                    'user'  => Auth::user()
                ));
        } else {
            return View::make('user.account.student', array(
                    'user'  => Auth::user()
                ));
        }
    }

    public function postMyAccount()
    {
        $userId = Input::get('id');
        if (Auth::user()->id != $userId && !Auth::user()->isAdmin()){
            App::abort('404', 'You are not authorized to update this user account.');
        }
        $user = User::findOrFail($userId);

        // TODO Validate and update the my-account data

        return Redirect::back()
            ->withInput(Input::all())
            ->with('flash_success', trans("We're gonna update your data soon."));
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
        $failedValidation = $validator->fails();

        if ($failedValidation){
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
     * This needs to be given public access
     *
     * @param integer $id
     */
    public function getView($id)
    {
        //TODO: verfiy that users can't add a purely numeric username otherwise this is a bug
        if (is_numeric($id)) {
            $model = User::findOrFail($id);
        }
        else {
            $model = User::where('username' , '=', $id)->first();
        }

        return View::make('user.view', array(
                'model' => $model,
                'userRating' => array(),
                'subjects' => array(),
                'userReviews' => array(),
                'lessonClasscount' => array(),
                'userStatuses' => array(),
            ));
    }
}
