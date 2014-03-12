<?php

/*!
* OpenTok PHP Library v0.90.0
* http://www.tokbox.com/
*
* Copyright 2010, TokBox, Inc.
*
* Date: November 05 14:50:00 2010
*/


class Session {

    private $sessionId;

    private $primaryMediaServer;

    private $sessionProperties;

    function __construct($sessionId, $primaryMediaServer, $properties) {
        $this->sessionId = $sessionId;
        $this->primaryMediaServer = $primaryMediaServer;
        $this->sessionProperties = $properties;
    }

    public function getSessionId() {
        return $this->sessionId;
    }

    public static function parseSession($sessionXml) {


    }
}
