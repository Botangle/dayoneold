@extends('page.layout')

@section('page-title')
{{{ trans('Updates') }}}
@stop

@section('content')
<div class="row-fluid">
	<div class="span3 media-img">
		<?php //TODO: News image upload ?>
		<?php //if (file_exists(WWW_ROOT . DS . 'uploads' . DS . 'news' . DS . $news['News']['image']) && $news['News']['image'] != "") { ?>
			<!--<img src="<?php //echo $this->webroot . 'uploads/news/' . $news['News']['image']    ?> ">-->
		<?php //} else { ?>
		{{ HTML::image('images/media-1.jpg', 'media') }}
		<?php //} ?>
	</div>
	<div class="span9 media-text">
		<p class="FontStyle20"><a href="#" >{{{ $news->title }}}</a></p>
		<p>{{{ $news->details }}}</p>
		<br>
		<p>Posted on: {{ date("M d,Y",strtotime($news->date)) }} </p></div>
</div>
@stop