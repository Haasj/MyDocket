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
//	6/13/2013	added disclosure functionality
//			bug: when you navigate to this page from the "add" page the id always increments by one, so it always throws a db error. may want to delete this functionality, as it works every other time
//	6/14/2013	fixed. i wrote if instead of elseif for disclosures, so it was always trying to delte a disclosure too
//			fixed an error where it would throw a db error when deleting tms. this was cause it was trying to delete an inventor.
//			I believe the db error is gone. was unable to replicate it from the add screen.
//	6/18/2013	adding delete file functionality
//			don't think it's accepting alt_name for some reason.
//			worked around it by doing another query imbedded.
//			some size is off somewhere, it won't delete longer files
//			changed the size in the table itself. max size is 256, as in the Windows invironment
//			forgot to add disc_actions functionality. did that
//	6/24/2013	added functionality to delete from the docket master as well. now it does that correctly.
//			I realized that it wasn't deleting the file from the server location when a disclosure was deleted, so I added that. had to change id to disc_id in the disc_documetns query
//			adapted to handle pat_documents, tm_documents, copyrights, contracts
//			re-arranged everything for continuity
//	6/25/2013	In all the checks for unlink added parameter that checks to see if the alt_name is blank. if it is, it won't throw the error. This fixes the error that comes up when you delete a record
//			with no file
//			Updated to work with General IPM, and also actions for any contract
//	6/27/2013	updated to delete associated records (pats/discs) when the record is deleted.
//	6/28/2013	updated to work with accounts.
//	7/1/2013	had misplaced curly braces, so it would only delete the first attached doc for all.
//			added the delete_pdf functionality. if you delete a pdf, it will delete all associated accounts with it.
//			made all the accounts deleted when you delete a record
//	7/8/2013	after deleting from table disc_documents, it now re-directs you to the disclosure.
//			did above for all document tables.
//			added re-direct after deletion of all actions.
//	7/9/2013	added table checks for pat_disclosures and disc_patents. now it does the re-direct when you un-link a patent/disclosure
//	7/17/2013	added $user_level to header call
//	7/18/2013	added authentication to $user_level while leaving in $sysadmin because I'm pretty sure that's just the root admin.
//			even though you can only get here from a protected page, it is still a good idea to protect it. otherwise, if I knew what I was doing, I could input the correct URL to delete something
//			added delete family number when it's the last one in the family. bug: it always deletes the family number. will troubleshoot tomorrow.
//	7/19/2013	I may just want to leave it this way... The only thing the families db does is generate an incrementing number, and once it's created, there's no reason to ever access the db again.
//			fixed anyway. I was trying to query the rnum of rows without running the query first, so it was always zero
//			added functionality for tm and pats. works for all three.
//	7/23/2013	modified pdf_combos delete so it works with alt_name now
//	7/30/2013	replaced paths with session variables
//			added re-directs based on module when you delete a transaction
//	8/9/2013	the re-directs were passing the wrong module. fixed.
// delete_confirm.php -- User Access Level: User
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
if ($sysadmin == "Y" or $user_level != "Viewer") {?>	<!--just another precaution that viewers can't delete anything-->
<br><br>
<center>DELETE CONFIRM</center><br>
<?
if ($submityes=="" and $submitno==""){
// Display the delete record form
?>
<form method=post action="<?=$PHP_SELF;?>">
<input type="hidden" name="module" value="<?=$module;?>">
<input type="hidden" name="TABLE" value="<?=$TABLE;?>">
<input type="hidden" name="ID" value="<?=$ID;?>">
<input type="hidden" name="NAME" value="<?=$NAME;?>">
<input type="hidden" name="FAM_ID" value="<?=$FAM_ID;?>">
<table align="center" width="500">
    <tr><td align="center">
	    Are you sure you want to delete <?=$NAME;?>?<br><br>
    </td></tr>
	<tr><td align="center">
        <input type=submit name="submityes" value=" YES ">
        <input type=submit name="submitno" value=" NO ">
    </td></tr>
</table><br>
<?
}
elseif ($submityes!="") {

  if ($TABLE=="pat_filings"){
    $sql = "DELETE FROM pat_filings WHERE pat_ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
	// Delete Associated Records
	$sql = "DELETE FROM pat_actions WHERE pat_ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
	$sql = "DELETE FROM pat_inventors WHERE pat_ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
      $sql = "DELETE FROM docket_master WHERE type='patent' AND ID='$ID'";		//docket master delte
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
       //to delete all accounts associated with the record
    $sql = "DELETE FROM accounts WHERE ecs_docket='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    //to delete associated records
    $sql="DELETE FROM pat_disclosures WHERE pat_ID='$ID'";
    if (!mysql_query($sql))
	error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    //to delete files from the server when a patent is deleted
    $sql = "SELECT * FROM pat_documents WHERE pat_id='$ID'";		
    //echo $sql;
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$alt_name=$row["alt_name"];
    $sql = "DELETE FROM pat_documents WHERE pat_id='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    else
	{
	   $file = $absolute_documents . $alt_name;
	   if (!unlink($file) and $alt_name!="")
	    {echo ("Error deleting file.");}
        }}
    //delete from pat_families if it's the last one
    $result = mysql_query("SELECT * FROM pat_filings WHERE patfam_ID='$FAM_ID'");
    if (mysql_num_rows($result) == 0)	//gonna be zero because we already deleted the record in question
	{
	    $sql = "DELETE FROM pat_families WHERE ID='$FAM_ID'";
	    if (!mysql_query($sql))
	    error("A database error occurred in processing your ".
	    "submission.\\nIf this error persists, please ".
	    "contact HelpDesk.");
        }
    }
      
  elseif ($TABLE=="disc_filings"){				//new for disclosures
    $sql = "DELETE FROM disc_filings WHERE disc_ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
	// Delete Associated Records
	$sql = "DELETE FROM disc_actions WHERE disc_ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
	$sql = "DELETE FROM disc_inventors WHERE disc_ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
      $sql = "DELETE FROM docket_master WHERE type='disclosure' AND ID='$ID'";	//docekt master delete
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    //to delete associated records
    $sql="DELETE FROM disc_patents WHERE disc_ID='$ID'";
    if (!mysql_query($sql))
	error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
	 //to delete all accounts associated with the record
    $sql = "DELETE FROM accounts WHERE ecs_docket='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    //to delete files from the server when a disclosure is deleted
    $sql = "SELECT * FROM disc_documents WHERE disc_id='$ID'";		//note: this has to be disc_id and not id, since the id in disc_documents is the doc id and not the disc id
    //echo $sql;
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$alt_name=$row["alt_name"];
    $sql = "DELETE FROM disc_documents WHERE disc_id='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    else
	{
	   $file = $absolute_documents . $alt_name;
	   if (!unlink($file) and $alt_name!="")
	    {echo ("Error deleting file.");}
        }}
    //delete from disc_families if it's the last one
    $result = mysql_query("SELECT * FROM disc_filings WHERE discfam_id='$FAM_ID'");
    if (mysql_num_rows($result) == 0)	//gonna be zero because we already deleted the record in question
	{
	    $sql = "DELETE FROM disc_families WHERE ID='$FAM_ID'";
	    if (!mysql_query($sql))
	    error("A database error occurred in processing your ".
	    "submission.\\nIf this error persists, please ".
	    "contact your HelpDesk.");
        }
    }
    
    elseif ($TABLE=="genipm_filings"){
    $sql = "DELETE FROM genipm_filings WHERE ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
	// Delete Associated Records
	$sql = "DELETE FROM genipm_actions WHERE genipm_ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
      $sql = "DELETE FROM docket_master WHERE type='GENIPM' AND ID='$ID'";	//docekt master delete
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
       //to delete all accounts associated with the record
    $sql = "DELETE FROM accounts WHERE ecs_docket='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    //to delete files from the server when a disclosure is deleted
    $sql = "SELECT * FROM genipm_documents WHERE genipm_id='$ID'";		
    //echo $sql;
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$alt_name=$row["alt_name"];
    $sql = "DELETE FROM genipm_documents WHERE genipm_id='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    else
	{
	   $file = $absolute_documents . $alt_name;
	   if (!unlink($file) and $alt_name!="")
	    {echo ("Error deleting file.");}
        }}
    }
      
    elseif ($TABLE=="tm_filings"){
    $sql = "DELETE FROM tm_filings WHERE tm_ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your tmfiling".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
	// Delete Associated Records
	$sql = "DELETE FROM tm_actions WHERE tm_ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your tmaction".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
	$sql = "DELETE FROM docket_master WHERE type='trademark' AND ID='$ID'";	//docket master delte
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
       //to delete all accounts associated with the record
    $sql = "DELETE FROM accounts WHERE ecs_docket='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    //to delete files from the server when a trademark is deleted
    $sql = "SELECT * FROM tm_documents WHERE tm_id='$ID'";		
    //echo $sql;
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$alt_name=$row["alt_name"];
    $sql = "DELETE FROM tm_documents WHERE tm_id='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    else
	{
	   $file = $absolute_documents . $alt_name;
	   if (!unlink($file) and $alt_name!="")
	    {echo ("Error deleting file.");}
        }}
    //delete from tm_families if it's the last one
    $result = mysql_query("SELECT * FROM tm_filings WHERE tmfam_ID='$FAM_ID'");
    if (mysql_num_rows($result) == 0)	//gonna be zero because we already deleted the record in question
	{
	    $sql = "DELETE FROM tm_families WHERE ID='$FAM_ID'";
	    if (!mysql_query($sql))
	    error($sql . "A database error occurred in processing your ".
	    "submission.\\nIf this error persists, please ".
	    "contact your HelpDesk.");
        }
    }
	
  elseif ($TABLE=="contracts"){
    $sql = "DELETE FROM contracts WHERE ID='$ID'";	//only have to do this once because an NDA/license will have different id numbers
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    $sql = "DELETE FROM ctr_actions WHERE ctr_ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your tmaction".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    $sql = "DELETE FROM docket_master WHERE type='NDA' AND ID='$ID'";		//have to do this twice because there are two types sharing the same set of IDs
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
      $sql = "DELETE FROM docket_master WHERE type='license' AND ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
      //to delete all accounts associated with the record
    $sql = "DELETE FROM accounts WHERE ecs_docket='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
      //to delete files from the server when a contract is deleted
    $sql = "SELECT * FROM ctr_documents WHERE ctr_id='$ID'";		
    //echo $sql;
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$alt_name=$row["alt_name"];
    $sql = "DELETE FROM ctr_documents WHERE ctr_id='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    else
	{
	   $file = $absolute_documents . $alt_name;
	   if (!unlink($file) and $alt_name!="")
	    {echo ("Error deleting file.");}
        }}
  }
      
  elseif ($TABLE=="copyrights") {
    $sql = "DELETE FROM copyrights WHERE ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
      $sql = "DELETE FROM docket_master WHERE type='copyright' AND ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
       //to delete all accounts associated with the record
    $sql = "DELETE FROM accounts WHERE ecs_docket='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    //to delete files from the server when a copyrigth is deleted
    $sql = "SELECT * FROM cpy_documents WHERE cpy_id='$ID'";		
    //echo $sql;
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$alt_name=$row["alt_name"];}
    $sql = "DELETE FROM cpy_documents WHERE cpy_id='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    else
	{
	   $file = $absolute_documents . $alt_name;
	   if (!unlink($file) and $alt_name!="")
	    {echo ("Error deleting file.");}
        }}
      
  elseif ($TABLE=="disc_documents"){
    $sql = "SELECT * FROM disc_documents WHERE id='$ID'";
    //echo $sql;
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$alt_name=$row["alt_name"];
	$disc_ID=$row["disc_ID"];}
    $sql = "DELETE FROM disc_documents WHERE id='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    else
	{
	   $file = $absolute_documents . $alt_name;
	   if (!unlink($file) and $alt_name!="")
	    {echo ("Error deleting file.");}
        }
