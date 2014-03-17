<?php
		include dirname(__FILE__).DIRECTORY_SEPARATOR."config.php";
		include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
		}

		foreach ($smilies_language as $i => $l) {
			$smilies_language[$i] = str_replace("'", "\'", $l);
		}
?>

/*
 * CometChat
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

(function($){   
  
	$.ccsmilies = (function () {
	
		var title = '<?php echo $smilies_language[0];?>';
		var height = <?php echo $smlHeight;?>;
		var width = <?php echo $smlWidth;?>;

        return {

			getTitle: function() {
				return title;	
			},

			init: function (id) {
				baseUrl = $.cometchat.getBaseUrl();
				baseData = $.cometchat.getBaseData();
				loadCCPopup(baseUrl+'plugins/smilies/index.php?id='+id+'&basedata='+baseData, 'smilies',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=0, width="+width+",height="+height,width,height-50,'<?php echo $smilies_language[1];?>'); 
			},

			addtext: function (id,text) {

				var activeId = $.cometchat.getActiveId();
				if (parseInt(activeId) > 0) {
					id = activeId;
				}

				var string = $('#cometchat_user_'+id+'_popup .cometchat_textarea').val();
				
				if (string.charAt(string.length-1) == ' ') {
					$('#cometchat_user_'+id+'_popup .cometchat_textarea').val(string+text);
				} else {
					if (string.length == 0) {
						$('#cometchat_user_'+id+'_popup .cometchat_textarea').val(text);
					} else {
						$('#cometchat_user_'+id+'_popup .cometchat_textarea').val(string+' '+text);
					}
				}
				
				$('#cometchat_user_'+id+'_popup .cometchat_textarea').focus();
				
			}

        };
    })();
 
})(jqcc);
