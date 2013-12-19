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
//  7/12/2013   updated the password. threw a bunch of errors, to fix these in the future go to C:\wamp\apps\phpmyadmin3.5.1/config.inc.php
//              NOTE: DON'T copy this over to ECSDocketTest since John has a different server and this will try to log in with the incorrect password.
// db.php
// This file should be placed in a directory acceessible to the system
// preferably not in the HTML directory (for security reasons)
// The two files that need access to this file are:
// accesscontrol.php and register.php
// Since this files is called by accesscontrol.php each time a protected page is
// opened, there is no need to connect to the db on each page
// (since it's already connected)


// Server configuration
$server = "Y";
// Read Only configuration
$read_only = "N";
// Database parameters
$dbhost = "server";
$dbuser = "uname";
$dbpass = "pw";  // ***** 20130610    --if you change the pw, make sure to change it in backup.php also
$db = "db";  // ***** 20130606

$dbcnx = @mysql_connect($dbhost, $dbuser, $dbpass)
  or die("The site database appears to be down.");

if (!@mysql_select_db($db))
  die("The site database is unavailable.");

?>
