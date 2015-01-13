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

    public function getUserCountCacheName()
    {
        return 'category-user-count-'.$this->id;
    }

    public function getUserCount()
    {
        $cacheName = $this->getUserCountCacheName();
        if (!Cache::has($cacheName)){
            $userCount = User::where('subject', 'LIKE', '%'. $this->name .'%')->count();
            Cache::forever($cacheName, $userCount);
        }
        return Cache::get($cacheName);
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

    /**
     * Returns a list of categories (e.g. for AutoComplete use)
     * @return array
     */
    public static function getSelect2List()
    {
        $categories = Category::active()->where('parent_id', null)->orderBy('name')->get(array('id', 'name'));

        $result = array();

        foreach ($categories as $item) {
            $result[strip_tags($item->name)] = strip_tags($item->name);
        }
        return $result;
    }

    public static function resetUserCountCaches(array $oldList, array $newList = [])
    {
        // Reset the cache for a category which has been removed from a user's subject list
        foreach($oldList as $categoryName){
            if (!in_array($categoryName, $newList)){
                $category = Category::where('name', $categoryName)->first();
                if ($category){
                    $category->resetUserCountCache();
                }
            }
        }

        // Reset the cache for a category which has been added to a user's subject list
        foreach($newList as $categoryName){
            if (!in_array($categoryName, $oldList)){
                $category = Category::where('name', $categoryName)->first();
                if ($category){
                    $category->resetUserCountCache();
                }
            }
        }
    }

    public function resetUserCountCache()
    {
        Cache::forget($this->getUserCountCacheName());
    }
}
