<?php

/*

CometChat
Copyright (c) 2012 Inscripts

CometChat ('the Software') is a copyrighted work of authorship. Inscripts 
retains ownership of the Software and any copies of it, regardless of the 
form in which the copies may exist. This license is not a sale of the 
original Software or any copies.

By installing and using CometChat on your server, you agree to the following
terms and conditions. Such agreement is either on your own behalf or on behalf
of any corporate entity which employs you or which you represent
('Corporate Licensee'). In this Agreement, 'you' includes both the reader
and any Corporate Licensee and 'Inscripts' means Inscripts (I) Private Limited:

CometChat license grants you the right to run one instance (a single installation)
of the Software on one web server and one web site for each license purchased.
Each license may power one instance of the Software on one domain. For each 
installed instance of the Software, a separate license is required. 
The Software is licensed only to you. You may not rent, lease, sublicense, sell,
assign, pledge, transfer or otherwise dispose of the Software in any form, on
a temporary or permanent basis, without the prior written consent of Inscripts. 

The license is effective until terminated. You may terminate it
at any time by uninstalling the Software and destroying any copies in any form. 

The Software source code may be altered (at your risk) 

All Software copyright notices within the scripts must remain unchanged (and visible). 

The Software may not be used for anything that would represent or is associated
with an Intellectual Property violation, including, but not limited to, 
engaging in any activity that infringes or misappropriates the intellectual property
rights of others, including copyrights, trademarks, service marks, trade secrets, 
software piracy, and patents held by individuals, corporations, or other entities. 

If any of the terms of this Agreement are violated, Inscripts reserves the right 
to revoke the Software license at any time. 

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

include_once dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules.php";
include_once dirname(__FILE__).DIRECTORY_SEPARATOR."config.php";

include_once dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
	include_once dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
}

foreach ($chatrooms_language as $i => $l) {
	$chatrooms_language[$i] = str_replace("'", "\'", $l);
}

if ($autoLogin != 0) {
	$sql = ("select name from cometchat_chatrooms where id = '".mysql_real_escape_string($autoLogin)."' limit 1");
 	$query = mysql_query($sql);
	
	$chatroom = mysql_fetch_array($query);
	$autoLoginName = base64_encode($chatroom['name']);
} else {
	$autoLoginName = '';
}


?>

/*
 * CometChat 
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

	var timestamp = 0;
	var currentroom = 0;
	var currentp = '';
	var currentroomcode = '';
	var myid = 0;
	var owner = 0;
	var isModerator = 0;
	jqcc = jQuery;
	var cu_uids = [];
	var heartbeatTimer;	
	var baseUrl = '<?php echo BASE_URL;?>';
	var minHeartbeat = <?php echo $minHeartbeat;?>;
	var maxHeartbeat = <?php echo $maxHeartbeat;?>;
	var fullName = <?php echo $displayFullName;?>;
	var hideEnterExit = <?php echo $hideEnterExit;?>;
	var messageBeep = '<?php echo $messageBeep;?>';
	var heartbeatTime = minHeartbeat;
	var heartbeatCount = 1;
	var todaysDate = new Date();
	var todaysDay = todaysDate.getDate();
	var clh = '';
	var ulh = '';
	var users = {};
	var usersName = {};
	var initializeRoom = 0;
	var password = '';
	var currentroomname = '';
	var armyTime = <?php echo $armyTime;?>;	
	var specialChars = /([^\x00-\x80]+)|([&][#])+/; 
	var apiAccess = 0;
	var lightboxWindows = '<?php echo $lightboxWindows;?>';
	var newMessages = 0;
	var plugins = ['<?php echo implode("','",$crplugins);?>'];
	var cookie_prefix = '<?php echo $cookiePrefix;?>';
	$.ajaxSetup({scriptCharset: "utf-8", cache: "false"});
	
	function loadCCPopup(url,name,properties,width,height,title,force) {
		if (apiAccess == 1 && lightboxWindows == 1) {
			parent.loadCCPopup(url,name,properties,width,height,title,force);
		} else {
			var w = window.open(url,name,properties);
			w.focus();
		}
	}

	function getFlashMovie(movieName) {
		var isIE = navigator.appName.indexOf("Microsoft") != -1;
		return (isIE) ? window[movieName] : document[movieName];
	}
	
	function getBaseUrl(){
		return baseUrl;
	}

	function playsound() {
		try	{
			getFlashMovie("cometchatbeep").beep();
		} catch (error) {
			messageBeep = 0;
		}
	}
	
	function popoutChat() {
		leaveChatroom();
		myRef = window.open(self.location,'popoutchat','left=20,top=20,status=0,toolbar=0,menubar=0,directories=0,location=0,status=0,scrollbars=0,resizable=1,width=800,height=600');
		parent.jqcc.cometchat.closeModule('chatrooms');
		setTimeout('window.location.reload()',3000);
	}

	function chatboxKeydown(event,chatboxtextarea,force) {
		if ((event.keyCode == 13 && event.shiftKey == 0) || force == 1)  {
			var message = $(chatboxtextarea).val();
			message = message.replace(/^\s+|\s+$/g,"");

			if (currentroom != 0) {
 
				$(chatboxtextarea).val('');
				$(chatboxtextarea).css('height','18px');
				
				var height = getWindowHeight();
				$("#currentroom_convo").css('height',height-58-parseInt($('.cometchat_textarea').css('height'))-8);

				$(".slimScrollDiv").css('height',$("#currentroom_convo").css('height'));

				$(chatboxtextarea).css('overflow-y','hidden');
				$(chatboxtextarea).focus();

				if (message != '') {
					$.post("chatrooms.php?action=sendmessage", {message: message, currentroom: currentroom} , function(data){				
						if (data) {
							<?php if (USE_COMET != 1 || COMET_CHATROOMS != 1):?>
							addMessage('1', message, '1', '1', data,1,Math.floor(new Date().getTime()/1000));
							<?php endif;?>
							scrollDown();
						}
					});
				}
			}
			return false;
		} 
	}

	function createChatroom(){
		hidetabs();
		$('#createtab').addClass('tab_selected');
		$('#create').css('display','block');
		$('.welcomemessage').html('<?php echo $chatrooms_language[5];?>');
	}

	function leaveChatroom(id) {
		var flag=0;
		if (typeof(id) != 'undefined') {
			flag=1; 
		}
		
		<?php if (USE_COMET == 1 && COMET_CHATROOMS == 1):?>
		cometuncall_function(currentroomcode);
		currentroomcode = '';
		<?php endif;?>

		$("#cometchat_userlist_"+currentroom).removeClass("cometchat_chatroomselected");
		$.post("chatrooms.php?action=leavechatroom", {currentroom: currentroom, flag:flag}, function(data){				
			if (data) {
				currentp = '';
				currentroomname = '';
				currentroom = 0;
				$('#currentroomtab').css('display','none');
				document.cookie = '<?php echo $cookiePrefix;?>chatroom=';
				loadLobby();
			}
		});
	}

	function createChatroomSubmit(){
		var string = $('.create_input').val();
		if (($.trim( string )).length == 0) {
			return false;
		}
		var name = document.getElementById('name').value;
		var type = document.getElementById('type').value;
		var password = document.getElementById('password').value;

		if (name != '' && name != null) {
			name = name.replace(/^\s+|\s+$/g,"");

			if (type == 1 && password == '') {
				alert ('<?php echo $chatrooms_language[26];?>');
				return false;
			}

			if (type == 2) {
				password = 'i'+(Math.round(new Date().getTime()));
			}
			if (type == 0) {
				password = '';
			}

			$.post("chatrooms.php?action=createchatroom", {name: name, type:type, password: password} , function(data){				
				if (parseInt(data)!=0) {
					currentp = SHA1(password);
					name = urlencode(name);
					chatroom(data,name,type,currentp,1);	
				} else {
					alert('<?php echo $chatrooms_language[38];?>');
					createChatroom();
				}
			});
		}
		return false;
	}

	function getTimeDisplay(ts,id) {
		var style ="style=\"display:none;\"";

		if (typeof(jqcc.ccchattime)!='undefined' && jqcc.ccchattime.getEnabled(id)) {
			style="style=\"display:inline;\"";
		}
		var ap = "";
		var hour = ts.getHours();
		var minute = ts.getMinutes();		
		var date = ts.getDate();
		var month = ts.getMonth();
		
		if (armyTime != 1) {
			if (hour > 11) { ap = "pm"; } else { ap = "am"; }
			if (hour > 12) { hour = hour - 12; }
			if (hour == 0) { hour = 12; }
		} else {
			if (hour < 10) { hour = "0" + hour; }
		}

		if (minute < 10) { minute = "0" + minute; }

		var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

		var type = 'th';
		if (date == 1 || date == 21 || date == 31) { type = 'st'; }
		else if (date == 2 || date == 22) { type = 'nd'; }
		else if (date == 3 || date == 23) { type = 'rd'; }
			
		if (date != todaysDay) {
			return "<span class=\"cometchat_ts\" "+style+">("+hour+":"+minute+ap+" "+date+type+" "+months[month]+")</span>";
		} else {
			return "<span class=\"cometchat_ts\" "+style+">("+hour+":"+minute+ap+")</span>";
		}
	}

	function addMessage(id,incomingmessage,self,old,incomingid,selfadded,sent) {

		fromname = '<?php echo $chatrooms_language[6]; ?>';
		separator = '<?php echo $chatrooms_language[7]; ?>';

		if ($("#cometchat_message_"+incomingid).length > 0) { 
			$("#cometchat_message_"+incomingid+' .cometchat_chatboxmessagecontent').html(incomingmessage);
		} else {
			sentdata = '';
			if (sent != null) {
				var ts = new Date(sent * 1000);
				sentdata = getTimeDisplay(ts,id);
			}

			if (!fullName && fromname.indexOf(" ") != -1) {
				fromname = fromname.slice(0,fromname.indexOf(" "));
			}

			if (parseInt(selfadded) == 1) {
				incomingmessage = incomingmessage.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<br>").replace(/\"/g,"&quot;");

				if ($.cookie(cookie_prefix+"chatroomcolor") != '') {
					incomingmessage = '<span style="color:#'+$.cookie(cookie_prefix+"chatroomcolor")+'">'+incomingmessage+'</span>';
				}
			} 				
			$("#currentroom_convotext").append('<div class="cometchat_chatboxmessage" id="cometchat_message_'+incomingid+'"><span class="cometchat_chatboxmessagefrom"><strong>'+fromname+'</strong>'+separator+'</span><span class="cometchat_chatboxmessagecontent">'+incomingmessage+'</span>'+sentdata+'</div>');
		}			
	}

	function addRawMessage(incomingid,incomingmessage,fromname,sent) {
			timestamp=incomingid;
			separator = '<?php echo $chatrooms_language[7]; ?>';                  
			var bannedKicked = incomingmessage;				
			var bannedOrKicked=bannedKicked.split('_');
			if (bannedOrKicked[1]=='kicked' || bannedOrKicked[1]=='banned') {
				if (myid==bannedOrKicked[2]) {
					if (bannedOrKicked[1]=='kicked') {
						kickUser(bannedOrKicked[1],incomingid);												
						alert ('<?php echo $chatrooms_language[36];?>');
						leaveChatroom();
					}
					if (bannedOrKicked[1]=='banned') {
						banUser(bannedOrKicked[1],incomingid);
						alert ('<?php echo $chatrooms_language[37];?>');
						leaveChatroom(bannedOrKicked[2]);
					}												
				}	
			$("#cometchat_userlist_"+bannedOrKicked[2]).remove();
		  } else {
				if ($("#cometchat_message_"+incomingid).length > 0) { 
					$("#cometchat_message_"+incomingid+' .cometchat_chatboxmessagecontent').html(incomingmessage);
			} else {

				sentdata = '';

				if (sent != null) {
					var ts = new Date(sent);
					sentdata = getTimeDisplay(ts,incomingid);
				}

				if (!fullName && fromname.indexOf(" ") != -1) {
					fromname = fromname.slice(0,fromname.indexOf(" "));
				}
				
				$("#currentroom_convotext").append('<div class="cometchat_chatboxmessage" id="cometchat_message_'+incomingid+'"><span class="cometchat_chatboxmessagefrom"><strong>'+fromname+'</strong>'+separator+'</span><span class="cometchat_chatboxmessagecontent">'+incomingmessage+'</span>'+sentdata+'</div>');
													
				if ($.cookie(cookie_prefix+"sound") && $.cookie(cookie_prefix+"sound") == 'true') { } else {
					playsound();					
				}
			}
		}
		scrollDown();			
	}

	function chatboxKeyup(event,chatboxtextarea) {

		if (event.keyCode == 13 && event.shiftKey == 0)  {
			$(chatboxtextarea).val('');
		}
	 
		var adjustedHeight = chatboxtextarea.clientHeight;
		var maxHeight = 94;
		var height = getWindowHeight();

		if (maxHeight > adjustedHeight) {
			adjustedHeight = Math.max(chatboxtextarea.scrollHeight, adjustedHeight);
			if (maxHeight)
				adjustedHeight = Math.min(maxHeight, adjustedHeight);
			if (adjustedHeight > chatboxtextarea.clientHeight) {
				$(chatboxtextarea).css('height',adjustedHeight+6 +'px');
				$("#currentroom_convo").css('height',height-58-parseInt($('.cometchat_textarea').css('height'))-6);
				$(".slimScrollDiv").css('height',$("#currentroom_convo").css('height'));
				scrollDown();
			}
		} else {
			$(chatboxtextarea).css('overflow-y','auto');
		}
	}

	function hidetabs() {
		$('li').removeClass('tab_selected');
		$('#lobby').css('display','none');
		$('#currentroom').css('display','none');
		$('#create').css('display','none');
		$('#plugins').css('display','none');
	}

	function loadLobby() {
		hidetabs();
		$('#lobbytab').addClass('tab_selected');
		$('#lobby').css('display','block');
		$('.welcomemessage').html('<?php echo $chatrooms_language[1];?>');
		clearTimeout(heartbeatTimer);
		chatHeartbeat(1);
	}

	function checkDropDown(dropdown) {
		var id = $('#type').attr("selectedIndex");
		if (id == 1) {
			$('.password_hide').css('display','block');
		} else {
			$('.password_hide').css('display','none');
		} 
	}

	function loadRoom() {
		hidetabs();
		$('#plugins').css('display','block');
		$('#currentroom').css('display','block');
		$('#currentroomtab').css('display','block');
		$('#currentroomtab').addClass('tab_selected');
		$('.welcomemessage').html('<?php echo $chatrooms_language[4];?>'+'<?php echo $chatrooms_language[39];?>');
		document.cookie = '<?php echo $cookiePrefix;?>chatroom='+urlencode(currentroom+':'+currentp+':'+urlencode(currentroomname));
		if ($('#currentroomtab a').attr('show')==0) {
			$('#unbanuser').remove();
		}
		var pluginshtml = '';

		if (plugins.length > 0)	{
			
			pluginshtml += '<div class="cometchat_plugins">';
			for (var i = 0;i < plugins.length;i++) {
				var name = 'cc'+plugins[i];
				if (typeof($[name]) == 'object') {
					pluginshtml += '<img src="'+baseUrl+'plugins/'+plugins[i]+'/icon.png" class="cometchat_pluginsicon" title="'+$[name].getTitle()+'" onclick="javascript:jqcc.'+name+'.init('+currentroom+');">';
				}
			}
			pluginshtml += '</div>';
		}

		$('#plugins').html(pluginshtml);

		windowResize();
	}

	function inviteUser() {
		if (parseInt('<?php echo ($lightboxWindows);?>')== 1 && apiAccess==1) {
			parent.loadCCPopup(baseUrl+'modules/chatrooms/chatrooms.php?action=invite&roomid='+currentroom+'&inviteid='+currentp+'&roomname='+urlencode(currentroomname), 'invite',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=1, width=400,height=200",400,200,'<?php echo $chatrooms_language[21];?>'); 
		} else {
			window.open(baseUrl+'modules/chatrooms/chatrooms.php?action=invite&roomid='+currentroom+'&inviteid='+currentp+'&roomname='+urlencode(currentroomname), 'invite',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=1, width=400,height=200",400,200,'<?php echo $chatrooms_language[21];?>'); 
		}
	}

	function unbanUser() {
		if (parseInt('<?php echo ($lightboxWindows);?>')== 1 && apiAccess==1) {
			parent.loadCCPopup(baseUrl+'modules/chatrooms/chatrooms.php?action=unban&roomid='+currentroom+'&inviteid='+currentp+'&roomname='+urlencode(currentroomname)+'&time='+Math.random(), 'invite',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=1, width=400,height=200",400,200,'<?php echo $chatrooms_language[21];?>');
		} else {
			window.open(baseUrl+'modules/chatrooms/chatrooms.php?action=unban&roomid='+currentroom+'&inviteid='+currentp+'&roomname='+urlencode(currentroomname)+'&time='+Math.random(), 'invite',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=1, width=400,height=200",400,200,'<?php echo $chatrooms_language[21];?>');
		}
	}

	function loadChatroomPro(uid,owner,longname) {	
		if (parseInt('<?php echo ($lightboxWindows);?>')== 1 && apiAccess==1) {
			parent.loadCCPopup(baseUrl+'modules/chatrooms/chatrooms.php?action=loadChatroomPro&apiAccess='+apiAccess+'&owner='+owner+'&roomid='+currentroom+'&inviteid='+uid+'&roomname='+urlencode(currentroomname)+'&embed=web', 'loadChatroomPro',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=1, width=365,height=100",365,75,longname); 
		} else {
			window.open(baseUrl+'modules/chatrooms/chatrooms.php?action=loadChatroomPro&apiAccess='+apiAccess+'&owner='+owner+'&roomid='+currentroom+'&inviteid='+uid+'&roomname='+urlencode(currentroomname), 'loadChatroomPro',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=1, width=365,height=100",365,75,longname); 
		}
	}

	function silentroom(roomid, inviteid, roomname) {
		chatroom(roomid,roomname,1,inviteid,1);				
	}

	function checkPass(id,name,silent,password) {
		if (silent != 1) {		
			password=SHA1(password);
		}
		$.post("chatrooms.php?action=checkpassword", {password: password, id: id} , function(data) {
			if (data) {
				if (data != '0' && data != '2' ) {
					var splitdata=data.split('^');

					<?php if (USE_COMET == 1 && COMET_CHATROOMS == 1):?>
						cometuncall_function(currentroomcode);
						currentroomcode = splitdata[0];
						chatroomcall_function(currentroomcode);
					<?php endif;?>

					owner = parseInt(splitdata[1]);
					myid = parseInt(splitdata[2]);
					isModerator = parseInt(splitdata[3]);
					currentp = password;
					initializeRoom = 1;
					hidetabs();
					$("#cometchat_userlist_"+currentroom).removeClass("cometchat_chatroomselected");
					$("#cometchat_userlist_"+id).addClass("cometchat_chatroomselected");
					currentroom = id;
					ulh = '';
					timestamp = 0;
					currentroomname = name;

					if (owner || isModerator) {
						replaceHtml("currentroomtab",'<a href="javascript:void(0);" show=1 onclick="javascript:loadRoom()">'+name+'</a>');
					} else {
						replaceHtml("currentroomtab",'<a href="javascript:void(0);" show=0 onclick="javascript:loadRoom()">'+name+'</a>');
					}

					replaceHtml("currentroom_convotext",'<div></div>');
					replaceHtml("currentroom_users",'<div></div>');
					loadRoom();
					clearTimeout(heartbeatTimer);
					cu_uids.length=0;
					chatHeartbeat(1);
				} else {
					if (data==2) {
						if (silent != 1) {
							alert ('<?php echo $chatrooms_language[37]; ?>');
						}
					} else {
						alert ('<?php echo $chatrooms_language[23];?>');
					}
				}
			}
		});
	}

	function chatroom(id,name,type,invite,silent) {
		name = urldecode(name);
		if (currentroom != id) {
			password = '';

			if (invite != '') {
				password = invite;
			}

			if (type == 1 || type == 2) {
				if (silent != 1) {
					if (lightboxWindows == 1) {
						parent.loadCCPopup(baseUrl+'modules/chatrooms/chatrooms.php?id='+id+'&name='+name+'&silent='+silent+'&action=passwordBox&embed=web', 'passwordBox',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=1, width=400,height=100",400,100,'<?php echo $chatrooms_language[8];?>'); 
					} else {
						var temp = prompt('<?php echo $chatrooms_language[8];?>','')
						if (temp) {
							checkPass(id,name,silent,temp);
						} else {
							return;
						}
					}
						
				} else {
					checkPass(id,name,silent,password);
				}
			} else {
				checkPass(id,name,silent,password);
			}
			
		} else {		
			loadRoom();
			clearTimeout(heartbeatTimer);
			chatHeartbeat(1);
		}
	}

	function getWindowHeight() { 
		var windowHeight = 0; 
		if (typeof(window.innerHeight) == 'number') { 
			windowHeight = window.innerHeight; 
		} else { 
			if (document.documentElement && document.documentElement.clientHeight) { 
				windowHeight = document.documentElement.clientHeight; 
			} else { 
				if (document.body && document.body.clientHeight) { 
					windowHeight = document.body.clientHeight; 
				} 
			} 
		} 
		return windowHeight; 
	}

	function getWindowWidth() { 
		var windowWidth = 0; 
		if (typeof(window.innerWidth) == 'number') { 
			windowWidth = window.innerWidth; 
		} else { 
			if (document.documentElement && document.documentElement.clientWidth) { 
				windowWidth = document.documentElement.clientWidth; 
			} else { 
				if (document.body && document.body.clientWidth) { 
					windowWidth = document.body.clientWidth; 
				} 
			} 
		} 
		return windowWidth; 
	} 
	
	function scrollDown() {
		if (jqcc().slimScroll) {
			$('#currentroom_convo').slimScroll({scroll: '1'});
		} else {
			setTimeout(function() {
				$("#currentroom_convo").scrollTop(50000);
			},100);
		}
	}

	function chatHeartbeat(forceUpdate) {	
				
		$.ajax({
			url: "chatrooms.php?action=heartbeat",
			data: {timestamp: timestamp, currentroom: currentroom, clh: clh, ulh: ulh, currentp: currentp, popout:apiAccess, force: forceUpdate},
			type: 'post',
			cache: false,
			dataFilter: function(data) {
				if (typeof (JSON) !== 'undefined' && typeof (JSON.parse) === 'function')
				  return JSON.parse(data);
				else
				  return eval('(' + data + ')');
			},
			timeout: 10000,
			error: function() {
				clearTimeout(heartbeatTimer);
				heartbeatTime = minHeartbeat;
				heartbeatTimer = setTimeout( function() { chatHeartbeat(); },heartbeatTime);
			},
			success: function(data) {
				if (data) {

					var fetchedUsers = 0;
	 
					$.each(data, function(type,item) {

						if (type == 'logout') {
							window.location.reload();
						}

						if (type == 'chatrooms') {

							var temp = '';
	
							$.each(item, function(i,room) {

								longname = room.name;
								shortname = room.name;
								
								if (room.status == 'available') {
									onlineNumber++;
								}

								var selected = '';

								if (currentroom == room.id) {
									selected = ' cometchat_chatroomselected';
								}

								roomtype = '';
								roomowner = '';

								if (room.type != 0) {
									roomtype = '<?php echo $chatrooms_language[24];?>';
								}

								if (room.s == 1) {
									roomowner = '<?php echo $chatrooms_language[25];?>';
								}

								if (room.s == 2) {
									room.s = 1;
								}
								
								temp += '<div id="cometchat_userlist_'+room.id+'" class="lobby_room'+selected+'" onmouseover="jQuery(this).addClass(\'cometchat_userlist_hover\');" onmouseout="jQuery(this).removeClass(\'cometchat_userlist_hover\');" onclick="javascript:chatroom(\''+room.id+'\',\''+urlencode(shortname)+'\',\''+room.type+'\',\''+room.i+'\',\''+room.s+'\');" ><span class="lobby_room_1">'+longname+'</span><span class="lobby_room_2">'+room.online+' <?php echo $chatrooms_language[34];?></span><span class="lobby_room_3">'+roomtype+'</span><span class="lobby_room_4">'+roomowner+'</span><div style="clear:both"></div></div>';
							
							});	

							if (temp != '') {
								replaceHtml("lobby_rooms",'<div>'+temp+'</div>');
							}

						}

						if (type == 'clh') { 
							clh = item;
						}

						if (type == 'ulh') { 
							ulh = item;
						}
	
						if (type == 'messages') {
							
							var beepNewMessages = 0;

							$.each(item, function(i,incoming) {
								timestamp = incoming.id;
								if (incoming.message != '') {
									var temp = '';
									var fromname = incoming.from;
									var bannedKicked = incoming.message;																	
									var bannedOrKicked=bannedKicked.split('_');
									if (bannedOrKicked[0]=='CC^CONTROL') {
										if (bannedOrKicked[1]=='kicked' || bannedOrKicked[1]=='banned') {
											if (myid==bannedOrKicked[2]) {
													if (bannedOrKicked[1]=='kicked') {
														kickUser(bannedOrKicked[1],incoming.id);												
														alert ('<?php echo $chatrooms_language[36];?>');
														leaveChatroom();
													}
													if (bannedOrKicked[1]=='banned') {
														banUser(bannedOrKicked[1],incoming.id);
														alert ('<?php echo $chatrooms_language[37];?>');
														leaveChatroom(bannedOrKicked[2]);
													}												
												}	
												$("#cometchat_userlist_"+bannedOrKicked[2]).remove();
										}
									} else {								
										if ($("#cometchat_message_"+incoming.id).length > 0) { 
											$("#cometchat_message_"+incoming.id+' .cometchat_chatboxmessagecontent').html(incoming.message);
										} else {
											var ts = new Date(incoming.sent * 1000);

											if (!fullName && fromname.indexOf(" ") != -1) {
												fromname = fromname.slice(0,fromname.indexOf(" "));
											}

											if (incoming.fromid != myid) {								
												temp += ('<div class="cometchat_chatboxmessage" id="cometchat_message_'+incoming.id+'"><span class="cometchat_chatboxmessagefrom"><strong>');
												
												if (apiAccess && incoming.fromid != 0) {
													temp += ('<a href="javascript:void(0)" onclick="javascript:parent.jqcc.cometchat.chatWith(\''+incoming.fromid+'\');">');
												}

												temp += fromname;
												
												if (apiAccess && incoming.fromid != 0) {
													temp += ('</a>');
												}
												
												temp += ('</strong>:&nbsp;&nbsp;</span><span class="cometchat_chatboxmessagecontent">'+incoming.message+'</span>'+getTimeDisplay(ts,incoming.from)+'</div>');
												newMessages++;
												beepNewMessages++;
											} else {
												temp += ('<div class="cometchat_chatboxmessage" id="cometchat_message_'+incoming.id+'"><span class="cometchat_chatboxmessagefrom"><strong>'+fromname+'</strong>:&nbsp;&nbsp;</span><span class="cometchat_chatboxmessagecontent">'+incoming.message+'</span>'+getTimeDisplay(ts,incoming.from)+'</div>');
											}
										}
									}
									$('#currentroom_convotext').append(temp);
									scrollDown();
								}
							});

							heartbeatCount = 1;
							heartbeatTime = minHeartbeat;
							if (apiAccess == 1 && fetchedUsers == 0 && typeof (parent.jqcc.cometchat.setAlert) != 'undefined') {
								parent.jqcc.cometchat.setAlert('chatrooms',newMessages);
							}

							if ($.cookie(cookie_prefix+"sound") && $.cookie(cookie_prefix+"sound") == 'true') { } else {
								if (beepNewMessages > 0 && fetchedUsers == 0) {
									playsound();					
								}
							}
						}

						if (type == 'users') {

							var temp = '';
							var newUsers = {};
							var newUsersName = {};
							fetchedUsers = 1;		
							$.each(item, function(i,user) {

								longname = user.n;
								
								if (users[user.id] != 1 && initializeRoom == 0 && hideEnterExit == 0) {
									var ts = new Date();

									$("#currentroom_convotext").append('<div class="cometchat_chatboxalert" id="cometchat_message_0">'+user.n+'<?php echo $chatrooms_language[14]?>'+getTimeDisplay(ts,user.id)+'</div>');
									scrollDown();
								}
																
								if (parseInt(user.b)!=1) {										
									var avatar = '';
									if (user.a != '') {									
										avatar = '<span class="cometchat_userscontentavatar"><img class="cometchat_userscontentavatarimage" src='+user.a+'></span>';
									}
																		
								newUsers[user.id] = 1;
								newUsersName[user.id] = user.n;
								if (user.id == myid) {
									temp += '<div id="chatroom_userlist_'+user.id+'" class="cometchat_userlist" style="cursor:default !important;">'+avatar+'<span class="cometchat_userscontentname">'+longname+'</span></div>';
								} else {
									temp += '<div id="chatroom_userlist_'+user.id+'" class="cometchat_userlist" onmouseover="jqcc(this).addClass(\'cometchat_userlist_hover\');" onmouseout="jqcc(this).removeClass(\'cometchat_userlist_hover\');" onClick="loadChatroomPro('+user.id+','+owner+',\''+user.n+'\')">'+avatar+'<span class="cometchat_userscontentname">'+longname+'</span></div>';
								}
							}
						
						});	

							for (user in users) {
								if (users.hasOwnProperty(user)) {
									if (newUsers[user] != 1 && initializeRoom == 0 && hideEnterExit == 0) {
										var ts = new Date();

										$("#currentroom_convotext").append('<div class="cometchat_chatboxalert" id="cometchat_message_0">'+usersname[user]+'<?php echo $chatrooms_language[13]?>'+getTimeDisplay(ts,user.id)+'</div>');
										scrollDown();
									}
								}
							}

							replaceHtml("currentroom_users",'<div>'+temp+'</div>');
							users = newUsers;
							usersname = newUsersName;
							initializeRoom = 0;

						}
					});					

				}
				heartbeatCount++;
				
				if (heartbeatCount > 4) {
					heartbeatTime *= 2;
					heartbeatCount = 1;
				}

				if (heartbeatTime > maxHeartbeat) {
					heartbeatTime = maxHeartbeat;
				}				

				clearTimeout(heartbeatTimer);
				heartbeatTimer = setTimeout( function() { chatHeartbeat(); },heartbeatTime);			
			}
		});
	}

	function kickUser(kickid,kick){	
	  $.ajax({
                url: "chatrooms.php?action=kickUser",
                type: "POST",
                data: {kickid:kickid,currentroom:currentroom,kick:kick},
                success: function(s){					
					$("#chatroom_userlist_"+kickid).remove();
				}
            });
	}

	function banUser(banid,ban){		
	  $.ajax({
                url: "chatrooms.php?action=banUser",
                type: "POST",
                data: {banid:banid,currentroom:currentroom,ban:ban},
                success: function(s){
					$("#chatroom_userlist_"+banid).remove();
                }
            });
	}

	function windowResize() {
		var height = getWindowHeight();
		$(".content_div").css('height',height-58-3);
		$("#currentroom_convo").css('height',height-58-parseInt($('.cometchat_textarea').css('height'))-4-3);

		var width = getWindowWidth();
		$('#currentroom_left').css('width',width-144-41);
		$('.cometchat_textarea').css('width',width-174-41);

		if (jqcc().slimScroll) {
			$(".slimScrollDiv").css('height',$("#currentroom_convo").css('height'));
		}

	}

	$(document).ready(function() {		
		if (messageBeep == 1) {	

			$('<div id="cometchat_flashcontent"></div>').appendTo($("body"));
			so = new SWFObjectCC(baseUrl+'swf/sound.swf?2.5', "cometchatbeep", "1", "1", "8", '#000');
			so.addParam("allowscriptaccess","always");
			so.addParam('flashvars','base='+baseUrl);
			so.write("cometchat_flashcontent");
		}

		try {
			if (parent.jqcc.cometchat.ping() == 1) {
				apiAccess = 1;
				$("#popouttab").css('display','block');
			}
		} catch (e) {

		}

		windowResize();

		if (jqcc().slimScroll) {
			$("#currentroom_convo").slimScroll({height: $("#currentroom_convo").css('height')});
		}

		window.onresize = function(event) {
			windowResize();
		}

		$('#currentroom').mouseover(function() {
			newMessages = 0;
		});

		chatHeartbeat(1);

		$(".cometchat_textarea").keydown(function(event) {
			return chatboxKeydown(event,this);
		});

		$(".cometchat_tabcontentsubmit").click(function(event) {
			return chatboxKeydown(event,$(".cometchat_textarea"),1);
		});

		$(".cometchat_textarea").keyup(function(event) {
			return chatboxKeyup(event,this);
		});
		
		
});



	function replaceHtml(el, html) {
		var oldEl = typeof el === "string" ? document.getElementById(el) : el;
		/*@cc_on // Pure innerHTML is slightly faster in IE
			oldEl.innerHTML = html;
			return oldEl;
		@*/
		var newEl = oldEl.cloneNode(false);
		newEl.innerHTML = html;
		oldEl.parentNode.replaceChild(newEl, oldEl);
		return newEl;
	};

	function cometready() {
		// Do nothing
	}

	function SHA1(e){function rotate_left(n,s){var a=(n<<s)|(n>>>(32-s));return a};function lsb_hex(a){var b="";var i;var c;var d;for(i=0;i<=6;i+=2){c=(a>>>(i*4+4))&0x0f;d=(a>>>(i*4))&0x0f;b+=c.toString(16)+d.toString(16)}return b};function cvt_hex(a){var b="";var i;var v;for(i=7;i>=0;i--){v=(a>>>(i*4))&0x0f;b+=v.toString(16)}return b};function Utf8Encode(a){a=a.replace(/\r\n/g,"\n");var b="";for(var n=0;n<a.length;n++){var c=a.charCodeAt(n);if(c<128){b+=String.fromCharCode(c)}else if((c>127)&&(c<2048)){b+=String.fromCharCode((c>>6)|192);b+=String.fromCharCode((c&63)|128)}else{b+=String.fromCharCode((c>>12)|224);b+=String.fromCharCode(((c>>6)&63)|128);b+=String.fromCharCode((c&63)|128)}}return b};var f;var i,j;var W=new Array(80);var g=0x67452301;var h=0xEFCDAB89;var k=0x98BADCFE;var l=0x10325476;var m=0xC3D2E1F0;var A,B,C,D,E;var o;e=Utf8Encode(e);var p=e.length;var q=new Array();for(i=0;i<p-3;i+=4){j=e.charCodeAt(i)<<24|e.charCodeAt(i+1)<<16|e.charCodeAt(i+2)<<8|e.charCodeAt(i+3);q.push(j)}switch(p%4){case 0:i=0x080000000;break;case 1:i=e.charCodeAt(p-1)<<24|0x0800000;break;case 2:i=e.charCodeAt(p-2)<<24|e.charCodeAt(p-1)<<16|0x08000;break;case 3:i=e.charCodeAt(p-3)<<24|e.charCodeAt(p-2)<<16|e.charCodeAt(p-1)<<8|0x80;break}q.push(i);while((q.length%16)!=14)q.push(0);q.push(p>>>29);q.push((p<<3)&0x0ffffffff);for(f=0;f<q.length;f+=16){for(i=0;i<16;i++)W[i]=q[f+i];for(i=16;i<=79;i++)W[i]=rotate_left(W[i-3]^W[i-8]^W[i-14]^W[i-16],1);A=g;B=h;C=k;D=l;E=m;for(i=0;i<=19;i++){o=(rotate_left(A,5)+((B&C)|(~B&D))+E+W[i]+0x5A827999)&0x0ffffffff;E=D;D=C;C=rotate_left(B,30);B=A;A=o}for(i=20;i<=39;i++){o=(rotate_left(A,5)+(B^C^D)+E+W[i]+0x6ED9EBA1)&0x0ffffffff;E=D;D=C;C=rotate_left(B,30);B=A;A=o}for(i=40;i<=59;i++){o=(rotate_left(A,5)+((B&C)|(B&D)|(C&D))+E+W[i]+0x8F1BBCDC)&0x0ffffffff;E=D;D=C;C=rotate_left(B,30);B=A;A=o}for(i=60;i<=79;i++){o=(rotate_left(A,5)+(B^C^D)+E+W[i]+0xCA62C1D6)&0x0ffffffff;E=D;D=C;C=rotate_left(B,30);B=A;A=o}g=(g+A)&0x0ffffffff;h=(h+B)&0x0ffffffff;k=(k+C)&0x0ffffffff;l=(l+D)&0x0ffffffff;m=(m+E)&0x0ffffffff}var o=cvt_hex(g)+cvt_hex(h)+cvt_hex(k)+cvt_hex(l)+cvt_hex(m);return o.toLowerCase()}

	function MD5(j){function RotateLeft(a,b){return(a<<b)|(a>>>(32-b))}function AddUnsigned(a,b){var c,lY4,lX8,lY8,lResult;lX8=(a&0x80000000);lY8=(b&0x80000000);c=(a&0x40000000);lY4=(b&0x40000000);lResult=(a&0x3FFFFFFF)+(b&0x3FFFFFFF);if(c&lY4){return(lResult^0x80000000^lX8^lY8)}if(c|lY4){if(lResult&0x40000000){return(lResult^0xC0000000^lX8^lY8)}else{return(lResult^0x40000000^lX8^lY8)}}else{return(lResult^lX8^lY8)}}function F(x,y,z){return(x&y)|((~x)&z)}function G(x,y,z){return(x&z)|(y&(~z))}function H(x,y,z){return(x^y^z)}function I(x,y,z){return(y^(x|(~z)))}function FF(a,b,c,d,x,s,e){a=AddUnsigned(a,AddUnsigned(AddUnsigned(F(b,c,d),x),e));return AddUnsigned(RotateLeft(a,s),b)};function GG(a,b,c,d,x,s,e){a=AddUnsigned(a,AddUnsigned(AddUnsigned(G(b,c,d),x),e));return AddUnsigned(RotateLeft(a,s),b)};function HH(a,b,c,d,x,s,e){a=AddUnsigned(a,AddUnsigned(AddUnsigned(H(b,c,d),x),e));return AddUnsigned(RotateLeft(a,s),b)};function II(a,b,c,d,x,s,e){a=AddUnsigned(a,AddUnsigned(AddUnsigned(I(b,c,d),x),e));return AddUnsigned(RotateLeft(a,s),b)};function ConvertToWordArray(a){var b;var c=a.length;var d=c+8;var e=(d-(d%64))/64;var f=(e+1)*16;var g=Array(f-1);var h=0;var i=0;while(i<c){b=(i-(i%4))/4;h=(i%4)*8;g[b]=(g[b]|(a.charCodeAt(i)<<h));i++}b=(i-(i%4))/4;h=(i%4)*8;g[b]=g[b]|(0x80<<h);g[f-2]=c<<3;g[f-1]=c>>>29;return g};function WordToHex(a){var b="",WordToHexValue_temp="",lByte,lCount;for(lCount=0;lCount<=3;lCount++){lByte=(a>>>(lCount*8))&255;WordToHexValue_temp="0"+lByte.toString(16);b=b+WordToHexValue_temp.substr(WordToHexValue_temp.length-2,2)}return b};function Utf8Encode(a){a=a.replace(/\r\n/g,"\n");var b="";for(var n=0;n<a.length;n++){var c=a.charCodeAt(n);if(c<128){b+=String.fromCharCode(c)}else if((c>127)&&(c<2048)){b+=String.fromCharCode((c>>6)|192);b+=String.fromCharCode((c&63)|128)}else{b+=String.fromCharCode((c>>12)|224);b+=String.fromCharCode(((c>>6)&63)|128);b+=String.fromCharCode((c&63)|128)}}return b};var x=Array();var k,AA,BB,CC,DD,a,b,c,d;var l=7,S12=12,S13=17,S14=22;var m=5,S22=9,S23=14,S24=20;var o=4,S32=11,S33=16,S34=23;var p=6,S42=10,S43=15,S44=21;j=Utf8Encode(j);x=ConvertToWordArray(j);a=0x67452301;b=0xEFCDAB89;c=0x98BADCFE;d=0x10325476;for(k=0;k<x.length;k+=16){AA=a;BB=b;CC=c;DD=d;a=FF(a,b,c,d,x[k+0],l,0xD76AA478);d=FF(d,a,b,c,x[k+1],S12,0xE8C7B756);c=FF(c,d,a,b,x[k+2],S13,0x242070DB);b=FF(b,c,d,a,x[k+3],S14,0xC1BDCEEE);a=FF(a,b,c,d,x[k+4],l,0xF57C0FAF);d=FF(d,a,b,c,x[k+5],S12,0x4787C62A);c=FF(c,d,a,b,x[k+6],S13,0xA8304613);b=FF(b,c,d,a,x[k+7],S14,0xFD469501);a=FF(a,b,c,d,x[k+8],l,0x698098D8);d=FF(d,a,b,c,x[k+9],S12,0x8B44F7AF);c=FF(c,d,a,b,x[k+10],S13,0xFFFF5BB1);b=FF(b,c,d,a,x[k+11],S14,0x895CD7BE);a=FF(a,b,c,d,x[k+12],l,0x6B901122);d=FF(d,a,b,c,x[k+13],S12,0xFD987193);c=FF(c,d,a,b,x[k+14],S13,0xA679438E);b=FF(b,c,d,a,x[k+15],S14,0x49B40821);a=GG(a,b,c,d,x[k+1],m,0xF61E2562);d=GG(d,a,b,c,x[k+6],S22,0xC040B340);c=GG(c,d,a,b,x[k+11],S23,0x265E5A51);b=GG(b,c,d,a,x[k+0],S24,0xE9B6C7AA);a=GG(a,b,c,d,x[k+5],m,0xD62F105D);d=GG(d,a,b,c,x[k+10],S22,0x2441453);c=GG(c,d,a,b,x[k+15],S23,0xD8A1E681);b=GG(b,c,d,a,x[k+4],S24,0xE7D3FBC8);a=GG(a,b,c,d,x[k+9],m,0x21E1CDE6);d=GG(d,a,b,c,x[k+14],S22,0xC33707D6);c=GG(c,d,a,b,x[k+3],S23,0xF4D50D87);b=GG(b,c,d,a,x[k+8],S24,0x455A14ED);a=GG(a,b,c,d,x[k+13],m,0xA9E3E905);d=GG(d,a,b,c,x[k+2],S22,0xFCEFA3F8);c=GG(c,d,a,b,x[k+7],S23,0x676F02D9);b=GG(b,c,d,a,x[k+12],S24,0x8D2A4C8A);a=HH(a,b,c,d,x[k+5],o,0xFFFA3942);d=HH(d,a,b,c,x[k+8],S32,0x8771F681);c=HH(c,d,a,b,x[k+11],S33,0x6D9D6122);b=HH(b,c,d,a,x[k+14],S34,0xFDE5380C);a=HH(a,b,c,d,x[k+1],o,0xA4BEEA44);d=HH(d,a,b,c,x[k+4],S32,0x4BDECFA9);c=HH(c,d,a,b,x[k+7],S33,0xF6BB4B60);b=HH(b,c,d,a,x[k+10],S34,0xBEBFBC70);a=HH(a,b,c,d,x[k+13],o,0x289B7EC6);d=HH(d,a,b,c,x[k+0],S32,0xEAA127FA);c=HH(c,d,a,b,x[k+3],S33,0xD4EF3085);b=HH(b,c,d,a,x[k+6],S34,0x4881D05);a=HH(a,b,c,d,x[k+9],o,0xD9D4D039);d=HH(d,a,b,c,x[k+12],S32,0xE6DB99E5);c=HH(c,d,a,b,x[k+15],S33,0x1FA27CF8);b=HH(b,c,d,a,x[k+2],S34,0xC4AC5665);a=II(a,b,c,d,x[k+0],p,0xF4292244);d=II(d,a,b,c,x[k+7],S42,0x432AFF97);c=II(c,d,a,b,x[k+14],S43,0xAB9423A7);b=II(b,c,d,a,x[k+5],S44,0xFC93A039);a=II(a,b,c,d,x[k+12],p,0x655B59C3);d=II(d,a,b,c,x[k+3],S42,0x8F0CCC92);c=II(c,d,a,b,x[k+10],S43,0xFFEFF47D);b=II(b,c,d,a,x[k+1],S44,0x85845DD1);a=II(a,b,c,d,x[k+8],p,0x6FA87E4F);d=II(d,a,b,c,x[k+15],S42,0xFE2CE6E0);c=II(c,d,a,b,x[k+6],S43,0xA3014314);b=II(b,c,d,a,x[k+13],S44,0x4E0811A1);a=II(a,b,c,d,x[k+4],p,0xF7537E82);d=II(d,a,b,c,x[k+11],S42,0xBD3AF235);c=II(c,d,a,b,x[k+2],S43,0x2AD7D2BB);b=II(b,c,d,a,x[k+9],S44,0xEB86D391);a=AddUnsigned(a,AA);b=AddUnsigned(b,BB);c=AddUnsigned(c,CC);d=AddUnsigned(d,DD)}var q=WordToHex(a)+WordToHex(b)+WordToHex(c)+WordToHex(d);return q.toLowerCase()}function base64_encode(a){var b="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";var c,o2,o3,h1,h2,h3,h4,bits,i=0,ac=0,enc="",tmp_arr=[];if(!a){return a}a=this.utf8_encode(a+'');do{c=a.charCodeAt(i++);o2=a.charCodeAt(i++);o3=a.charCodeAt(i++);bits=c<<16|o2<<8|o3;h1=bits>>18&0x3f;h2=bits>>12&0x3f;h3=bits>>6&0x3f;h4=bits&0x3f;tmp_arr[ac++]=b.charAt(h1)+b.charAt(h2)+b.charAt(h3)+b.charAt(h4)}while(i<a.length);enc=tmp_arr.join('');switch(a.length%3){case 1:enc=enc.slice(0,-2)+'==';break;case 2:enc=enc.slice(0,-1)+'=';break}return enc}function base64_decode(a){var b="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";var c,o2,o3,h1,h2,h3,h4,bits,i=0,ac=0,dec="",tmp_arr=[];if(!a){return a}a+='';do{h1=b.indexOf(a.charAt(i++));h2=b.indexOf(a.charAt(i++));h3=b.indexOf(a.charAt(i++));h4=b.indexOf(a.charAt(i++));bits=h1<<18|h2<<12|h3<<6|h4;c=bits>>16&0xff;o2=bits>>8&0xff;o3=bits&0xff;if(h3==64){tmp_arr[ac++]=String.fromCharCode(c)}else if(h4==64){tmp_arr[ac++]=String.fromCharCode(c,o2)}else{tmp_arr[ac++]=String.fromCharCode(c,o2,o3)}}while(i<a.length);dec=tmp_arr.join('');dec=this.utf8_decode(dec);return dec}function utf8_decode(a){var b=[],i=0,ac=0,c1=0,c2=0,c3=0;a+='';while(i<a.length){c1=a.charCodeAt(i);if(c1<128){b[ac++]=String.fromCharCode(c1);i++}else if((c1>191)&&(c1<224)){c2=a.charCodeAt(i+1);b[ac++]=String.fromCharCode(((c1&31)<<6)|(c2&63));i+=2}else{c2=a.charCodeAt(i+1);c3=a.charCodeAt(i+2);b[ac++]=String.fromCharCode(((c1&15)<<12)|((c2&63)<<6)|(c3&63));i+=3}}return b.join('')}function utf8_encode(a){var b=(a+'');var c="";var d,end;var e=0;d=end=0;e=b.length;for(var n=0;n<e;n++){var f=b.charCodeAt(n);var g=null;if(f<128){end++}else if(f>127&&f<2048){g=String.fromCharCode((f>>6)|192)+String.fromCharCode((f&63)|128)}else{g=String.fromCharCode((f>>12)|224)+String.fromCharCode(((f>>6)&63)|128)+String.fromCharCode((f&63)|128)}if(g!==null){if(end>d){c+=b.substring(d,end)}c+=g;d=end=n+1}}if(end>d){c+=b.substring(d,b.length)}return c}

	function urlencode (string) {
		return base64_encode(string);
	}

	function urldecode (string) {
		return base64_decode(string);
	}

	// Copyright (c) 2006 Klaus Hartl (stilbuero.de)
	// http://www.opensource.org/licenses/mit-license.php

	jqcc.cookie=function(a,b,c){if(typeof b!='undefined'){c=c||{};if(b===null){b='';c.expires=-1}var d='';if(c.expires&&(typeof c.expires=='number'||c.expires.toUTCString)){var e;if(typeof c.expires=='number'){e=new Date();e.setTime(e.getTime()+(c.expires*24*60*60*1000))}else{e=c.expires}d='; expires='+e.toUTCString()}var f=c.path?'; path='+(c.path):'';var g=c.domain?'; domain='+(c.domain):'';var h=c.secure?'; secure':'';document.cookie=[a,'=',encodeURIComponent(b),d,f,g,h].join('')}else{var j=null;if(document.cookie&&document.cookie!=''){var k=document.cookie.split(';');for(var i=0;i<k.length;i++){var l=jqcc.trim(k[i]);if(l.substring(0,a.length+1)==(a+'=')){j=decodeURIComponent(l.substring(a.length+1));break}}}return j}};

	// SWFObject is (c) 2007 Geoff Stearns and is released under the MIT License
	// http://www.opensource.org/licenses/mit-license.php

	if(typeof deconcept=="undefined"){var deconcept=new Object();}if(typeof deconcept.util=="undefined"){deconcept.util=new Object();}if(typeof deconcept.SWFObjectCCUtil=="undefined"){deconcept.SWFObjectCCUtil=new Object();}deconcept.SWFObjectCC=function(_1,id,w,h,_5,c,_7,_8,_9,_a){if(!document.getElementById){return;}this.DETECT_KEY=_a?_a:"detectflash";this.skipDetect=deconcept.util.getRequestParameter(this.DETECT_KEY);this.params=new Object();this.variables=new Object();this.attributes=new Array();if(_1){this.setAttribute("swf",_1);}if(id){this.setAttribute("id",id);}if(w){this.setAttribute("width",w);}if(h){this.setAttribute("height",h);}if(_5){this.setAttribute("version",new deconcept.PlayerVersion(_5.toString().split(".")));}this.installedVer=deconcept.SWFObjectCCUtil.getPlayerVersion();if(!window.opera&&document.all&&this.installedVer.major>7){deconcept.SWFObjectCC.doPrepUnload=true;}if(c){this.addParam("bgcolor",c);}var q=_7?_7:"high";this.addParam("quality",q);this.setAttribute("useExpressInstall",false);this.setAttribute("doExpressInstall",false);var _c=(_8)?_8:window.location;this.setAttribute("xiRedirectUrl",_c);this.setAttribute("redirectUrl","");if(_9){this.setAttribute("redirectUrl",_9);}};deconcept.SWFObjectCC.prototype={useExpressInstall:function(_d){this.xiSWFPath=!_d?"expressinstall.swf":_d;this.setAttribute("useExpressInstall",true);},setAttribute:function(_e,_f){this.attributes[_e]=_f;},getAttribute:function(_10){return this.attributes[_10];},addParam:function(_11,_12){this.params[_11]=_12;},getParams:function(){return this.params;},addVariable:function(_13,_14){this.variables[_13]=_14;},getVariable:function(_15){return this.variables[_15];},getVariables:function(){return this.variables;},getVariablePairs:function(){var _16=new Array();var key;var _18=this.getVariables();for(key in _18){_16[_16.length]=key+"="+_18[key];}return _16;},getSWFHTML:function(){var _19="";if(navigator.plugins&&navigator.mimeTypes&&navigator.mimeTypes.length){if(this.getAttribute("doExpressInstall")){this.addVariable("MMplayerType","PlugIn");this.setAttribute("swf",this.xiSWFPath);}_19="<embed type=\"application/x-shockwave-flash\" src=\""+this.getAttribute("swf")+"\" width=\""+this.getAttribute("width")+"\" height=\""+this.getAttribute("height")+"\" style=\""+this.getAttribute("style")+"\"";_19+=" id=\""+this.getAttribute("id")+"\" name=\""+this.getAttribute("id")+"\" ";var _1a=this.getParams();for(var key in _1a){_19+=[key]+"=\""+_1a[key]+"\" ";}var _1c=this.getVariablePairs().join("&");if(_1c.length>0){_19+="flashvars=\""+_1c+"\"";}_19+="/>";}else{if(this.getAttribute("doExpressInstall")){this.addVariable("MMplayerType","ActiveX");this.setAttribute("swf",this.xiSWFPath);}_19="<object id=\""+this.getAttribute("id")+"\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" width=\""+this.getAttribute("width")+"\" height=\""+this.getAttribute("height")+"\" style=\""+this.getAttribute("style")+"\">";_19+="<param name=\"movie\" value=\""+this.getAttribute("swf")+"\" />";var _1d=this.getParams();for(var key in _1d){_19+="<param name=\""+key+"\" value=\""+_1d[key]+"\" />";}var _1f=this.getVariablePairs().join("&");if(_1f.length>0){_19+="<param name=\"flashvars\" value=\""+_1f+"\" />";}_19+="</object>";}return _19;},write:function(_20){if(this.getAttribute("useExpressInstall")){var _21=new deconcept.PlayerVersion([6,0,65]);if(this.installedVer.versionIsValid(_21)&&!this.installedVer.versionIsValid(this.getAttribute("version"))){this.setAttribute("doExpressInstall",true);this.addVariable("MMredirectURL",escape(this.getAttribute("xiRedirectUrl")));document.title=document.title.slice(0,47)+" - Flash Player Installation";this.addVariable("MMdoctitle",document.title);}}if(this.skipDetect||this.getAttribute("doExpressInstall")||this.installedVer.versionIsValid(this.getAttribute("version"))){var n=(typeof _20=="string")?document.getElementById(_20):_20;n.innerHTML=this.getSWFHTML();return true;}else{if(this.getAttribute("redirectUrl")!=""){document.location.replace(this.getAttribute("redirectUrl"));}}return false;}};deconcept.SWFObjectCCUtil.getPlayerVersion=function(){var _23=new deconcept.PlayerVersion([0,0,0]);if(navigator.plugins&&navigator.mimeTypes.length){var x=navigator.plugins["Shockwave Flash"];if(x&&x.description){_23=new deconcept.PlayerVersion(x.description.replace(/([a-zA-Z]|\s)+/,"").replace(/(\s+r|\s+b[0-9]+)/,".").split("."));}}else{if(navigator.userAgent&&navigator.userAgent.indexOf("Windows CE")>=0){var axo=1;var _26=3;while(axo){try{_26++;axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash."+_26);_23=new deconcept.PlayerVersion([_26,0,0]);}catch(e){axo=null;}}}else{try{var axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7");}catch(e){try{var axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6");_23=new deconcept.PlayerVersion([6,0,21]);axo.AllowScriptAccess="always";}catch(e){if(_23.major==6){return _23;}}try{axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash");}catch(e){}}if(axo!=null){_23=new deconcept.PlayerVersion(axo.GetVariable("$version").split(" ")[1].split(","));}}}return _23;};deconcept.PlayerVersion=function(_29){this.major=_29[0]!=null?parseInt(_29[0]):0;this.minor=_29[1]!=null?parseInt(_29[1]):0;this.rev=_29[2]!=null?parseInt(_29[2]):0;};deconcept.PlayerVersion.prototype.versionIsValid=function(fv){if(this.major<fv.major){return false;}if(this.major>fv.major){return true;}if(this.minor<fv.minor){return false;}if(this.minor>fv.minor){return true;}if(this.rev<fv.rev){return false;}return true;};deconcept.util={getRequestParameter:function(_2b){var q=document.location.search||document.location.hash;if(_2b==null){return q;}if(q){var _2d=q.substring(1).split("&");for(var i=0;i<_2d.length;i++){if(_2d[i].substring(0,_2d[i].indexOf("="))==_2b){return _2d[i].substring((_2d[i].indexOf("=")+1));}}}return "";}};deconcept.SWFObjectCCUtil.cleanupSWFs=function(){var _2f=document.getElementsByTagName("OBJECT");for(var i=_2f.length-1;i>=0;i--){_2f[i].style.display="none";for(var x in _2f[i]){if(typeof _2f[i][x]=="function"){_2f[i][x]=function(){};}}}};if(deconcept.SWFObjectCC.doPrepUnload){if(!deconcept.unloadSet){deconcept.SWFObjectCCUtil.prepUnload=function(){__flash_unloadHandler=function(){};__flash_savedUnloadHandler=function(){};window.attachEvent("onunload",deconcept.SWFObjectCCUtil.cleanupSWFs);};window.attachEvent("onbeforeunload",deconcept.SWFObjectCCUtil.prepUnload);deconcept.unloadSet=true;}}if(!document.getElementById&&document.all){document.getElementById=function(id){return document.all[id];};}var getQueryParamValue=deconcept.util.getRequestParameter;var FlashObject=deconcept.SWFObjectCC;var SWFObjectCC=deconcept.SWFObjectCC;

<?php

foreach ($crplugins as $plugin) {
	if (file_exists(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."plugins".DIRECTORY_SEPARATOR.$plugin.DIRECTORY_SEPARATOR."chatrooms.js")) {
		include_once (dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."plugins".DIRECTORY_SEPARATOR.$plugin.DIRECTORY_SEPARATOR."chatrooms.js");
	}	
}

if (USE_COMET == 1 && COMET_CHATROOMS == 1) {
	include_once (dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."transports".DIRECTORY_SEPARATOR.TRANSPORT.DIRECTORY_SEPARATOR.'config.php');
	include_once (dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."transports".DIRECTORY_SEPARATOR.TRANSPORT.DIRECTORY_SEPARATOR.'includes.php');
}

if ($lightboxWindows == 1) {
	include_once (dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'scroll.js');
}