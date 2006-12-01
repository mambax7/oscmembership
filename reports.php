<?php
include("../../mainfile.php");
$GLOBALS['xoopsOption']['template_main'] ="reports.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}

//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit("Access Denied");
}

include(XOOPS_ROOT_PATH."/header.php");

$xoopsTpl->assign('title',_oscmem_reporttitle); 
$xoopsTpl->assign('OSCMEM_directoryreport',_oscmem_directoryreport); 
$xoopsTpl->assign('OSCMEM_csvexport',_oscmem_csvexport);
$xoopsTpl->assign('oscmem_csvimport',_oscmem_csvimport);

include(XOOPS_ROOT_PATH."/footer.php");
?>