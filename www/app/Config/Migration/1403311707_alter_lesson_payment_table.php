<?php
class AlterLessonPaymentTable extends CakeMigration {

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
    /**
     * Before migration callback
     *
     * @param string $direction, up or down direction of migration process
     * @return boolean Should process continue
     * @access public
     */
    public function after($direction) {
        $this->db->execute('ALTER TABLE lesson_payments CHANGE payment_amount payment_amount DECIMAL( 9, 2 ) NOT NULL');
        $this->db->execute('ALTER TABLE lesson_payments CHANGE fee fee DECIMAL( 9, 2 ) NOT NULL');
        return true;
    }
}