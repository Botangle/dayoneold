<?php
include dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";
include_once dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."chatrooms".DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";
include_once dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."chatrooms".DIRECTORY_SEPARATOR."config.php";

if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
	include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
}
if (file_exists (dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."chatrooms".DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
	include_once dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."chatrooms".DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta name="viewport" content="user-scalable=0,width=device-width, minimum-scale=1.0, maximum-scale=1.0, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $mobile_language[0];?></title>
<link type="text/css" href="<?php echo BASE_URL; ?>css.php?type=extension&name=mobile" rel="stylesheet" charset="utf-8">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo BASE_URL; ?>js.php?type=extension&name=mobile&callbackfn=mobilewebapp"></script>
<script>

    $("#buddy").live('pageshow', function() {
		document.title = "<?php echo $mobile_language[20]?>";
	});  
	
	$("#lobby").live('pageshow', function() {
		document.title = "<?php echo $mobile_language[21]?>";
	});
	
	$("#createChatroom").live('pageshow', function() {
		document.title = "<?php echo $mobile_language[22]?>";
	});
</script>
</head>
<body style="background:#f1f1f1;">
	<div data-role="page" id="buddy" style="background:inherit;">
		<div class="pageHeader" data-role="header" data-position="fixed">
			<h1><?php echo $mobile_language[18];?></h1>
		</div>
		<div data-role="content" id="wocontent">
			<div id="woscroll">
				<ul id="wolist" data-role="listview" data-filter="true" data-filter-placeholder="<?php echo $mobile_language[15];?>">
				</ul>
				<div id="endoftext"></div>
			</div>
		</div>
		<div data-role="footer" data-position="fixed" id="buddyFooter">
			<div data-role="navbar" class="nav-glyphish-example">
				<ul>
					<li>
						<a data-transition="none" data-icon="custom" id="buddy_link" class="chatlink" href="#buddy"><?php echo $mobile_language[20];?></a>
					</li>
					<li>
						<a data-transition="none" data-icon="custom" class="chatroomlink" href="#lobby"><?php echo $mobile_language[21];?></a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	
	
	<div data-role="page" id="chat" style="background:inherit;">
		<div id="chatheader" class="pageHeader" data-role="header" data-position="fixed">
			<a data-role="button" data-icon="back" data-iconpos="notext" onclick="javascript:loadChatboxReverse()"><?php echo $mobile_language[24]?></a>
			<h1></h1>
		</div>
		<div id="chatcontent" class="ui-content" style="">
			<div id="scroller">
			</div>
			<div id="endoftext"></div>
		</div>
		<div id="chatfooter" class="ui-footer ui-bar-d ui-footer-fixed slideup">
			<form id="chatmessageForm" onsubmit="#" data-ajax="false">
				<input id="chatmessage" type="text" name="chatmessage" placeholder="<?php echo $mobile_language[9];?>"/>
			</form>
		</div>
	</div>
	
	
	<div data-role="page" id="lobby" style="background:inherit;">
		<div class="pageHeader" data-role="header" data-position="fixed">
			<h1><?php echo $mobile_language[19];?></h1>
			<?php if ($allowUsers == '1'): ?>
			<a href="javascript:void(0);" data-role="button" data-icon="plus" data-iconpos="notext" class="ui-btn-right" onclick="javascript:createChatroom()"></a>
			<?php endif; ?>
		</div>
		<div data-role="content" id ="lobbycontent">
			<div id="lobbyscroller">
				<ul id="lobbylist" data-role="listview" data-filter="true" data-filter-placeholder="<?php echo $mobile_language[16];?>">
				</ul>
			</div>
		</div>
		<div data-role="footer" data-position="fixed" id="lobbyFooter">
			<div data-role="navbar" class="nav-glyphish-example">
				<ul>
					<li><a data-transition="none" data-direction="reverse" data-icon="custom" class="chatlink" href="#buddy"><?php echo $mobile_language[20];?></a></li>
					<li><a data-transition="none" data-icon="custom" id="lobby_link" class="chatroomlink" href="#lobby"><?php echo $mobile_language[21];?></a></li>
				</ul>
			</div>
		</div>
	</div>
	
	
	<div data-role="page" id="chatroom" style="background:inherit;">
		<div class="pageHeader" id="chatroomheader" data-role="header" data-position="fixed">
			<a data-role="button" data-icon="back" data-iconpos="notext" onclick="javascript:leaveChatroom();loadLobbyReverse();"><?php echo $mobile_language[24]?></a>
			<span id="chatroomName"></span>
			<a id="showUserButton" data-role="button" onclick="javascript:showChatroomUser();"><?php echo $mobile_language[23]?></a>
		</div>
		<div id="chatroomcontent" class="ui-content">
			<div id="crscroller">
			</div>
			<div id="endoftext"></div>
		</div>
		<div id="chatroomfooter" class="ui-footer ui-bar-d ui-footer-fixed slideup">
			<form id="chatroommessageForm" onsubmit="#" data-ajax="false">
				<input id="chatroommessage" type="text" name="chatmessage" placeholder="<?php echo $mobile_language[9];?>"/>
			</form>
		</div>
	</div>
	
	
	<div data-role="page" id="chatroomuser" style="background:inherit;">
		<div id="chatroomuserheader" class="pageHeader" data-role="header" data-position="fixed">
			<a data-role="button" data-icon="back" data-iconpos="notext" onclick="javascript:loadChatroomReverse();crscrollToBottom();"><?php echo $mobile_language[24]?></a>
			<span id="chatroomUserName" style="margin:0 auto;padding:0 10px;height:inherit;display:inline-block;"></span>
		</div>
		<div id="chatroomusercontent" data-role="content">
			<ul id="currentroom_users" data-role="listview">
			</ul>
		</div>
	</div>
	
	<div data-role="page" id="createChatroom" style="background:inherit;">
		<div class="pageHeader" data-role="header" data-position="fixed">
			<a data-role="button" data-icon="back" data-iconpos="notext" onclick="javascript:loadLobbyReverse()"><?php echo $mobile_language[24]?></a>
			<h1><?php echo $mobile_language[22];?></h1>
		</div>
		<div data-role="content" style="font-size:13px;">
			<form id="createChatroomForm"  onsubmit="return false" data-ajax="false">
				<div data-role="fieldcontain" style="padding-bottom:10px;">
					<label for="name"><?php echo $chatrooms_language[27];?></label>
					<input type="text" name="name" id="name" value="" />
				</div>
				<div data-role="fieldcontain" style="padding-bottom:10px;">
					<label for="type" class="select"><?php echo $chatrooms_language[28];?></label>
					<select name="type" id="type" data-mini="true" onchange="checkDropDown(this)">
					   <option value="0"><?php echo $chatrooms_language[29];?></option>
					   <option value="1"><?php echo $chatrooms_language[30];?></option>
					</select>
				</div>
				<div id="chatroomPassword" data-role="fieldcontain" style="padding-bottom:10px;">
					<label for="password"><?php echo $chatrooms_language[32];?></label>
					<input type="password" name="password" id="password" value="" />
				</div>
				<div id="createChatroomField" data-role="fieldcontain" style="padding-bottom:10px;">
					<button id="createChatroomButton" onclick="javascript:createChatroomSubmit()"><?php echo $chatrooms_language[33];?></button>
				</div>
			</form>
		</div>
	</div>
</body>
</html>