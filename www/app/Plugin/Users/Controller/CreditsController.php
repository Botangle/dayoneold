<?php
App::uses('UsersAppController', 'Users.Controller');
App::Import('ConnectionManager');

/*
 * CreditsController Controller
 */

class CreditsController extends UsersAppController {

/**
 * Components
 *
 * @var array
 * @access public
 */
	public $components = array(
        'Braintree',
		'Search.Prg' => array(
			'presetForm' => array(
				'paramType' => 'querystring',
			),
			'commonProcess' => array(
				'paramType' => 'querystring',
				'filterEmpty' => true,
			),
		),
	);

/**
 * Preset Variables Search
 *
 * @var array
 * @access public
 */
	public $presetVars = true;

	public $databaseName = "";

/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
    public $uses = array(
        'UserCredit',
        'Transaction',
        'Users.User',
    );

	public $helper = array('Session', 'Cache', 'Credits');
	
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);
		$this->getEventManager()->attach(new UserListener());
	}

	function beforeFilter() {
		$dataSource = ConnectionManager::getDataSource('default');
		$dsc = $dataSource->config;
		$this->databaseName = $dsc['database'];

		parent::beforeFilter();

// the below doesn't handle all the actions we need unlocked.  For now, to get the site working again,
// I'm enabling them all again
		$this->Security->validatePost = true;
		$this->Security->csrfCheck = false;
//        $this->Security->csrfCheck = true;

        // instead of worrying about aro / acl permissions on a per controller basis, we're going to loosen up and define things
        // by whether this user is logged in or not
        if ($this->Session->check('Auth.User')) {
            $this->Auth->allow('index');
        }
	}

    public function index()
    {
        $transactions = $this->Transaction->find('all', array(
                'conditions' => array(
                    'user_id' => (int)$this->Auth->user('id'),
                )
            ));

        if(count($transactions) == 0) {
            // generate our token to be used in communicating with Braintree
            /** @var $braintreeComponent BraintreeComponent */
            $this->set('token', $this->Braintree->generateToken());
            $this->set('refill_needed', false);
        }

        $this->set('balance', $this->User->getBalance($this->Auth->user('id')));

        // if we're not setup to handle selling credits, then we don't want to offer it in our UI
        $this->set('enableCreditSales', false);
//        if(Configure::read('Paypal.appId') != '') {
//            $this->set('enableCreditSales', true);
//        }

        $this->set('email', $this->Auth->user('email')); // our user's email address so they can send money to Paypal
        $this->set('transactions', $transactions);
    }
}