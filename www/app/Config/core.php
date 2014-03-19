<?php

if (file_exists(APP . 'Config' . DS . 'croogo.php')) {
	require APP . 'Config' . DS . 'croogo.php';
} else {
	if (!defined('LOG_ERROR')) {
		define('LOG_ERROR', LOG_ERR);
	}

	Configure::write('Error', array(
		'handler' => 'ErrorHandler::handleError',
		'level' => E_ALL & ~E_DEPRECATED,
		'trace' => true
	));

	Configure::write('Exception', array(
		'handler' => 'ErrorHandler::handleException',
		'renderer' => 'ExceptionRenderer',
		'log' => true
	));

	Configure::write('Session', array(
		'defaults' => 'php',
		'ini' => array(
			'session.cookie_httponly' => 1
		)
	));
}

// adding in environment support for CakePHP
// http://bakery.cakephp.org/articles/stevena0/2010/08/29/use-different-configs-for-different-environments
$env = getenv('CAKE_ENV');
if (!$env){$env = 'production';}
$env = strtolower($env);
if(file_exists(__DIR__ . 'core-' . $env . '.php')) {
    Configure::load('core-'.$env);
}
