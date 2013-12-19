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
//	7/1/2013	created for viewing the total cost of a docket item. created using the basic headers, I'll probably copy and paste in a lot of differnt code.
//                      the plan is to have a dynamic table of all of the accounts that are linked with the record
//                      copied the top table piece from accounts_list.
//                      this should be pretty much done. I have only tested it with ndas, but it shouldn't matter.
//      7/2/2013        changed account no. -> transaction no.
//	7/17/2013	added $user_level to header call
//      7/19/2013       adapted to work with families as well. it checks to see if there is a fam_id, and then there is a nested query to check for each docket to that family, and then each account to that docket
//      9/3/2013        cleaned up the code a little
//      9/10/2013       adapted to work with fiscal years as well. it's a simple sql query modifier.
//      9/11/2013       began work to filter fiscal lookup with client and ip type
//      9/12/2013       got it working. it uses a query to pull all accounts in the date range. then cuts them by ip type, then tries to pull from that ip type, and if it is valid, loops through to see if
//                      the client is correct. if it is, then it finally prints the record and escapes the menu loop. otherwise, it would print for each menu; plus it ensures once it prints it's done
//pdf_upload.php
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level);
if ($ORDER=="") $ORDER="acct_ID";
$total = 0;
if ($ID != "")  //for a specific record since ID was supplied
{?>
  <table align="center" width="100%" cellpadding="5">
  <tr bgcolor=EEEEEE><font size="-2">
    <td width="135"><U>Transaction No.</U>
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=acct_ID&ID=<?=$ID;?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=acct_ID DESC&ID=<?=$ID;?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
    <td width="125"><U>Amount</U>
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=amount&ID=<?=$ID;?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=amount DESC&ID=<?=$ID;?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  </font></tr>
  <?
  //SQL Query for Selecting All accounts with that ecs docket number
    $sql="SELECT * FROM accounts WHERE customer_ID = '$customer_ID' and ecs_docket='$ID' ORDER BY $ORDER";
  $result=mysql_query($sql);
  //Print the records
  while($row=mysql_fetch_array($result)){
    $acct_ID=$row["acct_id"];
    $amount=$row["amount"];
  ?>
  <tr bgcolor=EEEEEE>
    <td width="110"><small><a href="accounts_edit.php?module=<?=$module;?>&SORT=<?=$SORT?>&ACCTEDIT=1&acct_ID=<?=$acct_ID?>&I=1&EDIT=N"><?=$acct_ID;?></a></small>
    <td width="125"><small>$<?=$amount;?></small>
  </tr>
  <?
  $total = $total + $amount;
  }
  ?>
  <tr bgcolor=EEEEEE>
      <td width="110"><small><font color="red">TOTAL:</font></small>
      <td width="125"><small><font color="red">$<?=$total;?></font></small>
  </tr>
  </table>
<?}

