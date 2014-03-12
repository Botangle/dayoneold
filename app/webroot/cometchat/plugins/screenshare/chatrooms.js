<?php
		include dirname(__FILE__).DIRECTORY_SEPARATOR."config.php";
		include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
		} 

		foreach ($screenshare_language as $i => $l) {
			$screenshare_language[$i] = str_replace("'", "\'", $l);
		}
?>

/*
 * CometChat
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

(function($){   
  
	$.ccscreenshare = (function () {

		var title = '<?php echo $screenshare_language[0];?>';
		var lastcall = 0;
		var height = '<?php echo $scrHeight;?>';
		var width = '<?php echo $scrWidth;?>';
		var type = '<?php echo $screensharePluginType;?>';

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
					
					if(type=='1') {
						var w = window.open(baseUrl+'plugins/screenshare/index.php?action=screenshare&type=1&chatroommode=1&roomid='+id+'&id='+random,'screenshare',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=0, width=825,height=350");
					} else {
						var w = window.open(baseUrl+'plugins/screenshare/index.php?action=screenshare&type=1&chatroommode=1&roomid='+id+'&id='+random,'screenshare',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=0, width=430,height=100");
					}
					w.focus();

				} else {
					alert('<?php echo $screenshare_language[1];?>');
				}
			},

			accept: function (id,random) {
				baseUrl = getBaseUrl();
				loadCCPopup(baseUrl+'plugins/screenshare/index.php?action=screenshare&type=0&id='+random, 'screenshare',"status=0,toolbar=0,menubar=0,directories=0,resizable=1,location=0,status=0,scrollbars=0, width=<?php echo $scrWidth;?>,height=<?php echo $scrHeight;?>",width,height-50,'<?php echo $screenshare_language[7];?>',1);
			}
        };
    })();
 
})(jqcc);