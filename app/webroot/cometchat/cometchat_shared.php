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

function stripSlashesDeep($value) {
	$value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
	return $value;
}

function sanitize($text) {
	global $smileys;

	$text = sanitize_core($text);
	$text = $text." ";
	$text = str_replace('&amp;','&',$text);

	$search  = "/((?#Email)(?:\S+\@)?(?#Protocol)(?:(?:ht|f)tp(?:s?)\:\/\/|~\/|\/)?(?#Username:Password)(?:\w+:\w+@)?(?#Subdomains)(?:(?:[-\w]+\.)+(?#TopLevel Domains)(?:com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum|travel|a[cdefgilmnoqrstuwz]|b[abdefghijmnorstvwyz]|c[acdfghiklmnoruvxyz]|d[ejkmnoz]|e[ceghrst]|f[ijkmnor]|g[abdefghilmnpqrstuwy]|h[kmnrtu]|i[delmnoqrst]|j[emop]|k[eghimnprwyz]|l[abcikrstuvy]|m[acdghklmnopqrstuvwxyz]|n[acefgilopruz]|om|p[aefghklmnrstwy]|qa|r[eouw]|s[abcdeghijklmnortuvyz]|t[cdfghjkmnoprtvwz]|u[augkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw]|aero|arpa|biz|com|coop|edu|info|int|gov|mil|museum|name|net|org|pro))(?#Port)(?::[\d]{1,5})?(?#Directories)(?:(?:(?:\/(?:[-\w~!$+|.,=]|%[a-f\d]{2})+)+|\/)+|#)?(?#Query)(?:(?:\?(?:[-\w~!$+|\/.,*:]|%[a-f\d{2}])+=?(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)(?:&(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=?(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)*)*(?#Anchor)(?:#(?:[-\w~!$+|\/.,*:=]|%[a-f\d]{2})*)?)([^[:alpha:]]|\?)/i";
	
	if (DISABLE_LINKING != 1) { 
		$text = preg_replace_callback($search, "autolink", $text);  
	}

	if (DISABLE_SMILEYS != 1) { 
		foreach ($smileys as $pattern => $result) {
			$title = str_replace("-"," ",ucwords(preg_replace("/\.(.*)/","",$result)));
			$text = str_ireplace(str_replace('&amp;','&',htmlspecialchars($pattern, ENT_NOQUOTES)).' ','<img class="cometchat_smiley" height="16" width="16" src="'.BASE_URL.'images/smileys/'.$result.'" title="'.$title.'"> ',$text.' ');
		}
	}
	
	return trim($text);
}

function sanitize_core($text) {
	global $bannedWords;
	$text = htmlspecialchars($text, ENT_NOQUOTES);
	$text = str_replace("\n\r","\n",$text);
	$text = str_replace("\r\n","\n",$text);
	$text = str_replace("\n"," <br> ",$text);

	for ($i=0;$i < count($bannedWords);$i++) { 
		$text = str_ireplace(' '.$bannedWords[$i].' ',' '.$bannedWords[$i][0].str_repeat("*",strlen($bannedWords[$i])-1).' ',' '.$text.' ');      
	}
	$text = trim($text);
	return $text;
}

function autolink($matches) {

	$link = $matches[1];

	if (preg_match("/\@/",$matches[1])) {
		$text = "<a href=\"mailto: {$link}\">{$matches[0]}</a>"; 
	} else {
		if (!preg_match("/(file|gopher|news|nntp|telnet|http|ftp|https|ftps|sftp):\/\//",$matches[1])) {
			$link = "http://".$matches[1];
		}

		if (DISABLE_YOUTUBE != 1 && preg_match('#(?:<\>]+href=\")?(?:http://)?((?:[a-zA-Z]{1,4}\.)?youtube.com/(?:watch)?\?v=(.{11}?))[^"]*(?:\"[^\<\>]*>)?([^\<\>]*)(?:)?#',$link,$match)) {
			
			/* 
			
			// Bandwidth intensive function to fetch details about the YouTube video

			$contents = file_get_contents("http://gdata.youtube.com/feeds/api/videos/{$match[2]}?alt=json");

			$data = json_decode($contents);
			$title = $data->entry->title->{'$t'};

			if (strlen($title) > 50) {
				$title = substr($title,0,50)."...";
			}

			$description = substr($data->entry->content->{'$t'},0,100);
			$length = seconds2hms($data->entry->{'media$group'}->{'yt$duration'}->seconds);
			$rating = $data->entry->{'gd$rating'}->average; 
			
			*/

			$text = '<a href="'.$link.'" target="_blank">'.$link.'</a><br/><a href="'.$link.'" target="_blank" style="display:inline-block;margin-bottom:3px;margin-top:3px;"><img src="http://img.youtube.com/vi/'.$match[2].'/default.jpg" border="0" style="padding:0px;display: inline-block; width: 120px;height:90px;">
			<div style="margin-top:-30px;text-align: right;width:110px;margin-bottom:10px;">
			<img height="20" border="0" width="20" style="opacity: 0.88;" src="'.BASE_URL.'images/play.gif"/>
			</div></a>'; 

		} else {
			$text = $matches[1];

			if (strlen($matches[1]) > 30) {
				$left = substr($matches[1],0,22);
				$right = substr($matches[1],-5);
				$matches[1] = $left."...".$right;		
			}

			$text = "<a href=\"{$link}\" target=\"_blank\" title=\"{$text}\">{$matches[1]}</a>$matches[2]"; 
		}
	}


	return $text;
}


