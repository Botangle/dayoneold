<?php

		include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
		} 

		foreach ($filetransfer_language as $i => $l) {
			$filetransfer_language[$i] = str_replace("'", "\'", $l);
		}
?>

/*
 * CometChat
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

(function($){   
  
	$.ccfiletransfer = (function () {

		var title = '<?php echo $filetransfer_language[0];?>';

        return {

			getTitle: function() {
				return title;	
			},

			init: function (id) {
				baseUrl = $.cometchat.getBaseUrl();
				baseData = $.cometchat.getBaseData();

				loadCCPopup(baseUrl+'plugins/filetransfer/index.php?id='+id+'&basedata='+baseData, 'filetransfer',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=0, width=400,height=140",400,130,'<?php echo $filetransfer_language[1];?>'); 
			}

        };
    })();
 
})(jqcc);