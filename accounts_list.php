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
//	6/27/2013	created from licenses_list.php
//                      This is my plan: you go to accounting, it puts you here with a list of all the accounts. they have the id, ecs docket, and amount. you can also list by pdf files
//			beginning licensefam stuff -> accountfam
//	6/28/2013	converted everything to work. I think this should be pretty much done, I made it so it pulls from both dbs on the invoice compilations screen, so you can click on the pdf, record, or account
//	7/1/2013	the reason why it wasn't returning anything on the search was becuase the query was wrong. fixed that.
//			bug: goes blank for firm_docket order increasing... but not decreasing...
//			fixed. was missing an &
//			bug: on invoice compilations it shows that every account is a part of every pdf...
//			fixed in kinda a weird way. Made it two querys for the selecting, since it would go through all of the accounts, then the pdf_combos, thereby grabbing each pdf for each account
//	7/2/2013	changed it so it defaults to pdf_id desc on the invoice querys. I put the default ordering after each of the report type checks, so they have different ones
//			the sort by pdf name doesn't work. it's because it sorts the accounts by the pdf_id, so when you specify to sort by the pdf_name, it has no idea what you're talking about.
//			took out the functionality for now, although I may have to figure it out for reports_list.php
//	7/8/2013	stopped displaying list type
//	7/10/2013	added secondary sort functionality for list type A. for more details, see patents_list.php. didn't add for invoices, since there were only two that you could short by like indexes
//	7/17/2013	added $user_level to header call
//	7/23/2013	modified to work with alt_name
//	7/30/2013	enabled numbered list functionality. for more details, see patents_list
//			replaced path names with session variables
//	8/1/2013	enabled custom listings. details: patents_list.php
//	8/2/2013	added link on custom listing to acct_id
//	8/15/2013	added tooltips on the sorting images so I could delete the red text telling the user what to do. it has to be on the image, not the title of the link because IE makes sense...
//	8/26/2013	added some comments and cleanded up the code some
//accounts_list.php
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
// Set $var string
if ($SORT=="ACCOUNTFAM")
  $var="SORT=ACCOUNTFAM&ACCOUNTFAM_ID=$ACCOUNTFAM_ID";
elseif ($SORT=="SEARCH")
  $var="SORT=SEARCH&acct_ID=$acct_ID&ecs_docket=$ecs_docket&".
  "firm_docket=$firm_docket&pdf_id=$pdf_id&name=$name";
else $var="SORT=ALL";
// Set Defaults
if($number=="") {$number=50;}
if ($REPORT=="") $REPORT="A";
if ($NEXT=="1") $START=$START+$number;
elseif ($NEXT=="-1") {$START=$START-$number;}
else {$START="0";}
?>
<table align="left" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">Reports:&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=A&<?=$var;?>">Transactions</a>&nbsp;|&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=B&<?=$var;?>">Custom</a>&nbsp;|&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=C&<?=$var;?>">Invoices</a>
	</tr>
</table>
<? if ($REPORT != "B") {?>	<!-- need this since there needs to be a different title on B -->
<form action="<?=$PHP_SELF;?>">
<input type="hidden" name="module" value="<?=$module;?>">
<input type="hidden" name="REPORT" value="<?=$REPORT;?>">
<input type="hidden" name="NEXT" value="<?=$NEXT;?>">
<input type="hidden" name="START" value="<?=$START;?>">
<input type="hidden" name="ORDER" value="<?=$ORDER;?>">
<input type="hidden" name="var" value="<?=$var;?>">
<input type="hidden" name="ORDER2" value="<?=$ORDER2;?>">
<input type="hidden" name="SORT" value="<?=$SORT;?>">
<input type="hidden" name="acct_ID" value="<?=$acct_ID;?>">
<input type="hidden" name="ecs_docket" value="<?=$ecs_docket;?>">
<input type="hidden" name="firm_docket" value="<?=$firm_docket;?>">
<input type="hidden" name="pdf_id" value="<?=$pdf_id;?>">
<input type="hidden" name="name" value="<?=$name;?>">
<table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">
    <select name=number size="1">
	      <option><?=$number;?></option><option>10</option><option>15</option><option>25</option><option>50</option></select>
    <input type=submit value=Go>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&NEXT=0&START=<?=$START;?>&ORDER=<?=$ORDER;?>&<?=$var;?>&number=<?=$number;?>">First <?=$number;?></a>&nbsp;|&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&NEXT=-1&START=<?=$START;?>&ORDER=<?=$ORDER;?>&<?=$var;?>&number=<?=$number;?>">Previous <?=$number;?></a>&nbsp;|&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&NEXT=1&START=<?=$START;?>&ORDER=<?=$ORDER;?>&<?=$var;?>&number=<?=$number;?>">Next <?=$number;?></a>
  </td></tr>
</table><br><br>
<?      
if ($SORT=="ALL") echo("<center>LIST ALL TRANSACTIONS");
else echo("<center>LIST TRANSACTION SEARCH RESULTS");?><br><br><br><?}?>