?><META HTTP-EQUIV="refresh" 
CONTENT="0;URL=disclosures_edit.php?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=<?=$disc_ID;?>&I=1&EDIT=N"><?
    }
    
  elseif ($TABLE=="pat_documents"){
    $sql = "SELECT * FROM pat_documents WHERE id='$ID'";
    //echo $sql;
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$alt_name=$row["alt_name"];
	$pat_ID=$row["pat_ID"];}
    $sql = "DELETE FROM pat_documents WHERE id='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    else
	{
	   $file = $absolute_documents . $alt_name;
	   if (!unlink($file) and $alt_name!="")
	    {echo ("Error deleting file.");}
        }
?><META HTTP-EQUIV="refresh" 
CONTENT="0;URL=patents_edit.php?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$pat_ID;?>&I=1&EDIT=N"><?
    }
    
  elseif ($TABLE=="tm_documents"){
    $sql = "SELECT * FROM tm_documents WHERE id='$ID'";
    //echo $sql;
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$alt_name=$row["alt_name"];
	$tm_ID=$row["tm_ID"];}
    $sql = "DELETE FROM tm_documents WHERE id='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    else
	{
	   $file = $absolute_documents . $alt_name;
	   if (!unlink($file) and $alt_name!="")
	    {echo ("Error deleting file.");}
        }
