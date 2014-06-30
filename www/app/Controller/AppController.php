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
        $this->set('_rootNode', 'error');
        $this->set('_serialize', array('error', 'errorCode'));
        return $this->render();
    }

    /**
     * Sends back a JSON error in a format our jQuery system is expecting
     *
     * Pulled from: http://stackoverflow.com/questions/14945861/sending-correct-json-content-type-for-cakephp
     *
     * @param $message
     */
    protected function sendJsonError($message)
    {
        $this->sendJsonMessage('error', $message);
    }

    protected function sendJsonSuccess($message)
    {
        $this->sendJsonMessage('success', $message);
    }

    private function sendJsonMessage($type, $message)
    {
        $this->autoRender = false; // no view to render
        $this->response->type('json');

        $this->response->body(
            json_encode(
                array(
                    'status'    => $type,
                    'message' => $message,
                )
            )
        );

        $this->response->send();
        $this->_stop();
    }
}
