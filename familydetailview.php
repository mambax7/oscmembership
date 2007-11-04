<?php
// $Id: familydetailview.php,v 1.1.1.1 2006/03/12 14:57:25 root Exp $
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
include(XOOPS_ROOT_PATH."/header.php");
//include("../../../include/cp_header.php");
include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");
include_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/class/person.php");

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


if(!hasPerm("oscmembership_view",$xoopsUser))     redirect_header(XOOPS_URL, 3, _oscmem_accessdenied);

//determine action
$op = '';
$confirm = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];
if (isset($_GET['id'])) $familyid=$_GET['id'];
if (isset($_POST['id'])) $familyid=$_POST['id'];
if (isset($_POST['action'])) $action=$_POST['action'];
if (isset($_GET['action'])) $action=$_GET['action'];

if (isset($_POST['removemember'])) $op="remove";
if (isset($_POST['removeid'])) $removeid=$_POST['removid'];
if (isset($_GET['removeid'])) $removeid=$_GET['removeid'];

$myts = &MyTextSanitizer::getInstance();
$familydetail_handler = &xoops_getmodulehandler('family', 'oscmembership');
    
    
    if(isset($familyid))
    {
	$family = $familydetail_handler->get($familyid);  //only one     
    }
    else
    {
	$family = $familydetail_handler->create();    	
	
    }


$familyname_text = new XoopsFormLabel(_oscmem_familyname, $family->getVar('familyname'));

$address1_text = new XoopsFormLabel(_oscmem_address, $family->getVar('address1'));
$address2_text = new XoopsFormLabel('', $family->getVar('address2'));
$city_text = new XoopsFormLabel(_oscmem_city, $family->getVar('city'));
$state_text = new XoopsFormLabel(_oscmem_state, $family->getVar('state'));
$post_text = new XoopsFormLabel(_oscmem_post, $family->getVar('zip'));
$country_text = new XoopsFormLabel(_oscmem_country, $family->getVar('country'));

$homephone_text = new XoopsFormLabel(_oscmem_homephone, $family->getVar('homephone'));

$workphone_text = new XoopsFormLabel(_oscmem_workphone, $family->getVar('workphone'));

$cellphone_text = new XoopsFormLabel(_oscmem_cellphone, $family->getVar('cellphone'));

$email_text = new XoopsFormLabel(_oscmem_email,$family->getVar('email'));

$weddingdate_dt= new XoopsFormLabel(_oscmem_weddingdate,$family->getVar('weddingdate'));

$datelastedited_label = new XoopsFormLabel(_oscmem_datelastedited, $family->getVar('datelastedited'));

$user=new XoopsUser();

if($family->getVar('editedby')<>'')
{
	$user = $member_handler->getUser($family->getVar('editedby'));
}

$editedby_label = new XoopsFormLabel(_oscmem_editedby, $user->getVar('uname'));

$dateentered_label = new XoopsFormLabel(_oscmem_dateentered, $family->getVar('dateentered'));

$user=new XoopsUser();
if($family->getVar('enteredby')<>'')
{
	$user = $member_handler->getUser($family->getVar('enteredby'));
}

$enteredby_label = new XoopsFormLabel(_oscmem_enteredby,
 $user->getVar('uname'));

$personpicture=new XoopsFormLabel(_oscmem_personpicture,$myts->displayTArea($family->getVar('picloc')));

$id_hidden = new XoopsFormHidden("id",$family->getVar('id'));

$removeid_hidden = new XoopsFormHidden("removeid",'');

$op_hidden = new XoopsFormHidden("op", "save");  //save operation

$form = new XoopsThemeForm(_oscmem_familydetail_TITLE, "familydetailform", "familydetailform.php", "post", true);
$form->addElement($familyname_text);
$form->addElement($personpicture);
$form->addElement($address1_text);
$form->addElement($address2_text);
$form->addElement($city_text);
$form->addElement($state_text);
$form->addElement($post_text);

$form->addElement($country_text);
$form->addElement($homephone_text);
$form->addElement($workphone_text);
$form->addElement($cellphone_text);
$form->addElement($email_text);
$form->addElement($weddingdate_dt);
$form->addElement($datelastedited_label);
$form->addElement($editedby_label);
$form->addElement($dateentered_label);
$form->addElement($enteredby_label);

$form->addElement($removeid_hidden);
$form->addElement($op_hidden);

//Extract Family Members
$result = $familydetail_handler->getmembers($family);

$db = &Database::getInstance();

$person=new Person();
$member_tray = new XoopsFormElementTray('', '&nbsp;');

$memberresult="<table><th>" . _oscmem_lastname . "</th><th>" . _oscmem_firstname . "</th><th>" . _oscmem_actions . "</th>";

$len_memberresult=strlen($memberresult);
$rowcount=0;

while($row = $db->fetchArray($result)) 
{
	$rowcount++;
	
	$person->assignVars($row);
	$memberresult .= "<tr><td>" . $person->getVar('lastname') . "</td>";
	$memberresult .= "<td>" . $person->getVar('firstname') . "</td>";
	$memberresult .= "<td>";
	$memberresult .= "</td></tr>";
	
}
if($rowcount==0)
{
	$memberresult .= "<tr><td>" . _oscmem_nomembers . "</td></tr></table>" ;
	}
else
{
	$memberresult .= "</table>";
}


$member_label = new XoopsFormLabel(_oscmem_familymember, $memberresult);
$form->addElement($member_label);

$form->addElement($id_hidden);

//Upload stuff

$form->addElement($submit_button);
$form->setRequired($familyname_text);

//xoops_cp_header();
$form->display();

//xoops_cp_footer();
include(XOOPS_ROOT_PATH."/footer.php");

?>

