<?php
class AddUserStatusTable extends CakeMigration {

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
            'create_table' => array(
                'my_statuses' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'created_by_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'status_text' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 500, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
                    'status' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id'),
                    ),
                    'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
                ),
            ),
        ),
		'down' => array(
            'drop_table' => array(
                'my_statuses',
            ),
		),
	);
}
