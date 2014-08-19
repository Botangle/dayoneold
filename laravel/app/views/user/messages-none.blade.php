@extends('user.layout')

@section('head')
@parent
@stop

@section('page-content')
<div class="row-fluid">
    <div class="span9">
        <h2 class="page-title">{{ trans("Messages") }}</h2>
        <div class="StaticPageRight-Block">
            <div class="PageLeft-Block">
                <p>You haven't sent any messages yet, so there is nothing here to see :-)</p>
                <p>To start sending folks messages, browse through the people in
                    {{ Html::link(route('users.topcharts'), 'Top Charts') }} and
                    {{ Html::link(route('categories.index'), 'Categories') }}
                    and message someone there!</p>
            </div>
        </div>
    </div>
</div>


@stop

@section('jsFiles')
@parent
@stop