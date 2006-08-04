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
    
    
		
	     
	function &update(&$person)
    	{
		$sql = "UPDATE " . $person->table
		. " SET "
		. "title=" . $this->db->quoteString($person->getVar('title'))
		. ",firstname=" .
		$this->db->quoteString($person->getVar('firstname'))
		. ",lastname=" . 	
		$this->db->quoteString($person->getVar('lastname'))
		. ",middlename=" . 	
		$this->db->quoteString($person->getVar('middlename'))
		. ",suffix=" . 	
		$this->db->quoteString($person->getVar('suffix'))
		. ",address1=" . 	
		$this->db->quoteString($person->getVar('address1'))
		. ",address2=" . 	
		$this->db->quoteString($person->getVar('address2'))
		. ",city=" . 	
		$this->db->quoteString($person->getVar('city'))
		. ",state=" . 	
		$this->db->quoteString($person->getVar('state'))
		. ",zip=" . 	
		$this->db->quoteString($person->getVar('zip'))
		. ",country=" . 	
		$this->db->quoteString($person->getVar('country'))
		. ",homephone=" . 	
		$this->db->quoteString($person->getVar('homephone'))
		. ",workphone=" . 	
		$this->db->quoteString($person->getVar('workphone'))
		. ",cellphone=" . 	
		$this->db->quoteString($person->getVar('cellphone'))
		. ",email=" . 	
		$this->db->quoteString($person->getVar('email'))
		. ",workemail=" . 	
		$this->db->quoteString($person->getVar('workemail'))
		. ",birthmonth=" . $person->getVar('birthmonth')
		. ",birthday=" . $person->getVar('birthday')
		. ",birthyear=" . $person->getVar('birthyear')
		. ",membershipdate=" . 	
		$this->db->quoteString($person->getVar('membershipdate'))
		. ",gender=" . $person->getVar('gender');
		if($person->getVar('famid')!='')
		{
			$sql=$sql . ",famid=" . $person->getVar('famid');
		}
		else
		{
			$sql=$sql . ",famid=null";
		}
		if($person->getVar('envelope')!='')		
		{
			$sql=$sql . ",envelope=" . $person->getVar('envelope'); 	
		}
		else
		{
			$sql = $sql . ",envelope=null";
		}
		$sql = $sql . ",datelastedited=" .  			
		$this->db->quoteString($person->getVar('datelastedited'))
		. ",editedby=" . $this->db->quoteString($person->getVar('editedby')) . 
		 
		" where id=" . $person->getVar('id');
	
		if (!$result = $this->db->query($sql)) {
			echo "<br />oscmembershipHandler::get error::" . $sql;
			return false;
			}
			
	
	}

	     
	function &insert(&$person)
    	{

		$sql = "INSERT into " . $person->table
		. "(title, firstname, lastname, middlename, " 
		. "suffix, address1, address2, city, state, zip, " 
		. "country, homephone, workphone, cellphone, email, "
		. "workemail, birthmonth, birthday, birthyear, "
		. "membershipdate, gender, famid, envelope, "
		. "datelastedited, editedby, dateentered, enteredby) ";
	
		$sql = $sql . "values(" . $this->db->quoteString($person->getVar('title'))
		. "," . 
		$this->db->quoteString($person->getVar('firstname'))
		. "," . 
		$this->db->quoteString($person->getVar('lastname'))
		. "," . 
		$this->db->quoteString($person->getVar('middlename'))
		. "," . 
		$this->db->quoteString($person->getVar('suffix'))
		. "," .
		$this->db->quoteString($person->getVar('address1'))
		. "," .
		$this->db->quoteString($person->getVar('address2'))
		. "," .
		$this->db->quoteString($person->getVar('city'))
		. "," .
		$this->db->quoteString($person->getVar('state'))
		. "," .
		$this->db->quoteString($person->getVar('zip'))
		. "," .
		$this->db->quoteString($person->getVar('country'))
		. "," .
		$this->db->quoteString($person->getVar('homephone'))
		. "," .
		$this->db->quoteString($person->getVar('workphone'))
		. "," .
		$this->db->quoteString($person->getVar('cellphone'))
		. "," .
		$this->db->quoteString($person->getVar('email'))
		. "," .
		$this->db->quoteString($person->getVar('workemail'))
		. "," .
		$this->db->quoteString($person->getVar('birthmonth'))
		. "," .
		$this->db->quoteString($person->getVar('birthday'))
		. "," .
		$this->db->quoteString($person->getVar('birthyear'))
		. "," .
		$this->db->quoteString($person->getVar('membershipdate'))
		. "," .
		$this->db->quoteString($person->getVar('gender'))
		. "," .
		$this->db->quoteString($person->getVar('famid'))
		. "," .
		$this->db->quoteString($person->getVar('envelope'))
		. "," .
		$this->db->quoteString($person->getVar('datelastedited'))
		. "," .
		$this->db->quoteString($person->getVar('editedby'))
		. "," .
		$this->db->quoteString($person->getVar('dateentered'))
		. "," .
		$this->db->quoteString($person->getVar('enteredby')) . ")";
		
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