<?php
// $Id: group.php,v 1.1.1.1 2006/03/12 14:57:25 root Exp $
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

class Group extends XoopsObject {
    var $db;
    var $table;

    function Group()
    {
        $this->db = &Database::getInstance();
        $this->table = $this->db->prefix("oscmembership_group");
	$this->membershiptable = $this->db->prefix("oscmembership_group_members");
	$this->initVar('id',XOBJ_DTYPE_INT);
	$this->initVar('group_type',XOBJ_DTYPE_INT);
	$this->initVar('group_typeName',XOBJ_DTYPE_TXTBOX);
	$this->initVar('group_RoleListID',XOBJ_DTYPE_INT);
	$this->initVar('group_DefaultRole',XOBJ_DTYPE_INT);
	$this->initVar('group_Name',XOBJ_DTYPE_TXTBOX);
	$this->initVar('group_Description',XOBJ_DTYPE_TXTAREA);
	$this->initVar('group_hasSpecialProps',XOBJ_DTYPE_INT);
	$this->initVar('loopcount',XOBJ_DTYPE_INT);
	$this->initVar('oddrow',XOBJ_DTYPE_INT);
	$this->initVar('totalloopcount',XOBJ_DTYPE_INT);
    }

}    
    

class oscMembershipGroupHandler extends XoopsObjectHandler
{

	function &getmembers(&$group)
	//Search on criteria and return result
	{
		$result='';
		$returnresult='';
		
		$sql = "SELECT p.* FROM " . $group->membershiptable . " gm JOIN " . $this->db->prefix("oscmembership_person") . " p
		ON gm.person_id = p.id  WHERE group_id=" . $group->getVar('id');
		$sql =  $sql . " order by p.lastname";
	
		if (!$result = $this->db->query($sql)) 
		{
			//echo "<br />NewbbForumHandler::get error::" . $sql;
			return false;
		}
		return $result;
			
	}



    function &addtoCart(&$groupid, $uid)
    {
		$sql="Insert into " . $this->db->prefix("oscmembership_cart");
		$sql.= "(xoops_uid,person_id) select " . $uid . ", gm.person_id from " . $this->db->prefix("oscmembership_group_members") . " gm left join " . $this->db->prefix("oscmembership_cart") . " c on c.person_id=gm.person_id and c.xoops_uid=" . $uid . " where group_id=" . $groupid . " and c.xoops_uid is null";


		$returnvalue=false;

		if (!$result = $this->db->query($sql)) 
		{
			$returnvalue=false;
		}
		else $returnvalue=true;

		return $returnvalue;
   }

    function &removefromCart(&$groupid, $uid)
    {
	$sql="delete from " . $this->db->prefix("oscmembership_cart") . " where xoops_uid = " . $uid . " and person_id in (select gm.person_id from " . $this->db->prefix("oscmembership_group_members") . " gm where group_id=" . $groupid ;

	$returnvalue=false;

	if (!$result = $this->db->query($sql)) 
	{
		$returnvalue=false;
	}
	else $returnvalue=true;

	return $returnvalue;
   }

    function &emptyCarttoGroup(&$groupid, $uid)
    {

	$sql = "Insert " . $this->db->prefix("oscmembership_group_members");
	$sql.= "(group_id, person_id) Select " . $groupid . ",c.person_id from " . $this->db->prefix("oscmembership_cart") . " c left join " . $this->db->prefix("oscmembership_group_members") . " gm on c.person_id = gm.person_id where gm.id is null and c.xoops_uid = " . $uid;

	if (!$result = $this->db->query($sql)) 
	{
		return false;
	}

   }


    function &create($isNew = true)
    {
    	$group = new Group();
        if ($isNew) {
            $group->setNew();
        }
        return $group;
    }

