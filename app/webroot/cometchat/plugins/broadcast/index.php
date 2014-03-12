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

include dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."plugins.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."config.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
	include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
}

if (!file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR."broadcast".$rtl.".css")) {
	$theme = "default";
}

if ($p_<4) exit;

if(!checkcURL() && $videoPluginType == '2') {
	echo "<div style='background:white;'>Please contact your site administrator to configure this plugin.</div>";
	exit;
}

if($videoPluginType == '2') {
	require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'sdk'.DIRECTORY_SEPARATOR.'API_Config.php';
	require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'sdk'.DIRECTORY_SEPARATOR.'OpenTokSDK.php';
	
	$apiKey = '348501';
	$apiSecret = '1022308838584cb6eba1fd9548a64dc1f8439774';
	$apiServer = 'https://api.opentok.com/hl';
	$apiObj = new OpenTokSDK($apiKey, $apiSecret);
	if (empty($_SESSION['avchat_token'])) {
		$_SESSION['avchat_token'] = $apiObj->generate_token();
	}
}

if ($_REQUEST['action'] == 'request') {
	if($videoPluginType == '2') {
		$location = time();
		$session = $apiObj->create_session($location);
		$grp = $session->getSessionId();		
	} else {
		$grp = time();
	}
	
	sendMessageTo($_REQUEST['to'],$broadcast_language[2]." <a href='javascript:void(0);' onclick=\"javascript:jqcc.ccbroadcast.accept('".$userid."','".$grp."');\">".$broadcast_language[3]."</a> ".$broadcast_language[4]);

	sendSelfMessage($_REQUEST['to'],$broadcast_language[5]);

	if (!empty($_REQUEST['callback'])) {
		header('content-type: application/json; charset=utf-8');
		echo $_REQUEST['callback'].'()';
	}
}

if ($_REQUEST['action'] == 'call') {
	$grp = $_REQUEST['grp'];
}

