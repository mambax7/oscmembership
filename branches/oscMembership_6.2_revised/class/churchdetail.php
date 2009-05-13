<?php
// $Id: churchdetail.php, 2007/03/05 root Exp $
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

class Churchdetail extends XoopsObject {
    var $db;
    var $table;

    function Churchdetail()
    {
	$this->db = &Database::getInstance();
        $this->table = $this->db->prefix("oscmembership_churchdetail");
	$this->initVar('id',XOBJ_DTYPE_INT);
	$this->initVar('churchname',XOBJ_DTYPE_TXTBOX);
	$this->initVar('address1',XOBJ_DTYPE_TXTBOX);
	$this->initVar('address2',XOBJ_DTYPE_TXTBOX);
	$this->initVar('city',XOBJ_DTYPE_TXTBOX);
	$this->initVar('state',XOBJ_DTYPE_TXTBOX);
	$this->initVar('zip',XOBJ_DTYPE_TXTBOX);
	$this->initVar('country',XOBJ_DTYPE_TXTBOX);
	$this->initVar('phone',XOBJ_DTYPE_TXTBOX);
	$this->initVar('fax',XOBJ_DTYPE_TXTBOX);
	$this->initVar('email',XOBJ_DTYPE_TXTBOX);
	$this->initVar('website',XOBJ_DTYPE_TXTBOX);
	$this->initVar('dateentered',XOBJ_DTYPE_TXTBOX);
	$this->initVar('datelastedited',XOBJ_DTYPE_TXTBOX);
	$this->initVar('enteredby',XOBJ_DTYPE_TXTBOX);
	$this->initVar('editedby',XOBJ_DTYPE_TXTBOX);
	$this->initVar('directorydisclaimer',XOBJ_DTYPE_TXTBOX);
    }

}    
    

class oscMembershipChurchdetailHandler extends XoopsObjectHandler
{

	function &get()
	//Search on criteria and return result
	{
	
		$setting=$this->create(False);
		
		$result='';
		$returnresult='';
		
		$sql = "SELECT * FROM " . $setting->table;
		
		if ($result = $this->db->query($sql)) 
		{
			while($row = $this->db->fetchArray($result)) 
			{
				$setting->assignVars($row);
			}
			
			return $setting;
		}
		else return null;		
	}

	

    
    function &create($isNew = true)
    {
    	$churchdetail = new Churchdetail();
        if ($isNew) {
            $churchdetail->setNew();
        }
        return $churchdetail;
    }


	function &update(&$churchdetail)
	{

		$sql = "UPDATE " . $churchdetail->table
		. " SET "
		. "churchname=" . $this->db->quoteString($churchdetail->getVar('churchname'))
		. ",address1=" .
		$this->db->quoteString($churchdetail->getVar('address1'))
		. ",address2=" . 
		$this->db->quoteString($churchdetail->getVar('address2'))
		. ",city=" . $this->db->quoteString($churchdetail->getVar('city'))
		. ",state=" . $this->db->quoteString($churchdetail->getVar('state'))
		. ",zip=" . $this->db->quoteString($churchdetail->getVar('zip'))
		 . ",country=" . $this->db->quoteString($churchdetail->getVar('country')) . ",phone=" . $this->db->quoteString($churchdetail->getVar('phone')) . ",fax=" . $this->db->quoteString($churchdetail->getVar('fax')) . ", email=" . $this->db->quoteString($churchdetail->getVar('email')) . ",website=" . $this->db->quoteString($churchdetail->getVar('website')) . ",dateentered=" . $this->db->quoteString($churchdetail->getVar('dateentered')) . ",datelastedited=" . $this->db->quoteString($churchdetail->getVar('datelastedited')) . ",enteredby=" . $this->db->quoteString($churchdetail->getVar('enteredby')) . ",editedby=" . $this->db->quoteString($churchdetail->getVar('editedby'))
		 . ",directorydisclaimer=" . $this->db->quoteString($churchdetail->getVar('directorydisclaimer'));
			
		$result = $this->db->query($sql);
	}

}


?>