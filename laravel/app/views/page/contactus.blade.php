@extends('page.layout')

@section('page-title')
    {{{ trans('Contact Us') }}}
@stop

@section('content')
<div class="PageLeft-Block">
	<p class="FontStyle20 text-center">Send us a Quick Message!</p>
	<form class="form-inline form-horizontal" role="form" action="/contact"  method="post">
		@foreach($errors->all('
			:message
			') as $message) {{ $message }} @endforeach

		<div class="row-fluid">
			<div class="form-group span6 ">
				<label class="sr-only" for="your_name">{{{ trans('Your Name') }}}</label>
				<input type="text" class="form-control textbox1 " name="name" id="your_name" placeholder="{{{ trans('Your Name') }}}" required="required">
			</div>
			<div class="form-group span6">
				<label class="sr-only" for="email">{{{ trans('Your Email Address') }}}</label>
				<input type="email" class="form-control textbox1" id="email" placeholder="{{{ trans('Your Email Address') }}}" name="email" required="required">
			</div>
		</div>
		<div class="row-fluid marT10">
			<div class="span12 form-group">
				<label class="sr-only" for="category">{{{ trans('Subject') }}}</label>
				<input type="text" class="form-control textbox1" id="category" placeholder="{{{ trans('Your Subject') }}}" required="required"  name="subject">
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12 form-group marT10">
				<label class="sr-only" for="message">{{{ trans('Error') }}}</label>
				<textarea id="select-subject" class="textarea" placeholder="{{{ trans('Your Message') }}}" rows="3" required="required"  name="message"></textarea>
			</div>
		</div>
		<div class="row-fluid marT10">
			<div class="span12 ">
				<button type="submit" class="btn btn-primary">{{{ trans('Submit') }}}</button>
			</div>
		</div>
	</form>
</div>
@stop