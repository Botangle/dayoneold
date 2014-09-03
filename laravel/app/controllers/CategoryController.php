<?php

class CategoryController extends BaseController {

    public function getIndex()
    {
        $categoriesCriteria = Category::active()->noParent();
        if(!empty(Input::get('search'))){
            $categoriesCriteria = $categoriesCriteria->where('name', 'like', '%'. Input::get('search') .'%');
        }
        $categories = $categoriesCriteria->get();

        // @TODO: work out the API setup here
        //        if($this->RequestHandler->isXml()) {
        //
        //            $this->helpers[] = 'Categories.UserCount';
        //
        //            $this->set('categories', $c);
        //        }

        $results = "";
        $arrayAlphabets = "";
        for ($i = 65; $i <= 90; $i++) {
            $arrayAlphabets[] = chr($i);
        }
        foreach ($categories as $category) {
            $char = substr($category->name, 0, 1);
            $results[strtoupper($char)][] = array($category->id => $category->name);
        }

        return View::make('category.index', array(
                'categories' => $results,
            ));
    }
}
