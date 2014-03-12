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

foreach ($avchat_language as $i => $l) {
	$avchat_language[$i] = str_replace("'", "\'", $l);
}

?>
var vidWidth = <?php echo $vidWidth;?>; var vidHeight = <?php echo $vidHeight;?>; var baseUrl = "<?php echo BASE_URL;?>"; var session; var publisher; var subscribers = {}; var totalStreams = 0;

function disconnect() {
	unpublish();
	session.disconnect();
	hide('navigation');
	show('endcall');
	var div = document.getElementById('canvas');
	div.parentNode.removeChild(div);
	eval(resize +'300,330);');
}

function publish() {
	if (!publisher) {
		var canvas = document.getElementById("canvas");
		var div = document.createElement('div');		
		div.setAttribute('id', 'opentok_publisher');
		canvas.appendChild(div);
		var params = {width: vidWidth, height: vidHeight , name: name};
		publisher = session.publish('opentok_publisher', params); 	
		resizeWindow();
		show('unpublishLink');
		hide('publishLink');
	}
}

function resizeWindow() {
       var rows, cols;
       if(totalStreams == 0) {
               var marginOffset = (window.innerWidth - vidWidth)/2;
               document.getElementById('canvas').style.margin = "0px 0px 0px "+marginOffset+"px";
       } else {
               document.getElementById('canvas').style.margin = "";
       }
       rows = Math.round(Math.sqrt(totalStreams+1));
       cols = Math.ceil(Math.sqrt(totalStreams+1));
       width = (cols)*(vidWidth+2);
       height = (rows)*(vidHeight+30);
               
       if (width < vidWidth + 30) {
               width = vidWidth+30;
       }
       if(width < 400)
               width = 400;
       if(height < 300)
               height = 300;
       eval(resize +'width,'+height + ');');

       var h = vidHeight;
       if( typeof( window.innerWidth ) == 'number' ) {
               h = window.innerHeight;
       } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
               h = document.documentElement.clientHeight;
       } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
               h = document.body.clientHeight;
       }

       if (document.getElementById('canvas') && document.getElementById('canvas').style.display != 'none') {
               if (h > vidHeight){
                       offset = (h-30-vidHeight)/2;
                       document.getElementById('canvas').style.marginTop = offset+'px';
               } else {
                       document.getElementById('canvas').style.marginTop = '0px';
               }
       }
       if(totalStreams > 1) {
               document.getElementById('canvas').style.marginTop = '0px';
       }
}

function addStream(stream) {
	if (stream.connection.connectionId == session.connection.connectionId) {
		return;
	}	
	var div = document.createElement('div');
	var divId = stream.streamId;	
	div.setAttribute('id', divId);	
	div.setAttribute('class', 'camera');
	document.getElementById('canvas').appendChild(div);
	var params = {width: vidWidth, height: vidHeight};
	subscribers[stream.streamId] = session.subscribe(stream, divId, params); 
}

function inviteUser() {
	eval(invitefunction + '("' + baseUrl + 'plugins/avchat/invite.php?action=invite&roomid='+ sessionId +'","invite","status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=1, width=400,height=190",400,190,"<?php echo $avchat_language[16];?>");'); 
}		

function connect() {
	session.connect(apiKey, token);
}

function unpublish() {

	if (publisher) {
		session.unpublish(publisher);
	}
	
	publisher = null;
	
	show('publishLink');
	hide('unpublishLink');
	resizeWindow();
}


function sessionConnectedHandler(event) {
	
	hide('loading');
	show('canvas');
	
	publish();
	
	for (var i = 0; i < event.streams.length; i++) {

		if (event.streams[i].connection.connectionId != session.connection.connectionId) {
			totalStreams++;
		}
		addStream(event.streams[i]);
	}

	resizeWindow();
	show('navigation');
	show('unpublishLink');
	show('disconnectLink');
	hide('publishLink');
}

function streamCreatedHandler(event) {

	for (var i = 0; i < event.streams.length; i++) {
		if (event.streams[i].connection.connectionId != session.connection.connectionId) {
			totalStreams++;
		}
		addStream(event.streams[i]);
	}
	resizeWindow();
}

function streamDestroyedHandler(event) {

	for (var i = 0; i < event.streams.length; i++) {
		if (event.streams[i].connection.connectionId != session.connection.connectionId) {
			totalStreams--;
		}
	}
	resizeWindow();
}

function sessionDisconnectedHandler(event) {
	publisher = null;
}

function connectionDestroyedHandler(event) {
}

function connectionCreatedHandler(event) {
}

function exceptionHandler(event) {
}

function show(id) {
	document.getElementById(id).style.display = 'block';
}

function hide(id) {
	document.getElementById(id).style.display = 'none';
}

