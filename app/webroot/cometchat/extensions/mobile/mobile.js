<?php
include_once dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules.php";
include_once (dirname(__FILE__).DIRECTORY_SEPARATOR."js".DIRECTORY_SEPARATOR."jqm.js");
include_once (dirname(__FILE__).DIRECTORY_SEPARATOR."js".DIRECTORY_SEPARATOR."iscroll.js");?>
var chatScroll,lobbyScroll,chatroomScroll,woScroll,baseurl="<?php echo BASE_URL; ?>";;
document.addEventListener('DOMContentLoaded', loaded, false);
var cookie_prefix='<?php echo $cookiePrefix; ?>';
<?php 
include_once (dirname(__FILE__).DIRECTORY_SEPARATOR."js".DIRECTORY_SEPARATOR."chat.js");
include_once (dirname(__FILE__).DIRECTORY_SEPARATOR."js".DIRECTORY_SEPARATOR."chatrooms.js");
?>

$.mobile.transitionFallbacks.slide = "none";

function loaded() {
		setTimeout(function () {
			
			document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
			chatScroll = new iScroll('chatcontent');
			chatroomScroll = new iScroll('chatroomcontent');
			lobbyScroll = new iScroll('lobbycontent', {
                onBeforeScrollStart: function (e) {
                        var target = e.target;
                        while (target.nodeType != 1) target = target.parentNode;

                        if (target.tagName != 'SELECT' && target.tagName != 'INPUT' && target.tagName != 'TEXTAREA')
                                e.preventDefault();
                }
			});
			woScroll = new iScroll('wocontent', {
                onBeforeScrollStart: function (e) {
                        var target = e.target;
                        while (target.nodeType != 1) target = target.parentNode;

                        if (target.tagName != 'SELECT' && target.tagName != 'INPUT' && target.tagName != 'TEXTAREA')
                                e.preventDefault();
                }
			});
		},200);
}


$(document).ready(function(){
	
	$('#buddy, #lobby').live('pagebeforeshow',function(event){
		setTimeout(function () { 
			lobbyScroll.refresh();
			woScroll.refresh();
		}, 200);
		$('#' + $(this).attr('id') + '_link').addClass('ui-btn-active');
	});
	setInterval(function(){
		$('.notifier').each(function(){
			if($(this).html() == 0){
				$(this).css('display','none');
			}
			else{
				$(this).css('display','block');
			}
		});
	},100);
	
	$('#buddy').live('pageinit',function(){
		$('.chatlink .ui-icon').html('<span class="notifier">0</span>');
	});

	$('#lobby').live('pageinit',function(){
		$('.chatlink .ui-icon').html('<span class="notifier">0</span>');
	});
	$('#chatroommessage').live('keydown',function(event){
			return chatboxKeydown(event,this);
	});

	$('#chatmessage').live('click',function(event){
		setTimeout(function () {scrollToBottom();},500);
	});

	$('#chatroommessage').live('click',function(event){
		setTimeout(function () {crscrollToBottom();},500);
	});
	$('#chatroom').live("pageinit", function(){
		getChatroomCookie();
	});

	$('#chat').live("pageinit", function(){
		jqcc.mobilewebapp.getChatCookie();
	});
});
function scrollToBottom(){
	if($('#scroller').height() > $('#chatcontent').height()){
		setTimeout(function () {
			chatScroll.scrollToElement('#cwlist li:last-child', 100);
		},500);
	}
}

function crscrollToBottom(){
	if($('#crscroller').height() > $('#chatroomcontent').height()){
		setTimeout(function () {
			chatroomScroll.scrollToElement('#currentroom_convotext div:last-child', 100);
		},500);
	}
}

function refreshLists(id){
	$('#'+id).listview("refresh");
}


	
function loadChatbox(){
	$.mobile.changePage("#chat",{transition:"none"});
}

function loadChatboxReverse(){
	$.mobile.changePage("#buddy",{transition:"none",reverse: true});
	jqcc.mobilewebapp.back();
}

function loadChatroom(){ 
	$.mobile.changePage("#chatroom",{transition:"none"});
}

function loadChatroomReverse(){
	$.mobile.changePage("#chatroom",{transition:"none",reverse: true});
}

function loadLobbyReverse(){
	$.mobile.changePage("#lobby",{transition:"none",reverse: true});
}

function showChatroomUser(){
	$.mobile.changePage("#chatroomuser",{transition:"none"});
}

function chatwith(){
	$.mobile.changePage("#chat",{transition:"none"});
	return true;
}

function createChatroom(){
	$.mobile.changePage("#createChatroom",{transition:"none"});
}

function refreshPage(){
	location.reload();
}

function getCookieInfo(cookieName){
		var i,x,y,ARRcookies=document.cookie.split(";");
		for (i=0;i<ARRcookies.length;i++)
		{
			x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
			y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
			x=x.replace(/^\s+|\s+$/g,"");
			if (x==cookieName)
			{
				return unescape(y);
			}
		}
}

