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
//	8/28/2013	getting db error on ksms server; issue was with dates being set to nothing--the version of mysql doesn't like that
//	9/3/2013	cleaned up the code a little
// edit_tmaction.php --	User Access Level: User/Viewer
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
$sql="SELECT title, docket FROM tm_filings WHERE tm_ID='$TM_ID'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
    $title = $row["title"];
    $docket = $row["docket"];
// For new records, incoming link is set to $ACTION_ID="0" and I="0"
// Otherwise, $ACTION_ID is set to the existing record number
if ($I=="1"){
$sql="SELECT * FROM tm_actions WHERE action_ID='$ACTION_ID'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
    $TM_ID = $row["tm_ID"];
	$action_type = $row["action_type"];
	$oa_date = $row["oa_date"];
	$respdue_date = $row["respdue_date"];
	$done = $row["done"];
	$respdone_date = $row["respdone_date"];
	$description = $row["description"];
    $creator = $row["creator"];
    $create_date = $row["create_date"];
    $editor = $row["editor"];
    $edit_date = $row["edit_date"];			  
}?>
<!-- ADD OR EDIT TRADEMARK ACTIONS -->
<? if ($EDIT=="Y" and ($sysadmin=="Y" or $user_level != "Viewer")){?>
<table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">
    <? if ($TM_ID!="0" and $ACTION_ID!="0"){?>
    <a href="delete_confirm.php?module=<?=$module;?>&TABLE=tm_actions&ID=<?=$ACTION_ID;?>&NAME=Action For Docket No. <?=$docket;?>">Delete</a>&nbsp;|&nbsp;
    <?}?>
    <a href="JavaScript:window.close()">Close</a>
  </td></tr>
</table><br><br>
<? if ($ACTION_ID=="0") echo("<center>ADD ACTION</center>");
   else echo("<center>EDIT ACTION</center>");
if ($submitok=="" or $action_type=="" or ($radio_select=="" and $respdue_date=="")){
  if ($submitok!=""){?>
    <center><br><font color="red">Please update fields marked with a red asterisk.</font><br></center><?}?>
    <table align="center" width="500">
</table><br>
<form method=post action="<?=$PHP_SELF;?>">
  <input type="hidden" name="TM_ID" value="<?=$TM_ID;?>">
  <input type="hidden" name="docket" value="<?=$docket;?>">
  <input type="hidden" name="ACTION_ID" value="<?=$ACTION_ID;?>">
  <input type="hidden" name="I" value="0">
  <input type="hidden" name="EDIT" value="Y">
<table align="center" width="100%" border="0" cellpadding="0" cellspacing="10">
    <? if ($ACTION_ID!="0"){?>
	<tr>
        <td align=right width="150">
            Duplicate
        </td>
        <td width="400">
            <input type="checkbox" name="duplicate" value="Y">&nbsp;&nbsp;Saves Data as a New Record
        </td>
    </tr>
	<?}?>
  <tr>
      <td align=right width="150">
          Title
      </td>
      <td width="400" bgcolor=EEEEEE>
          <?=$title;?>
      </td>
  </tr>
  <tr>
      <td align=right width="150">
          Docket
      </td>
      <td width="400" bgcolor=EEEEEE>
          <?=$docket;?>
      </td>
  </tr>
  <tr>
      <td align=right width="150">
          Action Type
      </td>
      <td width="400">		  	  			
		  <select name="action_type" size="1">
		  <option><?=$action_type;?></option>
		  <? $sql="SELECT * FROM menus WHERE menu_type='TRADEMARK_ACTION' ORDER BY menu_name";
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
    <td align="right" width="150">
      Action Mailed
    </td>
    <td width="400">
      <input type="text" name="oa_date" size="11" maxlength="10" value="<?=$oa_date;?>">
      <small>&nbsp;(YYYY-MM-DD)</small>
    </td>
  </tr>
  <tr>
	<td align="right" width="150">
      Resp. Due
    </td>
    <td width="400">
	<input type="radio" name="radio_select" value="1">&nbsp;1 month&nbsp;&nbsp;
	<input type="radio" name="radio_select" value="3">&nbsp;3 months&nbsp;&nbsp;
	<input type="radio" name="radio_select" value="6">&nbsp;6 months&nbsp;&nbsp;OR
    </td>
  </tr>
  <tr>
	<td align="right" width="150">
    </td>
    <td width="400">
      <input type="radio" name="radio_select" value="M">Manual --&nbsp;&nbsp;
      <input type="text" name="repspdue_date" size="11" maxlength="10" value="<?=$respdue_date;?>">
      <small>&nbsp;(YYYY-MM-DD)</small>
      <font color="orangered"><TT><B>*</B></TT></font>
    </td>
  </tr>
  <tr>
	<td align="right" width="150" valign="top">
	  Completed
	</td>
    <td width="400">
      <input type="checkbox" name="done" value="Y" <? if ($done=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Action Completed
	</td>
  </tr>
  <tr>
    <td align="right" valign="top" width="150">
      Date
    </td>
    <td width="400">
      <input type="text" name="respdone_date" size="11" maxlength="10" value="<?=$respdone_date;?>">
      <small>&nbsp;(YYYY-MM-DD)</small>
    </td>
  </tr>
  <tr>
    <td align="right" width="150">
      Action Description
    </td>
    <td width="400">
      <textarea wrap name=description rows=2 cols=35><?=$description;?></textarea>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <hr noshade size="1" width="100%">
      <input type="submit" name="submitok" value="  OK  ">
    </td>
  </tr>
</table>
<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#336666" bgcolor="#336666"><tr>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Creator: <?=$creator;?> on <?=$create_date;?></small></td>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Editor: <?=$editor;?> on <?=$edit_date;?></small></td></tr>
</table>
<?}
else {
//set defaults for dates
if ($oa_date == "")	{$oa_date = "0000-00-00";}
if ($respdue_date == "") {$respdue_date = "0000-00-00";}
if ($respdone_date == "") {$respdone_date = "0000-00-00";}
// Calculate response due date if radio button is clicked
if ($radio_select!="M"){
  $tmp=explode("-", $oa_date); // $tmp[0] is the year, $tmp[1] is the month number
  if ($radio_select=="1")
    $tmp[1]=$tmp[1]+1;
  if ($radio_select=="3")
    $tmp[1]=$tmp[1]+3;
  if ($radio_select=="6")
    $tmp[1]=$tmp[1]+6;
// Adjust for year boundary
if ($tmp[1]>12){
  $tmp[1]=$tmp[1]-12;
  $tmp[0]=$tmp[0]+1;
  }
$respdue_date=$tmp[0]."-".$tmp[1]."-".$tmp[2];
}

// Make sure done is set properly
if ($done=="") $done="N";

if ($ACTION_ID=="0" or $duplicate=="Y") { // ADD NEW RECORD
  $sql="INSERT INTO tm_actions SET
    customer_ID='$customer_ID',
    tm_ID='$TM_ID',
    action_type='$action_type',
    oa_date='$oa_date',
    respdue_date='$respdue_date',
    done='$done',
    respdone_date='$respdone_date',
    description='$description',
	creator='$fullname',
	create_date='$today',
	editor='$fullname',
	edit_date='$today'";
}
	
else { // UPDATE EXISTING RECORD
  $sql="UPDATE tm_actions SET
    customer_ID='$customer_ID',
    tm_ID='$TM_ID',
    action_type='$action_type',
    oa_date='$oa_date',
    respdue_date='$respdue_date',
    done='$done',
    respdone_date='$respdone_date',
	description='$description',
	editor='$fullname',
	edit_date='$today'
	WHERE action_ID='$ACTION_ID'";
}
	
// RUN THE QUERY	
if (!mysql_query($sql)) {
  echo mysql_error();
  exit;}
?>

<!-- DONE -->
<table align="center" width="500"><tr><td><br>
<p><strong>Your record has been successfully updated.</strong></p>
<p> To return to the login page, click <a href="index.php">here</a></p>
<p> To view trademark records, click <a href="trademarks_view.php?module=<?=$module;?>&SORT=ALL">here</a></p>
<p> To add another record, click <a href="trademarks_edit.php?module=<?=$module;?>&TM_ID=<?=$TM_ID;?>&ACTION_ID=0&I=0&EDIT=Y">here</a></p>
</td></tr></table><br>
<?}}?>
<!-- READ -->
<? if ($EDIT=="N"){?>
<table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">
    <? if ($sysadmin=="Y" or $user_level != "Viewer"){?>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&TM_ID=<?=$TM_ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=Y">Edit</a>&nbsp;|&nbsp;
    <?}?>
    <a href="JavaScript:window.close()">Close</a>
  </td></tr>
</table><br><br>
<center>VIEW ACTION</center><br>
<table align="center" width="100%" border="0" cellpadding="0" cellspacing="10">
  <tr>
      <td align=right width="150">
          Title
      </td>
      <td width="400" bgcolor=EEEEEE>
          <?=$title;?>
      </td>
  </tr>
  <tr>
      <td align=right width="150">
          Docket
      </td>
      <td width="400" bgcolor=EEEEEE>
          <?=$docket;?>
      </td>
  </tr>
  <tr>
      <td align=right width="150">
          Action Type
      </td>
      <td width="400" bgcolor=EEEEEE>
          <?=$action_type;?>
      </td>
  </tr>
  <tr>
    <td align="right" width="150">
      Action Mailed
    </td>
    <td width="400" bgcolor=EEEEEE>
      <?=$oa_date;?>
    </td>
  </tr>
  <tr>
	<td align="right" width="150">
      Resp. Due
    </td>
    <td width="400" bgcolor=EEEEEE>
      <?=$respdue_date;?>
    </td>
  </tr>
  <tr>
	<td align="right" width="150" valign="top">
	  Completed
	</td>
    <td width="400" bgcolor=EEEEEE>
	  <?=$done;?>
	</td>
  </tr>
  <tr>
    <td align="right" valign="top" width="150">
      Date
    </td>
    <td width="400" bgcolor=EEEEEE>
      <?=$respdone_date;?>
    </td>
  </tr>
  <tr>
    <td align="right" width="150">
      Action Description
    </td>
    <td width="400" bgcolor=EEEEEE>
      <?=$description;?>
    </td>
  </tr>
</table>
<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#336666" bgcolor="#336666"><tr>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Creator: <?=$creator;?> on <?=$create_date;?></small></td>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Editor: <?=$editor;?> on <?=$edit_date;?></small></td></tr>
</table>
<?} html_footer(); ?>
