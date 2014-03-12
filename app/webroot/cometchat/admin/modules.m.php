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

if (!defined('CCADMIN')) { echo "NO DICE"; exit; }

$navigation = <<<EOD
	<div id="leftnav">
	<a href="?module=modules">Live modules</a>
	<a href="?module=modules&action=createmodule">Add custom tray icon</a>
	</div>
EOD;

function index() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	$modules = array();
	$livemodules = array();
	$livetrayicons = '';
	$no_trayicons = '';
	
	foreach ($trayicon as $ti) {
		$livemodules[] = $ti[0];
		if (empty($ti[2])) { $ti[2] = ''; }
		if (empty($ti[3])) { $ti[3] = ''; }
		if (empty($ti[4])) { $ti[4] = ''; }
		if (empty($ti[5])) { $ti[5] = ''; }
		if (empty($ti[6])) { $ti[6] = ''; }
		if (empty($ti[7])) { $ti[7] = ''; }
		if (empty($ti[8])) { $ti[8] = ''; $showhide = 'Show'; $opacity='1'; } else { $showhide = 'Hide'; $opacity='0.5';}
		
		$config = '';
		$popup = '';

		if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$ti[0].DIRECTORY_SEPARATOR.'settings.php')) {
			$config = '<a href="javascript:void(0)" onclick="javascript:modules_configmodule(\''.$ti[0].'\')" style="margin-left:5px;"><img src="images/config.png" title="Configure Module"></a>';
		} else {
			$config = '<img src="images/blank.gif" width="16" height="16" style="margin-left:5px;">';
		}

		if ($ti[3] == '_lightbox') {
			$popup = '<a href="javascript:void(0)" onclick="javascript:modules_showpopup(this,\''.$ti[0].'\')" style="margin-left:5px;"><img style="opacity:0.5" src="images/lightbox.png" title="Open module as popup (default)"></a>';
		} else if ($ti[3] == '_popup') {
			$popup = '<a href="javascript:void(0)" onclick="javascript:modules_showpopup(this,\''.$ti[0].'\')" style="margin-left:5px;"><img style="opacity:1"  src="images/lightbox.png" title="Open module in a lightbox"></a>';
		} else {
			$popup = '<img src="images/blank.gif" width="16" height="16" style="margin-left:5px;">';
		}
		
		$title = stripslashes($ti[1]);

		if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$ti[0].DIRECTORY_SEPARATOR.'code.php')) {
			require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$ti[0].DIRECTORY_SEPARATOR.'code.php';
			$title = $trayiconinfo[1];
		}

		$livetrayicons .= '<li class="ui-state-default" id="'.$ti[0].'" d1="'.addslashes($ti[1]).'" d2="'.$ti[2].'" d3="'.$ti[3].'" d4="'.$ti[4].'" d5="'.$ti[5].'" d6="'.$ti[6].'" d7="'.$ti[7].'" d8="'.$ti[8].'" rel="'.$trayicondata.'"><img src="../modules/'.$ti[0].'/icon.png" style="margin:0;margin-top:2px;margin-right:5px;float:left;"></img><span style="font-size:11px;float:left;margin-top:3px;margin-left:5px;" id="'.$ti[0].'_title">'.$title.'</span><span style="font-size:11px;float:right;margin-top:0px;margin-right:5px;"><a onclick="javascript:modules_showtext(this,\''.$ti[0].'\');" href="javascript:void(0)" style="margin-left:5px;"><img src="images/text.png" style="opacity:'.$opacity.';" title="'.$showhide.' the module title in the chatbar"></a>'.$popup.'<a onclick="javascript:embed_link(\''.BASE_URL.'modules/'.$ti[0].'/index.php\',\''.$ti[4].'\',\''.$ti[5].'\');" href="javascript:void(0)" style="margin-left:5px;"><img src="images/embed.png" title="Generate Embed Code"></a> '.$config.'<a href="javascript:void(0)" onclick="javascript:modules_removemodule(\''.$ti[0].'\')" style="margin-left:5px;"><img src="images/remove.png" title="Remove Module"></a></span><div style="clear:both"></div></li>';
	}

	if(!$livetrayicons){
		$no_trayicons .= '<div id="no_trayicon" style="width: 480px;float: left;color: #333333;">You haven\'t activated any CometChat Module yet. To activate a module, please add the module from the list of available modules.</div>';
	}
	else{
		$livetrayicons = '<ul id="modules_livemodules">'.$livetrayicons.'</ul>';
	}
	
	if ($handle = opendir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$file) && file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'code.php')) {
				$modules[] = $file;
			}
		}
		closedir($handle);
	}

	$moduleslist = '';
	
	foreach ($modules as $module) {
		require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.'code.php';
		
		$modulehref = 'href="?module=modules&action=addmodule&data='.$trayicondata.'&token='.$_SESSION['token'].'"';
		if (in_array($module, $livemodules)) {
			$modulehref = 'href="javascript: void(0)" style="opacity: 0.5;cursor: default;"';
		}
		
		$moduleslist .= '<li class="ui-state-default"><img src="../modules/'.$trayiconinfo[0].'/icon.png" style="margin:0;margin-right:5px;float:left;"></img><span style="font-size:11px;float:left;margin-top:2px;margin-left:5px;width:120px">'.$trayiconinfo[1].'</span><span style="font-size:11px;float:right;margin-top:2px;margin-right:5px;"><a '.$modulehref.' tid="'.$trayicondata.'"  id = "'.$module.'"  >add</a></span><div style="clear:both"></div></li>';
	}

	


	$body = <<<EOD
	$navigation

	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Live Modules</h2>
		<h3>Use your mouse to change the order in which the modules appear on the bar (left-to-right). You can add available modules from the right.</h3>

		<div>
			$no_trayicons
			$livetrayicons
			<div id="rightnav" style="margin-top:5px">
				<h1>Available modules</h1>
				<ul id="modules_availablemodules">
				$moduleslist
				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="button" onclick="javascript:modules_updateorder()" value="Update order" class="button">&nbsp;&nbsp;or <a href="?module=modules">cancel</a>
	</div>

	<div style="clear:both"></div>

	<script type="text/javascript">
		$(function() {
			$("#modules_livemodules").sortable({ connectWith: 'ul' });
			$("#modules_livemodules").disableSelection();
		});
	</script>

