<?php
/**
 * add.ctp.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 6/30/14
 * Time: 5:10 PM
 */

$user = $this->UserXmlTransformer->transformAuthUser($user);
$user['message'] = $message;
$response = array(
    'user'   => $user,
);

$response = array_change_key_case($response);

$xml = Xml::fromArray($response);

echo $xml->asXML();
