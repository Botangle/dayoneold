@extends('email')

@section('email-body')
<div>
    <p>Dagnabbit, we've got an error:</p>

    <h2>Variables</h2>
    <pre>{{{ print_r($vars, true) }}}</pre>

    <h2>Exception</h2>
    <pre>{{ $exception }}</pre>

</div>
@stop
