<?php
// Module 
//
define("_oscmem_MOD_NAME","OSC Membership Module");
define("_oscmem_MOD_DESC","Xoops OSC Module for Membership.");
define("_oscmem_ADMENU0","OSC 会员管理");
define("_oscmem_personselect","个人选项");
//Field names
//define("_oscdir_userfile","Church 照片");

define("_oscmem_name","会员姓名");
define("_oscmem_lastname", "姓氏");
define("_oscmem_firstname", "名字");
define("_oscmem_phone","电话号码");
define("_oscmem_address","地址");
define("_oscmem_city","城市");
define("_oscmem_state","州(省)");
define("_oscmem_post","邮政编码");
define("_oscmem_email","电子邮件");
define("_oscmem_country","国家");
define("_oscmem_title","会员列表 ");
define("_oscmem_birthday","生日");
define("_oscmem_month","月");
define("_oscmem_year","年");
define("_oscmem_birthfrom","From");
define("_oscmem_birthto","To");
define("_oscmem_gender","性别");
define("_oscmem_membershipdate","加入时间 ");
define("_oscmem_memberclass","会员类别");
define("_oscmem_submit","提交");
define("_osc_save","保存");
define("_osc_select","选择");
define("_osc_create","创建");
define("_oscmem_actions","Actions");
define("_osc_addmember","加入家庭成员");
define("_oscmem_addmember","添加会员");
define("_oscmem_addingmember","Redirecting to Add Member");

define("_oscmem_homephone","家庭电话");
define("_oscmem_workphone","办公电话");
define("_oscmem_cellphone","手机");

define("_oscmem_groupname", "小组名");
define("_oscmem_groupdescription","小组简介");
define("_oscmem_grouptype","小组类型");

define("_oscmem_workemail","办公电子邮件");

define("_oscmem_male","男性");
define("_oscmem_female","女性");

define("_oscmem_datelastedited","最后修改时间");
define("_oscmem_editedby","修改者");
define("_oscmem_dateentered","输入日期");
define("_oscmem_enteredby","输入者");
define("_oscmem_birthdayinstructions","&nbsp;&nbsp;生日格式 (月/日/年)");
define("_oscmem_weddingdate","结婚日期");

define("_oscmem_familyname","家庭名称");

define("_oscmem_persondetail_TITLE","个人资料");
define("_oscmem_familydetail_TITLE","家庭详细信息");
define("_oscmem_person_list","个人列表");
define("_oscmem_family_list","家庭列表");
define("_oscmem_familymember","家庭成员");
define("_oscmem_groupselect_TITLE","小组列表");
define("_oscmem_groupdetail_TITLE","小组信息");
//Messages
define('_oscmem_UPDATESUCCESS','The OSC Membership database has been updated successfully');
define('_oscmem_CREATESUCCESS_individual','The Individual has been created in the OSC database.');

define("_oscmem_CREATESUCCESS_family","The Family has been created in the OSC database.");
define("_oscmem_addfamily_redirect","Redirecting to Add Family");

define("_oscmem_UPDATESUCCESS_member","Member(s) added to family.");
define("_oscmem_REMOVEMEMBERSUCCESS","Family Member Removed.");
define("_oscmem_nomembers","没有成员");
define("_oscmem_nogroups","没有小组, 要创建小组请点击<a href=>这里</a>");

define("_oscmem_persondetail","成员");

//Groups
define("_oscmem_groupmember","小组成员");
define("_osc_addgroupmember","添加小组成员");
define("_oscmem_UPDATESUCCESS_member_grooup","已经成功添加成员到小组");
define("_oscmem_REMOVEGROUPMEMBERSUCCESS","成功删除小组成员.");

//Menu
define("_oscmembership_viewperson","成员");
define("_oscmembership_addperson", "添加成员");
define("_oscmembership_addfamily","添加家庭");
define("_oscmembership_viewfamily","家庭");
define("_oscmem_remove_member","删除成员");
define("_oscmem_edit_member","编辑成员");
define("_oscmem_edit","编辑");
define("_oscmembership_viewgroup","小组");
define("_oscmem_addgroup","添加小组");
define("_oscmem_addgroup_redirect","重定向至添加小组 ");


define("_oscmem_familyrole","家中角色");

define("_oscmem_unassigned","未指派");

define("_oscmem_optionname","选项名称");
define("_oscmem_optionsequence","次序");
define("_oscmem_osclist_famrole_TITLE","家中角色");

//define("_oscmem_osclist_TITLE_familyroles","家中角色");
//define("_oscmem_osclist_TITLE_memberclassifications","成员类别");
//define("_oscmem_osclist_TITLE_grouptypes","小组类别");


define("_oscmem_noclass","没有分类");


define("_oscmem_filter","筛选");
define("_oscmem_applyfilter","应用筛选");
define("_oscmem_clearfilter","清除筛选");
define("_oscmem_addtocart","Add to Cart");
define("_oscmem_remove","删除");
define("_oscmem_emptycart","Empty Cart");
define("_oscmem_emptycarttogroup","Empty Cart to Group");
define("_oscmem_emptycarttofamily","Empty Cart to Family");
define("_oscmem_generatelabels","Generate Labels");
define("_oscmem_addedtocart","Members have been added to the cart.");
define("_oscmem_intersectcart","Intersect Cart");
define("_oscmem_removefromcart","Remove from Cart");

