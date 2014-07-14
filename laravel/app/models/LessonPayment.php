<?php

class LessonPayment extends Eloquent {

    public function lesson()
    {
        $this->belongsTo('Lesson');
    }

    public function student()
    {
        $this->belongsTo('User', 'student_id');
    }

    public function tutor()
    {
        $this->belongsTo('User', 'tutor_id');
    }

}
