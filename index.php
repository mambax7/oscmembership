<?php
include("../../mainfile.php");
$GLOBALS['xoopsOption']['template_main'] ="memberview.html";

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
include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/person.php';

include(XOOPS_ROOT_PATH."/header.php");

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
	case _oscmem_applyfilter:
		//do nothing
		break;
	case _oscmem_clearfilter:
		$filter="";
		break;
		
	case _oscmem_addtocart:
		//call add cart
		for($i=1;$i<$loopcount+1;$i++)
		{
			if (isset($_POST['chk' . $i]))
			{
				$id=$_POST['chk' . $i];
				$uid=$xoopsUser->getVar('uid');
				$person_handler->addtoCart($id, $uid);
			}
		}
		redirect_header("index.php", 2, _oscmem_addedtocart);
		break;
	
	case _oscmem_removefromcart:
		for($i=1;$i<$loopcount+1;$i++)
		{
			if (isset($_POST['chk' . $i]))
			{
				$id=$_POST['chk' . $i];
				$uid=$xoopsUser->getVar('uid');
				$person_handler->removefromCart($id, $uid);
			}
		}
		redirect_header("index.php", 2, _oscmem_msg_removedfromcart);
		break;
	
	case _oscmem_intersectcart:
		for($i=1;$i<$loopcount+1;$i++)
		{
			if (isset($_POST['chk' . $i]))
			{
				$id=$_POST['chk' . $i];
				$uid=$xoopsUser->getVar('uid');
				//$person_handler->removefromCart($id, $uid);
			}
		}
		redirect_header("index.php", 2, _oscmem_msg_intersectedcart);
		break;
	}
}

if(isset($filter))
{
	$searcharray[0]=$filter;
}
else $searcharray[0]='';

$persons = $person_handler->search2($searcharray, $sort);

$xoopsTpl->assign('oscmem_applyfilter',_oscmem_applyfilter);
$xoopsTpl->assign('title',_oscmem_memberview); 
$xoopsTpl->assign('oscmem_name',_oscmem_name);
$xoopsTpl->assign('oscmem_address',_oscmem_address);
$xoopsTpl->assign('oscmem_email',_oscmem_email);
$xoopsTpl->assign('oscmem_clearfilter',_oscmem_clearfilter);
$xoopsTpl->assign('oscmem_addtocart',_oscmem_addtocart);
$xoopsTpl->assign('oscmem_intersectcart',_oscmem_intersectcart);
$xoopsTpl->assign('oscmem_removefromcart',_oscmem_removefromcart);
$xoopsTpl->assign('persons',$persons);
$xoopsTpl->assign('loopcount', $persons[0]['totalloopcount']);

include(XOOPS_ROOT_PATH."/footer.php");




?>