<!-- REPORT TYPE A -->
<? if ($REPORT=="A"){
if ($ORDER=="") $ORDER="acct_ID";
if($ORDER2=="") {$ORDER2="acct_ID";}?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="135"><U>Transaction No.</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=acct_ID&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=acct_ID DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=acct_ID"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=acct_ID DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="125"><U>ECS Docket</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=ecs_docket&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=ecs_docket DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=ecs_docket"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=ecs_docket DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="175"><U>Firm Docket</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=firm_docket&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=firm_docket DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=firm_docket"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=firm_docket DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="125"><U>Amount</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=amount&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=amount DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=amount"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=amount DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if ($SORT == "ALL")
  $sql="SELECT * FROM accounts WHERE customer_ID = '$customer_ID' ORDER BY $ORDER, $ORDER2, ecs_docket LIMIT $START, $number";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM accounts WHERE	  	  
    customer_ID = '$customer_ID' and
    ecs_docket LIKE '%$ecs_docket%' and
    firm_docket LIKE '%$firm_docket%' and
    pdf_id LIKE '%$pdf_id%' and
    acct_id LIKE '%$acct_ID%'
	ORDER BY $ORDER, $ORDER2, acct_id LIMIT $START, $number";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $acct_ID=$row["acct_id"];
  $ecs_docket=$row["ecs_docket"];
  $pdf_id=$row["pdf_id"];
  $amount=$row["amount"];
  $firm_docket=$row["firm_docket"];
?>
<tr bgcolor=EEEEEE>
  <td width="110"><small><a href="accounts_edit.php?module=<?=$module;?>&SORT=<?=$SORT?>&ACCTEDIT=1&acct_ID=<?=$acct_ID?>&I=1&EDIT=N"><?=$acct_ID;?></a></small></td>
  <td width="125"><small><?=$ecs_docket;?><br>
  <td width="175"><small><?=$firm_docket;?></small></td>
  <td width="125"><small>$<?=$amount;?>
</tr>
<?}?>
</table>
<?}

