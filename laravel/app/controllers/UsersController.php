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
        $users->leftJoin('reviews', 'reviews.rate_to', '=', 'users.id')
               ->select(array('users.*',
                    DB::raw('AVG(rating) as ratings_average')
                ))
                ->groupBy('id')
                ->orderBy('ratings_average', 'DESC');

        $topListed = $users->paginate(9);

        // TODO: Reconsider use of noParent - existing system only uses root categories
        $categories = Category::active()->noParent()->orderBy('name')->get();

        return View::make('users.topChart', array(
                'users'         => $topListed,
                'categories'    => $categories,
            ));
    }
}
