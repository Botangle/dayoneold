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
	| Keys, tokens, secrets, etc. should not be committed to the repo but should be
	| held in an appropriate location where they're accessible only to those who
	| need to know them.
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
        'accessKey'         => '', // Set according to environment e.g. in local/services.php (not in repo)
        'secretKey'         => '', // Set according to environment e.g. in local/services.php (not in repo)
        'bucket'            => 'botangleassets',
        'region'            => Aws\Common\Enum\Region::US_EAST_1,
        'url'               => "https://s3.amazonaws.com",
        'profilepicFolder'  => 'profilepic',
    ),

);
