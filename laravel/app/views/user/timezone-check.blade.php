@extends('user.layout')

@section('page-content')
    <h2 class="page-title">{{ trans('Timezone') }}</h2>
    <div class="StaticPageRight-Block">
        <div class="PageLeft-Block">
            <p class="FontStyle20 color1">{{ trans("Confirm Timezone") }}</p>

            {{ Form::open(array(
                'action' => 'UserController@postTimezoneChange',
                'class'=>'form-horizontal',
            )) }}

            {{ Form::hidden('id', $user->id) }}
            {{ Form::hidden('browserTimezone', $browserTimezone) }}

            <p>We have detected that your device's timezone is {{ $browserTimezone }}, which is
                different from your account's timezone.</p>

            <div class="row-fluid">
                {{ Form::button('Set to '. $browserTimezone, array(
                    'type' => 'submit', 'class' => 'btn btn-primary btn-small',
                )) }}
                @if (!empty($user->timezone))
                {{ Html::link(
                    route('user.my-account'),
                    trans('Leave as'). $user->timezone,
                    array('class' => 'btn btn-primary btn-small')
                ) }}
                @endif
                {{ Html::link(
                    route('user.timezone'),
                    trans('Choose another timezone'),
                    array('class' => 'btn btn-primary btn-small')
                ) }}

            </div>

            {{ Form::close() }}
        </div>
    </div>

@stop
