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
        $this->initVar('recipient', XOBJ_DTYPE_TXTBOX);
        $this->initVar('address1', XOBJ_DTYPE_TXTBOX);
        $this->initVar('address2', XOBJ_DTYPE_TXTBOX);
        $this->initVar('city', XOBJ_DTYPE_TXTBOX);
        $this->initVar('state', XOBJ_DTYPE_TXTBOX);
				$this->initVar('zip',XOBJ_DTYPE_TXTBOX);
        $this->initVar('country', XOBJ_DTYPE_TXTBOX);
				$this->initVar('sortme',XOBJ_DTYPE_TXTBOX);
				$this->initVar('body',XOBJ_DTYPE_TXTBOX);
    }
}    

    function Labelcriteria()
    {
				$this->initVar('id',XOBJ_DTYPE_INT);
        $this->initVar('bdiraddress', XOBJ_DTYPE_INT);
        $this->initVar('bdirwedding', XOBJ_DTYPE_INT);
        $this->initVar('bdirbirthday', XOBJ_DTYPE_INT);
        $this->initVar('bdirfamilyphone', XOBJ_DTYPE_INT);
        $this->initVar('bdirfamilywork', XOBJ_DTYPE_INT);
        $this->initVar('bdirfamilycell', XOBJ_DTYPE_INT);
        $this->initVar('bdirfamilyemail', XOBJ_DTYPE_INT);
        $this->initVar('bdirpersonalphone', XOBJ_DTYPE_INT);
        $this->initVar('bdirpersonalwork', XOBJ_DTYPE_INT);
        $this->initVar('bdirpersonalcell', XOBJ_DTYPE_INT);
        $this->initVar('bdirpersonalemail', XOBJ_DTYPE_INT);
        $this->initVar('bdirpersonalworkemail', XOBJ_DTYPE_INT);
        $this->initVar('sdirclassifications', XOBJ_DTYPE_TXTBOX);
        $this->initVar('sdirroleheads', XOBJ_DTYPE_TXTBOX);

    }


class oscMembershipLabelHandler extends XoopsObjectHandler
{
    
    function &create($isNew = true)
    {
        $label = new Label();
        if ($isNew) {
            $label->setNew();
	    $label->cr="<br>";
        }
        return $label;
    }

    function &createlabelcriteria($isNew = true)
    {
        $labelcriteria = new Labelcriteria();
        if ($isNew) {
            $labelcriteria->setNew();
        }
        return $labelcriteria;
    }


