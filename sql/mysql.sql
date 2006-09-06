CREATE TABLE `oscmembership_person` (
  `id` mediumint(9) unsigned NOT NULL auto_increment ,
  `churchid` int(11) NOT NULL default '0',
  `title` varchar(50) default NULL,
  `firstname` varchar(50) default NULL,
  `middlename` varchar(50) default NULL,
  `lastname` varchar(50) default NULL,
  `suffix` varchar(50) default NULL,
  `address1` varchar(50) default NULL,
  `address2` varchar(50) default NULL,
  `city` varchar(50) default NULL,
  `state` varchar(50) default NULL,
  `zip` varchar(50) default NULL,
  `country` varchar(50) default NULL,
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
  `fmrid` tinyint(3) unsigned NOT NULL default '0',
  `clsid` tinyint(3) unsigned NOT NULL default '0',
  `famid` smallint(5) unsigned NOT NULL default '0',
  `envelope` smallint(5) unsigned default NULL,
  `datelastedited` datetime default NULL,
  `dateentered` datetime NOT NULL default '0000-00-00 00:00:00',
  `enteredby` smallint(5) unsigned NOT NULL default '0',
  `editedby` smallint(5) unsigned default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ;

CREATE TABLE `oscmembership_family` (
  `id` mediumint(9) NOT NULL auto_increment,
  `churchid` int(11) NOT NULL default '0',
  `familyname` varchar(50) default NULL,
  `address1` varchar(255) default NULL,
  `address2` varchar(255) default NULL,
  `city` varchar(50) default NULL,
  `state` varchar(50) default NULL,
  `zip` varchar(50) default NULL,
  `country` varchar(50) default NULL,
  `homephone` varchar(30) default NULL,
  `workphone` varchar(30) default NULL,
  `cellphone` varchar(30) default NULL,
  `email` varchar(100) default NULL,
  `weddingdate` date default NULL,
  `dateentered` datetime NOT NULL default '0000-00-00 00:00:00',
  `datelastedited` datetime default NULL,
  `enteredby` smallint(5) unsigned NOT NULL default '0',
  `editedby` smallint(5) unsigned default '0',
  PRIMARY KEY  (`id`),
  KEY `familyid` (`id`)
) ;


CREATE TABLE `oscmembership_group` (
  `id` mediumint(9) NOT NULL auto_increment,
  `group_type` tinyint(4) default '0',
  `group_RoleListID` mediumint(8) default '0' not null,
  `group_DefaultRole` mediumint(9) default '0' not null,
  `group_Description` text default NULL,
  `group_Name` varchar(50) not null default '',
  `group_hasSpecialProps` enum('true','false') not null default 'false',
  PRIMARY KEY  (`id`),
  KEY `familyid` (`id`)
) ;


    
CREATE TABLE `oscmembership_groupprop_master` (
  `grp_ID` mediumint(9) unsigned NOT NULL default '0',
  `prop_ID` tinyint(3) unsigned NOT NULL default '0',
  `chu_Church_ID` int(11) NOT NULL default '0',
  `prop_Field` varchar(5) NOT NULL default '0',
  `prop_Name` varchar(40) default NULL,
  `prop_Description` varchar(60) default NULL,
  `type_ID` smallint(5) unsigned NOT NULL default '0',
  `prop_Special` mediumint(9) unsigned default NULL,
  `prop_PersonDisplay` enum('false','true') NOT NULL default 'false'
) TYPE=MyISAM COMMENT='Group-specific properties order, name, description, type'; 


CREATE TABLE `oscmembership_list` (
  `id` mediumint(8) unsigned NOT NULL default '0' ,
  `optionid` mediumint(8) unsigned NOT NULL default '0',
  `optionsequence` tinyint(3) unsigned NOT NULL default '0',
  `optionname` varchar(50) NOT NULL default ''
) TYPE=MyISAM; 

CREATE TABLE `oscmembership_p2g2r` (
  `person_id` mediumint(8) unsigned NOT NULL default '0',
  `group_id` mediumint(8) unsigned NOT NULL default '0',
  `role_id` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`person_id`, `group_id`),
  KEY `p2g2r_personid` (`person_id`,`group_id`,`role_id`)
) TYPE=MyISAM; 

CREATE TABLE `oscmembership_group_members` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` int(11) NOT NULL default '0',
  `person_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;


