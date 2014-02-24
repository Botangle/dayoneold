<?php

/**
 * Failed login attempts
 *
 * Default is 5 failed login attempts in every 5 minutes
 */
$cacheConfig = array_merge(
	Configure::read('Cache.defaultConfig'),
	array('groups' => array('categories'))
); 
CroogoNav::add('categories', array(
	'icon' => array('user', 'large'),
	'title' => __d('croogo', 'Categories'),
	'url' => array(
		'admin' => true,
		'plugin' => 'categories',
		'controller' => 'categories',
		'action' => 'index',
	),
	'weight' => 50,
	'children' => array(
		'categories' => array(
			'title' => __d('croogo', 'Categories'),
			'url' => array(
				'admin' => true,
				'plugin' => 'categories',
				'controller' => 'categories',
				'action' => 'index',
			),
			'weight' => 10,
		),
		'subject' => array(
			'title' => __d('croogo', 'Subject'),
			'url' => array(
				'admin' => true,
				'plugin' => 'categories',
				'controller' => 'subject',
				'action' => 'index',
			),
			'weight' => 10,
		),
		),
	 
));
