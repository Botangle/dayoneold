<?php

/**
 * Class UserStream
 */

class UserStream extends MagniloquentContextsPlus {

	private $_openTokToken;

    protected $fillable = [
        'title',
        'description',
    ];

	/**
	 * Validation rules
	 */
	public static $rules = [
		"save" => [
		],
		// additional validation rules for the following contexts
		'update'    => [],
		'create'    => [
			'title'         => ['required', 'max:100'],
			'description'   => ['required', 'max:140'],
		],
	];

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

	/**
	 * @TODO: the below is horribly setup for testing, let's improve this down the road
	 * Seems like it needs to go into a "service" object instead of this
	 *
	 * @return bool
	 */
	public function createSessionAndSave()
	{
		$openTok = new OpenTok(Config::get('services.openTok.apiKey'), Config::get('services.openTok.apiSecret'));
		$this->opentok_session_id = $openTok->generateSessionId();
		return $this->save();
	}

	/**
	 * Retrieves an openTokToken to be used for the stream
	 *
	 * @TODO: a lot of this should be moved out to a service layer as well.  Seems like it's a simple component
	 * that has the OpenTok and Config objects loaded in via the DI layer and then we just do a simple configure ...
	 * @return mixed
	 */
	public function getOpenTokTokenAttribute()
	{
		if (!isset($this->_openTokToken)){
			$openTok = new OpenTok(Config::get('services.openTok.apiKey'), Config::get('services.openTok.apiSecret'));
			$this->_openTokToken = $openTok->generateToken($this->opentok_session_id);
		}
		return $this->_openTokToken;
	}
}
