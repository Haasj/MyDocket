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
//	7/2/2013	created from accounts_list.php because I think I can utilize some of the dual table querys.
//			checked for each type, and picked up the other fields accordingly. I changed the type to capitolize it
//			finished the A report type.
//	7/3/2013	added links to titles in A report.
//			added the financial piece. it is the same as report A, but uses another query and loop to calulate total cost
//	7/8/2013	added search functionality. only can search by docket number, index, and type since they are in the same table. can't figure out multi-table searching with the model i've created
//			deleted display list number functionality
//			at the declaration of variables, added GENIPM if type==General IPM. this is because when this IP is entered into docket_master, it is inserted as GENIPM, so when it searches for type,
//			it won't find General IPM.
//	7/17/2013	added $user_level to header call
//	7/30/2013	enabled custom number listings. details in patents_list.php
//	8/15/2013	added tooltips on the sorting images so I could delete the red text telling the user what to do. it has to be on the image, not the title of the link because IE makes sense...
//	9/3/2013	cleaned up the code a little
//	9/6/2013	fixed a bug that had it reset the report with every sort. passed it in report c
//reports_list.php
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
// Set $var string\
if ($SORT=="SEARCH"){
  $var="SORT=SEARCH&type=$type&docket_number=$docket_number&".
  "type_ID=$type_ID";
  if($type == "General IPM")
  {$type = "GENIPM";}//this is here so it matches docket_master
  }
else $var="SORT=ALL";
// Set Defaults
if($number=="") {$number=50;}
if ($REPORT=="") $REPORT="A";
if ($NEXT=="1") {$START=$START+$number;}
elseif ($NEXT=="-1") {$START=$START-$number;}
else {$START="0";}
?>
<table align="left" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">Reports:&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=A&<?=$var;?>">All</a>&nbsp;|&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=C&<?=$var;?>">Financial</a>
	</tr>
</table>
<form action="<?=$PHP_SELF;?>">
<input type="hidden" name="module" value="<?=$module;?>">
<input type="hidden" name="REPORT" value="<?=$REPORT;?>">
<input type="hidden" name="NEXT" value="<?=$NEXT;?>">
<input type="hidden" name="START" value="<?=$START;?>">
<input type="hidden" name="ORDER" value="<?=$ORDER;?>">
<input type="hidden" name="var" value="<?=$var;?>">
<input type="hidden" name="ORDER2" value="<?=$ORDER2;?>">
<input type="hidden" name="SORT" value="<?=$SORT;?>">
<input type="hidden" name="type" value="<?=$type;?>">
<input type="hidden" name="docket_number" value="<?=$docket_number;?>">
<input type="hidden" name="type_ID" value="<?=$type_ID;?>">
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
if ($SORT=="ALL") echo("<center>LIST ALL DOCKETS");
else echo("<center>LIST DOCKET SEARCH RESULTS");
?><br><br>
<br>
  
