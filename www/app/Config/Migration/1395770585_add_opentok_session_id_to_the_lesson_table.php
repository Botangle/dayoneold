<?php
class AddOpentokSessionIdToTheLessonTable extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Add Opentok session id to the lesson table';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
    public $migration = array(
        'up' => array(
            'create_field' => array(
                'lessons' => array(
                    'opentok_session_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 255, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
                ),
            ),
        ),
        'down' => array(
            'drop_field' => array(
                'lessons' => array(
                    'opentok_session_id',
                ),
            ),
        ),
    );
}