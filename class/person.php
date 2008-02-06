<?php
// $Id: person.php,v 1.1.1.1 2006/03/12 14:57:25 root Exp $
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

class  Person extends XoopsObject {
    var $db;
    var $table;

    function Person()
    {
        $this->db = &Database::getInstance();
        $this->table = $this->db->prefix("oscmembership_person");
	$this->initVar('id',XOBJ_DTYPE_INT);
	$this->initVar('churchid',XOBJ_DTYPE_INT);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX);
	$this->initVar('firstname', XOBJ_DTYPE_TXTBOX);
        $this->initVar('middlename', XOBJ_DTYPE_TXTBOX);
        $this->initVar('lastname', XOBJ_DTYPE_TXTBOX);
        $this->initVar('suffix', XOBJ_DTYPE_TXTBOX);
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
        $this->initVar('workemail', XOBJ_DTYPE_TXTBOX);
        $this->initVar('birthmonth', XOBJ_DTYPE_INT);
        $this->initVar('birthday', XOBJ_DTYPE_INT);
	$this->initVar('birthyear', XOBJ_DTYPE_INT);
	$this->initVar('membershipdate', XOBJ_DTYPE_TXTBOX);
	$this->initVar('gender', XOBJ_DTYPE_TXTBOX);
	$this->initVar('fmrid', XOBJ_DTYPE_TXTBOX);
	$this->initVar('clsid', XOBJ_DTYPE_TXTBOX);
	$this->initVar('famid', XOBJ_DTYPE_TXTBOX);
	$this->initVar('envelope', XOBJ_DTYPE_TXTBOX);
	$this->initVar('datelastedited', XOBJ_DTYPE_TXTBOX);
	$this->initVar('dateentered', XOBJ_DTYPE_TXTBOX);
	$this->initVar('enteredby', XOBJ_DTYPE_INT);
	$this->initVar('editedby', XOBJ_DTYPE_INT);

	$this->initVar('familyname', XOBJ_DTYPE_TXTBOX);

	$this->initVar('picloc',XOBJ_DTYPE_TXTBOX);
	$this->initVar('customfields',XOBJ_DTYPE_TXTBOX);
	$this->initVar('loopcount',XOBJ_DTYPE_INT);
	$this->initVar('oddrow',XOBJ_DTYPE_INT);
	$this->initVar('totalloopcount',XOBJ_DTYPE_INT);
	
	$this->initVar('addressflag',XOBJ_DTYPE_INT);
	$this->initVar('emailflag',XOBJ_DTYPE_INT);
    }

}    
    

class oscMembershipPersonHandler extends XoopsObjectHandler
{

    function &addtoFamily($personid, $familyid)
    {
		$sql = "Update  " . $this->db->prefix("oscmembership_person");
		$sql = $sql . " set famid=" . $familyid;
		$sql = $sql . " where id=" . $personid;     

		if (!$result = $this->db->query($sql)) 
		{
			return false;
		}
    }

    function &addCarttoFamily($uid,$familyid)
    {
		$sql = "Update  " . $this->db->prefix("oscmembership_person");
		$sql = $sql . " set famid=" . $familyid;
		$sql = $sql . " where id in (select person_id from " . $this->db->prefix("oscmembership_cart") . " where xoops_uid=" . $uid . ")";

		if (!$result = $this->db->query($sql)) 
		{
			return false;
		}
    }

    function &addtoGroup($personid, $groupid)
    {
		$sql = "Insert " . $this->db->prefix("oscmembership_group_members");
		$sql = $sql . "(group_id, person_id) values(" . $groupid . "," . $personid . ")";     

		if (!$result = $this->db->query($sql)) 
		{
			return false;
		}
    }

    function &addtoCart(&$personid, &$xoopsuid)
    {
		$sql = "Insert into " . $this->db->prefix("oscmembership_cart");
		$sql = $sql . "(xoops_uid, person_id) select " . $xoopsuid . "," . $personid . " from " . $this->db->prefix("oscmembership_person") . " p left join  " . $this->db->prefix("oscmembership_cart") . " c on p.id=c.person_id " . " and c.xoops_uid=" . $xoopsuid . " where c.person_id is null and p.id=" . $personid ;

		$return=true;
		if (!$result = $this->db->query($sql)) 
		{
			$return=false;
		}

		return $return;
    }

