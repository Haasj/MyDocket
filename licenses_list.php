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
//	6/20/2013	changed it to list by docket_alt (from docket_master) instead of docket (now docket number from the firm)
//	6/21/2013	took out report type b (expanded) functionality because it looked bad
//			took out docket functionality becuase there are no dockets on licenses
//			added report type z (list by index) for symmetry between tabs.
//	6/25/2013	adjusted the width slightly on ECS docket to fix formatting
//	6/26/2013	added docket listing functionality. used continue to only list contracts that aren't NDAs.
//			also, John wanted Pay Due on the docket list as well, yet that's not even a part of the license.... I'll ask him once I get this version out to him.
//	7/3/2013	added client to list A
//	7/8/2013	deleted displaying list letter
//	7/10/2013	enabled secondary sorting functionality. for more details, see patents_list.php
//	7/17/2013	added $user_level to header call
//	7/19/2013	enabled index sort for search. there were no queries associated with it.
//	7/29/2013	enabled number for listings. details: pat_list
//	7/31/2013	enabled custom listings. for details: patents_list.php
//	8/2/2013	added link to record from custom listing title
//	8/13/2013	deleted org and client checking since for some reason it started to not show the listing
//	8/15/2013	added tooltips on the sorting images so I could delete the red text telling the user what to do. it has to be on the image, not the title of the link because IE makes sense...
//	8/29/2013	cleaned up the code a little
//licenses_list.php
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
// Set $var string
if ($SORT=="LICENSEFAM")
  $var="SORT=LICENSEFAM&LICENSEFAM_ID=$LICENSEFAM_ID";
elseif ($SORT=="SEARCH")
  $var="SORT=SEARCH&title=$title&docket_alt=$docket_alt&".
  "firm=$firm&firm_contact=$firm_contact&client=$client&client_contact=$client_contact&".
  "parties=$parties&direction=$direction&status=$status&contract_type=$contract_type&".
  "effective_date=$effective_date&expiry_date=$expiry_date&paydue_date=$paydue_date&".
  "payment_type=$payment_type";  
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
    <input type="hidden" name="docket_alt" value="<?=$docket_alt;?>">
    <input type="hidden" name="firm" value="<?=$firm;?>">
    <input type="hidden" name="firm_contact" value="<?=$firm_contact;?>">
    <input type="hidden" name="client" value="<?=$client;?>">
    <input type="hidden" name="filing_type" value="<?=$filing_type;?>">
    <input type="hidden" name="parties" value="<?=$parties;?>">
    <input type="hidden" name="direction" value="<?=$direction;?>">
    <input type="hidden" name="effective_date" value="<?=$effective_date;?>">
    <input type="hidden" name="expiry_date" value="<?=$expiry_date;?>">
    <input type="hidden" name="payments" value="<?=$payments;?>">
    <input type="hidden" name="paydue_date" value="<?=$paydue_date;?>">
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
  <?      
  if ($SORT=="ALL") echo("<center>LIST ALL LICENSES");
  else echo("<center>LIST LICENSE SEARCH RESULTS");
  ?><br><br>
  <br><?}?>

