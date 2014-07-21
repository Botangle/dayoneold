<?php

App::uses('AppModel', 'Model');

/**
 * UserCredit
 */
class UserCredit extends AppModel {

/**
 * Model name
 *
 * @var string
 * @access public
 */
	public $name = 'UserCredit';

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
     * Enables retrieving a scoped balance amount for this user
     *
     * @param $userId
     * @return int|mixed
     */
    function getBalance($userId){

        if(!isset($this->_balanceCache)) {
            $conditions = "user_id = ". (int)$userId;
            $count = $this->find('first', array('conditions' => $conditions));

            // @TODO: cache this amount so we don't have to query for it every time
            if(isset($count['UserCredit']['amount'])) {
                $this->_balanceCache = $count['UserCredit']['amount'];
            } else {
                $this->_balanceCache = 0;
            }
        }

        return $this->_balanceCache;
    }
}
