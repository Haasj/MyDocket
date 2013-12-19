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
//	6/28/2013	copied from patents_edit. I used this one becuase I want the same sort of search functionality when you add a new one.
//               	taking out the I stuff and therefore the ability to straight add a new one. You will have to select a related pdf file-that way they are always paired with one.
//               	everything works, but when you update it, it resets the pdf id...
//               	this was because it was doing it in the update sql, even though I didn't need to. also it shouldn't have mattered, but it works now that I took it out.
//	7/1/2013	hyperlinked the pdf_id to the upload screen, so you can delete it. currently the only way to.
//	7/2/2013	changed it to say transaction instead of account. the back end still thinks it's an account though
//			deleted a bunch of un-needed, commented out code. to see this, go back to 7/1's code
//			validated the submitted docket number. Note: it checks to see if the result from the pull of the submitted number is in docket_master. this check is in the other validtion.
//			now opens pdf in new window (added target=_blank)
//	7/10/2013	commented out the validity check. going to start the lookup tomorrow.
//	7/11/2013	made firm_docket a required field
//			finished the validation. put it right before the update sql code. here's how it works:
//			it initializes a string for the tables that are going to be searched, the variables that will need to be checked (docket), and the alias declarations. it starts it with patents, so it will
//			return 0 if there are no patents. eventually, when there is at least one record in each category, you can delete this and just write a query with all tables. if you do that now, it will
//			return zero. adds the table to all three strings if there is data in it. next it runs the query, but the query returns an entry with one row in every table, even though only one is valid.
//			to get around this, you check the aliases (you can't use $row with duplicate names) for the one with the correct firm_docket
//	7/17/2013	added $user_level to header call
//	7/18/2013	replaced all authentication to $user_level while leaving in $sysadmin because I'm pretty sure that's just the root admin.
//			commented out autoaction piece. there isn't anything that goes from there for now.
//	7/19/2013	bug: always returns -1. i think the query is searching every table, and there isn't data in every table, so it's returning the empty set. not sure why...
//			fixed. I was checking mysql_num_rows with the query, and not the result array. not sure why I thought this worked before... anyway, it works now.
//	7/23/2013	changed to work with alt_name for pdfs
//			modified so it passes the firm/ecs docket as hidden variables when you select the pdf. this way, it works if you pass them in (from recrord)
//			currently working on the authentication. it will check at the top if at least one docket has been entered, then before the update it auto-grabs one to fit with the ecs, one for the firm,
//			then sees if they match to the same record, or fills in the other if one is left blank.
//	7/24/2013	got the authentication to work. basically it finds a firm_docket based on the ecs_docket and vice versa (if both inputted) and then checks them against each other. it's really confusing,
//			but I think the comments on the code should clarify everything.
//	7/30/2013	replaced paths with session variables
//	8/9/2013	John was reporting errors rounding and getting decimal points to show up; changed the value in the db to DECIMAL 19,2 to fix.
//			allowed speacial chars in data fields. for more info, check out trademarks_edit.php
//	8/13/2013	changed error protocol to exit/display error
//			lined up the fields, changed some formatting
//	8/15/2013	added checkdates
//	8/19/2013	fixed date logic; incorrectly was using checkdate. this won't work, because of formatting. now, it will work, but throw the mysql_error if the date is invalid. works with ""
//	8/20/2013	was throwing null error; fixed this by not inserting those collumns when it's initially created.
//	8/21/2013	put them back in. was making the inherit not work, so I inserted zero when there was nothing passed.
//	8/26/2013	added comments, cleaned up the code a little bit
//	8/29/2013	replaced server_invs with abs_invs
//	8/30/2013	fixed some formatting things
//	9/6/2013	added a link to the pdf itself on both views from the pdf id
//accounts_edit.php -- User Access Level: User
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
// Set $var string
$var="PDFID=$PDFID&NAME=$NAME";
// Set Defaults
if ($NEXT=="1") $START=$START+50;
  else $START="0";
