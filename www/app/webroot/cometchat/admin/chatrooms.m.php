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

$navigation = <<<EOD
	<div id="leftnav">
	<a href="?module=chatrooms">Chatrooms</a>
	<a href="?module=chatrooms&action=newchatroom">Add new chatroom</a>
	<a href="?module=chatrooms&action=moderator">Manage Moderators</a>
	</div>
EOD;

function index() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;
	
	require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'chatrooms'.DIRECTORY_SEPARATOR.'config.php';

	$sql = ("select * from cometchat_chatrooms order by lastactivity desc");
	$query = mysql_query($sql);
	if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }

	$chatroomlist = '';
	
	while ($chatroom = mysql_fetch_array($query)) {

		$type = '';
		
		if ($chatroom['type'] == '1') {
			$type = ' (password protected)';
		} else if ($chatroom['type'] == '2') {
			$type = ' (invitation only)';
		}

		$typeuser = '';

 
		if ($chatroom['createdby'] != 0) {
			$typeuser = ' (user created)';
		}

		$time = date('g:iA M dS', ($chatroom['lastactivity'] + $_SESSION['cometchat']['timedifference']));

		$css = '';
		$extra = '';

		if(getTimeStamp()-$chatroom['lastactivity'] > $chatroomTimeout * 100 ) {
			$css = 'background-color:#FFE2E2';
		} else if(getTimeStamp()-$chatroom['lastactivity'] > $chatroomTimeout && getTimeStamp()-$chatroom['lastactivity'] < $chatroomTimeout * 100 ) {
			$css = 'background-color:rgba(245, 255, 0, 0.09)';
		}
		if (empty($type)) {
			$extra = '<a href="../modules/chatrooms/index.php?id='.$chatroom['id'].'" target="_blank" style="margin-right:5px;"><img src="images/link.png" title="Direct link to chatroom"></a><a onclick="javascript:embed_link(\''.BASE_URL.'modules/chatrooms/index.php?id='.$chatroom['id'].'\',\'500\',\'300\');" href="#" style="margin-right:5px;"><img src="images/embed.png" title="Embed code for chatroom"></a>';
		}

		$chatroomlist .= '<li class="ui-state-default" style="'.$css.'"><span style="font-size:11px;float:left;margin-top:3px;margin-left:5px;max-width: 400px;text-overflow: ellipsis;overflow: hidden;">'.$chatroom['name'].' (ID: '. $chatroom['id'].')'.$type.$typeuser.'('.$time.')</span><p style="float:right"></p><span style="font-size:11px;float:right;margin-top:0px;margin-right:5px;">'.$extra.'<a href="?module=chatrooms&action=deletechatroom&data='.$chatroom['id'].'&token='.$_SESSION['token'].'"><img src="images/remove.png" title="Delete Chatroom"></a></span><div style="clear:both"></div></li>';
	}

	$body = <<<EOD
	$navigation

	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Chatrooms</h2>
		<h3>Displaying user created and permanent chatrooms</h3>

		<div>
			<ul id="modules_chatrooms">
				$chatroomlist
			</ul>
			<div id="rightnav" style="margin-top:5px">
				<h1>Tips</h1>		
				<ul id="modules_chatroomtips">
					<li>When you add a chatroom, it will be displayed live to all online chatroom users.</li>
				</ul>
				<ul id="modules_chatroomtips">
					<li><b>White</b> background rooms are active. <b>Yellow</b> background rooms are idle and not visible. <b>Red</b> background rooms are inactive.</li>
				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
	</div>

	<div style="clear:both"></div>

EOD;

	template();

}

function deletechatroom() {
	checktoken();

	if (!empty($_GET['data'])) {
		$sql = ("delete from cometchat_chatrooms where id = '".mysql_real_escape_string(sanitize_core($_GET['data']))."'");
		$query = mysql_query($sql);
	}

	header("Location:?module=chatrooms");
}

function newchatroom() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	$body = <<<EOD
	
	$navigation
	<form action="?module=chatrooms&action=newchatroomprocess" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>New chatroom</h2>
		<h3>You can add permanent chatrooms using the following form</h3>

		<div>
			<div id="centernav">
				<div class="title">Chatroom:</div><div class="element"><input type="text" class="inputbox" name="chatroom"></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Type:</div><div class="element"><select class="inputbox" name="type"><option value="0">Public room<option  value="1">Password protected room</select></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">If password protected, enter password:</div><div class="element"><input type="text" class="inputbox" name="ppassword"></div>
			</div>
			<div id="rightnav">
				<h1>Warning</h1>
				<ul id="modules_availablemodules">
					<li>Your chatrooms will be shown live to all online users. Double check before proceeding.</li>
 				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Add Chatroom" class="button">&nbsp;&nbsp;or <a href="?module=chatrooms">cancel</a>
		<input type="hidden" value="{$_SESSION['token']}" name="token">
	</div>

	<div style="clear:both"></div>

EOD;

	template();

}

function newchatroomprocess() {
	checktoken();

	$chatroom = $_POST['chatroom'];
	$type = $_POST['type'];
	$password = $_POST['ppassword'];

	if (!empty($password) && ($type == 1 || $type == 2)) {
		$password = sha1($password);
	} else {
		$password = '';
	}
	if(!empty($chatroom)) {
		$sql = ("insert into cometchat_chatrooms (name,createdby,lastactivity,password,type) values ('".mysql_real_escape_string(sanitize_core($chatroom))."', '0','".getTimeStamp()."','".mysql_real_escape_string($password)."','".mysql_real_escape_string($type)."')");
		$query = mysql_query($sql);
	}

	header( "Location: ?module=chatrooms" ); 
}

