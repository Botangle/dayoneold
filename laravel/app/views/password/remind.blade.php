@extends('layout')

<!--Wrapper main-content Block Start Here-->
@section('content')
<div id="main-content">
  <div class="container">
    <div class="row-fluid">
      <div class="span12">
        <h2 class="page-title">Botangle Password Recovery</h2>
      </div>
    </div>
    <div class="row-fluid">
      <div class="span9 PageLeft-Block">
        <p class="FontStyle20">Did you forget your username or password?</p>
        <p>No problem, just fill in your email address below and we'll send you an email to reset your password!</p>
        <div class="Signup">
            {{ Form::open([
            'action' => 'RemindersController@postRemind',
            'class' => 'form-inline form-horizontal',
            ]) }}
         
          <div class="form-group span8" style="margin-left:0px;">
            <label class="sr-only" for="email">Email</label>
            {{ Form::text('email', '', ['class'=>'form-control textbox1','placeholder'=>'Your email: e.g. email@email.com']) }}
          </div>
          <div class="span2">
            <button type="submit" class="btn btn-primary">Reset my password</button>
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