<?php
include("../../mainfile.php");
$GLOBALS['xoopsOption']['template_main'] ="reports.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}


include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if(!hasPerm("oscmembership_view",$xoopsUser) && !hasPerm("oscmembership_modify",$xoopsUser))     redirect_header(XOOPS_URL, 3, _oscmem_accessdenied);

include(XOOPS_ROOT_PATH."/header.php");

$xoopsTpl->assign('title',_oscmem_reporttitle); 
$xoopsTpl->assign('OSCMEM_directoryreport',_oscmem_directoryreport); 
$xoopsTpl->assign('OSCMEM_csvexport',_oscmem_csvexport);
$xoopsTpl->assign('oscmem_csvimport',_oscmem_csvimport);
$xoopsTpl->assign('oscmem_membershipcard',_oscmem_membershipcard);
$xoopsTpl->assign('oscmem_childcard',_oscmem_childcard);

include(XOOPS_ROOT_PATH."/footer.php");
?>