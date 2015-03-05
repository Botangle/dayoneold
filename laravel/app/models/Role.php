<?php

class Role extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

	/**
	 * Adjusting our Laravel created / updated column names to match
	 * what we had in Cake
	 *
	 * @TODO: take this out long-term to keep things more consistent between this and normal Laravel apps
	 */
	const CREATED_AT = 'created';
	const UPDATED_AT = 'updated';

	protected $fillable = array(
        'title',
        'alias',
        'created',
	    'updated',
    );

    public function users()
    {
        return $this->hasMany('User');
    }
}
