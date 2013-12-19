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
//	6/20/2013	created by copying in patents_list.php.
//                      going to be the same as the list page, but when you click on one it links it instead of opens it to be edited.
//                      when you click on something, it will call this page again with the done flag getting set to Y. then it will go to the bottom and update the db\
//                      done. updating the db correctly; I think everything is working fine.
//	7/15/2013	updated to work with adding patents to families as well. the only thing i had to change was to pass the patfam as well to the done screen, then check to see if patfam or disc_id
//			was populated, and update the correct db accordingly. works great!
//	7/17/2013	added $user_level to header call
//	8/29/2013	took out the link to the old patent office site--i'm not gonna up the links in here, or the custom view.
//			cleaned up the code a little
//link_patent.php
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
// Set $var string
if($done==""){
if($SORT=="PATFAM")
  $var="SORT=PATFAM&PATFAM_ID=$PATFAM_ID";
elseif($SORT=="SEARCH")
  $var="SORT=SEARCH&title=$title&docket_alt=$docket_alt&".
  "firm=$firm&firm_contact=$firm_contact&client=$client&filing_type=$filing_type&".
  "country=$country&priority_date=$priority_date&".
  "filing_date=$filing_date&ser_no=$ser_no&pub_date=$pub_date&".
  "pub_no=$pub_no&issue_date=$issue_date&pat_no=$pat_no&status=$status";
else $var="SORT=ALL";
// Set Defaults
if($REPORT=="") $REPORT="A";
if($NEXT=="1") $START=$START+50;
  else $START="0";
?>
<table align="left" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">Reports:&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=A&<?=$var;?>">All</a>&nbsp;|&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=C&<?=$var;?>">Docket</a>&nbsp;|&nbsp;
	<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=Z&<?=$var;?>">Index</a></td>
	</tr>
</table>
<table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&NEXT=0&START=<?=$START;?>&ORDER=<?=$ORDER;?>&<?=$var;?>">First 50</a>&nbsp;|&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&NEXT=1&START=<?=$START;?>&ORDER=<?=$ORDER;?>&<?=$var;?>">Next 50</a>
  </td></tr>
</table><br><br>
<? if($SORT=="ALL") echo("<center>LIST ALL PATENTS");
  else echo("<center>LIST PATENT SEARCH RESULTS");
  echo (" -- REPORT TYPE ".$REPORT."<br>");?><br>
<br>
  
<!-- REPORT A -->
<? if($REPORT=="ALL" or $REPORT=="A"){
if($ORDER=="") $ORDER="docket_alt";?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="100"><U>Docket</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=docket_alt&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=docket_alt DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="70"><U>Type</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=filing_type&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=filing_type DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="300"><U>Title</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=title&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=title DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="125"><U>Filing Data</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=filing_date&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=filing_date DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="125"><U>Issue Data</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=issue_date&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=issue_date DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if($SORT == "ALL")
  $sql="SELECT * FROM pat_filings WHERE customer_ID='$customer_ID' and (org='$member' or client='$member' or firm='$member') ORDER BY $ORDER, docket_alt LIMIT $START, 50";
elseif($SORT == "PATFAM")
  $sql="SELECT * FROM pat_filings WHERE customer_ID='$customer_ID' and patfam_ID='$PATFAM_ID' and (org='$member' or client='$member' or firm='$member') ORDER BY $ORDER, docket_alt LIMIT $START, 50";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM pat_filings WHERE	  	  
    customer_ID = '$customer_ID' and
    docket_alt LIKE '%$docket_alt%' and
	(org='$member' or client='$member' or firm='$member') and
    firm LIKE '%$firm%' and
    firm_contact LIKE '%$firm_contact%' and
    client LIKE '%$client%' and
    title LIKE '%$title%' and
    filing_type LIKE '%$filing_type%' and
    inventors LIKE '%$inventors%' and
    country LIKE '%$country%' and
	status LIKE '%$status%' and
	filing_date LIKE '%$filing_date%' and
	pub_date LIKE '%$pub_date%' and
    issue_date LIKE '%$issue_date%' and
	pat_no LIKE '%$pat_no%'
	ORDER BY $ORDER, docket_alt LIMIT $START, 50";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $PAT_ID=$row["pat_ID"];
  $org=$row["org"];
  $docket_alt=$row["docket_alt"];
  $title=$row["title"];
  $country=$row["country"];
  $filing_date=$row["filing_date"];
  $ser_no=$row["ser_no"];
  $status=$row["status"];
  $respdue_date=$row["respdue_date"];
  $pat_no=$row["pat_no"];
  $issue_date=$row["issue_date"];
  $filing_type=$row["filing_type"];
?>
<tr bgcolor=EEEEEE>
  <td width="100"><small><?=$docket_alt;?></small></td>
  <td width="70"><small><?=$filing_type;?></small></td>
  <td width="300"><small><a href="<?=$PHP_SELF;?>?done=Y&PAT_ID=<?=$PAT_ID;?>&disc_ID=<?=$disc_ID;?>&PATFAM_ID=<?=$PATFAM_ID;?>"><?=$title;?></a></small></td>
  <td width="125"><small><?=$ser_no;?><? if($filing_date!="0000-00-00") echo("<br>".$filing_date);?></small></td>
  <td width="125"><small><?=$pat_no;?>
  <? if($issue_date!="0000-00-00") echo("<br>".$issue_date);?></small></td>
</tr>
<?}?>
</table>
<?}?>

