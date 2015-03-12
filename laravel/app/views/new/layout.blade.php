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
		<link href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.2/readable/bootstrap.min.css" rel="stylesheet">
		<link href="/css/new.css" rel="stylesheet">

		<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

	@show
</head>
<body>
<!--Wrapper Main Nav Block Start Here-->
@section('navigation')
    @include('_partials.nav')
@show

@section('breadcrumbs')
{{-- Breadcrumbs::renderIfExists() --}}
@show

<div id="main-content">
    <div class="container">
		@if(Session::has('flash_error'))
			<div class="alert alert-danger">
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
			<div class="alert alert-success">
				{{ Session::get('flash_success') }}
			</div>
		@endif

	@section('content')
			@show
	</div>
</div>

<div id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-5 textleft"> &copy; {{ date('Y'); }}. All right reserved. startdayone.com </div>
        </div>
    </div>
</div>


@section('jsFiles')
	{{--<script type='text/javascript'>
		window.__wtw_lucky_site_id = 34581;

		(function() {
			var wa = document.createElement('script'); wa.type = 'text/javascript'; wa.async = true;
			wa.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://cdn') + '.luckyorange.com/w.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(wa, s);
		})();
	</script>
	--}}
@show

</body>
</html>
