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
	<a href="?module=logs">One-on-one Chat</a>
	<a href="?module=logs&action=chatroomlog">Chatrooms</a>
	</div>
EOD;

function index() {
	global $db;
	global $body;	
	global $navigation;
	$overlay = '';

	if(USE_COMET == 1 && SAVE_LOGS == 0) {
		$overlay = <<<EOD

			<script>
			jQuery('#content').before('<div id="overlaymain" style="position:relative"></div>');
					var overlay = $('<div></div>')
						.attr('id','overlay')
						.css({
							'position':'absolute',
							'height':$('#content').innerHeight(),
							'width':$('#content').innerWidth(),
							'background-color':'#FFFFFF',
							'opacity':'0.9',
							'z-index':'99',
							'margin-left':'1px'
						})
						.appendTo('#overlaymain');
						$('<span>This feature is currently disabled. To activate this feature, please enable "Save Logs" setting in "Settings" &#8594; "CometService.</span>')
							.css({'z-index':' 9999',
							'color':'#000000',
							'font-size':'15px',
							'font-weight':'bold',
							'display':'block',
							'text-align':'center',
							'margin':'auto',
							'position':'absolute',
							'width':'100%',
							'top':'100px'
						}).appendTo('#overlaymain');
		</script>

EOD;
	}

	$body = <<<EOD
	{$navigation}
	<form action="?module=logs&action=searchlogs" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Search One-on-one Chat</h2>
		<h3>You can search by username or user ID. Please fill in atleast one field below.</h3>

		<div>
			<div id="centernav">
				<div class="title">User ID:</div><div class="element"><input type="text" class="inputbox" name="userid"></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Username:</div><div class="element"><input type="text" class="inputbox" name="susername"></div>
				<div style="clear:both;padding:5px;"></div>
			</div>
			<div id="rightnav">
				<h1>Warning</h1>
				<ul id="modules_logtips">
					<li>This feature is resource intensive. Please use judiciously.</li>
 				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Search Logs" class="button">&nbsp;&nbsp;or <a href="?module=logs">cancel</a>
		<input type="hidden" value="{$_SESSION['token']}" name="token">
	</div>

	<div style="clear:both"></div>
	{$overlay}

EOD;

	template();

}

function searchlogs() {

	global $usertable_userid;
	global $usertable_username;
	global $usertable;
	global $navigation;
	global $body;
	global $guestsMode;
	
	$userid = $_POST['userid'];
	$username = $_POST['susername'];
	
	if (empty($username)) {
		// Base 64 Encoded
		$username = 'Q293YXJkaWNlIGFza3MgdGhlIHF1ZXN0aW9uIC0gaXMgaXQgc2FmZT8NCkV4cGVkaWVuY3kgYXNrcyB0aGUgcXVlc3Rpb24gLSBpcyBpdCBwb2xpdGljPw0KVmFuaXR5IGFza3MgdGhlIHF1ZXN0aW9uIC0gaXMgaXQgcG9wdWxhcj8NCkJ1dCBjb25zY2llbmNlIGFza3MgdGhlIHF1ZXN0aW9uIC0gaXMgaXQgcmlnaHQ/DQpBbmQgdGhlcmUgY29tZXMgYSB0aW1lIHdoZW4gb25lIG11c3QgdGFrZSBhIHBvc2l0aW9uDQp0aGF0IGlzIG5laXRoZXIgc2FmZSwgbm9yIHBvbGl0aWMsIG5vciBwb3B1bGFyOw0KYnV0IG9uZSBtdXN0IHRha2UgaXQgYmVjYXVzZSBpdCBpcyByaWdodC4=';
	}

	$guestpart = "";

	if($guestsMode) {
		$guestpart = "union (select cometchat_guests.id, cometchat_guests.name username from cometchat_guests where cometchat_guests.name LIKE '%".mysql_real_escape_string(sanitize_core($username))."%' or cometchat_guests.id = '".mysql_real_escape_string(sanitize_core($userid))."')";	
	}
    
	$sql = ("(select ".$usertable_userid." id, ".$usertable_username." username from ".$usertable." where ".$usertable_username." LIKE '%".mysql_real_escape_string(sanitize_core($username))."%' or ".$usertable_userid." = '".mysql_real_escape_string(sanitize_core($userid))."') ".$guestpart." ");
	$query = mysql_query($sql);

	$userslist = '';
	$no_users = '';

	while ($user = mysql_fetch_array($query)) {
		if (function_exists('processName')) {
			$user['username'] = processName($user['username']);
		}

		$userslist .= '<li class="ui-state-default" onclick="javascript:logs_gotouser(\''.$user['id'].'\');"><span style="font-size:11px;float:left;margin-top:2px;margin-left:5px;">'.$user['username'].'</span><div style="clear:both"></div></li>';
	}

	if(!$userslist){
		$no_users .= '<div id="no_plugin" style="width: 480px;float: left;color: #333333;">No results found</div>';
	}

	$body = <<<EOD
	{$navigation}

	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Logs</h2>
		<h3>Please select a user from below. <a href="?module=logs">Click here to search again</a></h3>

		<div>
			<ul id="modules_logs">
				{$no_users}
				{$userslist}
			</ul>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
	</div>

	<div style="clear:both"></div>

EOD;
	
	template();
}

