<?php
//get the first name
$name = Input::get('name');
$email = Input::get ('email');
$subject = Input::get ('subject');
$message = Input::get ('message');
$date_time = date("F j, Y, g:i a");
$userIpAddress = Request::getClientIp();
?> 

<p>You have received a new message</p>
<br />
<p>
Name: <?php echo ($name); ?><br />
Email: <?php echo ($email);?><br />
Subject: <?php echo ($subject); ?><br />
Message: <?php echo ($message);?><br />
Date: <?php echo($date_time);?><br />
User IP address: <?php echo($userIpAddress);?><br />
</p>