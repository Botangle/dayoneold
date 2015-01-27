@extends('layout')

@section('head')
@parent
{{HTML::style('js/hopscotch/css/hopscotch.min.css')}}
<script src="//load.sumome.com/" data-sumo-site-id="2a51f2f5b868bc45c334915a961ea81e69cb264c8cc6d4025e852a6cdaa59de4" async></script>
@stop

@section('header')
<header id="Bannerblock">
    <div class="container text-center">
        <h1>What do you need help with?</h1>
        <div class="Searchblock row-fluid">
            {{ Form::open(array('route' => 'users.search', 'id' => 'searchuser', 'class' => 'form-search')) }}
                <div class="Search-main1">
                    <input name="searchvalue" id="searchvalue" type="text" placeholder="Example: Chemistry, Maths etc" />
                </div>
                <div class="Search-main1-btn">
                    <button type="submit" class="btn search-main-btn">Search</button>
                </div>
            {{ Form::close() }}
        </div>
        <div class="joinus">
            <div class="title"> Join Us </div>
            <div class=" row-fluid join-btn-block">
                <div class="span6 joinus-button1">
                    {{ link_to_action('RegistrationController@getRegisterStudent', trans('Become a Student'), [], ['class' => 'join-btn', 'title' => trans('Become a Student')]) }}
                </div>
                <div class="span6 joinus-button1">
                    {{ link_to_action('RegistrationController@getRegisterExpert', trans('Become an Expert'), [], ['class' => 'join-btn', 'title' => trans('Become an Expert')]) }}
                </div>
            </div>
        </div>
    </div>
</header>
@overwrite

@section('breadcrumbs')
@show

@section('content')
<!--Wrapper HomeServices Block Start Here-->
<div id="HomeServices">
    <div class="container">
        <div class=" row-fluid">
            <div class="span4 Servicebox">
                <div class="service-img"><img src="/images/join-img.png" alt="Join"></div>
                <div class="service-text" onclick="window.location.href = '/register'">
                    <h2>Join Botangle</h2>
                    <p>lets you connect with one of the<br/> best online experts the moment you'd like a hand.</p>
                </div>
            </div>
            <div class="span4 Servicebox"  onclick="window.location.href = '/user/search'">
                <div class="service-img"><img src="/images/search-tutor.png" alt="Join"></div>
                <div class="service-text">
                    <h2>Search Experts</h2>
                    <p>Work with someone instantly or schedule<br/> a lesson with your preferred expert at a convenient time. </p>
                </div>
            </div>
            <div class="span4 Servicebox"  onclick="window.location.href = '/user/search'">
                <div class="service-img"><img src="/images/learn-class.png" alt="Join"></div>
                <div class="service-text">
                    <h2>Learn in Class</h2>
                    <p>You'll be able to chat, use video, upload<br/> documents and write on a shared whiteboard.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Wrapper HomeServices Block End Here-->
<!--Wrapper HomeQuoteBlock Block Start Here-->
<header id="HomeQuoteBlock">
    <div class="container text-center">
        <div class="QuoteBlock row-fluid">
            <div class="span12"> <span class="left">"</span>
                <p> Botangle offers tutoring over video chat. So if you need help, you can get help from anyone, anywhere, for anything. </p>
                <span class="right">"</span>
                <p class="quote-client"><span>Jack,</span> New York</p>
            </div>
        </div>
    </div>
</header>
@if(count($featuredUsers) > 0)
<!--Wrapper HomeQuoteBlock Block End Here-->
<div class="row-fluid Featured-tutors-block">
    <center>
        <h2>Featured Experts</h2>
    </center>
</div>
<div class="row-fluid">
	@foreach ($featuredUsers as $user)
	<div class="span4 Tutor-list1">
		<div class="tutor-img">
			<a href="{{{ action('UserController@getView', ['username' => $user->username]) }}}">
				<img src="<?php echo!empty($user->profilepic) ? $user->profilepic : '/images/tutor.jpg' ?>" class="img-circle" alt="student" style="width: 195px; height: 195px">
			</a>
		</div>
		<div class="tutor-title">
			<h3>
				<a href="{{{ action('UserController@getView', ['username' => $user->username]) }}}">{{{ $user->full_name }}}</a>
			</h3>
			<p>{{{ $user->qualification }}}</p>
		</div>
		<div class="tutor-bio">
			<p>{{{ $user->extracurricular_interests }}}</p>
			<div class="social">
                {{-- social link - FB --}}
                @if (!empty($user->link_fb))
				<a href="{{{ $user->link_fb }}}" class="img-circle-left">
					<img src="/img/facebook.png">
				</a>
                @endif

				{{-- social link - Twitter --}}
                @if (!empty($user->link_twitter))
				<a href="{{{ $user->link_twitter }}}" class="img-circle-left">
					<img src="/img/twitter.png">
				</a>
                @endif

				{{-- social link - Google+ --}}
                @if (!empty($user->link_googleplus))
				<a href="{{{ $user->link_googleplus }}}" class="img-circle-left">
					<img src="/img/google.png">
				</a>
                @endif

				{{-- social link - Thumblr --}}
                @if (!empty($user->link_thumblr))
				<a href="{{{ $user->link_thumblr }}}" class="img-circle-left">
					<img src="/img/trumbler.png">
				</a>
                @endif
			</div>
		</div>
	</div>
    @endforeach
</div>
@endif
@overwrite

@section('jsFiles')
@parent
{{ HTML::script('/js/hopscotch/js/hopscotch.min.js') }}
{{ HTML::script('/js/welcome-tour.js') }}
@stop
