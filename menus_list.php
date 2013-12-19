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
//Maitenece Log Jonathan Haas:
//      6/18/2013       need to update this to reflect disclosures
//                      disclosures added
//      6/24/2013       added all of the documents menus
//      6/26/2013       changed so there are docs for NDAs and Licenses
//                      changed so there are actions for NDAs and Licenses
//      6/27/2013       added autoactions
//      7/1/2013        changed to be AUTO_ACTION instead of ACTION_TYPE
//                      added type to all things but ndas and genipm. they don't have types
//      7/16/2013       changed it to say view/edit for clarity
//	7/17/2013	added $user_level to header call
//      8/29/2013       cleaned up the code a little
// view_menus.php
include("accesscontrol.php");
html_header($fullname,$module,$orgadmin,$user_level); ?>
<br><br>
<center>VIEW/EDIT MENU RECORDS</center><br>
<table align="center" width="500"><br><br>
  SERVICE FIRM RECORDS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Service Firm Sort Records
$sql="SELECT * FROM menus WHERE customer_ID = '$customer_ID' and menu_type='FIRM' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
  <br>
<table align="center" width="500">
  AUTO_ACTION TYPES
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='AUTO_ACTION' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  CLIENTS/COMPANIES
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE customer_ID = '$customer_ID' and menu_type='CLIENT' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  PATENT TYPE MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='PATENT_TYPE' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  PATENT STATUS MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='PATENT_STATUS' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  PATENT ACTION MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='PATENT_ACTION' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
  <br>
<table align="center" width="500">
  PATENT DOCUMENT MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='PATENT_DOCUMENT' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  DISCLOSURE TYPE MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='DISCLOSURE_TYPE' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  DISCLOSURE STATUS MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='DISCLOSURE_STATUS' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  DISCLOSURE ACTION MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='DISCLOSURE_ACTION' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
  <br>
<table align="center" width="500">
  DISCLOSURE DOCUMENT MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='DISCLOSURE_DOCUMENT' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  TRADEMARK TYPE MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='TRADEMARK_TYPE' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  TRADEMARK STATUS MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='TRADEMARK_STATUS' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  TRADEMARK ACTION MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='TRADEMARK_ACTION' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
  <br>
<table align="center" width="500">
  TRADEMARK DOCUMENT MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='TRADEMARK_DOCUMENT' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  COPYRIGHT TYPE MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='COPYRIGHT_TYPE' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  COPYRIGHT STATUS MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='COPYRIGHT_STATUS' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
  <br>
<table align="center" width="500">
  COPYRIGHT DOCUMENT MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='COPYRIGHT_DOCUMENT' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  LICENSE TYPE MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='LICENSE_TYPE' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  LICENSE STATUS MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='LICENSE_STATUS' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  LICENSE DOCUMENT MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='LICENSE_DOCUMENT' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  LICENSE ACTION MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='LICENSE_ACTION' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  NDA STATUS MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='NDA_STATUS' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  NDA DOCUMENT MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='NDA_DOCUMENT' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  NDA ACTION MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='NDA_ACTION' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  GENERAL IPM ACTION MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='GENERAL_IPM_ACTION' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  GENERAL IPM STATUS MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='GENERAL_IPM_STATUS' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<br>
<hr noshade size="1" width="650">
<br>
<table align="center" width="500">
  GENERAL IPM DOCUMENT MENUS
</table><br>
<table align="center" width="650" cellpadding="5">
<?
// SQL Query for Selecting All Client Sort Records
$sql="SELECT * FROM menus WHERE menu_type='GENERAL_IPM_DOCUMENT' ORDER BY menu_name";
$result=mysql_query($sql);
while($row_1=mysql_fetch_array($result)){
  $menu_name_1=$row_1["menu_name"];
  $ID_1=$row_1["ID"];
  $row_2=mysql_fetch_array($result);
  $menu_name_2=$row_2["menu_name"];
  $ID_2=$row_2["ID"];
?>
<tr bgcolor=EEEEEE><font size="-2">
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_1?>&I=1"><small><?=$menu_name_1;?></small></a></td>
  <td width="325"><a href="menus_edit.php?module=<?=$module;?>&ID=<?=$ID_2?>&I=1"><small><?=$menu_name_2;?></small></a></td>
</font></tr>
<?}?>
</table>
<? html_footer(); ?>
