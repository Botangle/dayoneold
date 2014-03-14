<?php

		include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";
		include dirname(__FILE__).DIRECTORY_SEPARATOR."config.php";
		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
		}

		foreach ($broadcast_language as $i => $l) {
			$broadcast_language[$i] = str_replace("'", "\'", $l);
		}
		
?>

/*
 * CometChat
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

(function($){   
  
		$.ccbroadcast = (function () {
		var title = '<?php echo $broadcast_language[0];?>';
		var type = <?php echo $videoPluginType;?>;
		
		var lastcall = 0;

		   return {

				getTitle: function() {
					return title;	
				},

				init: function (id) {
					var random = '';
					var currenttime = new Date();
					currenttime = parseInt(currenttime.getTime()/1000);
					if (currenttime-lastcall > 10) {
						baseUrl = $.cometchat.getBaseUrl();
						baseData = $.cometchat.getBaseData();
						loadCCPopup(baseUrl+'plugins/broadcast/index.php?action=request&type=1&to='+id+'&basedata='+baseData, 'broadcast',"status=0,toolbar=0,menubar=0,directories=0,resizable=1,location=0,status=0,scrollbars=0, width=440,height=410",440,410,'<?php echo $broadcast_language[8];?>',1);
						
						lastcall = currenttime;
					} else {
						alert('<?php echo $broadcast_language[1];?>');
					}
				},

				accept: function (id,grp) {
					baseUrl = $.cometchat.getBaseUrl();
					baseData = $.cometchat.getBaseData();
					loadCCPopup(baseUrl+'plugins/broadcast/index.php?action=call&type=0&grp='+grp+'&basedata='+baseData, 'broadcast',"status=0,toolbar=0,menubar=0,directories=0,type=0,resizable=1,location=0,status=0,scrollbars=0, width=440,height=410",440,410,'<?php echo $broadcast_language[8];?>',1);
				}

			};
		})();
 
})(jqcc);