<!-- REPORT TYPE A -->
<? if ($REPORT=="A"){
if ($ORDER=="") $ORDER="docket_number";?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
  <td width="135"><U>ECS Docket</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=docket_master.docket_number&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=docket_master.docket_number DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a></td>
  <td width="125"><U>Type</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=docket_master.type&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=docket_master.type DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a></td>
  <td width="175"><U>Title</U>
  <td width="125"><U>Client</U>
  <td width="125"><U>Create Date</U>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if ($SORT == "ALL")
  $sql="SELECT * FROM docket_master ORDER BY $ORDER, docket_number LIMIT $START, $number";
else // ($SORT == "SEARCH")           //the dual db search would be too extensive. only doing a single search
 $sql="SELECT * FROM docket_master WHERE	  	  
   ID LIKE '%$type_ID%' and
    docket_number LIKE '%$docket_number%' and
     type LIKE '%$type%' 
	ORDER BY $ORDER, docket_number LIMIT $START, $number";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){
  $docket_number=$row["docket_number"];
  $type=$row["type"];
  $type_ID=$row["ID"];

if ($type == "disclosure")
{
    $sql9="SELECT * FROM disc_filings WHERE disc_id='$type_ID'";
    $result9=mysql_query($sql9);
    while($row9=mysql_fetch_array($result9)){
        $title=$row9["title"];
        $create_date=$row9["create_date"];
	$client=$row9["client"];
    }
    $type = "Disclosure";	//to capitalize it
    $link = "disclosures_edit.php?module=" . $module . "&DISCFAM=0&DISCEDIT=1&DISC_ID=" . $type_ID . "&ACTIONS=Open&I=1&EDIT=N";
}
elseif ($type == "patent")
{
    $sql9="SELECT * FROM pat_filings WHERE pat_ID='$type_ID'";
    $result9=mysql_query($sql9);
    while($row9=mysql_fetch_array($result9)){
        $title=$row9["title"];
        $create_date=$row9["create_date"];
	$client=$row9["client"];
    }
    $type = "Patent";	//to capitalize it
    $link = "patents_edit.php?module=" . $module . "&PATFAM=0&PATEDIT=1&PAT_ID=" . $type_ID . "&ACTIONS=Open&I=1&EDIT=N";
}
elseif ($type == "trademark")
{
    $sql9="SELECT * FROM tm_filings WHERE tm_ID='$type_ID'";
    $result9=mysql_query($sql9);
    while($row9=mysql_fetch_array($result9)){
        $title=$row9["title"];
        $create_date=$row9["create_date"];
	$client=$row9["client"];
    }
    $type = "Trademark";	//to capitalize it
    $link = "trademarks_edit.php?module=" . $module . "&TMFAM=0&TMEDIT=1&TM_ID=" . $type_ID . "&ACTIONS=Open&I=1&EDIT=N";
}
elseif ($type == "NDA")
{
    $sql9="SELECT * FROM contracts WHERE ID='$type_ID'";
    $result9=mysql_query($sql9);
    while($row9=mysql_fetch_array($result9)){
        $title=$row9["title"];
        $create_date=$row9["create_date"];
	$client=$row9["client"];
    }
    $link = "ndas_edit.php?module=" . $module . "&ID=" . $type_ID . "&I=1&EDIT=N";
}
elseif ($type == "license")
{
    $sql9="SELECT * FROM contracts WHERE ID='$type_ID'";
    $result9=mysql_query($sql9);
    while($row9=mysql_fetch_array($result9)){
        $title=$row9["title"];
        $create_date=$row9["create_date"];
	$client=$row9["client"];
    }
    $type = "License";	//to capitalize it
    $link = "licenses_edit.php?module=" . $module . "&ID=" . $type_ID . "&I=1&EDIT=N";
}
elseif ($type == "GENIPM")
{
    $sql9="SELECT * FROM genipm_filings WHERE ID='$type_ID'";
    $result9=mysql_query($sql9);
    while($row9=mysql_fetch_array($result9)){
        $title=$row9["title"];
        $create_date=$row9["create_date"];
	$client=$row9["client"];
    }
    $type = "General IPM";	//to capitalize it
    $link = "genIPM_edit.php?module=" . $module . "&GENIPMEDIT=1&ID=" . $type_ID . "&ACTIONS=Open&I=1&EDIT=N&ATTACHEDFILES=All";
}
elseif ($type == "copyright")
{
    $sql9="SELECT * FROM copyrights WHERE ID='$type_ID'";
    $result9=mysql_query($sql9);
    while($row9=mysql_fetch_array($result9)){
        $title=$row9["title"];
        $create_date=$row9["create_date"];
	$client=$row9["client"];
    }
    $type = "Copyright";	//to capitalize it
    $link = "copyrights_edit.php?module=" . $module . "&ID=" . $type_ID . "&I=1&EDIT=N";
}
?>
<tr bgcolor=EEEEEE>
  <td width="135"><small><?=$docket_number;?></small></td>
  <td width="125"><small><?=$type;?><br>
  <td width="175"><small><a href="<?=$link;?>"><?=$title;?></a></small></td>
  <td width="125"><small><?=$client;?></small></td>
  <td width="125"><small><?=$create_date;?>
</tr>
<?}?>
</table>
<?}

