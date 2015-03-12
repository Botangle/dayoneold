@extends('new.layout')

@section('head')
@parent
{{-- we register up here because things die if we reference an opentok library below without it --}}
{{ Html::script('//static.opentok.com/v2/js/opentok.min.js') }}
@stop

@section('header')
@overwrite

@section('breadcrumbs')
@show

@section('content')
<div class="row">
	<div class="col-md-12">
		@foreach ($liveStreams as $stream)
			<div class="row panel panel-default">
				<div class="panel-body">
				<div class="col-md-6">
					@include('new.page.home.watch-screencast', array('model' => $stream))
				</div>
				<div class="col-md-6">
					<div class="row">
						<h2 class="col-md-12">{{ $stream->title }}</h2>
					</div>
					<div class="row">
						<p class="col-md-12">{{ $stream->description }}</p>
					</div>
					<div class="row">
						<p class="col-md-12 text-muted">Broadcaster: {{ $stream->user->full_name }}</p>
							{{-- <a href="#">{{ $stream->twitter }}</a> --}}
					</div>
				</div>
				</div>
			</div>
		@endforeach
	</div>

</div>
@overwrite

@section('jsFiles')
@parent
@stop
