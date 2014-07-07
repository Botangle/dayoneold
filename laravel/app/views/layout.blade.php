<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>
        {{--
            Configure::read('Site.title');
        --}}
    </title>
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

    {{--
    $CurrentController = $this->params['controller'];
    $CurrentAction = $this->params['action'];
    --}}
</head>
<body>
<!--Wrapper Main Nav Block Start Here-->
@section('navigation')
    @include('nav')
@show

<!--Wrapper Main Nav Block End Here-->

<!--Wrapper Bannerblock Block Start Here-->
@section('header')
    @import('headerinner')
@show
<!--Wrapper Bannerblock Block End Here-->
<!--Wrapper HomeQuoteBlock Block End Here-->
<!--Wrapper main-content Block Start Here-->

@section('content')
@show

<!--Wrapper main-content Block End Here-->
<!--Wrapper main-content1 Block Start Here-->
{{-- echo $this->element('footermiddle'); --}}
<!--Wrapper main-content1 Block End Here-->

{{-- echo $this->element('footerbottom'); ?>
//echo $this->Blocks->get('scriptBottom');
//echo $this->Js->writeBuffer();
--}}
</body>
</html>
