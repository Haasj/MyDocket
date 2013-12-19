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
//Maitnence Log Jonathan Haas:
//	6/10/2013	attempted to fix the update db function. won't even echo the query after I write it... not sure why
//			broke it. had to go back to the original at the beginning of 6/11
//	6/11/2013	doesn't even get to the update elseif. when it is "updateing," it is just running the "display" code. have to analyze to see why this is
//			fixed. the error was for some reason edit and I were being changed back once they were checked for values. see line 256. going to see if this is the issue for NDAs, TMs, etc. line 256
//			changed it so it displays the "done" code instead of the updated patent. see line 602, 848
//	6/12/2013	this randomely brok again (same issue as above), and after hours of tinkering, in which at the end i'm pretty sure i changed nothing, it now works. so this version is on the server.
//	6/19/2013	changed the forms to reflect the new master_docket system. going to begin to change it over to how disclosures_edit.php works with it.
//			done. it now reflects the docket no. as the master docket number pulled and added to docket_master.
//	6/20/2013	added link to derive patent on the top right
//	6/21/2013	changed the formatting so add new and derive from disclosure are on top. also defaulted country to us.
//	6/24/2013	added attach file structure.
//	6/26/2013	defaulted ATTACHEDFILES to All
//			updated to dynamically display document names instead of manually. also changed it so it doesn't go to edit mode when you select different lists.
//	6/27/2013	inserted meta refresh to display updated record instead of done screen.
//	7/1/2013	added the cost hyperlink
//	7/3/2013	ordered inventors by ID, so show up in order attached
//			changed so it only runs autoactions if it's new.
//	7/8/2013	added a bunch of spaces so it looks cleaner
//	7/9/2013	put a limit on the file type length. now it'll look better
//			updated querys to have PTE in them. also did calculation to calculate the expiry date-currently displays the issue date.
//			updated edit to have pte and expiry, not view yet.
//	7/10/2013	view shows pte and expiry
//			use intval to type-cast the floats after multiplication.
//			bug: dates are a little off because I divide by 30 to get the months, but some months don't have 30 days. no idea how to fix this.
//			after trying myself for far too long, I found a function that does it for me. implemented that. have to append chars on the beginning and end of the days, but it works
//			changed it so it calculates the expiry date on blank or 0000-00-00
//			put notice that it'll auto-calculate on blank
//	7/12/2013	added condition that it won't calculate the expiry date without an issue date inputted
//			added a checkbox that includes autoactions so you can run it again if it isn't new.
//			changed the date calculation to go 14 years if the type is a design
//	7/15/2013	added functionality to view patfams within the view screen.
//			commented out. that's what the view family is for... I did it here to link patents, but i'm going to now do that in the patfam_list
//	7/16/2013	allowed everyone to view cost
//			changed so you see completed date on done actions
//	7/17/2013	added $user_level to header call
//	7/18/2013	replaced all authentication to $user_level while leaving in $sysadmin because I'm pretty sure that's just the root admin.
//	7/19/2013	passed PATFAM_ID to delete_confirm to delete family if it is the last one in there.
//	7/24/2013	added add transaction button
//	7/30/2013	replaced path names with session variables
//			modified user settings so viewers can't see edit families/docs/inventors from view screen.
//	8/9/2013	ordered pat_documents by doc_date
//			allwoed special characters in data fields. info: trademarks_edit
//	8/13/2013	changed error protocol to exit/display error
//	8/15/2013	checked format of dates; removed assignment of blank docket_alt
//	8/19/2013	fixed date logic; incorrectly was using checkdate. this won't work, because of formatting. now, it will work, but throw the mysql_error if the date is invalid. works with ""
//	8/20/2013	added a check for zero on the pte stuff. it's casue this version of mysql doesn't like null values, even when you set them to allow null. easy check, even though I shouldn't have to
//	8/21/2013	calculate expiry date stopped working for some reason locally and on the ksmsweb server. no idea why. tested the old code, and it doesn't work either.
//	8/23/2013	got expiry_date calculation to work on the server. you need to create a new timezone for some reason with the newer version of php since it doesn't auto-get it from php.ini like apache
//	8/29/2013	cleaned up the code a little
//	8/29/2013	replaced server_docs with abs_docs
// patents_edit.php -- 	User Access Level: User/Viewer
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
// Set $var string
$var="DOCKET=$DOCKET&TITLE=$TITLE";
// Set Defaults
if ($NEXT=="1") $START=$START+50;
  else $START="0";

