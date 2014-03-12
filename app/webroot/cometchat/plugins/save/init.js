<?php

		include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
		}

		foreach ($save_language as $i => $l) {
			$save_language[$i] = str_replace("'", "\'", $l);
		}
?>

/*
 * CometChat
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

(function($){   
  
	$.ccsave = (function () {

		var title = '<?php echo $save_language[0];?>';

        return {

			getTitle: function() {
				return title;	
			},

			init: function (id) {
				if ($("#cometchat_user_"+id+"_popup .cometchat_tabcontenttext").html() != '') {
					baseUrl = $.cometchat.getBaseUrl();
					baseData = $.cometchat.getBaseData();
					location.href=(baseUrl+'plugins/save/index.php?id='+id+'&basedata='+baseData);
				} else {
					alert('<?php echo $save_language[1];?>');
				}
				
			}

        };
    })();
 
})(jqcc);