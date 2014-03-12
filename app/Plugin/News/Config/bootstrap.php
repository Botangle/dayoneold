<?php

/**
 * Failed login attempts
 *
 * Default is 5 failed login attempts in every 5 minutes
 */
$cacheConfig = array_merge(
	Configure::read('Cache.defaultConfig'),
	array('groups' => array('News'))
); 
CroogoNav::add('News', array(
	'icon' => array('user', 'large'),
	'title' => __d('croogo', 'News'),
	'url' => array(
		'admin' => true,
		'plugin' => 'news',
		'controller' => 'news',
		'action' => 'index',
	),
	'weight' => 50,
	 
));
