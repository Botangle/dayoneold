<?php

class Lesson extends Eloquent {

    public function student()
    {
        return $this->belongsTo('User', 'student');
    }

    public function tutor()
    {
        return $this->belongsTo('User', 'tutor');
    }

    public function payments()
    {
        return $this->hasMany('LessonPayment');
    }

}
