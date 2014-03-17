<?php
include dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
	include dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
}

foreach ($mobile_language as $i => $l) {
	$mobile_language[$i] = str_replace("'", "\'", $l);
}

?>
var chatMessageCount = 0;
var initializeMobileWebapp = 1;
(function($){   
		  
			$.mobilewebapp = (function () {

				var currentChatboxId = 0;
				var onlineScroll;
				var hideWebBar;
				var keyboardOpen = 0;
				var landscapeMode = 0;
				var buddyListName = {};
				var buddyListAvatar = {};
				var buddyListMessages = {};
				var detectTimer;
				
				return {
					playSound: function() {
							var snd = new Audio("mp3/beep.mp3"); 
							setTimeout(function(){snd.play();},500);
						},

					detect: function(keyboard) {
						var baseHeight = $(window).height();
						var baseHeight = window.innerHeight;
						var headerHeight = $("#chatheader").outerHeight();
						var footerHeight = $("#chatfooter").outerHeight();
						var crheaderHeight = $("#chatroomheader").outerHeight();
						var crfooterHeight = $("#chatroomfooter").outerHeight();
						var contentPadding = 30;
						
						$('body').css('height',(baseHeight)+'px');
						$('body').css('min-height',(baseHeight)+'px');
						$('body').css('max-height',(baseHeight)+'px');

						$("body").css('width',(window.innerWidth)+'px');
						$("#chatcontent").css('width',(window.innerWidth-30)+'px');
						$("#chatroomcontent").css('width',(window.innerWidth-30)+'px');
						
						$("#chatcontent").css('height',(baseHeight-headerHeight-footerHeight-contentPadding+2)+'px');
						$("#chatcontent").css('min-height',(baseHeight-headerHeight-footerHeight-contentPadding+2)+'px');
						$("#chatcontent").css('max-height',(baseHeight-headerHeight-footerHeight-contentPadding+2)+'px');
						
						$("#chatroomcontent").css('height',(baseHeight-crheaderHeight-crfooterHeight-contentPadding)+'px');
						$("#chatroomcontent").css('min-height',(baseHeight-crheaderHeight-crfooterHeight-contentPadding)+'px');
						$("#chatroomcontent").css('max-height',(baseHeight-crheaderHeight-crfooterHeight-contentPadding)+'px');

						$("#chat").css('height',(baseHeight)+'px');
						$("#chat").css('min-height',(baseHeight)+'px');
						$("#chat").css('max-height',(baseHeight)+'px');
						$("#chatcontent").css('max-height',(parseInt($("#chat").css('height'))-120)+'px');
						
						$("#chatroomusercontent").css('max-height',(parseInt($("#chat").css('height')))+'px');
						$("#chatroomusercontent").css('margin-top','13px');
						
						$("#chatroom").css('height',(baseHeight)+'px');
						$("#chatroom").css('min-height',(baseHeight)+'px');
						$("#chatroom").css('max-height',(baseHeight)+'px');
						$("#chatroomcontent").css('max-height',(parseInt($("#chatroom").css('height'))-120)+'px');
						
						$("#lobbycontent").css('width',(window.innerWidth-30)+'px');
						$("#wocontent").css('width',(window.innerWidth-30)+'px');
						clearTimeout(detectTimer);
						detectTimer = setTimeout(function() {
							jqcc.mobilewebapp.detect();
						},1000);
						
						setTimeout(function() {chatroomScroll.refresh();chatScroll.refresh();lobbyScroll.refresh();woScroll.refresh();},500);
					},

					init: function() { 
					
						jqcc.mobilewebapp.detect();
						window.addEventListener('onorientationchange' in window ? 'orientationchange' : 'resize', function() {
						jqcc.mobilewebapp.detect();
						}, false);
						document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
						if(typeof (jqcc.cookie(cookie_prefix+'new')) != 'undefined' && jqcc.cookie(cookie_prefix+'new') != ''){
							jqcc.mobilewebapp.loadPanel(jqcc.cookie(cookie_prefix+'new'));
							jqcc.cookie(cookie_prefix+'new','', {path: '/'});
						}
					},

					updateBuddyList: function(data) {
					
						var buddylist = '';
						var buddylisttemp = {};
						buddylisttemp['available'] = '';
						buddylisttemp['busy'] = '';
						buddylisttemp['offline'] = '';
						buddylisttemp['away'] = '';
			
						$.each(data, function(i,buddy) {
								 
							longname = buddy.n;
							
							buddyListName[buddy.id] = buddy.n;
							buddyListAvatar[buddy.id] = buddy.a;

							if (!buddyListMessages[buddy.id]) {
								buddyListMessages[buddy.id] = 0;
							}

							buddylisttemp[buddy.s] += '<li id="onlinelist_'+buddy.id+'" class="ui-li ui-li-static ui-body-c ui-li-has-count ui-li-has-icon" onclick="javascript:jqcc.mobilewebapp.loadPanel(\''+buddy.id+'\')" data-filtertext="'+longname+'"><img src="images/cleardot.gif" class="ui-li-icon status status-'+buddy.s+' "><span class="longname">'+longname+'</span><span class="ui-li-aside"><img src="'+buddy.a+'" class=" avatarimage"/></span><span class="newmessages ui-li-count">'+buddyListMessages[buddy.id]+'</span></li>';
							$('#onlinelist_'+buddy.id).remove();
						});			
						
						buddylist = buddylisttemp['available']+buddylisttemp['busy']+buddylisttemp['away']+buddylisttemp['offline'];

						if (buddylist == '') {
							buddylist += '<li class="onlinelist ui-li ui-li-static ui-body-c ui-li-has-count ui-li-has-icon" id="nousersonline">'+jqcc.cometchat.getLanguage(14)+'</li>';
							$('#wolist').html(buddylist);
						}
						else{
							$('#wolist').html(buddylist);
							$('#wolist').append('<li class="onlinelist ui-li ui-li-static ui-body-c ui-li-has-count ui-li-has-icon" id="wolast" style="background:none !important;"></li>');
						}
						
						if (initializeMobileWebapp) {
							ccstateReader();
							initializeMobileWebapp = 0;
						}
					},

					loggedOut: function() {
						alert('<?php echo $mobile_language[5];?>');
						location.href = jqcc.cometchat.getBaseUrl()+'../';
					},
					
					chatWith: function(id) {
						if(chatwith()){
							jqcc.mobilewebapp.loadPanel(id);
						}
					},

					sendMessage: function(id) {
						var message = $('#chatmessage').val();
						if($.trim(message)!=""){
							jqcc.cometchat.sendMessage(id,message);
							$('#chatmessage').focus();
							fromname = '<?php echo $mobile_language[6];?>';
							selfstyle = 'selfmessage';
							var ts = Math.round(new Date().getTime() / 1000)+''+Math.floor(Math.random()*1000000);
							var temp = (('<li><div class="cometchat_chatboxmessage '+selfstyle+' me" id="cometchat_message_'+ts+'"><span class="cometchat_chatboxmessagecontent">'+message+'</span>'+'</div><div style="clear:both;"></div></li>'));
							if (currentChatboxId == id) {
								$('#cwlist').append(temp);
								setTimeout(function () {scrollToBottom();},500);
							}
						}
						$('#chatmessage').val('');
						return false;
					},

					newMessage: function(incoming) {
						if (!buddyListName[incoming.from]) {		
							jqcc.cometchat.getUserDetails(incoming.from);
						}

						fromname = buddyListName[incoming.from];
						if (fromname.indexOf(" ") != -1) {
							fromname = fromname.slice(0,fromname.indexOf(" "));
						}

						var ts = Math.round(new Date().getTime() / 1000)+''+Math.floor(Math.random()*1000000);
						var atleastOneNewMessage = 0;
						if (incoming.self == 0) {
							var temp = (('<li><div class="cometchat_chatboxmessage you" id="cometchat_message_'+ts+'"><span class="cometchat_chatboxmessagecontent">'+incoming.message+'</span>'+'</div><div style="clear:both;"></div></li>'));
							atleastOneNewMessage++;
						}

						if (currentChatboxId == incoming.from) {
							$('#cwlist').append(temp);
							setTimeout(function () {scrollToBottom();},500);
						} else {
							if (buddyListMessages[incoming.from]) {
								buddyListMessages[incoming.from] += 1;
							} else {
								buddyListMessages[incoming.from] = 1;
							}
							$('#onlinelist_'+incoming.from+' .newmessages').html(buddyListMessages[incoming.from]).addClass('newmessageCount');
							jqcc.mobilewebapp.notification();
						}

						if (atleastOneNewMessage) {
							jqcc.mobilewebapp.playSound();
						}
					},

					loadUserData: function(id,data) {
						buddyListName[id] = data.n;
						buddyListAvatar[id] = data.a;
					
						if (!buddyListMessages[id]) {
							buddyListMessages[id] = 0;
						}

						longname = data.n;
						var buddylist = '<li id="onlinelist_'+data.id+'" onclick="javascript:jqcc.mobilewebapp.loadPanel(\''+data.id+'\')" data-filtertext="'+longname+'"><img src="images/cleardot.gif" class="ui-li-icon status status-'+data.s+' "><span class="longname">'+longname+'</span><span class="ui-li-aside"><img src="'+buddy.a+'" class=" avatarimage"/></span><span class="newmessages ui-li-count">0</span></li>';
						$('#nousersonline').css('display','none');
						$('#permanent').prepend(buddylist);
					},

					joinChatroom: function(roomid,inviteid,roomname){						
						javascript:chatroom(roomid,roomname,1,inviteid,1);loadChatroom();
					},

					newMessages: function(data) {
						
						var temp = '';
						var atleastOneNewMessage = 0;				

						$.each(data, function(i,incoming) {
								if (!buddyListName[incoming.from]) {
									jqcc.cometchat.getUserDetails(incoming.from);
								}

								fromname = buddyListName[incoming.from];

								if (fromname.indexOf(" ") != -1) {
									fromname = fromname.slice(0,fromname.indexOf(" "));
								}

								var ts = Math.round(new Date().getTime() / 1000)+''+Math.floor(Math.random()*1000000);

								if (incoming.self == 0) {
									var temp = (('<li><div class="cometchat_chatboxmessage you" id="cometchat_message_'+ts+'"><span class="cometchat_chatboxmessagecontent">'+incoming.message+'</span>'+'</div><div style="clear:both;"></div></li>'));
									atleastOneNewMessage++;

									if (currentChatboxId == incoming.from) {
										$('#cwlist').append(temp);
										setTimeout(function () {scrollToBottom();},500);

									} else {
										if (buddyListMessages[incoming.from]) {
											buddyListMessages[incoming.from] += 1;
										} else {
											buddyListMessages[incoming.from] = 1;
										}
										
										$('#onlinelist_'+incoming.from+' .newmessages').html(buddyListMessages[incoming.from]).addClass('newmessageCount');
										jqcc.mobilewebapp.notification();
									}
								}
						});
						
						if (atleastOneNewMessage) {
							jqcc.mobilewebapp.playSound();
						}
					},

					loadPanel: function (id,name) {
						buddyListMessages[id] = 0;						
						$('#onlinelist_'+id+' .newmessages').html('0').removeClass('newmessageCount');
						var cc_state = jqcc.cookie(cookie_prefix+'state');
						if (typeof (cc_state) != 'undefined' && cc_state != null && cc_state != '') {
							var pattern = id+"\\|[0-9]+";
							var regex = new RegExp(pattern);
							cc_state = cc_state.replace(regex,id+"|0");
							jqcc.cookie(cookie_prefix+'state',cc_state, {path: '/'});	
						}
						var userName=buddyListName[id];
						var flag=0;
						if(userName===undefined){
							if(!isNaN(id)){	
								$.ajax({
									url:baseurl+"cometchat_getid.php",
									data:{userid:id},
									dataType:"jsonp",
									type:"get",
									async:"false",
									timeout:"10000",
									cache:false,
									success:function(data){
										if(data){
											userName = data.n;
											flag=1;
										}
									},
									error:function(){
									}
								});
							}
						}else{
							flag=1;
						}

						var refreshIntervalId = setInterval(function(){
							if(flag==1){
								clearInterval(refreshIntervalId);
								$('#chatheader h1').html(userName);
								$('#scroller').html('<ul id="cwlist"></ul>');
								$('#chatmessageForm').attr('onsubmit','return jqcc.mobilewebapp.sendMessage(\''+id+'\')');
								loadChatbox();
							}
						},100);
					
						jqcc.mobilewebapp.detect();
						currentChatboxId = id;
						$('#chatmessage').blur(function() {
							keyboardOpen = 0;
							jqcc.mobilewebapp.detect();
						});

						jqcc.cometchat.getRecentData(id);
						setTimeout(function () {scrollToBottom();},500);
						document.cookie = '<?php echo $cookiePrefix;?>chat='+urlencode(id+':'+urlencode(userName));
					},

					loadData: function (id,data) {
						$.each(data, function(type,item){
								if (type == 'messages') {
									var temp = '';
									$.each(item, function(i,incoming) {
										var self;
										var selfstyle = '';
										var messagefrom = '';
										
										if (incoming.self == 1) {
											fromname = '<?php echo $mobile_language[6];?>';
											selfstyle = 'selfmessage';
										} else {
											fromname = buddyListName[id];
										}

										var ts = new Date(incoming.sent * 1000);
										if (fromname.indexOf(" ") != -1) {
											fromname = fromname.slice(0,fromname.indexOf(" "));
										}
										if(selfstyle == ''){
											self = 'you';
										}else{
											self = 'me';
										}
										temp += ('<li><div class="cometchat_chatboxmessage '+selfstyle+' '+self+'" id="cometchat_message_'+incoming.id+'">'+messagefrom+'<span class="cometchat_chatboxmessagecontent'+selfstyle+'">'+incoming.message+'</span>'+'</div><div style="clear:both;"></div>');

									});

									if (currentChatboxId == id) {
										setTimeout(function () {
											$('#cwlist').append(temp);
											setTimeout(function () {scrollToBottom();},500);
										},200)
									}
								}

							});
					},
					
					getChatCookie: function(){
						var cookieName = '<?php echo $cookiePrefix;?>'+'chat';
						var cookieData = getCookieInfo(cookieName);
						if (typeof (cookieData) != 'undefined' && cookieData != "") {
							var cDetails = urldecode(cookieData).split(":")
							var id = cDetails[0];
							setTimeout(function () {
								jqcc.mobilewebapp.loadPanel(id);
							},500);
						} else {
							loadChatboxReverse();
						}
					},
					
					back: function() {
						document.cookie = '<?php echo $cookiePrefix;?>chat=';
						currentChatboxId = 0;
					},					
					notification: function(){
						chatMessageCount=0;
						$.each(buddyListMessages,function(i,j){
							chatMessageCount = chatMessageCount + j;
						});
						$('.chatlink  .notifier').html(chatMessageCount);
					}
				};
			})();
		 
		})(jqcc);

		var listener = function (e) {
			e.preventDefault();
		};

window.onload = function() {
	getChatroomCookie();
	jqcc.mobilewebapp.getChatCookie();
	document.cookie = '<?php echo $cookiePrefix;?>chat=';
	document.cookie = '<?php echo $cookiePrefix;?>chatroom=';
	jqcc.mobilewebapp.init();
}

$('#buddy').live('pageshow',function(){
	jqcc.mobilewebapp.notification();
});