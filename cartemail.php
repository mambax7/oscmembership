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

if (isset($_POST['body'])) $osc_body=$_POST['body'];
if (isset($_POST['subject'])) $osc_subject=$_POST['subject'];

$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
$searcharray=array();

$results = $person_handler->getCartc($xoopsUser->getVar('uid'));
$osc_to="";
$person=$person_handler->Create(false);

foreach($results as $person)
{
	if(strlen($person->getVar('email'))>0)
	{
		$osc_to.=$person->getVar('email') . ", ";
	}
}

$osc_to=substr($osc_to,0,strlen($osc_to)-2);  //strip trailing comma
$osc_header='From: ' . $xoopsUser->getVar('email')  . "\r\n" . 'Reply-To: ' . $xoopsUser->getVar('email');

if(isset($submit))
{
	switch($submit)
	{
		case 0:
		if (mail($osc_to, $osc_subject, $osc_body, $osc_header)) {
		
			redirect_header("viewcart.php",2,"Message Sent.");

 		} else {
  		echo("<p>Message delivery failed...</p>");
 		}

		break;
	}
}


$to_label= new XoopsFormlabel(_oscmem_emailto_label . ":",$osc_to);

$emailsubject_text= new XoopsFormText(_oscmem_emailsubject . ":", "subject", 30, 50, "");


$editor = new XoopsFormDhtmlTextArea(_oscmem_emailbody_label . ":", "body", "", 10, 50, "");
$submit_button = new XoopsFormButton("", "submit", "Send", "submit");

$form = new XoopsThemeForm("", "cartgenerateemailform", "cartemail.php", "post", true);

$form->addElement($to_label);
$form->addElement($emailsubject_text);
$form->addElement($editor);
$form->addElement($submit_button);

$xoopsTpl->assign('title',_oscmem_cartgenerateemail_TITLE); 
$xoopsTpl->assign('formbody',$form->render());

include(XOOPS_ROOT_PATH."/footer.php");
?>