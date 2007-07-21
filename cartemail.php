<?php
include("../../mainfile.php");
$GLOBALS['xoopsOption']['template_main'] ="cartemail.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";


include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/person.php';


include(XOOPS_ROOT_PATH."/header.php");


if(!hasPerm("oscmembership_view",$xoopsUser) && !hasPerm("oscmembership_modify",$xoopsUser))     redirect_header(XOOPS_URL, 3, _oscmem_accessdenied);

$sort="";
$filter="";
if (isset($_POST['sort'])) $sort = $_POST['sort'];
if (isset($_POST['filter'])) $filter=$_POST['filter'];
if (isset($_POST['submit'])) $submit = $_POST['submit'];
if (isset($_POST['loopcount'])) $loopcount = $_POST['loopcount'];


$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
$searcharray=array();

if(isset($submit))
{
	switch($submit)
	{
		case 0:
		break;
	}
}

$results = $person_handler->getCartc($xoopsUser->getVar('uid'));
$to="";
$person=$person_handler->Create(false);

foreach($results as $person)
{
	if(strlen($person->getVar('email'))>0)
	{
		$to.=$person->getVar('email') . ", ";
	}
}

$to=substr($to,0,strlen($to)-2);  //strip trailing comma

$to_label= new XoopsFormlabel(_oscmem_emailto_label . ":",$to);

$emailsubject_text= new XoopsFormText(_oscmem_emailsubject . ":", "subject", 30, 50, "");


$editor = new XoopsFormDhtmlTextArea(_oscmem_emailbody_label . ":", "body", "", 10, 50, "");

$form = new XoopsThemeForm("", "cartgenerateemailform", "cartemail.php", "post", true);

$form->addElement($to_label);
$form->addElement($emailsubject_text);
$form->addElement($editor);

$xoopsTpl->assign('title',_oscmem_cartgenerateemail_TITLE); 
$xoopsTpl->assign('formbody',$form->render());

include(XOOPS_ROOT_PATH."/footer.php");
?>