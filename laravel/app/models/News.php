<?php

class News extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'news';

    /**
     * Converts our date attribute to a carbon model so we can do more flexible things with it
     *
     * @return \Carbon\Carbon
     */
    public function getDateAttribute($value)
    {
        return Carbon\Carbon::parse($value);
    }
}
