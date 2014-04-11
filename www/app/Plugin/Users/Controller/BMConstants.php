<?php

/**
 * BMConstants
 *
 * I assume "BMConstants" stands for "Billing Management Constants." This file
 * contains a class that sets the necessary constants for access to the
 * Paypal API.
 *
 * @TODO This should be moved to a more logical location, but for now we shall
 * 			keep it here.
 *
 * @NOTE Stripe information is included in the UsersController.php and
 * 			`app/Config/core.php`
 */

/**
 * PaypalConstants
 *
 * Sets information for accessing the Paypal API.
 *
 * @package billing
 * @type class
 */
class PaypalConstants {

	/* PAYPAL CONSTANTS testing*/
	const API_USER_NAME              = 'soganideepak241979-facilitator_api1.gmail.com';
	const API_PASSWORD               = '1379612486';
	const API_SIGNATURE              = 'A1HD1b0ALDhlSFyWYsL2z2eAMaQ3AtC.v2.OKTfh-qgDefiUx4HV7sc3';
	const API_END_POINT              = 'https://api-3t.paypal.com/nvp';
	const API_END_POINT_SANDBOX      = 'https://api-3t.sandbox.paypal.com/nvp';
	const API_END_POINT_BETA_SANDBOX = 'https://api-3t.beta-sandbox.paypal.com/nvp';

	const API_VERSION = '51.0';
	const ENVIRONMENT = 'sandbox';

	const ENCRYPTION_CONSTANT = 525325.24;

	/* PAYPAL TESTING LIVE
	const API_USER_NAME = 'charlieclarke84_api1.hotmail.com';
	const API_PASSWORD = 'J9US9X4WLJ3QYVU7';
	const API_SIGNATURE ='AFcWxV21C7fd0v3bYYYRCpSSRl31AkOXenaL5rRK-0FUHmrPc6nnAZzk';
	const API_END_POINT 				= 'https://api-3t.paypal.com/nvp';
	const API_END_POINT_SANDBOX 		= 'https://api-3t.sandbox.paypal.com/nvp';
	const API_END_POINT_BETA_SANDBOX 	= 'https://api-3t.beta-sandbox.paypal.com/nvp';

	const API_VERSION = '51.0';
	const ENVIRONMENT = '';

	const ENCRYPTION_CONSTANT = 525325.24;
	*/
}

?>
