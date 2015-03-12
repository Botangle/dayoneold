<?php

class NewPageController extends BaseController
{
	protected $layout = 'newlayout';

	public function getIndex()
	{
		$erik = new stdClass();
		$erik->id = 3;
		$erik->full_name = 'Erik Finman';
		$erik->title = 'Our Title goes here';
		$erik->description = 'This is a max of 140 characters available here';
		$erik->video_id = '123123';
		$erik->twitter = '@erikjf';

		$ryan = new stdClass();
		$ryan->id = 3;
		$ryan->full_name = 'Kyle Ryna';
		$ryan->title = 'Kyle\'s Title goes here';
		$ryan->description = 'This is a max of 140 characters available here';
		$ryan->video_id = '123123';
		$ryan->twitter = '@kryan';

		$liveStreams = [
			$erik,
			$ryan
		];

		return View::make( 'newpage.home', array(
			'liveStreams' => $liveStreams,
		) );
	}
}