function moderator() {

	global $db;
	global $body;	
	global $trayicon;
	global $navigation;
	global $moderatorUserIDs;
	$usertable = TABLE_PREFIX.DB_USERTABLE;
	$usertable_username = DB_USERTABLE_NAME;
	$usertable_userid = DB_USERTABLE_USERID;	

	require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'chatrooms'.DIRECTORY_SEPARATOR.'config.php';	
	
	if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }

	$moderatorids = '';

		foreach ($moderatorUserIDs as $b) {
			$moderatorids .= $b.',';		
		}

	$body = <<<EOD
	$navigation
	
		<form action="?module=chatrooms&action=moderatorprocess" method="post">
		<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
			<h2>Manage Moderators</h2>
			<h3>Moderators can kick/ban users from any chatroom. Please enter their user IDs.</h3>
			<div>
				<div id="centernav">
					<div class="title">Moderator IDs:</div><div class="element"><input type="text" class="inputbox" name="moderatorids" value="$moderatorids"> <a href="?module=chatrooms&action=finduser">Don`t know ID?</a></div>
					<div style="float:left;margin-top: 81px;"><input type="submit" value="Modify" class="button">&nbsp;&nbsp;or <a href="?module=chatrooms&action=moderator">cancel</a></div>
				</div>
				<div id="rightnav" style="margin-top:5px">
					<h1>Tips</h1>
					<ul id="modules_chatroomtips">
					<li>Please use comma to separate IDs.</li>
					</ul>
					<ul id="modules_chatroomtips">
					<li>Moderators can kick/ban any user from any chatroom.</li>
					</ul>
				</div>
			</div>
			<div style="clear:both;padding:7.5px;"></div>
		</div>
	<div style="clear:both"></div>
EOD;
	template();

}

function moderatorprocess() {
	require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'chatrooms'.DIRECTORY_SEPARATOR.'config.php';			
	
		$_SESSION['cometchat']['error'] = 'Moderator list successfully modified.';
		$data = '$moderatorUserIDs = array('.$_POST['moderatorids'].');'."\r\n";
		configeditor('MODERATOR',$data,0,dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'chatrooms'.DIRECTORY_SEPARATOR.'config.php');	
	
	
	header("Location:?module=chatrooms&action=moderator");
}

function finduser() {
	global $db;
	global $body;	
	global $navigation;

	$body = <<<EOD
	$navigation
	<form action="?module=chatrooms&action=searchlogs" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Find User ID</h2>
		<h3>You can search by username.</h3>

		<div>
			<div id="centernav">
				<div class="title">Username:</div><div class="element"><input type="text" class="inputbox" name="susername"></div>
				<div style="clear:both;padding:5px;"></div>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Search Database" class="button">&nbsp;&nbsp;or <a href="?module=chatrooms&action=moderator">cancel</a>
		<input type="hidden" value="{$_SESSION['token']}" name="token">
	</div>

	<div style="clear:both"></div>

EOD;

	template();

}


function searchlogs() {
	checktoken();

	global $usertable_userid;
	global $usertable_username;
	global $usertable;
	global $navigation;
	global $body;
	
	$username = $_POST['susername'];

	if (empty($username)) {
		// Base 64 Encoded
		$username = 'Q293YXJkaWNlIGFza3MgdGhlIHF1ZXN0aW9uIC0gaXMgaXQgc2FmZT8NCkV4cGVkaWVuY3kgYXNrcyB0aGUgcXVlc3Rpb24gLSBpcyBpdCBwb2xpdGljPw0KVmFuaXR5IGFza3MgdGhlIHF1ZXN0aW9uIC0gaXMgaXQgcG9wdWxhcj8NCkJ1dCBjb25zY2llbmNlIGFza3MgdGhlIHF1ZXN0aW9uIC0gaXMgaXQgcmlnaHQ/DQpBbmQgdGhlcmUgY29tZXMgYSB0aW1lIHdoZW4gb25lIG11c3QgdGFrZSBhIHBvc2l0aW9uDQp0aGF0IGlzIG5laXRoZXIgc2FmZSwgbm9yIHBvbGl0aWMsIG5vciBwb3B1bGFyOw0KYnV0IG9uZSBtdXN0IHRha2UgaXQgYmVjYXVzZSBpdCBpcyByaWdodC4=';
	}

	$sql = ("select $usertable_userid id, $usertable_username username from $usertable where $usertable_username LIKE '%".mysql_real_escape_string(sanitize_core($username))."%'");
	$query = mysql_query($sql);

	$userslist = '';

	while ($user = mysql_fetch_array($query)) {
		if (function_exists('processName')) {
			$user['username'] = processName($user['username']);
		}

		$userslist .= '<li class="ui-state-default"><span style="font-size:11px;float:left;margin-top:2px;margin-left:5px;">'.$user['username'].' - '.$user['id'].'</span><div style="clear:both"></div></li>';
	}

	$body = <<<EOD
	$navigation

	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Search results</h2>
		<h3>Please find the user id next to each username. <a href="?module=chatrooms&action=finduser">Click here to search again</a></h3>

		<div>
			<ul id="modules_logs">
				$userslist
			</ul>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
	</div>

	<div style="clear:both"></div>

EOD;
	
	template();
}