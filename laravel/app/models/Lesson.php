<?php

class Lesson extends Eloquent {

    public function student()
    {
        $this->belongsTo('User', 'student');
    }

    public function payments()
    {
        $this->hasMany('LessonPayment');
    }

}
