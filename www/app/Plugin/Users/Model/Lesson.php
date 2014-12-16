<?php

App::uses('UsersAppModel', 'Users.Model'); 

/**
 * User
 *
 * @category Model
 * @package  Croogo.Users.Model
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class Lesson extends UsersAppModel {

/**
 * Model name
 *
 * @var string
 * @access public
 */
	public $name = 'Lesson';

    /**
     * Whether the student user already has a stripe account or not
     * @var bool
     */
    public $need_stripe_account_setup = false;

    static public $repetitionValues = array(
        0 => 'Single lesson',
        1 => 'Daily',
        2 => 'Weekly',
    );

    /**
 * Validation
 *
 * @var array
 * @access public
 */
	public $validate = array(
		'tutor' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'last' => true,
			),
		),
		'lesson_date' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'last' => true,
			),
		),
		'lesson_time' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'last' => true,
			),
		),
		'duration' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'last' => true,
			),
		),
		'subject' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'This field cannot be left blank.',
				'last' => true,
			)
		),
// @TODO: this was breaking
//		'repet' => array(
//			'notEmpty' => array(
//				'rule' => 'notEmpty',
//				'message' => 'This field cannot be left blank.',
//				'last' => true,
//			),
//		),
	);

    public function activeLessons($userId, $roleId)
    {
        // we want to leave lessons off if a student isn't setup to pay
        $extraConditions = $this->lessonsExtraConditions();

        $activeLessonSQL = $this->basicLessonSQL((int)$userId, (int)$roleId, $extraConditions);
        $activeLessonSQL .= " AND Lesson.is_confirmed = 0 AND Lesson.lesson_date >= '" . date('Y-m-d') . "'";

        return $this->query($activeLessonSQL);
    }

    public function pastLessons($userId, $roleId)
    {
        $pastLessonSQL = $this->basicLessonSQL((int)$userId, (int)$roleId);
        $pastLessonSQL .= " AND Lesson.lesson_date < '" . date('Y-m-d') . "'";

        return $this->query($pastLessonSQL);
    }

    public function upcomingLessons($userId, $roleId)
    {
        // we want to leave lessons off if a student isn't setup to pay
        $extraConditions = $this->lessonsExtraConditions();

        $upcomingLessonSQL = $this->basicLessonSQL((int)$userId, (int)$roleId, $extraConditions);
        $upcomingLessonSQL .= " AND Lesson.is_confirmed = 1 AND Lesson.lesson_date >= '" . date('Y-m-d') . "'";

        return $this->query($upcomingLessonSQL);
    }

    private function basicLessonSQL($userId, $roleId, $extraConditions = '')
    {
        $userConditionsField        = "tutor";
        $otherConditionsField       = "student";
        $userLessonConditionsField  = "tutor";

        if ($roleId == 4) {
            $userConditionsField = "student";
            $otherConditionsField = "tutor";
            $userLessonConditionsField = "student";
            $extraConditions = '';
        }

        return "Select * from lessons as Lesson
            {$extraConditions}
            INNER JOIN users AS User
                ON (User.id = Lesson.{$userConditionsField})
                JOIN (
                    SELECT MAX(id) as ids
                        FROM lessons
                        GROUP BY parent_id
                ) as newest
                ON Lesson.id = newest.ids
                INNER JOIN users AS Other
                    ON (Other.id = Lesson.{$otherConditionsField})
            WHERE Lesson.{$userLessonConditionsField} = '{$userId}'";
    }

    /**
     * We want to leave lessons off if a student isn't setup to pay
     *
     * @return string
     */
    private function lessonsExtraConditions()
    {
        return 'INNER JOIN users as student ON (student.id = Lesson.student AND student.stripe_customer_id IS NOT NULL)';
    }
}