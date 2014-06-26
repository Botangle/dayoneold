<?php
App::uses('CakeResponse', 'Network');
/**
 * BotangleResponse.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 6/26/14
 * Time: 8:50 AM
 */

class BotangleResponse extends CakeResponse {

    /**
     * Attempts to fix a CakePHP Content-Length issue that may be causing problems with
     * our Amazon load balancer properly GZIPing our content.  We tell CakePHP to
     * not send back content length and let our web servers handle it instead.
     */
    protected function _setContentLength() {
        unset($this->_headers['Content-Length']);
        return;
    }

} 