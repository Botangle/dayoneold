<?php
App::uses('AppController', 'Controller');

class IndexController extends AppController {

	function beforeFilter() {
		parent::beforeFilter();
//		 $this->Security->validatePost = false;
//		$this->Security->csrfCheck = false;
//		$this->Security->unlockedActions = array('index', 'step2', 'step3', 'thanks', 'error', 'index2', 'cancelbooking');
		$this->Auth->allow('index', 'step2', 'step3', 'thanks', 'error', 'index2', 'cancelbooking');
	}

	function Index() {
		
	}

}