if ($ATTACHEDFILES==""){$ATTACHEDFILES="All";}
?>
<!-- SELECT RELATED PATENT -->
<?
if ($PATFAM=="1") {
  if ($submit_1=="" or $PAT_ID==""){?>
  <table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
    <td width="100%" align="center" bgcolor="#FFFFFF">
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&PATFAM=1&PATEDIT=0&NEXT=0&START=<?=$START;?>&<?=$var;?>">First 50</a>&nbsp;|&nbsp;
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&PATFAM=1&PATEDIT=0&NEXT=1&START=<?=$START;?>&<?=$var;?>">Next 50</a>
    </td></tr>
  </table><br><br> 
  <center><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=0&I=2&EDIT=Y&NEW=Y&country=US">ADD NEW PATENT</a><br><br>
	<a href="derive_patent.php?module=<?=$module;?>">DERIVE PATENT FROM DISCLOSURE</a></center><br><br>
  <center>DERIVE PATENT FROM PATENT -- SELECT RELATED PATENT</center>
  <form method=get action="<?=$PHP_SELF;?>">
    <input type="hidden" name="module" value="<?=$module;?>">
    <input type="hidden" name="PATFAM" value="1">
    <input type="hidden" name="PATEDIT" value="0">
    <input type="hidden" name="I" value="0">
    <input type="hidden" name="EDIT" value="Y">
  <table align="center" width="100%" border="0" cellpadding="5" cellspacing="2">
    <tr>
      <td align="right" colspan="4">
	    <small>DOCKET&nbsp;<input type="text" name="DOCKET" maxlength="10" size="10" value="<?=$DOCKET;?>"></small>&nbsp;&nbsp;
	<small>TITLE&nbsp;<input type="text" name="TITLE" maxlength="30" size="10" value="<?=$TITLE;?>"></small>&nbsp;&nbsp;
	<input type="submit" name="submit_search" value=" SEARCH ">&nbsp;&nbsp;
	<input type="submit" name="submit_50" value=" NEXT 50 ">&nbsp;&nbsp;</td>
    </tr>
    <tr bgcolor=EEEEEE>
      <td width="20"><small>SELECT</small></td>
      <td width="80"><small>ECS DOCKET</small></td>
      <td width="480"><small>TITLE</small></td>
    </tr>
    <?
      $sql="SELECT pat_ID, docket_alt, title FROM pat_filings WHERE
	customer_ID='$customer_ID' and
	    docket_alt LIKE '%$DOCKET%' and
	title LIKE '%$TITLE%' and
	    original='Y'	
	    ORDER BY docket_alt LIMIT $START, 50";
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$PAT_ID=$row["pat_ID"];
	$docket_alt=$row["docket_alt"];
	$title=$row["title"];
      ?>
      </td></tr>
      <tr bgcolor=EEEEEE>
	<td width="20"><input type="radio" name="PAT_ID" value="<?=$PAT_ID;?>"></td>
	<td width="100"><small><?=$docket_alt;?></small></td>
	<td width="430"><small><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&PATEDIT=1&PAT_ID=<?=$PAT_ID;?>&I=1&EDIT=N"><?=$title;?></small></td>
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
  $sql="SELECT * FROM pat_filings WHERE pat_ID='$PAT_ID'";
  $result=mysql_query($sql);
  $row=mysql_fetch_array($result);
    $PATFAM_ID=$row["patfam_ID"];
    $docket=$row["docket"];
    $title=$row["title"];
    $firm=$row["firm"];
    $firm_contact=$row["firm_contact"];
    $client=$row["client"];
    $client_contact=$row["client_contact"];
    $filing_type=$row["filing_type"];
    $priority_date=$row["priority_date"];
    $abstract=$row["abstract"];
    $products=$row["products"];
    
  // Create the record that will be edited
  $sql="INSERT INTO pat_filings SET
	  customer_ID='$customer_ID',
      org='$userorg',
      patfam_ID='$PATFAM_ID',
      original='N',
      docket='$docket',
      title='$title',
	  firm='$firm',
	  firm_contact='$firm_contact',
      client='$client',
      client_contact='$client_contact',
	  filing_type='$filing_type',
      priority_date='$priority_date',	
      abstract='$abstract',
      products='$products',
	  creator='$fullname',
	  create_date='$today',
	  editor='$fullname',
	  edit_date='$today'";
  if (!mysql_query($sql)){
  echo mysql_error();
   exit;}
    
  // Get the ID of the new pat_filings record
  $PAT_ID_NEW=mysql_insert_id();
  // Copy the inventors to the new application
  $sql_1="SELECT * FROM pat_inventors WHERE pat_ID='$PAT_ID'";
  $result_1=mysql_query($sql_1);
  while ($row_1=mysql_fetch_array($result_1))
  {
      $inventor_ID=$row_1["inventor_ID"];
    $sql_2="INSERT INTO pat_inventors SET
      customer_ID='$customer_ID',
      pat_ID='$PAT_ID_NEW',
      inventor_ID='$inventor_ID'";
    if (!mysql_query($sql_2)){
  echo mysql_error();
   exit;}
  }
  
  $sql_3="INSERT INTO docket_master SET
	  type='patent',
	  ID='$PAT_ID_NEW'";
  if (!mysql_query($sql_3))
  error("db error into master");
  $docket_alt_new=mysql_insert_id();
  
  $sql_4="UPDATE pat_filings SET
	  docket_alt='$docket_alt_new'
	  WHERE pat_ID='$PAT_ID_NEW'";

    if (!mysql_query($sql_4)){
  echo mysql_error();
   exit;}

  // Move on to adding the patent filing record
  $PATEDIT="1";
  $I="1";
  $PAT_ID=$PAT_ID_NEW;
  $NEW="Y";
  $docket_alt=$docket_alt_new;
  }
}?>

