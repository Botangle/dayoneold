<?php
$date_time = date("F j, Y, g:i a");
$userIpAddress = Request::getClientIp();
?> 

<p>Request for new category:</p>
<p>
Username: {{ Auth::user()->username }}<br>
Requested Categories: {{ $request }}</p>

<p></p>Date: {{ $date_time }}<br>
User IP address: {{ $userIpAddress }}
</p>