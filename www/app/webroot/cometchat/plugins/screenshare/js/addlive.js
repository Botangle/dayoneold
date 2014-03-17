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

include_once dirname(dirname(dirname(dirname(__FILE__)))).DIRECTORY_SEPARATOR."plugins.php";
include_once dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."config.php";

include_once dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
	include_once dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
}

foreach ($screenshare_language as $i => $l) {
	$screenshare_language[$i] = str_replace("'", "\'", $l);
}

?>
/**
 * Id of media scope to connect to upon user's request.
 * @type {String}
 */
/**
 * Configuration of the streams to publish upon connection established
 * @type {Object}
 */
CONNECTION_CONFIGURATION={autopublishVideo:false,autopublishAudio:false};var scrHeight=<?php echo $scrHeight;?>;var scrWidth=<?php echo $scrWidth;?>;var lightboxWindows=<?php echo $lightboxWindows;?>;SCREEN_SHARING_SRC_PRV_WIDTH=240;SCREEN_SHARING_SRC_PRV_HEIGHT=160;isConnected=false;sharedItemId=null;SCREEN_SHARING_ITM_WIDGET_TMPL=null;function onDomReady(){var initOptions={applicationId:<?php echo $applicationid;?>};initializeAddLiveQuick(onPlatformReady,initOptions);SCREEN_SHARING_ITM_WIDGET_TMPL=$('<li class="scr-share-src-itm">'+'<img src="/shared-assets/no_screenshot_available.png"/>'+'<p><\/p>'+'<\/li>');};function initializeAddLiveQuick(completeHandler,options){var initListener=new ADL.PlatformInitListener();initListener.onInitStateChanged=function(e){switch(e.state){case ADL.InitState.ERROR:console.log("Failed to initialize the AddLive SDK Reason: "+e.errMessage+' ('+e.errCode+')');break;case ADL.InitState.INITIALIZED:completeHandler();$('#installBtn').hide();break;case ADL.InitState.INSTALLATION_REQUIRED:$('#message').show();$('#installBtn').attr('href',e.installerURL).css('display','block');break;case ADL.InitState.DEVICES_INIT_BEGIN:break;case ADL.InitState.BROWSER_RESTART_REQUIRED:alert("Please restart your browser in order to complete platform auto-update");break;default:console.log("Got unsupported init state: "+e.state);}};ADL.initPlatform(initListener,options);};function onPlatformReady(){initServiceListener();refreshScreenShareSources();connect();};function initServiceListener(){var listener=new ADL.AddLiveServiceListener();var handlePublishEvent=function(e){if(e.screenPublished){ADL.renderSink({sinkId:e.screenSinkId,containerId:'renderRemoteUser'});$('#remoteUserIdLbl').html(e.userId);console.log('connect');}else{$('#renderRemoteUser').html('<img src="loading.gif" style="position: absolute; top: 30%; left: 43%;"><p id="message" style="position: absolute;top: 10;left: 12;color:#ffffff;">User is selecting a new window to share.</p>');console.log('stop');$('#remoteUserIdLbl').html('undefined');}};listener.onUserEvent=handlePublishEvent;listener.onMediaStreamEvent=handlePublishEvent;listener.onVideoFrameSizeChanged=function(e){$('#renderRemoteUser').css(fitDims(e.width,e.height,scrWidth,scrHeight));};ADL.getService().addServiceListener(ADL.createResponder(),listener);};function connect(){var connDescriptor=$.extend({},CONNECTION_CONFIGURATION);connDescriptor.scopeId=scopeid;var userId=genRandomUserId();connDescriptor.authDetails=genAuthDetails(connDescriptor.scopeId,userId);var onSucc=function(){isConnected=true;};var onErr=function(errCode,errMessage){alert('Failed to establish the connection due to: '+errMessage+'(err code: '+errCode+')');};ADL.getService().connect(ADL.createResponder(onSucc,onErr),connDescriptor);};function refreshScreenShareSources(){$('#refreshBtn').unbind('click').addClass('disabled');ADL.getService().getScreenCaptureSources(ADL.createResponder(showScreenShareSources),SCREEN_SHARING_SRC_PRV_WIDTH);};function publishShareItem(shareItemId){if(isConnected){var onSucc=function(){};var onErr=function(){alert('Screen share source publishing failed');};ADL.getService().publish(ADL.createResponder(onSucc,onErr),scopeid,ADL.MediaType.SCREEN,{windowId:shareItemId,nativeWidth:960});}else{}};function unpublishShareItem(callback){var onSucc=function(){if(callback){callback();}};var onErr=function(){alert('Screen share source unpublishing failed');};ADL.getService().unpublish(ADL.createResponder(onSucc,onErr),scopeid,ADL.MediaType.SCREEN);};function showScreenShareSources(sources){var $srcsList=$('#screenShareSources');$srcsList.html('');$.each(sources,screenSharingItemAppender);var $refreshBtn=$('#refreshBtn');if($refreshBtn.hasClass('disabled')){$refreshBtn.click(refreshScreenShareSources).removeClass('disabled');}var select=setInterval(function(){$('#shareItm0').click();clearTimeout(select);},4000);};function screenSharingItemAppender(i,src){var $srcWrapper=SCREEN_SHARING_ITM_WIDGET_TMPL.clone();if(sharedItemId==src.id){$srcWrapper.addClass('selected');}$srcWrapper.attr('id','shareItm'+i);if(src.image.base64){$srcWrapper.find('img').attr('src','data:image/png;base64,'+src.image.base64).css(fitDims(src.image.width,src.image.height,SCREEN_SHARING_SRC_PRV_WIDTH,SCREEN_SHARING_SRC_PRV_HEIGHT));}$srcWrapper.find('p').text(src.title);$srcWrapper.attr('share-itm-id',src.id);$srcWrapper.click(screenSharingItmClickHandler);$srcWrapper.appendTo($('#screenShareSources'));};function screenSharingItmClickHandler(){var $this=$(this);if($this.hasClass('selected')){$this.removeClass('selected');sharedItemId=null;unpublishShareItem();}else{$('.scr-share-src-itm').removeClass('selected');$this.addClass('selected');var shareItemId=$this.attr('share-itm-id');var publishFunction=function(){sharedItemId=shareItemId;publishShareItem(shareItemId);};if(sharedItemId===null){publishFunction();}else{unpublishShareItem(publishFunction);}}};function genAuthDetails(scopeId,userId){var dateNow=new Date();var now=Math.floor((dateNow.getTime()/1000));var authDetails={expires:now+(5*60),userId:userId,salt:randomString(100)};var signatureBody=<?php echo $applicationid;?>+scopeId+userId+authDetails.salt+authDetails.expires+'<?php echo $appAuthSecret;?>';authDetails.signature=CryptoJS.SHA256(signatureBody).toString(CryptoJS.enc.Hex).toUpperCase();return authDetails;};function fitDims(srcW,srcH,targetW,targetH){var srcAR=srcW/srcH;var targetAR=targetW/targetH;var width,height,padding;if(srcW<targetW&&srcH<targetH){return{width:srcW,height:srcH,'margin-top':(targetH-srcH)/2,'margin-bottom':(targetH-srcH)/2,'margin-left':(targetW-srcW)/2};}if(srcAR<targetAR){height=targetH;width=srcW*targetH/srcH;padding=(targetW-width)/2;return{width:width,height:height,'margin-left':padding,'margin-right':padding,'margin-top':0,'margin-bottom':0};}else{width=targetW;height=targetW*srcH/srcW;padding=(targetH-height)/2;return{width:width,height:height,'margin-left':0,'margin-right':0,'margin-top':padding,'margin-bottom':padding};}};function genRandomUserId(){return Math.floor(Math.random()*10000)};function randomString(len,charSet){charSet=charSet||'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';var str='';for(var i=0;i<len;i++){var randomPoz=Math.floor(Math.random()*charSet.length);str+=charSet.substring(randomPoz,randomPoz+1);}return str;};$(onDomReady);function settings(){$('#settings').css('display','block');}function closeSetting(){$('#settings').hide();}if($('#installBtn').length>0){$('#info').css('display','none');}
/*
 CryptoJS v3.0.2
 code.google.com/p/crypto-js
 (c) 2009-2012 by Jeff Mott. All rights reserved.
 code.google.com/p/crypto-js/wiki/License
 */
