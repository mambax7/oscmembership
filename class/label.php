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
	$this->initVar('lastname',XOBJ_DTYPE_TXTBOX);
	$this->initVar('firstname',XOBJ_DTYPE_TXTBOX);
	$this->initVar('addresslabel',XOBJ_DTYPE_TXTBOX);
        $this->initVar('AddressLine1', XOBJ_DTYPE_TXTBOX);
        $this->initVar('AddressLine2', XOBJ_DTYPE_TXTBOX);
        $this->initVar('City', XOBJ_DTYPE_TXTBOX);
        $this->initVar('State', XOBJ_DTYPE_TXTBOX);
	$this->initVar('Zip',XOBJ_DTYPE_TXTBOX);
        $this->initVar('country', XOBJ_DTYPE_TXTBOX);
	$this->initVar('sortme',XOBJ_DTYPE_TXTBOX);
	$this->initVar('body',XOBJ_DTYPE_TXTBOX);
	$this->initVar('person_id',XOBJ_DTYPE_INT);
	$this->initVar('picloc',XOBJ_DTYPE_TXTBOX);
	$this->initVar('homephone',XOBJ_DTYPE_TXTBOX);
	$this->initVar('workphone',XOBJ_DTYPE_TXTBOX);
	$this->initVar('cellphone',XOBJ_DTYPE_TXTBOX);
	$this->initVar('email',XOBJ_DTYPE_TXTBOX);
	$this->initVar('workemail',XOBJ_DTYPE_TXTBOX);
	$this->initVar('birthday',XOBJ_DTYPE_TXTBOX);
	$this->initvar('birthmonth',XOBJ_DTYPE_TXTBOX);
	$this->initvar('birthyear',XOBJ_DTYPE_TXTBOX);
	$this->initvar('membershipdate',XOBJ_DTYPE_TXTBOX);
	$this->initvar('classification',XOBJ_DTYPE_TXTBOX);
	$this->initvar('gender',XOBJ_DTYPE_TXTBOX);
    }
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


    function &getlabels($bSortFirstName, $baltFamilyName, $sGroupsList, $sDirClassifications,$labelcriteria, $baltFamilyNamedup, $baltIndividualOnly)
    {
	$labels[]=array();
	$i=0;

	$cr="'<br>'";
	$blank="''";		
	$sGroupTable = "";
	
	$sWhereExt="";
		
	if (strlen($sDirClassifications)) $sClassQualifier = "AND person.clsid in (" . $sDirClassifications . ")";
	
	if (!empty($sGroupsList))
	{

		$sGroupTable = ", " . $this->db->prefix("oscmembership_group_members") . " g ";
	
		$sWhereExt .= " AND g.person_id = person.id AND g.group_id in (" . $sGroupsList . ") ";
	
		// This is used by per-role queries to remove duplicate rows from people assigned multiple groups.
		$sGroupBy = " GROUP BY per_ID";
	}
		
		//Determine sort selection
	if($bSortFirstName)
		$sortMe = " person.firstname ";
	else
		$sortMe=" person.lastname ";


	$familyprefix="";

//$sSQL=" drop table `tmplabel`;
	$sSQL= "CREATE TEMPORARY TABLE  `tmplabel` (
	`person_id` int default null,	
	`recipient` varchar(255) default NULL,
	`AddressLine1` varchar(255) default NULL,
	`AddressLine2` varchar(255) default NULL,
	`addresslabel` text null,
	`City` varchar(255) default NULL,
	`State` varchar(255) default NULL,
	`Zip` varchar(255) default NULL,
	`sortme` varchar(255) default NULL,
	`familyid` int default null,
	`body` text,
	`picloc` text )";

//	$sSQL= "truncate table tmplabel";
	$this->db->query($sSQL);

	$address="'','','','','',''";
	$fambody="''";
	
//Build column clause
	$familyprefix="";
	$recipientplus="''";
