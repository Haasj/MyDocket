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
//	7/16/2013	changed it to say list/edit for clarity
//	7/17/2013	added $user_level to header call
// view_users.php
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level); ?>
<br><br>
<center>LIST/EDIT USER RECORDS</center><br>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="125"><U>Member</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=company"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=company DESC"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="150"><U>Fullname</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=fullname"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=fullname DESC"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="125"><U>Username</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=username"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
	<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=username DESC"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="125"><U>Telephone</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=tel"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
	<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=tel DESC"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="125"><U>email</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=email"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
	<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=email DESC"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
</font></tr>
<?
// SQL Query for Selecting All $TYPE IP Records
if ($sysadmin=="Y" or $orgadmin=="Y")
  $sql="SELECT * FROM users WHERE customer_ID='$customer_ID' ORDER BY $ORDER";
else
  $sql="SELECT * FROM users WHERE customer_ID='$customer_ID' and member='$member' ORDER BY $ORDER";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $ID=$row["ID"];
  $member = $row["member"];		  
  $fullname = $row["fullname"];
  $username = $row["username"];
  $tel = $row["tel"];
  $email = $row["email"];
  
?>
<tr bgcolor=EEEEEE>
  <td width="125"><small><?=$member;?></small></td>
  <td width="150"><small><a href="users_edit.php?module=<?=$module;?>&ID=<?=$ID;?>&I=1"><?=$fullname;?></a></small></td>
  <td width="100"><small><?=$username;?></small></td>
  <td width="100"><small><?=$tel;?></small></td>
  <td width="100"><small><?=$email;?></small></td>
</tr>
<?}?>
</table>
<? html_footer(); ?>
