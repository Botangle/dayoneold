@extends('user.layout')

@section('page-content')
    <div class="StaticPageRight-Block">
        <div class="PageLeft-Block">
            <p class="FontStyle20 color1">{{ trans("Active Lesson Proposals") }}</p>
        </div>

        <div class="PageLeft-Block">
            <p class="FontStyle20 color1">{{ trans("Upcoming Lessons") }}</p>
        </div>
        
        <div class="PageLeft-Block">
            <p class="FontStyle20 color1">{{ trans("Past Lessons") }}</p>
        </div>
    </div>

@stop
