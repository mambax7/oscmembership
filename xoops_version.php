<?php
/**
 * Configuration file for oscMembership
 * @package oscMembership
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @version $Id$
 */
$modversion['name'] = _oscmem_MOD_NAME;
$modversion['version'] = '6.5';
$modversion['description'] = _oscmem_MOD_DESC;
$modversion['credits'] = 'Open Source Church Project - http://sourceforge.net/projects/osc';
$modversion['author'] = 'Steve McAtee';
$modversion['help'] = 'help.html';
$modversion['license'] = 'GPL see LICENSE';
$modversion['official'] = '6.5';
$modversion['image'] = 'images/module_logo.png';
$modversion['dirname'] = 'oscmembership';

// Database information
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][0] = 'oscmembership_person';
$modversion['tables'][] = 'oscmembership_family';
$modversion['tables'][] = 'oscmembership_group';
$modversion['tables'][] = 'oscmembership_groupprop_master';
$modversion['tables'][] = 'oscmembership_list';
$modversion['tables'][] = 'oscmembership_p2g2r';
$modversion['tables'][] = 'oscmembership_group_members';
$modversion['tables'][] = 'oscmembership_person_custom';
$modversion['tables'][] = 'oscmembership_person_custom_master';
$modversion['tables'][] = 'oscmembership_cart';
$modversion['tables'][] = 'oscmembership_churchdetail';

// Templates
$modversion['templates'][0] = array(
	'file' => 'simple.html',
	'description' => _OSCMEM_TEMPLATE_SIMPLE_DESC);
$modversion['templates'][] = array(
	'file' => 'oscmembership_optionlist.html',
	'description' => '');
$modversion['templates'][] = array(
	'file' => 'cartview.html',
	'description' => _OSCMEM_TEMPLATE_CARTVIEW_DESC);
$modversion['templates'][] = array(
	'file' => 'memberview.html',
	'description' => _OSCMEM_TEMPLATE_MEMBERVIEW_DESC);
$modversion['templates'][] = array(
	'file' => 'reports.html',
	'description' => _OSCMEM_TEMPLATE_REPORTS_DESC);
$modversion['templates'][] = array(
	'file' => 'reportdirectory.html',
	'description' => _OSCMEM_TEMPLATE_RPT_DIR_DESC);
$modversion['templates'][] = array(
	'file' => 'csvexport.html',
	'description' => _OSCMEM_TEMPLATE_CSV_DESC);
$modversion['templates'][] = array(
	'file' => 'oscselect.html',
	'description' => _OSCMEM_TEMPLATE_SELECT_DESC);
$modversion['templates'][] = array(
	'file' => 'familyview.html',
	'description' => _OSCMEM_TEMPLATE_FAMILY_DESC);
$modversion['templates'][] = array(
	'file' => 'groupview.html',
	'description' => _OSCMEM_TEMPLATE_GROUP_DESC);
$modversion['templates'][] = array(
	'file' => 'cartemail.html',
	'description' => _OSCMEM_TEMPLATE_CARTEMAIL_DESC);
$modversion['templates'][] = array(
	'file' => 'familyselect.html',
	'description' => _OSCMEM_TEMPLATE_FAMILYSEL_DESC);
$modversion['templates'][] = array(
	'file' => 'orphanselect.html',
	'description' => _OSCMEM_TEMPLATE_ORPHAN_DESC);



// Blocks
$modversion['blocks'][1] = array(
	'file' => 'oscmemnav.php',
	'name' => _OSCMEM_BLOCK_MEM_NAME,
	'description' => _OSCMEM_BLOCK_MEM_DESC,
	'show_func' => 'oscmemnav_show');
$modversion['blocks'][] = array(
	'file' => 'oscmemalphanav.php',
	'name' => _OSCMEM_BLOCK_ALPHANAV_NAME,
	'description' => _OSCMEM_BLOCK_ALPHANAV_DESC,
	'show_func' => 'oscmemalphanav_show');
$modversion['blocks'][] = array(
	'file' => 'oscbirthdayblock.php',
	'name' => _OSCMEM_BLOCK_BIRTHDAYS_NAME,
	'description' => _OSCMEM_BLOCK_BIRTHDAYS_DESC,
	'show_func' => 'oscbirthdayblock_show');

// Search
$modversion['hasSearch'] = 0;
//$modversion['search']['file']='include/search.inc.php';
//$modversion['search']['func']='oscmem_search';

// Administration menu
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

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