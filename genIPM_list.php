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
//	6/25/2013	Created using disclosures_list.php. did this instead of ndas even though it's longer, it has what I want
//              	going to change all disc->genipm. not sure about genipmfam and what that means... to my knowledge it won't do anything. but ndas had it, and I'm pretty sure it does nothing there
//        		I hopefully changed everything I needed to. Everything shows up correctly on all fields (created test genipm and action to go with it)
//        		wasn't working with search, but that was because in the query I still had it check for country
//	7/3/2013	added client to list A
//	7/8/2013	made it so it doesn't display list types
//	7/10/2013	added secondary sorting functionality. for details, see patents_list.php
//	7/17/2013	added $user_level to header call
//	7/19/2013	enabled index sort for search. wasn't a query for it.
//	7/30/2013	enabled custom numbers for listing. details on patents_list.php
//			changed it from status to date. still displays the same, but order/title are docket_date
//	8/1/2013	enabled custom listings. details in patents_list.php
//	8/2/2013	added link on title custom listing
//	8/13/2013	deleted org and client checking since for some reason it started to not show the listing
//	8/15/2013	added tooltips on the sorting images so I could delete the red text telling the user what to do. it has to be on the image, not the title of the link because IE makes sense...
//	8/28/2013	cleaned up the code a little
//	9/5/2013	enabled search by docket as well
//genIPM_list.php
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
// Set $var string
if($SORT=="GENIPMFAM")
  $var="SORT=GENIPMFAM&GENIPMFAM_ID=$GENIPMFAM_ID";
elseif ($SORT=="SEARCH"){
  $var="SORT=SEARCH&title=$title&docket_alt=$docket_alt&docket=$docket&".
  "firm=$firm&firm_contact=$firm_contact&client=$client&client_contact=$client_contact&".
  "docket_date=$docket_date";
}
else $var="SORT=ALL";
// Set Defaults
if($number=="") {$number=50;}
if($REPORT=="") $REPORT="A";
if ($NEXT=="1") {$START=$START+$number;}
elseif ($NEXT=="-1") {$START=$START-$number;}
else $START="0";
?>
<table align="left" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">Reports:&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=A&<?=$var;?>">All</a>&nbsp;|&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=B&<?=$var;?>">Custom</a>&nbsp;|&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=C&<?=$var;?>">Docket</a>&nbsp;|&nbsp;
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
<input type="hidden" name="docket" value="<?=$docket;?>">
<input type="hidden" name="docket_alt" value="<?=$docket_alt;?>">
<input type="hidden" name="firm" value="<?=$firm;?>">
<input type="hidden" name="firm_contact" value="<?=$firm_contact;?>">
<input type="hidden" name="client" value="<?=$client;?>">
<input type="hidden" name="docket_date" value="<?=$docket_date;?>">
<input type="hidden" name="description" value="<?=$description;?>">
<input type="hidden" name="notes" value="<?=$notes;?>">
<input type="hidden" name="status" value="<?=$status;?>">
<input type="hidden" name="client_contact" value="<?=$client_contact;?>">
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
<? if($SORT=="ALL") echo("<center>LIST ALL GENERAL IP MATTER");
  else echo("<center>LIST GENERAL IP MATTER SEARCH RESULTS");?><br><br>
<br><?}?>

<!-- REPORT A -->
<? if($REPORT=="ALL" or $REPORT=="A"){
if($ORDER=="") $ORDER="docket_alt";
if($ORDER2=="") {$ORDER2="docket_alt";}?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="130"><U>ECS Docket</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=docket_alt&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=docket_alt DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=docket_alt"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=docket_alt DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="350"><U>Title</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=title&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=title DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=title"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=title DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="225"><U>Client</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=client&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=client DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=client"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=client DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="225"><U>Docket Date</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=docket_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=docket_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=docket_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=docket_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if($SORT == "ALL")
  $sql="SELECT * FROM genipm_filings WHERE customer_ID='$customer_ID' ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
else{ // ($SORT == "SEARCH"){
  $sql="SELECT * FROM genipm_filings WHERE	  	  
    customer_ID = '$customer_ID' and
    docket LIKE '%$docket%' and
    docket_alt LIKE '%$docket_alt%' and
    firm LIKE '%$firm%' and
    firm_contact LIKE '%$firm_contact%' and
    client LIKE '%$client%' and
    title LIKE '%$title%' and
	status LIKE '%$status%' and
	docket_date LIKE '%$docket_date%'
	ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
}

$result=mysql_query($sql);
//Print the records

while($row=mysql_fetch_array($result)){
  $org=$row["org"];
  $docket_alt=$row["docket_alt"];
  $title=$row["title"];
  $country=$row["country"];
  $docket_date=$row["docket_date"];
  $status=$row["status"];
  $GENIPM_ID=$row["ID"];
  $client=$row["client"];
  ?>
  <tr bgcolor=EEEEEE>
    <td width="100"><small><?=$docket_alt;?></small></td>
    <td width="300"><small><a href="genIPM_edit.php?module=<?=$module;?>&GENIPMEDIT=1&ID=<?=$GENIPM_ID;?>&ACTIONS=Open&I=1&EDIT=N&ATTACHEDFILES=All"><?=$title;?></a></small></td>
    <td width="150"><small><?=$client;?></small></td>
    <td width="125"><small><?=$status; if($docket_date!="0000-00-00") echo("<br>".$docket_date);?></small></td>
    </small></td>
  </tr>
<?}?>
</table>
<?}?>

