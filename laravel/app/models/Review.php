<?php

class Review extends Eloquent {

    public function lesson()
    {
        return $this->belongsTo('Lesson');
    }

    public function reviewer()
    {
        return $this->belongsTo('User', 'rate_by');
    }

    public function reviewedUser()
    {
        return $this->belongsTo('User', 'rate_to');
    }

}
