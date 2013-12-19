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
//	6/11/2013	looking in to see why it is not updating to the db
//			fixed. Same as patents_edit, edit and I were being changed back after they were checked. See line 234
//			changed to view confirmation page rather than updated record page. See line 558, 785
//			changed incorrect link pointer
//	6/19/2013	changed formatting to reflect new docket_master protocol
//			changed everything to reflect new docket_master protocol. now everything works with the new system.
//	6/21/2013	added delete action functionality from the edit page for actions
//			changed it so it says ECS Docket now, also changed country to defualt to US
//	6/24/2013	made file upload work
//	6/26/2013	defaulted ATTACHEDFILES to All
//			updated to dynamically display document names instead of manually. also changed it so it doesn't go to edit mode when you select different lists.
//			BUG: it shifts everything right on the view. I have no idea why. I'll look at this after I update the other edit phps
//			I have no idea why it does this. I made it it's own tr to get around it....
//	6/27/2013	changed it so it uses meta refresh to view the updated record.
//	7/1/2013	added cost links
//	7/3/2013	changed so it only runs autoactions if it's new.
//	7/8/2013	deleted the descriptions for the actions--makes it look cleaner
//			added a bunch of spaces to make it look cleaner
//	7/16/2013	made it so everyone can view cost
//			changed it so it displays done actions with completed date
//	7/17/2013	added $user_level to header call
//	7/18/2013	replaced all authentication to $user_level while leaving in $sysadmin because I'm pretty sure that's just the root admin.
//	7/23/2013	added link to add transaction on the edit screen.
//	7/24/2013	^^also on view screen^^
//	7/30/2013	replaced paths with session variables
//			modified user access so viewers can't see edit family/files from view screen
//	8/9/2013	ordered pat_documents by doc_date
//			allowed apostrophes in data fields. did this using mysql_real_escape_string. Note: this is deprecated, and will be removed in 5.5, but I couldn't get the mysqli one to work, and I figured
//			that so much other stuff will break if we ever upgrade that it won't be too big of a hassle to do one more function. the function manually inserts a backslash before each speacial
//			character, which escapes it for the query to run properly. also adjusted so every data field can have special characters to increase durability and avoid a possible breakpoint
//	8/13/2013	changed error protocol to exit/display error
//	8/15/2013	deleted assignment of blank docket_alt, added date validation (default)
//	8/19/2013	fixed date logic; incorrectly was using checkdate. this won't work, because of formatting. now, it will work, but throw the mysql_error if the date is invalid. works with ""
//	8/29/2013	replaced server_docs with abs_docs
//	9/3/2013	cleaned up the code a little
// trademarks_edit.php -- User Access Level: User/Viewer
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
// Set $var string
$var="DOCKET=$DOCKET_ALT&TITLE=$TITLE";
// Set Defaults
if ($NEXT=="1") $START=$START+50;
  else $START="0";
  