<!-- REPORT B -->
<? if($REPORT=="B"){
if($ORDER=="") {$ORDER="docket_alt";};
if($ORDER2=="") {$ORDER2="docket_alt";}
  if($submit_cust==""){?>
  <form method=get action="<?=$PHP_SELF;?>">
    <input type="hidden" name="module" value="<?=$module;?>">
    <input type="hidden" name="REPORT" value="<?=$REPORT;?>">
    <input type="hidden" name="SORT" value="<?=$SORT;?>">
    <input type="hidden" name="PATFAM_ID" value="<?=$PATFAM_ID;?>">
  <br><center>SELECT FIELDS</center><br><br>
    <table align="center" width="100%" cellpadding="5">
  <tr bgcolor=EEEEEE><font size="-2">
    <td width="200"><input type="checkbox" name="check_ecs_docket" value="Y">&nbsp;&nbsp;<U>ECS Docket</U>
    <td width="200"><input type="checkbox" name="check_firm_docket" value="Y">&nbsp;&nbsp;<U>Firm Docket</U>
    <td width="200"><input type="checkbox" name="check_title" value="Y">&nbsp;&nbsp;<U>Title</U>
    <td width="200"><input type="checkbox" name="check_genipm_ID" value="Y">&nbsp;&nbsp;<U>GENIPM ID</U>
    <td width="200"><input type="checkbox" name="check_status" value="Y">&nbsp;&nbsp;<U>Status</U>
    <td width="200"><input type="checkbox" name="check_docket_date" value="Y">&nbsp;&nbsp;<U>Docket Date</U></tr>
  <tr bgcolor=EEEEEE>  
    <td width="200"><input type="checkbox" name="check_firm" value="Y">&nbsp;&nbsp;<U>Firm</U>
    <td width="200"><input type="checkbox" name="check_firm_contact" value="Y">&nbsp;&nbsp;<U>Firm Contact</U>
    <td width="200"><input type="checkbox" name="check_client" value="Y">&nbsp;&nbsp;<U>Client</U>
    <td width="200"><input type="checkbox" name="check_client_contact" value="Y">&nbsp;&nbsp;<U>Client Contact</U>
    <td width="200"><input type="checkbox" name="check_description" value="Y">&nbsp;&nbsp;<U>Descritpion</U>
    <td width="200"><input type="checkbox" name="check_notes" value="Y">&nbsp;&nbsp;<U>Notes</U></tr>
  <tr>
    <td align="center" colspan="6">
      <hr noshade size="1" width="100%">
      <input type="submit" name="submit_cust" value="  OK  ">
    </td>
  </tr>
  </font></tr></table></form>
<?}
else	//submit_cust = pressed
{?>
  <?
  $var=$var."&check_ecs_docket=$check_ecs_docket&check_title=$check_title&check_status=$check_status&".
      "check_client=$check_client&".
      "check_client_contact=$check_client_contact&check_firm_docket=$check_firm_docket&check_genipm_ID=$check_genipm_ID&".
      "check_docket_date=$check_docket_date&check_firm=$check_firm&".
      "check_notes=$check_notes&check_firm_contact=$check_firm_contact&check_description=$check_description&".
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
    <input type="hidden" name="docket" value="<?=$docket;?>">
    <input type="hidden" name="docket_alt" value="<?=$docket_alt;?>">
    <input type="hidden" name="firm" value="<?=$firm;?>">
    <input type="hidden" name="firm_contact" value="<?=$firm_contact;?>">
    <input type="hidden" name="client" value="<?=$client;?>">
    <input type="hidden" name="docket_date" value="<?=$docket_date;?>">
    <input type="hidden" name="description" value="<?=$description;?>">
    <input type="hidden" name="notes" value="<?=$notes;?>">
    <input type="hidden" name="status" value="<?=$status;?>">
    <input type="hidden" name="client_contact" value="<?=$client_contact;?>">
    <input type="hidden" name="submit_cust" value="<?=$submit_cust;?>">
    <input type="hidden" name="check_ecs_docket" value="<?=$check_ecs_docket;?>">
    <input type="hidden" name="check_title" value="<?=$check_title;?>">
    <input type="hidden" name="check_status" value="<?=$check_status;?>">
    <input type="hidden" name="check_client" value="<?=$check_client;?>">
    <input type="hidden" name="check_client_contact" value="<?=$check_client_contact;?>">
    <input type="hidden" name="check_firm_docket" value="<?=$check_firm_docket;?>">
    <input type="hidden" name="check_genipm_ID" value="<?=$check_genipm_ID;?>">
    <input type="hidden" name="check_docket_date" value="<?=$check_docket_date;?>">
    <input type="hidden" name="check_firm" value="<?=$check_firm;?>">
    <input type="hidden" name="check_notes" value="<?=$check_notes;?>">
    <input type="hidden" name="check_firm_contact" value="<?=$check_firm_contact;?>">
    <input type="hidden" name="check_description" value="<?=$check_description;?>">
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
<center>CUSTOM GENERAL IPM LISTING</center><br><br>
  <table align="center" width="100%" cellpadding="5">
  <tr bgcolor=EEEEEE><font size="-2">
<?
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
  <?if ($check_genipm_ID == "Y"){?>
  <td width="150"><U>GENIPM ID</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=ID&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=ID DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=ID"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=ID DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_status == "Y"){?>
  <td width="150"><U>Status</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=status&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=status DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=status"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=status DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_docket_date == "Y"){?>
  <td width="150"><U>Docket Date</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=docket_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=docket_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=docket_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=docket_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
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
  <?if ($check_client_contact == "Y"){?>
  <td width="150"><U>Client Contact</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=client_contact&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=client_contact DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=client_contact"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=client_contact DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
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
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=notes DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
<?
//SQL Query for Selecting All $TYPE IP Records
if($SORT == "ALL")
  $sql="SELECT * FROM genipm_filings WHERE customer_ID='$customer_ID' ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
else{ // ($SORT == "SEARCH"){
  $sql="SELECT * FROM genipm_filings WHERE	  	  
    customer_ID = '$customer_ID' and
    docket LIKE '%$docket%' and
    docket_alt LIKE '%$docket_alt%' and
    firm LIKE '%$firm%' and
    firm_contact LIKE '%$firm_contact%' and
    client LIKE '%$client%' and
    title LIKE '%$title%' and
	status LIKE '%$status%' and
	docket_date LIKE '%$docket_date%'
	ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";}
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $GENIPM_ID=$row["ID"];
  $org=$row["org"];
  $docket_alt=$row["docket_alt"];
  $title=$row["title"];
  $docket=$row["docket"];
  $docket_date=$row["docket_date"];
  $status=$row["status"];
  $client=$row["client"];
  $client_contact=$row["client_contact"];
  $firm=$row["firm"];
  $firm_contact=$row["firm_contact"];
  $notes=$row["notes"];
  $description=$row["description"];
  ?><tr bgcolor=EEEEEE><?
  if ($check_ecs_docket == "Y") {?>
  <td width="150" align="left"><small><?=$docket_alt;?></small></td><?}
  if ($check_firm_docket == "Y") {?>
  <td width="150" align="left"><small><?=$docket;?></small></td><?}
  if ($check_title == "Y") {?>
  <td width="150" align="left"><small><a href="genIPM_edit.php?module=<?=$module;?>&GENIPMEDIT=1&ID=<?=$GENIPM_ID;?>&ACTIONS=Open&I=1&EDIT=N&ATTACHEDFILES=All"><?=$title;?></a></small></td><?}
  if ($check_genipm_ID == "Y") {?>
  <td width="150" align="left"><small><?=$GENIPM_ID;?></small></td><?}
  if ($check_status == "Y") {?>
  <td width="150" align="left"><small><?=$status;?></small></td><?}
  if ($check_docket_date == "Y") {?>
  <td width="150" align="left"><small><?=$docket_date;?></small></td><?}
  if ($check_firm == "Y") {?>
  <td width="150" align="left"><small><?=$firm;?></small></td><?}
  if ($check_firm_contact == "Y") {?>
  <td width="150" align="left"><small><?=$firm_contact;?></small></td><?}
  if ($check_client == "Y") {?>
  <td width="150" align="left"><small><?=$client;?></small></td><?}
  if ($check_client_contact == "Y") {?>
  <td width="150" align="left"><small><?=$client_contact;?></small></td><?}
  if ($check_description == "Y") {?>
  <td width="150" align="left"><small><?=$description;?></small></td><?}
  if ($check_notes == "Y") {?>
  <td width="150" align="left"><small><?=$notes;?></small></td></tr><?}
  }
} 
}?>

<!-- REPORT C -->
<? if($REPORT=="C"){
if($ORDER=="") {$ORDER="genipm_actions.respdue_date";}
if($ORDER2=="") {$ORDER2="genipm_actions.respdue_date";}?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="110"><U>ECS Docket</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=genipm_filings.docket_alt&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=genipm_filings.docket_alt DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=genipm_filings.docket_alt"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=genipm_filings.docket_alt DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="300"><U>Title</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=genipm_filings.title&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=genipm_filings.title DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=genipm_filings.title"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=genipm_filings.title DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="250"><U>Open Actions</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=genipm_actions.respdue_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=genipm_actions.respdue_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=genipm_actions.respdue_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=genipm_actions.respdue_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if($SORT == "ALL")
  $sql="SELECT * FROM genipm_filings, genipm_actions WHERE
    genipm_filings.customer_ID='$customer_ID' and
    genipm_actions.genipm_ID=genipm_filings.ID and
	genipm_actions.done='N'
    ORDER BY $ORDER, $ORDER2, genipm_filings.docket_alt LIMIT $START, $number";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM genipm_filings, genipm_actions WHERE
    genipm_filings.customer_ID='$customer_ID' and
    genipm_actions.genipm_ID=genipm_filings.ID and
	genipm_actions.done='N' and
    genipm_filings.docket LIKE '%$docket%' and
    genipm_filings.docket_alt LIKE '%$docket_alt%' and
    genipm_filings.firm LIKE '%$firm%' and
    genipm_filings.firm_contact LIKE '%$firm_contact%' and
    genipm_filings.client LIKE '%$client%' and
    genipm_filings.client_contact LIKE '%$client_contact%' and
    genipm_filings.title LIKE '%$title%' and
    genipm_filings.docket_date LIKE '%$docket_date%' and
	ORDER BY $ORDER, $ORDER2, genipm_filings.docket_alt LIMIT $START, $number";

$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){ //on the ID number, on disc it pulls the same variable name from both tables. I grabbed this one from filings
  $ACTION_ID=$row["action_ID"];
  $action_type=$row["action_type"];
  $GENIPM_ID=$row["ID"];
  $docket_alt=$row["docket_alt"];
  $title=$row["title"];
  $invention_date=$row["invention_date"];
  $respdue_date=$row["respdue_date"];
?>
<tr bgcolor=EEEEEE>
  <td width="100"><small><?=$docket_alt;?></small></td>
  <td width="300"><small><a href="genIPM_edit.php?module=<?=$module;?>&GENIPMEDIT=1&ID=<?=$GENIPM_ID;?>&ACTIONS=Open&I=1&EDIT=N&ATTACHEDFILES=ALL"><?=$title;?></a></small></td>
  <td width="250"><a href="genIPMaction_edit.php?module=<?=$module;?>&GENIPM_ID=<?=$GENIPM_ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
	  <small><?=$action_type;?></a>&nbsp;
      Due&nbsp;<?=$respdue_date;?></small></td>
</tr>
<?}?>
</table>
<?}?>

<!-- REPORT Z -->
<? if($REPORT=="Z"){
if($ORDER=="") $ORDER="ID";?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="150"><U>GenIPM_ID No.</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=genipm_filings.ID&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=genipm_filings.ID DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a></td>
  <td width="500"><U>Title</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=genipm_filings.title&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=genipm_filings.title DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if($SORT == "ALL")
  $sql="SELECT * FROM genipm_filings WHERE customer_ID='$customer_ID' ORDER BY $ORDER, docket_alt LIMIT $START, $number";
else{ // ($SORT == "SEARCH"){
  $sql="SELECT * FROM genipm_filings WHERE	  	  
    customer_ID = '$customer_ID' and
    docket LIKE '%$docket%' and
    docket_alt LIKE '%$docket_alt%' and
    firm LIKE '%$firm%' and
    firm_contact LIKE '%$firm_contact%' and
    client LIKE '%$client%' and
    title LIKE '%$title%' and
	status LIKE '%$status%' and
	docket_date LIKE '%$docket_date%'
	ORDER BY $ORDER, docket_alt LIMIT $START, $number";
}
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $GENIPM_ID=$row["ID"];
  $title=$row["title"];
?>
<tr bgcolor=EEEEEE>
  <td width="150"><small><a href="genIPM_edit.php?module=<?=$module;?>&GENIPMEDIT=1&ID=<?=$GENIPM_ID;?>&ACTIONS=Open&I=1&EDIT=N&ATTACHEDFILES=All"><?=$GENIPM_ID;?></a></small></td>
  <td width="500"><small><?=$title;?></small></td>
</tr>
<?}?>
</table>
<?}?>
<? html_footer(); ?>