define("_oscmem_msg_removedfromcart","Selected Individuals have been successfully removed from the cart");
define("_oscmem_msg_intersectedcart","Selected Individuals have been successfully intersected with the cart");

define("_oscmem_view_cart","View Cart");
define("_oscmem_cartcontents","Cart Contents");
define("_oscmem_memberview","Membership View");
define("_oscmem_yes","Yes");
define("_oscmem_no","No");

define("_oscmem_reporttitle","OSC Membership Reports");
define("_oscmem_directoryreport","Membership Directory");
define("_oscmem_reports", "Reports");
define("_oscmem_nav_reports","Reports/Data");
define("_oscmem_csvimport","CSV Import");

define("_oscmem_dirreport_selectclass","Select classifications to include:");
define("_oscmem_usectl","Use Ctrl Key to select multiple");
define("_oscmem_dirreport_groupmemb","Group Membership:");
define("_oscmem_dirreport_headhouse","Which role is the head of household?");
define("_oscmem_dirreport_spouserole","Which role is the spouse?");
define("_oscmem_dirreport_childrole","Which role is a child?");
define("_oscmem_dirreport_infoinclude","包含的信息:");

define("_oscmem_address_label","地址:");
define("_oscmem_familyhomephone","该家庭的家中电话");
define("_oscmem_familyworkphone","该家庭的工作电话");
define("_oscmem_familycellphone","该家庭的移动电话");
define("_oscmem_familyemail","该家庭的电子邮件");
define("_oscmem_personalphone","个人电话");
define("_oscmem_personalworkphone","个人工作电话");
define("_oscmem_personalcell","个人移动电话");
define("_oscmem_personalemail","个人电子");
define("_oscmem_personalworkemail","个人工作电子邮件");

define("_oscmem_informationinclude","要包含的信息:");
define("_oscmem_diroptions","目录选项");
define("_oscmem_altfamilyname","Dual Listing - Alternate Family Name");
define("_oscmem_althead","Use only Family Name for listing");
define("_oscmem_dirsort","Directory Sort");
define("_oscmem_orderbyfirstname","Order Directory by First Name");
define("_oscmem_directorytitle","Church Directory Options");

define("_oscmem_titlepagesettings","Directory Title Page Settings");

define("_oscmem_usetitlepageyn","Use Title Page");
define("_oscmem_churchname_label","Church Name");
define("_oscmem_disclaimer","Disclaimer");

define("_oscmem_directory","目录");
define("_oscmem_page","页");

define("_oscmem_incorrectdt_membershipdate","Incorrect Membership Date Format");
define("_oscmem_incorrectdt_weddingdate","结婚日期格式错误");

define("_oscmem_CREATESUCCESS_group","小组创建成功");
define("_oscmem_csvexport","CSV Export");

define("_oscmem_cvsexport_infoinclude","Fields to include in Export");


define("_oscmem_season_select","选择季节");
define("_oscmem_season_spring","春季");
define("_oscmem_season_summer","夏季");
define("_oscmem_season_fall","秋季");
define("_oscmem_season_winter","冬季");

define("_oscmem_filters","Filters");
define("_oscmem_recordstoexport","Records to Export");
define("_oscmem_classificationstoexport","Classifications to Export");

define("_oscmem_fromfilterbelow","Based on Filters Below...");
define("_oscmem_fromcart","People in Cart (filters ignored)");

define("_oscmem_cvsexport_customfields","Custom Fields for Export");
define("_oscmem_rolestoexport","Family Roles to Export");

define("_oscmem_gender_nofilter","Do not filter on Gender");

define("_oscmem_filter_from","从");
define("_oscmem_filter_to","至");

define("_oscmem_otheremail","其它电子邮件");
define("_oscmem_envelopenumber","Envelope Number");
define("_oscmem_csv_birthanniversary","Birth/Anniversary");
define("_oscmem_csv_ageyearsmarried","结婚日期");
define("_oscmem_csv_familyrole","家中角色");
define("_oscmem_csv_familyname","家庭姓名");
define("_oscmem_csv_ministry","Ministry");
define("_oscmem_anniversary","Anniversary");

define("_oscmem_csv_individual","CSV Individual Records");
define("_oscmem_csv_combinefamily","CSV Combine Families");
define("_oscmem_csv_addtocart","Add Individuals to Cart");
define("_oscmem_csv_outputmethod","Output Method");

define("_oscmem_csv_skipincompleteaddress","Skip Incomplete Mail Addresses");
define("_oscmem_csv_skipnoenvelope","Skip Records with No Envelope Number<br>(Individual Records Only)");

define("_oscmem_admin_osclist_familyroles","修改家庭角色");
define("_oscmem_admin_osclist_memberclassification","修改 成员 类别");
define("_oscmem_admin_osclist_grouptype","修改小组类别");
define("_oscmem_admin_osclist_permissions","修改权限");
define("_oscmem_admin_customfield","Modify Custom Fields");

define("_oscmem_view","视图");

define("_oscmem_defaultcountry_value",22);
define("_oscmem_defaultcountry_text"," &nbsp;&nbsp;(Default Country is USA)");

define("_oscmem_accessdenied","Access Denied");

?>
