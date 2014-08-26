<?php

class UserStatus extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'my_statuses';

    protected $fillable = array(
        'created_by_id',
        'status_text',
        'status',
        'created_at',
    );

    public function user()
    {
        return $this->belongsTo('User', 'created_by_id');
    }

    /**
     * @param $eventType
     * @return array|\Illuminate\Database\Eloquent\Model|static
     */
    public function logEvent($eventType)
    {
        $logEntry = UserLog::create(array(
                'user_id'           => $this->user->id,
                'type'              => $eventType,
                'related_type_id'   => $this->id,
                'created'           => $this->freshTimestampString(),
            ));
        if (!$logEntry->id){
            Event::fire('user_log.errors', array($logEntry->errors()));
        }
    }
}
