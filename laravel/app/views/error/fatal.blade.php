@extends('error.layout')

@section('error-content')
<h1>Hmmm, that wasn't supposed to happen!</h1>
<p>An internal error has occurred. Details of the error have been sent to technical support.</p>
<p>Please contact us if the problem persists.</p>
<p>Sorry for the inconvenience.</p>

{{ HTML::link(url('/'), 'Click here to return to the home page.') }}
@stop