<?php

class UserRate extends Eloquent {

    const RATE_PER_MINUTE   = 'permin';
    const RATE_PER_HOUR     = 'perhour';


    public function user()
    {
        $this->belongsTo('User');
    }

    public function getFormattedRateAttribute()
    {
        if ($this->price_type === self::RATE_PER_MINUTE){
            $type = trans('per min');
        } else {
            $type = trans('per hour');
        }
        $rate = $this->rate .' '. $type;
        return self::getCurrency() . $rate;
    }

    /**
     * Just thinking ahead to the time when we might want to support additional currencies. This enables
     * us to remove hardwired currencies from views and other models.
     * @return string
     */
    public static function getCurrency()
    {
        return '$';
    }
}
