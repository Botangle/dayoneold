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
		{{-- Html::script('js/jqueryui/jquery.ui.core.js') --}}

	@show
</head>
<body>

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

</body>
</html>