elseif ($FY != "") //for a fiscal year since FY was supplied
{
  //set defaults
  if ($client == "") {$client = "All";}
  if ($IP_Type == "") {$IP_Type = "All";}
    ?>
        <center>Enter Fiscal Year</center>
        <table align="center" width="500">
        </table><br>
        <form method=post action="<?=$PHP_SELF;?>">
          <input type="hidden" name="module" value="<?=$module;?>">
        <table align="center" width="400" border="0" cellpadding="0" cellspacing="10">
        <tr>
            <td align="right" valign="top" width="150">
                Year
            </td>
            <td>
                    <input name="FY" type="text" maxlength="4" size="6" value="<?=$FY;?>">
            </td>
        </tr>
        <tr>
            <td align="right">
              Client
            </td>
            <td>
                <select name="client" size="1">
			  <option><?=$client;?></option>
                          <option>All</option>
		      <? $sql="SELECT * FROM menus WHERE customer_ID='$customer_ID' and menu_type='CLIENT' ORDER BY menu_name";
			  $result=mysql_query($sql);
			  while($row=mysql_fetch_array($result)){
		$menu_name=$row["menu_name"];?>
		<option><?=$menu_name?></option>"
			  <?}?>
	      </select>
            </td>
        </tr>
        <tr>
            <td align="right">
              IP Type
            </td>
            <td>
                <select name="IP_Type" size="1">
                          <option><?=$IP_Type;?></option>
			  <option>All</option>
                          <option>Copyright</option>
                          <option>Disclosure</option>
                          <option>General IPM</option>
                          <option>License</option>
                          <option>NDA</option>
                          <option>Patent</option>
                          <option>Trademark</option>
	      </select>
            </td>
        </tr>
        <tr>
            <td align=center colspan=2>
                    <input type=submit name="submit_2" value="  OK  ">
            </td>
        </tr>
        </table>
        
  <table align="center" width="100%" cellpadding="5">
  <tr bgcolor=EEEEEE><font size="-2">
    <td width="135"><U>Transaction No.</U>
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=acct_ID&ID=<?=$ID;?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=acct_ID DESC&ID=<?=$ID;?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
    <td width="125"><U>Amount</U>
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=amount&ID=<?=$ID;?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=amount DESC&ID=<?=$ID;?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  </font></tr>
  <?
  $FYprev = $FY - 1;
  $LowerLimit = $FYprev . "-11-01";
  $UpperLimit = $FY . "-10-31";
  //sql query for selecting all accounts in that year
  $sql = "SELECT * FROM accounts WHERE invoice_date >= '$LowerLimit' and invoice_date <= '$UpperLimit'";
   $result=mysql_query($sql);
  //Print the records. Do the whole following loop with only one grabbed account. it should only display one, no matter where it goes
  while($row=mysql_fetch_array($result)){
    $acct_ID=$row["acct_id"];
    $amount=$row["amount"];
    $ecs_docket=$row["ecs_docket"];
  //code for getting the client from the record
  if ($IP_Type == "Copyright" or $IP_Type == "All")
    {
      $sql2 = "SELECT * FROM copyrights WHERE docket_alt ='$ecs_docket'";
      $result2 = mysql_query($sql2);
      $row2 = mysql_fetch_array($result2);
      $real_client = $row2["client"];
      //another loop for grabbing the valid clients
      $sql3 = "SELECT * FROM menus WHERE menu_type='CLIENT' ORDER BY menu_name";
      $result3 = mysql_query($sql3);
      while($row3=mysql_fetch_array($result3))
      {
        $menu_name=$row3["menu_name"];
        //have to check if $real_client is populated in case the type is "All"--it will get here and not actually be a copyright
        //also have to make sure both clients are the same as the grabbed menu, or all clients is selected
        if ($real_client != "" and (($real_client == $client and $client == $menu_name) or $client == "All"))
        {
            ?>
            <tr bgcolor=EEEEEE>
            <td width="110"><small><a href="accounts_edit.php?module=<?=$module;?>&SORT=<?=$SORT?>&ACCTEDIT=1&acct_ID=<?=$acct_ID?>&I=1&EDIT=N"><?=$acct_ID;?></a></small>
            <td width="125"><small>$<?=$amount;?></small>
            </tr>
            <?
            $total = $total + $amount;
            break;  //this will end the current loop (menu type check) and subsequently the outer loop, unless it is ALL ip types
        }
      }
    }
  //not an elseif, since it would also fire on All, but would only display one from each master iteration
  if ($IP_Type == "Disclosure" or $IP_Type == "All")
    {
      $sql2 = "SELECT * FROM disc_filings WHERE docket_alt ='$ecs_docket'";
      $result2 = mysql_query($sql2);
      $row2 = mysql_fetch_array($result2);
      $real_client = $row2["client"];
      //another loop for grabbing the valid clients
      $sql3 = "SELECT * FROM menus WHERE menu_type='CLIENT' ORDER BY menu_name";
      $result3 = mysql_query($sql3);
      while($row3=mysql_fetch_array($result3))
      {
        $menu_name=$row3["menu_name"];
        //have to check if $real_client is populated in case the type is "All"--it will get here and not actually be a disclosure
        //also have to make sure both clients are the same as the grabbed menu, or all clients is selected
        if ($real_client != "" and (($real_client == $client and $client == $menu_name) or $client == "All"))
        {
            ?>
            <tr bgcolor=EEEEEE>
            <td width="110"><small><a href="accounts_edit.php?module=<?=$module;?>&SORT=<?=$SORT?>&ACCTEDIT=1&acct_ID=<?=$acct_ID?>&I=1&EDIT=N"><?=$acct_ID;?></a></small>
            <td width="125"><small>$<?=$amount;?></small>
            </tr>
            <?
            $total = $total + $amount;
            break;  //this will end the current loop (menu type check) and subsequently the outer loop, unless it is ALL ip types
        }
      }
    }
  //not an elseif, since it would also fire on All, but would only display one from each master iteration
  if ($IP_Type == "General IPM" or $IP_Type == "All")
    {
      $sql2 = "SELECT * FROM genipm_filings WHERE docket_alt ='$ecs_docket'";
      $result2 = mysql_query($sql2);
      $row2 = mysql_fetch_array($result2);
      $real_client = $row2["client"];
      //another loop for grabbing the valid clients
      $sql3 = "SELECT * FROM menus WHERE menu_type='CLIENT' ORDER BY menu_name";
      $result3 = mysql_query($sql3);
      while($row3=mysql_fetch_array($result3))
      {
        $menu_name=$row3["menu_name"];
        //have to check if $real_client is populated in case the type is "All"--it will get here and not actually be a disclosure
        //also have to make sure both clients are the same as the grabbed menu, or all clients is selected
        if ($real_client != "" and (($real_client == $client and $client == $menu_name) or $client == "All"))
        {
            ?>
            <tr bgcolor=EEEEEE>
            <td width="110"><small><a href="accounts_edit.php?module=<?=$module;?>&SORT=<?=$SORT?>&ACCTEDIT=1&acct_ID=<?=$acct_ID?>&I=1&EDIT=N"><?=$acct_ID;?></a></small>
            <td width="125"><small>$<?=$amount;?></small>
            </tr>
            <?
            $total = $total + $amount;
            break;  //this will end the current loop (menu type check) and subsequently the outer loop, unless it is ALL ip types
        }
      }
    }
  //not an elseif, since it would also fire on All, but would only display one from each master iteration
  if ($IP_Type == "License" or $IP_Type == "All")
    {
      $sql2 = "SELECT * FROM contracts WHERE contract_type != 'NDA' and docket_alt ='$ecs_docket'";
      $result2 = mysql_query($sql2);
      $row2 = mysql_fetch_array($result2);
      $real_client = $row2["client"];
      //another loop for grabbing the valid clients
      $sql3 = "SELECT * FROM menus WHERE menu_type='CLIENT' ORDER BY menu_name";
      $result3 = mysql_query($sql3);
      while($row3=mysql_fetch_array($result3))
      {
        $menu_name=$row3["menu_name"];
        //have to check if $real_client is populated in case the type is "All"--it will get here and not actually be a disclosure
        //also have to make sure both clients are the same as the grabbed menu, or all clients is selected
        if ($real_client != "" and (($real_client == $client and $client == $menu_name) or $client == "All"))
        {
            ?>
            <tr bgcolor=EEEEEE>
            <td width="110"><small><a href="accounts_edit.php?module=<?=$module;?>&SORT=<?=$SORT?>&ACCTEDIT=1&acct_ID=<?=$acct_ID?>&I=1&EDIT=N"><?=$acct_ID;?></a></small>
            <td width="125"><small>$<?=$amount;?></small>
            </tr>
            <?
            $total = $total + $amount;
            break;  //this will end the current loop (menu type check) and subsequently the outer loop, unless it is ALL ip types
        }
      }
    }
  //not an elseif, since it would also fire on All, but would only display one from each master iteration
  if ($IP_Type == "NDA" or $IP_Type == "All")
    {
      $sql2 = "SELECT * FROM disc_filings WHERE contract_type = 'NDA' docket_alt ='$ecs_docket'";
      $result2 = mysql_query($sql2);
      $row2 = mysql_fetch_array($result2);
      $real_client = $row2["client"];
      //another loop for grabbing the valid clients
      $sql3 = "SELECT * FROM menus WHERE menu_type='CLIENT' ORDER BY menu_name";
      $result3 = mysql_query($sql3);
      while($row3=mysql_fetch_array($result3))
      {
        $menu_name=$row3["menu_name"];
        //have to check if $real_client is populated in case the type is "All"--it will get here and not actually be a disclosure
        //also have to make sure both clients are the same as the grabbed menu, or all clients is selected
        if ($real_client != "" and (($real_client == $client and $client == $menu_name) or $client == "All"))
        {
            ?>
            <tr bgcolor=EEEEEE>
            <td width="110"><small><a href="accounts_edit.php?module=<?=$module;?>&SORT=<?=$SORT?>&ACCTEDIT=1&acct_ID=<?=$acct_ID?>&I=1&EDIT=N"><?=$acct_ID;?></a></small>
            <td width="125"><small>$<?=$amount;?></small>
            </tr>
            <?
            $total = $total + $amount;
            break;  //this will end the current loop (menu type check) and subsequently the outer loop, unless it is ALL ip types
        }
      }
    }
  //not an elseif, since it would also fire on All, but would only display one from each master iteration
  if ($IP_Type == "Patent" or $IP_Type == "All")
    {
      $sql2 = "SELECT * FROM pat_filings WHERE docket_alt ='$ecs_docket'";
      $result2 = mysql_query($sql2);
      $row2 = mysql_fetch_array($result2);
      $real_client = $row2["client"];
      //another loop for grabbing the valid clients
      $sql3 = "SELECT * FROM menus WHERE menu_type='CLIENT' ORDER BY menu_name";
      $result3 = mysql_query($sql3);
      while($row3=mysql_fetch_array($result3))
      {
        $menu_name=$row3["menu_name"];
        //have to check if $real_client is populated in case the type is "All"--it will get here and not actually be a disclosure
        //also have to make sure both clients are the same as the grabbed menu, or all clients is selected
        if ($real_client != "" and (($real_client == $client and $client == $menu_name) or $client == "All"))
        {
            ?>
            <tr bgcolor=EEEEEE>
            <td width="110"><small><a href="accounts_edit.php?module=<?=$module;?>&SORT=<?=$SORT?>&ACCTEDIT=1&acct_ID=<?=$acct_ID?>&I=1&EDIT=N"><?=$acct_ID;?></a></small>
            <td width="125"><small>$<?=$amount;?></small>
            </tr>
            <?
            $total = $total + $amount;
            break;  //this will end the current loop (menu type check) and subsequently the outer loop, unless it is ALL ip types
        }
      }
    }
  //not an elseif, since it would also fire on All, but would only display one from each master iteration
  if ($IP_Type == "Trademark" or $IP_Type == "All")
    {
      $sql2 = "SELECT * FROM tm_filings WHERE docket_alt ='$ecs_docket'";
      $result2 = mysql_query($sql2);
      $row2 = mysql_fetch_array($result2);
      $real_client = $row2["client"];
      //another loop for grabbing the valid clients
      $sql3 = "SELECT * FROM menus WHERE menu_type='CLIENT' ORDER BY menu_name";
      $result3 = mysql_query($sql3);
      while($row3=mysql_fetch_array($result3))
      {
        $menu_name=$row3["menu_name"];
        //have to check if $real_client is populated in case the type is "All"--it will get here and not actually be a disclosure
        //also have to make sure both clients are the same as the grabbed menu, or all clients is selected
        if ($real_client != "" and (($real_client == $client and $client == $menu_name) or $client == "All"))
        {
            ?>
            <tr bgcolor=EEEEEE>
            <td width="110"><small><a href="accounts_edit.php?module=<?=$module;?>&SORT=<?=$SORT?>&ACCTEDIT=1&acct_ID=<?=$acct_ID?>&I=1&EDIT=N"><?=$acct_ID;?></a></small>
            <td width="125"><small>$<?=$amount;?></small>
            </tr>
            <?
            $total = $total + $amount;
            break;  //this will end the current loop (menu type check) and subsequently the outer loop, unless it is ALL ip types
        }
      }
    }
  } //end master loop. Grab next account
  ?>
  <tr bgcolor=EEEEEE>
      <td width="110"><small><font color="red">TOTAL:</font></small>
      <td width="125"><small><font color="red">$<?=$total;?></font></small>
  </tr>
  </table>
<?}

