<?php
// $Id: persondetailform.php,v 1.1.1.1 2006/03/12 14:57:25 root Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
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
include("../../mainfile.php");
//$xoopsOption['template_main'] = 'cs_index.html';
include(XOOPS_ROOT_PATH."/header.php");
//include("../../../include/cp_header.php");
include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");
include_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php");

// include the default language file for the admin interface
if ( file_exists( "../language/" . $xoopsConfig['language'] . "/main.php" ) ) {
    include "../language/" . $xoopsConfig['language'] . "/main.php";
}
elseif ( file_exists( "../language/english/main.php" ) ) {
    include "../language/english/main.php";
}

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}


$personid = (isset($_GET['id'])) ? intval($_GET['id']) : 0;

if (isset($_GET['id'])) $personid=$_GET['id'];
if (isset($_POST['id'])) $personid=$_POST['id'];

$db = &Database::getInstance();

$myts = &MyTextSanitizer::getInstance();
$persondetail_handler = &xoops_getmodulehandler('person', 'oscmembership');
    
$member_handler =& xoops_gethandler('member');
$acttotal = $member_handler->getUserCount(new Criteria('level', 0, '>'));

$person = $persondetail_handler->get($personid);  //only one record


$customFields = $persondetail_handler->getcustompersonFields();
	
$customfieldata_post="";
$osclist_handler = &xoops_getmodulehandler('osclist', 'oscmembership');

$osclist = $osclist_handler->create();
$id=1;
$osclist->assignVar('id',$id);
$optionItems = $osclist_handler->getitems($osclist);

$option_array=array();

$osclist = $osclist_handler->create();

$option_array[0]=_oscmem_noclass;
if(isset($optionItems))
{
	foreach($optionItems as $osclist)
	{
		$option_array[$osclist['optionid']]=$osclist['optionname'];
	}
}


$firstname_text = new XoopsFormLabel(_oscmem_firstname, $person->getVar('firstname'));
$lastname_text=new XoopsFormLabel(_oscmem_lastname,$person->getVar('lastname'));

$address1_text = new XoopsFormLabel(_oscmem_address, $person->getVar('address1'));
$address2_text = new XoopsFormLabel('', $person->getVar('address2'));
$city_text = new XoopsFormLabel(_oscmem_city, $person->getVar('city'));
$state_text = new XoopsFormLabel(_oscmem_state, $person->getVar('state'));
$post_text = new XoopsFormLabel(_oscmem_post,  $person->getVar('zip'));
$country_text = new XoopsFormLabel(_oscmem_country,  $person->getVar('country'));

$homephone_text = new XoopsFormLabel(_oscmem_homephone, $person->getVar('homephone'));

$workphone_text = new XoopsFormLabel(_oscmem_workphone,  $person->getVar('workphone'));

$cellphone_text = new XoopsFormLabel(_oscmem_cellphone, $person->getVar('cellphone'));

$email_text = new XoopsFormLabel(_oscmem_email,  $person->getVar('email'));

$workemail_text = new XoopsFormLabel(_oscmem_workemail, $person->getVar('workemail'));

$birthmonth_text = new XoopsFormLabel('', $person->getVar('birthmonth'));

$birthday_text = new XoopsFormLabel('', $person->getVar('birthday'));

$birthyear_text = new XoopsFormLabel('', $person->getVar('birthyear'));

$birthday_instructions= new XoopsFormLabel('',_oscmem_birthdayinstructions);

$birth_tray = new XoopsFormElementTray(_oscmem_birthday, '&nbsp;');
$birth_tray->addElement($birthmonth_text);
$birth_tray->addElement($birthday_text);
$birth_tray->addElement($birthyear_text);
$birth_tray->addElement($birthday_instructions);

$membershipdate_dt= new XoopsFormLabel(_oscmem_membershipdate,$person->getVar('membershipdate'));
	
//$membershipdate_text = new XoopsFormText(_oscmem_membershipdate, "membershipdate", 30, 50, $person->getVar('membershipdate'));

$gender_array=array();
$gender_array[1]=_oscmem_male;
$gender_array[2]=_oscmem_female;

$gender_select = new XoopsFormLabel(_oscmem_gender,$gender_array[$person->getVar('gender')]);

$osclist_handler = &xoops_getmodulehandler('osclist', 'oscmembership');

$osclist = $osclist_handler->create();
$id=1;
$osclist->assignVar('id',$id);
$optionItems = $osclist_handler->getitems($osclist);

$option_array=array();

$osclist = $osclist_handler->create();

$option_array[0]=_oscmem_noclass;
if(isset($optionItems))
{
	foreach($optionItems as $osclist)
	{
		$option_array[$osclist['optionid']]=$osclist['optionname'];
	}
}

$memberclass_select = new XoopsFormLabel(_oscmem_memberclass,$option_array[$person->getVar('clsid')]);

$datelastedited_label = new XoopsFormLabel(_oscmem_datelastedited, $person->getVar('datelastedited'));

