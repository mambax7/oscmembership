<?php
include("../../mainfile.php");
$GLOBALS['xoopsOption']['template_main'] ="familyselect.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}


include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if(!hasPerm("oscmembership_view",$xoopsUser))     redirect_header(XOOPS_URL, 3, _oscmem_accessdenied);

if(hasPerm("oscmembership_view",$xoopsUser)) $ispermview=true;
if(hasPerm("oscmembership_modify",$xoopsUser)) $ispermmodify=true;

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/family.php';

$sort="";
$filter="";
if (isset($_GET['sort'])) $sort = $_GET['sort'];
if (isset($_POST['filter'])) $filter=$_POST['filter'];
if (isset($_POST['submit'])) $submit=$_POST['submit'];


include(XOOPS_ROOT_PATH."/header.php");

$person_handler = &xoops_getmodulehandler('person', 'oscmembership');

$family_handler = &xoops_getmodulehandler('family', 'oscmembership');

if(isset($filter))
{
	$searcharray[0]=$filter;
}
else $searcharray[0]='';

//Check to see if items returned were selected

if(isset($submit))
{
	switch($submit)
	{
	case _oscmembership_addcarttofamily:
		//redirect_header("familydetailform.php?action=create", 2, _oscmem_addfamily_redirect);

		if(isset($_POST['totalloopcount']))
		{
			$topid=$_POST['totalloopcount'];
		
			for($i=0;$i<$topid;$i++)
			{
				//add cart to family
				if($_POST["chk" . $i]>0)
				{
					$uid=$xoopsUser->getVar('uid');
					$person_handler->addCarttoFamily($uid,$_POST["chk" . $i]);
				break;
				}
			}
			
		}
		
		//do nothing
		break;
	}
}


$results = $family_handler->search($searcharray, $sort);
$xoopsTpl->assign("title",_oscmem_family_select);

$xoopsTpl->assign('oscmem_applyfilter',_oscmem_applyfilter);
$xoopsTpl->assign('oscmem_familyname',_oscmem_familyname);
$xoopsTpl->assign('oscmem_address',_oscmem_address);
$xoopsTpl->assign('oscmem_clearfilter',_oscmem_clearfilter);
$xoopsTpl->assign('oscmem_addmember',_oscmem_addmember);
$xoopsTpl->assign('oscmem_email',_oscmem_email);
$xoopsTpl->assign('is_perm_view',$ispermview);
$xoopsTpl->assign('is_perm_modify',$ispermmodify);
$xoopsTpl->assign('oscmem_view',_oscmem_view);
$xoopsTpl->assign('oscmem_edit',_oscmem_edit);
$xoopsTpl->assign('oscmembership_addcarttofamily',_oscmembership_addcarttofamily);

$xoopsTpl->assign('families',$results);

$family=$results[0];

$totalloopcount=$family->getVar('totalloopcount');
$xoopsTpl->assign('totalloopcount', $totalloopcount);


include(XOOPS_ROOT_PATH."/footer.php");

?>