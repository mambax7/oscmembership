<?php
/*******************************************************************************
*
*  filename    : Reports/DirectoryReport.php
*  last change : 2003-08-30
*  description : Creates a Member directory
*
*
 *  http://osc.sourceforge.net
 *
 *  This product is based upon work previously done by Infocentral (infocentral.org)
 *  on their PHP version Church Management Software that they discontinued
 *  and we have taken over.  We continue to improve and build upon this product
 *  in the direction of excellence.
 * 
 *  OpenSourceChurch (OSC) is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 * 
 *  Any changes to the software must be submitted back to the OpenSourceChurch project
 *  for review and possible inclusion.
*
*  Copyright 2003  Jason York
*  Copyright 2006, Steve McAtee - Converted to Xoops/OSC
******************************************************************************/

include_once "../../mainfile.php";
/*
require "../Include/Config.php";
require "../Include/Functions.php";
require "../Include/ReportConfig.php";
require "../Include/ReportFunctions.php";
*/


//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule))  {
    exit("Access Denied");
}

require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/ReportConfig.php");
include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/fpdf151/fpdf.php";

require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/class_fpdf_labels.php";

require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";


// Load the FPDF library
//LoadLib_FPDF();

class PDF_Directory extends FPDF {

	// Private properties
	var $_Margin_Left = 0;         // Left Margin
	var $_Margin_Top  = 0;         // Top margin 
	var $_Char_Size   = 12;        // Character size
	var $_CurLine     = 0;
	var $_Column      = 0;
	var $_Font        = "Times";
	var $sFamily;
	var $sLastName;

	function Header()
	{
		global $bDirUseTitlePage;

		if (($this->PageNo() > 1) || ($bDirUseTitlePage == false))
		{
			global $sChurchName;
			//Select Arial bold 15
			$this->SetFont($this->_Font,'B',15);
			//Line break
			$this->Ln(7);
			//Move to the right
			$this->Cell(10);
			//Framed title
			$this->Cell(190,10,$sChurchName . " - " . _oscmem_page,1,0,'C');
		}
	}

	function Footer()
	{
		global $bDirUseTitlePage;

		if (($this->PageNo() > 1) || ($bDirUseTitlePage == false))
		{
			//Go to 1.7 cm from bottom
			$this->SetY(-17);
			//Select Arial italic 8
			$this->SetFont($this->_Font,'I',8);
			//Print centered page number
			$iPageNumber = $this->PageNo();
			if ($bDirUseTitlePage)
				$iPageNumber--;
			$this->Cell(0,10, gettext("Page") . " " . $iPageNumber,0,0,'C');
		}
	}

	function TitlePage()
	{
		global $sChurchName;
		global $sDirectoryDisclaimer;
		global $sChurchAddress;
		global $sChurchCity;
		global $sChurchState;
		global $sChurchZip;
		global $sChurchPhone;
		global $bDirLetterHead;

		//Select Arial bold 15
		$this->SetFont($this->_Font,'B',15);

		if (is_readable($bDirLetterHead))
			$this->Image($bDirLetterHead,10,5,190);

		//Line break
		$this->Ln(5);
		//Move to the right
		$this->MultiCell(197,10,"\n\n\n". $sChurchName . "\n\n" . _oscmem_directory . "\n\n",0,'C');
		$this->Ln(5);
		$today = date("F j, Y");
		$this->MultiCell(197,10,$today . "\n\n",0,'C');

		$sContact = sprintf("%s\n%s, %s  %s\n\n%s\n\n", $sChurchAddress, $sChurchCity, $sChurchState, $sChurchZip, $sChurchPhone);
		$this->MultiCell(197,10,$sContact,0,'C');
		$this->Cell(10);
		$this->MultiCell(197,10,$sDirectoryDisclaimer,0,'C');
		$this->AddPage();
	}


	// Sets the character size
	// This changes the line height too
	function Set_Char_Size($pt) {
		if ($pt > 3) {
			$this->_Char_Size = $pt;
			$this->SetFont($this->_Font,'',$this->_Char_Size);
		}
	}

