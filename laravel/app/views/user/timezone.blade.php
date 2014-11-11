@extends('user.layout')

@section('header')
@parent
{{ Html::script('/js/jstz-1.0.4.min.js') }}
@stop

@section('page-content')
<h2 class="page-title">{{ trans('Timezone') }}</h2>
<div class="StaticPageRight-Block">
    <div class="PageLeft-Block">
        <p class="FontStyle20 color1">{{ trans("Change Timezone") }}</p>

        {{ Former::open()
            ->url(action('UserController@postTimezoneChange'))
            ->class('form-horizontal')
        }}

        {{ Former::populate($user) }}

        {{ Former::hidden('id') }}

        @include('user.account.timezone-fields')

        <div class="row-fluid">
            {{ Former::actions(
            Former::submit(trans('Change Timezone'))
            ->addClass('btn btn-primary')
            ->name('change')
            )->addClass('control-group')
            }}
        </div>

        {{ Former::close() }}
    </div>
</div>
@stop

@section('jsFiles')
@parent
<script>
    var tz = jstz.determine(); // Determines the time zone of the browser client
    if (jQuery('#timezone').val() == ''){
        jQuery('#timezone').val(tz.name());
    }
</script>
@stop