<?php

class PageController extends BaseController {

    protected $layout = 'page.layout';

    public function getIndex()
    {
        $this->layout = 'layout';
        return View::make('page.home');
    }

    public function getAbout()
    {
        return View::make('page.reportbug')
            ->nest(
                'leftPanel',
                'page.leftpanel'
            );
    }

    public function getContact()
    {
        return View::make('page.reportbug')
            ->nest(
                'leftPanel',
                'page.leftpanel'
            );
    }

    public function getReportbug()
	{
		return View::make('page.reportbug')
            ->nest(
                'leftPanel',
                'page.leftpanel'
            );
	}
}
