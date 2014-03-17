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

if ($p_<3) exit;

if ($_GET['action'] == 'request') {
	sendMessageTo($_REQUEST['to'],$whiteboard_language[2]." <a href='javascript:void(0);' onclick=\"javascript:jqcc.ccwhiteboard.accept('".$userid."','".$_REQUEST['id']."');\">".$whiteboard_language[3]."</a> ".$whiteboard_language[4]);

	sendSelfMessage($_REQUEST['to'],$whiteboard_language[5]);

	
	if (!empty($_GET['callback'])) {
		header('content-type: application/json; charset=utf-8');
		echo $_GET['callback'].'()';
	}

}

if ($_GET['action'] == 'accept') {
	sendMessageTo($_REQUEST['to'],$whiteboard_language[6]);
		
	if (!empty($_GET['callback'])) {
		header('content-type: application/json; charset=utf-8');
		echo $_GET['callback'].'()';
	}
}

if ($_GET['action'] == 'whiteboard') {
	
	$id = $_GET['id'];
	$type = 'whiteboard';

	if (!empty($_GET['chatroommode'])) {
		if (!empty($_GET['subaction'])) {
		sendChatroomMessage($_GET['id'],$whiteboard_language[7]." <a href='javascript:void(0);' onclick=\"javascript:jqcc.ccwhiteboard.accept('".$id."');\">".$whiteboard_language[8]."</a>");
		}
		$id .= "chatroom";
		
	}
	else
	{
		$id =  abs($userid*$userid*$userid-$id*$id*$id)."users";
	}
	ini_set('display_errors', 0);
	 




	$displayName = "Unknown".rand(0,999);
	$username = $displayName;

    $sql = getUserDetails($userid);

	if ($guestsMode && $userid >= 10000000) {
		$sql = getGuestDetails($userid);
	}

	$result = mysql_query($sql);
	
	if($row = mysql_fetch_array($result)) {
		
		if (function_exists('processName')) {
			$row['username'] = processName($row['username']);
		}

		$displayName = $row['username'];
		$username = $row['username'];
	}
    
    $role = 0;
	
	if (empty($hostAddress)) {
	
		echo "<div style='background:white;'>Please configure this plugin using administration centre before using.</div>"; exit;
	}
	
	if(!empty($port))
	{
		$connectUrl = "rtmp://" . $hostAddress .":".$port. "/" . $application;
	}
	else
	{
		$connectUrl = "rtmp://" . $hostAddress . "/" . $application;
	}
	$baseURL = str_replace('_','',str_replace(':','_', str_replace('.','_',str_replace('/','_',BASE_URL.$_SERVER['SERVER_NAME']))));
	$connectUrl = "{$connectUrl}/{$id}"."{$baseURL}";
	

echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
<title>{$whiteboard_language[0]}</title> 
<style>
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, font, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td {
	margin: 0;
	padding: 0;
	border: 0;
	outline: 0;
	font-weight: inherit;
	font-style: inherit;
	font-size: 100%;
	font-family: inherit;
	vertical-align: baseline;
    text-align: center;
}

html {
  height: 100%;
  overflow: hidden; /* Hides scrollbar in IE */
}

body {
  height: 100%;
  margin: 0;
  padding: 0;
}

#flashcontent {
  height: 100%;
}


</style>

  <script type="text/javascript" src="swfobject.js"></script>
        <script type="text/javascript">
            var swfVersionStr = "10.1.0";
            var xiSwfUrlStr = "playerProductInstall.swf";
            var flashvars = {connectUrl: "{$connectUrl}"};
            var params = {};
            params.quality = "high";
            params.bgcolor = "#000000";
            params.allowscriptaccess = "sameDomain";
            params.allowfullscreen = "true";
			params.wmode = "transparent";
            var attributes = {};
            attributes.id = "whiteboard";
            attributes.name = "whiteboard";
            attributes.align = "middle";
            swfobject.embedSWF(
                "whiteboard.swf", "flashContent", 
                "100%", "100%", 
                swfVersionStr, xiSwfUrlStr, 
                flashvars, params, attributes);
			swfobject.createCSS("#flashContent", "display:block;text-align:left;");
        </script>

</head>
<body>


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
}