	// Constructor
	function PDF_Directory() {
		global $paperFormat;
		parent::FPDF("P", "mm", $paperFormat);

		$this->_Column      = 0;
		$this->_CurLine     = 2;
		$this->_Font        = "Times";
		$this->SetMargins(0,0);
		$this->Open();
		$this->Set_Char_Size(12);
		$this->AddPage();
		$this->SetAutoPageBreak(false);

		$this->_Margin_Left = 12;
		$this->_Margin_Top  = 12;
	}

	function Check_Lines($numlines)
	{
		$CurY = $this->GetY();  // Temporarily store off the position

		// Need to determine if we will extend beyoned 17mm from the bottom of
		// the page.
		$this->SetY(-17);
		if ($this->_Margin_Top+(($this->_CurLine+$numlines)*5) > $this->GetY())
		{
			// Next Column or Page
			if ($this->_Column == 1)
			{
				$this->_Column = 0;
				$this->_CurLine = 2;
				$this->AddPage();
			}
			else
			{
				$this->_Column = 1;
				$this->_CurLine = 2;
			}
		}
		$this->SetY($CurY); // Put the position back
	}

	// This function prints out the heading when a letter
	// changes.
	function Add_Header($sLetter)
	{
		$this->Check_Lines(2);
		$this->SetTextColor(255);
		$this->SetFont($this->_Font,'B',12);
		$_PosX = $this->_Margin_Left+($this->_Column*108);
		$_PosY = $this->_Margin_Top+($this->_CurLine*5);
		$this->SetXY($_PosX, $_PosY);
		$this->Cell(80, 5, $sLetter, 1, 1, "C", 1) ;
		$this->SetTextColor(0);
		$this->SetFont($this->_Font,'',$this->_Char_Size);
		$this->_CurLine+=2;
	}

	// This prints the family name in BOLD
	function Print_Name($sName)
	{
		$this->SetFont($this->_Font,'B',12);
		$_PosX = $this->_Margin_Left+($this->_Column*108);
		$_PosY = $this->_Margin_Top+($this->_CurLine*5);
		$this->SetXY($_PosX, $_PosY);
		$this->Write(5, $sName);
		$this->SetFont($this->_Font,'',$this->_Char_Size);
		$this->_CurLine++;
	}

	// This function formats the string for the family info
	function sGetFamilyString( $aRow )
	{
		global $bDirFamilyPhone;
		global $bDirFamilyWork;
		global $bDirFamilyCell;
		global $bDirFamilyEmail;
		global $bDirWedding;
		global $bDirAddress;

		extract($aRow);

		$sFamilyStr = "";

		if ($bDirAddress)
		{
			if (strlen($fam_Address1)) { $sFamilyStr .= $fam_Address1 . "\n";  }
			if (strlen($fam_Address2)) { $sFamilyStr .= $fam_Address2 . "\n";  }
			if (strlen($fam_City)) { $sFamilyStr .= $fam_City . ", " . $fam_State . " " . $fam_Zip . "\n";  }
		}

		if ($bDirFamilyPhone && strlen($fam_HomePhone))
			$sFamilyStr .= "   " . gettext("Phone") . ": " . ExpandPhoneNumber($fam_HomePhone, $fam_Country, $bWierd) . "\n";
		if ($bDirFamilyWork && strlen($fam_WorkPhone))
			$sFamilyStr .= "   " . gettext("Work") . ": " . ExpandPhoneNumber($fam_WorkPhone, $fam_Country, $bWierd) . "\n";
		if ($bDirFamilyCell && strlen($fam_CellPhone))
			$sFamilyStr .= "   " . gettext("Cell") . ": " . ExpandPhoneNumber($fam_CellPhone, $fam_Country, $bWierd) . "\n";
		if ($bDirFamilyEmail && strlen($fam_Email))
			$sFamilyStr .= "   " . gettext("Email") . ": " . $fam_Email . "\n";
		if ($bDirWedding && ($fam_WeddingDate > 0))
			$sFamilyStr .= "   " . gettext("Wedding") . ": " . Date("m/d/Y", mysql_to_epoch($fam_WeddingDate)) . "\n";

		return $sFamilyStr;
	}

