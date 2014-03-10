<?php
App::uses('AppController', 'Controller');

class UserController extends AppController
{

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Security->validatePost = false;
        $this->Security->csrfCheck = false;
        $this->Security->unlockedActions = array('registration');
        $this->Auth->allow('registration');

    }

    function Registration()
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            pr($this->request->data);
            die;
        }
    }
}