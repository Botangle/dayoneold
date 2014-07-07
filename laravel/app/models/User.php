<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

    /**
     * Scopes our users down to just show our featured users on the homepage
     *
     * @param $query
     */
    public function scopeFeatured($query)
    {
        $query->where('is_featured', '=', 1);
    }

    /**
     * Builds a quick getter (useable at $user->picture) that gets the appropriate image
     *
     * @return mixed|string
     */
    public function getPictureAttribute()
    {
        return ($this->profilepic != null) ? $this->profilepic : "/images/tutor1.jpg";
    }

    /**
     * Returns our user's fullname ($user->full_name)
     *
     * @TODO: localize this as needed so it works better in countries where names work differently (China, Japan, etc)
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return ucfirst($this->name) . ' ' . ucfirst($this->lname);
    }
}
