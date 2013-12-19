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
//	6/19/2013	To Do: add another collumn between docket and title for patent type
//			done.
//			deleted report type b functionality.
//			changed all of the lists to list by the docket no. from docket_master
//	6/24/2013	changed it to pass module with the index listing
//	6/25/2013	adjusted the width slightly on ECS docket to fix formatting
//	7/3/2013	added client and status to list A. spent forever on formatting, trying to get the buttons to always stay, and I eventually gave up. the only one that drops when the screen shrinks
//			is ECS docket, but when I expand the pixels, for some crazy reason it changes the date ones, so they drop down. basically I hate html table formatting
//			fixed.
//			added country. the formatting is off again, and the best I could do was to make it all jump at roughly the same time.
//	7/9/2013	made it so it doesn't show the report type.
//	7/10/2013	to do: make a secondary sort
//			done. made $ORDER2 the secondary, and you select it by red buttons below the column title.
//			done for reports a and b. not index, since there are only two columns.
//	7/12/2013	enabled report type B. this will be the custom table view
//			created the formatting to select the different fileds that you would like displayed.
//			added client and country fields for docket view
//			added a check so it doesn't display results on patfam=-1
//			to do: dis-associate patents. not sure if I should do this in a new page like delete_confirm, or somehow get it so it is a link to this page, and add the deletion piece here...
//	7/15/2013	done. added dis_associate.php to do this.
//			change: no longer assigns a dis-associated patent the family of -1, instead it assigns it a new fam number. this way, it can still be the 'parent' of a new family
//			added link at the top of patfam list to link a new patent to that family. gonna use link_patent.php and specify in there what happens when it gets a patfam and not a disc_id
//			finished report type B (custom) you choose the fields you want, and then it displays them. there is no searching or anything with this, and there are also no hyperlinks. Since the
//			fields could be anything, i didn't know which one to put a hyperlink on.
//			sort wasn't working, so I added the $var string at the beginning to hold all of the data fields (checkboxes and submit_cust)
//	7/17/2013	added $user_level to header call
//	7/19/2013	put in cost link in family sort. this way, you can see the cost of a whole family. passed type and patfam_id
//			bug: custom and index search don't work on families. I think index doesn't work on any (at least disclosures)
//			fixed. index wasn't changing the query, and custom had to enable reset on var and pass patfam_id as well as sort on the submit form
//			the only issue I can see now is it resets the custom view if you click on next 50. however, it shoudln't since submit_cust is set in $var...
//	7/22/2013	this happens because $var is changed after the link is made. not sure how to fix this...
//			fixed. added another first/next 50 in report type b. to do: make it go to the top, comment the if statement on the first first/next 50
//	7/23/2013	done and done.
//	7/26/2013	attempted to get the combobox to display the next n records but couldn't get it to work. the issue is you need to make it a submit button, but the form won't pass the right varialbes...
//			actually if I want to try this again, it would probably work if I used them as <input>s
//	7/29/2013	added notes to the custom view
//			got the custom number working. did make it a form in the end, but there is a go button that sets the number. Otherwise, it won't work. I did have to pass everything via hidden inputs,
//			and since the var was being put in the address bar (global use) on hyperlinks, it wouldn't work if I passed $var like another variable since it views it as a string. because of this,
//			I had to pass each different searchable value through a hidden input.
//			for the custom view, I had to pass each checkbox as a hidden variable for the same reason.
//	7/31/2013	changed it so the custom view had left aligned fields.
//	8/2/2013	added link to record from title
//	8/9/2013	added logic and different links for patents from different countries. on every country on the espacenet webpage the link is for espacenet, uspto for us, and wipo for pct.
//			anything else just displays the number without a link
//	8/13/2013	deleted org and client checking since for some reason it started to not show the listing
//	8/15/2013	added tooltips on the sorting images so I could delete the red text telling the user what to do. it has to be on the image, not the title of the link because IE makes sense...
//	8/22/2013	fixed a collumn bug
//	8/29/2013	cleaned up the code a little
//	9/4/2013	enabled search by firm docket too
// patents_list.php -- user access level: viewer
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
// Set $var string
if($SORT=="PATFAM")
  $var="SORT=PATFAM&PATFAM_ID=$PATFAM_ID";
