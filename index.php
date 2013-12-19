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
//Maitenece Log Jonathan Haas:
//	6/11/2013	Edited sites, deleted "Home", and edited description
//	6/21/2013	edited some formatting to attempt to make it more centered. It isn't perfect, but it's better
//	7/17/2013	added $user_level to header call
//	8/28/2013	cleaned up the code a little
// index.php
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);?>
<br><br><br>
<table align="center" width="200" border="0">
	  <tr><td align="center" colspan="2" bgcolor="EEEEEE">
          <font size="2"><b>Helpful Links</b></font>
      </td></tr>
	  <tr><td height="5">
      </td></tr>
      <tr><td width="11" align="center"><b>&nbsp;&#183;&nbsp;</b></td>
        <td width="100%" align="left"><a target="_blank" href="http://www.esterline.com">Esterline</a>
      </td></tr>
      <tr><td width="11" align="center" valign="top"><b>&nbsp;&#183;&nbsp;</b></td>
        <td width="100%" align="left"><a target=_blank href="https://www.korry.net/owa/auth/logon.aspx?replaceCurrent=1&url=https%3a%2f%2fwww.korry.net%2fowa%2f">Korry Email</a>
      </td></tr>
      <tr><td width="11" align="center" valign="top"><b>&nbsp;&#183;&nbsp;</b></td>
        <td width="100%" align="left"><a target=_blank href="http://www.uspto.gov">USPTO</a>
      </td></tr>
      <tr><td width="11" align="center" valign="top"><b>&nbsp;&#183;&nbsp;</b></td>
        <td width="100%" align="left"><a target=_blank href="http://www.loc.gov/copyright">US Copyright Office</a>
      </td></tr>
      <tr><td width="11" align="center" valign="top"><b>&nbsp;&#183;&nbsp;</b></td>
        <td width="100%" align="left"><a target=_blank href="http://www.wipo.int">World IP Organization</a>
      </td></tr>
       <tr><td width="11" align="center" valign="top"><b>&nbsp;&#183;&nbsp;</b></td>
        <td width="100%" align="left"><a target=_blank href="http://www.uspto.gov/web/offices/ac/qs/ope/fee031913.htm">USPTO Fees</a>
      </td></tr>
	  <tr><td colspan="2" height="5">
      </td></tr>
    </table></td><td>
    
<table align="right" width="100%" border="0"><tr><td>
&nbsp;&nbsp;Welcome to ECS Docket<br><br>
&nbsp;&nbsp;A database of Patents,<br>
&nbsp;&nbsp;Trademarks, and more
</td></tr></table>
<? html_footer(); ?>
