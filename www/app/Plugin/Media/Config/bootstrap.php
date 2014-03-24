<?php

/**
 * Failed login attempts
 *
 * Default is 5 failed login attempts in every 5 minutes
 */
$cacheConfig = array_merge(
	Configure::read('Cache.defaultConfig'),
	array('groups' => array('Media'))
); 
CroogoNav::add('Media', array(
	'icon' => array('user', 'large'),
	'title' => __d('croogo', 'Media'),
	'url' => array(
		'admin' => true,
		'plugin' => 'media',
		'controller' => 'media',
		'action' => 'index',
	),
	'weight' => 50,
	 
));