?><META HTTP-EQUIV="refresh" 
CONTENT="0;URL=trademarks_edit.php?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=<?=$tm_ID;?>&I=1&EDIT=N"><?
    }
    
    elseif ($TABLE=="cpy_documents"){
    $sql = "SELECT * FROM cpy_documents WHERE id='$ID'";
    //echo $sql;
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$alt_name=$row["alt_name"];
	$cpy_ID=$row["cpy_ID"];}
    $sql = "DELETE FROM cpy_documents WHERE id='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    else
	{
	   $file = $absolute_documents . $alt_name;
	   if (!unlink($file) and $alt_name!="")
	    {echo ("Error deleting file.");}
        }
?><META HTTP-EQUIV="refresh" 
CONTENT="0;URL=copyrights_edit.php?module=<?=$module;?>&ID=<?=$cpy_ID;?>&I=1&EDIT=N"><?
    }
    
    elseif ($TABLE=="ctr_documents"){
    $sql = "SELECT * FROM ctr_documents WHERE id='$ID'";
    //echo $sql;
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$alt_name=$row["alt_name"];
	$ctr_ID=$row["ctr_ID"];}
    $sql = "DELETE FROM ctr_documents WHERE id='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    else
	{
	   $file = $absolute_documents . $alt_name;
	   if (!unlink($file) and $alt_name!="")
	    {echo ("Error deleting file.");}
        }
    if($module=="NDAs")
    {
	?><META HTTP-EQUIV="refresh" 
CONTENT="0;URL=ndas_edit.php?module=<?=$module;?>&ID=<?=$ctr_ID;?>&I=1&EDIT=N"><?
    }
    elseif ($module=="Licenses")
    {
	?><META HTTP-EQUIV="refresh" 
CONTENT="0;URL=licenses_edit.php?module=<?=$module;?>&ID=<?=$ctr_ID;?>&I=1&EDIT=N"><?
    }
    //else it displays done screen
    }
    
    elseif ($TABLE=="genipm_documents"){
    $sql = "SELECT * FROM genipm_documents WHERE id='$ID'";
    //echo $sql;
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$alt_name=$row["alt_name"];
	$genIPM_ID=$row["genipm_ID"];}
    $sql = "DELETE FROM genipm_documents WHERE id='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    else
	{
	   $file = $absolute_documents . $alt_name;
	   if (!unlink($file) and $alt_name!="")
	    {echo ("Error deleting file.");}
        }
