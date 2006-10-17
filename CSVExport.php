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


$xoopsTpl->assign('oscmem_lastname',_oscmem_lastname);
$xoopsTpl->assign('oscmem_title',_oscmem_title);
$xoopsTpl->assign('oscmem_firstname',_oscmem_firstname);
$xoopsTpl->assign('oscmem_address',_oscmem_address);
$xoopsTpl->assign('oscmem_city',_oscmem_city);
$xoopsTpl->assign('oscmem_state',_oscmem_state);
$xoopsTpl->assign('oscmem_post',_oscmem_post);
$xoopsTpl->assign('oscmem_country',_oscmem_country);
$xoopsTpl->assign('oscmem_homephone',_oscmem_homephone);
$xoopsTpl->assign('oscmem_workphone',_oscmem_workphone);
$xoopsTpl->assign('oscmem_cellphone',_oscmem_cellphone);
$xoopsTpl->assign('oscmem_email',_oscmem_email);
$xoopsTpl->assign('oscmem_otheremail',_oscmem_otheremail);
$xoopsTpl->assign('oscmem_envelopenumber',_oscmem_otheremail);
$xoopsTpl->assign('oscmem_membershipdate',_oscmem_membershipdate);
$xoopsTpl->assign('oscmem_csv_birthanniversary',_oscmem_csv_birthanniversary);
$xoopsTpl->assign('oscmem_csv_ageyearsmarried',_oscmem_csv_ageyearsmarried);
$xoopsTpl->assign('oscmem_csv_familyrole',_oscmem_csv_familyrole);
$xoopsTpl->assign('oscmem_csv_familyname',_oscmem_csv_familyname);
$xoopsTpl->assign('oscmem_csv_ministry',_oscmem_csv_ministry);
$xoopsTpl->assign('oscmem_cvsexport_infoinclude',_oscmem_cvsexport_infoinclude);

$xoopsTpl->assign('oscmem_cvsexport_customfields',_oscmem_cvsexport_customfields);
$xoopsTpl->assign('oscmem_filters',_oscmem_filters);

//$form->addElement($element_tray);

$submit_button = new XoopsFormButton("", "submit", _oscmem_submit, "submit");

//$form->addElement($submit_button);
$db = &Database::getInstance();
$persondetail_handler = &xoops_getmodulehandler('person', 'oscmembership');

$customFields = $persondetail_handler->getcustompersonFields();

$i=0;
$custfieldarr=array();

while($row = $db->fetchArray($customFields)) 
{
	$custfieldarr[$i]["id"]=$row["custom_Field"];
	$custfieldarr[$i]["name"]=$row["custom_Field"];
	$custfieldarr[$i]["value"]=0;
	$custfieldarr[$i]["field_name"]=$row["custom_Name"];
	$i++;
}

$xoopsTpl->assign('custfieldarr',$custfieldarr);

$xoopsTpl->assign('oscmem_recordstoexport',_oscmem_recordstoexport);
$xoopsTpl->assign('oscmem_fromfilterbelow',_oscmem_fromfilterbelow);
$xoopsTpl->assign('oscmem_fromcart','_oscmem_fromcart');

$xoopsTpl->assign('oscmem_classificationstoexport',_oscmem_classificationstoexport);

$option_array=array();
$osclist = $osclist_handler->create();
if(isset($optionItems))
{
	foreach($optionItems as $osclist)
	{
		$option_array[$osclist['optionid']]['id']=$osclist['optionid'];
		$option_array[$osclist['optionid']]['name']=$osclist['optionname'];
	}
}

$xoopsTpl->assign('option_array', $option_array);

$xoopsTpl->assign('oscmem_rolestoexport',_oscmem_rolestoexport);

// Get Group Types for the drop-down
$sSQL = "SELECT * FROM " . $db->prefix("oscmembership_list") . " WHERE id= 2 ORDER BY optionsequence";

$familyroles=$db->query($sSQL);

$container='';
$roles_array=array();
$i=0;
while($row = $db->fetchArray($familyroles)) 
{
	$roles_array[$i]['id']=$row['optionid'];
	$roles_array[$i]['name']=$row['optionname']
	$i++;
}

$xoopsTpl->assign('roles_array',$roles_array);

