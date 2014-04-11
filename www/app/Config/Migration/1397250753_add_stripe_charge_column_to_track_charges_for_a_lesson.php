<?php
class AddStripeChargeColumnToTrackChargesForALesson extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
        'up' => array(
            'create_field' => array(
                'lesson_payments' => array(
                    'fee' => array(
                        'type' => 'integer',
                    ),
                    'stripe_charge_id' => array(
                        'type' => 'string',
                    ),
                ),
            ),
        ),
        'down' => array(
            'drop_field' => array(
                'users' => array(
                    'fee',
                    'stripe_charge_id',
                ),
            ),
        ),
	);
}