//	echo $labelcriteria->getVar('bdiraddress');
	
	If($labelcriteria->getVar('bdiraddress'))
	{
		$famaddress="fam.address1, fam.address2,fam.city,fam.state,fam.zip";
		$address="address1, address2,'',city,state,zip";
	}	
	
	If($labelcriteria->getVar('bdirwedding'))
	{
		$fambody.=", concat($cr,'" . _oscmem_weddingdate . ": ', coalesce(weddingdate,$blank)) ";
	}

	$bdate="''";	
	if($labelcriteria->getVar('bdirbirthday'))
	{ $bdate=" concat(' (', birthmonth, '/', birthday , ')')"; }

	if($labelcriteria->getVar('bdirfamilyphone'))
	{ $fambody.= ", concat($cr, '" . _oscmem_homephone . ": ',  coalesce(homephone,$blank))"; }
	
	if($labelcriteria->getVar('bdirfamilywork'))
	{ $fambody.= ", concat($cr, '" . _oscmem_workphone . ": ', coalesce(workphone,$blank)) "; }
	
	if($labelcriteria->getVar('bdirfamilycell'))
	{ $fambody.=", concat($cr, '" . _oscmem_cellphone . ": ', coalesce(cellphone,$blank))"; }
	
	if($labelcriteria->getVar('bdirfamilyemail'))
	{ $fambody.=", concat($cr, '" . _oscmem_email . ": ', coalesce(email,$blank))"; }
		
	if($labelcriteria->getVar('bdirpersonalphone'))
	{ $recipientplus.=", concat($cr, '" . _oscmem_phone . ": ', coalesce(homephone,$blank))"; }
	
//	echo $recipientplus;
	
	if($labelcriteria->getVar('bdirpersonalwork'))
	{ $recipientplus.=", concat($cr, '" . _oscmem_workphone . ": ', coalesce(workphone,$blank))"; }

	if($labelcriteria->getVar('bdirpersonalcell'))
	{ $recipientplus.=", concat($cr, '". _oscmem_cellphone . ": ', coalesce(cellphone,$blank))"; }

	if($labelcriteria->getVar('bdirpersonalemail'))
	{ $recipientplus.=", concat($cr, '" . _oscmem_email . ": ', coalesce(email,$blank)) "; }

	if($labelcriteria->getVar('bdirpersonalworkemail'))
	{ $recipientplus.=", concat($cr, '" . _oscmem_workemail . ": ', coalesce(workemail,$blank)) "; }

	$recipientplus.=",$cr";
	$fambody.=",$cr";
	
		
	$sSQL = "insert into tmplabel Select person.id, concat(lastname, ', ', firstname,$bdate)," . $address . ", $sortMe,0, concat($recipientplus), picloc from " . $this->db->prefix("oscmembership_person") . " person " . $sGroupTable . " where famid=0" . $sWhereExt;

	$this->db->query($sSQL); 

//echo $baltIndividualOnly;
	if(($baltIndividualOnly)!=1)
	{
		$sSQL = "insert into tmplabel(familyid) Select distinct fam.id from " . $this->db->prefix("oscmembership_family") . " fam , " . $this->db->prefix("oscmembership_person") . " person  " . $sGroupTable . " where person.famid>0 and  fam.id=person.famid " . $sWhereExt;
	}
	//Pull all family individuals
	else
	{
		$sSQL = "insert into tmplabel Select person.id, concat(lastname, ', ', firstname,$bdate)," . $address . ", $sortMe,0, concat($recipientplus), picloc from " . $this->db->prefix("oscmembership_person") . " person " . $sGroupTable . " where famid<>0" . $sWhereExt;
	}
	$this->db->query($sSQL); 

	if($baltIndividualOnly==0) //do not do if individual only
	{
		if($baltFamilyName)
		{
			$famrecipient="altfamilyname";
			$sortMe="altfamilyname";
		}
		else
		{
			$famrecipient="familyname";
			$sortMe="familyname";
		}

	
		$sSQL="update tmplabel, " . $this->db->prefix("oscmembership_family") . " fam set recipient=concat('$familyprefix', " . $famrecipient . "), AddressLine1=fam.address1, AddressLine2=fam.address2, tmplabel.City=fam.city, tmplabel.state=fam.state, tmplabel.zip=fam.zip, tmplabel.picloc=fam.picloc, sortme=$sortMe, body=concat($fambody) where tmplabel.familyid=fam.id";
		$this->db->query($sSQL); 
	
		if($baltFamilyNamedup)
		{
			$sortMe='altfamilyname';
	
			$sSQL = "insert into tmplabel(familyid) Select distinct fam.id from " . $this->db->prefix("oscmembership_family") . " fam , " . $this->db->prefix("oscmembership_person") . " person  " . $sGroupTable . " where person.famid>0 and  fam.id=person.famid and fam.altfamilyname is not null " . $sWhereExt;
			$this->db->query($sSQL); 
		
			$sSQL="update tmplabel, " . $this->db->prefix("oscmembership_family") . " fam set recipient=concat('$familyprefix', altfamilyname), AddressLine1=fam.address1, AddressLine2=fam.address2, tmplabel.City=fam.city, tmplabel.state=fam.state, tmplabel.zip=fam.zip, tmplabel.picloc=fam.picloc sortme=$sortMe, body=concat($fambody) where tmplabel.familyid=fam.id and recipient is null";
			$this->db->query($sSQL); 
		}
	
			
		$persondetail_handler = &xoops_getmodulehandler('person', 'oscmembership');    
		$person = $persondetail_handler->create(true);  //only one record
		
		
		$sSQL="select distinct person.* from " . $this->db->prefix("oscmembership_person") . " person join tmplabel t on person.famid = t.familyid where person.famid>0";
	
		$result=$this->db->query($sSQL);
	
		$i=0;		
		$families=array();
		
		while($row = $this->db->fetchArray($result)) 
		{
			if(isset($row))
			{
	//			if(! isset($families[$i]['label']))
	//			$families[$i]['label']="";
				$person = $persondetail_handler->create(true);  //only one record	
				$person->assignVars($row);
				$i=$person->getVar('famid');
	//			$families[$i]['personid']=$person->getVar('id');
				$families[$i]['familyid']=$person->getVar("famid");
				$families[$i]['label'].=$person->getVar("lastname") . ", " . $person->getVar("firstname");
				
				if($labelcriteria->getVar('bdirbirthday'))
				{
					if($person->getVar('birthmonth')<>0)
					$families[$i]['label'].= " (" . $person->getVar('birthmonth') . "/" . $person->getVar('birthday') . ")";				
				}
		
				$families[$i]['label'].="<br>";
			}		
	//		$i++;
		}	
		
		foreach($families as $family)
		{
			$sSQL = "Update tmplabel set body=concat(body, '<br>" . $family['label'] . "'), person_id=0" . " where familyid=" . $family['familyid'];
			$this->db->query($sSQL); 
	
	//echo $sSQL;
	
		}
	}

	
