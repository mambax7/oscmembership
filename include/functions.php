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

// Returns a string of a person's full name, formatted as specified by $Style
// $Style = 0  :  "Title FirstName MiddleName LastName, Suffix"
// $Style = 1  :  "Title FirstName MiddleInitial. LastName, Suffix"
// $Style = 2  :  "LastName, Title FirstName MiddleName, Suffix"
// $Style = 3  :  "LastName, Title FirstName MiddleInitial., Suffix"
//
function FormatFullName($Title, $FirstName, $MiddleName, $LastName, $Suffix, $Style)
{
	$nameString = "";

	switch ($Style) {

	case 0:
		if ($Title) $nameString .= $Title . " ";
		$nameString .= $FirstName;
		if ($MiddleName) $nameString .= " " . $MiddleName;
		if ($LastName) $nameString .= " " . $LastName;
		if ($Suffix) $nameString .= ", " . $Suffix;
		break;

	case 1:
		if ($Title) $nameString .= $Title . " ";
		$nameString .= $FirstName;
		if ($MiddleName) $nameString .= " " . strtoupper($MiddleName{0}) . ".";
		if ($LastName) $nameString .= " " . $LastName;
		if ($Suffix) $nameString .= ", " . $Suffix;
		break;

	case 2:
		if ($LastName) $nameString .= $LastName . ", ";
		if ($Title) $nameString .= $Title . " ";
		$nameString .= $FirstName;
		if ($MiddleName) $nameString .= " " . $MiddleName;
		if ($Suffix) $nameString .= ", " . $Suffix;
		break;

	case 3:
		if ($LastName) $nameString .= $LastName . ", ";
		if ($Title) $nameString .= $Title . " ";
		$nameString .= $FirstName;
		if ($MiddleName) $nameString .= " " . strtoupper($MiddleName{0}) . ".";
		if ($Suffix) $nameString .= ", " . $Suffix;
		break;
	}

	return $nameString;
}


// Collapses a formatted phone number as long as the Country is known
// Eg. for United States:  555-555-1212 Ext. 123 ==> 5555551212e123
//
// Need to add other countries besides the US...
//
function CollapsePhoneNumber($sPhoneNumber,$sPhoneCountry)
{
	switch ($sPhoneCountry)	{

	case "United States":
		$sCollapsedPhoneNumber = "";
		$bHasExtension = false;

		// Loop through the input string
		for ($iCount = 0; $iCount <= strlen($sPhoneNumber); $iCount++) {

			// Take one character...
			$sThisCharacter = substr($sPhoneNumber, $iCount, 1);

			// Is it a number?
			if (Ord($sThisCharacter) >= 48 && Ord($sThisCharacter) <= 57) {
				// Yes, add it to the returned value.
				$sCollapsedPhoneNumber .= $sThisCharacter;
			}
			// Is the user trying to add an extension?
			else if (!$bHasExtension && ($sThisCharacter == "e" || $sThisCharacter == "E")) {
				// Yes, add the extension identifier 'e' to the stored string.
				$sCollapsedPhoneNumber .= "e";
				// From now on, ignore other non-digits and process normally
				$bHasExtension = true;
			}
		}
		break;

	default:
		$sCollapsedPhoneNumber = $sPhoneNumber;
		break;
	}

	return $sCollapsedPhoneNumber;
}

//Filter object properties.  Called by array_filter function
function FilterProps($svalue)
{
	$returnvalue=true;
	switch ($svalue)
	{		
		case "loopcount":
			$returnvalue=false;
			break;

		case "totalloopcount":
			$returnvalue=false;
			break;

		case "dateentered":
			$returnvalue=false;
			break;

		case "datelastedited":
			$returnvalue=false;
			break;

		case "enteredby":
			$returnvalue=false;
			break;

		case "editedby":
			$returnvalue=false;
			break;

		case "oddrow":
			$returnvalue=false;
			break;
	}

	if(stripos($svalue,"id")>-1) $returnvalue=false;

	return $returnvalue;
}

?>