// SELECT RELATED invoice
if($ACCTEDIT!="1"){
  if ($submit_1=="" or $pdf_id==""){?>
  <table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
    <td width="100%" align="center" bgcolor="#FFFFFF">
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&PATFAM=1&PATEDIT=0&NEXT=0&START=<?=$START;?>&<?=$var;?>">First 50</a>&nbsp;|&nbsp;
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&PATFAM=1&PATEDIT=0&NEXT=1&START=<?=$START;?>&<?=$var;?>">Next 50</a>
    </td></tr>
  </table><br><br> 
  <center><a href="pdf_upload.php?module=<?=$module;?>&I=2&EDIT=Y&NEW=Y&ecs_docket=<?=$ecs_docket;?>&firm_docket=<?=$firm_docket;?>">UPLOAD PDF</a><br><br></center><br><br>
  <center>SELECT EXISTING PDF</center>
  <form method=get action="<?=$PHP_SELF;?>">
    <input type="hidden" name="firm_docket" value="<?=$firm_docket;?>">
    <input type="hidden" name="ecs_docket" value="<?=$ecs_docket;?>">
    <input type="hidden" name="module" value="<?=$module;?>">
    <input type="hidden" name="I" value="0">
    <input type="hidden" name="EDIT" value="Y">
    <input type="hidden" name="acct_ID" value="<?=$acct_ID;?>">
  <table align="center" width="100%" border="0" cellpadding="5" cellspacing="2">
    <tr>
      <td align="right" colspan="4">
	    <small>PDF ID&nbsp;<input type="text" name="PDFID" maxlength="10" size="10" value="<?=$PDFID;?>"></small>&nbsp;&nbsp;
	<small>NAME&nbsp;<input type="text" name="NAME" maxlength="30" size="10" value="<?=$NAME;?>"></small>&nbsp;&nbsp;
	<input type="submit" name="submit_search" value=" SEARCH ">&nbsp;&nbsp;
	<input type="submit" name="submit_50" value=" NEXT 50 ">&nbsp;&nbsp;</td>
    </tr>
    <tr bgcolor=EEEEEE>
      <td width="20"><small>SELECT</small></td>
      <td width="80"><small>PDF ID</small></td>
      <td width="480"><small>FILE NAME</small></td>
    </tr>
    <?
  // not requiring customer_id here, since it's not about the accounts themselves. different customers currently can view the same invoice docs and make records off them
      $sql="SELECT * FROM pdf_combos WHERE
	    id LIKE '%$PDFID%' and
	name LIKE '%$NAME%'
	    ORDER BY id LIMIT $START, 50";

    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$pdf_id=$row["id"];
	$alt_name=$row["alt_name"];
	$name=$row["name"];
      ?>
      </td></tr>
      <tr bgcolor=EEEEEE>
	<td width="20"><input type="radio" name="pdf_id" value="<?=$pdf_id;?>"></td>
	<td width="100"><small><a href="pdf_upload.php?module=<?=$module;?>&I=1&id=<?=$pdf_id;?>&EDIT=N"><?=$pdf_id;?></a></small></td>
	<td width="430"><small><a target=_blank href="<?=$absolute_invoices.$alt_name;?>"><?=$name;?></a></small></td>
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
  if($ecs_docket == "") {$ecs_docket = "0";}	//need the initialization code for newer mysql versions
  if($amount == "") {$amount = "0";}
  if($firm_docket == "") {$firm_docket = "0";}
  if($invoice_date == "") {$invoice_date = "0000-00-00";}
  // Create the record that will be edited
  $sql="INSERT INTO accounts SET
	  customer_ID='$customer_ID',
      ecs_docket='$ecs_docket',
      pdf_id='$pdf_id',
      amount='$amount',
      firm_docket='$firm_docket',
      invoice_date='$invoice_date',
      invoice_number='$invoice_number',
      	  creator='$fullname',
	  create_date='$today',
	  editor='$fullname',
	  edit_date='$today'";
  if (!mysql_query($sql)){
    echo mysql_error();
   exit;}
    
  // Get the ID of the new pat_filings record
  $acct_ID_NEW=mysql_insert_id();
  $ACCTEDIT="1";
  $acct_ID=$acct_ID_NEW;
  }
}?>