if ($ATTACHEDFILES==""){$ATTACHEDFILES="All";}
//<!-- SELECT RELATED TRADEMARK -->
if ($TMFAM=="1") {
if ($submit_1=="" or $TM_ID==""){?>
<table align="right" border="3" cellpadding="0" cellspacing="0" bordercolor="#336666" bgcolor="#336666"><tr>
  <td width="100" align="center" bgcolor="#EEEEEE"><a href="<?=$PHP_SELF;?>?module=<?=$module;?>?TMFAM=1&TMEDIT=0&NEXT=1&START=<?=$START;?>&<?=$var;?>"><small>Next 50</small></a></td></tr>
</table><br><br> 
<center>ADD TRADEMARK RECORD -- SELECT RELATED TRADEMARK</center><br>
<form method=get action="<?=$PHP_SELF;?>">
  <input type="hidden" name="module" value="<?=$module;?>">
  <input type="hidden" name="TMFAM" value="1">
  <input type="hidden" name="TMEDIT" value="0">
  <input type="hidden" name="I" value="0">
  <input type="hidden" name="EDIT" value="Y">
<table align="center" width="100%" border="0" cellpadding="5" cellspacing="2">
  <tr>
    <td align="right" colspan="3">
	  <small>DOCKET&nbsp;<input type="text" name="DOCKET_ALT" maxlength="10" size="10" value="<?=$DOCKET_ALT;?>"></small>&nbsp;&nbsp;
      <small>TITLE&nbsp;<input type="text" name="TITLE" maxlength="30" size="10" value="<?=$TITLE;?>"></small>&nbsp;&nbsp;
      <input type="submit" name="submit_search" value=" SEARCH ">&nbsp;&nbsp;
      <input type="submit" name="submit_50" value=" NEXT 50 ">&nbsp;&nbsp;
      <small><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=0&I=2&EDIT=Y&NEW=Y&country=US">ADD NEW TRADEMARK</a></small></td>
  </tr>
  <tr bgcolor=EEEEEE>
    <td width="20"><small>SELECT</small></td>
    <td width="80"><small>ECS DOCKET</small></td>
    <td width="480"><small>TITLE</small></td>
  </tr>
  <?
    $sql="SELECT tm_ID, docket_alt, title FROM tm_filings WHERE
      customer_ID='$customer_ID' and
	  docket_alt LIKE '%$DOCKET_ALT%' and
      title LIKE '%$TITLE%' and
	  original='Y'	
	  ORDER BY docket_alt LIMIT $START, 50";
  $result=mysql_query($sql);
  while($row=mysql_fetch_array($result)){
    $TM_ID=$row["tm_ID"];
    $docket_alt=$row["docket_alt"];
    $title=$row["title"];
  ?>
  </td></tr>
  <tr bgcolor=EEEEEE>
    <td width="20"><input type="radio" name="TM_ID" value="<?=$TM_ID;?>"></td>
    <td width="100"><small><?=$docket_alt;?></small></td>
    <td width="430"><small><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&TMEDIT=1&TM_ID=<?=$TM_ID;?>&I=1&EDIT=N"><?=$title;?></small></td>
  </tr>
  <?}?>
  <tr>
    <td align="center" colspan="3">
      <hr noshade size="1" width="100%">
      <input type="submit" name="submit_1" value="  OK  ">
    </td>
  </tr>
</table>
<?}
else {

// The script copies the information from the original case to a new record
$sql="SELECT * FROM tm_filings WHERE tm_ID='$TM_ID'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
  $TMFAM_ID=$row["tmfam_ID"];
  $docket=$row["docket"];
  $title=$row["title"];
  $firm=$row["firm"];
  $firm_contact=$row["firm_contact"];
  $client=$row["client"];
  $client_contact=$row["client_contact"];
  $filing_type=$row["filing_type"];
  $intl_class=$row["intl_class"];
  $description=$row["description"];
  $products=$row["products"];
  
// Create the record that will be edited
$sql="INSERT INTO tm_filings SET
	customer_ID='$customer_ID',
    org='$userorg',
    tmfam_ID='$TMFAM_ID',
    original='N',
    docket='$docket',
    title='$title',
	firm='$firm',
	firm_contact='$firm_contact',
    client='$client',
    client_contact='$client_contact',
	filing_type='$filing_type',
	intl_class='$intl_class',
    description='$description',
    products='$products',
	creator='$fullname',
	create_date='$today',
	editor='$fullname',
	edit_date='$today'";	
	
if (!mysql_query($sql)){
  echo mysql_error();
   exit;}

  
// Get the ID of the new tm_filings record
$TM_ID_NEW=mysql_insert_id();

$sql_3="INSERT INTO docket_master SET
	  type='trademark',
	  ID='$TM_ID_NEW'";
  if (!mysql_query($sql_3))
  error("db error into master");
  $docket_alt_new=mysql_insert_id();
  
  $sql_4="UPDATE tm_filings SET
	  docket_alt='$docket_alt_new'
	  WHERE tm_ID='$TM_ID_NEW'";
    if (!mysql_query($sql_4)){
  echo mysql_error();
   exit;}

// Move on to adding the trademark filing record
$TMEDIT="1";
$I="1";
$TM_ID=$TM_ID_NEW;
$NEW="Y";
$docket_alt=$docket_alt_new;
}}

