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
//	6/24/2013	created for uploading copyrights docs. Created using discdoc_upload.php
//              	updated to work with copyrights
//	7/17/2013	added $user_level to header call
//	7/18/2013	replaced all authentication to $user_level while leaving in $sysadmin because I'm pretty sure that's just the root admin.
//	7/30/2013	replaced path names with session variables
//	8/2/2013	deleted max file size; changed in php.ini to 50M, added error message when file is too big
//			added doc_date
//	8/9/2013	allowed special chars for durabiltiy in date
//	8/15/2013	checked the date
//			allowed editing. I had to make the add and edit screens two different ones becasue they had different form types. I'm not allowing them to change the file itself, jsut the type and date
//	8/19/2013	fixed date logic; incorrectly was using checkdate. this won't work, because of formatting. now, it will work, but throw the mysql_error if the date is invalid. works with ""
//	8/21/2013	tried these four to find the folder on the server, can't get it. tomorrow I'll try to go directly to the server, and see if any upload works:
//			//AppsMyDocket:pw@korry.com/kdata/korry_data/AppData/MyDocket/
//			file://kdata/korry_data/AppData/MyDocket/
//			\\kdata\korry_data\AppData\MyDocket/
//			\\AppsMyDocket:pw@korry.com\kdata\korry_data\AppData\MyDocket/
//	8/27/2013	cleaned up the code a little
//	8/29/2013	got the file upload to work. correct path: \\\\kdata\\korry_data\\AppData\\MyDocket\\
//	9/9/2013	allowed apostrophes in name fields.
//	9/11/2013	added exit and footer after notification that a file exists
//cpydoc_upload.php -- User Access Level: User
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);?>
<?
$sql="SELECT title, docket FROM copyrights WHERE ID='$CPY_ID'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
    $title = $row["title"];
    $docket = $row["docket"];
