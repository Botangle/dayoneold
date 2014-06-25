<?php

$newUsers = array();
foreach($userlist as $user) {
    $user = $user['User'];
    $newUsers[] = array(
        'id'                            => $user['id'],
        'firstname'                     => $user['name'],
        'lastname'                      => $user['lname'],
//        @TODO: get this working so it's not hard-coded
        'profilepic'                    => 'https://www.botangle.com/images/botangle-default-pic.jpg',
        'qualification'                 => $user['qualification'],
        'extracurricular_interests'     => $user['extracurricular_interests'],
    );
}

$newUsers = array(
    'users' => $newUsers,
);

$response = array(
    'topchart'   => $newUsers,
);

$response = array_change_key_case($response);

$xml = Xml::fromArray($response);

echo $xml->asXML();