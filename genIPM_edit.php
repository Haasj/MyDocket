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
//Maitenence Log Jonathan Haas
//	6/25/2013	Created from ndas_edit.
//			contracts->genipm_filings, all other things changed
//			I think this should be done. added all funtionality, and took out what wasn't needed.
//	6/26/2013	defaulted ATTACHEDFILES to All
//			updated to dynamically display document names instead of manually. also changed it so it doesn't go to edit mode when you select different lists.
//	7/1/2013	added cost links
//	7/16/2013	made it so everyone can view cost
//	7/17/2013	added $user_level to header call
//	7/18/2013	replaced all authentication to $user_level while leaving in $sysadmin because I'm pretty sure that's just the root admin.
//	7/19/2013	add didn't refresh to the record; fixed by assigning id=new_id
//	7/23/2013	added add transaction to edit screen; widened the docket date field
//	7/24/2013	^^also on view screen^^
//	7/30/2013	replaced path names with session_variables
//	8/9/2013	ordered files by doc_date
//			allowed specail chars in data fields. more info: trademarks_edit
//	8/13/2013	changed error protocol to exit/display error
//	8/15/2013	added checkdates
//	8/19/2013	fixed date logic; incorrectly was using checkdate. this won't work, because of formatting. now, it will work, but throw the mysql_error if the date is invalid. works with ""
//	8/28/2013	cleaned up the code a little
//	8/29/2013	replaced server_docs with abs_docs
//genIPM_edit.php -- 	User Access Level: Viewer/User
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
if ($ATTACHEDFILES==""){$ATTACHEDFILES="All";}
// For new records, incoming link is set to $ID="0" and I="0"
// Otherwise, $ID is set to the existing record number
// SQL Query for an existing NDA record and retrieving data
if ($I=="1"){
$sql="SELECT * FROM genipm_filings WHERE ID='$ID'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
    $docket = $row["docket"];
    $docket_alt = $row["docket_alt"];
    $org = $row["org"];
    $firm = $row["firm"];
    $firm_contact = $row["firm_contact"];
    $client = $row["client"];
    $client_contact = $row["client_contact"];
    $title = $row["title"];
	$status = $row["status"];
	$docket_date = $row["docket_date"];
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
  <a href="delete_confirm.php?module=<?=$module;?>&TABLE=genipm_filings&ID=<?=$ID;?>&NAME=<?=$docket?>">Delete</a></td>
  </td></tr>
</table>
<?}?><br><br>
<? if ($ID=="0") echo("<center>ADD GENERAL IP MATTER RECORD</center><br>");
   else echo("<center>EDIT GENERAL IP MATTER RECORD</center><br>");
