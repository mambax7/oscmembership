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

include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/vcard.php";

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

$uploaddir = XOOPS_ROOT_PATH. '/uploads/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

move_uploaded_file($_FILES['userfile']['tmp_name'],$uploadfile);

$lines = file($uploadfile);
if (!$lines) 
{
	//throw error message
	//exit("Can't read the vCard file: $file");
}

$persondetail_handler = &xoops_getmodulehandler('person', 'oscmembership');

$person=$persondetail_handler->create();

$cards = parse_vcards($lines);
foreach ($cards as $card_name => $card) 
{

    $names = array('FN', 'TITLE', 'ORG', 'TEL', 'EMAIL', 'URL', 'ADR', 'BDAY', 'NOTE', 'INTERNET');

    $row = 0;

    foreach ($names as $name) {
        $properties = $card->getProperties($name);

        if ($properties) 
	{
        	foreach ($properties as $property) 
		{
                $types = $property->params['TYPE'];

		switch ($property->name)
		{
			case "ADR":

				$adrarr=explode(";",$property->value);

				if($adrarr[3]!=null)
				{
	
					if($adrarr[0]!=null)
					{
						$person->setVar('address1',$adrarr[1]);
						$person->setVar('address2',$adrarr[2]);
					}					
					else 
						$person->setVar('address1',$adrarr[2]);
	
					$person->setVar('city',$adrarr[3]);
					$person->setVar('state',$adrarr[4]);
					$person->setVar('zip',$adrarr[5]);

					switch(strtoupper(trim($adrarr[6])))
					{
						case "UNITED STATES OF AMERICA":
							$person->setVar('country','US');
							break;

						default:
							$person->setVar('country',$adrarr[6]);
						

					}
				}
				break;

			case "EMAIL":
				$person->setVar('email',$property->value);
				break;

			case "FN":
				$namearr=explode(" ",$property->value);
		
				$person->setVar('lastname',$namearr[2]);
				$person->setVar('middlename',$namearr[1]);
				$person->setVar('firstname',$namearr[0]);

				break;

			case "TEL":
			
				//Order: WORK, CELL
				switch($property->params["TYPE"][0])
				{
				case "WORK":
					$person->setVar('workphone',$property->value);
					break;

				case "CELL":
					$person->setVar('cellphone', $property->value);
					break;

				case "HOME":
					$person->setVar('homephone', $property->value);
					break;
				}

				break;
		}

            	}
        }
    }

//	$person->setVar("address1",$prop->value);

	
//	echo var_dump($prop);

		// Display the select file form
}

$iStage = 2;

$form = new XoopsThemeForm(_oscmem_vcardimport_step2, "importstep2form", "persondetailform.php", "post", true);

$firstname_text = new XoopsFormText(_oscmem_firstname, "firstname", 30, 50, $person->getVar('firstname'));

//$middlename_text = new XoopsFormText(_oscmem_middlename, "middlename", 30, 50, $person->getVar('middlename'));

$lastname_text=new XoopsFormText(_oscmem_lastname,"lastname",30,50,$person->getVar('lastname'));

$address_text=new XoopsFormText(_oscmem_address,"address1",30,50,$person->getVar('address1'));

$address2_text=new XoopsFormText("","address2",30,50,$person->getVar('address2'));

$city_text=new XoopsFormText(_oscmem_city,"city",30,50,$person->getVar('city'));
$state_text=new XoopsFormText(_oscmem_state,"state",30,50,$person->getVar('state'));
$zip_text=new XoopsFormText(_oscmem_post,"zip",30,50,$person->getVar('zip'));
$country_text = new XoopsFormSelectCountry(_oscmem_country, "country", $person->getVar('country'));

$workphone_text=new XoopsFormText(_oscmem_workphone,"workphone",30,50,$person->getVar('workphone'));

$homephone_text=new XoopsFormText(_oscmem_homephone,"homephone",30,50,$person->getVar('homephone'));

$cellphone_text=new XoopsFormText(_oscmem_cellphone,"cellphone",30,50,$person->getVar('cellphone'));
$email_text=new XoopsFormText(_oscmem_email,"email",30,50,$person->getVar('email'));

$op_action=new XoopsFormHidden("op","create");

$submit_button = new XoopsFormButton("", "persondetailsubmit", _osc_create, "submit");

$form->addElement($submit_button);
$form->addElement($firstname_text);
//$form->addElement($middlename_text);
$form->addElement($lastname_text);
$form->addElement($address_text);
$form->addElement($address2_text);
$form->addElement($city_text);
$form->addElement($state_text);
$form->addElement($zip_text);
$form->addElement($country_text);

$form->addElement($workphone_text);
$form->addElement($cellphone_text);
$form->addElement($homephone_text);
$form->addElement($email_text);

$form->addElement($submit_button);

$form->addElement($op_action);

$form->Display();
/*
	echo "<p style=\"color: red\">" . $csvError . "</p>";
	echo "<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\" enctype=\"multipart/form-data\">";
	echo "<input class=\"icTinyButton\" type=\"file\" name=\"CSVfile\"> <input type=\"submit\" class=\"icButton\" value=\"" . gettext("Upload CSV File") . "\" name=\"UploadCSV\">";
	echo "</form>";
*/

/**
 * Parses a set of cards from one or more lines. The cards are sorted by
 * the N (name) property value. There is no return value. If two cards
 * have the same key, then the last card parsed is stored in the array.
 */
function parse_vcards(&$lines)
{
    $cards = array();
    $card = new VCard();
    while ($card->parse($lines)) {
        $property = $card->getProperty('N');
        if (!$property) {
            return "";
        }
        $n = $property->getComponents();
        $tmp = array();
        if ($n[3]) $tmp[] = $n[3];      // Mr.
        if ($n[1]) $tmp[] = $n[1];      // John
        if ($n[2]) $tmp[] = $n[2];      // Quinlan
        if ($n[4]) $tmp[] = $n[4];      // Esq.
        $ret = array();
        if ($n[0]) $ret[] = $n[0];
        $tmp = join(" ", $tmp);
        if ($tmp) $ret[] = $tmp;
        $key = join(", ", $ret);
        $cards[$key] = $card;
        // MDH: Create new VCard to prevent overwriting previous one (PHP5)
        $card = new VCard();
    }
    ksort($cards);
    return $cards;
}

include(XOOPS_ROOT_PATH."/footer.php");
?>