	// This function formats the string for the head of household.
	// NOTE: This is used for the Head AND Spouse (called twice)
	function sGetHeadString( $aHead, $baltHeader, $SortMe )
	{
		global $bDirBirthday;
		global $bDirPersonalPhone;
		global $bDirPersonalWork;
		global $bDirPersonalCell;
		global $bDirPersonalEmail;
		global $bDirPersonalWorkEmail;

		extract($aHead);

		$sHeadStr = "";

		if ( strlen($per_LastName) && ($per_LastName != $this->sLastName) )
			$bDifferentLastName = true;
		else
			$bDifferentLastName = false;

			
		// First time build with last name, second time append spouse name.
		if($baltHeader)
		{
			$this->sRecordName = $SortMe;

		}
		else
		{
		
			if (strlen($this->sRecordName)) 
			{
				$this->sRecordName .= " " . gettext("and") . " " . $per_FirstName;
			}
			else
			{
			$this->sRecordName .= $per_LastName . " " . $per_FirstName;
			}
			if ($bDifferentLastName)
				$this->sRecordName .= " (" . $per_LastName . ")";
			$sHeadStr .= $per_FirstName;
		}

		if ($bDifferentLastName)
			$sHeadStr .= " " . $per_LastName;
		$iTempLen = strlen($sHeadStr);

		if ($bDirBirthday && $per_BirthMonth && $per_BirthDay)
			$sHeadStr .= sprintf(" (%d/%d)\n", $per_BirthMonth, $per_BirthDay);
		else
		{
			$sHeadStr .= "\n";
			$iTempLen = strlen($sHeadStr);
		}

		$sCountry = SelectWhichInfo($per_Country,$fam_Country,false);

		if ($bDirPersonalPhone && strlen($per_HomePhone)) {
			$TempStr = ExpandPhoneNumber($per_HomePhone, $sCountry, $bWierd);
			$sHeadStr .= "   " . gettext("Phone") . ": " . $TempStr .= "\n";
		}
		if ($bDirPersonalWork && strlen($per_WorkPhone)) {
			$TempStr = ExpandPhoneNumber($per_WorkPhone, $sCountry, $bWierd);
			$sHeadStr .= "   " . gettext("Work") . ": " . $TempStr .= "\n";
		}
		if ($bDirPersonalCell && strlen($per_CellPhone)) {
			$TempStr = ExpandPhoneNumber($per_CellPhone, $sCountry, $bWierd);
			$sHeadStr .= "   " . gettext("Cell") . ": " . $TempStr .= "\n";
		}
		if ($bDirPersonalEmail && strlen($per_Email))
			$sHeadStr .= "   " . gettext("Email") . ": " . $per_Email .= "\n";
		if ($bDirPersonalWorkEmail && strlen($per_WorkEmail))
			$sHeadStr .= "   " . gettext("Work/Other Email") . ": " . $per_WorkEmail .= "\n";

		// If there is no additional information for either head or spouse, there is no
		// need to print the name in the sublist, they are already are in the heading.
		if (strlen($sHeadStr) == $iTempLen)
			return "";
		else
			return $sHeadStr;
	}

	// This function formats the string for other family member records
	function sGetMemberString( $aRow )
	{
		global $bDirPersonalPhone;
		global $bDirPersonalWork;
		global $bDirPersonalCell;
		global $bDirPersonalEmail;
		global $bDirPersonalWorkEmail;
		global $bDirBirthday;
		global $aChildren;

		extract($aRow);

		$sMemberStr = $per_FirstName;

		// Check to see if family member has different last name
		if ( strlen($per_LastName) && ($per_LastName != $this->sLastName) )
			$sMemberStr .= " " . $per_LastName;

		if ($bDirBirthday && $per_BirthMonth && $per_BirthDay)
		{
			$sMemberStr .= sprintf(" (%d/%d", $per_BirthMonth, $per_BirthDay);
			if ($per_BirthYear && in_array($per_fmr_ID, $aChildren))
				$sMemberStr .= sprintf("/%d)\n", $per_BirthYear);
			else
				$sMemberStr .= ")\n";
		}
		else
		{
			$sMemberStr .= "\n";
		}

		$sCountry = SelectWhichInfo($per_Country,$fam_Country,false);

		if ($bDirPersonalPhone && strlen($per_HomePhone)) {
			$TempStr = ExpandPhoneNumber($per_HomePhone, $sCountry, $bWierd);
			$sMemberStr .= "   " . gettext("Phone") . ": " . $TempStr .= "\n";
		}
		if ($bDirPersonalWork && strlen($per_WorkPhone)) {
			$TempStr = ExpandPhoneNumber($per_WorkPhone, $sCountry, $bWierd);
			$sMemberStr .= "   " . gettext("Work") . ": " . $TempStr .= "\n";
		}
		if ($bDirPersonalCell && strlen($per_CellPhone)) {
			$TempStr = ExpandPhoneNumber($per_CellPhone, $sCountry, $bWierd);
			$sMemberStr .= "   " . gettext("Cell") . ": " . $TempStr .= "\n";
		}
		if ($bDirPersonalEmail && strlen($per_Email))
			$sMemberStr .= "   " . gettext("Email") . ": " . $per_Email .= "\n";
		if ($bDirPersonalWorkEmail && strlen($per_WorkEmail))
			$sMemberStr .= "   " . gettext("Work/Other Email") . ": " . $per_WorkEmail .= "\n";

		return $sMemberStr;
	}

