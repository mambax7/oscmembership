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

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/ReportConfig.php");

include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/fpdf151/fpdf.php";

require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/class_fpdf_labels.php";

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/person.php';

include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if(!hasPerm("oscmembership_view",$xoopsUser)) exit(_oscmem_access_denied);

$GLOBALS['xoopsOption']['template_main'] ="reportdirectory.html";

// Set the page title and include HTML header
//$sPageTitle = gettext("Directory reports");
//require "Include/Header.php";
include(XOOPS_ROOT_PATH."/header.php");


include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/osclist.php';

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/group.php';


include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";


$churchdetail_handler = &xoops_getmodulehandler('churchdetail', 'oscmembership');
$churchdetail= $churchdetail_handler->create();
$churchdetail = $churchdetail_handler->get($churchdetail);

$osclist_handler = &xoops_getmodulehandler('osclist', 'oscmembership');
$osclist = $osclist_handler->create();
$osclist->assignVar('id','1');

$classification_result = $osclist_handler->getitems($osclist);
$xoopsTpl->assign('optionitems',$classification_result);


$osclist = $osclist_handler->create();
$osclist->assignVar('id',1);  //pull membership classifications
$optionItems = $osclist_handler->getitems($osclist);

$form = new XoopsThemeForm("", "reportdirectoryform", "DirectoryReport_pdf.php", "post", true);

$class_select = new XoopsFormSelect(_oscmem_dirreport_selectclass,'sDirClassifications',"",5,true, 'class');
foreach($optionItems as $osclist)
{
	$class_select->addOption($osclist['optionid'], $osclist['optionname']);
}

$group_handler = &xoops_getmodulehandler('group', 'oscmembership');
$groups = $group_handler->getarray();

if(array_key_exists('id',$groups))
{
	$group_select = new XoopsFormSelect(_oscmem_dirreport_groupmemb,'GroupID',"",5,true, 'group');
	foreach($groups as $group)
	{

		$group_select->addOption($group['id'], $group['group_Name']);
	}
}

$dirAddress = new XoopsFormCheckBox("","bDirAddress",0);
$dirAddress->addOption(0,_oscmem_address);
$dirWedding = new XoopsFormCheckBox("","bDirWedding",0);
$dirWedding->addOption(0,_oscmem_weddingdate);
$dirBirthday = new XoopsFormCheckBox("","bDirBirthday",0);
$dirBirthday->addOption(0,_oscmem_birthday);
$dirFamilyPhone = new XoopsFormCheckBox("","bDirFamilyPhone",0);
$dirFamilyPhone->addOption(0,_oscmem_familyhomephone);
$dirFamilyWork = new XoopsFormCheckBox("","bDirFamilyWork",0);
$dirFamilyWork->addOption(0,_oscmem_familyworkphone);
$dirFamilyCell = new XoopsFormCheckBox("","bDirFamilyCell",0);
$dirFamilyCell->addOption(0,_oscmem_familycellphone);
$dirFamilyEmail = new XoopsFormCheckBox("","bDirFamilyEmail",0);
$dirFamilyEmail->addOption(0,_oscmem_familyemail);
$dirPersonalPhone = new XoopsFormCheckBox("","bDirPersonalPhone",0);
$dirPersonalPhone->addOption(0,_oscmem_personalphone);
$dirPersonalWork = new XoopsFormCheckBox("","bDirPersonalWork",0);
$dirPersonalWork->addOption(0,_oscmem_personalworkphone);
$dirPersonalCell = new XoopsFormCheckBox("","bDirPersonalCell",0);
$dirPersonalCell->addOption(0,_oscmem_personalcell);
$dirPersonalEmail = new XoopsFormCheckBox("","bDirPersonalEmail",0);
$dirPersonalEmail->addOption(0,_oscmem_personalemail);
$dirPersonalWorkEmail = new XoopsFormCheckBox("","bDirPersonalWorkEmail",0);
$dirPersonalWorkEmail->addOption(0,_oscmem_personalworkemail);


$information_tray = new XoopsFormElementTray(_oscmem_dirreport_infoinclude, '&nbsp;');

$information_tray2 = new XoopsFormElementTray(_oscmem_diroptions, '&nbsp;');