//<!-- REPORT B -->
if($REPORT=="B"){
if($ORDER=="") {$ORDER="ecs_docket";};
if($ORDER2=="") {$ORDER2="ecs_docket";}
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
    <td width="200"><input type="checkbox" name="check_acct_id" value="Y">&nbsp;&nbsp;<U>Transaction ID</U>
    <td width="200"><input type="checkbox" name="check_amount" value="Y">&nbsp;&nbsp;<U>Amount</U></tr>
  <tr bgcolor=EEEEEE>
    <td width="200"><input type="checkbox" name="check_pdf_id" value="Y">&nbsp;&nbsp;<U>PDF_ID</U>
    <td width="200"><input type="checkbox" name="check_pdf_name" value="Y">&nbsp;&nbsp;<U>PDF Name</U>
    <td width="200"><input type="checkbox" name="check_invoice_number" value="Y">&nbsp;&nbsp;<U>Invoice Number</U>
    <td width="200"><input type="checkbox" name="check_invoice_date" value="Y">&nbsp;&nbsp;<U>Invoice Date</U></tr>
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
  $var=$var."&check_ecs_docket=$check_ecs_docket&check_firm_docket=$check_firm_docket&check_acct_id=$check_acct_id&".
      "check_amount=$check_amount&check_pdf_id=$check_pdf_id&check_pdf_name=$check_pdf_name&".
      "check_invoice_number=$check_invoice_number&check_invoice_date=$check_invoice_date&".
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
      <input type="hidden" name="acct_ID" value="<?=$acct_ID;?>">
      <input type="hidden" name="ecs_docket" value="<?=$ecs_docket;?>">
      <input type="hidden" name="firm_docket" value="<?=$firm_docket;?>">
      <input type="hidden" name="pdf_id" value="<?=$pdf_id;?>">
      <input type="hidden" name="name" value="<?=$name;?>">
      <input type="hidden" name="submit_cust" value="<?=$submit_cust;?>">
      <input type="hidden" name="check_ecs_docket" value="<?=$check_ecs_docket;?>">
      <input type="hidden" name="check_firm_docket" value="<?=$check_firm_docket;?>">
      <input type="hidden" name="check_acct_id" value="<?=$check_acct_id;?>">
      <input type="hidden" name="check_amount" value="<?=$check_amount;?>">
      <input type="hidden" name="check_pdf_id" value="<?=$check_pdf_id;?>">
      <input type="hidden" name="check_pdf_name" value="<?=$check_pdf_name;?>">
      <input type="hidden" name="check_invoice_number" value="<?=$check_invoice_number;?>">
      <input type="hidden" name="check_invoice_date" value="<?=$check_invoice_date;?>">
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
<center>CUSTOM TRANSACTION LISTING</center><br><br>
  <table align="center" width="100%" cellpadding="5">
  <tr bgcolor=EEEEEE><font size="-2">
<?
  if ($check_ecs_docket == "Y"){?>
  <td width="150"><U>ECS Docket</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=ecs_docket&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=ecs_docket DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=ecs_docket"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=ecs_docket DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_firm_docket == "Y"){?>
  <td width="150"><U>Firm Docket</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=firm_docket&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=firm_docket DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=firm_docket"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=firm_docket DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_acct_id == "Y"){?>
  <td width="150"><U>Transaction ID</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=acct_id&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=acct_id DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=acct_id"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=acct_id DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_amount == "Y"){?>
  <td width="150"><U>Amount</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=amount&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=amount DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=amount"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=amount DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_pdf_id == "Y"){?>
  <td width="150"><U>PDF ID</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pdf_id&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pdf_id DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pdf_id"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pdf_id DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_pdf_name == "Y"){?>
  <td width="150"><U>PDF Name</U></td><?}?>
  <?if ($check_invoice_number == "Y"){?>
  <td width="150"><U>Invoice Number</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=invoice_number&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=invoice_number DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=invoice_number"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=invoice_number DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_invoice_date == "Y"){?>
  <td width="150"><U>Invoice Date</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=invoice_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=invoice_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=invoice_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=invoice_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td></tr><?}?>
<?
//SQL Query for Selecting All $TYPE IP Records
if ($SORT == "ALL")
  $sql="SELECT * FROM accounts WHERE customer_ID = '$customer_ID' ORDER BY $ORDER, $ORDER2, ecs_docket LIMIT $START, $number";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM accounts WHERE	  	  
    customer_ID = '$customer_ID' and
    ecs_docket LIKE '%$ecs_docket%' and
    firm_docket LIKE '%$firm_docket%' and
    pdf_id LIKE '%$pdf_id%' and
    acct_id LIKE '%$acct_ID%'
	ORDER BY $ORDER, $ORDER2, acct_id LIMIT $START, $number";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $acct_ID=$row["acct_id"];
  $ecs_docket=$row["ecs_docket"];
  $pdf_id=$row["pdf_id"];
  $amount=$row["amount"];
  $firm_docket=$row["firm_docket"];
  $invoice_date=$row["invoice_date"];
  $invoice_number=$row["invoice_number"];
  //select the name from pdf_combos
  $resulto=mysql_query("SELECT name FROM pdf_combos WHERE id = '$pdf_id'");
  while($rowo=mysql_fetch_array($resulto)){
    $name=$rowo["name"];
  }
  ?><tr bgcolor=EEEEEE><?
  if ($check_ecs_docket == "Y") {?>
  <td width="150" align="left"><small><?=$ecs_docket;?></small></td><?}
  if ($check_firm_docket == "Y") {?>
  <td width="150" align="left"><small><?=$firm_docket;?></small></td><?}
  if ($check_acct_id == "Y") {?>
  <td width="150" align="left"><small><a href="accounts_edit.php?module=<?=$module;?>&SORT=<?=$SORT?>&ACCTEDIT=1&acct_ID=<?=$acct_ID?>&I=1&EDIT=N"><?=$acct_ID;?></a></small></td><?}
  if ($check_amount == "Y") {?>
  <td width="150" align="left"><small><?=$amount;?></small></td><?}
  if ($check_pdf_id == "Y") {?>
  <td width="150" align="left"><small><?=$pdf_id;?></small></td><?}
  if ($check_pdf_name == "Y") {?>
  <td width="150" align="left"><small><?=$name;?></small></td><?}
  if ($check_invoice_number == "Y") {?>
  <td width="150" align="left"><small><?=$invoice_number;?></small></td><?}
  if ($check_invoice_date == "Y") {?>
  <td width="150" align="left"><small><?=$invoice_date;?></small></td></tr><?}
  }
}}

