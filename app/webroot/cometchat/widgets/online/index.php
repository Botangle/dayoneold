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

include_once dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."cometchat_init.php";

$time = getTimeStamp();

$blockList = array();

if (in_array('block',$plugins)) {

	$sql = "(select toid as id from cometchat_block where fromid = '".mysql_real_escape_string($userid)."') union (select fromid as id from cometchat_block where toid = '".mysql_real_escape_string($userid)."') ";

	$query = mysql_query($sql);
	while ($user = mysql_fetch_array($query)) {
		array_push($blockList,$user['id']);
	}
}

if (empty($_REQUEST['rows'])) $_REQUEST['rows'] = 6;
if (empty($_REQUEST['cols'])) $_REQUEST['cols'] = 5;
if (empty($_REQUEST['height'])) $_REQUEST['height'] = 30;
if (empty($_REQUEST['width'])) $_REQUEST['width'] = 30;
if (empty($_REQUEST['hideoffline'])) $_REQUEST['hideoffline'] = 0;

$maxrows = $_REQUEST['rows'];
$maxcols = $_REQUEST['cols'];
$height = $_REQUEST['height'];
$width = $_REQUEST['width'];
$hideOffline = $_REQUEST['hideoffline'];

$sql = getFriendsList($userid,$time);

$query = mysql_query($sql);
if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }

$rows = 0;
$cols = 0;

$data['available'] = array();
$data['busy'] = array();
$data['away'] = array();
$data['offline'] = array();

while ($chat = mysql_fetch_array($query)) {
	if (!in_array($chat['userid'],$blockList)) {

		if ((($time-processTime($chat['lastactivity'])) < ONLINE_TIMEOUT) && $chat['status'] != 'invisible' && $chat['status'] != 'offline') {
			if ($chat['status'] != 'busy' && $chat['status'] != 'away') {
				$chat['status'] = 'available';
			}
		} else {
			$chat['status'] = 'offline';
		}

		$avatar = getAvatar($chat['avatar']);
		if (!empty($chat['username']) && ($hideOffline == 0 || ($hideOffline == 1 && $chat['status'] != 'offline'))) {
			$data[$chat['status']][] = '<a class="cometchat_online" href="javascript:parent.jqcc.cometchat.chatWith(\''.$chat['userid'].'\');"><div class="cometchat_'.$chat['status'].'"></div><img src="'.$avatar.'" height="'.$height.'" width="'.$width.'"></a>';
		}
	}
}

$results = array_merge($data['available'],$data['busy'],$data['away'],$data['offline']);

$thumbnails = '';

foreach ($results as $result) {
	++$cols;

	if ($cols > $maxcols) {
		$thumbnails .= '<div style="clear:both"></div>';
		$cols = 1;
		++$rows;
	}

	if ($rows >= $maxrows) {
		break;
	}

	$thumbnails .= $result;

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>Online List</title> 
<link type="text/css" rel="stylesheet" media="all" href="online.css" /> 
<?php echo $thumbnails;?>
</body>
</html>