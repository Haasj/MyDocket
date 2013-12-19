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
//	6/13/2013   	created with contents of patents_search.php
//	            	deleted search fields that were unneeded
//              	returns empty table, but I think that's an issue with disclosures_list.php. so I think this is done
//	6/20/2013	changed it to search by docket_alt instead of docket.
//			fixed the empty set error. there was an extra and in the query
//	7/17/2013	added $user_level to header call
//	7/31/2013	took out firm stuff
//	8/28/2013	cleaned up the code a little
// disclosures_search.php
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);?>
<br><br>
<center>SEARCH DISCLOSURES</center><br>
<? if ($submitok==""){?>
<form method=get action="disclosures_list.php">
  <input type="hidden" name="module" value="<?=$module;?>">
  <input type="hidden" name="SORT" value="SEARCH">
  <input type="hidden" name="ORDER" value="docket_alt">
<table align="center" width="100%" border="0" cellpadding="0" cellspacing="5">
    <tr>
        <td align="right" width="115">
            Title
        </td>
        <td colspan="3" align="left" width="450">
            <input name="title" type="text" maxlength="200" size="60" value="<?=$title;?>">
        </td>
    </tr>
    <tr>
        <td align="right" width="115">
            Docket No.
        </td>
        <td width="205">
            <input name="docket_alt" type="text" maxlength="35" size="20" value="<?=$docket_alt;?>">
        </td>
        <td align="right" width="115">
            Status
        </td>
        <td width="205">			
			<select name="status" size="1">
			<option><?=$status;?></option>
		    <? $sql="SELECT * FROM menus WHERE menu_type='PATENT_STATUS' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td align="right" width="115">
            Client
        </td>
        <td width="205">
            <select name="client" size="1">
			<option><?=$client;?></option>
		    <? $sql="SELECT * FROM menus WHERE customer_ID='$customer_ID' and menu_type='CLIENT' ORDER BY menu_name";
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
              $menu_name=$row["menu_name"];?>
              <option><?=$menu_name?></option>"
			<?}?>
            </select>
        </td>
        <td align="right" width="115">
            Client Contact
        </td>
        <td width="205">
            <input name="client_contact" type="text" maxlength="100" size="25" value="<?=$client_contact;?>">
        </td>
    </tr>
    </tr>
    <tr>
        <td align="right" width="115">
            Country
        </td>
        <td width="205">
            <select name="country" size="1">
            <option><?=$country;?></option>
			<? country_list();?>
            </select>
			&nbsp;&nbsp;<a target="_blank" href="help.php#country_codes">Country Codes</a>
        </td>
	</tr>
	<tr>
        <td align="right" width="115">
            Invention Date
        </td>
        <td width="205">
            <input type=text name="invention_date" size="11" maxlength="10" value="<?=$invention_date;?>">
			<small>&nbsp;(YYYY-MM-DD)</small>
        </td>
        </tr>
     <tr>
        <td align="center" colspan="4" width="6500">
            <hr noshade size="1" width="650">
            <input type=submit name="submitok" value="  OK  ">
    </tr>
</table>
</form>
<?} html_footer(); ?>
