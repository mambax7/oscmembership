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