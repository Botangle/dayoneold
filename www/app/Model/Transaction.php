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
     * A cached user total amount so we don't repeatedly query for a total in the same response
     * @var decimal
     */
    private $_cachedUserTotalAmount;

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
            'check_that_their_sell_amount_isnt_at_or_under_zero' => array(
                'rule' => 'validateThatUserDoesntHaveANegativeBalance',
                'message' => "Sorry, you can't sell credits when your balance is at or below zero. :-)",
                'required'  => true,
                'last'      => true,
            ),
            'make_sure_they_have_that_amount_to_sell' => array(
                'rule' => 'validateUserHasThatAmountToSell',
                'message' => "Sorry, our records on your credit balance seem to have been mixed up.  Please contact support for assistance.",
                'required' => true,
            ),
            'if_selling_too_much' => array(
                'rule' => 'validateMaxOneHundredCreditsSoldPer24Hrs',
                'message' => "Sorry, you can only sell 100 credits per 24 hrs",
                'required' => true,
            ),
        ),
        // either buy, sell or transfer, required
        'type' => array(
            'rule' => array('inList', array('buy', 'sell', 'transfer')),
            'message' => "Sorry, your transaction was of the wrong type",
        ),

        // needed across the board, but we don't enforce this, as models will get validated or saved prior to having a transaction_key
        // (especially when doing transfers)
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
     * Make sure this person isn't trying to sell when they already have a negative balance
     *
     * @param $check
     * @return bool
     */
    public function validateThatUserDoesntHaveANegativeBalance($check)
    {
        if($this->data['Transaction']['type'] == 'sell') {
            $user_id = $this->data['Transaction']['user_id'];
            $transactionTotal = $this->getUserTotals((int)$user_id);

            // if our transaction total is beneath zero already, then they aren't allowed to sell anything more (obviously :-)
            if($transactionTotal <= 0) {
                return false;
            }
        }
        return true;
    }

    /**
     * Make sure that our user's credit denormalized value matches what our transaction total says we have
     * If it doesn't, then something fishy is going on and it's best to just error out
     *
     * @param $check
     */
    public function validateUserHasThatAmountToSell($check)
    {
        if($this->data['Transaction']['type'] == 'sell') {

            $user_id = $this->data['Transaction']['user_id'];

            $transactionTotal = $this->getUserTotals((int)$user_id);

            App::import("Model", "UserCredit");
            $userCredit = new UserCredit();
            $userTotal = $userCredit->getBalance((int)$user_id);

            if($userTotal != $transactionTotal) {
                // @TODO: log an error about this, probably worth having someone look into ...

                return false;
            }
        }
        return true;
    }

    /**
     * If we're doing a sell, we can only sell 100 credits in a twenty-four hour period.  Otherwise, error out
     *
     * @param $check
     * @return bool
     */
    public function validateMaxOneHundredCreditsSoldPer24Hrs($check)
    {
        if($this->data['Transaction']['type'] == 'sell') {

            $amount = $this->data['Transaction']['amount'];

            // if this specific transaction amount is greater than 100, we can error out immediately
            if($amount > 100) {
                return false;
            }

            // otherwise, let's sum all 'sell' transactions in the last 24 hrs and prevent a transfer out if
            // it combined with our current transaction amount is greater than 100
            $user_id = $this->data['Transaction']['user_id'];
            $amountSold = $this->find('first', array(
                    'fields'        => 'sum(amount) as amount',
                    'conditions'    => "Transaction.created > DATE_SUB(NOW(), INTERVAL 24 HOUR)
                                            AND type = 'sell'
                                            AND user_id = " . (int)$user_id,
                ));

            // get the absolute value of our previously sold amount, as it's a negative in the DB
            $amountPreviouslySold = abs($amountSold[0]['amount']);

            if(($amount + $amountPreviouslySold) > 100) {
                return false;
            }
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
    function getUserTotals($userId, $useCached = true) {

        if(!$useCached || $useCached && !$this->_cachedUserTotalAmount) {
            $conditions = "user_id  = ". (int)$userId;
            $total = $this->find('first', array(
                    'conditions' => $conditions,
                    'fields'=>'sum(amount) as amount'
                ));

            if(isset($total[0]['amount'])){
                $this->_cachedUserTotalAmount = $total[0]['amount'];
            } else{
                $this->_cachedUserTotalAmount = 0;
            }
        }

        return $this->_cachedUserTotalAmount;
    }

    public function addBuy()
    {
        // enforce that this is a buy
        $this->data['Transaction']['type'] = 'buy';

        // @TODO: add in pre-event notifications here
        return $this->addTransaction();
        // @TODO: add in post-event notifications here
    }

    public function addSell()
    {
        // change the sign on the transaction amount when we sell
        $this->data['Transaction']['amount'] = 0 - $this->data['Transaction']['amount'];

        // enforce that this is a sell
        $this->data['Transaction']['type'] = 'sell';

        // @TODO: add in pre-event notifications here
        return $this->addTransaction();
        // @TODO: add in post-event notifications here
    }

    public function addTransfer()
    {
        // enforce that this is a transfer
        $this->data['Transaction']['type'] = 'transfer';

        // @TODO: add in pre-event notifications here
        return $this->addTransaction();
        // @TODO: add in post-event notifications here
    }


    private function addTransaction()
    {
        // run everything in a DB transaction to handle rolling changes back if we have payment or other issues
        $dataSource = $this->getDataSource();
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
            $userCredit['UserCredit']['amount'] = $this->getUserTotals((int)$userId, false);
        } else {
            // otherwise, we'll build a new row
            $userCredit = array(
                'UserCredit' => array(
                    'user_id' => (int)$userId,
                    'amount' => $this->getUserTotals((int)$userId, false),
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

    /**
     * Creates two transactions to move money from one user's credit balance to the other
     *
     * @access protected
     * @param  $lessonId  : The lesson that these transactions are for
     * @param  $expertId  : The specific Expert id we should be sending money to
     * @param  $studentId   : The specific Student id we should be charging
     * @param  $amount   : Payment amount
     * @param  $fee      : amount we take as our cut
     */
    public function charge($lessonId, $expertId, $studentId, $amount, $fee) {

        $dataSource = $this->getDataSource();
        $dataSource->begin();

        try {
            // charge the student the full amount
            $studentTransaction = new Transaction;
            $studentTransaction->create(array(
                    'Transaction' => array(
                        'user_id'   => (int)$studentId,
                        'amount'    => (0 - $amount), // make this amount negative, as we're taking it away from the student
                        'lesson_id' => (int)$lessonId,
                    ),
                ));
            if(!$studentTransaction->addTransfer()) {
                // @TODO: handle the errors here
            }
            $chargeId = $studentTransaction->getLastInsertID();

            $studentTransaction->id = $chargeId;
            $studentTransaction->saveField('transaction_key', $chargeId);

            $expertTransaction = new Transaction;
            $expertTransaction->create(array(
                    'Transaction' => array(
                        'user_id'           => (int)$expertId,
                        'amount'            => ($amount - $fee), // make this amount negative, as we're taking it away from the student
                        'lesson_id'         => (int)$lessonId,
                        'transaction_key'   => (int)$chargeId,
                    ),
                ));
            if(!$expertTransaction->addTransfer()) {
                // @TODO: handle the errors here
            }

            $dataSource->commit();

            // return an array with an id for our transaction if things work
            return array('id' => (int)$chargeId);
        }
        catch (Exception $e) {
            // @TODO: do we want to log this in some way?
        }

        $dataSource->rollback();
        return false;
    }

    /**
     *
     * @param array $options Options passed from Model::save().
     * @return boolean True if the operation should continue, false if it should abort
     * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforesave
     * @see Model::save()
     */
    public function beforeSave($options = array())
    {
        if($this->data['Transaction']['type'] == 'buy' || $this->data['Transaction']['type'] == 'sell') {
            if($this->data['Transaction']['type'] == 'buy') {
                $event = new CakeEvent('Transaction.handle_purchase', $this, array(
                    'amount' => $this->data['Transaction']['amount'],
                    'nonce' => $this->data['Transaction']['nonce'],
                    'customer' => $this->data['User'],
                ));
            } else {
                $event = new CakeEvent('Transaction.handle_sale', $this, array('nonce' => $this->data['Transaction']['nonce']));
            }
            $this->getEventManager()->dispatch($event);
            if($event->isStopped()) {
                return false;
            } else {
                // make sure our user info isn't somehow saved to the DB.  We only use it to allow events to know info
                unset($this->data['User']);
            }

        }

        return parent::beforeSave($options);
    }
}
