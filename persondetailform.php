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

/*
if (file_exists(XOOPS_ROOT_PATH. "/modules" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) 
{
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/modinfo.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/modinfo.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/modinfo.php";

}
*/
//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}


//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit("Access Denied");
}


//determine action
$op = '';
$action='';
$confirm = '';
$personid = (isset($_GET['id'])) ? intval($_GET['id']) : 0;

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];
if (isset($_GET['id'])) $personid=$_GET['id'];
if (isset($_POST['id'])) $personid=$_POST['id'];
if (isset($_POST['action'])) $action=$_POST['action'];
if (isset($_GET['action'])) $action=$_GET['action'];


$myts = &MyTextSanitizer::getInstance();
$persondetail_handler = &xoops_getmodulehandler('person', 'oscmembership');
    
$member_handler =& xoops_gethandler('member');
$acttotal = $member_handler->getUserCount(new Criteria('level', 0, '>'));

$person = $persondetail_handler->get($personid);  //only one record

switch (true) 
{
    case ($op=="save" || $op=="create"):
    	if(isset($_POST['lastname'])) $person->assignVar('lastname',$_POST['lastname']);

	if(isset($_POST['firstname'])) $person->assignVar('firstname',$_POST['firstname']);
    
	if(isset($_POST['address1']))
	$person->assignVar('address1',$_POST['address1']);
    
	if(isset($_POST['address2']))
	$person->assignVar('address2',$_POST['address2']);
	
	if(isset($_POST['city'])) $person->assignVar('city',$_POST['city']);

	if(isset($_POST['state'])) $person->assignVar('state',$_POST['state']);
    
	if(isset($_POST['zip'])) $person->assignVar('post',$_POST['post']);

	if(isset($_POST['country'])) $person->assignVar('country',$_POST['country']);
	
	if(isset($_POST['homephone'])) $person->assignVar('homephone',$_POST['homephone']);

	if(isset($_POST['workphone'])) $person->assignVar('workphone',$_POST['workphone']);

	if(isset($_POST['cellphone'])) $person->assignVar('cellphone',$_POST['cellphone']);

	if(isset($_POST['email'])) $person->assignVar('email',$_POST['email']);
	
	if(isset($_POST['workemail'])) $person->assignVar('workemail',$_POST['workemail']);

	if(isset($_POST['birthmonth'])) $person->assignVar('birthmonth',$_POST['birthmonth']);

	if(isset($_POST['birthday'])) $person->assignVar('birthday',$_POST['birthday']);

	if(isset($_POST['birthyear'])) $person->assignVar('birthyear',$_POST['birthyear']);

	if(isset($_POST['memberclass'])) $person->assignVar('clsid',$_POST['memberclass']);

	if(isset($_POST['membershipdate'])) $person->assignVar('membershipdate',$_POST['membershipdate']);

	if(isset($_POST['gender'])) $person->assignVar('gender',$_POST['gender']);

	$person->assignVar('datelastedited',date('y-m-d g:i:s'));
	$person->assignVar('editedby',$xoopsUser->getVar('uid'));	

	if(isset($_POST['fmrid']))
	{
		if($_POST['fmrid']>0)
		{
			$person->assignVar('fmrid',$_POST['fmrid']);
		}
		else
		if($_POST['fmrid']=0 || $_POST['fmrid']=-1)
		{
			$person->assignVar('fmrid',-1);
		}
	}
	
	
	if($op=="save")
	{
		$persondetail_handler->update($person);
		$message=_oscmem_UPDATESUCCESS;
	}
	if($op=="create")
	{
		$person->assignVar('dateentered',date('y-m-d g:i:s'));
		$person->assignVar('enteredby',$xoopsUser->getVar('uid'));
		$personid = $persondetail_handler->insert($person);	
		$message=_oscmem_CREATESUCCESS_individual;
	}
	    
	redirect_header("persondetailform.php?id=" . $personid, 3, $message);
    break;
}

