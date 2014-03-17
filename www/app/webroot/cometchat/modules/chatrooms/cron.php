<?php

if(isset($_REQUEST['url'])){
	include_once (dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules.php");
}

$auth = md5(ADMIN_USER).'$'.md5(ADMIN_PASS);

if($_REQUEST['auth'] == $auth ) {
	include_once (dirname(__FILE__).DIRECTORY_SEPARATOR."config.php");

	if (!empty($_REQUEST['cron']) &&in_array($_REQUEST['cron'], array('all','modules'))) {
		chatrooms();
		chatroommessages();
		chatroomsusers();
	} else {
		if(!empty($_REQUEST['cron']) && $_REQUEST['cron'] == "inactiverooms"){chatrooms();}
		if(!empty($_REQUEST['cron']) && $_REQUEST['cron'] == "chatroommessages"){chatroommessages();}
		if(!empty($_REQUEST['cron']) && $_REQUEST['cron'] == "inactiveusers"){chatroomsusers();}
	}
	
} else {
	echo 'Sorry you don`t have permission.';
}

function chatrooms() {
	global $chatroomTimeout;
	$sql = ("delete from cometchat_chatrooms where createdby <> 0 and (".getTimeStamp()."-lastactivity)> ".$chatroomTimeout * 100);
	$query = mysql_query($sql);
	if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }
	echo "All inactive chatrooms have been deleted.<br />";
}

function chatroommessages() {
	$sql = ("delete from cometchat_chatroommessages where (".getTimeStamp()."-sent)>10800");
	$query = mysql_query($sql);
	if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }
	echo "All chatroom messages older than 3 hours have been deleted.<br />";
}

function chatroomsusers() {
	$sql = ("delete from cometchat_chatrooms_users where (".getTimeStamp()."-lastactivity)>3600");
	$query = mysql_query($sql);
	if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }
	echo "All inactive chatroom users have been deleted.<br />";
}