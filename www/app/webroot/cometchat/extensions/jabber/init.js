<?php
		
		include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
		}

		foreach ($jabber_language as $i => $l) {
			$jabber_language[$i] = str_replace("'", "\'", $l);
		}

		include dirname(__FILE__).DIRECTORY_SEPARATOR."config.php";

		$connectPhrase = str_replace('Facebook',' Facebook/'.$jabberName.' ',str_replace('Facebook/','Facebook',$jabber_language[0]));
?>

/*
 * CometChat
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

(function($){   
  
	$.ccjabber = (function () {

		var title = 'Jabber Extension';
		var server = '<?php echo $cometchatServer;?>j';
		var session = '';
		var login = '<?php echo $connectPhrase;?><div style="clear:both"></div>';
		var logout = {};
		logout['facebook'] = '<?php echo $jabber_language[8];?>';
		logout['gtalk'] = '<?php echo $jabber_language[13];?><?php echo $jabberName;?>';
		var hash = '';
		var messageTimer;
		var friendsTimer;
		var minHeartbeat = 3000;
		var maxHeartbeat = 30000;
		var heartbeatTime = minHeartbeat;
		var heartbeatCount = 1;
		var crossDomain = '<?php echo CROSS_DOMAIN;?>';

        return {

			getTitle: function() {
				return title;	
			},

			init: function () {
				$('<div class="cometchat_tabsubtitle2" id="jabber_login">'+login+'</div>').insertAfter('#cometchat_userstab_popup .cometchat_userstabtitle');
				
				$('#jabber_login').unbind('click');
				$('#jabber_login').bind('click', function() {
					jqcc.ccjabber.login();
				});

				var list = '<div id="cometchat_userslist_jabber"></div>';
				$(list).insertAfter('#cometchat_userslist');

				if (jqcc.cookie('cc_jabber') && jqcc.cookie('cc_jabber') == 'true') {
					jqcc.ccjabber.process();
				}
			},

			login: function () {
				   hash = '';
				   baseUrl = $.cometchat.getBaseUrl();
				   baseData = $.cometchat.getBaseData();
				   baseDomain = document.domain;
				   loadCCPopup(baseUrl+'extensions/jabber/index.php?basedata='+baseData+'&basedomain='+baseDomain, 'jabber',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=0, width=410,height=100",410,100,'<?php echo $connectPhrase;?> <?php echo $jabber_language[15];?>');

		   },

			logout: function () {                             
				$.cometchat.updateJabberOnlineNumber(0);
				$('.cometchat_subsubtitle_siteusers').remove();
				$('.cometchat_subsubtitle_jabber').remove();
				hash = '';
				jqcc.cookie('cc_jabber','false',{ path: '/' });
				$('#jabber_login').html(login);
				$('#cometchat_userslist_jabber').html('');
				heartbeatCount = 1;
				clearTimeout(messageTimer);
				heartbeatTime = minHeartbeat;
				$.getJSON(server+session+"?json_callback=?", {'action':'logout'});
				$('#jabber_login').unbind('click');
				$('#jabber_login').bind('click', function() {
					jqcc.ccjabber.login();
				});
			},

			process: function () {
				session = ';jsessionid='+$.cookie('cc_jabber_id');
				if ($('.cometchat_subsubtitle').first().length == 0) {
					var head = '<div class="cometchat_subsubtitle cometchat_subsubtitle_top cometchat_subsubtitle_siteusers"><hr class="hrleft"><?php echo $jabber_language[10];?><hr class="hrright"></div>';
					$(head).insertBefore('#cometchat_userslist');
				}
				
				var head = '<div class="cometchat_subsubtitle cometchat_subsubtitle_jabber"><hr class="hrleft"><?php echo $jabber_language[11];?><hr class="hrright"></div>';
				
				if (jqcc.cookie('cc_jabber_type') == 'gtalk') {
					head = '<div class="cometchat_subsubtitle cometchat_subsubtitle_jabber"><hr class="hrleft"><?php echo $jabberName;?><?php echo $jabber_language[12];?><hr class="hrright"></div>';
				}
				
				$(head).insertBefore('#cometchat_userslist_jabber');
	
				$('#cometchat_searchbar').css('display','block');

				hash = '';
				$('#jabber_login').html(logout[jqcc.cookie('cc_jabber_type')]);
				
				$('#jabber_login').unbind('click');
				$('#jabber_login').bind('click', function() {
					jqcc.ccjabber.logout();
				});

				jqcc.ccjabber.getFriendsList(1);
			},

			sendMessage: function (id,message) {				
				var currenttime = new Date();
				currenttime = parseInt(currenttime.getTime());
				$.cometchat.addMessage(id,message,1,0,currenttime,1,null);

				id = jqcc.ccjabber.decodeName(id);
				$.getJSON(server+session+"?json_callback=?", {'action':'sendMessage',to:id,msg:message} , function(data){	
					heartbeatCount = 1;
						
					if (heartbeatTime > minHeartbeat) {
						heartbeatCount = 1;
						clearTimeout(messageTimer);
						heartbeatTime = minHeartbeat;
						messageTimer = setTimeout( function() { jqcc.ccjabber.getMessages(); }, minHeartbeat);
					}
				});
			},

			getRecentData: function(id) {
				var originalid = id;

				id = jqcc.ccjabber.decodeName(id);

				$.getJSON(server+session+"?json_callback=?", {'action':'getAllMessages',user:id} , function(data){
					if (data) {
						
						var temp = '';

						$.each(data, function(id,message) {
							
							var sent = 0;
							if (message.type == 'sent') { sent = 1; }

							var selfstyle = '';
							if (message.type == 'sent') {
								fromname = '<?php echo $language[10];?>';
								selfstyle = ' cometchat_self';
							} else {
								fromname = $.cometchat.getName(jqcc.ccjabber.encodeName(message.from));
							}
						
							if (fromname.indexOf(" ") != -1) {
								fromname = fromname.slice(0,fromname.indexOf(" "));
							}

							fromname = fromname.split("@")[0];

							message.from = jqcc.ccjabber.encodeName(message.from);

							message.msg = message.msg.replace(/</g, '&lt;').replace(/>/g, '&gt;');

															
							temp += ($.cometchat.processMessage('<div class="cometchat_chatboxmessage" id="cometchat_message_'+message.time+'"><span class="cometchat_chatboxmessagefrom'+selfstyle+'"><strong>'+fromname+'</strong>:&nbsp;&nbsp;</span><span class="cometchat_chatboxmessagecontent'+selfstyle+'">'+message.msg+'</span></div>',selfstyle));


						});

						if (temp != '') {
							$.cometchat.updateHtml(originalid,temp);
						}
					}
				});
			},

			getMessages: function () {

				$.ajax({
					url: server+session+"?json_callback=?",
					data: {'action':'getRecentMessages'},
					dataType: 'jsonp',
					timeout: 6000,
					error: function() {
						clearTimeout(messageTimer);
						messageTimer = setTimeout( function() { jqcc.ccjabber.getMessages(); }, heartbeatTime);
					},
					success: function(data) {
						if (data) {
							if (data[0] && data[0].error == '1') {
								jqcc.ccjabber.logout();
							} else {

								$.each(data, function(id,message) {
									message.from = jqcc.ccjabber.encodeName(message.from);

									$.cometchat.addMessage(message.from,message.msg,0,0,message.time,1,null);

									heartbeatTime = minHeartbeat;
								});

								heartbeatCount++;

								if (heartbeatTime != maxHeartbeat) {
									if (heartbeatCount > 4) {
										heartbeatTime *= 2;
										heartbeatCount = 1;
									}

									if (heartbeatTime > maxHeartbeat) {
										heartbeatTime = maxHeartbeat;
									}
								} else {
									if (heartbeatCount > 30) {
										jqcc.ccjabber.logout();
									}
								}

								clearTimeout(messageTimer);
								messageTimer = setTimeout( function() { jqcc.ccjabber.getMessages(); }, heartbeatTime);

							}
						}
					}
				});		

			},

			getFriendsList: function (first) {
				if ($('#cometchat_userslist_jabber').html()=='') {
					$('#cometchat_userslist_jabber').html('<div class="cometchat_subsubtitle" style="margin-left:10px;" >Loading...</div>');
				}
				$.ajax({
					url: server+session+"?json_callback=?",
					data: {'action':'getOnlineBuddies', md5: hash},
					dataType: "json",
					type:"GET",
					async:true,
					success: function(data){
					
						if (data[0] && data[0].error == '1') {
							jqcc.ccjabber.logout();
						} else {
							var buddylisttemp = '';
							var buddylisttempavatar = '';
							var md5updated = 0;
							var onlineNumber = 0;
							$.each(data, function(id,user) {
								if (user.id) {	
									var numericid = ((user.id).split('@')[0]).split('-')[1];
									++onlineNumber;
									user.id = jqcc.ccjabber.encodeName(user.id);
									shortname = $.cometchat.getName(user.id);
									if (typeof (shortname) === "undefined") {
										user.n = user.n.split("@")[0];
										if(user.n != ''){
											shortname = user.n
										} else {
											$.ajax({
												url: "http://graph.facebook.com/"+numericid,
												dataType: "json",
												type:"GET",
												async:false,
												success: function(output){
													shortname = output.name;
												}
											});
										}
									} 
									user.n = shortname;
									buddylisttemp += '<div id="cometchat_userlist_'+user.id+'" class="cometchat_userlist" onmouseover="jqcc(this).addClass(\'cometchat_userlist_hover\');" onmouseout="jqcc(this).removeClass(\'cometchat_userlist_hover\');"><span class="cometchat_userscontentname">'+shortname+'</span><span class="cometchat_userscontentdot cometchat_'+user.s+'"></span></div>';
									buddylisttempavatar += '<div id="cometchat_userlist_'+user.id+'" class="cometchat_userlist" onmouseover="jqcc(this).addClass(\'cometchat_userlist_hover\');" onmouseout="jqcc(this).removeClass(\'cometchat_userlist_hover\');"><span class="cometchat_userscontentavatar"><img class="cometchat_userscontentavatarimage" original="'+user.a+'"></span><span class="cometchat_userscontentname">'+shortname+'</span><span class="cometchat_userscontentdot cometchat_'+user.s+'"></span></div>';
									$.cometchat.userAdd(user.id,user.s,user.m,user.n,user.a,'');
								}
								if (user.md5) {
									hash = user.md5;
									md5updated = 1;
								}
							});
							if (onlineNumber == 0) {
								buddylisttempavatar = ('<div class="cometchat_nofriends" style="margin-bottom:10px"><?php echo $jabber_language[14];?></div>');
							}
							if (md5updated) {
								if (jqcc.cookie('cc_jabber') && jqcc.cookie('cc_jabber') == 'true') {
									$.cometchat.updateJabberOnlineNumber(onlineNumber);
									$.cometchat.replaceHtml('cometchat_userslist_jabber', '<div>'+buddylisttempavatar+'</div>');
									$('.cometchat_userlist').unbind('click');
									$('.cometchat_userlist').bind('click', function(e) {
										$.cometchat.userClick(e.target); 
									});

									if ($.cometchat.getSessionVariable('buddylist') == 1) {
										$(".cometchat_userscontentavatar img").each(function() {
											if ($(this).attr('original')) {
												$(this).attr("src", $(this).attr('original'));
												$(this).removeAttr('original');
											}
										});
									}
									$('#cometchat_search').keyup();
								}
							}
							clearTimeout(friendsTimer);
							friendsTimer = setTimeout( function() { jqcc.ccjabber.getFriendsList(); }, 60000);
							if (first) {
								jqcc.ccjabber.getMessages();
							}
						}
					}
				});
	
			},

			encodeName: function(name) {
				name = name.toLowerCase();
				name = name.replace('-','M');
				name = name.replace('@','A');
				name = name.replace(/\./g,'D');
				return name;
			},

			decodeName: function(name) {
				name = name.replace('M','-');
				name = name.replace('A','@');
				name = name.replace(/D/g,'\.');
				return name;
			}


        };
    })();
 
})(jqcc);