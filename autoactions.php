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
//	6/12/2013	created the disclosure autoactions by copy and pasting the patent autoactions.
//			should be done with the disclosures autoactions, the only issue is if the commenting out of "original" does anything. it shoudln't
//	7/2/2013	to specify subtypes of patents that run autoactions. not sure which ones go with what--will have to ask John
//	7/3/2013	added break when there is no valid date. this is so it doesn't throw invalid date, just doesn't attach the action
//			added nda, license autoactions.
//	7/16/2013	it had been marking them as done if the date had passed and it was a certain type. since I made the types dynamic, commented out the check type. I'll delete it tomorrow once backup
//			commented out the deletion of existing auto-generated actions. for reason, see one line down
//			added sqla--it checks the action list for one with the same name and id. if there is one, it doesn't add it again. if there isn't, it does nothing
//	7/23/2013	edited for pre/post actions. how it works: if it detects pre_post is "pre", it negates the added date and adds it the same.
//	8/26/2013	added comments and cleaned up the code a little
//	9/4/2013	it broke for pre-dates that were too big. this was because when the dates went negative, they were invalid. manually changed them to positive if they were negative
//			also, all the breaks should be continues so it continues getting actions when one fails. fixed.
// autoactions.php
// Patent Autoactions
if ($PAT_ID!=""){
  // Retrieve parameters about the case
  $sql="SELECT * FROM pat_filings WHERE pat_ID='$PAT_ID'";
  $result = mysql_query($sql);
  $row = mysql_fetch_array($result);
  $original = $row["original"];
  $country = $row["country"];
  $filing_type = $row["filing_type"];
  // Retrieve autotactions
  $sql="SELECT * FROM autoactions WHERE ip_type='PATENT' and subtype='$filing_type' and country='$country' and on_off='ON'";
  $result = mysql_query($sql);
  while ($row = mysql_fetch_array($result)){
    $insert="0";
    $original_only=$row["original_only"];
    $action_type=$row["action_type"];
    $description=$row["description"];
    $due_year=$row["due_year"];
    $due_month=$row["due_month"];
    $due_day=$row["due_day"];
    $reference_date=$row["reference_date"];
    $pre_post=$row["pre_post"];
    echo $reference_date;
    $sqla="SELECT * FROM pat_actions WHERE action_type='$action_type' and pat_ID='$PAT_ID'";
    $resulta=mysql_query($sqla);
    if (mysql_num_rows($resulta) != "0") {continue;}	//don't add it if one of the same name already exists
	   
	// Check for Priority Date Related Actions and insure that there is a priority date set
    if ($reference_date=="Priority Date" and $priority_date!="0000-00-00" and ($original_only=="N" or $original=="Y")){
    $tmp = explode("-", $priority_date); // $tmp[0]=year, $tmp[1]=month, $tmp[2]=day
	$insert = "1";
	echo $tmp[0] . $tmp[1] . $tmp[2];
    }
	// Check for Filing Date Related Actions and insure that there is a filing date set
    elseif ($reference_date=="Filing Date" and $filing_date!="0000-00-00" and ($original_only=="N" or $original=="Y")){
	$tmp = explode("-", $filing_date); // $tmp[0]=year, $tmp[1]=month, $tmp[2]=day
	$insert = "1";
    }
	// Check for Issue Date Related Actions and insure that there is a issue date set
    elseif ($reference_date=="Issue Date" and $issue_date!="0000-00-00" and ($original_only=="N" or $original=="Y")){
	$tmp = explode("-", $issue_date); // $tmp[0]=year, $tmp[1]=month, $tmp[2]=day
	$insert = "1";
    }
    
    if ($pre_post == "pre")	//this will negate the due dates if it's a pre action.
    {
      $due_day = -$due_day;
      $due_month = -$due_month;
      $due_year = -$due_year;
    }

	if ($insert=="1"){
	// Calculate the due date
	$respdue_year=$tmp[0]+$due_year;	
	$respdue_month=$tmp[1]+$due_month;	
	$respdue_day=$tmp[2]+$due_day;		
	//check for negative dates
	if ($respdue_day < 0) {
	  $respdue_day = $respdue_day + 31;	//this will 'carry' the month over
	  $respdue_month = $respdue_month - 1;	//^
	}
	if ($respdue_month < 0) {
	  $respdue_month = $respdue_month + 12;	//this will 'carry' the year over
	  $respdue_year = $respdue_year - 1;
	}
	// Adjust for months
	if ($respdue_month>12){
	  $respdue_month=$respdue_month-12;
	  $respdue_year=$respdue_year+1;}
	// Click back days for short months
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  $respdue_day=$respdue_day-1;
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  $respdue_day=$respdue_day-1;
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  $respdue_day=$respdue_day-1;
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  continue;	//this will not attach the action if the date is invalid--aka if the ref date is 0
    $respdue_date = $respdue_year."-".$respdue_month."-".$respdue_day;
	// if time passed, then mark as done for certain actions
	if (($respdue_date < $today))	//marks as complete if the due date is passed. note: it will not assign a completed date, so it will display all zeros for that
	  $done="Y";
	  else $done="N";
	
    $sql_x="INSERT INTO pat_actions SET
	  customer_ID='$customer_ID',
	  pat_ID='$PAT_ID',
	  autogen='Y',
	  action_type='$action_type',
	  respdue_date='$respdue_date',
	  description='$description',
	  done='$done',
	  creator='$fullname',
	  create_date='$today'";
	  if (!mysql_query($sql_x))
        error("A database error occurred in processing your ".
        "submission.\\nIf this error persists, please ".
        "contact your HelpDesk.");
}}}

