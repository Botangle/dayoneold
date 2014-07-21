<?php

class SubjectController extends BaseController {

    public function search()
    {
        $categories = Category::active()->where('parent_id', null)->get(array('id', 'name'));

		$result = array();
		
		foreach ($categories as $item) {
			array_push($result, array("id" => $item->id, "label" => $item->name, "value" => strip_tags($item->name)));
		}
		
		return Response::json($result);
    }
}