	// Number of lines is only for the $text parameter
	function Add_Record($sName, $text, $numlines)
	{
		$numlines++; // add an extra blank line after record
		$this->Check_Lines($numlines);

		$this->Print_Name($sName);

		$_PosX = $this->_Margin_Left+($this->_Column*108);
		$_PosY = $this->_Margin_Top+($this->_CurLine*5);
		$this->SetXY($_PosX, $_PosY);
		$this->MultiCell(108, 5, $text);
		$this->_CurLine += $numlines;
	}
}



// Get and filter the classifications selected

$count = 0;
$strCls="";
foreach ($_POST["sDirClassifications"] as $strCls)
{
	$aClasses[$count++] = $strCls; //FilterInput($Cls,'int');
}
$sDirClassifications = implode(",",$aClasses);

$count = 0;
foreach ($_POST["sDirRoleHead"] as $Head)
{
	$aHeads[$count++] = $Head; //FilterInput($Head,'int');
}
$sDirRoleHeads = implode(",",$aHeads);

$count = 0;
foreach ($_POST["sDirRoleSpouse"] as $Spouse)
{
	$aSpouses[$count++] = $Spouse; // FilterInput($Spouse,'int');
}
$sDirRoleSpouses = implode(",",$aSpouses);

$count = 0;
foreach ($_POST["sDirRoleChild"] as $Child)
{
	$aChildren[$count++] = $Child; // FilterInput($Child,'int');
}
// $sDirRoleChildren = implode(",",$aChildren);

$label_handler = &xoops_getmodulehandler('label', 'oscmembership');
$labelcritiera_handler = &xoops_getmodulehandler('labelcriteria', 'oscmembership');

$groups=array();

$labelcritiera=$labelcritiera_handler->create();

$labelcritiera->assignVar('bdiraddress',isset($_POST["bDirAddress"]));
$labelcritiera->assignVar('bdirwedding',isset($_POST["bDirWedding"]));
$labelcritiera->assignVar('bdirbirthday',isset($_POST["bDirBirthday"]));
$labelcritiera->assignVar('bdirfamilyphone',isset($_POST["bDirFamilyPhone"]));
$labelcritiera->assignVar('bdirfamilywork',isset($_POST["bDirFamilyWork"]));
$labelcritiera->assignVar('bdirfamilycell',isset($_POST["bDirFamilyWork"]));
$labelcritiera->assignVar('bdirfamilyemail',isset($_POST["bDirFamilyEmail"]));
$labelcritiera->assignVar('bdirpersonalphone',isset($_POST["bDirPersonalPhone"]));
$labelcritiera->assignVar('bdirpersonalwork',isset($_POST["bDirPersonalWork"]));
$labelcritiera->assignVar('bdirpersonalcell',isset($_POST["bDirPersonalCell"]));
$labelcritiera->assignVar('bdirpersonalemail',isset($_POST["bDirPersonalEmail"]));
$labelcritiera->assignVar('bdirpersonalworkemail',isset($_POST["bDirPersonalWorkEmail"]));


