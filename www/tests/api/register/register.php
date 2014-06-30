<?php
// usage: php register.php | http --form POST app.botangle.dev/api/v1/register.xml 'Cookie:XDEBUG_SESSION=13355;' 'Accept:text/html,application/xhtml+xml,application/xml'

$data = array(
    'RegisterStudentForm' => array(
        'username'      => 'jdoe',
        'firstname'     => 'John',
        'lastname'      => 'Doe',
        'password'      => 'testing',
        'password_confirmation'  => 'testing',
        'timezone'      => 'US/Denver',
        'terms'         => 1,
    )
);

$data = array(
    'data' => $data,
    '_method' => 'POST',
);

print http_build_query($data);
