<?php
/**
 * Configuration file for oscMembership
 * @package oscMembership
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @version $Id$
 */
$modversion['name'] = _oscmem_MOD_NAME;
$modversion['version'] = "6.2";
$modversion['description'] = _oscmem_MOD_DESC;
$modversion['credits'] = "Open Source Church Project - http://sourceforge.net/projects/osc";
$modversion['author'] = "Steve McAtee";
$modversion['help'] = "help.html";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = "6.2";
$modversion['image'] = "images/module_logo.png";
$modversion['dirname'] = "oscmembership";

// Database information
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][0] = "oscmembership_person";
$modversion['tables'][] = "oscmembership_family";
$modversion['tables'][] = "oscmembership_group";
$modversion['tables'][] = "oscmembership_groupprop_master";
$modversion['tables'][] = "oscmembership_list";
$modversion['tables'][] = "oscmembership_p2g2r";
$modversion['tables'][] = "oscmembership_group_members";
$modversion['tables'][] = "oscmembership_person_custom";
$modversion['tables'][] = "oscmembership_person_custom_master";
$modversion['tables'][] = "oscmembership_cart";
$modversion['tables'][] = "oscmembership_churchdetail";

// Templates
$modversion['templates'][0] = array(
	'file' => 'simple.html',
	'description' => 'Simple');
$modversion['templates'][] = array(
	'file' => 'oscmembership_optionlist.html',
	'description' => '');
$modversion['templates'][] = array(
	'file' => 'cartview.html',
	'description' => 'Cart View Template');
$modversion['templates'][] = array(
	'file' => 'memberview.html',
	'description' => 'Member View Template');
$modversion['templates'][] = array(
	'file' => 'reports.html',
	'description' => 'Report Page');
$modversion['templates'][] = array(
	'file' => 'reportdirectory.html',
	'description' => 'Report Directory Options');
$modversion['templates'][] = array(
	'file' => 'csvexport.html',
	'description' => 'CSV Export Options');
$modversion['templates'][] = array(
	'file' => 'oscselect.html',
	'description' => 'standard select template');
$modversion['templates'][] = array(
	'file' => 'familyview.html',
	'description' => 'family view template');
$modversion['templates'][] = array(
	'file' => 'groupview.html',
	'description' => 'group view template');
$modversion['templates'][] = array(
	'file' => 'cartemail.html',
	'description' => 'cart generate email template');
$modversion['templates'][] = array(
	'file' => 'familyselect.html',
	'description' => 'family select template');
$modversion['templates'][] = array(
	'file' => 'orphanselect.html',
	'description' => 'orphan select template');

// Blocks
$modversion['blocks'][1] = array(
	'file' => "oscmemnav.php",
	'name' => 'OSC Navigation',
	'description' => "OSC Membership Menu",
	'show_func' => "oscmemnav_show");
$modversion['blocks'][] = array(
	'file' => "oscmemalphanav.php",
	'name' => 'Member Alpha Navigation',
	'description' => "Alpha Navigation of Membership",
	'show_func' => "oscmemalphanav_show");
$modversion['blocks'][] = array(
	'file' => "oscbirthdayblock.php",
	'name' => 'Member BirthDays',
	'description' => "Block Displaying Birthdays for the Current Month",
	'show_func' => "oscbirthdayblock_show");

// Search
$modversion['hasSearch'] = 0;
//$modversion['search']['file']="include/search.inc.php";
//$modversion['search']['func']="oscmem_search";

// Administration menu
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Include in Main Menu?
$modversion['hasMain'] = 1;
//$modversion['templates'][1]['file'] = 'cs_index.html';
//$modversion['templates'][1]['description'] = 'cs main template file';

// Comments
$modversion['hasComments'] = 1;
$modversion['comments']['pageName'] = 'index.php';
$modversion['comments']['itemName'] = 'id';

// Admin preferences items
$modversion['config'][1] = array(
	'name' => 'usermapnomap',
	'title' => _OSCMEM_USERFIELDSDONOTMAP,
	'description' => _OSCMEM_USERFIELDSDONOTMAP_DESC,
	'formtype' => 'textarea',
	'valuetype' => 'text',
	'default' => 'user_aim,user_yim,user_msnm,user_from,timezone_offset,user_occ,user_intrest,bio,user_regdate,user_viewemail,attachsig,user_mailok,theme,umode_uorder,notify_mode,notify_method,url,posts,rank,last_login,user_sig,uorder')
;

$modversion['config'][] = array(
	'name' => 'membermapnomap',
	'title' => _OSCMEM_MEMBERNOMAP,
	'description' => _OSCMEM_MEMBERNOMAP_DESC,
	'formtype' => 'textarea',
	'valuetype' => 'text',
	'default' => 'churchid,fmrid,clsid,famid,datelastedited,dateentered,enteredby,editedby,loopcount,oddrow,customfields,totalloopcount');

?>