// Trademark Autoactions
if ($TM_ID!=""){
  // Retrieve parameters about the case
  $sql="SELECT * FROM tm_filings WHERE tm_ID='$TM_ID'";
  $result = mysql_query($sql);
  $row = mysql_fetch_array($result);
  $original = $row["original"];
  $country = $row["country"];
  // Retrieve autotactions
  $sql="SELECT * FROM autoactions WHERE ip_type='TRADEMARK' and country='$country' and on_off='ON'";
  $result = mysql_query($sql);
  while ($row = mysql_fetch_array($result)){
    $insert="0";
    $original_only=$row["original_only"];
    $action_type=$row["action_type"];
    $description=$row["description"];
    $due_year=$row["due_year"];
    $due_month=$row["due_month"];
    $due_day=$row["due_day"];
    $reference_date=$row["reference_date"];
    $pre_post=$row["pre_post"];
      
    $sqla="SELECT * FROM tm_actions WHERE action_type='$action_type' and tm_ID='$TM_ID'";
    $resulta=mysql_query($sqla);
    if (mysql_num_rows($resulta) != "0") {continue;}	//don't add it if one of the same name already exists      
	// Check for Priority Date Related Actions and insure that there is a priority date set
    if ($reference_date=="Priority Date" and $priority_date!="0000-00-00" and ($original_only=="N" or $original=="Y")){
    $tmp = explode("-", $priority_date); // $tmp[0]=year, $tmp[1]=month, $tmp[2]=day
	$insert = "1";
    }
	// Check for Filing Date Related Actions and insure that there is a filing date set
    elseif ($reference_date=="Filing Date" and $filing_date!="0000-00-00" and ($original_only=="N" or $original=="Y")){
	$tmp = explode("-", $filing_date); // $tmp[0]=year, $tmp[1]=month, $tmp[2]=day
	$insert = "1";
    }
	// Check for Issue Date Related Actions and insure that there is a issue date set
    elseif ($reference_date=="Issue Date" and $issue_date!="0000-00-00" and ($original_only=="N" or $original=="Y")){
	$tmp = explode("-", $issue_date); // $tmp[0]=year, $tmp[1]=month, $tmp[2]=day
	$insert = "1";
    }
    
    if ($pre_post == "pre")	//this will negate the due dates if it's a pre action.
    {
      $due_day = -$due_day;
      $due_month = -$due_month;
      $due_year = -$due_year;
    }
	
	if ($insert=="1"){
	// Calculate the due date
	$respdue_year=$tmp[0]+$due_year;	
	$respdue_month=$tmp[1]+$due_month;	
	$respdue_day=$tmp[2]+$due_day;		
	//check for negative dates
	if ($respdue_day < 0) {
	  $respdue_day = $respdue_day + 31;	//this will 'carry' the month over
	  $respdue_month = $respdue_month - 1;	//^
	}
	if ($respdue_month < 0) {
	  $respdue_month = $respdue_month + 12;	//this will 'carry' the year over
	  $respdue_year = $respdue_year - 1;
	}
	// Adjust for months
	if ($respdue_month>12){
	  $respdue_month=$respdue_month-12;
	  $respdue_year=$respdue_year+1;}
	// Click back days for short months
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  $respdue_day=$respdue_day-1;
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  $respdue_day=$respdue_day-1;
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  $respdue_day=$respdue_day-1;
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  continue;	//this will not attach the action if the date is invalid--aka if the ref date is 0
    $respdue_date = $respdue_year."-".$respdue_month."-".$respdue_day;
	// if time passed, then mark as done for certain actions
	if (($respdue_date < $today))	//complete if past due at creation
	  $done="Y";
	else $done="N";
    $sql_x="INSERT INTO tm_actions SET
	  customer_ID='$customer_ID',
	  tm_ID='$TM_ID',
	  autogen='Y',
	  action_type='$action_type',
	  respdue_date='$respdue_date',
	  description='$description',
	  done='$done',
      creator='$fullname',
	  create_date='$today'";
	  if (!mysql_query($sql_x))
        error("A database error occurred in processing your ".
        "submission.\\nIf this error persists, please ".
        "contact your HelpDesk.");
}}}

