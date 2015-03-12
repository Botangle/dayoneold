@extends('new.layout')

@section('header')
@parent
@stop

<!--Wrapper main-content Block Start Here-->
@section('content')
    <div class="row">
		<div class="col-md-12">
			{{ Form::open([
					'url' => 'login',
					'class' => 'form-signin',
					])
			  }}
			<h2 class="form-signin-heading">Sign in</h2>
			<label for="inputEmail" class="sr-only">Email address</label>
			{{ Form::text(
					  'username',
					  '', [
						  'class'=>'form-control',
						  'placeholder'=>'Username',
						  'required',
						  'autofocus',
						  'id' => 'inputEmail'
						  ]
					  )
			  }}
			<label for="inputPassword" class="sr-only">Password</label>
			{{ Form::password(
					  'password',
					  [
						  'class'=>'form-control',
						  'placeholder'=>'Password',
						  'required',
						  'id' => 'inputPassword'
						  ]
					  )
			  }}
			<div class="checkbox">
				<label>
					{{ Form::checkbox('remember_me') }} Remember me
				</label>
			</div>

			<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

			{{ HTML::link(action('RemindersController@getRemind'), trans("Did you forget your username /password?")) }}
			{{ Form::close() }}
		</div>
	</div>
@overwrite

@section('jsFiles')
@parent
@stop
