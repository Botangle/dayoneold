<?php

/*
 * This script establishes a MySQL Database connection to `phelixinfosolution.com`,
 * which is another web design company. It also exposes their password. Hmmm... that
 * could be fun...
 *
 * Anyway this file is useless and pegged for deletion.
 *
 * @deleteme
 *
 */

$link = mysql_connect('phelixinfosolution.com', 'phelixin_track', 'track123#');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully';
mysql_close($link);
?>