<!-- ADD OR EDIT PATENT RECORD -->
<?
if ($ACCTEDIT=="1") {      

if ($submit_2==""){
$sql="SELECT * FROM accounts WHERE acct_id='$acct_ID'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
    $ecs_docket = $row["ecs_docket"];
    $pdf_id = $row["pdf_id"];
    $amount = $row["amount"];
    $firm_docket = $row["firm_docket"];
    $invoice_date = $row["invoice_date"];
    $invoice_number = $row["invoice_number"];}		  

if (($sysadmin=="Y" or $user_level != "Viewer") and $read_only=="N" and $EDIT=="Y")
{?>
  <table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
    <td width="100%" align="center" bgcolor="#FFFFFF">
      <a href="delete_confirm.php?module=<?=$module;?>&TABLE=accounts&ID=<?=$acct_ID;?>&NAME=<?=$acct_ID;?>">Delete</a>
        </td></tr>
  </table><br><br>
  <? if ($NEW=="Y") echo("<center>ADD TRANSACTION RECORD</center>");
    else echo("<center>EDIT TRANSACTION RECORD<br>" . $msg . "</center>");
  if ($submit_2=="" or $amount=="" or ($firm_docket=="" and $ecs_docket=="")){
    if ($submit_2!=""){?>
    <center><br><font color="red">Please update fields marked with a red asterisk, and enter in at least one docket number</font><br></center><?}?>
    <form method=get action="<?=$PHP_SELF;?>">
    <input type="hidden" name="module" value="<?=$module;?>">
    <input type="hidden" name="acct_ID" value="<?=$acct_ID;?>">
    <input type="hidden" name="ecs_docket" value="<?=$ecs_docket;?>">
    <input type="hidden" name="firm_docket" value="<?=$firm_docket;?>">
    <input type="hidden" name="ACCTEDIT" value="<?=$ACCTEDIT;?>">
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
	      Firm Docket No.
	  </td>
	  <td colspan="1" align="right" width="300">
	      &nbsp; <input name="firm_docket" type="text" maxlength="200" size="40" value="<?=$firm_docket;?>">&nbsp;&nbsp;
	  </td>
      </tr>
      <tr>
	  <td align="right" width="115">
	      ECS Docket No.
	  </td>
	  <td colspan="1" align="right" width="300">
	     &nbsp; <input name="ecs_docket" type="text" maxlength="200" size="40" value="<?=$ecs_docket;?>">&nbsp;&nbsp;
	  </td>
      </tr>
        <tr>
	  <td align="right" width="115">
	      Amount
	  </td>
	  <td colspan="1" align="right" width="299">
	      $<input name="amount" type="text" maxlength="200" size="39" value="<?=$amount;?>">
              <font color="orangered"><TT><B>*</B></TT></font>
	  </td>
      </tr>
      <tr>
	    <td align="right" width="115">
	      Invoice Date
	  </td>
	  <td width="300">
	      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=text name="invoice_date" size="12" maxlength="10" value="<?=$invoice_date;?>">
			  <small>&nbsp;(YYYY-MM-DD)</small>&nbsp;
	  </td>
      </tr>
        <tr>
	  <td align="right" width="115">
	      Invoice Number
	  </td>
	  <td colspan="1" align="right" width="300">
	      &nbsp;<input name="invoice_number" type="text" maxlength="200" size="39" value="<?=$invoice_number;?>">
              <font color="orangered"><TT><B>*</B></TT></font>
	  </td>
      </tr>
         <tr>
	  <td align="right" width="115">
	      PDF ID
	  </td>
	  <?
	    $sql="SELECT * FROM pdf_combos WHERE id=$pdf_id";	//should just return the row that is the pdf. note: I'm doing select * so if I decide to change to display the name the code is mostly the same
	    $result=mysql_query($sql);
	    //Print the records
	    while($row=mysql_fetch_array($result)){ 
	    $name=$row["name"];
	    $alt_name=$row["alt_name"];}
	  ?>
	  <td colspan="1" align="left" width="300">
	       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target=_blank href="<?=$absolute_invoices.$alt_name;?>"><?=$pdf_id;?></a>
	  </td>
      </tr>
     
      <tr>
	  <td align="center" colspan="2" width="100%">
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
    //code to do the validation.
    if ($firm_docket != "")	//lookup the ecs_docket from the firm_docket
    {
      echo "firm";
      //note: the following will return an empty set if there are no patents in the db. I think there always should be, since John already inputted his, so hopefully this won't be a problem.
      $from_tables = "pat_filings";	//string to hold the tables that are being searched
      $variable_match = "pat_filings.docket='$firm_docket'"; //string to hold declarations for searching the dockets
      $alias_string = "pat_filings.docket_alt as Pat_dock, pat_filings.docket as Pat_firm";  //string to hold aliases. do this so $row knows what to grab. otherwise, it would always pull from the last table searched
      $sql = "SELECT * FROM tm_filings";	//select all tms
      $result=mysql_query($sql);
      if (mysql_num_rows($result)!='0')//if there are some, add it to the query
      {$from_tables = $from_tables . ", tm_filings";
      $variable_match = $variable_match . " or tm_filings.docket='$firm_docket'";
      $alias_string = $alias_string . ", tm_filings.docket_alt as Tm_dock, tm_filings.docket as Tm_firm";}
      $sql = "SELECT * FROM copyrights";	//slect all cps
      $result=mysql_query($sql);
      if (mysql_num_rows($result)!='0') //if there are some, add it to the query
      {$from_tables = $from_tables . ", copyrights";
      $variable_match = $variable_match . " or copyrights.docket='$firm_docket'";
      $alias_string = $alias_string . ", copyrights.docket_alt as Cpy_dock, copyrights.docket as Cpy_firm";}
      $sql = "SELECT * FROM contracts";	//select all licenses/ndas
      $result=mysql_query($sql);
      if (mysql_num_rows($result)!='0') //if there are some, add it to the query
      {$from_tables = $from_tables . ", contracts";
      $variable_match = $variable_match . " or contracts.docket='$firm_docket'";
      $alias_string = $alias_string . ", contracts.docket_alt as Ctr_dock, contracts.docket as Ctr_firm";}
      $sql = "SELECT * FROM genipm_filings"; //select all genipm
      $result=mysql_query($sql);
      if (mysql_num_rows($result)!='0')//if there are some, add it to the query
      {$from_tables = $from_tables . ", genipm_filings";
      $variable_match = $variable_match . " or genipm_filings.docket='$firm_docket'";
      $alias_string = $alias_string . ", genipm_filings.docket_alt as Gen_dock, genipm_filings.docket as Gen_firm";}
      
      $sql = "SELECT $alias_string FROM $from_tables WHERE $variable_match";
      echo $sql;
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);		//don't have to loop since there should be only one
      //check each one, since they will be joined with one from each starting with the first hit on the docket
      if ($firm_docket == $row["Pat_firm"]) {$ecs_docket_test = $row["Pat_dock"];}
      elseif ($row["Cpy_firm"] == $firm_docket) {$ecs_docket_test = $row["Cpy_dock"]; }
      elseif ($row["Ctr_firm"] == $firm_docket) {$ecs_docket_test = $row["Ctr_dock"];}
      elseif ($row["Gen_firm"] == $firm_docket) {$ecs_docket_test = $row["Gen_dock"];}
      elseif ($row["Tm_firm"] == $firm_docket) {$ecs_docket_test = $row["Tm_dock"];}
      else {$ecs_docket_test = -1;}
    }
    
    if ($ecs_docket != "")	//lookup the firm docket from the ecs docket. Note: this is not an elseif, since I will check them against each other at the end, so they both need to be run sometimes
    {
      echo "ecs";
      $sql = "SELECT * FROM docket_master WHERE docket_number='$ecs_docket'";
      $result = mysql_query($sql);
      $row = mysql_fetch_array($result);
      $type = $row["type"];
      $type_ID = $row["ID"];
      if ($type == "disclosure")	//disclosures have no firm_dockets
      {
	$firm_docket_test = "disclosure";
      }
      elseif ($type == "patent")
      {
	$sql = "SELECT docket FROM pat_filings WHERE pat_ID='$type_ID'";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$firm_docket_test = $row["docket"];
      }
      elseif ($type == "trademark")
      {
	$sql = "SELECT docket FROM tm_filings WHERE tm_ID='$type_ID'";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$firm_docket_test = $row["docket"];
      }
      elseif ($type == "copyright")
      {
	$sql = "SELECT docket FROM copyrights WHERE ID='$type_ID'";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$firm_docket_test = $row["docket"];
      }
      elseif ($type == "NDA" or $type == "license")
      {
	$sql = "SELECT docket FROM contracts WHERE ID='$type_ID'";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$firm_docket_test = $row["docket"];
      }
      elseif ($type == "GENIPM")
      {
	$sql = "SELECT docket FROM genipm_filings WHERE ID='$type_ID'";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$firm_docket_test = $row["docket"];
      }
      else //no type-no result
      {$firm_docket_test = -1;}
    }
    
    if ($firm_docket_test == -1 and $ecs_docket_test == -1)	//both are invalid, but populated
    {
      $msg = "Neither docket number is valid.";
	  ?><META HTTP-EQUIV="refresh" 
	  CONTENT="0;URL=<?=$PHP_SELF;?>?module=<?=$module;?>&ACCTEDIT=1&acct_ID=<?=$acct_ID?>&I=1&EDIT=Y&msg=<?=$msg;?>"><?
    }
    
    elseif (($firm_docket_test != -1 and $firm_docket_test != "") and ($ecs_docket_test == -1 or $ecs_docket_test == ""))
    {	//ecs_docket was valid while firm_docket wasn't or was absent. now populate firm_docket with firm_docket_test
      $firm_docket = $firm_docket_test;
      $msg = "ECS DOCKET CORRECT. AUTO-POPULATED FIRM DOCKET.";	//message explaining what happened
    }
    
    elseif (($ecs_docket_test != -1 and $ecs_docket_test != "") and ($firm_docket_test == -1 or $firm_docket_test == ""))
    {	//firm_docket was valid while ecs_docket wasn't or was absent. now populate ecs_docket with ecs_docket_test
      $ecs_docket = $ecs_docket_test;
      $msg = "FIRM DOCKET CORRECT. AUTO-POPULATED ECS DOCKET.";	//message explaining what happened
    }
    
    else //both are inputted, both are valid. need to check if they belong to the same record.
    {
	if ($ecs_docket != $ecs_docket_test)	//else: calculated ecs and inputted ecs match, so it's right
	{
	  $msg = "Both dockets are valid, but they don't match.";
	  ?><META HTTP-EQUIV="refresh" 
	  CONTENT="0;URL=<?=$PHP_SELF;?>?module=<?=$module;?>&ACCTEDIT=1&acct_ID=<?=$acct_ID?>&I=1&EDIT=Y&msg=<?=$msg;?>"><?
	}
    }
  //put in correct defaults
if($invoice_date == "")
  {$invoice_date = "0000-00-00";}
    // enable apostrophe's in data fields by using escape_string to insert a '/' before every special character as defined in mysql
      $firm_docket = mysql_real_escape_string($firm_docket);
      $ecs_docket = mysql_real_escape_string($ecs_docket);
      $amount = mysql_real_escape_string($amount);
      $invoice_date = mysql_real_escape_string($invoice_date);
      $invoice_number = mysql_real_escape_string($invoice_number);
    //delete any commas put in the amount field
    $amount=str_replace(",","",$amount);
    
    // The script performs the database modification
    $sql = "UPDATE accounts SET
	customer_ID = '$customer_ID',
	ecs_docket = '$ecs_docket',
        amount = '$amount',
        firm_docket = '$firm_docket',
	invoice_date = '$invoice_date',
	invoice_number = '$invoice_number',
	    editor='$fullname',
	    edit_date='$today'
	WHERE acct_id='$acct_ID'";
    // RUN THE QUERY
      if (!mysql_query($sql)){
  echo mysql_error();
   exit;}

	      
    // **Commented out to display updated record rather than confirmation screen
     $DONE="1";
  }
}

