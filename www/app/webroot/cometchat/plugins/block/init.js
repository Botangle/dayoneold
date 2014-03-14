<?php
		include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
		} 

		foreach ($block_language as $i => $l) {
			$block_language[$i] = str_replace("'", "\'", $l);
		}
?>

/*
 * CometChat
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

(function($){   
  
	$.ccblock = (function () {

		var title = '<?php echo $block_language[0];?>';
		var lightmode = '<?php echo $lightWeight;?>';

        return {

			getTitle: function() {
				return title;	
			},

			init: function (id) {
				baseUrl = $.cometchat.getBaseUrl();
				baseData = $.cometchat.getBaseData();

				var result = confirm('<?php echo $block_language[1];?>');

				if (result) {
					$.getJSON(baseUrl+'plugins/block/index.php?action=block&callback=?', {to: id, basedata: baseData},
						function(data) {
							alert('<?php echo $block_language[2];?>');
							setTimeout(function() {
								if ($('#cometchat_user_'+id).length > 0) {
									$('#cometchat_user_'+id+' .cometchat_closebox_bottom').click();
								}
							}, 1000);
						}
					);
				}
			},

			addCode: function() {
				if(lightmode!=1){
					$('#cometchat_optionsbutton_popup .cometchat_tabcontent').append('<a href="javascript:void(0);" onclick="javascript:jqcc.ccblock.blockList();" style="margin-left:5px;margin-top:5px;"><?php echo $block_language[5];?></a>');
				}else{				
					$('#cometchat_userstab_popup_offline').after('<span class="cometchat_unblock" title="<?php echo $block_language[5];?>" onclick="javascript:jqcc.ccblock.blockList();"></span>');
				}
			},

			blockList: function (id) {
				baseUrl = $.cometchat.getBaseUrl();
				baseData = $.cometchat.getBaseData();
				loadCCPopup(baseUrl+'plugins/block/index.php?basedata='+baseData, 'blocks',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=1, width=500,height=200",500,200,'<?php echo $block_language[3];?>');  
			}
        };
    })();
 
})(jqcc);