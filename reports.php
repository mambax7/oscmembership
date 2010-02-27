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
$xoopsTpl->assign('OSCMEM_csvexport_individual',_oscmem_csvexport_individual);
$xoopsTpl->assign('oscmem_csvimport_individual',_oscmem_csvimport_individual);
$xoopsTpl->assign('oscmem_vcardimport_individual',_oscmem_vcardimport_individual);
$xoopsTpl->assign('oscmem_csvimport_family',_oscmem_csvimport_family);
$xoopsTpl->assign('oscmem_membershipcard',_oscmem_membershipcard);
$xoopsTpl->assign('oscmem_childcard',_oscmem_childcard);
$xoopsTpl->assign('oscmem_menu_orphanmatchup',_oscmem_menu_orphanmatchup);
$xoopsTpl->assign('oscmem_menu_googlesync',_oscmem_menu_googlesync);

$xoopsTpl->assign('OSCMEM_directoryreport_description',_OSCMEM_directoryreport_description);
$xoopsTpl->assign('OSCMEM_csvexport_individual_description',_OSCMEM_csvexport_individual_description);
$xoopsTpl->assign('oscmem_csvimport_individual_description',_oscmem_csvimport_individual_description);
$xoopsTpl->assign('oscmem_vcardimport_individual_description',_oscmem_vcardimport_individual_description);
$xoopsTpl->assign('oscmem_menu_orphanmatchup_description',_oscmem_menu_orphanmatchup_description);
$xoopsTpl->assign('oscmem_csvimport_family_description',_oscmem_csvimport_family_description);
$xoopsTpl->assign('oscmem_membershipcard_description',_oscmem_membershipcard_description);
$xoopsTpl->assign('oscmem_childcard_description',_oscmem_childcard_description);
$xoopsTpl->assign('oscmem_googlecontactsync_description',_oscmem_googlecontactsync_description);

$xoopsTpl->assign('oscmem_report_label',_oscmem_report_label);
$xoopsTpl->assign('oscmem_report_description',_oscmem_report_description);

include(XOOPS_ROOT_PATH."/footer.php");
?>