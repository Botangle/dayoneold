<?php

$newUsers = array();
foreach($userlist as $user) {
    $user = $user['User'];

    $newUsers[] = array(
        'id'                            => $user['id'],
        'firstname'                     => $user['name'],
        'lastname'                      => $user['lname'],
        'profilepic'                    => $this->UserXmlTransformer->transformProfilePic($user['profilepic']),
        'qualification'                 => $user['qualification'],
        'extracurricular_interests'     => $user['extracurricular_interests'],
    );
}

$newUsers = array(
    'users' => array(
        'user' => $newUsers,
    ),
);

$response = array(
    'topchart'   => $newUsers,
);

$response = array_change_key_case($response);

$xml = Xml::fromArray($response);

echo $xml->asXML();