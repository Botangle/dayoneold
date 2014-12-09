@extends('error.layout')

@section('error-content')
<h1>Sorry, but that page doesn't currently exist.</h1>
<p>We're not sure how you've ended up here, but there's no page to be found.</p>

<p>{{ HTML::link(url('/'), 'Click here to return to the home page.') }}</p>
@stop