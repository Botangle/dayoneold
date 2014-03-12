<?php

if (!defined('CCADMIN')) { echo "NO DICE"; exit; }

if (empty($_GET['process'])) {
	global $getstylesheet;
	require dirname(__FILE__).DIRECTORY_SEPARATOR.'config.php';

	echo <<<EOD
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	{$getstylesheet}
	<form action="?module=dashboard&action=loadexternal&type=module&name=games&process=true" method="post">
	<div id="content">
		<h2>Settings</h2>
		<h3>Banned keywords will hide games which contain those keywords. Separate each word by comma e.g. adult, 18+, spiders</h3>
		
		<div>
			<div id="centernav" style="width:380px">
				<div class="title">Banned keywords:</div>
				<div class="element">
					<textarea type="text" class="inputbox" name="keywords">{$keywords}</textarea>
				</div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Partner ID:</div>
				<div class="element">
					<input type="text" class="inputbox" name="partner_id" value="{$partner_id}" />
				</div>
				<div style="clear:both;padding:5px;"></div>				
			</div>
		</div>
		
		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Update Settings" class="button" />&nbsp;&nbsp;or <a href="javascript:window.close();">cancel or close</a>
	</div>
	</form>
EOD;
} else {
	$data = '';
	foreach ($_POST as $field => $value) {
		$data .= '$'.$field.' = \''.$value.'\';'."\r\n";
	}
	
	configeditor('SETTINGS',$data,0,dirname(__FILE__).DIRECTORY_SEPARATOR.'config.php');	
	header("Location:?module=dashboard&action=loadexternal&type=module&name=games");
}