@extends('layout')

@section('content')

<!--Wrapper main-content Block Start Here-->
<div id="main-content">
	<div class="container">
		<div class="row-fluid">
			<div class="span12">

			</div>
		</div>
		<div class="row-fluid">
			@include('_partials.leftinner')
			<div class="span9">
				<h2 class="page-title">Updates</h2>
				<div class="StaticPageRight-Block">
					<div class="PageLeft-Block">
						<div class="row-fluid">
							<div class="span3 media-img">
								<?php //TODO: News image upload ?>
								<?php //if (file_exists(WWW_ROOT . DS . 'uploads' . DS . 'news' . DS . $news['News']['image']) && $news['News']['image'] != "") { ?>
									<!--<img src="<?php //echo $this->webroot . 'uploads/news/' . $news['News']['image'] ?> ">-->
								<?php //} else { ?>
									{{ HTML::image('images/media-1.jpg', 'media') }}
								<?php //} ?>
							</div>
							<div class="span9 media-text">
								<p class="FontStyle20"><a href="#" >{{{ $news->title }}}</a></p>
								<p>{{{ $news->details }}}</p>
								<br>
								<p>Posted on: {{ date("M d,Y",strtotime($news->date)) }} </p></div>
						</div> </div>

				</div>
			</div>
		</div>
		<!-- @end .row --> 

		@include('_partials.get-in-touch')

	</div>
	<!-- @end .container --> 
</div>
<!--Wrapper main-content Block End Here-->
@overwrite