<?php 
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'config.php');
include dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";
if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
	include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
}
?>
var gamessource = {};
var gamesheight = {};
var gameswidth = {};
var gamestag = {};
var gamesslug = {};
var gamesname = {};
var categorygames = {};
var keywords = "<?php echo $keywordlist; ?>";
var keywordmatch = new RegExp(keywords.toLowerCase());
var useridentifier="";
var uname="";
var gameajax="null";

var apiAccess = 0;
var lightboxWindows = '<?php echo $lightboxWindows;?>';

$(document).ready(function() {

	$('.leadtitle').html('<img src="leaderboard.png" /><span><?php echo $games_language[5]; ?></span>');
	
	try {
		if (parent.jqcc.cometchat.ping() == 1) {
			apiAccess = 1;
		}
	} catch (e) {
	}

	$.post('../../cometchat_getid.php',{}, function(data){useridentifier=data['id']; uname=data['n'];},"json");				
	
	if (jQuery().slimScroll) {
		$('#categories').slimScroll({height: '270px',allowPageScroll: false});
		$("#categories").css("height","268px");			
	}

	$(".slimScrollDiv").css("float","left");
	$('#loader').css('display','block');
	$('#categories').html("<li>"+"<?php echo $games_language[2]; ?>"+"</li>");	
	var catog=new Array("action","adventure","board-game","casino","driving","dress-up","fighting","puzzles","customize","shooting","sports","strategy","education","rhythm","jigsaw","other");
	var categoriesinfo = '';
	var cnt=0;

	for(x in catog){				
		if (catog[x].toLowerCase().match(keywordmatch) != null) {					
		} else {
			if(cnt==0){
				var firstcat=catog[x];
			}
			var cato=catog[x];
			cato=$.trim(cato);
			cato=cato.replace(" ", "_space_");
			categoriesinfo += '<li id=\''+catog[x]+'\' onclick="javascript:getCategory(\''+catog[x]+'\',0)">'+catog[x]+'</li>';
			cnt=1;
		}
	}

	$('#loader').css('display','none');
	$('#categories').html(categoriesinfo);

	if (jQuery().slimScroll) {
		$("#categories").slimScroll({resize: '1'});
	}

	if(firstcat){
		getCategory(firstcat,0);
	}

	$(".leftarrowdiv").click(function(){
		var off= parseInt($(this).attr("offset"))-12;
		if(off<12) {
			off=0;
		}
		if($(".pageno").html()!="1"){
			getCategory($(this).attr("cat"),off);
		}
	});

	$(".rightarrowdiv").click(function(){
		var off= parseInt($(this).attr("offset"))+12;
		getCategory($(this).attr("cat"),off);
	});

	$(".scorepro").live("click",function() {

		var temp1='<div class="profilebox" style="float:left;width:353px;"><div class="avatar"><img src="'+$(this).find(".avatarf").attr("src")+'" class="avtrimg" /></div><div class="userleft"><div class="username" >'+$(this).find(".fname").html()+'</div><span class="score"><?php echo $games_language[9]; ?>: '+$(this).attr("totscore")+'</span><span class="tot"><?php echo $games_language[10]; ?>: '+$(this).attr("totplay")+'</span></div><div style="width:100%;float:left;clear:left;"><div style="width:100%;float:left;clear:left;"><div class="latest" ></div></div><div style="width:100%;float:left;clear:left;"><div class="highest" ></div></div></div></div>'; 

		$.ajax({
			url:"profile.php",
			type:"POST",
			data:{uid:$(this).attr("uid")},
			success:function(reply){	
				$("#games").html(temp1);
				var str="";
				$.each( reply, function(type,value){	
					if (type == "latest") {
						var str1='<div class="leadtitle2"><?php echo $games_language[7]; ?></div>';
						$.each( value, function(i,game){
							str1=str1+'<div style="width:100%;float:left;"><div class="gamename" style="overflow:hidden;">'+game.gn+'</div><div class="gamescore" >'+game.sc+'</div> </div>';
						});
						$(".latest").html(str1);
					}

					if (type == "highest") {
						var str1='<div class="leadtitle2"><?php echo $games_language[4]; ?></div>';
						$.each(value, function(i,game){
							str1=str1+'<div style="width:100%;float:left;"><div class="gamename" style="overflow:hidden;">'+game.gn+'</div><div class="gamescore" >'+game.sc+'</div> </div>';
						});
						$(".highest").html(str1);
					}
				});
			},
			dataType:"json",
			async:false
		});
	});

	$(".leadtitle").click(function() {
		$('#categories li').removeClass('catselected');
		$(this).addClass("catselected");
		$(".paginator").hide(0);
		$('#games').css("height","300px");

		if (jQuery().slimScroll) {
			$("#games").slimScroll({height: '300px',width:'358px',allowPageScroll: false});
			$("#games").parent().css("height","300px");
		}
		if(gameajax!="null"){
			gameajax.abort();
		}

		gameajax=$.ajax({
			url:"search.php",
			type:"POST",
			data:{},
			async:false,
			dataType:"json",
			success:function(reply){
				var str = '<div class="scorepro scoreprotitle"><div class="fname" style="margin-left: 47px"><?php echo $games_language[8]; ?></div><div class="fscore"><?php echo $games_language[9]; ?></div><div class="ftot"><?php echo $games_language[10]; ?></div><div style="clear:both"></div></div>';
				gameajax="null";
				if(reply.length>0){
					for(x in reply){	
						str=str+'<div class="scorepro" uid='+reply[x]['id']+' totscore='+reply[x]["tsc"]+' totplay='+reply[x]["gc"]+' ><img class="avatarf" src="'+reply[x]["a"]+'"  /><div class="fname">'+reply[x]['n']+'</div><div class="fscore" >'+reply[x]["tsc"]+'</div><div class="ftot">'+reply[x]["gc"]+'</div><div style="clear:both"></div></div>';
					}
				}else{
					str+="<div class='norecords'><?php echo $games_language[11]; ?></div>";
				}
				$('#loader').css('display','none');			
				$("#games").html(str);
			}
		});
	});	
	
}); 

