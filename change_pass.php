<?php
/*********************************************************************************
 * The contents of this file are subject to the Mozilla Public License Version 1.1.
 * ("License"); You may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://www.mozilla.org/MPL/.
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  MyDocket Open Source.
 * The Initial Developer of the Original Code is IP Group (www.ipgroup.org).
 * Portions created by IP Group are Copyright (C) IP Group, All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
//Maitenence Log Jonathan Haas:
//	7/17/2013	added $user_level to header call
// change_pass.php
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);?>
<table align="left" border="3" cellpadding="0" cellspacing="0" bordercolor="#336666" bgcolor="#336666"><tr>
  <td width="200" align="center" bgcolor="#EEEEEE"><small><?=$fullname;?></small></td></tr>
</table><br><br>
<center>CHANGE PASSWORD</center><br>
<? if ($chgpw == ""){ ?>
<form method=post action="<?=$PHP_SELF?>">
<table align="center" width="300">
<tr>
    <td>Enter your new password:</td>
</tr>
<tr>
	<td align="right">New Password: <input type=password name=newpw></td>
</tr>
<tr>
	<td align="right">Retype: <input type=password name=newpw2></td>
</tr>
<tr>
	<td align="right" colspan="2"><hr noshade>
    <input type=submit name=chgpw value="   OK   ">
    </td>
</tr>
</table></form>
<?}
else {
	if ($newpw != $newpw2)
		error("The two password fields did not match! Please try again.");
	if ($newpw == "")
		error("You did not provide a password. Please try again.");
	$sql = "UPDATE users SET password=PASSWORD('$newpw') WHERE username='$username'";
	if (mysql_query($sql)){
	$pwd=$newpw;
	?>
  <table align="center" width="400">
  Your password has been changed! To return home, click <a href="index.php">here</a>.<br>
  </table><br>
	<?}
	else
		error("A database error occurred while processing your request.
		\\nIf the problem persists, please contact your HelpDesk.\\n".
		mysql_error());
}
html_footer(); ?>
