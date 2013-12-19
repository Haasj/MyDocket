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
//	6/20/2013	changed everything to sort by the docket_alt instead of the docket. This sorts by the master docket number
//	6/21/2013	added list by index functionality and took out expanded/docket. they didn't do anything anyway.
//	6/25/2013	adjusted the width slightly on ECS docket to fix formatting
//	7/3/2013	added client and status to list A
//	7/10/2013	added secondary sort functionality. for details, see patents_list.php
//	7/17/2013	added $user_level to header call
//	7/19/2013	enabled index sort for searches. there were no specialized queries
//	7/29/2013	enabled custom list numbers. for more details, look at patents_list.php
//	7/31/2013	enabled custom list view. for more details, loot at patents_list.php
//	8/2/2013	added link to record from custom listing title
//	8/13/2013	deleted org and client checking since for some reason it started to not show the listing
//	8/15/2013	added tooltips on the sorting images so I could delete the red text telling the user what to do. it has to be on the image, not the title of the link because IE makes sense...
//	8/27/2013	cleaned up the code and added some comments
//copyrights_list.php -- User Access Level: Viewer
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
// Set $var string
if ($SORT=="COPYFAM")
  $var="SORT=COPYFAM&COPYFAM_ID=$COPYFAM_ID";
elseif ($SORT=="SEARCH")
  $var="SORT=SEARCH&title=$title&docket_alt=$docket_alt&".
  "firm=$firm&firm_contact=$firm_contact&client=$client&client_contact=$client_contact&".
  "authors=$authors&status=$status&filing_type=$filing_type&".
  "country=$country&filing_date=$filing_date&pub_date=$pub_date&".
  "issue_date=$issue_date&c_no=$c_no";
else $var="SORT=ALL";
// Set Defaults
if($number=="") {$number=50;}
if ($ORDER=="") {$ORDER="docket_alt";}
if($ORDER2=="") {$ORDER2="docket_alt";}
if ($REPORT=="") {$REPORT="A";}
if ($NEXT=="1") {$START=$START+$number;}
elseif ($NEXT=="-1") {$START=$START-$number;}
else $START="0";
?>
<table align="left" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">Reports:&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=A&<?=$var;?>">All</a>&nbsp;|&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=B&<?=$var;?>">Custom</a>&nbsp;|&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=Z&<?=$var;?>">Index</a></td>
	</tr>
