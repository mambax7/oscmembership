<?php
/*******************************************************************************
 *
 *  filename    : DirectoryReports.php
 *  last change : 2003-09-03
 *  description : form to invoke directory report
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
 *  Modified 2006, Steve McAtee
 *  Original Copyright 2003 Chris Gebhardt
 ******************************************************************************/
include_once "../../mainfile.php";

//if (!isset($xoopsOption['nocommon']) && XOOPS_ROOT_PATH != '') 
//{
//	require XOOPS_ROOT_PATH."/include/common.php";
//}

//$xoopsOption['template_main'] = 'cs_index.html';


include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");
include_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/class/person.php");

//include_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php");

// include the default language file for the admin interface
if ( file_exists( "../language/" . $xoopsConfig['language'] . "/main.php" ) ) {
    include "../language/" . $xoopsConfig['language'] . "/main.php";
}
elseif ( file_exists( "../language/english/main.php" ) ) {
    include "../language/english/main.php";
}

//require_once XOOPS_ROOT_PATH.'/kernel/object.php';

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if(!hasPerm("oscmembership_view",$xoopsUser)) exit(_oscmem_access_denied);

//require "../Include/Config.php";
//require "include/oscleg_Functions.php";
require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/ReportConfig.php");
//require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/ReportFunctions.php");

include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/fpdf151/fpdf.php";


// Load the FPDF library
//LoadLib_FPDF();
require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/class_fpdf_labels.php";

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/person.php';

function GenerateLabels(&$pdf, $mode, $uid, $bOnlyComplete = false, $bremoveFamily=false)
{
	$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
	$results = $person_handler->getCart($uid);

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
				//$persons[$i]['lastname']=$person->getVar('lastname');
			}
//			if (!$bOnlyComplete || ( (strlen($sAddress)) && strlen($sCity) && strlen($sState) && strlen($sZip) ) )
			if(strlen($person->getVar('address1')))
			{
				$pdf->Add_PDF_Label(sprintf("%s\n%s\n%s, %s %s", $person->getVar('firstname') . ' ' .$person->getVar('lastname'), $person->getVar('address1'), $person->getVar('city'), $person->getVar('state'),  $person->getVar('zip')));
			}
			
			
			
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

$sLabelType="5160";

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
GenerateLabels($pdf, $mode, $xoopsUser->getVar('uid'), $bOnlyComplete, $bremoveFamily);

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
?>