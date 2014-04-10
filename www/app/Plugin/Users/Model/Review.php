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
class Review extends UsersAppModel {

/**
 * Model name
 *
 * @var string
 * @access public
 */
	public $name = 'Review';
 
	public $belongsTo = array(
		'Lesson' => array(
				'className' => 'Lesson',
				'foreignKey' => 'lesson_id'
		)
	); 
	
	public function __construct($id = false, $table = null, $ds = null)
	{
		parent::__construct($id, $table, $ds);
	 
		$this->validate = array(
			 'rating' => array( 
	            'range' => array(
	                'rule'    => array('range', 0, 6),
	                'message' => __('Rating must be between 1 to 5'),
            		'required'   => true,
            		'allowEmpty' => false,
	            )
	        ), 
			'reviews' => array(
				'between' => array(
						'rule'    => array('between', 0, 1000),
						'message' => __('Review must be between 1 to 1000 characters'),
						'required'   => true,
						'allowEmpty' => false,
				)
			), 
			'rating_unique' => array
			(
				'unique' => array
				(
								'rule' => array('checkUnique', array('lesson_id' ,'rate_by')),
								'message' => __('One review per lesson allowed.'),
				)
			),
				
		);				
	}
	
	public function checkUnique($data, $fields)
	{
		// check if the param contains multiple columns or a single one
		if (!is_array($fields))
		{
			$fields = array($fields);
		}
	
		// go trough all columns and get their values from the parameters
		foreach($fields as $key)
		{
			$unique[$key] = $this->data[$this->name][$key];
		}
	
		// primary key value must be different from the posted value
		if (isset($this->data[$this->name][$this->primaryKey]))
		{
			$unique[$this->primaryKey] = "<>" . $this->data[$this->name][$this->primaryKey];
		}
	
		// use the model's isUnique function to check the unique rule
		return $this->isUnique($unique, false);
	}

}
