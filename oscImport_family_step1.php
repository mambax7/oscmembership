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

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/osclist.php';

include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/group.php';

//include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/churchdir.php';
include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");


include(XOOPS_ROOT_PATH."/header.php");

//$GLOBALS['xoopsOption']['template_main'] ="csvexport.html";




$iStage = 1;
$db = &Database::getInstance();

//$GLOBALS['xoopsOption']['template_main'] ="oscimport_family_step1.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

if(!hasPerm("oscmembership_modify",$xoopsUser)) exit(_oscmem_access_denied);

$submit_button = new XoopsFormButton("", "uploadfile", _oscmem_uploadfile, "submit");

//$form = new XoopsThemeForm(_oscmem_cvsimport_family_step1, "importstep1form", "oscImport_family_step2.php", "post", true);


$form = new XoopsThemeForm(_oscmem_cvsimport_family_step1, "importstep1form", "oscImport_family_step2.php", "post", true);

$fileupload=new XoopsFormFile(_oscmem_filetoupload,"userfile",1000000);
$form->addElement($fileupload);

$delimeter_select = new XoopsFormSelect(_oscmem_csvdelimiter,'delimiter' ,"value",1,false);
$delimiters=array("tab"=>_oscmem_tab,"comma"=>_oscmem_comma);

$delimeter_select->addOptionArray($delimiters);
$form->addElement($delimeter_select);

$form->addElement($submit_button);
$form->setExtra("enctype=\"multipart/form-data\"",true);

$form->Display();


include(XOOPS_ROOT_PATH."/footer.php");
?>
