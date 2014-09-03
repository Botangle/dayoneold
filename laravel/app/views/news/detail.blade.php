@extends('page.layout')

@section('page-title')
{{{ $news->title }}}
@stop

@section('breadcrumbs')
{{ Breadcrumbs::render('news.detail', $news) }}
@overwrite

@section('content')
<div class="PageLeft-Block">
    @include('news.article', array('news' => $news, 'noHeading' => true))
</div>
@stop