//<!-- ADD OR EDIT TRADEMARK RECORD -->
if ($TMEDIT=="1") {
// If it's a brand new case ($I=="2"), we need to set up a trademark family number, 
// trademark number and identify the new trademark as an original
if ($I=="2") {
  $sql = "INSERT INTO tm_families SET
    customer_ID = '$customer_ID',
    org = '$userorg'";  
	if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
  $TMFAM_ID=mysql_insert_id();	//had docket_alt = '$docket_alt', in there, and it wasn't causing an error like it was in patents and disclosures.... took it out anyway
  $sql = "INSERT INTO tm_filings SET
    customer_ID = '$customer_ID',
	tmfam_ID = '$TMFAM_ID',
    original='Y',
    org = '$userorg',
	creator='$fullname',
	create_date='$today',
	editor='$fullname',
	edit_date='$today'";  
	if (!mysql_query($sql)){
  echo mysql_error();
   exit;}

  $TM_ID=mysql_insert_id();
  $original="Y";
  
  $sql = "INSERT INTO docket_master SET
	  type='trademark',
	  ID='$TM_ID'";
	  if (!mysql_query($sql)){
  echo mysql_error();
   exit;}

  $docket_alt=mysql_insert_id();
  }

// To retrieve data, $TM_ID is set to the existing record number
// SQL Query for an existing NDA record and retrieving data
if ($I=="1"){
$sql="SELECT * FROM tm_filings WHERE tm_ID='$TM_ID'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
    $TMFAM_ID = $row["tmfam_ID"];
    $original = $row["original"];
    $docket = $row["docket"];
    $docket_alt = $row["docket_alt"];
    $org = $row["org"];
    $creator = $row["creator"];
    $create_date = $row["create_date"];
    $editor = $row["editor"];
    $edit_date = $row["edit_date"];
    $firm = $row["firm"];
    $firm_contact = $row["firm_contact"];
    $client = $row["client"];
    $client_contact = $row["client_contact"];
    $title = $row["title"];
	$filing_type = $row["filing_type"];
	$country = $row["country"];
	$status = $row["status"];
    $priority_date = $row["priority_date"];
    $priority_date_1 = $row["priority_date"];
    $filing_date = $row["filing_date"];
    $filing_date_1 = $row["filing_date"];
    $filing_receipt = $row["filing_receipt"];
    $assignment = $row["assignment"];
    $assignment_recorded = $row["assignment_recorded"];
    $pub_date = $row["pub_date"];
	$regi_date = $row["regi_date"];
	$regi_date_1 = $row["regi_date"];
    $ser_no = $row["ser_no"];
	$tm_no = $row["tm_no"];
	$intl_class = $row["intl_class"];
	$description = $row["description"];
	$products = $row["products"];
    $notes=$row["notes"];			  
}
if (($sysadmin=="Y" or $user_level != "Viewer") and $read_only=="N" and $EDIT=="Y"){?>
<table align="right" border="0" cellpadding="0" cellspacing="0">
  <tr><td width="100%" align="center">
    <a href="trademarks_list.php?module=<?=$module;?>&SORT=TMFAM&TMFAM_ID=<?=$TMFAM_ID;?>">View Family</a>
    <? // The ability to delete a record only if there is an existing record ($TM_ID!="0") --or cost
    if ($TM_ID!="0"){?>&nbsp;|&nbsp;
    <a href="accounts_edit.php?module=<?=$module;?>&ecs_docket=<?=$docket_alt;?>&firm_docket=<?=$docket;?>&I=0">Add Transaction</a>&nbsp;|&nbsp;
    <a href="total_cost.php?module=<?=$module;?>&ID=<?=$docket_alt;?>">Cost</a>&nbsp;|&nbsp;
    <a href="delete_confirm.php?module=<?=$module;?>&TABLE=tm_filings&ID=<?=$TM_ID;?>&NAME=<?=$docket_alt;?>&FAM_ID=<?=$TMFAM_ID;?>">Delete</a>
    <?}?></td></tr>
</table><br><br>
<? if ($NEW=="Y") echo("<center>ADD TRADEMARK RECORD</center>");
  else echo("<center>EDIT TRADEMARK RECORD</center>");
if ($submit_2=="" or $docket=="" or $firm=="" or $title=="" or $filing_type=="" or $status=="" or $country==""){
  if ($submit_2!=""){?>
    <center><br><font color="red">Please update fields marked with a red asterisk.</font><br></center><?}?>
<form method=get action="<?=$PHP_SELF;?>">
  <input type="hidden" name="module" value="<?=$module;?>">
  <input type="hidden" name="TMFAM" value="0">
  <input type="hidden" name="TMEDIT" value="1">
  <input type="hidden" name="TMFAM_ID" value="<?=$TMFAM_ID;?>">
  <input type="hidden" name="TM_ID" value="<?=$TM_ID;?>">
  <input type="hidden" name="docket_alt" value="<?=$docket_alt;?>">
  <input type="hidden" name="ACTIONS" value="<?=$ACTIONS;?>">
  <input type="hidden" name="ATTACHEDFILES" value="<?=$ATTACHEDFILES;?>">
  <input type="hidden" name="I" value="0">
  <input type="hidden" name="EDIT" value="Y">
  <input type="hidden" name="NEW" value="<?=$NEW;?>">
  <input type="hidden" name="original" value="<?=$original;?>">
  <input type="hidden" name="creator" value="<?=$creator;?>">
  <input type="hidden" name="create_date" value="<?=$create_date;?>">
  <input type="hidden" name="editor" value="<?=$editor;?>">
  <input type="hidden" name="edit_date" value="<?=$edit_date;?>">
<table align="center" border="0" cellpadding="0" cellspacing="5">
    <tr>
        <td align="right" width="115">
            Title
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td colspan="1" align="left" width="450">
            <input name="title" type="text" maxlength="200" size="60" value="<?=$title;?>">
            <font color="orangered" size="+1"><TT><B>*</B></TT></font>
        </td>
    </tr>
    <tr>
        <td align="right" width="115">
            Firm Docket No.
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td colspan="3" align="left" width="450">
            <input name="docket" type="text" maxlength="200" size="60" value="<?=$docket;?>">
            <font color="orangered" size="+1"><TT><B>*</B></TT></font>
        </td>
    </tr>
    <tr>
      <td align="right" width="115">
	ECS Docket No.
      </td>
      <td>&nbsp;&nbsp;&nbsp;</td>
      <td width="205">
	<?=$docket_alt;?>
      </td>
        <td align="right" width="115">
            Status
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205">						
			<select name="status" size="1">
			<option><?=$status;?></option>
		    <? $sql="SELECT * FROM menus WHERE menu_type='TRADEMARK_STATUS' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
            <font color="orangered" size="+1"><TT><B>*</B></TT></font>
        </td>
    </tr>
    <tr>
        <td align="right" width="115">
            Service Firm
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205">
            <select name="firm" size="1">
			<option><?=$firm;?></option>
		    <? $sql="SELECT * FROM menus WHERE customer_ID='$customer_ID' and menu_type='FIRM' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
            <font color="orangered" size="+1"><TT><B>*</B></TT></font>
        </td>
        <td align="right" width="115">
            Firm Contact
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205">
            <input name="firm_contact" type="text" maxlength="100" size="25" value="<?=$firm_contact;?>">
        </td>
    </tr>
    <tr>
        <td align="right" width="115">
            Client
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205">
            <select name="client" size="1">
			<option><?=$client;?></option>
		    <? $sql="SELECT * FROM menus WHERE customer_ID='$customer_ID' and menu_type='CLIENT' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
            <font color="orangered" size="+1"><TT><B>*</B></TT></font>
        </td>
        <td align="right" width="115">
            Client Contact
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205">
            <input name="client_contact" type="text" maxlength="100" size="25" value="<?=$client_contact;?>">
        </td>
    </tr>
    <tr>
        <td align="right" width="115">
            Type
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205">			
			<select name="filing_type" size="1">
			<option><?=$filing_type;?></option>
		    <? $sql="SELECT * FROM menus WHERE menu_type='TRADEMARK_TYPE' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
            <font color="orangered" size="+1"><TT><B>*</B></TT></font>
			&nbsp;&nbsp;<a target="_blank" href="help.php#glossary">Glossary</a>
        </td>
        <td align="right" width="115">
            Country
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205">
            <select name=country size="1">
            <option><?=$country;?></option>
			<? country_list();?>
            </select>
            <font color=orangered size=+1><TT><B>*</B></TT></font>
			&nbsp;&nbsp;<a target="_blank" href="help.php#country_codes">Country Codes</a>
        </td>
	</tr>
	<tr>
        <td align="right" width="115">
            Filing Date
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205">
            <input type="text" name="filing_date" size="11" maxlength="10" value="<?=$filing_date;?>">
			<small>&nbsp;(YYYY-MM-DD)</small>
        </td>
        <td align="right" width="115">
            Serial No.
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205">
            <input name="ser_no" type="text" maxlength="25" size="25" value="<?=$ser_no;?>">
        </td>	
	</tr>
	<tr>
        <td align="right" width="115">
            Publ. for Opposition
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205">
            <input type="text" name="pub_date" size="11" maxlength="10" value="<?=$pub_date;?>">
			<small>&nbsp;(YYYY-MM-DD)</small>
        </td>
        <td align="right" width="115">
            Publ. No.
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205">
            <input name="pub_no" type="text" maxlength="25" size="25" value="<?=$pub_no;?>">
        </td>
	</tr>
	<tr>
        <td align="right" width="115">
            Registration Date
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205">
            <input type="text" name="regi_date" size="11" maxlength="10" value="<?=$regi_date;?>">
			<small>&nbsp;(YYYY-MM-DD)</small>
        </td>
        <td align="right" width="115">
            Reg. No.
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205">
            <input name="tm_no" type="text" maxlength="25" size="25" value="<?=$tm_no;?>">
        </td>
	</tr>
    <tr>
        <td align="right" valign="top" width="115">
	      Related<br>Documents
	    </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205">
          <input type="checkbox" name="filing_receipt" value="Y" <? if ($filing_receipt=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Filing Receipt<br>
          <input type="checkbox" name="assignment" value="Y" <? if ($assignment=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Assignment<br>
          <input type="checkbox" name="assignment_recorded" value="Y" <? if ($assignment_recorded=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Recorded Assignment
	    </td>
	          <td align="right" width="115">
            Original
        </td>
		  <td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205">
            <select name=original size="1">
            <option><?=$original;?></option>
			<option>Y</option><option>N</option>
            </select>
            <font color=orangered size=+1><TT><B>*</B></TT></font>
			&nbsp;&nbsp;<a target="_blank" href="help.php#glossary">Glossary</a>
		</td>
	</tr>
	<tr>
        <td align=right width="110">
            Intl. Classes
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205">
            <input name=intl_class type=text maxlength=25 size=25 value="<?=$intl_class;?>">
        </td>
	</tr>
	<tr>
        <td align="right" valign="top" width="115">
            Description
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td colspan="3">
            <textarea wrap name="description" rows="3" cols="50"><?=$description;?></textarea>
        </td>
	</tr>
	<tr>
        <td align="right" valign="top" width="115">
            Products
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td colspan="3">
            <textarea wrap name="products" rows="2" cols="50"><?=$products;?></textarea>
        </td>
	</tr>	
	<tr>
        <td align="right" valign="top" width="115">
            Notes
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td colspan="3">
            <textarea wrap name="notes" rows="2" cols="50"><?=$notes;?></textarea>
        </td>
	</tr>
    <tr>
        <td align="right" valign="top" width="115">
            Actions<br>
			<a target=_blank href="tmaction_edit.php?module=<?=$module;?>&TM_ID=<?=$TM_ID;?>&ACTION_ID=0&I=0&EDIT=Y">Add</a>&nbsp;&nbsp;
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td colspan="1"><!-- EXISTING TM ACTIONS -->
		  <? // SQL Query for Selecting Actions Type
		  echo($ACTIONS." Actions");?>
		  	<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=<?=$TM_ID;?>&ACTIONS=All&I=1&EDIT=Y">All</a> -
			<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=<?=$TM_ID;?>&ACTIONS=Open&I=1&EDIT=Y">Open</a> -
			<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=<?=$TM_ID;?>&ACTIONS=Closed&I=1&EDIT=Y">Closed</a><br>
		  <? if ($ACTIONS=="Open") 
		    $sql="SELECT * FROM tm_actions WHERE
		      tm_ID='$TM_ID' and
			  done='N'";
		  elseif ($ACTIONS=="Closed")
		    $sql="SELECT * FROM tm_actions WHERE
		      tm_ID='$TM_ID' and
			  done='Y'";
		  else
            $sql="SELECT * FROM tm_actions WHERE
		      tm_ID='$TM_ID'
			  ORDER BY respdue_date";
		  $result=mysql_query($sql);
		  while($row=mysql_fetch_array($result)){
		    $ACTION_ID=$row["action_ID"];
		    $action_type=$row["action_type"];
		    $respdue_date=$row["respdue_date"];
		    $description=$row["description"];
		    $respdone_date=$row["respdone_date"];
		    $done=$row["done"];
		    if ($done == "N") {?>
		    <a href="delete_confirm.php?module=<?=$module;?>&TABLE=tm_actions&ID=<?=$ACTION_ID;?>&NAME=Action For Docket No. <?=$docket_alt;?>"><font color="red"><b>X</b></font></a>&nbsp;
			<a target=_blank href="tmaction_edit.php?module=<?=$module;?>&TM_ID=<?=$TM_ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
			  <?=$action_type;?></a>&nbsp;Due&nbsp;<?=$respdue_date;?><br> <?}
		    else {	//done
		      ?><a href="delete_confirm.php?module=<?=$module;?>&TABLE=tm_actions&ID=<?=$ACTION_ID;?>&NAME=Action For Docket No. <?=$docket_alt;?>"><font color="red"><b>X</b></font></a>&nbsp;
		      <a target=_blank href="tmaction_edit.php?module=<?=$module;?>&TM_ID=<?=$TM_ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
			  <?=$action_type;?></a>&nbsp;Completed&nbsp;<?=$respdone_date;?><br>
		  <?}}?>
        </td>
	<td align="right" valign="top" width="115">
	      Files<br>
		<a target=_blank href="tmdoc_upload.php?module=<?=$module;?>&TM_ID=<?=$TM_ID;?>&id=0&I=0&EDIT=Y">Add</a>
	  </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
	 <td colspan="1" valign="top"><!-- EXISTING DISC FILES -->
		    &nbsp;&nbsp;<?
		    echo($ATTACHEDFILES." Files");?>
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=<?=$TM_ID;?>&ATTACHEDFILES=All&I=1&EDIT=Y">All</a>&nbsp;<?	//All will always be default
		    $sql = "SELECT * FROM menus WHERE menu_type='TRADEMARK_DOCUMENT' ORDER BY menu_name";	//selecting doc names
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
			  $doctype=$row["menu_name"];
			  ?>-&nbsp;<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=<?=$TM_ID;?>&ATTACHEDFILES=<?=$doctype;?>&I=1&EDIT=Y"><?=$doctype;?></a>&nbsp; 
			<?}?><br>
		    <? if($ATTACHEDFILES=="All")
			$sql="Select * FROM tm_documents WHERE
			      tm_ID='$TM_ID'
				ORDER BY doc_date";
		    else	//should only go if ATTACHEDFILES is a valid subtype
		      $sql="SELECT * FROM tm_documents WHERE
			tm_ID='$TM_ID' and
			  subtype='$ATTACHEDFILES'
			    ORDER BY doc_date";
		    $result=mysql_query($sql);
		    while($row=mysql_fetch_array($result)){
		      $DOC_ID=$row["id"];
		      $subtype=$row["subtype"];
		      //$respdue_date=$row["respdue_date"];
		      $docName=$row["name"];
		      $alt_name=$row["alt_name"];?>
			  <a href="delete_confirm.php?module=<?=$module;?>&TABLE=tm_documents&ID=<?=$DOC_ID;?>&NAME=Document For Docket No. <?=$docket_alt;?>&alt_name=<?=$alt_name;?>"><font color="red"><B>X</B></font></a>&nbsp
			  <a target=_blank href="tmdoc_upload.php?module=<?=$module;?>&TM_ID=<?=$TM_ID;?>&id=<?=$DOC_ID;?>&subtype=<?=$subtype;?>&I=1&EDIT=N">
			    <?=$subtype;?></a>&nbsp;&nbsp;
			    <a href="<?=$absolute_documents.$alt_name;?>"><?=$docName;?></a><br>
		    <?}?>
	  </td>
    </tr>		
    <tr>
        <td align="center" colspan="6" width="100%">
            <hr noshade size="1" width="500">
            <input type=submit name="submit_2" value="  OK  ">
        </td>
    </tr>
</table>  	
<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#336666" bgcolor="#336666"><tr>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Creator: <?=$creator;?> on <?=$create_date;?></small></td>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Editor: <?=$editor;?> on <?=$edit_date;?></small></td></tr>
</table>
<?}
else{
//check to make sure dates are valid
if($priority_date == "")
  {$priority_date = "0000-00-00";}
if($filing_date == "")
  {$filing_date = "0000-00-00";}
if($pub_date == "")
  {$pub_date = "0000-00-00";}
if($regi_date == "")
  {$regi_date = "0000-00-00";}
// enable apostrophe's in data fields by using escape_string to insert a '/' before every special character as defined in mysql
$title = mysql_real_escape_string($title);
$docket = mysql_real_escape_string($docket);
$firm_contact = mysql_real_escape_string($firm_contact);
$client_contact = mysql_real_escape_string($client_contact);
$intl_class = mysql_real_escape_string($intl_class);
$description = mysql_real_escape_string($description);
$products = mysql_real_escape_string($products);
$notes = mysql_real_escape_string($notes);
$ser_no = mysql_real_escape_string($ser_no);
$filing_date = mysql_real_escape_string($filing_date);
$pub_date = mysql_real_escape_string($pub_date);
$pub_no = mysql_real_escape_string($pub_no);
$regi_date = mysql_real_escape_string($regi_date);
$tm_no = mysql_real_escape_string($tm_no);
// The script performs the database modification
$sql = "UPDATE tm_filings SET
    customer_ID = '$customer_ID',
    tmfam_ID = '$TMFAM_ID',
    original = '$original',
    org = '$userorg',
    docket = '$docket',
    docket_alt = '$docket_alt',
    firm = '$firm',
    firm_contact = '$firm_contact',
    client = '$client',
    client_contact = '$client_contact',
    title = '$title',
    filing_type = '$filing_type',
    country = '$country',
    status = '$status',
    filing_date = '$filing_date',
    filing_receipt = '$filing_receipt',
    assignment = '$assignment',
    assignment_recorded = '$assignment_recorded',
    pub_date = '$pub_date',
    regi_date = '$regi_date',
    ser_no = '$ser_no',
    pub_no = '$pub_no',
    tm_no = '$tm_no',
    intl_class = '$intl_class',
    description = '$description',
    products = '$products',
    notes = '$notes',
	editor='$fullname',
	edit_date='$today'
    WHERE tm_ID='$TM_ID'";
  
// RUN THE QUERY
  if (!mysql_query($sql)){
  echo mysql_error();
   exit;}
	  
// Autodocket if it's a first case
  if($NEW=="Y"){
    include("autoactions.php");}
	  
// **Commented out to display updated record rather than confirmation screen
 $DONE="1";
}}
else{ // DISPLAY RECORD -- NOT EDIT
?>
<!-- DISPLAY TRADEMARK -->

<table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">
    <a href="trademarks_list.php?module=<?=$module;?>&SORT=TMFAM&TMFAM_ID=<?=$TMFAM_ID;?>">View Family</a>&nbsp;|&nbsp;
    <a href="total_cost.php?module=<?=$module;?>&ID=<?=$docket_alt;?>">Cost</a>    
    <? if ($sysadmin=="Y" or $user_level != "Viewer"){?>&nbsp;|&nbsp;
    <a href="accounts_edit.php?module=<?=$module;?>&ecs_docket=<?=$docket_alt;?>&firm_docket=<?=$docket;?>&I=0">Add Transaction</a>&nbsp;|&nbsp;
  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=<?=$TM_ID?>&ACTIONS=Open&I=1&EDIT=Y&SORT=<?=$SORT;?>&VAR=<?=$VAR?>">Edit</a>
  <?}?></td></tr>
</table><br><br>
<center>FULL TRADEMARK RECORD</center><br>
<table align="center" border="0" cellpadding="0" cellspacing="5">
    <tr>
        <td align="right" width="115">
            Title
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td colspan="4" align="left" width="450" bgcolor="#EEEEEE">
            <?=$title;?>
        </td>
    </tr>
    <tr>
        <td align="right" width="115">
            Firm Docket No.
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td colspan="4" align="left" width="450" bgcolor="#EEEEEE">
            <?=$docket;?>
        </td>
    </tr>
    <tr>
	<td align="right" width="115">
            ECS Docket No.
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$docket_alt;?>
        </td>
        <td align="right" width="115">
            Status
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$status;?>
        </td>
    </tr>
    <tr>
        <td align="right" width="115">
            Service Firm
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205" bgcolor="#EEEEEE">
			<?=$firm;?>
        </td>
        <td align="right" width="115">
            Firm Contact
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$firm_contact;?>
        </td>
    </tr>
    <tr>
        <td align="right" width="115">
            Client
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205" bgcolor="#EEEEEE">
			<?=$client;?>
        </td>
        <td align="right" width="115">
            Client Contact
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$client_contact;?>
        </td>
    </tr>
    <tr>
        <td align="right" width="115">
            Type
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$filing_type;?>
			&nbsp;&nbsp;<a target="_blank" href="help.php#glossary">Glossary</a>
        </td>
        <td align="right" width="115">
            Country
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$country;?>
			&nbsp;&nbsp;<a target="_blank" href="help.php#country_codes">Country Codes</a>
        </td>
	</tr>
	<tr>
        <td align="right" width="115">
            Filing Date
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$filing_date;?>
        </td>
        <td align="right" width="115">
            Serial No.
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$ser_no;?>
        </td>	
	</tr>
	<tr>
        <td align="right" width="115">
            Publ. for Opposition
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$pub_date;?>
        </td>
        <td align="right" width="115">
            Publ. No.
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$pub_no;?>
        </td>
	</tr>
	<tr>
        <td align="right" width="115">
            Registration Date
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$regi_date;?>
        </td>
        <td align="right" width="115">
            Reg. No.
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$tm_no;?>
        </td>
	</tr>
    <tr>
        <td align="right" valign="top" width="115">
	      Related<br>Documents
	    </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205" bgcolor="#EEEEEE">
          <input type="checkbox" name="filing_receipt" value="Y" <? if ($filing_receipt=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Filing Receipt<br>
          <input type="checkbox" name="assignment" value="Y" <? if ($assignment=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Assignment<br>
          <input type="checkbox" name="assignment_recorded" value="Y" <? if ($assignment_recorded=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Recorded Assignment
	    </td>
	          <td align="right" width="115">
            Original
        </td>
		  <td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$original;?>
			&nbsp;&nbsp;<a target="_blank" href="help.php#glossary">Glossary</a>
        </td>
	</tr>
	<tr>
        <td align=right width="115">
            Intl. Classes
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$intl_class;?>
        </td>
	</tr>
	<tr>
        <td align="right" valign="top" width="115">
            Description
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td colspan="4" bgcolor="#EEEEEE">
            <?=$description;?>
        </td>
	</tr>
	<tr>
        <td align="right" valign="top" width="115">
            Products
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td colspan="4" bgcolor="#EEEEEE">
            <?=$products;?>
        </td>
	</tr>	
	<tr>
        <td align="right" valign="top" width="115">
            Notes
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td colspan="4" bgcolor="#EEEEEE">
            <?=$notes;?>
        </td>
	</tr>
    <tr>
        <td align="right" valign="top" width="115">
            Actions
            <?if ($user_level != "Viewer") {?>
			<br><a target=_blank href="tmaction_edit.php?module=<?=$module;?>&TM_ID=<?=$TM_ID;?>&ACTION_ID=0&I=0&EDIT=Y">Add</a>&nbsp;&nbsp;<?}?>
        </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
        <td colspan="4" valign="top" bgcolor="#EEEEEE"><!-- EXISTING TM ACTIONS -->
		  <? // SQL Query for Selecting Actions Type
		  echo($ACTIONS." Actions");?>
		  	<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=<?=$TM_ID;?>&ACTIONS=All&I=1&EDIT=N">All</a> -
			<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=<?=$TM_ID;?>&ACTIONS=Open&I=1&EDIT=N">Open</a> -
			<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=<?=$TM_ID;?>&ACTIONS=Closed&I=1&EDIT=N">Closed</a><br>
		  <? if ($ACTIONS=="Open") 
		    $sql="SELECT * FROM tm_actions WHERE
		      tm_ID='$TM_ID' and
			  done='N'";
		  elseif ($ACTIONS=="Closed")
		    $sql="SELECT * FROM tm_actions WHERE
		      tm_ID='$TM_ID' and
			  done='Y'";
		  else
            $sql="SELECT * FROM tm_actions WHERE
		      tm_ID='$TM_ID'
			  ORDER BY respdue_date";
		  $result=mysql_query($sql);
		  while($row=mysql_fetch_array($result)){
		    $ACTION_ID=$row["action_ID"];
		    $action_type=$row["action_type"];
		    $respdue_date=$row["respdue_date"];
		    $description=$row["description"];
		    $respdone_date=$row["respdone_date"];
		    $done=$row["done"];
		    if ($done == "N") {?>
			<a target=_blank href="tmaction_edit.php?module=<?=$module;?>&TM_ID=<?=$TM_ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
			  <?=$action_type;?></a>&nbsp;Due&nbsp;<?=$respdue_date;?><br> <?}
		    else {	//done
		      ?>
		      <a target=_blank href="tmaction_edit.php?module=<?=$module;?>&TM_ID=<?=$TM_ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
			  <?=$action_type;?></a>&nbsp;Completed&nbsp;<?=$respdone_date;?><br>
		  <?}}?>
        </td>
    </tr>
    <tr>
	<td align="right" valign="top" width="115">
	      Files<br>
		<?if ($user_level != "Viewer") {?><a target=_blank href="tmdoc_upload.php?module=<?=$module;?>&TM_ID=<?=$TM_ID;?>&id=0&I=0&EDIT=Y">Add</a><?}?>
	  </td>
	<td>&nbsp;&nbsp;&nbsp;</td>
	 <td colspan="4" valign="top" bgcolor=EEEEEE><!-- EXISTING DISC FILES -->
		    <? 
		    echo($ATTACHEDFILES." Files");?>
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=<?=$TM_ID;?>&ATTACHEDFILES=All&I=1&EDIT=N">All</a>&nbsp;<?	//All will always be default
		    $sql = "SELECT * FROM menus WHERE menu_type='TRADEMARK_DOCUMENT' ORDER BY menu_name";	//selecting doc names
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
			  $doctype=$row["menu_name"];
			  ?>-&nbsp;<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=<?=$TM_ID;?>&ATTACHEDFILES=<?=$doctype;?>&I=1&EDIT=N"><?=$doctype;?></a>&nbsp; 
			<?}?><br></td></tr><td></td><td>&nbsp;&nbsp;&nbsp;</td><td colspan="4" valign="top" bgcolor=EEEEEE>
		    <? if($ATTACHEDFILES=="All")
			$sql="Select * FROM tm_documents WHERE
			      tm_ID='$TM_ID'
				ORDER BY doc_date";
		    else	//should only go if ATTACHEDFILES is a valid subtype
		      $sql="SELECT * FROM tm_documents WHERE
			tm_ID='$TM_ID' and
			  subtype='$ATTACHEDFILES'
			    ORDER BY doc_date";
		    $result=mysql_query($sql);
		    while($row=mysql_fetch_array($result)){
		      $DOC_ID=$row["id"];
		      $subtype=$row["subtype"];
		      $docName=$row["name"];
		      $alt_name=$row["alt_name"];?>
			  <a target=_blank href="tmdoc_upload.php?module=<?=$module;?>&TM_ID=<?=$TM_ID;?>&id=<?=$DOC_ID;?>&subtype=<?=$subtype;?>&I=1&EDIT=N">
			    <?=$subtype;?></a>&nbsp;&nbsp;
			    <a href="<?=$absolute_documents.$alt_name;?>"><?=$docName;?></a><br>
		    <?}?>
	  </td>
    </tr>
</table>  	
<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#336666" bgcolor="#336666"><tr>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Creator: <?=$creator;?> on <?=$create_date;?></small></td>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Editor: <?=$editor;?> on <?=$edit_date;?></small></td></tr>
</table>
<?}}?>

<!-- DONE -->
<? if ($DONE=="1") {?>
<META HTTP-EQUIV="refresh" 
CONTENT="0;URL=<?=$PHP_SELF;?>?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=<?=$TM_ID?>&I=1&EDIT=N">
<?} html_footer(); ?>