function viewuser() {
	
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;
	global $usertable_userid;
	global $usertable_username;
	global $usertable;
	global $guestsMode;
	global $guestnamePrefix;

	$userid = $_GET['data'];
	
	$guestpart = "";

	if($userid < '10000000') {
		$sql = ("select ".$usertable_username." username from ".$usertable." where ".$usertable_userid." = '".mysql_real_escape_string($userid)."'");
	} else {
		$sql = ("select concat('".$guestnamePrefix."',' ',name) username from cometchat_guests where cometchat_guests.id = '".mysql_real_escape_string($userid)."'");
	}
	$query = mysql_query($sql);
	$usern = mysql_fetch_array($query);

	if($guestsMode) {
		$guestpart = " union (select distinct(f.id) id, concat('".$guestnamePrefix."',' ',f.name) username  from cometchat m1, cometchat_guests f where (f.id = m1.from and m1.to = '".mysql_real_escape_string($userid)."') or (f.id = m1.to and m1.from = '".mysql_real_escape_string($userid)."'))";
	}
	
	$sql = ("(select distinct(f.".$usertable_userid.") id, f.".$usertable_username." username  from cometchat m1, ".$usertable." f where (f.".$usertable_userid." = m1.from and m1.to = '".mysql_real_escape_string($userid)."') or (f.".$usertable_userid." = m1.to and m1.from = '".mysql_real_escape_string($userid)."')) ".$guestpart." order by username asc");

	$query = mysql_query($sql); 

	$userslist = '';
	$no_users = '';

	if (function_exists('processName')) {
		$usern['username'] = processName($usern['username']);
	}

	while ($user = mysql_fetch_array($query)) {
		if (function_exists('processName')) {
			$user['username'] = processName($user['username']);
		}

		$userslist .= '<li class="ui-state-default" onclick="javascript:logs_gotouserb(\''.$userid.'\',\''.$user['id'].'\');"><span style="font-size:11px;float:left;margin-top:2px;margin-left:5px;">'.$user['username'].'</span><div style="clear:both"></div></li>';
	}

	if(!$userslist){
		$no_users .= '<div id="no_plugin" style="width: 480px;float: left;color: #333333;">No results found</div>';
	}

	$body = <<<EOD
	{$navigation}
	<form action="?module=logs&action=newlogprocess" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Log for {$usern['username']}</h2>
		<h3>Select a user between whom you want to view the conversation.</h3>

		<div>
			<ul id="modules_logs">
				{$no_users}
				{$userslist}
			</ul>
		</div>
	</div>

	<div style="clear:both"></div>

EOD;

	template();

}

