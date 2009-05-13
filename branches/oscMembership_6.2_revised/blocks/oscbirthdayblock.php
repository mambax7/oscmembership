<?php
//  ------------------------------------------------------------------------ //
//                PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//                OSC Open Source Church Project//
//                    Copyright (c) 2009 ChurchLedger.com //
//                       <http://www.churchledger.com/>                             //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Steve McAtee                                          //
// URL: http://www.churchledger.com, http://www.xoops.org/
// Project: The ImpressCMS Project, The Open Source Church project (OSC)
// -------------------------------------------------------------------------// **************************************************************************//

//include_once(XOOPS_ROOT_PATH . "/modules/churchsplash/class/formimage.php");

// include the default language file for the admin interface
include_once XOOPS_ROOT_PATH . '/modules/oscmembership/class/person.php';

function oscbirthdayblock_show($options) 
{
	global $xoopsUser;
	$person_handler = &xoops_getmodulehandler('person', 'oscmembership');

	//find current month
	$month=date("m");
	switch($month)
	{
	case 1:
		$title=_oscmem_bday_1; break;
	case 2:
		$title=_oscmem_bday_2; break;
	case 3:
		$title=_oscmem_bday_3; break;
	case 4:
		$title=_oscmem_bday_4; break;
	case 5:
		$title=_oscmem_bday_5; break;
	case 6:
		$title=_oscmem_bday_6; break;
	case 7:
		$title=_oscmem_bday_7; break;
	case 8:
		$title=_oscmem_bday_8; break;
	case 9:
		$title=_oscmem_bday_9; break;
	case 10:
		$title=_oscmem_bday_10; break;
	case 11:
		$title=_oscmem_bday_11; break;
	case 12:
		$title=_oscmem_bday_12; break;
	}

	$sort="birthday";
	$birthdays=$person_handler->getbdays($month,$sort);

	$content_block="<table class='outer' cellspacing='1'>";

	$content_block.="<tr><th colspan=2 align=center>$title</th></tr>";

	$switch=true;

	foreach($birthdays as $person)
	{
		if($switch) $evenodd="even"; else $evenodd="odd";
		if($switch) $switch=false; else $switch=true;
	
		$content_block.="<tr><td class=$evenodd>" . $person->getVar("lastname") . ", " . $person->getVar("firstname") . "</td><td align=center class=$evenodd>" . $person->getVar("birthday") . "</td></tr>";
	}

	$content_block.="</table>";
	
	
	$block['title'] = _oscbirthdayblock_block_title;
	$block['content'] = $content_block;

        return $block;
}


?>