if ($submitok=="" or $docket=="" or $firm=="" or $title=="" or $status==""){
  if ($submitok!=""){?>
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
<input type="hidden" name="ACTIONS" value="<?=$ACTIONS;?>">
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
           Firm Contact
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
            Client Contact
        </td>
	<td></td>
        <td width="400">
            <input name="client_contact" type="text" maxlength="35" size="35" value="<?=$client_contact;?>">
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
		    <? $sql="SELECT * FROM menus WHERE menu_type='GENERAL_IPM_STATUS' ORDER BY menu_name";
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
            Docket Date
        </td>
       <td></td>
        <td width="400">
            <input type="text" name="docket_date" size="12" maxlength="10" value="<?=$docket_date;?>">
			<small>&nbsp;(YYYY-MM-DD)</small>
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
	  <td align="right" valign="top" width="150">
	      Actions<br>
			  <a target=_blank href="genIPMaction_edit.php?module=<?=$module;?>&GENIPM_ID=<?=$ID;?>&ACTION_ID=0&I=0&EDIT=Y">Add</a>&nbsp;&nbsp;
	  </td><td></td>
	  <td colspan="1" valign="top"><!-- EXISTING DISC ACTIONS -->
		    <? // SQL Query for Selecting Actions Type
		    echo($ACTIONS." Actions");?>
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&GENIPMEDIT=1&ID=<?=$ID;?>&ACTIONS=All&I=1&EDIT=Y">All</a> -
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&GENIPMEDIT=1&ID=<?=$ID;?>&ACTIONS=Open&I=1&EDIT=Y">Open</a> -
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&GENIPMEDIT=1&ID=<?=$ID;?>&ACTIONS=Closed&I=1&EDIT=Y">Closed</a><br>
		    <? if ($ACTIONS=="Open") 
		      $sql="SELECT * FROM genipm_actions WHERE
			genipm_id='$ID' and
			    done='N'";
		    elseif ($ACTIONS=="Closed")
		      $sql="SELECT * FROM genipm_actions WHERE
			genipm_id='$ID' and
			    done='Y'";
		    else
	      $sql="SELECT * FROM genipm_actions WHERE
			genipm_id='$ID'
			    ORDER BY respdue_date";
		    $result=mysql_query($sql);
		    while($row=mysql_fetch_array($result)){
		      $ACTION_ID=$row["action_ID"];
		      $action_type=$row["action_type"];
		      $respdue_date=$row["respdue_date"];
		      $description=$row["description"];?>
		      <a href="delete_confirm.php?module=<?=$module;?>&TABLE=genipm_actions&ID=<?=$ACTION_ID;?>&NAME=Action For Docket No. <?=$docket_alt;?>"><font color="red"><b>X</b></font></a>&nbsp;
			  <a target=_blank href="genipmaction_edit.php?module=<?=$module;?>&GENIPM_ID=<?=$ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
			    <?=$action_type;?></a>&nbsp;Due&nbsp;<?=$respdue_date;?><br>
		    <?}?>
	  </td>
        </tr>
	<tr>
	  <td align="right" width="150">
	      Files<br>
		<a target=_blank href="genIPMdoc_upload.php?module=<?=$module;?>&GENIPM_ID=<?=$ID;?>&id=0&I=0&EDIT=Y">Add</a>
	  </td><td></td>
	 <td colspan="1" valign="top"><!-- EXISTING DISC FILES -->
		    <? 
		    echo($ATTACHEDFILES." Files");?>
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ID=<?=$ID;?>&ATTACHEDFILES=All&I=1&EDIT=Y">All</a>&nbsp;<?	//All will always be default
		    $sql = "SELECT * FROM menus WHERE menu_type='GENERAL_IPM_DOCUMENT' ORDER BY menu_name";	//selecting doc names
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
			  $doctype=$row["menu_name"];
			  ?>-&nbsp;<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ID=<?=$ID;?>&ATTACHEDFILES=<?=$doctype;?>&I=1&EDIT=Y"><?=$doctype;?></a>&nbsp; 
			<?}?><br>
		    <? if($ATTACHEDFILES=="All")
			$sql="Select * FROM genipm_documents WHERE
			      genipm_ID='$ID'
				ORDER BY doc_date";
		    else	//should only go if ATTACHEDFILES is a valid subtype
		      $sql="SELECT * FROM genipm_documents WHERE
			genipm_ID='$ID' and
			  subtype='$ATTACHEDFILES'
			    ORDER BY doc_date";
		    $result=mysql_query($sql);
		    while($row=mysql_fetch_array($result)){
		      $DOC_ID=$row["id"];
		      $subtype=$row["subtype"];
		      $docName=$row["name"];
		      $alt_name=$row["alt_name"];?>
			  <a href="delete_confirm.php?module=<?=$module;?>&TABLE=genipm_documents&ID=<?=$DOC_ID;?>&NAME=Document For Docket No. <?=$docket_alt;?>&alt_name=<?=$alt_name;?>"><font color="red"><B>X</B></font></a>&nbsp
			  <a target=_blank href="genipmdoc_upload.php?module=<?=$module;?>&GENIPM_ID=<?=$ID;?>&id=<?=$DOC_ID;?>&subtype=<?=$subtype;?>&I=1&EDIT=N">
			    <?=$subtype;?></a>&nbsp;&nbsp;
			    <a href="<?=$absolute_documents . $alt_name;?>"><?=$docName;?></a><br>
		    <?}?>
	  </td>
	</tr>
    <tr>
        <td align="center" colspan="6" width="100%">
            <hr noshade size="1" width="500">
            <input type=submit name="submitok" value="   OK   ">
        </td>
    </tr>
</table>

<?}
// UPDATE RECORD
else {
//put in correct defaults
if($docket_date == "")
  {$docket_date = "0000-00-00";}
// enable apostrophe's in data fields by using escape_string to insert a '/' before every special character as defined in mysql
$title = mysql_real_escape_string($title);
$docket = mysql_real_escape_string($docket);
$firm_contact = mysql_real_escape_string($firm_contact);
$client_contact = mysql_real_escape_string($client_contact);
$docket_date = mysql_real_escape_string($docket_date);
$description = mysql_real_escape_string($description);
$notes = mysql_real_escape_string($notes);

if ($ID=="0" or $duplicate=="Y") // ADD NEW GEN IPM
{
    $sql = "INSERT INTO genipm_filings SET
              customer_ID = '$customer_ID',
              docket = '$docket',
              org = '$userorg',
              firm = '$firm',
              firm_contact = '$firm_contact',
              client = '$client',
              client_contact = '$client_contact',
              title = '$title',
			  status = '$status',
			  docket_date = '$docket_date',
              description = '$description',
              notes = '$notes',
			  creator='$fullname',
			  create_date='$today'";
			  
	    if (!mysql_query($sql)){
             echo mysql_error();
	     exit;}
	     
    $New_ID=mysql_insert_id();
    
    $sql_1 = "INSERT INTO docket_master SET
		type='GENIPM',
		ID='$New_ID'";
		
      if (!mysql_query($sql_1)){
  echo mysql_error();
   exit;}

    $New_docket_alt=mysql_insert_id();
    
    $sql_2 = "UPDATE genipm_filings SET
		docket_alt='$New_docket_alt'
		WHERE ID='$New_ID'";
		
      if (!mysql_query($sql_2)){
  echo mysql_error();
   exit;}

    $ID = $New_ID;
}
			  
else // UPDATE EXISTING IP
{
    $sql = "UPDATE genipm_filings SET
              customer_ID = '$customer_ID',
              docket = '$docket',
	      docket_alt = '$docket_alt',
              org = '$userorg',
              firm = '$firm',
              firm_contact = '$firm_contact',
              client = '$client',
              client_contact = '$client_contact',
              title = '$title',
			  status = '$status',
			  docket_date = '$docket_date',
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
<table align="center" width="500"><br>
<p><strong>Your record has been successfully updated.</strong></p>
<p> To return to the login page, click <a href="index.php">here</a></p>
<p> To view General IPM records, click <a href="genIPM_list.php?module=<?=$module;?>&SORT=ALL">here</a></p>
</table>
<? }} ?>

