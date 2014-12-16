<?php

class NewsController extends BaseController {

    public function getIndex()
    {
        $articles = News::all();
        return View::make('news.index', array('articles' => $articles))
            ->nest(
                'leftPanel',
                'page.leftpanel'
            );
    }

    /**
     * This needs to be given public access
     *
     * @param integer $id
     */
    public function getDetail($id)
    {
		$news = News::findOrFail($id);
		
		return View::make('news.detail', array('news' => $news))
			->nest(
				'leftPanel',
				'page.leftpanel'
			);
    }
}
