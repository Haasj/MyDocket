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
//			changed all the $orgadmins to user_level==admin for permission stuff
//			updated so you can edit/add user_levels. required category. currently only an admin or sysadmin can update these permissions
//	7/18/2013	added change password link whenever you can't delete it--only should happen when it's yourself. this is cause if you don't have the privs, then you can't get there.
//			bug: still see delete when you go to your own user edit as admin
//			got around it by checking if the name is the same as your own; then you can't delete.
// full_user.php
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);?>
<?

if ($read_only=="Y"){
  echo("<br><center>ACCESS DENIED THIS IS A READ-ONLY SYSTEM</center><br>");
  html_footer();
  exit;
}
if ($identity=="myself")
  $ID=$userid;

// For new records, incoming link is set to $ID="0" and I="0"
// Otherwise, $ID is set to the existing record number
// SQL Query for an existing NDA record and retrieving data
if ($I=="1"){
$sql="SELECT * FROM users WHERE ID='$ID'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
    $username_new = $row["username"];
    $userorg = $row["userorg"];
    $member_new = $row["member"];
    $memadmin_new = $row["memadmin"];
    $user_level_new = $row["user_level"];
    $reminders_new = $row["reminders"];			  
    $fullname_new = $row["fullname"];
    $company_new = $row["company"];
    $street_new = $row["street"];
    $city_new = $row["city"];
    $state_new = $row["state"];
    $zip_new = $row["zip"];
    $country_new = $row["country"];
    $tel_new = $row["tel"];
    $fax_new = $row["fax"];
    $email_new = $row["email"];
    $notes_new = $row["notes"];
}?>
<table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">
   <? if (($sysadmin=="Y" or $user_level=="Admin") and ($ID!="0" and $identity!="myself" and $fullname!=$fullname_new)){?>
    <a href="delete_confirm.php?module=<?=$module;?>&TABLE=users&ID=<?=$ID;?>&NAME=<?=$username_new;?>">Delete</a><?}
    else {?>
    <a href="changepass.php?module=<?=$module;?>">Change Password...</a><?}?>
  </td></tr>
</table>
<br>
<? if ($ID=="0") echo("<center><br>ADD USER RECORD</center><br>");
   else echo("<center><br>EDIT USER RECORD</center><br>");
