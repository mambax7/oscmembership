<?php
include("../../mainfile.php");
//$xoopsOption['template_main'] = 'cs_index.html';

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}


//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit("Access Denied");
}

if (isset($_POST['submit'])) $submit = $_POST['submit'];

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/family.php';

include(XOOPS_ROOT_PATH."/header.php");

$family_handler = &xoops_getmodulehandler('family', 'oscmembership');
$searcharray=array();
$searcharray[0]='';

if(isset($submit))
{
	switch($submit)
	{
	case _oscmembership_addfamily:
		redirect_header("familydetailform.php?action=create", 2, _oscmem_addfamily_redirect);
		
		//do nothing
		break;
	}
}


$results = $family_handler->search($searcharray);
echo "<h2 class=comTitle>" . _oscmem_family_list. "</h2>";
echo "<form action='familylistform.php' method=POST>";
echo "<table class='outer'  >";
echo "<tr><td><input type=submit name=submit value='" . _oscmembership_addfamily . "'></td></tr>";
echo "<tr><th>" . _oscmem_familyname . "</th><th>" . _oscmem_address . "</th>";
echo "<th>" . _oscmem_city . "," . _oscmem_state . "</th>";
echo "<th>" . _oscmem_email . "</th>";
echo "<th>&nbsp;</th>";
echo "</tr>";

echo $results;
echo "</table></form>";

include(XOOPS_ROOT_PATH."/footer.php");

?>