// Disclosure Autoactions
if ($DISC_ID!=""){
  $sql="SELECT * FROM disc_filings WHERE disc_ID='$DISC_ID'";
  $result = mysql_query($sql);
  $row = mysql_fetch_array($result);
  //$original = $row["original"];
  $country = $row["country"];
  // Retrieve autotactions
  $sql="SELECT * FROM autoactions WHERE ip_type='DISCLOSURE' and country='$country' and on_off='ON'";
  $result = mysql_query($sql);
  while ($row = mysql_fetch_array($result)){
    $insert="0";
    $original_only=$row["original_only"];
    $action_type=$row["action_type"];
    $description=$row["description"];
    $due_year=$row["due_year"];
    $due_month=$row["due_month"];
    $due_day=$row["due_day"];
    $reference_date=$row["reference_date"];
    $pre_post=$row["pre_post"];
	   
    $sqla="SELECT * FROM disc_actions WHERE action_type='$action_type' and disc_ID='$DISC_ID'";
    $resulta=mysql_query($sqla);
    if (mysql_num_rows($resulta) != "0") {continue;}	//don't add it if one of the same name already exists
	   
	// Check for Priority Date Related Actions and insure that there is a priority date set
    if ($reference_date=="Priority Date" and $priority_date!="0000-00-00" and ($original_only=="N" /*or $original=="Y"*/)){
    $tmp = explode("-", $priority_date); // $tmp[0]=year, $tmp[1]=month, $tmp[2]=day
	$insert = "1";
    }
	// Check for Filing Date Related Actions and insure that there is a filing date set
    elseif ($reference_date=="Filing Date" and $filing_date!="0000-00-00" and ($original_only=="N" /*or $original=="Y"*/)){
	$tmp = explode("-", $filing_date); // $tmp[0]=year, $tmp[1]=month, $tmp[2]=day
	$insert = "1";
    }
	// Check for Issue Date Related Actions and insure that there is a issue date set
    elseif ($reference_date=="Issue Date" and $issue_date!="0000-00-00" and ($original_only=="N" /*or $original=="Y"*/)){
	$tmp = explode("-", $issue_date); // $tmp[0]=year, $tmp[1]=month, $tmp[2]=day
	$insert = "1";
    }
    
    if ($pre_post == "pre")	//this will negate the due dates if it's a pre action.
    {
      $due_day = -$due_day;
      $due_month = -$due_month;
      $due_year = -$due_year;
    }

	if ($insert=="1"){
	// Calculate the due date
	$respdue_year=$tmp[0]+$due_year;	
	$respdue_month=$tmp[1]+$due_month;	
	$respdue_day=$tmp[2]+$due_day;		
	//check for negative dates
	if ($respdue_day < 0) {
	  $respdue_day = $respdue_day + 31;	//this will 'carry' the month over
	  $respdue_month = $respdue_month - 1;	//^
	}
	if ($respdue_month < 0) {
	  $respdue_month = $respdue_month + 12;	//this will 'carry' the year over
	  $respdue_year = $respdue_year - 1;
	}
	// Adjust for months
	if ($respdue_month>12){
	  $respdue_month=$respdue_month-12;
	  $respdue_year=$respdue_year+1;}
	// Click back days for short months
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  $respdue_day=$respdue_day-1;
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  $respdue_day=$respdue_day-1;
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  $respdue_day=$respdue_day-1;
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  continue;	//this will not attach the action if the date is invalid--aka if the ref date is 0
    $respdue_date = $respdue_year."-".$respdue_month."-".$respdue_day;
	// if time passed, then mark as done for certain actions
	if (($respdue_date < $today))	//shows completed if due date is passed on creation
	  $done="Y";
	  else $done="N";
    $sql_x="INSERT INTO disc_actions SET
	  customer_ID='$customer_ID',
	  disc_ID='$DISC_ID',
	  autogen='Y',
	  action_type='$action_type',
	  respdue_date='$respdue_date',
	  description='$description',
	  done='$done',
      creator='$fullname',
	  create_date='$today'";
	  if (!mysql_query($sql_x))
        error("A database error occurred in processing your ".
        "submission.\\nIf this error persists, please ".
        "contact your HelpDesk.");
}}}

