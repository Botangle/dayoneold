<?php
App::uses('CroogoAppController', 'Croogo.Controller');

/**
 * Base Application Controller
 *
 * @package  Croogo
 * @link     http://www.croogo.org
 */
class AppController extends CroogoAppController {

    /**
     * Used to send back consistent API error messages for our XML API
     *
     * @param $code int
     * @param $message
     * @return CakeResponse
     */
    public function sendXmlError($code, $message)
    {
        $this->set('error', $message);
        $this->set('errorCode', $code);
        $this->set('_serialize', array('error'));
        return $this->render();
    }
}
