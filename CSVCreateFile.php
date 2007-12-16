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

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}


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
// Turn ON output buffering
//ob_start();

// Get Source and Format from the request object and assign them locally
if (isset($_POST['Source'])) $sSource = strtolower($_POST['Source']);
if (isset($_POST['Format'])) $sFormat = strtolower($_POST['Format']);

$bSkipIncompleteAddr = isset($_POST["SkipIncompleteAddr"]);
$bSkipNoEnvelope = isset($_POST["SkipNoEnvelope"]);
$bdropfamily = isset($_POST["dropfamily"]);
$bfirstlastorder = isset($_POST["firstnamelastorder"]);

if (isset($_POST['gender'])) $gender=$_POST['gender'];

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

$db = &Database::getInstance();
$persondetail_handler = &xoops_getmodulehandler('person', 'oscmembership');
$customFields = $persondetail_handler->getcustompersonFields();

$i=0;
$custfieldarr=array();

while($row = $db->fetchArray($customFields)) 
{
	if(isset($_POST[$row["custom_Field"]]))
	{
		$custfieldarr[$i]['custom_Field']=$row["custom_Field"];
		$custfieldarr[$i]['custom_Name']=$row["custom_Name"];
	}
	$i++;
}


$label_handler = &xoops_getmodulehandler('label', 'oscmembership');
$labelcritiera_handler = &xoops_getmodulehandler('labelcriteria', 'oscmembership');

$labelcritiera=$labelcritiera_handler->create();

$labelcritiera->assignVar('gender',$gender);
$labelcritiera->assignVar('sdirclassifications',$sDirClassifications);

$labelcritiera->assignVar('customfields',$custfieldarr);
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

$labelcritiera->assignVar('bincompleteaddress',isset($_POST["bincompleteaddress"]));

$labelcritiera->assignVar('benvelope',isset($_POST["benvelope"]));
$labelcritiera->assignVar('brole',isset($_POST["bfamilyrole"]));
$labelcritiera->assignVar('bfamilyname',isset($_POST["bfamilyname"]));

if(isset($_POST["soutputmethod"]))
{
	$outputmethod=$_POST["soutputmethod"];
	$labelcritiera->assignVar('soutputmethod',$_POST["soutputmethod"]);
}

if(isset($_POST["memberdatefrom"])) 
{
	if($_POST["memberdatefrom"]!="YYYY/MM/DD")
	{
	$labelcritiera->assignVar('membershipdatefrom',$_POST["memberdatefrom"]);
	$labelcritiera->assignVar('membershipdateto',$_POST["memberdateto"]);
	}
}

if(isset($_POST["birthdaymonthfrom"]))
{
	$labelcritiera->assignVar('birthdaymonthfrom',$_POST["birthdaymonthfrom"]);
	$labelcritiera->assignVar('birthdaymonthto',$_POST["birthdaymonthto"]);
}

if(isset($_POST["birthdayyearfrom"]))
{
	$labelcritiera->assignVar('birthdayyearfrom',$_POST["birthdayyearfrom"]);
	$labelcritiera->assignVar('birthdayyearto',$_POST["birthdayyearto"]);
}

if(isset($_POST["anniversaryfrom"]))
{
	if($_POST["anniversaryfrom"]!="YYYY/MM/DD")
	{
	$labelcritiera->assignVar('anniversaryfrom',$_POST["anniversaryfrom"]);
	$labelcritiera->assignVar('anniversaryto',$_POST["anniversaryto"]);
	}
}

if(isset($_POST["dateenteredfrom"]))
{
	if($_POST["dateenteredfrom"]!="YYYY/MM/DD")
	{
	$labelcritiera->assignVar('dateenteredfrom',$_POST["dateenteredfrom"]);
	$labelcritiera->assignVar('dateenteredto',$_POST["dateenteredto"]);
	}
}

if(isset($_POST["sfilters"])) $filter=$_POST["sfilters"];
//determine if source is to be from cart
$xoopsuid=$xoopsUser->getVar("uid");

if($filter==_oscmem_fromcart)
{
	$labels=$label_handler->getexportfromcart(false, false, $groups,$labelcritiera, $xoopsuid);
}
else
{
	$labels=$label_handler->getexport(false, false, $groups,$labelcritiera);
}

if($outputmethod==_oscmem_csv_addtocart)
{
	//Add labels to cart
	foreach($labels as $label)
	{
		$persondetail_handler->addtoCart($label["person_id"],$xoopsuid);
	}
	redirect_header("index.php", 3, _oscmem_addedtocart);

}
else
{
//Create Dump file
	header("Content-type: text/x-csv");
	header("Content-Disposition: attachment; filename=osc-export-" . date("Ymd-Gis") . ".csv");
	
		foreach($labels as $label)
		{
			echo $label['body'] . chr(10) . chr(13);
		}
}

// Turn OFF output buffering
//ob_end_flush();

?>