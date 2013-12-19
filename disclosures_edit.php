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
//	6/11/2013	created by copying patents_edit.php. not working though... just giving me the footer
//                      it's doing this because for some reason it's not setting PATFAM and PATEDIT correctly
//                      fixed. you have to change this in common.php where you initially link to the page.
//                      re-did all the formatting just right for adding a disclosure record. Note: browse button is just text now; don't know how to make that work yet
//                      also, all of the names I just created will throw errors. they are all undefined, but should work for now
//                      should display correctly for viewing as well-I copied the formatting from new. However, still is pointing to patents_edit. this issue is in disclosures_list
//                      fixed. changed pointer
//                      Bug: doesn't write inventors to the textbox
//                      To do: figure out the file_paths stuff; declare varialbes somewhere....

//	6/12/2013    /| Just created the disc_filings table in phpMyAdmin. Created a test entry; going to attempt to set this page up to query that table now.
//   (not implementd)-| updates: all pat_filings->disc_filings, pat_id->disc_id, patfam(_id)->discfam(_id), priority_date->invention_date, pat_inventors-disc_inventors, patedit->discedit, pat_actions->disc_actions
//		     \| created disc_inventors table
//		     || note: autoactions.php will not work with this, since it has to do with the patent dbs. also have to create discactions_edit.php
//		     || changed all the add actions to point to discactions_edit.php
//	6/13/2013    || everything is correct in common.php and disclosures_list.php to pass the correct values.
//		     || still blank page
//		     \| changed everything from patents->disclosures. the issue should be somewhere else now

//			reverted back to 6/11 code. I think it'll be faster than trying to figure out where I went wrong in the modification. going to test every change I make from now on
//			still have old code to copy the variable declarations, etc. so this should be fast...
//			pat_id->disc_id. in add menu nothing shows up, that's fine. also, no data shows up, but I still get the screen so that's fine. It grabs the data from the id#, so it thinks it doesn't exist
//			patfam->discfam, priority_date->invention_date, pataction_edit->discaction_edit, patent_status->disclosure_status
//			all the variables and links are replaced with the correct ones with the page loading correctly. the quereys now need to be changed. 
//			pat_inventors->disc_inventors was successful, pat_actions->disc_actions successful, pat_families->disc_families successful. the only thing left to implement is disc_families.
//			pat_filings->disc_filings successful. the issue must be when I edit the query. beginning that now
//			maybe found the error: in declarations, there were two missing semicolons. updated old files, but not testing them. I'd feel too bad
//			querys succesfully updated.
//			Note: edited delete_confirm, which is working, but it gets passed the wrong id. everytime it's called, the id# increments for some reason. only happens on disclosures
//			db wasn't updating because filing_date was required. now i'm getting a db query error. will check that out now
//			oops! foolish me had it throw an error and critically stop when it was correct for testing purposes. updates the db now!
//			this should be almost done-haven't tested it with selecting from the list, since I haven't done the list yet.
//	6/14/2013	everything is done except file path stuff
//			the file path won't get past the if post check
//			changed the if so it would go past if there was no error; the variables aren't getting populated. either an error with the getting, or setting
//			no longer getting a db error; it inputs blank fields for everything, but still gets inputed....
//			note: not going to bother with the different file types for now; going to do that later once I can get them to actually upload
//			changed the formatting of the disclosure view to gray out when it's just viewing
//	6/17/2013	can't figure out why the files aren't uploading. I think it may be a php.ini issue, as I can't find anything wrong in the code.
//			deleted the ok on the bottom of views
//			put the upload form on Test_Page and it works. So now I think the issue is with the variables changing in the address bar
//			I'm going to attempt to make it like the discactions_edit in that it will be a seperatate form that is created and dies when it is complete. That way I get around the variables issue
//	6/18/2013	fixed an error where the id was being sent incorrectly (sent as DOC_ID)
//			added delete functionality from the edit screen.
//			added link to the document from both screens. NOTE: you cannot view some types of files. .php is one type of these. I'd assume that you can only view the standard types. however, .wav seemed to work
//			added back in the inventors stuff like it is in patents_edit.php. not sure why I commented this out
//			it doesn't work, but I think the issue is in inventors_edit.php since I haven't even looked at it.
//	6/19/2013	everything works fine in inventors_list.php, but won't display here. I think the issue is in here
//			the issue was that the displaying lines were commented out, they just didn't look commented out. it was because of an earlier comment that wasn't closed, so it was commenting only the html
//			Looks just like patents now.
//			added functionality with the docket_master db. the way this works is that it updates docket_master when it makes a new one, and that master id number goes into docket_alt.
//			the user thinks this is the main docket number, and it is, but for simplicity's sake I left it as docket_alt. less code.
//			everything works, except it throws a db error whenever you try to add a related disclosre.
//			everything works now. there was a comma where the shouldn'tve been in the query.
//	6/20/2013	starting to add functionality to attach a patent to a disclosre and vice versa.
//			added appropriate links and stuff under the edit portion. going to begin to code the link page.
//	6/21/2013	deleted firm docket, firm, and firm contact. added ecs to docket, and put a warning if you don't fill in something. defaulted country to us when making a new one.
//	6/26/2013	made ATTACHEDFILES default to All
//			updated to dynamically display document names instead of manually. also changed it so it doesn't go to edit mode when you select different lists.
//	6/27/2013	updated to use meta refresh to display the updated record instead of the done screen.
//			added the cost hyperlink
//	7/3/2013	ordered inventors by ID, so show up in order attached
//			changed so it only runs autoactions.php if it is a new one
//	7/9/2013	put a limit on the file type length. now it'll look better
//	7/16/2013	now you don't have to be an admin to view cost.
//			changed it so actions display the completed date when they are done
//	7/17/2013	added $user_level to header call
//	7/18/2013	replaced all authentication to $user_level while leaving in $sysadmin because I'm pretty sure that's just the root admin.
//			discfam_id wasn't getting populated because I was searching for it in upper case.
//	7/23/2013	added add transaction button from edit screen. however! you currently cannot have a disclosure transaction because it looks up the firm docket number, and disclosures don't have them
//	7/24/2013	fixed that issue with accounts_edit. also, you can now add transaction from the view screen.
//	7/30/2013	replaced the server paths with the session variables
//			disabled editing actions/inventors/patents/families from view screen for viewer levels.
//	8/9/2013	changed so it orders the attached files by doc_date
//			allowed apostrophes in data fields. details: trademarks_edit
//	8/13/2013	changed error protocol to exit/display error
//	8/15/2013	on the korry server it didn't like the docket_alt being set to '' since it's an int, so I took that out. Also made sure the date was valid, otherwise inputted 0000-00-00
//	8/19/2013	fixed date logic; incorrectly was using checkdate. this won't work, because of formatting. now, it will work, but throw the mysql_error if the date is invalid. works with ""
//	8/28/2013	cleaned up the code a little bit
//	8/29/2013	replaced server_docs with abs_docs
//disclosures_edit.php -- User Access Level: User/Viewer
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
// Set $var string
$var="DOCKET=$DOCKET_ALT&TITLE=$TITLE";
// Set Defaults
if ($NEXT=="1") $START=$START+50;
  else $START="0";
