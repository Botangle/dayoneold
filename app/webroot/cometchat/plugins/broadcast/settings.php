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
	
	$curl = 0;
	$errorMsg = '';

	$rchkd = '';
	$fchkd = '';
	$ochkd = '';
	
	
	$hideFMSSettings = '';
	$hideRed5Settings = '';
	$hideOTASettings = '';
	

	$commonSettings = '';

	if ($videoPluginType == '2') {
		$ochkd = "selected";
		$hideFMSSettings = 'style="display:none;"';
		$commonSettings = 'display:none;';
		$errorMsg = "";
		if(!checkcURL()) {
			$curl = 1;
			$hideOTASettings = 'style="display:none;"';
			$errorMsg = "<h2 id='errormsg' style='font-size: 11px; color: rgb(255, 0, 0);'>cURL extension is disabled on your server. Please contact your webhost to enable it. cURL is required for CometChat Server.</h2>";
		}
	} elseif ($videoPluginType == '1') {
		$rchkd = "selected";
		$hideFMSSettings = 'style="display:none;"';
		$hideOTASettings = 'style="display:none;"';
		$errorMsg = "";
	} else { 
		$fchkd = "selected";
		$hideRed5Settings = 'style="display:none;"';
		$hideOTASettings = 'style="display:none;"';
		$errorMsg = "";
	}
	

	
	$message = "<h3 id='data'></h3>";

	
echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" language="javascript">
	
		$(document).ready(function() {
			var selected = $("#pluginTypeSelector :selected").val();
			var errorMsg = 0;
			if(selected=="0" || selected=="1") {
				$('h3').hide();
			} else {
				$('h3').show();
				$('#data').html('Make sure that the width and height are in the ratio 4:3. Default width is 350 and height is 262.');
			}
			resizeWindow();			
				
			$("#pluginTypeSelector").change(function() {
				var selected = $("#pluginTypeSelector :selected").val();
				if(selected=="1") {
					$(".element_fms").hide();
					$(".element_red5").show();
					$("#fmsSettings").show();
					$("#centernav").show();
					$("#centernavot").hide();
					$("#otaSettings").hide();
					$('h3').hide();
				} else if(selected=="2") {
					$("#fmsSettings").hide();
					$("#centernav").hide();
					if(errorMsg == {$curl}) {
						$("#otaSettings").show();
						$("#centernavot").show();
					} else {
						$('#errormsg').show();
					}
					$('h3').show();
					$('#data').html('Make sure that the width and height are in the ratio 4:3. Default width is 350 and height is 262.');
				} else if(selected=="0") {
					$(".element_fms").show();
					$(".element_red5").hide();
					$("#fmsSettings").show();
					$("#centernav").show();
					$("#centernavot").hide();
					$('h3').hide();
				}
				resizeWindow();
			});
			
			function resizeWindow() {
				window.resizeTo(($("form").width()+30), ($("form").height()+60));
			}
		});
	</script>

	$getstylesheet

</head>

<body style="float:left;">
	<form action="?module=dashboard&action=loadexternal&type=plugin&name=broadcast&process=true" method="post" style="float:left;">
	<div id="content" style="float:left; margin:0px;">
			<h2>Audio/Video Broadcast Settings</h2><br />
					{$message}
			<div style="margin-bottom:10px;">
					<div class="title">Use :</div>
					<div class="element" id="">
						<select name="videoPluginType" id="pluginTypeSelector">
							<option value="2" $ochkd>CometChat Server</option>							
							<option value="1" $rchkd>RED5 or FMS (RTMP)</option>
							<option value="0" $fchkd>FMS (RTMFP)</option>
						</select>
					</div>
					<div style="clear:both;padding:5px;"></div>
					{$errorMsg}
					<div id="otaSettings" $hideOTASettings>
						
					<div>
						<div id="centernavot" style="width:380px">
							<div class="title">Video Width:</div><div class="element"><input type="text" class="inputbox" name="vidWidth" value="$vidWidth"></div>
							<div style="clear:both;padding:5px;"></div>
							<div class="title">Video height:</div><div class="element"><input type="text" class="inputbox" name="vidHeight" value="$vidHeight"></div>
							<div style="clear:both;padding:5px;"></div>
						</div>
					</div>
				</div>

				<div id="centernav" style="width:380px; $commonSettings ">
					<div class="title">Maximum Participants:</div><div class="element"><input type="text" class="inputbox" name="maxP" value="$maxP"></div>
					<div style="clear:both;padding:5px;"></div>
					<div class="title">Quality:</div><div class="element"><input type="text" class="inputbox" name="quality" value="$quality"></div>
					<div style="clear:both;padding:5px;"></div>
				</div>
			
				<div id="fmsSettings" $hideFMSSettings>
					<div>
						<div id="centernav" style="width:380px">
							<div class="title">Connect URL:</div><div class="element_fms" $hideFMSSettings><input type="text" class="inputbox" name="connectUrl" value="$connectUrl"></div>
							<div class="element_red5" $hideRed5Settings><input type="text" class="inputbox" name="connectUrl" value="$connectUrl"></div>
							<div style="clear:both;padding:5px;"></div>
							<div class="title">Camera Width:</div><div class="element"><input type="text" class="inputbox" name="camWidth" value="$camWidth"></div>
							<div style="clear:both;padding:5px;"></div>
							<div class="title">Camera Height:</div><div class="element"><input type="text" class="inputbox" name="camHeight" value="$camHeight"></div>
							<div style="clear:both;padding:5px;"></div>
							<div class="title">Frames Per Second:</div><div class="element"><input type="text" class="inputbox" name="fps" value="$fps"></div>
							<div style="clear:both;padding:5px;"></div>
							<div class="title">Sound Quality:</div><div class="element"><input type="text" class="inputbox" name="soundQuality" value="$soundQuality"></div>
							<div style="clear:both;padding:5px;"></div>
						</div>
					</div>
				</div>

				<div style="clear:both;padding:5px;"></div>					
			</div>
			<div style="clear:both;padding:7.5px;"></div>
			<input type="submit" value="Update Settings" class="button">&nbsp;&nbsp;or <a href="javascript:window.close();">cancel or close</a>
	</div>
	</form>
</body>
</html>
EOD;
} else {
	
	$data = '';
	foreach ($_POST as $field => $value) {
		$data .= '$'.$field.' = \''.$value.'\';'."\r\n";
	}

	configeditor('SETTINGS',$data,0,dirname(__FILE__).DIRECTORY_SEPARATOR.'config.php');	
	header("Location:?module=dashboard&action=loadexternal&type=plugin&name=broadcast");
}