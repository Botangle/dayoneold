<?php

class PageController extends BaseController {

    protected $layout = 'page.layout';

    public function getIndex()
    {
        if (!Cache::has('featured-users')){
            Cache::put('featured-users', User::featured()->get(), 1440);
        }
        $featuredUsers = Cache::get('featured-users');

        $this->layout = 'layout';
        return View::make('page.home', array(
                'featuredUsers' => $featuredUsers,
            ));
    }

    public function getTerms()
    {
        return View::make('page.terms')
            ->nest(
                'leftPanel',
                'page.leftpanel'
            );
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
        return View::make('page.contactus')
            ->nest(
                'leftPanel',
                'page.leftpanel'
            );
    }
	
	public function getContactForm(){
		$data = Input::all();

		//Validation rules
		$rules = array (
			'name' => 'required',
			'email' => 'required|email',
			'subject' => 'required|min:5',
			'message' => 'required|min:25',
		);

		$validator = Validator::make ($data, $rules);

		if ($validator -> passes()){

		Mail::send('emails.contact', $data, function($message) use ($data)
		{
    		$message->to(Config::get('site.email'))->subject($data['subject']);
		});

		Session::flash('flash_success', 'Your email successfully sent to our admin.');
		
		return View::make('page.contactus')
            ->nest(
                'leftPanel',
                'page.leftpanel'
            );

		}else{
			return Redirect::back()
                ->with('flash_error', trans("There was a problem with your message:"))
                ->withErrors($validator)
                ->withInput($data);
		}
	}

    public function getReportbug()
	{
		return View::make('page.reportbug')
            ->nest(
                'leftPanel',
                'page.leftpanel'
            );
	}
	
	public function getReportbugForm(){
		$data = Input::all();

		//Validation rules
		$rules = array (
			'name' => 'required',
			'email' => 'required|email',
			'subject' => 'required|min:5',
			'message' => 'required|min:25',
		);

		$validator = Validator::make ($data, $rules);

		if ($validator -> passes()){

		Mail::send('emails.reportbug', $data, function($message) use ($data)
		{
    		$message->to(Config::get('site.email'))->subject($data['subject']);
		});

		Session::flash('flash_success', 'Thanks for submitting your bug report!');
		
		return View::make('page.reportbug')
            ->nest(
                'leftPanel',
                'page.leftpanel'
            );

		}else{
			return Redirect::back()
                ->with('flash_error', trans("There was a problem with your message:"))
                ->withErrors($validator)
                ->withInput($data);
		}
	}
	
	public function getHowItWorks()
	{
		return View::make('page.howitworks');
	}
}
