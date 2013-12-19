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
//	8/1/2013	created. this is a simple page that optimizes all of the tables. i'm not 100% sure exactly how optimizing actually works, but it cleans up the database. this doesn't have to be run that
//              	often, since i've only done on one or two tables before this and there were less that 500 bytes of overhead. I tried to get it to display the done when it was done with the cleaning, but
//              	it seems to delay before the page load instead of halfway through the program. nevertheless, it still works fine.
//	8/12/2013	added ctr_actions optimizer
// setup.php
include("accesscontrol.php");
// $fullname = $accessrow["fullname"];
html_header($fullname,$module,$orgadmin,$user_level);?>
<br>
    <? if ("Y"==$read_only){?><p>THIS IS A BACKUP READ-ONLY SYSTEM.</p><?}?>
    <table align="center" border="0" width="400" cellpadding="0" cellspacing="10">
	  <? if ($user_level=="Admin"){?>
	  <tr><td colspan="3" align="center">OPTIMIZATION STATUS</td></tr><tr>
	  <tr>
	  <td width="33%" align=right height="30">accounts</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table accounts");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
          <tr>
	  <td width="33%" align=right height="30">autoactions</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table autoactions");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
          <tr>
	  <td width="33%" align=right height="30">contracts</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table contracts");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
          <tr>
	  <td width="33%" align=right height="30">copyrights</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table copyrights");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
          <tr>
	  <td width="33%" align=right height="30">cpy_documents</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table cpy_documents");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	    <tr>
	  <td width="33%" align=right height="30">ctr_actions</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table ctr_actions");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">ctr_documents</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table ctr_documents");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">customers</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table customers");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">disc_actions</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table disc_actions");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">disc_documents</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table disc_documents");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">disc_families</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table disc_families");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">disc_filings</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table disc_filings");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">disc_inventors</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table disc_inventors");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">disc_patents</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table disc_patents");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">docket_master</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table docket_master");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">genipm_actions</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table genipm_actions");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">genipm_documents</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table genipm_documents");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">genipm_filings</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table genipm_filings");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">inventors</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table inventors");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">menus</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table menus");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">pat_actions</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table pat_actions");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">pat_disclosures</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table pat_disclosures");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">pat_documents</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table pat_documents");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">pat_families</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table pat_families");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">pat_filings</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table pat_filings");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">pat_inventors</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table pat_inventors");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">pdf_combos</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table pdf_combos");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">tm_actions</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table tm_actions");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">tm_documents</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table tm_documents");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">tm_families</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table tm_families");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">tm_filings</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table tm_filings");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	 <tr>
	  <td width="33%" align=right height="30">users</td>
	  <td width="33%" align=center><small>...</small></td>
          <? mysql_query("optimize table users");?>
	  <td width="33%" align=center ><small><font color=green>done</font></small></td>
	  </tr>
	  <?}?>
	</table>
    <center>Click <a href="setup.php?module=Setup">here</a> to return to Setup.</center>
<? html_footer();?>
