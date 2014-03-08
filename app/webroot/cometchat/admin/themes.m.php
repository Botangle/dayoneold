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
		<a href="?module=themes">Themes</a>
		<a href="?module=themes&action=clonetheme&theme=default">Create new theme</a>
		<a href="?module=themes&action=uploadtheme">Upload theme</a>
	</div>
EOD;

function index() {
	global $db;
	global $body;	
	global $themes;
	global $navigation;
	global $theme;

	$athemes = array();
	
	if ($handle = opendir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'themes')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != "base" && is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$file) && file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'cometchat.css')) {
				$athemes[] = $file;
			}
		}
		closedir($handle);
	}

	$activethemes = '';
	$no = 0;

	foreach ($athemes as $ti) {

		$title = ucwords($ti);

		++$no;

		$default = '';
		$opacity = '1';
		$titlemakedefault = 'title="Make theme default"';
		$setdefault = 'onclick="javascript:themes_makedefault(\''.$ti.'\')"';
		
		if (strtolower($theme) == strtolower($ti)) {
			$default = ' (Default)';
			$opacity = '0.5;cursor:default;';
			$titlemakedefault = '';
			$setdefault = '';
		}

		$clone = '<a href="?module=themes&action=clonetheme&theme='.$ti.'"><img src="images/clone.png" title="Clone Theme" style="margin-right:5px;"></a>';
		
		$activethemes .= '<li class="ui-state-default" id="'.$no.'" d1="'.$ti.'"><span style="font-size:11px;float:left;margin-top:3px;margin-left:5px;" id="'.$ti.'_title">'.stripslashes($title).$default.'</span><span style="font-size:11px;float:right;margin-top:0px;margin-right:5px;"><a href="javascript:void(0)" '.$setdefault.' style="margin-right:5px;"><img src="images/default.png" '.$titlemakedefault.' style="opacity:'.$opacity.';"></a><a href="javascript:void(0)" onclick="javascript:themes_edittheme(\''.$ti.'\')" style="margin-right:5px;"><img src="images/config.png" title="Edit Theme"></a>'.$clone.'<a href="javascript:void(0)" onclick="javascript:themes_exporttheme(\''.$ti.'\')" style="margin-right:5px;"><img src="images/export.png" title="Download Theme"></a><a href="javascript:void(0)" onclick="javascript:themes_removetheme(\''.$ti.'\')"><img src="images/remove.png" title="Remove Theme"></a></span><div style="clear:both"></div></li>';
	}


	$body = <<<EOD
	$navigation

	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Themes</h2>
		<h3>To set the theme, click on the star button next to the theme. If you do not want the user to change the themes, disable the "Theme Changer" module in the modules tab.</h3>

		<div>
			<ul id="modules_livethemes">
				$activethemes
			</ul>
		</div>


	</div>

	<div style="clear:both"></div>



EOD;

	template();

}

function makedefault() {
	checktoken();

	if (!empty($_POST['theme'])) {

		$themedata = '$theme = \'';

		$themedata .= $_POST['theme'];
		$themedata .= '\';';
		if ($_POST['theme'] != 'lite') {
			configeditor('THEME',$themedata);
			$_SESSION['cometchat']['error'] = 'Default theme successfully updated. Please clear your browser cache and try.';
		} else {
			$_SESSION['cometchat']['error'] = 'Sorry, you cannot set the lite theme as default.';
		}
	}

	echo "1";

}



function checkcolor($color) {

	if (substr($color,0,1) == '#') {
		$color = strtoupper($color);

		if (strlen($color) == 4) {
			$color = $color.substr($matches[0],1);
		}

	}

	return $color;
	
}

