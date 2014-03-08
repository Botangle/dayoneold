#SimpleTwitter Datasource for CakePHP

##Description
This is a simple datasource for CakePHP to allow easy quick implementation of twitter.

This is a read-only datasource. You can only read tweets from a specified user. 

### Need more than read-only? OAuth, CRUD/BREAD*? See [CompleteTwitter](http://github.com/alairock/CompleteTwitter).
*Browse, Read, Edit, Add, Delete 

##Installation

1: Download and copy the TwitterSource.php file into your APP/Model/Datasource/ folder.

2: Add the following lines to your APP/Config/database.php file, modify to meet your requirements.

```php
public $twitterDb = array(
    'datasource' => 'TwitterSource', //Do not modify
    'sourceUrl' => 'http://api.twitter.com/1/statuses/user_timeline.json', //Do no modify
    
    /* Optional settings below */
    'screen_name' => 'alairock',
    'include_rts' => true, //recommended true
    'abridged' => true, // Results only tweet and timestamp, recommended true
    'count' => '10', // default twitter limit is 20, 
);
```

3: Create your model: APP/Model/Twitter.php

```php
<?php
class Twitter extends AppModel {

    public $useDbConfig = 'twitterDb';

}
```

##Usage

You can now use SimpleTwitter with your application. Below is an example of Usage in a new Controller/View

###Controller example:
Note: $uses pulls in our twitter model, which includes our datasource. This is required.

```php
public $uses = array('Twitter');
```

This is a simple find, using the default's and your custom configurations in the APP/Config/database.php file.
```php
public function index() {
    $this->set('twitter', $this->Twitter->find('all'));
}
```
You can also add conditions, which modify the configuration. Example: 

```php
$this->Twitter->find('all', array('conditions' => array('count' => 8, 'screen_name' => 'alairock')));
```
###View example:
The above controller passes the tweets with $this->set, the below view shows usage.

```php
<h1>Twitter Results</h1>
<?php pr($twitter); ?>
```

##Options 
These options can be enabled in the APP/Config/database.php or during your $this->Twitter->find method

* screen_name*: REQUIRED, this will be the twitter account your datasource will pull. 
* include_rts: this comes from twitter. True by default. Show retweets = true, Do not show = false
* abridged: this is my function I wrote. It will parse through the returned object and give you just the tweet and timestamp
* count: twitter returns 20 by default. You can return less or more with this option.


#License
This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by/3.0/deed.en_US">Creative Commons Attribution 3.0 Unported License</a>.

In simple terms. Use. Share. Modify. Good for commercial and non commercial uses. Mi casa es su casa.