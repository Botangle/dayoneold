<?php
App::uses('PostLessonAddController', 'Users.Controller');
App::Import('ConnectionManager');

/*
 * TransactionsController Controller
 */

class TransactionsController extends PostLessonAddController {

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

        // we've got a Braintree payment listener event setup to handle payment transactions when they happen
        App::uses('BraintreePaymentListener', 'Event');
        $this->Transaction->getEventManager()->attach(new BraintreePaymentListener());
	}

	function beforeFilter() {
		$dataSource = ConnectionManager::getDataSource('default');
		$dsc = $dataSource->config;
		$this->databaseName = $dsc['database'];

		parent::beforeFilter();

		$this->Security->validatePost = true;
		$this->Security->csrfCheck = true;

        // instead of worrying about aro / acl permissions on a per controller basis, we're going to loosen up and define things
        // by whether this user is logged in or not
        if ($this->Session->check('Auth.User')) {
            $this->Auth->allow('create');
        }

        // Here, we disable the Security post validation for POST requests to our "create" action so they work
        if(isset($this->Security) &&  ($this->request->is('post')) && $this->action == 'create'){
            $this->Security->validatePost = false;
        }
	}

    public function create()
    {
        if ($this->request->is('post') && isset($_POST['payment_method_nonce'])) {

            if(isset($_POST['payment_method_nonce'])) {
                $this->request->data['Transaction']['nonce'] = $_POST['payment_method_nonce'];
            }

            // adjust our payment nonce if we're in debugging mode so we can
            // make purchases without really triggering a Braintree purchase
            if(Configure::read('debug') == 2) {
                $this->request->data['Transaction']['nonce'] = Braintree_Test_Nonces::$paypalOneTimePayment;
            }

            $userId = $this->Auth->user('id');

            $this->request->data['Transaction']['user_id'] = (int)$userId;
            $this->Transaction->set($this->request->data);

            if($this->Transaction->validates()) {

                $amount             = $this->request->data['Transaction']['amount'];
                $transactionType    = $this->request->data['Transaction']['type'];

                $successMsg = '';
                $errorMsg   = 'Something strange happened.  Please try again.';
                $status     = false;

                if($transactionType == 'buy') {
                    $status     = $this->Transaction->addBuy();
                    $amount     = (int)$amount;
                    $successMsg = __d('croogo', "You have purchased {$amount} Botangle credits successfully");
                    $errorMsg   = __d('croogo', "We had problems making that purchase.  Please try again.");
                }

                if($transactionType == 'sell') {
                    $status     = $this->Transaction->addSell();
                    $amount     = number_format($amount, 2);
                    $successMsg = __d('croogo', "You have sold {$amount} Botangle credits successfully");
                    $errorMsg   = __d('croogo', "We had problems making that sale.  Please try again.");
                }

                if($status) {

                    // Now let's see if we're in the middle of a lesson setup that got interrupted by the need for a credit refill
                    // if we are, we'll need to finish up our lesson setup
                    if ($this->Session->read('credit_refill_needed')) {
                        $user_id_to_message = $this->Session->read('new_lesson_user_id_to_message');
                        $lesson_id = $this->Session->read('new_lesson_lesson_id');

                        // clear up our session so we don't have anything else weird happen to this person later on
                        $this->Session->delete('credit_refill_needed');
                        $this->Session->delete('new_lesson_lesson_id');
                        $this->Session->delete('new_lesson_user_id_to_message');

                        // a redirect and session flash gets posted here
                        $this->postLessonAddSetup($lesson_id, $user_id_to_message);
                    } else {
                        $this->Session->setFlash(
                            $successMsg,
                            'default',
                            array(
                                'class' => 'success',
                            )
                        );

                        $this->redirect(
                            Router::url(
                                array(
                                    'plugin'        => 'users',
                                    'controller'    => 'credits',
                                    'action'        => 'index'
                                )
                            )
                        );
                    }
                } else {
                    $this->Session->setFlash(
                        $errorMsg,
                        'default',
                        array(
                            'class' => 'error',
                        )
                    );
                }
            }
        }

        if(isset($this->request->data['Transaction']['type']) && $this->request->data['Transaction']['type'] == 'sell') {
            $this->render('sell');
        }

        // generate our token to be used in communicating with Braintree
        /** @var $braintreeComponent BraintreeComponent */
        $this->set('token', $this->Braintree->generateToken());

        $this->set('refill_needed', false);
        if ($this->Session->read('credit_refill_needed')) {
            $this->set('refill_needed', true);
        }
    }
}