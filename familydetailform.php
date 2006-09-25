<?php
// $Id: familydetailform.php,v 1.1.1.1 2006/03/12 14:57:25 root Exp $
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
$xoopsOption['template_main'] = 'cs_index.html';
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

/*
if (file_exists(XOOPS_ROOT_PATH. "/modules" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) {
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

	if(isset($_POST['familyname'])) $family->assignVar('familyname',$_POST['familyname']);
	
	if(isset($_POST['address1']))
	$family->assignVar('address1',$_POST['address1']);
	
	if(isset($_POST['address2']))
	$family->assignVar('address2',$_POST['address2']);
	
	if(isset($_POST['city'])) $family->assignVar('city',$_POST['city']);
	
	if(isset($_POST['state'])) $family->assignVar('state',$_POST['state']);
	
	if(isset($_POST['zip'])) $family->assignVar('post',$_POST['post']);
	
	if(isset($_POST['country'])) $family->assignVar('country',$_POST['country']);
	
	if(isset($_POST['homephone'])) $family->assignVar('homephone',$_POST['homephone']);
	
	if(isset($_POST['workphone'])) $family->assignVar('workphone',$_POST['workphone']);
	
	if(isset($_POST['cellphone'])) $family->assignVar('cellphone',$_POST['cellphone']);
	
	if(isset($_POST['email'])) $family->assignVar('email',$_POST['email']);
	
	
	if(isset($_POST['weddingdate']))
	{
		if(!preg_match('`[0-9]{4}/[01][0-9]/[0123][0-9]`', $_POST['weddingdate'])) 
		{
			redirect_header("famildetailform.php?id=" . $familyid, 3, _oscmem_incorrectdt_weddingdate."<br />".implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
			exit;
		}
		else
		$family->assignVar('weddingdate',$_POST['weddingdate']);
	}
	
	$family->assignVar('datelastedited',date('y-m-d g:i:s'));
	$family->assignVar('editedby',$xoopsUser->getVar('uid'));

switch (true) 
{
	case($op=="save"):
		$familydetail_handler->update($family);
		$message=_oscmem_UPDATESUCCESS;
		redirect_header("familydetailform.php?id=" . $familyid, 3, $message);

		break;
	case($op=="create"):
		$family->assignVar('dateentered',date('y-m-d g:i:s'));
		$family->assignVar('enteredby',$xoopsUser->getVar('uid'));
		$familyid= $familydetail_handler->insert($family);	
		$message=_oscmem_CREATESUCCESS_family;
		redirect_header("familydetailform.php?id=" . $familyid, 3, $message);
		break;
		
	case($op=="addmembersubmit"):
		redirect_header("personselect.php?type=family&id=" . $familyid,0,$message);		
		break;    

	case($op=="remove"):
		//save form changes first
		$familydetail_handler->update($family);
		
		$persondetail_handler= &xoops_getmodulehandler('person', 'oscmembership');
		$persondetail_handler->removefromFamily($removeid);
		$removeid=$_POST['removeid'];

		$message = _oscmem_REMOVEMEMBERSUCCESS;
		redirect_header("familydetailform.php?id=" . $familyid,3,$message);
		break;    
		
	case($op=="edit"):
		//save before redirect
		$familydetail_handler->update($family);

		$removeid=$_POST['removeid'];
		
		$message=_oscmem_UPDATESUCCESS;
		redirect_header("persondetailform.php?id=" . $removeid,3,$message);
		break;	
		
}

$familyname_text = new XoopsFormText(_oscmem_familyname, "familyname", 30, 50, $family->getVar('familyname'));

$address1_text = new XoopsFormText(_oscmem_address, "address1", 30, 50, $family->getVar('address1'));
$address2_text = new XoopsFormText('', "address2", 30, 50, $family->getVar('address2'));
$city_text = new XoopsFormText(_oscmem_city, "city", 30, 50, $family->getVar('city'));
$state_text = new XoopsFormText(_oscmem_state, "state", 30, 50, $family->getVar('state'));
$post_text = new XoopsFormText(_oscmem_post, "post", 30, 50, $family->getVar('zip'));
$country_text = new XoopsFormText(_oscmem_country, "country", 30, 50, $family->getVar('country'));

$homephone_text = new XoopsFormText(_oscmem_homephone, "homephone", 30, 50, $family->getVar('homephone'));

$workphone_text = new XoopsFormText(_oscmem_workphone, "workphone", 30, 50, $family->getVar('workphone'));

$cellphone_text = new XoopsFormText(_oscmem_cellphone, "cellphone", 30, 50, $family->getVar('cellphone'));

$email_text = new XoopsFormText(_oscmem_email, "email", 30, 50, $family->getVar('email'));

$weddingdate_dt= new XoopsFormTextDateSelect(_oscmem_weddingdate,'weddingdate', 15, $family->getVar('weddingdate'));

$datelastedited_label = new XoopsFormLabel(_oscmem_datelastedited, $family->getVar('datelastedited'));

$user=new XoopsUser();

if($family->getVar('editedby')<>'')
{
	$user = $member_handler->getUser($family->getVar('editedby'));
}

$editedby_label = new XoopsFormLabel(_oscmem_editedby, $user->getVar('uname'));

$dateentered_label = new XoopsFormLabel(_oscmem_dateentered, $family->getVar('dateentered'));

$user = $member_handler->getUser($family->getVar('enteredby'));

$enteredby_label = new XoopsFormLabel(_oscmem_enteredby, $user->getVar('uname'));

$id_hidden = new XoopsFormHidden("id",$family->getVar('id'));

$removeid_hidden = new XoopsFormHidden("removeid",'');

$op_hidden = new XoopsFormHidden("op", "save");  //save operation
$submit_button = new XoopsFormButton("", "familydetailsubmit", _osc_save, "submit");

if(isset($action))
{
	if($action=="create")
	{
		$op_hidden = new XoopsFormHidden("op", "create");  //save operation
		$submit_button = new XoopsFormButton("", "familydetailsubmit", _osc_create, "submit");
	}
}

$form = new XoopsThemeForm(_oscmem_familydetail_TITLE, "familydetailform", "familydetailform.php", "post", true);
$form->addElement($familyname_text);
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
	$remove_button = new XoopsFormButton($person->getVar("id"), "removemember", _oscmem_remove_member, "button", $person->getVar("id"));
	$remove_button->setExtra("onclick=familydetailform.op.value='remove';familydetailform.removeid.value=" . $person->getVar("id") . ";familydetailform.submit()");
	
	$editmember_button=new XoopsFormButton($person->getVar("id"), "editmember", _oscmem_edit_member, "button", $person->getVar("id"));
	$editmember_button->setExtra("onclick=familydetailform.op.value='edit';familydetailform.removeid.value=" . $person->getVar("id") . ";familydetailform.submit()");
	
	$memberresult .= $remove_button->render();
	$memberresult .= "&nbsp;&nbsp;" . $editmember_button->render() . "</td></tr>";
	
}
if($rowcount==0)
{
	$memberresult .= "<tr><td>" . _oscmem_nomembers . "</td></tr></table>" ;
	}
else
{
	$memberresult .= "</table>";
}

$addMember_button = new XoopsFormButton("", "addmembersubmit", _osc_addmember, "submit");

$addMember_link=new XoopsFormLabel('',"<a href='personselect.php?type=family&id=" . $familyid . "'>" . _osc_addmember . "</a>");
$form->addElement($addMember_link);
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

