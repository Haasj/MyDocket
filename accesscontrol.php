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
//Maitenence Log Jonathan Haas
//	6/11/2013	Edited guest pw update
//	6/14/2013	added link to work with register.php, but commented it out in the end.
//	7/16/2013	currently only blank passwords work. we may try to switch this over to ADUC managed passwords through IT, so I'm going to tentatively ignore this issue for now.
//			added declaration for user_level at the bottom. also, got passwords to work. did this by making the varchar length in the db 80 (?) but it works now
//	7/22/2013	First try got it to bind to aduc. binds fine to my own account, I'll make an account in the db for me, and we'll see if I can log into the program and bind successfully.
//			works with ADUC. However, since I'm not sure how permissions are going to work eventually, (AD controlled or MyDocket controlled) I have to have dual users.
//			a.k.a. the same username and password in MyDocket as well as in ADUC. I've done this for myself (jhaas) currently. So currently that is the only way into the site.
//			edit: made an ADUC authentication exception for guest. this will eventually need to be removed, but now you can get in that way as well.
//			nex steps: the issue now is that it hits ADUC every page load for credentials. I need to make a class to store the variables so it only hits ADUC once on login, then stores the info
//			did it without a class. If I had a class, I'd still run into the problem of storing the data somewhere without re-pinging aduc for it. did this by registering a bunch of session variables
//			this is kinda annoying, but the number will go down once I streamline it with aduc and we don't even have a user table in the db.
//			tried to replace session_register with $_session, but I couldn't get it to work.
//	7/24/2013	tried to get it to work with aduc to authenticate the group membership. can't figure it out at all. I think I have the right CN, I just think that the filter is wrong somehow.
//			also, I don't know how to check against different things, such as "group"=ECSDocket_Admin --I don't know if it's even called group
//	7/25/2013	after all day of searching the internet, I finally figured it out. the way I will check is kinda do a double search -- here's how it works:
//			I connect to AD using ldap_connect like i've been doing, then check for the user in the Users AD directory with the search filter that they have to be a part of the group.
//			if they are, then good. if not, then they're not authorized.
//			works fine on my machine for all users I have tested (me, John, the test users) however, it doesn't work on John's computer--it hangs on a blank screen. I have no idea why...
//			fixed! it was he didn't have the ldap dll enabled on his php.ini. I did it so long ago on this one that I forgot.
//	7/26/2013	deleted all the commented out db users validation code. to view, open a page BEFORE THIS DATE
//			John wants to be able to change where the files/invoices go with just one change, so I was going to register them as session variables, but that won't work.
//			the reason is that when you look for a document, it has to be uploaded to the server, so you can't do direct paths.
//			changed it all to say ecsdocket
//			had to put in an exit() after the refresh because it executed the trailing code even though the refresh time was zero seconds....
//	7/30/2013	got the session variables for path names working. the issue was sometimes it needed the server location and sometimes the absolute location. i fixed this by having two variables
//	8/26/2013	added some comments and cleaned up the code a little bit
//	8/27/2013	changed from short php tags. this will cause it to not break if we ever migrate to a server that doesn't allow them.
//			note: could not get the inline <?= to work with <?php, so i manually entered the address to get around it. however, I won't be able to do that for all pages... not good.
//	9/4/2013	deleted guest functionality. It wouldn't be good for just anyone to view this information
// accesscontrol.php
include("common.php");
include("db.php");  // ***** 20130606
//include("../../include/mydocket/db.php"); // DATABASE ACCESS FILE   ***** 20130606
// Call session_start to either begin a new session, or load the variables 
// belonging to the user's current session.
session_start();
// At this point, the user's login details should be available whether they were
// just submitted from a login form or stored in the user's session. The only
// case in which the user's ID and password would not be available at this point
// in the script is if they had not yet been entered during this visit to the
// site. Thus, the script checks to see if the $uid variable (which will contain
// the user's ID) exists. If it doesn't, the user is presented with a login form
// and the script exits.
if ($LOGOUT=="1") {		//throws error. $LOGOUT undefined
  unset($uid);
  unset($pwd);
  session_destroy();}	//delete session variables
