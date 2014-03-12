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

include dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules.php";

$themeslist = '';
$dir = dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."themes"; 
$files = scandir($dir); 
foreach ($files as $listedtheme){
	if (is_dir($dir."/".$listedtheme) && $listedtheme != '' && !preg_match('/^\.(.*)/',$listedtheme) && file_exists($dir.DIRECTORY_SEPARATOR.$listedtheme.DIRECTORY_SEPARATOR.$listedtheme.".php")) {
		$themename = ucfirst($listedtheme);
		if ($theme != $listedtheme && $listedtheme != 'lite' && $listedtheme != 'base') {
		$themeslist .=  <<<EOD
<a href="javascript:void(0);" onclick="javascript:changeTheme('{$listedtheme}')">{$themename}</a><br/>
EOD;
		}
	}
}

include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
	include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
}

$currenttheme = ucfirst($theme);

$themesoptions = '';
if(!empty($themeslist)) {
	$themesoptions = "<b>{$themechanger_language[1]}</b><br/><br/>{$themeslist}";
} else {
	$themesoptions = "<b>{$themechanger_language[2]}</b>";
}

echo <<<EOD
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="cache-control" content="no-cache">
		<meta http-equiv="pragma" content="no-cache">
		<meta http-equiv="expires" content="-1">
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"/> 
		<link type="text/css" rel="stylesheet" media="all" href="../../css.php?type=module&name=themechanger" /> 
		<script>
			function changeTheme(name) {
				set_cookie('theme',name);

				try {
						if (parent.jqcc.cometchat.ping() == 1) {
							parent.jqcc.cometchat.closeModule('themechanger');	
						}
					} catch (e) { }

				parent.location.reload();
			}

			function set_cookie(name,value) {
				var today = new Date();
				today.setTime( today.getTime() );
				expires = 1000 * 60 * 60 * 24;
				var expires_date = new Date( today.getTime() + (expires) );
				document.cookie = "{$cookiePrefix}" + name + "=" +escape( value ) + ";path=/" + ";expires=" + expires_date.toGMTString();
			}
			
		</script>
	</head>
	<body>
		<div class="container">
			{$themechanger_language[0]} <b>$currenttheme</b><br/><br/>

			{$themesoptions}
		</div>
	</body>
</html>
EOD;
?>