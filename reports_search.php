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
//	7/8/2013	created from accounts_search.php. Deleted and renamed a few different fields. this should be done.
//                      it would be too complicated to search by everything, so I'm limiting it to anything in the docket_master table
//                      made type select a combo box.
//                      Note: variable declaration will be off for General IPM because of this. also, capital letters are off too, but that doesn't matter in a mysql search.
//	7/17/2013	added $user_level to header call
//reports_search.php
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);?>
<br><br>
<center>SEARCH REPORTS</center><br>
<? if ($submitok==""){?>
<form method=get action="reports_list.php">
  <input type="hidden" name="module" value="<?=$module;?>">
  <input type="hidden" name="SORT" value="SEARCH">
  <input type="hidden" name="ORDER" value="docket_number">
<table align="center" border="0" cellpadding="0" cellspacing="5">
    <tr>
        <td align=right width="150">
            ECS Docket No.
        </td>
        <td width="400">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="docket_number" type="text" maxlength="35" size="35" value"<?=$docket_number;?>">
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            Type
        </td>
        <td width="400">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <select name=type size="1">
            <option><?=$type;?></option>
            <option>Disclosure</option>
	    <option>Patent</option>
            <option>Trademark</option>
            <option>Copyright</option>
            <option>License</option>
            <option>NDA</option>
            <option>General IPM</option>
            </select>
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            Type Index
        </td>
        <td width="400">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="client" type="type_ID" maxlength="35" size="35" value="<?=$type_ID;?>">
        </td>
    </tr>
    <tr>
        <td align="center" colspan="2" width="650">
            <hr noshade size="1" width="650">
            <input type=submit name="submitok" value="  OK  ">
        </td>
    </tr>
</table>			
</form>
<?} html_footer(); ?>
