<?php
App::uses('AppHelper', 'View/Helper');

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
                'tutor_id'                      => $lesson['tutor'],    // @TODO: change this in the DB to tutor_id
                'student'                       => $transformedStudent,
                'lesson_date'                   => $lesson['lesson_date'],
                'lesson_time'                   => $lesson['lesson_time'],
                'duration'                      => $lesson['duration'],
                'subject'                       => $lesson['subject'],
                'repetition'                    => '',                  // @TODO: work this into our API eventually
                'notes'                         => $lesson['notes'],
                'is_confirmed'                  => $lesson['is_confirmed'],
            );

            $transformedLessons[] = $transformedLesson;
        }

        return $transformedLessons;
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
}