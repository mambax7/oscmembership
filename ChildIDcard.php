<?php
/*******************************************************************************
 *
 *  filename    : MemberIDCard.php
 *  last change : 2007-07-04
 *  description : form to invoke directory report
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
 *  Modified 2006, Steve McAtee
 ******************************************************************************/
include_once "../../mainfile.php";

include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");
include_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/class/person.php");

// include the default language file for the admin interface
if ( file_exists( "../language/" . $xoopsConfig['language'] . "/main.php" ) ) {
    include "../language/" . $xoopsConfig['language'] . "/main.php";
}
elseif ( file_exists( "../language/english/main.php" ) ) {
    include "../language/english/main.php";
}

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

if (file_exists(XOOPS_ROOT_PATH. "/modules/" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) 
{
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/modinfo.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/modinfo.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/modinfo.php";

}

include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if(!hasPerm("oscmembership_view",$xoopsUser)) exit(_oscmem_access_denied);

require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/ReportConfig.php");

include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/fpdf151/fpdf.php";

//Setup Barcode inclusion
require(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') .  "/barcode/barcode.php");  
require(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') .  "/barcode/c39object.php");


// Load the FPDF library
//LoadLib_FPDF();
require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/class_fpdf_labels.php";

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/person.php';

$churchdetail_handler = &xoops_getmodulehandler('churchdetail', 'oscmembership');

$churchdetail=$churchdetail_handler->get();
$person_handler = &xoops_getmodulehandler('person', 'oscmembership');

$cart = $person_handler->getCartc($xoopsUser->getVar('uid'));

$person=$person_handler->create(false);

function GenerateLabels(&$pdf, $mode, $uid, $cart, $bOnlyComplete = false, $bremoveFamily=false)
{

	//barcode defaults
	$style=196;
	$width=100;
	$height=55;
	$xres=2;
	$font=5;

	foreach($cart as $person)
	{
		if(strlen($person->getVar('address1')))
		{
			
			$code=sprintf("%06s",$person->getVar('id'));
			//$code=$person->getVar('id');
			$obj = new C39Object($width, $height, $style, $code);
			$obj->SetFont($font);   
			$obj->DrawObject($xres);
				
			$result=imagepng($obj->mImg, XOOPS_ROOT_PATH . "/uploads/" . $person->getVar('id') . ".png");

			$image=XOOPS_ROOT_PATH . "/uploads/" . $person->getVar('id') . ".png";
			
			$textleft=sprintf("%s\n%s\n%s\n%s, %s %s", _oscmem_child , $person->getVar('lastname') . ", " . $person->getVar('firstname'), $person->getVar('address1'), $person->getVar('city'), $person->getVar('state'),  $person->getVar('zip'));
			
			$textright=sprintf("%s\n%s\n%s\n%s, %s %s", _oscmem_parent , $person->getVar('lastname') . ", " . $person->getVar('firstname'), $person->getVar('address1'), $person->getVar('city'), $person->getVar('state'),  $person->getVar('zip'));
			
			
			$pdf->Add_PDF_LabelSidebySide($textleft, $textright,$image);

		}
	}
}

$startcol=1;
$startrow=1;
$sLabelType="";

if(isset($_POST["startcol"])) $startcol = $_POST["startcol"];

if ($startcol < 1) $startcol = 1;

if(isset($_POST["startrow"])) $startrow = $_POST["startrow"];
if ($startrow < 1) $startrow = 1;

if(isset($_POST["labeltype"])) $sLabelType = $_POST["labeltype"];

$sLabelType="8371";

// Standard format
$pdf = new PDF_Label($sLabelType,$startcol,$startrow);
$pdf->Open();

// Manually add a new page if we're using offsets
if ($startcol > 1 || $startrow > 1)	$pdf->AddPage();

if(isset($_GET["mode"])) $mode = $_GET["mode"];

if(isset($_GET["onlyfull"])) 
{$bOnlyComplete = ($_GET["onlyfull"] == 1);}
else $bOnlyComplete=false;

if(isset($_GET["removefamily"])) $bremoveFamily = ($_GET["removefamily"] == 1);
else $bremoveFamily=false;

$mode=null;
GenerateLabels($pdf, $mode, $xoopsUser->getVar('uid'), $cart, $bOnlyComplete, $bremoveFamily);

//kill cache
//use session_cache_limiter('none');

//make sure nothing caches

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cachhe");

//get_headers(); --see what is going on.

if ($iPDFOutputType == 1)
	{
	$pdf->Output("Labels-" . date("Ymd-Gis") . ".pdf", true);
	
	}
else
	$pdf->Output();

//wipe out pdf files
foreach($cart as $person)
{

	unlink(XOOPS_ROOT_PATH . "/uploads/" . $person->getVar('id') . ".png");
}


?>