function viewuserconversation() {
	
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;
	global $usertable_userid;
	global $usertable_username;
	global $usertable;
	global $guestsMode;
	global $guestnamePrefix;

	$userid = $_GET['data'];
	$userid2 = $_GET['data2'];

	if($userid < '10000000') {
		$sql = ("select ".$usertable_username." username from ".$usertable." where ".$usertable_userid." = '".mysql_real_escape_string($userid)."'");
	} else {
		$sql = ("select concat('".$guestnamePrefix."',' ',name) username from cometchat_guests where cometchat_guests.id = '".mysql_real_escape_string($userid)."'");
	}
	$query = mysql_query($sql); 
	$usern = mysql_fetch_array($query);

	if($userid2 < '10000000') {
		$sql = ("select ".$usertable_username." username from ".$usertable." where ".$usertable_userid." = '".mysql_real_escape_string($userid2)."'");
	} else {
		$sql = ("select concat('".$guestnamePrefix."',' ',name) username from cometchat_guests where cometchat_guests.id = '".mysql_real_escape_string($userid2)."'");
	}
	$query = mysql_query($sql); 
	$usern2 = mysql_fetch_array($query);

	$sql = ("(select m.*  from cometchat m where  (m.from = '".mysql_real_escape_string($userid)."' and m.to = '".mysql_real_escape_string($userid2)."') or (m.to = '".mysql_real_escape_string($userid)."' and m.from = '".mysql_real_escape_string($userid2)."'))
	order by id desc");

	$query = mysql_query($sql); 

	$userslist = '';

	while ($chat = mysql_fetch_array($query)) {
		$time = date('g:iA M dS', $chat['sent']);

		if ($userid == $chat['from']) {
			$dir = '>';
		} else {
			$dir = '<';
		}

		$userslist .= '<li class="ui-state-default"><span style="font-size:11px;float:left;margin-top:2px;margin-left:0px;width:10px;margin-right:10px;color:#fff;background-color:#333;padding:0px;-moz-border-radius:5px;-webkit-border-radius:5px;"><b>'.$dir.'</b></span><span style="font-size:11px;float:left;margin-top:2px;margin-left:5px;width:560px;">&nbsp; '.$chat['message'].'</span><span style="font-size:11px;float:right;width:100px;overflow:hidden;margin-top:2px;margin-left:10px;">'.$time.'</span><div style="clear:both"></div></li>';
	}

	if (function_exists('processName')) {
			$usern['username'] = processName($usern['username']);
			$usern2['username'] = processName($usern2['username']);
	}

	$body = <<<EOD
	{$navigation}
	<form action="?module=logs&action=newlogprocess" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Log between {$usern['username']} and {$usern2['username']}</h2>
		<h3>To see other conversations of {$usern['username']}, <a href="?module=logs&action=viewuser&data={$userid}">click here</a></h3>

		<div>
			<ul id="modules_logslong">
				{$userslist}
			</ul>
		</div>
	</div>

	<div style="clear:both"></div>

EOD;

	template();

}

function chatroomLog() {
	
	global $usertable_userid;
	global $usertable_username;
	global $usertable;
	global $navigation;
	global $body;
		
	$sql = ("select * from cometchat_chatrooms order by lastactivity desc");

	$query = mysql_query($sql);

	$chatroomlog = '';

	while ($chatroom = mysql_fetch_array($query)) {
		if (function_exists('processName')) {
			$user['username'] = processName($chatroom['name']);
		}

		$chatroomlog .= '<li class="ui-state-default" onclick="javascript:logs_gotochatroom(\''.$chatroom['id'].'\');"><span style="font-size:11px;float:left;margin-top:2px;margin-left:5px;">'.$chatroom['name'].'</span><span style="font-size:11px;float:left;margin-top:2px;margin-left:5px;">(ID:'.$chatroom['id'].')</span><div style="clear:both"></div></li>';
	}

	$body = <<<EOD
	{$navigation}

	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Chatrooms</h2>
		<h3>View chatrooms logs for active rooms.</h3>

		<div>
			<h5 style="font-size: 12px; margin-bottom: 5px;">Please select a chatroom:</h5>
			<ul id="modules_logs">
				{$chatroomlog}
			</ul>
		</div>
		<div id="rightnav">
			<h1>Warning</h1>
			<ul id="modules_logtips">
				<li>This feature is resource intensive. Please use judiciously.</li>
			</ul>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
	</div>

	<div style="clear:both"></div>

EOD;
	
	template();
}


function viewuserchatroomconversation() {
	
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;
	global $usertable_userid;
	global $usertable_username;
	global $usertable;
	global $guestsMode;
	global $guestnamePrefix;

	if($guestsMode) {	
		$usertable = "(select ".$usertable_userid.", ".$usertable_username."  from ".$usertable." union select id ".$usertable_userid.",concat('".$guestnamePrefix."',' ',name) ".$usertable_username." from cometchat_guests)";	
	}

	$chatroomid = $_GET['data'];

	$sql = ("select name chatroomname from cometchat_chatrooms where id = '".mysql_real_escape_string($chatroomid)."'");
	$query = mysql_query($sql); 
	$chatroomn = mysql_fetch_array($query);

	$sql = ("select cometchat_chatroommessages.*, f.".$usertable_username." username  from cometchat_chatroommessages join ".$usertable." f on cometchat_chatroommessages.userid = f.".$usertable_userid." where chatroomid = '".mysql_real_escape_string($chatroomid)."' order by id desc LIMIT 200");

	$query = mysql_query($sql); 
	$num = mysql_num_rows($query);

	$chatroomlog = '';

	while ($chat = mysql_fetch_array($query)) {

		if (function_exists('processName')) {
			$chatroomn['chatroomname'] = processName($chatroomn['chatroomname']);
		}
		$time = date('g:iA M dS', $chat['sent']);

		$chatroomlog .= '<li class="ui-state-default"><span style="font-size: 11px; float: left; margin-top: 2px; margin-left: 0px; width: 8em; text-overflow: ellipsis; white-space: nowrap; overflow: hidden; padding: 0px; text-align: center;">'.$chat["username"].'</span><span style="font-size:11px;float:left;margin-top:2px;margin-left:5px;width:495px;">&nbsp; '.$chat['message'].'</span><span style="font-size:11px;float:right;width:100px;overflow:hidden;margin-top:2px;margin-left:10px;">'.$time.'</span><div style="clear:both"></div></li>';
	}

	
	$body = <<<EOD
	{$navigation}
	<form action="?module=logs&action=newlogprocess" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Log of  in {$chatroomn['chatroomname']} chatroom</h2>
		<h3>To see other conversations of  in other chatrooms, <a href="?module=logs&action=chatroomlog">click here</a></h3>

		<div>
			<ul id="modules_logslong">
				{$chatroomlog}
			</ul>
		</div>
	</div>

	<div style="clear:both"></div>

EOD;

	template();
}