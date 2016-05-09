<?php
/**
 * Twiddla.php
 *
 * @author: Martyn
 * @adapted from code by David
 * Date: 3/27/14
 * Time: 6:55 PM
 */

class Twiddla {

    /**
     * Twiddla username
     * @var string
     */
    protected $username;

    /**
     * Twiddla password
     * @var string
     */
    protected $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getMeetingId()
    {
        // @TODO: shift to using SSL for this long-term?
        // @TODO: do we want to do any type of error checking in here?  currently, this is pretty bare bones ... :-/
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
