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
//	6/19/2013	To do: edit to look like John wants it
//			fixed the delete button to delte from the correct table. it was attempting to delete from the users table
//			altered permissions to require ok, first/last, company, country, citizen
//			commented out middle, street, tele, fax, email. I left them in the db so it's really easy to get them back
//			added select box for the company; using the firms in menus
//			fixed some broken links
//			to do: fix the centering issue... it should be centered, but it's not for some reason
//	7/3/2013	added close button
//	7/9/2013	added td's with spaces and deleted width=100% to fix formatting issues.
//			added refresh to go back to record if it's done being edited
//	7/12/2013	added fetch_id so the refresh would work on new entries, also passed module to delete_confirm.
//	7/16/2013	added new link to create new inventor
//	7/17/2013	added $user_level to header call
//	7/18/2013	replaced all authentication to $user_level while leaving in $sysadmin because I'm pretty sure that's just the root admin.
//	8/28/2013	cleaned up the code a little
// inventors_edit.php -- User Access Level: User
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
// For new records, incoming link is set to $ID="0" and I="0"
// Otherwise, $ID is set to the existing record number
// SQL Query for an existing NDA record and retrieving data
if ($I=="1"){
$sql="SELECT * FROM inventors WHERE ID='$ID'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
    $org = $row["org"];			  
    $first_name_new = $row["first_name"];			  
    $middle_name_new = $row["middle_name"];			  
    $last_name_new = $row["last_name"];
	$company_new = $row["company"];
	$street_new = $row["street"];
	$city_new = $row["city"];
	$state_new = $row["state"];
	$zip_new = $row["zip"];
	$country_new = $row["country"];
	$citizen_new = $row["citizen"];
	$tel_new = $row["tel"];
	$fax_new = $row["fax"];
	$email_new = $row["email"];
    $notes_new = $row["notes"];
    $creator = $row["creator"];
    $create_date = $row["create_date"];
    $editor = $row["editor"];
    $edit_date = $row["edit_date"];
}
if (($sysadmin=="Y" or $user_level != "Viewer") and $read_only=="N" and $EDIT=="Y"){?>
<table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
  <td align="right" bgcolor="#FFFFFF">
    <td width="150" align="center" bgcolor="#FFFFFF">
	<a href="JavaScript:window.close()">Close</a>
<? if ($ID!="0"){?>
	&nbsp;|&nbsp;<a href="delete_confirm.php?module=<?=$module;?>&TABLE=inventors&ID=<?=$ID;?>&NAME=<?=$first_name_new;?> <?=$last_name_new;?>">Delete</a>
<?}?><br><br>
  </td></tr>
</table>
<? if ($ID=="0") echo("<br><br><center>ADD INVENTOR RECORD (<font color=orangered size=+1><TT><B>*</B></TT></font> REQUIRED)</center><br>");
   else echo("<br><br><center>EDIT INVENTOR RECORD (<font color=orangered size=+1><TT><B>*</B></TT></font> REQUIRED)</center><br>");
if ($submitok=="" or $last_name_new=="" or $first_name_new=="" or $company_new=="" or $country_new=="" or $citizen_new==""){
    if ($submitok!=""){?>
    <center><br><font color="red">Please update fields marked with a red asterisk.</font><br></center><?}?>
<form method=post action="<?=$PHP_SELF;?>">
  <input type="hidden" name="ID" value="<?=$ID;?>">
  <input type="hidden" name="I" value="0">
  <input type="hidden" name="EDIT" value="Y">
  <input type="hidden" name="module" value="<?=$module;?>">
<table align="center" border="0" cellpadding="0" cellspacing="10">
    <tr>
    <tr>
        <td align=right width="150">
            <p>First Name</p>
        </td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="400">
            <input name=first_name_new type=text maxlength=100 size=35 value="<?=$first_name_new;?>">
            <font color=orangered size=+1><TT><B>*</B></TT></font>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Last Name</p>
        </td>
	<td></td>
        <td width="400">
            <input name=last_name_new type=text maxlength=100 size=35 value="<?=$last_name_new;?>">
            <font color=orangered size=+1><TT><B>*</B></TT></font>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Company</p>
        </td>
	<td></td>
        <td width="400">
	    <select name="company_new" size="1">
                          <option><?=$company_new;?></option>
                          <? $sql="SELECT * FROM menus WHERE customer_ID='$customer_ID' and menu_type='CLIENT' ORDER BY menu_name";
                          $result=mysql_query($sql);
                          while($row=mysql_fetch_array($result)){
                    $menu_name=$row["menu_name"];?>
                    <option><?=$menu_name?></option>"
                          <?}?>
                  </select>
	    <font color=orangered size=+1><TT><B>*</B></TT></font>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>City</p>
        </td>
	<td></td>
        <td width="400">
            <input name=city_new type=text maxlength=100 size=35 value="<?=$city_new;?>">
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>State</p>
        </td>
	<td></td>
        <td width="400">
            <select name=state_new size="1">
            <option><?=$state_new;?></option>
            <option>AL</option><option>AK</option><option>AZ</option><option>AR</option><option>CA</option>
            <option>CO</option><option>CT</option><option>DE</option><option>DC</option><option>FL</option>
            <option>GA</option><option>HI</option><option>ID</option><option>IL</option><option>IN</option>
            <option>IA</option><option>KS</option><option>KY</option><option>LA</option><option>ME</option>
	    <option>MD</option><option>MA</option><option>MI</option><option>MN</option><option>MS</option>
	    <option>MO</option><option>MT</option><option>NE</option><option>NV</option><option>NH</option>
            <option>NJ</option><option>NM</option><option>NY</option><option>NC</option><option>ND</option>
            <option>OH</option><option>OK</option><option>OR</option><option>PA</option><option>RI</option>
            <option>SC</option><option>SD</option><option>TN</option><option>TX</option><option>UT</option>
            <option>VT</option><option>VA</option><option>WA</option><option>WV</option><option>WI</option>
            <option>WY</option>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Zip Code</p>
        </td>
	<td></td>
        <td width="400">
            <input name=zip_new type=text maxlength=100 size=35 value="<?=$zip_new;?>">
        </td>
    </tr>
	<tr>
		<td align=right width="150">
            <p>Country</p>
        </td>
	<td></td>
        <td width="400">
            <select name=country_new>
            <option><?=$country_new;?></option>
	        <option>AL</option><option>AM</option><option>AP</option><option>AT</option>
	        <option>AU</option><option>AZ</option><option>BA</option><option>BB</option>
	        <option>BE</option><option>BF</option><option>BG</option><option>BJ</option>
	        <option>BR</option><option>BY</option><option>CA</option><option>CF</option>
	        <option>CG</option><option>CH</option><option>CI</option><option>CM</option>
	        <option>CN</option><option>CR</option><option>CU</option><option>CY</option>
	        <option>CZ</option><option>DE</option><option>DK</option><option>DZ</option>
	        <option>EA</option><option>EE</option><option>EP</option><option>ES</option>
	        <option>FI</option><option>FR</option><option>GA</option><option>GB</option>
	        <option>GE</option><option>GH</option><option>GM</option><option>GN</option>
	        <option>GR</option><option>GW</option><option>HR</option><option>HU</option>
	        <option>IB</option><option>ID</option><option>IE</option><option>IL</option>
	        <option>IN</option><option>IS</option><option>IT</option><option>JP</option>
	        <option>KE</option><option>KG</option><option>KP</option><option>KR</option>
	        <option>KZ</option><option>LI</option><option>LK</option><option>LR</option>
	        <option>LS</option><option>LT</option><option>LU</option><option>LV</option>
	        <option>MA</option><option>MC</option><option>MD</option><option>MG</option>
	        <option>MK</option><option>ML</option><option>MN</option><option>MR</option>
	        <option>MW</option><option>MX</option><option>NE</option><option>NL</option>
	        <option>NO</option><option>NZ</option><option>OA</option>
	        <option>PL</option><option>PT</option><option>RO</option><option>RU</option>
	        <option>SD</option><option>SE</option><option>SG</option><option>SI</option>
	        <option>SK</option><option>SL</option><option>SN</option><option>SZ</option>
	        <option>TD</option><option>TG</option><option>TJ</option><option>TM</option>
	        <option>TR</option><option>TT</option><option>TW</option><option>TZ</option>
	        <option>UA</option><option>UG</option><option selected>US</option><option>UZ</option>
	        <option>VN</option><option>YU</option><option>ZA</option><option>ZW</option>
            </select>
            <font color=orangered size=+1><TT><B>*</B></TT></font>
	        &nbsp;&nbsp;<a target="_blank" href="help.php#country_codes">Country Codes</a>
        </td>
	</tr>
	<tr>
		<td align=right width="150">
            <p>Citizen</p>
        </td>
	<td></td>
        <td width="400">
            <select name=citizen_new>
            <option><?=$citizen_new;?></option>
	        <option>AL</option><option>AM</option><option>AP</option><option>AT</option>
	        <option>AU</option><option>AZ</option><option>BA</option><option>BB</option>
	        <option>BE</option><option>BF</option><option>BG</option><option>BJ</option>
	        <option>BR</option><option>BY</option><option>CA</option><option>CF</option>
	        <option>CG</option><option>CH</option><option>CI</option><option>CM</option>
	        <option>CN</option><option>CR</option><option>CU</option><option>CY</option>
	        <option>CZ</option><option>DE</option><option>DK</option><option>DZ</option>
	        <option>EA</option><option>EE</option><option>EP</option><option>ES</option>
	        <option>FI</option><option>FR</option><option>GA</option><option>GB</option>
	        <option>GE</option><option>GH</option><option>GM</option><option>GN</option>
	        <option>GR</option><option>GW</option><option>HR</option><option>HU</option>
	        <option>IB</option><option>ID</option><option>IE</option><option>IL</option>
	        <option>IN</option><option>IS</option><option>IT</option><option>JP</option>
	        <option>KE</option><option>KG</option><option>KP</option><option>KR</option>
	        <option>KZ</option><option>LI</option><option>LK</option><option>LR</option>
	        <option>LS</option><option>LT</option><option>LU</option><option>LV</option>
	        <option>MA</option><option>MC</option><option>MD</option><option>MG</option>
	        <option>MK</option><option>ML</option><option>MN</option><option>MR</option>
	        <option>MW</option><option>MX</option><option>NE</option><option>NL</option>
	        <option>NO</option><option>NZ</option><option>OA</option>
	        <option>PL</option><option>PT</option><option>RO</option><option>RU</option>
	        <option>SD</option><option>SE</option><option>SG</option><option>SI</option>
	        <option>SK</option><option>SL</option><option>SN</option><option>SZ</option>
	        <option>TD</option><option>TG</option><option>TJ</option><option>TM</option>
	        <option>TR</option><option>TT</option><option>TW</option><option>TZ</option>
	        <option>UA</option><option>UG</option><option selected>US</option><option>UZ</option>
	        <option>VN</option><option>YU</option><option>ZA</option><option>ZW</option>
            </select>
            <font color=orangered size=+1><TT><B>*</B></TT></font>
	        &nbsp;&nbsp;<a target="_blank" href="help.php#country_codes">Country Codes</a>
        </td>
	</tr>
	<tr>
        <td align=right width="150">
            <p>Notes</p>
        </td>
	<td></td>
        <td width="400">
            <textarea wrap name=notes_new rows=2 cols=35><?=$notes_new;?></textarea>
        </td>
	</tr>	
    <tr>
        <td align="center" colspan="4" width="100%">
            <hr noshade size="1" width="500">
            <input type=submit name="submitok" value="   OK   ">
        </td>
    </tr>
</table>
<?
}

else {
    if ($ID=="0"){ // Process new record submission
        $sql = "INSERT INTO inventors SET
              customer_ID = '$customer_ID',
              org = '$userorg',
			  first_name = '$first_name_new',
			  middle_name = '$middle_name_new',
			  last_name = '$last_name_new',
			  company = '$company_new',
			  street = '$street_new',
			  city = '$city_new',
			  state = '$state_new',
			  zip = '$zip_new',
			  country = '$country_new',
			  citizen = '$citizen_new',
			  tel = '$tel_new',
			  fax = '$fax_new',
			  email = '$email_new',
              notes = '$notes_new',
			  creator = '$fullname',
			  create_date = '$today'";
    
}

else // UPDATE EXISTING RECORD
    $sql = "UPDATE inventors SET
              customer_ID = '$customer_ID',
              org = '$userorg',
			  first_name = '$first_name_new',
			  middle_name = '$middle_name_new',
			  last_name = '$last_name_new',
			  company = '$company_new',
			  street = '$street_new',
			  city = '$city_new',
			  state = '$state_new',
			  zip = '$zip_new',
			  country = '$country_new',
			  citizen = '$citizen_new',
			  tel = '$tel_new',
			  fax = '$fax_new',
			  email = '$email_new',
              notes = '$notes_new',
			  editor = '$fullname',
			  edit_date = '$today'
              WHERE ID='$ID'";
			  
// RUN THE QUERY
     if (!mysql_query($sql)){
             error("A database error occurred in processing your ".
              "submission.\\nIf this error persists, please ".
              "contact your HelpDesk.");
             }
if ($ID == "0") {$ID=mysql_insert_id();}	//this will return zero if there was nothing auto_incremented. that's why the if is there
?>
<META HTTP-EQUIV="refresh" 
CONTENT="0;URL=<?=$PHP_SELF;?>?module=<?=$module;?>&ID=<?=$ID?>&I=1">
<?}}

