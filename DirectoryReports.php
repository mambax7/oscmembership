<?php
/*******************************************************************************
 *
 *  filename    : DirectoryReports.php
 *  last change : 2003-09-03
 *  description : form to invoke directory report
 *
 *  http://osc.sourceforge.net
 *
 *  This product is based upon work previously done by Infocentral (infocentral.org)
 *  on their PHP version Church Management Software that they discontinued
 *  and we have taken over.  We continue to improve and build upon this product
 *  in the direction of excellence.
 * 
 *  OpenSourceChurch (OSC) is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 * 
 *  Any changes to the software must be submitted back to the OpenSourceChurch project
 *  for review and possible inclusion.
 *
 *  Copyright 2003 Chris Gebhardt
 *  Copyright 2006, Steve McAtee
 ******************************************************************************/
include_once "../../mainfile.php";

if ( !is_object($xoopsUser) || !is_object($xoopsModule))  {
    exit("Access Denied");
}

require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/ReportConfig.php");

include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/fpdf151/fpdf.php";

require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/class_fpdf_labels.php";

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/person.php';


// Set the page title and include HTML header
//$sPageTitle = gettext("Directory reports");
//require "Include/Header.php";
include(XOOPS_ROOT_PATH."/header.php");

$GLOBALS['xoopsOption']['template_main'] ="reportdirectory.html";

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/osclist.php';

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/group.php';

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/churchdir.php';

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";


$churchdir_handler = &xoops_getmodulehandler('churchdir', 'oscmembership');
$churchdir= $churchdir_handler->create();
$churchdir = $churchdir_handler->get($churchdir);

$osclist_handler = &xoops_getmodulehandler('osclist', 'oscmembership');
$osclist = $osclist_handler->create();
$osclist->assignVar('id','1');

$classification_result = $osclist_handler->getitems($osclist);
$xoopsTpl->assign('optionitems',$classification_result);


$osclist = $osclist_handler->create();
$osclist->assignVar('id',1);  //pull membership classifications
$optionItems = $osclist_handler->getitems($osclist);

$form = new XoopsThemeForm("", "reportdirectoryform", "DirectoryReport_pdf.php", "post", true);

$class_select = new XoopsFormSelect(_oscmem_dirreport_selectclass,'sDirClassifications[]',"",5,true, 'class');
foreach($optionItems as $osclist)
{
	$class_select->addOption($osclist['optionid'], $osclist['optionname']);
}

$group_handler = &xoops_getmodulehandler('group', 'oscmembership');
$groups = $group_handler->getarray();

$group_select = new XoopsFormSelect(_oscmem_dirreport_groupmemb,'GroupID[]',"",5,true, 'group');
foreach($groups as $group)
{
	$group_select->addOption($group['id'], $group['group_Name']);
}

$osclist = $osclist_handler->create();
$osclist->assignVar('id','2');
$role_result = $osclist_handler->getitems($osclist);
$role_select = new XoopsFormSelect(_oscmem_dirreport_headhouse,'sDirRoleHead[]',"",5,true, 'role');
foreach($role_result as $osclist)
{
	$role_select->addOption($osclist['optionid'], $osclist['optionname']);
}

$dirAddress = new XoopsFormCheckBox(_oscmem_address,"bdirAddress",0);
$dirAddress->addOption(0,"test");
$information_tray = new XoopsFormElementTray(_oscmem_dirreport_infoinclude, '&nbsp;');

$information_tray->addElement($dirAddress);

$form->addElement($class_select);
$form->setRequired($class_select);
$form->addElement($group_select);
$form->addElement($role_select);
$form->addElement($information_tray);

/*
<tr><TD>
<{$oscmem_informationinclude}>

</TD>
<td>
<input type="checkbox" Name="bDirAddress" value="1" checked><{$oscmem_address}><br>
<input type="checkbox" Name="bDirWedding" value="1" checked><{$oscmem_weddingdate}><br>
<input type="checkbox" Name="bDirBirthday" value="1" checked><{$oscmem_birthday}><br>
*/



$rform= $form->render();

$xoopsTpl->assign('form',$rform);

$xoopsTpl->assign('groups',$groups);

$osclist = $osclist_handler->create();
$osclist->assignVar('id','2');

$xoopsTpl->assign('oscmem_dirreport_infoinclude',_oscmem_dirreport_infoinclude);

$xoopsTpl->assign('oscmem_address',_oscmem_address);
$xoopsTpl->assign('oscmem_weddingdate',_oscmem_weddingdate);
$xoopsTpl->assign('oscmem_birthday',_oscmem_birthday);
$xoopsTpl->assign('oscmem_familyworkphone',_oscmem_familyworkphone);
$xoopsTpl->assign('oscmem_familyhomephone',_oscmem_familyhomephone);
$xoopsTpl->assign('oscmem_familycellphone',_oscmem_familycellphone);
$xoopsTpl->assign('oscmem_familyemail',_oscmem_familyemail);
$xoopsTpl->assign('oscmem_personalphone',_oscmem_personalphone);
$xoopsTpl->assign('oscmem_personalworkphone',_oscmem_personalworkphone);
$xoopsTpl->assign('oscmem_personalcell',_oscmem_personalcell);
$xoopsTpl->assign('oscmem_personalemail',_oscmem_personalemail);
$xoopsTpl->assign('oscmem_personalworkemail',_oscmem_personalworkemail);
$xoopsTpl->assign('oscmem_informationinclude',_oscmem_informationinclude);

$xoopsTpl->assign('oscmem_diroptions',_oscmem_diroptions);
$xoopsTpl->assign('oscmem_submit',_oscmem_submit);
$xoopsTpl->assign('oscmem_altfamilyname',_oscmem_altfamilyname);

$xoopsTpl->assign('oscmem_dirsort',_oscmem_dirsort);
$xoopsTpl->assign('oscmem_orderbyfirstname',_oscmem_orderbyfirstname);
$xoopsTpl->assign('oscmem_althead',_oscmem_althead);
$xoopsTpl->assign('title',_oscmem_directorytitle);

$xoopsTpl->assign('oscmem_titlepagesettings',_oscmem_titlepagesettings);
$xoopsTpl->assign('oscmem_churchname_label',_oscmem_churchname_label);
$xoopsTpl->assign('oscmem_address',_oscmem_address);
$xoopsTpl->assign('oscmem_city',_oscmem_city);
$xoopsTpl->assign('oscmem_state',_oscmem_state);
$xoopsTpl->assign('oscmem_post',_oscmem_post);
$xoopsTpl->assign('oscmem_phone',_oscmem_phone);
$xoopsTpl->assign('oscmem_disclaimer',_oscmem_disclaimer);
$xoopsTpl->assign('oscmem_usetitlepageyn',_oscmem_usetitlepageyn);

$xoopsTpl->assign('oscmem_churchname',$churchdir->getVar('church_name'));
$xoopsTpl->assign('oscmem_churchaddress',$churchdir->getVar('church_address'));
$xoopsTpl->assign('oscmem_churchcity',$churchdir->getVar('church_city'));
$xoopsTpl->assign('oscmem_churchstate',$churchdir->getVar('church_state'));
$xoopsTpl->assign('oscmem_churchzip',$churchdir->getVar('church_post'));
$xoopsTpl->assign('oscmem_churchphone',$churchdir->getVar('church_phone'));
$xoopsTpl->assign('oscmem_directorydisclaimer',$churchdir->getVar('disclaimer'));


include(XOOPS_ROOT_PATH."/footer.php");
?>
