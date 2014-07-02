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
    {{--
    $CurrentController = $this->params['controller'];
    $CurrentAction = $this->params['action'];
    --}}
</head>
<body>
{{--
echo $this->element('navigation');
<!--Wrapper Main Navi Block End Here-->
<!--Wrapper Bannerblock Block Start Here-->
if($CurrentController=='nodes' && $CurrentAction =='promoted'){
    echo $this->element('header');
}else{
    echo $this->element('headerinner');
}
--}}
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
