<?php
/*******************************************************************************
 *
 *  filename    : orphanselect.php
 *
 *  OpenSourceChurch (OSC) is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 * 
 *  Any changes to the software must be submitted back to the OpenSourceChurch project
 *  for review and possible inclusion.
 *
 *  Copyright 2008 Steve McAtee
 ******************************************************************************/

include_once "../../mainfile.php";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/ReportConfig.php");

require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php");

// Include the function library
//require "Include/Config.php";
//require "Include/Functions.php";


include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/person.php';

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/osclist.php';

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/group.php';

//include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/churchdir.php';
include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");


include(XOOPS_ROOT_PATH."/header.php");
$GLOBALS['xoopsOption']['template_main'] ="orphanselect.html";

$iStage = 1;
$db = &Database::getInstance();

//$GLOBALS['xoopsOption']['template_main'] ="oscimport_family_step1.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

if(!hasPerm("oscmembership_modify",$xoopsUser)) exit(_oscmem_access_denied);

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/person.php';

$sort="";
$filter="";
if (isset($_POST['sort'])) $sort = $_POST['sort'];
if (isset($_POST['filter'])) $filter=$_POST['filter'];
if (isset($_POST['submit'])) $submit = $_POST['submit'];
if (isset($_POST['totalloopcount'])) $totalloopcount = $_POST['totalloopcount'];


include(XOOPS_ROOT_PATH."/header.php");

$person_handler = &xoops_getmodulehandler('person', 'oscmembership');


if(isset($submit))
{
	switch($submit)
	{
	case _oscmem_applyfilter:
		//do nothing
		break;
	case _oscmem_clearfilter:
		$filter="";
		break;

	case _oscmem_matchuporphans:
		//call match orphan
		for($i=0;$i<$totalloopcount+1;$i++)
		{
			if (isset($_POST['chk' . $i]))
			{
				$id=$_POST['chk' . $i];
				$familyid=$_POST['family' . $i];
				$person_handler->addtoFamily($id,$familyid);
			}
		}
		redirect_header("orphanselect.php", 3, _oscmem_orphansmatched);
		break;

		
	case _oscmem_addtocart: 
		//call add cart
		for($i=0;$i<$totalloopcount+1;$i++)
		{
			if (isset($_POST['chk' . $i]))
			{
				$id=$_POST['chk' . $i];
				$uid=$xoopsUser->getVar('uid');
				$person_handler->addtoCart($id, $uid);
			}
		}
		redirect_header("orphanselect.php", 3, _oscmem_addedtocart);
		break;
	
	case _oscmem_removefromcart:
		for($i=0;$i<$totalloopcount+1;$i++)
		{
			if (isset($_POST['chk' . $i]))
			{
				$id=$_POST['chk' . $i];
				$uid=$xoopsUser->getVar('uid');
				$person_handler->removefromCart($id, $uid);
			}
		}
		redirect_header("orphanselect.php", 2, _oscmem_msg_removedfromcart);
		break;
	
	case _oscmem_intersectcart:
		for($i=0;$i<$totalloopcount+1;$i++)
		{
			if (isset($_POST['chk' . $i]))
			{
				$id=$_POST['chk' . $i];
				$uid=$xoopsUser->getVar('uid');
			}
		}
		redirect_header("orphanselect.php", 2, _oscmem_msg_intersectedcart);
		break;
	}
}

if(isset($filter))
{
	$searcharray[0]=$filter;
}
else $searcharray[0]='';

$persons = $person_handler->getorphans($searcharray);

$xoopsTpl->assign('oscmem_applyfilter',_oscmem_applyfilter);
$xoopsTpl->assign('title',_oscmem_orphanselect); 
$xoopsTpl->assign('oscmem_name',_oscmem_name);
$xoopsTpl->assign('oscmem_matchedfamilyname',_oscmem_matchedfamilyname);
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
$xoopsTpl->assign('oscmem_matchuporphans',_oscmem_matchuporphans);
$xoopsTpl->assign('persons',$persons);
$xoopsTpl->assign("oscmem_noorphans",_oscmem_noorphans);
$xoopsTpl->assign("totalloopcount",$persons[0]->getVar("totalloopcount"));

//$form = new XoopsThemeForm(_oscmem_cvsimport_family_step1, "importstep1form", "oscImport_family_step2.php", "post", true);



include(XOOPS_ROOT_PATH."/footer.php");
?>
