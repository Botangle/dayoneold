<?php

class Review extends MagniloquentContextsPlus {

    /**
     * Adjusting our Laravel created / updated column names to match
     * what we had in Botangle
     *
     * @TODO: take this out long-term to keep things more consistent between this and normal Laravel apps
     * Note: this table doesn't have a CREATED_AT, so a mutator is in place lower down to prevent that
     * causing problems.
     */
    const UPDATED_AT = 'add_date';

    /**
     * What POST values we'll even take with massive assignment
     * @var array
     */
    protected $fillable = array(
        'lesson_id',
        'rating',
        'reviews',
        'rate_by',
        'rate_to',
    );

    /**
     * Validation rules
     */
    public static $rules = array(
        "save" => array(
            'lesson_id'                 => array('required', 'exists:lessons,id'),
            'rate_by'                   => array('required', 'exists:users,id'),
            'rate_to'                   => array('required', 'exists:users,id'),
            'rating'                    => array('required', 'min:1', 'max:5'),
        ),
        // additional validation rules for the following contexts
        'update'    => array(),
        'create'    => array(),
    );

    /**
     * Review doesn't have an created_at column but is does have a updated_at column (add_date) - see CONST above
     * This mutator prevents Eloquent from trying to include the created_at column
     * in db updates.
     * @param $value
     */
    public function setCreatedAtAttribute($value)
    {
        // Do nothing.
    }

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
