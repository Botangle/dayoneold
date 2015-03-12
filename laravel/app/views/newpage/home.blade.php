@extends('newlayout')

@section('head')
@parent
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
					<img src="http://placehold.it/450x250">
				</div>
				<div class="col-md-6">
					<div class="row">
						<h2 class="col-md-12">{{ $stream->title }}</h2>
					</div>
					<div class="row">
						<p class="col-md-12">{{ $stream->description }}</p>
					</div>
					<div class="row">
						<p class="col-md-12 text-muted">Broadcaster: {{ $stream->full_name }}, <a href="#">{{ $stream->twitter }}</a></p>
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
