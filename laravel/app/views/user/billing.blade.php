@extends('user.layout')

@section('page-content')
    <h2 class="page-title">{{ trans('Billing') }}</h2>
    <div class="StaticPageRight-Block">
        <div class="PageLeft-Block">
            <p class="FontStyle20 color1">{{ trans("Payment Type") }}</p>
            <p>In order for students to sign up for lessons, we'll need you to specify your rates as well. Please do so here.</p>
            <?php // TODO: complete the billing view ?>

        </div>
    </div>

@stop
