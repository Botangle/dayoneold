<?php
class AdjustUserTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Adjusts our user table to have better column names for Stripe information';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
        'up' => array(
            'rename_field' => array(
                'users' => array(
                    'stripe_id'     => 'stripe_user_id',
                    'secret_key'    => 'access_token',
                    'public_key'    => 'stripe_publishable_key',
                ),
            ),
            'create_field' => array(
                'users' => array(
                    'refresh_token' => array(
                        'type' => 'string',
                    ),
                ),
            ),
        ),
        'down' => array(
            'rename_field' => array(
                'users' => array(
                    'stripe_user_id'            => 'stripe_id',
                    'access_token'              => 'secret_key',
                    'stripe_publishable_key'    => 'public_key',
                ),
            ),
            'drop_field' => array(
                'users' => array(
                    'refresh_token',
                ),
            ),
        ),
    );
}
