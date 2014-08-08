<?php
class AddCreditsAndPurchaseSystem extends CakeMigration {

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
                'transactions' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    // type = buy, sell or transfer
                    'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
                    'transaction_key' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
                    'lesson_id' => array('type' => 'integer', 'null' => true, 'default' => null),
                    'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id'),
                        'idx_user_log_user_id' => array('column' => 'user_id'),
                        // we're going to skip a Foreign key on this for now.  It's not the best way of handling this
                        // but this migration tool doesn't handle this well at all :-/
                    ),
                    'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
                ),
                'user_credits' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
                    'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id'),
                        'user_credits_user_id_unique' => array(
                            'column' => 'user_id',
                            'unique' => true,
                        ),
                        // we're going to skip a Foreign key on this for now.  It's not the best way of handling this
                        // but this migration tool doesn't handle this well at all :-/
                    ),
                    'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
                ),
            ),
        ),
        'down' => array(
            'drop_table' => array(
                'transactions',
                'user_credits',
            ),
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
        if($direction == 'up') {
            // @TODO: decimal, allow how many decimal places here? pre and post?
            // @TODO: not sure about how to handle decimals here
            // an amount is signed, either positive or negative
            $this->db->execute('ALTER TABLE transactions ADD COLUMN amount DECIMAL( 11, 4 ) NOT NULL AFTER user_id');
            $this->db->execute('ALTER TABLE user_credits ADD COLUMN amount DECIMAL( 11, 4 ) NOT NULL AFTER user_id');
        }
		return true;
	}
}
