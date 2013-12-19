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
//	6/11/2013	Changed link to point to the correct place in done screen
//	6/20/2013	changed everything to work with the new docket_master system. Took me awhile, because I was inadvertantly running the same sql code over and over instead of running the correct ones
//	6/21/2013	changed it to say ECS Docket
//	6/24/2013	added attach file functionality
//			after fiddling around with this for a while, I still don't know why it is off to the left. I believe it is something to do with the bottom gray line.
//	6/26/2013	defaulted ATTACHEDFILES to All
//			updated to dynamically display document names instead of manually. also changed it so it doesn't go to edit mode when you select different lists.
//	6/27/2013	updated to use meta refresh to display the updated record instead of the done screen.
//	7/1/2013	added cost links
//	7/3/2013	removed width=100% to make it centered
//	7/8/2013	added a bunch of spaces to make it look cleaner
//	7/16/2013	made it so everyone can see cost
//	7/17/2013	added $user_level to header call
//	7/18/2013	replaced all authentication to $user_level while leaving in $sysadmin because I'm pretty sure that's just the root admin.
//	7/23/2013	added add transaction link under edit screen
//	7/24/2013	^^also view screen^^
//	7/30/2013	replaced paths with session variables
//			modified permissions so viewers can't edit files from view screen
//	8/9/2013	ordered files by doc_date
//			allowed special characters in data fields. for more info, check out trademarks_edit
//	8/13/2013	changed error protocol to exit/display error
//	8/15/2013	added checkdates
//	8/19/2013	fixed date logic; incorrectly was using checkdate. this won't work, because of formatting. now, it will work, but throw the mysql_error if the date is invalid. works with ""
//	8/23/2013	fixed date length formatting
//	8/27/2013	cleaned up the code a little; added comments
//	8/29/2013	replaced server_docs with abs_docs
// copyrights_edit.php -- User Acess Level: User/Viewer
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
if ($ATTACHEDFILES==""){$ATTACHEDFILES="All";}?>
<?
// For new records, incoming link is set to $ID="0" and I="0"
// Otherwise, $ID is set to the existing record number
// SQL Query for an existing NDA record and retrieving data
if ($I=="1"){
$sql="SELECT * FROM copyrights WHERE ID='$ID'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
    $docket = $row["docket"];
    $docket_alt = $row["docket_alt"];
    $org = $row["org"];
    $firm = $row["firm"];
    $firm_contact = $row["firm_contact"];
    $client = $row["client"];
    $title = $row["title"];
    $authors = $row["authors"];
	$filing_type = $row["filing_type"];
	$country = $row["country"];
	$status = $row["status"];
    $filing_date = $row["filing_date"];
    $pub_date = $row["pub_date"];
	$issue_date = $row["issue_date"];
	$c_no = $row["c_no"];
    $description=$row["description"];
    $notes=$row["notes"];
    $creator=$row["creator"];
    $create_date=$row["create_date"];
    $editor=$row["editor"];
    $edit_date=$row["edit_date"];
}
if (($sysadmin=="Y" or $user_level != "Viewer") and $read_only=="N" and $EDIT=="Y"){?>
<? if ($ID!="0"){?>
<table align="right" border="0" cellpadding="0" cellspacing="0">
  <tr><td align="center">
  <a href="accounts_edit.php?module=<?=$module;?>&ecs_docket=<?=$docket_alt;?>&firm_docket=<?=$docket;?>&I=0">Add Transaction</a>&nbsp;|&nbsp;
  <a href="total_cost.php?module=<?=$module;?>&ID=<?=$docket_alt;?>">Cost</a>&nbsp;|&nbsp;
  <a href="delete_confirm.php?module=<?=$module;?>&TABLE=copyrights&ID=<?=$ID;?>&NAME=<?=$docket;?>">Delete</a></td>
  </td></tr>
</table>
<?}?><br><br>
<? if ($ID=="0") echo("<center>ADD COPYRIGHT RECORD</center><br>");
   else echo("<center>EDIT COPYRIGHT RECORD</center><br>");
if ($submitok=="" or $docket=="" or $firm=="" or $title=="" or $filing_type=="" or $status=="" or $country=="" or $authors==""){
  if ($submitok!=""){?>	<!--if it gets here, either ok hasn't been pressed, or something above is missing-->
    <center><br><font color="red">Please update fields marked with a red asterisk.</font><br></center><?}?>
<table align="center" width="500">
</table><br>
<form method=post action="<?=$PHP_SELF;?>">
  <input type="hidden" name="module" value="<?=$module;?>">
  <input type="hidden" name="SORT" value="<?=$SORT;?>">
  <input type="hidden" name="VAR" value="<?=$VAR;?>">
  <input type="hidden" name="ID" value="<?=$ID;?>">
  <input type="hidden" name="docket_alt" value="<?=$docket_alt;?>">
  <input type="hidden" name="I" value="0">
  <input type="hidden" name="EDIT" value="Y">
  <input type="hidden" name="ATTACHEDFILES" value="<?=$ATTACHEDFILES;?>">
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
            Title
        </td>
	<td></td>
        <td width="400">
            <input name="title" type="text" maxlength="100" size="35" value="<?=$title;?>">
            <font color="orangered"><TT><B>*</B></TT></font>
        </td>
    </tr>
    <tr>
      <td align=right width="150">
            Firm Docket No.
        </td>
      <td></td>
        <td width="400">
            <input name="docket" type="text" maxlength="35" size="35" value="<?=$docket;?>">
            <font color="orangered"><TT><B>*</B></TT></font>
        </td>
    </tr>
    <tr>
        <td align="right" width="150">
	  ECS Docket No.
	</td>
	<td></td>
	<td width="400">
	  <?=$docket_alt;?>
	</td>
    </tr>
    <tr>
        <td align=right width="150">
            Service Firm
        </td>
	<td></td>
        <td width="400">
            <select name="firm" size="1">
			<option><?=$firm;?></option>
		    <? $sql="SELECT * FROM menus WHERE org='$userorg' and menu_type='FIRM' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
            <font color="orangered"><TT><B>*</B></TT></font>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            Contact
        </td>
	<td></td>
        <td width="400">
            <input name="firm_contact" type="text" maxlength="100" size="35" value="<?=$firm_contact;?>">
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            Client
        </td>
	<td></td>
        <td width="400">
            <select name="client" size="1">
			<option><?=$client;?></option>
		    <? $sql="SELECT * FROM menus WHERE org='$userorg' and menu_type='CLIENT' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
            <font color="orangered"><TT><B>*</B></TT></font>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            Type
        </td>
	<td></td>
        <td width="400">
            <select name="filing_type" size="1">
            <option><?=$filing_type;?></option>
            <? $sql="SELECT * FROM menus WHERE org='$userorg' and menu_type='COPYRIGHT_TYPE' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
            <font color="orangered"><TT><B>*</B></TT></font>
        </td>
    </tr>
	<tr>
		<td align=right width="150">
            Country
        </td>
	<td></td>
        <td width="400">
            <input name="country" type="text" maxlength="100" size="35" value="<?=$country;?>">
            <font color="orangered"><TT><B>*</B></TT></font>
        </td>
	</tr>
	<tr>
        <td align=right width="150">
            Filing Date
        </td>
	<td></td>
        <td width="400">
            <input type="text" name="filing_date" size="9" maxlength="10" value="<?=$filing_date;?>">
			<small>&nbsp;(YYYY-MM-DD)</small>
        </td>
	</tr>
	<tr>
        <td align=right width="150">
            Publication Date
        </td>
	<td></td>
        <td width="400">
            <input type="text" name="pub_date" size="9" maxlength="10" value="<?=$pub_date;?>">
			<small>&nbsp;(YYYY-MM-DD)</small>
        </td>
	</tr>
	<tr>
        <td align=right width="150">
            Issue Date
        </td>
	<td></td>
        <td width="400">
            <input type="text" name="issue_date" size="9" maxlength="10" value="<?=$issue_date;?>">
			<small>&nbsp;(YYYY-MM-DD)</small>
        </td>
	</tr>
	<tr>
        <td align=right width="150">
            Reg. No.
        </td>
	<td></td>
        <td width="400">
            <input name="c_no" type="text" maxlength="25" size="25" value="<?=$c_no;?>">
        </td>
	</tr>
    <tr valign=top>
        <td align=right width="150">
            Authors
        </td>
	<td></td>
        <td width="400">
            <textarea wrap name="authors" rows="3" cols="35"><?=$authors;?></textarea>
            <font color="orangered"><TT><B>*</B></TT></font>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            Status
        </td>
	<td></td>
        <td width="400">
            <select name="status" size="1">
            <option><?=$status;?></option>
		    <? $sql="SELECT * FROM menus WHERE org='$userorg' and menu_type='COPYRIGHT_STATUS' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
            <font color="orangered"><TT><B>*</B></TT></font>
        </td>
    </tr>
	<tr>
        <td align=right width="150">
            Description
        </td>
	<td></td>
        <td width="400">
            <textarea wrap name="description" rows="2" cols="35"><?=$description;?></textarea>
        </td>
	</tr>
	<tr>
        <td align=right width="150">
            Notes
        </td>
	<td></td>
        <td width="400">
            <textarea wrap name="notes" rows="2" cols="35"><?=$notes;?></textarea>
        </td>
	</tr>
	<tr>
	  <td align="right" width="150">
	      Files<br>
		<a target=_blank href="cpydoc_upload.php?module=<?=$module;?>&CPY_ID=<?=$ID;?>&id=0&I=0&EDIT=Y">Add</a>
	  </td>
	  <td></td>
	 <td colspan="1" valign="top"><!-- EXISTING DISC FILES -->
		    <?
		    echo($ATTACHEDFILES." Files");?>
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ID=<?=$ID;?>&ATTACHEDFILES=All&I=1&EDIT=Y">All</a>&nbsp;<?	//All will always be default
		    $sql = "SELECT * FROM menus WHERE menu_type='COPYRIGHT_DOCUMENT' ORDER BY menu_name";	//selecting doc names
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
			  $doctype=$row["menu_name"];
			  ?><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ID=<?=$ID;?>&ATTACHEDFILES=<?=$doctype;?>&I=1&EDIT=Y"><?=$doctype;?></a>&nbsp; 
			<?}?><br>
		    <? if($ATTACHEDFILES=="All")
			$sql="Select * FROM cpy_documents WHERE
			      cpy_ID='$ID'
				ORDER BY doc_date";
		    else	//should only go if ATTACHEDFILES is a valid subtype
		      $sql="SELECT * FROM cpy_documents WHERE
			cpy_ID='$ID' and
			  subtype='$ATTACHEDFILES'
			    ORDER BY doc_date";
		    $result=mysql_query($sql);
		    while($row=mysql_fetch_array($result)){
		      $DOC_ID=$row["id"];
		      $subtype=$row["subtype"];
		      //$respdue_date=$row["respdue_date"];
		      $docName=$row["name"];
		      $alt_name=$row["alt_name"];?>
			  <a href="delete_confirm.php?module=<?=$module;?>&TABLE=cpy_documents&ID=<?=$DOC_ID;?>&NAME=Document For Docket No. <?=$docket_alt;?>&alt_name=<?=$alt_name;?>"><font color="red"><B>X</B></font></a>&nbsp
			  <a target=_blank href="cpydoc_upload.php?module=<?=$module;?>&CPY_ID=<?=$ID;?>&id=<?=$DOC_ID;?>&subtype=<?=$subtype;?>&I=1&EDIT=N">
			    <?=$subtype;?></a>&nbsp;&nbsp;
			    <a href="<?=$absolute_documents . $alt_name;?>"><?=$docName;?></a><br>
		    <?}?>
	  </td></tr>
    <tr>
        <td align="center" colspan="4" width="100%">
            <hr noshade size="1" width="500">
            <input type=submit name="submitok" value="   OK   ">
        </td>
    </tr>
</table>

<?}
// UPDATE RECORD
else{
//not using checkdate here since formatting is off
if($filing_date == "")
  {$filing_date = "0000-00-00";}
if($pub_date == "")
  {$pub_date = "0000-00-00";}
if($issue_date == "")
  {$issue_date = "0000-00-00";}
// enable apostrophe's in data fields by using escape_string to insert a '/' before every special character as defined in mysql
$title = mysql_real_escape_string($title);
$docket = mysql_real_escape_string($docket);
$firm_contact = mysql_real_escape_string($firm_contact);
$description = mysql_real_escape_string($description);
$authors = mysql_real_escape_string($authors);
$notes = mysql_real_escape_string($notes);
$country = mysql_real_escape_string($country);
$c_no = mysql_real_escape_string($c_no);
$filing_date = mysql_real_escape_string($filing_date);
$pub_date = mysql_real_escape_string($pub_date);
$issue_date = mysql_real_escape_string($issue_date);

if ($ID=="0" or $duplicate=="Y") // ADD NEW COPYRIGHT
{
    $sql = "INSERT INTO copyrights SET
              customer_ID = '$customer_ID',
              docket = '$docket',
              org = '$userorg',
              firm = '$firm',
              firm_contact = '$firm_contact',
              client = '$client',
              title = '$title',
              filing_type = '$filing_type',
			  authors = '$authors',
			  country = '$country',
			  status = '$status',
              filing_date = '$filing_date',
              pub_date = '$pub_date',
			  issue_date = '$issue_date',
			  c_no = '$c_no',
			  description = '$description',
              notes = '$notes',
			  creator='$fullname',
			  create_date='$today'";
			  
     if (!mysql_query($sql)){
  echo mysql_error();
   exit;}

    $New_ID=mysql_insert_id();
    
    $sql_1 = "INSERT INTO docket_master SET
		type='copyright',
		ID='$New_ID'";
      if (!mysql_query($sql_1)){
  echo mysql_error();
   exit;}

    $New_docket_alt=mysql_insert_id();
    
    $sql_2 = "UPDATE copyrights SET
		docket_alt='$New_docket_alt'
		WHERE ID='$New_ID'";
      if (!mysql_query($sql_2)){
  echo mysql_error();
   exit;}
	     
  $ID=$New_ID;
}

else // UPDATE EXISTING COPYRIGHT
{
    $sql = "UPDATE copyrights SET
              customer_ID = '$customer_ID',
              docket = '$docket',
	      docket_alt = '$docket_alt',
              org = '$userorg',
              firm = '$firm',
              firm_contact = '$firm_contact',
              client = '$client',
              title = '$title',
              filing_type = '$filing_type',
			  authors = '$authors',
			  country = '$country',
			  status = '$status',
              filing_date = '$filing_date',
              pub_date = '$pub_date',
			  issue_date = '$issue_date',
			  c_no = '$c_no',
			  description = '$description',
              notes = '$notes',
			  editor='$fullname',
			  edit_date='$today'
              WHERE ID='$ID'";
	      
     if (!mysql_query($sql)){
  echo mysql_error();
   exit;}
}
?>
<META HTTP-EQUIV="refresh" 
CONTENT="0;URL=<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&VAR=<?=$VAR?>&ID=<?=$ID?>&I=1&EDIT=N">
<? }} ?>
<!-- DISPLAY -->
<? if ($EDIT=="N") { ?>

<table align="right" border="0" cellpadding="0" cellspacing="0">
  <tr><td align="center">
  <a href="total_cost.php?module=<?=$module;?>&ID=<?=$docket_alt;?>">Cost</a>  
  <? if ($sysadmin=="Y" or $user_level != "Viewer"){?>&nbsp;|&nbsp;
  <a href="accounts_edit.php?module=<?=$module;?>&ecs_docket=<?=$docket_alt;?>&firm_docket=<?=$docket;?>&I=0">Add Transaction</a>&nbsp;|&nbsp;
  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&VAR=<?=$VAR?>&ID=<?=$ID?>&I=1&EDIT=Y">Edit</a><?}?>  
  </td></tr>
</table>
<br><br>
<center>FULL COPYRIGHT RECORD</center><br>
<table align="center" border="0" cellpadding="0" cellspacing="10">
    <tr>
        <td align=right width="150">
            Title
        </td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="400" bgcolor=EEEEEE>
            <?=$title;?>
        </td>
    </tr>
    <tr>
      <td align="right" width="150">
	Firm Docket No.
      </td>
      <td></td>
      <td width="400" bgcolor=EEEEEE>
	<?=$docket;?>
      </td>
    </tr>
    <tr>
        <td align=right width="150">
            ECS Docket No.
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$docket_alt;?>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            Service Firm
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$firm;?>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            Contact
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$firm_contact;?>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            Client
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$client;?>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            Type
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$filing_type;?>
        </td>
    </tr>
	<tr>
		<td align=right width="150">
            Country
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$country;?>
        </td>
	</tr>
    <tr>
        <td align=right width="150">
            Status
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$status;?>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            Authors
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$authors;?>
        </td>
    </tr>
	<tr>
        <td align=right width="150">
            Filing Date
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$filing_date;?>
        </td>
	</tr>
	<tr>
        <td align=right width="150">
            Publication Date
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$pub_date;?>
        </td>
	</tr>
	<tr>
        <td align=right width="150">
            Issue Date
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$issue_date;?>
        </td>
	</tr>
	<tr>
        <td align=right width="150">
            Reg. No.
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$c_no;?>
        </td>
	</tr>
	<tr>
        <td align=right width="150">
            Description
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$description;?>
        </td>
	</tr>
	<tr>
        <td align=right width="150">
            Notes
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$notes;?>
        </td>
	</tr>
	<tr>
	  <td align="right" width="150">
	      Files<br>
		<?if ($user_level != "Viewer") {?><a target=_blank href="cpydoc_upload.php?module=<?=$module;?>&CPY_ID=<?=$ID;?>&id=0&I=0&EDIT=Y">Add</a><?}?>
	  </td><td></td>
	 <td colspan="1" valign="top" width="400" bgcolor=EEEEEE><!-- EXISTING DISC FILES -->
		    <? 
		    echo($ATTACHEDFILES." Files");?>
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ID=<?=$ID;?>&ATTACHEDFILES=All&I=1&EDIT=N">All</a>&nbsp;<?	//All will always be default
		    $sql = "SELECT * FROM menus WHERE menu_type='COPYRIGHT_DOCUMENT' ORDER BY menu_name";	//selecting doc names
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
			  $doctype=$row["menu_name"];
			  ?>-&nbsp;<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ID=<?=$ID;?>&ATTACHEDFILES=<?=$doctype;?>&I=1&EDIT=N"><?=$doctype;?></a>&nbsp; 
			<?}?><br></td></tr><td></td><td></td><td colspan="3" valign="top" bgcolor=EEEEEE>
		    <? if($ATTACHEDFILES=="All")
			$sql="Select * FROM cpy_documents WHERE
			      cpy_ID='$ID'
				ORDER BY doc_date";
		    else	//should only go if ATTACHEDFILES is a valid subtype
		      $sql="SELECT * FROM cpy_documents WHERE
			cpy_ID='$ID' and
			  subtype='$ATTACHEDFILES'
			    ORDER BY doc_date";
		    $result=mysql_query($sql);
		    while($row=mysql_fetch_array($result)){
		      $DOC_ID=$row["id"];
		      $subtype=$row["subtype"];
		      $docName=$row["name"];
		      $alt_name=$row["alt_name"];?>
			   <a target=_blank href="cpydoc_upload.php?module=<?=$module;?>&CPY_ID=<?=$ID;?>&id=<?=$DOC_ID;?>&subtype=<?=$subtype;?>&I=1&EDIT=N">
			    <?=$subtype;?></a>&nbsp;&nbsp;
			    <a href="<?=$absolute_documents . $alt_name;?>"><?=$docName;?></a><br>
		    <?}?>
	  </td>
	</tr>
</table>
<?}?>
<table width="100%" border="3" cellpadding="0" cellspacing="0" bordercolor="#336666" bgcolor="#336666"><tr>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Creator: <?=$creator;?> on <?=$create_date;?></small></td>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Editor: <?=$editor;?> on <?=$edit_date;?></small></td></tr>
</table><br>
<? html_footer(); ?>
