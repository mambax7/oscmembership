<?php
/*******************************************************************************
 *
 *  filename    : MemberIDcard.php
 *  last change : 2007-06-26
 *
 *  http://osc.sourceforge.net
 *
 *  OpenSourceChurch (OSC) is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 * 
 *  Any changes to the software must be submitted back to the OpenSourceChurch project
 *  for review and possible inclusion.
 *
 *  Copyright 2007, Steve McAtee
 ******************************************************************************/

include_once "../../mainfile.php";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/ReportConfig.php");

require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/html2fpdf/html2fpdf.php");
//include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/fpdf151/fpdf.php";

//require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/class_fpdf_labels.php";

require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if(hasPerm("oscgiving_modify",$xoopsUser)) 
{
$ispermmodify=true;
}
if(!($ispermmodify==true) & !($xoopsUser->isAdmin($xoopsModule->mid())))
{
    redirect_header(XOOPS_URL , 3, _oscgiv_accessdenied);
}

if(isset($_GET['year'])) $year=$_GET['year'];

if (file_exists(XOOPS_ROOT_PATH. "/modules/" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) 
{
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/modinfo.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/modinfo.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/modinfo.php";

}

//Setup Barcode inclusion
require(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') .  "/barcode/barcode.php");  
require(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') .  "/barcode/c39object.php");

// Avoid a bug in FPDF..
setlocale(LC_NUMERIC,'C');

$setting_handler = &xoops_getmodulehandler('givsetting', 'oscgiving');
$oscgivsetting = $setting_handler->getSetting();

$churchdetail_handler = &xoops_getmodulehandler('churchdetail', 'oscmembership');
	
$churchdetail=$churchdetail_handler->get();
$person_handler = &xoops_getmodulehandler('person', 'oscmembership');

$cart = $person_handler->getCartc($xoopsUser->getVar('uid'));

$person=$person_handler->create(false);

// Load the FPDF library
//LoadLib_FPDF();

class PDF extends HTML2FPDF
{

	//Page header
	function Header()
	{
		global $sExemptionLetter_Letterhead;

		// if ($this->PageNo() == 1)
		// {
//			if (is_readable($sExemptionLetter_Letterhead))
//				$this->Image($sExemptionLetter_Letterhead,10,5,190);
//			$this->Ln(30);
		// }
		// else
		//	$this->Ln(10);
	}

	//Page footer
	function Footer()
	{
		global $churchdetail;
		$footer=$churchdetail->getVar('churchname') . " " . $churchdetail->getVar('address1') . " " . $churchdetail->getVar('city') . ", " . $churchdetail->getVar('state') . " " . $churchdetail->getVar('zip') . "  " . _oscmem_phone . ":" . $churchdetail->getVar('phone') . "  " . _oscmem_fax . ":" . $churchdetail->getVar('fax') . "  " . _oscmem_website . ":" . $churchdetail->getVar('website');
		
				// if ($this->PageNo() == 1){
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		$this->SetFont('Arial','',9);
		$this->SetLineWidth(0.5);
		$this->Cell(0,10,$footer,'T',0,'C');

	}

	function generateCards($cart)
	{

		$this->SetFont('Times','',12);
		$this->AddPage(); // Create a new page

		$style=196;
		$width=100;
		$height=55;
		$xres=2;
		$font=5;
		
		//Create barcode
		$out="<table>";
		foreach($cart as $person)
		{
			$code=sprintf("%06s",$person->getVar('id'));
			//$code=$person->getVar('id');
			$obj = new C39Object($width, $height, $style, $code);
			$obj->SetFont($font);   
      			$obj->DrawObject($xres);

			$result=imagepng($obj->mImg, XOOPS_ROOT_PATH . "/uploads/" . $person->getVar('id') . ".png");
			
			$out.="<tr><td>";
			$out.="<img src=" . XOOPS_URL . "/uploads/" . $person->getVar('id') . ".png>";
			$out.="</td><td>";
			$out.=$person->getVar('lastname') . ", " . $person->getVar('firstname') . "</td></tr>";

		}
		$out.="</table>";
		$this->WriteHTML($out);
	}	
}

$pdf=new PDF('P','mm',$paperFormat);
$pdf->Open();
$pdf->AliasNbPages();

$pdf->generateCards($cart);

$pdf->Output();

//wipe out pdf files
foreach($cart as $person)
{

	unlink(XOOPS_ROOT_PATH . "/uploads/" . $person->getVar('id') . ".png");
}
?>
