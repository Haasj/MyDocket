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
//	6/19/2013	changed everything to sort by the docket_alt instead. Note: this is the docket_master number that is global. the user thinks docket is this, but to simplify I made it docket_alt
//	6/21/2013	took out report type b (expanded) functionality because it looked bad
//			added report type z (list by index) for symmetry between tabs.
//			changed it so it says ECS Docket instead of just DOcket
//	6/25/2013	adjusted the width slightly on ECS docket to fix formatting
//	7/2/2013	added status and client to list A
//	7/10/2013	deleted list from the page
//			enabled secondary sorting. for more details, look at patents_list.php
//	7/17/2013	added $user_level to header call
//	7/19/2013	added cost link for family sorts. this shows each transaction with each ecs_docket in the family with a total cost. passed type and tmfam_id
//			enabled index search for families/search. it didn't have its own selected querys, so it always listed all of them.
//	7/29/2013	enabled custom numbers for lists. for details, visit patents_list.ophp
//	7/31/2013	enabled custom lists. for details, see patents_list.php
//	8/2/2013	added link to record from custom title
//	8/13/2013	deleted org and client checking since for some reason it started to not show the listing
//	8/15/2013	added tooltips on the sorting images so I could delete the red text telling the user what to do. it has to be on the image, not the title of the link because IE
//	9/3/2013	cleaned up the code a little
// trademarks_list.php
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
// Set $var string
if ($SORT=="TMFAM")
  $var="SORT=TMFAM&TMFAM_ID=$TMFAM_ID";
elseif ($SORT=="SEARCH")
  $var="SORT=SEARCH&title=$title&docket_alt=$docket_alt&".
  "firm=$firm&firm_contact=$firm_contact&client=$client&filing_type=$filing_type&".
  "country=$country&priority_date=$priority_date&".
  "filing_date=$filing_date&".
  "ser_no=$ser_no&pub_date=$pub_date&".
  "pub_no=$pub_no&regi_date=$regi_date&".
  "tm_no=$tm_no&intl_class=$intl_class&status=$status";
else $var="SORT=ALL";
// Set Defaults
if($number=="") {$number=50;}
if ($REPORT=="") $REPORT="A";
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
<!--this way if it is report b, the first/next 50 can be passed with the updated $var -->
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
<input type="hidden" name="priority_date" value="<?=$priority_date;?>">
<input type="hidden" name="ser_no" value="<?=$ser_no;?>">
<input type="hidden" name="pub_date" value="<?=$pub_date;?>">
<input type="hidden" name="pub_no" value="<?=$pub_no;?>">
<input type="hidden" name="regi_date" value="<?=$regi_date;?>">
<input type="hidden" name="intl_class" value="<?=$intl_class;?>">
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
    <? if ($SORT == "TMFAM"){?>
    &nbsp;|&nbsp;<a href="total_cost.php?module=<?=$module;?>&type=Trademark&FAM_ID=<?=$TMFAM_ID;?>">Cost</a><?}?>
  </td></tr>
</table><br><br></form>
<? if ($SORT=="ALL") echo("<center>LIST ALL TRADEMARKS");
  else echo("<center>LIST TRADEMARK SEARCH RESULTS");?><br>  <br>      
<br><?}?>

