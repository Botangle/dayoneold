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
}