elseif($SORT=="SEARCH")
  $var="SORT=SEARCH&title=$title&docket=$docket&docket_alt=$docket_alt&".
  "firm=$firm&firm_contact=$firm_contact&client=$client&filing_type=$filing_type&".
  "country=$country&priority_date=$priority_date&client_contact=$client_contact&".
  "filing_date=$filing_date&ser_no=$ser_no&pub_date=$pub_date&".
  "pub_no=$pub_no&issue_date=$issue_date&pat_no=$pat_no&status=$status";
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
<!--this way if it is report b, the first/next 50 can be passed with the updated $var -->
<? if ($REPORT != "B") {?>
<!--I had to pass all these variables because just var wouldn't work for some reason.-->
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
<input type="hidden" name="filing_type" value="<?=$filing_type;?>">
<input type="hidden" name="filing_date" value="<?=$filing_date;?>">
<input type="hidden" name="country" value="<?=$country;?>">
<input type="hidden" name="priority_date" value="<?=$priority_date;?>">
<input type="hidden" name="ser_no" value="<?=$ser_no;?>">
<input type="hidden" name="pub_date" value="<?=$pub_date;?>">
<input type="hidden" name="pub_no" value="<?=$pub_no;?>">
<input type="hidden" name="issue_date" value="<?=$issue_date;?>">
<input type="hidden" name="pat_no" value="<?=$pat_no;?>">
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
    <? if ($SORT == "PATFAM"){?>
    &nbsp;|&nbsp;<a href="total_cost.php?module=<?=$module;?>&type=Patent&FAM_ID=<?=$PATFAM_ID;?>">Cost</a><?}?>
  </td></tr>
</table><br><br></form>
<?
if($SORT=="ALL") {echo("<center>LIST ALL PATENTS<br><br>");}
  else echo("<center>LIST PATENT SEARCH RESULTS<br><br>");
if ($SORT == "PATFAM") {?><center><a target=_blank href="link_patent.php?module=<?=$module;?>&PATFAM_ID=<?=$PATFAM_ID;?>">ADD PATENT TO FAMILY...</a></center><?}?>
<br><?}?>

