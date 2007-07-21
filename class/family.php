<?php
// $Id: family.php,v 1.1.1.1 2006/03/12 14:57:25 root Exp $
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

class Family extends XoopsObject {
    var $db;
    var $table;

    function Family()
    {    
    
        $this->db = &Database::getInstance();
        $this->table = $this->db->prefix("oscmembership_family");
	$this->membershiptable = $this->db->prefix("oscmembership_person");
	
	$this->initVar('id', XOBJ_DTYPE_TXTBOX);
	$this->initVar('churchid', XOBJ_DTYPE_INT);
        $this->initVar('familyname', XOBJ_DTYPE_TXTBOX);
        $this->initVar('address1', XOBJ_DTYPE_TXTBOX);
        $this->initVar('address2', XOBJ_DTYPE_TXTBOX);
        $this->initVar('city', XOBJ_DTYPE_TXTBOX);
        $this->initVar('state', XOBJ_DTYPE_TXTBOX);
	$this->initVar('zip',XOBJ_DTYPE_TXTBOX);
        $this->initVar('country', XOBJ_DTYPE_TXTBOX);
        $this->initVar('homephone', XOBJ_DTYPE_TXTBOX);
	$this->initVar('workphone', XOBJ_DTYPE_TXTBOX);
        $this->initVar('cellphone', XOBJ_DTYPE_TXTBOX);
        $this->initVar('email', XOBJ_DTYPE_TXTBOX);
	$this->initVar('weddingdate', XOBJ_DTYPE_TXTBOX);
	$this->initVar('dateentered', XOBJ_DTYPE_TXTBOX);
	$this->initVar('datelastedited', XOBJ_DTYPE_TXTBOX);
	$this->initVar('enteredby', XOBJ_DTYPE_INT);
	$this->initVar('editedby', XOBJ_DTYPE_INT);
	$this->initVar('loopcount',XOBJ_DTYPE_INT);
    
    	$this->initVar('oddrow', XOBJ_DTYPE_INT);
    	$this->initVar('totalloopcount', XOBJ_DTYPE_INT);
	
}

}    
    

class oscMembershipFamilyHandler extends XoopsObjectHandler
{

    function &create($isNew = true)
    {
        $family = new Family();
        if ($isNew) {
            $family->setNew();
        }
        return $family;
    }

    function &get($id)
    {
        $family =&$this->create(false);
        if ($id > 0) 
	{
		$sql = "SELECT * FROM " . $family->table . " WHERE id = " . intval($id);
		if (!$result = $this->db->query($sql)) 
		{
			//echo "<br />NewbbForumHandler::get error::" . $sql;
			return false;
		} 
		if($row = $this->db->fetchArray($result)) 
		{
			$family->assignVars($row);
		}

		
        }
        return $family;
    }

