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

//LoadLib_FPDF();
require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/class_fpdf_labels.php";

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

function generateCards($cart, $pdf)
{

	$pdf->SetFont('Times','',12);
	$pdf->AddPage(); // Create a new page

	//barcode defaults
	$style=196;
	$width=100;
	$height=55;
	$xres=2;
	$font=5;
	
	//Create barcode
	$out="<table border=1 width=400px>";
	$col=0;
	foreach($cart as $person)
	{
	
		$pdf->Add_PDF_Label(sprintf("%s\n%s\n%s, %s %s", $person->getVar('lastname'), $person->getVar('address1'), $person->getVar('city'), $person->getVar('state'),  $person->getVar('zip')));
	
		$code=sprintf("%06s",$person->getVar('id'));
		//$code=$person->getVar('id');
		$obj = new C39Object($width, $height, $style, $code);
		$obj->SetFont($font);   
		$obj->DrawObject($xres);

		$result=imagepng($obj->mImg, XOOPS_ROOT_PATH . "/uploads/" . $person->getVar('id') . ".png");
		
/*		
		$out.="<img src=" . XOOPS_URL . "/uploads/" . $person->getVar('id') . ".png>";
		$out.="<br><br><br><br>";
		$out.=$person->getVar('lastname') . ", " . $person->getVar('firstname') ;
*/
	}
//	$out.="</table>";
//	echo $out;
//	$this->WriteHTML($out);
}	


if(isset($_POST["labeltype"])) $sLabelType = $_POST["labeltype"];

$sLabelType="5160";

// Standard format
$startcol=1;
$startrow=1;
$pdf = new PDF_Label($sLabelType,$startcol,$startrow);
$pdf->Open();

//$pdf=new PDF('P','mm',$paperFormat);
//$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
generateCards($cart, $pdf);

$pdf->Output();

//wipe out pdf files
foreach($cart as $person)
{

	unlink(XOOPS_ROOT_PATH . "/uploads/" . $person->getVar('id') . ".png");
}
?>
