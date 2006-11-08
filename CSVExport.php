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
$xoopsTpl->assign('oscmem_membername',_oscmem_name);
$xoopsTpl->assign('oscmem_address',_oscmem_address);
$xoopsTpl->assign('oscmem_homephone',_oscmem_homephone);
$xoopsTpl->assign('oscmem_workphone',_oscmem_workphone);
$xoopsTpl->assign('oscmem_cellphone',_oscmem_cellphone);
$xoopsTpl->assign('oscmem_email',_oscmem_email);
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
	$roles_array[$i]['name']=$row['optionname'];
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

$xoopsTpl->assign('memberdatefrom', $membershipdate_from->render());
$xoopsTpl->assign('memberdateto',$membershipdate_to->render()); 

$xoopsTpl->assign('oscmem_birthday',_oscmem_birthday);
$birthday_from = new XoopsFormTextDateSelect(_oscmem_filter_from,'birthdayfrom');
$birthday_to = new XoopsFormTextDateSelect(_oscmem_filter_to,'birthdayto');

$xoopsTpl->assign('birthdayfrom',$birthday_from->render());
$xoopsTpl->assign('birthdayto',$birthday_to->render());

$xoopsTpl->assign('oscmem_anniversary',_oscmem_anniversary);

$ann_from = new XoopsFormTextDateSelect(_oscmem_filter_from,'anniversaryfrom');
$ann_to=new XoopsFormTextDateSelect(_oscmem_filter_to,'anniversaryto');
$xoopsTpl->assign('ann_from',$ann_from->render());
$xoopsTpl->assign('ann_to',$ann_to->render());

$xoopsTpl->assign('oscmem_dateentered',_oscmem_dateentered);

$dateentered_from = new XoopsFormTextDateSelect(_oscmem_filter_from,'dateenteredfrom');
$dateentered_to=new XoopsFormTextDateSelect(_oscmem_filter_to,'dateenteredto');

$xoopsTpl->assign('dateentered_from',$dateentered_from->render());
$xoopsTpl->assign('dateentered_to',$dateentered_to->render());

$outputmethod = new XoopsFormSelect(_oscmem_csv_outputmethod,'soutputmethod',"",1,false, 'class');

$outputmethod->addOption(_oscmem_csv_individual, _oscmem_csv_individual);
$outputmethod->addOption(_oscmem_csv_combinefamily, _oscmem_csv_combinefamily);
$outputmethod->addOption(_oscmem_csv_addtocart,_oscmem_csv_addtocart);

$xoopsTpl->assign('oscmem_csv_outputmethod',_oscmem_csv_outputmethod);
$xoopsTpl->assign('outputmethod',$outputmethod->render());

$chkskipincompleteaddress=new XoopsFormCheckBox("","bincompleteaddress",0);
$chkskipincompleteaddress->addOption(0,_oscmem_csv_skipincompleteaddress);
$xoopsTpl->assign('oscmem_csv_skipincompleteaddress');
$xoopsTpl->assign('skipincompleteaddress',$chkskipincompleteaddress->render());

$chkskipnoenvelope=new XoopsFormCheckBox("","bnoenvelope",0);
$chkskipnoenvelope->addOption(0,_oscmem_csv_skipnoenvelope);
$xoopsTpl->assign('envelope',$chkskipnoenvelope->render());

$submit_button = new XoopsFormButton("", "createcsvsubmit", _oscmem_submit, "submit");

$xoopsTpl->assign('submit',$submit_button->render());

//$rform= $form->render();

//$xoopsTpl->assign('form',$rform);


include(XOOPS_ROOT_PATH."/footer.php");
?>
