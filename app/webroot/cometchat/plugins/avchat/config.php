<?php

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* SETTINGS START */

$videoPluginType = '3';
$vidWidth = '220';
$vidHeight = '165';
$maxP = '10';
$quality = '90';
$winWidth = '650';
$winHeight = '365';
$connectUrl = '';
$camWidth = '320';
$camHeight = '240';
$fps = '30';
$soundQuality = '7';
$applicationid = '';
$appAuthSecret = '';


/* SETTINGS END */

/* videoPluginType Codes
0. Stratus
1. RED5/FMS (RTMP)
2. FMS (RTMFP)
3. CometChat Servers
4. AddLive
*/

if ($videoPluginType == '0') {
	$camWidth = '435';
	$camHeight = '327';
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////