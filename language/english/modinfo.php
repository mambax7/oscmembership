<?php
/**
 * Module danguage constants
 * @version $Id$
 */
// Module
//
define("_oscmem_MOD_NAME","OSC Membership Module");
define("_oscmem_MOD_DESC","Xoops OSC Module for Membership.");
define("_oscmem_ADMENU0","OSC Membership Administation");
define("_oscmem_personselect","Person Select");
//Field names
//define("_oscdir_userfile","Church Picture");

define("_oscmem_name","Member Name");
define("_oscmem_lastname", "Last Name");
define("_oscmem_firstname", "First Name");
define("_oscmem_phone","Phone Number");
define("_oscmem_address","Address");
define("_oscmem_city","City");
define("_oscmem_state","State");
define("_oscmem_post","Zip");
define("_oscmem_email","Email");
define("_oscmem_country","Country");
define("_oscmem_title","Member Directory");
define("_oscmem_birthday","Birthday");
define("_oscmem_month","Month");
define("_oscmem_year","Year");
define("_oscmem_birthfrom","From");
define("_oscmem_birthto","To");
define("_oscmem_gender","Gender");
define("_oscmem_membershipdate","Membership Date");
define("_oscmem_memberclass","Classification");
define("_oscmem_submit","Submit");
define("_osc_save","Save");
define("_osc_select","Select");
define("_osc_create","Create");
define("_oscmem_actions","Actions");
define("_osc_addmember","Add Family Member");
define("_oscmem_addmember","Add Member");
define("_oscmem_addingmember","Redirecting to Add Member");

define("_oscmem_homephone","Home Phone");
define("_oscmem_workphone","Work Phone");
define("_oscmem_cellphone","Cell Phone");

define("_oscmem_groupname", "Group Name");
define("_oscmem_groupdescription","Group Description");
define("_oscmem_grouptype","Group Type");

define("_oscmem_workemail","Work Email");

define("_oscmem_male","Male");
define("_oscmem_female","Female");

define("_oscmem_datelastedited","Date Last Edited");
define("_oscmem_editedby","Edited By");
define("_oscmem_dateentered","Date Entered");
define("_oscmem_enteredby","Entered By");
define("_oscmem_birthdayinstructions","&nbsp;&nbsp;Birthday Format (MM/DD/YYYY)");
define("_oscmem_weddingdate","Wedding Date");

define("_oscmem_familyname","Family Name");
define("_oscmem_altfamilyname","Alternate Family Name");
define("_oscmem_altfamilynamedupe","Create duplicate entry using Alternate Family Name");
define("_oscmem_persondetail_TITLE","Person Detail Form");
define("_oscmem_familydetail_TITLE","Family Detail Form");
define("_oscmem_person_list","Person List");
define("_oscmem_family_list","Family List");
define("_oscmem_familymember","Family Members");
define("_oscmem_groupselect_TITLE","Group List");
define("_oscmem_groupdetail_TITLE","Group Detail");
//Messages
define('_oscmem_UPDATESUCCESS','The OSC Membership database has been updated successfully');
define('_oscmem_CREATESUCCESS_individual','The Individual has been created in the OSC database.');

define("_oscmem_CREATESUCCESS_family","The Family has been created in the OSC database.");
define("_oscmem_addfamily_redirect","Redirecting to Add Family");

define("_oscmem_UPDATESUCCESS_member","Member(s) added to family.");
define("_oscmem_REMOVEMEMBERSUCCESS","Family Member Removed.");
define("_oscmem_nomembers","No Members");
define("_oscmem_nogroups","No Groups.  To create a group click <a href=>here</a>");

define("_oscmem_persondetail","Members");

//Groups
define("_oscmem_groupmember","Group Membership");
define("_osc_addgroupmember","Add Group Members");
define("_oscmem_UPDATESUCCESS_member_grooup","Member added to Group Successfully");
define("_oscmem_REMOVEGROUPMEMBERSUCCESS","Group Member Successfully removed.");

//Menu
define("_oscmembership_viewperson","Members");
define("_oscmembership_addperson", "Add Member");
define("_oscmembership_addfamily","Add Family");
define("_oscmembership_viewfamily","Family");
define("_oscmem_remove_member","Remove Member");
define("_oscmem_edit_member","Edit Member");
define("_oscmem_edit","Edit");
define("_oscmembership_viewgroup","Groups");
define("_oscmem_addgroup","Add Group");
define("_oscmem_addgroup_redirect","Redirecting to Add Group");


define("_oscmem_familyrole","Family Role");

