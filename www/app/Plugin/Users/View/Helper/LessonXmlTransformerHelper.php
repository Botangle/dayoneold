<?php
App::uses('AppHelper', 'View/Helper');
App::uses("Model", "Users.Lesson");

class LessonXmlTransformerHelper extends AppHelper {

    /**
     * Pass in a user array and we'll spit out only what we want our XML system to get
     *
     * @param $user
     * @return array
     */
    public function transformLessons($lessons)
    {
        $transformedLessons = array();
        foreach($lessons as $lesson) {

            $user       = $lesson['User'];
            $other      = $lesson['Other'];
            $lesson     = $lesson['Lesson'];

            if($lesson['tutor'] == $user['id']) {
                $student    = $user;
                $tutor      = $other;
            } else {
                $student    = $other;
                $tutor      = $user;
            }

            $transformedStudent = array(
                'id'                            => $student['id'],
                'username'                      => $student['username'],
                'firstname'                     => $student['name'],
                'lastname'                      => $student['lname'],
                'profilepic'                    => $this->transformProfilePic($student['profilepic']),
                'timezone'                      => 'UTC', // @TODO: update this to pull the live info back
                'is_online'                     => $student['is_online'],
            );

            $transformedTutor = array(
                'id'                            => $tutor['id'],
                'username'                      => $tutor['username'],
                'firstname'                     => $tutor['name'],
                'lastname'                      => $tutor['lname'],
                'profilepic'                    => $this->transformProfilePic($tutor['profilepic']),
                'timezone'                      => 'UTC', // @TODO: update this to pull the live info back
                'is_online'                     => $tutor['is_online'],
                'is_featured'                   => $tutor['is_featured'],
            );

            $transformedLesson = array(
                'id'                            => $lesson['id'],
                'created'                       => $lesson['created'],
                'tutor'                         => $transformedTutor,
                'student'                       => $transformedStudent,
                'lesson_date'                   => $lesson['lesson_date'],
                'lesson_time'                   => $lesson['lesson_time'],
                'duration'                      => $this->transformDuration($lesson['duration']),
                'subject'                       => $lesson['subject'],

                // if we shift this to an ID, then we need to send back strings through our API
                'repetition'                    => $lesson['repet'],
                'notes'                         => $lesson['notes'],
                'is_confirmed'                  => $lesson['is_confirmed'],
            );

            $transformedLessons[] = $transformedLesson;
        }

        return $transformedLessons;
    }

    public function transformCreatedLesson($lesson)
    {
        return array(
            'id'                            => $lesson['id'],
            'tutor_id'                      => $lesson['tutor'],
            'student_id'                    => $lesson['student'],
            'lesson_date'                   => $lesson['lesson_date'],
            'lesson_time'                   => $lesson['lesson_time'],
            'duration'                      => $this->transformDuration($lesson['duration']),
            'subject'                       => $lesson['subject'],

            // if we shift this to an ID, then we need to send back strings through our API
            'repetition'                    => $lesson['repet'],
            'notes'                         => $lesson['notes'],
            'is_confirmed'                  => $lesson['is_confirmed'],
        );
    }

    public function transformProfilePic($profilePic)
    {
        if($profilePic == '') {
            return 'https://www.botangle.com/images/botangle-default-pic.jpg';
        } elseif(strpos($profilePic, '/') === 0) {
            return 'https://www.botangle.com' . $profilePic;
        } else {
            return $profilePic;
        }
    }

    /**
     * Transforms from a string (.5, 1.0, 1.5, etc) to a minute representation of our lesson
     * as specified by our API
     *
     * @param $durationField
     * @return mixed
     */
    private function transformDuration($durationField)
    {
        return $durationField * 60;
    }
}