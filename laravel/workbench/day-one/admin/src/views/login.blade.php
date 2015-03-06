<html>
<body>
<br><br><br>

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

<form method="post">
	<input type="text" name="username">
	<input type="password" name="password">
	<input type="submit">
</form>

</body>
</html>
