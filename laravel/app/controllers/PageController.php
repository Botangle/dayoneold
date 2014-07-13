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
			'subject' => 'required|min:25',
			'message' => 'required|min:25',
		);

		$validator = Validator::make ($data, $rules);

		if ($validator -> passes()){

		Mail::send('emails.contact', $data, function($message) use ($data)
		{

		$message->from($data['email'] , $data['name']);
		$message->to('zhaff@yahoo.com')->cc('test@neptunescripts.com')->subject($data['subject']);

		});

		Session::flash('flash_success', 'Your email successfully sent to our admin.');
		
		return View::make('page.contactus')
            ->nest(
                'leftPanel',
                'page.leftpanel'
            );

		}else{
			return Redirect::to('/contact')->withErrors($validator)->withInput();
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
	
	public function getHowItWorks()
	{
		return View::make('page.howitworks');
	}
}
