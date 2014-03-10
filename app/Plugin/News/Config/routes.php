<?php

// Users
CroogoRouter::connect('/news/detail/:title/:id', array(
    'plugin' => 'news', 'controller' => 'news', 'action' => 'detail'
), array('pass' => array('title', 'id')));
CroogoRouter::connect('/news', array(
    'plugin' => 'news', 'controller' => 'news', 'action' => 'index'
));
CroogoRouter::connect('/subject/search', array(
    'plugin' => 'categories', 'controller' => 'subject', 'action' => 'search'
));
/*
CroogoRouter::connect('/add', array(
	'plugin' => 'categories', 'controller' => 'categories', 'action' => 'add'
)); 
*/