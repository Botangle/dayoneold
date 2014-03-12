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

include_once (dirname(__FILE__).DIRECTORY_SEPARATOR."config.php");

if (BAR_DISABLED == 1) { exit; }

if(get_magic_quotes_runtime()) { 
	set_magic_quotes_runtime(false); 
}

$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime; 

$HTTP_USER_AGENT = '';
$useragent = (isset($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : $HTTP_USER_AGENT;

ob_start();

if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.$theme.'.php')) {
	include_once (dirname(__FILE__).DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.$theme.'.php');
} else {
	include_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'default.php');
}

$left = 'left';
$right = 'right';
$dir = 'ltr';
$lite = 0;
$cbfn = '';

if ($rtl == 1) {
	$left = 'right';
	$right = 'left';
	$dir = 'rtl';
}

if ($lightWeight == 1) {
	$lite = 1;
}

if(!empty($_REQUEST['callbackfn'])){
	$cbfn = $_REQUEST['callbackfn'];
}

if (!empty($_REQUEST['type'])) {
	$type = cleanInput($_REQUEST['type']);
	if (!empty($_REQUEST['name'])) {
		$name = cleanInput($_REQUEST['name']);
	} else {
		$name = '';
	}
	if($type=='desktop' || $type=='mobile'){
		$name=$type;
		$type='extension';
	}
} else {
	$type = 'core';
	$name = 'default';
}

if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$type.$name.$cbfn.$theme.'.css') && DEV_MODE != 1) {

	if (!empty($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == filemtime(dirname(__FILE__).DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$type.$name.$cbfn.$theme.'.css')) {
		header("HTTP/1.1 304 Not Modified");
		exit;
	}

	readfile(dirname(__FILE__).DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$type.$name.$cbfn.$theme.'.css');
	$css = ob_get_clean();

} else {

	if ($type != 'core' || $name != 'default') {

		if (empty($name)) {
			if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.$type.".css")) {
				include_once (dirname(__FILE__).DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.$type.".css");
			} else {
				include_once (dirname(__FILE__).DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR."default".DIRECTORY_SEPARATOR.$type.".css");
			}
		} elseif(!empty($name) && $cbfn != 'desktop') {
			if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.$type."s".DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.$name.".css") ) {				
				include_once (dirname(__FILE__).DIRECTORY_SEPARATOR.$type."s".DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.$name.".css");
				
			} else {
				include_once (dirname(__FILE__).DIRECTORY_SEPARATOR.$type."s".DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR."default".DIRECTORY_SEPARATOR.$name.".css");
			}
			
		} else {
			include_once (dirname(__FILE__).DIRECTORY_SEPARATOR.$type."s".DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."cometchat.css");
		}
	} else {
		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."cometchat.css")) {
			include_once (dirname(__FILE__).DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."cometchat.css");
		} else {
			include_once (dirname(__FILE__).DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR."default".DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."cometchat.css");
		}
	}

	$css = minify(ob_get_clean());

	$fp = @fopen(dirname(__FILE__).DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$type.$name.$cbfn.$theme.'.css', 'w'); 
	@fwrite($fp, $css);
	@fclose($fp);
}

if (phpversion() >= '4.0.4pl1' && (strstr($useragent,'compatible') || strstr($useragent,'Gecko'))) {
	if (extension_loaded('zlib') && GZIP_ENABLED == 1) {
		ob_start('ob_gzhandler');
	} else { ob_start(); }
} else { ob_start(); }

$lastModified = filemtime(dirname(__FILE__).DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$type.$name.$cbfn.$theme.'.css');

header('Content-type: text/css;charset=utf-8');
header('Cache-Control: max-age=259200');
header("Last-Modified: ".gmdate("D, d M Y H:i:s", $lastModified)." GMT");
header('Expires: '.gmdate("D, d M Y H:i:s", time() + 3600*24*365).' GMT');

echo $css;

$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$endtime = $mtime;
$totaltime = ($endtime - $starttime);
echo "\n\n/* Execution time: ".$totaltime." seconds */";

function cleanInput($input) {
	$input = trim($input);
	$input = preg_replace("/[^+A-Za-z0-9\_]/", "", $input); 
	return strtolower($input);
}

function minify( $css ) {
	$css = preg_replace( '#\s+#', ' ', $css );
	$css = preg_replace( '#/\*.*?\*/#s', '', $css );
	$css = str_replace( '; ', ';', $css );
	$css = str_replace( ': ', ':', $css );
	$css = str_replace( ' {', '{', $css );
	$css = str_replace( '{ ', '{', $css );
	$css = str_replace( ', ', ',', $css );
	$css = str_replace( '} ', '}', $css );
	$css = str_replace( ';}', '}', $css );

	return trim( $css );
}