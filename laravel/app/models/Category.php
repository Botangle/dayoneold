<?php

class Category extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'categories';

    public function parent()
    {
        return $this->belongsTo('Category', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('Category', 'parent_id');
    }

    /**
     * Scopes our categories down to just active categories
     *
     * @param $query
     */
    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    public function scopeHasParent($query)
    {
        $query->where('parent_id', '<>', NULL);
    }

    public function scopeNoParent($query)
    {
        $query->where('parent_id', '=', NULL);
    }

    public function getUserCount()
    {
        return User::where('subject', 'LIKE', '%'. $this->name .'%')->count();
    }

}
