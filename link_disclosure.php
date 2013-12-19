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
//	6/20/2013   	Created by copying the code from disclosures_list.php
//                  	gets to the bottom if done is something
//                  	does the db adding at the bottom. works.
//	7/17/2013	added $user_level to header call
//	7/18/2013	updated to work with adding patents to families as well. the only thing i had to change was to pass the patfam as well to the done screen, then check to see if patfam or disc_id
//			was populated, and update the correct db accordingly.
//	8/29/2013	cleaned up the code a little
//link_disclosure.php
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
// Set $var string
if($done==""){
if($SORT=="DISCFAM")
  $var="SORT=DISCFAM&DISCFAM_ID=$DISCFAM_ID";
elseif($SORT=="SEARCH"){
  $var="SORT=SEARCH&title=$title&docket_alt=$docket_alt&".
  "firm=$firm&firm_contact=$firm_contact&client=$client&client_contact=$client_contact&".
  "country=$country&status=$status&invention_date=$invention_date";//.
}
else $var="SORT=ALL";
// Set Defaults
if($REPORT=="") $REPORT="A";
if($NEXT=="1") $START=$START+50;
  else $START="0";
?>
<table align="left" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">Reports:&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=A&<?=$var;?>">All</a>&nbsp;|&nbsp;
    <!--<a href="<?//=$PHP_SELF;?>?module=<?//=$module;?>&REPORT=B&<?//=$var;?>">Expanded</a>&nbsp;|&nbsp;-->
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
<? if($SORT=="ALL") echo("<center>LIST ALL DISCLOSURES");
  else echo("<center>LIST DISCLOSURE SEARCH RESULTS");
  echo (" -- REPORT TYPE ".$REPORT."<br>");?><br>
<br>
  
<!-- REPORT A -->
<? if($REPORT=="ALL" or $REPORT=="A"){
if($ORDER=="") $ORDER="docket_alt";?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="225"><U>Docket</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=docket_alt&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=docket_alt DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="350"><U>Title</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=title&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=title DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="225"><U>Invention Date</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=invention_date&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=invention_date DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if($SORT == "ALL")
  $sql="SELECT * FROM disc_filings WHERE customer_ID='$customer_ID' and (org='$member' or client='$member' or firm='$member') ORDER BY $ORDER, docket_alt LIMIT $START, 50";
elseif($SORT == "DISCFAM")
  $sql="SELECT * FROM disc_filings WHERE customer_ID='$customer_ID' and discfam_ID='$DISCFAM_ID' and (org='$member' or client='$member' or firm='$member') ORDER BY $ORDER, docket_alt LIMIT $START, 50";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM disc_filings WHERE	  	  
    customer_ID = '$customer_ID' and
    docket_alt LIKE '%$docket_alt%' and
	(org='$member' or client='$member' or firm='$member') and
    firm LIKE '%$firm%' and
    firm_contact LIKE '%$firm_contact%' and
    client LIKE '%$client%' and
    title LIKE '%$title%' and
    inventors LIKE '%$inventors%' and
    country LIKE '%$country%' and
	status LIKE '%$status%' and
	invention_date LIKE '%$invention_date%'
	ORDER BY $ORDER, docket_alt LIMIT $START, 50";

$result=mysql_query($sql);
//Print the records

while($row=mysql_fetch_array($result)){
  $org=$row["org"];
  $docket_alt=$row["docket_alt"];
  $title=$row["title"];
  $country=$row["country"];
  $invention_date=$row["invention_date"];
  $status=$row["status"];
  $DISC_ID=$row["disc_id"];
  ?>
  <tr bgcolor=EEEEEE>
    <td width="100"><small><?=$docket_alt;?></small></td>
    <td width="300"><small><a href="link_disclosure.php?done=Y&DISC_ID=<?=$DISC_ID;?>&pat_ID=<?=$pat_ID;?>&DISCFAM_ID=<?=$DISCFAM_ID;?>"><?=$title;?></a></small></td>
    <td width="125"><small><? if($invention_date!="0000-00-00") echo("<br>".$invention_date);?></small></td>
  </tr>
<?}?>
</table>
<?}?>

<!-- REPORT C -->
<? if($REPORT=="C"){
if($ORDER=="") $ORDER="disc_actions.respdue_date";?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="100"><U>Docket</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=disc_filings.docket_alt&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=disc_filings.docket_alt DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="300"><U>Title</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=disc_filings.title&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=disc_filings.title DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="250"><U>Open Actions</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=disc_actions.respdue_date&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=disc_actions.respdue_date DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if($SORT == "ALL")
  $sql="SELECT * FROM disc_filings, disc_actions WHERE
    disc_filings.customer_ID='$customer_ID' and
    (disc_filings.org='$member' or disc_filings.client='$member' or disc_filings.firm='$member') and
    disc_actions.disc_id=disc_filings.disc_id and
	disc_actions.done='N'
    ORDER BY $ORDER, disc_filings.docket_alt LIMIT $START, 50";
elseif($SORT == "DISCFAM")    
  $sql="SELECT * FROM disc_filings, disc_actions WHERE
    disc_filings.customer_ID='$customer_ID' and
    (disc_filings.org='$member' or disc_filings.client='$member' or disc_filings.firm='$member') and
	disc_filings.discfam_ID='$DISCFAM_ID' and
    disc_actions.disc_id=disc_filings.disc_id and
	disc_actions.done='N'
    ORDER BY $ORDER, disc_filings.docket_alt LIMIT $START, 50";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM disc_filings, disc_actions WHERE
    disc_filings.customer_ID='$customer_ID' and
    (disc_filings.org='$member' or disc_filings.client='$member' or disc_filings.firm='$member') and
    disc_actions.disc_id=disc_filings.disc_id and
	disc_actions.done='N' and
    disc_filings.docket_alt LIKE '%$docket_alt%' and
    disc_filings.firm LIKE '%$firm%' and
    disc_filings.firm_contact LIKE '%$firm_contact%' and
    disc_filings.client LIKE '%$client%' and
    disc_filings.client_contact LIKE '%$client_contact%' and
    disc_filings.title LIKE '%$title%' and
    disc_filings.inventors LIKE '%$inventors%' and
    disc_filings.country LIKE '%$country%' and
	ORDER BY $ORDER, disc_filings.docket_alt LIMIT $START, 50";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $ACTION_ID=$row["action_ID"];
  $action_type=$row["action_type"];
  $DISC_ID=$row["disc_id"];
  $docket_alt=$row["docket_alt"];
  $title=$row["title"];
  $invention_date=$row["invention_date"];
  $respdue_date=$row["respdue_date"];
?>
<tr bgcolor=EEEEEE>
  <td width="100"><small><?=$docket_alt;?></small></td>
  <td width="300"><small><a href="link_disclosure.php?done=Y&DISC_ID=<?=$DISC_ID;?>&pat_ID=<?=$pat_ID;?>&DISCFAM_ID=<?=$DISCFAM_ID;?>"><?=$title;?></a></small></td>
  <td width="250"><a href="discaction_edit.php?module=<?=$module;?>&DISC_ID=<?=$DISC_ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
	  <small><?=$action_type;?></a>&nbsp;
      Due&nbsp;<?=$respdue_date;?></small></td>
</tr>
<?}?>
</table>
<?}?>

