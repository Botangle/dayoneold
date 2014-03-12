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

include dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."plugins.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
    include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
}

if ($guestnamePrefix == '') { $guestnamePrefix = 'Guest'; }

if (defined('ERROR_LOGGING') && ERROR_LOGGING == '1') { 
    error_reporting(E_ALL);
    ini_set('error_log', 'error.log');
    ini_set('log_errors', 'On');
}

if (empty($_GET['id']) && empty($_GET['history'])) { exit; }

$body = '';
$history = intval($_GET['history']);

logs();

function logsview() {
    global $db;
    $usertable = TABLE_PREFIX.DB_USERTABLE;
    $usertable_username = DB_USERTABLE_NAME;
    $usertable_userid = DB_USERTABLE_USERID;
    global $body;
    global $history;
    global $userid;
    global $chathistory_language;
    global $guestsMode;
    global $guestnamePrefix;

    $data = array();

    if (!empty($_GET['id'])) {
        $data = preg_split("/,/",base64_decode($_GET['id']));
    }

    $data[0] = intval($data[0]); 
    $data[1] = intval($data[1]);
	$guestpart= "";

    if (!empty($_GET['chatroommode'])) {
		if ($guestsMode == '1') {
			$guestpart = "union (select m1.*, m2.name chatroom, concat('".$guestnamePrefix."-',f.name) fromu from cometchat_chatroommessages m1, cometchat_chatrooms m2, cometchat_guests f where  f.id = m1.userid and m1.chatroomid=m2.id and m1.chatroomid=".$history." and m1.id >= ".$data[0]." and m1.id < ".$data[1].")";
		}
        $sql = ("(select m1.*, m2.name chatroom, f.".$usertable_username." fromu from cometchat_chatroommessages m1, cometchat_chatrooms m2, ".$usertable." f where  f.".$usertable_userid." = m1.userid and m1.chatroomid=m2.id and m1.chatroomid='".$history."' and m1.id >= ".$data[0]." and m1.id < ".$data[1].") ".$guestpart." order by id");          
    } else {
		if ($guestsMode == '1') {
			$guestpart = "union (select m1.*, concat('".$guestnamePrefix."-',f.name) fromu, concat('".$guestnamePrefix."-',t.name) tou from cometchat m1, cometchat_guests f, cometchat_guests t where f.id = m1.from and t.id = m1.to and ((m1.from = '".mysql_real_escape_string($userid)."' and m1.to = '".mysql_real_escape_string($history)."') or (m1.to = '".mysql_real_escape_string($userid)."' and m1.from = '".mysql_real_escape_string($history)."')) and m1.id >= ".$data[0]." and m1.id < ".$data[1]." and m1.direction <> 2) 
			union (select m1.*, concat('".$guestnamePrefix."-',f.name) fromu, t.".$usertable_username." tou from cometchat m1, cometchat_guests f, ".$usertable." t where f.id = m1.from and t.".$usertable_userid." = m1.to and ((m1.from = '".mysql_real_escape_string($userid)."' and m1.to = '".mysql_real_escape_string($history)."') or (m1.to = '".mysql_real_escape_string($userid)."' and m1.from = '".mysql_real_escape_string($history)."')) and m1.id >= ".$data[0]." and m1.id < ".$data[1]." and m1.direction <> 2) 
			union (select m1.*, f.".$usertable_username." fromu, concat('".$guestnamePrefix."-',t.name) tou from cometchat m1, ".$usertable." f, cometchat_guests t where f.".$usertable_userid." = m1.from and t.id = m1.to and ((m1.from = '".mysql_real_escape_string($userid)."' and m1.to = '".mysql_real_escape_string($history)."') or (m1.to = '".mysql_real_escape_string($userid)."' and m1.from = '".mysql_real_escape_string($history)."')) and m1.id >= ".$data[0]." and m1.id < ".$data[1]." and m1.direction <> 2)";
		}
        $sql = ("(select m1.*, f.".$usertable_username." fromu, t.".$usertable_username." tou from cometchat m1, ".$usertable." f, ".$usertable." t  where  f.".$usertable_userid." = m1.from and t.".$usertable_userid." = m1.to and ((m1.from = '".mysql_real_escape_string($userid)."' and m1.to = '".mysql_real_escape_string($history)."') or (m1.to = '".mysql_real_escape_string($userid)."' and m1.from = '".mysql_real_escape_string($history)."')) and m1.id >= ".$data[0]." and m1.id < ".$data[1]." and m1.direction <> 2) ".$guestpart." order by id");
    }

    $query = mysql_query($sql); 
    $chatdata = '';
    $previd = '';
    $lines = 0;
    $s = 0;
    while ($chat = mysql_fetch_array($query)) {
        if (function_exists('processName')) {
            $chat['fromu'] = processName($chat['fromu']);
            if (empty($_GET['chatroommode'])) {
                $chat['tou'] = processName($chat['tou']);
            }
        }

        if ($s == 0) { 
            $s = $chat['sent']; 
        }   
        $requester = $chat['fromu'];
        if (!empty($_GET['chatroommode'])) {
            $chathistory_language[2]=$chathistory_language[7];
            $requester=$chat['chatroom'];
            if ($chat['userid']==$userid) {
                $chat['fromu'] = $chathistory_language[1];
            }
        } else {
            if ($chat['from'] == $userid) {
                $chat['fromu'] = $chathistory_language[1];
            }   
        }

        $chat['message'] = $chat['message'];        
        $display = $chat['fromu'];
        $chatnoline = '';
        if ($previd == $chat['fromu']) {
            $display = '';
            $chatnoline = ''; 
        }

        $lines++;

        $chatdata = <<<EOD
        {$chatdata}
        <div class="chat chatnoline">
            <div class="chatrequest"><b>{$display}</b></div>
            <div class="chatmessage chatnowrap">{$chat['message']}</div>
            <div class="chattime" timestamp={$chat['sent']}></div>
            <div style="clear:both"></div>
        </div> 
EOD;
        $previd = $chat['fromu'];
    }

    $time = date('M jS Y', $s);

    $history = '?basedata='.$_REQUEST['basedata'].'&history='.$_GET['history'];
    if (!empty($_GET['embed']) && $_GET['embed'] == 'web') { 
        $history .= '&embed=web';
    }

    if (!empty($_GET['embed']) && $_GET['embed'] == 'desktop') { 
        $history .= '&embed=desktop';
    }

    if (!empty($_GET['chatroommode'])) {
        $history .= '&chatroommode=1';
    }
    
    $body = <<<EOD
    <div class="chatbar">
        <div class="chatbar_1">{$chathistory_language[2]} {$requester} {$chathistory_language[4]} {$time} ({$lines} {$chathistory_language[3]})</div>
        <div class="chatbar_2"><a href="index.php{$history}">{$chathistory_language[5]}</a></div>
        <div style="clear:both"></div>
    </div>
    {$chatdata}
EOD;
    template();
}

