<?php

/**
 * Failed login attempts
 *
 * Default is 5 failed login attempts in every 5 minutes
 */
$cacheConfig = array_merge(
	Configure::read('Cache.defaultConfig'),
	array('groups' => array('Testimonials'))
); 
CroogoNav::add('Testimonials', array(
	'icon' => array('user', 'large'),
	'title' => __d('croogo', 'Testimonials'),
	'url' => array(
		'admin' => true,
		'plugin' => 'testimonials',
		'controller' => 'testimonials',
		'action' => 'index',
	),
	'weight' => 50,
	 
));