var CryptoJS=CryptoJS||function(i,p){var f={},q=f.lib={},j=q.Base=function(){function a(){}return{extend:function(h){a.prototype=this;var d=new a;h&&d.mixIn(h);d.$super=this;return d},create:function(){var a=this.extend();a.init.apply(a,arguments);return a},init:function(){},mixIn:function(a){for(var d in a)a.hasOwnProperty(d)&&(this[d]=a[d]);a.hasOwnProperty("toString")&&(this.toString=a.toString)},clone:function(){return this.$super.extend(this)}}}(),k=q.WordArray=j.extend({init:function(a,h){a=this.words=a||[];this.sigBytes=h!=p?h:4*a.length},toString:function(a){return(a||m).stringify(this)},concat:function(a){var h=this.words,d=a.words,c=this.sigBytes,a=a.sigBytes;this.clamp();if(c%4)for(var b=0;b<a;b++)h[c+b>>>2]|=(d[b>>>2]>>>24-8*(b%4)&255)<<24-8*((c+b)%4);else if(65535<d.length)for(b=0;b<a;b+=4)h[c+b>>>2]=d[b>>>2];else h.push.apply(h,d);this.sigBytes+=a;return this},clamp:function(){var a=this.words,b=this.sigBytes;a[b>>>2]&=4294967295<<32-8*(b%4);a.length=i.ceil(b/4)},clone:function(){var a=j.clone.call(this);a.words=this.words.slice(0);return a},random:function(a){for(var b=[],d=0;d<a;d+=4)b.push(4294967296*i.random()|0);return k.create(b,a)}}),r=f.enc={},m=r.Hex={stringify:function(a){for(var b=a.words,a=a.sigBytes,d=[],c=0;c<a;c++){var e=b[c>>>2]>>>24-8*(c%4)&255;d.push((e>>>4).toString(16));d.push((e&15).toString(16))}return d.join("")},parse:function(a){for(var b=a.length,d=[],c=0;c<b;c+=2)d[c>>>3]|=parseInt(a.substr(c,2),16)<<24-4*(c%8);return k.create(d,b/2)}},s=r.Latin1={stringify:function(a){for(var b=a.words,a=a.sigBytes,d=[],c=0;c<a;c++)d.push(String.fromCharCode(b[c>>>2]>>>24-8*(c%4)&255));return d.join("")},parse:function(a){for(var b=a.length,d=[],c=0;c<b;c++)d[c>>>2]|=(a.charCodeAt(c)&255)<<24-8*(c%4);return k.create(d,b)}},g=r.Utf8={stringify:function(a){try{return decodeURIComponent(escape(s.stringify(a)))}catch(b){throw Error("Malformed UTF-8 data");}},parse:function(a){return s.parse(unescape(encodeURIComponent(a)))}},b=q.BufferedBlockAlgorithm=j.extend({reset:function(){this._data=k.create();this._nDataBytes=0},_append:function(a){"string"==typeof a&&(a=g.parse(a));this._data.concat(a);this._nDataBytes+=a.sigBytes},_process:function(a){var b=this._data,d=b.words,c=b.sigBytes,e=this.blockSize,f=c/(4*e),f=a?i.ceil(f):i.max((f|0)-this._minBufferSize,0),a=f*e,c=i.min(4*a,c);if(a){for(var g=0;g<a;g+=e)this._doProcessBlock(d,g);g=d.splice(0,a);b.sigBytes-=c}return k.create(g,c)},clone:function(){var a=j.clone.call(this);a._data=this._data.clone();return a},_minBufferSize:0});q.Hasher=b.extend({init:function(){this.reset()},reset:function(){b.reset.call(this);this._doReset()},update:function(a){this._append(a);this._process();return this},finalize:function(a){a&&this._append(a);this._doFinalize();return this._hash},clone:function(){var a=b.clone.call(this);a._hash=this._hash.clone();return a},blockSize:16,_createHelper:function(a){return function(b,d){return a.create(d).finalize(b)}},_createHmacHelper:function(a){return function(b,d){return e.HMAC.create(a,d).finalize(b)}}});var e=f.algo={};return f}(Math);(function(i){var p=CryptoJS,f=p.lib,q=f.WordArray,f=f.Hasher,j=p.algo,k=[],r=[];(function(){function f(a){for(var b=i.sqrt(a),d=2;d<=b;d++)if(!(a%d))return!1;return!0}function g(a){return 4294967296*(a-(a|0))|0}for(var b=2,e=0;64>e;)f(b)&&(8>e&&(k[e]=g(i.pow(b,0.5))),r[e]=g(i.pow(b,1/3)),e++),b++})();var m=[],j=j.SHA256=f.extend({_doReset:function(){this._hash=q.create(k.slice(0))},_doProcessBlock:function(f,g){for(var b=this._hash.words,e=b[0],a=b[1],h=b[2],d=b[3],c=b[4],i=b[5],j=b[6],k=b[7],l=0;64>l;l++){if(16>l)m[l]=f[g+l]|0;else{var n=m[l-15],o=m[l-2];m[l]=((n<<25|n>>>7)^(n<<14|n>>>18)^n>>>3)+m[l-7]+((o<<15|o>>>17)^(o<<13|o>>>19)^o>>>10)+m[l-16]}n=k+((c<<26|c>>>6)^(c<<21|c>>>11)^(c<<7|c>>>25))+(c&i^~c&j)+r[l]+m[l];o=((e<<30|e>>>2)^(e<<19|e>>>13)^(e<<10|e>>>22))+(e&a^e&h^a&h);k=j;j=i;i=c;c=d+n|0;d=h;h=a;a=e;e=n+o|0}b[0]=b[0]+e|0;b[1]=b[1]+a|0;b[2]=b[2]+h|0;b[3]=b[3]+d|0;b[4]=b[4]+c|0;b[5]=b[5]+i|0;b[6]=b[6]+j|0;b[7]=b[7]+k|0},_doFinalize:function(){var f=this._data,g=f.words,b=8*this._nDataBytes,e=8*f.sigBytes;g[e>>>5]|=128<<24-e%32;g[(e+64>>>9<<4)+15]=b;f.sigBytes=4*g.length;this._process()}});p.SHA256=f._createHelper(j);p.HmacSHA256=f._createHmacHelper(j)})(Math);