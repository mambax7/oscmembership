<?php
if ( $xoopsUser )
{
	function hasPerm($permission, $user)
	{
		$groupPermHandler =& xoops_gethandler('groupperm');
		$moduleHandler =& xoops_gethandler('module');
		$module = $moduleHandler->getByDirname('oscmembership');

		$returnval = false;
		$perm=$groupPermHandler->getItemIds($permission,$user->getGroups(),$module->getVar("mid"));
		foreach (array_keys($perm) as $i) 
		{
			if($perm[$i] )
			{
				$returnvalue=true;
			}
			else $returnvalue=false;
		}
	
		if($user->isAdmin($module->getVar("mid")))
		{
			$returnvalue=true;
		}
		
		return $returnvalue;
	}
}


function oscverifyXoopsDate($passeddate, $exceptiontext=_oscmem_invaliddate)
{

	if(isset($passeddate))
	{
		if($passeddate=='YYYY/MM/DD' or empty($passeddate))
		{
			return '';
		}
		else
		{
			if(!preg_match('`[0-9]{4}/[01][0-9]/[0123][0-9]`', $passeddate)) 
			{
				if(!preg_match('`[0-9]{4}-[01][0-9]-[0123][0-9]`', $passeddate))
				{ 
					return 'error';
					exit;
				}
				else return $passeddate;	
			}
			else
			{
				return $passeddate;
			}
		}
	}
}


/******************************************************************************
 * Returns the proper information to use for a field.
 * Person info overrides Family info if they are different.
 * If using family info and bFormat set, generate HTML tags for text color red.
 * If neither family nor person info is available, return an empty string.
 *****************************************************************************/

function SelectWhichInfo($sPersonInfo, $sFamilyInfo, $bFormat = false)
{
	global $bShowFamilyData;

	if ($bShowFamilyData) {

		if ($bFormat) {
			$sFamilyInfoBegin = "<span style=\"color: red;\">";
			$sFamilyInfoEnd = "</span>";
		}

		if ($sPersonInfo != "") {
			return $sPersonInfo;
		} elseif ($sFamilyInfo != "") {
			if ($bFormat) {
				return $sFamilyInfoBegin . $sFamilyInfo . $sFamilyInfoEnd;
			} else {
				return $sFamilyInfo;
			}
		} else {
			return "";
		}

	} else {
		if ($sPersonInfo != "")
			return $sPersonInfo;
		else
			return "";
	}
}

?>