    function &get($id)
    {
        $group =&$this->create(false);
        if ($id > 0) 
	{
		$sql = "SELECT * FROM " . $this->db->prefix("oscmembership_group") . " WHERE id = " . intval($id);
		if (!$result = $this->db->query($sql)) 
		{
			echo "<br />NewbbForumHandler::get error::" . $sql;
			return false;
		} 
		if($row = $this->db->fetchArray($result)) 
		{
			$group->assignVars($row);
		}

		// Get Group Types for the drop-down
		$sql = "SELECT optionname FROM " . $this->db->prefix("oscmembership_list") . " WHERE id= 3 and optionid=" . $group->getVar('group_type') . " ORDER BY optionsequence";

		$result=$this->db->query($sql);

		while($row = $this->db->fetchArray($result)) 
		{
			$group->assignVar('group_typeName',$row['optionname']);
		}
        }
        return $group;
    }

function &modsearch($searcharray)
    //Search on criteria and return result
{
	$result='';
	$returnresult='';
	$ret=array();
	
        if (isset($searcharray)) 
	{
	        $group= &$this->create(false);
		$sql = "SELECT * FROM " . $this->db->prefix("oscmembership_group") . " WHERE (";

		$count = count($searcharray);
		if ( $count > 0 && is_array($searcharray) ) 
		{
			$sql .= "(group_name LIKE '%$searcharray[0]%' )";
		
			for ( $i = 1; $i < $count; $i++ ) 
			{
				$sql .= " OR ";	$group_handler = &xoops_getmodulehandler('group', 'oscmembership');
			
				$results = $group_handler->search($queryarray);
		
				$sql .= "(group_name LIKE '%$searcharray[$i]%')";
				
				$sql .= " ) order by group_name";
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
		$ret[$i]['title'] = $row['group_name'];

		$ret[$i]['time'] = '';
		$ret[$i]['uid'] = $row['id'];
		$i++;
		}

	}
	return $ret;
}


    function &search($searcharray)
    //Search on criteria and return result
    {
	$result='';
	$returnresult='';
	
        if (isset($searcharray)) 
	{
	        $group= &$this->create(false);
		$sql = "SELECT * FROM " . $this->db->prefix("oscmembership_group") . " WHERE (";

		$count = count($searcharray);
		if ( $count > 0 && is_array($searcharray) ) {
		$sql .= "(group_name LIKE '%$searcharray[0]%' )";}
		
		for ( $i = 1; $i < $count; $i++ ) {
			$sql .= " OR ";	$group_handler = &xoops_getmodulehandler('group', 'oscmembership');
			$sql .= "(group_name LIKE '%$searcharray[$i]%' )";
		
		}
		$sql .= ") order by group_name ";
	}
		if (!$result = $this->db->query($sql)) 
		{
			//echo "<br />NewbbForumHandler::get error::" . $sql;
			return false;
		}
		else
		{
			$groups=array();
			
			$i=0;
			
			while($row = $this->db->fetchArray($result)) 
			{
				
				$groupi=&$this->create(false);
				$groupi->assignVars($row);
				$groupi->assignVar('loopcount',$i);
								
				$groups[$i]=$groupi;
				
				$i++;
				

				
				
/*	$this->initVar('id',XOBJ_DTYPE_INT);
	$this->initVar('group_type',XOBJ_DTYPE_INT);
	$this->initVar('group_RoleListID',XOBJ_DTYPE_INT);
	$this->initVar('group_DefaultRole',XOBJ_DTYPE_INT);
	$this->initVar('group_Name',XOBJ_DTYPE_TXTBOX);
	$this->initVar('group_Description',XOBJ_DTYPE_TXTAREA);
	$this->initVar('group_hasSpecialProps',XOBJ_DTYPE_INT);
*/			
			
			}

		
			$group=$groups[0];
			$group->assignVar('totalloopcount',$i);
			$groups[0]=$group;
		
			return $groups;
		}
    }
		

    function &getarray()
    //Search on criteria and return result
    {
	$result='';
	$returnresult='';

	$group =&$this->create(false);
	
	$sql = "SELECT * FROM " . $this->db->prefix("oscmembership_group") ;

//	echo $sql;		
	$groupitems[][]=array();
	
	if ($result = $this->db->query($sql)) 
	{
		$oddrow=1;
		$i=0; //start counter
		while($row = $this->db->fetchArray($result)) 
		{
			if(isset($row))
			{
				$group =&$this->create(false);
				$group->assignVars($row);
				$groupitems[$i]['oddrow']=$oddrow;
				$oddrow=$oddrow * -1;
				$groupitems[$i]['group']=$group;
			}
			$i++; //start counter
		}
		return $groupitems;
	}
}	     
	function &update(&$group)
    	{
		$sql = "UPDATE " . $group->table
		. " SET "
		. "group_Name=" . $this->db->quoteString($group->getVar('group_Name'))
		. ",group_type=" .
		$this->db->quoteString($group->getVar('group_type'))
		. ",group_RoleListID=" . 	
		$this->db->quoteString($group->getVar('group_RoleListID'))
		. ",group_DefaultRole=" . 	
		$this->db->quoteString($group->getVar('group_DefaultRole'))
		. ",group_Description=" . 	
		$this->db->quoteString($group->getVar('group_Description'))
		. ",group_hasSpecialProps=" . 	
		$this->db->quoteString($group->getVar('group_hasSpecialProps'));
			
		$sql .=" where id=" . $group->getVar('id');
		
		if (!$result = $this->db->query($sql)) {
			echo "<br />oscmembershipHandler::get error::" . $sql;
			return false;
			}
	}

	     
	function &insert(&$group)
    	{
	
		$sql = "INSERT into " . $group->table
		. "(group_type, group_RoleListID, group_DefaultRole, group_Name,";
		$sql .= "group_Description, group_hasSpecialProps)";
		$sql = $sql . "values(" . $this->db->quoteString($group->getVar('group_type'))
		. "," . 
		$this->db->quoteString($group->getVar('group_RoleListID'))
		. "," . 
		$this->db->quoteString($group->getVar('group_DefaultRole'))
		. "," . 
		$this->db->quoteString($group->getVar('group_Name')) 
		. "," .
		$this->db->quoteString($group->getVar('group_Description')) 
		. "," .
		$this->db->quoteString($group->getVar('group_hasSpecialProps')) .
		 ")";
		
		if (!$result = $this->db->query($sql)) {
			echo "<br />oscmembershipHandler::get error::" . $sql;
			return false;
			}
		else
		{
			return $this->db->getInsertId();
		}
	}

}


?>