<?php

class UserMessage extends MagniloquentContextsPlus {

    const LESSON_NEW        = 'messages.new-lesson';
    const LESSON_CHANGED    = 'messages.changed-lesson';
    const LESSON_CONFIRMED  = 'messages.confirmed-lesson';
    const LESSON_REVIEWED   = 'messages.reviewed-lesson';
    const CUSTOM            = 'messages.custom';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'usermessages';

    protected $fillable = array(
        'sent_from',
        'send_to',
        'body',
        'date',
        'readmessage'
    );

    /**
     * Disable timestamps
     * Even though we'd like to use the created_at (set to date field)
     * However, there is no effective way of disabling updated_at completely. If you prevent
     * it from being added by having a setUpdatedAt function that does nothing, updated_at still
     * gets added in later on in Eloquent/Builder::update()
     * @var bool
     */
    public $timestamps = false;

    /**
     * Validation rules
     */
    public static $rules = array(
        "save" => array(
            'sent_from'             => array('required', 'exists:users,id'),
            'send_to'               => array('required', 'exists:users,id'),
            'body'                  => array('required'),
        ),
        // additional validation rules for the following contexts
        'update'    => array(),
        'create'    => array(),
    );

    public function sender()
    {
        return $this->belongsTo('User', 'sent_from');
    }

    public function recipient()
    {
        return $this->belongsTo('User', 'send_to');
    }

    public function scopeBetween($query, User $user1, User $user2)
    {
        $query->where(function($query) use($user1, $user2){
                $query->where(function($query) use($user1, $user2){
                        $query->where('sent_from', $user1->id)
                            ->where('send_to', $user2->id);
                    })
                ->orWhere(function($query) use($user1, $user2){
                            $query->where('sent_from', $user2->id)
                                ->where('send_to', $user1->id);
                        });
            });
    }

    public function scopeToUser($query, User $user)
    {
        $query->where('send_to', $user->id);
    }

    public function scopeUnread($query)
    {
        $query->where('readmessage', false);
    }

    /**
     * Saves (sends) a new message between $sender and $recipient
     * @param User $sender
     * @param User $recipient
     * @param $viewName
     * @param array $viewData
     * @return UserMessage
     */
    public function send(User $sender, User $recipient, $viewName, Array $viewData)
    {
        $this->fill(array(
                'sent_from'     => $sender->id,
                'send_to'       => $recipient->id,
                'body'          => View::make($viewName, $viewData),
                'date'          => $this->freshTimestampString(),
            ));
        if ($this->save()){
            Event::fire('userMessage.sent', array(
                    'userMessage' => $this, 'recipient' => $recipient, 'type' => $viewName
                ));
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $eventType
     * @param $description = ''
     * @return array|\Illuminate\Database\Eloquent\Model|static
     */
    public function logEvent($eventType, $description = '')
    {
        // TODO Improve by replacing this function with dependency injection of UserMessage to UserLog::new()
        $logEntry = UserLog::create(array(
                'user_id'           => $this->sender->id,
                'type'              => $eventType,
                'related_type_id'   => $this->id,
                'created'           => $this->freshTimestampString(),
                'description'       => $description,
            ));
        if (!$logEntry->id){
            Event::fire('user_log.errors', array($logEntry->errors()));
        }
    }

    public static function getEmailSubjectFromViewName($viewName, $sender)
    {
        switch($viewName){
            case self::LESSON_NEW:
                return trans('Lesson Proposal');
            case self::LESSON_CHANGED:
                return trans('Lesson Changed');
            case self::LESSON_CONFIRMED:
                return trans('Lesson Confirmed');
            case self::LESSON_REVIEWED:
                return trans('Lesson Reviewed');
            case self::CUSTOM:
                return trans('You have a message from ') . $sender;
        }
    }

}