else {  //for a family
?>  
  <table align="center" width="100%" cellpadding="5">
  <tr bgcolor=EEEEEE><font size="-2">
    <td width="135"><U>ECS Docket No.</U></td>
    <td width="135"><U>Transaction No.</U>
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=acct_ID&ID=<?=$ID;?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=acct_ID DESC&ID=<?=$ID;?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
    <td width="125"><U>Amount</U>
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=amount&ID=<?=$ID;?>"><img src="up.gif" width="13" height="11" alt="" border="0"></a>
      <a href="<?=$PHP_SELF;?>?module=<?=$module;?>&SORT=<?=$SORT;?>&DIRECTION=<?=$DIRECTION;?>&ORDER=amount DESC&ID=<?=$ID;?>"><img src="down.gif" width="13" height="11" alt="" border="0"></a></td>
  </font></tr>
  <?
  //sql queries for selecting the correct db records
  if ($type == "Disclosure")
    {$sql = "SELECT * FROM disc_filings WHERE discfam_id='$FAM_ID'";}
  elseif ($type == "Patent")
    {$sql = "SELECT * FROM pat_filings WHERE patfam_ID='$FAM_ID'";}
  else //type == trademark
    {$sql = "SELECT * FROM tm_filings WHERE tmfam_ID='$FAM_ID'";}
  $result=mysql_query($sql);
  while($row=mysql_fetch_array($result)){
    $ecs_docket = $row["docket_alt"];
    $sql2="SELECT * FROM accounts WHERE customer_ID = '$customer_ID' and ecs_docket='$ecs_docket' ORDER BY $ORDER";
  $result2=mysql_query($sql2);
  //Print the records
  while($row=mysql_fetch_array($result2)){
    $acct_ID=$row["acct_id"];
    $amount=$row["amount"];
  ?>
  <tr bgcolor=EEEEEE>
    <td width="110"><small><?=$ecs_docket;?></small></td>
    <td width="110"><small><a href="accounts_edit.php?module=<?=$module;?>&SORT=<?=$SORT?>&ACCTEDIT=1&acct_ID=<?=$acct_ID?>&I=1&EDIT=N"><?=$acct_ID;?></a></small>
    <td width="125"><small>$<?=$amount;?></small>
  </tr>
  <?
  $total = $total + $amount;
  }}?>
    <tr bgcolor=EEEEEE>
      <td></td>
      <td width="110"><small><font color="red">TOTAL:</font></small>
      <td width="125"><small><font color="red">$<?=$total;?></font></small>
  </tr>
  </table>
<?} html_footer(); ?>
