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
        $subjects[] = array(
            'name' => $subject,
        );
    }
}

$rate = '';
if(isset($userRate['UserRate'])) {

// handle prettying up our rates
    if ($userRate['UserRate']['price_type'] == 'permin') {
        $userRatePrice = 'per min';
    } else {
        $userRatePrice = "per hr";
    }

    $rate = '$' . $userRate['UserRate']['rate'] . ' ' . $userRatePrice;
}

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

$user = $this->UserXmlTransformer->transformFullProfile($user);


// all these are relations or fields that needed adjustments
$user['rate']       = $rate;
$user['rating']     = $userRating[0]['avg'];
$user['reviews']    = $reviews;
$user['statuses']   = $statuses;

$user['subjects'] = array(
    'subject' => $subjects,
);

$user['roles'] = array(
    $roles,
);

$response = array(
    'user'   => $user,
);

$response = array_change_key_case($response);

$xml = Xml::fromArray($response);

echo $xml->asXML();