// $sSQL = "insert into tmplabel Select concat('$familyprefix', " . $famrecipient . ")," . $address . ", $sortMe, id, concat($fambody) from " . $this->db->prefix("oscmembership_family") ;

	if($labelcriteria->getVar('bincompleteaddress'))
	{
		$sSQL="delete from tmplabel where ";
	}
	
	$sSQL="select * from tmplabel order by sortme";
	$result=$this->db->query($sSQL); 

	$i=0;
	$label=new Label();
	while($row = $this->db->fetchArray($result)) 
	{
		if(isset($row))
		{
			$label=new Label();
			$label->assignVars($row);
			$labels[$i]['recipient']=$label->getVar('recipient');
			//echo $labelcriteria->getVar('bdiraddress');
			
			If($labelcriteria->getVar('bdiraddress'))
			{
				$labels[$i]['addresslabel']=$label->getVar('AddressLine1');
							//echo $label->getVar('AddressLine1');

				if($label->getVar('AddressLine2')<>'')
				$labels[$i]['addresslabel'].= "\n" . $label->getVar('AddressLine2');
				$labels[$i]['addresslabel'].= "\n" . $label->getVar('City') . ", " . $label->getVar('State') . "  " . $label->getVar('Zip');
			}
			$labels[$i]['address1']=$label->getVar('AddressLine1');
			$labels[$i]['address2']=$label->getVar('AddressLine2');
			$labels[$i]['city']=$label->getVar('City');
			$labels[$i]['state']=$label->getVar('State');
			$labels[$i]['zip']=$label->getVar('Zip');
			$labels[$i]['country']=$label->getVar('country');
			$labels[$i]['sortme']=$label->getVar('sortme');
			$labels[$i]['body']=$label->getVar('body');
			$labels[$i]['picloc']=$label->getVar('picloc');
		}		
	
	//echo $labels[$i]['addresslabel'];	
		$i++;	
	}


 	return $labels;   
    }    
    

    
    function &getexport($bSortFirstName, $baltFamilyName, $sGroupsList, $labelcriteria)
    {
	$labels=array();
    
	$i=0;

	$headersql="lastname, firstname";
	$indivbodysql="lastname,',',firstname";
	$customwhere="";
	$familybodysql="familyname,',',''";
	$cr="'<br>'";
	$blank="''";		
	$sGroupTable = "";
	
	$sWhereExt=" ";
	
/*
	if (!empty($labelcriteria->getVar('sdirclassifications')))
	{
	 $sWhereExt = "  and person.clsid in (" . $labelcriteria->getVar('sdirclassifications') . ")";
	 }
*/	
	if (!empty($sGroupsList))
	{
		$sGroupTable = "join " . $this->db->prefix("oscmembership_group_members") . " g on g.person_id = person.id ";
	
		$sWhereExt .= " AND g.group_id in (" . $sGroupsList . ") ";
	
		// This is used by per-role queries to remove duplicate rows from people assigned multiple groups.
		$sGroupBy = " GROUP BY per_ID";
	}
	
	$gender = $labelcriteria->getVar('gender');
	
	if (!empty($gender))
	{
		$sWhereExt .= " AND person.gender=" . $labelcriteria->getVar('gender') ;
	}

	$membershipdatefrom= $labelcriteria->getVar('membershipdatefrom');
	if (!empty($membershipdatefrom))
	{
		$sWhereExt .= " AND person.membershipdate between " . $this->db->quoteString($labelcriteria->getVar('membershipdatefrom')) . " AND " . $this->db->quoteString($labelcriteria->getVar('membershipdateto'));
	}

	$birthdayfrom=$labelcriteria->getVar('birthdaymonthfrom');
	
	if(!empty($birthdayfrom))
	{
		$sWhereExt .= " AND person.birthmonth between " . $labelcriteria->getVar('birthdaymonthfrom') . " AND " . $labelcriteria->getVar('birthdaymonthto');
	}
			

	$birthdayyearfrom=$labelcriteria->getVar('birthdayyearfrom');
	if(!empty($birthdayyearfrom))
	{
		$sWhereExt .= " AND person.birthyear between " . $labelcriteria->getVar('birthdayyearfrom') . " AND " . $labelcriteria->getVar('birthdayyearto');
	}

	$anniversaryfrom=$labelcriteria->getVar('anniversaryfrom');
	if(!empty($anniversaryfrom))
	{
		$sWhereExt .= " AND family.weddingdate between " . $this->db->quoteString($labelcriteria->getVar('anniversaryfrom')) . " AND " . $this->db->quoteString($labelcriteria->getVar('anniversaryto'));
	}


	$dateenteredfrom=$labelcriteria->getVar('dateenteredfrom');
	if(!empty($dateenteredfrom))
	{
		$sWhereExt .= " AND person.dateentered between " . $this->db->quoteString($labelcriteria->getVar('dateenteredfrom')) . " AND " . $this->db->quoteString($labelcriteria->getVar('dateenterefto'));
	}
	
				
		//Determine sort selection
	if($bSortFirstName)
		$sortMe = " person.firstname ";
	else
		$sortMe=" person.lastname ";

	$familyprefix="";

//	$sSQL=" drop table `tmplabel`;

//$sSQL="truncate table tmplabel";
/*
$sSQL= "CREATE TABLE `tmplabel` (
	`person_id` int default NULL,	
	`recipient` varchar(255) default NULL,
	`AddressLine1` varchar(255) default NULL,
	`AddressLine2` varchar(255) default NULL,
	`addresslabel` text null,
	`City` varchar(255) default NULL,
	`State` varchar(255) default NULL,
	`Zip` varchar(255) default NULL,
	`sortme` varchar(255) default NULL,
	`familyid` int default null,
	`body` text )";
*/


$sSQL= "CREATE  temporary   TABLE `tmplabel` (
	`id` int default NULL,
	`lastname` varchar(255) default NULL,
	`firstname` varchar(255) default NULL,
	`recipient` varchar(255) default NULL,
	`AddressLine1` varchar(255) default NULL,
	`AddressLine2` varchar(255) default NULL,
	`addresslabel` text null,
	`City` varchar(255) default NULL,
	`State` varchar(255) default NULL,
	`Zip` varchar(255) default NULL,
	`sortme` varchar(255) default NULL,
	`classification` varchar(255) default NULL,
	`familyid` int default null,
  `homephone` varchar(30) default NULL,
  `workphone` varchar(30) default NULL,
  `cellphone` varchar(30) default NULL,
  `email` varchar(50) default NULL,
  `workemail` varchar(50) default NULL,
  `birthday` tinyint(3) unsigned NOT NULL default '0',
  `birthmonth` tinyint(3) unsigned NOT NULL default '0',
  `birthyear` year(4) default NULL,
  `membershipdate` date default NULL,
  `gender` tinyint(1) unsigned NOT NULL default '0',
	`body` text ";


$customfields=$labelcriteria->getVar('customfields');
$customfieldsql="";
$customfieldinsertsql="";

if(!empty($customfields))
{	
	//iterate thru custom fields and add the column type for each selected custom field
	foreach($customfields as $customfld)
	{
		$customfieldinsertsql.=", " . $customfld['custom_Field'];
		$customfieldsql.=", custom." . $customfld['custom_Field'];


		$sSQL.="," . $customfld['custom_Field'] . " ";
		switch($customfld['type_ID'])
		{
  			case 1:$sSQL .= "ENUM('false', 'true')";
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
  			case 7:$sSQL .= "TEXT";
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


		}
	}
}
$sSQL .=")";

//	$sSQL= "truncate table tmplabel";
	$this->db->query($sSQL);

	$address="'','','','','',''";
	$fambody="''";
	
//Build column clause
	$familyprefix="";
	$recipientplus="''";
	if($baltFamilyName)
		$famrecipient="altfamilyname";
	else
		$famrecipient="familyname";

//	echo $labelcriteria->getVar('bdiraddress');

	
	If($labelcriteria->getVar('bdiraddress'))
	{
		$headersql .= ",address1,address2,city,state,zip";
		$indivbodysql .= ",',',coalesce(person.address1,''),',',coalesce(person.address2,''),',',coalesce(person.city,''),',',coalesce(person.state,''),',',coalesce(person.zip,'')";
		$familybodysql .= ",',',coalesce(family.address1,''),',',coalesce(family.address2,''),',',coalesce(family.city,''),',',coalesce(family.state,''),',',coalesce(family.zip,'')";
	}	
	
	If($labelcriteria->getVar('bdirwedding'))
	{
		$headersql .= ",weddingdate";
		$familybodysql .= ",',',weddingdate";
		$indivbodysql  .= ",','," . "''";
	}
	
	$bdate="''";	
	if($labelcriteria->getVar('bdirbirthday'))
	{ 
		$bdate=" concat(' (', birthmonth, '/', birthday , ')')"; 
		$headersql .= ",birthdate";
		$indivbodysql .= ",','," . $bdate;
		$familybodysql .= ",',',''";
	}

	if($labelcriteria->getVar('bphone'))
	{ 
	 	$indivbodysql .= ",',',coalesce(person.homephone,'')";
	 	$familybodysql .= ",',',coalesce(family.homephone,'')";
	 	$headersql .=",homephone";

	 	$indivbodysql .= ",',',coalesce(person.workphone,'')";
	 	$familybodysql .= ",',',coalesce(family.workphone,'')";
	 	$headersql .= ",workphone";

		$indivbodysql .=",',',coalesce(person.cellphone,'')";
		$familybodysql .=",',',coalesce(family.cellphone,'')";
		$headersql .= ",cellphone";
		
	}
	
	
	if($labelcriteria->getVar('bemail'))
	{ 
		$indivbodysql .= ",',',coalesce(person.email,'')";
		$familybodysql .= ",',',coalesce(person.email,'')";
		$headersql.= ",email";
	}

	if($labelcriteria->getVar('bfamilyname'))
	{
		$headersql .= ", " . _oscmem_familyname;
		$familybodysql .= ",',',family.familyname";
		$indivbodysql .= ",',',coalesce(family.familyname,'')";
	}
	
	$customfields=$labelcriteria->getVar('customfields');
	
	if(!empty($customfields))
	{	
		foreach($customfields as $customfld)
		{
			$indivbodysql .=",',',coalesce(" . $customfld['custom_Field'] . ",'')";
			$familybodysql .=",',',coalesce(" . $customfld['custom_Field'] . ",'')";
			$headersql.="," . $customfld['custom_Name'];

			if(isset($custfld['custom_Value']))
			{
				$customwhere .= "x" . $customfld['custom_Value']; //$customfld['custom_Field'];
// . "=" $customfld['custom_Value'];
			}
		}
	}

	$customfieldcriterias=$labelcriteria->getVar('customcriteria');

	if(!empty($customfieldcriterias))
	{	
		foreach($customfieldcriterias as $cfc)
		{
			if(isset($cfc['custom_Value']))
			{
				switch($cfc["type_ID"])
				{
				case "1": //True false
					$customwhere .= "custom." . $cfc['custom_Field'] . "="; 
					if($cfc['custom_Value']==1)
					{
						$customwhere .= $this->db->quoteString("true") . " and "; //$customfld['custom_Field'];
					}
					else
					{
						$customwhere .= $this->db->quoteString("false") . " and "; 
					}
				break;

				case "2": //Date
					if($cfc["custom_Value"]!="")
					{
					$customwhere .= "custom." . $cfc['custom_Field'] . "="; 
					$customwhere .= $this->db->quoteString($cfc['custom_Value']) . " and ";
					}
					break;
						
				case "3": //Text 50
				case "4": //Text 100
				case "5": //Text 200
					$customwhere .= "custom." .$cfc['custom_Field'] . "="; 

					$customwhere .= $this->db->quoteString($cfc['custom_Value']) . " and ";
				break;
				}

			}
		}
	}

	$customwhere=substr($customwhere,0,strlen($customwhere)-4);

	if(strlen($customwhere)>0)
	{
		$customwhere= " and " . $customwhere;
	}

	$recipientplus.=",$cr";
	$fambody.=",$cr";

	$sSQL = "insert into tmplabel(id,lastname, firstname,familyid,recipient, sortMe,addressLabel, AddressLine1, AddressLine2, City, State, Zip, homephone,workphone,cellphone,email,workemail,birthday,birthmonth,birthyear,membershipdate,gender, body, classification" . $customfieldinsertsql . ") Select person.id,lastname,firstname,0,concat(lastname,', ',firstname)  ,$sortMe, concat(person.address1, person.address2, person.city, person.state, person.zip), person.address1, person.address2, person.city, person.state, person.zip, person.homephone,person.workphone,person.cellphone,person.email, person.workemail,person.birthday, person.birthmonth, person.birthyear, person.membershipdate, person.gender, concat($indivbodysql) body, list.optionname " . $customfieldsql . "  from " . $this->db->prefix("oscmembership_person") . " person left join  " . $this->db->prefix("oscmembership_person_custom") . " custom on person.id=custom.per_ID left join " . $this->db->prefix("oscmembership_family") . " family on person.famid=family.id " . $sGroupTable . " left join " . $this->db->prefix("oscmembership_list") . " list on person.clsid = list.optionid and list.id=1 where famid=0" . $sWhereExt . $customwhere;
	
	$this->db->query($sSQL); 

	$sortMe="familyname";	
	
	switch ($labelcriteria->getVar('soutputmethod'))
	{	
		case _oscmem_csv_combinefamily :
		
		$sSQL = "insert into tmplabel(familyid) Select distinct family.id from " . $this->db->prefix("oscmembership_family") . " family , " . $this->db->prefix("oscmembership_person") . " person  " . $sGroupTable . " where person.famid>0 and  family.id=person.famid " . $sWhereExt;
		$this->db->query($sSQL); 
		

		$sSQL="update tmplabel, " . $this->db->prefix("oscmembership_family") . " family set 
		sortme=$sortMe,
		recipient=family.familyname,
		body=concat($familybodysql)
		, addressLabel = concat(family.address1, family.address2, family.city, family.state, family.zip)
		,AddressLine1 = family.address1
		,AddressLine2 = family.address2
		, tmplabel.City = family.city
		, tmplabel.state = family.state
		, tmplabel.zip = family.zip
		where tmplabel.familyid=family.id";
		$this->db->query($sSQL); 
		
		break;
		
		default:
		
		$sSQL = "insert into tmplabel(id,lastname,firstname,recipient,familyid, sortMe, addresslabel, AddressLine1, AddressLine2, City, State, Zip, body) Select person.id,person.lastname, person.firstname, concat(person.lastname,', ', person.firstname),family.id,familyname, concat(family.address1, family.address2, family.city, family.state, family.zip), family.address1, family.address2, family.city, family.state, family.zip, concat($indivbodysql) body from " . $this->db->prefix("oscmembership_person") . " person left join  " . $this->db->prefix("oscmembership_person_custom") . " custom on person.id=custom.per_ID left join " . $this->db->prefix("oscmembership_family") . " family on person.famid=family.id " . $sGroupTable . " where famid!=0" . $sWhereExt . $customwhere;

		$this->db->query($sSQL); 
		break;

		//only one record
	}

	if($labelcriteria->getVar('bincompleteaddress'))
	{
		$sSQL="delete from tmplabel where length(AddressLine1)=0 or length(AddressLine2)=0 or length(city)=0 or length(state)=0 or length(zip)=0";
		$this->db->query($sSQL);	
	}
	
	$sSQL="select * from tmplabel order by sortme";

	$result=$this->db->query($sSQL);
		
	$i=0;
	$i++;

	$customfields=$labelcriteria->getVar('customfields');
	
	
	while($row = $this->db->fetchArray($result)) 
	{
		if(isset($row))
		{
			$label=&$this->create(false);

			reset($customfields);
			foreach($customfields as $customfld)
			{
				$label->initVar($customfld['custom_Field'], XOBJ_DTYPE_TXTBOX);
			}

			$label->assignVars($row);

			$labels[$i]=$label;
		}		
	
		$i++;	
	}


 	return $labels;   
    }    
    
    function &getexportfromcart($bSortFirstName, $baltFamilyName, $sGroupsList, $labelcriteria, $uid)
    {
	$labels[]=array();
    
	$i=0;

	$headersql="lastname, firstname";
	$indivbodysql="lastname,',',firstname";
	$familybodysql="familyname,',',''";
	$cr="'<br>'";
	$blank="''";		
	
	$sWhereExt=" ";
	
/*
	if (!empty($labelcriteria->getVar('sdirclassifications')))
	{
	 $sWhereExt = "  and person.clsid in (" . $labelcriteria->getVar('sdirclassifications') . ")";
	 }
*/	
	if (!empty($sGroupsList))
	{
		$sGroupTable = "join " . $this->db->prefix("oscmembership_group_members") . " g on g.person_id = person.id ";
	
		$sWhereExt .= " AND g.group_id in (" . $sGroupsList . ") ";
	
		// This is used by per-role queries to remove duplicate rows from people assigned multiple groups.
		$sGroupBy = " GROUP BY per_ID";
	}
				
		//Determine sort selection
	if($bSortFirstName)
		$sortMe = " person.firstname ";
	else
		$sortMe=" person.lastname ";

	$familyprefix="";

//	$sSQL=" drop table `tmplabel`;

//$sSQL="truncate table tmplabel";

$sSQL= "CREATE temporary TABLE `tmplabel` (
	`person_id` int default NULL,
	`recipient` varchar(255) default NULL,
	`AddressLine1` varchar(255) default NULL,
	`AddressLine2` varchar(255) default NULL,
	`addresslabel` text null,
	`City` varchar(255) default NULL,
	`State` varchar(255) default NULL,
	`Zip` varchar(255) default NULL,
	`sortme` varchar(255) default NULL,
	`familyid` int default null,
  `homephone` varchar(30) default NULL,
  `workphone` varchar(30) default NULL,
  `cellphone` varchar(30) default NULL,
  `email` varchar(50) default NULL,
  `workemail` varchar(50) default NULL,
  `birthday` tinyint(3) unsigned NOT NULL default '0',
  `birthmonth` tinyint(3) unsigned NOT NULL default '0',
  `birthyear` year(4) default NULL,
  `membershipdate` date default NULL,
  `gender` tinyint(1) unsigned NOT NULL default '0',
	`body` text ) ";


