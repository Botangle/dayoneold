@extends('layout')

<!--Wrapper main-content Block Start Here-->
@section('content')
<div id="main-content">
  <div class="container">
    <div class="row-fluid">
      <div class="span12">
        <h2 class="page-title">Botangle Password Reset</h2>
      </div>
    </div>
    <div class="row-fluid">
      <div class="span9 PageLeft-Block">
        <div class="Signup">
            {{ Form::open([
            'action' => 'RemindersController@postReset',
            'class' => 'form-inline form-horizontal',
            ]) }}

            {{ Form::hidden('token', $token) }}
            <div class="row-fluid">
              <div class="form-group span8" style="margin-left:0px;">
                <label class="sr-only" for="email">Email</label>
                {{ Form::text('email', '', ['class'=>'form-control textbox1','placeholder'=>'Your email: e.g. email@email.com']) }}
              </div>
            </div>
            <br>
            <div class="row-fluid">
                <div class="form-group span5" style="margin-left:0px;">
                    <label class="sr-only" for="password">Password</label>
                    {{ Form::password('password', ['class'=>'form-control textbox1','placeholder'=>'Password']) }}
                </div>
                <div class="form-group span5">
                    <label class="sr-only" for="email">Confirm Password</label>
                    {{ Form::password('password_confirmation', ['class'=>'form-control textbox1','placeholder'=>'Confirm Password']) }}
                </div>
            </div>
            <br>
            <div class="row-fluid">
              <div class="span2">
                <button type="submit" class="btn btn-primary">Reset password</button>
              </div>
            </div>

            {{ Form::close() }}

        </div>
      </div>
      <div class="span3 PageRight-Block">
       <p class="FontStyle20">Not a member? Sign Up here</p>
        <p>Get a Free Account. Sign Up here.</p><br><br>
          {{ HTML::link(route('register.expert'), trans("Sign Up"), ['class' => 'btn btn-primary']) }}
      </div>
    </div>
    <!-- @end .row --> 
    
	@include('_partials.get-in-touch')
    
  </div>
  <!-- @end .container --> 
</div>
<!--Wrapper main-content Block End Here-->
@overwrite