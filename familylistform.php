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
include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/family.php';

include(XOOPS_ROOT_PATH."/header.php");

$family_handler = &xoops_getmodulehandler('family', 'oscmembership');
$searcharray=array();
$searcharray[0]='';
$results = $family_handler->search($searcharray);
echo "<h2 class=comTitle>" . _oscmem_family_list. "</h2>";
echo "<table class='outer'  >";
echo "<tr><th>" . _oscmem_familyname . "</th><th>" . _oscmem_address . "</th>";
echo "<th>" . _oscmem_city . "," . _oscmem_state . "</th>";
echo "<th>" . _oscmem_email . "</th>";
echo "<th>&nbsp;</th>";
echo "</tr>";

echo $results;
echo "</table>";

include(XOOPS_ROOT_PATH."/footer.php");
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//echo "module/index.php";




?>