//	$sSQL= "truncate table tmplabel";
	$this->db->query($sSQL);

	$address="'','','','','',''";
	$fambody="''";
	
//Build column clause
	$familyprefix="";
	$recipientplus="''";
	if($baltFamilyName)
		$famrecipient="altfamilyname";
	else
		$famrecipient="familyname";

//	echo $labelcriteria->getVar('bdiraddress');

	
	If($labelcriteria->getVar('bdiraddress'))
	{
		$headersql .= ",address1,address2,city,state,zip";
		$indivbodysql .= ",',',coalesce(person.address1,''),',',coalesce(person.address2,''),',',coalesce(person.city,''),',',coalesce(person.state,''),',',coalesce(person.zip,'')";
		$familybodysql .= ",',',coalesce(family.address1,''),',',coalesce(family.address2,''),',',coalesce(family.city,''),',',coalesce(family.state,''),',',coalesce(family.zip,'')";
	}	
	
	If($labelcriteria->getVar('bdirwedding'))
	{
		$headersql .= ",weddingdate";
		$familybodysql .= ",',',weddingdate";
		$indivbodysql  .= ",','," . "''";
	}
	
	$bdate="''";	
	if($labelcriteria->getVar('bdirbirthday'))
	{ 
		$bdate=" concat(' (', birthmonth, '/', birthday , ')')"; 
		$headersql .= ",birthdate";
		$indivbodysql .= ",','," . $bdate;
		$familybodysql .= ",',',''";
	}

	if($labelcriteria->getVar('bphone'))
	{ 
	 	$indivbodysql .= ",',',coalesce(person.homephone,'')";
	 	$familybodysql .= ",',',coalesce(family.homephone,'')";
	 	$headersql .=",homephone";

	 	$indivbodysql .= ",',',coalesce(person.workphone,'')";
	 	$familybodysql .= ",',',coalesce(family.workphone,'')";
	 	$headersql .= ",workphone";

		$indivbodysql .=",',',coalesce(person.cellphone,'')";
		$familybodysql .=",',',coalesce(family.cellphone,'')";
		$headersql .= ",cellphone";
		
	}
	
	
	if($labelcriteria->getVar('bemail'))
	{ 
		$indivbodysql .= ",',',coalesce(person.email,'')";
		$familybodysql .= ",',',coalesce(person.email,'')";
		$headersql.= ",email";
	}

	if($labelcriteria->getVar('bfamilyname'))
	{
		$headersql .= ", " . _oscmem_familyname;
		$familybodysql .= ",',',family.familyname";
		$indivbodysql .= ",',',coalesce(family.familyname,'')";
	}
	
	$customfields=$labelcriteria->getVar('customfields');
	
	if(!empty($customfields))
	{	
		foreach($customfields as $customfld)
		{
			$indivbodysql .=",',',coalesce(" . $customfld['custom_Field'] . ",'')";
			$familybodysql .=",',',coalesce(" . $customfld['custom_Field'] . ",'')";
			$headersql.="," . $customfld['custom_Name'];
		}
	}
	
	$recipientplus.=",$cr";
	$fambody.=",$cr";


	$sSQL = "insert into tmplabel(person_id,familyid,recipient, sortMe,addressLabel, AddressLine1, AddressLine2, City, State, Zip, body) Select person.id, 0,$sortMe,$sortMe, concat(person.address1, person.address2, person.city, person.state, person.zip), person.address1, person.address2, person.city, person.state, person.zip, concat($indivbodysql) body from " . $this->db->prefix("oscmembership_cart") . " c join " . $this->db->prefix("oscmembership_person") . " person on c.person_id=person.id and c.xoops_uid= " . $uid . " left join  " . $this->db->prefix("oscmembership_person_custom") . " custom on person.id=custom.per_ID left join " . $this->db->prefix("oscmembership_family") . " family on person.famid=family.id where famid=0" . $sWhereExt;
	
	$this->db->query($sSQL); 

	$sortMe="familyname";	
	
	switch ($labelcriteria->getVar('soutputmethod'))
	{	
		case _oscmem_csv_combinefamily :
		
		$sSQL = "insert into tmplabel(familyid) Select distinct family.id from " . $this->db->prefix("oscmembership_family") . " family join " . $this->db->prefix("oscmembership_person") . " person  on family_id=person.famid join " . $this->db->prefix("oscmembership_cart") . " c on person.id=c.person_id and c.xoops_uid=" . $uid . " where person.famid>0 and  family.id=person.famid " . $sWhereExt;
		$this->db->query($sSQL); 
		
		$sSQL="update tmplabel, " . $this->db->prefix("oscmembership_family") . " family set 
		sortme=$sortMe,
		body=concat($familybodysql)
		, addressLabel = concat(family.addressline1, family.addressline2, family.city, family.state, family.zip)
		,AddressLine1 = family.addressline1
		,AddressLine2 = family.addressline2
		, city = family.city
		, state = family.state
		, zip = family.zip
		where tmplabel.familyid=family.id";
		$this->db->query($sSQL); 
		
		break;
		
		default:
		
		$sSQL = "insert into tmplabel(person_id,familyid, sortMe, addresslabel, AddressLine1, AddressLine2, City, State, Zip, body) Select person.id, 0,$sortMe, concat(person.address1, person.address2, person.city, person.state, person.zip), person.address1, person.address2, person.city, person.state, person.zip, concat($indivbodysql) body from " . $this->db->prefix("oscmembership_person") . " person left join  " . $this->db->prefix("oscmembership_person_custom") . " custom on person.id=custom.per_ID left join " . $this->db->prefix("oscmembership_family") . " family on person.famid=family.id join " . $this->db->prefix("oscmembership_cart") . " c on person.id = c.person_id and c.xoops_uid=" . $uid . " where famid!=0" . $sWhereExt;
		$this->db->query($sSQL); 
		break;

/*		$persondetail_handler = &xoops_getmodulehandler('person', 'oscmembership');    
		$person = $persondetail_handler->create(true);  
*/		
		//only one record
	}

	if($labelcriteria->getVar('bincompleteaddress'))
	{
		$sSQL="delete from tmplabel where length(AddressLine1)=0 or length(city)=0 or length(state)=0 or length(zip)=0";
		$this->db->query($sSQL);	
	}
	
	$sSQL="select * from tmplabel order by sortme";

	$result=$this->db->query($sSQL);
		
	$i=0;
	$label=new Label();
	$labels[$i]['body']=$headersql;
	$i++;
	
	while($row = $this->db->fetchArray($result)) 
	{
		if(isset($row))
		{
			$label->assignVars($row);
			$labels[$i]['recipient']=$label->getVar('recipient');
			//echo $labelcriteria->getVar('bdiraddress');
			
			If($labelcriteria->getVar('bdiraddress'))
			{
				$labels[$i]['addresslabel']=$label->getVar('AddressLine1');
							//echo $label->getVar('AddressLine1');

				if($label->getVar('AddressLine2')<>'')
				$labels[$i]['addresslabel'].= "\n" . $label->getVar('AddressLine2');
				$labels[$i]['addresslabel'].= "\n" . $label->getVar('City') . ", " . $label->getVar('State') . "  " . $label->getVar('Zip');
			}
			$labels[$i]['address1']=$label->getVar('AddressLine1');
			$labels[$i]['address2']=$label->getVar('AddressLine2');
			$labels[$i]['city']=$label->getVar('City');
			$labels[$i]['state']=$label->getVar('State');
			$labels[$i]['zip']=$label->getVar('Zip');
			$labels[$i]['country']=$label->getVar('country');
			$labels[$i]['sortme']=$label->getVar('sortme');
			$labels[$i]['body']=$label->getVar('body');
			$labels[$i]['person_id']=$label->getVar('person_id');
		}		
	
		//echo $labels[$i]['addresslabel'];	
		$i++;	
	}



 	return $labels;   
    }    

    	
}


?>