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

if ($p_<1) exit;

if (!empty($_GET['action']) && !empty($_SESSION['cometchat']['report_rand']) && $_SESSION['cometchat']['report_rand'] == $_POST['rand']) {

unset($_SESSION['cometchat']['report_rand']);

$id = $_POST['id'];
$issue = $_POST['issue'];

$sql = getUserDetails($userid);

if ($guestsMode && $userid >= 10000000) {
	$sql = getGuestDetails($userid);
}

$query = mysql_query($sql);
if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }
$user = mysql_fetch_array($query);
if (function_exists('processName')) {
	$user['username'] = processName($user['username']);
}

$reporter = $user['username'];

$sql = getUserDetails($id);

if ($guestsMode && $id >= 10000000) {
	$sql = getGuestDetails($id);
}

$query = mysql_query($sql);
if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }
$user = mysql_fetch_array($query);
if (function_exists('processName')) {
	$user['username'] = processName($user['username']);
}

$log = '';
$filename = 'Conversation with '.$user['username'].' on '.date('M jS Y');

$messages = array();

getChatboxData($id);

$log .= 'Conversation with '.$user['username'].' ('.$id.') on '.date('M jS Y');
$log .= "\r\n-------------------------------------------------------\r\n\r\n";

foreach ($messages as $chat) {
	$chat['message'] = strip_tags($chat['message']);
	if ($chat['self'] == 1) {
		$log .= '('.date('g:iA', $chat['sent']).") ".$language[10].': '.$chat['message']."\r\n";
	} else {
		$log .= '('.date('g:iA', $chat['sent']).") ".$user['username'].': '.$chat['message']."\r\n";
	}
}

$to      = $reportEmail;
$subject = 'CometChat Incident Report';
$message = <<<EOD
Hello,

The following incident was reported by $reporter:

-------------------------------------------------------
$issue
-------------------------------------------------------

$log

EOD;

$headers = 'From: bounce@chat.com' . "\r\n" .
    'Reply-To: bounce@chat.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);

$embed = '';
$embedcss = '';
$close = "setTimeout('window.close()',2000);";

if (!empty($_GET['embed']) && $_GET['embed'] == 'web') { 
	$embed = 'web';
	$embedcss = 'embed';
	$close = "parent.closeCCPopup('report');";
}	

if (!empty($_GET['embed']) && $_GET['embed'] == 'desktop') { 
	$embed = 'desktop';
	$embedcss = 'embed';
	$close = "parentSandboxBridge.closeCCPopup('report');";
}

echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>{$report_language[0]} (closing)</title> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
<link type="text/css" rel="stylesheet" media="all" href="../../css.php?type=plugin&name=report" /> 

</head>
<body onload="{$close}">

<div  class="container">
<div  class="container_title {$embedcss}">{$report_language[3]}</div>

<div  class="container_body {$embedcss}">

<div class="report">{$report_language[4]}</div>

<div style="clear:both"></div>
</div>
</div>
</div>

</body>
</html>
EOD;

} else {

	$toId = $_GET['id'];

	$_SESSION['cometchat']['report_rand'] = rand(0,9999);


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
	
	if (isset($_REQUEST['callbackfn']) && $_REQUEST['callbackfn'] == 'mobileapp') {
		echo $_SESSION['cometchat']['report_rand'];	
	} else {
	
		echo <<<EOD
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html>
			<head>
				<title>{$report_language[0]}</title> 
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
				<link type="text/css" rel="stylesheet" media="all" href="../../css.php?type=plugin&name=report" /> 
			</head>
			<body>
				<form method="post" action="index.php?action=report&id={$toId}&embed={$embed}">
					<div class="container">
						<div class="container_title {$embedcss}">{$report_language[1]}</div>

						<div class="container_body {$embedcss}">
							<textarea id="reportbox" name="issue"></textarea><div style="clear:both"></div>
							<div>
								<div id="send">
									<input type="submit" value="{$report_language[2]}" class="reportbutton">
									<input type="hidden" value="{$toId}" name="id">
									<input type="hidden" value="{$_SESSION['cometchat']['report_rand']}" name="rand">
								</div>
							<div style="clear:both"></div>
							</div>
						</div>
					</div>
					</div>
				</form>
			</body>
		</html>
EOD;
	}
}