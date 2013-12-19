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
//	7/18/2013	replaced all authentication to $user_level while leaving in $sysadmin because I'm pretty sure that's just the root admin.
//	8/29/2013	cleaned up the code a little
// edit_notices.php -- 	User Access Level: Admin
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);?>
<br><br>
<?
  if ($read_only=="Y"){
    echo("<br><center>ACCESS DENIED THIS IS A READ-ONLY SYSTEM</center><br>");
    html_footer();
    exit;
  }
  
  if (!($sysadmin=="Y" or $user_level=="Admin")){
    echo("<br><center>ACCESS DENIED</center><br>");
    html_footer();
    exit;
  }

// SQL Query for Selecting Reminder Records and get data
if ($I!=1){
$sql = "SELECT * FROM customers WHERE org = '$userorg'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
    $email_from = $row["email_from"];
    $n1_day = $row["n1_day"];
    $n1_msg = $row["n1_msg"];
    $n2_day = $row["n2_day"];
    $n2_msg = $row["n2_msg"];
    $n3_day = $row["n3_day"];
    $n3_msg = $row["n3_msg"];
    $nfor_day = $row["nfor_day"];
    $nfor_msg = $row["nfor_msg"];
    $npub_day = $row["npub_day"];
    $npub_msg = $row["npub_msg"];
}?>

<center>EDIT REMINDERS</center><br>
<?
if ($submitok==""):
// Display the reminders record form
?>
<form method=post action="<?=$PHP_SELF;?>?I=1">
<table align="center" width="650" border=0 cellpadding=0 cellspacing=10>
    <tr><td></td>
	    <td>
		    <p>An e-mail message will be sent on the day indicated below.  To not 
			send a message place 0 in the entry.  The message contains the 
			following information</p>
			<p><u>Subject:</u> Due Date Reminder - Docket No. "docket number inserted"<br>
			<u>Message:</u> The following is due within the next "number of days inserted" 
			days: Docket No. "docket", Title "title", Status "status", Due Date "due date".</p>
        </td>	
	</tr>
    <tr>
        <td align=right width="150">
            <u>E-mail From</u>
        </td>
        <td width="400">
            <input name="email_from" maxlength="20" size="20" value="<?=$email_from;?>">
			For The "From" Field
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <u>Notice 1</u>
        </td>
        <td width="400">
            <input name="n1_day" maxlength="10" size="10" value="<?=$n1_day;?>">
			Days Advance Notice
        </td>
    </tr>
	<tr>
        <td align=right width="150">
            Message
        </td>
        <td width="400">
            <textarea wrap name="n1_msg" rows=2 cols=35><?=$notes;?><?=$n1_msg;?></textarea>
        </td>
	</tr>
    <tr>
        <td align=right width="150">
            <u>Notice 2</u>
        </td>
        <td width="400">
            <input name="n2_day" maxlength="10" size="10" value="<?=$n2_day;?>">
			Days Advance Notice
        </td>
    </tr>
	<tr>
        <td align=right width="150">
            Message
        </td>
        <td width="400">
            <textarea wrap name="n2_msg" rows=2 cols=35><?=$notes;?><?=$n2_msg;?></textarea>
        </td>
	</tr>
    <tr>
        <td align=right width="150">
            <u>Notice 3</u>
        </td>
        <td width="400">
            <input name="n3_day" maxlength="10" size="10" value="<?=$n3_day;?>">
			Days Advance Notice
        </td>
    </tr>
	<tr>
        <td align=right width="150">
            Message
        </td>
        <td width="400">
            <textarea wrap name="n3_msg" rows=2 cols=35><?=$notes;?><?=$n3_msg;?></textarea>
        </td>
	</tr>
	<tr>
	    <td colspan="2">
		    THE FOLLOWING MESSAGES ARE IN ADDITION TO THOSE ABOVE
	    </td>
	</tr>
    <tr>
        <td align=right width="150">
            <u>Foreign Filing</u>
        </td>
        <td width="400">
            <input name="nfor_day" maxlength="10" size="10" value="<?=$nfor_day;?>">
			Days Advance Notice
        </td>
    </tr>
	<tr>
        <td align=right width="150">
            Message
        </td>
        <td width="400">
            <textarea wrap name="nfor_msg" rows=2 cols=35><?=$notes;?><?=$nfor_msg;?></textarea>
        </td>
	</tr>
    <tr>
        <td align=right width="150">
            <u>Publication Notice</u>
        </td>
        <td width="400">
            <input name="npub_day" maxlength="10" size="10" value="<?=$npub_day;?>">
			Days Advance Notice
        </td>
    </tr>
	<tr>
        <td align=right width="150">
            Message
        </td>
        <td width="400">
            <textarea wrap name="npub_msg" rows=2 cols=35><?=$notes;?><?=$npub_msg;?></textarea>
        </td>
	</tr>	
    <tr>
        <td align="center" colspan="4" width="650">
            <input type=submit name="submitok" value="   OK   ">
            <hr noshade size="1" width="650">
        </td>
    </tr>
</table></form>

<?
else:
// Update Customer Records
   $sql = "UPDATE customers SET
      email_from = '$email_from',
      n1_day = '$n1_day',
      n1_msg = '$n1_msg',
      n2_day = '$n2_day',
      n2_msg = '$n2_msg',
      n3_day = '$n3_day',
      n3_msg = '$n3_msg',
      nfor_day = '$nfor_day',
      nfor_msg = '$nfor_msg',
      npub_day = '$npub_day',
      npub_msg = '$npub_msg'
	  WHERE org = '$userorg'";
   		
// The script performs the database insert
if (!mysql_query($sql))
    error("A database error occurred in processing your ".
    "submission.\\nIf this error persists, please ".
    "contact your HelpDesk.");
?>
<table align="center" width="500"><br>
<p><strong>Your record has been successfully changed.</strong></p>
<p> To return to the login page, click <a href="index.php">here</a></p>
<p> To view the Control Panel, click <a href="setup.php">here</a></p>
</table><br>
<?
endif;
html_footer(); ?>
