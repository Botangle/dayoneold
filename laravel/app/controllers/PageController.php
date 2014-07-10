<?php

class PageController extends BaseController {

    protected $layout = 'page.layout';

    public function getIndex()
    {
        $featuredUsers = User::featured()->get();

        $this->layout = 'layout';
        return View::make('page.home', array(
                'featuredUsers' => $featuredUsers,
            ));
    }

    public function getAbout()
    {
        return View::make('page.about')
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
	
	public function getHowItWorks()
	{
		return View::make('page.howitworks');
	}
}
