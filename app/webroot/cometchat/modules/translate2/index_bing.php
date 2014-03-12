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

include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php";

if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
	include dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php";
}

echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="-1">
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/> 
<link type="text/css" rel="stylesheet" media="all" href="../../css.php?type=module&name=translate2" /> 
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>

<script>

$(document).ready(function() {
	parent.addLanguageCode();

	$("li").click(function() {
		var info = $(this).attr('id');
		parent.changeLanguage(info);

		$('.languages').hide();
		$('.translating').show();
		setTimeout(function() {
		try {
			if (parent.jqcc.cometchat.ping() == 1) {
				parent.jqcc.cometchat.closeModule('translate');	
			}
		} catch (e) { }

		$('.languages').show();
		$('.translating').hide();

		},3000);
	});
});



</script>

</head>
<body>
<div style="width:100%; margin:0 auto; margin-top: 0px;">

<div class="container">

<ul class="languages">

<li id="ar">Arabic</li><li id="bg">Bulgarian</li><li id="ca">Catalan</li><li id="zh-chs">Chinese (Simpl)</li><li id="zh-cht">Chinese (Trad)</li><li id="cs">Czech</li><li id="da">Danish</li><li id="nl">Dutch</li><li id="et">Estonian</li><li id="fi">Finnish</li><li id="fr">French</li><li id="de">German</li><li id="el">Greek</li><li id="ht">Haitian Creole</li><li id="he">Hebrew</li><li id="hi">Hindi</li><li id="hu">Hungarian</li><li id="id">Indonesian</li><li id="it">Italian</li><li id="ja">Japanese</li><li id="ko">Korean</li><li id="lv">Latvian</li><li id="lt">Lithuanian</li><li id="no">Norwegian</li><li id="pl">Polish</li><li id="pt">Portuguese</li><li id="ro">Romanian</li><li id="ru">Russian</li><li id="sk">Slovak</li><li id="sl">Slovenian</li><li selected="selected" id="es">Spanish</li><li id="sv">Swedish</li><li id="th">Thai</li><li id="tr">Turkish</li><li id="uk">Ukrainian</li><li id="vi">Vietnamese</li>

</ul>

<div class="translating">{$translate2_language[0]}</div>

<div style="clear:both"></div>
</div>
</div>
</body>
</html>
EOD;
?>