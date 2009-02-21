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
if(isset($submit))
{
	switch($submit)
	{
		
	case _oscmem_addtocart: 
		//call add cart
		for($i=0;$i<$loopcount+1;$i++)
		{
			if (isset($_POST['chk' . $i]))
			{
				$id=$_POST['chk' . $i];
				$uid=$xoopsUser->getVar('uid');
				$person_handler->addtoCart($id, $uid);
			}
		}
		redirect_header("index.php", 3, _oscmem_addedtocart);
		break;
	}
}


//$profile_handler =& xoops_getmodulehandler('smartprofile','profile');

//$profile_handler->search(null,null);

$profile_handler =& xoops_getmodulehandler('profile','smartprofile');

$oscmem_criteria = new CriteriaCompo(new Criteria('email', 'null', "<>"));
$oscmem_vars=array();
$oscmem_vars[]='email';
$oscmem_users=$profile_handler->search($oscmem_criteria,$oscmem_vars);

$test=$oscmem_users[0][2];

echo $test->getVar('uname');
//print_r($test);

$oscmem_user=$test[0];
print_r($oscmem_user);

echo $test['email'];
// Get fields
$fields =& $profile_handler->loadFields();
$oscfieldnames=array_keys($fields);


$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
$person=$person_handler->create();

$myts = &MyTextSanitizer::getInstance();

$vars=$person->getVars();
$oscmem_person_keys=array_keys($vars);
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

/*
foreach (array_keys($fields) as $i) 
{
	echo $fields[$i]->getVar('field_name') . "<br>";
}
*/

$profile_keys=array_keys($fields);


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

$form->display();

/*
$xoopsTpl->assign('oscmem_person_keys',$oscmem_person_keys[0]);

$xoopsTpl->assign('oscmem_oscmap_field1',_oscmem_oscmap_field1);
$xoopsTpl->assign('oscmem_oscmap_field2',_oscmem_oscmap_field2);
$xoopsTpl->assign('oscmem_oscmap_field3',_oscmem_oscmap_field3);
$xoopsTpl->assign('oscmem_oscmap_field4',_oscmem_oscmap_field4);
$xoopsTpl->assign('oscmem_oscmap_field5',_oscmem_oscmap_field5);
$xoopsTpl->assign('oscmem_oscmap_field6',_oscmem_oscmap_field6);
$xoopsTpl->assign('oscmem_oscmap_field7',_oscmem_oscmap_field7);
*/
/*
$member_handler =& xoops_gethandler('profile');
$users=$member_handler->getUsers();

echo $users[0]->getVar('email');
foreach($users as $user)
{
	echo $user->getVar('email') . "<br>";
	echo $user->getVar('name');

               foreach ($user->vars as $k => $v) {
		echo $k . '&nbsp;' . $v . '<br>';
                }



}
 */

/*
$xoopsTpl->assign('oscmem_applyfilter',_oscmem_applyfilter);
$xoopsTpl->assign('title',_oscmem_memberview); 
$xoopsTpl->assign('oscmem_name',_oscmem_name);
$xoopsTpl->assign('oscmem_address',_oscmem_address);
$xoopsTpl->assign('oscmem_email',_oscmem_email);
$xoopsTpl->assign('oscmem_clearfilter',_oscmem_clearfilter);
$xoopsTpl->assign('oscmem_addtocart',_oscmem_addtocart);
$xoopsTpl->assign('oscmem_removefromcart',_oscmem_removefromcart);
$xoopsTpl->assign('oscmem_addmember',_oscmem_addmember);
$xoopsTpl->assign('is_perm_view',$ispermview);
$xoopsTpl->assign('is_perm_modify',$ispermmodify);
$xoopsTpl->assign('oscmem_view',_oscmem_view);
$xoopsTpl->assign('oscmem_edit',_oscmem_edit);
$xoopsTpl->assign('oscmem_confirmdelete',_oscmem_confirmdelete);
$xoopsTpl->assign('oscmem_deletemember',_oscmem_deletemember);
$xoopsTpl->assign('oscmem_filter',$filter);
$xoopsTpl->assign('page',$page);
$_SESSION['page']=$page;
$xoopsTpl->assign('oscmem_rowstodisplay',$limit);
$xoopsTpl->assign('oscmem_label_rowstodisplay',_oscmem_label_rowstodisplay);

if($page==1)
{
	$xoopsTpl->assign('oscmem_prevpage',1);
}
else
{
	$xoopsTpl->assign('oscmemb_prevpage',$page-1);
}



if($persons[0]->getVar('totalloopcount')>0)
{
	$xoopsTpl->assign('persons',$persons);
	$xoopsTpl->assign('loopcount', $persons[0]->getVar('totalloopcount'));
}
else
{
	$person=array();
	$xoopsTpl->assign('persons',$persons);
	$xoopsTpl->assign('loopcount', '0');
}	
*/	

xoops_cp_footer();




?>