$osclist_handler = &xoops_getmodulehandler('osclist', 'oscmembership');

$osclist = $osclist_handler->create();
$id=1;
$osclist->assignVar('id',$id);
$optionItems = $osclist_handler->getitems($osclist);

$option_array=array();
$db = &Database::getInstance();

$osclist = $osclist_handler->create();

if(isset($optionItems))
{
	foreach($optionItems as $osclist)
	{
		$option_array[$osclist['optionid']]=$osclist['optionname'];
	}
}


$firstname_text = new XoopsFormText(_oscmem_firstname, "firstname", 30, 50, $person->getVar('firstname'));
$lastname_text=new XoopsFormText(_oscmem_lastname,"lastname",30,50,$person->getVar('lastname'));

$address1_text = new XoopsFormText(_oscmem_address, "address1", 30, 50, $person->getVar('address1'));
$address2_text = new XoopsFormText('', "address2", 30, 50, $person->getVar('address2'));
$city_text = new XoopsFormText(_oscmem_city, "city", 30, 50, $person->getVar('city'));
$state_text = new XoopsFormText(_oscmem_state, "state", 30, 50, $person->getVar('state'));
$post_text = new XoopsFormText(_oscmem_post, "post", 30, 50, $person->getVar('zip'));
$country_text = new XoopsFormText(_oscmem_country, "country", 30, 50, $person->getVar('country'));

$homephone_text = new XoopsFormText(_oscmem_homephone, "homephone", 30, 50, $person->getVar('homephone'));

$workphone_text = new XoopsFormText(_oscmem_workphone, "workphone", 30, 50, $person->getVar('workphone'));

$cellphone_text = new XoopsFormText(_oscmem_cellphone, "cellphone", 30, 50, $person->getVar('cellphone'));

$email_text = new XoopsFormText(_oscmem_email, "email", 30, 50, $person->getVar('email'));

$workemail_text = new XoopsFormText(_oscmem_workemail, "workemail", 30, 50, $person->getVar('workemail'));

$birthmonth_text = new XoopsFormText('', "birthmonth", 2, 2, $person->getVar('birthmonth'));

$birthday_text = new XoopsFormText('', "birthday", 2, 2, $person->getVar('birthday'));

$birthyear_text = new XoopsFormText('', "birthyear", 4, 4, $person->getVar('birthyear'));

$birthday_instructions= new XoopsFormLabel('',_oscmem_birthdayinstructions);

$birth_tray = new XoopsFormElementTray(_oscmem_birthday, '&nbsp;');
$birth_tray->addElement($birthmonth_text);
$birth_tray->addElement($birthday_text);
$birth_tray->addElement($birthyear_text);
$birth_tray->addElement($birthday_instructions);

$membershipdate_text = new XoopsFormText(_oscmem_membershipdate, "membershipdate", 30, 50, $person->getVar('membershipdate'));

$gender_array=array();
$gender_array[0]=_oscmem_male;
$gender_array[1]=_oscmem_female;

$gender_select = new XoopsFormSelect(_oscmem_gender,'gender',$person->getVar('gender'),1,false, 'gender');
$gender_select->addOptionArray($gender_array);

$memberclass_select = new XoopsFormSelect(_oscmem_memberclass,'memberclass',$person->getVar('clsid'),1,false, 'memberclass');

$memberclass_select->addOptionArray($option_array);


$datelastedited_label = new XoopsFormLabel(_oscmem_datelastedited, $person->getVar('datelastedited'));

$user=new XoopsUser();

if($person->getVar('editedby')<>'')
{
	$user = $member_handler->getUser($person->getVar('editedby'));
}

$familyrole_select = new XoopsFormSelect(_oscmem_familyrole,"fmrid");

$db = &Database::getInstance();

// Get Group Types for the drop-down
$sSQL = "SELECT * FROM " . $db->prefix("oscmembership_list") . " WHERE id= 2 ORDER BY optionsequence";

