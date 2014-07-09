<?php
// include the Composer autoloader
require_once dirname(__DIR__) . '/Vendor/autoload.php';

// adding in environment support for CakePHP
// http://bakery.cakephp.org/articles/stevena0/2010/08/29/use-different-configs-for-different-environments
$env = env('CAKE_ENV');
if (!$env){$env = 'production';}
$env = strtolower($env);

/**
 * CakePHP Debug Level:
 *
 * Production Mode:
 * 	0: No error messages, errors, or warnings shown. Flash messages redirect.
 *
 * Development Mode:
 * 	1: Errors and warnings shown, model caches refreshed, flash messages halted.
 * 	2: As in 1, but also with full debug messages and SQL output.
 *
 * In production mode, flash messages redirect after a time interval.
 * In development mode, you need to click the flash message to continue.
 */
if($env == "production") {
    Configure::write('debug', 0);

    // don't try to access the $_SERVER setup if we're running from the command line
    // http://stackoverflow.com/questions/343557/how-to-distinguish-command-line-and-web-server-invocation
    if(php_sapi_name() != 'cli') {
        $siteurl = "http://".$_SERVER['HTTP_HOST'];
        Configure::write('SiteUrl',$siteurl);
    }
}
if($env == "dev") {
    Configure::write('debug', 2);

    // don't try to access the $_SERVER setup if we're running from the command line
    // http://stackoverflow.com/questions/343557/how-to-distinguish-command-line-and-web-server-invocation
    if(php_sapi_name() != 'cli') {
        $siteurl = "http://".$_SERVER['HTTP_HOST'];
        Configure::write('SiteUrl',$siteurl);
    }
}

if(file_exists(__DIR__ . '/core-' . $env . '.php')) {
    require(__DIR__ . '/core-'.$env.'.php');
} else {
    // NOTE: we *don't* want to set these variables here, but we need them so new devs
    // can get going without them set.  These *should* be placed in another file (core-dev or core-production)
    // so they aren't a part of our Git repo
    $tokBoxApiKey       = '';
    $tokBoxApiSecret    = '';

    $twiddlaUsername    = '';
    $twiddlaPassword    = '';

    $stripeTestSecret           = '';
    $stripeTestPublishableKey   = '';
    $stripeTestClientId         = '';

    $stripeLiveSecret           = '';
    $stripeLivePublishableKey   = '';
    $stripeLiveClientId         = '';
}

// now import our Croogo stuff after we've already had a change to setup some other key items
if (file_exists(APP . 'Config' . DS . 'croogo.php')) {
    require APP . 'Config' . DS . 'croogo.php';
} else {
    if (!defined('LOG_ERROR')) {
        define('LOG_ERROR', LOG_ERR);
    }

    Configure::write('Error', array(
            'handler' => 'ErrorHandler::handleError',
            'level' => E_ALL & ~E_DEPRECATED,
            'trace' => true
        ));

    Configure::write('Exception', array(
            'handler' => 'ErrorHandler::handleException',
            'renderer' => 'ExceptionRenderer',
            'log' => true
        ));

    Configure::write('Session', array(
            'defaults' => 'php',
            'ini' => array(
                'session.cookie_httponly' => 1
            )
        ));
}

Configure::write('OpenTokComponent', array(
        'apiKey' => (isset($tokBoxApiKey) ? $tokBoxApiKey : ''),
        'apiSecret' => (isset($tokBoxApiSecret) ? $tokBoxApiSecret : ''),
    ));

Configure::write('TwiddlaComponent', array(
        'username' => (isset($twiddlaUsername) ? $twiddlaUsername : ''),
        'password' => (isset($twiddlaPassword) ? $twiddlaPassword : ''),
    ));