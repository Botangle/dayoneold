@extends('page.layout')

@section('page-title')
{{{ trans('Latest News') }}}
@stop

@section('content')
@foreach ($articles as $article)
<div class="PageLeft-Block">
    @include('news.article', array('news' => $article))
</div>
@endforeach
@stop