// For new records, incoming link is set to $id="0" and I="0"
// Otherwise, $id is set to the existing record number
if ($I=="1"){
$sql="SELECT * FROM cpy_documents WHERE id='$id'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
    $CPY_ID = $row["cpy_ID"];
	$subtype = $row["subtype"];
	$name = $row["name"];
	$alt_name = $row["alt_name"];
    $doc_date = $row["doc_date"];
    $creator = $row["creator"];
    $create_date = $row["create_date"];
    $editor = $row["editor"];
    $edit_date = $row["edit_date"];
}?>
<!-- ADD OR EDIT trademark Documents -->
<? if ($EDIT=="Y" and ($sysadmin=="Y" or $user_level != "Viewer")){?>
    <table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
      <td width="100%" align="center" bgcolor="#FFFFFF">
        <? if ($CPY_ID!="0" and $id!="0"){?>
        <a href="delete_confirm.php?module=<?=$module;?>&TABLE=cpy_documents&ID=<?=$id;?>&NAME=Document For Docket No. <?=$docket;?>&alt_name=<?=$alt_name;?>">Delete</a>&nbsp;|&nbsp;
        <?}?>
        <a href="JavaScript:window.close()">Close</a>
      </td></tr>
    </table><br><br>
    <? if ($id=="0"){ echo("<center>ADD Document</center>");
    if ($upload==""){?>
        <table align="center" width="500">
        </table><br>
        <form method=post action="<?=$PHP_SELF;?>" enctype="multipart/form-data">
	          <input type="hidden" name="CPY_ID" value="<?=$CPY_ID;?>">
          <input type="hidden" name="docket" value="<?=$docket;?>">
          <input type="hidden" name="id" value="<?=$id;?>">
          <input type="hidden" name="I" value="0">
          <input type="hidden" name="EDIT" value="Y">
          <input type="hidden" name="subtype" value="<?=$subtype;?>">
          
        <table align="center" width="100%" border="0" cellpadding="0" cellspacing="10">
          <tr>
              <td align=right width="150">
                  Document Type
              </td>
              <td width="400">		  			
                          <select name="subtype" size="1">
                          <option><?=$subtype;?></option>
                          <? $sql="SELECT * FROM menus WHERE menu_type='COPYRIGHT_DOCUMENT' ORDER BY menu_name"; 
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
	    <td align=right width="150">
		Document Date
	    </td>
	    <td width="400">
	      <input type=text name="doc_date" size="11" maxlength="10" value="<?=$doc_date;?>">
			  <small>&nbsp;(YYYY-MM-DD)</small>
	  </td>
	  </tr>
        <tr>
            <td align="right" valign="top" width="150">
                Name
            </td>
            <td>
                <form method="post" enctype="multipart/form-data">
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
    <?}}
    
    elseif($I=="1")
    {
	
       echo("<center>EDIT Document</center>");
       if ($submit==""){?>
	    <table align="center" width="500">
	    </table><br>
	    <form method=post action="<?=$PHP_SELF;?>" enctype="multipart/form-data">
		      <input type="hidden" name="DISC_ID" value="<?=$DISC_ID;?>">
	      <input type="hidden" name="docket" value="<?=$docket;?>">
	      <input type="hidden" name="id" value="<?=$id;?>">
	      <input type="hidden" name="I" value="0">
	      <input type="hidden" name="EDIT" value="Y">
	      <input type="hidden" name="subtype" value="<?=$subtype;?>">
	      <input type="hidden" name="doc_date" value="<?=$doc_date;?>">
	      
	    <table align="center" width="100%" border="0" cellpadding="0" cellspacing="10">
	      <tr>
		  <td align=right width="150">
		      Document Type
		  </td>
		  <td width="400">		  			
			      <select name="subtype" size="1">
			      <option><?=$subtype;?></option>
			      <? $sql="SELECT * FROM menus WHERE menu_type='DISCLOSURE_DOCUMENT' ORDER BY menu_name"; 
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
		<td align=right width="150">
		    Document Date
		</td>
		<td width="400">
		  <input type=text name="doc_date" size="11" maxlength="10" value="<?=$doc_date;?>">
			      <small>&nbsp;(YYYY-MM-DD)</small>
	      </td>
	      </tr>
	    <tr>
		<td align="center" colspan="2" width="100%">
	      <hr noshade size="1" width="500">
	      <input type=submit name="submit" value="  OK  ">
	  </td>
	    </tr>
	    </table>
	    <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#336666" bgcolor="#336666"><tr>
	      <td width="50%" align="center" bgcolor="#EEEEEE"><small>Creator: <?=$creator;?> on <?=$create_date;?></small></td>
	      <td width="50%" align="center" bgcolor="#EEEEEE"><small>Editor: <?=$editor;?> on <?=$edit_date;?></small></td></tr>
	    </table></form><?
    }}
    if($upload != "" or $submit != ""){	//something was done
    if($doc_date == "")
	{$doc_date = "0000-00-00";}
    $doc_date = mysql_real_escape_string($doc_date);	//this is just here as a precaution so it won't break if someone inserts a special character in the date
    
    if($submit == ""){	//new record
	// ADD NEW RECORD
        $name = $_FILES["file"]["name"];
	$alt_name = "cp" . "$CPY_ID" . "_" . $_FILES["file"]["name"];

	if (file_exists($absolute_documents . $alt_name))
	    {
		echo "We're sorry, but a file by that name already exists under this copyright.";
		?>&nbsp;&nbsp;<a href="cpydoc_upload.php?module=<?=$module;?>&CPY_ID=<?=$CPY_ID;?>&id=0&I=0&EDIT=Y">Try Again</a><?
		html_footer();
		exit;
	    }
	else
	{
	    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $absolute_documents . $alt_name))
	    {echo "Critical Failure: File size too big. Update in php.ini."; exit;}
	    	//have to do sql stuff here; since windows doesn't allow backlashes in filenames, the above code won't work after I do the escapes since that function escapes special characters with backslashes
	$name = mysql_real_escape_string($name);
	$alt_name = mysql_real_escape_string($alt_name);
	
      $sql="INSERT INTO cpy_documents SET
        cpy_ID='$CPY_ID',
        subtype='$subtype',
        name='$name',
	alt_name='$alt_name',
	doc_date='$doc_date',
            creator='$fullname',
            create_date='$today',
            editor='$fullname',
            edit_date='$today'";
	 // RUN THE QUERY	  
	  if (!mysql_query($sql))
              error("A database error occurred in processing your ".
              "submission.\\nIf this error persists, please ".
              "contact your HelpDesk.");
            ?>
	<?}}
	else { 	//update
	    $sql = "UPDATE cpy_documents SET doc_date='$doc_date', subtype='$subtype' WHERE id=$id";
	    if(!mysql_query($sql)){
		echo mysql_error();
		exit;
	    }
	}?>
	
            <!-- DONE -->
            <table align="center" width="500"><tr><td><br>
            <p><strong>Your record has been successfully updated.</strong></p><br>
            <p> To see your updated record, close this window</p><br>
            <p> and refresh the page.</p>
	    </td></tr></table><br>  
    
<?}}?>
<!-- READ -->
<? if ($EDIT=="N"){?>
<table align="right" border="0" cellpadding="0" cellspacing="0"><tr>
  <td width="100%" align="center" bgcolor="#FFFFFF">
    <? if ($sysadmin=="Y" or $user_level != "Viewer"){?>	<!--this is now how you delete. no more editing, since it would be pointless-->
    <a href="delete_confirm.php?module=<?=$module;?>&TABLE=cpy_documents&ID=<?=$id;?>&NAME=Document For Docket No. <?=$docket;?>&alt_name=<?=$alt_name;?>">Delete</a>&nbsp;|&nbsp;
    <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&id=<?=$id;?>&I=1&EDIT=Y">Edit</a>&nbsp;|&nbsp;
    <?}?>
    <a href="JavaScript:window.close()">Close</a>
  </td></tr>
</table><br><br>
<center>VIEW DOCUMENT</center><br>
<table align="center" width="100%" border="0" cellpadding="0" cellspacing="10">
  <tr>
      <td align=right width="150">
          Title
      </td>
      <td width="400" bgcolor=EEEEEE>
          <?=$title;?>
      </td>
  </tr>
  <tr>
      <td align=right width="150">
         Firm Docket
      </td>
      <td width="400" bgcolor=EEEEEE>
          <?=$docket;?>
      </td>
  </tr>
  <tr>
      <td align=right width="150">
          Document Type
      </td>
      <td width="400" bgcolor=EEEEEE>
          <?=$subtype;?>
      </td>
  </tr>
  <tr>
      <td align=right width="150">
         Docket Date
      </td>
      <td width="400" bgcolor=EEEEEE>
          <?=$doc_date;?>
      </td>
  </tr>
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
<?}html_footer(); ?>
