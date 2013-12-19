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
//	6/20/2013  	created using disclosures_list.php
//                 	this will search for a patent, then once one is found, send all of the information to pat_edit.php, essentially populating most fields with the disc's information.
//		  	added javascript at the bottom to automatically re-direct to the patent page.
//	6/21/2013   	the javascript didn't work on John's computer, so I'm going to just put a hyperlink there. If I have time, I should try to get it to work
//		    	deleted all of the search code. Replaced it with the search code from the beginning of the disclosure_edit paga--it is shorter and easier to find what you want.
//		   	found a way to do the re-direct. it works fine now, although if you leave the workaround in it causes problems. Commented out now.
//	7/17/2013	added $user_level to header call
//	7/18/2013	added authentication to $user_level while leaving in $sysadmin because I'm pretty sure that's just the root admin.
//	8/27/2013	cleaned up the code a little
//derive_patent.php -- 	User Access Level: User
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);

if ($sysadmin == "Y" or $user_level != "Viewer") {
  
  if ($NEXT=="1") $START=$START+50;
  else $START="0";
?>
<!-- SELECT RELATED DISCLOSURE -->
<?
  if ($submit_1==""){?>
  <table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
    <td width="100%" align="center" bgcolor="#FFFFFF">
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&DISCFAM=1&DISCEDIT=0&NEXT=0&START=<?=$START;?>&<?=$var;?>">First 50</a>&nbsp;|&nbsp;
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&DISCFAM=1&DISCEDIT=0&NEXT=1&START=<?=$START;?>&<?=$var;?>">Next 50</a>
    </td></tr>
  </table><br><br> 
  
  <center>ADD PATENT RECORD -- SELECT RELATED DISCLOSURE</center><br>
  <form method=get action="<?=$PHP_SELF;?>">
    <input type="hidden" name="module" value="<?=$module;?>">
  <table align="center" width="100%" border="0" cellpadding="5" cellspacing="2">
    <tr>
      <td align="right" colspan="3">
	    <small>DOCKET&nbsp;<input type="text" name="DOCKET_ALT" maxlength="10" size="10" value="<?=$DOCKET_ALT;?>"></small>&nbsp;&nbsp;
	<small>TITLE&nbsp;<input type="text" name="TITLE" maxlength="30" size="10" value="<?=$TITLE;?>"></small>&nbsp;&nbsp;
	<input type="submit" name="submit_search" value=" SEARCH ">&nbsp;&nbsp;
	<input type="submit" name="submit_50" value=" NEXT 50 ">&nbsp;&nbsp;
	<small><a href="disclosures_edit?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=0&I=2&EDIT=Y&NEW=Y&country=US">ADD NEW DISCLOSURE</a></small></td>
    </tr>
    <tr bgcolor=EEEEEE>
      <td width="20"><small>SELECT</small></td>
      <td width="50"><small>ECS DOCKET</small></td>
      <td width="480"><small>TITLE</small></td>
    </tr>
    <?

      $sql="SELECT disc_id, docket_alt, title FROM disc_filings WHERE
	customer_ID='$customer_ID' and
	    docket_alt LIKE '%$DOCKET_ALT%' and
	title LIKE '%$TITLE%' 	
	    ORDER BY docket_alt LIMIT $START, 50";
	    
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$DISC_ID=$row["disc_id"];
	$docket_alt=$row["docket_alt"];
	$title=$row["title"];
      ?>
      </td></tr>
      <tr bgcolor=EEEEEE>
	<td width="20"><input type="radio" name="DISC_ID" value="<?=$DISC_ID;?>"></td>
	<td width="100"><small><?=$docket_alt;?></small></td>
	<td width="430"><small><a href="disclosures_edit.php?module=<?=$module;?>&DISCEDIT=1&DISC_ID=<?=$DISC_ID;?>&I=1&EDIT=N"><?=$title;?></small></td>
      </tr>
    <?}?>
    <tr>
      <td align="center" colspan="3">
	<hr noshade size="1" width="100%">
	<input type="submit" name="submit_1" value="  OK  ">
      </td>
    </tr>
  </table>
<?}

else
{
//if found is someting (someone clicked on one)
    //get info from the disclosure that was selected
    $sql = "SELECT * FROM disc_filings WHERE disc_id='$DISC_ID'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
        $title = $row["title"];
        $docket = $row["docket"];
        $status = $row["status"];
        $firm = $row["firm"];
        $firm_contact = $row["firm_contact"];
        $client = $row["client"];
        $client_contact = $row["client_contact"];
        $country = $row["country"];
        $inventors = $row["inventors"];
        $abstract = $row["abstract"];
        $products = $row["products"];
        $notes = $row["notes"];
    ?>

<META HTTP-EQUIV="refresh" 
CONTENT="0;URL=patents_edit.php?module=Patents&PATEDIT=1&I=2&EDIT=Y&title=<?=$title;?>&docket=<?=$docket;?>&status=<?=$status;?>&firm=<?=$firm;?>&firm_contact=<?=$firm_contact;?>&client=<?=$client;?>&client_contact=<?=$client_contact;?>&country=<?=$country;?>&inventors=<?=$inventors;?>&abstract=<?=$abstract;?>&products=<?=$products;?>&notes=<?$notes;?>">
<?}} html_footer(); ?>