EOD;

	template();

}

function updateorder() {
	checktoken();

	$icons = '';

	if (!empty($_POST['order'])) {
		foreach ($_POST['order'] as $order) {
			$icons .= $order."\r\n";
		}
	}

	$icons = str_replace("\\\\\\","\\\\\\\\\\\\",$icons);

	configeditor('ICONS',trim($icons),0);

	echo "1";

}

function addmodule() {
	checktoken();

	if (!empty($_GET['data'])) {
		configeditor('ICONS',base64_decode($_GET['data']),1);
	}
	header("Location:?module=modules");
}

function createmodule() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	$body = <<<EOD
	$navigation
	<form action="?module=modules&action=createmoduleprocess" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Add custom tray icon</h2>
		<h3>The maximum height for the icon is 16px</h3>

		<div>
			<div id="centernav">
				<div class="title">Title:</div><div class="element"><input type="text" class="inputbox" name="title"></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Icon:</div><div class="element"><input type="file" class="inputbox" name="file"></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Link:</div><div class="element"><input type="text" class="inputbox" name="link" value="http://www.cometchat.com"></div>
				<div style="clear:both;padding:5px;"></div>

				<div class="title">Type:</div><div class="element"><select class="inputbox" name="type"><option value="">Same window<option  value="_blank">New window<option  value="_popup">Pop-up<option  value="_lightbox">Lightbox (same window popup)</select></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="titlefull">If type is pop-up, please enter the width and height</div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Width:</div><div class="element"><input type="text" class="inputbox" name="width" value="300"></div>
				<div style="clear:both;padding:5px;"></div>	
				<div class="title">Height:</div><div class="element"><input type="text" class="inputbox" name="height" value="200"></div>
				<div style="clear:both;padding:5px;"></div>
			</div>
			<div id="rightnav">
				<h1>Tip</h1>
				<ul id="modules_availablemodules">
					<li>It is best to use PNG format for your icons. Set transparency on for your icons.</li>
 				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Add custom tray icon" class="button">&nbsp;&nbsp;or <a href="?module=modules">cancel</a>
		<input type="hidden" value="{$_SESSION['token']}" name="token">
	</div>

	<div style="clear:both"></div>

EOD;

	template();

}

function createmoduleprocess() {
	checktoken();

	$extension = '';
	$error = '';

	$modulename = createslug($_POST['title'],true);

	if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/png"))) {
		if ($_FILES["file"]["error"] > 0) {
			$error = "Module icon incorrect. Please try again.";
		} else {
			if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."temp" .DIRECTORY_SEPARATOR. $modulename)) {
				unlink(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."temp" .DIRECTORY_SEPARATOR. $modulename);
			}

			$extension = extension($_FILES["file"]["name"]);
			if (!move_uploaded_file($_FILES["file"]["tmp_name"], dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."temp" .DIRECTORY_SEPARATOR. $modulename)) {
				$error = "Unable to copy to temp folder. Please CHMOD temp folder to 777.";
			}
		}
	} else {
		$error = "Module icon not found. Please try again.";
	}
	
	if (empty($_POST['title'])) {
		$error = "Module title is empty. Please try again.";
	}

	if (empty($_POST['link'])) {
		$error = "Module link is empty. Please try again.";
	}

	if (!empty($error)) {
		$_SESSION['cometchat']['error'] = $error;
		header("Location: ?module=modules&action=createmodule");
		exit;
	}

	mkdir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$modulename);

	copy(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."temp" .DIRECTORY_SEPARATOR. $modulename,dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$modulename.DIRECTORY_SEPARATOR.'icon.png');

	unlink(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."temp" .DIRECTORY_SEPARATOR. $modulename);

	$code = "\$trayicon[] = array('".$modulename."','".addslashes(addslashes(addslashes(str_replace('"','',$_POST['title']))))."','".$_POST['link']."','".$_POST['type']."','".$_POST['width']."','".$_POST['height']."','','');";
	
	configeditor('ICONS',$code,1);
	header("Location:?module=modules");	
	
}
