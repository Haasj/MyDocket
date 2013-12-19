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
//Maitnenence Log Jonathan Haas:
//  7/17/2013   added $user_level to header call
//  8/27/2013   changed from short php tags. this will cause it to not break if we ever migrate to a server that doesn't allow them.
include("accesscontrol.php");
  html_header($fullname,$module,$orgadmin,$user_level);
echo "<br><center>ACCESS DENIED</center><br>";
html_footer();
//note: not closing php tag. reason: the whole file is php, so to avoid extra line parsing we're not closing them
