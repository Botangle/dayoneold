<?php

class UserRate extends MagniloquentContextsPlus {

    const RATE_PER_MINUTE   = 'permin';
    const RATE_PER_HOUR     = 'perhour';

    public $timestamps = false;

    public $currency = '$';

    /**
     * What POST values we'll even take with massive assignment
     * @var array
     */
    protected $fillable = array(
        'userid',
        'price_type',
        'rate',
    );

    /**
     * Validation rules
     */
    public static $rules = array(
        "save" => array(
            'userid'         => array('required', 'exists:users,id'),
            'price_type'     => array('in:permin,perhour'),
            'rate'           => array('required', 'numeric'),
        ),
        'create'    => array(),
        'update'    => array(),
    );


    public function user()
    {
        $this->belongsTo('User', 'userid');
    }

    public function getFormattedRateAttribute()
    {
        if ($this->price_type === self::RATE_PER_MINUTE){
            $type = trans('per min');
        } else {
            $type = trans('per hour');
        }
        $rate = $this->rate .' '. $type;
        return $this->currency . $rate;
    }

    /**
     * Calculates the total amount payable to the tutor for a lesson
     * @param $totalTime Seconds
     * @return string
     */
    public function calculateTutorTotalAmount($totalTime) {
        if ($this->price_type == 'permin') {
            $totalTimeMins = $totalTime / 60;
            $totalAmount = $totalTimeMins * $this->rate;
        } else { // per hour
            $totalTimeHours = ($totalTime / 60) / 60;
            $totalAmount = $totalTimeHours * $this->rate;
        }

        // return a formatted decimal to two decimal places with no commas
        // this will work much better in our DB and with Stripe
        return sprintf('%0.2f', $totalAmount);
    }


}
