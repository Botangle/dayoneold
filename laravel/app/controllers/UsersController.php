<?php

class UsersController extends BaseController {

    public function getTopChart($categoryId = null)
    {
        // TODO: Will need to sort descending based on average review rating and any filters
        $users = User::active()->tutor();
        if ($categoryId){
            $category = Category::findOrFail($categoryId);
        }
        if($categoryId && $category){
            $users = $users->where('subject', 'LIKE', '%'. $category->name .'%');
        }

        // Include average rating from review table in the data and sort by average_rating descending
        $topListed = $users->averageRating()->paginate(9);

        // TODO: Reconsider use of noParent - existing system only uses root categories
        $categories = Category::active()->noParent()->orderBy('name')->get();

        return View::make('users.topChart', array(
                'users'         => $topListed,
                'categories'    => $categories,
            ));
    }
}