function edittheme() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	$csslist = array();

	if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$_GET['data'].DIRECTORY_SEPARATOR.$_GET['data'].'.php')) {
		require_once (dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$_GET['data'].DIRECTORY_SEPARATOR.$_GET['data'].'.php');
	}

	$form = '';
	$inputs = '';
	$js = '';
	$uniqueColor = array();

	foreach ($themeSettings as $field => $input) {
		$input = checkcolor($input);

		$form .= '<div class="titlesmall" style="padding-top:14px;" >'.$field.'</div><div class="element">';

		if (substr($input,0,1) == '#') {

			if (empty($uniqueColor[$input])) {
				$inputs .= '<div class="themeBox colors" oldcolor="'.$input.'" newcolor="'.$input.'" style="background:'.$input.';"></div>';
			}

			$uniqueColor[$input] = 1;

			$form .= '<input type="text" class="inputbox themevariables" id=\''.$field.'_field\' name=\''.$field.'\' value=\''.$input.'\' style="width: 100px;height:28px">';		
			$form .= '<div class="colorSelector themeSettings" field="'.$field.'" id="'.$field.'" oldcolor="'.$input.'" newcolor="'.$input.'" ><div style="background:'.$input.'" style="float:right;margin-left:10px"></div></div>';

			$input = substr($input,1);
			$js .= <<<EOD
$('#$field').ColorPicker({
	color: '#$input',
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(500);
		return false;
	},
	onChange: function (hsb, hex, rgb) {
		$('#$field div').css('backgroundColor', '#' + hex);
		$('#$field').attr('newcolor','#'+hex);
		$('#{$field}_field').val('#'+hex);
	}
});

EOD;

		} else {
			$form .= '<input type="text" class="inputbox themevariables" name=\''.$field.'\' value=\''.$input.'\' style="height:28px;width:250px;">';		
		}

		$form .= '</div><div style="clear:both;padding:7px;"></div>';

	}

	$js .= <<<EOD

$(function() {
		$( "#slider" ).slider({
			value:0,
			min: 0,
			max: 1,
			step: 0.0001,
			slide: function( event, ui ) {
				shift(ui.value);
			}
		});
});

EOD;

	$body = <<<EOD

	<script>
	
	$(document).ready(function() { $js });
	
	</script>
	$navigation
	<form>
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Theme Editor</h2>
		<h3>Edit your theme using two simple tools. If you need advanced modifications, then manually edit the CSS files in the <b>cometchat</b> folder on your server.</h3>
	
		<div>
			<div id="centernav">
				<h2>Color changer</h2>
				<h3>Use the slider to change the base colors.</h3>
				$inputs
				<div style="clear:both;padding:7.5px;"></div>
				<div id="slider"></div>
				<div style="clear:both;padding:7.5px;"></div>
				<input type="button" value="Update colors" class="button" onclick="javascript:themes_updatecolors('{$_GET['data']}')">&nbsp;&nbsp;or <a href="?module=themes">cancel</a>
				<div style="clear:both;padding:20px;"></div>

				<h2>Theme Variables</h2>
				<h3>Update colors, font family and font sizes of your theme.</h3>

				<div>
					<div id="centernav" style="width:700px">
						$form
					</div>
				</div>

				<div style="clear:both;padding:7.5px;"></div>
				<input type="button" value="Update variables" class="button" onclick="javascript:themes_updatevariables('{$_GET['data']}')">&nbsp;&nbsp;or <a href="?module=themes">cancel</a>
			</div>
			<div id="rightnav">
				<h1>Tips</h1>
				<ul id="modules_availablethemes">
					<li>For more advanced modifications, please edit themes/{$_GET['data']}/cometchat.css file.</li>
 				</ul>
			</div>
		</div>
	</div>

	<div style="clear:both"></div>

EOD;

	template();
}

function updatecolorsprocess() {
	checktoken();

	$colors = $_POST['colors'];
	$_GET['data'] = $_POST['theme'];

	require_once (dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$_GET['data'].DIRECTORY_SEPARATOR.$_GET['data'].'.php');

	foreach ($themeSettings as $field => $input) {
		$input = checkcolor($input);

		if (!empty($colors[strtoupper($input)])) {
			$themeSettings[$field] = strtoupper($colors[$input]);
		}
	}
	
	$data = '$themeSettings = array('."\r\n";

	foreach ($themeSettings as $field => $input) {
		$data .= "'".$field."' => '".$input."',"."\r\n";
	}

	$data .= ");";


	$_SESSION['cometchat']['error'] = 'Theme updated successfully';

	configeditor('SETTINGS',$data,0,dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$_GET['data'].DIRECTORY_SEPARATOR.$_GET['data'].'.php');	

	echo 1;

}

function updatevariablesprocess() {
	checktoken();

	$colors = $_POST['colors'];
	$_GET['data'] = $_POST['theme'];

	$data = '$themeSettings = array('."\r\n";

	foreach ($colors as $field => $input) {
		$data .= "'".$field."' => '".$input."',"."\r\n";
	}

	$data .= ");";

	$_SESSION['cometchat']['error'] = 'Theme updated successfully';

	configeditor('SETTINGS',$data,0,dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$_GET['data'].DIRECTORY_SEPARATOR.$_GET['data'].'.php');	

	echo 1;

}

