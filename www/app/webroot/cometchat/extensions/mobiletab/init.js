<?php

	include_once dirname(__FILE__).DIRECTORY_SEPARATOR."config.php";
	include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";
	
	if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
		include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
	}

	foreach ($mobiletab_language as $i => $l) {
		$mobiletab_language[$i] = str_replace("'", "\'", $l);
	}
 
?>

/*
 * CometChat
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

(function($){  
	$.ccmobiletab = (function() {
		var title = "<?php echo $mobiletab_language[0]; ?>", timestamp = 0, amount = 0, loggedout=0, cookie_prefix='<?php echo $cookiePrefix; ?>', mobileDevice = navigator.userAgent.match(/ipad|ipod|iphone|android|windows ce|blackberry|palm|symbian/i);
	
		return{
			init : function() {
				if(mobileDevice || location.href.match('extensions/mobiletab/')){
					$('#cometchat').css('display','none');
					$('body').append('<div class="cometchat_ccmobiletab_redirect "> '+title+' (<span id="ccmobiletab_buddycount">0</span>) </div>');
					jqcc.ccmobiletab.tabalertScale();
					$(".cometchat_ccmobiletab_redirect ,.cometchat_ccmobiletab_tabalert").live('click',function(){
						$(".cometchat_ccmobiletab_redirect .cometchat_ccmobiletab_tabalert").remove();
						window.open(jqcc.cometchat.getBaseUrl()+'extensions/mobile/','mobiletab','_blank');
					});	
				}
			},
			tabalertScale : function() {
				var winWidth = $(window).width();
				if (winWidth <= 200) {
					$('.cometchat_ccmobiletab_redirect,#ccmobiletab_buddycount').css('font-size','12px');
					$('.cometchat_ccmobiletab_redirect').css('width','60%');
					$(".cometchat_ccmobiletab_redirect .cometchat_ccmobiletab_tabalert").css('right','17%');
				}else if (winWidth > 200 && winWidth <= 300) {
					$('.cometchat_ccmobiletab_redirect,#ccmobiletab_buddycount').css('font-size','17px');
					$('.cometchat_ccmobiletab_redirect').css('width','60%');
					$(".cometchat_ccmobiletab_redirect .cometchat_ccmobiletab_tabalert").css('right','25%');
				}else if (winWidth > 300 && winWidth <= 400) {
					$('.cometchat_ccmobiletab_redirect,#ccmobiletab_buddycount').css('font-size','18px');
					$('.cometchat_ccmobiletab_redirect').css('width','55%');
					$(".cometchat_ccmobiletab_redirect .cometchat_ccmobiletab_tabalert").css('right','30%');
				}else if (winWidth > 400 && winWidth <= 600) {
					$('.cometchat_ccmobiletab_redirect,#ccmobiletab_buddycount').css('font-size','20px');
					$('.cometchat_ccmobiletab_redirect').css('width','50%');
					$(".cometchat_ccmobiletab_redirect .cometchat_ccmobiletab_tabalert").css('right','33%');
				}else if (winWidth > 600 && winWidth <= 1000) {
					$('.cometchat_ccmobiletab_redirect,#ccmobiletab_buddycount').css('font-size','24px');
					$('.cometchat_ccmobiletab_redirect').css('width','30%');
					$(".cometchat_ccmobiletab_redirect .cometchat_ccmobiletab_tabalert").css('right','24%');
				}else if (winWidth > 1000) {
					$('.cometchat_ccmobiletab_redirect,#ccmobiletab_buddycount').css('font-size','30px');
					$('.cometchat_ccmobiletab_redirect').css('width','25%');
					$(".cometchat_ccmobiletab_redirect .cometchat_ccmobiletab_tabalert").css('right','28%');
				}	
			},
			notify : function(totmsg) {

				if(typeof(totmsg) != "undefined") {
					amount = totmsg;
				}
				if (amount === 0) {
					$(".cometchat_ccmobiletab_redirect .cometchat_ccmobiletab_tabalert").remove();
				} else {
					if (<?php echo $confirmOnAllMessages; ?>) {
						jqcc.cookie(cookie_prefix+"confirmOnMsg",null,{ path: '/', expires: -1 });
					}
					if (jqcc.cookie(cookie_prefix+"confirmOnMsg") != 1) { 					
						if (confirm("<?php echo $mobiletab_language[1]; ?>")) {
							$(".cometchat_ccmobiletab_redirect .cometchat_ccmobiletab_tabalert").remove();
							window.open(jqcc.cometchat.getBaseUrl()+'extensions/mobile/','mobiletab','_blank');
						} else {
							if ($(".cometchat_ccmobiletab_redirect .cometchat_ccmobiletab_tabalert").length>0) {
								$(".cometchat_ccmobiletab_redirect .cometchat_ccmobiletab_tabalert").html(amount);
							} else {
								$("<div/>").addClass("cometchat_ccmobiletab_tabalert").html(amount).appendTo($('.cometchat_ccmobiletab_redirect'));	
							}
							jqcc.ccmobiletab.tabalertScale();
						}
						if (!<?php echo $confirmOnAllMessages; ?>) {
							jqcc.cookie(cookie_prefix+"confirmOnMsg","1",{ path: '/', expires: 365 });
						}
					} else {
						if ($(".cometchat_ccmobiletab_redirect .cometchat_ccmobiletab_tabalert").length>0) {
							$(".cometchat_ccmobiletab_redirect .cometchat_ccmobiletab_tabalert").html(amount);
						} else {
							$("<div/>").addClass("cometchat_ccmobiletab_tabalert").html(amount).appendTo($('.cometchat_ccmobiletab_redirect'));	
						}
						jqcc.ccmobiletab.tabalertScale();
					}
				}
			},
			updateBuddyList : function(data) {
				var buddyCount = 0;
				$.each(data,function(index,user){
					if(user.s!= 'offline'){
						buddyCount++;
					}
				});
				$("#ccmobiletab_buddycount").html(buddyCount);
			},
			newMessages : function(data) {
				$.each(data, function(i,incoming) {
					if (incoming.self == 0) {
						if(incoming.id > timestamp) {
							amount++;
							jqcc.ccmobiletab.notify();
							timestamp = incoming.id;
						}
					}
				});					
			},
			newMessage : function(incoming) {
				if (incoming.self == 0) {
					if(incoming.sent > timestamp) {
						amount++;
						jqcc.ccmobiletab.notify();
						timestamp = incoming.sent;
					}
				}
			},
			loggedOut : function() {
				loggedout = 1;
				$(".cometchat_ccmobiletab_redirect").hide(0);
				jqcc.cookie(cookie_prefix+"confirmOnMsg",null,{ path: '/', expires: -1 });
			}
		};
	})();
	window.onresize = function(){
		jqcc.ccmobiletab.tabalertScale();
	}
	window.onload = function(){
		jqcc.ccmobiletab.tabalertScale();
	}
})(jqcc);