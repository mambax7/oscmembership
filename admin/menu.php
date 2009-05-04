<?php
$admini=0;


$adminmenu[$admini]['title'] = _oscmem_admin_osclist_familyroles;
$adminmenu[$admini]['link'] = "admin/osclistselect_smarty.php?id=2";
$adminmenu[$admini]['icon'] = "admin/images/1Family-Roles.png";
$adminmenu[$admini]['small'] = "admin/images/1Family-Roles.png";

$admini++;
$adminmenu[$admini]['title'] = _oscmem_admin_osclist_memberclassification;
$adminmenu[$admini]['link'] = "admin/osclistselect_smarty.php?id=1";
$adminmenu[$admini]['icon'] = "admin/images/2Member-Classifications.png";
$adminmenu[$admini]['small'] = "admin/images/2Member-Classifications.png";

$admini++;
$adminmenu[$admini]['title'] = _oscmem_admin_customfield;
$adminmenu[$admini]['link'] = "admin/customfieldselectform.php";
$adminmenu[$admini]['icon'] = "admin/images/3Custom-Fields-2.png";
$adminmenu[$admini]['small'] = "admin/images/3Custom-Fields-2.png";

$admini++;
$adminmenu[$admini]['title'] = _oscmem_admin_osclist_grouptype;
$adminmenu[$admini]['link'] = "admin/osclistselect_smarty.php?id=3";
$adminmenu[$admini]['icon'] = "admin/images/4Group-Types.png";
$adminmenu[$admini]['small'] = "admin/images/4Group-Types.png";

$admini++;
$adminmenu[$admini]['title'] = _oscmem_admin_churchdetail;
$adminmenu[$admini]['link'] = "admin/churchdetailform.php";
$adminmenu[$admini]['icon'] = "admin/images/5Church-Details.png";
$adminmenu[$admini]['small'] = "admin/images/5Church-Details.png";

$admini++;
$adminmenu[$admini]['title'] = _oscmem_admin_profiletomembermap;
$adminmenu[$admini]['link'] = "admin/oscMapUserstoMembership.php";
$adminmenu[$admini]['icon'] = "admin/images/5Church-Details.png";
$adminmenu[$admini]['small'] = "admin/images/5Church-Details.png";

$admini++;
$adminmenu[$admini]['title'] = _oscmem_admin_osclist_permissions;
$adminmenu[$admini]['link'] = "admin/perm.php?id=4";
$adminmenu[$admini]['icon'] = "admin/images/6Permissions.png";
$adminmenu[$admini]['small'] = "admin/images/6Permissions.png";

?>