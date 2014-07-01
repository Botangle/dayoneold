<?php
/**
 * add.ctp.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 6/30/14
 * Time: 5:10 PM
 */

if(isset($user)) {
    $user = $this->UserXmlTransformer->transformAuthUser($user);
    $user['message'] = $message;
} else {
    $user = array(
        'message' => "Please send things using POST",
    );
}
$response = array(
    'user'   => $user,
);

$response = array_change_key_case($response);

$xml = Xml::fromArray($response);

echo $xml->asXML();
