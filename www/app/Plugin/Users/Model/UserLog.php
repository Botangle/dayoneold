<?php

App::uses('UsersAppModel', 'Users.Model');

/**
 * User
 *
 * @category Model
 * @package  Croogo.Users.Model
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class UserLog extends UsersAppModel {

/**
 * Model name
 *
 * @var string
 * @access public
 */
	public $name = 'UserLog';

/**
 * Order
 *
 * @var string
 * @access public
 */
	public $order = 'UserLog.created DESC';

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
