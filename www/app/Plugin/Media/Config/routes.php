<?php

// Users
 CroogoRouter::connect('/media/detail/:title/:id', array(
	'plugin' => 'media', 'controller' => 'media', 'action' => 'detail'
),array('pass'=>array('title','id')));
CroogoRouter::connect('/media', array(
	'plugin' => 'media', 'controller' => 'media', 'action' => 'index'
));