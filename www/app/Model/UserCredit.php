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
}
