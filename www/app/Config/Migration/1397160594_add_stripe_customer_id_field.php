<?php
class AddStripeCustomerIdField extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Adds a Stripe customer id field so we can charge this person for lessons';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
        'up' => array(
            'create_field' => array(
                'users' => array(
                    'stripe_customer_id' => array(
                        'type' => 'string',
                    ),
                ),
            ),
        ),
        'down' => array(
            'drop_field' => array(
                'users' => array(
                    'stripe_customer_id',
                ),
            ),
		),
	);
}