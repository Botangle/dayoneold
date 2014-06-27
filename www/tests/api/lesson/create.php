<?php
// usage: php create.php | http --form POST app.botangle.dev/api/v1/lesson/create.xml 'Cookie:XDEBUG_SESSION=13355;' 'Accept:text/html,application/xhtml+xml,application/xml'

$data = array(
//    '_Token' => array(
//        'key' => '401995f868840c48910942974075c7907b74f69c', // CSRF protection possibly?
//    ),
    'Lesson' => array(
        'tutorname' => 'erikejf',
        'tutor'     => 4,
        'lesson_date'   => '2014-06-28',
        'lesson_time'   => '09:55',
        'duration'  => '.5',
        'subject'   => 'Test subject',
        'repet'     => 'Single lesson',
        'notes'     => 'This will be awesome!',
    )
);

$data = array(
    'data' => $data,
    '_method' => 'POST',
);

print http_build_query($data);
