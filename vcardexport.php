<?php

include("../../mainfile.php");
//$xoopsOption['template_main'] = 'cs_index.html';
//include(XOOPS_ROOT_PATH."/header.php");

ini_set("memory_limit","400M");

//include("../../../include/cp_header.php");
include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");
include_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php");

// include the default language file for the admin interface
if ( file_exists( "../language/" . $xoopsConfig['language'] . "/main.php" ) ) {
    include "../language/" . $xoopsConfig['language'] . "/main.php";
}
elseif ( file_exists( "../language/english/main.php" ) ) {
    include "../language/english/main.php";
}


//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

if(!hasPerm("oscmembership_view",$xoopsUser))     redirect_header(XOOPS_URL, 3, _oscmem_accessdenied);

$personid = (isset($_POST['id'])) ? intval($_POST['id']) : 0;

$persondetail_handler = &xoops_getmodulehandler('person', 'oscmembership');

$person=$persondetail_handler->get($personid);

if(isset($person))
{
	require_once 'Contact_Vcard_Build.php';
	$vcard = new Contact_Vcard_Build();

	$vcard->setName($person->getVar("lastname"),$person->getVar("firstname"),"","","");
	$vcard->setFormattedName($label["recipient"]);

	$vcard->addEmail($person->getVar("email"));
	$vcard->addTelephone($person->getVar("homephone"));
	$workphone=$person->getVar("workphone");
	if($workphone!="")
		$vcard->addTelephone($workphone);


	$pob="";
	$extend="";
	$street=$person->getVar("address1");
	$locality=$person->getVar("city");
	$region=$person->getVar("state");
	$country=$person->getVar("country");
	$postcode=$person->getVar("zip");

	$vcard->addAddress($pob, $extend,$street,$locality, $region, $postcode, $country);


	$text=$vcard->fetch();

	header("Content-type: text/x-vcard");
	header("Content-Disposition: attachment; filename=" . $person->getVar("firstname") . $person->getVar("lastname") . ".vcf");

	echo $text;
}

?>