function getCategory(catname,offset){
	
	gamessource= {};
	gamesheight= {};
	gameswidth= {};
	gamestag={};
	gamesslug={};
	gamesname={};
	
	var disp=0;
	var i=0;
	$(".paginator").show(0);
	$('#games').css("height","270px");

	if (jQuery().slimScroll) {
		$("#games").slimScroll({height: '271px',width:'358px',allowPageScroll: false});
		$("#games").parent().css("height","271px");
	}

	$(".leadtitle").removeClass('catselected');
	$('#categories li').removeClass('catselected');
	$('#'+catname).addClass('catselected');
	catname=$.trim(catname);
	catname=catname.replace("_space_"," " );
	$(".rightarrowdiv").attr("cat",catname);
	$(".leftarrowdiv").attr("cat",catname);
	$(".rightarrowdiv").attr("offset",offset);
	$(".leftarrowdiv").attr("offset",offset);
	$(".pageno").html((offset/12)+1);

	if($(".pageno").html()=="1") {
		$(".leftarrowdiv").css("opacity","0.2");
	} else {
		$(".leftarrowdiv").css("opacity","1");
	}

	$('#games').html('');
	$('#loader').css('display','block');
	if (jQuery().slimScroll) {
		$(".slimScrollBar").css('top','0px');			
	}

	$(".rightmenu").html("");
	
	$(".rightmenu").hide(0);

	if(gameajax!="null"){
		gameajax.abort();
	}

	gameajax=$.ajax({
			url:"//feedmonger.mochimedia.com/feeds/query/",
			type:"GET",
			dataType: 'jsonp',
			jsonpCallback: "mochi",
			data:{q:'category:'+catname,
					offset:offset,
					limit:'12'
					},
			success:function(reply){
				 gameajax="null";
				 for(x in reply['games']){
					var name = reply['games'][x]['name'];
					var thumbnail = reply['games'][x]['thumbnail_url'];
					var width = reply['games'][x]['width'];
					var height = reply['games'][x]['height'];
					var source = reply['games'][x]['swf_url'];
					var tag=reply['games'][x]['gameid'];
					var slug=reply['games'][x]['slug'];
					var le=reply['games'][x]['leaderboard_enabled'];
					gamessource[i] = source;
					gamesheight[i] = height;
					gameswidth[i] = width;
					gamestag[i]=tag;
					gamesslug[i]=slug;
					gamesname[i]=name;
					
					if (name.toLowerCase().match(keywordmatch) != null) {
						
					} else {
						if(le) {
							$('#games').append('<div class="gamelist '+slug+'" onclick="javascript:loadGame(\''+i+'\')"><img src="leaderboard.png" class="leadicon"/><img src="'+thumbnail+'" style="border-radius:5px;"/><br/><div class="title">'+name+'</div></div>');
						}else{
							$('#games').append('<div class="gamelist '+slug+'" onclick="javascript:loadGame(\''+i+'\')"><img src="'+thumbnail+'" style="border-radius:5px;"/><br/><div class="title">'+name+'</div></div>');
						}
						i++;
					}				
				}
				$('#loader').css('display','none');	
				if (jQuery().slimScroll) {
					$("#games").slimScroll({resize: '1'});
				}
			}
		});
}
function mochi(){
//do nothing
}
function loadGame(id) {
	var options = {partnerID: $.trim("<?php echo $partner_id; ?>"), id: "leaderboard_bridge"};
	if(useridentifier && uname) {
		options.userID = useridentifier;
		options.username = uname; 
	} else {
		options.userID 	 = 81772545423;
		options.username = 'guest'; 
	}
	options.callback = function (params) { 
		$.ajax({
			url:"gateway.php",
			type:"POST",
			data:{params:params,gamename:gamesname[id]},
			dataType:"json",
			async:false,
			success:function(reply){}
		});			
	}
	options.width = 1;
	options.height = 1;
	options.debug = "true";
	Mochi.addLeaderboardIntegration(options);
	loadCCPopup(gamessource[id], 'singleplayergame',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=0, width="+gameswidth[id]+",height="+gamesheight[id]+"",gameswidth[id],gamesheight[id],gamesname[id],1);
}

function loadCCPopup(url,name,properties,width,height,title,force) {
	if (apiAccess == 1 && lightboxWindows == 1) {
		parent.loadCCPopup(url,name,properties,width,height,title,force);
	} else {
		var w = window.open(url,name,properties);
		w.focus();
	}
}