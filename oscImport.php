<?php
/*******************************************************************************
 *
 *  filename    : oscImport.php
 *  last change : 2003-10-02
 *  description : Tool for importing CSV person data oscmembership
 *  migrated: 2006-11-27, Steve Mcatee
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
 *  Copyright 2006 Steve McAtee
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

// Is the CSV file being uploaded?
if (isset($_POST["UploadCSV"]))
{
	// Check if a valid CSV file was actually uploaded
	if ($_FILES['CSVfile']['name'] == "")
	{
		$csvError = gettext("No file selected for upload.");
	}

	// Valid file, so save it and display the import mapping form.
	else
	{
		$system_temp = ini_get("session.save_path");
		$csvTempFile = $system_temp . "/import.csv";
		move_uploaded_file($_FILES['CSVfile']['tmp_name'], $csvTempFile);

		// create the file pointer
		$pFile = fopen ($csvTempFile, "r");

		// count # lines in the file
		$iNumRows = 0;
		while ($tmp = fgetcsv($pFile,2048,",","'")) $iNumRows++;
		rewind($pFile);

		// create the form
		?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

		<?php
		echo gettext("Total number of rows in the CSV file:") . $iNumRows;
		echo "<br><br>";
		echo "<table border=1>";

		// grab and display up to the first 8 lines of data in the CSV in a table
		$iRow = 0;
		while (($aData = fgetcsv($pFile, 2048, ",","'")) && $iRow++ < 9)
		{
			$numCol = count($aData);

			echo "<tr>";
			for ($col = 0; $col < $numCol; $col++) {
				echo "<td>" . $aData[$col] . "&nbsp;</td>";
			}
			echo "</tr>";
		}

		fclose($pFile);


		$sSQL = "SELECT * FROM " . $db->prefix("oscmembership_person_custom_master") . " ORDER BY custom_Order";
		
		$rsCustomFields = $db->query($sSQL);

		// add select boxes for import destination mapping
		for ($col = 0; $col < $numCol; $col++)
		{
		?>
		
			<td>
			<select name="<?php echo "col" . $col;?>">
				<option value="0"><?php echo gettext("Ignore this Field"); ?></option>
				<option value="1"><?php echo gettext("Title"); ?></option>
				<option value="2"><?php echo gettext("First Name"); ?></option>
				<option value="3"><?php echo gettext("Middle Name"); ?></option>
				<option value="4"><?php echo gettext("Last Name"); ?></option>
				<option value="5"><?php echo gettext("Suffix"); ?></option>
				<option value="6"><?php echo gettext("Gender"); ?></option>
				<option value="7"><?php echo gettext("Donation Envelope"); ?></option>
				<option value="8"><?php echo gettext("Address1"); ?></option>
				<option value="9"><?php echo gettext("Address2"); ?></option>
				<option value="10"><?php echo gettext("City"); ?></option>
				<option value="11"><?php echo gettext("State"); ?></option>
				<option value="12"><?php echo gettext("Zip"); ?></option>
				<option value="13"><?php echo gettext("Country"); ?></option>
				<option value="14"><?php echo gettext("Home Phone"); ?></option>
				<option value="15"><?php echo gettext("Work Phone"); ?></option>
				<option value="16"><?php echo gettext("Mobile Phone"); ?></option>
				<option value="17"><?php echo gettext("Email"); ?></option>
				<option value="18"><?php echo gettext("Work / Other Email"); ?></option>
				<option value="19"><?php echo gettext("Birth Date"); ?></option>
				<option value="20"><?php echo gettext("Membership Date"); ?></option>

				<?php
				mysql_data_seek($rsCustomFields,0);
				while ($aRow = mysql_fetch_array($rsCustomFields))
				{
					extract($aRow);
					// No easy way to import person-from-group or custom-list types
					if ($type_ID != 9 && $type_ID != 12)
						echo "<option value=\"" . $custom_Field . "\">" . $custom_Name . "</option>";
				}
				?>
			</select>
			</td>
		<?php
		}

		echo "</table>";
		?>
		<BR>
		<input type="checkbox" value="1" name="IgnoreFirstRow"><?php echo gettext("Ignore first CSV row (to exclude a header)"); ?>
		<BR><BR>
		<select name="DateMode">
			<option value="1">YYYY-MM-DD</option>
			<option value="2">MM-DD-YYYY</option>
			<option value="3">DD-MM-YYYY</option>
		</select>
		<?php echo gettext("NOTE: Separators (dashes, etc.) or lack thereof do not matter"); ?>
		<BR><BR>
		<?php
			//$sCountry = $sDefaultCountry;
			//require "Include/CountryDropDown.php";
			$countrySelect = new XoopsFormSelectCountry("caption","country");
			
			echo $countrySelect->render() . _oscmem_defaultcountry_text;

			$osclist_handler = &xoops_getmodulehandler('osclist', 'oscmembership');
			$osclist = $osclist_handler->create();
			$osclist->assignVar('id','1');

			$optionItems = $osclist_handler->getitems($osclist);

			$option_array=array();
			$osclistitems = $osclist_handler->create();
}

//			$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 1 AND chu_Church_ID=" . $_SESSION['iChurchID'] . " ORDER BY lst_OptionSequence";
//			$rsClassifications = RunQuery($sSQL);
		?>
		<BR><BR>
		<select name="Classification">
			<option value="0"><?php echo gettext("Unassigned"); ?></option>
			<option value="0">-----------------------</option>
			<?php
				foreach($optionItems as $osclist)
				{
					extract($aRow);
					echo "<option value=\"" .  $osclist['optionid'] . "\"";
					echo ">" . $osclist['optionname'] . "&nbsp;";
				}
			?>
		</select>
		<?php echo gettext("Classification"); ?>
		<BR><BR>
		<input type="submit" class="icButton" value="<?php echo gettext("Perform Import"); ?>" name="DoImport">
		</form>

		<?php
		$iStage = 2;
	}


// Has the import form been submitted yet?
if (isset($_POST["DoImport"]))
{
	$system_temp = ini_get("session.save_path");
	$csvTempFile = $system_temp . "/import.csv";

	// make sure the file still exists
	if (file_exists($csvTempFile))
	{
		// create the file pointer
		$pFile = fopen ($csvTempFile, "r");

		$bHasCustom = false;
		if(isset($_POST["Country"])) $sDefaultCountry = $_POST["Country"];
		if(isset($_POST["Classification"])) $iClassID = $_POST["Classification"];
		if(isset($_POST["DateMode"])) $iDateMode = $_POST["DateMode"];
		
		// Get the number of CSV columns for future reference
		$aData = fgetcsv($pFile, 2048, ",","'");
		$numCol = count($aData);
		if (!isset($_POST["IgnoreFirstRow"])) rewind($pFile);

		// Put the column types from the mapping form into an array
		for ($col = 0; $col < $numCol; $col++)
		{
			if (substr($_POST["col" . $col],0,1) == "c")
			{
				$aColumnCustom[$col] = 1;
				$bHasCustom = true;
			}
			else
			{
				$aColumnCustom[$col] = 0;
			}
			$aColumnID[$col] = $_POST["col" . $col];
		}

		if ($bHasCustom)
		{
			$sSQL = "SELECT * FROM " . $db->prefix("oscmembership_person_custom_master");
			$rsCustomFields = $db->query($sSQL);

			while ($aRow = mysql_fetch_array($rsCustomFields))
			{
				extract($aRow);
				$aCustomTypes[$custom_Field] = $type_ID;
			}
		}
		//
		// Need to lock the person_custom and person_per tables!!
		//

		$aPersonTableFields = array (
				1=>"title", 2=>"firstname", 3=>"middlename", 4=>"lastname",
				5=>"suffix", 6=>"gender", 7=>"envelope", 8=>"address1", 9=>"address2",
				10=>"city", 11=>"state", 12=>"zip", 13=>"country", 14=>"homephone",
				15=>"workphone", 16=>"cellphone", 17=>"email", 18=>"workemail",
				20=>"membershipdate"
				);

		$importCount = 0;

		while ($aData = fgetcsv($pFile, 2048, ",","'"))
		{
			// Use the default country from the mapping form in case we don't find one otherwise
			$sCountry = $sDefaultCountry;

			$sSQLpersonFields = "INSERT INTO " . $db->prefix("oscmembership_person") . " (";
			$sSQLpersonData = " VALUES (";
			$sSQLcustom = "UPDATE " . $db->prefix("oscmembership_person_custom") . " SET ";

			// Build the person_per SQL first.
			// We do this in case we can get a country, which will allow phone number parsing later
			for ($col = 0; $col < $numCol; $col++)
			{
				// Is it not a custom field?
				if (!$aColumnCustom[$col])
				{
					$currentType = $aColumnID[$col];

					// handler for each of the 20 person_per table column possibilities
					switch($currentType)
					{
						// Simple strings.. no special processing
						case 1: case 2: case 3: case 4: case 5: case 8: case 9:
						case 10: case 11: case 12: case 17: case 18:
							$sSQLpersonData .= "'" . addslashes($aData[$col]) . "',";
							break;

						// Country.. also set $sCountry for use later!
						case 13:
							$sCountry = $aData[$col];
							break;

						// Gender.. check for multiple possible designations from input
						case 6:
							switch(strtolower($aData[$col]))
							{
								case 'male': case 'm': case 'boy': case 'man':
									$sSQLpersonData .= "1, ";
									break;
								case 'female': case 'f': case 'girl': case 'woman':
									$sSQLpersonData .= "2, ";
									break;
								default:
									$sSQLpersonData .= "0, ";
									break;
							}
							break;

						// Donation envelope.. make sure it's available!
						case 7:
							$iEnv = FilterInput($aData[$col],'int');
							$sSQL = "SELECT '' FROM " . $db->prefix("oscmembership_person") . " WHERE envelope = " . $iEnv ;

							$rsTemp = $db->query($sSQL);
							if (mysql_num_rows($rsTemp) == 0)
								$sSQLpersonData .= $iEnv . ", ";
							else
								$sSQLpersonData .= "NULL, ";
							break;

						// Birth date.. parse multiple date standards.. then split into day,month,year
						case 19:
							$sDate = $aData[$col];
							$aDate = ParseDate($sDate,$iDateMode);
							if (empty($aDate[0])) $aDate[0] = 0;
							if (empty($aDate[1])) $aDate[1] = 0;
							if (empty($aDate[2])) $aDate[2] = 0;
							$sSQLpersonData .= $aDate[0] . "," . $aDate[1] . "," . $aDate[2] . ",";
							break;

						// Membership date.. parse multiple date standards
						case 20:
							$sDate = $aData[$col];
							$aDate = ParseDate($sDate,$iDateMode);
							if (empty($aDate[0])) $aDate[0] = 0;
							if (empty($aDate[1])) $aDate[1] = 0;
							if (empty($aDate[2])) $aDate[2] = 0;
							$sSQLpersonData .= "\"" . $aDate[0] . "-" . $aDate[1] . "-" . $aDate[2] . "\",";
							break;

						// Ignore field option
						case 0:

						// Phone numbers.. uh oh.. don't know country yet.. wait to do a second pass!
						case 14: case 15: case 16:
						default:
							break;

					}

					// Birthday is a special case because it is stored across 3 columns
					switch($currentType)
					{
						case 19:
							$sSQLpersonFields .= "birthyear, birthmonth, birthday,";
							break;
						case 0: case 13: case 14: case 15: case 16:
							break;
						default:
							$sSQLpersonFields .= $aPersonTableFields[$currentType] . ", ";
							break;
					}
				}
			}

			// Second pass at the person_per SQL.. this time we know the Country
			for ($col = 0; $col < $numCol; $col++)
			{
				// Is it not a custom field?
				if (!$aColumnCustom[$col])
				{
					$currentType = $aColumnID[$col];
					switch($currentType)
					{
						// Phone numbers..
						case 14: case 15: case 16:
							$sSQLpersonData .= "'" . addslashes(CollapsePhoneNumber($aData[$col],$sCountry)) . "',";
							$sSQLpersonFields .= $aPersonTableFields[$currentType] . ", ";
							break;
						default:
							break;
					}
				}
			}

			// Finish up the person_per SQL..
			$sSQLpersonData .= $iClassID . ",'" . addslashes($sCountry) . "',";
			$sSQLpersonData .= "'" . date("YmdHis") . "'," .  $xoopsUser->getVar('uid') . ")";

			$sSQLpersonFields .= "clsid, country, dateentered, enteredby)";
			$sSQLperson = $sSQLpersonFields . $sSQLpersonData;

			$db->query($sSQLperson);
			//echo "<br>" . $sSQLperson . "<br>";

			// Get the last inserted person ID and insert a dummy row in the person_custom table
			$sSQL = "SELECT MAX(id) AS iPersonID FROM " . $db->prefix("oscmembership_person");
			$rsPersonID =  $db->query($sSQL);
			
			extract(mysql_fetch_array($rsPersonID));
			$sSQL = "INSERT INTO " . $db->prefix("oscmembership_person_custom") . " (per_ID) VALUES ('" . $iPersonID . "')";
			
			$db->query($sSQL);
				//echo "<br>" . $sSQL . "<br>";
			if ($bHasCustom)
			{

				// Build the person_custom SQL
				for ($col = 0; $col < $numCol; $col++)
				{
					// Is it a custom field?
					if ($aColumnCustom[$col])
					{
						$currentType = $aCustomTypes[$aColumnID[$col]];
						$currentFieldData = trim($aData[$col]);

						// If date, first parse it to the standard format..
						if ($currentType == 2)
						{
							$aDate = ParseDate($currentFieldData,$iDateMode);
							$currentFieldData = implode("-",$aDate);
						}
						// If boolean, convert to the expected values for custom field
						elseif ($currentType == 1)
						{
							if (strlen($currentFieldData))
								$currentFieldData = ConvertToBoolean($currentFieldData);
						}
						else
							$currentFieldData = addslashes($currentFieldData);

						// aColumnID is the custom table column name
						sqlCustomField($sSQLcustom, $currentType, $currentFieldData, $aColumnID[$col], $sCountry);
					}
				}

				// Finalize and run the update for the person_custom table.
				$sSQLcustom = substr($sSQLcustom,0,-2);
				$sSQLcustom .= " WHERE per_ID = " . $iPersonID;
				$db->query($sSQLcustom);
				//echo "<br>" . $sSQLcustom . "<br>";
			}

			$importCount++;
		}

		fclose($pFile);

		// delete the temp file
		unlink($csvTempFile);

		$iStage = 3;
	}
	else
		echo _oscmembership_csvuploaderror;
}

if ($iStage == 1)
{
	// Display the select file form

	echo "<p style=\"color: red\">" . $csvError . "</p>";
	echo "<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\" enctype=\"multipart/form-data\">";
	echo "<input class=\"icTinyButton\" type=\"file\" name=\"CSVfile\"> <input type=\"submit\" class=\"icButton\" value=\"" . gettext("Upload CSV File") . "\" name=\"UploadCSV\">";
	echo "</form>";
}

if ($iStage == 3)
{
	echo "<p class=\"MediumLargeText\">" . gettext("Data import successful.") . ' ' . $importCount . ' ' . gettext("persons were imported") . "</p>";
}

// Returns a date array [year,month,day]
function ParseDate($sDate,$iDateMode)
{
	switch($iDateMode)
	{
		// International standard: YYYY-MM-DD
		case 1:
			$cSeparator = substr($sDate,4,1);
			// Remove separator if it exists
			if (!is_numeric($cSeparator))
				$sDate = str_replace($cSeparator,"",$sDate);
			$aDate[0] = substr($sDate,0,4);
			$aDate[1] = substr($sDate,4,2);
			$aDate[2] = substr($sDate,6,2);
			break;

		// MM-DD-YYYY
		case 2:
			$cSeparator = substr($sDate,2,1);
			// Remove separator if it exists
			if (!is_numeric($cSeparator))
				$sDate = str_replace($cSeparator,"",$sDate);
			$aDate[0] = substr($sDate,4,4);
			$aDate[1] = substr($sDate,0,2);
			$aDate[2] = substr($sDate,2,2);
			break;

		// DD-MM-YYYY
		case 3:
			$cSeparator = substr($sDate,2,1);
			// Remove separator if it exists
			if (!is_numeric($cSeparator))
				$sDate = str_replace($cSeparator,"",$sDate);
			$aDate[0] = substr($sDate,4,4);
			$aDate[1] = substr($sDate,2,2);
			$aDate[2] = substr($sDate,0,2);
			break;
	}
	return $aDate;
}

include(XOOPS_ROOT_PATH."/footer.php");
?>
