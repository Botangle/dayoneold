<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

    /**
     * Adjusting our Laravel created / updated column names to match
     * what we had in Cake
     *
     * @TODO: take this out long-term to keep things more consistent between this and normal Laravel apps
     */
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';

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
     * Relationship for user reviews
     * @return mixed
     */
    public function Reviews()
    {
        return $this->hasMany('Review', 'rate_to');
    }

    /**
     * Scopes our users down to just active users
     *
     * @param $query
     */
    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

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
     * Scopes our users down to just online users
     *
     * @param $query
     */
    public function scopeOnline($query)
    {
        $query->where('is_online', 1);
    }

    public function scopeTutor($query)
    {
        $query->where('role_id', 2);
    }

    public function scopeAverageRating($query)
    {
        $query->leftJoin('reviews', 'reviews.rate_to', '=', 'users.id')
            ->select(array('users.*',
                    DB::raw('AVG(rating) as ratings_average')
                ))
            ->groupBy('id')
            ->orderBy('ratings_average', 'DESC');
    }

    /**
     * Get the password for the user.
     *
     * @TODO: replace this hack with a proper use of Bcrypt hashing setup long-term: http://stackoverflow.com/a/23409614/334913
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return Hash::make($this->password);
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

    /**
     * @return bool
     */
    public function isTutor()
    {
        return ($this->role_id == 2) ? true : false;
    }

    /**
     * Whether the specified user is online or not
     * @param boolean $isOnline
     */
    public function setOnlineStatus($isOnline)
    {
        $this->is_online = $isOnline;
        $this->save();
    }

    public function getAverageRatingAttribute()
    {
        return round($this->ratings_average, 0, PHP_ROUND_HALF_DOWN);
    }
}