    function &removefromCart(&$personid, &$xoopsuid)
    {
    
		$sql = "delete from " . $this->db->prefix("oscmembership_cart");
		$sql = $sql . " where xoops_uid=" . $xoopsuid . " and person_id=" . $personid;     

		//echo $sql;
		
		if (!$result = $this->db->query($sql)) 
		{
			return false;
		}
    }

    function &wipeCart(&$xoopsuid)
    {
    
		$sql = "delete from " . $this->db->prefix("oscmembership_cart");
		$sql = $sql . " where xoops_uid=" . $xoopsuid;     

		//echo $sql;
		
		if (!$result = $this->db->query($sql)) 
		{
			return false;
		}
    }

    function &getCart($xoopsuid, $pullnoenvelope=false)
    {
		$sql = "select p.* from " . $this->db->prefix("oscmembership_cart") . " c join " . $this->db->prefix("oscmembership_person") . " p on c.person_id = p.id where c.xoops_uid=" . $xoopsuid ;

		if($pullnoenvelope)
		{
			$sql .= " and envelope is null or envelope=0 ";
		}
		
		//echo $sql;
		
		if (!$result = $this->db->query($sql)) 
		{
			return false;
		}
		else return $result;
    }

    function &getCartc($xoopsuid, $pullnoenvelope=false)
    {
    	$persons[]=array();
	
	$sql = "select p.* from " . $this->db->prefix("oscmembership_cart") . " c join " . $this->db->prefix("oscmembership_person") . " p on c.person_id = p.id where c.xoops_uid=" . $xoopsuid ;

	if($pullnoenvelope)
	{
		$sql .= " and envelope is null or envelope=0 ";
	}

	$returnfalse=false;
		
	if (!$result = $this->db->query($sql)) 
	{
		return $returnfalse;
	}
	else
	{
		$i=0;
		while($row = $this->db->fetchArray($result)) 
		{
			$person =&$this->create(false);
			$person->assignVars($row);
			$persons[$i]=$person;
			$i++;
		}

		
		if($i==0)
		{
			return $returnfalse;
		}

	}

		 
	return $persons;
    }
    
    
    
    function removefromFamily($personid )
    {
		$sql = "Update  " .
		$this->db->prefix("oscmembership_person");
		$sql = $sql . " set famid=0";
		$sql = $sql . " where id=" . $personid;     
	
		if (!$result = $this->db->query($sql)) 
		{
			return false;
		}
    }
       
    function removefromGroup($personid,$groupid )
    {
		$sql = "Delete from " .
		$this->db->prefix("oscmembership_group_members");
		$sql = $sql . " where group_id=" . $groupid . " and person_id = " . $personid;     

		if (!$result = $this->db->query($sql)) 
		{
			return false;
		}
    }


    
    function &create($isNew = true)
    {
        $person = new Person();
        if ($isNew) {
            $person->setNew();
        }
        return $person;
    }

    function &get($id)
    {
        if ($id > 0) 
	{
		$sql = "SELECT *, '' as text FROM " . 
		$this->db->prefix("oscmembership_person") . " WHERE id = " . intval($id);
		if (!$result = $this->db->query($sql)) 
		{
			return false;
		} 

		if($row = $this->db->fetchArray($result)) 
		{
        		$person =&$this->create(false);
		
			$person->assignVars($row);
			//pull custom fields
			$customresult=$this->getcustompersonData($id);

			$customrow=$this->db->fetchArray($customresult);

			$customfields=implode(",",$customrow);
			$person->assignVar('customfields',$customfields);
//	else $person->assignVar('customfields',null);
			
			return $person;

			
		}
		
        }
    }
    
    
	function &getcustompersonFields()
	//Search on criteria and return result
	{
		$result='';
		$returnresult='';
		$ret=array();
		
		// Get the list of custom person fields
		$sql = "SELECT * FROM " . $this->db->prefix("oscmembership_person_custom_master") . " cm JOIN " . $this->db->prefix("oscmembership_list") . " l ON  cm.type_ID = l.optionid WHERE l.id=4 ORDER BY custom_Order";

		if (!$result = $this->db->query($sql)) 
		{
			return false;
		}
		return $result;
	}