define("_oscmem_unassigned","Unassigned");

define("_oscmem_optionname","Option Name");
define("_oscmem_optionsequence","Sequence");
define("_oscmem_osclist_famrole_TITLE","Family Roles");

//define("_oscmem_osclist_TITLE_familyroles","Family Roles");
//define("_oscmem_osclist_TITLE_memberclassifications","Member Classifications");
//define("_oscmem_osclist_TITLE_grouptypes","Group Types");


define("_oscmem_noclass","No Classification");


define("_oscmem_filter","Filter");
define("_oscmem_applyfilter","Apply Filter");
define("_oscmem_clearfilter","Clear Filter");
define("_oscmem_addtocart","Add to Cart");
define("_oscmem_remove","Remove");
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
define("_oscmem_csvimport_individual","CSV Import Individuals");
define("_oscmem_vcardimport_individual","VCard Import Individuals");

define("_oscmem_dirreport_selectclass","Select classifications to include:");
define("_oscmem_usectl","Use Ctrl Key to select multiple");
define("_oscmem_dirreport_groupmemb","Group Membership:");
define("_oscmem_dirreport_headhouse","Which role is the head of household?");
define("_oscmem_dirreport_spouserole","Which role is the spouse?");
define("_oscmem_dirreport_childrole","Which role is a child?");
define("_oscmem_dirreport_infoinclude","Information to Include:");

define("_oscmem_address_label","Address:");
define("_oscmem_familyhomephone","Family Home Phone");
define("_oscmem_familyworkphone","Family Work Phone");
define("_oscmem_familycellphone","Family Cell Phone");
define("_oscmem_familyemail","Family Email");
define("_oscmem_personalphone","Individual Phone");
define("_oscmem_personalworkphone","Individual Work Phone");
define("_oscmem_personalcell","Individual Cell Phone");
define("_oscmem_personalemail","Individual Email");
define("_oscmem_personalworkemail","Individual Work Email");

define("_oscmem_informationinclude","Information to Include:");
define("_oscmem_diroptions","Directory Options");
define("_oscmem_altfamilyname_dual","Dual Listing - Alternate Family Name");
define("_oscmem_althead","Use only Family Name for listing");
define("_oscmem_dirsort","Directory Sort");
define("_oscmem_orderbyfirstname","Order Directory by First Name");
define("_oscmem_directorytitle","Church Directory Options");

define("_oscmem_titlepagesettings","Directory Title Page Settings");

define("_oscmem_usetitlepageyn","Use Title Page");
define("_oscmem_churchname_label","Church Name");
define("_oscmem_disclaimer","Disclaimer");

define("_oscmem_directory","Directory");
define("_oscmem_page","Page");

define("_oscmem_incorrectdt_membershipdate","Incorrect Membership Date Format");
define("_oscmem_incorrectdt_weddingdate","Incorrect Wedding Date Format");

define("_oscmem_CREATESUCCESS_group","Group Successfully Created");
define("_oscmem_csvexport_individual","CSV Export Individuals");
define("_oscmem_cvsexport_infoinclude","Fields to include in Export");


define("_oscmem_season_select","Select a season");
define("_oscmem_season_spring","Spring");
define("_oscmem_season_summer","Summer");
define("_oscmem_season_fall","Fall");
define("_oscmem_season_winter","Winter");

define("_oscmem_filters","Filters");
define("_oscmem_recordstoexport","Records to Export");
define("_oscmem_classificationstoexport","Classifications to Export");

define("_oscmem_fromfilterbelow","Based on Filters Below...");
define("_oscmem_fromcart","People in Cart (filters ignored)");

define("_oscmem_cvsexport_customfields","Custom Fields To Include In Export");
define("_oscmem_rolestoexport","Family Roles to Export");

define("_oscmem_gender_nofilter","Do not filter on Gender");

define("_oscmem_filter_from","From");
define("_oscmem_filter_to","To");

define("_oscmem_otheremail","Other Email");
define("_oscmem_envelopenumber","Envelope Number");
define("_oscmem_csv_birthanniversary","Birth/Anniversary");
define("_oscmem_csv_ageyearsmarried","Age and Years Married");
define("_oscmem_csv_familyrole","Family Role");
define("_oscmem_csv_familyname","Family Name");
define("_oscmem_csv_ministry","Ministry");
define("_oscmem_anniversary","Anniversary");

define("_oscmem_csv_individual","CSV Individual Records");
define("_oscmem_csv_combinefamily","CSV Combine Families");
define("_oscmem_csv_addtocart","Add Individuals to Cart");
define("_oscmem_csv_outputmethod","Output Method");

