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
//	6/18/2013	Added disclosure to the action types
//	6/27/2013	added nda, license, and general ipm to action types
//			made action types a dynamic list from menus.
//	7/1/2013	changed from action_type to auto_action in the select ^
//	7/2/2013	To do: require subtype for patents
//			done. it assigns an auto value for subtype, then checks to see if it is a patent and then checks the subtype.
//			added require for trademark. now that I have two, any additional ones should be easier
//	7/16/2013	made asterik show up by closing select for tm and pat subtypes
//			made the font orangered so it is easier to see when you get kicked back to update it.
//	7/17/2013	added $user_level to header call
//	7/18/2013	replaced all authentication to $user_level while leaving in $sysadmin because I'm pretty sure that's just the root admin.
//	8/14/2013	changed error protocol to exit/display error
//	8/26/2013	it was throwing an error because the date logic was wrong. fixed.
//			cleaned up the code
// autoactions_edit.php	-- User Access Level: Admin
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);?>
<?
// For new records, incoming link is set to $ID="0" and I="0"
// Otherwise, $ID is set to the existing record number
if ($I=="1"){
$sql="SELECT * FROM autoactions WHERE ID='$ID'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
  $ID=$row["ID"];
  $ip_type = $row["ip_type"];
  $subtype = $row["subtype"];
  $country = $row["country"];
  $action_type = $row["action_type"];
  $original_only = $row["original_only"];
  $recurring = $row["recurring"];
  $due_month = $row["due_month"];
  $due_day = $row["due_day"];
  $due_year = $row["due_year"];
  $due_time = $row["due_time"];
  $reference_date = $row["reference_date"];
  $on_off = $row["on_off"];
  $pre_post = $row["pre_post"];
  $description = $row["description"];
  $creator = $row["creator"];
  $create_date = $row["create_date"];
  $editor = $row["editor"];
  $edit_date = $row["edit_date"];			  
}?>
<!-- ADD OR EDIT AUTO ACTIONS -->
<? if ($sysadmin=="Y" or $user_level == "Admin"){?>
<? if ($ID!="0"){?>
<table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">
    <a href="delete_confirm.php?TABLE=autoactions&ID=<?=$ID;?>&NAME=Auto Action for <?=$action_type;?>">Delete</a>
  </td></tr>
</table>
<?}?><br><br>
<? if ($ID=="0") echo("<center>ADD AUTO ACTION</center>");
   else echo("<center>EDIT AUTO ACTION</center>");
   if ($ip_type!="PATENT" and $ip_type!="TRADEMARK") {$subtype="N/A";}	//puts a default value in subtype if it isn't a patent so it gets past the next check
   else { if($subtype=="N/A") {$subtype = "";}}	//if it is and has an invalid value (default)
if ($submitok=="" or $action_type=="" or $ip_type=="" or $country=="" or $subtype==""){
  if ($submitok!=""){?>
    <center><br><font color="red">Please update fields marked with a red asterisk.</font><br></center><?}?>
<form method=post action="<?=$PHP_SELF;?>">
  <input type="hidden" name="ID" value="<?=$ID;?>">
  <input type="hidden" name="I" value="0">
<table align="center" border="0" cellpadding="0" cellspacing="10">
    <? if ($ID!="0"){?>
	<tr>
        <td align=right width="150">
            Duplicate
        </td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="400">
            <input type="checkbox" name="duplicate" value="Y">&nbsp;&nbsp;Saves Data as a New Record
        </td>
    </tr>
	<?}?>
  <tr>
      <td align=right width="150">
          Type IP
      </td>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      <td width="400">
          <select name=ip_type type=text>
          <option><?=$ip_type;?></option>
          <option>PATENT</option>
	      <option>TRADEMARK</option>
	      <option>DISCLOSURE</option>
	      <option>NDA</option>
	      <option>LICENSE</option>
	      <option>GENERAL IPM</option>
	      </select>
          <font color="orangered" size="+1"><TT><B>*</B></TT></font>
      </td>
  </tr>
  <? if($ip_type == "PATENT")
  {?>
    <tr>
      <td align=right width="150">
          <font color="orangered">Patent Subtype</font>
      </td>
      <td></td>
      <td width="400">
	  <select name=subtype size="1" bgcolor="orangered">
			<option><?=$subtype;?></option>
		    <? $sql="SELECT * FROM menus WHERE menu_type='PATENT_TYPE' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>
			<?}?></select>
          <font color="orangered" size="+1"><TT><B>*</B></TT></font>
      </td>
  </tr>
  <?}?>
  <? if($ip_type == "TRADEMARK")
  {?>
    <tr>
      <td align=right width="150">
          <font color="orangered">Trademark Subtype</font>
      </td><td></td>
      <td width="400">
	  <select name=subtype size="1">
			<option><?=$subtype;?></option>
		    <? $sql="SELECT * FROM menus WHERE menu_type='TRADEMARK_TYPE' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>
			<?}?></select>
          <font color="orangered" size="+1"><TT><B>*</B></TT></font>
      </td>
  </tr>
  <?}?>
  <tr>
      <td align=right width="150">
          Country
      </td><td></td>
      <td width="400">
            <select name=country size="1">
            <option><?=$country;?></option>
			<? country_list();?>
            </select>
            <font color=orangered size=+1><TT><B>*</B></TT></font>
			&nbsp;&nbsp;<a target="_blank" href="help.php#country_codes">Country Codes</a>
      </td>
  </tr>
  <tr>
      <td align=right width="150">
          Action Type
      </td><td></td>
      <td width="400">
	  <select name=action_type size="1">
			<option><?=$action_type;?></option>
		    <? $sql="SELECT * FROM menus WHERE menu_type='AUTO_ACTION' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
          <font color="orangered"><TT><B>*</B></TT></font>
      </td>
  </tr>
  <tr>
      <td align=right width="150">
          Original Filing Only
      </td><td></td>
      <td width="400">
          <select name=original_only type=text>
          <option><?=$original_only;?></option>
          <option>N</option>
	      <option>Y</option>
	      </select>
          <font color="orangered"><TT><B>*</B></TT></font>
		  &nbsp;&nbsp;Applies Only To An Original Filing
      </td>
  </tr>
  <tr>
      <td align=right width="150">
          Recurring
      </td><td></td>
      <td width="400">
          <select name=recurring type=text>
          <option><?=$recurring;?></option>
          <option>N</option>
	      <option>Y</option>
	      </select>
          <font color="orangered"><TT><B>*</B></TT></font>
		  &nbsp;&nbsp;Set After Previous Action Is Completed
      </td>
  </tr>
  <tr>
    <td align="right" width="150">
      Date Due
    </td><td></td>
    <td width="400">
      <input type="text" name="due_year" value="<?=$due_year;?>" maxlength="4" size="5">&nbsp;Years&nbsp;
      <input type="text" name="due_month" value="<?=$due_month;?>" maxlength="2" size="3">&nbsp;Months&nbsp;
      <input type="text" name="due_day" value="<?=$due_day;?>" maxlength="2" size="3">&nbsp;Days
      <font color="orangered"><TT><B>*</B></TT></font>
    </td>
  </tr>
  <tr>
    <td align=right width="150">
      Pre/Post
    </td><td></td>
    <td width="400">
      <select name=pre_post type=text>
	<option><?=$pre_post;?></option>
	<option>pre</option>
	<option>post</option>
      </select>
    </td>
  </tr>
  <tr>
      <td align=right width="150">
          Reference Date
      </td><td></td>
      <td width="400">
          <select name=reference_date type=text>
          <option><?=$reference_date;?></option>
          <option>Priority Date</option>
	      <option>Filing Date</option>
	      <option>Issue Date</option>
	      <option>Other</option>
	      </select>
          <font color="orangered"><TT><B>*</B></TT></font>
      </td>
  </tr>
  <tr>
      <td align=right width="150">
          On or Off
      </td><td></td>
      <td width="400">
          <select name=on_off type=text>
          <option><?=$on_off;?></option>
          <option>ON</option>
	      <option>OFF</option>
	      </select>
          <font color="orangered"><TT><B>*</B></TT></font>
      </td>
  </tr>
  <tr>
    <td align="right" width="150">
      Action Description
    </td><td></td>
    <td width="400">
      <textarea wrap name=description rows=2 cols=35><?=$description;?></textarea>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="3">
      <hr noshade size="1" width="100%">
      <input type="submit" name="submitok" value="  OK  ">
    </td>
  </tr>
