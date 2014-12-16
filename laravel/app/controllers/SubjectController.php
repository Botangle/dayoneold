<?php

class SubjectController extends BaseController {

    public function search()
    {
		return Response::json(Category::getList());
    }
}
