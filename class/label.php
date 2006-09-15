<?php
// $Id: label.php, 2006/09/13
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

class  Label extends XoopsObject {
    var $db;
//    var $table;

    function Label()
    {
        $this->db = &Database::getInstance();
//        $this->table = $this->db->prefix("oscmembership_person");
//
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
    }

}    
    

class oscMembershipLabelHandler extends XoopsObjectHandler
{


    
    function &create($isNew = true)
    {
        $label = new Label();
        if ($isNew) {
            $label->setNew();
        }
        return $label;
    }


    function &getlabels($bSortFirstName, $baltFamilyName, $arrGroups, $sDirClassifications)
    {
	$labels[]=array();
    
	$i=0;

	$sGroupTable = "";
	
	$sWhereExt="";
	
	if (strlen($sDirClassifications)) $sClassQualifier = "AND person.clsid in (" . $sDirClassifications . ")";

	
	if (!empty($arrGroups))
	{

		$sGroupTable = ", " . $db->prefix('oscmembership_p2g2r');

		foreach ($arrGroups as $Grp)
		{
			$aGroups[$count++] = $Grp;
		}
		$sGroupsList = implode(",",$aGroups);
	
		$sWhereExt .= "AND per_ID = p2g2r_per_ID AND p2g2r_grp_ID in (" . $sGroupsList . ") ";
	
		// This is used by per-role queries to remove duplicate rows from people assigned multiple groups.
		$sGroupBy = " GROUP BY per_ID";
	}
		
		//Determine sort selection
	if($bSortFirstName)
		$sortMe = " person.firstname ";
	else
		$sortMe=" person.lastname ";

	$sSQL = "(SELECT *, 0 AS memberCount, " . $sortMe . " AS SortMe  FROM  " . $this->db->prefix("oscmembership_person") . " person $sGroupTable LEFT JOIN " . $this->db->prefix("oscmembership_family") . " family ON family.id= person.famid WHERE person.famid = 0 " . " $sWhereExt $sClassQualifier)
		UNION (SELECT *, COUNT(*) AS memberCount, familyname AS SortMe FROM " . $this->db->prefix("oscmembership_person") . "  person $sGroupTable LEFT JOIN  " . $this->db->prefix("oscmembership_family") . " family ON person.famid = family.id WHERE person.famid > 0  " . " $sWhereExt $sClassQualifier GROUP BY person.famid HAVING memberCount = 1)
		UNION (SELECT *, COUNT(*) AS memberCount, familyname AS SortMe FROM " . $this->db->prefix("oscmembership_person") . " person $sGroupTable LEFT JOIN  " . $this->db->prefix("oscmembership_family") . " family ON person.famid = family.id WHERE person.famid > 0 " . " $sWhereExt $sClassQualifier GROUP BY person.famid HAVING memberCount > 1) ";
	
	if($baltFamilyName) 
	{
		$sSQL= $sSQL . "UNION (SELECT *, COUNT(*) AS memberCount, altfamilyname AS SortMe FROM " . $this->db->prefix("oscmembership_person") . " person $sGroupTable LEFT JOIN  " . $this->db->prefix("oscmembership_family") . " family ON person.famid = family.id WHERE person.famid > 0  AND length(family.altfamilyname)>0 " . " $sWhereExt $sClassQualifier GROUP BY person.famid HAVING memberCount = 1)
		UNION (SELECT *, COUNT(*) AS memberCount, altfamilyname AS SortMe FROM " . $this->db->prefix("oscmembership_person") . " person $sGroupTable LEFT JOIN " . $this->db->prefix("oscmembership_family") . " family ON person.famid = family.id WHERE person.famid > 0 AND length(family.altfamilyname)>0 " . " $sWhereExt $sClassQualifier GROUP BY person.famid HAVING memberCount > 1) ";
	}	
	
	$sSQL = $sSQL . "ORDER BY SortMe ";


	$familyprefix="";
	$sSQL="
CREATE TABLE  `xoops`.`tmplabel` (
  `Recipient` varchar(255) default NULL,
  `AddressLine1` varchar(255) default NULL,
  `AddressLine2` varchar(255) default NULL,
  `City` varchar(255) default NULL,
  `State` varchar(255) default NULL,
  `Zip` varchar(255) default NULL
) ";
	$sSQL= "truncate table tmpLabel";
	$this->db->query($sSQL);
	
	$sSQL = "insert into tmpLabel Select concat(lastname, ',', firstname),address1,address2,city,state,zip from " . $this->db->prefix("oscmembership_person") . " where famid=0";
	$this->db->query($sSQL); 
	
	$sSQL = "insert into tmpLabel Select concat('$familyprefix', familyname),address1,address2, city, state, zip from " . $this->db->prefix("oscmembership_family") ;
echo $sSQL;
	$this->db->query($sSQL); 
	
	$sSQL="select * from tmpLabel";
	if (!$result = $this->db->query($sSQL)) 
		{
			return false;
		}


	
	$labels[$i]['labelname']="Name";
	$labels[$i]['address1']="Address";
	
 	return $labels;   
    }    
    
	
}


?>