$dirIncludePictures = new XoopsFormCheckBox("","bIncludePictures",0);
$dirIncludePictures->addOption(1,_oscmem_dirincludepictures);

$diraltIndividualOnly = new XoopsFormCheckBox("","baltIndividualOnly",0);
$diraltIndividualOnly->addOption(1,_oscmem_altindividualonly);

$diraltFamilyName = new XoopsFormCheckBox("","baltFamilyName",0);
$diraltFamilyName->addOption(0,_oscmem_altfamilyname);

$diraltFamilyNamedupe = new XoopsFormCheckBox("","baltFamilyNamedupe",0);
$diraltFamilyNamedupe->addOption(0,_oscmem_altfamilynamedupe);

$diraltHeader = new XoopsFormCheckBox("","baltHeader",0);
$diraltHeader->addOption(0,_oscmem_althead);

$information_tray3 = new XoopsFormElementTray(_oscmem_dirsort, '&nbsp;');

$dirSortFirstName = new XoopsFormCheckBox("","bSortFirstName",0);
$dirSortFirstName->addOption(0,_oscmem_orderbyfirstname);

$information_tray4 = new XoopsFormElementTray(_oscmem_titlepagesettings, '&nbsp;');
$diruseTitlePage = new XoopsFormCheckBox("","bDirUseTitlePage",0);
$diruseTitlePage->addOption(0,_oscmem_usetitlepageyn);

$churchname_text = new XoopsFormText(_oscmem_churchname_label, "sChurchName", 30, 50, $churchdetail->getVar('churchname'));

$churchaddress_text = new XoopsFormText(_oscmem_address, "sChurchAddress", 30, 50, $churchdetail->getVar('address1'));

$churchcity_text = new XoopsFormText(_oscmem_city, "sChurchCity", 30, 50, $churchdetail->getVar('city'));

$churchstate_text = new XoopsFormText(_oscmem_state, "sChurchState", 30, 50, $churchdetail->getVar('state'));

$churchpost_text = new XoopsFormText(_oscmem_post, "sChurchZip", 30, 50, $churchdetail->getVar('zip'));

$churchphone_text = new XoopsFormText(_oscmem_phone, "sChurchPhone", 30, 50, $churchdetail->getVar('phone'));


$disclaimer_textarea= new XoopsFormTextArea(_oscmem_disclaimer,"sDirectoryDisclaimer",$churchdetail->getVar('directorydisclaimer'));

$form->addElement($class_select);
$form->setRequired($class_select);
$form->addElement($group_select);
//$form->addElement($role_select);

//$form->addElement($spouserole_select);
//$form->addElement($childrole_select);


$form->addElement($information_tray);
$form->addElement($dirAddress);
$form->addElement($dirBirthday);
$form->addElement($dirWedding);
$form->addElement($dirBirthday);
$form->addElement($dirFamilyPhone);
$form->addElement($dirFamilyWork);
$form->addElement($dirFamilyCell);
$form->addElement($dirFamilyEmail);
$form->addElement($dirPersonalPhone);
$form->addElement($dirPersonalWork);
$form->addElement($dirPersonalCell);
$form->addElement($dirPersonalEmail);
$form->addElement($dirPersonalWorkEmail);
$form->addElement($information_tray2);
$form->addElement($dirIncludePictures);
$form->addElement($diraltIndividualOnly);
$form->addElement($diraltFamilyName);
$form->addElement($diraltFamilyNamedupe);
$form->addElement($information_tray3);
$form->addElement($dirSortFirstName);

$form->addElement($information_tray4);
$form->addElement($diruseTitlePage);
$form->addElement($churchname_text);
$form->addElement($churchaddress_text);
$form->addElement($churchcity_text);
$form->addElement($churchstate_text);
$form->addElement($churchpost_text);
$form->addElement($churchphone_text);
$form->addElement($disclaimer_textarea);

$submit_button = new XoopsFormButton("", "submit", _oscmem_submit, "submit");

$form->addElement($submit_button);

$rform= $form->render();

$xoopsTpl->assign('form',$rform);

/*
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
*/

include(XOOPS_ROOT_PATH."/footer.php");
?>
