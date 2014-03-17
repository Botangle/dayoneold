<?php 
include dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules.php";

if($_REQUEST['params']['username']!="guest" && $userid < 10000000) {
	
	$recentlist = "";
	$highscorelist = "";
	$gameid = $_REQUEST['params']['gameID'];
	$gamename = $_REQUEST['gamename'];
	$gamescore = $_REQUEST['params']['score'];
	$gametime = time();
	

	$sql = ("select recentlist,highscorelist from cometchat_games where userid = '".mysql_real_escape_string($userid)."'");
	$res = mysql_query($sql);
	if($row = mysql_fetch_array($res)){
		$recentlist = $row['recentlist'];
		$highscorelist = $row['highscorelist'];
	}
	
	
	$flag = 1;
	if($highscorelist){
		$explodedHighscorelist = explode(';',$highscorelist);
		foreach($explodedHighscorelist as $key => $val){
			$highscorelistElements = explode(',',$val);
			if($highscorelistElements[0] == $gameid ){
				if($highscorelistElements[2] < $gamescore){
					$highscorelistElements[2] = $gamescore;
					$highscorelistElements[3] = $gametime;
				}
				$flag = 0;
			}
			$scores[] = $highscorelistElements[2];
			$newHighscores[] = array('gameid' => $highscorelistElements[0],'gamename' => $highscorelistElements[1],'gamescore' => $highscorelistElements[2],'playedon' => $highscorelistElements[3]);
		}
	}

	if($flag){
		$scores[] = $gamescore;
		$newHighscores[] = array('gameid' => $gameid,'gamename' => $gamename,'gamescore' => $gamescore,'playedon' => $gametime);
	}

	array_multisort($scores, SORT_DESC, $newHighscores);
	array_splice($newHighscores,5);

	foreach($newHighscores as $key => $val){
		$newHighscores[$key] = implode(',',$val);
	}
	$highscorelist = implode(';',$newHighscores);
 
	$flag = 1;
	if($recentlist){
		$explodedRecentlist = explode(';',$recentlist);
		foreach($explodedRecentlist as $key => $val){
			$recentlistElements = explode(',',$val);
			if($recentlistElements[0] == $gameid){
				$recentlistElements[2] = $gamescore;
				$recentlistElements[3] = $gametime;
				$flag = 0;
			}
			$gametimes[] = $recentlistElements[3];
			$newRecentgames[] =  array('gameid' => $recentlistElements[0],'gamename' => $recentlistElements[1],'gamescore' => $recentlistElements[2],'playedon' => $recentlistElements[3]);
		}
	}

	if($flag){
		$gametimes[] = $gametime;
		$newRecentgames[] = array('gameid' => $gameid,'gamename' => $gamename,'gamescore' => $gamescore,'playedon' => $gametime);
	}

	array_multisort($gametimes, SORT_DESC, $newRecentgames);
	array_splice($newRecentgames,5);

	foreach($newRecentgames as $key => $val){
		$newRecentgames[$key] = implode(',',$val);
	}

	$recentlist = implode(';',$newRecentgames);
	
	$sql = ("insert into cometchat_games (userid,score,games,recentlist,highscorelist) values ('".mysql_real_escape_string($userid)."','".mysql_real_escape_string($gamescore)."','1','".mysql_real_escape_string($recentlist)."','".mysql_real_escape_string($highscorelist)."') on duplicate key update games = games+1 , score= score+'".mysql_real_escape_string($gamescore)."', recentlist = '".mysql_real_escape_string($recentlist)."' ,highscorelist ='".mysql_real_escape_string($highscorelist)."'");
	mysql_query($sql);
}
?>