<?php
		include_once dirname(__FILE__).DIRECTORY_SEPARATOR."config.php";

		include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
		}

		foreach ($avchat_language as $i => $l) {
			$avchat_language[$i] = str_replace("'", "\'", $l);
		}

		if ($videoPluginType == 3) {
			$width = 330; 
			$height = 330;
		} else {
			$width = 434;
			$height = 356;
		}
?>

/*
 * CometChat
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

(function($){
  
	$.ccavchat = (function () {

		var title = '<?php echo $avchat_language[0];?>';

        return {

			getTitle: function() {
				return title;	
			},

			init: function (id) {
				baseUrl = getBaseUrl();
				loadCCPopup(baseUrl+'plugins/avchat/index.php?action=call&chatroommode=1&grp='+id, 'audiovideochat',"status=0,toolbar=0,menubar=0,directories=0,resizable=1,location=0,status=0,scrollbars=0, width=<?php echo $width;?>,height=<?php echo $height;?>",<?php echo $width;?>,<?php echo $height;?>,'<?php echo $avchat_language[8];?>',1); 
			},

			join: function (id) {
				baseUrl = getBaseUrl();
				loadCCPopup(baseUrl+'plugins/avchat/index.php?action=call&chatroommode=1&join=1&grp='+id, 'audiovideochat',"status=0,toolbar=0,menubar=0,directories=0,resizable=1,location=0,status=0,scrollbars=0, width=<?php echo $width;?>,height=<?php echo $height;?>",<?php echo $width;?>,<?php echo $height;?>,'<?php echo $avchat_language[8];?>',1); 
			}


        };
    })();
 
})(jqcc);