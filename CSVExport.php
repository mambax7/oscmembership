<?php
/*******************************************************************************
 *
 *  filename    : CSVExport.php
 *  last change : 2006-09-29
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

$GLOBALS['xoopsOption']['template_main'] ="csvexport.html";

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

$form = new XoopsThemeForm("", "csvexportform", "CSVExport_out.php", "post", true);

$class_select = new XoopsFormSelect(_oscmem_dirreport_selectclass,'sDirClassifications',"",5,true, 'class');
foreach($optionItems as $osclist)
{
	$class_select->addOption($osclist['optionid'], $osclist['optionname']);
}

$group_handler = &xoops_getmodulehandler('group', 'oscmembership');
$groups = $group_handler->getarray();

$group_select = new XoopsFormSelect(_oscmem_dirreport_groupmemb,'GroupID',"",5,true, 'group');
foreach($groups as $group)
{
	$group_select->addOption($group['id'], $group['group_Name']);
}


$lblLastName = new XoopsFormLabel(_oscmem_lastname);
$chkTitle = new XoopsFormCheckBox("","btitle",0);
$chkTitle->addOption(0,_oscmem_title);
$chkFirstName = new XoopsFormCheckBox("","bfirstname",0);
$chkFirstName->addOption(0,_oscmem_firstname);
$chkMiddlename = new XoopsFormCheckBox("","bmiddlename",0);
$chkMiddlename->addOption(0,_oscmem_middlename);
$chkSuffix = new XoopsFormCheckBox("","bsuffix",0);
$chkSuffix->addOption(0,_oscmem_suffix);
$chkAddress1 = new XoopsFormCheckBox("","baddress1",0);
$chkAddress1->addOption(0,_oscmem_address);
$chkCity = new XoopsFormCheckBox("","bcity",0);
$chkCity->addOption(0,_oscmem_city);
$chkState = new XoopsFormCheckBox("","bstate",0);
$chkState->addOption(0,_oscmem_state);
$chkPost = new XoopsFormCheckBox("","bpost",0);
$chkPost->addOption(0,_oscmem_post);
$chkCountry = new XoopsFormCheckBox("","bcountry",0);
$chkCountry->addOption(0,_oscmem_country);
$chkhomephone = new XoopsFormCheckBox("","bhomephone",0);
$chkhomephone->addOption(0,_oscmem_homephone);
$chkworkphone = new XoopsFormCheckBox("","bworkphone",0);
$chkworkphone->addOption(0,_oscmem_workphone);

//$form->addElement($lblLastName);
//$form->setRequired($class_select);
//$form->addElement($role_select);

//$form->addElement($spouserole_select);
//$form->addElement($childrole_select);

$information_tray = new XoopsFormElementTray(_oscmem_cvsexport_infoinclude, '&nbsp;');

$form->addElement($information_tray);
$form->addElement($chkTitle);
$form->addElement($chkFirstName);
//$form->addElement($chkMiddlename);
//$form->addElement($chkSuffix);
$form->addElement($chkAddress1);
$form->addElement($chkCity);
$form->addElement($chkState);
$form->addElement($chkPost);
$form->addElement($chkCountry);
$form->addElement($chkhomephone);
$form->addElement($chkworkphone);

$submit_button = new XoopsFormButton("", "submit", _oscmem_submit, "submit");

$form->addElement($submit_button);

$rform= $form->render();

$xoopsTpl->assign('form',$rform);


include(XOOPS_ROOT_PATH."/footer.php");
?>
