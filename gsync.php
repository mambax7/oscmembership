<?php
include_once "../../mainfile.php";
ini_set("memory_limit","400M");

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

require XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if (file_exists(XOOPS_ROOT_PATH. "/modules/" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) 
{
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/modinfo.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/modinfo.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/modinfo.php";

}

echo $_POST['googleaccount'];

if (isset($_POST['googleaccount'])) $user=$_POST['googleaccount'];
if (isset($_POST['googlepwd'])) $pass=$_POST['googlepwd'];


if(!hasPerm("oscmembership_view",$xoopsUser) && !hasPerm("oscmembership_modify",$xoopsUser))     redirect_header(XOOPS_URL, 3, _oscmem_accessdenied);


include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/person.php';

$person_handler = &xoops_getmodulehandler('person', 'oscmembership');

// load Zend Gdata libraries

$xoopsuid=$xoopsUser->getVar("uid");

$cart = $person_handler->getCartc($xoopsUser->getVar('uid'));

$person=$person_handler->create(false);



try
{

require_once 'Zend/Loader.php';

Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Http_Client');
Zend_Loader::loadClass('Zend_Gdata_Query');
Zend_Loader::loadClass('Zend_Gdata_Feed');
}
catch (Exception $e) {
  die('ERROR:' . $e->getMessage());
}


try {
  // perform login and set protocol version to 3.0
  $client = Zend_Gdata_ClientLogin::getHttpClient(
    $user, $pass, 'cp');
  $gdata = new Zend_Gdata($client);
  $gdata->setMajorProtocolVersion(3);
  

  // create new entry
//Loop through people
foreach($cart as $person)
{

  $doc  = new DOMDocument();
  $doc->formatOutput = true;
  $entry = $doc->createElement('atom:entry');
  $entry->setAttributeNS('http://www.w3.org/2000/xmlns/' ,
   'xmlns:atom', 'http://www.w3.org/2005/Atom');
  $entry->setAttributeNS('http://www.w3.org/2000/xmlns/' ,
   'xmlns:gd', 'http://schemas.google.com/g/2005');
  $doc->appendChild($entry);
  
  // add name element
  $name = $doc->createElement('gd:name');
  $entry->appendChild($name);
  $fullName = $doc->createElement('gd:fullName', $person->getVar('firstname') . ' ' . $person->getVar('lastname'));
  $name->appendChild($fullName);
  
  // add email element
  $email = $doc->createElement('gd:email');
  $email->setAttribute('address' , $person->getVar('email'));
  $email->setAttribute('rel' ,'http://schemas.google.com/g/2005#home');
  $entry->appendChild($email);
  
  // add org name element
  $org = $doc->createElement('gd:organization');
  $org->setAttribute('rel' ,'http://schemas.google.com/g/2005#work');
  $entry->appendChild($org);
  $orgName = $doc->createElement('gd:orgName', 'My Church Name');
  $org->appendChild($orgName);
  
  // insert entry
  $entryResult = $gdata->insertEntry($doc->saveXML(), 
   'http://www.google.com/m8/feeds/contacts/default/full');

}


redirect_header("viewcart.php",2,"Cart has been Sync'd with Google Contacts.");

} catch (Exception $e) {
  die('ERROR:' . $e->getMessage());
}

?>