else { // DISPLAY RECORD -- NOT EDIT
?>
<!-- VIEW RECORD -->
<? if ($sysadmin=="Y" or $user_level != "Viewer"){?>
<table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">

    <td width="100" align="center" bgcolor="#FFFFFF">
	<a href="JavaScript:window.close()">Close</a>&nbsp;|&nbsp;<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&ID=<?=$ID;?>&I=1&EDIT=Y">Edit</a>&nbsp;|&nbsp;<a href="inventors_edit.php?EDIT=Y&I=0&ID=0">New</a>
  </td></tr>
</table>
<?}?><br><br>
<center>FULL INVENTOR RECORD</center><br>
<table align="center" border="0" cellpadding="0" cellspacing="10">
    <tr>
        <td align=right width="150">
            <p>First Name</p>
        </td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="400" bgcolor=EEEEEE>
            <?=$first_name_new;?>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Last Name</p>
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$last_name_new;?>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Company</p>
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$company_new;?>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>City</p>
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$city_new;?>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>State</p>
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$state_new;?>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            <p>Zip Code</p>
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$zip_new;?>
        </td>
    </tr>
	<tr>
		<td align=right width="150">
            <p>Country</p>
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$country_new;?>
	        &nbsp;&nbsp;<a target="_blank" href="help.php#country_codes">Country Codes</a>
        </td>
	</tr>
	<tr>
		<td align=right width="150">
            <p>Citizen</p>
        </td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$citizen_new;?>
	        &nbsp;&nbsp;<a target="_blank" href="help.php#country_codes">Country Codes</a>
        </td>
	</tr>
	<tr>
        <td align=right width="150">
            <p>Notes</p>
	</td>
	<td></td>
        <td width="400" bgcolor=EEEEEE>
            <?=$notes_new;?>
        </td>
	</tr>
</table>
<?}?>
<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#336666" bgcolor="#336666"><tr>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Creator: <?=$creator;?> on <?=$create_date;?></small></td>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Editor: <?=$editor;?> on <?=$edit_date;?></small></td></tr>
</table>
<? html_footer(); ?>
