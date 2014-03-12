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

include_once dirname(__FILE__).DIRECTORY_SEPARATOR."cometchat_init.php";

if (isset($_REQUEST['status'])) {

	if ($userid > 0) {
	
		$message = $_REQUEST['status'];

		$sql = ("insert into cometchat_status (userid,status) values ('".mysql_real_escape_string($userid)."','".mysql_real_escape_string(sanitize_core($message))."') on duplicate key update status = '".mysql_real_escape_string(sanitize_core($message))."'");
		$query = mysql_query($sql);
		if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }

		if ($message == 'offline') {
			$_SESSION['cometchat']['cometchat_sessionvars']['buddylist'] = 0;
		}
		
		if (function_exists('hooks_activityupdate')) {
			hooks_activityupdate($userid,$message);
		}
	}
	
	if (isset($_GET['callback'])) {
		header('content-type: application/json; charset=utf-8');
		echo $_GET['callback'].'(1)';
	} else {
		echo "1";
	}	
	exit(0);
}

if (isset($_GET['guestname']) && $userid > 0) {	
	$guestname = mysql_real_escape_string(sanitize_core($_GET['guestname']));
	
	$sql = ("UPDATE `cometchat_guests` SET name='".$guestname."' where id='".mysql_real_escape_string($userid)."'");
	$query = mysql_query($sql);
	if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }	
	
	$_SESSION['cometchat']['username'] =  $guestnamePrefix.'-'.$guestname;
	
	if (isset($_GET['callback'])) {
		header('content-type: application/json; charset=utf-8');
		echo $_GET['callback'].'(1)';
	} else {
		echo "1";
	}
	exit(0);
}

if (isset($_REQUEST['statusmessage'])) {
	$message = $_REQUEST['statusmessage'];

	if (empty($_SESSION['cometchat']['statusmessage']) || ($_SESSION['cometchat']['statusmessage'] != $message)) {
	
		$sql = ("insert into cometchat_status (userid,message) values ('".mysql_real_escape_string($userid)."','".mysql_real_escape_string(sanitize_core($message))."') on duplicate key update message = '".mysql_real_escape_string(sanitize_core($message))."'");
		$query = mysql_query($sql);
		if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }

		$_SESSION['cometchat']['statusmessage'] = $message;
		
		if (function_exists('hooks_statusupdate')) {
			hooks_statusupdate($userid,$message);
		}

	}
	
	if (isset($_GET['callback'])) {
		header('content-type: application/json; charset=utf-8');
		echo $_GET['callback'].'(1)';
	} else {
		echo "1";
	}

	exit(0);
}

if (isset($_REQUEST['to']) && isset($_REQUEST['message'])) {
	$to = $_REQUEST['to'];
	$message = $_REQUEST['message'];

	if (!empty($_REQUEST['callback'])) {
	    if (!empty($_SESSION['cometchat']['duplicates'][$_REQUEST['callback']])) {
	        exit;
	    }
	    $_SESSION['cometchat']['duplicates'][$_REQUEST['callback']] = 1;
	}

	if ($userid > 0) {
		
		if (!in_array($userid,$bannedUserIDs) && !in_array($_SERVER['REMOTE_ADDR'],$bannedUserIPs)) {

			if (in_array('block',$plugins)) {
			
				if($blockedUsers = getCache($cookiePrefix.'blocked_id_of_'.$userid, 3600)) {
					if(in_array($to,unserialize($blockedUsers))){
						return;
					}
				} else {
					$sql = ("select * from cometchat_block where (fromid = '".mysql_real_escape_string($to)."' and toid ='".mysql_real_escape_string($userid)."') OR (fromid = '".mysql_real_escape_string($userid)."' and toid ='".mysql_real_escape_string($to)."')");
					$query = mysql_query($sql);
					if (mysql_num_rows($query) > 0){
						return;
					}
				}
			}

			if (USE_COMET == 1) {
				$insertedid = getTimeStamp().rand(100,999);
				$key = KEY_A.KEY_B.KEY_C;
				$channel = md5($to.$key);
				if (function_exists('mcrypt_encrypt')) {
					$channel = md5(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $to, MCRYPT_MODE_CBC, md5(md5($key)))).$key);
				}
				$comet = new Comet(KEY_A,KEY_B);
				$info = $comet->publish(array(
					'channel' => $channel,
					'message' => array ( "from" => $userid, "message" => sanitize($message), "sent" => $insertedid, "self" => 0)
				));	
				
				if (defined('SAVE_LOGS') && SAVE_LOGS == 1) {
					$sql = ("insert into cometchat (cometchat.from,cometchat.to,cometchat.message,cometchat.sent,cometchat.read) values ('".mysql_real_escape_string($userid)."', '".mysql_real_escape_string($to)."','".mysql_real_escape_string(sanitize($message))."','".getTimeStamp()."',1)");
					$query = mysql_query($sql);
				}
 
			} else {

				$sql = ("insert into cometchat (cometchat.from,cometchat.to,cometchat.message,cometchat.sent,cometchat.read) values ('".mysql_real_escape_string($userid)."', '".mysql_real_escape_string($to)."','".mysql_real_escape_string(sanitize($message))."','".getTimeStamp()."',0)");
				$query = mysql_query($sql);
				$insertedid = mysql_insert_id();
				
				if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }			
			}

			if (empty($_SESSION['cometchat']['cometchat_user_'.$to])) {
				$_SESSION['cometchat']['cometchat_user_'.$to] = array();
			}

			$_SESSION['cometchat']['cometchat_user_'.$to][$insertedid] = array("id" => $insertedid, "from" => $to, "message" => sanitize($message), "self" => 1, "old" => 1, 'sent' => (getTimeStamp()+$_SESSION['cometchat']['timedifference']));
			
			
			if (isset($_GET['callback'])) {
				header('content-type: application/json; charset=utf-8');
				echo $_GET['callback'].'('.$insertedid.')';
			} else {
				echo $insertedid;
			}

		} else {
			$sql = ("insert into cometchat (cometchat.from,cometchat.to,cometchat.message,cometchat.sent,cometchat.read,cometchat.direction) values ('".mysql_real_escape_string($userid)."', '".mysql_real_escape_string($to)."','".mysql_real_escape_string(sanitize($bannedMessage))."','".getTimeStamp()."',0,2)");
			$query = mysql_query($sql);
			if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }

						
			if (isset($_GET['callback'])) {
				header('content-type: application/json; charset=utf-8');
				echo $_GET['callback'].'()';
			}
		}

		if (function_exists('hooks_message')) {
			hooks_message($userid,$to,$message);
		}
	}
	$_SESSION['cometchat']['duplicates'][$_REQUEST['callback']] = 1;
	exit(0);
}
