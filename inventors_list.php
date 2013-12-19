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
//      6/18/2013       I'm hoping that I can modify this to work with inventors of disclosures too. Otherwise, I'll have to make another one for disclosure inventors
//                      in the middle of modifying it. I'll have to use $module to check to see what is being passed. this will allow it to be easier if there are other inventor types
//      6/19/2013       for some reason it won't work on patents. Might have to start over
//                      the issue was that I was trying to pass Module instead of module, so it wouldn't recognize it.
//                      everything works fine here. Now the only issue is that it doesn't display any inventors to disclosures_edit.php.
//                      fixed. the issue was in there
//      6/21/2013       added a close button to the top right
//      7/9/2013        enabled the sort functionality. did this by slightly changing the sql code and making sure it was getting passed the correct initial value from common.php
//                      made sure the modules were being passed correctly
//                      the search broke when I updated something... fixed it by passing the variables after the submit. also added the feature that you can sort the searches. before it cleared when you sorted
//      7/12/2013       wasn't listing inventors. fixed by giving $ORDER a default value.
//                      ordered existing inventors by ID (when they were assigned)
//	7/17/2013	added $user_level to header call
//      7/30/2013       added permission level user so if you navigate you can't add/remove inventors to/from a record
//      8/28/2013       cleaned up the code a little
//inventors_list.php  --User Access Level: User
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);?>
    <table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
      <td width="100%" align="center" bgcolor="#FFFFFF">
        <a href="JavaScript:window.close()">Close</a>
      </td></tr>
    </table><br><br>
