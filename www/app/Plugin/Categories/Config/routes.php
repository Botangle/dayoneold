<?php

// Users
 
CroogoRouter::connect('/categories', array(
	'plugin' => 'categories', 'controller' => 'categories', 'action' => 'index'
));
CroogoRouter::connect('/subject/search', array(
	'plugin' => 'categories', 'controller' => 'subject', 'action' => 'search'
));
/*
CroogoRouter::connect('/add', array(
	'plugin' => 'categories', 'controller' => 'categories', 'action' => 'add'
)); 
*/

/**
 * API v1
 */
CroogoRouter::connect(
    '/api/v1/categories/index.xml',
    array(
        'plugin'        => 'categories',
        'controller'    => 'categories',
        'action'        => 'index',
        'ext'           => 'xml'
    ));
