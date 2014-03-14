<?php $link = mysql_connect('phelixinfosolution.com','phelixin_track','track123#');
    if (!$link)
    {
        die('Could not connect: ' . mysql_error());
    }
    echo 'Connected successfully';
    mysql_close($link);
    ?> 