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

$data = explode(';',$_GET['tid']);
$_REQUEST['basedata'] = $data[1];

include dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."plugins.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
	include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
}

$data = explode(';',$_GET['tid']);
$_GET['tid'] = $data[0];
$_GET['embed'] = $data[2];

$randomImage = md5(rand(0,9999999999).time());

$file = fopen(dirname(__FILE__).DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.$randomImage.".jpg","w");

if (isset($GLOBALS["HTTP_RAW_POST_DATA"])) { 
    $jpg = $GLOBALS["HTTP_RAW_POST_DATA"]; 
    fwrite($file,$jpg);
    fclose($file);
} else {
	$inputSocket = fopen('php://input','rb');
	$jpg = stream_get_contents($inputSocket);
	fclose($inputSocket);
	fwrite($file,$jpg);
    fclose($file);
}

$linkToImage = BASE_URL."plugins/handwrite/uploads/".$randomImage.".jpg";

$text = '<a href="'.$linkToImage.'" target="_blank" style="display:inline-block;margin-bottom:3px;margin-top:3px;"><img src="'.$linkToImage.'" border="0" style="padding:0px;display: inline-block;border:1px solid #666;" height="90"></a>'; 

if (substr($_GET['tid'],0,1) == 'c') {
	$_GET['tid'] = substr($_GET['tid'],1);
	sendChatroomMessage($_GET['tid'],$handwrite_language[3]."<br/>$text");
} else {
	sendMessageTo($_GET['tid'],$handwrite_language[1]."<br/>$text");
	sendSelfMessage($_GET['tid'],$handwrite_language[2]."<br/>$text");
}

$embed = '';
$embedcss = '';
$close = "setTimeout('window.close()',2000);";

if (!empty($_GET['embed']) && $_GET['embed'] == 'web') { 
	$embed = 'web';
	$embedcss = 'embed';
	$close = "parent.closeCCPopup('handwrite');";
}	

if (!empty($_GET['embed']) && $_GET['embed'] == 'desktop') { 
	$embed = 'desktop';
	$embedcss = 'embed';
	$close = "parentSandboxBridge.closeCCPopup('handwrite');";
}

echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>{$handwrite_language[0]} (closing)</title> 
</head>
<body onload="{$close}">
</body>
</html>
EOD;
?>