// License Autoactions
if ($ID!="" and $module=="Licenses"){
  // Retrieve parameters about the case
  $sql="SELECT * FROM contracts WHERE ID='$ID'";
  $result = mysql_query($sql);
  $row = mysql_fetch_array($result);
  $country = $row["country"];
  // Retrieve autotactions
  $sql="SELECT * FROM autoactions WHERE ip_type='LICENSE' and on_off='ON'";
  $result = mysql_query($sql);
  while ($row = mysql_fetch_array($result)){
    $insert="0";
    $original_only=$row["original_only"];
    $action_type=$row["action_type"];
    $description=$row["description"];
    $due_year=$row["due_year"];
    $due_month=$row["due_month"];
    $due_day=$row["due_day"];
    $reference_date=$row["reference_date"];
    $pre_post=$row["pre_post"];
	   
    $sqla="SELECT * FROM ctr_actions WHERE action_type='$action_type' and ctr_ID='$ID'";
    $resulta=mysql_query($sqla);
    if (mysql_num_rows($resulta) != "0") {continue;}	//don't add it if one of the same name already exists
	  
	// Check for Priority Date Related Actions and insure that there is a priority date set
    if ($reference_date=="Priority Date" and $priority_date!="0000-00-00" and ($original_only=="N" /*or $original=="Y"*/)){
    $tmp = explode("-", $priority_date); // $tmp[0]=year, $tmp[1]=month, $tmp[2]=day
	$insert = "1";
    }
	// Check for Filing Date Related Actions and insure that there is a filing date set
    elseif ($reference_date=="Filing Date" and $filing_date!="0000-00-00" and ($original_only=="N" /*or $original=="Y"*/)){
	$tmp = explode("-", $filing_date); // $tmp[0]=year, $tmp[1]=month, $tmp[2]=day
	$insert = "1";
    }
	// Check for Issue Date Related Actions and insure that there is a issue date set
    elseif ($reference_date=="Issue Date" and $issue_date!="0000-00-00" and ($original_only=="N" /*or $original=="Y"*/)){
	$tmp = explode("-", $issue_date); // $tmp[0]=year, $tmp[1]=month, $tmp[2]=day
	$insert = "1";
    }
    
    if ($pre_post == "pre")	//this will negate the due dates if it's a pre action.
    {
      $due_day = -$due_day;
      $due_month = -$due_month;
      $due_year = -$due_year;
    }

	if ($insert=="1"){
	// Calculate the due date
	$respdue_year=$tmp[0]+$due_year;	
	$respdue_month=$tmp[1]+$due_month;	
	$respdue_day=$tmp[2]+$due_day;		
	//check for negative dates
	if ($respdue_day < 0) {
	  $respdue_day = $respdue_day + 31;	//this will 'carry' the month over
	  $respdue_month = $respdue_month - 1;	//^
	}
	if ($respdue_month < 0) {
	  $respdue_month = $respdue_month + 12;	//this will 'carry' the year over
	  $respdue_year = $respdue_year - 1;
	}
	// Adjust for months
	if ($respdue_month>12){
	  $respdue_month=$respdue_month-12;
	  $respdue_year=$respdue_year+1;}
	// Click back days for short months
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  $respdue_day=$respdue_day-1;
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  $respdue_day=$respdue_day-1;
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  $respdue_day=$respdue_day-1;
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  continue;	//this will not attach the action if the date is invalid--aka if the ref date is 0
    $respdue_date = $respdue_year."-".$respdue_month."-".$respdue_day;
	// if time passed, then mark as done for certain actions
	if (($respdue_date < $today))
	  $done="Y";
	  else $done="N";
    $sql_x="INSERT INTO ctr_actions SET
	  customer_ID='$customer_ID',
	  ctr_ID='$ID',
	  autogen='Y',
	  action_type='$action_type',
	  respdue_date='$respdue_date',
	  description='$description',
	  done='$done',
      creator='$fullname',
	  create_date='$today'";
	  if (!mysql_query($sql_x))
        error("A database error occurred in processing your ".
        "submission.\\nIf this error persists, please ".
        "contact your HelpDesk.");
}}}