    function &search($searcharray, $sort)
    //Search on criteria and return result
    {
	$result='';
	$returnfamilies[]=array();
	
        if (isset($searcharray)) 
	{
	        $family= &$this->create(false);
		$sql = "SELECT * FROM " . $family->table . " WHERE (";

		$count = count($searcharray);
		if ( $count > 0 && is_array($searcharray) ) {
		$sql .= "(familyname LIKE '%$searcharray[0]%'  or homephone like '%$searcharray[0]%' or workphone like '%$searcharray[0]%' or cellphone like '%$searcharray[0]%' or city like '%$searcharray[0]%' or state like '%$searcharray[0]%' )";
		
		for ( $i = 1; $i < $count; $i++ ) {
			$sql .= " OR ";	$family_handler = &xoops_getmodulehandler('family', 'oscmembership');
	
		$results = $family_handler->search($queryarray);

		$sql .= "(familyname LIKE '%$searcharray[$i]%' OR homephone LIKE '%$searcharray[$i]%' OR workphone LIKE '%$searcharray[$i]%' OR cellphone LIKE '%$searcharray[$i]%' OR city LIKE '%$searcharray[$i]%' OR state LIKE '%$searcharray[$i]%')";
		}
		
//		$sql .= " ) ";
		
		if(isset($sort))
		{
			switch($sort)
			{
			case "name":
			$sql .= ") order by familyname";
			break;
			case "citystate":
			$sql .= ") order by city,state ";
			break;

			case "email":
			$sql .= ") order by email ";
			break;
						
			default:
			$sql .= ") order by familyname ";
			break;
			}
		}
	}
		
		if (!$result = $this->db->query($sql)) 
		{
			//echo "<br />NewbbForumHandler::get error::" . $sql;
			return false;
		}
		$oddrow=false;

		$i=0;
		$family=new Family ();
		$returnfamilies[0]=$family;
		while($row = $this->db->fetchArray($result)) 
		{
			$family =&$this->create(false);
			$family->assignVars($row);
			$family->assignVar('oddrow',$oddrow);
			$family->assignVar('loopcount',$i);
			
			$returnfamilies[$i]=$family;
			
			if($oddrow){$oddrow=false;}
			else {$oddrow=true;}

			$i++;
			
			
		}
		$returnfamilies[0]->assignVar('totalloopcount',$i);
		
	}
	return $returnfamilies;
    }

function &getmembers(&$family)
    //Search on criteria and return result
{
	$result='';
	$returnresult='';
	
	$sql = "SELECT * FROM " . $family->membershiptable . " WHERE famid=" . $family->getVar('id');
	$sql =  $sql . " order by lastname";

	if (!$result = $this->db->query($sql)) 
	{
		//echo "<br />NewbbForumHandler::get error::" . $sql;
		return false;
	}
	return $result;
		
 }

		
    function &removeMember($personid)
    {
    	$result="";
		$sql = "Update  " . $this->db->prefix("oscmembership_person");
		$sql = $sql . " set famid=0";
		$sql = $sql . " where id=" . $personid;     

		if (!$result = $this->db->query($sql)) 
		{
			
			return false;
		}
    }
    
function &modsearch($searcharray)
    //Search on criteria and return result
{
	$result='';
	$returnresult='';
	$ret=array();
	
        if (isset($searcharray)) 
	{
	        $family= &$this->create(false);
		$sql = "SELECT * FROM " . $this->table . " WHERE (";

		$count = count($searcharray);
		if ( $count > 0 && is_array($searcharray) ) 
		{
			$sql .= "(familyname LIKE '%$searcharray[0]%' OR homephone like '%$searcharray[0]%' or workphone like '%$searcharray[0]%' or cellphone like '%$searcharray[0]%')";
		
			for ( $i = 1; $i < $count; $i++ ) 
			{
				$sql .= " OR ";	$family_handler = &xoops_getmodulehandler('family', 'oscmembership');
			
				$results = $family_handler->search($queryarray);
		
				$sql .= "(familyname LIKE '%$searcharray[$i]%' or homephone LIKE '%$searcharray[$i]%' OR workphone LIKE '%$searcharray[$i]%' OR cellphone LIKE '%$searcharray[$i]%')";
				
				$sql .= ") order by familyname";
			}
		}

		$sql=$sql . ")";
		if (!$result = $this->db->query($sql)) 
		{
			//echo "<br />NewbbForumHandler::get error::" . $sql;
			return false;
		}

		$i = 0;
		
		while($row = $this->db->fetchArray($result)) 
		{
		$ret[$i]['image'] = "fc.gif";
		$ret[$i]['link'] = '#'; //"index.php?id=".$row['id']."";
		$ret[$i]['title'] = $row['familyname'] . ", " . $row['address1'];
		if(isset($row['address2'])) { $ret[$i]['title']=$ret[$i]['title'] . " " . $row['address2']; }
		if(isset($row['city'])) { $ret[$i]['title'] = $ret[$i]['title'] . " " . $row['city'] . ", " . $row['state'] . " " . $row['zip']; }
		
		if(isset($row['homephone']))
		{ $ret[$i]['title'] = $ret[$i]['title'] ." " . _oscmem_homphone_prefix . $row['homephone']; }

		if(isset($row['email']))
		{ $ret[$i]['title'] = $ret[$i]['title'] ." " . $row['email']; }

				
		$ret[$i]['time'] = '';
		$ret[$i]['uid'] = $row['id'];
		$i++;
		}

	}
	return $ret;
	}
     
