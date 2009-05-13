<?php
//  ------------------------------------------------------------------------ //
//                PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//                OSC Open Source Church Project//
//                    Copyright (c) 2005 ChurchLedger.com //
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

function oscmemalphanav_show($options) 
{
	global $xoopsUser;
	$person_handler = &xoops_getmodulehandler('person', 'oscmembership');

	$offset=30;
	$pages=$person_handler->getPages($offset);
	$letters=$person_handler->getalphanav();

	$content_block="
<table class=navbar >
<tr>";

foreach($letters as $letter)
{
	$content_block.="<td align=center><small><a class=navbar href='" . XOOPS_URL . "/modules/oscmembership/index.php?filter=lastname" . $letter . "%'>$letter</a></small></td><td align=center>|</td>";
}

$content_block=substr($content_block,0,strlen($content_block)-23) . "</tr>";

$content_block.="
<tr>";
for ($i = 1; $i <= $pages; $i++) 
{
	$content_block.="<td align='center'><a href='". XOOPS_URL . "/modules/oscmembership/index.php?page=$i'>$i</a></td><td align=center>|</td>";
}

$content_block.="
</tr>
</table>
";
	

		
	$block['title'] = _oscmemalpha_nav_block_title;
	$block['content'] = $content_block;

        return $block;
}


?>