    function &getlabels($bSortFirstName, $baltFamilyName, $arrGroups, $sDirClassifications,$labelcriteria)
    {
	$labels[]=array();
    
	$i=0;

	$cr="'<br>'";
	$blank="''";		
	$sGroupTable = "";
	
	$sWhereExt="";
		
//	if (strlen($sDirClassifications)) $sClassQualifier = "AND person.clsid in (" . $sDirClassifications . ")";


/*	
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
*/		
		//Determine sort selection
	if($bSortFirstName)
		$sortMe = " person.firstname ";
	else
		$sortMe=" person.lastname ";

/*
	$sSQL = "(SELECT *, 0 AS memberCount, " . $sortMe . " AS SortMe  FROM  " . $this->db->prefix("oscmembership_person") . " person $sGroupTable LEFT JOIN " . $this->db->prefix("oscmembership_family") . " family ON family.id= person.famid WHERE person.famid = 0 " . " $sWhereExt $sClassQualifier)
		UNION (SELECT *, COUNT(*) AS memberCount, familyname AS SortMe FROM " . $this->db->prefix("oscmembership_person") . "  person $sGroupTable LEFT JOIN  " . $this->db->prefix("oscmembership_family") . " family ON person.famid = family.id WHERE person.famid > 0  " . " $sWhereExt $sClassQualifier GROUP BY person.famid HAVING memberCount = 1)
		UNION (SELECT *, COUNT(*) AS memberCount, familyname AS SortMe FROM " . $this->db->prefix("oscmembership_person") . " person $sGroupTable LEFT JOIN  " . $this->db->prefix("oscmembership_family") . " family ON person.famid = family.id WHERE person.famid > 0 " . " $sWhereExt $sClassQualifier GROUP BY person.famid HAVING memberCount > 1) ";
	
	if($baltFamilyName) 
	{
		$sSQL= $sSQL . "UNION (SELECT *, COUNT(*) AS memberCount, altfamilyname AS SortMe FROM " . $this->db->prefix("oscmembership_person") . " person $sGroupTable LEFT JOIN  " . $this->db->prefix("oscmembership_family") . " family ON person.famid = family.id WHERE person.famid > 0  AND length(family.altfamilyname)>0 " . " $sWhereExt $sClassQualifier GROUP BY person.famid HAVING memberCount = 1)
		UNION (SELECT *, COUNT(*) AS memberCount, altfamilyname AS SortMe FROM " . $this->db->prefix("oscmembership_person") . " person $sGroupTable LEFT JOIN " . $this->db->prefix("oscmembership_family") . " family ON person.famid = family.id WHERE person.famid > 0 AND length(family.altfamilyname)>0 " . " $sWhereExt $sClassQualifier GROUP BY person.famid HAVING memberCount > 1) ";
	}	
	
	$sSQL = $sSQL . "ORDER BY SortMe ";
*/


	$familyprefix="";
/*
$sSQL="
CREATE TABLE  `tmplabel` (
  `recipient` varchar(255) default NULL,
  `addressLine1` varchar(255) default NULL,
  `addressLine2` varchar(255) default NULL,
  `city` varchar(255) default NULL,
  `state` varchar(255) default NULL,
  `zip` varchar(255) default NULL,
  `sortme` varchar(255) default NULL,
  `body` text )";
  */

	$sSQL= "truncate table tmplabel";
	$this->db->query($sSQL);

	$address="'','','','',''";
	$fambody="''";
	
//Build column clause
	$familyprefix="";
	$recipientplus="''";
	$famrecipient="familyname";

//	echo $labelcriteria->getVar('bdiraddress');
	
	If($labelcriteria->getVar('bdiraddress'))
	{
		$address="address1, address2,city,state,zip";
	}	
	
	If($labelcriteria->getVar('bdirwedding'))
	{
		$fambody.=", concat($cr, coalesce(weddingdate,$blank)) ";
	}

	$bdate="''";	
	if($labelcriteria->getVar('bdirbirthday'))
	{ $bdate=" concat(' (', birthday, '/', birthmonth, ')')"; }

	if($labelcriteria->getVar('bdirfamilyphone'))
	{ $fambody.= ", concat($cr, coalesce(homephone,$blank))"; }
	
	if($labelcriteria->getVar('bdirfamilywork'))
	{ $fambody.= ", concat($cr, coalesce(workphone,$blank)) "; }
	
	if($labelcriteria->getVar('bdirfamilycell'))
	{ $fambody.=", conat($cr, coalesce(cellphone,$blank))"; }
	
	if($labelcriteria->getVar('bdirfamilyemail'))
	{ $fambody.=", concat($cr, coalesce(email,$blank))"; }
		
	if($labelcriteria->getVar('bdirpersonalphone'))
	{ $recipientplus.=', concat(' . $cr . ', coalesce(homephone,' . $blank . '))'; }
	
//	echo $recipientplus;
	
	if($labelcriteria->getVar('bdirpersonalwork'))
	{ $recipientplus.=", concat($cr, coalesce(workphone,$blank))"; }

	if($labelcriteria->getVar('bdirpersonalcell'))
	{ $recipientplus.=", concat($cr, coalesce(cellphone,$blank))"; }

	if($labelcriteria->getVar('bdirpersonalemail'))
	{ $recipientplus.=", concat($cr, coalesce(email,$blank)) "; }

	if($labelcriteria->getVar('bdirpersonalworkemail'))
	{ $recipientplus.=", concat($cr, coalesce(workemail,$blank)) "; }

	$sSQL = "insert into tmplabel Select concat(lastname, ', ', firstname,$bdate)," . $address . ", $sortMe, concat($recipientplus) from " . $this->db->prefix("oscmembership_person") . " person " . $sGroupTable . " where famid=0" . $sWhereExt;
	$this->db->query($sSQL); 
//	echo $sSQL;

	$sortMe="familyname";	
	$sSQL = "insert into tmplabel Select concat('$familyprefix', " . $famrecipient . ")," . $address . ", $sortMe,concat($fambody) from " . $this->db->prefix("oscmembership_family") ;
//echo $sSQL;
	$this->db->query($sSQL); 
	
	$sSQL="select * from tmplabel";
	$result=$this->db->query($sSQL); 

	$i=0;
	$label=new Label();
	while($row = $this->db->fetchArray($result)) 
	{
		if(isset($row))
		{
			$label->assignVars($row);
			$labels[$i]['recipient']=$label->getVar('recipient');
			$labels[$i]['address1']=$label->getVar('address1');
			$labels[$i]['address2']=$label->getVar('address2');
			$labels[$i]['city']=$label->getVar('city');
			$labels[$i]['state']=$label->getVar('state');
			$labels[$i]['zip']=$label->getVar('zip');
			$labels[$i]['country']=$label->getVar('country');
			$labels[$i]['sortme']=$label->getVar('sortme');
			$labels[$i]['body']=$label->getVar('body');
		}		
	
//		echo $labels[$i]['recipient'];	
		$i++;	
	}

 	return $labels;   
    }    
    
	
}


?>