// NDA Autoactions
if ($ID!="" and $module=="NDA"){
  // Retrieve parameters about the case
  $sql="SELECT * FROM contracts WHERE ID='$ID'";
  $result = mysql_query($sql);
  $row = mysql_fetch_array($result);
  $country = $row["country"];
  // Retrieve autotactions
  $sql="SELECT * FROM autoactions WHERE ip_type='NDA' and on_off='ON'";
  $result = mysql_query($sql);
  while ($row = mysql_fetch_array($result)){
    $insert="0";
    $original_only=$row["original_only"];
    $action_type=$row["action_type"];
    $description=$row["description"];
    $due_year=$row["due_year"];
    $due_month=$row["due_month"];
    $due_day=$row["due_day"];
    $reference_date=$row["reference_date"];
    $pre_post=$row["pre_post"];
	   
    $sqla="SELECT * FROM ctr_actions WHERE action_type='$action_type' and ctr_ID='$ID'";
    $resulta=mysql_query($sqla);
    if (mysql_num_rows($resulta) != "0") {continue;}	//don't add it if one of the same name already exists
	   
	// Check for Priority Date Related Actions and insure that there is a priority date set
    if ($reference_date=="Priority Date" and $priority_date!="0000-00-00" and ($original_only=="N" /*or $original=="Y"*/)){
    $tmp = explode("-", $priority_date); // $tmp[0]=year, $tmp[1]=month, $tmp[2]=day
	$insert = "1";
    }
	// Check for Filing Date Related Actions and insure that there is a filing date set
    elseif ($reference_date=="Filing Date" and $filing_date!="0000-00-00" and ($original_only=="N" /*or $original=="Y"*/)){
	$tmp = explode("-", $filing_date); // $tmp[0]=year, $tmp[1]=month, $tmp[2]=day
	$insert = "1";
    }
	// Check for Issue Date Related Actions and insure that there is a issue date set
    elseif ($reference_date=="Issue Date" and $issue_date!="0000-00-00" and ($original_only=="N" /*or $original=="Y"*/)){
	$tmp = explode("-", $issue_date); // $tmp[0]=year, $tmp[1]=month, $tmp[2]=day
	$insert = "1";
    }
    
    if ($pre_post == "pre")	//this will negate the due dates if it's a pre action.
    {
      $due_day = -$due_day;
      $due_month = -$due_month;
      $due_year = -$due_year;
    }

	if ($insert=="1"){
	// Calculate the due date
	$respdue_year=$tmp[0]+$due_year;	
	$respdue_month=$tmp[1]+$due_month;	
	$respdue_day=$tmp[2]+$due_day;		
	//check for negative dates
	if ($respdue_day < 0) {
	  $respdue_day = $respdue_day + 31;	//this will 'carry' the month over
	  $respdue_month = $respdue_month - 1;	//^
	}
	if ($respdue_month < 0) {
	  $respdue_month = $respdue_month + 12;	//this will 'carry' the year over
	  $respdue_year = $respdue_year - 1;
	}
	// Adjust for months
	if ($respdue_month>12){
	  $respdue_month=$respdue_month-12;
	  $respdue_year=$respdue_year+1;}
	// Click back days for short months
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  $respdue_day=$respdue_day-1;
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  $respdue_day=$respdue_day-1;
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  $respdue_day=$respdue_day-1;
	if (!checkdate($respdue_month, $respdue_day, $respdue_year))
	  continue;	//this will not attach the action if the date is invalid--aka if the ref date is 0
    $respdue_date = $respdue_year."-".$respdue_month."-".$respdue_day;
	// if time passed, then mark as done for certain actions
	if (($respdue_date < $today)/* and ($action_type=="Foreign Filing" or $action_type=="Publication")*/)	//action types dynamic; not robust
	  $done="Y";
	  else $done="N";
    $sql_x="INSERT INTO ctr_actions SET
	  customer_ID='$customer_ID',
	  ctr_ID='$ID',
	  autogen='Y',
	  action_type='$action_type',
	  respdue_date='$respdue_date',
	  description='$description',
	  done='$done',
      creator='$fullname',
	  create_date='$today'";
	  if (!mysql_query($sql_x))
        error("A database error occurred in processing your ".
        "submission.\\nIf this error persists, please ".
        "contact your HelpDesk.");
}}}?>