if ($ATTACHEDFILES=="")
  {$ATTACHEDFILES="All";}
  
//<!-- SELECT RELATED DISCLOSURE -->
if ($DISCFAM=="1") {
  if ($submit_1=="" or $DISC_ID==""){?>
  <table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
    <td width="100%" align="center" bgcolor="#FFFFFF">
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&DISCFAM=1&DISCEDIT=0&NEXT=0&START=<?=$START;?>&<?=$var;?>">First 50</a>&nbsp;|&nbsp;
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&DISCFAM=1&DISCEDIT=0&NEXT=1&START=<?=$START;?>&<?=$var;?>">Next 50</a>
    </td></tr>
  </table><br><br> 
  
  <center>ADD DISCLOSURE RECORD -- SELECT RELATED DISCLOSURE</center><br>
  <form method=get action="<?=$PHP_SELF;?>">
    <input type="hidden" name="module" value="<?=$module;?>">
    <input type="hidden" name="DISCFAM" value="1">
    <input type="hidden" name="DISCEDIT" value="0">
    <input type="hidden" name="I" value="0">
    <input type="hidden" name="EDIT" value="Y">
  <table align="center" width="100%" border="0" cellpadding="5" cellspacing="2">
    <tr>
      <td align="right" colspan="3">
	    <small>DOCKET&nbsp;<input type="text" name="DOCKET_ALT" maxlength="10" size="10" value="<?=$DOCKET_ALT;?>"></small>&nbsp;&nbsp;
	<small>TITLE&nbsp;<input type="text" name="TITLE" maxlength="30" size="10" value="<?=$TITLE;?>"></small>&nbsp;&nbsp;
	<input type="submit" name="submit_search" value=" SEARCH ">&nbsp;&nbsp;
	<input type="submit" name="submit_50" value=" NEXT 50 ">&nbsp;&nbsp;
	<small><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=0&I=2&EDIT=Y&NEW=Y&country=US">ADD NEW DISCLOSURE</a></small></td>
    </tr>
    <tr bgcolor=EEEEEE>
      <td width="20"><small>SELECT</small></td>
      <td width="50"><small>ECS DOCKET</small></td>
      <td width="480"><small>TITLE</small></td>
    </tr>
    <?
      $sql="SELECT disc_id, docket_alt, title FROM disc_filings WHERE
	customer_ID='$customer_ID' and
	    docket_alt LIKE '%$DOCKET_ALT%' and
	title LIKE '%$TITLE%' 	
	    ORDER BY docket_alt LIMIT $START, 50";
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result)){
	$DISC_ID=$row["disc_id"];
	$docket_alt=$row["docket_alt"];
	$title=$row["title"];
      ?>
      </td></tr>
      <tr bgcolor=EEEEEE>
	<td width="20"><input type="radio" name="DISC_ID" value="<?=$DISC_ID;?>"></td>
	<td width="100"><small><?=$docket_alt;?></small></td>
	<td width="430"><small><a href="<?=$PHP_SELF;?>?module=<?=$module;?>&DISCEDIT=1&DISC_ID=<?=$DISC_ID;?>&I=1&EDIT=N"><?=$title;?></small></td>
      </tr>
    <?}?>
    <tr>
      <td align="center" colspan="3">
	<hr noshade size="1" width="100%">
	<input type="submit" name="submit_1" value="  OK  ">
      </td>
    </tr>
  </table>
  <?}
  else {
  // The script copies the information from the original case to a new record
  $sql="SELECT * FROM disc_filings WHERE disc_id='$DISC_ID'";
  $result=mysql_query($sql);
  $row=mysql_fetch_array($result);
    $DISCFAM_ID=$row["discfam_id"];
    $docket=$row["docket"];
    //$docket_alt=$row["docket_alt"];
    $title=$row["title"];
    $firm=$row["firm"];
    $firm_contact=$row["firm_contact"];
    $client=$row["client"];
    $client_contact=$row["client_contact"];
    $filing_type=$row["filing_type"];
    $invention_date=$row["invention_date"];
    $abstract=$row["abstract"];
    $products=$row["products"];
    $file_paths=$row["file_paths"];
    $odocs=$odocs["odocs"];
    
  // Create the record that will be edited
  $sql="INSERT INTO disc_filings SET
	  customer_ID='$customer_ID',
      org='$userorg',
      discfam_id='$DISCFAM_ID',
      docket='$docket',
	  title='$title',
	  firm='$firm',
	  firm_contact='$firm_contact',
      client='$client',
      client_contact='$client_contact',
      invention_date='$invention_date',	
      abstract='$abstract',
      products='$products',
      notes='$notes',
      inventors='$inventors',
      file_paths='$file_paths',
	  creator='$fullname',
	  create_date='$today',
	  editor='$fullname',
	  edit_date='$today'";
	  
  if (!mysql_query($sql)){
  echo mysql_error();
   exit;}

    
  // Get the ID of the new pat_filings record
  $DISC_ID_NEW=mysql_insert_id();
  
  // Copy the inventors to the new application
  $sql_1="SELECT * FROM disc_inventors WHERE disc_id='$DISC_ID'";
  $result_1=mysql_query($sql_1);
  while ($row_1=mysql_fetch_array($result_1))
  {
    $inventor_ID=$row_1["inventor_ID"];
    $sql_2="INSERT INTO disc_inventors SET
      customer_ID='$customer_ID',
      disc_id='$DISC_ID_NEW',
      inventor_ID='$inventor_ID'";
    if (!mysql_query($sql_2))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
  }
  
  $sql_3="INSERT INTO docket_master SET
	  type='disclosure',
	  ID='$DISC_ID_NEW'";
  if (!mysql_query($sql_3)){
  echo mysql_error();
   exit;}

  $docket_alt_new=mysql_insert_id();
  
  $sql_4="UPDATE disc_filings SET
	  docket_alt='$docket_alt_new'
	  WHERE disc_id='$DISC_ID_NEW'";
    if (!mysql_query($sql_4))
    error("A database error occurred in updating your ".
    "submission.\\nIf this error persists, please ".
    "contact your HelpDesk.");
    
  // Move on to adding the patent filing record
  $DISCEDIT="1";
  $I="1";
  $DISC_ID=$DISC_ID_NEW;
  $NEW="Y";
  $docket_alt=$docket_alt_new;
  }
}?>

