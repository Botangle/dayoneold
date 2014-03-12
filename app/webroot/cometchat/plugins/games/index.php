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
include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
	include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
}

if (empty($_GET['action'])) {

$toId = $_GET['id'];
$baseData = $_REQUEST['basedata'];

$embed = '';
$embedcss = '';

if (!empty($_GET['embed']) && $_GET['embed'] == 'web') { 
	$embed = 'web';
	$embedcss = 'embed';
}	

if (!empty($_GET['embed']) && $_GET['embed'] == 'desktop') { 
	$embed = 'desktop';
	$embedcss = 'embed';
}

echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>{$games_language[1]}</title> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
<link type="text/css" rel="stylesheet" media="all" href="../../css.php?type=plugin&name=games" /> 

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script>
$(document).ready(function() {
	$("li").click(function() {
		var info = $(this).attr('id').split(',');
		var gameId = info[0];
		var width = info[1];
		location.href = 'index.php?action=request&basedata={$baseData}&toId={$toId}&gameId='+gameId+'&gameWidth='+width+'&embed={$embed}';
	});
});

</script>

</head>
<body>
<div class="container">
<div class="container_title {$embedcss}">{$games_language[2]}</div>

<div class="container_body {$embedcss}">

<ul class="games">
	{$games_language[13]}
</ul>
<div style="clear:both"></div>
</div>
</div>
</div>

</body>
</html>
EOD;


} else {

if ($_GET['action'] == 'request') {
	$random_from = md5(getTimeStamp()+$userid+'from');
	$random_to = md5(getTimeStamp()+intval($_GET['toId'])+'to');
	$random_order = $random_from.','.$random_to;
	$toId = intval($_GET['toId']);
	$baseData = $_REQUEST['basedata'];

	$embed = '';
	$embedcss = '';
	$close = "setTimeout('window.close()',2000);";

	if (!empty($_GET['embed']) && $_GET['embed'] == 'web') { 
		$embed = 'web';
		$embedcss = 'embed';
		$close = "parent.closeCCPopup('games_init');";
	}	

	if (!empty($_GET['embed']) && $_GET['embed'] == 'desktop') { 
		$embed = 'desktop';
		$embedcss = 'embed';
		$close = "parentSandboxBridge.closeCCPopup('games_init');";
	}

	sendMessageTo(intval($_GET['toId']),$games_language[3]." <a href='javascript:void(0);' onclick=\"javascript:jqcc.ccgames.accept('".$userid."','".$random_from."','".$random_to."','".$random_order."','".intval($_GET['gameId'])."','".intval($_GET['gameWidth'])."');\">".$games_language[4]."</a>".$games_language[5]);

	sendSelfMessage(intval($_GET['toId']),$games_language[6]);

echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>{$games_language[7]} (closing)</title> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
<link type="text/css" rel="stylesheet" media="all" href="../../css.php?type=plugin&name=games" /> 
</head>
<body onload="{$close}">

<div  class="container">
<div  class="container_title {$embedcss}">{$games_language[8]}</div>

<div  class="container_body {$embedcss}">

<div class="games">{$games_language[9]}</div>

<div style="clear:both"></div>
</div>
</div>
</div>

</body>
</html>
EOD;

}

if ($_GET['action'] == 'accept') {
	sendMessageTo($_REQUEST['to'],$games_language[10]." <a href='javascript:void(0);' onclick=\"javascript:jqcc.ccgames.accept_fid('".$userid."','".$_REQUEST['tid']."','".$_REQUEST['fid']."','".$_REQUEST['rid']."','".$_REQUEST['gameId']."','".preg_replace('/[^a-zA-Z0-9]/', '', $_REQUEST['gameWidth'])."');\">".$games_language[11]."</a>");

	if (!empty($_GET['callback'])) {
		header('content-type: application/json; charset=utf-8');
		echo $_GET['callback'].'()';
	} 
}

if ($_GET['action'] == 'play') {

	$fid = $_GET['fid'];
	$tid = $_GET['tid'];
	$rid = $_GET['rid'];
	$gameid = intval($_GET['gameId']);
	$auth =  ($fid.$rid.'100'.$gameid.'fdd4605ba06214842e3caee695bd2787');

	$rid = urlencode($rid);

	global $userid;
	global $guestsMode;

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

	echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
<title>{$games_language[12]}</title>
<script language="javascript">AC_FL_RunContent = 0;</script>
<script src="js/AC_RunActiveContent.js" language="javascript"></script>
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

body{ overflow-x:hidden;overflow-y:hidden; }

</style>
</head>
<body bgcolor="#fff"> 

<iframe src="http://games.cometchat.com/channel_auth.asp?channel_id=27377&uid={$fid}&nick_name={$name}&method_type=matching&matching_uids={$rid}&matching_stake=100&matching_game_id={$gameid}&auth_sig={$auth}" height="710" width="1000" scrolling="no"></iframe>
</body>
</html>
EOD;
}

}