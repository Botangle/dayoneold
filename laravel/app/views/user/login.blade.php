@extends('layout')

<!--Wrapper main-content Block Start Here-->
@section('content')
<div id="main-content">
  <div class="container">
    <div class="row-fluid">
      <div class="span12">
        <h2 class="page-title">Botangle Sign In</h2>
      </div>
    </div>
    <div class="row-fluid">
      <div class="span9 PageLeft-Block">
        <p class="FontStyle20">Already a Botangle member?</p>
        <p>Please enter your Botangle username/password to access your Botangle account.</p>
        <div class="Signup">
            {{ Form::open([
            'url' => 'login',
            'class' => 'form-inline form-horizontal',
            ]) }}
         
          <div class="form-group span5" style="margin-left:0px;">
            <label class="sr-only" for="Username2">Username</label>
            {{ Form::text('username', '', ['class'=>'form-control textbox1','placeholder'=>'Username']) }}
          </div>
          <div class="form-group span5">
            <label class="sr-only" for="Password2">Password</label>
           {{ Form::password('password', ['class'=>'form-control textbox1','placeholder'=>'Password']) }}
          </div>
         <div class="span2">
           <button type="submit" class="btn btn-primary">Login</button>
       </div>
       <div class="checkbox span12 mar0">
            <label>
              <input type="checkbox"><label>Remember me</label>
            </label>
      </div>
      <div class="span12 mar0">
          {{ HTML::link(route('user.forgot'), trans("Did you forget your username /password?")) }}

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