CREATE TABLE `oscmembership_person_custom` (
  `per_ID` mediumint(9) NOT NULL default '0',
  `c1` date default NULL,
  `c2` enum('false','true') default NULL,
  `c3` enum('winter','spring','summer','fall') default NULL,
  `c4` enum('false','true') default NULL,
  `c5` date default NULL,
  `c6` varchar(50) default NULL,
  `c7` tinyint(4) default NULL,
  PRIMARY KEY  (`per_ID`)
) TYPE=MyISAM; 

CREATE TABLE `oscmembership_churchdir` (
`id` int not null,
 `church_name` varchar(255) null
 ,`church_address` varchar(255) null
 ,`church_city` varchar(255) null
 ,`church_state` varchar(255) null
 , `church_post` varchar(255) null
 , `church_phone` varchar(255) null
 ,`disclaimer` text
 , PRIMARY KEY (`church_name`)
 ) TYPE=MyISAM;

# 
CREATE TABLE `oscmembership_person_custom_master` (
  `custom_Order` smallint(6) NOT NULL default '0',
  `custom_Field` varchar(5) NOT NULL default '',
  `custom_Name` varchar(40) NOT NULL default '',
  `custom_Special` mediumint(8) unsigned default NULL,
  `custom_Side` enum('left','right') NOT NULL default 'left',
  `type_ID` tinyint(4) NOT NULL default '0'
) TYPE=MyISAM; 

# 
CREATE TABLE `oscmembership_cart` (
  `xoops_uid` mediumint(8) NOT NULL,
  `cart_timestamp` timestamp NOT NULL,
  `person_id` mediumint(9) NOT NULL
  ) TYPE=MyISAM; 


# Sample data for member classifications
INSERT INTO oscmembership_list VALUES (1, 1, 1, 'Member');
INSERT INTO oscmembership_list VALUES (1, 2, 2, 'Regular Attender');
INSERT INTO oscmembership_list VALUES (1, 3, 3, 'Guest');
INSERT INTO oscmembership_list VALUES (1, 5, 4, 'Non-Attender');
INSERT INTO oscmembership_list VALUES (1, 4, 5, 'Non-Attender (staff)');

# Sample data for family roles
INSERT INTO oscmembership_list VALUES (2, 1, 1, 'Head of Household');
INSERT INTO oscmembership_list VALUES (2, 2, 2, 'Spouse');
INSERT INTO oscmembership_list VALUES (2, 3, 3, 'Child');
INSERT INTO oscmembership_list VALUES (2, 4, 4, 'Other Relative');
INSERT INTO oscmembership_list VALUES (2, 5, 5, 'Non Relative');

# Sample data for group types
INSERT INTO oscmembership_list VALUES (3, 1, 1, 'Ministry');
INSERT INTO oscmembership_list VALUES (3, 2, 2, 'Team');
INSERT INTO oscmembership_list VALUES (3, 3, 3, 'Bible Study');
INSERT INTO oscmembership_list VALUES (3, 4, 4, 'Sunday School Class');

# Insert the custom-field / group-property types
INSERT INTO oscmembership_list VALUES (4, 1, 1, 'True / False');
INSERT INTO oscmembership_list VALUES (4, 2, 2, 'Date');
INSERT INTO oscmembership_list VALUES (4, 3, 3, 'Text Field (50 char)');
INSERT INTO oscmembership_list VALUES (4, 4, 4, 'Text Field (100 char)');
INSERT INTO oscmembership_list VALUES (4, 5, 5, 'Text Field (Long)');
INSERT INTO oscmembership_list VALUES (4, 6, 6, 'Year');
INSERT INTO oscmembership_list VALUES (4, 7, 7, 'Season');
INSERT INTO oscmembership_list VALUES (4, 8, 8, 'Number');
INSERT INTO oscmembership_list VALUES (4, 9, 9, 'Person from Group');
INSERT INTO oscmembership_list VALUES (4, 10, 10, 'Money');
INSERT INTO oscmembership_list VALUES (4, 11, 11, 'Phone Number');
INSERT INTO oscmembership_list VALUES (4, 12, 12, 'Custom Drop-Down List');

# insert church Directory info
insert into oscmembership_churchdir(id,church_name,church_address,church_city,church_state,church_post,church_phone, disclaimer) values(0,'Your Church Name','Your Church Address','Your Church City','Your Church State','Your Church Zip Code','Your Church Phone #','Enter your disclaimer here');
