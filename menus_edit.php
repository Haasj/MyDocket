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
//Maitnence Log Jonathan Haas
//	6/11/2013	fixed a broken link on line 326
//	6/18/2013	added disclosure menu items. additionally added documents submenus to the types.
//			switched the top two and changed the names to look cleaner
//	6/24/2013	added document sections to everyting
//	6/25/2013	added general ipm sections for everything
//			added contract actions
//	6/26/2013	changed docs so there are nda and license
//			changed actions so there are nda and license
//	6/27/2013	added auto actions
//	7/1/2013	error: AUTO_ACTION isn't what it's called in the db. going to change it all to AUTO_ACTION for clarity
//	7/17/2013	added $user_level to header call
//	7/18/2013	replaced all authentication to $user_level while leaving in $sysadmin because I'm pretty sure that's just the root admin.
//	8/9/2013	since I'm not checking the drop down boxes for apostrophes in all of the edits, I don't allow them here and I made a note of it next to the field. also updated error msg accordingly
//			added some spacing to make it look a little cleaner-it still doesn't look the best, but it's better
//	8/29/2013	cleaned up the code a little
// menus_edit.php -- 	User Access Level: Admin
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
// For new records, incoming link is set to $ID="0" and $I="0"
// Otherwise, $ID is set to the existing record number
// SQL Query for an existing NDA record and retrieving data
if ($I=="1"){
$sql="SELECT * FROM menus WHERE ID='$ID'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
    $org = $row["org"];
    $menu_type = $row["menu_type"];
    $menu_name = $row["menu_name"];		
}

