<?php
class ChangeIdOfStudentRole extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Adjusts our student role id from 3 to 4 to match the code';

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
    public function before($direction) {

        $Role = ClassRegistry::init('Role');
        if ($direction === 'up') {

            $studentRole = $Role->find('first',array('conditions'=>array('alias'=>'student')));
            $studentRole['Role']['id'] = 4;

            // delete the old role so we can save in the new role
            $Role->delete(3);

            if ($Role->save($studentRole)) {
                $this->callback->out('student id has been upgraded');
            }
        } elseif ($direction === 'down') {

            $studentRole = $Role->find('first',array('conditions'=>array('alias'=>'student')));
            $studentRole['Role']['id'] = 3;

            // delete the new role so we can re-setup the old role
            $Role->delete(4);

            if ($Role->save($studentRole)) {
                $this->callback->out('student role id has been downgraded');
            }

        }
        return true;
    }
}
