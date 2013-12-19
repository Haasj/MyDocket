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
//	7/17/2013	added $user_level to header call
//      7/30/2013       changed some wording and added the viewer description
//      8/9/2013        added serbia (RS)
//      8/28/2013       cleaned up the code a little
//      9/6/2013        added OHIM (EM)
// help.php
include("common.php");
html_header($fullname,$module,$orgadmin,$user_level); ?>
<br>
<center>ECSDOCKET HELP</center><br>
<table border="0" width="300" align="center" style="border: 1px solid #0000FF" cellpadding="0" cellspacing="0">
  <td width="33%" align="center" bgcolor="#EEEEEE"><small><a href="#user_manual">User Manual</a></small></td>
  <td width="34%" align="center" bgcolor="#EEEEEE"><small><a href="#glossary">Glossary</a></small></td>
  <td width="33%" align="center" bgcolor="#EEEEEE"><small><a href="#country_codes">Country Codes</a></small></td>
</table><br>
<table border="0" align="center" width="630"><tr><td colspan="4">
<p><a name="user_manual"></a><font color="#0000FF"><u>USER MANUAL</u></font></p>
<p>ADMINISTRATION AND SETUP</p>
<p>ECSDocket includes administrative features that need to be setup by a user 
with administrative privileges.  Before IP records can be added, the administrator 
will add the names of service firms and clients to the menu.  This will allow the 
entry of data records with corresponding entries that can be sorted for quick identification.  
If the ECSDocket customer is a single corporation, there may be only one client.  If a 
corporation has many divisions, there may be a client set up for each division.  If the 
ECSDocket customer is a law firm, there may be only one service firm.</p>
<p>The Korry HelpDesk has the authority to add additional users who 
may be restricted to only viewing records associated with their service firm or client identity.
For example a corporation may have a number of service firms and each service firm user is 
restricted to viewing records associated with that service firm.  Alternately, a law 
firm may have a number of clients and each client user is restricted to viewing 
records associated with that client.</p>
<p>STANDARD USER</p>
<p>A standard user can access the system but cannot add service firms or clients.  
A standard user can do anything else available to do in the system such as edit,
add, or delete records.</p>
<p>STANDARD VIEWER</p>
<p>A standard viewer is read-only. They can view every record and transaction, but
not edit anything.</p>
<p><a name="glossary"></a><font color="#0000FF"><u>GLOSSARY</u></font></p>
<p>PATENTS</p>
<p>Provisional Patent Application -- a provisional application is not examined 
and automatically goes abandoned 12 months after filing.  A provisional application 
must be followed up with a utility patent application within the 12 month period.</p>
<p>Utility Patent Application -- this is what is normally referred to as a patent application. 
A utility application typically describes and claims a new structure, composition or method. 
A utility patent is valid for 20 years from the first priority claim unless it lapses due 
to failure to pay a maintenance fee or it is declarared invalid by a court.</p>
<p>Design Patent Application -- this type of patent application protects the ornamental 
appearance of a product.  A design patent is valid for 14 years from it date of issue.  
Design patents don't require maintenance fees.</p>
<p>TRADEMARKS</p>
<p>Intent To Use (ITU) Application -- an ITU application is one where the applicant has 
not yet used the mark in commerce, but intends to.  An ITU application is granted constructive 
use of the mark on its filing date, but a Statement of Use must be filed to obtain a registration.</p>
<p>Principal Register -- the Principal Register is the primary regsiter at the Trademark Office. 
Only fanciful or suggestive marks may be initially registered.  Marks having secondary meaning (e.g. 
widespread consumer recognition) may also be registered upon proof.</p>
<p>Supplemental Register -- the Supplemental Register is a secondary register and can 
include descriptive marks.  Once the mark is registered on the Supplemental Register for five 
years, the owner may petition to have it transferred to the Principal Register upon proof 
of secondary meaning.</p>

