<?php

class UserLog extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_logs';

    /**
     * What POST values we'll even take with massive assignment
     * @var array
     */
    protected $fillable = array(
        'user_id',
        'type',
        'created',
    );

    /**
     * Disable timestamps
     * Even though we'd like to use the created_at (set to created field)
     * However, there is no effective way of disabling updated_at completely. If you prevent
     * it from being added by having a setUpdatedAt function that does nothing, updated_at still
     * gets added in later on in Eloquent/Builder::update()
     * @var bool
     */
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('User');
    }

    /**
     * Scopes the logs for a particular user
     *
     * @param $query
     * @param User $user
     */
    public function scopeForUser($query, User $user)
    {
        $query->where('user_id', $user->id);
    }


}