//<!-- REPORT C -->
 elseif($REPORT=="C"){
if($ORDER=="") $ORDER="docket_number";?>
<table align="center" width="100%" cellpadding="5">
<tr bgcolor=EEEEEE><font size="-2">
 <tr bgcolor=EEEEEE><font size="-2">
  <td width="135"><U>ECS Docket</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=docket_master.docket_number&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=docket_master.docket_number DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a></td>
  <td width="125"><U>Type</U>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=docket_master.type&<?=$var?>"><img src="up.gif" width="13" height="11" alt="" border="0" title="Primary"></a>
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&REPORT=<?=$REPORT;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=docket_master.type DESC&<?=$var?>"><img src="down.gif" width="13" height="11" alt="" border="0" title="Primary"></a></td>
  <td width="175"><U>Title</U>
  <td width="125"><U>Client</U>
  <td width="125"><U>Total Cost</U>
</font></tr>
<?
//SQL Query for Selecting All $TYPE IP Records
if($SORT == "ALL")	
  $sql="SELECT * FROM docket_master ORDER BY $ORDER, docket_number LIMIT $START, $number";
else // ($SORT == "SEARCH")           //the dual db search would be too extensive. only doing a single search
 $sql="SELECT * FROM docket_master WHERE	  	  
   ID LIKE '%$type_ID%' and
    docket_number LIKE '%$docket_number%' and
     type LIKE '%$type%' 
	ORDER BY $ORDER, docket_number LIMIT $START, $number";
$result=mysql_query($sql);
//Print the records
while($row=mysql_fetch_array($result)){ 
  $docket_number=$row["docket_number"];
  $type_ID=$row["ID"];
  $type=$row["type"];
$total = 0;
  
if ($type == "disclosure")
{
    $sql9="SELECT * FROM disc_filings WHERE disc_id='$type_ID'";
    $result9=mysql_query($sql9);
    while($row9=mysql_fetch_array($result9)){
        $title=$row9["title"];
        $create_date=$row9["create_date"];
	$client=$row9["client"];
    }
    $sql10="SELECT * FROM accounts WHERE customer_ID = '$customer_ID' and ecs_docket=$docket_number";
    $result10=mysql_query($sql10);
    //Print the records
    while($row10=mysql_fetch_array($result10)){
      $amount=$row10["amount"];
      $total = $total + $amount;
    }
    $type = "Disclosure";	//to capitalize it
    $link = "disclosures_edit.php?module=" . $module . "&DISCFAM=0&DISCEDIT=1&DISC_ID=" . $type_ID . "&ACTIONS=Open&I=1&EDIT=N";
}
elseif ($type == "patent")
{
    $sql9="SELECT * FROM pat_filings WHERE pat_ID='$type_ID'";
    $result9=mysql_query($sql9);
    while($row9=mysql_fetch_array($result9)){
        $title=$row9["title"];
        $create_date=$row9["create_date"];
	$client=$row9["client"];
    }
    $sql10="SELECT * FROM accounts WHERE customer_ID = '$customer_ID' and ecs_docket=$docket_number";
    $result10=mysql_query($sql10);
    //Print the records
    while($row10=mysql_fetch_array($result10)){
      $amount=$row10["amount"];
      $total = $total + $amount;
    }
    $type = "Patent";	//to capitalize it
    $link = "patents_edit.php?module=" . $module . "&PATFAM=0&PATEDIT=1&PAT_ID=" . $type_ID . "&ACTIONS=Open&I=1&EDIT=N";
}
elseif ($type == "trademark")
{
    $sql9="SELECT * FROM tm_filings WHERE tm_ID='$type_ID'";
    $result9=mysql_query($sql9);
    while($row9=mysql_fetch_array($result9)){
        $title=$row9["title"];
        $create_date=$row9["create_date"];
	$client=$row9["client"];
    }
    $type = "Trademark";	//to capitalize it
    $link = "trademarks_edit.php?module=" . $module . "&TMFAM=0&TMEDIT=1&TM_ID=" . $type_ID . "&ACTIONS=Open&I=1&EDIT=N";
}
elseif ($type == "NDA")
{
    $sql9="SELECT * FROM contracts WHERE ID='$type_ID'";
    $result9=mysql_query($sql9);
    while($row9=mysql_fetch_array($result9)){
        $title=$row9["title"];
        $create_date=$row9["create_date"];
	$client=$row9["client"];
    }
    $sql10="SELECT * FROM accounts WHERE customer_ID = '$customer_ID' and ecs_docket=$docket_number";
    $result10=mysql_query($sql10);
    //Print the records
    while($row10=mysql_fetch_array($result10)){
      $amount=$row10["amount"];
      $total = $total + $amount;
    }
    $link = "ndas_edit.php?module=" . $module . "&ID=" . $type_ID . "&I=1&EDIT=N";
}
elseif ($type == "license")
{
    $sql9="SELECT * FROM contracts WHERE ID='$type_ID'";
    $result9=mysql_query($sql9);
    while($row9=mysql_fetch_array($result9)){
        $title=$row9["title"];
        $create_date=$row9["create_date"];
	$client=$row9["client"];
    }
    $sql10="SELECT * FROM accounts WHERE customer_ID = '$customer_ID' and ecs_docket=$docket_number";
    $result10=mysql_query($sql10);
    //Print the records
    while($row10=mysql_fetch_array($result10)){
      $amount=$row10["amount"];
      $total = $total + $amount;
    }
    $type = "License";	//to capitalize it
    $link = "licenses_edit.php?module=" . $module . "&ID=" . $type_ID . "&I=1&EDIT=N";
}
elseif ($type == "GENIPM")
{
    $sql9="SELECT * FROM genipm_filings WHERE ID='$type_ID'";
    $result9=mysql_query($sql9);
    while($row9=mysql_fetch_array($result9)){
        $title=$row9["title"];
        $create_date=$row9["create_date"];
	$client=$row9["client"];
    }
    $sql10="SELECT * FROM accounts WHERE customer_ID = '$customer_ID' and ecs_docket=$docket_number";
    $result10=mysql_query($sql10);
    //Print the records
    while($row10=mysql_fetch_array($result10)){
      $amount=$row10["amount"];
      $total = $total + $amount;
    }
    $type = "General IPM";	//to capitalize it
    $link = "genIPM_edit.php?module=" . $module . "&GENIPMEDIT=1&ID=" . $type_ID . "&ACTIONS=Open&I=1&EDIT=N&ATTACHEDFILES=All";
}
elseif ($type == "copyright")
{
    $sql9="SELECT * FROM copyrights WHERE ID='$type_ID'";
    $result9=mysql_query($sql9);
    while($row9=mysql_fetch_array($result9)){
        $title=$row9["title"];
        $create_date=$row9["create_date"];
	$client=$row9["client"];
    }
    $sql10="SELECT * FROM accounts WHERE customer_ID = '$customer_ID' and ecs_docket=$docket_number";
    $result10=mysql_query($sql10);
    //Print the records
    while($row10=mysql_fetch_array($result10)){
      $amount=$row10["amount"];
      $total = $total + $amount;
    }
    $type = "Copyright";	//to capitalize it
    $link = "copyrights_edit.php?module=" . $module . "&ID=" . $type_ID . "&I=1&EDIT=N";
}
?>
<tr bgcolor=EEEEEE>
  <td width="135"><small><?=$docket_number;?></small></td>
  <td width="125"><small><?=$type;?><br>
  <td width="175"><small><a href="<?=$link;?>"><?=$title;?></a></small></td>
  <td width="125"><small><?=$client;?></small></td>
  <td width="125"><small><a href="total_cost.php?module=<?=$module;?>&ID=<?=$docket_number;?>"><font color="red">$<?=$total;?></font></a></small></td>
</tr>
<?}?>
</table>
<?}html_footer(); ?>