function clonetheme() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	$body = <<<EOD
	$navigation
	<form action="?module=themes&action=clonethemeprocess" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Create Theme</h2>
		<h3>Please enter the name of your new theme. Do not include special characters in your theme name.</h3>
		<div>
			<div id="centernav">
				<div class="title">Theme name:</div><div class="element"><input type="text" class="inputbox" name="theme"><input type="hidden" name="clone" value="{$_GET['theme']}"></div>
				<div style="clear:both;padding:5px;"></div>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Add Theme" class="button">&nbsp;&nbsp;or <a href="?module=language">cancel</a>
		<input type="hidden" value="{$_SESSION['token']}" name="token">
	</div>

	<div style="clear:both"></div>

EOD;

	template();

}

function clonethemeprocess() {
	checktoken();

	$theme = createslug($_POST['theme']);
	$clone = $_POST['clone'];

	$dirstoclone = array();
	
	if ($handle = opendir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$file) && file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'code.php')) {
				if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$clone.DIRECTORY_SEPARATOR.$file.'.css')) {
					array_push($dirstoclone,(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$clone));
				}
			}
		}
		closedir($handle);
	}

	if ($handle = opendir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'plugins')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$file) && file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'code.php')) {
				if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$clone.DIRECTORY_SEPARATOR.$file.'.css')) {
					array_push($dirstoclone,(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$clone));
				}
			}
		}
		closedir($handle);
	}
	
	if ($handle = opendir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'extensions')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.$file) && file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'code.php')) {
				if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$clone.DIRECTORY_SEPARATOR.$file.'.css')) {
					array_push($dirstoclone,(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$clone));
				}
			}
		}
		closedir($handle);
	}

	if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$clone.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'cometchat.css')) {
		array_push($dirstoclone,(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$clone));
		array_push($dirstoclone,(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$clone.DIRECTORY_SEPARATOR.'css'));
		array_push($dirstoclone,(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$clone.DIRECTORY_SEPARATOR.'images'));
	}

	foreach ($dirstoclone as $dir) {
		$newdir = str_replace($clone,$theme,$dir);
		copydirectory($dir,$newdir,$clone,$theme);
	}

	$_SESSION['cometchat']['error'] = 'New theme added successfully';
	header("Location:?module=themes");
}

function removethemeprocess() {
	checktoken();

	$theme = $_GET['data'];

	if ($theme != 'default' && $theme != 'dark' && $theme != 'base' && $theme != 'lite' && !empty($theme)) {
	
		if ($handle = opendir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules')) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$file) && file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'code.php')) {
					if (is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme)) {
						deletedirectory((dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme));
					}
				}
			}
			closedir($handle);
		}

		if ($handle = opendir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'plugins')) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$file) && file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'code.php')) {
					if (is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme)) {
						deletedirectory((dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme));
					}
				}
			}
			closedir($handle);
		}

		
		if ($handle = opendir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'extensions')) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.$file) && file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'code.php')) {
					if (is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme)) {
						deletedirectory((dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme));
					}
				}
			}
			closedir($handle);
		}

		if (is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'i'.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme)) {
			deletedirectory((dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'i'.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme));
		}
	
		if (is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'m'.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme)) {
			deletedirectory((dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'m'.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme));
		}
	
		if (is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'desktop'.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme)) {
			deletedirectory((dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'desktop'.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme));
		}

		if (is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme)) {
			deletedirectory((dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme));
		}

		$_SESSION['cometchat']['error'] = 'Theme deleted successfully';

	} else {
		$_SESSION['cometchat']['error'] = 'Sorry, this theme cannot be deleted. Please manually remove the theme from the "themes" folder.';
	}

	
	header("Location:?module=themes");
}

