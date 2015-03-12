@extends('new.layout')

@section('content')
	<div class="row">
		{{--@include('new.user._sidebar')--}}

		{{--<div class="col-md-9">--}}
		<div class="col-md-12">
			@yield('page-content')
		</div>
	</div>
	<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

@overwrite
