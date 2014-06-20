<?php
/**
 * Base Application Controller
 *
 * @package  Croogo
 * @link     http://www.croogo.org
 */
class TestsController extends Controller {

    public function lbTest()
    {
        $this->autoRender = false;
        $this->layouts = false;

        if(!isset($_GET['accessCode']) || $_GET['accessCode'] != 'enoo4eiGohbaeh0Sahchei8Huis3ohk') {
            header("HTTP/1.0 404 Not Found");
            print "Things are broken";
            die;
        }

        try {
            @$connected = ConnectionManager::getDataSource('default');
            if (!$connected->isConnected()) {
                header("HTTP/1.0 404 Not Found");
                print "No db connection";
                die;
            }
        } catch(Exception $e) {
            header("HTTP/1.0 404 Not Found");
            print "No db connection";
            die;
        }

        print "Things are working";
        die;
    }

}
