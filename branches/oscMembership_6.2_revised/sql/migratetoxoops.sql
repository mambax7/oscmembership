truncate table xoops2016.xoops_oscmembership_cart;
truncate table xoops2016.xoops_oscmembership_family;
truncate table xoops2016.xoops_oscmembership_group;
truncate table xoops2016.xoops_oscmembership_group_members;
truncate table xoops2016.xoops_oscmembership_groupprop_master;
truncate table xoops2016.xoops_oscmembership_list;
truncate table xoops2016.xoops_oscmembership_p2g2r;
truncate table xoops2016.xoops_oscmembership_person;
truncate table xoops2016.xoops_oscmembership_person_custom_master;

insert into xoops2016.xoops_oscmembership_person
(id,title,firstname,middlename,lastname,suffix,address1,address2,city,state,
zip,country,homephone,workphone, cellphone,email,workemail,birthday,birthmonth,
birthyear, membershipdate, gender,fmrid,clsid,famid,envelope,datelastedited,dateentered,enteredby,editedby)
select
	per_ID
	,per_Title
	,per_FirstName
	,per_MiddleName
	,per_LastName
	,per_Suffix
	,per_Address1
	,per_Address2
	,per_City
	,per_State
	,per_Zip
	,per_Country
	,per_HomePhone
	,per_WorkPhone
	,per_CellPhone
	,per_Email
	,per_WorkEmail
	,per_BirthMonth
	,per_BirthDay
	,per_BirthYear
	,per_MembershipDate
	,per_Gender
	,per_fmr_ID
	,per_cls_ID
	,per_fam_ID
	,per_Envelope
	,per_DateLastEdited
	,per_DateEntered
	,1
	,1
from osc.person_per;

insert into xoops2016.xoops_oscmembership_person_custom_master(
custom_Order, custom_Field, custom_Name,custom_Special,custom_Side,
type_ID)

select 
	custom_Order
	,custom_Field
	,custom_Name
	,custom_Special
	,custom_Side
	,type_ID
from osc.person_custom_master;
	
drop table xoops2016.xoops_oscmembership_person_custom;

CREATE TABLE xoops2016.`xoops_oscmembership_person_custom` (
  `per_ID` mediumint(9) NOT NULL default '0',
PRIMARY KEY  (`per_ID`)
);

insert into xoops2016.xoops_oscmembership_person_custom
(per_ID /* c1, c2... */)
select 
	per_ID
/*	,c1
	,c2
*/
from osc.person_custom;

insert into xoops2016.xoops_oscmembership_family(id,familyname,altfamilyname,
address1,address2,city,state,zip,country,homephone,workphone,cellphone
,email,weddingdate,dateentered,datelastedited,enteredby,editedby)
select
	fam_ID
	,fam_Name
	,fam_Address1
	,fam_Address2
	,fam_City
	,fam_State
	,fam_Zip
	,fam_Country
	,fam_HomePhone
	,fam_WorkPhone
	,fam_CellPhone
	,fam_Email
	,fam_WeddingDate
	,fam_DateEntered
	,fam_DateLastEdited
	,1
	,1
	,fam_altFamilyname
from osc.family_fam;

insert into xoops2016.xoops_oscmembership_group(id, group_type,group_RoleListID,group_DefaultRole,
group_Description, group_Name,group_hasSpecialProps)
select
	grp_ID
	,grp_Type
	,grp_RoleListID
	,grp_DefaultRole
	,grp_Name
	,grp_Description
	,grp_hasSpecialProps
from osc.group_grp;

insert into xoops2016.xoops_oscmembership_groupprop_master(grp_ID,prop_ID,
prop_Field, prop_Name, prop_Description, type_ID, prop_Special, prop_PersonDisplay)
select
	grp_ID
	,prop_ID
	,prop_Field
	,prop_Name
	,prop_Description
	,type_ID
	,prop_Special
	,prop_PersonDisplay
from osc.groupprop_master;

insert into xoops2016.xoops_oscmembership_list(id, optionid, optionsequence
, optionname)
select
	lst_ID
	,lst_OptionID
	,lst_OptionSequence
	,lst_OptionName
from osc.list_lst;

insert into xoops2016.xoops_oscmembership_p2g2r(personid, group_id,
role_id)
select
	p2g2r_per_ID
	,p2g2r_grp_ID
	,p2g2r_rle_ID
from osc.person2group2role_p2g2r;
