<?php
include_once "../../mainfile.php";
ini_set("memory_limit","400M");

$GLOBALS['xoopsOption']['template_main'] ="setupgsync.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if (file_exists(XOOPS_ROOT_PATH. "/modules/" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) 
{
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/modinfo.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/modinfo.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/modinfo.php";

}

include(XOOPS_ROOT_PATH."/header.php");

if(!hasPerm("oscmembership_view",$xoopsUser) && !hasPerm("oscmembership_modify",$xoopsUser))     redirect_header(XOOPS_URL, 3, _oscmem_accessdenied);


include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/person.php';

$person_handler = &xoops_getmodulehandler('person', 'oscmembership');

// load Zend Gdata libraries

$xoopsuid=$xoopsUser->getVar("uid");

$cart = $person_handler->getCartc($xoopsUser->getVar('uid'));

$person=$person_handler->create(false);



include(XOOPS_ROOT_PATH."/footer.php");
?>