<!-- REPORT TYPE A -->
<? if ($REPORT=="A") {?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="120"><U>ECS Docket</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=docket_alt&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=docket_alt DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=docket_alt"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=docket_alt DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="155"><U>Parties</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=parties&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=parties DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=parties"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=parties DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="175"><U>Title</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=title&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=title DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=title"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=title DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="125"><U>Client</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=client&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=client DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=client"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=client DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="125"><U>Status</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=expiry_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=expiry_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=expiry_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=expiry_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="125"><U>Pay Due</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=paydue_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=paydue_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=paydue_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=paydue_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if ($SORT == "ALL")
  $sql="SELECT * FROM contracts WHERE customer_ID = '$customer_ID' and (contract_type='EXCLUSIVE' or contract_type='NON-EXCLUSIVE' or contract_type='PARTIAL-EXCLUSIVE' or contract_type='EXCLUSIVE') ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM contracts WHERE	  	  
    customer_ID = '$customer_ID' and
    docket_alt LIKE '%$docket_alt%' and
    firm LIKE '%$firm%' and
    firm_contact LIKE '%$firm_contact%' and
    client LIKE '%$client%' and
    parties LIKE '%$parties%' and
    title LIKE '%$title%' and
	direction LIKE '%$direction%' and
	contract_type LIKE '%$contract_type%' and
	status LIKE '%$status%' and
	effective_date LIKE '%$effective_date%' and
	expiry_date LIKE '%$expiry_date%' and
    paydue_date LIKE '%$paydue_date%' and
	payment_type LIKE '%$payment_type%'
	ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $ID=$row["ID"];
  $org=$row["org"];
  $docket_alt=$row["docket_alt"];
  $parties=$row["parties"];
  $title=$row["title"];
  $direction=$row["direction"];
  $contract_type = $row["contract_type"];
  $status=$row["status"];
  $client=$row["client"];
  $effective_date=$row["effective_date"];
  $expiry_date=$row["expiry_date"];
  $paydue_date=$row["paydue_date"];
  $payment_type=$row["payment_type"];
?>
<tr bgcolor=EEEEEE>
  <td width="100"><small><?=$docket_alt;?></small></td>
  <td width="125"><small><?=$parties;?><br>
  <td width="175"><small><a href="licenses_edit.php?module=<?=$module;?>&SORT=<?=$SORT?>&ID=<?=$ID?>&I=1&EDIT=N"><?=$title;?></a></small></td>
  <td width="125"><small><?=$client;?></small></td>
  <td width="125"><small><?=$status;?>
    <? if ($effective_date!="") echo("<br>".$effective_date);?>
    <? if ($expiry_date!="") echo("<br>".$expiry_date);?></small></td>
  <td width="125"><small><?=$payment_type;?>
    <? if ($payment_type!="One Time" and $paydue_date!="") echo("<br>".$paydue_date);?></small></td>
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
    <td width="200"><input type="checkbox" name="check_parties" value="Y">&nbsp;&nbsp;<U>Parties</U>
    <td width="200"><input type="checkbox" name="check_direction" value="Y">&nbsp;&nbsp;<U>Direction</U>
    <td width="200"><input type="checkbox" name="check_effective_date" value="Y">&nbsp;&nbsp;<U>Effective Date</U>
    <td width="200"><input type="checkbox" name="check_expiry_date" value="Y">&nbsp;&nbsp;<U>Expiration Date</U></tr>
  <tr bgcolor=EEEEEE>  
    <td width="200"><input type="checkbox" name="check_payment_type" value="Y">&nbsp;&nbsp;<U>Payment Type</U>
    <td width="200"><input type="checkbox" name="check_paydue_date" value="Y">&nbsp;&nbsp;<U>Payment Due Date</U>
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
  $var=$var."&check_ecs_docket=$check_ecs_docket&check_firm_docket=$check_firm_docket&check_notes=$check_notes&".
      "check_title=$check_title&check_status=$check_status&check_firm=$check_firm&".
      "check_firm_contact=$check_firm_contact&check_client=$check_client&check_type=$check_type&".
      "check_parties=$check_parties&check_direction=$check_direction&check_effective_date=$check_effective_date&".
      "check_expiry_date=$check_expiry_date&check_payment_type=$check_payment_type&check_paydue_date=$check_paydue_date&check_description=$check_description&".
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
	<input type="hidden" name="check_parties" value="<?=$check_parties;?>">
	<input type="hidden" name="check_direction" value="<?=$check_direction;?>">
	<input type="hidden" name="check_effective_date" value="<?=$check_effective_date;?>">
	<input type="hidden" name="check_expiry_date" value="<?=$check_expiry_date;?>">
	<input type="hidden" name="check_payment_type" value="<?=$check_payment_type;?>">
	<input type="hidden" name="check_paydue_date" value="<?=$check_paydue_date;?>">
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
<center>CUSTOM LICENSE LISTING</center><br><br>
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
  <?if ($check_parties == "Y"){?>
  <td width="150"><U>Parties</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=parties&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=parties DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=parties"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=parties DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_direction == "Y"){?>
  <td width="150"><U>Direction</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=direction&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=direction DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=direction"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=direction DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_effective_date == "Y"){?>
  <td width="150"><U>Effective Date</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=effective_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=effective_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=effective_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=effective_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_expiry_date == "Y"){?>
  <td width="150"><U>Expiration Date</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=expiry_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=expiry_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=expiry_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=expiry_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_payment_type == "Y"){?>
  <td width="150"><U>Payment Type</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=payment_type&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=payment_type DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=payment_type"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=payment_type DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_paydue_date == "Y"){?>
  <td width="150"><U>Payment Due Date</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=paydue_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=paydue_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=paydue_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=paydue_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
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
if ($SORT == "ALL")
  $sql="SELECT * FROM contracts WHERE customer_ID = '$customer_ID' and (contract_type='EXCLUSIVE' or contract_type='NON-EXCLUSIVE' or contract_type='PARTIAL-EXCLUSIVE' or contract_type='EXCLUSIVE') ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM contracts WHERE	  	  
    customer_ID = '$customer_ID' and
    docket_alt LIKE '%$docket_alt%' and
    firm LIKE '%$firm%' and
    firm_contact LIKE '%$firm_contact%' and
    client LIKE '%$client%' and
    parties LIKE '%$parties%' and
    title LIKE '%$title%' and
	direction LIKE '%$direction%' and
	contract_type LIKE '%$contract_type%' and
	status LIKE '%$status%' and
	effective_date LIKE '%$effective_date%' and
	expiry_date LIKE '%$expiry_date%' and
    paydue_date LIKE '%$paydue_date%' and
	payment_type LIKE '%$payment_type%'
	ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $ID=$row["ID"];
  $org=$row["org"];
  $docket=$row["docket"];
  $docket_alt=$row["docket_alt"];
  $title=$row["title"];
  $parties=$row["parties"];
  $status=$row["status"];
  $firm=$row["firm"];
  $firm_contact=$row["firm_contact"];
  $effective_date=$row["effective_date"];
  $contract_type=$row["contract_type"];
  $client=$row["client"];
  $direction=$row["direction"];
  $payment_type=$row["payment_type"];
  $paydue_date=$row["paydue_date"];
  $description=$row["description"];
  $expiry_date=$row["expiry_date"];
  $notes=$row["notes"];
  ?><tr bgcolor=EEEEEE><?
  if ($check_ecs_docket == "Y") {?>
  <td width="150" align="left"><small><?=$docket_alt;?></small></td><?}
  if ($check_firm_docket == "Y") {?>
  <td width="150" align="left"><small><?=$docket;?></small></td><?}
  if ($check_title == "Y") {?>
  <td width="150" align="left"><small><a href="licenses_edit.php?module=<?=$module;?>&SORT=<?=$SORT?>&ID=<?=$ID?>&I=1&EDIT=N"><?=$title;?></a></small></td><?}
  if ($check_status == "Y") {?>
  <td width="150" align="left"><small><?=$status;?></small></td><?}
  if ($check_firm == "Y") {?>
  <td width="150" align="left"><small><?=$firm;?></small></td><?}
  if ($check_firm_contact == "Y") {?>
  <td width="150" align="left"><small><?=$firm_contact;?></small></td><?}
  if ($check_client == "Y") {?>
  <td width="150" align="left"><small><?=$client;?></small></td><?}
  if ($check_type == "Y") {?>
  <td width="150" align="left"><small><?=$contract_type;?></small></td><?}
  if ($check_parties == "Y") {?>
  <td width="150" align="left"><small><?=$parties;?></small></td><?}
  if ($check_direction == "Y") {?>
  <td width="150" align="left"><small><?=$direction;?></small></td><?}
  if ($check_effective_date == "Y") {?>
  <td width="150" align="left"><small><?=$effective_date;?></small></td><?}
  if ($check_expiry_date == "Y") {?>
  <td width="150" align="left"><small><?=$expiry_date;?></small></td><?}
  if ($check_payment_type == "Y") {?>
  <td width="150" align="left"><small><?=$payment_type;?></small></td><?}
  if ($check_paydue_date == "Y") {?>
  <td width="150" align="left"><small><?=$paydue_date;?></small></td><?}
  if ($check_description == "Y") {?>
  <td width="150" align="left"><small><?=$description;?></small></td><?}
  if ($check_notes == "Y") {?>
  <td width="150" align="left"><small><?=$notes;?></small></td></tr><?}
  }
} }

