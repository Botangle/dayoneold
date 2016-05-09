<?php
/**
 * OpenTok.php
 *
 * @author: Martyn
 * @adapted from code by David
 * Date: 3/27/14
 * Time: 5:34 PM
 */

class OpenTok {

    /**
     * API key from OpenTok
     * @var string
     */
    protected $apiKey;

    /**
     * API secret from OpenTok
     * @var string
     */
    protected $apiSecret;

    /**
     * A private OpenTok object, please don't adjust it directly
     *
     * @var OpenTok
     */
    private $_apiObject;

    public function  __construct($key, $secret)
    {
        $this->apiKey = $key;
        $this->apiSecret = $secret;
    }

    public function generateToken($session_id) {
        return $this->getApiObject()->generateToken($session_id);
    }

    /**
     * Returns an OpenTok session id to be used
     * Note: these don't expire, so we have plenty of time to use it
     *
     * @return string|boolean
     */
    public function generateSessionId()
    {
        try {
            $session = $this->getApiObject()->createSession();
            return $session->getSessionId();
        } catch(Exception $e) {
            Log::critical('OpenTok error', array('message' => $e->getMessage(),'code' => $e->getCode()));
            return false;
        }
    }

    /**
     * Handles loading of our OpenTok object so we don't do it repeatedly
     * @return OpenTok
     */
    private function getApiObject()
    {
        if(!isset($this->_apiObject)) {
            $this->_apiObject = new \OpenTok\OpenTok($this->apiKey, $this->apiSecret);
        }

        return $this->_apiObject;
    }
}
