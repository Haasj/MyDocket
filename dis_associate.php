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
//	7/15/2013 	created using delete_confirm.php
//                 	when you click yes, it inserts a new entry into pat_families, then updates the passed record to have the new (unused) pat_family number. the only thing that I don't like is the lack of
//                 	db cleanup, or deletion, of pat_family entries. it doesn't happen in delete_confirm, because you may not be deleting the last patent. adding to look at eventually.
//                 	added re-directs after it is done to return you to the patfam screen. added 1 second delays so the user can see a confirmation screen.
//	7/17/2013	added $user_level to header call
//	7/18/2013	added authentication to $user_level while leaving in $sysadmin because I'm pretty sure that's just the root admin.
//	8/27/2013	cleaned up the code a little
// delete_confirm.php--	User Access Level: User
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
if ($sysadmin == "Y" or $user_level != "Viewer") {?>
<br><br>
<center>DIS-ASSOCIATE CONFIRM</center><br>
<?
if ($submityes=="" and $submitno==""){
// Display the delete record form
?>
<form method=post action="<?=$PHP_SELF;?>">
<input type="hidden" name="module" value="<?=$module;?>">
<input type="hidden" name="PATFAM" value="<?=$PATFAM;?>">
<input type="hidden" name="PAT_ID" value="<?=$PAT_ID;?>">
<input type="hidden" name="DISCFAM" value="<?=$DISCFAM;?>">
<input type="hidden" name="DISC_ID" value="<?=$DISC_ID;?>">
<input type="hidden" name="NAME" value="<?=$NAME;?>">
<table align="center" width="500">
    <tr><td align="center">
	    Are you sure you want to delete this association?<br><br>
    </td></tr>
	<tr><td align="center">
        <input type=submit name="submityes" value=" YES ">
        <input type=submit name="submitno" value=" NO ">
    </td></tr>
</table><br>
<?
}
elseif ($submityes!="") {
//code to break the association
    if ($PAT_ID != "") { //sent from patent_list
    $sql = "INSERT INTO pat_families SET
	customer_ID = '$customer_ID',
	org = '$userorg'";  
	    if (!mysql_query($sql))
	  error("A database error occurred in processing your ".
	  "submission.\\nIf this error persists, please ".
	  "contact your HelpDesk.");
      $PATFAM_ID=mysql_insert_id(); //this gets the next available patfam number, and (below) puts it in that record.
      
    $sql = "UPDATE pat_filings SET patfam_ID = '$PATFAM_ID' WHERE pat_ID = '$PAT_ID'";
     if (!mysql_query($sql))
	  error("A database error occurred in processing your ".
	  "submission.\\nIf this error persists, please ".
	  "contact your HelpDesk.");
    }
    
    else { //sent from disclosure_list
    $sql = "INSERT INTO disc_families SET
	customer_ID = '$customer_ID',
	org = '$userorg'";  
	    if (!mysql_query($sql))
	  error("A database error occurred in processing your ".
	  "submission.\\nIf this error persists, please ".
	  "contact your HelpDesk.");
      $DISCFAM_ID=mysql_insert_id(); //this gets the next available patfam number, and (below) puts it in that record.
      
    $sql = "UPDATE disc_filings SET discfam_id = '$DISCFAM_ID' WHERE disc_ID = '$DISC_ID'";
     if (!mysql_query($sql))
	  error("A database error occurred in processing your ".
	  "submission.\\nIf this error persists, please ".
	  "contact your HelpDesk.");
    }
?>
<table align="center" width="500"><br>
<p><strong>The association has been successfully broken.</strong></p>
</table><br>
<? if ($PAT_ID != ""){?>
<META HTTP-EQUIV="refresh" 
CONTENT="1;URL=patents_list.php?module=<?=$module;?>&SORT=PATFAM&PATFAM_ID=<?=$PATFAM_ID;?>"><?}
else{?>
<META HTTP-EQUIV="refresh" 
CONTENT="1;URL=disclosures_list.php?module=<?=$module;?>&SORT=DISCFAM&DISCFAM_ID=<?=$DISCFAM_ID;?>">
<? }}
else {
?>
<table align="center" width="500"><br>
<p><strong>Your record has NOT been deleted.</strong></p>
</table><br>
<? if ($PAT_ID != ""){?>
<META HTTP-EQUIV="refresh" 
CONTENT="1;URL=patents_list.php?module=<?=$module;?>&SORT=PATFAM&PATFAM_ID=<?=$PATFAM;?>"><?}
else{?>
<META HTTP-EQUIV="refresh" 
CONTENT="1;URL=disclosures_list.php?module=<?=$module;?>&SORT=DISCFAM&DISCFAM_ID=<?=$DISCFAM;?>">
<? }}} html_footer();?>
