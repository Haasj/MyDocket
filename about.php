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
//  7/22/2013   modified a little bit to reflect more of the functionality.
//  8/27/2013   changed from short php tags. this will cause it to not break if we ever migrate to a server that doesn't allow them.
// about.php
include("common.php");
html_headeracc(); ?>
<br>
<center>ABOUT</center><br>
<table align="center" width="630">
<p>ECS Docket is a comprehensive tool for managing intellectual property filings,
maintenance and licensing. ECS Docket was created by heavily modifying MyDocket source code.
MyDocket is open source and available for free.
For subscriptions, customized modules, development or additional information, contact
<a href="mailto:info@mydocket.com">info@mydocket.com</a>.</p>
<p><a target='_blank' href="http://sourceforge.net/projects/mydocket/">
Download the latest version at SourceForge</a></p>
<p>
ECS Docket Features Include:
<small>
<li>Manages all types of IP including disclosures, patents, trademarks, copyrights, licenses, NDAs, and any other general IPM.</li>
<li>ECS Docket also includes an invoicing system where you can upload pdf files and link transactions with any type of IP.</li>
<li>Each type of IP can be displayed and organized by docket number, title, filing data, response data and issue data</li>
<li>Licenses can be displayed and organized by inbound licenses, outbound licenses and cross-licenses</li>
<li>Non-Disclosure Agreements (NDAs) can be displayed and organized by inbound, outbound and mutual</li>
<li>Administrative control panel to add users with various access levels including read-write and read-only</li>
<li>Company relationship management and tracking (that can be updated by the client company)</li>
<!--<li>Reminder notices by e-mail in advance of due dates (i.e. 30 days, 7 days and 1 day) until checked off as completed</li>-->
<!--<li>Software runs on a managed server with the latest software updates (no need for customers to maintain the system)</li>-->
<li>Software can be installed on a Windows, Linux or Solaris Intranet</li>
<li>Local version can be installed on a Windows or Linux laptop computer for mobility</li>
<!--<li>Additional features and graphical enhancements will be added to ensure that the user interface remains easy to use</li>--></small>
</p>
<p>ECS Docket is written in PHP and employs a MySQL database.<br>
The preferred server environment is Linux, Apache, MySQL and PHP (LAMP).<br>
MyDocket is &copy; 2001-2004 <a target='_blank' href="http://www.ipgroup.org">IP Group</a>, all rights reserved.</p>
<p>MyDocket is provided as Open Source License under the Mozilla Public License
version 1.1, wich can be found at http://www.mozilla.org/MPL/MPL-1.1.html.</p>
</table>
<?php html_footer(); ?>
