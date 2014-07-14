<?php

class UserRate extends Eloquent {

    const RATE_PER_MINUTE   = 'permin';
    const RATE_PER_HOUR     = 'perhour';


    public function user()
    {
        $this->belongsTo('User');
    }

}
