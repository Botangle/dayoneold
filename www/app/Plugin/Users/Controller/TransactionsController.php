<?php
App::uses('UsersAppController', 'Users.Controller');
App::Import('ConnectionManager');

/*
 * TransactionsController Controller
 */

class TransactionsController extends UsersAppController {

/**
 * Components
 *
 * @var array
 * @access public
 */
	public $components = array(
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
            $this->Auth->allow('create');
            $this->Auth->allow('test');
        }
	}

    public function create()
    {
        if ($this->request->is('post')) {

            $userId = $this->Auth->user('id');

            $this->request->data['Transaction']['user_id'] = (int)$userId;
            $this->Transaction->set($this->request->data);

            if($this->Transaction->validates()) {

                $amount             = (int)$this->request->data['Transaction']['amount'];
                $transactionType    = $this->request->data['Transaction']['type'];

                $successMsg = '';
                $errorMsg   = 'Something strange happened.  Please try again.';
                $status     = false;

                if($transactionType == 'buy') {
                    $status     = $this->Transaction->addBuy();
                    $successMsg = __d('croogo', "You have purchased {$amount} Botangle credits successfully");
                    $errorMsg   = __d('croogo', "We had problems making that purchase.  Please try again.");
                }

                if($transactionType == '') {
                    $status     = $this->Transaction->addSell();
                    $successMsg = __d('croogo', "You have sold {$amount} Botangle credits successfully");
                    $errorMsg   = __d('croogo', "We had problems making that sale.  Please try again.");
                }

                if($status) {
                    $this->Session->setFlash(
                        $successMsg,
                        'default',
                        array(
                            'class' => 'success',
                        )
                    );
                } else {
                    $this->Session->setFlash(
                        $errorMsg,
                        'default',
                        array(
                            'class' => 'error',
                        )
                    );
                }

                $this->redirect(array('action' => 'create'));
            }
        }
    }


}