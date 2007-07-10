<?php
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
include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");
include_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/class/group.php");

include_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php");

include_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/class/person.php");

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


if(!hasPerm("oscmembership_modify",$xoopsUser))     redirect_header(XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname') , 3, _oscmem_accessdenied);


//determine action
$op = '';
$confirm = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];
if (isset($_GET['id'])) $groupid=$_GET['id'];
if (isset($_POST['id'])) $groupid=$_POST['id'];
if (isset($_POST['action'])) $action=$_POST['action'];
if (isset($_GET['action'])) $action=$_GET['action'];

if (isset($_POST['removeid'])) $removeid=$_POST['removeid'];
if (isset($_GET['removeid'])) $removeid=$_GET['removeid'];


$myts = &MyTextSanitizer::getInstance();
$groupdetail_handler = &xoops_getmodulehandler('group', 'oscmembership');
    
    if(isset($groupid))
    {
	$group = $groupdetail_handler->get($groupid);  //only one     
	$members = $groupdetail_handler->getmembers($group);
    }
    else
    {
	$group = $groupdetail_handler->create();
	
    }  
    
	
$groupname_label = new XoopsFormLabel(_oscmem_groupname, $group->getVar('group_Name'));

$groupdescript_label= new XoopsFormLabel(_oscmem_groupdescription,$group->getVar('group_Description'));

$grouptype_label = new XoopsFormlabel(_oscmem_grouptype,$group->getVar('group_typeName'));

$id_hidden = new XoopsFormHidden("id",$group->getVar('id'));


$form = new XoopsThemeForm(_oscmem_groupdetail_TITLE, "groupdetailform", "groupdetailform.php", "post", true);

$db = &Database::getInstance();

$person=new Person();

//$member_tray = new XoopsFormElementTray('', '&nbsp;');

$memberresult="<table><th>" . _oscmem_lastname . "</th><th>" . _oscmem_firstname . "</th>";

$len_memberresult=strlen($memberresult);
$rowcount=0;

while($row = $db->fetchArray($members)) 
{
	$rowcount++;
	
	$person->assignVars($row);
	$memberresult .= "<tr><td>" . $person->getVar('lastname') . "</td>";
	$memberresult .= "<td>" . $person->getVar('firstname') . "</td></tr>";
	
}
if($rowcount==0)
{
	$memberresult .= "<tr><td>" . _oscmem_nomembers . "</td></tr></table>" ;
	}
else
{
	$memberresult .= "</table>";
}


//$form->addElement($addmembers_button);
$form->addElement($groupname_label);
$form->addElement($groupdescript_label);
$form->addElement($grouptype_label);

$member_label = new XoopsFormLabel(_oscmem_groupmember, $memberresult);
$form->addElement($member_label);


$form->addElement($op_hidden);

$removeid_hidden = new XoopsFormHidden("removeid",'');

$form->addElement($removeid_hidden);
$form->addElement($id_hidden);

//Upload stuff

//xoops_cp_header();
$form->display();

//xoops_cp_footer();
include(XOOPS_ROOT_PATH."/footer.php");

?>

