<?php

class NewsController extends BaseController {

    public function getIndex()
    {
    }

    /**
     * This needs to be given public access
     *
     * @param integer $id
     */
    public function getDetail($id)
    {
		$news = News::findOrFail($id);
		return View::make('news.detail', array(
			'news' => $news,
		));
    }
}