if ($submitok=="" or $fullname_new=="" or $email_new=="" or $company_new==""){
  if ($submitok!=""){?>
    <center><br><font color="red">Please update fields marked with a red asterisk.</font><br></center><?}?>
<form method=post action="<?=$PHP_SELF;?>">
  <input type="hidden" name="module" value="<?=$module;?>">
  <input type="hidden" name="ID" value="<?=$ID;?>">
  <input type="hidden" name="I" value="0">
<table align="center" width="650" border="0" cellpadding="0" cellspacing="10">
    <? if ($ID=="0"){?>
    <tr>
        <td align=right width="150">
            <p>Username</p>
        </td>
        <td width="400">
            <input name=username_new type=text maxlength=35 size=35 value="<?=$username_new;?>">
            <font color=orangered size=+1><TT><B>*</B></TT></font>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Password</p>
        </td>
        <td width="400">
            <input name=password_new type=text maxlength=35 size=35 value="<?=$password_new;?>">
            <font color=orangered size=+1><TT><B>*</B></TT></font>
        </td>
    </tr>
	<?} else {?>
    <tr>
        <td align=right width="150">
            <p>Username</p>
        </td>
        <td width="400">
            <?=$username_new;?>
        </td>
    </tr>
	<?}?>
	<? if ($sysadmin=="Y" or $user_level=="Admin"){?>
    <tr>
        <td align=right width="150">
            <p>Member Group</p>
        </td>		
        <td width="400">
            <select name=member_new size="1">			
            <option><?=$member_new;?></option>			
            <option><?=$userorg;?></option>
		    <? $sql="SELECT * FROM menus WHERE org='$userorg' and menu_type='FIRM' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
		    <? $sql="SELECT * FROM menus WHERE org='$userorg' and menu_type='CLIENT' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
			<font color=orangered size=+1><TT><B>*</B></TT></font>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Member Admin</p>
        </td>		
        <td width="400">
            <select name=memadmin_new size="1">
            <option><?=$memadmin_new;?></option>
            <option>N</option>
			<option>Y</option>
            </select>
            <small>Write/Modify Enabled (default=N)&nbsp;</small><font color=orangered size=+1><TT><B>*</B></TT></font>
        </td>
    </tr>
    <tr>
      <td align="right" width="150">
	<p>User Level</p>
      </td>
      <td width="400">
	<select name=user_level_new size="1">
	  <option><?=$user_level_new;?></option>
	  <option>Admin</option>
	  <option>User</option>
	  <option>Viewer</option>
	</select>
	<font color=orangered size=+1><TT><B>*</B></TT></font>
      </td>
    </tr>
	<?}
	else {?>
    <tr>
        <td align=right width="150">
            <p>Member Group</p>
        </td>
        <td width="400">
            <?=$member_new;?>
			<input type=hidden name=member_new value="<?=$member_new;?>">
			<input type=hidden name=memadmin_new value="<?=$memadmin_new;?>">
        </td>
    </tr>
	<?}?>	
    <tr>
        <td align=right width="150">
            <p>Email Reminders</p>
        </td>		
        <td width="400">
            <select name=reminders_new size="1">
            <option><?=$reminders_new;?></option>
            <option>Y</option>
			<option>N</option>
            </select>
            <small>Receive Reminders (default=Y)&nbsp;</small>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Full Name</p>
        </td>
        <td width="400">
            <input name=fullname_new type=text maxlength=100 size=35 value="<?=$fullname_new;?>">
            <font color=orangered size=+1><TT><B>*</B></TT></font>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Company</p>
        </td>
        <td width="400">
            <input name=company_new type=text maxlength=100 size=35 value="<?=$company_new;?>">
            <font color=orangered size=+1><TT><B>*</B></TT></font>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Street</p>
        </td>
        <td width="400">
            <input name=street_new type=text maxlength=100 size=35 value="<?=$street_new;?>">
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>City</p>
        </td>
        <td width="400">
            <input name=city_new type=text maxlength=100 size=35 value="<?=$city_new;?>">
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>State</p>
        </td>
        <td width="400">
            <select name=state_new size="1">
            <option><?=$state_new;?></option>
            <? state_list();?>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Zip Code</p>
        </td>
        <td width="400">
            <input name=zip_new type=text maxlength=100 size=35 value="<?=$zip_new;?>">
        </td>
    </tr>
	<tr>
		<td align=right width="150">
            <p>Country</p>
        </td>
        <td width="400">
            <select name=country_new>
            <option><?=$country_new;?></option>
            <? country_list();?>
            </select>
            <font color=orangered size=+1><TT><B>*</B></TT></font>
	        &nbsp;&nbsp;<a target="_blank" href="help.php#country_codes">Country Codes</a>
        </td>
	</tr>
    <tr>
        <td align=right width="150">
            <p>Telephone</p>
        </td>
        <td width="400">
            <input name=tel_new type=text maxlength=100 size=35 value="<?=$tel_new;?>">
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Fax</p>
        </td>
        <td width="400">
            <input name=fax_new type=text maxlength=100 size=35 value="<?=$fax_new;?>">
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>email</p>
        </td>
        <td width="400">
            <input name=email_new type=text maxlength=100 size=35 value="<?=$email_new;?>">
            <font color=orangered size=+1><TT><B>*</B></TT></font>
        </td>
    </tr>
	<tr>
        <td align=right width="150">
            <p>Notes</p>
        </td>
        <td width="400">
            <textarea wrap name=notes_new rows=2 cols=35><?=$notes_new;?></textarea>
        </td>
	</tr>	
    <tr>
        <td align="center" colspan="4" width="650">
            <hr noshade size="1" width="650">
            <input type=submit name="submitok" value="   OK   ">
        </td>
    </tr>
</table>
<?
}
else {
// Set defaults
if ($memadmin_new=="") $memadmin_new="N";
if ($reminders_new=="") $reminders_new="Y";

if ($ID=="0"){ // ADD NEW RECORD
// Check for existing user with the new id
    $sql = "SELECT COUNT(*) FROM users WHERE username = '$username_new'";
    $result = mysql_query($sql);
    if (!$result) {
       error("A database error occurred in processing your username ".
           "submission.\\nIf this error persists, please ".
           "contact your HelpDesk.");
               }
    if (mysql_result($result,0,0)>0) {
       error("A user already exists with your chosen username.\\n".
            "Please try another.");    }

// Process record submission
    $sql = "INSERT INTO users SET
              customer_ID = '$customer_ID',
              username = '$username_new',
              userorg = '$userorg',
	      member = '$member_new',
	      memadmin = '$memadmin_new',
	      user_level = '$user_level_new',
	      reminders = '$reminders_new', 			  
              password = PASSWORD('$password_new'),
	      fullname = '$fullname_new',
	      company = '$company_new',
	      street = '$street_new',
	      city = '$city_new',
	      state = '$state_new',
	      zip = '$zip_new',
	      country = '$country_new',
	      tel = '$tel_new',
	      fax = '$fax_new',
	      email = '$email_new',
              notes = '$notes_new'";}

else // UPDATE EXISTING RECORD
    $sql = "UPDATE users SET
              customer_ID = '$customer_ID',
              userorg = '$userorg',
	      member = '$member_new',
	      memadmin = '$memadmin_new',
	      user_level = '$user_level',
	      reminders = '$reminders_new',
	      fullname = '$fullname_new',
	      company = '$company_new',
	      street = '$street_new',
	      city = '$city_new',
	      state = '$state_new',
	      zip = '$zip_new',
	      country = '$country_new',
	      tel = '$tel_new',
	      fax = '$fax_new',
	      email = '$email_new',
              notes = '$notes_new'
              WHERE ID='$ID'";
			  
// RUN THE QUERY
     if (!mysql_query($sql)){
             error("A database error occurred in processing your ".
              "submission.\\nIf this error persists, please ".
              "contact your HelpDesk.");
             }

?>
<table align="center" width="500">
<p><strong>Your record has been successfully updated.</strong></p>
<p> To return to the login page, click <a href="index.php">here</a></p>
<? if ($sysadmin=="Y" or $user_level=="Admin"){?>
<p> To view user records, click <a href="users_list.php?module=<?=$module;?>&ORDER=ID">here</a></p>
<?}?>
</table><br>
<?}
html_footer(); ?>
