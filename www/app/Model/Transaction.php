<?php

App::uses('AppModel', 'Model');

/**
 * Transaction
 */
class Transaction extends AppModel {

/**
 * Model name
 *
 * @var string
 * @access public
 */
	public $name = 'Transaction';

/**
 * Order
 *
 * @var string
 * @access public
 */
	public $order = 'Transaction.created DESC';

/**
 * Model associations: belongsTo
 *
 * @var array
 * @access public
 */
	public $belongsTo = array('Users.User');

/**
 * Display fields for this model
 *
 * @var array
 */
	protected $_displayFields = array(
		'id',
		'User.username' => 'username',
		'type',
		'created'
	);

    /**
     * Validate our transaction in various manners depending on what we're doing
     * @var array
     */
    public $validate = array(
        'user_id' => array(
            'has_user' => array(
                'rule' => 'notEmpty',
                'message' => 'User id is required to create a transaction',
                'required' => true,
            ),
            'user_exists' => array(
                'rule' => array(
                    'relatedExists', array(
                        'plugin' => 'Users',
                        'model' => 'User',
                        'field' => 'id',
                    ),
                ),
                'message' => "Sorry, that user isn't found",
            ),
        ),
        'amount' => array(
            'if_a_buy' => array(
                'rule' => 'validateIntegerIfABuy',
                'message' => "Sorry, you are only allowed to buy credits in whole numbers and must specify at least one",
                'required' => true,
            ),
            'if_not_a_buy' => array(
                'rule' => 'validateDecimalIfNotABuy',
                'message' => "In here",
                'required' => false,
            ),
        ),
        // either buy, sell or transfer, required
        'type' => array(
            'rule' => array('inList', array('buy', 'sell', 'transfer')),
            'message' => "Sorry, your transaction was of the wrong type",
        ),

        // required if a buy or sell
        'transaction_key' => array(
            'allowEmpty' => false,
            'alphaNumeric',

        ),

        // required if a transaction
        'lesson_id' => array(
            'rule' => 'validateLessonIdIfATransaction',
        ),
        'created', // we want to have this auto-populated as a date
    );

    /**
     * If we're doing a buy, folks have to buy a complete credit (not a partial one)
     * Otherwise, this rule can pass
     *
     * @param $check
     * @return bool
     */
    public function validateIntegerIfABuy($check)
    {
        if($this->data['Transaction']['type'] == 'buy') {
            return Validation::naturalNumber($this->data['Transaction']['amount']);
        }
        return true;
    }

    /**
     * If we're not doing a buy, folks must pass the decimal validation test
     * Otherwise, this rule can pass
     *
     * @param $check
     * @return bool
     */
    public function validateDecimalIfNotABuy($check)
    {
        if($this->data['Transaction']['type'] != 'buy') {
            return Validation::decimal($this->data['Transaction']['amount']);
        }
        return true;
    }


    /**
     * If we're doing a buy, folks have to buy a complete credit (not a partial one)
     * Otherwise, this rule can pass
     *
     * @param $check
     * @return bool
     */
    public function validateLessonIdIfATransaction($check)
    {
        if($this->data['Transaction']['type'] == 'transaction') {
            return $this->validateExists('lesson_id', array('model' => 'Lesson', 'field' => 'id'));
        }
        return true;
    }


    /**
     * Used to return a live user total based on the transactions table totals
     *
     * @param $userId
     * @return int|decimal
     */
    function getUserTotals($userId){
        $conditions = "user_id  = ". (int)$userId;
        $total = $this->find('first', array(
                'conditions' => $conditions,
                'fields'=>'sum(amount) as amount'
            ));

        if($total[0]['amount'] > 0){
            return $total[0]['amount'];
        } else{
            return 0;
        }
    }

    public function addBuy()
    {
        // @TODO: add in pre-event notifications here
        return $this->addTransaction();
        // @TODO: add in post-event notifications here
    }

    public function addSell()
    {
        // change the sign on the transaction amount when we sell
        $this->data['Transaction']['amount'] = 0 - $this->data['Transaction']['amount'];
        
        // @TODO: add in pre-event notifications here
        return $this->addTransaction();
        // @TODO: add in post-event notifications here
    }

    private function addTransaction()
    {
        // run everything in a DB transaction to handle rolling changes back if we have payment or other issues
        $dataSource = ConnectionManager::getDataSource('default');
        $dataSource->begin();

        try {

            $data = $this->data;

            if(!$this->save()) {
                $dataSource->rollback();
                return false;
            }

            $data['Transaction']['id'] = $this->getLastInsertID();

            // now handle updating our user credit denormalized field info
            // would love to move this into a model instead of leaving it in the controller
            if(!$this->insertOrUpdateUserCredit($data['Transaction']['user_id'])) {
                $dataSource->rollback();
                return false;
            }

            // now commit our changes
            $dataSource->commit();

            return true;
        } catch(Exception $e) {
            // @TODO: log info about the error if it's related to payments

            // rollback our changes if we have issues
            $dataSource->rollback();
        }

        return false;
    }

    /**
     * @param $userId
     */
    protected function insertOrUpdateUserCredit($userId)
    {
        // find a user credit model if we have one in our system
        App::import("Model", "UserCredit");
        $userCreditModel = new UserCredit();
        $userCredit = $userCreditModel->findByUserId((int)$userId);

        // unset our User key as we don't want to adjust the User model in any way, shape or form (gosh ... :-/)
        unset($userCredit['User']);

        // we want to have a safety check between the user credit table and the transaction amount total
        // when we transfer money out, we'll make sure the user really *does* have that amount in their account
        // otherwise, they may be trying to hack the system

        // if we already have a record, let's update it
        if (count($userCredit) > 0) {
            $userCredit['UserCredit']['amount'] = $this->getUserTotals((int)$userId);
        } else {
            // otherwise, we'll build a new row
            $userCredit = array(
                'UserCredit' => array(
                    'user_id' => (int)$userId,
                    'amount' => $this->getUserTotals((int)$userId),
                )
            );
        }

        // now save our info
        if(!$userCreditModel->save($userCredit)) {
            $errors = $userCreditModel->validationErrors;
            return false;
        } else {
            return true;
        }
    }
}
