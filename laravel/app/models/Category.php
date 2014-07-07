<?php

class Category extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'categories';

    /**
     * Scopes our categories down to just active categories
     *
     * @param $query
     */
    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

}
