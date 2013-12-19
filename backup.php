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
// Maitence Log Jonathan Haas:
//	6/27/2013	created with header/footer contents of test_page.php
//                      couldn't get the mysqldump to work. always throws the error
//      7/11/2013       ran it with system(), and it works now. BUT the .sql file is always 0 bytes. I don't know why
//      7/12/2013       i've been trying this for three hours, and I can't get it to work. there are a ton of people online with this same problem, and they all have fixes that don't work for me. I'm thinking
//                      that the issue isn't here, since I can't get it to work in the console. Giving up for now......
//	7/17/2013	added $user_level to header call
//	7/18/2013	added authentication to $user_level while leaving in $sysadmin because I'm pretty sure that's just the root admin.
//      7/23/2013       after searching for a lot longer I finally got it working.I have no idea why it works now, but I'm not going to touch it.
//      7/26/2013       made the table/buttons for the different backup and restore options. --can't get the restore to work even with a filename, however I might have to drop the db before import.
//      8/2/2013        figured out the restore. I needed to do a shell command from the wamp directory, and it uses mysql as the command handler.
//                      I kinda did a quazy get around for the save to file: I use mysqldump to upload it to the server, then I try to open it. since I know it's a .sql file, it'll never be able to, so it'll
//                      always prompt to save it. when you click done and it detects a filename, it will delete it from the server, thus cleaning the server. the only issue I forsee is if a user selects to
//                      back up the db to a file, and then picks the folder that the temp goes into. although I guess they'd just change the name, since the temporary one will already be there
// backup.php -- User Access Level: Admin
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
if ($sysadmin == "Y" or $user_level == "Admin")
{
    if ($backup_to_file == "" and $backup_to_server == "" and $restore == "")
    {?>
        <form method=get action="<?=$PHP_SELF;?>">
        <input type="hidden" name="module" value="<?=$module;?>">
        <table align="center" border="0" width="400" cellpadding="0" cellspacing="10">
             <tr><td></td><td colspan="2" align="center">BACKUP AND RESTORE</td><td></td></tr><tr>
             <tr>
                <td width="25%" align=right height="30"><small>BACKUP</small></td>
                <td width="25%" align=center bgcolor=EEEEEE><small><input type="submit" name="backup_to_server" value="Backup to Server"></small></td>
                <td width="25%" align=center bgcolor=EEEEEE><small><input type="submit" name="backup_to_file" value="Backup to File"></small></td>
                <td width="25%" align=center><small></small></td>
             </tr>
             <tr>
                <td width="25%" align=right height="30"><small>RESTORE</small></td>
                <td colspan="2" width="50%" align=center bgcolor=EEEEEE><small><input type="submit" name="restore" value="Restore from File"></small></td>
                <td width="25%" align=center><small></small></td>
             </tr></table></form>
             <?if ($backup_file != "")
             {
                $backup_file = "C:\wamp\www\Documentation/" . $backup_file;
                if (!unlink($backup_file))
                    {echo ("Error deleting file.");}
             }?>
    <?}
    else    //something has been pushed
    if ($backup_to_server != "")
    {
        $backup_file = "demo" . date("Y-m-d-H-i-s") . ".sql";
        shell_exec ("C:\wamp\bin\mysql\mysql5.5.24\bin\mysqldump.exe --user=root --password=password demo > C:\wamp\www\Documentation/$backup_file");   //if you change the pw, make sure to do it in db.php also
        echo "<center>Your file has been placed in the Documentation folder.</center>";
        html_footer();
        exit;
    }
    elseif ($backup_to_file != "")
    {
        $backup_file = "demo" . date("Y-m-d-H-i-s") . ".sql";
        shell_exec ("C:\wamp\bin\mysql\mysql5.5.24\bin\mysqldump.exe --user=root --password=password demo > C:\wamp\www\Documentation/$backup_file");   //if you change the pw, make sure to do it in db.php also
        ?><META HTTP-EQUIV="refresh" CONTENT="0;URL=/Documentation\<?=$backup_file;?>">
        <center>Click <a href="<?=$PHP_SELF;?>?backup_file=<?=$backup_file;?>">here</a> when done.</center><?     
    }
    else    //restore is populated
    {
        ?><center>Select a file to import.<br><form method="post" enctype="multipart/form-data">
		    <input type="hidden" name="restore" value="<?=$restore;?>">
                    <input type="hidden" name="module" value="<?=$module;?>">
                    <input name="file" type="file" id="file"> 
                    <input align="left" name="choose" type="submit" class="box" id="choose" value=" Choose File ">
	</form></center><?
        if ($choose != ""){
        //i can use $file here since that is the temp directory on the server where the file is stored when the form is posted. I think this is just a pointer to the file location, and in any case, it is
        //erased on a reboot of the server. since I only need the file path, I don't have to mess with any of the $_FILE attributes
        shell_exec ("C:\wamp\bin\mysql\mysql5.5.24\bin\mysql -u root -ppassword demo < $file"); //also change pw here
        echo "<center><font color=red>The DB has been restored successfully.</font></center>";
        html_footer();
        exit;}
    }
}
html_footer();?>