	function &getcustompersonField($customfield)
	//Search on criteria and return result
	{
		$result='';
		$returnresult='';
		$ret=array();
		
		
		// Get the list of custom person fields
		$sql = "SELECT * FROM " . $this->db->prefix("oscmembership_person_custom_master") . " cm JOIN " . $this->db->prefix("oscmembership_list") . " l ON  cm.type_ID = l.optionid WHERE l.id=4 and cm.custom_Field='" . $customfield . "' ORDER BY custom_Order";
	
		if (!$result = $this->db->query($sql)) 
		{
			//echo "<br />NewbbForumHandler::get error::" . $sql;
			return false;
		}
		return $result;
	}
	
	function &getcustompersonData($personid)
	//Search on criteria and return result
	{
		$result='';
		
		$customFields = $this->getcustompersonFields();

		//build sql field list
		$sqlfield="";
		while($row = $this->db->fetchArray($customFields)) 
		{
		
			$sqlfield.= "," .$row["custom_Field"];
		}
		
//		$sqlfield=substr($sqlfield,0,-1);
				
		// Get the list of custom person fields
		$sql = "SELECT per_ID " . $sqlfield . " FROM " . $this->db->prefix("oscmembership_person_custom") . " WHERE per_ID=" . $personid ;
		if (!$result = $this->db->query($sql)) 
		{
			echo "<br />PersonHandler::get error::" . $sql;
			return false;
		}

		return $result;
	}
    
    
    function &search($searcharray, $sort)
    //Search on criteria and return result
    {
	$result='';
	$returnresult='';
	
        if (isset($searcharray)) 
	{
	        $person= &$this->create(false);
		$sql = "SELECT * FROM " . $this->db->prefix("oscmembership_person") . " WHERE (";

		$count = count($searcharray);
		if ( $count > 0 && is_array($searcharray) ) {
		$sql .= "(lastname LIKE '%$searcharray[0]%' OR firstname LIKE '%$searcharray[0]%' OR homephone like '%$searcharray[0]%' or workphone like '%$searcharray[0]%' or cellphone like '%$searcharray[0]%')";
		
		for ( $i = 1; $i < $count; $i++ ) {
			$sql .= " OR ";	$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
	
		$results = $person_handler->search($queryarray);

		$sql .= "(lastname LIKE '%$searcharray[$i]%' OR firstname LIKE '%$searcharray[$i]%' or homephone LIKE '%$searcharray[$i]%' OR workphone LIKE '%$searcharray[$i]%' OR cellphone LIKE '%$searcharray[$i]%')";
		}
		if(isset($sort))
		{
			switch($sort)
			{
			case "name":
			$sql .= ") order by lastname, firstname ";
			break;
			case "city":
			$sql .= ") order by city ";
			break;
			case "zip":
			$sql .= ") order by zip ";
			break;
			case "state":
			$sql .= ") order by state ";
			break;
			case "citystate":
			$sql .= ") order by city,state ";
			break;
						
			default:
			$sql .= ") order by lastname, firstname ";
			break;
			}
		}
	}
		
		if (!$result = $this->db->query($sql)) 
		{
			//echo "<br />NewbbForumHandler::get error::" . $sql;
			return false;
		}
		$oddon=false;

		$loopcount = 0;

		while($row = $this->db->fetchArray($result)) 
		{
			$loopcount++;
			if($oddon) 
				{
				$returnresult = $returnresult .  "<tr class=odd>";
				$oddon=false;
			}
			else
			{
				$returnresult = $returnresult .  "<tr class=even>";
				$oddon=true;
			}
						
			$person->assignVars($row);
			
			$returnresult .= "<td width=10><input name=chk" . $loopcount .  " value=" . $person->getVar('id') . " type=checkbox></td><td>";
			
			$returnresult = $returnresult .  $person->getVar('lastname') . ", " . $person->getVar('firstname') . "</td>";
			$returnresult = $returnresult . "<td>" .  $person->getVar('address1') . "</td>";
			//"<br>" . $person->getVar('address2') . "</td>";
			$returnresult = $returnresult . "<td>";
			if($person->getVar('city')<>'')
			{
				$returnresult=$returnresult .  $person->getVar('city') . ", " . $person->getVar('state');
			}
			$returnresult = $returnresult . "</td>";
			//$returnresult = $returnresult . "<td>" . //$person->getVar('zip') . "</td>";
			$returnresult = $returnresult . "<td>" . $person->getVar('email') . "</td>";
			$returnresult = $returnresult . "<td><a href='persondetailform.php?id=" . $person->getVar('id') . "'>" . "Edit" . "</a></td>";
			$returnresult = $returnresult . "</tr>";

		}
		$returnresult .= "<input type=hidden name=loopcount value=" . $loopcount . "/>";
	}
	return $returnresult;
    }

    
function &search2($searcharray, $sort)
    //Search on criteria and return result
    {
    	$persons=$this->search3($searcharray, $sort);
	
	return $persons;
    }


function &search3($searcharray, $sort, $hasenvelope=null)
    //Search on criteria and return result
    {
	$result='';
	$returnresult='';
	$persons[]=array();
	
        if (isset($searcharray)) 
	{
	        $person= &$this->create(false);
		$sql = "SELECT * FROM " . $this->db->prefix("oscmembership_person") . " WHERE (";

		$count = count($searcharray);
		if ( $count > 0 && is_array($searcharray) ) 
		{
		$sql .= "(lastname LIKE '%$searcharray[0]%' OR firstname LIKE '%$searcharray[0]%' OR homephone like '%$searcharray[0]%' or workphone like '%$searcharray[0]%' or cellphone like '%$searcharray[0]%' or city like '%$searcharray[0]%' or state like '%$searcharray[0]%')";
		}

		if(!is_null($hasenvelope))
		{
			if($hasenvelope=true)
			{
				$sql .= " AND envelope>0";
			}
		}
		
		for ( $i = 1; $i < $count; $i++ ) 
		{
			$sql .= " OR ";	$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
	
		$results = $person_handler->search($queryarray);

		$sql .= "(lastname LIKE '%$searcharray[$i]%' OR firstname LIKE '%$searcharray[$i]%' or homephone LIKE '%$searcharray[$i]%' OR workphone LIKE '%$searcharray[$i]%' OR cellphone LIKE '%$searcharray[$i]%' or city like '%$searcharray[$i]%' or state like '%$searcharray[$i]%')";
		}
		if(isset($sort))
		{
			switch($sort)
			{
			case "name":
			$sql .= ") order by lastname, firstname ";
			break;
			case "city":
			$sql .= ") order by city ";
			break;
			case "zip":
			$sql .= ") order by zip ";
			break;
			case "state":
			$sql .= ") order by state ";
			break;
			case "citystate":
			$sql .= ") order by city,state ";
			break;
						
			default:
			$sql .= ") order by lastname, firstname ";
			break;
			}
		}
		
		if (!$result = $this->db->query($sql)) 
		{
			//echo "<br />NewbbForumHandler::get error::" . $sql;
			return false;
		}
		$oddon=false;

		$loopcount = 0;

		
		$oddrow=false;
		$person = new Person();
		$i=0; //start counter
		while($row = $this->db->fetchArray($result)) 
		{
			if(isset($row))
			{
				$person=&$this->create(false);
				$person->assignVars($row);
				
				if($person->getVar('address1') !=null)
				{$person->assignVar('addressflag',_oscmem_yes);}
				else 
				{$person->assignVar('addressflag',_oscmem_no);}
				
				if($person->getVar('email') != null)
				{$person->assignVar('emailflag',_oscmem_yes);}
				else { $person->assignVar('emailflag',_oscmem_no);}

				$person->assignVar('oddrow',$oddrow);

				$person->assignVar('loopcount',$i);
				
				if($oddrow){$oddrow=false;}
				else {$oddrow=true;}
			
				$persons[$i]=$person;
				
				
			}
			$i++;
			$loopcount++;

		}
		if($i>0)
		{
			$person=$persons[0];
			$person->assignVar('totalloopcount',$loopcount-1);
			$persons[0]=$person;
			
		}
		else
		{
			$person = new Person();
			$person->assignVar('totalloopcount',0);
			$persons[0]=$person;
		}
	}
			
	return $persons;
    }

function &modsearch($searcharray)
    //Search on criteria and return result
{
	$result='';
	$returnresult='';
	$ret=array();
	
        if (isset($searcharray)) 
	{
	        $person= &$this->create(false);
		$sql = "SELECT * FROM " . $this->db->prefix("oscmembership_person") . " WHERE (";

		$count = count($searcharray);
		if ( $count > 0 && is_array($searcharray) ) 
		{
			$sql .= "(lastname LIKE '%$searcharray[0]%' OR firstname LIKE '%$searcharray[0]%' OR homephone like '%$searcharray[0]%' or workphone like '%$searcharray[0]%' or cellphone like '%$searcharray[0]%')";
		
			for ( $i = 1; $i < $count; $i++ ) 
			{
				$sql .= " OR ";	$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
			
				$results = $person_handler->search($queryarray);
		
				$sql .= "(lastname LIKE '%$searcharray[$i]%' OR firstname LIKE '%$searcharray[$i]%' or homephone LIKE '%$searcharray[$i]%' OR workphone LIKE '%$searcharray[$i]%' OR cellphone LIKE '%$searcharray[$i]%')";
				
				$sql .= ") order by lastname, firstname ";
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
		$ret[$i]['title'] = $row['lastname'] . ", " . $row['firstname'] . " " . $row['address1'];
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


function &searchmembers($searcharray, $inFamily)
    //Search on criteria and return result
{
	$result='';
	$returnresult='';
	$ret=array();
	
        if (isset($searcharray)) 
	{
	        $person= &$this->create(false);
		$sql = "SELECT * FROM " . $this->db->prefix("oscmembership_person") . " WHERE (";

		$count = count($searcharray);
		if ( $count > 0 && is_array($searcharray) ) 
		{
			$sql .= "(lastname LIKE '%$searcharray[0]%' OR firstname LIKE '%$searcharray[0]%' OR homephone like '%$searcharray[0]%' or workphone like '%$searcharray[0]%' or cellphone like '%$searcharray[0]%')";
		
			for ( $i = 1; $i < $count; $i++ ) 
			{
				$sql .= " OR ";	$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
			
				$results = $person_handler->search($queryarray);
		
				$sql .= "(lastname LIKE '%$searcharray[$i]%' OR firstname LIKE '%$searcharray[$i]%' or homephone LIKE '%$searcharray[$i]%' OR workphone LIKE '%$searcharray[$i]%' OR cellphone LIKE '%$searcharray[$i]%')";
				
				$sql .= ") order by lastname, firstname ";
			}
		}

		$sql=$sql . ")";
		if(isset($inFamily))
		{
			if(!$inFamily)
			{
				$sql = $sql . " AND (famid=null or famid=0)";
				
			}
		}

		if (!$result = $this->db->query($sql)) 
		{
			//echo "<br />NewbbForumHandler::get error::" . $sql;
			return false;
		}

		$i = 0;

	
	}
	return $result;
}

function &searchgroupmembers($searcharray, $groupid)
    //Search on criteria and return result
{
	$result='';
	$returnresult='';
	$ret=array();
	
        if (isset($searcharray)) 
	{
	        $person= &$this->create(false);
		$sql = "SELECT * FROM " . $this->db->prefix("oscmembership_person") . " WHERE (";

		$count = count($searcharray);
		if ( $count > 0 && is_array($searcharray) ) 
		{
			$sql .= "(lastname LIKE '%$searcharray[0]%' OR firstname LIKE '%$searcharray[0]%' OR homephone like '%$searcharray[0]%' or workphone like '%$searcharray[0]%' or cellphone like '%$searcharray[0]%')";
		
			for ( $i = 1; $i < $count; $i++ ) 
			{
				$sql .= " OR ";	$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
			
				$results = $person_handler->search($queryarray);
		
				$sql .= "(lastname LIKE '%$searcharray[$i]%' OR firstname LIKE '%$searcharray[$i]%' or homephone LIKE '%$searcharray[$i]%' OR workphone LIKE '%$searcharray[$i]%' OR cellphone LIKE '%$searcharray[$i]%')";
				
				$sql .= ") order by lastname, firstname ";
			}
		}

		$sql=$sql . ")";
		if(isset($inFamily))
		{
			if(!$inFamily)
			{
				$sql = $sql . " AND (famid=null or famid=0)";
				
			}
		}

		if (!$result = $this->db->query($sql)) 
		{
			//echo "<br />NewbbForumHandler::get error::" . $sql;
			return false;
		}

		$i = 0;

	
	}
	return $result;
}
		
	     
	function &update(&$person)
    	{
		//compute membership date
		
		if($person->getVar('membershipdate')>0)
		{
		$membershipdate_conv=date('Y-m-d',strtotime($person->getVar('membershipdate')));
		}
		else
			$membershipdate_conv="";
		
		
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
		. ",birthday=" . $person->getVar('birthday');

		if($person->getVar('birthyear')!='')
		{
			$sql.= ",birthyear=" . $person->getVar('birthyear');
		}

		$sql.=",membershipdate=" . $this->db->quoteString($membershipdate_conv)
		. ",clsid=" . $person->getVar('clsid')
		. ",gender=" . $person->getVar('gender')
		. ",fmrid=" . $person->getVar('fmrid');
		
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
		. ",editedby=" . $this->db->quoteString($person->getVar('editedby')) . ",picloc=" . $this->db->quoteString($person->getVar('picloc')) . 
		 
		" where id=" . $person->getVar('id');
	

		if (!$result = $this->db->query($sql)) {
			return false;
			}
			else
			{
				//echo "explode: " . $person->getVar('customfields');
				$customupdate=explode(",",$person->getVar('customfields'));

				//update custom fields
				$customFields = $this->getcustompersonFields();
				if($customFields!=false)  //false = no custom fields
				{
					$sql="Update " . $this->db->prefix("oscmembership_person_custom");
					$sql.= " set ";
						
					$i=1;
					while($row = $this->db->fetchArray($customFields)) 
					{

						switch($row["type_ID"])
						{
							case "1": //True false
								switch($customupdate[$i-1])
								{
								case 1:
									$sql.= $row['custom_Field'] . "= 'true',";
									break;
								
								case 0:
									$sql.= $row['custom_Field'] . "= 'false',";
									break;
								default:
									$sql.= $row['custom_Field'] . "= null,";
									break;
									
								}
								break;
							case "2": //Date
								$sql.= $row['custom_Field']  . "='" . $customupdate[$i-1] . "',";
								break;
				
							case "3":
								$sql.= $row['custom_Field'] . "='" . $customupdate[$i-1] . "',";
							break;
	
							case "4":
								$sql.= $row['custom_Field'] . "='" . $customupdate[$i-1] . "',";
							break;
			
							case "5":
								$sql.= $row['custom_Field'] . "='" . $customupdate[$i-1] . "',";
							break;
			
							case "6": //year
								$sql.= $row['custom_Field'] . "='" . $customupdate[$i-1] . "',";
								break;
			
	
							case "7":  //season

								switch($customupdate[$i-1])
								{
								case  _oscmem_season_select :
									$sql.= $row['custom_Field'] . "='',";
									break;
								
								case "-------------" :
									$sql.= $row['custom_Field'] . "='',";
									break;
								
								default:
									$sql.= $row['custom_Field'] . "= " . $this->db->quoteString($customupdate[$i-1]) . ",";
									break;
								}
								break;

							case "8": //number
								if(!is_numeric($customupdate[$i-1]))
								{
									$sql.= $row['custom_Field'] . "= null,";
								}
								else
								{
									$sql.= $row['custom_Field'] . "= " . $this->db->quoteString($customupdate[$i-1]) . ",";
								}
								break;

						}
						$i++;					
					}
					
					$sql= rtrim($sql,",");
					$sql.= " WHERE per_ID=" . $person->getVar('id');
					
					if (!$result = $this->db->query($sql)) 
					{
						return false;
					}
				}
			}
			
		$return=true;
		return $return;
	
	}

	     
	function &insert(&$person)
    	{

		$sql = "INSERT into " . $person->table
		. "(title, firstname, lastname, middlename, " 
		. "suffix, address1, address2, city, state, zip, " 
		. "country, homephone, workphone, cellphone, email, "
		. "workemail, birthmonth, birthday, birthyear, "
		. "membershipdate, gender, famid, envelope, "
		. "datelastedited, editedby, dateentered, enteredby, picloc) ";
	
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
		$this->db->quoteString($person->getVar('enteredby')) . "," . $this->db->quoteString($person->getVar('picloc')) . ")";
		
		if (!$result = $this->db->query($sql)) {
			echo "<br />oscmembershipHandler::get error::" . $sql;
			return false;
			}
		else
		{
			$personid = $this->db->getInsertId();
			$sql="INSERT into " . $this->db->prefix("oscmembership_person_custom") . " (per_ID) values(" . $personid . ")";
			
			$this->db->query($sql);
			
			return $personid;
		}
	
	}

	function &delete(&$id)
    	{
		$person=$this->create(false);

		$sql="delete from " . $person->table . " where id=" . $id;
		$this->db->query($sql);

		//delete from families
		
	}
	
	
	function &getPersonbyenvelope($envelopenum)
    	{
		$person =&$this->create(false);

		if ($envelopenum > 0) 
		{
			$sql = "SELECT *, '' as text FROM " . 
			$this->db->prefix("oscmembership_person") . " WHERE envelope = " . intval($envelopenum);
			if (!$result = $this->db->query($sql)) 
			{
				return false;
			} 
	
			if($row = $this->db->fetchArray($result)) 
			{
				$person->assignVars($row);
				//pull custom fields
				$customresult=$this->getcustompersonData($person->getVar('id'));
				$customrow=$this->db->fetchArray($customresult);
				if(strpos($customrow,",")>0)
				{
					$customfields=implode(",",$customrow);
				}
				else $customfields=array();
	
				//echo $customfields;			
				$person->assignVar('customfields',$customfields);
			}
			
		}
		return $person;
	}

function &getorphans($searcharray, $sort, $hasenvelope=null)
    //Search on criteria and return result
    {
	$result='';
	$returnresult='';
	$persons[]=array();
	
        if (isset($searcharray)) 
	{
	        $person= &$this->create(false);
		$sql = "select p.id, f.familyname, f.id `famid`, p.firstname, p.lastname, p.email, p.address1, p.address2, p.city, p.state, p.zip FROM " . $this->db->prefix("oscmembership_person") . " p join " . $this->db->prefix("oscmembership_family") . " f on p.address1=f.address1 and p.city=f.city where p.famid=0";

		$count = count($searcharray);
		if ( $count > 0 && is_array($searcharray) ) 
		{
		$sql .= " and (p.lastname LIKE '%$searcharray[0]%' OR p.firstname LIKE '%$searcharray[0]%' OR p.homephone like '%$searcharray[0]%' or p.workphone like '%$searcharray[0]%' or p.cellphone like '%$searcharray[0]%' or p.city like '%$searcharray[0]%' or p.state like '%$searcharray[0]%')";
		}

		if(!is_null($hasenvelope))
		{
			if($hasenvelope=true)
			{
				$sql .= " AND envelope>0";
			}
		}
		
		for ( $i = 1; $i < $count; $i++ ) 
		{
			$sql .= " OR ";	$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
	
		$results = $person_handler->search($queryarray);

		$sql .= "(lastname LIKE '%$searcharray[$i]%' OR firstname LIKE '%$searcharray[$i]%' or homephone LIKE '%$searcharray[$i]%' OR workphone LIKE '%$searcharray[$i]%' OR cellphone LIKE '%$searcharray[$i]%' or city like '%$searcharray[$i]%' or state like '%$searcharray[$i]%')";
		}
		if(isset($sort))
		{
			switch($sort)
			{
			case "name":
			$sql .= ") order by lastname, firstname ";
			break;
			case "city":
			$sql .= ") order by city ";
			break;
			case "zip":
			$sql .= ") order by zip ";
			break;
			case "state":
			$sql .= ") order by state ";
			break;
			case "citystate":
			$sql .= ") order by city,state ";
			break;
						
			default:
			$sql .= ") order by lastname, firstname ";
			break;
			}
		}
		
		if (!$result = $this->db->query($sql)) 
		{
			//echo "<br />NewbbForumHandler::get error::" . $sql;
			return false;
		}
		$oddon=false;

		$loopcount = 0;

		
		$oddrow=false;
		$person = new Person();
		$i=0; //start counter
		while($row = $this->db->fetchArray($result)) 
		{
			if(isset($row))
			{
				$person=&$this->create(false);
				$person->assignVars($row);
				
				if($person->getVar('address1') !=null)
				{$person->assignVar('addressflag',_oscmem_yes);}
				else 
				{$person->assignVar('addressflag',_oscmem_no);}
				
				if($person->getVar('email') != null)
				{$person->assignVar('emailflag',_oscmem_yes);}
				else { $person->assignVar('emailflag',_oscmem_no);}

				$person->assignVar('oddrow',$oddrow);

				$person->assignVar('loopcount',$i);
				
				if($oddrow){$oddrow=false;}
				else {$oddrow=true;}
			
				$persons[$i]=$person;
				
				
			}
			$i++;
			$loopcount++;

		}
		if($i>0)
		{
			$person=$persons[0];
			$person->assignVar('totalloopcount',$loopcount-1);
			$persons[0]=$person;
			
		}
		else
		{
			$person = new Person();
			$person->assignVar('totalloopcount',0);
			$persons[0]=$person;
		}
	}
			
	return $persons;
    }

}


?>