$familyroles=$db->query($sSQL);

$container='';
$familyrole_select->addOption('-1',_oscmem_unassigned);
$familyrole_select->addOption('0','----------------');
while($row = $db->fetchArray($familyroles)) 
{
	$familyrole_select->addOption($row['optionid'],$row['optionname']);
}

if($person->getVar('fmrid')>1)
$familyrole_select->setValue($person->getVar('fmrid'));

$editedby_label = new XoopsFormLabel(_oscmem_editedby, $user->getVar('uname'));

$dateentered_label = new XoopsFormLabel(_oscmem_dateentered, $person->getVar('dateentered'));

$user = $member_handler->getUser($person->getVar('enteredby'));

$enteredby_label = new XoopsFormLabel(_oscmem_enteredby, $user->getVar('uname'));

$id_hidden = new XoopsFormHidden("id",$person->getVar('id'));

$op_hidden = new XoopsFormHidden("op", "save");  //save operation
$submit_button = new XoopsFormButton("", "persondetailsubmit", _osc_save, "submit");

if($action=="create")
{
	$op_hidden = new XoopsFormHidden("op", "create");  //save operation
	$submit_button = new XoopsFormButton("", "persondetailsubmit", _osc_create, "submit");
}

$form = new XoopsThemeForm(_oscmem_persondetail_TITLE, "persondetailform", "persondetailform.php", "post", true);
$form->addElement($firstname_text);
$form->addElement($lastname_text);
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
$form->addElement($membershipdate_text);
$form->addElement($gender_select);
$form->addElement($datelastedited_label);
$form->addElement($editedby_label);
$form->addElement($dateentered_label);
$form->addElement($enteredby_label);


$form->addElement($op_hidden);
$form->addElement($id_hidden);

//Upload stuff

$form->addElement($submit_button);
$form->setRequired($lastname_text);
$form->setRequired($firstname_text);

$db = &Database::getInstance();

//Retrieve custom fields
$customFields = $persondetail_handler->getcustompersonFields();
$customData = $persondetail_handler->getcustompersonData($person->getVar('id'));

while($row = $db->fetchArray($customFields)) 
{
	switch($row["type_ID"])
	{
	case "1": //True false
		$form->addElement(new XoopsFormRadioYN($row["custom_Name"],$row["custom_Field"], $customData[$row["custom_Field"]]));
	
		break;
	case "2": //Date
		$form->addElement(new XoopsFormTextDateSelect($row["custom_Name"],$row["custom_Field"], 10,$customData[$row["custom_Field"]]));
		break;
			
	case "3":
		$form->addElement(new XoopsFormText($row["custom_Name"],$row["custom_Field"], 50, 50,$customData[$row["custom_Field"]]));
		break;

	case "4":
		$form->addElement(new XoopsFormText($row["custom_Name"],$row["custom_Field"], 100, 100,$customData[$row["custom_Field"]]));
		break;
		
	case "5":
		$form->addElement(new XoopsFormText($row["custom_Name"],$row["custom_Field"], 200, 200,$customData[$row["custom_Field"]]));
		break;
		
	case "6": //year
	case "8": //number
		$form->addElement(new XoopsFormText($row["custom_Name"],$row["custom_Field"], 10, 10,$customData[$row["custom_Field"]]));
		break;

	case "7":  //season
		$season=new XoopsFormSelect($row["custom_Name"],$row["custom_Field"], 1, false,$customData[$row["custom_Field"]]);
		
		$season->addOption("select season",0);
		$season->addOption("-------------",0);
		$season->addOption("spring");
		$season->addOption("fall");
		$form->addElement($season);
		break;

	}
	
	$form->addElement(new XoopsFormText($row["custom_Name"],$row["custom_Field"], 30, 50,$customData[$row["custom_Field"]]));
	
}


//xoops_cp_header();
$form->display();

//xoops_cp_footer();
include(XOOPS_ROOT_PATH."/footer.php");

?>