$user=new XoopsUser();

if($person->getVar('editedby')==0) $person->assignVar('editedby',$person->getVar('enteredby'));

if($person->getVar('editedby')<>'')
{
	$user = $member_handler->getUser($person->getVar('editedby'));
}



// Get Group Types for the drop-down
$sSQL = "SELECT * FROM " . $db->prefix("oscmembership_list") . " WHERE id= 2 and optionid=" . $person->getVar('fmrid') . " ORDER BY optionsequence";

$familyroles=$db->query($sSQL);

$familyrole_name="";

while($row = $db->fetchArray($familyroles)) 
{
	$familyrole_name=$row['optionname'];
}

$familyrole_select = new XoopsFormLabel(_oscmem_familyrole,$familyrole_name);

$container='';

$editedby_label = new XoopsFormLabel(_oscmem_editedby, $user->getVar('uname'));

$dateentered_label = new XoopsFormLabel(_oscmem_dateentered, $person->getVar('dateentered'));

$user = $member_handler->getUser($person->getVar('enteredby'));

$enteredby_label = new XoopsFormLabel(_oscmem_enteredby, $user->getVar('uname'));

$personpicture=new XoopsFormLabel(_oscmem_personpicture,$myts->displayTArea($person->getVar('picloc')));

$envelope_label=new XoopsFormLabel(_oscmem_envelopenumber,$person->getVar('envelope'));

$id_hidden = new XoopsFormHidden("id",$person->getVar('id'));

$op_hidden = new XoopsFormHidden("op", "save");  //save operation
$submit_button = new XoopsFormButton("", "persondetailsubmit", _osc_save, "submit");
$action="";

if($action=="create")
{
	$op_hidden = new XoopsFormHidden("op", "create");  //save operation
	$submit_button = new XoopsFormButton("", "persondetailsubmit", _osc_create, "submit");
}

$form = new XoopsThemeForm(_oscmem_persondetail_TITLE, "persondetailform", "persondetailform.php", "post", true);
$form->addElement($firstname_text);
$form->addElement($lastname_text);
$form->addElement($personpicture);
$form->addElement($address1_text);
$form->addElement($address2_text);
$form->addElement($city_text);
$form->addElement($state_text);
$form->addElement($post_text);

$form->addElement($country_text);
$form->addElement($familyrole_select);
$form->addElement($homephone_text);
$form->addElement($workphone_text);
$form->addElement($cellphone_text);
$form->addElement($email_text);
$form->addElement($workemail_text);
$form->addElement($birth_tray);
$form->addElement($memberclass_select);
$form->addElement($membershipdate_dt);
//$form->addElement($membershipdate_text);
$form->addElement($gender_select);
$form->addElement($datelastedited_label);
$form->addElement($editedby_label);
$form->addElement($dateentered_label);
$form->addElement($enteredby_label);
$form->addElement($envelope_label);


$form->addElement($op_hidden);
$form->addElement($id_hidden);

//Upload stuff

//$form->addElement($submit_button);

//$form->addElement($customfields);


//Retrieve custom fields
//$customfields = new XoopsFormText(_oscmem_cellphone, "customfield", 30, 50, $person->getVar('customfields'));

$customFields = $persondetail_handler->getcustompersonFields();
$customData = explode(",",$person->getVar('customfields'));

$i=1;
while($row = $db->fetchArray($customFields)) 
{
	switch($row["type_ID"])
	{
	case "1": //True false
		switch($customData[$i])
		{
		case "true":
			$form->addElement(new XoopsFormRadioYN($row["custom_Name"],$row["custom_Field"], 1));
			break;		
		case "false":
			$form->addElement(new XoopsFormRadioYN($row["custom_Name"],$row["custom_Field"], 0));
			break;
		}
	
		break;
	case "2": //Date
		$date_label = new XoopsFormLabel($row["custom_Name"], $customData[$i]);
		$form->addElement($date_label);
		break;
			
	case "3":
		$label_3 = new XoopsFormLabel($row["custom_Name"], $customData[$i]);
		$form->addElement($label_3);
		break;

	case "4":
		$label_4 = new XoopsFormLabel($row["custom_Name"], $customData[$i]);
		$form->addElement($label_4);
		break;
		
	case "5":
		$label_5 = new XoopsFormLabel($row["custom_Name"], $customData[$i]);
		$form->addElement($label_5);
		break;
		
	case "6": //year
	case "8": //number
		$label_8 = new XoopsFormLabel($row["custom_Name"], $customData[$i]);
		break;

	case "7":  //season
	
		$season = new XoopsFormLabel(_oscmem_season_select, $customData[$i]);

		$form->addElement($season);
		break;
	}
	
//	$form->addElement(new XoopsFormText($row["custom_Name"],$row["custom_Field"], 30, 50,$customData[$row["custom_Field"]]));
	
	$i++;
}

//xoops_cp_header();
$form->display();

//xoops_cp_footer();
include(XOOPS_ROOT_PATH."/footer.php");

?>