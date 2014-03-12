<?php

		include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
		}

		foreach ($chattime_language as $i => $l) {
			$chattime_language[$i] = str_replace("'", "\'", $l);
		}
 
?>

/*
 * CometChat
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

(function($){   
  
	$.ccchattime = (function () {

		var title = '<?php echo $chattime_language[0];?>';
		var enabled=new Array();
        return {

			getTitle: function() {
				return title;	
			},

			init: function (id) {

				if ($("#cometchat_user_"+id+"_popup .cometchat_ts").css('display') == 'none') {
					$("#cometchat_user_"+id+"_popup .cometchat_ts").css('display','inline');
					$("#cometchat_tabcontenttext_"+id).scrollTop(50000);
					enabled[id]=1;
				} else {
					$("#cometchat_user_"+id+"_popup .cometchat_ts_date").css('display','none');
					$("#cometchat_user_"+id+"_popup .cometchat_ts").css('display','none');
					enabled[id]=0;
				}
			},
			getEnabled:function (id){
				if(typeof(enabled[id])=='undefined'){
					return 0;
				}
				return enabled[id];
			}

        };
    })();
 
})(jqcc);