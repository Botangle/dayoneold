@extends('layout')

@section('head')
@parent
{{--HTML::script('js/jqueryui/jquery-1.9.1.js')--}}
{{HTML::script('js/jqueryui/jquery.ui.core.js')}}
{{HTML::script('js/jqueryui/jquery.ui.widget.js')}}
{{HTML::script('js/jqueryui/jquery.ui.position.js')}}
{{HTML::script('js/jqueryui/jquery.ui.menu.js')}}
{{HTML::script('js/jqueryui/jquery.ui.autocomplete.js')}}

{{HTML::style('css/jqueryui/themes/base/jquery.ui.all.css')}}
{{HTML::style('css/jqueryui/demos.css')}}

<script>
    $(function() {
        $.getJSON("/subject/search", function(response) {
            data = response;

            $("#searchvalue, #LessonSubject").autocomplete({
                minLength: 0,
                source: data,
                focus: function(event, ui) {
                    $("#searchvalue").val(ui.item.label);
                    return false;
                },
                select: function(event, ui) {
                    console.log(ui);
                    $("#searchvalue").val(ui.item.label);
                    return false;
                }
            })
                .data("ui-autocomplete")._renderItem = function(ul, item) {
                return $("<li>")
                    .append("<a>" + item.label + "</a>")
                    .appendTo(ul);
            };
        });
    });

    jQuery(function() {

        function split(val) {
            return val.split(/,\s*/);
        }
        function extractLast(term) {
            return split(term).pop();
        }
        var typeid = "";
        jQuery("#searchvalue2")
            // don't navigate away from the field on tab when selecting an item
            .bind("keydown", function(event) {
                typeid = this.id;
                if (event.keyCode === jQuery.ui.keyCode.TAB &&
                    $(this).data("ui-autocomplete").menu.active) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                source: function(request, response) {

                    var url = "/subject/search";

                    $.getJSON(url, {
                        term: extractLast(request.term)
                    }, response);
                },
                search: function() {
                    // custom minLength
                    var term = extractLast(this.value);
                    if (term.length < 2) {
                        return false;
                    }
                },
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                select: function(event, ui) {
                    var terms = split(this.value);
                    if (typeid == 'LessonTutor') {
                        jQuery("#" + typeid + "Value").val(ui.item.id);
                    }
                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push(ui.item.value);
                    // add placeholder to get the comma-and-space at the end
                    terms.push("");
                    this.value = terms.join(" ");
                    return false;
                }
            });
    });
</script>
@stop

@section('header')
<header id="Bannerblock">
    <div class="container text-center">
        <h1>What do you need help with?</h1>
        <div class="Searchblock row-fluid">
            <form method="post" action="/user/search" id="searchuser" class="form-search">
                <div class="Search-main1">
                    <input name="searchvalue" id="searchvalue" type="text" placeholder="Example: Chemistry, Maths etc" />
                </div>
                <div class="Search-main1-btn">
                    <button type="submit" class="btn search-main-btn">Search</button>
                </div>
            </form>
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
