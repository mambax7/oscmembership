<?php
/*******************************************************************************
 *
 *  filename    : oscImport_family_step1.php
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


$iStage = 1;
$db = &Database::getInstance();

//$GLOBALS['xoopsOption']['template_main'] ="oscimport_family_step1.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

if(!hasPerm("oscmembership_modify",$xoopsUser)) exit(_oscmem_access_denied);


if(isset($_POST["delimiter"])) $passdelimiter=$_POST['delimiter'];

switch($passdelimiter):
	case "tab": 
		$delimiter="\t";
		break;
	case "comma": 
		$delimiter=",";
		break;
	default: $delimiter=",";
endswitch;
//echo "hello" .  basename($_FILES['uploadfile']['name']);
$uploaddir = XOOPS_ROOT_PATH. '/uploads/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

echo move_uploaded_file($_FILES['userfile']['tmp_name'],$uploadfile);

//prep file for mapping
// create the file pointer
$pFile = fopen ($uploadfile, "r");

// count # lines in the file
$iNumRows = 0;
while ($tmp = fgets($pFile,2048)) $iNumRows++;
rewind($pFile);

$family= new Family();

$props=$family->getVars();
$keys=array_filter(array_keys($props),"FilterProps");

$keys[0]=_oscmem_ignore;

$form = new XoopsThemeForm(_oscmem_cvsimport_family_step2, "importstep2form", "oscImport_family_step3.php", "post", true);

$hidden_uploadfile=new XoopsFormHidden("uploadfile",$uploadfile);
$form->addElement($hidden_uploadfile);

$submit_button = new XoopsFormButton("", "importfile", _oscmem_importfile, "submit");

$form->addElement($submit_button);

$chk_ignorefirst=new XoopsFormCheckBox(_oscmem_ignorefirstrow, "chkignorefirstrow");
$chk_ignorefirst->addOption("ignore",_oscmem_ignore);

$form->addElement($chk_ignorefirst);


// grab and display up to the first 8 lines of data in the CSV in a table
$map_select=Array();
$sample_data=Array();

$iRow = 0;
$aData=fgetcsv($pFile,2034,$delimiter);

$numCol=count($aData);
rewind($pFile);
while (($aData = fgetcsv($pFile, 2048,$delimiter)) && $iRow++ < 9)
{

	for ($col = 0; $col < $numCol; $col++) 
	{
		$map_select[$col]["sample"][$iRow]=$aData[$col];
	}

}

for ($col = 0; $col < $numCol; $col++) 
{
	$map_select[$col]["select"] = new XoopsFormSelect('','mapname' . $col,"value",1,false);
	$map_select[$col]["select"]->addOptionArray($keys);
}
//echo "</table>";

fclose($pFile);

$hidden_passdelim=new XoopsFormHidden("passdelim",$passdelimiter);
$hidden_numcols=new XoopsFormHidden("numcols",$numCol);
$form->addElement($hidden_numcols);
$form->addElement($hidden_passdelim);


$map_tray=Array();
$labels=Array();
//loop thru columns
for($i=0;$i<$col;$i++)
{
	$map_tray[$i]=new XoopsFormElementTray(_oscmem_column . " " . $i , '&nbsp;');

	$labels[$i]=new XoopsFormLabel("",implode(",",$map_select[$i]["sample"]));

	$map_tray[$i]->addElement($map_select[$i]["select"]);
	$map_tray[$i]->addElement($labels[$i]);

	$form->addElement($map_tray[$i]);
	
}



$form->Display();

include(XOOPS_ROOT_PATH."/footer.php");
?>