//<!-- REPORT C -->
 elseif($REPORT=="C"){
if($ORDER=="") {$ORDER="ctr_actions.respdue_date";}
if($ORDER2=="") {$ORDER2="ctr_actions.respdue_date";}?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="110"><U>ECS Docket</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=contracts.docket_alt&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=contracts.docket_alt DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=contracts.docket_alt"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=contracts.docket_alt DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="200"><U>Title</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=contracts.title&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=contracts.title DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=contracts.title"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=contracts.title DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="150"><U>Open Actions</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=ctr_actions.respdue_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=ctr_actions.respdue_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=ctr_actions.respdue_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=ctr_actions.respdue_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="110"><U>Expiry Date</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=contracts.expiry_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=contracts.expiry_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=contracts.expiry_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=contracts.expiry_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if($SORT == "ALL")
  $sql="SELECT * FROM contracts, ctr_actions WHERE
    contracts.customer_ID='$customer_ID' and
    ctr_actions.ctr_ID=contracts.ID and
	ctr_actions.done='N'
    ORDER BY $ORDER, $ORDER2, contracts.docket_alt LIMIT $START, $number";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM contracts, ctr_actions WHERE
    contracts.customer_ID='$customer_ID' and
    ctr_actions.ctr_ID=contracts.ID and
	ctr_actions.done='N' and
    contracts.docket_alt LIKE '%$docket_alt%' and
    contracts.expiry_date LIKE '%$expiry_date%' and
    contracts.firm LIKE '%$firm%' and
    contracts.firm_contact LIKE '%$firm_contact%' and
    contracts.client LIKE '%$client%' and
    contracts.client_contact LIKE '%$client_contact%' and
    contracts.title LIKE '%$title%' and
    contracts.docket_date LIKE '%$docket_date%' and
	ORDER BY $ORDER, $ORDER2, contracts.docket_alt LIMIT $START, $number";

$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){ //on the ID number, on disc it pulls the same variable name from both tables. I grabbed this one from filings
  $ACTION_ID=$row["action_ID"];
  $action_type=$row["action_type"];
  $CTR_ID=$row["ID"];
  $docket_alt=$row["docket_alt"];
  $expiry_date=$row["expiry_date"];
  $title=$row["title"];
  $invention_date=$row["invention_date"];
  $respdue_date=$row["respdue_date"];
  
  if($row["contract_type"] == "NDA")		//need this because for some reason it won't let me check for equality in the sql. this skips the rest of the loop if it's an NDA
    continue;
?>
<tr bgcolor=EEEEEE>
  <td width="100"><small><?=$docket_alt;?></small></td>
  <td width="200"><small><a href="ndas_edit.php?module=<?=$module;?>&CTREDIT=1&ID=<?=$CTR_ID;?>&ACTIONS=Open&I=1&EDIT=N&ATTACHEDFILES=ALL"><?=$title;?></a></small></td>
  <td width="150"><a href="ctraction_edit.php?module=<?=$module;?>&CTR_ID=<?=$CTR_ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
	  <small><?=$action_type;?></a>&nbsp;
      Due&nbsp;<?=$respdue_date;?></small></td>
  <td width="100"><small><?=$expiry_date;?></small></td>
</tr>
<?}?>
</table>
<?}