</table>
<? if ($REPORT != "B") {?>
<form action="<?=$PHP_SELF;?>">
<input type="hidden" name="module" value="<?=$module;?>">
<input type="hidden" name="REPORT" value="<?=$REPORT;?>">
<input type="hidden" name="NEXT" value="<?=$NEXT;?>">
<input type="hidden" name="START" value="<?=$START;?>">
<input type="hidden" name="ORDER" value="<?=$ORDER;?>">
<input type="hidden" name="var" value="<?=$var;?>">
<input type="hidden" name="ORDER2" value="<?=$ORDER2;?>">
<input type="hidden" name="SORT" value="<?=$SORT;?>">
<input type="hidden" name="title" value="<?=$title;?>">
<input type="hidden" name="docket_alt" value="<?=$docket_alt;?>">
<input type="hidden" name="firm" value="<?=$firm;?>">
<input type="hidden" name="firm_contact" value="<?=$firm_contact;?>">
<input type="hidden" name="client" value="<?=$client;?>">
<input type="hidden" name="filing_type" value="<?=$filing_type;?>">
<input type="hidden" name="filing_date" value="<?=$filing_date;?>">
<input type="hidden" name="country" value="<?=$country;?>">
<input type="hidden" name="issue_date" value="<?=$issue_date;?>">
<input type="hidden" name="authors" value="<?=$authors;?>">
<input type="hidden" name="pub_date" value="<?=$pub_date;?>">
<input type="hidden" name="c_no" value="<?=$c_no;?>">
<input type="hidden" name="description" value="<?=$description;?>">
<input type="hidden" name="notes" value="<?=$notes;?>">
<input type="hidden" name="status" value="<?=$status;?>">
<table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">
    <select name=number size="1">
	      <option><?=$number;?></option><option>10</option><option>15</option><option>25</option><option>50</option></select>
    <input type=submit value=Go>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&NEXT=0&START=<?=$START;?>&ORDER=<?=$ORDER;?>&<?=$var;?>&ORDER2=<?=$ORDER2;?>&number=<?=$number;?>">First <?=$number;?></a>&nbsp;|&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&NEXT=-1&START=<?=$START;?>&ORDER=<?=$ORDER;?>&<?=$var;?>&ORDER2=<?=$ORDER2;?>&number=<?=$number;?>">Previous <?=$number;?></a>&nbsp;|&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&NEXT=1&START=<?=$START;?>&ORDER=<?=$ORDER;?>&<?=$var;?>&ORDER2=<?=$ORDER2;?>&number=<?=$number;?>">Next <?=$number;?></a>
  </td></tr>
</table><br><br>
<?      
if ($SORT=="ALL") echo("<center>LIST ALL COPYRIGHTS");
else echo("<center>LIST COPYRIGHT SEARCH RESULTS");
?><br><br>
<br><?}?>
<!-- REPORT TYPE A -->
<? if ($REPORT=="A") {?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="140"><U>ECS Docket</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&ORDER=docket_alt&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&ORDER=docket_alt DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=docket_alt"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=docket_alt DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="175"><U>Title</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&ORDER=title&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&ORDER=title DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=title"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=title DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="125"><U>Client</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&ORDER=client&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&ORDER=client DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=client"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=client DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="125"><U>Status</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&ORDER=status&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&ORDER=status DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=status"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=status DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="125"><U>Filing Date</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&ORDER=filing_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&ORDER=filing_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=filing_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=filing_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="125"><U>Issue Date</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&ORDER=issue_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&ORDER=issue_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=issue_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=issue_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if ($SORT == "ALL")
  $sql="SELECT * FROM copyrights WHERE customer_ID = '$customer_ID' ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM copyrights WHERE	  	  
    customer_ID = '$customer_ID' and
    docket_alt LIKE '%$docket_alt%' and
    firm LIKE '%$firm%' and
    firm_contact LIKE '%$firm_contact%' and
    client LIKE '%$client%' and
    title LIKE '%$title%' and
    filing_type LIKE '%$filing_type%' and
    authors LIKE '%$authors%' and
    country LIKE '%$country%' and
	status LIKE '%$status%' and
	filing_date LIKE '%$filing_date%' and
	pub_date LIKE '%$pub_date%' and
    issue_date LIKE '%$issue_date%' and
	c_no LIKE '%$c_no%' and
    description LIKE '%$description%' and
    notes LIKE '%$notes%'
	ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";	
$result=mysql_query($sql);
// Print the records
while($row=mysql_fetch_array($result)){
  $ID=$row["ID"];
  $org=$row["org"];
  $docket_alt=$row["docket_alt"];
  $title=$row["title"];
  $filing_date=$row["filing_date"];
  $issue_date=$row["issue_date"];
  $ser_no=$row["ser_no"];
  $status=$row["status"];
  $client=$row["client"];
  $c_no=$row["c_no"];
?>
<tr bgcolor=EEEEEE>
  <td width="100"><small><?=$docket_alt;?></small></td>
  <td width="175"><small><a href="copyrights_edit.php?module=<?=$module;?>&SORT=<?=$SORT?>&VAR=<?=$var?>&ID=<?=$ID;?>&I=1&EDIT=N"><?=$title;?></a></small></td>
  <td width="125"><small><?=$client;?></small></td>
  <td width="125"><small><?=$status;?>
    <? if ($respdue_date!="0000-00-00") echo("<br>".$respdue_date);?></small></td>
  <td width="125"><small><?=$filing_date;?><br><?=$ser_no;?></small></td>
  <td width="125"><small><?=$c_no;?>
    <? if ($issue_date!="0000-00-00") echo("<br>".$issue_date);?></small></td>
</tr>
<?}?>
</table>
<?}

