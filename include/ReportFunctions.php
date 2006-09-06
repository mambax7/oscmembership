<?php
/*******************************************************************************
 *
 *  filename    : /Include/ReportFunctions.php
 *  last change : 2003-03-20
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
 *  Copyright 2003 Chris Gebhardt
 ******************************************************************************/

// Try to find and load the FPDF library, giving preference to a user-specified copy
function LoadLib_FPDF()
{
	
	define('FPDF_FONTPATH',XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/fpdf151/font/");

	require (XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/fpdf151/fpdf.php");
	
	
}

// Finds and loads the base JPGraph library and any components specified as arguments
//
// ****  Example syntax:  LoadLib_JPGraph(pie,pie3d);  ****
// This would load jpgraph.php, jpgraph_pie.php, and jpgraph_pie3d.php

/*
function LoadLib_JPGraph()
{
	$numargs = func_num_args();
	$arg_list = func_get_args();

	global $sJPGRAPH_PATH;

	// Check if the Config.php given path is absolute
	if ($sJPGRAPH_PATH{0} == "/" || substr($sJPGRAPH_PATH,1,2) == ":\\")
		$sJPGRAPHpath = $sJPGRAPH_PATH . "/";
	else
		$sJPGRAPHpath = "../" . $sJPGRAPH_PATH . "/";

	// If JPGraph is not found at user specified path, fall back to PHP include_path, or else exit with error.
	if (!is_readable($sJPGRAPHpath . "jpgraph.php"))
	{
		if (is_readable('jpgraph.php'))
			$sJPGRAPHpath= "";
		else {
			echo "<h2>" . gettext("ERROR: JPGraph Library was not found in your path <i>or</i> at: ") . $sJPGRAPHpath;
			exit;
		}
	}

	// If all went well, load the requested libraries
	require $sJPGRAPHpath . "jpgraph.php";
	for ($i = 0; $i < $numargs; $i++) {
		require $sJPGRAPHpath . "jpgraph_" . $arg_list[$i] . ".php";
	}
}

function LoadLib_PHPMailer()
{
	global $sPHPMAILER_PATH;

	// Check if the Config.php given path is absolute
	if ($sPHPMAILER_PATH{0} == "/" || substr($sPHPMAILER_PATH,1,2) == ":\\")
		$sPHPMAILERpath = $sPHPMAILER_PATH . "/";
	else
		$sPHPMAILERpath = "./" . $sPHPMAILER_PATH . "/";

	// If PHPMailer is not found at user specified path, fall back to PHP include_path, or else exit with error.
	if (!is_readable($sPHPMAILERpath . "class.phpmailer.php"))
	{
		if (is_readable('class.phpmailer.php'))
			$sPHPMAILERpath= "";
		else {
			echo "<h2>" . gettext("ERROR: PHPMailer Library was not found in your path <i>or</i> at: ") . $sPHPMAILERpath;
			exit;
		}
	}
	// If all went well, load the requested libraries
	require $sPHPMAILERpath . "class.phpmailer.php";
	require $sPHPMAILERpath . "class.smtp.php";

	// Define parameters as class ICMail
	class ICMail extends PHPMailer {
		// Set default variables for all new objects
		var $From;
		var $FromName;
		var $Mailer;
		var $WordWrap;
		var $Host;
		var $SMTPAuth;
		var $Username;
		var $Password;
		function ICMail() {
			$this->From = $GLOBALS['sFromEmailAddress'];
			$this->FromName = $GLOBALS['sFromName'];
			$this->Mailer = $GLOBALS['sSendType'];
			$this->WordWrap = $GLOBALS['sWordWrap'];
			if ($this->Mailer == "smtp")
			{
				$this->Host = $GLOBALS['sSMTPHost'];
				$this->SMTPAuth = $GLOBALS['sSMTPAuth'];
				if ($this->SMTPAuth) {
					$this->Username = $GLOBALS['sSMTPUser'];
					$this->Password = $GLOBALS['sSMTPPass'];
				}
			}
		}
	}
}
*/
?>