if(isset($_POST["sChurchName"])) $sChurchName = $_POST["sChurchName"] ;
if(isset($_POST["sDirectoryDisclaimer"])) $sDirectoryDisclaimer = $_POST["sDirectoryDisclaimer"];
if(isset($_POST["sChurchAddress"])) $sChurchAddress = $_POST["sChurchAddress"];
if(isset($_POST["sChurchCity"]))  $sChurchCity = $_POST["sChurchCity"];
if(isset($_POST["sChurchState"])) $sChurchState = $_POST["sChurchState"];
if(isset($_POST["sChurchZip"]))  $sChurchZip = $_POST["sChurchZip"];
if(isset($_POST["sChurchPhone"])) $sChurchPhone = $_POST["sChurchPhone"] ;

$churchdir_handler = &xoops_getmodulehandler('churchdir', 'oscmembership');
$churchdir= $churchdir_handler->create();
$churchdir->assignVar('church_name',$sChurchName);
$churchdir->assignVar('disclaimer',$sDirectoryDisclaimer);
$churchdir->assignVar('church_address',$sChurchAddress);
$churchdir->assignVar('church_city',$sChurchCity);
$churchdir->assignVar('church_state',$sChurchState);
$churchdir->assignVar('church_post',$sChurchZip);
$churchdir->assignVar('church_phone',$sChurchPhone);


$churchdir = $churchdir_handler->update($churchdir);


$labels=$label_handler->getlabels(false, false, $groups,"",$labelcritiera);


$bDirUseTitlePage = isset($_POST["bDirUseTitlePage"]);

$baltFamilyName = isset($_POST["baltFamilyName"]);
$baltHeader = isset($_POST["baltHeader"]);

$bSortFirstName=0;
$bSortFirstName = isset($_POST["bSortFirstName"]);

