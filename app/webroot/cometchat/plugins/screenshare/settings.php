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

if (!defined('CCADMIN')) { echo "NO DICE"; exit; }

if (empty($_GET['process'])) {
	global $getstylesheet;
	require dirname(__FILE__).DIRECTORY_SEPARATOR.'config.php';
	
	$alchkd = '';
	$rchkd = '';

	if ($screensharePluginType == '0') {
		$rchkd = "selected";
	} else {
		$alchkd = "selected";
	}


echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
$getstylesheet
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" language="javascript">
	
		$(document).ready(function(){

			resizeWindow();
			var selected = $("#pluginTypeSelector :selected").val();
				if(selected=="0") {
					$('.red5').show();
					$('.addlive').hide();
				} else {
					$('.red5').hide();
					$('.addlive').show();
				}
				
				resizeWindow();
		});

		function resizeWindow()	{
				window.resizeTo(($("form").width()+35), ($("form").height()+65));
		}
</script>
</head>
<body>
<form action="?module=dashboard&action=loadexternal&type=plugin&name=screenshare&process=true" method="post">
<div id="content">
		<h2>ScreenShare Settings</h2>
		<h3 class="red5">Provide red5 server details</h3>
		<h3 class="addlive">Provide Height Width details for window size. Make sure that the width and height are in the ratio 16:9. Default width is 852 and height is 480.</h3>
		<div>
			<div class="title">Use :</div>
			<div class="element" id="">
				<select name="screensharePluginType" id="pluginTypeSelector">
					<option onclick="javascript:$('.red5').hide();$('.addlive').show();resizeWindow();" value="1" $alchkd>AddLive</option>
					<option onclick="javascript:$('.red5').show();$('.addlive').hide();resizeWindow();" value="0" $rchkd>RED5</option>
				</select>
			</div>
			<div style="clear:both;padding:3.5px;"></div>

			<div id="centernav" style="width:380px">
				<div class="addlive"><div>Don&#39;t have the API keys? <a href="https://developer.addlive.com/cometchat" target="_blank">Create a new AddLive account</a>.</div>
				<div style="clear:both;padding:5px;"></div></div>
				<div class="red5"><div class="title ">Host address:</div><div class="element"><input type="text" class="inputbox" name="hostAddress" value="$hostAddress"></div>
				<div style="clear:both;padding:5px;"></div></div>
				<div class="red5"><div class="title ">Rtmp port:</div><div class="element"><input type="text" class="inputbox" name="port" value="$port"></div>
				<div style="clear:both;padding:5px;"></div></div>
				<div class="red5"><div class="title">Application folder:</div><div class="element"><input type="text" class="inputbox" name="application" value="$application"></div>
				<div style="clear:both;padding:5px;"></div></div>
				<div class="title">Width:</div><div class="element"><input type="text" class="inputbox" name="scrWidth" value="$scrWidth"></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Height:</div><div class="element"><input type="text" class="inputbox" name="scrHeight" value="$scrHeight"></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="addlive"><div class="title">Application ID:</div><div class="element"><input type="text" class="inputbox" name="applicationid" value="$applicationid"></div>
				<div style="clear:both;padding:5px;"></div></div>
				<div class="addlive"><div class="title">Application Auth Secret key:</div><div class="element"><input type="text" class="inputbox" name="appAuthSecret" value="$appAuthSecret"></div>
				<div style="clear:both;padding:5px;"></div></div>
			</div>
		</div>
				
		<input type="submit" value="Update Settings" class="button">&nbsp;&nbsp;or <a href="javascript:window.close();">cancel or close</a>
</div>
</form>
</body>
EOD;
} else {
	
	$data = '';
	foreach ($_POST as $field => $value) {
		$data .= '$'.$field.' = \''.$value.'\';'."\r\n";
	}

	configeditor('SETTINGS',$data,0,dirname(__FILE__).DIRECTORY_SEPARATOR.'config.php');	
	header("Location:?module=dashboard&action=loadexternal&type=plugin&name=screenshare");
}