<?php

/*

CometChat
Copyright (c) 2012 Inscripts

CometChat ('the Software') is a copyrighted work of authorship. Inscripts 
retains ownership of the Software and any copies of it, regardless of the 
form in which the copies may exist. This license is not a sale of the 
original Software or any copies.

By installing and using CometChat on your server, you agree to the following
terms and conditions. Such agreement is either on your own behalf or on behalf
of any corporate entity which employs you or which you represent
('Corporate Licensee'). In this Agreement, 'you' includes both the reader
and any Corporate Licensee and 'Inscripts' means Inscripts (I) Private Limited:

CometChat license grants you the right to run one instance (a single installation)
of the Software on one web server and one web site for each license purchased.
Each license may power one instance of the Software on one domain. For each 
installed instance of the Software, a separate license is required. 
The Software is licensed only to you. You may not rent, lease, sublicense, sell,
assign, pledge, transfer or otherwise dispose of the Software in any form, on
a temporary or permanent basis, without the prior written consent of Inscripts. 

The license is effective until terminated. You may terminate it
at any time by uninstalling the Software and destroying any copies in any form. 

The Software source code may be altered (at your risk) 

All Software copyright notices within the scripts must remain unchanged (and visible). 

The Software may not be used for anything that would represent or is associated
with an Intellectual Property violation, including, but not limited to, 
engaging in any activity that infringes or misappropriates the intellectual property
rights of others, including copyrights, trademarks, service marks, trade secrets, 
software piracy, and patents held by individuals, corporations, or other entities. 

If any of the terms of this Agreement are violated, Inscripts reserves the right 
to revoke the Software license at any time. 

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

foreach($_REQUEST as $key => $val){
	if ($key != 'message' && $key != 'statusmessage') {
		$val = str_replace('<','',str_replace('"','',str_replace("'",'',str_replace('>','',$val))));
		$_REQUEST[$key] =  $val;
		if(!empty($_POST[$key])){
			$_POST[$key] =  $val;
		}
		if(!empty($_GET[$key])){
			$_GET[$key] =  $val;
		}
	}
}

include_once (dirname(__FILE__).DIRECTORY_SEPARATOR."config.php");
include_once (dirname(__FILE__).DIRECTORY_SEPARATOR."cometchat_guests.php");
include_once (dirname(__FILE__).DIRECTORY_SEPARATOR."cometchat_shared.php");
include_once (dirname(__FILE__).DIRECTORY_SEPARATOR."php4functions.php");

if (USE_COMET == 1) {
	include_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'transports'.DIRECTORY_SEPARATOR.TRANSPORT.DIRECTORY_SEPARATOR.'config.php');
	include_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'transports'.DIRECTORY_SEPARATOR.TRANSPORT.DIRECTORY_SEPARATOR.'comet.php');
}

if (CROSS_DOMAIN == 1) {
	header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');
}

if (SET_SESSION_NAME != '') {
	session_name(SET_SESSION_NAME);
}

if (empty($_REQUEST['basedata'])) {
	$_REQUEST['basedata'] = 'null';
} else {
	if (CROSS_DOMAIN == 1 || (isset($_REQUEST['callbackfn']) && in_array($_REQUEST['callbackfn'],array('desktop','mobileapp')))) {
		if ($_REQUEST['basedata'] != 'null') {
			session_id(md5($_REQUEST['basedata']));
		}
	}
}

if (DO_NOT_START_SESSION != 1) {
	session_start();
}

if (get_magic_quotes_gpc() || (defined('FORCE_MAGIC_QUOTES') && FORCE_MAGIC_QUOTES == 1)) {
	$_GET = stripSlashesDeep($_GET);
	$_POST = stripSlashesDeep($_POST);
	$_REQUEST = stripSlashesDeep($_REQUEST);
	$_COOKIE = stripSlashesDeep($_COOKIE);
}

if (CROSS_DOMAIN == 1) {
	if (!empty($_REQUEST)) {
		foreach ($_REQUEST as $param => $value) {
			if (substr($param,0,7) == 'cookie_') {
				if ($value != 'null') {
					$_COOKIE[substr($param,7)] = $value;
				}
			}
		}
	}
}

if (!empty($_REQUEST['basedata'])) {
	$_SESSION['basedata'] = $_REQUEST['basedata'];
}

if(get_magic_quotes_runtime()) { 
    set_magic_quotes_runtime(false); 
} 


ini_set('log_errors', 'Off');
ini_set('display_errors','Off');

if (defined('ERROR_LOGGING') && ERROR_LOGGING == '1') { 
	error_reporting(E_ALL);
	ini_set('error_log', 'error.log');
	ini_set('log_errors', 'On');
}

if (defined('DEV_MODE') && DEV_MODE == '1') { 
	error_reporting(E_ALL);
	ini_set('display_errors','On');
}

$dbh = mysql_connect(DB_SERVER.':'.DB_PORT,DB_USERNAME,DB_PASSWORD);
if (!$dbh) {
	echo "<h3>Unable to connect to database. Please check details in configuration file.</h3>";
	exit();
}
mysql_selectdb(DB_NAME,$dbh);
mysql_query("SET NAMES utf8");
mysql_query("SET CHARACTER SET utf8");
mysql_query("SET COLLATION_CONNECTION = 'utf8_general_ci'");  


if(MEMCACHE == 1 && class_exists('memcache')) {
	$memcache = new memcache;
	$memcache->connect(MC_SERVER, MC_PORT);
} elseif (MEMCACHE == 3 && file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."MemcacheSASL.php")) {
	include_once (dirname(__FILE__).DIRECTORY_SEPARATOR."MemcacheSASL.php");
	$memcache = new MemcacheSASL;
	$memcache->addServer(MC_SERVER, MC_PORT);
	$memcache->setSaslAuthData(MC_USERNAME, MC_PASSWORD);
}

$_SESSION['guestMode'] = 0;
if(!isset($bannedUserIPs)) { $bannedUserIPs = array(); }
$userid = getUserID();

if ($guestsMode && ($userid == 0 || $userid > 10000000)) { 
	$userid = getGuestID();
}

if (empty($_SESSION['cometchat']['timedifference'])) {
	$_SESSION['cometchat']['timedifference'] = 0;
}

if (empty($_SESSION['token'])) {
	$_SESSION['token'] = sha1(microtime());
}