if ($sysadmin=="Y" or $user_level == "Admin"){
  if ($ID!="0"){?>
<table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">
    <a href="delete_confirm.php?module=<?=$module;?>&TABLE=menus&ID=<?=$ID;?>&NAME=<?=$menu_name;?>">Delete</a>
  </td></tr>
</table>
<?}?><br><br>
<? if ($ID=="0") echo("<center>ADD MENU RECORD</center><br>");
   else echo("<center>EDIT MENU RECORD</center><br>");
if ($submitok=="" or $menu_type=="" or menu_name==""){
  if ($submitok!=""){?>
    <center><br><font color="red">Please update fields marked with a red asterisk.</font><br></center><?}?>
<form method=post action="<?=$PHP_SELF;?>">
  <input type="hidden" name="module" value="<?=$module;?>">
  <input type="hidden" name="ID" value="<?=$ID;?>">
  <input type="hidden" name="I" value="0">
<table align="center" border="0" cellpadding="0" cellspacing="10">
    <tr>
        <td align=right width="150">
            List
        </td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="400">
            <select name=menu_type size="1">
			<option><?=$menu_type;?></option>
			<option>AUTO_ACTION</option>
			<option>CLIENT</option>
			<option>FIRM</option>
			<option>PATENT_TYPE</option>
			<option>PATENT_STATUS</option>
			<option>PATENT_ACTION</option>
			<option>PATENT_DOCUMENT</option>
			<option>DISCLOSURE_ACTION</option>
			<option>DISCLOSURE_STATUS</option>
			<option>DISCLOSURE_TYPE</option>
			<option>DISCLOSURE_DOCUMENT</option>
			<option>TRADEMARK_TYPE</option>
			<option>TRADEMARK_STATUS</option>
			<option>TRADEMARK_ACTION</option>
			<option>TRADEMARK_DOCUMENT</option>
			<option>COPYRIGHT_TYPE</option>
			<option>COPYRIGHT_STATUS</option>
			<option>COPYRIGHT_DOCUMENT</option>
			<option>LICENSE_TYPE</option>
			<option>LICENSE_STATUS</option>
			<option>LICENSE_DOCUMENT</option>
			<option>LICENSE_ACTION</option>
			<option>NDA_STATUS</option>
			<option>NDA_DOCUMENT</option>
			<option>NDA_ACTION</option>
			<option>GENERAL_IPM_STATUS</option>
			<option>GENERAL_IPM_ACTION</option>
			<option>GENERAL_IPM_DOCUMENT</option>
            </select>
            <font color=orangered size=+1><TT><B>*</B></TT></font>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            Entry
        </td><td></td>
        <td width="400">
            <input name="menu_name" type="text" maxlength="35" size="35" value="<?=$menu_name;?>">
            <font color=orangered size=+1><TT><B>*</B></TT></font>
			(no " ' " characters)
        </td>
    </tr>
	<tr>
	  <td colspan="3">
        <br><hr noshade size="1" width="400">
      </td>
    </tr>
	<tr>
	  <td colspan="3">
        <center>EXISTING MENUS</center><br>
      </td>
    </tr>
	<tr>
        <td align=right width="150">
            <p>Auto-Actions</p>
        </td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="400">
            <select name=x size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='AUTO_ACTION' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Firms</p>
        </td>		<td></td>
        <td width="400">
            <select name=x size="1">
		    <? $sql="SELECT * FROM menus WHERE customer_ID = '$customer_ID' and menu_type='FIRM' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Clients</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE customer_ID = '$customer_ID' and menu_type='CLIENT' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Patent Types</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='PATENT_TYPE' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>	
    <tr>
        <td align=right width="150">
            <p>Patent Status</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='PATENT_STATUS' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>	
    <tr>
        <td align=right width="150">
            <p>Patent Actions</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='PATENT_ACTION' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Patent Documents</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='PATENT_DOCUMENT' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Disclosure Types</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='DISCLOSURE_TYPE' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>	
    <tr>
        <td align=right width="150">
            <p>Disclosure Status</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='DISCLOSURE_STATUS' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>	
    <tr>
        <td align=right width="150">
            <p>Disclosure Actions</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='DISCLOSURE_ACTION' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Disclosure Documents</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='DISCLOSURE_DOCUMENT' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Trademark Types</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='TRADEMARK_TYPE' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>	
    <tr>
        <td align=right width="150">
            <p>Trademark Status</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='TRADEMARK_STATUS' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>	
    <tr>
        <td align=right width="150">
            <p>Trademark Actions</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='TRADEMARK_ACTION' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Trademark Documents</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='TRADEMARK_DOCUMENT' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Copyright Types</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='COPYRIGHT_TYPE' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>	
    <tr>
        <td align=right width="150">
            <p>Copyright Status</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='COPYRIGHT_STATUS' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Copyright Documents</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='COPYRIGHT_DOCUMENT' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>License Types</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='LICENSE_TYPE' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>	
    <tr>
        <td align=right width="150">
            <p>License Status</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='LICENSE_STATUS' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
        <tr>
        <td align=right width="150">
            <p>License Documents</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='LICENSE_DOCUMENT' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
	    <tr>
        <td align=right width="150">
            <p>License Actions</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='LICENSE_ACTION' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>NDA Status</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='NDA_STATUS' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>NDA Documents</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='NDA_DOCUMENT' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>NDA Actions</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='NDA_ACTION' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>General IPM Status</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='GENERAL_IPM_STATUS' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>General IPM Action</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='GENERAL_IPM_ACTION' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>General IPM Document</p>
        </td>		<td></td>
        <td width="400">
            <select name=y size="1">
		    <? $sql="SELECT * FROM menus WHERE menu_type='GENERAL_IPM_DOCUMENT' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align="center" colspan="4" width="100%">
            <hr noshade size="1" width="600">
            <input type=submit name="submitok" value="  OK  ">
        </td>
    </tr>
</table>
<?
}
else {

// The script performs the database update

if ($ID=="0") // ADD NEW RECORD
   $sql = "INSERT INTO menus SET
       customer_ID = '$customer_ID',
       org = '$userorg',
       menu_type = '$menu_type',
       menu_name = '$menu_name'";

else // UPDATE EXISTING RECORD
    $sql = "UPDATE menus SET
       customer_ID = '$customer_ID',
       org = '$userorg',
       menu_type = '$menu_type',
       menu_name = '$menu_name'
       WHERE ID='$ID'";
			  
// RUN THE QUERY
     if (!mysql_query($sql)){
             error("Please make sure you do not have any of the following characters in your submission: ".
		   "\x00, \n, \r, \, ', or \x1a.\\nIf this error persists, please ".
              "contact your HelpDesk.");
             }
?>
<table align="center" width="500"><tr><td>
<p><strong>Your record has been successfully updated.</strong></p>
<p> To return to the login page, click <a href="index.php">here</a></p>
<p> To view menu records, click <a href="menus_list.php?module=<?=$module;?>">here</a></p>
<p> To add another menu, click <a href="menus_edit.php?module=<?=$module;?>&ID=0&I=0">here</a></p>
</td></tr></table><br>
<?}}?>
<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#336666" bgcolor="#336666"><tr>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Creator: <?=$creator;?> on <?=$create_date;?></small></td>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Editor: <?=$editor;?> on <?=$edit_date;?></small></td></tr>
</table>
<? html_footer(); ?>
