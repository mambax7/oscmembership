<?php

include("../../../mainfile.php");
include '../../../include/cp_header.php';

include(XOOPS_ROOT_PATH."/header.php");
// language files

$language = $xoopsConfig['language'] ;
// include the default language file for the admin interface
if( ! file_exists( XOOPS_ROOT_PATH . "/modules/system/language/$language/admin/blocksadmin.php") ) $language = 'english' ;

if (file_exists(XOOPS_ROOT_PATH. "/modules" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/main.php")) {
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/main.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/main.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/main.php";

}

if (file_exists(XOOPS_ROOT_PATH. "/modules" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) {
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/modinfo.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/modinfo.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/modinfo.php";

}

if (file_exists(XOOPS_ROOT_PATH. "/modules" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/admin.php")) {
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/admin.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/admin.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/admin.php";

}



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
include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/osclist.php';

//determine action
$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];
//echo $op;
switch (true) 
{
	case($op=="additemsubmit"):
		redirect_header("osclist.php?type=familyrole&action=create",0,$message);		
		break;    
}

$osclist_handler = &xoops_getmodulehandler('osclist', 'oscmembership');
	
if (isset($_GET['id'])) $id=$_GET['id'];
if (isset($_POST['id'])) $id=$_POST['id'];

$osclist = $osclist_handler->create();
$osclist->assignVar('id',$id);

$result = $osclist_handler->getitems($osclist);
$title="";

switch ($id)
{
case 2: //family roles
	$add_link=new XoopsFormLabel("<a href='osclistdetailform.php?id=" . $id . "&action=create'>" . _oscmem_addfamilyrole . "</a>","");
	$title=_oscmem_osclist_TITLE_familyroles;
	
	break;

case 4: //member classification
	$add_link=new XoopsFormLabel("<a href='osclistdetailform.php?id=" . $id . "&action=create'>" . _oscmem_addmemberclassification . "</a>","");
	$title=_oscmem_osclist_TITLE_memberclassifications;
	break;

case 3: //member classification
	$add_link=new XoopsFormLabel("<a href='osclistdetailform.php?id=" . $id . "&action=create'>" . _oscmem_addgrouptype . "</a>","");
	$title=_oscmem_osclist_TITLE_grouptypes;
	break;
}

$form = new XoopsThemeForm($title, "osclistselectform", "osclistselect.php", "post", false);

$submit_button = new XoopsFormButton("", "osclistselectsubmit", _osc_select, "submit");

//echo "<h2 class=comTitle>" . _oscmem_personselect . "</h2>";
$inner_table="<table class='outer'  >";
$inner_table = $inner_table . "<tr><th>&nbsp;</th><th>" . _oscmem_itemname . "</th>";
$inner_table = $inner_table . "<th>" . _oscmem_itemsequence . "</th>";
$inner_table = $inner_table . "</tr>";

$db = &Database::getInstance();

$osclist=new Osclist();

$rowsintable='';

while($row = $db->fetchArray($result)) 
{
	$rowcount++;
	$osclist->assignVars($row);	

	$rowsintable=$rowsintable . "<tr>";

	$rowsintable = $rowsintable . "<td><a href='osclistdetailform.php?id=" . $osclist->getVar('id') . "&optionid=" . $osclist->getVar('optionid') . "&action=save'>" . _oscmem_edit . "</a></td>";
	
	$rowsintable = $rowsintable . "<td>" . $osclist->getVar('optionname') . "</td>";
	
	$rowsintable = $rowsintable . "<td>" . $osclist->getVar('optionsequence') . "</td>";

	$rowsintable = $rowsintable . "</tr>";	
}

//echo "<h2 class=comTitle>" . _oscmem_persondetail . "</h2>";

$form->addElement($add_link);

$form->addElement($topid_hidden);
$form->addElement($table_label);

xoops_cp_header();

if(!isset($row))
{
	$osclist_label= new XoopsFormLabel('',_oscmem_noitems);
	$form->addElement($osclist_label);
	$form->display();
}	
else
{
	$form->display();

	echo $inner_table .  $rowsintable . "</table>"; 
}


include(XOOPS_ROOT_PATH."/footer.php");

?>