<?php

class NewPageController extends BaseController
{
	protected $layout = 'new.layout';

	public function getIndex()
	{
		$liveStreams = UserStream::where('state', 1)->get();

		return View::make( 'new.page.home', array(
			'liveStreams' => $liveStreams,
		) );
	}
}