<!-- REPORT A -->
<? if ($REPORT=="A"){
if ($ORDER=="") {$ORDER="docket_alt";}
if($ORDER2=="") {$ORDER2="docket_alt";}?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="165"><U>ECS Docket</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=docket_alt&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=docket_alt DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=docket_alt"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=docket_alt DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="300"><U>Title</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=title&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=title DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=title"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=title DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="125"><U>Client</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=client&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=client DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=client"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=client DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="125"><U>Status</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=status&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=status DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=status"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=status DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="170"><U>Filing Date</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=filing_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=filing_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=filing_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=filing_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="215"><U>Registration Date</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=regi_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=regi_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=regi_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=regi_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if ($SORT == "ALL")
  $sql="SELECT * FROM tm_filings WHERE customer_ID='$customer_ID' ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
elseif ($SORT == "TMFAM")
  $sql="SELECT * FROM tm_filings WHERE customer_ID='$customer_ID' and TMFAM_ID='$TMFAM_ID' ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM tm_filings WHERE	  	  
    customer_ID = '$customer_ID' and
    docket_alt LIKE '%$docket_alt%' and
    firm LIKE '%$firm%' and
    firm_contact LIKE '%$firm_contact%' and
    client LIKE '%$client%' and
    title LIKE '%$title%' and
    filing_type LIKE '%$filing_type%' and
    country LIKE '%$country%' and
	status LIKE '%$status%' and
	filing_date LIKE '%$filing_date%' and
	pub_date LIKE '%$pub_date%' and
    regi_date LIKE '%$regi_date%' and
	tm_no LIKE '%$tm_no%' and
	intl_class LIKE '%$intl_class%' and
    description LIKE '%$description%' and
    notes LIKE '%$notes%'
	ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $TM_ID=$row["tm_ID"];
  $org=$row["org"];
  $docket_alt=$row["docket_alt"];
  $title=$row["title"];
  $filing_date=$row["filing_date"];
  $ser_no=$row["ser_no"];
  $status=$row["status"];
  $client=$row["client"];
  $respdue_date=$row["respdue_date"];
  $tm_no=$row["tm_no"];
  $regi_date=$row["regi_date"];
?>
<tr bgcolor=EEEEEE>
  <td width="110"><small><?=$docket_alt;?></small></td>
  <td width="300"><small><a href="trademarks_edit.php?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=<?=$TM_ID;?>&ACTIONS=Open&I=1&EDIT=N"><?=$title;?></a></small></td>
  <td width="125"><small><?=$client;?></small></td>
  <td width="125"><small><?=$status;?></small></td>
  <td width="125"><small><?=$ser_no;?>
    <? if($filing_date!="0000-00-00") echo("<br>".$filing_date);?></small></td>
  <td width="145"><small><a href="http://164.195.100.11/netacgi/nph-Parser?Sect1=PTO1&Sect2=HITOFF&d=PALL&p=1&u=/netahtml/srchnum.htm&r=1&f=G&l=50&s1='<?=$tm_no;?>'.WKU.&OS=PN/<?=$tm_no;?>&RS=PN/<?=$tm_no;?>"><?=$tm_no;?></a><br>
    <? if($regi_date!="0000-00-00") echo("<br>".$regi_date);?></small></td>
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
    <td width="200"><input type="checkbox" name="check_tm_ID" value="Y">&nbsp;&nbsp;<U>Tm ID</U>
    <td width="200"><input type="checkbox" name="check_tmfam_ID" value="Y">&nbsp;&nbsp;<U>TmFam ID</U>
    <td width="200"><input type="checkbox" name="check_status" value="Y">&nbsp;&nbsp;<U>Status</U></tr>
  <tr bgcolor=EEEEEE>
    <td width="200"><input type="checkbox" name="check_firm" value="Y">&nbsp;&nbsp;<U>Firm</U>
    <td width="200"><input type="checkbox" name="check_firm_contact" value="Y">&nbsp;&nbsp;<U>Firm Contact</U>
    <td width="200"><input type="checkbox" name="check_client" value="Y">&nbsp;&nbsp;<U>Client</U>
    <td width="200"><input type="checkbox" name="check_client_contact" value="Y">&nbsp;&nbsp;<U>Client Contact</U>
    <td width="200"><input type="checkbox" name="check_country" value="Y">&nbsp;&nbsp;<U>Country</U>
    <td width="200"><input type="checkbox" name="check_type" value="Y">&nbsp;&nbsp;<U>Type</U></tr>
  <tr bgcolor=EEEEEE>  
    <td width="200"><input type="checkbox" name="check_pub_date" value="Y">&nbsp;&nbsp;<U>Publication Date</U>
    <td width="200"><input type="checkbox" name="check_pub_no" value="Y">&nbsp;&nbsp;<U>Publication No.</U>
    <td width="200"><input type="checkbox" name="check_ser_no" value="Y">&nbsp;&nbsp;<U>Serial No.</U>
    <td width="200"><input type="checkbox" name="check_regi_date" value="Y">&nbsp;&nbsp;<U>Registration Date</U>
    <td width="200"><input type="checkbox" name="check_regi_no" value="Y">&nbsp;&nbsp;<U>Registration No.</U>
    <td width="200"><input type="checkbox" name="check_filing_date" value="Y">&nbsp;&nbsp;<U>Filing Date</U></tr>
  <tr bgcolor=EEEEEE>  
    <td width="200"><input type="checkbox" name="check_original" value="Y">&nbsp;&nbsp;<U>Original</U>
    <td width="200"><input type="checkbox" name="check_intl_class" value="Y">&nbsp;&nbsp;<U>Intl. Classes</U>
    <td width="200"><input type="checkbox" name="check_description" value="Y">&nbsp;&nbsp;<U>Description</U>
    <td width="200"><input type="checkbox" name="check_products" value="Y">&nbsp;&nbsp;<U>Products</U>
    <td width="200"><input type="checkbox" name="check_notes" value="Y">&nbsp;&nbsp;<U>Notes</U>
    <td width="200"><input type="checkbox" name="check_assignment" value="Y">&nbsp;&nbsp;<U>Assignment</U></tr>
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
  $var=$var."&check_ecs_docket=$check_ecs_docket&check_firm_docket=$check_firm_docket&check_title=$check_title&".
      "check_tm_ID=$check_tm_ID&check_tmfam_ID=$check_tmfam_ID&check_status=$check_status&".
      "check_firm=$check_firm&check_firm_contact=$check_firm_contact&check_client=$check_client&".
      "check_client_contact=$check_client_contact&check_country=$check_country&check_type=$check_type&".
      "check_pub_date=$check_pub_date&check_pub_no=$check_pub_no&check_ser_no=$check_ser_no&check_regi_date=$check_regi_date&".
      "check_regi_no=$check_regi_no&check_filing_date=$check_filing_date&check_original=$check_original&check_intl_class=$check_intl_class&".
      "check_description=$check_description&check_products=$check_products&check_notes=$check_notes&check_assignment=$check_assignment&".
      "&submit_cust=$submit_cust";
      
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
	<input type="hidden" name="priority_date" value="<?=$priority_date;?>">
	<input type="hidden" name="ser_no" value="<?=$ser_no;?>">
	<input type="hidden" name="pub_date" value="<?=$pub_date;?>">
	<input type="hidden" name="pub_no" value="<?=$pub_no;?>">
	<input type="hidden" name="regi_date" value="<?=$regi_date;?>">
	<input type="hidden" name="intl_class" value="<?=$intl_class;?>">
	<input type="hidden" name="status" value="<?=$status;?>">
	<input type="hidden" name="client_contact" value="<?=$client_contact;?>">
	<input type="hidden" name="submit_cust" value="<?=$submit_cust;?>">
	<input type="hidden" name="check_ecs_docket" value="<?=$check_ecs_docket;?>">
	<input type="hidden" name="check_firm_docket" value="<?=$check_firm_docket;?>">
	<input type="hidden" name="check_title" value="<?=$check_title;?>">
	<input type="hidden" name="check_tm_ID" value="<?=$check_tm_ID;?>">
	<input type="hidden" name="check_tmfam_ID" value="<?=$check_tmfam_ID;?>">
	<input type="hidden" name="check_status" value="<?=$check_status;?>">
	<input type="hidden" name="check_firm" value="<?=$check_firm;?>">
	<input type="hidden" name="check_firm_contact" value="<?=$check_firm_contact;?>">
	<input type="hidden" name="check_client" value="<?=$check_client;?>">
	<input type="hidden" name="check_client_contact" value="<?=$check_client_contact;?>">
	<input type="hidden" name="check_country" value="<?=$check_country;?>">
	<input type="hidden" name="check_type" value="<?=$check_type;?>">
	<input type="hidden" name="check_pub_date" value="<?=$check_pub_date;?>">
	<input type="hidden" name="check_pub_no" value="<?=$check_pub_no;?>">
	<input type="hidden" name="check_ser_no" value="<?=$check_ser_no;?>">
	<input type="hidden" name="check_regi_date" value="<?=$check_regi_date;?>">
	<input type="hidden" name="check_regi_no" value="<?=$check_regi_no;?>">
	<input type="hidden" name="check_filing_date" value="<?=$check_filing_date;?>">
	<input type="hidden" name="check_original" value="<?=$check_original;?>">
	<input type="hidden" name="check_intl_class" value="<?=$check_intl_class;?>">
	<input type="hidden" name="check_description" value="<?=$check_description;?>">
	<input type="hidden" name="check_products" value="<?=$check_products;?>">
	<input type="hidden" name="check_notes" value="<?=$check_notes;?>">
	<input type="hidden" name="check_assignment" value="<?=$check_assignment;?>">
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
<center>CUSTOM TRADEMARK LISTING</center><br><br>
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
  <?if ($check_tm_ID == "Y"){?>
  <td width="150"><U>TM ID</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=tm_ID&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=tm_ID DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=tm_ID"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=tm_ID DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_tmfam_ID == "Y"){?>
  <td width="150"><U>TmFam ID</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=tmfam_ID&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=tmfam_ID DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=tmfam_ID"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=tmfam_ID DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
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
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=issue_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=issue_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
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
  <?if ($check_country == "Y"){?>
  <td width="150"><U>country</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=country&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=country DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=country"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=country DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_type == "Y"){?>
  <td width="150"><U>Type</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=type&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=type DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=type"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=type DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_pub_date == "Y"){?>
  <td width="150"><U>Publ. Date</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pub_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pub_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pub_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pub_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_pub_no == "Y"){?>
  <td width="150"><U>Publ. No.</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pub_no&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pub_no DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pub_no"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pub_no DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_ser_no == "Y"){?>
  <td width="150"><U>Serial No.</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=ser_no&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=ser_no DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=ser_no"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=ser_no DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_regi_date == "Y"){?>
  <td width="150"><U>Registration Date</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=regi_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=regi_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=regi_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=regi_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_regi_no == "Y"){?>
  <td width="150"><U>Registration No.</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=tm_no&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=tm_no DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=tm_no"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=tm_no DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_filing_date == "Y"){?>
  <td width="150"><U>Filing Date</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=filing_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=filing_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=filing_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=filing_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_original == "Y"){?>
  <td width="150"><U>Original</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=original&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=original DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=original"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=original DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_intl_class == "Y"){?>
  <td width="150"><U>Intl. Class</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=intl_class&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=intl_class DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=intl_class"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=intl_class DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_description == "Y"){?>
  <td width="150"><U>Description</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=description&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=description DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=description"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=description DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_products == "Y"){?>
  <td width="150"><U>Products</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=products&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=products DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=products"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=products DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_notes == "Y"){?>
  <td width="150"><U>Notes</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=notes&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=notes DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=notes"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=notes DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_assignment == "Y"){?>
  <td width="150"><U>Assignment</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=assignment&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=assignment DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=assignment"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=assignment DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td></tr><?}?>
<?
//SQL Query for Selecting All $TYPE IP Records
if($SORT == "ALL")
  $sql="SELECT * FROM tm_filings WHERE customer_ID='$customer_ID' ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
elseif($SORT == "TMFAM")
  $sql="SELECT * FROM tm_filings WHERE customer_ID='$customer_ID' and patfam_ID='$PATFAM_ID' ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
else // ($SORT == "SEARCH")
$sql="SELECT * FROM tm_filings WHERE	  	  
    customer_ID = '$customer_ID' and
    docket_alt LIKE '%$docket_alt%' and
    firm LIKE '%$firm%' and
    firm_contact LIKE '%$firm_contact%' and
    client LIKE '%$client%' and
    title LIKE '%$title%' and
    filing_type LIKE '%$filing_type%' and
    country LIKE '%$country%' and
	status LIKE '%$status%' and
	filing_date LIKE '%$filing_date%' and
	pub_date LIKE '%$pub_date%' and
    regi_date LIKE '%$regi_date%' and
	tm_no LIKE '%$tm_no%' and
	intl_class LIKE '%$intl_class%' and
    description LIKE '%$description%' and
    notes LIKE '%$notes%'
	ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $TM_ID=$row["tm_ID"];
  $org=$row["org"];
  $docket=$row["docket"];
  $docket_alt=$row["docket_alt"];
  $title=$row["title"];
  $country=$row["country"];
  $filing_date=$row["filing_date"];
  $ser_no=$row["ser_no"];
  $status=$row["status"];
  $regi_date=$row["regi_date"];
  $tm_no=$row["tm_no"];
  $issue_date=$row["issue_date"];
  $client=$row["client"];
  $client_contact=$row["client_contact"];
  $tmfam_ID=$row["tmfam_ID"];
  $intl_class=$row["intl_class"];
  $description=$row["description"];
  $pub_date=$row["pub_date"];
  $pub_no=$row["pub_no"];
  $firm=$row["firm"];
  $firm_contact=$row["firm_contact"];
  $original=$row["original"];
  $type=$row["filing_type"];
  $abstract=$row["abstract"];
  $products=$row["products"];
  $notes=$row["notes"];
  $assignment=$row["assignment"];
  ?><tr bgcolor=EEEEEE><?
  if ($check_ecs_docket == "Y") {?>
  <td width="150" align="left"><small><?=$docket_alt;?></small></td><?}
  if ($check_firm_docket == "Y") {?>
  <td width="150" align="left"><small><?=$docket;?></small></td><?}
  if ($check_title == "Y") {?>
  <td width="150" align="left"><small><a href="trademarks_edit.php?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=<?=$TM_ID;?>&ACTIONS=Open&I=1&EDIT=N"><?=$title;?></a></small></td><?}
  if ($check_tm_ID == "Y") {?>
  <td width="150" align="left"><small><?=$TM_ID;?></small></td><?}
  if ($check_tmfam_ID == "Y") {?>
  <td width="150" align="left"><small><?=$tmfam_ID;?></small></td><?}
  if ($check_status == "Y") {?>
  <td width="150" align="left"><small><?=$status;?></small></td><?}
  if ($check_firm == "Y") {?>
  <td width="150" align="left"><small><?=$firm;?></small></td><?}
  if ($check_firm_contact == "Y") {?>
  <td width="150" align="left"><small><?=$firm_contact;?></small></td><?}
  if ($check_client == "Y") {?>
  <td width="150" align="left"><small><?=$client;?></small></td><?}
  if ($check_client_contact == "Y") {?>
  <td width="150" align="left"><small><?=$client_contact;?></small></td><?}
  if ($check_country == "Y") {?>
  <td width="150" align="left"><small><?=$country;?></small></td><?}
  if ($check_type == "Y") {?>
  <td width="150" align="left"><small><?=$type;?></small></td><?}
  if ($check_pub_date == "Y") {?>
  <td width="150" align="left"><small><?=$pub_date;?></small></td><?}
  if ($check_pub_no == "Y") {?>
  <td width="150" align="left"><small><?=$pub_no;?></small></td><?}
  if ($check_ser_no == "Y") {?>
  <td width="150" align="left"><small><?=$ser_no;?></small></td><?}
  if ($check_regi_date == "Y") {?>
  <td width="150" align="left"><small><?=$regi_date;?></small></td><?}
  if ($check_regi_no == "Y") {?>
  <td width="150" align="left"><small><?=$tm_no;?></small></td><?}
  if ($check_filing_date == "Y") {?>
  <td width="150" align="left"><small><?=$filing_date;?></small></td><?}
  if ($check_original == "Y") {?>
  <td width="150" align="left"><small><?=$original;?></small></td><?}
  if ($check_intl_class == "Y") {?>
  <td width="150" align="left"><small><?=$intl_class;?></small></td><?}
  if ($check_description == "Y") {?>
  <td width="150" align="left"><small><?=$description;?></small></td><?}
  if ($check_products == "Y") {?>
  <td width="150" align="left"><small><?=$products;?></small></td><?}
  if ($check_notes == "Y") {?>
  <td width="150" align="left"><small><?=$notes;?></small></td><?}
  if ($check_assignment == "Y") {?>
  <td width="150" align="left"><small><?=$assignment;?></small></td></tr><?}
  }} 
}?>

