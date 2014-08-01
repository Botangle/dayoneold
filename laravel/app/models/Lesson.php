<?php

class Lesson extends Eloquent {

    const ROLE_STUDENT = 'student';
    const ROLE_TUTOR = 'tutor';
    const ROLE_STUDENT_AND_TUTOR = 'mixed';

    const EVENT_COLOR_STUDENT = '#FED2A1';
    const EVENT_COLOR_TUTOR = '#CF6F07';
    const EVENT_COLOR_MIXED = '#F38918';

    public function studentUser()
    {
        return $this->belongsTo('User', 'student');
    }

    public function tutorUser()
    {
        return $this->belongsTo('User', 'tutor');
    }

    public function payments()
    {
        return $this->hasMany('LessonPayment');
    }

    public function scopeActive($query)
    {
        $query->where('active', 1);
    }

    /**
     * Returns the calendar event title corresponding to the current lesson
     * If the current logged in user is either the student or tutor, the title provides details.
     *
     * @param $loggedInUserId
     * @param $viewingUserId
     * @return string
     */
    public function getCalendarEventTitle($loggedInUserId, $viewingUserId)
    {
        // Since subject and username are user entered, HTML encode them since they'll be directly output
        //   to a 3rd party js plugin that may not encode the text
        $lessonTime = DateTime::createFromFormat('H:i:s', $this->lesson_time)->format('g:i A');

        if ($this->studentUser->id == $loggedInUserId){
            $title = e($this->subject);
            if ($loggedInUserId == $viewingUserId){
                $title .= "\n   ". Lang::get('Tutor: '). e($this->tutorUser->username);
            } else {
                $title .= "\n   ". Lang::get('Student: '). e($this->studentUser->username);
            }
            $title .= "\n   ". trans('When: '). $lessonTime;
            return $title;

        } elseif ($this->tutorUser->id == $loggedInUserId){
            $title = e($this->subject);
            if ($loggedInUserId == $viewingUserId){
                $title .= "\n   ". Lang::get('Student: '). e($this->studentUser->username);
            } else {
                $title .= "\n   ". Lang::get('Tutor: '). e($this->tutorUser->username);
            }
            $title .= "\n   ". trans('When: '). $lessonTime;
            return $title;

        } else {
            return "Lesson at {$lessonTime}";
        }
    }

    /**
     * Works out the day type (used to determine the appropriate color to display on calendar)
     * given the current lesson and the previous lessons on the same day
     * @param $viewingUserId
     * @param $loggedInUserId
     * @param null $currentDayType
     * @return null|string
     */
    public function getDayType($viewingUserId, $loggedInUserId, $currentDayType = null)
    {
        if ($currentDayType == null){
            return $this->getLessonDayType($viewingUserId, $loggedInUserId);
        } else {
            $latestDayType = $this->getLessonDayType($viewingUserId, $loggedInUserId);
            if ($currentDayType != $latestDayType){
                return Lesson::ROLE_STUDENT_AND_TUTOR;
            } else {
                return $currentDayType;
            }
        }
    }

    /**
     * Gets the calendar day type corresponding to the current lesson for the user profile
     * @param $viewingUserId
     * @param $loggedInUserId
     * @return string
     */
    public function getLessonDayType($viewingUserId, $loggedInUserId)
    {
        if ($this->tutorUser->id != $loggedInUserId && $this->studentUser->id != $loggedInUserId){
            return Lesson::ROLE_STUDENT_AND_TUTOR;
        }

        if ($this->tutorUser->id == $viewingUserId) {
            return Lesson::ROLE_TUTOR;
        } elseif ($this->studentUser->id == $viewingUserId){
            return Lesson::ROLE_STUDENT;
        } else {
            return Lesson::ROLE_STUDENT_AND_TUTOR;
        }
    }

    /**
     * Returns the color to be used for the day background on the calendar based on the $dayType
     * @param $dayType
     * @return string
     */
    public static function getCalendarEventColor($dayType)
    {
        switch($dayType){
            case self::ROLE_TUTOR:
                return self::EVENT_COLOR_TUTOR;
            case self::ROLE_STUDENT:
                return self::EVENT_COLOR_STUDENT;
            case self::ROLE_STUDENT_AND_TUTOR:
                return self::EVENT_COLOR_MIXED;
        }
    }


}