?><META HTTP-EQUIV="refresh" 
CONTENT="0;URL=genIPM_edit.php?module=<?=$module;?>&GENIPMEDIT=1&ACTIONS=Open&ID=<?=$genIPM_ID;?>&I=1&EDIT=N"><?
    }
    
    elseif ($TABLE=="disc_actions"){
    $sql = "SELECT * FROM disc_actions WHERE action_ID='$ID'";
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$disc_ID=$row["disc_ID"];}
    $sql = "DELETE FROM disc_actions WHERE action_ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    ?><META HTTP-EQUIV="refresh" 
CONTENT="0;URL=disclosures_edit.php?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=<?=$disc_ID;?>&I=1&EDIT=N"><?}
      
  elseif ($TABLE=="pat_actions"){
    $sql = "SELECT * FROM pat_actions WHERE action_ID='$ID'";
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$pat_ID=$row["pat_ID"];}
    $sql = "DELETE FROM pat_actions WHERE action_ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    ?><META HTTP-EQUIV="refresh" 
CONTENT="0;URL=patents_edit.php?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$pat_ID;?>&I=1&EDIT=N"><?}
	  
  elseif ($TABLE=="tm_actions"){
    $sql = "SELECT * FROM tm_actions WHERE action_ID='$ID'";
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$tm_ID=$row["tm_ID"];}
    $sql = "DELETE FROM tm_actions WHERE action_ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    ?><META HTTP-EQUIV="refresh" 
CONTENT="0;URL=trademarks_edit.php?module=<?=$module;?>&TMFAM=0&TMEDIT=1&TM_ID=<?=$tm_ID;?>&I=1&EDIT=N"><?}
      
  elseif ($TABLE=="genipm_actions"){
    $sql = "SELECT * FROM genipm_actions WHERE action_ID='$ID'";
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$genipm_ID=$row["genipm_ID"];}
    $sql = "DELETE FROM genipm_actions WHERE action_ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    ?><META HTTP-EQUIV="refresh" 