<!-- REPORT C -->
<? if($REPORT=="C"){
if($ORDER=="") $ORDER="pat_actions.respdue_date";?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="100"><U>Docket</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.docket_alt&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.docket_alt DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="300"><U>Title</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.title&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.title DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="250"><U>Open Actions</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_actions.respdue_date&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_actions.respdue_date DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if($SORT == "ALL")
  $sql="SELECT * FROM pat_filings, pat_actions WHERE
    pat_filings.customer_ID='$customer_ID' and
    (pat_filings.org='$member' or pat_filings.client='$member' or pat_filings.firm='$member') and
    pat_actions.pat_ID=pat_filings.pat_ID and
	pat_actions.done='N'
    ORDER BY $ORDER, pat_filings.docket_alt LIMIT $START, 50";
elseif($SORT == "PATFAM")    
  $sql="SELECT * FROM pat_filings, pat_actions WHERE
    pat_filings.customer_ID='$customer_ID' and
    (pat_filings.org='$member' or pat_filings.client='$member' or pat_filings.firm='$member') and
	pat_filings.patfam_ID='$PATFAM_ID' and
    pat_actions.pat_ID=pat_filings.pat_ID and
	pat_actions.done='N'
    ORDER BY $ORDER, pat_filings.docket_alt LIMIT $START, 50";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM pat_filings, pat_actions WHERE
    pat_filings.customer_ID='$customer_ID' and
    (pat_filings.org='$member' or pat_filings.client='$member' or pat_filings.firm='$member') and
    pat_actions.pat_ID=pat_filings.pat_ID and
	pat_actions.done='N' and
    pat_filings.docket_alt LIKE '%$docket_alt%' and
    pat_filings.firm LIKE '%$firm%' and
    pat_filings.firm_contact LIKE '%$firm_contact%' and
    pat_filings.client LIKE '%$client%' and
    pat_filings.client_contact LIKE '%$client_contact%' and
    pat_filings.title LIKE '%$title%' and
    pat_filings.filing_type LIKE '%$filing_type%' and
    pat_filings.inventors LIKE '%$inventors%' and
    pat_filings.country LIKE '%$country%' and
	pat_filings.status LIKE '%$status%' and
	pat_filings.filing_date LIKE '%$filing_date%' and
	pat_filings.pub_date LIKE '%$pub_date%' and
    pat_filings.issue_date LIKE '%$issue_date%' and
	pat_filings.pat_no LIKE '%$pat_no%' and
	ORDER BY $ORDER, pat_filings.docket_alt LIMIT $START, 50";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $ACTION_ID=$row["action_ID"];
  $action_type=$row["action_type"];
  $PAT_ID=$row["pat_ID"];
  $docket_alt=$row["docket_alt"];
  $title=$row["title"];
  $filing_date=$row["filing_date"];
  $ser_no=$row["ser_no"];
  $respdue_date=$row["respdue_date"];
  $pat_no=$row["pat_no"];
  $issue_date=$row["issue_date"];
?>
<tr bgcolor=EEEEEE>
  <td width="100"><small><?=$docket_alt;?></small></td>
  <td width="300"><small><a href="<?=$PHP_SELF;?>?done=Y&PAT_ID=<?=$PAT_ID;?>&disc_ID=<?=$disc_ID;?>&PATFAM_ID=<?=$PATFAM_ID;?>"><?=$title;?></a></small></td>
  <td width="250"><a href="pataction_edit.php?module=<?=$module;?>&PAT_ID=<?=$PAT_ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
	  <small><?=$action_type;?></a>&nbsp;
      Due&nbsp;<?=$respdue_date;?></small></td>
</tr>
<?}?>
</table>
<?}?>

<!-- REPORT Z -->
<? if($REPORT=="Z"){
if($ORDER=="") $ORDER="pat_ID";?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="150"><U>PAT_ID No.</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.pat_id&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.pat_id DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="500"><U>Title</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.title&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.title DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
$sql="SELECT * FROM pat_filings WHERE customer_ID='$customer_ID' and (org='$member' or client='$member' or firm='$member') ORDER BY $ORDER, docket_alt LIMIT $START, 50";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $PAT_ID=$row["pat_ID"];
  $title=$row["title"];
?>
<tr bgcolor=EEEEEE>
  <td width="150"><small><a href="<?=$PHP_SELF;?>?done=Y&PAT_ID=<?=$PAT_ID;?>&disc_ID=<?=$disc_ID;?>&PATFAM_ID=<?=$PATFAM_ID;?>"><?=$PAT_ID;?></a></small></td>
  <td width="500"><small><?=$title;?></small></td>
</tr>
<?}?>
</table>
<?}}

else{
//db update stuff will be here
if ($disc_ID != "")	//adding patent to disclosure
{
  $sql = "SELECT * FROM pat_filings WHERE pat_ID='$PAT_ID'";
  $result = mysql_query($sql);
  $row=mysql_fetch_array($result);
      $docket_alt = $row["docket_alt"];
      $pat_name = $row["title"];
      
  $sql = "INSERT INTO disc_patents SET disc_ID='$disc_ID', pat_ID='$PAT_ID', pat_docket_alt='$docket_alt', pat_name='$pat_name'";
  
  if(!mysql_query($sql))
      {echo mysql_error();
      exit;}
}

else //patfam_id is populated while disc_id is not; adding patent to patfam
{
  $sql = "UPDATE pat_filings SET patfam_ID = '$PATFAM_ID' WHERE pat_ID = '$PAT_ID'";
  
  if(!mysql_query($sql))
      error("A database error occurred in processing your ".
    "submission.\\nIf this error persists, please ".
    "contact your HelpDesk.");
}?>
<br>
<table align="center" width="500"><tr><td>
<p><strong>Your record has been successfully updated.</strong></p>
<p> <a href="JavaScript:window.close()">Close</a> this page and refresh to see your updated record.</p>
</td></tr></table><br> 
<?}?>
<? html_footer(); ?>
