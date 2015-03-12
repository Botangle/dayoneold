@extends('new.user.layout')

@section('page-content')
    <div class="row">
        <div class="col-md-12">
            {{ Former::open()
            ->method('POST')
            ->action(route('new.stream.create'))
            ->class('form-horizontal')
            }}

			<fieldset>
				<legend>
					Start Live Stream
				</legend>

					{{ Former::text('title')
					->addClass('textbox')
					->placeholder(trans('Title'))
					->label(trans('Title:'))
					->required()
					}}

					{{ Former::textarea('description')
					->addClass('textarea')
					->placeholder(trans('Description (140 characters max)'))
					->label(trans('Description:'))
					->required()
					}}

					{{ Former::actions(
					Former::submit(trans('Start My Stream'))
					->addClass('btn btn-primary')
					)->addClass('control-group')
					}}
			</fieldset>
            {{ Former::close() }}
        </div>
    </div>
@stop

@section('jsFiles')
@parent
@stop