//<!-- REPORT B -->
if($REPORT=="B"){
if($ORDER=="") {$ORDER="docket_alt";};
if($ORDER2=="") {$ORDER2="docket_alt";}
  if($submit_cust==""){?>
  <form method=get action="<?=$PHP_SELF;?>">
    <input type="hidden" name="module" value="<?=$module;?>">
    <input type="hidden" name="REPORT" value="<?=$REPORT;?>">
    <input type="hidden" name="SORT" value="<?=$SORT;?>">
    <input type="hidden" name="PATFAM_ID" value="<?=$PATFAM_ID;?>">
 <br> <center>SELECT FIELDS</center><br><br>
    <table align="center" width="100%" cellpadding="5">
  <tr bgcolor=EEEEEE><font size="-2">
    <td width="200"><input type="checkbox" name="check_ecs_docket" value="Y">&nbsp;&nbsp;<U>ECS Docket</U>
    <td width="200"><input type="checkbox" name="check_firm_docket" value="Y">&nbsp;&nbsp;<U>Firm Docket</U>
    <td width="200"><input type="checkbox" name="check_title" value="Y">&nbsp;&nbsp;<U>Title</U>
    <td width="200"><input type="checkbox" name="check_status" value="Y">&nbsp;&nbsp;<U>Status</U></tr>
  <tr bgcolor=EEEEEE>
    <td width="200"><input type="checkbox" name="check_firm" value="Y">&nbsp;&nbsp;<U>Firm</U>
    <td width="200"><input type="checkbox" name="check_firm_contact" value="Y">&nbsp;&nbsp;<U>Firm Contact</U>
    <td width="200"><input type="checkbox" name="check_client" value="Y">&nbsp;&nbsp;<U>Client</U>
    <td width="200"><input type="checkbox" name="check_type" value="Y">&nbsp;&nbsp;<U>Type</U></tr>
  <tr bgcolor=EEEEEE>  
    <td width="200"><input type="checkbox" name="check_filing_date" value="Y">&nbsp;&nbsp;<U>Filing Date</U>
    <td width="200"><input type="checkbox" name="check_pub_date" value="Y">&nbsp;&nbsp;<U>Publication Date</U>
    <td width="200"><input type="checkbox" name="check_issue_date" value="Y">&nbsp;&nbsp;<U>Issue Date</U>
    <td width="200"><input type="checkbox" name="check_regi_no" value="Y">&nbsp;&nbsp;<U>Registration No.</U></tr>
  <tr bgcolor=EEEEEE>  
    <td width="200"><input type="checkbox" name="check_authors" value="Y">&nbsp;&nbsp;<U>Authors</U>
    <td width="200"><input type="checkbox" name="check_country" value="Y">&nbsp;&nbsp;<U>Country</U>
    <td width="200"><input type="checkbox" name="check_description" value="Y">&nbsp;&nbsp;<U>Description</U>
    <td width="200"><input type="checkbox" name="check_notes" value="Y">&nbsp;&nbsp;<U>Notes</U></tr>
  <tr>
    <td align="center" colspan="4">
      <hr noshade size="1" width="100%">
      <input type="submit" name="submit_cust" value="  OK  ">
    </td>
  </tr>
  </font></tr></table></form>
<?}
else	//submit_cust = pressed
{?>
  <?
  //need to update var with the new variables
  $var=$var."&check_ecs_docket=$check_ecs_docket&check_firm_docket=$check_firm_docket&check_notes=$check_notes&".
      "check_title=$check_title&check_status=$check_status&check_firm=$check_firm&".
      "check_firm_contact=$check_firm_contact&check_client=$check_client&check_type=$check_type&".
      "check_filing_date=$check_filing_date&check_pub_date=$check_pub_date&check_issue_date=$check_issue_date&".
      "check_regi_no=$check_regi_no&check_authors=$check_authors&check_country=$check_country&check_description=$check_description&".
      "submit_cust=$submit_cust";
      
      //*below* need to pass searchable values for search and check box values for custom listing
      ?>
      <form action="<?=$PHP_SELF;?>">
<input type="hidden" name="module" value="<?=$module;?>">
<input type="hidden" name="REPORT" value="<?=$REPORT;?>">
<input type="hidden" name="NEXT" value="<?=$NEXT;?>">
<input type="hidden" name="START" value="<?=$START;?>">
<input type="hidden" name="ORDER" value="<?=$ORDER;?>">
<input type="hidden" name="var" value="<?=$var;?>">
<input type="hidden" name="ORDER2" value="<?=$ORDER2;?>">
<input type="hidden" name="SORT" value="<?=$SORT;?>">
<input type="hidden" name="title" value="<?=$title;?>">
<input type="hidden" name="docket_alt" value="<?=$docket_alt;?>">
<input type="hidden" name="firm" value="<?=$firm;?>">
<input type="hidden" name="firm_contact" value="<?=$firm_contact;?>">
<input type="hidden" name="client" value="<?=$client;?>">
<input type="hidden" name="filing_type" value="<?=$filing_type;?>">
<input type="hidden" name="filing_date" value="<?=$filing_date;?>">
<input type="hidden" name="country" value="<?=$country;?>">
<input type="hidden" name="issue_date" value="<?=$issue_date;?>">
<input type="hidden" name="authors" value="<?=$authors;?>">
<input type="hidden" name="pub_date" value="<?=$pub_date;?>">
<input type="hidden" name="c_no" value="<?=$c_no;?>">
<input type="hidden" name="description" value="<?=$description;?>">
<input type="hidden" name="notes" value="<?=$notes;?>">
<input type="hidden" name="status" value="<?=$status;?>">
<input type="hidden" name="submit_cust" value="<?=$submit_cust;?>">
<input type="hidden" name="check_ecs_docket" value="<?=$check_ecs_docket;?>">
<input type="hidden" name="check_firm_docket" value="<?=$check_firm_docket;?>">
<input type="hidden" name="check_title" value="<?=$check_title;?>">
<input type="hidden" name="check_status" value="<?=$check_status;?>">
<input type="hidden" name="check_firm" value="<?=$check_firm;?>">
<input type="hidden" name="check_firm_contact" value="<?=$check_firm_contact;?>">
<input type="hidden" name="check_client" value="<?=$check_client;?>">
<input type="hidden" name="check_type" value="<?=$check_type;?>">
<input type="hidden" name="check_filing_date" value="<?=$check_filing_date;?>">
<input type="hidden" name="check_pub_date" value="<?=$check_pub_date;?>">
<input type="hidden" name="check_issue_date" value="<?=$check_issue_date;?>">
<input type="hidden" name="check_regi_no" value="<?=$check_regi_no;?>">
<input type="hidden" name="check_authors" value="<?=$check_authors;?>">
<input type="hidden" name="check_country" value="<?=$check_country;?>">
<input type="hidden" name="check_description" value="<?=$check_description;?>">
<input type="hidden" name="check_notes" value="<?=$check_notes;?>">
<table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">
    <select name=number size="1">
	      <option><?=$number;?></option><option>10</option><option>25</option><option>50</option></select>
    <input type=submit value=Go>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&NEXT=0&START=<?=$START;?>&ORDER=<?=$ORDER;?>&<?=$var;?>&ORDER2=<?=$ORDER2;?>&number=<?=$number;?>">First <?=$number;?></a>&nbsp;|&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&NEXT=-1&START=<?=$START;?>&ORDER=<?=$ORDER;?>&<?=$var;?>&ORDER2=<?=$ORDER2;?>&number=<?=$number;?>">Previous <?=$number;?></a>&nbsp;|&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&NEXT=1&START=<?=$START;?>&ORDER=<?=$ORDER;?>&<?=$var;?>&ORDER2=<?=$ORDER2;?>&number=<?=$number;?>">Next <?=$number;?></a>
  </td></tr>
</table><br><br></form>
<center>CUSTOM COPYRIGHT LISTING</center><br><br>
  <table align="center" width="100%" cellpadding="5">
  <tr bgcolor=EEEEEE><font size="-2">
<?
  //check for each checkbox check
  if ($check_ecs_docket == "Y"){?>
  <td width="150"><U>ECS Docket</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=docket_alt&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=docket_alt DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=docket_alt"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=docket_alt DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_firm_docket == "Y"){?>
  <td width="150"><U>Firm Docket</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=docket&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=docket DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=docket"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=docket DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_title == "Y"){?>
  <td width="150"><U>Title</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=title&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=title DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=title"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=title DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_status == "Y"){?>
  <td width="150"><U>Status</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=status&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=status DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=status"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=status DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_firm == "Y"){?>
  <td width="150"><U>Firm</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=firm&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=firm DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=firm"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=firm DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_firm_contact == "Y"){?>
  <td width="150"><U>Firm Contact</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=firm_contact&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=firm_contact DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=firm_contact"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=firm_contact DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_client == "Y"){?>
  <td width="150"><U>Client</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=client&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=client DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=client"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=client DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_type == "Y"){?>
  <td width="150"><U>Type</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=type&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=type DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=type"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=type DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_filing_date == "Y"){?>
  <td width="150"><U>Filing Date</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=filing_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=filing_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=filing_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=filing_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_pub_date == "Y"){?>
  <td width="150"><U>Publication Date</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pub_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pub_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pub_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pub_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_issue_date == "Y"){?>
  <td width="150"><U>Issue Date</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=issue_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=issue_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=issue_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=issue_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_regi_no == "Y"){?>
  <td width="150"><U>Registration No.</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=regi_no&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=regi_no DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=regi_no"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=regi_no DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_authors == "Y"){?>
  <td width="150"><U>Authors</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=authors&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=authors DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=authors"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=authors DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_country == "Y"){?>
  <td width="150"><U>Country</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=country&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=country DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=country"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=country DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_description == "Y"){?>
  <td width="150"><U>Description</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=description&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=description DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=description"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=description DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_notes == "Y"){?>
  <td width="150"><U>Notes</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=notes&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=notes DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=notes"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=notes DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td></tr><?}?>
<?
//SQL Query for Selecting All $TYPE IP Records
if($SORT == "ALL")
  $sql="SELECT * FROM copyrights WHERE customer_ID='$customer_ID' ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
else // ($SORT == "SEARCH")
 $sql="SELECT * FROM copyrights WHERE	  	  
    customer_ID = '$customer_ID' and
    docket_alt LIKE '%$docket_alt%' and
    firm LIKE '%$firm%' and
    firm_contact LIKE '%$firm_contact%' and
    client LIKE '%$client%' and
    title LIKE '%$title%' and
    filing_type LIKE '%$filing_type%' and
    authors LIKE '%$authors%' and
    country LIKE '%$country%' and
	status LIKE '%$status%' and
	filing_date LIKE '%$filing_date%' and
	pub_date LIKE '%$pub_date%' and
    issue_date LIKE '%$issue_date%' and
	c_no LIKE '%$c_no%' and
    description LIKE '%$description%' and
    notes LIKE '%$notes%'
	ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $ID=$row["ID"];
  $org=$row["org"];
  $docket=$row["docket"];
  $docket_alt=$row["docket_alt"];
  $title=$row["title"];
  $country=$row["country"];
  $filing_date=$row["filing_date"];
  $status=$row["status"];
  $firm=$row["firm"];
  $firm_contact=$row["firm_contact"];
  $issue_date=$row["issue_date"];
  $filing_type=$row["filing_type"];
  $client=$row["client"];
  $pub_date=$row["pub_date"];
  $c_no=$row["c_no"];
  $authors=$row["authors"];
  $description=$row["description"];
  $notes=$row["notes"];
  ?><tr bgcolor=EEEEEE><?
  if ($check_ecs_docket == "Y") {?>
  <td width="150" align="left"><small><?=$docket_alt;?></small></td><?}
  if ($check_firm_docket == "Y") {?>
  <td width="150" align="left"><small><?=$docket;?></small></td><?}
  if ($check_title == "Y") {?>
  <td width="150" align="left"><small><a href="copyrights_edit.php?module=<?=$module;?>&SORT=<?=$SORT?>&VAR=<?=$var?>&ID=<?=$ID;?>&I=1&EDIT=N"><?=$title;?></a></small></td><?}
  if ($check_status == "Y") {?>
  <td width="150" align="left"><small><?=$status;?></small></td><?}
  if ($check_firm == "Y") {?>
  <td width="150" align="left"><small><?=$firm;?></small></td><?}
  if ($check_firm_contact == "Y") {?>
  <td width="150" align="left"><small><?=$firm_contact;?></small></td><?}
  if ($check_client == "Y") {?>
  <td width="150" align="left"><small><?=$client;?></small></td><?}
  if ($check_type == "Y") {?>
  <td width="150" align="left"><small><?=$filing_type;?></small></td><?}
  if ($check_filing_date == "Y") {?>
  <td width="150" align="left"><small><?=$filing_date;?></small></td><?}
  if ($check_pub_date == "Y") {?>
  <td width="150" align="left"><small><?=$pub_date;?></small></td><?}
  if ($check_issue_date == "Y") {?>
  <td width="150" align="left"><small><?=$issue_date;?></small></td><?}
  if ($check_regi_no == "Y") {?>
  <td width="150" align="left"><small><?=$c_no;?></small></td><?}
  if ($check_authors == "Y") {?>
  <td width="150" align="left"><small><?=$authors;?></small></td><?}
  if ($check_country == "Y") {?>
  <td width="150" align="left"><small><?=$country;?></small></td><?}
  if ($check_description == "Y") {?>
  <td width="150" align="left"><small><?=$description;?></small></td><?}
  if ($check_notes == "Y") {?>
  <td width="150" align="left"><small><?=$notes;?></small></td></tr><?}
  }
}}

