<?php

		include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
		} 

		foreach ($handwrite_language as $i => $l) {
			$handwrite_language[$i] = str_replace("'", "\'", $l);
		}
?>

/*
 * CometChat
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

(function($){   
  
	$.cchandwrite = (function () {

		var title = '<?php echo $handwrite_language[0];?>';

        return {

			getTitle: function() {
				return title;	
			},

			init: function (id) {
				baseUrl = $.cometchat.getBaseUrl();
				baseData = $.cometchat.getBaseData();
				loadCCPopup(baseUrl+'plugins/handwrite/index.php?id='+id+'&basedata='+baseData, 'handwrite',"status=0,toolbar=0,menubar=0,directories=0,resizable=1,location=0,status=0,scrollbars=0, width=330,height=250",330,250,'<?php echo $handwrite_language[0];?>'); 
			}

        };
    })();
 
})(jqcc);