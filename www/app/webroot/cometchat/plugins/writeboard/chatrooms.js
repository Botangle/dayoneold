<?php
		include dirname(__FILE__).DIRECTORY_SEPARATOR."config.php";
		include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
		}

		foreach ($writeboard_language as $i => $l) {
			$writeboard_language[$i] = str_replace("'", "\'", $l);
		}
?>

/*
 * CometChat
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

(function($){   
  
	$.ccwriteboard = (function () {

		var title = '<?php echo $writeboard_language[0];?>';
		var lastcall = 0;
		var height = '<?php echo $writebHeight;?>';
		var width = '<?php echo $writebWidth;?>';

        return {

			getTitle: function() {
				return title;	
			},

			init: function (id) {
				var currenttime = new Date();
				currenttime = parseInt(currenttime.getTime()/1000);
				if (currenttime-lastcall > 10) {
					baseUrl = getBaseUrl();

					var random = currenttime;

					lastcall = currenttime;

					loadCCPopup(baseUrl+'plugins/writeboard/index.php?action=writeboard&type=1&chatroommode=1&roomid='+id+'&id='+random, 'writeboard',"status=0,toolbar=0,menubar=0,directories=0,resizable=1,location=0,status=0,scrollbars=0, width=<?php echo $writebWidth;?>,height=<?php echo $writebHeight;?>",width,height-50,'<?php echo $writeboard_language[7];?>',1);

				} else {
					alert('<?php echo $writeboard_language[1];?>');
				}
			},

			accept: function (id,random) {
				baseUrl = getBaseUrl();
				loadCCPopup(baseUrl+'plugins/writeboard/index.php?action=writeboard&type=0&id='+random, 'writeboard',"status=0,toolbar=0,menubar=0,directories=0,resizable=1,location=0,status=0,scrollbars=0, width=<?php echo $writebWidth;?>,height=<?php echo $writebHeight;?>",width,height-50,'<?php echo $writeboard_language[7];?>',1); 
			}
        };
    })();
 
})(jqcc);