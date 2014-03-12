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

if ($userid == 0) {
	print "Content-type: text/plain\n\n<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<result>";
	print "\t<update>false</update>";
	print "</result>";
	exit;
}

if (!empty($_GET['username']) && $_GET['identity'] == '0') {

	$sql = "insert into cometchat_videochatsessions (username,identity,timestamp) values ('".mysql_real_escape_string($_GET['username'])."','0','".getTimeStamp()."')";
 	$query = mysql_query($sql);
	
	print "Content-type: text/plain\n\n<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<result>";
	print "\t<update>true</update>";
	print "</result>";

} 

if (!empty($_GET['identity']) && $_GET['identity'] != '0') {

	$sql = "insert into cometchat_videochatsessions (username,identity,timestamp) values ('".mysql_real_escape_string($_GET['username'])."','".mysql_real_escape_string($_GET['identity'])."','".getTimeStamp()."')";
	$query = mysql_query($sql);

	$sql = "update cometchat_videochatsessions set identity = ('".mysql_real_escape_string($_GET['identity'])."') where username = ('".mysql_real_escape_string($_GET['username'])."')";
 	$query = mysql_query($sql);

	if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }
	
	print "Content-type: text/plain\n\n<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<result>";
	print "\t<update>true</update>";
	print "</result>";

} 

if (!empty($_GET['friends'])) {

	$friend = $_GET['friends'];
	$sql = "select * from cometchat_videochatsessions where username = '".mysql_real_escape_string($_GET['friends'])."'";
 	$query = mysql_query($sql);

	if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }
	
	$chat = mysql_fetch_array($query);

	print "Content-type: text/plain\n\n<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<result>";
	print "\t<friend>\n\t\t<user>{$friend}</user>";
	if (!empty($chat['identity'])) {
		print "\t\t<identity>{$chat['identity']}</identity>";
	}
	print "\t</friend>";

	print "</result>";
}