<!-- REPORT C -->
<? if ($REPORT=="C"){
if ($ORDER=="") $ORDER="tm_actions.respdue_date";
if($ORDER2=="") {$ORDER2="tm_actions.respdue_date";}?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="110"><U>ECS Docket</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=tm_filings.docket_alt&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=tm_filings.docket_alt DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=tm_filings.docket_alt"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=tm_filings.docket_alt DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="300"><U>Title</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=tm_filings.title&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=tm_filings.title DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=tm_filings.title"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=tm_filings.title DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="250"><U>Open Actions</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=tm_actions.respdue_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=tm_actions.respdue_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=tm_actions.respdue_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=tm_actions.respdue_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if ($SORT == "ALL")
  $sql="SELECT * FROM tm_filings, tm_actions WHERE
    tm_filings.customer_ID='$customer_ID' and
    tm_actions.tm_ID=tm_filings.tm_ID and
	tm_actions.done='N'
    ORDER BY $ORDER, $ORDER2, tm_filings.docket_alt LIMIT $START, $number";
elseif ($SORT == "TMFAM")    
  $sql="SELECT * FROM tm_filings, tm_actions WHERE
    tm_filings.customer_ID='$customer_ID' and
	tm_filings.TMFAM_ID='$TMFAM_ID' and
    tm_actions.tm_ID=tm_filings.tm_ID and
	tm_actions.done='N'
    ORDER BY $ORDER, $ORDER2, tm_filings.docket_alt LIMIT $START, $number";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM tm_filings, tm_actions WHERE	  	  
    tm_filings.customer_ID = '$customer_ID' and
    tm_actions.tm_ID=tm_filings.tm_ID and
    tm_filings.docket_alt LIKE '%$docket_alt%' and
    tm_filings.firm LIKE '%$firm%' and
    tm_filings.firm_contact LIKE '%$firm_contact%' and
    tm_filings.client LIKE '%$client%' and
    tm_filings.title LIKE '%$title%' and
    tm_filings.filing_type LIKE '%$filing_type%' and
    tm_filings.country LIKE '%$country%' and
	tm_filings.status LIKE '%$status%' and
	tm_filings.filing_date LIKE '%$filing_date%' and
	tm_filings.pub_date LIKE '%$pub_date%' and
    tm_filings.regi_date LIKE '%$regi_date%' and
	tm_filings.tm_no LIKE '%$tm_no%' and 
	tm_filings.intl_class LIKE '%$intl_class%' and
    tm_filings.description LIKE '%$description%' and
    tm_filings.notes LIKE '%$notes%' and
	tm_actions.done='N'
    ORDER BY $ORDER, $ORDER, tm_filings.docket_alt LIMIT $START, $number";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $ACTION_ID=$row["action_ID"];
  $action_type=$row["action_type"];
  $TM_ID=$row["tm_ID"];
  $docket_alt=$row["docket_alt"];
  $title=$row["title"];
  $filing_date=$row["filing_date"];
  $ser_no=$row["ser_no"];
  $respdue_date=$row["respdue_date"];
  $tm_no=$row["tm_no"];
  $regi_date=$row["regi_date"];
?>
<tr bgcolor=EEEEEE>
  <td width="100"><small><?=$docket_alt;?></small></td>
  <td width="300"><small><a href="trademarks_edit.php?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=<?=$TM_ID;?>&ACTIONS=Open&I=1&EDIT=N"><?=$title;?></a></small></td>
  <td width="250"><a href="tmaction_edit.php?module=<?=$module;?>&TM_ID=<?=$TM_ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
	  <small><?=$action_type;?></a>&nbsp;
      Due&nbsp;<?=$respdue_date;?></small></td>
</tr>
<?}?>
</table>
<?}?>

