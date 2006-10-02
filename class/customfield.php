<?php
// $Id: customfield.php,v 1.1.1.1 2006/03/12 14:57:25 root Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
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

class  Customfield extends XoopsObject {
    var $db;
    var $table;

    function Customfield()
    {
        $this->db = &Database::getInstance();
        $this->table = $this->db->prefix("oscmembership_person_custom_master");
	$this->initVar('custom_Order',XOBJ_DTYPE_INT);
	$this->initVar('custom_Field',XOBJ_DTYPE_TXTBOX);
        $this->initVar('custom_Name', XOBJ_DTYPE_TXTBOX);
	$this->initVar('custom_Special', XOBJ_DTYPE_INT);
        $this->initVar('custom_Side', XOBJ_DTYPE_TXTBOX);
        $this->initVar('type_ID', XOBJ_DTYPE_INT);
    }

}    
    

class oscMembershipCustomfieldHandler extends XoopsObjectHandler
{

    function &create($isNew = true)
    {
        $customfield = new Customfield();
        if ($isNew) {
            $customfield->setNew();
        }
        return $customfield;
    }

    function &get($id)
    {
        $customfield =&$this->create(false);
        if ($id > 0) 
	{
		$sql = "SELECT * FROM " . $this->db->prefix("oscmembership_person_custom_master") . " WHERE custom_Field = '" . $id . "'";
		if (!$result = $this->db->query($sql)) 
		{
			//echo "<br />NewbbForumHandler::get error::" . $sql;
			return false;
		} 
		if($row = $this->db->fetchArray($result)) 
		{
			$customfield->assignVars($row);
		}

		
        }
        return $customfield;
    }
    
    
		
	     
	function &update(&$customfield)
    	{
		$sql = "UPDATE " . $customfield->table
		. " SET "
		. "custom_Order=" . ($customfield->getVar('custom_Order'))
		. ",custom_Name=" . 	
		$this->db->quoteString($customfield->getVar('custom_Name'))
		. ",custom_Special=" . 	
		$this->db->quoteString($customfield->getVar('custom_Special'))
		. ",custom_Side=" . 	
		$this->db->quoteString($customfield->getVar('custom_Side'))
		. ",type_ID=" . 	
		($customfield->getVar('type_ID'))
		.
		" where custom_Field=" . $customfield->getVar('custom_Field');
	
		if (!$result = $this->db->query($sql)) {
			echo "<br />oscmembershipHandler::get error::" . $sql;
			return false;
			}
	
	}

	     
	function &insert(&$customfield)
    	{

		$sql = "INSERT into " . $customfield->table
		. "(custom_Order, custom_Field, custom_Name, custom_Special, custom_Side, type_ID) ";
	
		$sql = $sql . "values(" . ($customfield->getVar('custom_Order'))
		. "," . 
		$this->db->quoteString($customfield->getVar('custom_Field'))
		. "," . 
		$this->db->quoteString($customfield->getVar('custom_Name'))
		. "," . 
		$this->db->quoteString($customfield->getVar('custom_Special'))
		. "," . 
		$this->db->quoteString($customfield->getVar('custom_Side'))
		. "," .
		$this->db->quoteString($customfield->getVar('type_ID'))
		.  ")";

		if (!$result = $this->db->query($sql)) {
			echo "<br />oscmembershipHandler::get error::" . $sql;
			return false;
			}
			else
			{
// Insert into the custom fields table
$sSQL = "ALTER TABLE " . this->db->prefix("oscmembership_person_custom") . " ADD `c" . $newFieldNum . "` ";
switch($newFieldType)
{case 1:$sSQL .= "ENUM('false', 'true')";
break;
case 2:$sSQL .= "DATE";
break;
case 3:$sSQL .= "VARCHAR(50)";
break;
case 4:$sSQL .= "VARCHAR(100)";
break;
case 5:$sSQL .= "TEXT";
break;
case 6:$sSQL .= "YEAR";
break;
case 7:$sSQL .= "ENUM('winter', 'spring', 'summer', 'fall')";
break;
case 8:$sSQL .= "INT";
break;
case 9:$sSQL .= "MEDIUMINT(9)";
break;
case 10:$sSQL .= "DECIMAL(10,2)";
break;
case 11:$sSQL .= "VARCHAR(30)";
break;
case 12:$sSQL .= "TINYINT(4)";
}$sSQL .= " DEFAULT NULL ;"
;

$this->db>query($sql);

}}}				
				
		if (!$result = $this->db->query($sql)) {
			echo "<br />oscmembershipHandler::get error::" . $sql;
			return false;
			}
		else
		{
			return $this->db->getInsertId();
		}
	
	}

	function &getcustomfieldtypes()
	//Search on criteria and return result
	{
		$result='';
		
		$sql = "SELECT * FROM " . $this->db->prefix("oscmembership_list"). " WHERE id=4";
	
		$return_array=array();
		
		if (!$result = $this->db->query($sql)) 
		{
			//echo "<br />NewbbForumHandler::get error::" . $sql;
			return false;
		}
		
		return $result;
			
	}
	
	
	
}


?>