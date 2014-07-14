<?php

class Review extends Eloquent {

    public function lesson()
    {
        $this->belongsTo('Lesson');
    }

}
