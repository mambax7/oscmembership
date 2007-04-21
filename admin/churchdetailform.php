<?php
// $Id: settingsform.php,v 1. 2007/3/5 root Exp $
//  ------------------------------------------------------------------------ //
//                ChurchLedger.com/OSC                      //
//                    Copyright (c) 2006 ChurchLedger.com//
//                       <http://www.churchledger.com/>                             //
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
include("../../../include/cp_header.php");
include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");
include_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php");

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

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}


//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit(_oscmem_admin_accessdenied);
}


//determine action
$action='';
$op = '';
$confirm = '';
$optionid = (isset($_GET['optionid'])) ? intval($_GET['optionid']) : 0;


if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];
if (isset($_GET['optionid'])) $optionid=$_GET['optionid'];
if (isset($_POST['optionid'])) $optionid=$_POST['optionid'];
if (isset($_GET['id'])) $id=$_GET['id'];
if (isset($_POST['id'])) $id=$_POST['id'];
if (isset($_POST['directorydisclaimer'])) $directorydisclaimer=$_POST['directorydisclaimer'];


$myts = &MyTextSanitizer::getInstance();
$detail_handler = &xoops_getmodulehandler('churchdetail', 'oscmembership');

$churchdetail=$detail_handler->get();

switch (true) 
{
    case ($op=="save"):
    	if(isset($_POST['churchname'])) $churchdetail->assignVar('churchname',$_POST['churchname']);

	if(isset($_POST['address1'])) $churchdetail->assignVar('address1',$_POST['address1']);

	if(isset($_POST['address2'])) $churchdetail->assignVar('address2',$_POST['address2']);

	if(isset($_POST['city'])) $churchdetail->assignVar('city',$_POST['city']);
	
	if(isset($_POST['state'])) $churchdetail->assignVar('state',$_POST['state']);
	
	if(isset($_POST['zip'])) $churchdetail->assignVar('zip',$_POST['zip']);
	
	if(isset($_POST['country'])) $churchdetail->assignVar('country',$_POST['country']);

	if(isset($_POST['phone'])) $churchdetail->assignVar('phone',$_POST['phone']);
	
	if(isset($_POST['fax'])) $churchdetail->assignVar('fax',$_POST['fax']);
	
	if(isset($_POST['email'])) $churchdetail->assignVar('email',$_POST['email']);
	
	if(isset($_POST['website'])) $churchdetail->assignVar('website',$_POST['website']);
	
	if(isset($_POST['directorydisclaimer'])) $churchdetail->assignVar('directorydisclaimer',$_POST['directorydisclaimer']);
	

	$churchdetail->assignVar('datelastedited',date('y-m-d g:i:s'));
	$churchdetail->assignVar('editedby',$xoopsUser->getVar('uid'));	

	$detail_handler->update($churchdetail);	
	$message=_oscmem_UPDATESUCCESS;
	    
	redirect_header("churchdetailform.php", 3, $message);
    break;
}

$churchname=new XoopsFormText(_oscmem_churchname,"churchname",30,50,$churchdetail->getVar('churchname'));

$address1=new XoopsFormText(_oscmem_address,"address1",30,50,$churchdetail->getVar('address1'));

$address2=new XoopsFormText("","address2",30,50,$churchdetail->getVar('address2'));

$city=new XoopsFormText(_oscmem_city,"city",30,50,$churchdetail->getVar('city'));

$state=new XoopsFormText(_oscmem_state,"state",5,5,$churchdetail->getVar('state'));

$zip=new XoopsFormText(_oscmem_post,"zip",10,50,$churchdetail->getVar('zip'));

$country=new XoopsFormSelectCountry(_oscmem_country,"country",$churchdetail->getVar('country'));

$website=new XoopsFormText(_oscmem_website,"website",50,50,$churchdetail->getVar('website'));

$fax=new XoopsFormText(_oscmem_fax,"fax",50,50,$churchdetail->getVar('fax'));

$phone=new XoopsFormText(_oscmem_phone,"phone",50,50,$churchdetail->getVar('phone'));

$email=new XoopsFormText(_oscmem_email,"email",50,50,$churchdetail->getVar('email'));

$caption=_oscmembership_directorydisclaimer;
$name="directorydisclaimer";
$value=$churchdetail->getVar('directorydisclaimer');
$supplemental="";

$editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 10, 50, $supplemental);


$id_hidden = new XoopsFormHidden("id",$churchdetail->getVar('id'));

$op_hidden = new XoopsFormHidden("op", "save");  //save operation

$submit_button = new XoopsFormButton("", "submit", _oscmem_submit, "submit");

$form = new XoopsThemeForm(_oscmem_setting_TITLE, "churchdetailform", "churchdetailform.php", "post", true);

$form->addElement($churchname);
$form->addElement($address1);
$form->addElement($address2);
$form->addElement($city);
$form->addElement($state);
$form->addElement($zip);
$form->addElement($country);
$form->addElement($phone);
$form->addElement($fax);
$form->addElement($website);
$form->addElement($editor);
$form->addElement($id_hidden);
$form->addElement($op_hidden);
$form->addElement($submit_button);


//Upload stuff

xoops_cp_header();
$form->display();

xoops_cp_footer();

?>