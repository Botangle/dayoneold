<?php

class UserMessage extends Eloquent {

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
     * Adjusting our Laravel created / updated column names to match
     * what we had in Botangle
     *
     * @TODO: take this out long-term to keep things more consistent between this and normal Laravel apps
     * Note: this table doesn't have an UPDATED_AT, so a mutator is in place lower down to prevent that
     * causing problems.
     */
    const CREATED_AT = 'date';

    /**
     * UserMessage doesn't have an updated_at column but it does have a created_at column (add_date)
     * - see CONST above
     * This mutator prevents Eloquent from trying to include the updated_at column
     * in db updates.
     * @param $value
     */
    public function setUpdatedAtAttribute($value)
    {
        // Do nothing.
    }

    public function sender()
    {
        $this->belongsTo('User', 'sent_from');
    }

    public function recipient()
    {
        $this->belongsTo('User', 'send_to');
    }

}
