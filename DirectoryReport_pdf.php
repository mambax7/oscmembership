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

//require (XOOPS_ROOT_PATH . "/Frameworks/fpdf/fpdf.php";
require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/ReportConfig.php");
include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/fpdf151/fpdf.php";

require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/class_fpdf_labels.php";

require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if (file_exists(XOOPS_ROOT_PATH. "/modules/" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) 
{
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/modinfo.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/modinfo.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/modinfo.php";

}

//Check Permissions
if(!hasPerm("oscmembership_view",$xoopsUser)) exit(_oscmem_access_denied);

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
			$this->Cell(190,10,$sChurchName . " - " . _oscmem_directory ,1,0,'C');
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
			$this->Cell(0,10, _oscmem_page . " " . $iPageNumber,0,0,'C');
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

$aClasses=array();

$count = 0;
$strCls="";
foreach ($_POST["sDirClassifications"] as $strCls)
{
	$aClasses[$count++] = $strCls; //FilterInput($Cls,'int');
}
$sDirClassifications = implode(",",$aClasses);

$count = 0;
$strCls="";

foreach ($_POST["GroupID"] AS $strCls)
{
	$aClasses[$count++] = $strCls; //FilterInput($Cls,'int');
}
$groups= implode(",",$aClasses);

$label_handler = &xoops_getmodulehandler('label', 'oscmembership');
$labelcritiera_handler = &xoops_getmodulehandler('labelcriteria', 'oscmembership');

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

/*
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
*/

$labels=$label_handler->getlabels(false, false, $groups,"",$labelcritiera);

$bDirUseTitlePage = isset($_POST["bDirUseTitlePage"]);

$baltFamilyName = isset($_POST["baltFamilyName"]);
$baltHeader = isset($_POST["baltHeader"]);

$bSortFirstName=0;
$bSortFirstName = isset($_POST["bSortFirstName"]);

if($baltFamilyName)
$baltHeader=true;

// Instantiate the directory class and build the report.
$pdf = new PDF_Directory();
if ($bDirUseTitlePage) $pdf->TitlePage();

$sLastLetter="";

foreach($labels as $label)
{
	$pdf->sRecordName = preg_replace("/\(0\/0\)/","",$label['recipient']);
//	$pdf->sLastName = $pdf->sRecordName;

	$body = $label['addresslabel'] . "\n" . $label['body'];		

//echo $body;
//Strip out empty lines
  $body=preg_replace("/" . _oscmem_workemail . ": &lt;br&gt;/","",$body);
  $body=preg_replace("/" . _oscmem_workphone . ": &lt;br&gt;/","",$body);
  $body=preg_replace("/" . _oscmem_cellphone . ": &lt;br&gt;/", "", $body);
  $body=preg_replace("/" . _oscmem_homephone . ": &lt;br&gt;/", "", $body);
  $body=preg_replace("/" . _oscmem_phone . ": &lt;br&gt;/","",$body);
  $body=preg_replace("/" . _oscmem_email . ": &lt;br&gt;/", "", $body);

	$body=preg_replace("/&lt;br&gt;/","&n;",$body);
	$body=preg_replace("/&n;&n;/","&n;",$body);
	$body=preg_replace("/&n;&n;/","&n;",$body);
	$body=preg_replace("/&n;&n;/","&n;",$body);
	$body=preg_replace("/&n;/","\n",$body);

	
//	echo $pdf->sLastName;
	
	// Count the number of lines in the output string
	if (strlen($pdf->sLastName . $body))
		$numlines = substr_count($pdf->sLastName . $body, "\n");
	else
		$numlines = 0;

//echo $numlines;

	if ($numlines > 0)
	{
		if (strtoupper($sLastLetter) != strtoupper(substr($pdf->sRecordName,0,1)))
		{
			$pdf->Check_Lines($numlines+2);
			$sLastLetter = strtoupper(substr($pdf->sRecordName,0,1));
			$pdf->Add_Header($sLastLetter);
		}
//		echo $body;
		$pdf->Add_Record($pdf->sRecordName, $body, $numlines);  // 
	}
	
}

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cachhe");

//exit;
if ($iPDFOutputType == 1)
	$pdf->Output("Directory-" . date("Ymd-Gis") . ".pdf", true);
else
	$pdf->Output();	
?>
