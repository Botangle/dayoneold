<?php

		include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
		} 

		foreach ($games_language as $i => $l) {
			$games_language[$i] = str_replace("'", "\'", $l);
		}
?>

/*
 * CometChat
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

(function($){   
  
	$.ccgames = (function () {

		var title = '<?php echo $games_language[0];?>';
		var lastcall = 0;

        return {

			getTitle: function() {
				return title;	
			},

			init: function (id) {
				baseUrl = $.cometchat.getBaseUrl();
				baseData = $.cometchat.getBaseData();
				loadCCPopup(baseUrl+'plugins/games/index.php?id='+id+'&basedata='+baseData, 'games_init',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=0, width=440,height=260",430,205,'<?php echo $games_language[2];?>'); 
			},

			accept: function (id,fid,tid,rid,gameId,gameWidth) {
				baseUrl = $.cometchat.getBaseUrl();
				baseData = $.cometchat.getBaseData();
                $.getJSON(baseUrl+'plugins/games/index.php?action=accept&callback=?', {to: id,fid: fid,tid: tid, rid: rid, gameId: gameId, gameWidth: gameWidth, basedata: baseData});
				loadCCPopup(baseUrl+'plugins/games/index.php?action=play&fid='+fid+'&tid='+tid+'&rid='+rid+'&gameId='+gameId+'&basedata='+baseData, 'games'+fid+''+tid,"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=0, width="+(gameWidth-30)+",height=600",gameWidth-28,600,'<?php echo $games_language[12];?>',1); 
			},

			accept_fid: function (id,fid,tid,rid,gameId,gameWidth) {
				baseUrl = $.cometchat.getBaseUrl();
				baseData = $.cometchat.getBaseData();
				loadCCPopup(baseUrl+'plugins/games/index.php?action=play&fid='+fid+'&tid='+tid+'&rid='+rid+'&gameId='+gameId+'&basedata='+baseData, 'games'+fid+''+tid,"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=0, width="+(gameWidth-30)+",height=600",gameWidth-28,600,'<?php echo $games_language[12];?>',1); 
			}

        };
    })();
 
})(jqcc);