<p><a name="country_codes"></a><font color="#0000FF"><u>COUNTRY CODES</u></font></p>
</td></tr>
<tr>
<td>AL</td><td>Albania</td>
<td>AM</td><td>Armenia</td>
</tr>
<tr>
<td>AP</td><td>African Regional IP Organization</td>
<td>AR</td><td>Argentina</td>
</tr>
<tr>
<td>AT</td><td>Austria</td>
<td>AU</td><td>Australia</td>
</tr>
<tr>
<td>AZ</td><td>Azerbaijan</td>
<td>BA</td><td>Bosnia & Herzegovina</td>
</tr>
<tr>
<td>BB</td><td>Barbados</td>
<td>BE</td><td>Belgium</td>
</tr>
<tr>
<td>BF</td><td>Burkina Faso</td>
<td>BG</td><td>Bulgaria</td>
</tr>
<tr>
<td>BJ</td><td>Benin</td>
<td>BR</td><td>Brazil</td>
</tr>
<tr>
<td>BY</td><td>Belarus</td>
<td>CA</td><td>Canada</td>
</tr>
<tr>
<td>CF</td><td>Central African Republic</td>
<td>CG</td><td>Congo</td>
</tr>
<tr>
<td>CH</td><td>Switzerland</td>
<td>CI</td><td>Côte d'Ivoire</td>
</tr>
<tr>
<td>CM</td><td>Cameroon</td>
<td>CN</td><td>China</td>
</tr>
<tr>
<td>CR</td><td>Costa Rica</td>
<td>CU</td><td>Cuba</td>
</tr>
<tr>
<td>CY</td><td>Cyprus</td>
<td>CZ</td><td>Czech Republic</td>
</tr>
<tr>
<td>DE</td><td>Germany</td>
<td>DK</td><td>Denmark</td>
</tr>
<tr>
<td>DZ</td><td>Algeria</td>
<td>EA</td><td>Eurasian Patent Organization</td>
</tr>
<tr>
<td>EE</td><td>Estonia</td>
<td>EP</td><td>European Patent Organisation</td>
</tr>
<tr>
<td>ES</td><td>Spain</td>
<td>EM</td><td>Office for Harmonization of the Internal Market (OHIM)</td>
</tr>
<tr>
<td>FI</td><td>Finland</td>
<td>FR</td><td>France</td>
</tr>
<tr>
<td>GA</td><td>Gabon</td>
<td>GB</td><td>United Kingdom</td>
</tr>
<tr>
<td>GE</td><td>Georgia</td>
<td>GH</td><td>Ghana</td>
</tr>
<tr>
<td>GM</td><td>Gambia</td>
<td>GN</td><td>Guinea</td>
</tr>
<tr>
<td>GR</td><td>Greece</td>
<td>GW</td><td>Guinea-Bissau</td>
</tr>
<tr>
<td>HR</td><td>Croatia</td>
<td>HU</td><td>Hungary</td>
</tr>
<tr>
<td>IB</td><td>World Intellectual Property Organization</td>
<td>ID</td><td>Indonesia</td>
</tr>
<tr>
<td>IE</td><td>Ireland</td>
<td>IL</td><td>Israel</td>
</tr>
<tr>
<td>IN</td><td>India</td>
<td>IS</td><td>Iceland</td>
</tr>
<tr>
<td>IT</td><td>Italy</td>
<td>JP</td><td>Japan</td>
</tr>
<tr>
<td>KE</td><td>Kenya</td>
<td>KG</td><td>Kyrgyzstan</td>
</tr>
<tr>
<td>KP</td><td>Democratic People's Republic of Korea</td>
<td>KR</td><td>Republic of Korea</td>
</tr>
<tr>
<td>KZ</td><td>Kazakhstan</td>
<td>LI</td><td>Liechtenstein</td>
</tr>
<tr>
<td>LK</td><td>Sri Lanka</td>
<td>LR</td><td>Liberia</td>
</tr>
<tr>
<td>LS</td><td>Lesotho</td>
<td>LT</td><td>Lithuania</td>
</tr>
<tr>
<td>LU</td><td>Luxembourg</td>
<td>LV</td><td>Latvia</td>
</tr>
<tr>
<td>MA</td><td>Morocco</td>
<td>MC</td><td>Monaco</td>
</tr>
<tr>
<td>MD</td><td>Republic of Moldova</td>
<td>MG</td><td>Madagascar</td>
</tr>
<tr>
<td>MK</td><td>The former Yugoslav Republic of Macedonia</td>
<td>ML</td><td>Mali</td>
</tr>
<tr>
<td>MN</td><td>Mongolia</td>
<td>MR</td><td>Mauritania</td>
</tr>
<tr>
<td>MW</td><td>Malawi</td>
<td>MX</td><td>Mexico</td>
</tr>
<tr>
<td>NE</td><td>Niger</td>
<td>NL</td><td>Netherlands</td>
</tr>
<tr>
<td>NO</td><td>Norway</td>
<td>NZ</td><td>New Zealand</td>
</tr>
<tr>
<td>OA</td><td>African Intellectual Property Organization</td>
<td>PCT</td><td>Patent Cooperation Treaty</td>
</tr>
<tr>
<td>PL</td><td>Poland</td>
<td></td><td></td>
</tr>
<tr>
<td>PT</td><td>Portugal</td>
<td>RO</td><td>Romania</td>
</tr>
<tr>
<td>RU</td><td>Russian Federation</td>
<td>SD</td><td>Sudan</td>
</tr>
<tr>
<td>RS</td><td>Serbia</td>
</tr>
<tr>
<td>SE</td><td>Sweden</td>
<td>SG</td><td>Singapore</td>
</tr>
<tr>
<td>SI</td><td>Slovenia</td>
<td>SK</td><td>Slovakia</td>
</tr>
<tr>
<td>SL</td><td>Sierra Leone</td>
<td>SN</td><td>Senegal</td>
</tr>
<tr>
<td>SZ</td><td>Swaziland</td>
<td>TD</td><td>Chad</td>
</tr>
<tr>
<td>TG</td><td>Togo</td>
<td>TJ</td><td>Tajikistan</td>
</tr>
<tr>
<td>TM</td><td>Turkmenistan</td>
<td>TR</td><td>Turkey</td>
</tr>
<tr>
<td>TT</td><td>Trinidad and Tobago</td>
<td>TZ</td><td>United Republic of Tanzania</td>
</tr>
<tr>
<td>TW</td><td>Taiwan</td>
<td>UA</td><td>Ukraine</td>
</tr>
<tr>
<td>UG</td><td>Uganda</td>
<td>US</td><td>United States of America</td>
</tr>
<tr>
<td>UZ</td><td>Uzbekistan</td>
<td>VN</td><td>Viet Nam</td>
</tr>
<tr>
<td>YU</td><td>Yugoslavia</td>
<td>ZA</td><td>South Africa</td>
</tr>
<tr>
<td>ZW</td><td>Zimbabwe</td>
<td></td><td></td></tr>

</table>
<? html_footer(); ?>