else{ // DISPLAY RECORD -- NOT EDIT
?>
<!-- DISPLAY PATENT -->
<table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">
    <? if ($sysadmin=="Y" or $user_level != "Viewer"){?>
  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ACCTEDIT=1&acct_ID=<?=$acct_ID?>&I=1&EDIT=Y&SORT=<?=$SORT;?>&VAR=<?=$VAR?>">Edit</a>
  <?}?></td></tr>
</table><br><br>
<center>FULL TRANSACTION RECORD<br><?=$msg;?></center><br>
<table align="center" border="0" cellpadding="0" cellspacing="5">
    <tr>
	  <td align="right" width="115">
	      Firm Docket No.&nbsp;&nbsp;&nbsp;
	  </td>
	  <td colspan="1" align="left" width="200" bgcolor=EEEEEE>
	     <?=$firm_docket;?>
	  </td>
      </tr>
      <tr>
	  <td align="right" width="115">
	      ECS Docket No.&nbsp;&nbsp;&nbsp;
	  </td>
	  <td colspan="1" align="left" width="200" bgcolor=EEEEEE>
	      <?=$ecs_docket;?>
	  </td>
      </tr>
        <tr>
	  <td align="right" width="115">
	      Amount&nbsp;&nbsp;&nbsp;
	  </td>
	  <td colspan="1" align="left" width="200" bgcolor=EEEEEE>
	      $<?=$amount;?>
	  </td>
      </tr>
	<tr>
	  <td align="right" width="115">
	      Invoice Date&nbsp;&nbsp;&nbsp;
	  </td>
	  <td colspan="1" align="left" width="200" bgcolor=EEEEEE>
	      <?=$invoice_date;?>
	  </td>
      </tr>
	<tr>
	  <td align="right" width="115">
	      Invoice Number&nbsp;&nbsp;&nbsp;
	  </td>
	  <td colspan="1" align="left" width="200" bgcolor=EEEEEE>
	      <?=$invoice_number;?>
	  </td>
      </tr>
         <tr>
	  <td align="right" width="115">
	      PDF ID&nbsp;&nbsp;&nbsp;
	  </td>
	  <?
	    $sql="SELECT * FROM pdf_combos WHERE id=$pdf_id";	//should just return the row that is the pdf. note: I'm doing select * so if I decide to change to display the name the code is mostly the same
	    $result=mysql_query($sql);
	    //Print the records
	    while($row=mysql_fetch_array($result)){ 
	    $name=$row["name"];
	    $alt_name=$row["alt_name"];}
	  ?>
	  <td colspan="1" align="left" width="200" bgcolor=EEEEEE>
	      <a target=_blank href="<?=$absolute_invoices.$alt_name;?>"><?=$pdf_id;?></a>
	  </td>
      </tr>
    <tr>
	  <td align="center" colspan="2" width="100%">
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
CONTENT="0;URL=<?=$PHP_SELF;?>?module=<?=$module;?>&ACCTEDIT=1&acct_ID=<?=$acct_ID?>&I=1&EDIT=N&msg=<?=$msg;?>">
<?} html_footer(); ?>
