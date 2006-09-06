<?php
include("../../mainfile.php");
$GLOBALS['xoopsOption']['template_main'] ="cartview.html";

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
		case _oscmem_remove:
			for($i=0;$i<$loopcount+1;$i++)
			{
				if (isset($_POST['chk' . $i]))
				{
					$id=$_POST['chk' . $i];
					$uid=$xoopsUser->getVar('uid');
					$person_handler->removefromCart($id, $uid);
				}
			}
		
			redirect_header("viewcart.php", 2, _oscmem_msg_removedfromcart);
		break;
		
		case _oscmem_emptycart:
			$uid=$xoopsUser->getVar('uid');
			$person_handler->wipeCart($uid);

			redirect_header("viewcart.php", 2, _oscmem_msg_removedfromcart);
		
		break;
		
		case _oscmem_generatelabels:
			redirect_header("PDFLabels.php",2,"");
		break;
	}
}

$results = $person_handler->getCart($xoopsUser->getVar('uid'));

$xoopsTpl->assign('title',_oscmem_cartcontents); 
$xoopsTpl->assign('oscmem_name',_oscmem_name);
$xoopsTpl->assign('oscmem_address',_oscmem_address);
$xoopsTpl->assign('oscmem_email',_oscmem_email);
$xoopsTpl->assign('oscmem_emptycart',_oscmem_emptycart);
$xoopsTpl->assign('oscmem_remove',_oscmem_remove);
$xoopsTpl->assign('oscmem_emptycarttogroup',_oscmem_emptycarttogroup);
$xoopsTpl->assign('oscmem_emptycarttofamily',_oscmem_emptycarttofamily);
$xoopsTpl->assign('oscmem_generatelabels',_oscmem_generatelabels);

if (isset($results))
{
	$db = &Database::getInstance();
	$person = new Person();
	$persons[]=array();
	
	$i=0;
	while($row = $db->fetchArray($results)) 
	{
		if(isset($row))
		{
			$person->assignVars($row);
			$persons[$i]['lastname']=$person->getVar('lastname');
			$persons[$i]['firstname']=$person->getVar('firstname');
			
			if($person->getVar('address1') !=null)
			{$persons[$i]['addressflag']=_oscmem_yes;}
			else 
			{$persons[$i]['addressflag']=_oscmem_no;}
			
			if($person->getVar('email') != null)
			{$persons[$i]['emailflag']=_oscmem_yes;}
			else { $persons[$i]['emailflag']=_oscmem_no;}
			
			$persons[$i]['id']=$person->getVar('id');
			$persons[$i]['loopcount']=$i;
			
		}
		$i++;
	}
	
}
	
$xoopsTpl->assign('persons',$persons);
$xoopsTpl->assign('loopcount',$i);

include(XOOPS_ROOT_PATH."/footer.php");
?>