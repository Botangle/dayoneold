<?php
include dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules.php";

$response = array();

$sql = ("select recentlist, highscorelist from cometchat_games where userid = '".mysql_real_escape_string($_REQUEST['uid'])."'");
$res = mysql_query($sql);

if($row = mysql_fetch_array($res)){
	$recentlist = $row['recentlist'];
	$highscorelist = $row['highscorelist'];
}

if($highscorelist){
	$explodedHighscorelist = explode(';',$highscorelist);
	foreach($explodedHighscorelist as $key => $val){
		$highscorelistElements=explode(',',$val);
		$highest[] = array('gn'=>$highscorelistElements[1],'sc'=>$highscorelistElements[2]);
	}
}

if($recentlist){
	$explodedRecentlist = explode(';',$recentlist);
	foreach($explodedRecentlist as $key => $val){
		$recentlistElements = explode(',',$val);
		$latest[] = array('gn'=>$recentlistElements[1],'sc'=>$recentlistElements[2]);
	}
}

$response['latest'] = $latest;
$response['highest'] = $highest;
echo json_encode($response);

?>