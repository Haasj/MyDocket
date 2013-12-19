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
//	7/16/2013	changed it to say list/edit for clarity
//	7/17/2013	added $user_level to header call
//			deleted some beginning comments
//			changed the conditional statement so it only shows to user_level Admin
//	7/22/2013	deleted the users piece. this will migrate to aduc, so we don't need to edit any users.
//	8/1/2013	added optimize to the database. don't like how it looks very much now... but it isn't bad
//	8/15/2013	deleted database operations. Dale said IT was going to manage this
// setup.php
include("accesscontrol.php");
// $fullname = $accessrow["fullname"];
html_header($fullname,$module,$orgadmin,$user_level);?>
<br>
    <? if ("Y"==$read_only){?><p>THIS IS A BACKUP READ-ONLY SYSTEM.</p><?}?>
    <table align="center" border="0" width="400" cellpadding="0" cellspacing="10">
	  <? if ($user_level=="Admin"){?>
	  <tr><td></td><td colspan="2" align="center">SETUP CONTROLS</td><td></td></tr><tr>
	  <tr>
	  <td width="25%" align=right height="30"><small>MENUS</small></td>
	  <td width="25%" align=center bgcolor=EEEEEE><small><a href="menus_edit.php?module=<?=$module;?>&ID=0&I=0">ADD</a></small></td>
	  <td width="25%" align=center bgcolor=EEEEEE><small><a href="menus_list.php?module=<?=$module;?>">LIST/EDIT</a></small></td>
	  <td width="25%" align=center><small></small></td>
	  </tr>
	  <tr>
	  <td width="25%" align=right height="30"><small>REMINDERS</small></td>
	  <td width="25%" align=center bgcolor=EEEEEE><small><a href="notify.php?module=<?=$module;?>">SEND</a></small></td>
	  <td width="25%" align=center bgcolor=EEEEEE><small><a href="notices_edit.php?module=<?=$module;?>&I=0">LIST/EDIT</a></small></td>
	  <td width="25%" align=center><small></small></td>
	  </tr>
	  <tr>
	  <td width="25%" align=right height="30"><small>AUTO ACTIONS</small></td>
	  <td width="25%" align=center bgcolor=EEEEEE><small><a href="autoactions_edit.php?module=<?=$module;?>&ID=0&I=0">ADD</a></small></td>
	  <td width="25%" align=center bgcolor=EEEEEE><small><a href="autoactions_list.php?module=<?=$module;?>">LIST/EDIT</a></small></td>
	  <td width="25%" align=center><small></small></td>
	  </tr>
	  <?}?>
	</table>
<? html_footer();?>
