@extends('error.layout')

@section('error-content')
<h1>Unauthorized access</h1>
@if(Auth::check())
    <p>You are not authorized to access {{ Request::url() }}</p>
    <p>{{ HTML::link(url('/'), 'Click here to return to the home page.') }}</p>
@else
    <p>This page is only available to people who are authorized to view it.
        If you can usually access it, then you probably need to login.</p>
    <p>{{ HTML::link(url('/'), 'Click here to return to the home page.') }}</p>
@endif
@stop