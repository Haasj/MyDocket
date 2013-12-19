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
// common.php
//Maitnence Log Jonathan Haas:
//	6/10/2013	Added disclosures, other, and accounting tabs. Note: There are still errors that show up with undefined variables, but ignoring them for now.
//	6/11/2013	gettting a lot of errors with undefined othertab and currenttab usage. Ignoring them for now, but I don't know why I'm getting them...
//			added correct disclosures links
//			NOTE: on the links, it is important that you change the default values that are passed to the new webpage. Otherwise, the page will not initialize correctly
//			changed to disclosure values on the pointers
//	6/21/2013	added another parameter to the addition hyperlinks to default the country to US
//			added the Esterline logo to the top left corner
//			updated the "first-time" header to reflect the usual header.
//	6/24/2013	Changed everything to General IP from Other
//	6/25/2013	Changed everything to General IPM, including submenus. also links are now to the actual pages.
//	6/27/2013	updated the pre-login screen so the menus look like the login one
//	6/28/2013	made accounts links point to the right place
//	7/2/2013	changed accounts subtabs to transactions. leaving module name and everything else accounts for now...
//	7/8/2013	added a note to not use apostrophes until I figure out how to allow them
//	7/9/2013	when you clicked on list inventors from the disclosures module it wasn't linking correctly. fixed this.
//	7/16/2013	close to gettting user_level to work. it recognizes it.... but won't display it. can display it through access_control, so I know it's getting there, just not getting passed right
//	7/17/2013	added $user_level to header call
//			changed what's displayed to which user_level. eventually, we can delte orgadmin/sysadmin(?)/memadmin
//	7/22/2013	changed to powered by esterline and put a link on it
//	7/26/2013	deleted my account link.
//	8/2/2013	deleted protected test page under reports
//	8/9/2013	added RS (serbia)
//			you can now use apostrophes in all fields except menus. deleted note at top
//	8/15/2013	added /iptracker to the file path for ecs_logo. breaks it on my local server, but it works on the korry one
//	8/19/2013	^that on the pre-logged in index.phjp too
//	9/6/2013	added EM (OHIM)
function html_header($fullname,$module,$orgadmin,$user_level) {?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta http-equiv="Content-Language" content="en-us">
<meta name="description" content="MyDocket - Commercial Open Source IP Management">
<meta name="keywords" content="patent, trademark, copyright, docket, docketing">
<title>ECS Docket -- Esterline Control Systems</title>
<style type="text/css">@import url("Theme/style.css"); </style>
</head>
<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0" >
<table cellspacing="5" cellpadding="0" width="100%" border="0">
  <tbody>
  <tr><td colspan="2">
	<table cellspacing="0" cellpadding="0" width="100%" border="0">
	  <tbody>
	  <tr>
	  <td align="right">
		<img width="40" height="1" src="include/images/blank.gif"> <!--pushes menu to the right-->
	</td>
	<td valign="bottom">
	     <table cellspacing="0" cellpadding="0" border="0"><tbody>
		 <tr>
		   <td valign="top"><a href="index.php?module=Home"><img width="90" height="50" border="none" src="/ECS_logo_color_solid.jpg"></a></td>
	  	   <td valign="top" align="left" nowrap bgColor="#ffffff">
           <font size="5" color="red">ECS Docket</font><br><br></td>
		</tr><tr>
		</tr><tr>
	 	<td colspan="4">
		  <table cellpadding="0" cellspacing="0" border="0"><tbody><tr>
	<td><img src='Theme/images//blank.gif' width='1' height='1'></td>

	<?if($module=="Home") $HomeTab=currentTab; else $HomeTab=otherTab;?>
	<td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='<?=$HomeTab;?>' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='<?=$HomeTab;?>' valign='middle' nowrap align='center' height='26'>
		<a class='<?=$HomeTab;?>' href='index.php?module=Home'>Home</A></td>
      <td class='<?=$HomeTab;?>' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>
    
    <?if($module=="Disclosures") $DisclosuresTab=currentTab; else $DisclosuresTab=otherTab;?>
	<td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='<?=$DisclosuresTab;?>' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='<?=$DisclosuresTab;?>' valign='middle' nowrap align='center' height='26'>
		<a class='<?=$DisclosuresTab;?>' href='disclosures_list.php?module=Disclosures&SORT=ALL'>Disclosures</A></td>
      <td class='<?=$DisclosuresTab;?>' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>

	<?if($module=="Patents") $PatentsTab=currentTab; else $PatentsTab=otherTab;?>
	<td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='<?=$PatentsTab;?>' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='<?=$PatentsTab;?>' valign='middle' nowrap align='center' height='26'>
		<a class='<?=$PatentsTab;?>' href='patents_list.php?module=Patents&SORT=ALL'>Patents</A></td>
      <td class='<?=$PatentsTab;?>' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>

	<?if($module=="Trademarks") $TrademarksTab=currentTab; else $TrademarksTab=otherTab;?>
	<td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='<?=$TrademarksTab;?>' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='<?=$TrademarksTab;?>' valign='middle' nowrap align='center' height='26'>
		<a class='<?=$TrademarksTab;?>' href='trademarks_list.php?module=Trademarks&SORT=ALL'>Trademarks</A></td>
      <td class='<?=$TrademarksTab;?>' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>

	<?if($module=="Copyrights") $CopyrightsTab=currentTab; else $CopyrightsTab=otherTab;?>
	<td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='<?=$CopyrightsTab;?>' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='<?=$CopyrightsTab;?>' valign='middle' nowrap align='center' height='26'>
		<a class='<?=$CopyrightsTab;?>' href='copyrights_list.php?module=Copyrights&SORT=ALL'>Copyrights</A></td>
      <td class='<?=$CopyrightsTab;?>' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>

	<?if($module=="Licenses") $LicensesTab=currentTab; else $LicensesTab=otherTab;?>
	<td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='<?=$LicensesTab;?>' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='<?=$LicensesTab;?>' valign='middle' nowrap align='center' height='26'>
		<a class='<?=$LicensesTab;?>' href='licenses_list.php?module=Licenses&SORT=ALL'>Licenses</A></td>
      <td class='<?=$LicensesTab;?>' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>

	<?if($module=="NDAs") $NDAsTab=currentTab; else $NDAsTab=otherTab;?>
	<td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='<?=$NDAsTab;?>' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='<?=$NDAsTab;?>' valign='middle' nowrap align='center' height='26'>
		<a class='<?=$NDAsTab;?>' href='ndas_list.php?module=NDAs&SORT=ALL'>NDAs</A></td>
      <td class='<?=$NDAsTab;?>' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>
		
	 <?if($module=="General IPM") $GeneralTab=currentTab; else $GeneralTab=otherTab;?>
	<td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='<?=$GeneralTab;?>' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='<?=$GeneralTab;?>' valign='middle' nowrap align='center' height='26'>
		<a class='<?=$GeneralTab;?>' href='genIPM_list.php?module=General IPM&SORT=ALL'>General IPM</A></td>
      <td class='<?=$GeneralTab;?>' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>
		
	<?if($module=="Accounting") $AccountingTab=currentTab; else $AccountingTab=otherTab;?>
	<td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='<?=$AccountingTab;?>' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='<?=$AccountingTab;?>' valign='middle' nowrap align='center' height='26'>
		<a class='<?=$AccountingTab;?>' href='accounts_list.php?module=Accounting&SORT=ALL'>Accounting</A></td>
      <td class='<?=$AccountingTab;?>' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>
		
	<?if($module=="Reports") $ReportsTab=currentTab; else $ReportsTab=otherTab;?>
	<td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='<?=$ReportsTab;?>' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='<?=$ReportsTab;?>' valign='middle' nowrap align='center' height='26'>
		<a class='<?=$ReportsTab;?>' href='reports_list.php?module=Reports&SORT=ALL'>Reports</A></td>
      <td class='<?=$ReportsTab;?>' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>

	<?if($user_level=="Admin"){?><td><img src='Theme/images//blank.gif' width='1' height='1'></td>
	<?if($module=="Setup") $SetupTab=currentTab; else $SetupTab=otherTab;?>
      <td class='<?=$SetupTab;?>' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='<?=$SetupTab;?>' valign='middle' nowrap align='center' height='26'>
		<a class='<?=$SetupTab;?>' href='setup.php?module=Setup'>Setup</A></td>
      <td class='<?=$SetupTab;?>' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>
	<?}?>
		
		</tr></tbody></table>
		</td>
		</tr>
		</tbody></table>
     </td>
     <td nowrap Valign="middle" align="right">
		<font size="1">Welcome <?=$fullname;?></font><br>
	    <font size="1"><?=date("Y-m-d, g:i a");?></font><br>
	    <a target=_blank href="http://www.esterline.com"><font size="1" color="blue">Powered by Esterline</font></a>
     </td>
  </tr>
<tr>
    <td class="moduleMenu" align="left" width="100%" colSpan="3" height="25">
<table cellSpacing="3" cellpadding="0" border="0"><tbody><tr>
	<td align="right"><img width="70" height="1" src="include/images/blank.gif"></td>

	<?if($module=="Patents"){?>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="patents_list.php?module=<?=$module;?>&SORT=ALL">List Patents</A></td><td class="moduleMenu">|</td>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="patents_search.php?module=<?=$module;?>">Search Patents</A></td><td class="moduleMenu">|</td>
    <? if ($sysadmin=="Y" or $user_level=="Admin" or $user_level=="User"){?>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="patents_edit.php?module=<?=$module;?>&PATFAM=1&PATEDIT=0">Add Patents</A></td><td class="moduleMenu">|</td>
	<?}?>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="inventors_list.php?module=<?=$module;?>&SORT=ALL&ORDER=last_name">List Inventors</A></td>
	<?}
	elseif($module=="Trademarks"){?>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="trademarks_list.php?module=<?=$module;?>&SORT=ALL">List Trademarks</A></td><td class="moduleMenu">|</td>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="trademarks_search.php?module=<?=$module;?>">Search Trademarks</A></td>
    <? if ($sysadmin=="Y" or $user_level=="Admin" or $user_level=="User"){?>
	<td class="moduleMenu">|</td><td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="trademarks_edit.php?module=<?=$module;?>&TMFAM=1&TMEDIT=0">Add Trademarks</A></td>
	<?}}
	elseif($module=="Copyrights"){?>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="copyrights_list.php?module=<?=$module;?>&SORT=ALL">List Copyrights</A></td><td class="moduleMenu">|</td>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="copyrights_search.php?module=<?=$module;?>">Search Copyrights</A></td>
    <? if ($sysadmin=="Y" or $user_level=="Admin" or $user_level=="User"){?>
	<td class="moduleMenu">|</td><td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="copyrights_edit.php?module=<?=$module;?>&EDIT=Y&I=0&ID=0&country=US">Add Copyrights</A></td>
	<?}}
	elseif($module=="Licenses"){?>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="licenses_list.php?module=<?=$module;?>&SORT=ALL">List Licenses</A></td><td class="moduleMenu">|</td>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="licenses_search.php?module=<?=$module;?>">Search Licenses</A></td>
    <? if ($sysadmin=="Y" or $user_level=="Admin" or $user_level=="User"){?>
	<td class="moduleMenu">|</td><td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="licenses_edit.php?module=<?=$module;?>&EDIT=Y&I=0&ID=0&country=US">Add Licenses</A></td>
	<?}}
	elseif($module=="NDAs"){?>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="ndas_list.php?module=<?=$module;?>&SORT=ALL">List NDAs</A></td><td class="moduleMenu">|</td>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="ndas_search.php?module=<?=$module;?>">Search NDAs</A></td><td class="moduleMenu">
    <? if ($sysadmin=="Y" or $user_level=="Admin" or $user_level=="User"){?>
	<td class="moduleMenu">|</td><td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="ndas_edit.php?module=<?=$module;?>&EDIT=Y&I=0&ID=0&country=US">Add NDAs</A></td>
	<?}}

	elseif($module=="Disclosures"){?>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="disclosures_list.php?module=<?=$module;?>&SORT=ALL">List Disclosures</A></td><td class="moduleMenu">|</td>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="disclosures_search.php?module=<?=$module;?>">Search Disclosures</A></td><td class="moduleMenu">|</td>
    <? if ($sysadmin=="Y" or $user_level=="Admin" or $user_level=="User"){?>
	</td><td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="disclosures_edit.php?module=<?=$module;?>&DISCFAM=1&DISCEDIT=0">Add Disclosures</A></td><td class="moduleMenu">|</td>
	<?}?>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="inventors_list.php?module=<?=$module;?>&SORT=ALL&ORDER=last_name">List Inventors</A></td><td class="moduleMenu">
	<?}
	
	elseif($module=="Accounting"){?>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="accounts_list.php?module=<?=$module;?>&SORT=ALL">List Transactions</A></td><td class="moduleMenu">|</td>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="accounts_search.php?module=<?=$module;?>">Search Transactions</A></td><td class="moduleMenu">|</td>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="total_cost.php?module=<?=$module;?>&FY=0">Fiscal Year Lookup</a>
    <? if ($sysadmin=="Y" or $user_level=="Admin" or $user_level=="User"){?>
	<td class="moduleMenu">|</td><td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="accounts_edit.php?module=<?=$module;?>&I=0">Add Transaction</A></td>
	<?}}
	
	elseif($module=="General IPM"){?>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="genIPM_list.php?module=<?=$module;?>&SORT=ALL">List General IPM</A></td><td class="moduleMenu">|</td>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="genIPM_search.php?module=<?=$module;?>">Search General IPM</A></td><td class="moduleMenu">
    <? if ($sysadmin=="Y" or $user_level=="Admin" or $user_level=="User"){?>
	<td class="moduleMenu">|</td><td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="genIPM_edit.php?module=<?=$module;?>&EDIT=Y&I=0&ID=0">Add General IPM</A></td>
	<?}}
	
	elseif($module=="Reports"){?>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="reports_list.php?module=<?=$module;?>&SORT=ALL">List Reports</A></td><td class="moduleMenu">|</td>
	<td nowrap class="moduleMenu">
	  <a class="moduleMenu" href="reports_search.php?module=<?=$module;?>">Search Reports</A></td><td class="moduleMenu">
	<?}
	
	else{?>
	<td nowrap class="moduleMenu"></td>
	<?}?>
	<td nowrap class="moduleMenu" width="100%" align="right">
	  <a class="moduleMenu" href="index.php?LOGOUT=1">Log Out&nbsp;&nbsp;</a>
	</td>

</tr></tbody></table>
	</td>
	</tr></table></td></tr>
<tr><td valign="top" width="20%">
<!-- MAIN CONTENT TABLE-->
<table width="85%" align="center" border="0"><tr><td>
<?}?>
<?
//first thing the footer function does is close this open table; the main content one
function html_footer() {?>
</td></tr></table>
<!-- END MAIN CONTENT TABLE-->
 </td></tr>
<tr><td colspan="2" align="center">
	<table CELLSPACING=3 border=0><tr>
      <td align=center noWrap colSpan=4>
	  <A href="about.php">About</A> |
	  <A href="legal.php">Legal</A> |
	  <A href="help.php">Help</A>
	  </td>
    </tr></table>
</td></tr></table>
</body>
</html>
<table align='center'><tr><td align='center'>
ECS Docket is a production of <a target='_blank' href='http://www.esterline.com'>Esterline</a>.<br>
</td></tr></table>
<?}?>
<?
function error($msg) {
    ?>
    <html>
    <head>
    <script language="JavaScript">
    <!--
        alert("<?=$msg?>");
        history.back();
    //-->
    </script>
    </head>
    <body>
    </body>
    </html>
    <?
    exit;
}?>
<?
function db_error() {
    ?>
    <html>
    <head>
    <script language="JavaScript">
    <!--
        alert("A database error occurred, please contact your HelpDesk.");
        history.back();
    //-->
    </script>
    </head>
    <body>
    </body>
    </html>
    <?
    exit;
}?>
<?
function day_diff($month,$day,$year) {
$time_due=mktime("","","",$month,$day,$year);
$time_now=mktime();
$day_diff=floor(($time_due-$time_now)/(24*60*60));
return $day_diff;
}?>
<?
function country_list() {
echo ("<option>AL</option><option>AM</option><option>AP</option><option>AR</option>
		<option>AT</option><option>AU</option><option>AZ</option><option>BA</option>
		<option>BB</option><option>BE</option><option>BF</option><option>BG</option>
		<option>BJ</option><option>BR</option><option>BY</option><option>CA</option>
		<option>CF</option><option>CG</option><option>CH</option><option>CI</option>
		<option>CM</option><option>CN</option><option>CR</option><option>CU</option>
		<option>CY</option><option>CZ</option><option>DE</option><option>DK</option>
		<option>DZ</option><option>EA</option><option>EE</option><option>EM</option>
		<option>EP</option><option>ES</option><option>FI</option><option>FR</option>
		<option>GA</option><option>GB</option><option>GE</option><option>GH</option>
		<option>GM</option><option>GN</option><option>GR</option><option>GW</option>
		<option>HR</option><option>HU</option><option>IB</option><option>ID</option>
		<option>IE</option><option>IL</option><option>IN</option><option>IS</option>
		<option>IT</option><option>JP</option><option>KE</option><option>KG</option>
		<option>KP</option><option>KR</option><option>KZ</option><option>LI</option>
		<option>LK</option><option>LR</option><option>LS</option><option>LT</option>
		<option>LU</option><option>LV</option><option>MA</option><option>MC</option>
		<option>MD</option><option>MG</option><option>MK</option><option>ML</option>
		<option>MN</option><option>MR</option><option>MW</option><option>MX</option>
		<option>NE</option><option>NL</option><option>NO</option><option>NZ</option>
		<option>OA</option><option>PCT</option><option>PL</option><option>PT</option>
		<option>RO</option><option>RU</option><option>RS</option><option>SD</option>
		<option>SE</option><option>SG</option><option>SI</option><option>SK</option>
		<option>SL</option><option>SN</option><option>SZ</option><option>TD</option>
		<option>TG</option><option>TJ</option><option>TM</option><option>TR</option>
		<option>TT</option><option>TW</option><option>TZ</option><option>UA</option>
		<option>UG</option><option>US</option><option>UZ</option><option>VN</option>
		<option>YU</option><option>ZA</option><option>ZW</option>");
}?>
<?
function state_list() {
echo ("<option>AL</option><option>AK</option><option>AZ</option><option>AR</option><option>CA</option>
       <option>CO</option><option>CT</option><option>DE</option><option>DC</option><option>FL</option>
       <option>GA</option><option>HI</option><option>ID</option><option>IL</option><option>IN</option>
       <option>IA</option><option>KS</option><option>KY</option><option>LA</option><option>ME</option>
	   <option>MD</option><option>MA</option><option>MI</option><option>MN</option><option>MS</option>
	   <option>MO</option><option>MT</option><option>NE</option><option>NV</option><option>NH</option>
       <option>NJ</option><option>NM</option><option>NY</option><option>NC</option><option>ND</option>
       <option>OH</option><option>OK</option><option>OR</option><option>PA</option><option>RI</option>
       <option>SC</option><option>SD</option><option>TN</option><option>TX</option><option>UT</option>
       <option>VT</option><option>VA</option><option>WA</option><option>WV</option><option>WI</option>
       <option>WY</option>");}
?>
<?
function html_headeracc() {?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta http-equiv="Content-Language" content="en-us">
<meta name="description" content="ECSDocket -- Esterline Control Systems">
<meta name="keywords" content="patent, trademark, copyright, docket, docketing">
<title>ECSDocket -- Esterline Control Systems</title>
<style type="text/css">@import url("Theme/style.css"); </style>
</head>
<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0" >
<table cellspacing="5" cellpadding="0" width="100%" border="0">
  <tbody>
  <tr><td colspan="2">
	<table cellspacing="0" cellpadding="0" width="100%" border="0">
	  <tbody>
	  <tr>
	  <td align="right">
		<img width="40" height="1" src="include/images/blank.gif"> <!--pushes menu to the right-->
	</td>
	<td valign="bottom">
	     <table cellspacing="0" cellpadding="0" border="0"><tbody>
		 <tr>
		   <td valign="top"><img width="90" height="50" src="/ECS_logo_color_solid.jpg"></td>
	  	   <td valign="top" align="left" nowrap bgColor="#ffffff">
           <font size="5" color="red">ECS Docket</font><br><br></td>
		</tr><tr>
		</tr><tr>
	 	<td colspan="4">
		  <table cellpadding="0" cellspacing="0" border="0"><tbody><tr><tr>
	<td><img src='Theme/images//blank.gif' width='1' height='1'></td>

	<td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='otherTab' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='otherTab' valign='middle' nowrap align='center' height='26'>
		<a class='otherTab' href='index.php'>Home</A></td>
      <td class='otherTab' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>

	<td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='otherTab' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='otherTab' valign='middle' nowrap align='center' height='26'>
		<a class='otherTab' href='index.php'>Disclosures</A></td>
      <td class='otherTab' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>

	<td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='otherTab' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='otherTab' valign='middle' nowrap align='center' height='26'>
		<a class='otherTab' href='index.php'>Patents</A></td>
      <td class='otherTab' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>

	<td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='otherTab' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='otherTab' valign='middle' nowrap align='center' height='26'>
		<a class='otherTab' href='index.php'>Trademarks</A></td>
      <td class='otherTab' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>

	<td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='otherTab' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='otherTab' valign='middle' nowrap align='center' height='26'>
		<a class='otherTab' href='index.php'>Copyrights</A></td>
      <td class='otherTab' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>

	<td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='otherTab' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='otherTab' valign='middle' nowrap align='center' height='26'>
		<a class='otherTab' href='index.php'>Licenses</A></td>
      <td class='otherTab' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>
      
      <!-- new -->
	  <td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='otherTab' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='otherTab' valign='middle' nowrap align='center' height='26'>
		<a class='otherTab' href='index.php'>NDAs</A></td>
      <td class='otherTab' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>
      
      <td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='otherTab' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='otherTab' valign='middle' nowrap align='center' height='26'>
		<a class='otherTab' href='index.php'>General IPM</A></td>
      <td class='otherTab' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>
      
      <td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='otherTab' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='otherTab' valign='middle' nowrap align='center' height='26'>
		<a class='otherTab' href='index.php'>Accounting</A></td>
      <td class='otherTab' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>
      
      <td><img src='Theme/images//blank.gif' width='1' height='1'></td>
      <td class='otherTab' valign='top' align='left' height='26'>
		<img src='Theme/images//left_arc.gif' height='5' width='5' border='0'></td>
      <td class='otherTab' valign='middle' nowrap align='center' height='26'>
		<a class='otherTab' href='index.php'>Reports</A></td>
      <td class='otherTab' valign='top' align='left'>
		<img src='Theme/images//right_arc.gif' height='5' width='5' border='0'></td>

		</tr></tbody></table>
		</td>
		</tr>
		</tbody></table>
     </td>
     <td nowrap Valign="middle" align="right">
		<font size="1"></font><br>
	    <font size="1"><?=date("Y-m-d, g:i a");?></font><br>
	    <a target=_blank href="http://www.esterline.com"><font size="1" color="blue">Powered by Esterline</font></a>
     </td>
  </tr>
<tr>
    <td class="moduleMenu" align="left" width="100%" colSpan="3" height="25">
<table cellSpacing="3" cellpadding="0" border="0"><tbody><tr>
	<td align="right"><img width="70" height="1" src="include/images/blank.gif"></td>
	<td nowrap class="moduleMenu"></td>
</tr></tbody></table>
	</td>
	</tr></table></td></tr>
<tr><td valign="top" width="20%">
<!-- MAIN CONTENT TABLE-->
<table width="85%" align="center" border="0"><tr><td>
<?}?>
