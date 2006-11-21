<?php
// $Id: labelcritera.php, 2006/09/13
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


class  Labelcriteria extends XoopsObject 
{
//    var $db;
//    var $table;

	var $cr;

    function Labelcriteria()
    {
    	$this->cr="<br>";

	$this->initVar('id',XOBJ_DTYPE_INT);
        $this->initVar('bdiraddress', XOBJ_DTYPE_INT);
        $this->initVar('bdirwedding', XOBJ_DTYPE_INT);
        $this->initVar('bdirbirthday', XOBJ_DTYPE_INT);
        $this->initVar('bphone', XOBJ_DTYPE_INT);
        $this->initVar('bemail', XOBJ_DTYPE_INT);
        $this->initVar('sdirclassifications', XOBJ_DTYPE_TXTBOX);
        $this->initVar('sdirroleheads', XOBJ_DTYPE_TXTBOX);
        $this->initVar('benvelope', XOBJ_DTYPE_INT);
        $this->initVar('brole', XOBJ_DTYPE_INT);
        $this->initVar('bfamilyname', XOBJ_DTYPE_INT);
	$this->initVar('soutputmethod',XOBJ_DTYPE_TXTBOX);
		
	$this->initVar('customfields',XOBJ_DTYPE_ARRAY);
	$this->initVar('gender',XOBJ_DTYPE_TXTBOX);
	$this->initVar('membershipdatefrom',XOBJ_DTYPE_TXTBOX);
	$this->initVar('membershipdateto',XOBJ_DTYPE_TXTBOX);
	$this->initVar('birthdaymonthfrom',XOBJ_DTYPE_TXTBOX);
	$this->initVar('birthdayyearfrom',XOBJ_DTYPE_TXTBOX);
	$this->initVar('birthdaymonthto',XOBJ_DTYPE_TXTBOX);
	$this->initVar('birthdayyearto',XOBJ_DTYPE_TXTBOX);
	$this->initVar('anniversaryfrom',XOBJ_DTYPE_TXTBOX);
	$this->initVar('anniversaryto',XOBJ_DTYPE_TXTBOX);
	$this->initVar('dateenteredfrom',XOBJ_DTYPE_TXTBOX);
	$this->initVar('dateenteredto',XOBJ_DTYPE_TXTBOX);
	$this->initVar('bincompleteaddress',XOBJ_DTYPE_INT);

    }

}    
    

class oscMembershipLabelcriteriaHandler extends XoopsObjectHandler
{
    
    function &create($isNew = true)
    {
    
        $labelcriteria = new Labelcriteria ();
        if ($isNew) {
            $labelcriteria->setNew();
        }
        return $labelcriteria;
    }
    
	
}


?>