function seconds2hms ($sec, $padHours = true) {
	$hms = "";
	$hours = intval(intval($sec) / 3600); 
	if ($hours != 0) {
		$hms .= ($padHours) ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':' : $hours. ':';
	}

	$minutes = intval(($sec / 60) % 60); 
	$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';
	$seconds = intval($sec % 60); 
	$hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
	return $hms;
}

function sendChatroomMessage($to,$message) {
	global $userid;
	global $cookiePrefix;

	if (!empty($to) && !empty($message)) {
	
		if ($userid > 0) {

			if (USE_COMET == 1 && COMET_CHATROOMS == 1) {
				$comet = new Comet(KEY_A,KEY_B);

				if (empty($_SESSION['cometchat']['username'])) {
					$name = '';
					$sql = getUserDetails($userid);
					$result = mysql_query($sql);
					
					if ($row = mysql_fetch_array($result)) {				
						if (function_exists('processName')) {
							$row['username'] = processName($row['username']);
						}
						$name = $row['username'];
					}

					$_SESSION['cometchat']['username'] = $name;
				} else {
					$name = $_SESSION['cometchat']['username'];
				}

				$insertedid = getTimeStamp().rand(100,999);

				if (!empty($name)) {
					$info = $comet->publish(array(
						'channel' => md5('chatroom_'.$to.KEY_A.KEY_B.KEY_C),
						'message' => array ( "from" => $name, "message" => $message, "sent" => $insertedid)
					));
				}
				if (defined('SAVE_LOGS') && SAVE_LOGS == 1) {
					$sql = ("insert into cometchat_chatroommessages (userid,chatroomid,message,sent) values ('".mysql_real_escape_string($userid)."', '".mysql_real_escape_string($to)."','".mysql_real_escape_string($message)."','".getTimeStamp()."')");
					$query = mysql_query($sql);
				}
			} else {

				$sql = ("insert into cometchat_chatroommessages (userid,chatroomid,message,sent) values ('".mysql_real_escape_string($userid)."', '".mysql_real_escape_string($to)."','".mysql_real_escape_string($message)."','".getTimeStamp()."')");
				$query = mysql_query($sql);
				$insertedid = mysql_insert_id();
				
				if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }		
			}

		}
	}
}

function sendMessageTo($to,$message) {
	global $userid;
	global $cookiePrefix;

	if (!empty($_REQUEST['callback'])) {
	    if (!empty($_SESSION['cometchat']['duplicates'][$_REQUEST['callback']])) {
	        exit;
	    }
	    $_SESSION['cometchat']['duplicates'][$_REQUEST['callback']] = 1;
	}

	if (!empty($to) && !empty($message)) {
	
		if ($userid > 0) {

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
					'message' => array ( "from" => $userid, "message" => ($message), "sent" => $insertedid , "self" => 0)
				));
				if (defined('SAVE_LOGS') && SAVE_LOGS == 1) {
					$sql = ("insert into cometchat (cometchat.from,cometchat.to,cometchat.message,cometchat.sent,cometchat.read,cometchat.direction) values ('".mysql_real_escape_string($userid)."', '".mysql_real_escape_string($to)."','".mysql_real_escape_string($message)."','".getTimeStamp()."',1,1)");
					$query = mysql_query($sql);
				}

			} else {

				$sql = ("insert into cometchat (cometchat.from,cometchat.to,cometchat.message,cometchat.sent,cometchat.read,cometchat.direction) values ('".mysql_real_escape_string($userid)."', '".mysql_real_escape_string($to)."','".mysql_real_escape_string($message)."','".getTimeStamp()."',0,1)");
				$query = mysql_query($sql);
				if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }
			}
		}
	}
}

