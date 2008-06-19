<?php
/*******************************************************************************
 *
 *  filename    : oscImportvcard.php
 *  last change : 2008-6-10
 *  description : Tool for importing vcard person data oscmembership
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
 *  Copyright 2008 Steve McAtee
 ******************************************************************************/

include_once "../../mainfile.php";
ini_set("memory_limit","100M");

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/ReportConfig.php");

require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php");

include_once XOOPS_ROOT_PATH . "/include/vcard.php";

// Include the function library
//require "Include/Config.php";
//require "Include/Functions.php";


include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/person.php';

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/osclist.php';

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/group.php';

//include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/churchdir.php';

include(XOOPS_ROOT_PATH."/header.php");

//$GLOBALS['xoopsOption']['template_main'] ="csvexport.html";


include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

$iStage = 1;
$db = &Database::getInstance();

if(!hasPerm("oscmembership_modify",$xoopsUser))     
	redirect_header(XOOPS_URL, 3, _oscmem_accessdenied);
/*
// Is the CSV file being uploaded?
if (isset($_POST["Uploadvcard"]))
{
	// Check if a valid CSV file was actually uploaded
	if ($_FILES['vcardfile']['name'] == "")
	{
		$csvError = gettext("No file selected for upload.");
	}

	// Valid file, so save it and display the import mapping form.
	else
	{
		$system_temp = ini_get("session.save_path");
		$vcardTempFile = $system_temp . "/import.vcard";
		move_uploaded_file($_FILES['vcardfile']['tmp_name'], $vcardTempFile);


		$lines = file($vcardTempFile);
		if (!$lines) 
		{
			//throw error message
			//exit("Can't read the vCard file: $file");
		}
		
		$cards = parse_vcards($lines);
		$prop=$card->getProperty("ADR");   //N
		echo var_dump($prop);

		echo "<br><br>" . $prop->value;
	}
}
	// Display the select file form
*/
$iStage = 1;
$submit_button = new XoopsFormButton("", "uploadfile", _oscmem_uploadfile, "submit");

$form = new XoopsThemeForm(_oscmem_vcardimport_step1, "importstep1form", "oscImportvcard_step2.php", "post", true);

$fileupload=new XoopsFormFile(_oscmem_filetoupload,"userfile",1000000);
$form->addElement($fileupload);

$form->setExtra("enctype=\"multipart/form-data\"",true);

$form->addElement($submit_button);
$form->Display();

/*
	echo "<p style=\"color: red\">" . $csvError . "</p>";
	echo "<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\" enctype=\"multipart/form-data\">";
	echo "<input class=\"icTinyButton\" type=\"file\" name=\"CSVfile\"> <input type=\"submit\" class=\"icButton\" value=\"" . gettext("Upload CSV File") . "\" name=\"UploadCSV\">";
	echo "</form>";
*/
include(XOOPS_ROOT_PATH."/footer.php");
?>
