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

    /**
     * A private OpenTokSDK object, please don't adjust it directly
     *
     * @var OpenTokSDK
     */
    private $_apiObject;

    public function generateToken($session_id) {
        return $this->getApiObject()->generateToken($session_id);
    }

    /**
     * Returns an OpenTok session id to be used
     * Note: these don't expire, so we have plenty of time to use it
     *
     * @return string
     */
    public function generateSessionId()
    {
        $session = $this->getApiObject()->createSession();
        return $session->getSessionId();
    }

    /**
     * Handles loading of our OpenTokSDK object so we don't do it repeatedly
     * @return OpenTokSDK
     */
    private function getApiObject()
    {
        if(!isset($this->_apiObject)) {
            $this->_apiObject = new OpenTokSDK($this->apiKey, $this->apiSecret);
        }

        return $this->_apiObject;
    }
} 