function sendSelfMessage($to,$message,$sessionMessage = '') {
	global $userid;
	global $cookiePrefix;
	
	if (!empty($_REQUEST['callback'])) {
	    if (!empty($_SESSION['cometchat']['duplicates'][$_REQUEST['callback']])) {
	        exit;
	    }
	    $_SESSION['cometchat']['duplicates'][$_REQUEST['callback']] = 1;
	}

	if (!empty($to) && !empty($message)) {
	
		if ($userid > 0) {

			if (USE_COMET == 1) {
				$insertedid = getTimeStamp().rand(100,999);
				$key = KEY_A.KEY_B.KEY_C;
				$channel = md5($userid.$key);
				if (function_exists('mcrypt_encrypt')) {
					$channel = md5(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $userid, MCRYPT_MODE_CBC, md5(md5($key)))).$key);	
				}
				$comet = new Comet(KEY_A,KEY_B);
				$info = $comet->publish(array(
					'channel' => $channel,
					'message' => array ( "from" => $to, "message" => ($message), "sent" => $insertedid, "self" => 1)
				));
				if (defined('SAVE_LOGS') && SAVE_LOGS == 1) {
					$sql = ("insert into cometchat (cometchat.from,cometchat.to,cometchat.message,cometchat.sent,cometchat.read, cometchat.direction) values ('".mysql_real_escape_string($userid)."', '".mysql_real_escape_string($to)."','".mysql_real_escape_string($message)."','".getTimeStamp()."',1,2)");
					$query = mysql_query($sql);
				}

			} else {

				$sql = ("insert into cometchat (cometchat.from,cometchat.to,cometchat.message,cometchat.sent,cometchat.read, cometchat.direction) values ('".mysql_real_escape_string($userid)."', '".mysql_real_escape_string($to)."','".mysql_real_escape_string($message)."','".getTimeStamp()."',0,2)");
				$query = mysql_query($sql);
				if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }

				$insertedid = mysql_insert_id();

				if (empty($_SESSION['cometchat']['cometchat_user_'.$to])) {
					$_SESSION['cometchat']['cometchat_user_'.$to] = array();
				}

				if (empty($sessionMessage)) {
					$sessionMessage = $message;
				}
				$_SESSION['cometchat']['cometchat_user_'.$to][$insertedid] = array("id" => $insertedid, "from" => $to, "message" => $sessionMessage, "self" => 1, "old" => 1, 'sent' => (getTimeStamp()+$_SESSION['cometchat']['timedifference']));
			}		
		}
	}
}

function sendAnnouncement($to,$message) {
	global $userid;

	if (!empty($to) && !empty($message)) {

		$sql = ("insert into cometchat_announcements (announcement,time,`to`) values ('".mysql_real_escape_string($message)."', '".getTimeStamp()."','".mysql_real_escape_string($to)."')");
		$query = mysql_query($sql);

		if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }
	}
}

function getChatboxData($id) {
	global $messages;
	global $userid;
	
	if (!empty($id) && USE_COMET == 1) {
		
		if (!empty($_SESSION['cometchat']['cometmessagesafter'])) {
			$key = KEY_A.KEY_B.KEY_C;
			$channel = md5($userid.$key);
			if (function_exists('mcrypt_encrypt')) {
				$channel = md5(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $userid, MCRYPT_MODE_CBC, md5(md5($key)))).$key);	
			}
			$comet = new Comet(KEY_A,KEY_B);
			$history = $comet->history(array(
			  'channel' => $channel,
			  'limit'   => COMET_HISTORY_LIMIT  
			));

			if (!empty($_SESSION['cometchat']['cometchat_user_'.$id])) {
				$messages = array_merge($messages,$_SESSION['cometchat']['cometchat_user_'.$id]);
			}

			$moremessages = array();

			$messagesafter = $_SESSION['cometchat']['cometmessagesafter'];

			if (!empty($_SESSION['cometchat']['cometchat_user_'.$id.'_clear']) && $_SESSION['cometchat']['cometchat_user_'.$id.'_clear']['timestamp'] > $_SESSION['cometchat']['cometmessagesafter']) {
				$messagesafter = $_SESSION['cometchat']['cometchat_user_'.$id.'_clear']['timestamp'];
			}

			if (!empty($history)) {
				foreach ($history as $key => $message) {
				
					if ($message['from'] == $id && $message['sent'] >= $messagesafter) {
						$moremessages[$message['sent']] = array("id" => $message['sent'], "from" => $message['from'], "message" => $message['message'], "self" => $message['self'], "old" => 1, 'sent' => (($message['sent']/1000)+$_SESSION['cometchat']['timedifference']));
					}
				}
			}

			$messages = array_merge($messages,$moremessages);

			usort($messages, 'comparetime');
		
		}

	} else {
		if (!empty($id) && !empty($_SESSION['cometchat']['cometchat_user_'.$id])) {
			$messages = array_merge($messages,$_SESSION['cometchat']['cometchat_user_'.$id]);
		}
	}
}

