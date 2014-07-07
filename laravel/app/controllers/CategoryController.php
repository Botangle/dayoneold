<?php

class CategoryController extends BaseController {

    protected $layout = 'layout';

    public function getIndex()
    {
        $categories = Category::active()->where('parent_id', null)->get();

//  @TODO: allow searching to find certain categories
//        if (!empty($this->request->data)) {
//            $name = $this->request->data['search'];
//            $cond = array('status' => "1", 'name LIKE ' => "%$name%", 'parent_id' => null);
//        }

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
