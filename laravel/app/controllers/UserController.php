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
    }

    /**
     * This needs to be given public access
     *
     * @param string $username
     */
    public function getView($username)
    {

    }
}
