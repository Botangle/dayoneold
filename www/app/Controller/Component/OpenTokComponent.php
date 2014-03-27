<?php
/**
 * OpenTokComponent.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 3/27/14
 * Time: 5:34 PM
 */

App::uses('Component', 'Controller');
class OpenTokComponent extends Component {

    /**
     * API key from OpenTok
     * @var string
     */
    public $apiKey;

    /**
     * API secret from OpenTok
     * @var string
     */
    public $apiSecret;

    public function generateToken($session_id) {
        $apiObj = new OpenTokSDK($this->apiKey, $this->apiSecret);
        return $apiObj->generateToken($session_id);
    }

} 