<?php
include("../../mainfile.php");
$GLOBALS['xoopsOption']['template_main'] ="groupview.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}


include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if(!hasPerm("oscmembership_view",$xoopsUser))     redirect_header(XOOPS_URL."/modules/" . $xoopsModule->getVar('dirname'), 3, _oscmem_accessdenied);

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/group.php';

// include the default language file for the admin interface
if ( file_exists( "../language/" . $xoopsConfig['language'] . "/main.php" ) ) {
    include "../language/" . $xoopsConfig['language'] . "/main.php";
}
elseif ( file_exists( "../language/english/main.php" ) ) {
    include "../language/english/main.php";
}

include(XOOPS_ROOT_PATH."/header.php");

if (isset($_POST['sort'])) $sort = $_POST['sort'];
if (isset($_POST['filter'])) $filter=$_POST['filter'];
if (isset($_POST['submit'])) $submit = $_POST['submit'];

$group_handler = &xoops_getmodulehandler('group', 'oscmembership');


if(hasPerm("oscmembership_view",$xoopsUser)) $ispermview=true;
if(hasPerm("oscmembership_modify",$xoopsUser)) $ispermmodify=true;


if(isset($submit))
{
	switch($submit)
	{
	case _oscmem_addgroup:
		redirect_header("groupdetailform.php?action=create", 2, _oscmem_addgroup_redirect);
		break;

	case _oscmem_addtocart: 
		//call add cart
		$uid=$xoopsUser->getVar('uid');
		for($i=0;$i<$loopcount+1;$i++)
		{
			if (isset($_POST['chk' . $i]))
			{
				$id=$_POST['chk' . $i];
				$group_handler->addtoCart($id, $uid);
			}
		}

		redirect_header("groupselect.php", 5, _oscmem_addtocart_redirect);
		break;

	case _oscmem_removefromcart:
		for($i=0;$i<$loopcount+1;$i++)
		{
			if (isset($_POST['chk' . $i]))
			{
				$id=$_POST['chk' . $i];
				$group_handler->removefromCart($id, $uid);
			}
		}
		redirect_header("groupselect.php", 2, _oscmem_msg_removedfromcart);
		break;
	
	case _oscmem_intersectcart:
		for($i=0;$i<$loopcount+1;$i++)
		{
			if (isset($_POST['chk' . $i]))
			{
				$id=$_POST['chk' . $i];
				$uid=$xoopsUser->getVar('uid');
				$group_handler->intersectCart($id, $uid);
			}
		}
		redirect_header("groupselect.php", 2, _oscmem_msg_intersectedcart);
		break;
	}
}

	
$searcharray=array();
$searcharray[0]=$filter;
$result = $group_handler->search($searcharray);

//<a href='groupdetailform.php?action=create'>" . _oscmembership_addgroup . "</a>


//echo "<form action='groupselect.php' method=post>";
//echo "<input type=submit name=submit value='" . _oscmem_addgroup . "'>";
//echo "</form>";

//echo "<h2 class=comTitle>" . _oscmem_personselect . "</h2>";
/*
$inner_table="<table class='outer'  >";
$inner_table = $inner_table . "<tr><th>&nbsp;</th><th>" . _oscmem_groupname . "</th>";
$inner_table = $inner_table . "<th>" . _oscdir_city . "," . _oscdir_state . "</th>";
$inner_table = $inner_table . "<th>" . _oscdir_email . "</th>";
$inner_table = $inner_table . "<th>&nbsp;</th>";
$inner_table = $inner_table . "</tr>";

*/
$db = &Database::getInstance();
$rowcount=0;
$group=new Group();

foreach($result as $group)
{
	$group_label = new XoopsFormLabel("<a href='groupdetailform.php?id=" . $group->getVar('id') . "'>" . _oscmem_edit . "</a>&nbsp;&nbsp;&nbsp;<a href='groupselect.php?id=" . $group->getVar('id') . "&submit=addtocart'>" . _oscmem_addtocart . "</a>" ,$group->getVar('group_Name'));

//	$form->addElement($group_label);

	
}

$xoopsTpl->assign('oscmem_applyfilter',_oscmem_applyfilter);
$xoopsTpl->assign('title',_oscmem_groupview); 
$xoopsTpl->assign('oscmem_name',_oscmem_name);
$xoopsTpl->assign('oscmem_address',_oscmem_address);
$xoopsTpl->assign('oscmem_email',_oscmem_email);
$xoopsTpl->assign('oscmem_clearfilter',_oscmem_clearfilter);
$xoopsTpl->assign('oscmem_addtocart',_oscmem_addtocart);
$xoopsTpl->assign('oscmem_removefromcart',_oscmem_removefromcart);
$xoopsTpl->assign('oscmem_addgroup',_oscmem_addgroup);
$xoopsTpl->assign('is_perm_view',$ispermview);
$xoopsTpl->assign('is_perm_modify',$ispermmodify);
$xoopsTpl->assign('oscmem_view',_oscmem_view);
$xoopsTpl->assign('oscmem_edit',_oscmem_edit);
$xoopsTpl->assign('groups',$result);

/*
if(!isset($group_label))
{
	$group_label= new XoopsFormLabel('',_oscmem_nogroups);
	$form->addElement($group_label);
}	
*/
//$form->addElement($topid_hidden);
//$form->addElement($table_label);
//$form->addElement($submit_button);
//$form->display();

include(XOOPS_ROOT_PATH."/footer.php");


?>