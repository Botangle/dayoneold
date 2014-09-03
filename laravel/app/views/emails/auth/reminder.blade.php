<?php
//get the first name
$userIpAddress = Request::getClientIp();
?>
<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
        <p>Please visit this link to reset your password: {{ URL::to('password/reset', array($token)) }}</p>

        <p>This link will expire in {{ Config::get('auth.reminder.expire', 60) }} minutes.</p>

        <p>If you did not request a password reset, then please ignore this email.</p>

        <p>User IP address: {{$userIpAddress}}</p>

    </body>
</html>
