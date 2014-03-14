<?php
/**
 *SimpleTwitter - A simple twitter datasource for cakephp
 * @author Skyler Lewis (aka alairock) 2012
 * @link http://sixteenink.com
 * @link http://github.com/alairock
 **/
App::uses('HttpSocket', 'Network/Http');

class TwitterSource extends DataSource
{

    protected $_schema = array(
        'id' => array('type' => 'integer', 'key' => 'primary'),
        'text' => array('type' => 'string'),
        'created_at' => array('type' => 'string')
    );


    public function __construct($config)
    {
        parent::__construct($config);
        $this->sourceUrl = $this->config['sourceUrl'];
        $this->Http = new HttpSocket();
    }

    public function listSources()
    {
        return null;
    }

    public function describe($Model)
    {
        return $this->_schema;
    }

    public function read($model, $queryData = array())
    {

        //Override Config duing a 'find' with the following merge
        if (!empty($queryData['conditions'])) {
            $this->config = array_merge($this->config, $queryData['conditions']);
        }
        //go get tweets
        $results = json_decode($this->Http->get($this->sourceUrl, $this->config));
        //Show Only Tweet and TimeStamp
        if ($this->config['abridged'] == true) {
            $postsOnly = array();
            foreach ($results as $resultsValue) {
                $resultsValue = get_object_vars($resultsValue);
                array_push($postsOnly, array($resultsValue['text'], $resultsValue['created_at']));
            }
            return $postsOnly;
        } else {
            return $results;
        }

    }

}
