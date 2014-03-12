<?php
	
		include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
		} 

		foreach ($colors_language as $i => $l) {
			$colors_language[$i] = str_replace("'", "\'", $l);
		}
?>

/*
 * CometChat
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

(function($){   
  
	$.cccolors = (function () {

		var title = '<?php echo $colors_language[0];?>';

        return {

			getTitle: function() {
				return title;	
			},

			init: function (id) {
				baseUrl = getBaseUrl();
				loadCCPopup(baseUrl+'plugins/colors/index.php?id='+id, 'colors',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=0, width=260,height=130",260,80,'<?php echo $colors_language[1];?>'); 
			},

			updatecolor: function (text) {

				if (text != '' && text != null) {
					document.cookie = '<?php echo $cookiePrefix;?>chatroomcolor='+text;
				}

				$('#currentroom .cometchat_textarea').focus();
				
			}

        };
    })();
 
})(jqcc);