define("_oscmem_csv_skipincompleteaddress","Skip Incomplete Mail Addresses");
define("_oscmem_csv_skipnoenvelope","Skip Records with No Envelope Number<br>(Individual Records Only)");

define("_oscmem_admin_osclist_familyroles","Modify Family Roles");
define("_oscmem_admin_osclist_memberclassification","Modify Member Classifications");
define("_oscmem_admin_osclist_grouptype","Modify Group Types");
define("_oscmem_admin_osclist_permissions","Modify Permissions");
define("_oscmem_admin_customfield","Modify Custom Fields");
define("_oscmem_admin_churchdetail","Modify Church Detail");
define("_oscmem_view","View");

define("_oscmem_defaultcountry_value",22);
define("_oscmem_defaultcountry_text"," &nbsp;&nbsp;(Default Country is USA)");

define("_oscmem_accessdenied","Access Denied");
define("_oscmem_fax","Fax");
define("_oscmembership_directorydisclaimer","Directory Disclaimer");
define("_oscmembership_csvuploaderror","ERROR: the uploaded CSV file no longer exists!");
define("_oscmem_invaliddate","Invalid Date");
define("_oscmem_membershipcard","Membership Cards (PDF) - Generated from Cart Contents - Uses Business Card Layout - Avery #8371");
define("_oscmem_childcard","Child Check-In ID Cards (PDF) - Generated from Cart Contents - Uses Business Card Layout - Avery #8371");

define("_oscmem_website","Website");
define("_oscmem_child","Child");
define("_oscmem_parent","Parent");
define("_oscmem_addtocart_redirect","Group added to cart");
define("_oscmem_groupview","Group View");
define("_oscmem_generateemails","Generate Emails");
define("_oscmem_cartgenerateemail_TITLE","Generate Email");
define("_oscmem_emailsubject","Subject");
define("_oscmem_emailbody_label","Body of Email");
define("_oscmem_emailto_label","Recipients");
define("_oscmem_family_select","Select Families");
define("_oscmembership_addcarttofamily","Add Cart to Selected Family");
define("_oscmem_confirmdelete","Please confirm you want these members deleted.  The delete will be permanent.");
define("_oscmem_deletemember","Delete Member");
define("_oscmem_deletefamily","Delete Family");
define("_oscmem_deleted","Members have been deleted");

define("_oscmem_picloc","Picture Location");
define("_oscmem_familypicture","Family Picture");
define("_oscmem_personpicture","Person Picture");

define("_oscmem_filetoupload","File to Upload");
define("_oscmem_cvsimport_family_step1","Import Family - Step 1&nbsp;&nbsp;<font color=lightgray>Step 2</font>&nbsp;&nbsp;<font color=lightgray>Step 3</font>");

define("_oscmem_cvsimport_family_step2","Import Family - <font color=lightgray>Step 1</font>&nbsp;&nbsp;Step 2&nbsp;&nbsp;<font color=lightgray>Step3</font>");

define("_oscmem_cvsimport_family_step3","Import Family - <font color=lightgray>Step 1</font>&nbsp;&nbsp;<font color=lightgray>Step 2</font>&nbsp;&nbsp;Step 3");

define("_oscmem_importfile","Import File");

define("_oscmem_uploadfile","Upload File");

define("_oscmem_column","Column");

define("_oscmem_importfile_individual","Import Individuals File");
define("_oscmem_ignore","Ignore");
define("_oscmem_ignorefirstrow","Ignore First Row");
define("_oscmem_csvimport_family","CSV Import Family");
define("_oscmem_success","Success");
define("_oscmem_recordsimported","Records Imported");

define("_oscmem_csvdelimiter","CSV Delimiter");
define("_oscmem_tab","Tab");
define("_oscmem_comma","Comma");
define("_oscmem_import_family","Family Import");

define("_oscmem_menu_orphanmatchup","Orphan Matchup -- Match Individuals to Families");

define("_oscmem_orphanmatchup","Orphan Matchup");
define("_oscmem_matchuporphans","Matchup Selected Orphans");
define("_oscmem_orphanselect","Orphan Select");
define("_oscmem_matchedfamilyname","Matched<br>Family Name");
define("_oscmem_orphansmatched","The orphan(s) have been matched.");
define("_oscmem_noorphans","No orphans found.");
define("_oscmem_csvexport_title","Member Export");

define("_oscmem_altindividualonly","Do not Print Families, Only Individuals");

define("_oscmem_dirincludepictures","Include Pictures in Directory");

