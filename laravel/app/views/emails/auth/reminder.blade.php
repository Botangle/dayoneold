<@extends('email')
<?php
//get the first name
$userIpAddress = Request::getClientIp();
?>

@section('email-body')
<p>Please visit this link to reset your password: {{ URL::to('password/reset', array($token)) }}</p>

<p>This link will expire in {{ Config::get('auth.reminder.expire', 60) }} minutes.</p>

<p>If you did not request a password reset, then please ignore this email.</p>

@stop