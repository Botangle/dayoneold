<?php

class News extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'news';

    /**
     * Dates listed here are automatically mutated to be Carbon date objects - nice!
     * @var array
     */
    public $dates = ['date'];

    /**
     * Substitutes a default image if one doesn't exist for the news article
     * @param $value
     * @return string
     */
    public function getImageAttribute($value)
    {
        // Assuming that full url will be stored for image
        if ($value){
            return $value;
        } else {
             return url("/images/media-1.jpg");
        }
    }
}