function logs() {
    global $db;
    $usertable = TABLE_PREFIX.DB_USERTABLE;
    $usertable_username = DB_USERTABLE_NAME;
    $usertable_userid = DB_USERTABLE_USERID;
    global $body;
    global $history;
    global $userid;
    global $chathistory_language;
    global $guestsMode;
    global $guestnamePrefix;
    
    if (!empty($_GET['id'])) { logsview(); }

	$guestpart = "";

    if (!empty($_GET['chatroommode'])) {
		if ($guestsMode == '1') {
			$guestpart = "union (select m1.*, concat('".$guestnamePrefix."-',f.name) fromu from cometchat_chatroommessages m1, cometchat_guests f where f.id = m1.userid and m1.chatroomid = '".mysql_real_escape_string($history)."' and (m1.sent) > ALL(select (m2.sent)+1800 from cometchat_chatroommessages m2 where m1.chatroomid = m2.chatroomid and m2.sent <= m1.sent and m2.id < m1.id))";
		}
        $sql = ("(select m1.*, f.".$usertable_username." fromu from cometchat_chatroommessages m1, ".$usertable." f where f.".$usertable_userid." = m1.userid and m1.chatroomid = '".mysql_real_escape_string($history)."' and (m1.sent) > ALL(select (m2.sent)+1800 from cometchat_chatroommessages m2 where m1.chatroomid = m2.chatroomid and m2.sent <= m1.sent and m2.id < m1.id)) ".$guestpart." order by id desc");
    } else {
		if ($guestsMode == '1') {
			$guestpart = "union (select m1.*, concat('".$guestnamePrefix."-',f.name) fromu, concat('".$guestnamePrefix."-',t.name) tou from cometchat m1, cometchat_guests f, cometchat_guests t where  f.id = m1.from and t.id = m1.to and ((m1.from = '".mysql_real_escape_string($userid)."' and m1.to = '".mysql_real_escape_string($history)."') or (m1.to = '".mysql_real_escape_string($userid)."' and m1.from = '".mysql_real_escape_string($history)."')) and (m1.sent) > ALL (select (m2.sent)+1800 from cometchat m2 where ((m2.to = m1.to and m2.from = m1.from) or (m2.to = m1.from and m2.from = m1.to))and m2.sent <= m1.sent and m2.id < m1.id)) 
			union (select m1.*, concat('".$guestnamePrefix."-',f.name) fromu, t.".$usertable_username." tou from cometchat m1, cometchat_guests f, ".$usertable." t where  f.id = m1.from and t.".$usertable_userid." = m1.to and ((m1.from = '".mysql_real_escape_string($userid)."' and m1.to = '".mysql_real_escape_string($history)."') or (m1.to = '".mysql_real_escape_string($userid)."' and m1.from = '".mysql_real_escape_string($history)."')) and (m1.sent) > ALL (select (m2.sent)+1800 from cometchat m2 where ((m2.to = m1.to and m2.from = m1.from) or (m2.to = m1.from and m2.from = m1.to))and m2.sent <= m1.sent and m2.id < m1.id)) 
			union (select m1.*, f.".$usertable_username." fromu, concat('".$guestnamePrefix."-',t.name) tou from cometchat m1, ".$usertable." f, cometchat_guests t where  f.".$usertable_userid." = m1.from and t.id = m1.to and ((m1.from = '".mysql_real_escape_string($userid)."' and m1.to = '".mysql_real_escape_string($history)."') or (m1.to = '".mysql_real_escape_string($userid)."' and m1.from = '".mysql_real_escape_string($history)."')) and (m1.sent) > ALL (select (m2.sent)+1800 from cometchat m2 where ((m2.to = m1.to and m2.from = m1.from) or (m2.to = m1.from and m2.from = m1.to))and m2.sent <= m1.sent and m2.id < m1.id))";
		}
		$sql = ("(select m1.*, f.".$usertable_username." fromu, t.".$usertable_username." tou from cometchat m1, ".$usertable." f, ".$usertable." t where  f.".$usertable_userid." = m1.from and t.".$usertable_userid." = m1.to and ((m1.from = '".mysql_real_escape_string($userid)."' and m1.to = '".mysql_real_escape_string($history)."') or (m1.to = '".mysql_real_escape_string($userid)."' and m1.from = '".mysql_real_escape_string($history)."')) and (m1.sent) > ALL (select (m2.sent)+1800 from cometchat m2 where ((m2.to = m1.to and m2.from = m1.from) or (m2.to = m1.from and m2.from = m1.to))and m2.sent <= m1.sent and m2.id < m1.id)) ".$guestpart." order by id desc");
    }

    $query = mysql_query($sql); 
    $chatdata = '';
    $previd = 1000000;
    if (mysql_num_rows($query)>0) {
        while ($chat = mysql_fetch_array($query)) { 
            if (function_exists('processName')) {
                $chat['fromu'] = processName($chat['fromu']);
                if (empty($_GET['chatroommode'])) {
                    $chat['tou'] = processName($chat['tou']);
                }
            }

            $requester = $chat['fromu'];
            if (empty($_GET['chatroommode'])) {
                if ($chat['from'] == $userid) {
                    $chat['fromu'] = $chathistory_language[1];
                }
            } else {
                if ($chat['userid'] == $userid) {
                    $chat['fromu'] = $chathistory_language[1];
                }
            }

            $chat['message'] = $chat['message'];
            $encode = base64_encode($chat['id'].",".$previd);

            $chatdata = <<<EOD
            {$chatdata}
            <div class="chat" id="{$encode}" title="{$chathistory_language[8]}">
                <div class="chatrequest"><b>{$chat['fromu']}</b></div> 
                <div class="chatmessage chatmessage_short">{$chat['message']}</div>
                <div class="chattime" timestamp={$chat['sent']}></div>
                <div style="clear:both"></div>
            </div> 
EOD;
            $previd = $chat['id'];
        }
    } else {
        $chatdata = "<div class='norecords'>".$chathistory_language[9]."</div>"; 
    }

    $chatdata .= '';

    $history = '';

    if (!empty($_GET['history'])) {
        $history = '+"&history='.$_GET['history'].'"';
    }

    if (!empty($_GET['embed']) && $_GET['embed'] == 'web') { 
        $history .= '+"&embed=web"';
    }

    if (!empty($_GET['embed']) && $_GET['embed'] == 'desktop') { 
        $history .= '+"&embed=desktop"';
    }

    if (!empty($_GET['chatroommode'])) {
        $history .= '+"&chatroommode=1"';
    }

    $baseData = $_REQUEST['basedata'];

    $body = <<<EOD
    <script>
        jqcc(document).ready(function () {
            jqcc('.chat').click(function() {            
                var id = jqcc(this).attr('id');
                location.href = "?action=logs&basedata={$baseData}&id="+id{$history};
            });
        });
    </script>
    {$chatdata}
EOD;

    template();
}

function template() {

    global $body;
    global $chathistory_language;
    $embed = '';
    $embedcss = '';

    if (!empty($_GET['embed']) && $_GET['embed'] == 'web') { 
        $embed = 'web';
        $embedcss = 'embed';
    } elseif (!empty($_GET['embed']) && $_GET['embed'] == 'desktop') { 
        $embed = 'desktop';
        $embedcss = 'embed';
    }

    echo <<<EOD
    <!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
            <title>{$chathistory_language[6]}</title> 
            <link type="text/css" rel="stylesheet" media="all" href="../../css.php?type=plugin&name=chathistory" /> 
            <script src="../../js.php?type=core&name=jquery" type="text/javascript"></script>
            <script src="../../js.php?type=core&name=scroll" type="text/javascript"></script>
            <script src="../../js.php?type=plugin&name=chathistory"></script>
        </head>
        <body>
            <div class="container">
                <div class="container_title {$embedcss}" >{$chathistory_language[6]}</div>
                    <div class="container_body {$embedcss}">
                        <div class="container_body_chat">
                        {$body}
                        </div>
                    </div>  
                </div>
            </div>
        </body>
    </html>
EOD;

exit;

}