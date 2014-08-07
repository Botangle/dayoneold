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

    /**
     * Returns a list of categories (e.g. for AutoComplete use)
     * @return array
     */
    public static function getList()
    {
        $categories = Category::active()->where('parent_id', null)->orderBy('name')->get(array('id', 'name'));

        $result = array();

        foreach ($categories as $item) {
            array_push($result, array("id" => $item->id, "label" => $item->name, "value" => strip_tags($item->name)));
        }
        return $result;
    }

}