<!-- ADD OR EDIT PATENT RECORD -->
<?
if ($PATEDIT=="1") {
// If it's a brand new case ($I=="2"), we need to set up a patent family number, 
// patent number and identify the new patent as an original
if ($I=="2") {
  $sql = "INSERT INTO pat_families SET
    customer_ID = '$customer_ID',
    org = '$userorg'";  
	if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact info@ipdox.com.");
  $PATFAM_ID=mysql_insert_id();
  $sql = "INSERT INTO pat_filings SET
    customer_ID = '$customer_ID',
	patfam_ID = '$PATFAM_ID',
    original='Y',
    org = '$userorg',
	creator='$fullname',
	create_date='$today',
	editor='$fullname',
	edit_date='$today'";  
	if (!mysql_query($sql)){
  echo mysql_error();
   exit;}

  $PAT_ID=mysql_insert_id();
  $original="Y";
  
  $sql = "INSERT INTO docket_master SET
  type='patent',
  ID='$PAT_ID'";
  if (!mysql_query($sql))
     error("A database error occurred in processing your ".
     "submission.\\nIf this error persists, please ".
     "contact info@ipdox.com.");
 $docket_alt=mysql_insert_id();
  }

// To retrieve data, $PAT_ID is set to the existing record number
// SQL Query for an existing NDA record and retrieving data
if ($I=="1"){
$sql="SELECT * FROM pat_filings WHERE pat_ID='$PAT_ID'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
    $PATFAM_ID = $row["patfam_ID"];
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
    $inventors = $row["inventors"];
	$filing_type = $row["filing_type"];
	$country = $row["country"];
	$status = $row["status"];
    $priority_date = $row["priority_date"];
    $priority_date_1 = $row["priority_date"];
    $filing_date = $row["filing_date"];
    $filing_date_1 = $row["filing_date"];
    $expiry_date = $row["expiry_date"];
    $PTE_154 = $row["PTE_154"];
    $PTE_156 = $row["PTE_156"];
    $filing_receipt = $row["filing_receipt"];
    $assignment = $row["assignment"];
    $assignment_recorded = $row["assignment_recorded"];
    $ids = $row["ids"];
    $no_pub = $row["no_pub"];
    $pub_no = $row["pub_no"];
    $pub_date = $row["pub_date"];
	$issue_date = $row["issue_date"];
	$issue_date_1 = $row["issue_date"];
    $ser_no = $row["ser_no"];
	$pat_no = $row["pat_no"];
	$abstract = $row["abstract"];
	$products = $row["products"];
    $notes=$row["notes"];			  
}

if (($sysadmin=="Y" or $user_level != "Viewer") and $read_only=="N" and $EDIT=="Y")
{?>
  <table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
    <td width="100%" align="center" bgcolor="#FFFFFF">
      <a href="patents_list.php?module=<?=$module;?>&SORT=PATFAM&PATFAM_ID=<?=$PATFAM_ID;?>">View Family</a>
      <? // The ability to delete a record only if there is an existing record ($PAT_ID!="0") --and cost
      if ($PAT_ID!="0"){?>&nbsp;|&nbsp;
      <a href="accounts_edit.php?module=<?=$module;?>&ecs_docket=<?=$docket_alt;?>&firm_docket=<?=$docket;?>&I=0">Add Transaction</a>&nbsp;|&nbsp;
      <a href="total_cost.php?module=<?=$module;?>&ID=<?=$docket_alt;?>">Cost</a>&nbsp;|&nbsp;        
      <a href="delete_confirm.php?module=<?=$module;?>&TABLE=pat_filings&ID=<?=$PAT_ID;?>&NAME=<?=$docket_alt;?>&FAM_ID=<?=$PATFAM_ID;?>">Delete</a>
      <?}?></td></tr>
  </table><br><br>
  <? if ($NEW=="Y") echo("<center>ADD PATENT RECORD</center>");
    else echo("<center>EDIT PATENT RECORD</center>");
  if ($submit_2=="" or $docket=="" or $firm=="" or $title=="" or $filing_type=="" or $status=="" or $country==""){
    if ($submit_2!=""){?>
    <center><br><font color="red">Please update fields marked with a red asterisk.</font><br></center><?}?>
    <form method=get action="<?=$PHP_SELF;?>">
    <input type="hidden" name="module" value="<?=$module;?>">
    <input type="hidden" name="PATFAM" value="0">
    <input type="hidden" name="PATEDIT" value="1">
    <input type="hidden" name="PATFAM_ID" value="<?=$PATFAM_ID;?>">
    <input type="hidden" name="PAT_ID" value="<?=$PAT_ID;?>">
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
	      <font color="orangered"><TT><B>*</B></TT></font>
	  </td>
      </tr>
      <tr>
	  <td align="right" width="115">
	      Firm Docket No.
	  </td>
	  <td></td>
	  <td colspan="1" align="left" width="450">
	      <input name="docket" type="text" maxlength="200" size="60" value="<?=$docket;?>">
	      <font color="orangered"><TT><B>*</B></TT></font>
	  </td>
      </tr>
      <tr>
	  <td align="right" width="115">
	   ECS Docket No.
	  </td>
	  <td></td>
	  <td width="205">
	    <?=$docket_alt;?>
	  </td>
	  <td align="right" width="115">
	      Status
	  </td>
	  <td></td>
	  <td width="205">			
			  <select name="status" size="1">
			  <option><?=$status;?></option>
		      <? $sql="SELECT * FROM menus WHERE menu_type='PATENT_STATUS' ORDER BY menu_name";
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
	  <td align="right" width="115">
	      Service Firm
	  </td>
	  <td></td>
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
	      <font color="orangered"><TT><B>*</B></TT></font>
	  </td>
	  <td align="right" width="115">
	      Firm Contact
	  </td>
	  <td></td>
	  <td width="205">
	      <input name="firm_contact" type="text" maxlength="100" size="25" value="<?=$firm_contact;?>">
	  </td>
      </tr>
      <tr>
	  <td align="right" width="115">
	      Client
	  </td>
	  <td></td>
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
	      <font color="orangered"><TT><B>*</B></TT></font>
	  </td>
	  <td align="right" width="115">
	      Client Contact
	  </td>
	  <td></td>
	  <td width="205">
	      <input name="client_contact" type="text" maxlength="100" size="25" value="<?=$client_contact;?>">
	  </td>
      </tr>
      </tr>
      <tr>
	  <td align="right" width="115">
	      Type
	  </td>
	  <td></td>
	  <td width="205">			
			  <select name="filing_type" size="1">
			  <option><?=$filing_type;?></option>
		      <? $sql="SELECT * FROM menus WHERE menu_type='PATENT_TYPE' ORDER BY menu_name";
			  $result=mysql_query($sql);
			  while($row=mysql_fetch_array($result)){
		$menu_name=$row["menu_name"];?>
		<option><?=$menu_name?></option>"
			  <?}?>
	      </select>
	      <font color="orangered"><TT><B>*</B></TT></font>
			  &nbsp;&nbsp;<a target="_blank" href="help.php#glossary">Glossary</a>
	  </td>
	  <td align="right" width="115">
	      Country
	  </td>
	  <td></td>
	  <td width="205">
	      <select name=country size="1">
	      <option><?=$country;?></option>
			  <? country_list();?>
	      </select>
	      <font color="orangered"><TT><B>*</B></TT></font>
			  &nbsp;&nbsp;<a target="_blank" href="help.php#country_codes">Country Codes</a>
	  </td>
	  </tr>
	  <tr>
	  <td align="right" width="115">
	      Priority Date
	  </td>
	  <td></td>
	  <td width="205">
	      <input type=text name="priority_date" size="11" maxlength="10" value="<?=$priority_date;?>">
			  <small>&nbsp;(YYYY-MM-DD)</small>
	  </td>
	  <td align="right" width="115">
	      Original
	  </td>
	  <td></td>
	  <td width="205">
	      <select name=original size="1">
	      <option><?=$original;?></option>
			  <option>Y</option><option>N</option>
	      </select>
	      <font color="orangered"><TT><B>*</B></TT></font>
			  &nbsp;&nbsp;<a target="_blank" href="help.php#glossary">Glossary</a>
	  </td>
	  </tr>
	  <tr>
	  <td align="right" width="115">
	      Filing Date
	  </td>
	  <td></td>
	  <td width="205">
	      <input type="text" name="filing_date" size="11" maxlength="10" value="<?=$filing_date;?>">
			  <small>&nbsp;(YYYY-MM-DD)</small>
	  </td>
	  <td align="right" width="115">
	      Serial No.
	  </td>
	  <td></td>
	  <td width="205">
	      <input name="ser_no" type="text" maxlength="25" size="25" value="<?=$ser_no;?>">
	  </td>	
	  </tr>
	  <tr>
	  <td align="right" width="115">
	      Publ. Date
	  </td>
	  <td></td>
	  <td width="205">
	      <input type="text" name="pub_date" size="11" maxlength="10" value="<?=$pub_date;?>">
			  <small>&nbsp;(YYYY-MM-DD)</small>
	  </td>
	  <td align="right" width="115">
	      Publ. No.
	  </td>
	  <td></td>
	  <td width="205">
	      <input name="pub_no" type="text" maxlength="25" size="25" value="<?=$pub_no;?>">
	  </td>
	  </tr>
	  <tr>
	  <td align="right" width="115">
	      Issue Date
	  </td>
	  <td></td>
	  <td width="205">
	      <input type="text" name="issue_date" size="11" maxlength="10" value="<?=$issue_date;?>">
			  <small>&nbsp;(YYYY-MM-DD)</small>
	  </td>
	  <td align="right" width="115">
	      Pat. No.
	  </td>
	  <td></td>
	  <td width="205">
	      <input name="pat_no" type="text" maxlength="25" size="25" value="<?=$pat_no;?>">
	  </td>
	  </tr>
	  <tr>
	    <td align="right" width="115">
	      Expiration Date
	    </td>
	    <td></td>
	    <td width="400" colspan="3">
	      <input type="text" name="expiry_date" size="11" maxlength="10" value="<?=$expiry_date;?>">
			  <small>&nbsp;(YYYY-MM-DD)&nbsp;<font color="orangered">Leave field blank for auto-calculation</font></small>
	    </td>
	  </tr>
	  <tr>
	    <td align="right" width="115">
	      154 PTE
	    </td>
	    <td></td>
	    <td width="205">
	    <input name="PTE_154" type="text" maxlength="25" size="25" value="<?=$PTE_154;?>">
	    </td>
	    <td align="right" width="115">
	      156 PTE
	    </td>
	    <td></td>
	    <td width="205">
	    <input name="PTE_156" type="text" maxlength="25" size="25" value="<?=$PTE_156;?>">
	    </td>
	  </tr>
      <tr>
	  <td align="right" valign="top" width="115">
		Related<br>Documents
	      </td>
	  <td></td>
	  <td width="205">
	    <input type="checkbox" name="filing_receipt" value="Y" <? if ($filing_receipt=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Filing Receipt<br>
	    <input type="checkbox" name="assignment" value="Y" <? if ($assignment=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Assignment<br>
	    <input type="checkbox" name="assignment_recorded" value="Y" <? if ($assignment_recorded=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Recorded Assignment<br>
	    <input type="checkbox" name="ids" value="Y" <? if ($ids=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Information Disclosure<br>
	    <input type="checkbox" name="no_pub" value="Y" <? if ($no_pub=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;No Publication Request
	      </td>
	  <td align="right" valign="top" width="115">
	      Inventors<br>
			  <a target=_blank href="inventors_list.php?module=<?=$module;?>&PAT_ID=<?=$PAT_ID;?>&I=1&EDIT=Y">Edit</a>&nbsp;&nbsp;
	  </td>
	  <td></td>
	  <td width="205" valign="top"><!-- EXISTING INVENTORS -->
		    <? // SQL Query for Selecting All $TYPE IP Records
		    $sql_1="SELECT * FROM pat_inventors WHERE
		      customer_ID='$customer_ID' and
		      pat_ID='$PAT_ID' ORDER BY ID";
		    $result_1=mysql_query($sql_1);
		    while($row_1=mysql_fetch_array($result_1)){
		      $inventor_ID=$row_1["inventor_ID"];
		      $sql_2="SELECT * FROM inventors WHERE
			ID='$inventor_ID'";
		      $result_2=mysql_query($sql_2);
		      // Print the records
			  $row_2=mysql_fetch_array($result_2);
			  $ID=$row_2["ID"];
			  $first_name = $row_2["first_name"];
			  $middle_name = $row_2["middle_name"];
			  $middle_initial = substr($middle_name,0,1);  
			  $last_name = $row_2["last_name"];?>
			  <a target=_blank href="inventors_edit.php?module=<?=$module;?>&ID=<?=$ID;?>&I=1&EDIT=N"><?=$first_name;?>&nbsp;<?=$middle_initial;?>.&nbsp;<?=$last_name;?></a><br>
		    <?}?>
	  </td>
	  </tr>
	  <tr>
	  <td align="right" valign="top" width="115">
	      Abstract
	  </td>
	  <td></td>
	  <td colspan="4">
	      <textarea wrap name="abstract" rows="3" cols="50"><?=$abstract;?></textarea>
	  </td>
	  </tr>
	  <tr>
	  <td align="right" valign="top" width="115">
	      Products
	  </td>
	  <td></td>
	  <td colspan="4">
	      <textarea wrap name="products" rows="2" cols="50"><?=$products;?></textarea>
	  </td>
	  </tr>	
	  <tr>
	  <td align="right" valign="top" width="115">
	      Notes
	  </td>
	  <td></td>
	  <td colspan="4">
	      <textarea wrap name="notes" rows="2" cols="50"><?=$notes;?></textarea>
	  </td>
	  </tr>
      <tr>
	  <td align="right" valign="top" width="115">
	      Actions<br>
			  <a target=_blank href="pataction_edit.php?module=<?=$module;?>&PAT_ID=<?=$PAT_ID;?>&ACTION_ID=0&I=0&EDIT=Y">Add</a>&nbsp;&nbsp;<br>
			  <input type="checkbox" name="re_run" value="Y" <? if ($filing_receipt=="Y") echo ("checked");?>>Re-Run<br>
	  </td>
	  <td></td>
	  <td colspan="1" valign="top"><!-- EXISTING PAT ACTIONS -->
		    <? // SQL Query for Selecting Actions Type
		    echo($ACTIONS." Actions");?>
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$PAT_ID;?>&ACTIONS=All&I=1&EDIT=Y">All</a> -
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$PAT_ID;?>&ACTIONS=Open&I=1&EDIT=Y">Open</a> -
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$PAT_ID;?>&ACTIONS=Closed&I=1&EDIT=Y">Closed</a><br>
		    <? if ($ACTIONS=="Open") 
		      $sql="SELECT * FROM pat_actions WHERE
			pat_ID='$PAT_ID' and
			    done='N'";
		    elseif ($ACTIONS=="Closed")
		      $sql="SELECT * FROM pat_actions WHERE
			pat_ID='$PAT_ID' and
			    done='Y'";
		    else
	      $sql="SELECT * FROM pat_actions WHERE
			pat_ID='$PAT_ID'
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
		    <a href="delete_confirm.php?module=<?=$module;?>&TABLE=pat_actions&ID=<?=$ACTION_ID;?>&NAME=Action For Docket No. <?=$docket_alt;?>"><font color="red"><b>X</b></font></a>&nbsp;
			<a target=_blank href="pataction_edit.php?module=<?=$module;?>&PAT_ID=<?=$PAT_ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
			  <?=$action_type;?></a>&nbsp;Due&nbsp;<?=$respdue_date;?><br> <?}
		    else {	//done
		      ?><a href="delete_confirm.php?module=<?=$module;?>&TABLE=pat_actions&ID=<?=$ACTION_ID;?>&NAME=Action For Docket No. <?=$docket_alt;?>"><font color="red"><b>X</b></font></a>&nbsp;
		      <a target=_blank href="pataction_edit.php?module=<?=$module;?>&PAT_ID=<?=$PAT_ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
			  <?=$action_type;?></a>&nbsp;Completed&nbsp;<?=$respdone_date;?><br>
		  <?}}?>
	  </td>
	  <td align="right" valign="top" width="115">
	      Files<br>
		<a target=_blank href="patdoc_upload.php?module=<?=$module;?>&PAT_ID=<?=$PAT_ID;?>&id=0&I=0&EDIT=Y">Add</a>
	  </td><td></td>
	 <td colspan="1" valign="top" width="400"><!-- EXISTING DISC FILES -->
		    &nbsp;&nbsp;<?
		    echo($ATTACHEDFILES." Files");?>
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$PAT_ID;?>&ATTACHEDFILES=All&I=1&EDIT=Y">All</a>&nbsp;<?	//All will always be default
		    $sql = "SELECT * FROM menus WHERE menu_type='PATENT_DOCUMENT' ORDER BY menu_name";	//selecting doc names
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
			  $doctype=$row["menu_name"];
			  ?>-&nbsp;<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$PAT_ID;?>&ATTACHEDFILES=<?=$doctype;?>&I=1&EDIT=Y"><?=$doctype;?></a>&nbsp; 
			<?}?><br>
		    <? if($ATTACHEDFILES=="All")
			$sql="Select * FROM pat_documents WHERE
			      pat_ID='$PAT_ID'
				ORDER BY doc_date";
		    else	//should only go if ATTACHEDFILES is a valid subtype
		      $sql="SELECT * FROM pat_documents WHERE
			pat_id='$PAT_ID' and
			  subtype='$ATTACHEDFILES'
			    ORDER BY doc_date";
		    $result=mysql_query($sql);
		    while($row=mysql_fetch_array($result)){
		      $DOC_ID=$row["id"];
		      $subtype=$row["subtype"];
		      $docName=$row["name"];
		      $alt_name=$row["alt_name"];?>
			  &nbsp;&nbsp;<a href="delete_confirm.php?module=<?=$module;?>&TABLE=pat_documents&ID=<?=$DOC_ID;?>&NAME=Document For Docket No. <?=$docket_alt;?>&alt_name=<?=$alt_name;?>"><font color="red"><B>X</B></font></a>&nbsp
			  <a target=_blank href="patdoc_upload.php?module=<?=$module;?>&PAT_ID=<?=$PAT_ID;?>&id=<?=$DOC_ID;?>&subtype=<?=$subtype;?>&I=1&EDIT=N">
			    <?=$subtype;?></a>&nbsp;&nbsp;
			    <a href="<?=$absolute_documents.$alt_name;?>"><?=$docName;?></a><br>
		    <?}?>
	  </td>
      </tr>
      <tr>
	<td align="right" valign="top" width="115">
	      Attached Disclosures<br>
		<a target=_blank href="link_disclosure.php?pat_ID=<?=$PAT_ID;?>">Add</a>
	  </td>
	<td></td>
	 <td colspan="1" valign="top"><!-- EXISTING PAT DISCLOSURES -->
		    <? 
	      $sql="SELECT * FROM pat_disclosures WHERE
			pat_ID='$PAT_ID'
			    ORDER BY disc_ID";
		    $result=mysql_query($sql);
		    while($row=mysql_fetch_array($result)){
		      $attached_ID=$row["ID"];
		      $disc_ID=$row["pat_ID"];
		      $disc_docket_alt=$row["disc_docket_alt"];
		      $disc_name=$row["disc_name"];?>
		      <a href="delete_confirm.php?module=<?=$module;?>&TABLE=pat_disclosures&ID=<?=$attached_ID;?>&NAME=Disclosure For Docket No. <?=$docket_alt;?>&alt_name=<?=$alt_name;?>"><font color="red"><B>X</B></font></a>&nbsp
			  <?=$disc_docket_alt;?>&nbsp;&nbsp;
			  <a target=_blank href="disclosures_edit.php?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=<?=$disc_ID;?>&ACTIONS=Open&I=1&EDIT=N"><?=$disc_name;?></a><br>
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
    //check to make sure data is valid
    if($expiry_date == "")
      {$expiry_date = "0000-00-00";}
    if($issue_date == "")
      {$issue_date = "0000-00-00";}
    if($priority_date == "")
      {$priority_date = "0000-00-00";}
    if($filing_date == "")
      {$filing_date = "0000-00-00";}
    if($pub_date == "")
      {$pub_date = "0000-00-00";}
    if($PTE_154 == "")
      {$PTE_154 = 0;}
    if($PTE_156 == "")
      {$PTE_156 = 0;}
    //calculate expiration date
    if($expiry_date == "0000-00-00" and $issue_date != "0000-00-00")
    {
      $total_days = $PTE_154 + $PTE_156;	//total days to be added
      $daystring = "P".$total_days."D";		//to get the correct P*D format for dateinterval
      if ($filing_type == "Design") {$yearstring = "P14Y";}
      else {$yearstring = "P20Y";}		//20 years if not design
      $date = new DateTime($filing_date, new DateTimeZone('America/Los_Angeles'));	//create a new date object
      $date->add(new DateInterval($yearstring));	//add 20 years
      $date->add(new DateInterval($daystring)); //add the days
      $expiry_date=$date->format('Y-m-d') . "\n"; //convert to string
    }
    // enable apostrophe's in data fields by using escape_string to insert a '/' before every special character as defined in mysql
      $title = mysql_real_escape_string($title);
      $docket = mysql_real_escape_string($docket);
      $firm_contact = mysql_real_escape_string($firm_contact);
      $client_contact = mysql_real_escape_string($client_contact);
      $abstract = mysql_real_escape_string($abstract);
      $products = mysql_real_escape_string($products);
      $notes = mysql_real_escape_string($notes);
      $priority_date = mysql_real_escape_string($priority_date);
      $filing_date = mysql_real_escape_string($filing_date);
      $issue_date = mysql_real_escape_string($issue_date);
      $ser_no = mysql_real_escape_string($ser_no);
      $pub_date = mysql_real_escape_string($pub_date);
      $pub_no = mysql_real_escape_string($pub_no);
      $issue_date = mysql_real_escape_string($issue_date);
      $pat_no = mysql_real_escape_string($pat_no);
      $expiry_date = mysql_real_escape_string($expiry_date);
      $PTE_154 = mysql_real_escape_string($PTE_154);
      $PTE_156 = mysql_real_escape_string($PTE_156);
    // The script performs the database modification
    $sql = "UPDATE pat_filings SET
	customer_ID = '$customer_ID',
	patfam_ID = '$PATFAM_ID',
	original = '$original',
	org = '$userorg',
	docket = '$docket',
	docket_alt = '$docket_alt',
	firm = '$firm',
	firm_contact = '$firm_contact',
	client = '$client',
	client_contact = '$client_contact',
	title = '$title',
	inventors = '$inventors',
	filing_type = '$filing_type',
	country = '$country',
	status = '$status',
	priority_date = '$priority_date',
	filing_date = '$filing_date',
	filing_receipt = '$filing_receipt',
	assignment = '$assignment',
	assignment_recorded = '$assignment_recorded',
	ids = '$ids',
	no_pub = '$no_pub',
	pub_date = '$pub_date',
	issue_date = '$issue_date',
	expiry_date = '$expiry_date',
	PTE_154 = '$PTE_154',
	PTE_156 = '$PTE_156',
	ser_no = '$ser_no',
	pub_no = '$pub_no',
	pat_no = '$pat_no',
	abstract = '$abstract',
	products = '$products',
	notes = '$notes',
	    editor='$fullname',
	    edit_date='$today'
	WHERE pat_ID='$PAT_ID'";
    // RUN THE QUERY
    echo $re_run;
      if (!mysql_query($sql)){
  echo mysql_error();
   exit;}

    // Autodocket if it's a first case
      if($NEW=="Y" or $re_run=="Y"){
	include("autoactions.php");
	}

     $DONE="1";
  }}

else{ // DISPLAY RECORD -- NOT EDIT
?>
<!-- DISPLAY PATENT -->
<table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">
    <a href="patents_list.php?module=<?=$module;?>&SORT=PATFAM&PATFAM_ID=<?=$PATFAM_ID;?>">View Family</a>&nbsp;|&nbsp;
    <a href="total_cost.php?module=<?=$module;?>&ID=<?=$docket_alt;?>">Cost</a>    
    <? if ($sysadmin=="Y" or $user_level != "Viewer"){?>&nbsp;|&nbsp;
    <a href="accounts_edit.php?module=<?=$module;?>&ecs_docket=<?=$docket_alt;?>&firm_docket=<?=$docket;?>&I=0">Add Transaction</a>&nbsp;|&nbsp;
  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$PAT_ID?>&ACTIONS=Open&I=1&EDIT=Y&SORT=<?=$SORT;?>&VAR=<?=$VAR?>">Edit</a>
  <?}?></td></tr>
</table><br><br>
<center>FULL PATENT RECORD</center><br>
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
	<td></td>
	<td colspan="4" align="left" width="450" bgcolor="#EEEEEE">
            <?=$docket;?>
        </td>
    </tr>
    <tr>
      <td align="right" width="115">
	ECS Docket No.
      </td>
      <td></td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$docket_alt;?>
        </td>
        <td align="right" width="115">
            Status
        </td>
	<td></td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$status;?>
        </td>
    </tr>
    <tr>
        <td align="right" width="115">
            Service Firm
        </td>
	<td></td>
        <td width="205" bgcolor="#EEEEEE">
			<?=$firm;?>
        </td>
        <td align="right" width="115">
            Firm Contact
        </td>
	<td></td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$firm_contact;?>
        </td>
    </tr>
    <tr>
        <td align="right" width="115">
            Client
        </td>
	<td></td>
        <td width="205" bgcolor="#EEEEEE">
			<?=$client;?>
        </td>
        <td align="right" width="115">
            Client Contact
        </td>
	<td></td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$client_contact;?>
        </td>
    </tr>
    <tr>
        <td align="right" width="115">
            Type
        </td>
	<td></td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$filing_type;?>
			&nbsp;&nbsp;<a target="_blank" href="help.php#glossary">Glossary</a>
        </td>
        <td align="right" width="115">
            Country
        </td>
	<td></td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$country;?>
			&nbsp;&nbsp;<a target="_blank" href="help.php#country_codes">Country Codes</a>
        </td>
	</tr>
	<tr>
        <td align="right" width="115">
            Priority Date
        </td>
	<td></td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$priority_date;?>
        </td>
        <td align="right" width="115">
            Original
        </td>
	<td></td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$original;?>
			&nbsp;&nbsp;<a target="_blank" href="help.php#glossary">Glossary</a>
        </td>
	</tr>
	<tr>
        <td align="right" width="115">
            Filing Date
        </td>
	<td></td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$filing_date;?>
        </td>
        <td align="right" width="115">
            Serial No.
        </td>
	<td></td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$ser_no;?>
        </td>	
	</tr>
	<tr>
        <td align="right" width="115">
            Publ. Date
        </td>
	<td></td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$pub_date;?>
        </td>
        <td align="right" width="115">
            Publ. No.
        </td>
	<td></td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$pub_no;?>
        </td>
	</tr>
	<tr>
        <td align="right" width="115">
            Issue Date
        </td>
	<td></td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$issue_date;?>
        </td>
        <td align="right" width="115">
            Pat. No.
        </td>
	<td></td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$pat_no;?>
        </td>
	</tr>
	<tr>
	    <td align="right" width="115">
	      Expiration Date
	    </td>
	    <td></td>
	    <td width="205" bgcolor=EEEEEE>
	      <?=$expiry_date;?>
	    </td>
	  </tr>
	  <tr>
	    <td align="right" width="115">
	      154 PTE
	    </td>
	    <td></td>
	    <td width="205" bgcolor=EEEEEE>
	    <?=$PTE_154;?>
	    </td>
	    <td align="right" width="115">
	      156 PTE
	    </td>
	    <td></td>
	    <td width="205" bgcolor=EEEEEE>
	    <?=$PTE_156;?>
	    </td>
	  </tr>
    <tr>
        <td align="right" valign="top" width="115">
	      Related<br>Documents
	    </td><td></td>
        <td width="205" bgcolor="#EEEEEE">
          <input type="checkbox" name="filing_receipt" value="Y" <? if ($filing_receipt=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Filing Receipt<br>
          <input type="checkbox" name="assignment" value="Y" <? if ($assignment=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Assignment<br>
          <input type="checkbox" name="assignment_recorded" value="Y" <? if ($assignment_recorded=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Recorded Assignment<br>
          <input type="checkbox" name="ids" value="Y" <? if ($ids=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Information Disclosure<br>
          <input type="checkbox" name="no_pub" value="Y" <? if ($no_pub=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;No Publication Request
	    </td>
        <td align="right" valign="top" width="115">
            Inventors			
			<?if ($user_level != "Viewer") {?>
			<br><a target=_blank href="inventors_list.php?module=<?=$module;?>&PAT_ID=<?=$PAT_ID;?>&I=1&EDIT=Y">Edit</a>&nbsp;&nbsp;<?}?>
        </td><td></td>
        <td width="205" valign="top" bgcolor="#EEEEEE"><!-- EXISTING INVENTORS -->
		  <? // SQL Query for Selecting All $TYPE IP Records
		  $sql_1="SELECT * FROM pat_inventors WHERE
		    customer_ID='$customer_ID' and
		    pat_ID='$PAT_ID' ORDER BY ID";
		  $result_1=mysql_query($sql_1);
		  while($row_1=mysql_fetch_array($result_1)){
		    $inventor_ID=$row_1["inventor_ID"];
		    $sql_2="SELECT * FROM inventors WHERE
		      ID='$inventor_ID'";
		    $result_2=mysql_query($sql_2);
		    // Print the records
			$row_2=mysql_fetch_array($result_2);
			$ID=$row_2["ID"];
			$first_name = $row_2["first_name"];
			$middle_name = $row_2["middle_name"];
			$middle_initial = substr($middle_name,0,1);  
			$last_name = $row_2["last_name"];?>
			<a target=_blank href="inventors_edit.php?module=<?=$module;?>&ID=<?=$ID;?>&I=1&EDIT=N"><?=$first_name;?>&nbsp;<?=$middle_initial;?>.&nbsp;<?=$last_name;?></a><br>
		  <?}?>
        </td>
	</tr>
	<tr>
        <td align="right" valign="top" width="115">
            Abstract
        </td>
	<td></td>
        <td colspan="4" bgcolor="#EEEEEE">
            <?=$abstract;?>
        </td>
	</tr>
	<tr>
        <td align="right" valign="top" width="115">
            Products
        </td>
	<td></td>
        <td colspan="4" bgcolor="#EEEEEE">
            <?=$products;?>
        </td>
	</tr>	
	<tr>
        <td align="right" valign="top" width="115">
            Notes
        </td>
	<td></td>
        <td colspan="4" bgcolor="#EEEEEE">
            <?=$notes;?>
        </td>
	</tr>
    <tr>
        <td align="right" valign="top" width="115">
            Actions
            <?if ($user_level != "Viewer") {?>
		    <br><a target=_blank href="pataction_edit.php?module=<?=$module;?>&PAT_ID=<?=$PAT_ID;?>&ACTION_ID=0&I=0&EDIT=Y">Add</a>&nbsp;&nbsp;<?}?>
        </td><td></td>
        <td colspan="1" valign="top" bgcolor="#EEEEEE"><!-- EXISTING PAT ACTIONS -->
		  <? // SQL Query for Selecting Actions Type
		  echo($ACTIONS." Actions");?>
		  	<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$PAT_ID;?>&ACTIONS=All&I=1&EDIT=N">All</a> -
			<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$PAT_ID;?>&ACTIONS=Open&I=1&EDIT=N">Open</a> -
			<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$PAT_ID;?>&ACTIONS=Closed&I=1&EDIT=N">Closed</a><br>
		  <? if ($ACTIONS=="Open") 
		    $sql="SELECT * FROM pat_actions WHERE
		      pat_ID='$PAT_ID' and
			  done='N'";
		  elseif ($ACTIONS=="Closed")
		    $sql="SELECT * FROM pat_actions WHERE
		      pat_ID='$PAT_ID' and
			  done='Y'";
		  else
            $sql="SELECT * FROM pat_actions WHERE
		      pat_ID='$PAT_ID'
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
			<a target=_blank href="pataction_edit.php?module=<?=$module;?>&PAT_ID=<?=$PAT_ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
			  <?=$action_type;?></a>&nbsp;Due&nbsp;<?=$respdue_date;?><br> <?}
		    else {	//done
		      ?><a target=_blank href="pataction_edit.php?module=<?=$module;?>&PAT_ID=<?=$PAT_ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
			  <?=$action_type;?></a>&nbsp;Completed&nbsp;<?=$respdone_date;?><br>
		  <?}}?>
        </td>
	<td align="right" valign="top" width="115">
	      Files<br>
		<?if ($user_level != "Viewer") {?><a target=_blank href="patdoc_upload.php?module=<?=$module;?>&PAT_ID=<?=$PAT_ID;?>&id=0&I=0&EDIT=Y">Add</a><?}?>
	  </td><td></td>
	<td colspan="1" bgcolor="#EEEEEE" valign="top" width="400"><!-- EXISTING DISC FILES -->
		    <? 
		    echo($ATTACHEDFILES." Files");?>
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$PAT_ID;?>&ATTACHEDFILES=All&I=1&EDIT=N">All</a>&nbsp;<?	//All will always be default
		    $sql = "SELECT * FROM menus WHERE menu_type='PATENT_DOCUMENT' ORDER BY menu_name";	//selecting doc names
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
			  $doctype=$row["menu_name"];
			  ?>-&nbsp;<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$PAT_ID;?>&ATTACHEDFILES=<?=$doctype;?>&I=1&EDIT=N"><?=$doctype;?></a>&nbsp; 
			<?}?><br></td></tr><td></td><td></td><td></td><td></td><td></td><td colspan="1" valign="top" bgcolor=EEEEEE>
		    <? if($ATTACHEDFILES=="All")
			$sql="Select * FROM pat_documents WHERE
			      pat_ID='$PAT_ID'
				ORDER BY doc_date";
		    else	//should only go if ATTACHEDFILES is a valid subtype
		      $sql="SELECT * FROM pat_documents WHERE
			pat_id='$PAT_ID' and
			  subtype='$ATTACHEDFILES'
			    ORDER BY doc_date";
		    $result=mysql_query($sql);
		    while($row=mysql_fetch_array($result)){
		      $DOC_ID=$row["id"];
		      $subtype=$row["subtype"];
		      //$respdue_date=$row["respdue_date"];
		      $docName=$row["name"];
		      $alt_name=$row["alt_name"];?>
			  <a target=_blank href="patdoc_upload.php?module=<?=$module;?>&PAT_ID=<?=$PAT_ID;?>&id=<?=$DOC_ID;?>&subtype=<?=$subtype;?>&I=1&EDIT=N">
			    <?=$subtype;?></a>&nbsp;&nbsp;
			    <a href="<?=$absolute_documents.$alt_name;?>"><?=$docName;?></a><br>
		    <?}?>
	  </td>
    </tr>
    <tr>
	<td align="right" valign="top" width="115">
	      Attached Disclosures<br>
		<?if ($user_level != "Viewer") {?><a target=_blank href="link_disclosure.php?pat_ID=<?=$PAT_ID;?>">Add</a><?}?>
	  </td><td></td>
	 <td colspan="4" valign="top" bgcolor="#EEEEEE"><!-- EXISTING PAT DISCLOSURES -->
		    <? 
	      $sql="SELECT * FROM pat_disclosures WHERE
			pat_ID='$PAT_ID'
			    ORDER BY disc_ID";
		    $result=mysql_query($sql);
		    while($row=mysql_fetch_array($result)){
		      $attached_ID=$row["ID"];
		      $disc_ID=$row["pat_ID"];
		      $disc_docket_alt=$row["disc_docket_alt"];
		      $disc_name=$row["disc_name"];?>
			  <?=$disc_docket_alt;?>&nbsp;&nbsp;
			  <a target=_blank href="disclosures_edit.php?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=<?=$disc_ID;?>&ACTIONS=Open&I=1&EDIT=N"><?=$disc_name;?></a><br>
		    <?}?>
	  </td>
	  </td>
      </tr>
    <tr>
	  <td align="center" colspan="6" width="100%">
	      <hr noshade size="1" width="500">
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
CONTENT="0;URL=<?=$PHP_SELF;?>?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$PAT_ID?>&I=1&EDIT=N">
<?} html_footer(); ?>
