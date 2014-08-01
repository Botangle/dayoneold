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
        $this->belongsTo('User', 'created_by_id');
    }

}
