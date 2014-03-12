<?php

include dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules.php";

$response = array();
$sql = "select DISTINCT ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, ".DB_AVATARFIELD." avatar , score, games from ".TABLE_PREFIX.DB_USERTABLE." ".DB_AVATARTABLE." join cometchat_games on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_games.userid order by score desc";

$query = mysql_query($sql);

while($game = mysql_fetch_array($query)){
	
	if (function_exists('processName')) {
		$game['username'] = processName($game['username']);
	}
	$response[] = array('id' => $game['userid'],'n' => $game['username'],'a' => getAvatar($game['avatar']),'gc' => $game['games'],'tsc' => $game['score']);
}

echo json_encode($response);