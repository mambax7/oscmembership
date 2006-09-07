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

class Churchdir extends XoopsObject {
    var $db;
    var $table;

    function Churchdir()
    {
        $this->db = &Database::getInstance();
        $this->table = $this->db->prefix("oscmembership_churchdir");
	$this->initVar('id',XOBJ_DTYPE_INT);
	$this->initVar('church_name',XOBJ_DTYPE_TXTBOX);
	$this->initVar('church_address',XOBJ_DTYPE_TXTBOX);
	$this->initVar('church_city',XOBJ_DTYPE_TXTBOX);
	$this->initVar('church_state',XOBJ_DTYPE_TXTBOX);
	$this->initVar('church_post',XOBJ_DTYPE_TXTBOX);
	$this->initVar('church_phone',XOBJ_DTYPE_TXTBOX);
	$this->initVar('disclaimer',XOBJ_DTYPE_TXTBOX);
	
    }

}    
    

class oscMembershipChurchdirHandler extends XoopsObjectHandler
{

    function &get($churchdir)
    {
        if (intval($churchdir->getVar('id')) == 0) 
	{
		$sql = "SELECT * FROM " . $churchdir->table . " WHERE id =0";
		
		if (!$result = $this->db->query($sql)) 
		{
			echo "<br />oscmembership:churchdir::get error::" . $sql;
			return false;
		} 
		if($row = $this->db->fetchArray($result)) 
		{
			$churchdir->assignVars($row);
		}

		
        }
        return $churchdir;
    }
    
    function &create($isNew = true)
    {
    	$churchdir= new Churchdir();
        if ($isNew) {
	    $churchdir->assignVar('id',0);
            $churchdir->setNew();
        }
        return $churchdir;
    }

    
	function &update($churchdir)
	{		
		$sql = "UPDATE " . $churchdir->table
		. " SET "
		. "church_name=" . $this->db->quoteString($churchdir->getVar('church_name'))
		. ",church_address=" .
		$this->db->quoteString(strip_tags($churchdir->getVar('church_address'))) . ", church_city=" . $this->db->quoteString(strip_tags($churchdir->getVar('church_city'))) . ", church_state=" . $this->db->quoteString(strip_tags($churchdir->getVar('church_state'))) . ", church_post=" . $this->db->quoteString(strip_tags($churchdir->getVar('church_post'))) . ", church_phone=" . $this->db->quoteString(strip_tags($churchdir->getVar('church_phone'))) . ", disclaimer=" . $this->db->quoteString(strip_tags($churchdir->getVar('disclaimer')));
		
		$sql .=" where id=0";

		if (!$result = $this->db->query($sql)) {
			echo "<br />oscmembershipHandler::get error::" . $sql;
			return false;
			}
	}

	     

}
?>