/*


if($baltFamilyName)
$baltHeader=true;

// Instantiate the directory class and build the report.
$pdf = new PDF_Directory();

if ($bDirUseTitlePage) $pdf->TitlePage();

if (strlen($sDirClassifications)) $sClassQualifier = "AND person.clsid in (" . $sDirClassifications . ")";

$db = &Database::getInstance();

$sGroupTable = "";

$sWhereExt="";

if (!empty($_POST["GroupID"]))
{
	$sGroupTable = ", " . $db->prefix('oscmembership_p2g2r');
//	$sGroupTable = ", person2group2role_p2g2r";

	$count = 0;
	foreach ($_POST["GroupID"] as $Grp)
	{
		$aGroups[$count++] = $Grp;
	}
	$sGroupsList = implode(",",$aGroups);

	$sWhereExt .= "AND per_ID = p2g2r_per_ID AND p2g2r_grp_ID in (" . $sGroupsList . ") ";

	// This is used by per-role queries to remove duplicate rows from people assigned multiple groups.
	$sGroupBy = " GROUP BY per_ID";
}

// This query is similar to that of the CSV export with family roll-up.
// Here we want to gather all unique families, and those that are not attached to a family.

//Determine sort selection
if($bSortFirstName)
	$sortMe = " person.firstname ";
else
	$sortMe=" person.lastname ";

$sSQL = "(SELECT *, 0 AS memberCount, " . $sortMe . " AS SortMe  FROM  " . $db->prefix("oscmembership_person") . " person $sGroupTable LEFT JOIN " . $db->prefix("oscmembership_family") . " family ON family.id= person.famid WHERE person.famid = 0 " . " $sWhereExt $sClassQualifier)
	UNION (SELECT *, COUNT(*) AS memberCount, familyname AS SortMe FROM " . $db->prefix("oscmembership_person") . "  person $sGroupTable LEFT JOIN  " . $db->prefix("oscmembership_family") . " family ON person.famid = family.id WHERE person.famid > 0  " . " $sWhereExt $sClassQualifier GROUP BY person.famid HAVING memberCount = 1)
	UNION (SELECT *, COUNT(*) AS memberCount, familyname AS SortMe FROM " . $db->prefix("oscmembership_person") . " person $sGroupTable LEFT JOIN  " . $db->prefix("oscmembership_family") . " family ON person.famid = family.id WHERE person.famid > 0 " . " $sWhereExt $sClassQualifier GROUP BY person.famid HAVING memberCount > 1) ";

if($baltFamilyName) 
{
	$sSQL= $sSQL . "UNION (SELECT *, COUNT(*) AS memberCount, altfamilyname AS SortMe FROM " . $db->prefix("oscmembership_person") . " person $sGroupTable LEFT JOIN  " . $db->prefix("oscmembership_family") . " family ON person.famid = family.id WHERE person.famid > 0  AND length(family.altfamilyname)>0 " . " $sWhereExt $sClassQualifier GROUP BY person.famid HAVING memberCount = 1)
	UNION (SELECT *, COUNT(*) AS memberCount, altfamilyname AS SortMe FROM " . $db->prefix("oscmembership_person") . " person $sGroupTable LEFT JOIN " . $db->prefix("oscmembership_family") . " family ON person.famid = family.id WHERE person.famid > 0 AND length(family.altfamilyname)>0 " . " $sWhereExt $sClassQualifier GROUP BY person.famid HAVING memberCount > 1) ";
}	

$sSQL = $sSQL . "ORDER BY SortMe ";

//echo $sSQL;
$rsRecords=$db->query($sSQL);
//$rsRecords = RunQuery($sSQL);
// This is used for the headings for the letter changes.
// Start out with something that isn't a letter to force the first one to work
$sLastLetter = "0";

while ($aRow = mysql_fetch_array($rsRecords))
{
	$OutStr = "";
	extract($aRow);

	if ($memberCount > 1) // Here we have a family record.
	{
		$iFamilyID = $per_fam_ID;

		$pdf->sRecordName = "";

		if($baltHeader)
		{
//			$pdf->sRecordName = $baltHeader;
			$pdf->sRecordName = $SortMe ;
		}
		else
		{
		$pdf->sRecordName = $fam_Name;
		}
		$pdf->sLastName = $fam_Name;
		//$OutStr .= $SortMe . "\n";
		$OutStr .=  $pdf->sGetFamilyString($aRow);
		$bNoRecordName = true;

		// Find the Head of Household
		$sSQL = "SELECT * from " . $this->db->prefix("oscmembership_person") . " person $sGroupTable LEFT JOIN " . $this->db->prefix("oscmembership_family") . " family ON person.famid = family.id 
			WHERE person.famid = " . $iFamilyID . " 
			AND person.fmrid in ($sDirRoleHeads) $sWhereExt $sClassQualifier $sGroupBy";
		$rsPerson = RunQuery($sSQL);

		if (mysql_num_rows($rsPerson) > 0)
		{
			$aHead = mysql_fetch_array($rsPerson);
			$OutStr .= $pdf->sGetHeadString($aHead, $baltHeader, $SortMe);
			$bNoRecordName = false;
		}
		// Find the Spouse of Household
		$sSQL = "SELECT * from " . $db->prefix("oscmembership_person") . "  person $sGroupTable LEFT JOIN family_fam ON person.famid = family.id 
			WHERE person.famid = " . $iFamilyID . " 
			AND person.famid in ($sDirRoleSpouses) $sWhereExt $sClassQualifier $sGroupBy";
		$rsPerson = RunQuery($sSQL);

		if (mysql_num_rows($rsPerson) > 0)
		{
			$aSpouse = mysql_fetch_array($rsPerson);
			$OutStr .= $pdf->sGetHeadString($aSpouse,$baltHeader, $SortMe);
			$bNoRecordName = false;
		}
		
		// In case there was no head or spouse, just set record name to family name
		if ($bNoRecordName)
		{
			if($baltHeader)
			{
				$pdf->sRecordName = $SortMe;
			}
			else
			{
				$pdf->sRecordName = $fam_Name;
			}
		}
			
		// Find the other members of a family
		$sSQL = "SELECT * from " . $db->prefix("oscmembership_person") . " person $sGroupTable LEFT JOIN " . $db->prefix("oscmembership_family") . " family ON person.famid = family.id
            WHERE person.famid = " . $iFamilyID . " AND !(person.fmrid in ($sDirRoleHeads))
            AND !(person.fmrid in ($sDirRoleSpouses))  $sWhereExt $sClassQualifier $sGroupBy";
//		$sSQL = "SELECT * from person_per $sGroupTable LEFT JOIN family_fam ON per_fam_ID = fam_ID
//			WHERE per_fam_ID = " . $iFamilyID . " AND !(per_fmr_ID in ($sDirRoleHeads))
//			AND per_fmr_ID in ($sDirRoleChildren) $sWhereExt $sGroupBy";
		$rsPerson = RunQuery($sSQL);

		while ($aRow = mysql_fetch_array($rsPerson))
		{
			$OutStr .= $pdf->sGetMemberString($aRow);
		}
		
	
	}
	else
	{
		if (strlen($familyname))
			$pdf->sLastName = $familyname;
		else
			$pdf->sLastName = $lastname;
		
//		if($baltHeader)
//		{ //Do nothing
//		}
//		else
//		{
		//here
		if($bSortFirstName)
			$pdf->sRecordName = $firstname . " " . $pdf->sLastName;
		else
			$pdf->sRecordName = $pdf->sLastName . ", " . $firstname;
//                }

		if ($bDirBirthday && $birthmonth && $birthday)
			$pdf->sRecordName .= sprintf(" (%d/%d)", $birthmonth, $birthday);

		SelectWhichAddress($address1, $address2, $per_Address1, $per_Address2, $fam_Address1, $fam_Address2, false);
		$sAddress2 = SelectWhichInfo($per_Address2, $fam_Address2, false);
		$sCity = SelectWhichInfo($per_City, $fam_City, false);
		$sState = SelectWhichInfo($per_State, $fam_State, false);
		$sZip = SelectWhichInfo($per_Zip, $fam_Zip, false);
		$sHomePhone = SelectWhichInfo($per_HomePhone, $fam_HomePhone, false);
		$sWorkPhone = SelectWhichInfo($per_WorkPhone, $fam_WorkPhone, false);
		$sCellPhone = SelectWhichInfo($per_CellPhone, $fam_CellPhone, false);
		$sEmail = SelectWhichInfo($per_Email, $fam_Email, false);

		if ($bDirAddress)
		{
			if (strlen($sAddress1)) { $OutStr .= $sAddress1 . "\n";  }
			if (strlen($sAddress2)) { $OutStr .= $sAddress2 . "\n";  }
			if (strlen($sCity)) { $OutStr .= $sCity . ", " . $sState . " " . $sZip . "\n";  }
		}
		if ($bDirPersonalPhone && strlen($sHomePhone)) {
			$TempStr = ExpandPhoneNumber($sHomePhone, $sDefaultCountry, $bWierd);
			$OutStr .= "   " . gettext("Phone") . ": " . $TempStr . "\n";
		}
		if ($bDirPersonalWork && strlen($sWorkPhone)) {
			$TempStr = ExpandPhoneNumber($sWorkPhone, $sDefaultCountry, $bWierd);
			$OutStr .= "   " . gettext("Work") . ": " . $TempStr . "\n";
		}
		if ($bDirPersonalCell && strlen($sCellPhone)) {
			$TempStr = ExpandPhoneNumber($sCellPhone, $sDefaultCountry, $bWierd);
			$OutStr .= "   " . gettext("Cell") . ": " . $TempStr . "\n";
		}
		if ($bDirPersonalEmail && strlen($sEmail))
			$OutStr .= "   " . gettext("Email") . ": " . $sEmail . "\n";
		if ($bDirPersonalWorkEmail && strlen($per_WorkEmail))
			$OutStr .= "   " . gettext("Work/Other Email") . ": " . $per_WorkEmail .= "\n";
	}

	// Count the number of lines in the output string
	if (strlen($OutStr))
		$numlines = substr_count($OutStr, "\n");
	else
		$numlines = 0;

	if ($numlines > 0)
	{
		if (strtoupper($sLastLetter) != strtoupper(substr($pdf->sRecordName,0,1)))
		{
			$pdf->Check_Lines($numlines+2);
			$sLastLetter = strtoupper(substr($pdf->sRecordName,0,1));
			$pdf->Add_Header($sLastLetter);
		}
//		$pdf->Add_Record($SortMe, $OutStr, $numlines);
		$pdf->Add_Record($pdf->sRecordName, $OutStr, $numlines);  // another hack: added +1
	}
}
*/
exit;

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cachhe");

if ($iPDFOutputType == 1)
	$pdf->Output("Directory-" . date("Ymd-Gis") . ".pdf", true);
else
	$pdf->Output();	
?>