if($videoPluginType < '2') {		
	$sender = $_REQUEST['type'];
	if (!empty($_REQUEST['chatroommode'])) {
		if (empty($_REQUEST['join'])) {
			sendChatroomMessage($grp,$broadcast_language[17]." <a href='javascript:void(0);' onclick=\"javascript:jqcc.ccbroadcast.join('".$_REQUEST['grp']."');\">".$broadcast_language[16]."</a>");
		}
	}
	ini_set('display_errors', 0);
	$mode = 3;
	$flashvariables = '{grp:"'.$grp.'",connectUrl: "'.$connectUrl.'",name:"",quality: "'. $quality. '",bandwidth: "'.$bandwidth.'",fps:"'.$fps.'",mode: "'.$mode.'",maxP: "'.$maxP.'",camWidth: "'.$camWidth.'",camHeight: "'.$camHeight.'",soundQuality: "'.$soundQuality.'",sender: "'.$sender.'"}';
	$file = '_fms';
	
	echo <<<EOD
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
		<title>{$broadcast_language[8]}</title> 
		<link href="css/fmsred5.css" type="text/css" rel="stylesheet" >
		<script type="text/javascript" src="swfobject.js"></script>
		<script type="text/javascript">
			var swfVersionStr = "10.1.0";
			var xiSwfUrlStr = "playerProductInstall.swf";
			var flashvars = {$flashvariables};
			var params = {};
			params.quality = "high";
			params.bgcolor = "#000000";
			params.allowscriptaccess = "sameDomain";
			params.allowfullscreen = "true";
			var attributes = {};
			attributes.id = "audiovideochat";
			attributes.name = "audiovideochat";
			attributes.align = "middle";
			swfobject.embedSWF(
				"audiovideochat{$file}.swf?v2.2", "flashContent", 
				"100%", "100%", 
				swfVersionStr, xiSwfUrlStr, 
				flashvars, params, attributes);
			swfobject.createCSS("#flashContent", "display:block;text-align:left;");

			function getFocus() {
				setTimeout('self.focus();',10000);
			}		
			window.onbeforeunload = function() {
				var AddCallbackExample = document.getElementById("audiovideochat_fms");
				AddCallbackExample.getUnsavedDataWarning();
			}			
		</script>
		</head>
		<body onblur="getFocus()"> 
		  <div id="flashContent">
					<p>
						To view this page ensure that Adobe Flash Player version 
						10.1.0 or greater is installed. 
					</p>
					<script type="text/javascript"> 
						var pageHost = ((document.location.protocol == "https:") ? "https://" :	"http://"); 
						document.write("<a href='http://www.adobe.com/go/getflashplayer'><img src='" 
										+ pageHost + "www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a>" ); 
					</script> 
				</div>	
		</body>
	</html>
EOD;
	} else {

	if (!empty($_REQUEST['chatroommode'])) {
		if (empty($_REQUEST['join'])) {
			sendChatroomMessage($grp,$broadcast_language[9]." <a href='javascript:void(0);' onclick=\"javascript:jqcc.ccbroadcast.join('".$grp."');\">".$broadcast_language[10]."</a>");
		}

		$sql = ("select vidsession from cometchat_chatrooms where id = '".mysql_real_escape_string($grp)."'");
		$query = mysql_query($sql);
		$chatroom = mysql_fetch_array($query);

		if (empty($chatroom['vidsession'])) {
			$session = $apiObj->create_session(time());
			$newsessionid = $session->getSessionId();

			$sql = ("update cometchat_chatrooms set  vidsession = '".mysql_real_escape_string($newsessionid)."' where id = '".mysql_real_escape_string($grp)."'");
			$query = mysql_query($sql);
			$grp = $newsessionid;
		} else {
			$grp = $chatroom['vidsession'];
		}
	}

	$name = "";
	$sql = getUserDetails($userid);
	if ($guestsMode && $userid >= 10000000) {
		$sql = getGuestDetails($userid);
	}

	$result = mysql_query($sql);
	if($row = mysql_fetch_array($result)) {		
		if (function_exists('processName')) {
			$row['username'] = processName($row['username']);
		}
		$name = $row['username'];
	}
	$name = urlencode($name);

	$baseUrl = BASE_URL;
	$embed = '';
	$embedcss = '';
	$resize = 'window.resizeTo(';
	$invitefunction = 'window.open';

	if (!empty($_REQUEST['embed']) && $_REQUEST['embed'] == 'web') {
		$embed = 'web';
		$resize = "parent.resizeCCPopup('broadcast',";
		$embedcss = 'embed';
		$invitefunction = 'parent.loadCCPopup';
	}
	if (!empty($_REQUEST['embed']) && $_REQUEST['embed'] == 'desktop') {
		$embed = 'desktop';
		$resize = "parentSandboxBridge.resizeCCPopupWindow('broadcast',";
		$embedcss = 'embed';
		$invitefunction = 'parentSandboxBridge.loadCCPopupWindow';
	}

	$publish = "";
	$control = "";
	
	if ($_REQUEST['type'] == 1) {
		$publish = "publish();";
		$control = '<div id="navigation">
			<div id="navigation_elements">
				<a href="#" onclick="javascript:inviteUser()" id="inviteLink"><img src="res/invite.png"></a>
				<a href="#" onclick="javascript:publish()" id="publishLink"><img src="res/turnonvideo.png"></a>
				<a href="#" onclick="javascript:unpublish()" id="unpublishLink"><img src="res/turnoffvideo.png"></a>
				<div style="clear:both"></div>
			</div>
			<div style="clear:both"></div>
		</div>';
	}

	echo <<<EOD
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
		<title>{$broadcast_language[8]}</title> 
		<link href="css/otchat.css" type="text/css" rel="stylesheet" >
		<script src="http://www.tokbox.com/v1.1/js/TB.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="../../js.php?type=plugin&name=broadcast&subtype=opentok" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript" charset="utf-8">
			var apiKey = {$apiKey};
			var sessionId = '{$grp}';
			var token = '{$_SESSION["avchat_token"]}';		
			var session;
			var publisher;
			var subscribers = {};
			var totalStreams = 0;
			var resize = "{$resize}";
			var name = "{$name}";
			var publishFunction = "{$publish}";
			var invitefunction = "{$invitefunction}";
			if (TB.checkSystemRequirements() != TB.HAS_REQUIREMENTS) {
				alert('Sorry, but your computer configuration does not meet minimum requirements for video chat.');
			} else {
				session = TB.initSession(sessionId);
				session.addEventListener('sessionConnected', sessionConnectedHandler);
				session.addEventListener('sessionDisconnected', sessionDisconnectedHandler);
				session.addEventListener('connectionCreated', connectionCreatedHandler);
				session.addEventListener('connectionDestroyed', connectionDestroyedHandler);
				session.addEventListener('streamCreated', streamCreatedHandler);
				session.addEventListener('streamDestroyed', streamDestroyedHandler);
			}
		</script>
		</head>
		<body>
			<div id="loading"><img src="res/init.png"></div>
			<div id="endcall"><img src="res/ended.png"></div>
			<div id="canvas">
				<img id="loadinggif" src="loading.gif" style="position: absolute; left: 35%; top: 32%;display:none;">
				<img id="noImg" src="res/no_img.png" style="position: absolute; left: 35%; top: 32%;">
				<div id="myCamera" class="publisherContainer"></div>
				<div id="otherCamera"></div>
				<div style="clear:both"></div>
			</div>
			{$control}
		</body>
		<script>			
			{$resize}300,330);
			connect();
			window.onload = function() { resizeWindow(); }
			window.onresize = function() { resizeWindow(); }		
		</script>
	</html>
EOD;
}