<!-- REPORT A -->
<? if($REPORT=="ALL" or $REPORT=="A"){
if($ORDER=="") {$ORDER="docket_alt";};
if($ORDER2=="") {$ORDER2="docket_alt";}?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <?if ($SORT == "PATFAM"){?>
  <td width="150"><U>Dis-Associate</U></td><?}?>
  <td width="210"><U>ECS Docket</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=docket_alt&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=docket_alt DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=docket_alt"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=docket_alt DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="220"><U>Type</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=filing_type&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=filing_type DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=filing_type"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=filing_type DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="150"><U>Country</U>
   <br> <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=country&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=country DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=country"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=country DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="80"><U>Title</U>
   <br> <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=title&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=title DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=title"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=title DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="245"><U>Client</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=client&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=client DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=client"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=client DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="145"><U>Status</U>
   <br> <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=status&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=status DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=status"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=status DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="250"><U>Filing Date</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=filing_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=filing_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=filing_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=filing_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="250"><U>Issue Date</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=issue_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=issue_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=issue_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=issue_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if($SORT == "ALL")
  $sql="SELECT * FROM pat_filings WHERE customer_ID='$customer_ID' ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
elseif($SORT == "PATFAM")
  $sql="SELECT * FROM pat_filings WHERE customer_ID='$customer_ID' and patfam_ID='$PATFAM_ID' ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM pat_filings WHERE	  	  
    customer_ID = '$customer_ID' and
    docket LIKE '%$docket%' and
    docket_alt LIKE '%$docket_alt%' and
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
	ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";

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
  $client=$row["client"];
  // Remove commas in patent number
  $pat_nocommas=str_replace(",","",$pat_no);
?>

<tr bgcolor=EEEEEE>
  <? if ($SORT == "PATFAM") {?>
  <td width="150" align="center"><small><a href="dis_associate.php?module=<?=$module;?>&PATFAM=<?=$PATFAM_ID;?>&PAT_ID=<?=$PAT_ID;?>"><font color="red">X</font></a></small></td><?}?>
  <td width="115"><small><?=$docket_alt;?></small></td>
  <td width="70"><small><?=$filing_type;?></small></td>
  <td width="100"><small><?=$country;?></small></td>
  <td width="260"><small><a href="patents_edit.php?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$PAT_ID;?>&ACTIONS=Open&I=1&EDIT=N"><?=$title;?></a></small></td>
  <td width="125"><small><?=$client;?></small></td>
  <td width="125"><small><?=$status;?></small></td>
  <td width="110"><small><?=$ser_no;?><? if($filing_date!="0000-00-00") echo("<br>".$filing_date);?></small></td>
  <?
  //all the countries on the espacenet website
  if($country == "AT" or $country == "BG" or $country == "HR" or $country == "CZ" or $country == "EE" or $country == "FI" or $country == "DE" or $country == "HU" or $country == "IE" or $country == "JP"
     or $country == "LT" or $country == "MC" or $country == "EP" or $country == "NO" or $country == "PT" or $country == "RU" or $country == "SK" or $country == "ES" or $country == "CH" or $country == "BE"
     or $country == "CN" or $country == "CY" or $country == "DK" or $country == "FR" or $country == "GR" or $country == "IS" or $country == "IT" or $country == "KR" or $country == "LV" or $country == "LU"
     or $country == "NL" or $country == "PL" or $country == "RO" or $country == "RS" or $country == "SI" or $country == "SE" or $country == "TR")
  {?>
    <td width="110"><small><a target=_blank href="http://v3.espacenet.com/results?sf=n&FIRST=1&F=0&CY=ep&LG=en&DB=EPODOC&PN=<?echo($country.$pat_nocommas);?>&Submit=SEARCH&=&=&=&=&="><?=$pat_no;?></a>
   <?  if($issue_date!="0000-00-00") echo("<br>".$issue_date);?></small></td>
 <?}
 //US patent
 elseif($country == "US")
 {?>
  <td width="110"><small><a target=_blank href="http://patft.uspto.gov/netacgi/nph-Parser?Sect1=PTO2&Sect2=HITOFF&p=1&u=%2Fnetahtml%2FPTO%2Fsearch-bool.html&r=1&f=G&l=50&co1=AND&d=PALL&s1=<?=$pat_nocommas;?>.PN.&OS=PN/<?=$pat_nocommas;?>&RS=PN/<?=$pat_nocommas;?>"><?=$pat_no;?></a>
   <?  if($issue_date!="0000-00-00") echo("<br>".$issue_date);?></small></td>
 <?}
 //patent coorperation treaty
 elseif($country == "PCT")
 {?>
  <td width="110"><small><a target=_blank href="http://patentscope.wipo.int/search/en/detail.jsf?docId=<?=$pat_nocommas;?>&recNum=1&docAn=US2008067945&queryString=ALLNUM:(<?=$pat_nocommas;?>)&maxRec=1"><?=$pat_no;?></a>
   <?  if($issue_date!="0000-00-00") echo("<br>".$issue_date);?></small></td>
 <?}
 //none of the above
 else
 {?>
  <td width="110"><small><?=$pat_no;?>
   <?  if($issue_date!="0000-00-00") echo("<br>".$issue_date);?></small></td>
 <?} ?>
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
 <br> <center>SELECT FIELDS</center><br><br>
    <table align="center" width="100%" cellpadding="5">
  <tr bgcolor=EEEEEE><font size="-2">
    <td width="200"><input type="checkbox" name="check_ecs_docket" value="Y">&nbsp;&nbsp;<U>ECS Docket</U>
    <td width="200"><input type="checkbox" name="check_firm_docket" value="Y">&nbsp;&nbsp;<U>Firm Docket</U>
    <td width="200"><input type="checkbox" name="check_title" value="Y">&nbsp;&nbsp;<U>Title</U>
    <td width="200"><input type="checkbox" name="check_pat_ID" value="Y">&nbsp;&nbsp;<U>Pat ID</U>
    <td width="200"><input type="checkbox" name="check_patfam_ID" value="Y">&nbsp;&nbsp;<U>PatFam ID</U></tr>
  <tr bgcolor=EEEEEE>
    <td width="200"><input type="checkbox" name="check_pat_no" value="Y">&nbsp;&nbsp;<U>Pat No.</U>
    <td width="200"><input type="checkbox" name="check_filing_date" value="Y">&nbsp;&nbsp;<U>Filing Date</U>
    <td width="200"><input type="checkbox" name="check_issue_date" value="Y">&nbsp;&nbsp;<U>Issue Date</U>
    <td width="200"><input type="checkbox" name="check_expiry_date" value="Y">&nbsp;&nbsp;<U>Expiration Date</U>
    <td width="200"><input type="checkbox" name="check_pub_date" value="Y">&nbsp;&nbsp;<U>Publication Date</U></tr>
  <tr bgcolor=EEEEEE>  
    <td width="200"><input type="checkbox" name="check_priority_date" value="Y">&nbsp;&nbsp;<U>Priority Date</U>
    <td width="200"><input type="checkbox" name="check_pub_no" value="Y">&nbsp;&nbsp;<U>Publication No.</U>
    <td width="200"><input type="checkbox" name="check_ser_no" value="Y">&nbsp;&nbsp;<U>Serial No.</U>
    <td width="200"><input type="checkbox" name="check_client" value="Y">&nbsp;&nbsp;<U>Client</U>
    <td width="200"><input type="checkbox" name="check_client_contact" value="Y">&nbsp;&nbsp;<U>Client Contact</U></tr>
  <tr bgcolor=EEEEEE>  
    <td width="200"><input type="checkbox" name="check_firm" value="Y">&nbsp;&nbsp;<U>Firm</U>
    <td width="200"><input type="checkbox" name="check_firm_contact" value="Y">&nbsp;&nbsp;<U>Firm Contact</U>
    <td width="200"><input type="checkbox" name="check_type" value="Y">&nbsp;&nbsp;<U>Type</U>
    <td width="200"><input type="checkbox" name="check_original" value="Y">&nbsp;&nbsp;<U>Original</U>
    <td width="200"><input type="checkbox" name="check_country" value="Y">&nbsp;&nbsp;<U>Country</U></tr>
  <tr bgcolor=EEEEEE>  
    <td width="200"><input type="checkbox" name="check_status" value="Y">&nbsp;&nbsp;<U>Status</U>
    <td width="200"><input type="checkbox" name="check_abstract" value="Y">&nbsp;&nbsp;<U>Abstract</U>
    <td width="200"><input type="checkbox" name="check_pte_154" value="Y">&nbsp;&nbsp;<U>154 PTE</U>
    <td width="200"><input type="checkbox" name="check_pte_156" value="Y">&nbsp;&nbsp;<U>156 PTE</U>
    <td width="200"><input type="checkbox" name="check_notes" value="Y">&nbsp;&nbsp;<U>Notes</U>
  <tr>
    <td align="center" colspan="5">
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
      "check_title=$check_title&check_pat_ID=$check_pat_ID&check_patfam_ID=$check_patfam_ID&".
      "check_pat_no=$check_pat_no&check_filing_date=$check_filing_date&check_issue_date=$check_issue_date&".
      "check_expiry_date=$check_expiry_date&check_pub_date=$check_pub_date&check_priority_date=$check_priority_date&".
      "check_pub_no=$check_pub_no&check_ser_no=$check_ser_no&check_client=$check_client&check_client_contact=$check_client_contact&".
      "check_firm=$check_firm&check_firm_contact=$check_firm_contact&check_type=$check_type&check_original=$check_original&".
      "check_country=$check_country&check_status=$check_status&check_abstract=$check_abstract&check_pte_154=$check_pte_154&".
      "check_pte_156=$check_pte_156&submit_cust=$submit_cust";
      
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
      <input type="hidden" name="issue_date" value="<?=$issue_date;?>">
      <input type="hidden" name="pat_no" value="<?=$pat_no;?>">
      <input type="hidden" name="status" value="<?=$status;?>">
      <input type="hidden" name="client_contact" value="<?=$client_contact;?>">
      <input type="hidden" name="submit_cust" value="<?=$submit_cust;?>">
      <input type="hidden" name="check_ecs_docket" value="<?=$check_ecs_docket;?>">
      <input type="hidden" name="check_firm_docket" value="<?=$check_firm_docket;?>">
      <input type="hidden" name="check_title" value="<?=$check_title;?>">
      <input type="hidden" name="check_pat_ID" value="<?=$check_pat_ID;?>">
      <input type="hidden" name="check_patfam_ID" value="<?=$check_patfam_ID;?>">
      <input type="hidden" name="check_pat_no" value="<?=$check_pat_no;?>">
      <input type="hidden" name="check_filing_date" value="<?=$check_filing_date;?>">
      <input type="hidden" name="check_issue_date" value="<?=$check_issue_date;?>">
      <input type="hidden" name="check_expiry_date" value="<?=$check_expiry_date;?>">
      <input type="hidden" name="check_pub_date" value="<?=$check_pub_date;?>">
      <input type="hidden" name="check_priority_date" value="<?=$check_priority_date;?>">
      <input type="hidden" name="check_pub_no" value="<?=$check_pub_no;?>">
      <input type="hidden" name="check_ser_no" value="<?=$check_ser_no;?>">
      <input type="hidden" name="check_client" value="<?=$check_client;?>">
      <input type="hidden" name="check_client_contact" value="<?=$check_client_contact;?>">
      <input type="hidden" name="check_firm" value="<?=$check_firm;?>">
      <input type="hidden" name="check_firm_contact" value="<?=$check_firm_contact;?>">
      <input type="hidden" name="check_type" value="<?=$check_type;?>">
      <input type="hidden" name="check_original" value="<?=$check_original;?>">
      <input type="hidden" name="check_country" value="<?=$check_country;?>">
      <input type="hidden" name="check_status" value="<?=$check_status;?>">
      <input type="hidden" name="check_abstract" value="<?=$check_abstract;?>">
      <input type="hidden" name="check_pte_154" value="<?=$check_pte_154;?>">
      <input type="hidden" name="check_pte_156" value="<?=$check_pte_156;?>">
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
<center>CUSTOM PATENT LISTING</center><br><br>
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
  <?if ($check_pat_ID == "Y"){?>
  <td width="150"><U>PAT ID</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_ID&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_ID DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pat_ID"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pat_ID DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_patfam_ID == "Y"){?>
  <td width="150"><U>PatFam ID</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=patfam_ID&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=patfam_ID DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=patfam_ID"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=patfam_ID DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_pat_no == "Y"){?>
  <td width="150"><U>Pat No.</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_no&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_no DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pat_no"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pat_no DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_filing_date == "Y"){?>
  <td width="150"><U>Filing Date</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=filing_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=filing_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=filing_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=filing_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_issue_date == "Y"){?>
  <td width="150"><U>Issue Date</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=issue_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=issue_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=issue_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=issue_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_expiry_date == "Y"){?>
  <td width="150"><U>Exiration Date</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=expiry_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=expiry_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=expiry_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=expiry_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_pub_date == "Y"){?>
  <td width="150"><U>Publication Date</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pub_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pub_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pub_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pub_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_priority_date == "Y"){?>
  <td width="150"><U>Priority Date</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=priority_date&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=priority_date DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=priority_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=priority_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_pub_no == "Y"){?>
  <td width="150"><U>Publication No.</U>
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
  <?if ($check_type == "Y"){?>
  <td width="150"><U>Type</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=type&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=type DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=type"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=type DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_original == "Y"){?>
  <td width="150"><U>Original</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=original&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=original DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=original"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=original DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_country == "Y"){?>
  <td width="150"><U>Country</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=country&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=country DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=country"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=country DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_status == "Y"){?>
  <td width="150"><U>Status</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=status&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=status DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=status"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=status DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_abstract == "Y"){?>
  <td width="150"><U>Abstract</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=abstract&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=abstract DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=abstract"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=abstract DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_pte_154 == "Y"){?>
  <td width="150"><U>154 PTE</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=PTE_154&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=PTE_154 DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=154_PTE"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=154_PTE DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_pte_156 == "Y"){?>
  <td width="150"><U>156 PTE</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=PTE_156&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=PTE_156 DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=PTE_156"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=PTE_156 DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td><?}?>
  <?if ($check_notes == "Y"){?>
  <td width="150"><U>Notes</U>
  <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=notes&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=notes DESC&<?=$var?>&ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=notes"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=notes DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td></tr><?}?>
<?
//SQL Query for Selecting All $TYPE IP Records
if($SORT == "ALL")
  $sql="SELECT * FROM pat_filings WHERE customer_ID='$customer_ID' ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
elseif($SORT == "PATFAM")
  $sql="SELECT * FROM pat_filings WHERE customer_ID='$customer_ID' and patfam_ID='$PATFAM_ID' ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM pat_filings WHERE	  	  
    customer_ID = '$customer_ID' and
    docket LIKE '%$docket%' and
    docket_alt LIKE '%$docket_alt%' and
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
	ORDER BY $ORDER, $ORDER2, docket_alt LIMIT $START, $number";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $PAT_ID=$row["pat_ID"];
  $org=$row["org"];
  $docket=$row["docket"];
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
  $client=$row["client"];
  $client_contact=$row["client_contact"];
  $patfam_ID=$row["patfam_ID"];
  $expiry_date=$row["expiry_date"];
  $priority_date=$row["priority_date"];
  $pub_date=$row["pub_date"];
  $pub_no=$row["pub_no"];
  $firm=$row["firm"];
  $firm_contact=$row["firm_contact"];
  $original=$row["original"];
  $type=$row["filing_type"];
  $status=$row["status"];
  $abstract=$row["abstract"];
  $pte_154=$row["PTE_154"];
  $pte_156=$row["PTE_156"];
  $notes=$row["notes"];
  // Remove commas in patent number
  $pat_nocommas=str_replace(",","",$pat_no);
  ?><tr bgcolor=EEEEEE><?
  if ($check_ecs_docket == "Y") {?>
  <td width="150" align="left"><small><?=$docket_alt;?></small></td><?}
  if ($check_firm_docket == "Y") {?>
  <td width="150" align="left"><small><?=$docket;?></small></td><?}
  if ($check_title == "Y") {?>
  <td width="150" align="left"><small><a href="patents_edit.php?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$PAT_ID;?>&ACTIONS=Open&I=1&EDIT=N"><?=$title;?></a></small></td><?}
  if ($check_pat_ID == "Y") {?>
  <td width="150" align="left"><small><?=$PAT_ID;?></small></td><?}
  if ($check_patfam_ID == "Y") {?>
  <td width="150" align="left"><small><?=$patfam_ID;?></small></td><?}
  if ($check_pat_no == "Y") {
  //all the countries on the espacenet website
  if($country == "AT" or $country == "BG" or $country == "HR" or $country == "CZ" or $country == "EE" or $country == "FI" or $country == "DE" or $country == "HU" or $country == "IE" or $country == "JP"
     or $country == "LT" or $country == "MC" or $country == "EP" or $country == "NO" or $country == "PT" or $country == "RU" or $country == "SK" or $country == "ES" or $country == "CH" or $country == "BE"
     or $country == "CN" or $country == "CY" or $country == "DK" or $country == "FR" or $country == "GR" or $country == "IS" or $country == "IT" or $country == "KR" or $country == "LV" or $country == "LU"
     or $country == "NL" or $country == "PL" or $country == "RO" or $country == "RS" or $country == "SI" or $country == "SE" or $country == "TR")
  {?>
    <td width="110"><small><a target=_blank href="http://v3.espacenet.com/results?sf=n&FIRST=1&F=0&CY=ep&LG=en&DB=EPODOC&PN=<?echo($country.$pat_nocommas);?>&Submit=SEARCH&=&=&=&=&="><?=$pat_no;?></a>
    </small></td>
 <?}
 //US patent
 elseif($country == "US")
 {?>
  <td width="110"><small><a target=_blank href="http://patft.uspto.gov/netacgi/nph-Parser?Sect1=PTO2&Sect2=HITOFF&p=1&u=%2Fnetahtml%2FPTO%2Fsearch-bool.html&r=1&f=G&l=50&co1=AND&d=PALL&s1=<?=$pat_nocommas;?>.PN.&OS=PN/<?=$pat_nocommas;?>&RS=PN/<?=$pat_nocommas;?>"><?=$pat_no;?></a>
  </small></td>
 <?}
 //patent coorperation treaty
 elseif($country == "PCT")
 {?>
  <td width="110"><small><a target=_blank href="http://patentscope.wipo.int/search/en/detail.jsf?docId=<?=$pat_nocommas;?>&recNum=1&docAn=US2008067945&queryString=ALLNUM:(<?=$pat_nocommas;?>)&maxRec=1"><?=$pat_no;?></a>
  </small></td>
 <?}
 //none of the above
 else
 {?>
  <td width="110"><small><?=$pat_no;?></small></td>
 <?} }
  if ($check_filing_date == "Y") {?>
  <td width="150" align="left"><small><?=$filing_date;?></small></td><?}
  if ($check_issue_date == "Y") {?>
  <td width="150" align="left"><small><?=$issue_date;?></small></td><?}
  if ($check_expiry_date == "Y") {?>
  <td width="150" align="left"><small><?=$expiry_date;?></small></td><?}
  if ($check_pub_date == "Y") {?>
  <td width="150" align="left"><small><?=$pub_date;?></small></td><?}
  if ($check_priority_date == "Y") {?>
  <td width="150" align="left"><small><?=$priority_date;?></small></td><?}
  if ($check_pub_no == "Y") {?>
  <td width="150" align="left"><small><?=$pub_no;?></small></td><?}
  if ($check_ser_no == "Y") {?>
  <td width="150" align="left"><small><?=$ser_no;?></small></td><?}
  if ($check_client == "Y") {?>
  <td width="150" align="left"><small><?=$client;?></small></td><?}
  if ($check_client_contact == "Y") {?>
  <td width="150" align="left"><small><?=$client_contact;?></small></td><?}
  if ($check_firm == "Y") {?>
  <td width="150" align="left"><small><?=$firm;?></small></td><?}
  if ($check_firm_contact == "Y") {?>
  <td width="150" align="left"><small><?=$firm_contact;?></small></td><?}
  if ($check_type == "Y") {?>
  <td width="150" align="left"><small><?=$type;?></small></td><?}
  if ($check_original == "Y") {?>
  <td width="150" align="left"><small><?=$original;?></small></td><?}
  if ($check_country == "Y") {?>
  <td width="150" align="left"><small><?=$country;?></small></td><?}
  if ($check_status == "Y") {?>
  <td width="150" align="left"><small><?=$status;?></small></td><?}
  if ($check_abstract == "Y") {?>
  <td width="150" align="left"><small><?=$abstract;?></small></td><?}
  if ($check_pte_154 == "Y") {?>
  <td width="150" align="left"><small><?=$pte_154;?></small></td><?}
  if ($check_pte_156 == "Y") {?>
  <td width="150" align="left"><small><?=$pte_156;?></small></td><?}
  if ($check_notes == "Y") {?>
  <td width="150" align="left"><small><?=$notes;?></small></td></tr><?}
  }
} }

//<!-- REPORT C -->
if($REPORT=="C"){
if($ORDER=="") {$ORDER="pat_actions.respdue_date";}
if($ORDER2=="") {$ORDER2="pat_actions.respdue_date";}?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="110"><U>ECS Docket</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.docket_alt&<?=$var?>&$ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.docket_alt DESC&<?=$var?>&$ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pat_filings.docket_alt"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pat_filings.docket_alt DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="110"><U>Client</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.client&<?=$var?>&$ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.client DESC&<?=$var?>&$ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pat_filings.client"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pat_filings.client DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="300"><U>Title</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.title&<?=$var?>&$ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.title DESC&<?=$var?>&$ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pat_filings.title"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pat_filings.title DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="110"><U>Country</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.country&<?=$var?>&$ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.country DESC&<?=$var?>&$ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pat_filings.country"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pat_filings.country DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
  <td width="250"><U>Open Actions</U>
    <br><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_actions.respdue_date&<?=$var?>&$ORDER2=<?=$ORDER2;?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_actions.respdue_date DESC&<?=$var?>&$ORDER2=<?=$ORDER2;?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pat_actions.respdue_date"><img src="up2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=<?=$ORDER;?>&<?=$var?>&ORDER2=pat_actions.respdue_date DESC"><img src="down2.gif" width="13" height="11" alt="" border="0" title="Secondary"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if($SORT == "ALL")
  $sql="SELECT * FROM pat_filings, pat_actions WHERE
    pat_filings.customer_ID='$customer_ID' and
    pat_actions.pat_ID=pat_filings.pat_ID and
	pat_actions.done='N'
    ORDER BY $ORDER, $ORDER2, pat_filings.docket_alt LIMIT $START, $number";
elseif($SORT == "PATFAM")    
  $sql="SELECT * FROM pat_filings, pat_actions WHERE
    pat_filings.customer_ID='$customer_ID' and
	pat_filings.patfam_ID='$PATFAM_ID' and
    pat_actions.pat_ID=pat_filings.pat_ID and
	pat_actions.done='N'
    ORDER BY $ORDER, $ORDER2, pat_filings.docket_alt LIMIT $START, $number";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM pat_filings, pat_actions WHERE
    pat_filings.customer_ID='$customer_ID' and
    pat_actions.pat_ID=pat_filings.pat_ID and
	pat_actions.done='N' and
    docket LIKE '%$docket%' and
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
	ORDER BY $ORDER, $ORDER2, pat_filings.docket_alt LIMIT $START, $number";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $ACTION_ID=$row["action_ID"];
  $action_type=$row["action_type"];
  $client=$row["client"];
  $country=$row["country"];
  $PAT_ID=$row["pat_ID"];
  $docket_alt=$row["docket_alt"];
  $title=$row["title"];
  $filing_date=$row["filing_date"];
  $ser_no=$row["ser_no"];
  $respdue_date=$row["respdue_date"];
  $pat_no=$row["pat_no"];
  $issue_date=$row["issue_date"];
  
if ($PATFAM_ID == "-1") {break;}	//don't display anything if it has broken association
?>
<tr bgcolor=EEEEEE>
  <td width="110"><small><?=$docket_alt;?></small></td>
  <td width="110"><small><?=$client;?></small></td>
  <td width="300"><small><a href="patents_edit.php?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$PAT_ID;?>&ACTIONS=Open&I=1&EDIT=N"><?=$title;?></a></small></td>
  <td width="110"><small><?=$country;?></small></td>
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
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.pat_id&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.pat_id DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a></td>
  <td width="500"><U>Title</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.title&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&ORDER=pat_filings.title DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a></td>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if($SORT == "ALL")
  $sql="SELECT * FROM pat_filings WHERE customer_ID='$customer_ID' ORDER BY $ORDER, docket_alt LIMIT $START, $number";
elseif($SORT == "PATFAM")
  $sql="SELECT * FROM pat_filings WHERE customer_ID='$customer_ID' and patfam_ID='$PATFAM_ID' ORDER BY $ORDER, docket_alt LIMIT $START, $number";
else // ($SORT == "SEARCH")
  $sql="SELECT * FROM pat_filings WHERE	  	  
    customer_ID = '$customer_ID' and
    docket LIKE '%$docket%' and
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
	ORDER BY $ORDER, docket_alt LIMIT $START, $number";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $PAT_ID=$row["pat_ID"];
  $title=$row["title"];
?>
<tr bgcolor=EEEEEE>
  <td width="150"><small><a href="patents_edit.php?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$PAT_ID;?>&ACTIONS=Open&I=1&EDIT=N"><?=$PAT_ID;?></a></small></td>
  <td width="500"><small><?=$title;?></small></td>
</tr>
<?}?>
</table>
<?}?>
<? html_footer(); ?>