CONTENT="0;URL=genIPM_edit.php?module=<?=$module;?>&GENIPMFAM=0&GENIPMEDIT=1&ID=<?=$genipm_ID;?>&I=1&EDIT=N"><?}
      
  elseif ($TABLE=="ctr_actions"){
    $sql = "SELECT * FROM ctr_actions WHERE action_ID='$ID'";
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$ctr_ID=$row["ctr_ID"];}
    $sql = "DELETE FROM ctr_actions WHERE action_ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
    if($module=="NDAs")
    {
	?><META HTTP-EQUIV="refresh" 
CONTENT="0;URL=ndas_edit.php?module=<?=$module;?>&ID=<?=$ctr_ID;?>&I=1&EDIT=N"><?
    }
    elseif ($module=="Licenses")
    {
	?><META HTTP-EQUIV="refresh" 
CONTENT="0;URL=licenses_edit.php?module=<?=$module;?>&ID=<?=$ctr_ID;?>&I=1&EDIT=N"><?
    }
    //else it displays done screen
    }
      
    elseif ($TABLE=="accounts"){
	$sql = "DELETE FROM accounts WHERE acct_id='$ID'";
	if (!mysql_query($sql))
	    error("A database error occurred in processing your ".
	    "submission.\\nIf this error persists, please ".
	    "contact your HelpDesk.");
	    if($module=="Disclosures")
	    {
		?><META HTTP-EQUIV="refresh" CONTENT="0;URL=disclosures_list.php?module=Disclosures"><?
            }
	    elseif($module=="Patents")
	    {
		?><META HTTP-EQUIV="refresh" CONTENT="0;URL=patents_list.php?module=Patents"><?
            }
	    elseif($module=="Trademarks")
	    {
		?><META HTTP-EQUIV="refresh" CONTENT="0;URL=trademarks_list.php?module=Trademarks"><?
            }
	    elseif($module=="Copyrights")
	    {
		?><META HTTP-EQUIV="refresh" CONTENT="0;URL=copyrights_list.php?module=Copyrights"><?
            }
	    elseif($module=="Licenses")
	    {
		?><META HTTP-EQUIV="refresh" CONTENT="0;URL=licenses_list.php?module=Licenses"><?
            }
	    elseif($module=="NDAs")
	    {
		?><META HTTP-EQUIV="refresh" CONTENT="0;URL=ndas_list.php?module=NDAs"><?
            }
	    elseif($module=="General IPM")
	    {
		?><META HTTP-EQUIV="refresh" CONTENT="0;URL=genIPM_list.php?module=General IPM"><?
            }
	    elseif($module=="Accounting")
	    {
		?><META HTTP-EQUIV="refresh" CONTENT="0;URL=accounts_list.php?module=Accounting"><?
            }
	    elseif($module=="Reports")
	    {
		?><META HTTP-EQUIV="refresh" CONTENT="0;URL=reports_list.php?module=Reports"><?
            }	    
    }
	    
  elseif ($TABLE=="pdf_combos"){
    $sql = "SELECT * FROM pdf_combos WHERE id='$ID'";
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$alt_name=$row["alt_name"];}
    $sql = "DELETE FROM pdf_combos WHERE id='$ID'";
	if(!mysql_query($sql))
	    error("A database error occurred in processing your pdf ".
	    "submission.\\nIf this error persists, please ".
	    "contact your HelpDesk.");
	else
	{
	   $file = $absolute_invoices . $alt_name;
	   if (!unlink($file) and $alt_name!="")
	    {echo ("Error deleting file.");}
        }
    $sql = "SELECT * FROM accounts WHERE pdf_id='$ID'";
    $result=mysql_query($sql);
       while($row=mysql_fetch_array($result)){
    $acct_ID = $row["acct_id"];
    $sql = "DELETE FROM accounts WHERE acct_id='$acct_ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");}
  }
  
  elseif ($TABLE == "disc_patents")
  {
    $sql="SELECT * FROM disc_patents WHERE ID='$ID'";
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result)){
	$disc_ID = $row["disc_ID"];}
    $sql="DELETE FROM disc_patents WHERE ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
?><META HTTP-EQUIV="refresh" 
CONTENT="0;URL=disclosures_edit.php?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=<?=$disc_ID;?>&I=1&EDIT=N"><?
  }
  
  elseif ($TABLE == "pat_disclosures")
  {
    $sql="SELECT * FROM pat_disclosures WHERE ID='$ID'";
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result)){
	$pat_ID = $row["pat_ID"];}
    $sql="DELETE FROM pat_disclosures WHERE ID='$ID'";
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
?><META HTTP-EQUIV="refresh" 
CONTENT="0;URL=patents_edit.php?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$pat_ID;?>&I=1&EDIT=N"><?
  }
  
  else {
    $sql = "DELETE FROM $TABLE WHERE ID='$ID'";
    //echo ("no valid table\n");
    //echo $sql;
    if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");}
?>
<table align="center" width="500"><br>
<p><strong>Your record has been successfully deleted.</strong></p>
</table><br>
<? }
else {
?>
<table align="center" width="500"><br>
<p><strong>Your record has NOT been deleted.</strong></p>
</table><br>
<? }} html_footer();?>