if(!isset($uid)) {
html_headeracc(); ?>
<br><br>  
<center>ECSDOCKET LOGIN</center><br>
<table align="center" width="300" border="0">
<form method="post" action="index.php">
  <input type="hidden" name="module" value="Home">
  <tr>
    <td align="right">
      <small>User ID:&nbsp;&nbsp;</small>
    </td>
    <td align="left" valign="top">
      <input type="text" name="uid" size="20">
    </td>
  </tr>
  <tr>
    <td align="right">
      <small>Password:&nbsp;&nbsp;</small>
    </td>
    <td align="left" valign="top">
      <input type="password" name="pwd" SIZE="20">
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="submit" value="Log in" name="Login">
    </td>
  </tr>
</table>
</td><td>
<table align="center" width="400" border="0">
<tr><td>
<p>ECSDocket is an Open Source intellectual property docket and management program.</p>
</td></tr>
</table>
</td></tr><tr><td colspan="2"><center><hr WIDTH="90%"></center>
<?php html_footer();
  exit;
}
// When the login form above is submitted, the page will be reloaded, this time
// with the $uid and $pwd variables set to the user's ID and password. The next
// step according to the flowchart above is to register these as session
// variables, ensuring that they are available to all other protected pages that
// the user views during this visit. Note that, at this point, the script still
// doesn't know whether or not the user ID and password that were entered are
// valid.
if($Login != "") {
//$_SESSION["uid"];		//new session variable syntax
session_register("uid");
session_register("pwd");
//connect to the server
$ad = ldap_connect("company.com")
      or die("Could not connect to AD");
//bind to the server
$uname = $uid . "@korry.com";
if (!ldap_bind($ad, "$uname", "$pwd"))	//ldap_bind returns true if bound. therefore, if it doesn't bind, we need to unbind $ad and display incorrect pw screen.
{
  session_unregister("uid");
  session_unregister("pwd");
  session_register("username");
  session_unregister("fullname");
  session_unregister("userorg");
  session_unregister("member");
  session_unregister("sysadmin");
  session_unregister("user_level");
  session_unregister("customer_ID");
  session_unregister("today");
  ldap_unbind($ad);
  html_headeracc();
  ?>
  <br><center>ACCESS DENIED<br>
  <table align="center" width="600">Your user ID or password is incorrect.<br>
  <br>To try logging in again, click
  <a href="index.php">here</a>.</table><br>
  </center>
  <?php html_footer();
  exit;
}

////////////////////////////////////////change file locations here///////////////////////////////////////////////
$absolute_documents = "\\\\data\\_data\\AppData\\MyDocket\\";		//absolute path
$absolute_invoices = "\\\\data\\_data\\AppData\\MyDocket\\Invoices\\";	//absolute path
/////////////////////////////////////////end file locations////////////////////////////////////////////////

$dn = "CN=$uid,CN=Users,DC=company,DC=com";
$filter = "(memberOf=CN=Docket_Admin,OU=Groups,DC=company,DC=com)";
$justthese = array("memberOf,distinguishedName");
//search in admin group
$sr = ldap_search($ad, $dn, $filter, $justthese);
$info = ldap_get_entries($ad, $sr);
if ($info["count"] == 0)
{//search in user group
  $filter = "(memberOf=CN=Docket_User,OU=Groups,DC=company,DC=com)";
  $sr = ldap_search($ad, $dn, $filter, $justthese);
  $info = ldap_get_entries($ad, $sr);
  if ($info["count"] == 0)
  {//search in viewer group
    $filter = "(memberOf=CN=Docket_Viewer,OU=Groups,DC=company,DC=com)";
    $sr = ldap_search($ad, $dn, $filter, $justthese);
    $info = ldap_get_entries($ad, $sr);
    if ($info["count"] == 0)
    {	//valid user, but not in any of the member groups.
      ?>
      <br><center>ACCESS DENIED<br>
      <table align="center" width="600">You are not a registered user on this site.<br>
      If you believe that you are seeing this in error, please contact your HelpDesk.<br>To try logging in again, click
      <a href="index.php">here</a>.</table><br>
      </center>
      <?php
      ldap_unbind($ad);	//have to make sure to do this when we leave. 
      html_footer();
      exit;
    }
    //if it gets here, the user is part of ECSDocket_Viewer
    //register viewer variables
    $username = $uid;	
    $fullname = $uid;	
    $email = $uid . "@company.com";	//tested this--works 
    $userorg = "MyDocket";	//all users have this as the org
    $customer_ID = "1";	//all users have this as the ID
    $member = "MyDocket";	//^^
    $sysadmin = "N";	
    $user_level = "Viewer";
    $today = date("Y-m-d");   // e.g. 2051-10-22
    
    session_register("absolute_documents");
    session_register("server_documents");
    session_register("absolute_invoices");
    session_register("server_invoices");
    session_register("username");
    session_register("fullname");
    session_register("userorg");	//if we ever do a company specific docketing system powered by the same engine, THIS is what will change
    session_register("member");
    session_register("sysadmin");
    session_register("user_level");
    session_register("customer_ID");
    session_register("today");
    ldap_unbind($ad);
    html_footer();
    ?><META HTTP-EQUIV="refresh" CONTENT="0;URL=index.php"><?php //note: exit won't work here, since exit terminates all code, and we need to return control to index.php
    exit();	//still need this, otherwise in the 0 seconds that takes to redirect, the rest of the code will execute. not sure why, but it's an easy fix.
  }
  //if it gets here, the user is part of ECSDocket_User
  //register user variables
  $username = $uid;	
  $fullname = $uid;
  $email = $uid . "@company.com";	//tested this--works 
  $userorg = "MyDocket";	//all users have this as the org
  $customer_ID = "1";	//all users have this as the ID
  $member = "MyDocket";	//^^
  $sysadmin = "N";	
  $user_level = "User";
  $today = date("Y-m-d");   // e.g. 2051-10-22
  
  session_register("absolute_documents");
  session_register("server_documents");
  session_register("absolute_invoices");
  session_register("server_invoices");
  session_register("username");
  session_register("fullname");
  session_register("userorg");
  session_register("member");
  session_register("sysadmin");
  session_register("user_level");
  session_register("customer_ID");
  session_register("today");
  ldap_unbind($ad);
  html_footer();
  ?><META HTTP-EQUIV="refresh" CONTENT="0;URL=index.php"><?php //note: exit won't work here, since exit terminates all code, and we need to return control to index.php
    exit();	//still need this, otherwise in the 0 seconds that takes to redirect, the rest of the code will execute. not sure why, but it's an easy fix.
}
//if it gets here, the user is part of ECSDocket_Admin
echo $info[0]["displayname"];	//for some reason only the dn returns data. I'd like to use this for the name... but alas, it will not happen.
//register admin variables
$username = $uid;	
$fullname = $uid;
$email = $uid . "@company.com";	//tested this--works
$userorg = "MyDocket";	//all users have this as the org
$customer_ID = "1";	//all users have this as the ID
$member = "MyDocket";	//^^
$sysadmin = "N";	//I think this can be no. That means no one will ever be sysadmin, however I think i'll still leave it in if there is ever the need for a superuser
$user_level = "Admin";
$today = date("Y-m-d");   // e.g. 2051-10-22

session_register("absolute_documents");
session_register("server_documents");
session_register("absolute_invoices");
session_register("server_invoices");
session_register("username");
session_register("fullname");
session_register("userorg");
session_register("member");
session_register("sysadmin");
session_register("user_level");
session_register("customer_ID");
session_register("today");
ldap_unbind($ad);
}?>
