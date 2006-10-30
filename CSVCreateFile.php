<?php
/*******************************************************************************
 *
 *  filename    : CSVCreateFile.php
 *  last change : 2003-06-11
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
 *  copyright   : Copyright 2001-2003 Deane Barker, Chris Gebhardt
 *  copyright   : 2006 Steve McAtee
 ******************************************************************************/
include_once "../../mainfile.php";


if ( !is_object($xoopsUser) || !is_object($xoopsModule))  {
    exit("Access Denied");
}


require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/ReportConfig.php");
include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/fpdf151/fpdf.php";

require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/class_fpdf_labels.php";

require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if (file_exists(XOOPS_ROOT_PATH. "/modules" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) 
{
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/modinfo.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/modinfo.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/modinfo.php";

}
// Turn ON output buffering
//ob_start();

// Get Source and Format from the request object and assign them locally
$sSource = isset($_POST["Source"]);
$sSource= strtolower($sSource);
$sFormat=isset($_POST["Format"]);
$sFormat = strtolower($sFormat);

$bSkipIncompleteAddr = isset($_POST["SkipIncompleteAddr"]);
$bSkipNoEnvelope = isset($_POST["SkipNoEnvelope"]);
$bdropfamily = isset($_POST["dropfamily"]);
$bfirstlastorder = isset($_POST["firstnamelastorder"]);

$aClasses=array();

$count = 0;
$strCls="";
$sDirClassifications ="";
if(isset($_POST["sDirClassifications"]))
{
  foreach ($_POST["sDirClassifications"] as $strCls)
  {
  	$aClasses[$count++] = $strCls; //FilterInput($Cls,'int');
  }
  $sDirClassifications = implode(",",$aClasses);
}

$count = 0;
$strCls="";
$groups="";
if(isset($_POST["GroupID"]))
{
  foreach ($_POST["GroupID"] AS $strCls)
  {
  	$aClasses[$count++] = $strCls; //FilterInput($Cls,'int');
  }
  $groups= implode(",",$aClasses);
}

$label_handler = &xoops_getmodulehandler('label', 'oscmembership');
$labelcritiera_handler = &xoops_getmodulehandler('labelcriteria', 'oscmembership');

$labelcritiera=$labelcritiera_handler->create();


$labelcritiera->assignVar('bdiraddress',isset($_POST["baddress"]));
$labelcritiera->assignVar('bdirwedding',isset($_POST["bagemarried"]));
$labelcritiera->assignVar('bdirbirthday',isset($_POST["bbirthanniversary"]));
$labelcritiera->assignVar('bdirfamilyphone',isset($_POST["bhomephone"]));
$labelcritiera->assignVar('bdirfamilywork',isset($_POST["bworkphone"]));
$labelcritiera->assignVar('bdirfamilycell',isset($_POST["bcellphone"]));
$labelcritiera->assignVar('bdirfamilyemail',isset($_POST["bemail"]));
$labelcritiera->assignVar('bdirpersonalphone',isset($_POST["bhomephone"]));
$labelcritiera->assignVar('bdirpersonalwork',isset($_POST["bworkphone"]));
$labelcritiera->assignVar('bdirpersonalcell',isset($_POST["bcellphone"]));
$labelcritiera->assignVar('bdirpersonalemail',isset($_POST["bemail"]));
$labelcritiera->assignVar('bdirpersonalworkemail',isset($_POST["otheremail"]));


$labelcritiera->assignVar('benvelope',isset($_POST["benvelope"]));
$labelcritiera->assignVar('brole',isset($_POST["bfamilyrole"]));
$labelcritiera->assignVar('bfamilyname',isset($_POST["bfamilyname"]));


	$labels=$label_handler->getexport(false, false, $groups,"",$labelcritiera);

	header("Content-type: text/x-csv");
	header("Content-Disposition: attachment; filename=osc-export-" . date("Ymd-Gis") . ".csv");

	foreach($labels as $label)
	{
		echo $label['body'] . chr(13);
	}


// Turn OFF output buffering
//ob_end_flush();

?>
