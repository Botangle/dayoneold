@extends('new.user.layout')

@section('page-content')
	<div class="row">
		<h2 class="col-md-12">Broadcasting Live</h2>
	</div>
    <div class="row">
        <div class="col-md-12 video-chat">
			@include('new.stream.live-screencaster', array('model' => $model))
        </div>
    </div>
	<div class="row">
		<div class="col-md-3 pull-right">
			{{ Former::open()
						->method('POST')
						->action(route('new.stream.stop'))
						->class('form-horizontal')
						}}

			{{ Form::hidden('id', $model->id) }}

			<button class="btn btn-lg btn-danger btn-block" type="submit">Stop Broadcasting</button>

			{{ Former::close() }}
		</div>
	</div>
@stop

@section('jsFiles')
@parent
@stop
