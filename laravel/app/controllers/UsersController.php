<?php

class UsersController extends BaseController {

    public function getTopChart($categoryId = null)
    {
        $users = User::active()->tutor();
        if ($categoryId){
            $category = Category::findOrFail($categoryId);
        }
        if($categoryId && $category){
            $users = $users->where('subject', 'LIKE', '%'. $category->name .'%');
        }

        // Sort by average_rating descending
        $topListed = $users->averageRating()->orderBy('id')->paginate(9);

        // TODO: Reconsider use of noParent - existing system only uses root categories
        $categories = Category::active()->noParent()->orderBy('name')->get();

        return View::make('users.topChart', array(
                'users'         => $topListed,
                'categories'    => $categories,
            ));
    }

    public function getSearch($searchText = "")
    {
        $users = User::active()->tutor();
        if (!empty($searchText)){
            $users = $users->where('subject', 'LIKE', '%'. $searchText .'%');
        }

        // Sort by average_rating descending
        $users = $users->averageRating()->paginate(12);

        return View::make('users.search', array(
                'users' => $users,
            ));
    }

    public function postSearch()
    {
        $routeArray = [];
        if (Input::has('searchvalue')){
            $routeArray[] = Input::get('searchvalue');
        }
        return Redirect::route('users.search', $routeArray);
    }
}