//<!-- REPORT C -->
 elseif($REPORT=="C"){
if($ORDER=="") $ORDER="accounts.pdf_id DESC";?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="110"><U>PDF ID</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=accounts.pdf_id&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=accounts.pdf_id DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a></td>
  <td width="200"><U>PDF Name</U>
    <!--<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pdf_combos.name&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pdf_combos.name DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a></td>-->
  <td width="150"><U>Transaction No.</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=accounts.acct_ID&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=accounts.acct_ID DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if($SORT == "ALL")	
  $sql="SELECT * FROM accounts WHERE
    customer_ID='$customer_ID' 
    ORDER BY $ORDER, pdf_id DESC LIMIT $START, $number";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM accounts WHERE	  	  
    customer_ID = '$customer_ID' and
    ecs_docket LIKE '%$ecs_docket%' and
    firm_docket LIKE '%$firm_docket%' and
    pdf_id LIKE '%$pdf_id%' and
    acct_id LIKE '%$acct_ID%'
	ORDER BY $ORDER, acct_id LIMIT $START, $number";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){ 
  $acct_ID=$row["acct_id"];
  $ecs_docket=$row["ecs_docket"];
  $pdf_id=$row["pdf_id"];
  $amount=$row["amount"];
  $firm_docket=$row["firm_docket"];
  
  $sql5="SELECT * FROM pdf_combos WHERE id=$pdf_id";	//should just return the row that is the pdf
  $result5=mysql_query($sql5);
//Print the records
while($row5=mysql_fetch_array($result5)){ 
  $name=$row5["name"];
  $alt_name=$row5["alt_name"];}
?>
<tr bgcolor=EEEEEE>
  <td width="100"><small><a href="pdf_upload.php?module=<?=$module;?>&id=<?=$pdf_id;?>&I=1&EDIT=N"><?=$pdf_id;?></a></small></td>
  <td width="200"><small><a href="<?=$absolute_invoices.$alt_name;?>"><?=$name;?></a></small></td>
  <td width="150"><small><a href="accounts_edit.php?module=<?=$module;?>&SORT=<?=$SORT?>&ACCTEDIT=1&acct_ID=<?=$acct_ID?>&I=1&EDIT=N"><?=$acct_ID;?></a></small></td>
</tr>
<?}?>
</table>
<?} html_footer(); ?>
