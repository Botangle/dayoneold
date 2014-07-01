<?php

class AddSocialLinksFields extends CakeMigration {

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
				'users' => array(
					'link_fb' => array(
						'type' => 'string',
						'length ' => 255,
						'null' => true,
					),
					'link_twitter' => array(
						'type' => 'string',
						'length ' => 255,
						'null' => true,
					),
					'link_googleplus' => array(
						'type' => 'string',
						'length ' => 255,
						'null' => true,
					),
					'link_thumblr' => array(
						'type' => 'string',
						'length ' => 255,
						'null' => true,
					),
				)
			)
		),
		'down' => array(
			'drop_field' => array(
				'users' => array(
					'link_fb',
					'link_twitter',
					'link_googleplus',
					'link_thumblr',
				),
			)
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}

}
