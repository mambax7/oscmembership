<?php
/*******************************************************************************
 *
 *  filename    : oscImport_family_step3.php
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
 *  Copyright 2007 Steve McAtee
 ******************************************************************************/

include_once "../../mainfile.php";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/ReportConfig.php");

require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php");

// Include the function library
//require "Include/Config.php";
//require "Include/Functions.php";


include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/person.php';

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/family.php';

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/osclist.php';

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/group.php';

//include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/churchdir.php';


include(XOOPS_ROOT_PATH."/header.php");

//$GLOBALS['xoopsOption']['template_main'] ="csvexport.html";

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH."/class/uploader.php";

if(isset($_POST["uploadfile"])) $uploadfile=$_POST["uploadfile"];
if(isset($_POST["numcols"])) $numCol=$_POST["numcols"];
if(isset($_POST['chkignorefirstrow']))  $ignorefirstrow=$_POST['chkignorefirstrow'];

$iStage = 1;
$db = &Database::getInstance();

//$GLOBALS['xoopsOption']['template_main'] ="oscimport_family_step1.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

if(!hasPerm("oscmembership_modify",$xoopsUser)) exit(_oscmem_access_denied);

if(isset($_POST["passdelim"])) $passdelimiter=$_POST['passdelim'];

switch($passdelimiter):
	case "tab": 
		$delimiter="\t";
		break;
	case "comma": 
		$delimiter=",";
		break;
	default: $delimiter=",";
endswitch;


$family_handler = &xoops_getmodulehandler('family', 'oscmembership');

//prep file for mapping
// create the file pointer
$pFile = fopen ($uploadfile, "r");

$family= new Family();

$props=$family->getVars();
$keys=array_filter(array_keys($props),"FilterProps");
$keys[0]=_oscmem_ignore;

//map keys to map

//loop thru file and import
//Display success form

//if ignore read and discard first row
if($ignorefirstrow=="ignore") $aData = fgetcsv($pFile, 2048,$delimiter);

while (($aData = fgetcsv($pFile, 2048,$delimiter)))
{
	$family_new=new Family();
	for ($col = 0; $col < $numCol; $col++) 
	{

/*		echo "<br>";
		echo "col:" . $col;
		echo "data:" . $aData[$col];
		echo "mapname:" . $_POST['mapname'.$col];
		echo "keyname:" . $keys[$_POST['mapname' . $col]];
*/
		if($keys[$_POST['mapname' . $col]]!=_oscmem_ignore)
		{
			$family_new->assignVar($keys[$_POST['mapname' . $col]],$aData[$col]);
		}
		//update 
		$family_new->assignVar('dateentered',date('y-m-d g:i:s'));
		$family_new->assignVar('enteredby',$xoopsUser->getVar('uid'));		
		$family_new->assignVar('datelastedited',date('y-m-d g:i:s'));
		$family_new->assignVar('editedby',$xoopsUser->getVar('uid'));

	}
	$family_handler->insert($family_new);

	$iRow++;
}

fclose($pFile);


$form = new XoopsThemeForm(_oscmem_cvsimport_family_step3, "importstep3form", "", "post", true);

$labelsuccess=new XoopsFormLabel(_oscmem_import_family,_oscmem_success);
$labelimportcount=new XoopsFormLabel(_oscmem_recordsimported,$iRow);

$form->addElement($labelsuccess);
$form->addElement($labelimportcount);
$form->Display();

include(XOOPS_ROOT_PATH."/footer.php");
?>
