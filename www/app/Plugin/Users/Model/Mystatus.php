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
class MyStatus extends UsersAppModel {

/**
 * Model name
 *
 * @var string
 * @access public
 */
	public $name = 'MyStatus';
 
 

/**
 * Validation
 *
 * @var array
 * @access public
 */
	public $validate = array(
		'status_text' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank.',
			),
		)
		
	);
    

}
