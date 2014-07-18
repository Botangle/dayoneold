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
}