define("_oscmem_vcardimport_step1","Import VCard &nbsp;&nbsp;Step 1&nbsp;-&nbsp;<font color=lightgray>Step 2</font>");
define("_oscmem_vcardimport_step2","Import VCard &nbsp;<font color=lightgray>Step 1</font>&nbsp;-&nbsp;Step2");

define("_oscmem_csv_exporttovcard","Export to VCard");
define("_oscmem_createvcard","Create VCard");

define("_oscmem_label_rowstodisplay","Rows to Display");
define("_OSCMEM_USERFIELDSDONOTMAP","User fields that do not map");
define("_OSCMEM_USERFIELDSDONOTMAP_DESC","User fields that will not map in the profile to membership map process");
define("_OSCMEM_MEMBERNOMAP","Member fields that do not map");
define("_OSCMEM_MEMBERNOMAP_DESC","Member fields that will not map in the profile to membership map process");

/** Constants changed or added in 6.2 revision - */
define('_OSCMEM_TEMPLATE_SIMPLE_DESC','Simple');
define('_OSCMEM_TEMPLATE_CARTVIEW_DESC','Cart View Template');
define('_OSCMEM_TEMPLATE_MEMBERVIEW_DESC','Member View Template');
define('_OSCMEM_TEMPLATE_REPORTS_DESC','Report Page');
define('_OSCMEM_TEMPLATE_RPT_DIR_DESC','Report Directory Options');
define('_OSCMEM_TEMPLATE_CSV_DESC','CSV Export Options');
define('_OSCMEM_TEMPLATE_SELECT_DESC','standard select template');
define('_OSCMEM_TEMPLATE_FAMILY_DESC','family view template');
define('_OSCMEM_TEMPLATE_GROUP_DESC','group view template');
define('_OSCMEM_TEMPLATE_CARTEMAIL_DESC','cart generate email template');
define('_OSCMEM_TEMPLATE_FAMILYSEL_DESC','family select template');
define('_OSCMEM_TEMPLATE_ORPHAN_DESC','orphan select template');
define('_OSCMEM_BLOCK_MEM_NAME','OSC Navigation');
define('_OSCMEM_BLOCK_MEM_DESC','OSC Membership Menu');
define('_OSCMEM_BLOCK_ALPHANAV_NAME','Member Alpha Navigation');
define('_OSCMEM_BLOCK_ALPHANAV_DESC','Alpha Navigation of Membership');
define('_OSCMEM_BLOCK_BIRTHDAYS_NAME','Member BirthDays');
define('_OSCMEM_BLOCK_BIRTHDAYS_DESC','Block Displaying Birthdays for the Current Month');
define('_oscmem_menu_googlesync','Sync Membership Info With Google Contacts');
define('_oscmem_googleaccount','Google Account');
define('_oscmem_password','Google Password');
define('_oscmem_gsync_TITLE','Google OSC Sync Page');
define('_oscmem_instructions','Instructions');
define('_oscmem_gsync_instructions_content','Submitting this form will take the contents of the cart and send them to Google contacts for the specified Google Account.  This process will sync contacts and create new ones.  The process will not delete contacts in Google contacts.');

define("_OSCMEM_directoryreport_description","Phone book directory with pictures.  Created in PDF format.");

define("_OSCMEM_csvexport_individual_description","Screen that allows the user to filter their selection of members or use the contents of the cart to generate a Comma Seperated file.");

define("_oscmem_csvimport_individual_description","Import allows the import of indivual records provided in a comma seperated format.");

define("_oscmem_vcardimport_individual_description","Screen imports vcard format files to create an individual");

define("_oscmem_menu_orphanmatchup_description","Screen matches orphan records to likely family records.  Allows user to select the orphaned records and associate them with a family record.");

define("_oscmem_csvimport_family_description","Screen imports family records that are provided in a comma seperated format.");

define("_oscmem_membershipcard_description","Generates membership id cards with a scannable bar code.");

define("_oscmem_childcard_description","Generates a child and parent id card for child check-in purposes.");

define("_oscmem_googlecontactsync","Google Contact Sync");

define("_oscmem_googlecontactsync_description","Syncs membership data with any selected Google gmail's contacs.");


define('_oscmem_report_label','Reports');
define('_oscmem_report_description','Description of Available Reports');

define('_oscmem_google_group','Google Contact Group');
define('_oscmem_menu_rightbutton','Next >');
define('_oscmem_menu_rightrightbutton','>>');
define('_oscmem_menu_leftbutton','< Previous');
define('_oscmem_menu_leftleftbutton','<<');
?>