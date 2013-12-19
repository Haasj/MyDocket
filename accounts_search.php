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
//	6/28/2013	created from ndas_search.php. everything should be right, but I have no idea why the formatting is way left. everything is set to center.
//      7/2/2013        account->transaction
//                      made the formatting look a little better. the reason why it was right was acually because of the width=100% field in the table. there must be something that is that size over there...
//	7/17/2013	added $user_level to header call
//      8/26/2013       cleaned up the code a little
//ndas_search.php
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);?>
<br><br>
<center>SEARCH TRANSACTIONS</center><br>
<? if ($submitok==""){?>
<form method=get action="accounts_list.php">
  <input type="hidden" name="module" value="<?=$module;?>">
  <input type="hidden" name="SORT" value="SEARCH">
  <input type="hidden" name="ORDER" value="acct_ID">
<table align="center" border="0" cellpadding="0" cellspacing="5">
    <tr>
        <td align=right width="150">
            Transaction ID
        </td>
        <td width="400">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="acct_ID" type="text" maxlength="35" size="35" value="<?=$acct_ID;?>">
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            Firm Docket No.
        </td>
        <td width="400">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="firm_docket" type="text" maxlength="35" size="35" value="<?=$firm_docket;?>">
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            ECS Docket No.
        </td>
        <td width="400">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="ecs_docket" type="text" maxlength="35" size="35" value"<?=$ecs_docket;?>">
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            PDF ID
        </td>
        <td width="400">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="pdf_id" type="text" maxlength="35" size="35" value="<?=$pdf_id;?>">
        </td>
    </tr>
    <tr>
        <td align=right width="150">
            PDF Name
        </td>
        <td width="400">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="name" type="text" maxlength="100" size="35" value="<?=$name;?>">
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
