<?php
include("../../mainfile.php");
$xoopsOption['template_main'] = 'cs_index.html';

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

$group_handler = &xoops_getmodulehandler('group', 'oscmembership');
	
$searcharray=array();
$searcharray[0]='';
$result = $group_handler->search($searcharray);

$form = new XoopsThemeForm(_oscmem_groupselect_TITLE, "groupselectform", "groupselect.php", "post", false);

$submit_button = new XoopsFormButton("", "groupselectsubmit", _osc_select, "submit");

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

$group=new Group();

while($row = $db->fetchArray($result)) 
{
	$rowcount++;
	$group->assignVars($row);
	
	$group_label= new XoopsFormLabel("<a href='groupdetailform.php?id=" . $group->getVar('id') . "'>" . _oscmem_edit . "</a>",$group->getVar('group_Name'));

	$form->addElement($group_label);
	
/*	
	$memberresult = $memberresult . "<tr>";
	$memberresult=$memberresult . "<td><input type=checkbox name=" . $rowcount . " value=" . $person->getVar('id') . ">";
	$memberresult=$memberresult . "<td>";
	$memberresult=$memberresult . $person->getVar('lastname') . ", " . $person->getVar('firstname') . "</td>";
	$memberresult=$memberresult . "<td>" . $person->getVar('address1') . "</td>";
	$memberresult=$memberresult . "<td>" . $person->getVar('city') . ", " . $person->getVar('state') . "</td>";
	$memberresult=$memberresult . "<td>" . $person->getVar('email') . "</td>";
	$memberresult = $memberresult . "</tr>";
*/
}

if(!isset($group_label))
{
	$group_label= new XoopsFormLabel('',_oscmem_nogroups);
	$form->addElement($group_label);
}	

//$form->addElement($topid_hidden);
//$form->addElement($table_label);
//$form->addElement($submit_button);
$form->display();

include(XOOPS_ROOT_PATH."/footer.php");
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
echo "module/index.php";


?>