<?php
/**
 * view.ctp.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 6/24/14
 * Time: 5:10 PM
 */
$this->set('_serialize', array('user', 'userRate', 'userRating', 'userReviews', 'userstatus'));

$role = $user['Role'];
$roles = array(
    'role' => $role,
);

ksort($role, SORT_NATURAL);



$user = $user['User'];

// handle our subjects the way we want them long-term
// we're going to split them apart and display them individually
$subjects = explode(',', $user['subject']);
$trimmed_subjects = array_map('trim', $subjects);
sort($trimmed_subjects);

$subjects = array();
foreach($trimmed_subjects as $subject) {
    if($subject != '') {
        $subjects[] = $subject;
    }
}

// handle prettying up our rates
if ($userRate['UserRate']['price_type'] == 'permin') {
    $userRatePrice = 'per min';
} else {
    $userRatePrice = "per hr";
}

$rate = '$' . $userRate['UserRate']['rate'] . ' ' . $userRatePrice;

// @TODO: get statuses working
$statuses = $userstatus;
$statuses = array(
    'status' => array(
        'info' => 'coming after my dinner',
    ),
);

// @TODO: get reviews sent in
$reviews = $userReviews;
$reviews = array(
    'review' => array(
        'also' => 'coming after my dinner',
    ),
);

$user = array(
    'id'                            => $user['id'],
    'username'                      => $user['username'],
    'firstname'                     => $user['name'],
    'lastname'                      => $user['lname'],
    'email'                         => $user['email'],
    'website'                       => $user['website'],
    'bio'                           => $user['bio'],
    'timezone'                      => 'UTC',
    'qualification'                 => $user['qualification'],
    'teaching_experience'           => $user['teaching_experience'],
    'extracurricular_interests'     => $user['extracurricular_interests'],
    'university'                    => $user['university'],
    'other_experience'              => $user['other_experience'],
    'expertise'                     => $user['expertise'],
    'aboutme'                       => $user['aboutme'],
    'is_online'                     => $user['is_online'],
    'is_featured'                   => $user['is_featured'],
    'created'                       => $user['created'],
    'updated'                       => $user['updated'],

    // @TODO: get this working so it's not hard-coded
    'profilepic'                    => 'https://www.botangle.com/images/botangle-default-pic.jpg',

    // all these are relations or fields that needed adjustments
    'rate'                          => $rate,
    'rating'                        => $userRating[0]['avg'],
    'reviews'  => $reviews,
    'statuses' => $statuses,

    'subjects' => array(
        'subject' => $subjects,
    ),
    'roles' => array(
        $roles,
    ),
);

$response = array(
    'user'   => $user,
);

$response = array_change_key_case($response);

$xml = Xml::fromArray($response);

echo $xml->asXML();