<!-- ADD OR EDIT DISCLOSURE RECORD -->
<?
if ($DISCEDIT=="1") {

// If it's a brand new case ($I=="2"), we need to set up a disclosure family number, 
// disclosure number and identify the new disclosure as an original
if ($I=="2") {
  $sql = "INSERT INTO disc_families SET
    customer_ID = '$customer_ID',
    org = '$userorg'";  
	if (!mysql_query($sql))
      error("A database error occurred in processing your ".
      "submission.\\nIf this error persists, please ".
      "contact your HelpDesk.");
  $DISCFAM_ID=mysql_insert_id();
  $sql = "INSERT INTO disc_filings SET
    customer_ID = '$customer_ID',
	discfam_id = '$DISCFAM_ID',
    org = '$userorg',
	creator='$fullname',
	create_date='$today',
	editor='$fullname',
	edit_date='$today'";
	if (!mysql_query($sql)){
  echo mysql_error();
   exit;}

  $DISC_ID=mysql_insert_id();
  
  $sql = "INSERT INTO docket_master SET
	  type='disclosure',
	  ID='$DISC_ID'";
	  if (!mysql_query($sql)){
  echo mysql_error();
   exit;}

  $docket_alt=mysql_insert_id();
  }

// To retrieve data, $DISC_ID is set to the existing record number
// SQL Query for an existing NDA record and retrieving data
if ($I=="1"){
$sql="SELECT * FROM disc_filings WHERE disc_id='$DISC_ID'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
    $DISCFAM_ID = $row["discfam_id"];
    $docket = $row["docket"];
    $docket_alt = $row["docket_alt"];
    $org = $row["org"];
    $creator = $row["creator"];
    $create_date = $row["create_date"];
    $editor = $row["editor"];
    $edit_date = $row["edit_date"];
    $firm = $row["firm"];
    $firm_contact = $row["firm_contact"];
    $client = $row["client"];
    $client_contact = $row["client_contact"];
    $title = $row["title"];
    $inventors = $row["inventors"];
	$country = $row["country"];
	$status = $row["status"];
    $invention_date = $row["invention_date"];
    $invention_date_1 = $row["invention_date"];
    $ids = $row["ids"];
    $odocs = $row["odocs"];	
    $ffil = $row["ffil"];	
	$abstract = $row["abstract"];
	$products = $row["products"];
    $notes=$row["notes"];
    $file_paths = $row["file_paths"];
}

if (($sysadmin=="Y" or $user_level != "Viewer") and $read_only=="N" and $EDIT=="Y")
{?>
  <table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
    <td width="100%" align="center" bgcolor="#FFFFFF">
      <a href="disclosures_list.php?module=<?=$module;?>&SORT=DISCFAM&DISCFAM_ID=<?=$DISCFAM_ID;?>">View Family</a>
      <? // The ability to delete a record only if there is an existing record ($DISC_ID!="0")
      if ($DISC_ID!="0"){?>&nbsp;|&nbsp;
      <a href="accounts_edit.php?module=<?=$module;?>&ecs_docket=<?=$docket_alt;?>&firm_docket=<?=$docket;?>&I=0">Add Transaction</a>&nbsp;|&nbsp;
      <a href="total_cost.php?module=<?=$module;?>&ID=<?=$docket_alt;?>">Cost</a>&nbsp;|&nbsp;      
      <a href="delete_confirm.php?module=<?=$module;?>&TABLE=disc_filings&ID=<?=$DISC_ID;?>&NAME=<?=$docket_alt;?>&FAM_ID=<?=$DISCFAM_ID;?>">Delete</a>
      <?}?></td></tr>
  </table><br><br>
  <? if ($NEW=="Y") echo("<center>ADD DISCLOSURE RECORD</center>");
    else echo("<center>EDIT DISCLOSURE RECORD</center>");
  if ($submit_2=="" or $title==""  or $status=="" or $country=="")/*Where the update else comes from*/{
    if ($submit_2!=""){?>
    <center><br><font color="red">Please update fields marked with a red asterisk.</font><br></center><?}?>
  <form method=get action="<?=$PHP_SELF;?>">
    <input type="hidden" name="module" value="<?=$module;?>">
    <input type="hidden" name="DISCFAM" value="0">
    <input type="hidden" name="DISCEDIT" value="1">
    <input type="hidden" name="DISCFAM_ID" value="<?=$DISCFAM_ID;?>">
    <input type="hidden" name="DISC_ID" value="<?=$DISC_ID;?>">
    <input type="hidden" name="docket_alt" value="<?=$docket_alt;?>">
    <input type="hidden" name="ACTIONS" value="<?=$ACTIONS;?>">
    <input type="hidden" name="ATTACHEDFILES" value="<?=$ATTACHEDFILES;?>">
    <input type="hidden" name="I" value="0">
    <input type="hidden" name="EDIT" value="Y">
    <input type="hidden" name="NEW" value="<?=$NEW;?>">
    <input type="hidden" name="original" value="<?=$original;?>">
    <input type="hidden" name="creator" value="<?=$creator;?>">
    <input type="hidden" name="create_date" value="<?=$create_date;?>">
    <input type="hidden" name="editor" value="<?=$editor;?>">
    <input type="hidden" name="edit_date" value="<?=$edit_date;?>">
  <table align="center" width="100%" border="0" cellpadding="0" cellspacing="5">
      <tr>
	  <td align="right" width="115">
	      Title
	  </td>
	  <td>&nbsp;&nbsp;&nbsp;</td>
	  <td colspan="1" align="left" width="450">
	      <input name="title" type="text" maxlength="200" size="60" value="<?=$title;?>">
	      <font color="orangered"><TT><B>*</B></TT></font>
	  </td>
      </tr>
      <tr>
	  <td align="right" width="115">
	    ECS Docket No.
	  </td>
	  <td></td>
	  <td width="205">
	    <?=$docket_alt?>
	  </td>
	  <td align="right" width="115">
	      Status
	  </td>
	  <td></td>
	  <td width="205">			
			  <select name="status" size="1">
			  <option><?=$status;?></option>
		      <? $sql="SELECT * FROM menus WHERE menu_type='DISCLOSURE_STATUS' ORDER BY menu_name";
			  $result=mysql_query($sql);
			  while($row=mysql_fetch_array($result)){
		$menu_name=$row["menu_name"];?>
		<option><?=$menu_name?></option>"
			  <?}?>
	      </select>
	      <font color="orangered"><TT><B>*</B></TT></font>
	  </td>
      </tr>
      <tr>
	  <td align="right" width="115">
	      Client
	  </td>
	  <td></td>
	  <td width="205">
	      <select name="client" size="1">
			  <option><?=$client;?></option>
		      <? $sql="SELECT * FROM menus WHERE customer_ID='$customer_ID' and menu_type='CLIENT' ORDER BY menu_name";
			  $result=mysql_query($sql);
			  while($row=mysql_fetch_array($result)){
		$menu_name=$row["menu_name"];?>
		<option><?=$menu_name?></option>
			  <?}?>
	      </select>
	      <font color="orangered"><TT><B>*</B></TT></font>
	  </td>
	  <td align="right" width="115">
	      Client Contact
	  </td>
	  <td></td>
	  <td width="205">
	      <input name="client_contact" type="text" maxlength="100" size="25" value="<?=$client_contact;?>">
	  </td>
      </tr>
      </tr>
      <tr>
	  <td align="right" width="115">
	      Country
	  </td>
	  <td></td>
	  <td width="205">
	      <select name=country size="1">
	      <option><?=$country;?></option>
			  <? country_list();?>
	      </select>
	      <font color="orangered"><TT><B>*</B></TT></font>
			  &nbsp;&nbsp;<a target="_blank" href="help.php#country_codes">Country Codes</a>
	  </td>
	  <td align="right" width="115">
	      Invention Date
	  </td>
	  <td></td>
	  <td width="205">
	      <input type=text name="invention_date" size="11" maxlength="10" value="<?=$invention_date;?>">
			  <small>&nbsp;(YYYY-MM-DD)</small>
	  </td>
      <tr>
	  <td align="right" valign="top" width="115">
		Related<br>Documents
	      </td><td></td>
	  <td width="205">
	    <input type="checkbox" name="ids" value="Y" <? if ($ids=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Invented Disclosure<br>
	    <input type="checkbox" name="odocs" value="Y" <? if ($odocs=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Other Documents<br>
            <input type="checkbox" name="ffil" value="Y" <? if ($ffil=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Foreign Filing
	      </td>
	  <td align="right" valign="top" width="115">
	      Inventors<br>
			  <a target=_blank href="inventors_list.php?module=<?=$module;?>&DISC_ID=<?=$DISC_ID;?>&I=1&EDIT=Y">Edit</a>&nbsp;&nbsp;
	  </td><td></td>
	  <td width="205" valign="top"><!-- EXISTING INVENTORS -->
		   <? // SQL Query for Selecting All $TYPE IP Records
		    $sql_1="SELECT * FROM disc_inventors WHERE
		      customer_ID='$customer_ID' and
		      disc_ID='$DISC_ID' ORDER BY ID";
		    $result_1=mysql_query($sql_1);
		    while($row_1=mysql_fetch_array($result_1)){
		      $inventor_ID=$row_1["inventor_ID"];
		      $sql_2="SELECT * FROM inventors WHERE
			ID='$inventor_ID'";
		      $result_2=mysql_query($sql_2);
		      // Print the records
			  $row_2=mysql_fetch_array($result_2);
			  $ID=$row_2["ID"];
			  $first_name = $row_2["first_name"];
			  $middle_name = $row_2["middle_name"];
			  $middle_initial = substr($middle_name,0,1);  
			  $last_name = $row_2["last_name"];?>
			  <a target=_blank href="inventors_edit.php?module=<?=$module;?>&ID=<?=$ID;?>&I=1&EDIT=N"><?=$first_name;?>&nbsp;<?=$middle_initial;?>.&nbsp;<?=$last_name;?></a><br>
		    <?}?>
	  </td>
	  </tr>
	  <tr>
	  <td align="right" valign="top" width="115">
	      Abstract
	  </td><td></td>
	  <td colspan="1">
	      <textarea wrap name="abstract" rows="3" cols="50"><?=$abstract;?></textarea>
	  </td>
	  <td align="right" valign="top" width="115">
	      Products
	  </td><td></td>
	  <td colspan="3" align="left" valign="top">
	      <textarea wrap name="products" rows="3" cols="50"><?=$products;?></textarea>
	  </td>
	  </tr>	
	  <tr>
	  <td align="right" valign="top" width="115">
	      Notes
	  </td><td></td>
	  <td colspan="1" valign="top">
	      <textarea wrap name="notes" rows="2" cols="50"><?=$notes;?></textarea>
	  </td>
	  <tr>
	  <td align="right" valign="top" width="115">
	      Actions<br>
			  <a target=_blank href="discaction_edit.php?module=<?=$module;?>&DISC_ID=<?=$DISC_ID;?>&ACTION_ID=0&I=0&EDIT=Y">Add</a>&nbsp;&nbsp;
	  </td><td></td>
	  <td colspan="1" valign="top"><!-- EXISTING DISC ACTIONS -->
		    <? // SQL Query for Selecting Actions Type
		    echo($ACTIONS." Actions");?>
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=<?=$DISC_ID;?>&ACTIONS=All&I=1&EDIT=Y">All</a> -
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=<?=$DISC_ID;?>&ACTIONS=Open&I=1&EDIT=Y">Open</a> -
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=<?=$DISC_ID;?>&ACTIONS=Closed&I=1&EDIT=Y">Closed</a><br>
		    <? if ($ACTIONS=="Open") 
		      $sql="SELECT * FROM disc_actions WHERE
			disc_id='$DISC_ID' and
			    done='N'";
		    elseif ($ACTIONS=="Closed")
		      $sql="SELECT * FROM disc_actions WHERE
			disc_id='$DISC_ID' and
			    done='Y'";
		    else
	      $sql="SELECT * FROM disc_actions WHERE
			disc_id='$DISC_ID'
			    ORDER BY respdue_date";
		    $result=mysql_query($sql);
		    while($row=mysql_fetch_array($result)){
		      $ACTION_ID=$row["action_ID"];
		      $action_type=$row["action_type"];
		      $respdue_date=$row["respdue_date"];
		      $description=$row["description"];
		      $respdone_date=$row["respdone_date"];
		      $done=$row["done"];
		    if ($done == "N") {?>
			<a href="delete_confirm.php?module=<?=$module;?>&TABLE=disc_actions&ID=<?=$ACTION_ID;?>&NAME=Action For Docket No. <?=$docket_alt;?>"><font color="red"><b>X</b></font></a>&nbsp;
			<a target=_blank href="discaction_edit.php?module=<?=$module;?>&DISC_ID=<?=$DISC_ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
			    <?=$action_type;?></a>&nbsp;Due&nbsp;<?=$respdue_date;?><br> <?}
		    else {	//done
		      ?><a href="delete_confirm.php?module=<?=$module;?>&TABLE=disc_actions&ID=<?=$ACTION_ID;?>&NAME=Action For Docket No. <?=$docket_alt;?>"><font color="red"><b>X</b></font></a>&nbsp;
		      <a target=_blank href="discaction_edit.php?module=<?=$module;?>&DISC_ID=<?=$DISC_ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
			    <?=$action_type;?></a>&nbsp;Completed&nbsp;<?=$respdone_date;?><br>
		  <?}}?>
	  </td>
         <td align="right" valign="top" width="115">
	      Files<br>
		<a target=_blank href="discdoc_upload.php?module=<?=$module;?>&DISC_ID=<?=$DISC_ID;?>&id=0&I=0&EDIT=Y">Add</a>
	  </td><td></td>
	 <td colspan="1" valign="top" width="400"><!-- EXISTING DISC FILES -->
		    <??>&nbsp;&nbsp;<?
		    echo($ATTACHEDFILES." Files");?>
			 <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=<?=$DISC_ID;?>&ATTACHEDFILES=All&I=1&EDIT=Y">All</a>&nbsp;<?	//All will always be default
		    $sql = "SELECT * FROM menus WHERE menu_type='DISCLOSURE_DOCUMENT' ORDER BY menu_name";	//selecting doc names
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
			  $doctype=$row["menu_name"];
			  ?>-&nbsp;<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=<?=$DISC_ID;?>&ATTACHEDFILES=<?=$doctype;?>&I=1&EDIT=Y"><?=$doctype;?></a>&nbsp; 
			<?}?><br>
		    <? if($ATTACHEDFILES=="All")
			$sql="Select * FROM disc_documents WHERE
			      disc_ID='$DISC_ID'
				ORDER BY doc_date";
		    else	//should only go if ATTACHEDFILES is a valid subtype
		      $sql="SELECT * FROM disc_documents WHERE
			disc_id='$DISC_ID' and
			  subtype='$ATTACHEDFILES'
			    ORDER BY doc_date";;
		    $result=mysql_query($sql);
		    while($row=mysql_fetch_array($result)){
		      $DOC_ID=$row["id"];
		      $subtype=$row["subtype"];
		      //$respdue_date=$row["respdue_date"];
		      $docName=$row["name"];
		      $alt_name=$row["alt_name"];?>
			  &nbsp;&nbsp;<a href="delete_confirm.php?module=<?=$module;?>&TABLE=disc_documents&ID=<?=$DOC_ID;?>&NAME=Document For Docket No. <?=$docket_alt;?>&alt_name=<?=$alt_name;?>"><font color="red"><B>X</B></font></a>&nbsp
			  <a target=_blank href="discdoc_upload.php?module=<?=$module;?>&DISC_ID=<?=$DISC_ID;?>&id=<?=$DOC_ID;?>&subtype=<?=$subtype;?>&I=1&EDIT=N">
			    <?=$subtype;?></a>&nbsp;&nbsp;
			    <a href="<?=$absolute_documents.$alt_name;?>"><?=$docName;?></a><br>
		    <?}?>
	  </td>
      </tr>
      <tr>
	<td align="right" valign="top" width="115">
	      Attached Patents<br>
		<a target=_blank href="link_patent.php?disc_ID=<?=$DISC_ID;?>">Add</a>
	  </td><td></td>
	 <td colspan="1" valign="top"><!-- EXISTING DISC PATENTS -->
		    <? 
	      $sql="SELECT * FROM disc_patents WHERE
			disc_ID='$DISC_ID'
			    ORDER BY pat_ID";
		    $result=mysql_query($sql);
		    while($row=mysql_fetch_array($result)){
		      $attached_ID=$row["ID"];
		      $pat_ID=$row["pat_ID"];
		      $pat_docket_alt=$row["pat_docket_alt"];
		      $pat_name=$row["pat_name"];?>
			  <a href="delete_confirm.php?module=<?=$module;?>&TABLE=disc_patents&ID=<?=$attached_ID;?>&NAME=Patent For Docket No. <?=$docket_alt;?>&alt_name=<?=$alt_name;?>"><font color="red"><B>X</B></font></a>&nbsp
			  <?=$pat_docket_alt;?>&nbsp;&nbsp;
			  <a target=_blank href="patents_edit.php?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$pat_ID;?>&ACTIONS=Open&I=1&EDIT=N"><?=$pat_name;?></a><br>
		    <?}?>
	  </td>
      </tr>
      <tr>
	  <td align="center" colspan="6" width="100%">
	      <hr noshade size="1" width="500">
	      <input type=submit name="submit_2" value="  OK  ">
	  </td>
      </tr>
  </table>
  <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#336666" bgcolor="#336666"><tr>
    <td width="50%" align="center" bgcolor="#EEEEEE"><small>Creator: <?=$creator;?> on <?=$create_date;?></small></td>
    <td width="50%" align="center" bgcolor="#EEEEEE"><small>Editor: <?=$editor;?> on <?=$edit_date;?></small></td></tr>
  </table>
  
  
<table width="350" border="0" cellpadding="1" cellspacing="1" class="box">
<tr> 

</tr>
</table>
<?}

  else{
// enable apostrophe's in data fields by using escape_string to insert a '/' before every special character as defined in mysql
$title = mysql_real_escape_string($title);
$client_contact = mysql_real_escape_string($client_contact);
$abstract = mysql_real_escape_string($abstract);
$products = mysql_real_escape_string($products);
$notes = mysql_real_escape_string($notes);
$invention_date = mysql_real_escape_string($invention_date);
//check to make sure dates are valid
if($invention_date == "")
  {$invention_date = "0000-00-00";}
    // The script performs the database modification
$sql = "UPDATE disc_filings SET
	customer_ID = '$customer_ID',
	discfam_id = '$DISCFAM_ID',
	org = '$userorg',
	docket = '$docket',
	docket_alt = '$docket_alt',
	firm = '$firm',
	firm_contact = '$firm_contact',
	client = '$client',
	client_contact = '$client_contact',
	title = '$title',
	inventors = '$inventors',
	country = '$country',
	status = '$status',
	invention_date = '$invention_date',
	ids = '$ids',
	odocs = '$odocs',
	ffil = '$ffil',
	abstract = '$abstract',
	products = '$products',
	notes = '$notes',
	file_paths = '$file_paths',
	    editor='$fullname',
	    edit_date='$today'
	WHERE disc_id='$DISC_ID'";
    // RUN THE QUERY
      if (!mysql_query($sql)){
  echo mysql_error();
   exit;}
	      
    // Autodocket if it's a first
      if($NEW=="Y"){
	include("autoactions.php");}
     $DONE="1";
  }
}

else{ // DISPLAY RECORD -- NOT EDIT
?>
<!-- DISPLAY DISCLOSURE -->
<table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">
    <a href="disclosures_list.php?module=<?=$module;?>&SORT=DISCFAM&DISCFAM_ID=<?=$DISCFAM_ID;?>">View Family</a>&nbsp;|&nbsp;
    <a href="total_cost.php?module=<?=$module;?>&ID=<?=$docket_alt;?>">Cost</a>    
    <? if ($sysadmin=="Y" or $user_level != "Viewer"){?>&nbsp;|&nbsp;
    <a href="accounts_edit.php?module=<?=$module;?>&ecs_docket=<?=$docket_alt;?>&firm_docket=<?=$docket;?>&I=0">Add Transaction</a>&nbsp;|&nbsp;
  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=<?=$DISC_ID?>&ACTIONS=Open&I=1&EDIT=Y&SORT=<?=$SORT;?>&VAR=<?=$VAR?>">Edit</a>
  <?}?></td></tr>
</table><br><br>
<center>FULL DISCLOSURE RECORD</center><br>
  <table align="center" width="100%" border="0" cellpadding="0" cellspacing="5">
      <tr>
	  <td align="right" width="115">
	      Title
	  </td>
	  <td>&nbsp;&nbsp;&nbsp;</td>
	  <td colspan="4" align="left" width="450" bgcolor="#EEEEEE">
            <?=$title;?>
        </td>
      </tr>
      <tr>
      <tr>
	<td align="right" width="115">
	ECS Docket No.</td>
	<td></td>
	<td width="205" bgcolor="#EEEEEE">
	  <?=$docket_alt;?>
	</td>
        <td align="right" width="115">
            Status
        </td><td></td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$status;?>
        </td>
      </tr>
    </tr>
      <tr>
        <td align="right" width="115">
            Client
        </td><td></td>
        <td width="205" bgcolor="#EEEEEE">
			<?=$client;?>
        </td>
        <td align="right" width="115">
            Client Contact
        </td><td></td>
        <td width="205" bgcolor="#EEEEEE">
            <?=$client_contact;?>
        </td>
    </tr>
      <tr>
	  <td align="right" width="115">
	      Country
	  </td><td></td>
	  <td width="205" bgcolor="#EEEEEE">
	      <?=$country;?>
	      &nbsp;&nbsp;<a target="_blank" href="help.php#country_codes">Country Codes</a>
	  </td>
	  <td align="right" width="115">
	      Invention Date
	  </td><td></td>
	  <td width="205" bgcolor="#EEEEEE">
	     <?=$invention_date;?>
	  </td>
      <tr>
	  <td align="right" valign="top" width="115">
		Related<br>Documents
	      </td><td></td>
	  <td width="205" bgcolor="#EEEEEE">
	    <input type="checkbox" name="ids" value="Y" <? if ($ids=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Invented Disclosure<br>
	    <input type="checkbox" name="odocs" value="Y" <? if ($odocs=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Other Documents<br>
            <input type="checkbox" name="ffil" value="Y" <? if ($ffil=="Y") echo ("checked");?>>&nbsp;&nbsp;&nbsp;Foreign Filing
	      </td>
	  <td align="right" valign="top" width="115">
	      Inventors<br>
			  <?if ($user_level != "Viewer") {?><a target=_blank href="inventors_list.php?module=<?=$module;?>&DISC_ID=<?=$DISC_ID;?>&I=1&EDIT=Y">Edit</a>&nbsp;&nbsp;<?}?>
	  </td><td></td>
	  <td width="205" valign="top" bgcolor="#EEEEEE"><!-- EXISTING INVENTORS -->
		   <? // SQL Query for Selecting All $TYPE IP Records
		    $sql_1="SELECT * FROM disc_inventors WHERE
		      customer_ID='$customer_ID' and
		      disc_id='$DISC_ID' ORDER BY ID";
		    $result_1=mysql_query($sql_1);
		    while($row_1=mysql_fetch_array($result_1)){
		      $inventor_ID=$row_1["inventor_ID"];
		      $sql_2="SELECT * FROM inventors WHERE
			ID='$inventor_ID'";
		      $result_2=mysql_query($sql_2);
		      // Print the records
			  $row_2=mysql_fetch_array($result_2);
			  $ID=$row_2["ID"];
			  $first_name = $row_2["first_name"];
			  $middle_name = $row_2["middle_name"];
			  $middle_initial = substr($middle_name,0,1);  
			  $last_name = $row_2["last_name"];?>
			  <a target=_blank href="inventors_edit.php?module=<?=$module;?>&ID=<?=$ID;?>&I=1&EDIT=N"><?=$first_name;?>&nbsp;<?=$middle_initial;?>.&nbsp;<?=$last_name;?></a><br>
		    <?}?>
	  </td>
          </td>
	  </tr>
	  <tr>
	  <td align="right" valign="top" width="115">
	      Abstract
	  </td><td></td>
	  <td colspan="4" bgcolor="#EEEEEE">
	      <?=$abstract;?>
	  </td>
	  </tr>
	  <tr>
	  <td align="right" valign="top" width="115">
	      Products
	  </td><td></td>
	  <td colspan="4" valign="top" bgcolor="#EEEEEE">
	     <?=$products;?>
	  </td>
	  </tr>
	  <tr>
	  <td align="right" valign="top" width="115">
	      Notes
	  </td><td></td>
	  <td colspan="4" valign="top" bgcolor="#EEEEEE">
	     <?=$notes;?>
	  </td>
	  </tr>
	  <tr>
	  <td align="right" valign="top" width="115">
	      Actions<br>
			  <?if ($user_level != "Viewer") {?><a target=_blank href="discaction_edit.php?module=<?=$module;?>&DISC_ID=<?=$DISC_ID;?>&ACTION_ID=0&I=0&EDIT=Y">Add</a>&nbsp;&nbsp;<?}?>
	  </td><td></td>
	  <td colspan="1" valign="top" bgcolor="#EEEEEE"><!-- EXISTING DISC ACTIONS -->
		    <? // SQL Query for Selecting Actions Type
		    echo($ACTIONS." Actions");?>
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=<?=$DISC_ID;?>&ACTIONS=All&I=1&EDIT=N">All</a> -
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=<?=$DISC_ID;?>&ACTIONS=Open&I=1&EDIT=N">Open</a> -
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=<?=$DISC_ID;?>&ACTIONS=Closed&I=1&EDIT=N">Closed</a><br>
		    <? if ($ACTIONS=="Open") 
		      $sql="SELECT * FROM disc_actions WHERE
			disc_id='$DISC_ID' and
			    done='N'";
		    elseif ($ACTIONS=="Closed")
		      $sql="SELECT * FROM disc_actions WHERE
			disc_id='$DISC_ID' and
			    done='Y'";
		    else
	      $sql="SELECT * FROM disc_actions WHERE
			disc_id='$DISC_ID'
			    ORDER BY respdue_date";
		    $result=mysql_query($sql);
		    while($row=mysql_fetch_array($result)){
		      $ACTION_ID=$row["action_ID"];
		      $action_type=$row["action_type"];
		      $respdue_date=$row["respdue_date"];
		      $description=$row["description"];
		      $respdone_date=$row["respdone_date"];
		      $done=$row["done"];
		    if ($done == "N") {?>
			<a target=_blank href="discaction_edit.php?module=<?=$module;?>&DISC_ID=<?=$DISC_ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
			    <?=$action_type;?></a>&nbsp;Due&nbsp;<?=$respdue_date;?><br> <?}
		    else {	//done
		      ?><a target=_blank href="discaction_edit.php?module=<?=$module;?>&DISC_ID=<?=$DISC_ID;?>&ACTION_ID=<?=$ACTION_ID;?>&I=1&EDIT=N">
			    <?=$action_type;?></a>&nbsp;Completed&nbsp;<?=$respdone_date;?><br>
		  <?}}?>
	  </td>
         <td align="right" valign="top" width="115">
	      Files<br>
		<?if ($user_level != "Viewer") {?><a target=_blank href="discdoc_upload.php?module=<?=$module;?>&DISC_ID=<?=$DISC_ID;?>&id=0&I=0&EDIT=Y">Add</a><?}?>
	  </td><td></td>
	 <td colspan="1" bgcolor="#EEEEEE" valign="top" width="400"><!-- EXISTING DISC FILES -->
		    <? 
		    echo($ATTACHEDFILES." Files");?>
			  <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=<?=$DISC_ID;?>&ATTACHEDFILES=All&I=1&EDIT=N">All</a>&nbsp;<?	//All will always be default
		    $sql = "SELECT * FROM menus WHERE menu_type='DISCLOSURE_DOCUMENT' ORDER BY menu_name";	//selecting doc names
			$result=mysql_query($sql);
			while($row=mysql_fetch_array($result)){
			  $doctype=$row["menu_name"];
			  ?>-&nbsp;<a href="<?=$PHP_SELF;?>?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=<?=$DISC_ID;?>&ATTACHEDFILES=<?=$doctype;?>&I=1&EDIT=N"><?=$doctype;?></a>&nbsp; 
			<?}?><br></td></tr><td></td><td></td><td></td><td></td><td></td><td colspan="1" valign="top" bgcolor=EEEEEE>
		    <? if($ATTACHEDFILES=="All")
			$sql="Select * FROM disc_documents WHERE
			      disc_ID='$DISC_ID'
				ORDER BY doc_date";
		    else	//should only go if ATTACHEDFILES is a valid subtype
		      $sql="SELECT * FROM disc_documents WHERE
			disc_id='$DISC_ID' and
			  subtype='$ATTACHEDFILES'
			    ORDER BY doc_date";
		    $result=mysql_query($sql);
		    while($row=mysql_fetch_array($result)){
		      $DOC_ID=$row["id"];
		      $subtype=$row["subtype"];
		      //$respdue_date=$row["respdue_date"];
		      $docName=$row["name"];
			$alt_name=$row["alt_name"];?>
			  <a target=_blank href="discdoc_upload.php?module=<?=$module;?>&DISC_ID=<?=$DISC_ID;?>&id=<?=$DOC_ID;?>&subtype=<?=$subtype;?>&I=1&EDIT=N">
			    <?=$subtype;?></a>&nbsp;&nbsp;
			    <a href="<?=$absolute_documents.$alt_name;?>"><?=$docName;?></a><br>
		    <?}?>
	  </td>
      </tr>
	  <tr>
	<td align="right" valign="top" width="115">
	      Attached Patents<br>
		<?if ($user_level != "Viewer") {?><a target=_blank href="link_patent.php?disc_ID=<?=$DISC_ID;?>">Add</a><?}?>
	  </td><td></td>
	 <td colspan="4" valign="top" bgcolor=EEEEEE><!-- EXISTING DISC PATENTS -->
		    <? 
	      $sql="SELECT * FROM disc_patents WHERE
			disc_ID='$DISC_ID'
			    ORDER BY pat_ID";
		    $result=mysql_query($sql);
		    while($row=mysql_fetch_array($result)){
		      $attached_ID=$row["ID"];
		      $pat_ID=$row["pat_ID"];
		      $pat_docket_alt=$row["pat_docket_alt"];
		      $pat_name=$row["pat_name"];?>
			  <?=$pat_docket_alt;?>&nbsp;&nbsp;
			  <a target=_blank href="patents_edit.php?module=<?=$module;?>&PATFAM=0&PATEDIT=1&PAT_ID=<?=$pat_ID;?>&ACTIONS=Open&I=1&EDIT=N"><?=$pat_name;?></a><br>
		    <?}?>
	  </td>
      </tr>
      <tr>
	  <td align="center" colspan="6" width="100%">
	      <hr noshade size="1" width="500">
	  </td>
      </tr>
  </table>
  <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#336666" bgcolor="#336666"><tr>
    <td width="50%" align="center" bgcolor="#EEEEEE"><small>Creator: <?=$creator;?> on <?=$create_date;?></small></td>
    <td width="50%" align="center" bgcolor="#EEEEEE"><small>Editor: <?=$editor;?> on <?=$edit_date;?></small></td></tr>
  </table>

<?}}?>

<!-- DONE -->
<? if ($DONE=="1") {?>
<META HTTP-EQUIV="refresh" 
CONTENT="0;URL=<?=$PHP_SELF;?>?module=<?=$module;?>&DISCFAM=0&DISCEDIT=1&DISC_ID=<?=$DISC_ID?>&I=1&EDIT=N">
<?} html_footer(); ?>
