<?php
/**
 * TwiddlaComponent.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 3/27/14
 * Time: 6:55 PM
 */

App::uses('Component', 'Controller');
class TwiddlaComponent extends Component {

    /**
     * Twiddla username
     * @var string
     */
    public $username;

    /**
     * Twiddla password
     * @var string
     */
    public $password;

    /**
     * @return mixed
     */
    public function getMeetingId()
    {
        // @TODO: shift to using SSL for this long-term?
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://www.twiddla.com/API/CreateMeeting.aspx");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "username={$this->username}&password={$this->password}&controltype=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);
        return $server_output;
    }
} 