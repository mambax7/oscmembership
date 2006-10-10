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

$form = new XoopsThemeForm("", "csvexportform", "CSVCreateFile.php", "post", true);

$table1 = new XoopsTableForm("", "csvexporttable", "", "post", true);
$table2 = new XoopsTableForm("", "csvexporttable", "", "post", true);
$table3 = new XoopsTableForm("", "csvexporttable", "", "post", true);

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
$chkcellphone=new XoopsFormCheckBox("","bcellphone",0);
$chkcellphone->addOption(0,_oscmem_cellphone);
$chkemail=new XoopsFormCheckBox("","bemail",0);
$chkemail->addOption(0,_oscmem_email);
$chkotheremail=new XoopsFormCheckBox("","otheremail",0);
$chkotheremail->addOption(0,_oscmem_otheremail);
$chkenvelope=new XoopsFormCheckBox("","benvelope",0);
$chkenvelope->addOption(0,_oscmem_envelopenumber);
$chkmembership=new XoopsFormCheckBox("","bmembershipdate",0);
$chkmembership->addOption(0,_oscmem_membershipdate);
$chkbirth=new XoopsFormCheckBox("","bbirthanniversary",0);
$chkbirth->addOption(0,_oscmem_csv_birthanniversary);
$chkagemarriage=new XoopsFormCheckBox("","bagemarried",0);
$chkagemarriage->addOption(0,_oscmem_csv_ageyearsmarried);
$chkfamilyrole=new XoopsFormCheckBox("","bfamilyrole",0);
$chkfamilyrole->addOption(0,_oscmem_csv_familyrole);
$chkfamilyname=new XoopsFormCheckBox("","bfamilyname",0);
$chkfamilyname->addOption(0,_oscmem_csv_familyname);

$chkministry=new XoopsFormCheckBox("","bministry",0);
$chkministry->addOption(0,_oscmem_csv_ministry);


//$form->addElement($lblLastName);
//$form->setRequired($class_select);
//$form->addElement($role_select);

//$form->addElement($spouserole_select);
//$form->addElement($childrole_select);

$element_tray = new XoopsFormElementTray(_oscmem_cvsexport_infoinclude, '&nbsp;');

$element_tray2 = new XoopsFormElementTray("Custom Fields", '&nbsp;');

$element_tray3 = new XoopsFormElementTray(_oscmem_filters, '&nbsp;');

$table1->addElement($element_tray);
$table1->addElement($chkTitle);
$table1->addElement($chkFirstName);
$table1->addElement($chkTitle);
$table1->addElement($chkFirstName);
//$table1->addElement($chkMiddlename);
//$table1->addElement($chkSuffix);
$table1->addElement($chkAddress1);
$table1->addElement($chkCity);
$table1->addElement($chkState);
$table1->addElement($chkPost);
$table1->addElement($chkCountry);
$table1->addElement($chkhomephone);
$table1->addElement($chkworkphone);

$table1->addElement($chkcellphone);
$table1->addElement($chkemail);
$table1->addElement($chkotheremail);
$table1->addElement($chkenvelope);
$table1->addElement($chkmembership);
$table1->addElement($chkbirth);
$table1->addElement($chkagemarriage);
$table1->addElement($chkfamilyrole);
$table1->addElement($chkfamilyname);

//$form->addElement($element_tray);

$submit_button = new XoopsFormButton("", "submit", _oscmem_submit, "submit");

//$form->addElement($submit_button);
$db = &Database::getInstance();
$persondetail_handler = &xoops_getmodulehandler('person', 'oscmembership');

$customFields = $persondetail_handler->getcustompersonFields();

$i=1;
$table2->addElement($element_tray2);
while($row = $db->fetchArray($customFields)) 
{
	$custfield=new XoopsFormCheckBox("",$row["custom_Field"],0);
	$custfield->addOption(0,$row["custom_Name"]);

	$table2->addElement($custfield);
	$i++;
}

//Table 3
$filter_select = new XoopsFormSelect(_oscmem_recordstoexport,'sfilters',"",5,true, 'class');

$classification_select = new XoopsFormSelect(_oscmem_classificationstoexport,'sclassifications',"",5,true, 'class');

$table3->addElement($element_tray3);
$table3->addElement($filter_select);
$table3->addElement($classification_select);

$rtray1=$table1->render();
$rtray2=$table2->render();
$rtray3=$table3->render();

//$rform= $form->render();

$xoopsTpl->assign('col1',$rtray1);
$xoopsTpl->assign('col2',$rtray2);
$xoopsTpl->assign('col3',$rtray3);
//$xoopsTpl->assign('form',$rform);


include(XOOPS_ROOT_PATH."/footer.php");
?>
