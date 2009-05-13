<?php
// $Id: osclist.php,v 1.1.1.1 2006/03/12 14:57:25 root Exp $
//  ------------------------------------------------------------------------ //
//                ChurchhLedger.com
//                    Copyright (c) 2006 
//
//                       <http://www.churchledger.com/>    
//
//  ------------------------------------------------------------------------ //
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

class Oscdefaults extends XoopsObject {
    var $db;
    var $table;

    function Oscdefaults()
    {
        $this->db = &Database::getInstance();
        $this->table = $this->db->prefix("oscmembership_defaults");
	$this->initVar('id',XOBJ_DTYPE_INT);
	$this->initVar('defaultkey',XOBJ_DTYPE_TXTBOX);
	$this->initVar('defaultvalue',XOBJ_DTYPE_TXTBOX);
    }

}    
    

class oscMembershipOscdefaultsHandler extends XoopsObjectHandler
{


    function &get($oscdefault)
    {

	$returndefaults=$this->create(False);
    
	if(isset($oscdefault))
	{

		$sql = "SELECT * FROM " . $oscdefault->table . " WHERE defaultkey = " . $this->db->quoteString($oscdefault->getVar('defaultkey'));
		
		
		if (!$result = $this->db->query($sql)) 
		{
			return false;
		} 
		if($row = $this->db->fetchArray($result)) 
		{
			$returndefaults->assignVars($row);
		}

		
        }
        return $returndefaults;
    }
    
    
    function &create($isNew = true)
    {
    	$oscdefaults = new Oscdefaults();
        if ($isNew) {
            $oscdefaults->setNew();
        }
        return $oscdefaults;
    }

	function &update(&$oscdefaults)
	{
		$sql = "UPDATE " . $oscdefaults->table
		. " SET "
		. "defaultvalue=" . $this->db->quoteString($oscdefaults->getVar('defaultvalue'));
		
		$sql .=" where defaultkey=" . $this->db->quoteString($oscdefaults->getVar('defaultkey'));
		
		if (!$result = $this->db->query($sql)) {
			return false;
			}
	}

}


?>