function exporttheme() {
	checktoken();

	global $currentversion;

	$theme = createslug($_GET['data']);

	$zip = new ZipArchive();

	if ($zip->open(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."temp".DIRECTORY_SEPARATOR.$theme.".zip", ZIPARCHIVE::CREATE) !== TRUE) {
		echo "This feature is experimental and works only for certain configurations.";
		exit;
	}

	$dirstotheme = array();

	if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'i'.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.'i.css')) {
		array_push($dirstotheme,(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'i'.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme));
	}

	if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'m'.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.'m.css')) {
		array_push($dirstotheme,(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'m'.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme));
	}

	if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'desktop'.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.'desktop.css')) {
		array_push($dirstotheme,(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'desktop'.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme));
	}
	
	if ($handle = opendir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$file) && file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'code.php')) {
				if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.$file.'.css')) {
					array_push($dirstotheme,(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme));
				}
			}
		}
		closedir($handle);
	}

	if ($handle = opendir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'plugins')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$file) && file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'code.php')) {
				if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.$file.'.css')) {
					array_push($dirstotheme,(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme));
				}
			}
		}
		closedir($handle);
	}
	
	if ($handle = opendir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'extensions')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && is_dir(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.$file) && file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'code.php')) {
				if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.$file.'.css')) {
					array_push($dirstotheme,(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme));
				}
			}
		}
		closedir($handle);
	}

	if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'cometchat.css')) {
		array_push($dirstotheme,(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme));
		array_push($dirstotheme,(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.'css'));
		array_push($dirstotheme,(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.'images'));
	}

	foreach ($dirstotheme as $dir) {
		$iterator = new DirectoryIterator($dir);

		foreach ($iterator as $key) {
			$key2 = str_replace(dirname(dirname(__FILE__)).'/','',$dir.'/'.$key);
			if (is_file($dir.'/'.$key)) {
				$zip->addFile($dir.'/'.$key, $key2);
			}
		}
	}

	$zip->addFromString('version.txt', $currentversion);

	header("Location:../temp/$theme.zip");
}

function uploadtheme() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	$body = <<<EOD
	$navigation
	<form action="?module=themes&action=uploadthemeprocess" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Upload new theme</h2>
		<h3>Have you downloaded a new CometChat theme? Use our simple installation facility to add the new theme to your site.</h3>

		<div>
			<div id="centernav">
				<div class="title">Theme:</div><div class="element"><input type="file" class="inputbox" name="file"></div>
				<div style="clear:both;padding:5px;"></div>
			</div>
			<div id="rightnav">
				<h1>Tips</h1>
				<ul id="modules_availablethemes">
					<li>Please make sure that the theme you upload is designed for your version.</li>
 				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Add theme" class="button">&nbsp;&nbsp;or <a href="?module=themes">cancel</a>
		<input type="hidden" value="{$_SESSION['token']}" name="token">
	</div>

	<div style="clear:both"></div>

EOD;

	template();

}

function uploadthemeprocess() {
	checktoken();

	global $db;
	global $body;	
	global $trayicon;
	global $navigation;
	global $themes;

	$extension = '';
	$error = '';

	if (!empty($_FILES["file"]["size"])) {
		if ($_FILES["file"]["error"] > 0) {
			$error = "Theme corrupted. Please try again.";
		} else {
			if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."temp" .DIRECTORY_SEPARATOR. $_FILES["file"]["name"])) {
				unlink(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."temp" .DIRECTORY_SEPARATOR. $_FILES["file"]["name"]);
			}

			if (!move_uploaded_file($_FILES["file"]["tmp_name"], dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."temp" .DIRECTORY_SEPARATOR. $_FILES["file"]["name"])) {
				$error = "Unable to copy to temp folder. Please CHMOD temp folder to 777.";
			}
		}
	} else {
		$error = "Theme not found. Please try again.";
	}
	
	if (!empty($error)) {
		$_SESSION['cometchat']['error'] = $error;
		header("Location: ?module=themes&action=uploadtheme");
		exit;
	}

	require_once('pclzip.lib.php');

	$filename = $_FILES['file']['name'];

	$archive = new PclZip(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."temp" .DIRECTORY_SEPARATOR. $_FILES["file"]["name"]);

	if ($archive->extract(PCLZIP_OPT_PATH, dirname(dirname(__FILE__))) == 0) {
		$error = "Unable to unzip archive. Please upload the contents of the zip file to themes folder.";
	}

	if (!empty($error)) {
		$_SESSION['cometchat']['error'] = $error;
		header("Location: ?module=themes&action=uploadtheme");
		exit;
	}

	unlink(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."temp" .DIRECTORY_SEPARATOR. $_FILES["file"]["name"]);

	header("Location: ?module=themes");
	exit;
	
}