$gender_array=array();
$gender_array[0]['id']=0;
$gender_array[0]['name']=_oscmem_gender_nofilter;
$gender_array[1]['id']=1;
$gender_array[1]['name']=_oscmem_male;
$gender_array[2]['id']=2;
$gender_array[2]['name']=_oscmem_female;

$xoopsTpl->assign('oscmem_gender',_oscmem_gender);
$xoopsTpl->assign('gender_array',$gender_array);

$xoopsTpl->assign('oscmem_groupmember',_oscmem_groupmember);

$group_handler = &xoops_getmodulehandler('group', 'oscmembership');
	
$searcharray=array();
$searcharray[0]='';
$result = $group_handler->search($searcharray);

$rowcount=0;
$group=new Group();
$groups_array=array();
while($row = $db->fetchArray($result)) 
{
	$group->assignVars($row);	
	$groups_array[$i]['id']=$group->getVar('id');
	$groups_array[$i]['name']=$group->getVar('group_Name');
	$i++;	
}

$xoopsTpl->assign('oscmem_membershipdate',_oscmem_membershipdate);

$membershipdate_from = new XoopsFormTextDateSelect(_oscmem_filter_from,'memberdatefrom');
$membershipdate_to = new XoopsFormTextDateSelect(_oscmem_filter_to,'memberdateto');

$xoopsTpl->assign('memberdatefrom', $membershipdate_from->render(););
$xoopsTpl->assign('memberdateto',$membershipdate_to->render()); 

$birthday_tray = new XoopsFormElementTray(_oscmem_birthday,"<br>",true );
$birthday_from = new XoopsFormTextDateSelect(_oscmem_filter_from,'birthdayfrom');
$birthday_to = new XoopsFormTextDateSelect(_oscmem_filter_to,'birthdayto');

$birthday_tray->addElement($birthday_from);
$birthday_tray->addElement($birthday_to);

$anniversary_tray = new XoopsFormElementTray(_oscmem_anniversary,"<br>",true );

$ann_from = new XoopsFormTextDateSelect(_oscmem_filter_from,'anniversaryfrom');
$anniversary_tray->addElement($ann_from);

$ann_to=new XoopsFormTextDateSelect(_oscmem_filter_to,'anniversaryto');
$anniversary_tray->addElement($ann_to);

$dateentered_tray = new XoopsFormElementTray(_oscmem_dateentered,"<br>",true );

$dateentered_from = new XoopsFormTextDateSelect(_oscmem_filter_from,'dateenteredfrom');
$dateentered_tray->addElement($dateentered_from);

$dateentered_to=new XoopsFormTextDateSelect(_oscmem_filter_to,'dateenteredto');
$dateentered_tray->addElement($dateentered_to);

$outputmethod = new XoopsFormSelect(_oscmem_csv_outputmethod,'soutputmethod',"",1,false, 'class');

$outputmethod->addOption(_oscmem_csv_individual, _oscmem_csv_individual);
$outputmethod->addOption(_oscmem_csv_combinefamily, _oscmem_csv_combinefamily);
$outputmethod->addOption(_oscmem_csv_addtocart,_oscmem_csv_addtocart);

$chkskipincompleteaddress=new XoopsFormCheckBox("","bincompleteaddress",0);
$chkskipincompleteaddress->addOption(0,_oscmem_csv_skipincompleteaddress);

$chkskipnoenvelope=new XoopsFormCheckBox("","bnoenvelope",0);
$chkskipnoenvelope->addOption(0,_oscmem_csv_skipnoenvelope);

$submit_button = new XoopsFormButton("", "createcsvsubmit", _oscmem_submit, "submit");


$table3->addElement($tableheadper3);
$table3->addElement($filter_select);
$table3->addElement($classification_select);
$table3->addElement($familyrole_select);
$table3->addElement($gender_select);
$table3->addElement($group_select);
$table3->addElement($membershipdate_tray);
$table3->addElement($birthday_tray);
$table3->addElement($anniversary_tray);
$table3->addElement($dateentered_tray);
$table3->addElement($outputmethod);
$table3->addElement($chkskipincompleteaddress);
$table3->addElement($chkskipnoenvelope);
$table3->addElement($submit_button);

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
