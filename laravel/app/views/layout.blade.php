<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>
		@if (trim($__env->yieldContent('page-title')))
			@yield('page-title') |
		@endif 
		{{ Config::get('site.title') }}
    </title>
	
	@section('head')
    <link href='//fonts.googleapis.com/css?family=Roboto:400,100,300' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/global.css" />
    <link rel="stylesheet" type="text/css" href="/css/dev.css" />

    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/bootstrap.js"></script>
	 

    {{-- @TODO: Let's take this line out and move it to only the pages we need it on --}}
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script type="text/javascript" src="/js/cbpAnimatedHeader.min.js"></script>
    <script type="text/javascript" src="/js/classie.js"></script>
    <script type="text/javascript" src="/js/modernizr.custom.js"></script>
	@show
	
    {{--
    $CurrentController = $this->params['controller'];
    $CurrentAction = $this->params['action'];
    --}}
</head>
<body>
<!--Wrapper Main Nav Block Start Here-->
@section('navigation')
    @include('_partials.nav')
@show

<!--Wrapper Main Nav Block End Here-->

<!--Wrapper Bannerblock Block Start Here-->
@section('header')
    @include('_partials.header-inner')
@show
<!--Wrapper Bannerblock Block End Here-->
<!--Wrapper HomeQuoteBlock Block End Here-->
<!--Wrapper main-content Block Start Here-->

@section('breadcrumbs')
{{ Breadcrumbs::renderIfExists() }}
@show

<div id="main-content">
    <div class="container">
        @if(Session::has('flash_error'))
        <div id="flashMessage" class="alert alert-error error">
            <strong>{{ Session::get('flash_error') }}</strong>
            @if($errors->count() > 0)
            <ul>
                @foreach ($errors->all() as $message)
                <li>{{ $message }}</li>
                @endforeach
            </ul>
            @endif

        </div>
        @endif

        @if(Session::has('flash_success'))
        <div id="flashMessage" class="alert alert-success success">
            {{ Session::get('flash_success') }}
        </div>
        @endif
    </div>
</div>

@section('content')
@show

<!--Wrapper main-content Block End Here-->
<!--Wrapper main-content1 Block Start Here-->
@include('_partials.footer-middle')
<!--Wrapper main-content1 Block End Here-->

<div id="footer">
    <div class="container">
        <div class="row-fluid">
            <div class="span4 fotter-left"> &copy; {{ date('Y'); }}. All right reserved. botangle.com </div>
            <div class="span5 fotter-right pull-right">
                <ul class="nav nav-pills pull-right">
                    {{--
                    <li><a href="#" title="Blog">Blog</a></li>
                    <li><a href="#" title="Sitemap">Sitemap</a></li>
                    <li>
                    echo $this->Html->link(
                        __('Terms of use'), '/terms'
                        , array('class' => ' active', 'title' => __('Terms of use'))
                    );
					</li>
					<li>
                    echo $this->Html->link(
                        __('Privacy Policy'), '/privacy'
                        , array('class' => ' active', 'title' => __('Privacy Policy'))
                    );
					</li>
                    --}}
                </ul>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="/css/prettyCheckable.css" />
<script type="text/javascript" src="/js/prettyCheckable.js"></script>

@section('jsFiles')
@show

<script type='text/javascript'>
    jQuery(document).ready(function() {

        if( $('input:checkbox').length )  {
            $('input:checkbox').prettyCheckable({
                color: 'red'

            });
        }
        @section('jqueryReady')
        @show
    });

    <?php /* Lucky Orange JS tracking script */ ?>
    window.__wtw_lucky_site_id = 23539;
    (function() {
        var wa = document.createElement('script'); wa.type = 'text/javascript'; wa.async = true;
        wa.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://cdn') + '.luckyorange.com/w.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(wa, s);
    })();
</script>
</body>
</html>
