<?php
include_once "../../mainfile.php";
//ini_set("memory_limit","400M");

//$GLOBALS['xoopsOption']['template_main'] ="setupgsync.html";

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _oscmem_accessdenied);
}

include_once(XOOPS_ROOT_PATH . "/class/xoopsformloader.php");
include_once(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php");

if (file_exists(XOOPS_ROOT_PATH. "/modules/" . 	$xoopsModule->getVar('dirname') .  "/language/" . $xoopsConfig['language'] . "/modinfo.php")) 
{
    include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/language/" . $xoopsConfig['language'] . "/modinfo.php";
}
elseif( file_exists(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') ."/language/english/modinfo.php"))
{ include XOOPS_ROOT_PATH ."/modules/" . $xoopsModule->getVar('dirname') . "/language/english/modinfo.php";

}

include(XOOPS_ROOT_PATH."/header.php");

if(!hasPerm("oscmembership_view",$xoopsUser) && !hasPerm("oscmembership_modify",$xoopsUser))     redirect_header(XOOPS_URL, 3, _oscmem_accessdenied);

if (isset($_POST['op'])) $op=$_POST['op'];
if (isset($_POST['googleaccount'])) $user=$_POST['googleaccount'];
if (isset($_POST['googlepwd'])) $pass=$_POST['googlepwd'];
if (isset($_POST['googlegroup'])) $osc_googlegroup=$_POST['googlegroup'];


switch (true) 
{
    case ($op=="sync"):

	include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/person.php';
	
	$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
	
	// load Zend Gdata libraries
	
	$xoopsuid=$xoopsUser->getVar("uid");
	
	$cart = $person_handler->getCartc($xoopsUser->getVar('uid'));
	
	$person=$person_handler->create(false);


       try{
		require_once XOOPS_ROOT_PATH . '/Zend/Loader.php';

		Zend_Loader::loadClass('Zend_Gdata');
		Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
		Zend_Loader::loadClass('Zend_Http_Client');
		Zend_Loader::loadClass('Zend_Gdata_Query');
		Zend_Loader::loadClass('Zend_Gdata_Feed');
       }

       catch (Exception $e)
        {
            echo 'exception here';
            die('ERROR:' . $e->getMessage());
	}
	

	try 
	{

		// perform login and set protocol version to 3.0
		$client = Zend_Gdata_ClientLogin::getHttpClient(
		$user, $pass, 'cp');
		$gdata = new Zend_Gdata($client);
		$gdata->setMajorProtocolVersion(3);
		
		//verify group exists.  if not create it
		$osc_query = new Zend_Gdata_Query('http://www.google.com/m8/feeds/groups/default/full');
		$osc_feed = $gdata->getFeed($osc_query);

		$osc_ggroup_exits=false;
		
		foreach($osc_feed as $entry)
		{
			if($entry->title->text=='ChurchLedger')
			{
				$osc_ggroup_exists=true;
				$osc_ggroup_id=$entry->id->text; //grab id from Google
			}

		}

		$doc  = new DOMDocument();
		$doc->formatOutput = true;

		if(!($osc_ggroup_exists))
		{
			//group does not exist.  create it.
			$osc_group = $doc->createElement('atom:entry');
			$osc_group->setAttributeNS('http://www.w3.org/2000/xmlns/' ,
			'xmlns:atom', 'http://www.w3.org/2005/Atom');
			$osc_group->setAttributeNS('http://www.w3.org/2000/xmlns/' ,
			'xmlns:gd', 'http://schemas.google.com/g/2005');
			$doc->appendChild($osc_group);
	
			$osc_group_title=$doc->createElement('atom:title','ChurchLedger');
			$osc_group->appendChild($osc_group_title);
			
			$entryResult = $gdata->insertEntry($doc->saveXML(), 
			'http://www.google.com/m8/feeds/groups/default/full');

		}


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
			$entry->setAttributeNS('http://www.w3.org/2000/xmlns/','xmlns:gContact','http://schemas.google.com/contact/2008');
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
			$orgName = $doc->createElement('gd:orgName', $osc_googlegroup);
			$org->appendChild($orgName);

			$group=$doc->createElement('gContact:groupMembershipInfo');
			$group->setAttribute('href',$osc_ggroup_id);
			$entry->appendChild($group);

                        // insert entry
                        $entryResult = $gdata->insertEntry($doc->saveXML(),'http://www.google.com/m8/feeds/contacts/default/full');


			
		}


	$message=_oscmem_msg_googlesyncsuccess;

        redirect_header("index.php", 3, $message);

	} 

	catch (Exception $e) 
	{
		$message=$e->getMessage();
                echo '<font color=red>' . $message . '</font>';
//                 redirect_header(XOOPS_URL ."/modules/" . $xoopsModule->getVar('dirname') . '/setupgsync.php',10,$message);
	}




	break;
}


include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/person.php';

$person_handler = &xoops_getmodulehandler('person', 'oscmembership');

// load Zend Gdata libraries

$xoopsuid=$xoopsUser->getVar("uid");

$cart = $person_handler->getCartc($xoopsUser->getVar('uid'));

$person=$person_handler->create(false);

$osc_gaccount = new XoopsFormText(_oscmem_googleaccount, "googleaccount", 30, 50);
$osc_ggroup=new XoopsFormText(_oscmem_google_group,"googlegroup",30,50);

$osc_gpwd=new XoopsFormPassword(_oscmem_password,"googlepwd",30,50);
$submit_button = new XoopsFormButton("", "setupsyncsubmit", _oscmem_submit, "submit");
$osc_gcontacts_instruc = new XoopsFormLabel(_oscmem_instructions,_oscmem_gsync_instructions_content);

$op_hidden = new XoopsFormHidden("op", "sync");  //save operation

$form = new XoopsThemeForm(_oscmem_gsync_TITLE, "setupgsync", "setupgsync.php", "post", true);

$form->setRequired($osc_gaccount);
$form->setRequired($osc_gpwd);
$form->addElement($osc_gaccount);
$form->addElement($osc_ggroup);
$form->addElement($osc_gpwd);
$form->addElement($submit_button);
$form->addElement($osc_gcontacts_instruc);
$form->addElement($op_hidden);

$form->display();



include(XOOPS_ROOT_PATH."/footer.php");
?>