</table>
<table width="100%" border="3" cellpadding="0" cellspacing="0" bordercolor="#336666" bgcolor="#336666"><tr>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Creator: <?=$creator;?> on <?=$create_date;?></small></td>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Editor: <?=$editor;?> on <?=$edit_date;?></small></td></tr>
</table>
<?}
else {

// Make time for due date -- not exact, but close enough for ordering
$due_time = ($due_day * 86400) + ($due_month * 2592000) + ($due_year * 31104000);

if ($ID=="0" or $duplicate=="Y") { // ADD NEW RECORD
  //$create_date = date("F j, Y");   // e.g. March 10, 2001
  $sql="INSERT INTO autoactions SET
    customer_ID='$customer_ID',
    ip_type = '$ip_type',
    subtype = '$subtype',
    country = '$country',
    action_type = '$action_type',
	original_only = '$original_only',
    recurring = '$recurring',
    due_month = '$due_month',
    due_day = '$due_day',
    due_year = '$due_year',
    due_time = '$due_time',
    reference_date = '$reference_date',
    on_off = '$on_off',
    pre_post = '$pre_post',
    description = '$description',
    creator = '$fullname',
    create_date = '$today'";
}
	
else { // UPDATE EXISTING RECORD
  //$edit_date = date("F j, Y");   // e.g. March 10, 2001
  $sql="UPDATE autoactions SET
    customer_ID='$customer_ID',
    ip_type = '$ip_type',
    subtype = '$subtype',
    country = '$country',
    action_type = '$action_type',
	original_only = '$original_only',
    recurring = '$recurring',
    due_month = '$due_month',
    due_day = '$due_day',
    due_year = '$due_year',
    due_time = '$due_time',
    reference_date = '$reference_date',
    on_off = '$on_off',
    pre_post = '$pre_post',
    description = '$description',
    editor = '$fullname',
    edit_date = '$today'
	WHERE ID='$ID'";
}
	
// RUN THE QUERY	
if (!mysql_query($sql)){
             echo mysql_error();
	     exit;}
?>
<!-- DONE -->
<table align="center" width="500"><tr><td><br>
<p><strong>Your record has been successfully updated.</strong></p>
<p> To return to the login page, click <a href="index.php">here</a></p>
<p> To view auto actions, click <a href="autoactions_list.php">here</a></p>
<p> To add another auto action, click <a href="autoactions_edit.php?ID=0&I=0">here</a></p>
</td></tr></table>
<?}}
else echo("ACCESS DENIED<br>");
html_footer(); ?>
