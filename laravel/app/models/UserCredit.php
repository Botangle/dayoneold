<?php

class UserCredit extends Eloquent {

    protected $fillable = array(
        'user_id',
        'amount',
    );

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('User');
    }

    /**
     * @param $eventType
     * @param $description
     * @return array|\Illuminate\Database\Eloquent\Model|static
     */
    public function logEvent($eventType, $description = '')
    {
        $logEntry = UserLog::create(array(
                'user_id'           => $this->user->id,
                'type'              => $eventType,
                'related_type_id'   => $this->id,
                'created'           => $this->freshTimestampString(),
                'description'       => $description,
            ));
        if (!$logEntry->id){
            Event::fire('user_log.errors', array($logEntry->errors()));
        }
    }
}