//report type z
elseif ($REPORT=="Z")
{
  if($ORDER=="") $ORDER="ID";?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="150"><U>ID No.</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=contracts.ID&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=contracts.ID DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a></td>
  <td width="500"><U>Title</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=contracts.title&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=contracts.title DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if ($SORT == "ALL")
  $sql="SELECT * FROM contracts WHERE customer_ID = '$customer_ID' and (contract_type='EXCLUSIVE' or contract_type='NON-EXCLUSIVE' or contract_type='PARTIAL-EXCLUSIVE' or contract_type='EXCLUSIVE') ORDER BY $ORDER, docket_alt LIMIT $START, $number";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM contracts WHERE	  	  
    customer_ID = '$customer_ID' and
    docket_alt LIKE '%$docket_alt%' and
    firm LIKE '%$firm%' and
    firm_contact LIKE '%$firm_contact%' and
    client LIKE '%$client%' and
    parties LIKE '%$parties%' and
    title LIKE '%$title%' and
	direction LIKE '%$direction%' and
	contract_type LIKE '%$contract_type%' and
	status LIKE '%$status%' and
	effective_date LIKE '%$effective_date%' and
	expiry_date LIKE '%$expiry_date%' and
    paydue_date LIKE '%$paydue_date%' and
	payment_type LIKE '%$payment_type%'
	ORDER BY $ORDER, docket_alt LIMIT $START, $number";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $ID=$row["ID"];
  $title=$row["title"];
?>
<tr bgcolor=EEEEEE>
  <td width="150"><small><a href="licenses_edit.php?module=<?=$module;?>&SORT=<?=$SORT?>&ID=<?=$ID?>&I=1&EDIT=N"><?=$ID;?></a></small></td>
  <td width="500"><small><?=$title;?></small></td>
</tr>
<?}?>
</table>
<?} html_footer(); ?>