<!-- REPORT Z -->
<? if($REPORT=="Z"){
if($ORDER=="") $ORDER="tm_ID";?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="150"><U>TM_ID No.</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=tm_filings.tm_ID&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=tm_filings.tm_ID DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a></td>
  <td width="500"><U>Title</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=tm_filings.title&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=tm_filings.title DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if ($SORT == "ALL")
  $sql="SELECT * FROM tm_filings WHERE customer_ID='$customer_ID' ORDER BY $ORDER, docket_alt LIMIT $START, $number";
elseif ($SORT == "TMFAM")
  $sql="SELECT * FROM tm_filings WHERE customer_ID='$customer_ID' and TMFAM_ID='$TMFAM_ID' ORDER BY $ORDER, docket_alt LIMIT $START, $number";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM tm_filings WHERE	  	  
    customer_ID = '$customer_ID' and
    docket_alt LIKE '%$docket_alt%' and
    firm LIKE '%$firm%' and
    firm_contact LIKE '%$firm_contact%' and
    client LIKE '%$client%' and
    title LIKE '%$title%' and
    filing_type LIKE '%$filing_type%' and
    country LIKE '%$country%' and
	status LIKE '%$status%' and
	filing_date LIKE '%$filing_date%' and
	pub_date LIKE '%$pub_date%' and
    regi_date LIKE '%$regi_date%' and
	tm_no LIKE '%$tm_no%' and
	intl_class LIKE '%$intl_class%' and
    description LIKE '%$description%' and
    notes LIKE '%$notes%'
	ORDER BY $ORDER, docket_alt LIMIT $START, $number";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $TM_ID=$row["tm_ID"];
  $title=$row["title"];
?>
<tr bgcolor=EEEEEE>
  <td width="150"><small><a href="trademarks_edit.php?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=<?=$TM_ID;?>&ACTIONS=Open&I=1&EDIT=N"><?=$TM_ID;?></a></small></td>
  <td width="500"><small><?=$title;?></small></td>
</tr>
<?}?>
</table>
<?} html_footer(); ?>