function comparetime($a, $b) { return strnatcmp($a['id'], $b['id']); } 

function text_translate($text, $from = 'en', $to = 'en') {
	include_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'realtimetranslate'.DIRECTORY_SEPARATOR.'translate.php');
	return translate_text($text,$from,$to);
} 
	  	  
function unescapeUTF8EscapeSeq($str) { 
	return preg_replace_callback("/\\\u([0-9a-f]{4})/i", create_function('$matches', 'return bin2utf8(hexdec($matches[1]));'), $str); 
} 

function bin2utf8($bin) { 
	if ($bin <= 0x7F) { 
		return chr($bin); 
	} elseif ($bin >= 0x80 && $bin <= 0x7FF) { 
		return pack("C*", 0xC0 | $bin >> 6, 0x80 | $bin & 0x3F); 
	} else if ($bin >= 0x800 && $bin <= 0xFFF) { 
		return pack("C*", 0xE0 | $bin >> 11, 0x80 | $bin >> 6 & 0x3F, 0x80 | $bin & 0x3F); 
	} else if ($bin >= 0x10000 && $bin <= 0x10FFFF) { 
		return pack("C*", 0xE0 | $bin >> 17, 0x80 | $bin >> 12 & 0x3F, 0x80 | $bin >> 6& 0x3F, 0x80 | $bin & 0x3F); 
	} 
}

function checkcURL($http = 0) {
	if (!function_exists('curl_init')) {
		return false;
	}        
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	if ($http == 0) {
		curl_setopt($ch, CURLOPT_URL, 'http://www.google.com');
	} else {
		curl_setopt($ch, CURLOPT_URL, 'https://msp.f-secure.com/web-test/common/test.html');
	}
	$data = curl_exec($ch);	
	curl_close($ch);
	if (empty($data)) {
		return false;
	}
	return true;
} 

function setCache($key,$contents,$timeout = 30) {
	removeCache($key);
	if (MEMCACHE == 0) {
		return ;
	} elseif (MEMCACHE == 1) {
		global $memcache;
		$memcache->set($key,$contents,MEMCACHE_COMPRESSED,$timeout);
	} elseif (MEMCACHE == 2) {
		$file= dirname(__FILE__).DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$key.'.txt';
		$handle = fopen($file, 'w');
		fwrite($handle, $contents);
		fclose($handle);
	} elseif (MEMCACHE == 3) {
		global $memcache;
		$memcache->add($key,$contents,$timeout);
	} 
}

function getCache($key, $timeout = 30) {
	$contents = false;

	if (MEMCACHE <> 0) {
		if (MEMCACHE == 1 || MEMCACHE == 3) {
			global $memcache;
			$contents = $memcache->get($key);
		} elseif (MEMCACHE == 2) {
			$file= dirname(__FILE__).DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$key.'.txt';
			if(file_exists($file) && (time() - filemtime($file)) < $timeout) {	
				$handle = fopen($file, "rb");
				$contents = fread($handle, filesize($file));
				fclose($handle);
			} else {
				removeCache($key);
			}
		}
	}
	if (empty($contents)) {
		return false;
	}
	return $contents;
}

function removeCache($key) {
	if (MEMCACHE == 0) {
		return ;
	} elseif (MEMCACHE == 1 || MEMCACHE == 3) {
		global $memcache;
		$memcache->delete($key);	
	} elseif (MEMCACHE == 2) {
		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$key.'.txt')) {
			unlink(dirname(__FILE__).DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$key.'.txt');
		}
	}
}
