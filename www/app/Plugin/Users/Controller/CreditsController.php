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

                // run everything in a DB transaction to handle rolling changes back if we have payment or other issues
                $dataSource = ConnectionManager::getDataSource('default');
                $dataSource->begin();
                try {

                    $this->Transaction->save();

                    // find a user credit model if we have one in our system
                    $userCredit = $this->UserCredit->findByUserId((int)$userId);

                    // we want to have a safety check between the user credit table and the transaction amount total
                    // when we transfer money out, we'll make sure the user really *does* have that amount in their account
                    // otherwise, they may be trying to hack the system

                    // if we already have a record, let's update it
                    if(count($userCredit) > 0) {
                        $userCredit['UserCredit']['amount'] = $this->Transaction->getUserTotals((int)$userId);
                    } else {
                        // otherwise, we'll build a new row
                        $userCredit = array(
                            'UserCredit' => array(
                                'user_id'   => (int)$userId,
                                'amount'    => $this->Transaction->getUserTotals((int)$userId),
                            )
                        );
                    }

                    // now save our info
                    $this->UserCredit->save($userCredit);

                    // now commit our changes
                    $dataSource->commit();

                    // set our amount to an int (it should be on already) prior to sending it back to the user
                    // makes things more secure this way
                    $amount = (int)$this->request->data['Transaction']['amount'];
                    $this->Session->setFlash(
                        __d('croogo', "You have purchased {$amount} Botangle credits successfully"),
                        'default',
                        array(
                            'class' => 'success',
                        )
                    );
                } catch(Exception $e) {
                    $this->Session->setFlash(
                        __d('croogo', "We had problems making that purchase.  Please try again."),
                        'default',
                        array(
                            'class' => 'error',
                        )
                    );

                    // @TODO: log info about the error if it's related to payments

                    // rollback our changes if we have issues
                    $dataSource->rollback();
                }

                $this->redirect(array('action' => 'create'));
            }
        }
    }

    public function index()
    {
        $transactions = $this->Transaction->find('all', array(
                'conditions' => array(
                    'user_id' => (int)$this->Auth->user('id'),
                )
            ));

        $this->set('balance', $this->User->getBalance($this->Auth->user('id')));
        $this->set('transactions', $transactions);
    }
}