//<!--report type z-->
elseif ($REPORT=="Z")
{
  if($ORDER=="") $ORDER="ID";?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="150"><U>ID No.</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=copyrights.ID&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=copyrights.ID DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a></td>
  <td width="500"><U>Title</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=copyrights.title&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=copyrights.title DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if ($SORT == "ALL")
  $sql="SELECT * FROM copyrights WHERE customer_ID = '$customer_ID' ORDER BY $ORDER, docket_alt LIMIT $START, $number";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM copyrights WHERE	  	  
    customer_ID = '$customer_ID' and
    docket_alt LIKE '%$docket_alt%' and
    firm LIKE '%$firm%' and
    firm_contact LIKE '%$firm_contact%' and
    client LIKE '%$client%' and
    title LIKE '%$title%' and
    filing_type LIKE '%$filing_type%' and
    authors LIKE '%$authors%' and
    country LIKE '%$country%' and
	status LIKE '%$status%' and
	filing_date LIKE '%$filing_date%' and
	pub_date LIKE '%$pub_date%' and
    issue_date LIKE '%$issue_date%' and
	c_no LIKE '%$c_no%' and
    description LIKE '%$description%' and
    notes LIKE '%$notes%'
	ORDER BY $ORDER, docket_alt LIMIT $START, $number";	
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $ID=$row["ID"];
  $title=$row["title"];
?>
<tr bgcolor=EEEEEE>
  <td width="150"><small><a href="copyrights_edit.php?module=<?=$module;?>&SORT=<?=$SORT?>&VAR=<?=$var?>&ID=<?=$ID;?>&I=1&EDIT=N"><?=$ID;?></a></small></td>
  <td width="500"><small><?=$title;?></small></td>
</tr>
<?}?>
</table>
<?}
html_footer(); ?>