	function &update(&$family)
    	{
		$sql = "UPDATE " . $family->table
		. " SET "		
		. "familyname=" . $this->db->quoteString($family->getVar('familyname'))
		. ",address1=" . 	
		$this->db->quoteString($family->getVar('address1'))
		. ",address2=" . 	
		$this->db->quoteString($family->getVar('address2'))
		. ",city=" . 	
		$this->db->quoteString($family->getVar('city'))
		. ",state=" . 	
		$this->db->quoteString($family->getVar('state'))
		. ",zip=" . 	
		$this->db->quoteString($family->getVar('zip'))
		. ",country=" . 	
		$this->db->quoteString($family->getVar('country'))
		. ",homephone=" . 	
		$this->db->quoteString($family->getVar('homephone'))
		. ",workphone=" . 	
		$this->db->quoteString($family->getVar('workphone'))
		. ",cellphone=" . 	
		$this->db->quoteString($family->getVar('cellphone'))
		. ",email=" . 	
		$this->db->quoteString($family->getVar('email'))
		. ",weddingdate=" .
		$this->db->quoteString(date('Y-m-d',strtotime($family->getVar('weddingdate'))));
		
//		$this->db->quoteString(date('Y-m-d',strtotime($this->db->quoteString($family->getVar('weddingdate')))));
		$sql .= ",datelastedited=" .  			
		$this->db->quoteString($family->getVar('datelastedited'))
		. ",editedby=" . $this->db->quoteString($family->getVar('editedby')) . 
		 
		" where id=" . $family->getVar('id');
			
		if (!$result = $this->db->query($sql)) {
			echo "<br />oscmembershipHandler::get error::" . $sql;
			return false;
			}
	
	}

	     
	function &insert(&$family)
    	
	{

		$sql = "INSERT into " . $family->table
		. "(familyname, address1, address2, city, state, zip, " 
		. "country, homephone, workphone, cellphone, email, "
		. "weddingdate,"
		. "dateentered, datelastedited, editedby , enteredby) ";
	
		$sql = $sql . "values(" . $this->db->quoteString($family->getVar('familyname'))
		. "," . 
		$this->db->quoteString($family->getVar('address1'))
		. "," .
		$this->db->quoteString($family->getVar('address2'))
		. "," .
		$this->db->quoteString($family->getVar('city'))
		. "," .
		$this->db->quoteString($family->getVar('state'))
		. "," .
		$this->db->quoteString($family->getVar('zip'))
		. "," .
		$this->db->quoteString($family->getVar('country'))
		. "," .
		$this->db->quoteString($family->getVar('homephone'))
		. "," .
		$this->db->quoteString($family->getVar('workphone'))
		. "," .
		$this->db->quoteString($family->getVar('cellphone'))
		. "," .
		$this->db->quoteString($family->getVar('email'))
		. "," .
		$this->db->quoteString($family->getVar('weddingdate'))
		. "," .
		$this->db->quoteString($family->getVar('dateentered'))
		. "," .
		$this->db->quoteString($family->getVar('datelastedited'))
		. "," .
		$this->db->quoteString($family->getVar('editedby'))
		. "," .
		$this->db->quoteString($family->getVar('enteredby')) . ")";
		
		if (!$result = $this->db->query($sql)) {
			echo "<br />oscmembershipHandler::get error::" . $sql;
			return false;
			}
			else
			{
			return  $this->db->getInsertId();
			}
	
	}

	
	
	
}


?>