<!-- REPORT Z -->
<? if($REPORT=="Z"){
if($ORDER=="") $ORDER="disc_id";?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="150"><U>DISC_ID No.</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=disc_filings.disc_id&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=disc_filings.disc_id DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  <td width="500"><U>Title</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=disc_filings.title&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=disc_filings.title DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
$sql="SELECT * FROM disc_filings WHERE customer_ID='$customer_ID' and (org='$member' or client='$member' or firm='$member') ORDER BY $ORDER, docket_alt LIMIT $START, 50";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $DISC_ID=$row["disc_id"];
  $title=$row["title"];
?>
<tr bgcolor=EEEEEE>
  <td width="150"><small><a href="link_disclosure.php?done=Y&DISC_ID=<?=$DISC_ID;?>&pat_ID=<?=$pat_ID;?>&DISCFAM_ID=<?=$DISCFAM_ID;?>"><?=$DISC_ID;?></a></small></td>
  <td width="500"><small><?=$title;?></small></td>
</tr>
<?}?>
</table>
<?}}

else{
//db update stuff will be here
if ($pat_ID != "")	//adding disclosure to patent
{
    $sql = "SELECT * FROM disc_filings WHERE disc_id='$DISC_ID'";
    $result = mysql_query($sql);
    $row=mysql_fetch_array($result);
        $docket_alt = $row["docket_alt"];
        $disc_name = $row["title"];
        
    $sql = "INSERT INTO pat_disclosures SET disc_ID='$DISC_ID', pat_ID='$pat_ID', disc_docket_alt='$docket_alt', disc_name='$disc_name'";
    
    if(!mysql_query($sql))
        error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
}

else //discfam_id is populated while pat_id is not; adding disclosure to discfam
{
  $sql = "UPDATE disc_filings SET discfam_id = '$DISCFAM_ID' WHERE disc_id = '$DISC_ID'";
  
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
