<?php
// $Id: customfieldselectform.php,v 1.1.1.1 2006/03/12 14:57:25 root Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2006 osc churchledger.com
//			http://www.churchledger.com
//
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Steve McAtee                                          //
// URL: http://www.churchledger.com, http://www.xoops.org/
// Project: The XOOPS Project, The Open Source Church project (OSC)
// ------------------------------------------------------------------------- //
//$xoopsOption['template_main'] = 'cs_index.html';
include("../../../mainfile.php");
include '../../../include/cp_header.php';

//include(XOOPS_ROOT_PATH."/header.php");
// language files

$language = $xoopsConfig['language'] ;
// include the default language file for the admin interface
if( ! file_exists( XOOPS_ROOT_PATH . "/modules/system/language/$language/admin/blocksadmin.php") ) $language = 'english' ;

if (file_exists(XOOPS_ROOT_PATH. "/modules/" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/admin.php")) {
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/admin.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/admin.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/admin.php";

}

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";


//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

//verify permission
if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    redirect_header(XOOPS_URL, 3, _oscmem_accessdenied);
}



$myts = &MyTextSanitizer::getInstance();
$persondetail_handler = &xoops_getmodulehandler('person', 'oscmembership');

$member_handler =& xoops_gethandler('member');

$title=_oscmem_customfield;

$db = &Database::getInstance();

//Retrieve custom fields
$customFields = $persondetail_handler->getcustompersonFields();
$count=1;
$table_header.="<table class='outer'>";
$table_header.="<tr><td colspan=4><a href='customfielddetailform.php?action=create'>" . _oscmem_addcustomfield . "</td></tr>";
$table_header.="<tr>";
$table_header .= "<th></th><th>" . _oscmem_customfieldName . "</th><th>" . _oscmem_customfieldType . "</th><th></th></tr>";

while($row = $db->fetchArray($customFields)) 
{
	$table_code = $table_code . "<tr><td>" . $count++ . "</td>";

	$customName = new XoopsFormLabel('',$row["custom_Name"]);	
		
	$table_code = $table_code . "<td>" . $row["custom_Name"] . "</td>";
	$table_code = $table_code . "<td>" . $row["optionname"]. "</td>";
	$table_code = $table_code . "<td><a href='customfielddetailform.php?id=" . $row["custom_Field"] . "'>" . "Edit" . "</a></td>";
	$table_code = $table_code . "</tr>";
	
}


xoops_cp_header();

echo "<h2>" . $title . "</h2>";
	
if(!isset($row))
{
	
	$osc_label= new XoopsFormLabel('',_oscmem_noitems);
	echo $table_header . "<tr><td colspan=4>" . _oscmem_noitems . "</td></tr></table>";
}	
else
{
	echo $table_header . $table_code . "</table>"; 
}


xoops_cp_footer();

?>