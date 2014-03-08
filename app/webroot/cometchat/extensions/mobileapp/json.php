<?php

	/*

	CometChat
	Copyright (c) 2013 Inscripts

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

	include_once(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."config.php");
	
	
	if(file_exists(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."chatrooms".DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")){
		include_once(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."chatrooms".DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php");
	} else {
		include_once(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."chatrooms".DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php");
	}
	
	if(file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")){
		include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php");
	} else {
		include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php");
	}
	
	if (file_exists(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.$theme.'.php')) {
		include_once (dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."themes".DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.$theme.'.php');
	} else {
		include_once (dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'default.php');
	}
	
	$response_lang = Array();
	$response_lang['rtl'] = $rtl;
	
	$language['hash'] = md5(serialize($language));
	$mobileapp_language['hash'] = md5(serialize($mobileapp_language));
	$chatrooms_language['hash'] = md5(serialize($chatrooms_language));
	$response_lang['core'] = $language;
	$response_lang['chatrooms'] = $chatrooms_language;
	$response_lang['mobile'] = $mobileapp_language;

	foreach($response_lang as $key => $val){
		if(is_array($val)){
			foreach($val as $langkey => $langval){
				$response_lang[$key][$langkey] = strip_tags($langval);
			}
		}
	}
	if(empty($_REQUEST['langhash']) || $_REQUEST['langhash'] <> md5(serialize($response_lang))){
		$response['langhash'] = md5(serialize($response_lang));
		$response['lang'] = $response_lang;
	}

	$response_css = $themeSettings;
	if(empty($_REQUEST['csshash']) || $_REQUEST['csshash'] <> md5(serialize($response_css))){
		$response['csshash'] = md5(serialize($response_css));
		$response['css'] = $response_css;
	}

	$response_config['fullName'] = $fullName;
	$response_config['DISPLAY_ALL_USERS'] = DISPLAY_ALL_USERS;
	$response_config['REFRESH_BUDDYLIST'] = REFRESH_BUDDYLIST;
	$response_config['USE_COMET'] = USE_COMET;
	if(defined('USE_COMET') && USE_COMET == '1'){
		$response_config['KEY_A'] = KEY_A;
		$response_config['KEY_B'] = KEY_B;
		$response_config['KEY_C'] = KEY_C;
		$response_config['TRANSPORT'] = TRANSPORT;
		$response_config['COMET_CHATROOMS'] = COMET_CHATROOMS;
	}
	if(empty($_REQUEST['confighash']) || $_REQUEST['confighash'] <> md5(serialize($response_config))){
		$response['confighash'] = md5(serialize($response_config));
		$response['config'] = $response_config;
	}
	
	$response['avchat_enabled'] = '0';			
	if (in_array('avchat',$plugins) && file_exists(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."plugins".DIRECTORY_SEPARATOR."avchat".DIRECTORY_SEPARATOR."config.php")) {	   
		include_once (dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."plugins".DIRECTORY_SEPARATOR."avchat".DIRECTORY_SEPARATOR."config.php");		
		if ($videoPluginType == '3') {			
			$response['avchat_enabled'] = '1';		
		}	
	}
	
	$response['filetransfer_enabled'] = '0';		
	if (in_array('filetransfer',$plugins)) {		
		$response['filetransfer_enabled'] = '1';	
	}

	$response['report_enabled'] = '0';
	if (in_array('report',$plugins) && file_exists(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."plugins".DIRECTORY_SEPARATOR."report".DIRECTORY_SEPARATOR."config.php")) {
		$response['report_enabled'] = '1';
	}
	
	$response['clearconversation_enabled'] = '0';
	if (in_array('clearconversation',$plugins)) {
		$response['clearconversation_enabled'] = '1';
	}
	
	$response['allowusers_createchatroom'] = '0';
	include_once(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."chatrooms".DIRECTORY_SEPARATOR."config.php");
	if ($allowUsers == '1') {
		$response['allowusers_createchatroom'] = '1';
	}

	echo json_encode($response);
