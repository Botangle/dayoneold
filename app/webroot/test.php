<?php
$link = mysql_connect('64.37.52.138', 'phelixin_track', 'track123#');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully';
mysql_close($link);
?>