<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php
 *
 * This is an application wide file to load any function that is not used within a class
 * define. You can also use this to include or require any files in your application.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * App::build(array(
 *     'plugins' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
 *     'models' =>  array('/full/path/to/models/', '/next/full/path/to/models/'),
 *     'views' => array('/full/path/to/views/', '/next/full/path/to/views/'),
 *     'controllers' => array('/full/path/to/controllers/', '/next/full/path/to/controllers/'),
 *     'datasources' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
 *     'behaviors' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
 *     'components' => array('/full/path/to/components/', '/next/full/path/to/components/'),
 *     'helpers' => array('/full/path/to/helpers/', '/next/full/path/to/helpers/'),
 *     'vendors' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
 *     'shells' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
 *     'locales' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
 * ));
 *
 */

/**
 * As of 1.3, additional rules for the inflector are added below
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));

/**
 * Plugin section where we load all our various plugins
 */

CakePlugin::load('Croogo', array('bootstrap' => true));
CakePlugin::load('Migrations');
CakePlugin::load('Uploader');
CakePlugin::load('CsvView');

/**
 * Stripe Configuration
 *
 * Sets the secret keys and other environment conditions for use with the CakePHP-
 * Stripe plugin and our own custom setup
 *
 * For documentation, go to https://github.com/chronon/CakePHP-StripeComponent-Plugin
 */
CakePlugin::load('Stripe');

// we store secrets here for the CakePHP StripeComponent, but we handle secrets another way down below as well
// we may not end up using this component ...
Configure::write('Stripe.TestSecret', $stripeTestSecret);
Configure::write('Stripe.LiveSecret', $stripeLiveSecret);

if($env == "production") {
    Configure::write('Stripe.mode', 'Live');
    Configure::write('Stripe.client_id', $stripeLiveClientId);
    Configure::write('Stripe.publishable_key', $stripeLivePublishableKey);
    Configure::write('Stripe.secret', $stripeLiveSecret); // we put this in this way too so by default we can look here

    Configure::write('Braintree.environment', 'live');
    Configure::write('Braintree.merchantId', $braintreeLiveMerchantId);
    Configure::write('Braintree.publicKey', $braintreeLivePublishableKey);
    Configure::write('Braintree.privateKey', $braintreeLivePrivateKey); // we put this in this way too so by default we can look here
} else {
    Configure::write('Stripe.mode', 'Test');
    Configure::write('Stripe.client_id', $stripeTestClientId);
    Configure::write('Stripe.publishable_key', $stripeTestPublishableKey);
    Configure::write('Stripe.secret', $stripeTestSecret); // we put this in this way too so by default we can look here

    Configure::write('Braintree.environment', 'sandbox');
    Configure::write('Braintree.merchantId', $braintreeTestMerchantId);
    Configure::write('Braintree.publicKey', $braintreeTestPublishableKey);
    Configure::write('Braintree.privateKey', $braintreeTestPrivateKey); // we put this in this way too so by default we can look here
}

/* We best do some logging */
CakeLog::config('stripe', array(
    'engine' => 'FileLog',
    'types' => array('info', 'error'),
    'scopes' => array('stripe'),
    'file' => 'stripe',
));

// we've got a user listener event setup to handle user retention logging
// article here: http://martinbean.co.uk/blog/2013/11/22/getting-to-grips-with-cakephps-events-system/
App::uses('UserListener', 'Event');