<?
if ($user_level != "Viewer") {
if ($ORDER=="") {$ORDER="last_name";}
if ($EDIT=="Y" and $PAT_ID!="0" and $DISC_ID!="0") echo("<center>ADD INVENTOR TO RECORD</center>");
  else echo("<center>LIST INVENTORS</center>"); ?><br>
<?
if ($submitok!=""){
  if($module=="Patents")
  {
    // An Inventor to add
    if ($INVENTOR_ID!=""){
      if ($EDIT=="Y" and $PAT_ID!="0") // PAT UPDATE
        $sql="INSERT INTO pat_inventors SET
          customer_ID='$customer_ID',
          pat_ID='$PAT_ID',
          inventor_ID='$INVENTOR_ID'";
          if (!mysql_query($sql))
                 error("A database error occurred in processing your ".
                  "submission.\\nIf this error persists, please ".
                  "contact your HelpDesk.");}
      
    // An Inventor to delete
    if ($INVENTOR_DELETE_ID!=""){
      if ($EDIT=="Y" and $PAT_ID!="0") // PAT UPDATE
        $sql="DELETE FROM pat_inventors WHERE ID='$INVENTOR_DELETE_ID'";
        if (!mysql_query($sql))
                 error("A database error occurred in processing your ".
                  "submission.\\nIf this error persists, please ".
                  "contact your HelpDesk.");}
  }
  
  else //module==disclosures
  {
    // An Inventor to add
    if ($INVENTOR_ID!=""){
      if ($EDIT=="Y" and $DISC_ID!="0") // PAT UPDATE
        $sql="INSERT INTO disc_inventors SET
          customer_ID='$customer_ID',
          disc_ID='$DISC_ID',
          inventor_ID='$INVENTOR_ID'";
          if (!mysql_query($sql))
                 error("A database error occurred in processing your ".
                  "submission.\\nIf this error persists, please ".
                  "contact your HelpDesk.");}
      
    // An Inventor to delete
    if ($INVENTOR_DELETE_ID!=""){
      if ($EDIT=="Y" and $DISC_ID!="0") // PAT UPDATE
        $sql="DELETE FROM disc_inventors WHERE ID='$INVENTOR_DELETE_ID'";
        if (!mysql_query($sql))
                 error("A database error occurred in processing your ".
                  "submission.\\nIf this error persists, please ".
                  "contact your HelpDesk.");}
  }
}
?>
<form method=get action="<?=$PHP_SELF;?>">
<? if ($EDIT=="Y"){?>
  <!-- EXISTING INVENTORS -->
  <table align="center" width="100%" cellpadding="5">
  <tr><td colspan="6"><small><u>EXISTING INVENTORS FOR CASE</u></small></td></tr>
  <tr bgcolor=EEEEEE><font size="-2">
    <td width="20"><U>Delete</U></td>
    <td width="125"><U>First Name</U>
    <td width="130"><U>Last Name</U>
    <td width="125"><U>Company</U>
    <td width="125"><U>Telephone</U>
    <td width="125"><U>email</U>
  </font></tr>
  
  <?
  // SQL Query for Selecting Existing Inventors
  if ($module=="Patents"){
    if ($PAT_ID!="0") // PAT FILING UPDATE
      $sql_1="SELECT * FROM pat_inventors WHERE
      customer_ID='$customer_ID' and
      pat_ID='$PAT_ID' ORDER BY ID";
    $result_1=mysql_query($sql_1);
    while($row_1=mysql_fetch_array($result_1))
    {
      $inventor_ID=$row_1["inventor_ID"];
      $pat_inventor_ID=$row_1["ID"];
      $sql_2="SELECT * FROM inventors WHERE
        ID='$inventor_ID'";
      $result_2=mysql_query($sql_2);
      // Print the records
      $row_2=mysql_fetch_array($result_2);
        $ID=$row_2["ID"];		  
        $first_name = $row_2["first_name"];		  
        $last_name = $row_2["last_name"];
        $company = $row_2["company"];
        $tel = $row_2["tel"];
        $email = $row_2["email"];
        ?>
        <tr bgcolor=EEEEEE>
          <td width="20"><small><input type="radio" name="INVENTOR_DELETE_ID" value="<?=$pat_inventor_ID;?>"></small></td>
          <td width="125"><small><?=$first_name;?></small></td>
          <td width="130"><small><a href="inventors_edit.php?module=<?=$module;?>&ID=<?=$ID;?>&I=1"><?=$last_name;?></a></small></td>
          <td width="100"><small><?=$company;?></small></td>
          <td width="100"><small><?=$tel;?></small></td>
          <td width="100"><small><?=$email;?></small></td>
        </tr>
  <?}}
  else  //module==Disclosures
  {
    if ($DISC_ID!="0") // PAT FILING UPDATE
      $sql_1="SELECT * FROM disc_inventors WHERE
      customer_ID='$customer_ID' and
      disc_ID='$DISC_ID' ORDER BY ID";
    $result_1=mysql_query($sql_1);
    while($row_1=mysql_fetch_array($result_1))
    {
      $inventor_ID=$row_1["inventor_ID"];
      $disc_inventor_ID=$row_1["ID"];
      $sql_2="SELECT * FROM inventors WHERE
        ID='$inventor_ID'";
      $result_2=mysql_query($sql_2);
      // Print the records
      $row_2=mysql_fetch_array($result_2);
        $ID=$row_2["ID"];		  
        $first_name = $row_2["first_name"];		  
        $last_name = $row_2["last_name"];
        $company = $row_2["company"];
        $tel = $row_2["tel"];
        $email = $row_2["email"];
        ?>
        <tr bgcolor=EEEEEE>
          <td width="20"><small><input type="radio" name="INVENTOR_DELETE_ID" value="<?=$disc_inventor_ID;?>"></small></td>
          <td width="125"><small><?=$first_name;?></small></td>
          <td width="130"><small><a href="inventors_edit.php?module=<?=$module;?>&ID=<?=$ID;?>&I=1"><?=$last_name;?></a></small></td>
          <td width="100"><small><?=$company;?></small></td>
          <td width="100"><small><?=$tel;?></small></td>
          <td width="100"><small><?=$email;?></small></td>
        </tr>
  <?}}?>
  </table>
<?}?>

  <input type="hidden" name="PAT_ID" value="<?=$PAT_ID?>">
  <input type="hidden" name="DISC_ID" value="<?=$DISC_ID?>">
  <input type="hidden" name="EDIT" value="<?=$EDIT?>">
  <input type="hidden" name="module" value="<?=$module?>">
  <input type="hidden" name="ORDER" value="<?=$ORDER?>"> <!--to pass the order when you submit-->
  <input type="hidden" name="LAST_NAME_SEARCH" value="<?=$LAST_NAME_SEARCH?>"> <!--to keep the search if you re-order the rows-->
  <input type="hidden" name="COMPANY_SEARCH" value="<?=$COMPANY_SEARCH?>">
  <? if ($submit_50 != "") $START=$START+50;
     else $START="0";?>
  <input type="hidden" name="START" value="<?=$START?>">
<table align="center" width="650" cellpadding="5">
  <? if ($EDIT=="Y") echo("<tr><td><small><u>POTENTIAL INVENTORS TO ADD</u></small></td></tr>"); ?>
  <tr><td>
  <small>LAST NAME&nbsp;<input type="text" name="LAST_NAME_SEARCH" maxlength="10" size="10" value="<?=$LAST_NAME_SEARCH;?>"></small>&nbsp;&nbsp;
  <small>COMPANY&nbsp;<input type="text" name="COMPANY_SEARCH" maxlength="30" size="10" value="<?=$COMPANY_SEARCH;?>"></small>&nbsp;&nbsp;
  <input type="submit" name="submit_search" value=" SEARCH ">&nbsp;&nbsp;
  <input type="submit" name="submit_50" value=" NEXT 50 ">&nbsp;&nbsp;
  <small><a href="inventors_edit.php?module=<?=$module;?>&EDIT=Y&I=0&ID=0">ADD INVENTOR</a></small>
  </td></tr>
</table>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <? if ($EDIT=="Y"){?><td width="20"><U>Add</U></td><?}?>
  <td width="125"><U>First Name</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=first_name&LAST_NAME_SEARCH=<?=$LAST_NAME_SEARCH;?>&COMPANY_SEARCH=<?=$COMPANY_SEARCH;?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=first_name DESC&LAST_NAME_SEARCH=<?=$LAST_NAME_SEARCH;?>&COMPANY_SEARCH=<?=$COMPANY_SEARCH;?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="130"><U>Last Name</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=last_name&LAST_NAME_SEARCH=<?=$LAST_NAME_SEARCH;?>&COMPANY_SEARCH=<?=$COMPANY_SEARCH;?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=last_name DESC&LAST_NAME_SEARCH=<?=$LAST_NAME_SEARCH;?>&COMPANY_SEARCH=<?=$COMPANY_SEARCH;?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="125"><U>Company</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=company&LAST_NAME_SEARCH=<?=$LAST_NAME_SEARCH;?>&COMPANY_SEARCH=<?=$COMPANY_SEARCH;?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
	<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=company DESC&LAST_NAME_SEARCH=<?=$LAST_NAME_SEARCH;?>&COMPANY_SEARCH=<?=$COMPANY_SEARCH;?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="125"><U>Telephone</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=tel&LAST_NAME_SEARCH=<?=$LAST_NAME_SEARCH;?>&COMPANY_SEARCH=<?=$COMPANY_SEARCH;?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
	<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=tel DESC&LAST_NAME_SEARCH=<?=$LAST_NAME_SEARCH;?>&COMPANY_SEARCH=<?=$COMPANY_SEARCH;?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="125"><U>email</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=email&LAST_NAME_SEARCH=<?=$LAST_NAME_SEARCH;?>&COMPANY_SEARCH=<?=$COMPANY_SEARCH;?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
	<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ORDER=email DESC&LAST_NAME_SEARCH=<?=$LAST_NAME_SEARCH;?>&COMPANY_SEARCH=<?=$COMPANY_SEARCH;?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
</font></tr>
<?

// SQL Query for Selecting All $TYPE IP Records
  $sql="SELECT * FROM inventors WHERE
  customer_ID='$customer_ID' and
  last_name LIKE '%$LAST_NAME_SEARCH%' and
  company LIKE '%$COMPANY_SEARCH%'
  ORDER BY $ORDER, last_name
  LIMIT $START, 50";

$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $ID=$row["ID"];
  $member = $row["member"];		  
  $first_name = $row["first_name"];		  
  $last_name = $row["last_name"];
  $company = $row["company"];
  $tel = $row["tel"];
  $email = $row["email"];
?>
<tr bgcolor=EEEEEE>
  <? if ($EDIT=="Y"){?><td width="20"><small><input type="radio" name="INVENTOR_ID" value="<?=$ID;?>"></small></td><?}?>
  <td width="125"><small><?=$first_name;?></small></td>
  <td width="130"><small><a href="inventors_edit.php?module=<?=$module;?>&ID=<?=$ID;?>&I=1"><?=$last_name;?></a></small></td>
  <td width="100"><small><?=$company;?></small></td>
  <td width="100"><small><?=$tel;?></small></td>
  <td width="100"><small><?=$email;?></small></td>
</tr>
<?}?>
</table>
<? if ($EDIT=="Y"){?>
<br><center>
  <input type="submit" name="submitok" value=" SUBMIT ">
</center><?}}?>
<? html_footer(); ?>
