<?php

App::uses('TestimonialAppModel', 'Testimonials.Model');
App::uses('AuthComponent', 'Controller/Component');

/**
 * User
 *
 * @category Model
 * @package  Croogo.Testimonials.Model
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class Testimonial extends TestimonialAppModel {

/**
 * Model name
 *
 * @var string
 * @access public
 */
	public $name = 'Testimonial';

/**
 * Order
 *
 * @var string
 * @access public
 */
	public $order = 'Testimonial.date ASC';
 

/**
 * Model associations: belongsTo
 *
 * @var array
 * @access public
 */
 
/**
 * Validation
 *
 * @var array
 * @access public
 */
	public $validate = array(
		 
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'last' => true,
			),
			 
		),
		 
	);

 
/**
 * Display fields for this model
 *
 * @var array
 */
	protected $_displayFields = array(
		'id', 
		'title',
		'details',
		'date', 
		'status' => array('type' => 'boolean'),
		 
	);
	
 

}
