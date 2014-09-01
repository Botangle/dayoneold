<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => array(
		'domain' => '',
		'secret' => '',
	),

	'mandrill' => array(
		'secret' => '',
	),

	'stripe' => array(
		'model'  => 'User',
		'secret' => '',
	),

    's3' => array(
        'accessKey' => 'AKIAJWF54OAT34LFKR3Q',
        'secretKey' => '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',
        'bucket' => 'botangleassets',
        'region' => Aws\Common\Enum\Region::US_EAST_1,
        'folder' => 'profilepic/',
    ),

);
