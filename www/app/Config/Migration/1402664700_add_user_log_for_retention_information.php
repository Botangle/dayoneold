<?php
class AddUserLogForRetentionInformation extends CakeMigration {

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
                'user_logs' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
                    'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id'),
                        'idx_user_log_user_id' => array('column' => 'user_id'),
                        // we're going to skip a Foreign key on this for now.  It's not the best way of handling this
                        // but this migration tool doesn't handle this well at all :-/
                    ),
                    'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
                ),
            ),
        ),
        'down' => array(
            'drop_table' => array(
                'user_log',
            ),
        ),
    );
}