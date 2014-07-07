<?php

class UserController extends BaseController {

    public function getForgot()
    {
    }

    public function getIndex()
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
        $validated = $validator->fails();

        $salt = Config::get('cake.salt');
        $password = sha1($salt . $password);

        // @TODO: attempt only logins for active users
        if ($validated || !Auth::attempt(array('username' => $username, 'password' => $password))) {
            Event::fire('Controller.User.loginFailure', $this);

//            if($this->RequestHandler->isXml()) {
//                return $this->sendXmlError(1, "The password you entered is incorrect");
//            } else {
//            }

            return Redirect::route('user.login')
                ->with('flash_error', 'The password you entered is incorrect.')
                ->withErrors($validator)
                ->withInput();
        }

        Event::fire('Controller.User.loginSuccessful', $this);

        // register that this user is online now
        Auth::user()->setOnlineStatus(true);

        // @TODO: do we want to send them a welcome back message?
        return Redirect::intended('user.profile');

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

    /**
     * This needs to be given public access
     *
     * @param string $username
     */
    public function getView($username)
    {

    }

    public function getLogout()
    {
        print "we're here";
        die;
    }

    public function getMyAccount()
    {
        print "we're here";
        die;
    }
}
