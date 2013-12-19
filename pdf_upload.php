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
//  Maitenence Log Jonathan Haas:
//	7/1/2013	created for uploading pdf invoice files. created from cpydoc_upload.
//			deleted a lot of superfluous code that is for linkages. also, I'm going to convert this into a page that doens't get created as a new window. this should ease some hassle
//			this should be done. converted it all over to the pdfs, it will upload and then go back to the add_accounts screen.
//	7/17/2013	added $user_level to header call
//	7/18/2013	replaced all authentication to $user_level while leaving in $sysadmin because I'm pretty sure that's just the root admin.
//	7/23/2013	changed it so it does do an alt_name like the other docs. this way it will allow for duplicate names
//	7/26/2013	passed ecs_docket and firm_docket throughout the whole thing; this way if you get to this page from a record, it keeps the data so it auto-populates.
//	7/30/2013	replaced path names with session variables
//	8/2/2013	deleted max file size; changed in php.ini to 50M; added msg when it's too big
//	8/29/2013	cleaned up the code a little
//	9/9/2013	allowed apostrophes in name fields.
//	9/11/2013	added exit and footer after notification that a file exists
//pdf_upload.php -- User Access Level: User
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
//// For new records, incoming link is set to $id="0" and I="0"
//// Otherwise, $id is set to the existing record number
if ($I=="1"){
$sql="SELECT * FROM pdf_combos WHERE id='$id'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
	$name = $row["name"];
    $alt_name = $row["alt_name"];
    $creator = $row["creator"];
    $create_date = $row["create_date"];
    $editor = $row["editor"];
    $edit_date = $row["edit_date"];
}?>
<!-- ADD OR EDIT trademark Documents -->
<? if ($EDIT=="Y" and ($sysadmin=="Y" or $user_level != "Viewer")){?>
    <table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
      <td width="100%" align="center" bgcolor="#FFFFFF">
        <? if ($id!="0"){?>
        <a href="delete_confirm.php?module=<?=$module;?>&TABLE=pdf_combos&ID=<?=$id;?>&NAME=this PDF and all accounts associated with it&alt_name=<?=$alt_name;?>">Delete</a>
        <?}?>
      </td></tr>
    </table><br><br>
    <? if ($id=="0") echo("<center>ADD PDF File</center>");
    if ($upload==""){?>
        <table align="center" width="500">
        </table><br>
        <form method=post action="<?=$PHP_SELF;?>" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="I" value="0">
          <input type="hidden" name="EDIT" value="Y">
	  <input type="hidden" name="ecs_docket" value="<?=$ecs_docket;?>">
	  <input type="hidden" name="firm_docket" value="<?=$firm_docket;?>">
          
        <table align="center" width="100%" border="0" cellpadding="0" cellspacing="10">
        <tr>
            <td align="right" valign="top" width="150">
                Name
            </td>
            <td>
                <form method="post" enctype="multipart/form-data">
		    <input type="hidden" name="id" value="<?=$id;?>">
                    <input type="hidden" name="module" value="<?=$module;?>">
                    <input name="file" type="file" id="file"> 
                    <input align="left" name="upload" type="submit" class="box" id="upload" value=" Upload ">
		</form>
            </td>
        </tr>
        </table>
        <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#336666" bgcolor="#336666"><tr>
          <td width="50%" align="center" bgcolor="#EEEEEE"><small>Creator: <?=$creator;?> on <?=$create_date;?></small></td>
          <td width="50%" align="center" bgcolor="#EEEEEE"><small>Editor: <?=$editor;?> on <?=$edit_date;?></small></td></tr>
        </table>
    <?}
    else {
    
	// ADD NEW RECORD	NOTE: don't need to check anything, as we disabled update functionality. if it's here, it's a new additon.
        $name = $_FILES["file"]["name"];
	$name = mysql_real_escape_string($name);
      $sql="INSERT INTO pdf_combos SET
        name='$name',
            creator='$fullname',
            create_date='$today',
            editor='$fullname',
            edit_date='$today'";
	// RUN THE QUERY	  
	  if (!mysql_query($sql))
              error("A database error occurred in processing your ".
              "submission.\\nIf this error persists, please ".
              "contact your HelpDesk.");
	      
	$id = mysql_insert_id();	//need the id to create the alt_name
	$alt_name = "pdf" . "$id" . "_" . $_FILES["file"]["name"];
	
	if (file_exists($absolute_invoices . $alt_name))
	    {
		echo "We're sorry, but a file by that name already exists.";
		$sql = "DELETE FROM pdf_combos WHERE id='$id'";
		if (!mysql_query($sql))
              error("A database error occurred in processing your ".
              "submission.\\nIf this error persists, please ".
              "contact your HelpDesk.");
		?>&nbsp;&nbsp;<a href="pdf_upload.php?module=<?=$module;?>&id=0&I=0&EDIT=Y">Try Again</a><?
		html_footer();
		exit;
	    }
	else
	{
	    if (!move_uploaded_file($_FILES["file"]["tmp_name"],$absolute_invoices . $alt_name))
	    {echo "Critical Failure: File size too big. Update in php.ini."; exit;}

		$alt_name = mysql_real_escape_string($alt_name);
	    $sql="UPDATE pdf_combos SET alt_name='$alt_name' WHERE id='$id'";
	  if (!mysql_query($sql))
              error("A database error occurred in processing your ".
              "submission.\\nIf this error persists, please ".
              "contact your HelpDesk.");      
            ?>
            <!-- DONE -->
	    <META HTTP-EQUIV="refresh" 
	    CONTENT="0;URL=accounts_edit.php?module=Accounting&I=0&ecs_docket=<?=$ecs_docket;?>&firm_docket=<?=$firm_docket;?>">
	<?}?>
	
<?}}?>
<!-- READ -->
<? if ($EDIT=="N"){?>

<table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">
    <? if ($sysadmin=="Y" or $user_level != "Viewer"){?>	<!--this is now how you delete. no more editing, since it would be pointless-->
    <a href="delete_confirm.php?module=<?=$module;?>&TABLE=pdf_combos&ID=<?=$id;?>&NAME=this PDF and all accounts associated with it&alt_name=<?=$alt_name;?>">Delete</a><!--&nbsp;|&nbsp;-->
    <?}?>
  </td></tr>
</table><br><br>
<center>VIEW PDF RECORD</center><br>
<table align="center" width="100%" border="0" cellpadding="0" cellspacing="10">
  <tr>
    <td align="right" valign="top" width="150">
	Name
    </td>
    <td width="400" bgcolor=EEEEEE>
        <?=$name?>
    </td>
  </tr>
</table>
<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#336666" bgcolor="#336666"><tr>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Creator: <?=$creator;?> on <?=$create_date;?></small></td>
  <td width="50%" align="center" bgcolor="#EEEEEE"><small>Editor: <?=$editor;?> on <?=$edit_date;?></small></td></tr>
</table>
<?} html_footer(); ?>
