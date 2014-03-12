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
	
echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

$getstylesheet
<form action="?module=dashboard&action=loadexternal&type=extension&name=jabber&process=true" method="post">
<div id="content">
		<h2>Settings</h2>
		<h3>You can enable your users to connect to any one Jabber server. By default, we use Google Talk.</h3>
		<div>
			<div id="centernav" style="width:380px">
				<div class="title">Jabber Name:</div><div class="element"><input type="text" class="inputbox" name="jabberName" value="$jabberName"></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Jabber Server:</div><div class="element"><input type="text" class="inputbox" name="jabberServer" value="$jabberServer"></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Port:</div><div class="element"><input type="text" class="inputbox" name="jabberPort" value="$jabberPort"></div>
				<div style="clear:both;padding:5px;"></div>
			</div>
		</div>

		<h3 style="border-top: 1px solid #CCCCCC;margin-top:17px;padding-top:10px;">If you would like to use your own GTalk application for GTalk Connect please fill in the values below. If not leave them blank.</h3>
		<div>
			<div id="centernav" style="width:380px">
				<div class="title">GTalk App ID:</div><div class="element"><input type="text" class="inputbox" name="gtalkAppId" value="$gtalkAppId"></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">GTalk App Secret Key:</div><div class="element"><input type="text" class="inputbox" name="gtalkSecretKey" value="$gtalkSecretKey"></div>
				<div style="clear:both;padding:5px;"></div>
				<div style="clear:both;padding:5px;"></div>
			</div>
		</div>

		<h3 style="border-top: 1px solid #CCCCCC;margin-top:17px;padding-top:10px;">If you would like to use your own Facebook application for Facebook Connect please fill in the values below. If not leave them blank.</h3>
		<div>
			<div id="centernav" style="width:380px">
				<div class="title">Facebook App ID:</div><div class="element"><input type="text" class="inputbox" name="facebookAppId" value="$facebookAppId"></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Facebook App Secret Key:</div><div class="element"><input type="text" class="inputbox" name="facebookSecretKey" value="$facebookSecretKey"></div>
				<div style="clear:both;padding:5px;"></div>
				<div style="clear:both;padding:5px;"></div>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Update Settings" class="button">&nbsp;&nbsp;or <a href="javascript:window.close();">cancel or close</a>
</div>
</form>
EOD;
} else {
	
	$data = '';
	foreach ($_POST as $field => $value) {
		$data .= '$'.$field.' = \''.$value.'\';'."\r\n";
	}

	configeditor('SETTINGS',$data,0,dirname(__FILE__).DIRECTORY_SEPARATOR.'config.php');	
	header("Location:?module=dashboard&action=loadexternal&type=extension&name=jabber");
}