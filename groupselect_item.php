<?php
include("../../mainfile.php");
$xoopsOption['template_main'] = 'oscselect.html';

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}


//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit("Access Denied");
}

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

if (isset($_POST['submit'])) $submit = $_POST['submit'];


if(isset($submit))
{
	switch($submit)
	{
	case _oscmem_addgroup:
		redirect_header("groupdetailform.php?action=create", 2, _oscmem_addgroup_redirect);
		
		//do nothing
		break;
	}
}


$group_handler = &xoops_getmodulehandler('group', 'oscmembership');
$item_handler = 	&xoops_getmodulehandler('item', 'oscmembership');

$items=array();

$searcharray=array();
$searcharray[0]='';
$result = $group_handler->search($searcharray);

//$form = new XoopsThemeForm(_oscmem_groupselect_TITLE, "groupselectform", "groupselect.php", "post", false);

//<a href='groupdetailform.php?action=create'>" . _oscmembership_addgroup . "</a>

$submit_button = new XoopsFormButton("", "groupselectsubmit", _osc_select, "submit");
$db = &Database::getInstance();
$rowcount=0;
$group=new Group();

while($row = $db->fetchArray($result)) 
{
	$rowcount++;
	$group->assignVars($row);
	
	$item = $item_handler->create();
	$item->assignVar('id',$group->getVar('id'));
	$item->assignVar('detail1',$group->getVar('name'));

	$items[$rowcount]=$item;
	
}
$xoopsTpl->assign('items',$items);

if(!isset($items))
{
//	$group_label= new XoopsFormLabel('',_oscmem_nogroups);
//	$form->addElement($group_label);
}	

//$form->addElement($topid_hidden);
//$form->addElement($table_label);
//$form->addElement($submit_button);

include(XOOPS_ROOT_PATH."/footer.php");


?>