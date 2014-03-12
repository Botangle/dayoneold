<?php
		include dirname(__FILE__).DIRECTORY_SEPARATOR."config.php";
		include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
		}

		foreach ($whiteboard_language as $i => $l) {
			$whiteboard_language[$i] = str_replace("'", "\'", $l);
		}
?>

/*
 * CometChat
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

(function($){   
  
	$.ccwhiteboard = (function () {

		var title = '<?php echo $whiteboard_language[0];?>';
		var lastcall = 0;
		var height = '<?php echo $whitebHeight;?>';
		var width = '<?php echo $whitebWidth;?>';

        return {

			getTitle: function() {
				return title;	
			},

			init: function (id) {
				var currenttime = new Date();
				currenttime = parseInt(currenttime.getTime()/1000);
				if (currenttime-lastcall > 10) {
					baseUrl = getBaseUrl();
					basedata = parent.jqcc.cometchat.getBaseData();

					var random = currenttime;
					loadCCPopup(baseUrl+'plugins/whiteboard/index.php?action=whiteboard&chatroommode=1&subaction=request&id='+id+'&basedata='+basedata, 'whiteboard',"status=0,toolbar=0,menubar=0,directories=0,resizable=1,location=0,status=0,scrollbars=0,width=<?php echo $whitebWidth;?>,height=<?php echo $whitebHeight;?>",width,height-50,'<?php echo $whiteboard_language[9];?>',1);
				} else {
					alert('<?php echo $whiteboard_language[1];?>');
				}
			},

			accept: function (id) {
				baseUrl = getBaseUrl();
				basedata = parent.jqcc.cometchat.getBaseData();

				loadCCPopup(baseUrl+'plugins/whiteboard/index.php?action=whiteboard&chatroommode=1&id='+id+'&basedata='+basedata, 'whiteboard',"status=0,toolbar=0,menubar=0,directories=0,resizable=1,location=0,status=0,scrollbars=0, width=<?php echo $whitebWidth;?>,height=<?php echo $whitebHeight;?>",width,height-50,'<?php echo $whiteboard_language[9];?>',1); 
			}
        };
    })();
 
})(jqcc);