<!-- VIEW -->
<? if (!(($sysadmin=="Y" or $user_level != "Viewer") and $read_only=="N" and $EDIT=="Y")){?>
<table align="right" border="0" cellpadding="0" cellspacing="0">
  <tr><td align="center">
  <a href="total_cost.php?module=<?=$module;?>&ID=<?=$docket_alt;?>">Cost</a>
  <? if ($sysadmin=="Y" or $user_level != "Viewer"){?>&nbsp;|&nbsp;
  <a href="accounts_edit.php?module=<?=$module;?>&ecs_docket=<?=$docket_alt;?>&firm_docket=<?=$docket;?>&I=0">Add Transaction</a>&nbsp;|&nbsp;
  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&VAR=<?=$VAR?>&ID=<?=$ID?>&I=1&EDIT=Y">Edit</a><?}?>
  </td></tr>
</table>
<br><br>
<center>FULL GENERAL IP MATTER RECORD</center><br>
<table align="center" border=0 cellpadding=0 cellspacing=10>
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
        <td align=right width="150">
          Firm Docket No.
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$docket;?>
        </td>
    </tr>
    <tr>
      <td align="right" width="150">
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
            Firm Contact
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
            Client Contact
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$client_contact;?>
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
            Docket Date
        </td>
       <td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$docket_date;?>
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
	  <td align="right" valign="top" width="150">
	      Actions<br>
			  <?if ($user_level != "Viewer") {?><a target=_blank href="genIPMaction_edit.php?module=<?=$module;?>&GENIPM_ID=<?=$ID;?>&ACTION_ID=0&I=0&EDIT=Y">Add</a>&nbsp;&nbsp;<?}?>
	  </td><td></td>
	  <td colspan="1" valign="top" bgcolor=EEEEEE><!-- EXISTING DISC ACTIONS -->
		    <? // SQL Query for Selecting Actions Type
		    echo($ACTIONS." Actions");?>
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&GENIPMEDIT=1&ID=<?=$ID;?>&ACTIONS=All&I=1&EDIT=N">All</a> -
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&GENIPMEDIT=1&ID=<?=$ID;?>&ACTIONS=Open&I=1&EDIT=N">Open</a> -
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&GENIPMEDIT=1&ID=<?=$ID;?>&ACTIONS=Closed&I=1&EDIT=N">Closed</a><br>
		    <? if ($ACTIONS=="Open") 
		      $sql="SELECT * FROM genipm_actions WHERE
			genipm_id='$ID' and
			    done='N'";
		    elseif ($ACTIONS=="Closed")
		      $sql="SELECT * FROM genipm_actions WHERE
			genipm_id='$ID' and
			    done='Y'";
		    else
	      $sql="SELECT * FROM genipm_actions WHERE
			genipm_id='$ID'
			    ORDER BY respdue_date";
		    $result=mysql_query($sql);
		    while($row=mysql_fetch_array($result)){
		      $ACTION_ID=$row["action_ID"];
		      $action_type=$row["action_type"];
		      $respdue_date=$row["respdue_date"];
		      $description=$row["description"];?>
		      <a target=_blank href="genipmaction_edit.php?module=<?=$module;?>&GENIPM_ID=<?=$ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
			    <?=$action_type;?></a>&nbsp;Due&nbsp;<?=$respdue_date;?><br>
		    <?}?>
	  </td>
        </tr>
	<tr>
	  <td align="right" width="150">
	      Files<br>
		<?if ($user_level != "Viewer") {?><a target=_blank href="genIPMdoc_upload.php?module=<?=$module;?>&GENIPM_ID=<?=$ID;?>&id=0&I=0&EDIT=Y">Add</a><?}?>
	  </td><td></td>
	 <td colspan="1" valign="top" bgcolor=EEEEEE><!-- EXISTING DISC FILES -->
		    <?
		    echo($ATTACHEDFILES." Files");?>
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ID=<?=$ID;?>&ATTACHEDFILES=All&I=1&EDIT=N">All</a>&nbsp;<?	//All will always be default
		    $sql = "SELECT * FROM menus WHERE menu_type='GENERAL_IPM_DOCUMENT' ORDER BY menu_name";	//selecting doc names
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
			  $doctype=$row["menu_name"];
			  ?>-&nbsp;<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ID=<?=$ID;?>&ATTACHEDFILES=<?=$doctype;?>&I=1&EDIT=N"><?=$doctype;?></a>&nbsp; 
			<?}?><br></td></tr><td></td><td></td><td colspan="3" valign="top" bgcolor=EEEEEE>
		    <? if($ATTACHEDFILES=="All")
			$sql="Select * FROM genipm_documents WHERE
			      genipm_ID='$ID'
				ORDER BY doc_date";
		    else	//should only go if ATTACHEDFILES is a valid subtype
		      $sql="SELECT * FROM genipm_documents WHERE
			genipm_ID='$ID' and
			  subtype='$ATTACHEDFILES'
			    ORDER BY doc_date";
		    $result=mysql_query($sql);
		    while($row=mysql_fetch_array($result)){
		      $DOC_ID=$row["id"];
		      $subtype=$row["subtype"];
		      $docName=$row["name"];
		      $alt_name=$row["alt_name"];?>
			  <a target=_blank href="genipmdoc_upload.php?module=<?=$module;?>&GENIPM_ID=<?=$ID;?>&id=<?=$DOC_ID;?>&subtype=<?=$subtype;?>&I=1&EDIT=N">
			    <?=$subtype;?></a>&nbsp;&nbsp;
			    <a href="<?=$absolute_documents . $alt_name;?>"><?=$docName;?></a><br>
		    <?}?>
	  </td>
	</tr>
</table>
<?}?>
<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#336666" bgcolor="#336666"><tr>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Creator: <?=$creator;?> on <?=$create_date;?></small></td>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Editor: <?=$editor;?> on <?=$edit_date;?></small></td></tr>
</table>
<? html_footer(); ?>
