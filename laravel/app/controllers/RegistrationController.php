<?php

class RegistrationController extends BaseController {

    public function __construct(){
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    public function getRegisterExpert()
    {
        return View::make('registration.registration', array(
                'mode'  => 'expert',
                'route' => 'register.expert',
            ));
    }

    public function getRegisterStudent()
    {
        return View::make('registration.registration', array(
                'mode'  => 'student',
                'route' => 'register.student',
            ));
    }

    public function postRegisterExpert()
    {
        return $this->processRegistration(Input::all());
    }

    public function postRegisterStudent()
    {
        return $this->processRegistration(Input::all());
    }

    protected function processRegistration($inputs)
    {
        $user = new User;

        /**
         * Configure MagniloquentContextsPlus for validation
         */
        $user->addContext(array('student-save', 'password-save', 'registration-save'));
        // This form can only be used to create experts and students (not Admins)
        if($inputs['mode'] == 'expert'){
            $user->addContext('tutor-save');
            $inputs['role_id'] = 2;
        } else {
            $inputs['role_id'] = 4;
        }

        $user->fill($inputs);


        if ($user->save()){
            if ($user->isTutor()){
                Event::fire('user.expert-registration', array($user));
            } else {
                Event::fire('user.student-registration', array($user));
            }
            Auth::login($user);
            Event::fire('user.login', array($user));
            if (Auth::user()->isTutor()){
                return Redirect::route('user.billing')
                    ->with('flash_success', trans("You have successfully registered an account. Please add in your hourly rate and you will be set."));

            } else {
                return Redirect::route('user.my-account')
                    ->with('flash_success', trans("You have successfully registered an account! On to find a good tutor! :-)"));

            }
        }
        // Since save failed, we need to pass back the unencrypted password to the form (that may or may
        //  not have been autoHashed by Magniloquent before failing)
        $user->password = $inputs['password'];

        return Redirect::back()
            ->withErrors($user->errors())
            ->withInput(Input::all())
            ->with('flash_error', trans("Please correct the following errors and try again:"));

    }

}
