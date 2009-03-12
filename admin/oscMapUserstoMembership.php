<?php
// $Id: perm.php,v 2006/11/21 srmcatee Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2006 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
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
//  ------------------------------------------------------------------------ /
include '../../../include/cp_header.php';
include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';

include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");
include_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php");

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

//verify permission
if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    redirect_header(XOOPS_URL, 3, _oscmem_accessdenied);
}

if(hasPerm("oscmembership_modify",$xoopsUser)) $ispermmodify=true;

if(!$ispermmodify) redirect_header(XOOPS_URL, 3, _oscmem_accessdenied);



xoops_cp_header();


// include the default language file for the admin interface
if ( file_exists( "../language/" . $xoopsConfig['language'] . "/main.php" ) ) {
    include "../language/" . $xoopsConfig['language'] . "/main.php";
}
elseif ( file_exists( "../language/english/main.php" ) ) {
    include "../language/english/main.php";
}

if (file_exists(XOOPS_ROOT_PATH. "/modules/" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) {
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/modinfo.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/modinfo.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/modinfo.php";

}

if (file_exists(XOOPS_ROOT_PATH. "/modules/" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/admin.php")) {
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/admin.php";
}


//$GLOBALS['xoopsOption']['template_main'] ="oscMapUserstoMembership_step1.html";

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/person.php';
include_once XOOPS_ROOT_PATH . '/modules/oscmembership/include/functions.php';


$searcharray=array();
$fieldcount=8;
$fieldvalues=array();
$membervalues=array();
$profile_handler =& xoops_getmodulehandler('profile','smartprofile');

$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
$person=$person_handler->create();
$oscmemvars=$person->getVars();
$oscmem_person_keys=array_keys($oscmemvars);

if(isset($_POST['mapusers'])) $submit=$_POST['mapusers'];
if(isset($submit))
{
	switch($submit)
	{
		
	case _oscmem_submit: 
		//call add cart

		//find all users
		$oscmem_criteria = new CriteriaCompo(new Criteria('email', 'null', "<>"));
		$oscmem_vars=array();
		$oscmem_vars[]='email';

		$start=0;
		$limit=50;
		$order="ASC";
		$sort='uname';
		$oscmem_criteria->setSort($sort);
		$oscmem_criteria->setOrder($order);
		$oscmem_criteria->setLimit($limit);
		$oscmem_criteria->setStart($start);
		$groups=array();
		$foundusers =& $member_handler->getUsersByGroupLink($groups, $criteria, true);

		//print_r($foundusers);

		$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
		$person=$person_handler->create();

		$osc_searcharray=array();

		foreach($foundusers as $founduser)
		{
//			echo $founduser->getVar('email') . "<br>";

			for($i=0;$i<$fieldcount;$i++)
			{
				if(isset($_POST['field' . $i])) $fieldvalues[$i]=$_POST['field' . $i];


				if($_POST['member' . $i]!=0)
				{
					echo $oscmem_person_keys[$_POST['member' . $i]];

/*
					$membervalues[$i]=$_POST['member' . $i];

					$osc_searcharray[0]=$founduser->getVar('email');
					$personupdate=$person_handler->search3($osc_searcharray,'');

				//print_r($personupdate[0]);
				//echo "id" . $personupdate[0]->getVar('email');
					$personupdate->setVar($membervalues[$i],$founduser->getVar($fieldvalues[$i]));

					echo "xx".  $personupdate->getVar($membervalues[$i]);
*/
				}

				//$person_handler->update($personupdate);

				

			}

		}


		//redirect_header("index.php", 3, _oscmem_addedtocart);
		break;
	}
}


//$profile_handler =& xoops_getmodulehandler('smartprofile','profile');

//$profile_handler->search(null,null);



// Get smart profile fields
$fields =& $profile_handler->loadFields();
$oscfieldnames=array_keys($fields);

//get user fields
//$oscuser=$oscmem_users[0][1];
//$oscuserarr=$oscuser->getVars();

//join smart and user fields
//$oscfieldnames +=array_keys($oscuserarr);



$myts = &MyTextSanitizer::getInstance();

$oscmem_person_keys=array_keys($oscmemvars);
$oscmem_person_keys[0]=_oscmem_map_nomap;

$oscfieldnames[0]=_oscmem_map_nomap;
$field_select1 = new XoopsFormSelect(null,'field1');
$field_select1->addOptionArray($oscfieldnames);

$oscmembers_select1 = new XoopsFormSelect(null,'member1');
$oscmembers_select1->addOptionArray($oscmem_person_keys);

$osc_tray1 = new XoopsFormElementTray(_oscmem_oscmap_field1,"&nbsp;<large>---></large>&nbsp;");
$osc_tray1->addElement($field_select1);
$osc_tray1->addElement($oscmembers_select1);

$field_select2 = new XoopsFormSelect(null,'field2');
$field_select2->addOptionArray($oscfieldnames);
$oscmembers_select2 = new XoopsFormSelect(null,'member2');
$oscmembers_select2->addOptionArray($oscmem_person_keys);
$osc_tray2 = new XoopsFormElementTray(_oscmem_oscmap_field2,"&nbsp;<large>---></large>&nbsp;");
$osc_tray2->addElement($field_select2);
$osc_tray2->addElement($oscmembers_select2);


$field_select3 = new XoopsFormSelect(null,'field3');
$field_select3->addOptionArray($oscfieldnames);
$oscmembers_select3 = new XoopsFormSelect(null,'member3');
$oscmembers_select3->addOptionArray($oscmem_person_keys);
$osc_tray3 = new XoopsFormElementTray(_oscmem_oscmap_field1,"&nbsp;<large>---></large>&nbsp;");
$osc_tray3->addElement($field_select3);
$osc_tray3->addElement($oscmembers_select3);

$field_select4 = new XoopsFormSelect(null,'field4');
$field_select4->addOptionArray($oscfieldnames);
$oscmembers_select4 = new XoopsFormSelect(null,'member4');
$oscmembers_select4->addOptionArray($oscmem_person_keys);
$osc_tray4 = new XoopsFormElementTray(_oscmem_oscmap_field1,"&nbsp;<large>---></large>&nbsp;");
$osc_tray4->addElement($field_select4);
$osc_tray4->addElement($oscmembers_select4);

$field_select5 = new XoopsFormSelect(null,'field5');
$field_select5->addOptionArray($oscfieldnames);
$oscmembers_select5 = new XoopsFormSelect(null,'member5');
$oscmembers_select5->addOptionArray($oscmem_person_keys);
$osc_tray5 = new XoopsFormElementTray(_oscmem_oscmap_field5,"&nbsp;<large>---></large>&nbsp;");
$osc_tray5->addElement($field_select5);
$osc_tray5->addElement($oscmembers_select5);

$field_select6 = new XoopsFormSelect(null,'field6');
$field_select6->addOptionArray($oscfieldnames);
$oscmembers_select6 = new XoopsFormSelect(null,'member6');
$oscmembers_select6->addOptionArray($oscmem_person_keys);
$osc_tray6 = new XoopsFormElementTray(_oscmem_oscmap_field6,"&nbsp;<large>---></large>&nbsp;");
$osc_tray6->addElement($field_select6);
$osc_tray6->addElement($oscmembers_select6);

$field_select7 = new XoopsFormSelect(null,'field7');
$field_select7->addOptionArray($oscfieldnames);
$oscmembers_select7 = new XoopsFormSelect(null,'member7');
$oscmembers_select7->addOptionArray($oscmem_person_keys);
$osc_tray7 = new XoopsFormElementTray(_oscmem_oscmap_field7,"&nbsp;<large>---></large>&nbsp;");
$osc_tray7->addElement($field_select7);
$osc_tray7->addElement($oscmembers_select7);

$field_select8 = new XoopsFormSelect(null,'field8');
$field_select8->addOptionArray($oscfieldnames);
$oscmembers_select8 = new XoopsFormSelect(null,'member8');
$oscmembers_select8->addOptionArray($oscmem_person_keys);
$osc_tray8 = new XoopsFormElementTray(_oscmem_oscmap_field8,"&nbsp;<large>---></large>&nbsp;");
$osc_tray8->addElement($field_select8);
$osc_tray8->addElement($oscmembers_select8);

$submit_button = new XoopsFormButton("", "mapusers", _oscmem_submit, "submit");

$form = new XoopsThemeForm(_oscmem_oscmapusers_TITLE, "oscmapusers", "oscMapUserstoMembership.php", "post", true);

$form->addElement($osc_tray1);
$form->addElement($osc_tray2);
$form->addElement($osc_tray3);
$form->addElement($osc_tray4);
$form->addElement($osc_tray5);
$form->addElement($osc_tray6);
$form->addElement($osc_tray7);
$form->addElement($osc_tray8);
$form->addElement($submit_button);
$form->addElement($blank_tray);


//Create Sample Data
//print_r($oscmem_users[1][1]);
$oscmem_criteria = new CriteriaCompo(new Criteria('email', 'null', "<>"));
$oscmem_vars=array();
$oscmem_vars[]='email';
$oscmem_users=$profile_handler->search($oscmem_criteria,$oscmem_vars);

$user_smart=$oscmem_users[1][1];
$vars=$user_smart->getVars();
$i=0;
$osclabel=array();

$thisUser =& $xoopsUser;
$fields =& $profile_handler->loadFields();
$profile =& $profile_handler->get($thisUser->getVar('uid'));

foreach (array_keys($fields) as $i) 
{
	
	$osclabel[$i]=new XoopsFormLabel($i,$profile->getVar($i));
	$form->addElement($osclabel[$i]);
}


$form->display();


xoops_cp_footer();




?>