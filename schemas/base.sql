SET character_set_client = utf8;
drop table if exists `user`;
create table `user` ( 
	id int not null auto_increment,
	Username varchar(255) default null,
	Email varchar(512) not null,
	Password varchar(512) not null,
	FirstName varchar(512) default null,
	LastName varchar(512) default null,
	Address1 varchar(512) default null,
	Address2 varchar(512) default null,
	Town varchar(512) default null,
	County varchar(512) default null,
	country_id int default null,
	daytime__ui_theme_id int not null default 1,
	nighttime__ui_theme_id int not null default 6,
	visual__rich_editor_id int not null default 1,
	code__rich_editor_id int not null default 4,
	DayState enum('Daytime','Nighttime') not null default 'Daytime',
	ThemeIdentifier varchar(255) not null default 'simple-blog',
	default__page_id int not null default 1,
	CreateDate TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
	ModifyDate datetime default null,
	DeleteDate datetime default null,
	primary key (id),
	unique key (Username),
	index (country_id),
	index (DeleteDate),
	index (daytime__ui_theme_id),
	index (nighttime__ui_theme_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
insert into user set Username='slinq',Email='kieran@slinq.com',Password='testpass',FirstName='Kieran',LastName='Simkin';
drop table if exists `user_picture_size`;
create table `user_picture_size` (
	id int not null auto_increment,
	user_id int not null,
	picture_size_type enum('web-small','web-medium','web-large','thumbnail-large','thumbnail-small','square') not null,
	`size` int not null,
	primary key (id),
	index (user_id),
	index (user_id,picture_size_type),
	unique key (user_id,picture_size_type)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
insert into user_picture_size set user_id=1, picture_size_type='web-small', `size`=320;
insert into user_picture_size set user_id=1, picture_size_type='web-medium', `size`=640;
insert into user_picture_size set user_id=1, picture_size_type='web-large', `size`=960;
insert into user_picture_size set user_id=1, picture_size_type='thumbnail-large', `size`=200;
insert into user_picture_size set user_id=1, picture_size_type='thumbnail-small', `size`=150;
insert into user_picture_size set user_id=1, picture_size_type='square', `size`=100;
drop table if exists `ui_theme`;
create table `ui_theme` (
	id int not null auto_increment,
	Tag varchar(255) NOT NULL,
	ui_state enum('day','night','both') not null default 'both',
	primary key (id),
	index (ui_state),
	unique key (Tag)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
insert into ui_theme set Tag='cupertino', ui_state='day';
insert into ui_theme set Tag='black-tie', ui_state='day';
insert into ui_theme set Tag='blitzer', ui_state='day';
insert into ui_theme set Tag='dark-hive', ui_state='night';
insert into ui_theme set Tag='dot-luv', ui_state='night';
insert into ui_theme set Tag='eggplant', ui_state='both';
insert into ui_theme set Tag='excite-bike', ui_state='day';
insert into ui_theme set Tag='flick', ui_state='day';
insert into ui_theme set Tag='hot-sneaks', ui_state='day';
insert into ui_theme set Tag='humanity', ui_state='day';
insert into ui_theme set Tag='le-frog', ui_state='night';
insert into ui_theme set Tag='mint-choc', ui_state='night';
insert into ui_theme set Tag='overcast', ui_state='day';
insert into ui_theme set Tag='pepper-grinder', ui_state='day';
insert into ui_theme set Tag='redmond', ui_state='day';
insert into ui_theme set Tag='smoothness', ui_state='day';
insert into ui_theme set Tag='south-street', ui_state='day';
insert into ui_theme set Tag='start', ui_state='day';
insert into ui_theme set Tag='sunny', ui_state='day';
insert into ui_theme set Tag='swanky-purse', ui_state='night';
insert into ui_theme set Tag='trontastic', ui_state='night';
insert into ui_theme set Tag='ui-darkness', ui_state='both';
insert into ui_theme set Tag='ui-lightness', ui_state='day';
insert into ui_theme set Tag='vader', ui_state='both';
insert into ui_theme set Tag='purple-lady', ui_state='day';
insert into ui_theme set Tag='leafy-suburb', ui_state='day';
insert into ui_theme set Tag='midnight-blue', ui_state='day';
insert into ui_theme set Tag='pattern-plum', ui_state='day';
drop table if exists `rich_editor`;
create table `rich_editor` (
	id int not null auto_increment,
	`Type` enum('Code','Visual') not null default 'Visual',
	Tag varchar(255) NOT NULL,
	Description text not null,
	primary key (id),
	index (`Type`),
	unique key (Tag)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
insert into rich_editor set Tag='TinyMCE',Description='The TinyMCE WYSIWYG HTML editor.', `Type`='Visual';
insert into rich_editor set Tag='CKEditor',Description='The CKEditor WYSIWYG HTML editor.', `Type`='Visual';
insert into rich_editor set Tag='EditArea',Description='The EditArea code editor.', `Type`='Code';
insert into rich_editor set Tag='Ace',Description='The Ace code editor.', `Type`='Code';
insert into rich_editor set Tag='markItUp',Description='The markItUp code editor.', `Type`='Code';
insert into rich_editor set Tag='CodeMirror',Description='The CodeMirror code editor.', `Type`='Code';
drop table if exists `user_challenge`;
create table `user_challenge` ( 
	id int not null auto_increment,
	user_id int not null,
	challenge varchar(64) NOT NULL,
	CreateDate TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
	primary key (id),
	index (user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists `user_token`;
create table `user_token` (
	id int not null auto_increment,
	user_id int not null,
	token varchar(255) not null,
	CreateDate TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
	DeleteDate datetime default null,
	expires enum('false','true') not null default 'false',
	primary key (id),
	index (user_id),
	index (DeleteDate,user_id,token)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists `group`;
create table `group` (
	id int not null auto_increment,
	Tag varchar(255) NOT NULL,
	Description text not null,
	primary key (id),
	unique key (Tag)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists `user_group`;
create table `user_group` ( 
	id int not null auto_increment,
	user_id int NOT NULL,
	group_id int not null,
	primary key (id),
	unique key (user_id,group_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `Name` varchar(256) NOT NULL,
  `Alpha2` char(2) NOT NULL,
  `Alpha3` char(3) NOT NULL,
  `CreateDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModifyDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Alpha2` (`Alpha2`),
  UNIQUE KEY `Alpha3` (`Alpha3`)
) ENGINE=MyISAM AUTO_INCREMENT=247 DEFAULT CHARSET=utf8;
LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
INSERT INTO `country` VALUES (1,'Afghanistan','AF','AFG','2009-11-15 02:41:18',NULL),(2,'Ãland Islands','AX','ALA','2009-11-15 02:41:18',NULL),(3,'Albania','AL','ALB','2009-11-15 02:41:18',NULL),(4,'Algeria','DZ','DZA','2009-11-15 02:41:18',NULL),(5,'American Samoa','AS','ASM','2009-11-15 02:41:18',NULL),(6,'Andorra','AD','AND','2009-11-15 02:41:18',NULL),(7,'Angola','AO','AGO','2009-11-15 02:41:18',NULL),(8,'Anguilla','AI','AIA','2009-11-15 02:41:18',NULL),(9,'Antarctica','AQ','ATA','2009-11-15 02:41:18',NULL),(10,'Antigua and Barbuda','AG','ATG','2009-11-15 02:41:18',NULL),(11,'Argentina','AR','ARG','2009-11-15 02:41:18',NULL),(12,'Armenia','AM','ARM','2009-11-15 02:41:18',NULL),(13,'Aruba','AW','ABW','2009-11-15 02:41:18',NULL),(14,'Australia','AU','AUS','2009-11-15 02:41:18',NULL),(15,'Austria','AT','AUT','2009-11-15 02:41:18',NULL),(16,'Azerbaijan','AZ','AZE','2009-11-15 02:41:18',NULL),(17,'Bahamas','BS','BHS','2009-11-15 02:41:18',NULL),(18,'Bahrain','BH','BHR','2009-11-15 02:41:18',NULL),(19,'Bangladesh','BD','BGD','2009-11-15 02:41:18',NULL),(20,'Barbados','BB','BRB','2009-11-15 02:41:18',NULL),(21,'Belarus','BY','BLR','2009-11-15 02:41:18',NULL),(22,'Belgium','BE','BEL','2009-11-15 02:41:18',NULL),(23,'Belize','BZ','BLZ','2009-11-15 02:41:18',NULL),(24,'Benin','BJ','BEN','2009-11-15 02:41:18',NULL),(25,'Bermuda','BM','BMU','2009-11-15 02:41:18',NULL),(26,'Bhutan','BT','BTN','2009-11-15 02:41:18',NULL),(27,'Bolivia, Plurinational State of','BO','BOL','2009-11-15 02:41:18',NULL),(28,'Bosnia and Herzegovina','BA','BIH','2009-11-15 02:41:18',NULL),(29,'Botswana','BW','BWA','2009-11-15 02:41:18',NULL),(30,'Bouvet Island','BV','BVT','2009-11-15 02:41:18',NULL),(31,'Brazil','BR','BRA','2009-11-15 02:41:18',NULL),(32,'British Indian Ocean Territory','IO','IOT','2009-11-15 02:41:18',NULL),(33,'Brunei Darussalam','BN','BRN','2009-11-15 02:41:18',NULL),(34,'Bulgaria','BG','BGR','2009-11-15 02:41:18',NULL),(35,'Burkina Faso','BF','BFA','2009-11-15 02:41:18',NULL),(36,'Burundi','BI','BDI','2009-11-15 02:41:18',NULL),(37,'Cambodia','KH','KHM','2009-11-15 02:41:19',NULL),(38,'Cameroon','CM','CMR','2009-11-15 02:41:19',NULL),(39,'Canada','CA','CAN','2009-11-15 02:41:19',NULL),(40,'Cape Verde','CV','CPV','2009-11-15 02:41:19',NULL),(41,'Cayman Islands','KY','CYM','2009-11-15 02:41:19',NULL),(42,'Central African Republic','CF','CAF','2009-11-15 02:41:19',NULL),(43,'Chad','TD','TCD','2009-11-15 02:41:19',NULL),(44,'Chile','CL','CHL','2009-11-15 02:41:19',NULL),(45,'China','CN','CHN','2009-11-15 02:41:19',NULL),(46,'Christmas Island','CX','CXR','2009-11-15 02:41:19',NULL),(47,'Cocos (Keeling) Islands','CC','CCK','2009-11-15 02:41:19',NULL),(48,'Colombia','CO','COL','2009-11-15 02:41:19',NULL),(49,'Comoros','KM','COM','2009-11-15 02:41:19',NULL),(50,'Congo','CG','COG','2009-11-15 02:41:19',NULL),(51,'Congo, the Democratic Republic of the','CD','COD','2009-11-15 02:41:19',NULL),(52,'Cook Islands','CK','COK','2009-11-15 02:41:19',NULL),(53,'Costa Rica','CR','CRI','2009-11-15 02:41:19',NULL),(54,'CÃ´te d\'Ivoire','CI','CIV','2009-11-15 02:41:19',NULL),(55,'Croatia','HR','HRV','2009-11-15 02:41:19',NULL),(56,'Cuba','CU','CUB','2009-11-15 02:41:19',NULL),(57,'Cyprus','CY','CYP','2009-11-15 02:41:19',NULL),(58,'Czech Republic','CZ','CZE','2009-11-15 02:41:19',NULL),(59,'Denmark','DK','DNK','2009-11-15 02:41:19',NULL),(60,'Djibouti','DJ','DJI','2009-11-15 02:41:19',NULL),(61,'Dominica','DM','DMA','2009-11-15 02:41:19',NULL),(62,'Dominican Republic','DO','DOM','2009-11-15 02:41:19',NULL),(63,'Ecuador','EC','ECU','2009-11-15 02:41:19',NULL),(64,'Egypt','EG','EGY','2009-11-15 02:41:19',NULL),(65,'El Salvador','SV','SLV','2009-11-15 02:41:19',NULL),(66,'Equatorial Guinea','GQ','GNQ','2009-11-15 02:41:19',NULL),(67,'Eritrea','ER','ERI','2009-11-15 02:41:19',NULL),(68,'Estonia','EE','EST','2009-11-15 02:41:19',NULL),(69,'Ethiopia','ET','ETH','2009-11-15 02:41:19',NULL),(70,'Falkland Islands (Malvinas)','FK','FLK','2009-11-15 02:41:19',NULL),(71,'Faroe Islands','FO','FRO','2009-11-15 02:41:19',NULL),(72,'Fiji','FJ','FJI','2009-11-15 02:41:19',NULL),(73,'Finland','FI','FIN','2009-11-15 02:41:19',NULL),(74,'France','FR','FRA','2009-11-15 02:41:19',NULL),(75,'French Guiana','GF','GUF','2009-11-15 02:41:19',NULL),(76,'French Polynesia','PF','PYF','2009-11-15 02:41:19',NULL),(77,'French Southern Territories','TF','ATF','2009-11-15 02:41:19',NULL),(78,'Gabon','GA','GAB','2009-11-15 02:41:19',NULL),(79,'Gambia','GM','GMB','2009-11-15 02:41:19',NULL),(80,'Georgia','GE','GEO','2009-11-15 02:41:19',NULL),(81,'Germany','DE','DEU','2009-11-15 02:41:19',NULL),(82,'Ghana','GH','GHA','2009-11-15 02:41:19',NULL),(83,'Gibraltar','GI','GIB','2009-11-15 02:41:19',NULL),(84,'Greece','GR','GRC','2009-11-15 02:41:19',NULL),(85,'Greenland','GL','GRL','2009-11-15 02:41:19',NULL),(86,'Grenada','GD','GRD','2009-11-15 02:41:19',NULL),(87,'Guadeloupe','GP','GLP','2009-11-15 02:41:19',NULL),(88,'Guam','GU','GUM','2009-11-15 02:41:19',NULL),(89,'Guatemala','GT','GTM','2009-11-15 02:41:19',NULL),(90,'Guernsey','GG','GGY','2009-11-15 02:41:19',NULL),(91,'Guinea','GN','GIN','2009-11-15 02:41:19',NULL),(92,'Guinea-Bissau','GW','GNB','2009-11-15 02:41:19',NULL),(93,'Guyana','GY','GUY','2009-11-15 02:41:19',NULL),(94,'Haiti','HT','HTI','2009-11-15 02:41:19',NULL),(95,'Heard Island and McDonald Islands','HM','HMD','2009-11-15 02:41:19',NULL),(96,'Holy See (Vatican City State)','VA','VAT','2009-11-15 02:41:19',NULL),(97,'Honduras','HN','HND','2009-11-15 02:41:19',NULL),(98,'Hong Kong','HK','HKG','2009-11-15 02:41:19',NULL),(99,'Hungary','HU','HUN','2009-11-15 02:41:19',NULL),(100,'Iceland','IS','ISL','2009-11-15 02:41:19',NULL),(101,'India','IN','IND','2009-11-15 02:41:19',NULL),(102,'Indonesia','ID','IDN','2009-11-15 02:41:19',NULL),(103,'Iran, Islamic Republic of','IR','IRN','2009-11-15 02:41:19',NULL),(104,'Iraq','IQ','IRQ','2009-11-15 02:41:19',NULL),(105,'Ireland','IE','IRL','2009-11-15 02:41:19',NULL),(106,'Isle of Man','IM','IMN','2009-11-15 02:41:19',NULL),(107,'Israel','IL','ISR','2009-11-15 02:41:19',NULL),(108,'Italy','IT','ITA','2009-11-15 02:41:19',NULL),(109,'Jamaica','JM','JAM','2009-11-15 02:41:19',NULL),(110,'Japan','JP','JPN','2009-11-15 02:41:19',NULL),(111,'Jersey','JE','JEY','2009-11-15 02:41:19',NULL),(112,'Jordan','JO','JOR','2009-11-15 02:41:19',NULL),(113,'Kazakhstan','KZ','KAZ','2009-11-15 02:41:19',NULL),(114,'Kenya','KE','KEN','2009-11-15 02:41:19',NULL),(115,'Kiribati','KI','KIR','2009-11-15 02:41:19',NULL),(116,'Korea, Democratic People\'s Republic of','KP','PRK','2009-11-15 02:41:19',NULL),(117,'Korea, Republic of','KR','KOR','2009-11-15 02:41:19',NULL),(118,'Kuwait','KW','KWT','2009-11-15 02:41:19',NULL),(119,'Kyrgyzstan','KG','KGZ','2009-11-15 02:41:19',NULL),(120,'Lao People\'s Democratic Republic','LA','LAO','2009-11-15 02:41:19',NULL),(121,'Latvia','LV','LVA','2009-11-15 02:41:19',NULL),(122,'Lebanon','LB','LBN','2009-11-15 02:41:19',NULL),(123,'Lesotho','LS','LSO','2009-11-15 02:41:19',NULL),(124,'Liberia','LR','LBR','2009-11-15 02:41:19',NULL),(125,'Libyan Arab Jamahiriya','LY','LBY','2009-11-15 02:41:19',NULL),(126,'Liechtenstein','LI','LIE','2009-11-15 02:41:19',NULL),(127,'Lithuania','LT','LTU','2009-11-15 02:41:19',NULL),(128,'Luxembourg','LU','LUX','2009-11-15 02:41:19',NULL),(129,'Macao','MO','MAC','2009-11-15 02:41:19',NULL),(130,'Macedonia, the former Yugoslav Republic of','MK','MKD','2009-11-15 02:41:19',NULL),(131,'Madagascar','MG','MDG','2009-11-15 02:41:19',NULL),(132,'Malawi','MW','MWI','2009-11-15 02:41:19',NULL),(133,'Malaysia','MY','MYS','2009-11-15 02:41:19',NULL),(134,'Maldives','MV','MDV','2009-11-15 02:41:19',NULL),(135,'Mali','ML','MLI','2009-11-15 02:41:19',NULL),(136,'Malta','MT','MLT','2009-11-15 02:41:19',NULL),(137,'Marshall Islands','MH','MHL','2009-11-15 02:41:19',NULL),(138,'Martinique','MQ','MTQ','2009-11-15 02:41:19',NULL),(139,'Mauritania','MR','MRT','2009-11-15 02:41:19',NULL),(140,'Mauritius','MU','MUS','2009-11-15 02:41:19',NULL),(141,'Mayotte','YT','MYT','2009-11-15 02:41:19',NULL),(142,'Mexico','MX','MEX','2009-11-15 02:41:19',NULL),(143,'Micronesia, Federated States of','FM','FSM','2009-11-15 02:41:19',NULL),(144,'Moldova, Republic of','MD','MDA','2009-11-15 02:41:19',NULL),(145,'Monaco','MC','MCO','2009-11-15 02:41:19',NULL),(146,'Mongolia','MN','MNG','2009-11-15 02:41:19',NULL),(147,'Montenegro','ME','MNE','2009-11-15 02:41:19',NULL),(148,'Montserrat','MS','MSR','2009-11-15 02:41:19',NULL),(149,'Morocco','MA','MAR','2009-11-15 02:41:19',NULL),(150,'Mozambique','MZ','MOZ','2009-11-15 02:41:19',NULL),(151,'Myanmar','MM','MMR','2009-11-15 02:41:19',NULL),(152,'Namibia','NA','NAM','2009-11-15 02:41:19',NULL),(153,'Nauru','NR','NRU','2009-11-15 02:41:19',NULL),(154,'Nepal','NP','NPL','2009-11-15 02:41:19',NULL),(155,'Netherlands','NL','NLD','2009-11-15 02:41:19',NULL),(156,'Netherlands Antilles','AN','ANT','2009-11-15 02:41:19',NULL),(157,'New Caledonia','NC','NCL','2009-11-15 02:41:19',NULL),(158,'New Zealand','NZ','NZL','2009-11-15 02:41:19',NULL),(159,'Nicaragua','NI','NIC','2009-11-15 02:41:19',NULL),(160,'Niger','NE','NER','2009-11-15 02:41:19',NULL),(161,'Nigeria','NG','NGA','2009-11-15 02:41:19',NULL),(162,'Niue','NU','NIU','2009-11-15 02:41:19',NULL),(163,'Norfolk Island','NF','NFK','2009-11-15 02:41:19',NULL),(164,'Northern Mariana Islands','MP','MNP','2009-11-15 02:41:19',NULL),(165,'Norway','NO','NOR','2009-11-15 02:41:19',NULL),(166,'Oman','OM','OMN','2009-11-15 02:41:19',NULL),(167,'Pakistan','PK','PAK','2009-11-15 02:41:19',NULL),(168,'Palau','PW','PLW','2009-11-15 02:41:19',NULL),(169,'Palestinian Territory, Occupied','PS','PSE','2009-11-15 02:41:19',NULL),(170,'Panama','PA','PAN','2009-11-15 02:41:19',NULL),(171,'Papua New Guinea','PG','PNG','2009-11-15 02:41:19',NULL),(172,'Paraguay','PY','PRY','2009-11-15 02:41:19',NULL),(173,'Peru','PE','PER','2009-11-15 02:41:19',NULL),(174,'Philippines','PH','PHL','2009-11-15 02:41:19',NULL),(175,'Pitcairn','PN','PCN','2009-11-15 02:41:19',NULL),(176,'Poland','PL','POL','2009-11-15 02:41:19',NULL),(177,'Portugal','PT','PRT','2009-11-15 02:41:19',NULL),(178,'Puerto Rico','PR','PRI','2009-11-15 02:41:19',NULL),(179,'Qatar','QA','QAT','2009-11-15 02:41:19',NULL),(180,'RÃ©union','RE','REU','2009-11-15 02:41:19',NULL),(181,'Romania','RO','ROU','2009-11-15 02:41:19',NULL),(182,'Russian Federation','RU','RUS','2009-11-15 02:41:19',NULL),(183,'Rwanda','RW','RWA','2009-11-15 02:41:19',NULL),(184,'Saint BarthÃ©lemy','BL','BLM','2009-11-15 02:41:19',NULL),(185,'Saint Helena','SH','SHN','2009-11-15 02:41:19',NULL),(186,'Saint Kitts and Nevis','KN','KNA','2009-11-15 02:41:19',NULL),(187,'Saint Lucia','LC','LCA','2009-11-15 02:41:19',NULL),(188,'Saint Martin (French part)','MF','MAF','2009-11-15 02:41:19',NULL),(189,'Saint Pierre and Miquelon','PM','SPM','2009-11-15 02:41:19',NULL),(190,'Saint Vincent and the Grenadines','VC','VCT','2009-11-15 02:41:19',NULL),(191,'Samoa','WS','WSM','2009-11-15 02:41:19',NULL),(192,'San Marino','SM','SMR','2009-11-15 02:41:19',NULL),(193,'Sao Tome and Principe','ST','STP','2009-11-15 02:41:19',NULL),(194,'Saudi Arabia','SA','SAU','2009-11-15 02:41:19',NULL),(195,'Senegal','SN','SEN','2009-11-15 02:41:19',NULL),(196,'Serbia','RS','SRB','2009-11-15 02:41:19',NULL),(197,'Seychelles','SC','SYC','2009-11-15 02:41:19',NULL),(198,'Sierra Leone','SL','SLE','2009-11-15 02:41:19',NULL),(199,'Singapore','SG','SGP','2009-11-15 02:41:19',NULL),(200,'Slovakia','SK','SVK','2009-11-15 02:41:19',NULL),(201,'Slovenia','SI','SVN','2009-11-15 02:41:19',NULL),(202,'Solomon Islands','SB','SLB','2009-11-15 02:41:19',NULL),(203,'Somalia','SO','SOM','2009-11-15 02:41:19',NULL),(204,'South Africa','ZA','ZAF','2009-11-15 02:41:19',NULL),(205,'South Georgia and the South Sandwich Islands','GS','SGS','2009-11-15 02:41:19',NULL),(206,'Spain','ES','ESP','2009-11-15 02:41:19',NULL),(207,'Sri Lanka','LK','LKA','2009-11-15 02:41:19',NULL),(208,'Sudan','SD','SDN','2009-11-15 02:41:19',NULL),(209,'Suriname','SR','SUR','2009-11-15 02:41:19',NULL),(210,'Svalbard and Jan Mayen','SJ','SJM','2009-11-15 02:41:19',NULL),(211,'Swaziland','SZ','SWZ','2009-11-15 02:41:19',NULL),(212,'Sweden','SE','SWE','2009-11-15 02:41:19',NULL),(213,'Switzerland','CH','CHE','2009-11-15 02:41:19',NULL),(214,'Syrian Arab Republic','SY','SYR','2009-11-15 02:41:19',NULL),(215,'Taiwan, Province of China','TW','TWN','2009-11-15 02:41:19',NULL),(216,'Tajikistan','TJ','TJK','2009-11-15 02:41:19',NULL),(217,'Tanzania, United Republic of','TZ','TZA','2009-11-15 02:41:19',NULL),(218,'Thailand','TH','THA','2009-11-15 02:41:19',NULL),(219,'Timor-Leste','TL','TLS','2009-11-15 02:41:19',NULL),(220,'Togo','TG','TGO','2009-11-15 02:41:19',NULL),(221,'Tokelau','TK','TKL','2009-11-15 02:41:19',NULL),(222,'Tonga','TO','TON','2009-11-15 02:41:19',NULL),(223,'Trinidad and Tobago','TT','TTO','2009-11-15 02:41:19',NULL),(224,'Tunisia','TN','TUN','2009-11-15 02:41:19',NULL),(225,'Turkey','TR','TUR','2009-11-15 02:41:19',NULL),(226,'Turkmenistan','TM','TKM','2009-11-15 02:41:19',NULL),(227,'Turks and Caicos Islands','TC','TCA','2009-11-15 02:41:19',NULL),(228,'Tuvalu','TV','TUV','2009-11-15 02:41:19',NULL),(229,'Uganda','UG','UGA','2009-11-15 02:41:19',NULL),(230,'Ukraine','UA','UKR','2009-11-15 02:41:19',NULL),(231,'United Arab Emirates','AE','ARE','2009-11-15 02:41:19',NULL),(232,'United Kingdom','GB','GBR','2009-11-15 02:41:19',NULL),(233,'United States','US','USA','2009-11-15 02:41:19',NULL),(234,'United States Minor Outlying Islands','UM','UMI','2009-11-15 02:41:19',NULL),(235,'Uruguay','UY','URY','2009-11-15 02:41:19',NULL),(236,'Uzbekistan','UZ','UZB','2009-11-15 02:41:19',NULL),(237,'Vanuatu','VU','VUT','2009-11-15 02:41:19',NULL),(238,'Venezuela, Bolivarian Republic of','VE','VEN','2009-11-15 02:41:19',NULL),(239,'Viet Nam','VN','VNM','2009-11-15 02:41:19',NULL),(240,'Virgin Islands, British','VG','VGB','2009-11-15 02:41:19',NULL),(241,'Virgin Islands, U.S.','VI','VIR','2009-11-15 02:41:19',NULL),(242,'Wallis and Futuna','WF','WLF','2009-11-15 02:41:19',NULL),(243,'Western Sahara','EH','ESH','2009-11-15 02:41:19',NULL),(244,'Yemen','YE','YEM','2009-11-15 02:41:19',NULL),(245,'Zambia','ZM','ZMB','2009-11-15 02:41:19',NULL),(246,'Zimbabwe','ZW','ZWE','2009-11-15 02:41:19',NULL);
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;
   alter table country add column EU_Member enum('false','true') not null default 'false';
   alter table country add column Currency char(3) NOT NULL default 'USD';
   update country set EU_Member='true' where Alpha3='AUT';
   update country set EU_Member='true' where Alpha3='BEL';
   update country set EU_Member='true' where Alpha3='BGR';
   update country set EU_Member='true' where Alpha3='CYP';
   update country set EU_Member='true' where Alpha3='CZE';
   update country set EU_Member='true' where Alpha3='DNK';
   update country set EU_Member='true' where Alpha3='EST';
   update country set EU_Member='true' where Alpha3='FIN';
   update country set EU_Member='true' where Alpha3='FRA';
   update country set EU_Member='true' where Alpha3='DEU';
   update country set EU_Member='true' where Alpha3='GRC';
   update country set EU_Member='true' where Alpha3='HUN';
   update country set EU_Member='true' where Alpha3='IRL';
   update country set EU_Member='true' where Alpha3='ITA';
   update country set EU_Member='true' where Alpha3='LVA';
   update country set EU_Member='true' where Alpha3='LTU';
   update country set EU_Member='true' where Alpha3='LUX';
   update country set EU_Member='true' where Alpha3='MLT';
   update country set EU_Member='true' where Alpha3='NLD';
   update country set EU_Member='true' where Alpha3='POL';
   update country set EU_Member='true' where Alpha3='PRT';
   update country set EU_Member='true' where Alpha3='ROU';
   update country set EU_Member='true' where Alpha3='SVK';
   update country set EU_Member='true' where Alpha3='SVN';
   update country set EU_Member='true' where Alpha3='ESP';
   update country set EU_Member='true' where Alpha3='SWE';
   update country set EU_Member='true' where Alpha3='GBR';
   update country set Currency='EUR' where Alpha3='AUT';
   update country set Currency='EUR' where Alpha3='BEL';
   update country set Currency='BGN' where Alpha3='BGR';
   update country set Currency='EUR' where Alpha3='CYP';
   update country set Currency='CZK' where Alpha3='CZE';
   update country set Currency='DKK' where Alpha3='DNK';
   update country set Currency='EEK' where Alpha3='EST';
   update country set Currency='EUR' where Alpha3='FIN';
   update country set Currency='EUR' where Alpha3='FRA';
   update country set Currency='EUR' where Alpha3='DEU';
   update country set Currency='EUR' where Alpha3='GRC';
   update country set Currency='HUF' where Alpha3='HUN';
   update country set Currency='EUR' where Alpha3='IRL';
   update country set Currency='EUR' where Alpha3='ITA';
   update country set Currency='LVL' where Alpha3='LVA';
   update country set Currency='LTL' where Alpha3='LTU';
   update country set Currency='EUR' where Alpha3='LUX';
   update country set Currency='EUR' where Alpha3='MLT';
   update country set Currency='EUR' where Alpha3='NLD';
   update country set Currency='PLN' where Alpha3='POL';
   update country set Currency='EUR' where Alpha3='PRT';
   update country set Currency='RON' where Alpha3='ROU';
   update country set Currency='SKK' where Alpha3='SVK';
   update country set Currency='EUR' where Alpha3='SVN';
   update country set Currency='EUR' where Alpha3='ESP';
   update country set Currency='SEK' where Alpha3='SWE';
   update country set Currency='GBP' where Alpha3='GBR';
   update country set Currency='AUD' where Alpha3='AUS';
   update country set Currency='BRL' where Alpha3='BRA';
   update country set Currency='CAD' where Alpha3='CAN';
   update country set Currency='CHF' where Alpha3='CHE';
   update country set Currency='CNY' where Alpha3='CHN';
   update country set Currency='HKD' where Alpha3='HKG';
   update country set Currency='INR' where Alpha3='IND';
   update country set Currency='JPY' where Alpha3='JPN';
   update country set Currency='KRW' where Alpha3='KOR';
   update country set Currency='NZD' where Alpha3='NZL';
   update country set Currency='RUB' where Alpha3='RUS';
drop table if exists `website`;
drop table if exists `post`;
create table `post` (
	id int not null auto_increment,
	GUID varchar(255) not null default '',
	user_id int not null,
	Title text not null,
	Content longtext not null,
	parent__post_id int default null,
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	PublishDate datetime default null,
	DeleteDate datetime default null,
	ModifyDate datetime default null,
	primary key (id),
	index (user_id,DeleteDate),
	index (user_id,DeleteDate,PublishDate),
	index (user_id),
	index (guid),
	index (parent__post_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists asset;
create table asset (
	id int not null auto_increment,
	user_id int not null,
	GUID varchar(255) not null default '',
	`Size` bigint not null,
	Name varchar(512) not null,
	ChunkSize bigint not null,
	MimeType varchar(512) not null,
	HashType varchar(10) default null,
	AssetHash varchar(255) default null,
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	UploadCompleteDate datetime default null,
	PublishDate datetime default null,
	DeleteDate datetime default null,
	ModifyDate datetime default null,
	primary key (id),
	index (user_id,DeleteDate),
	index (user_id,DeleteDate,PublishDate),
	index (user_id,UploadCompleteDate),
	index (user_id),
	index (guid),
	index (`user_id`,`Size`,`Name`(100),`UploadCompleteDate`,`ChunkSize`,`MimeType`(30))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists asset_chunk;
create table asset_chunk (
	id int not null auto_increment,
	asset_id int not null,
	Chunk int not null,
	ChunkSize int not null,
	HashType varchar(10) not null,
	ChunkHash varchar(255) not null,
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	primary key (id),
	index (asset_id),
	index (Chunk,HashType,ChunkHash),
	index (HashType,ChunkHash,Chunk,asset_id),
	unique key (`asset_id`,`Chunk`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists asset_chunk_data;
create table asset_chunk_data ( 
	id int not null auto_increment,
	asset_chunk_id int not null,
	Data longblob not null,
	primary key (id),
	index (asset_chunk_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists album;
create table album (
	id int not null auto_increment,
	user_id int not null,
	picture_id int default null,
	GUID varchar(255) not null default '',
	Name varchar(512) not null,
	Description text not null,
	UserVisible enum('false','true') not null default 'false',
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	PublishDate datetime default null,
	DeleteDate datetime default null,
	ModifyDate datetime default null,
	primary key (id),
	index (user_id,DeleteDate),
	index (user_id,DeleteDate,UserVisible),
	index (user_id,DeleteDate,PublishDate),
	index (user_id),
	index (guid),
	index (picture_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists picture;
create table picture (
	id int not null auto_increment,
	album_id int default null,
	GUID varchar(255) not null default '',
	Name varchar(512) NOT NULL,
	Description text not null,
	digital_negative__asset_id int not null,
	web_small__asset_id int default null,
	web_medium__asset_id int default null,
	web_large__asset_id int default null,
	web_fullsize__asset_id int default null,
	thumbnail_fullsize__asset_id int default null,
	thumbnail_large__asset_id int default null,
	thumbnail_small__asset_id int default null,
	square__asset_id int default null,
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	PublishDate datetime default null,
	DeleteDate datetime default null,
	ModifyDate datetime default null,
	primary key (id),
	index (album_id),
	index (DeleteDate,PublishDate,album_id),
	index (guid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists video;
create table video (
	id int not null auto_increment,
	album_id int default null,
	GUID varchar(255) NOT NULL default '',
	Name varchar(512) NOT NULL,
	Description text not null,
	digital_negative__asset_id int not null,
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	PublishDate datetime default null,
	DeleteDate datetime default null,
	ModifyDate datetime default null,
	primary key (id),
	index (album_id),
	index (DeleteDate,PublishDate,album_id),
	index (guid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists audio;
create table audio (
	id int not null auto_increment,
	album_id int default null,
	GUID varchar(255) NOT NULL default '',
	Name varchar(512) NOT NULL,
	Description text not null,
	digital_negative__asset_id int not null,
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	PublishDate datetime default null,
	DeleteDate datetime default null,
	ModifyDate datetime default null,
	primary key (id),
	index (album_id),
	index (DeleteDate,PublishDate,album_id),
	index (guid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists `file`;
create table `file` (
	id int not null auto_increment,
	album_id int default null,
	GUID varchar(255) NOT NULL default '',
	Name varchar(512) NOT NULL,
	Description text not null,
	icon__asset_id int not null,
	MimeType varchar(512) not null,
	digital_negative__asset_id int not null,
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	PublishDate datetime default null,
	DeleteDate datetime default null,
	ModifyDate datetime default null,
	primary key (id),
	index (album_id),
	index (DeleteDate,PublishDate,album_id),
	index (guid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists message;
create table message (
	id int not null auto_increment,
	user_id int not null,
	Message varchar(512) not null,
	Severity enum('Notice','Error','Warning') not null default 'Notice',
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	CompleteDate datetime default null,
	primary key (id),
	index (user_id,CompleteDate),
	index (CreateDate)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists tag;
create table tag (
	id int not null auto_increment,
	user_id int not null,
	Name varchar(512) not null,
	Description text not null,
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	PublishDate datetime default null,
	DeleteDate datetime default null,
	ModifyDate datetime default null,
	primary key (id),
	index (user_id,DeleteDate,PublishDate),
	index (Name(100),DeleteDate,PublishDate),
	index (user_id,Name(100),DeleteDate,PublishDate),
	index (user_id,DeleteDate)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists picture_tag;
create table picture_tag (
	id int not null auto_increment,
	tag_id int not null,
	picture_id int not null,
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	primary key (id),
	index (tag_id),
	index (picture_id),
	unique key (tag_id,picture_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists audio_tag;
create table audio_tag (
	id int not null auto_increment,
	tag_id int not null,
	audio_id int not null,
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	primary key (id),
	index (tag_id),
	index (audio_id),
	unique key (tag_id,audio_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists video_tag;
create table video_tag (
	id int not null auto_increment,
	tag_id int not null,
	video_id int not null,
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	primary key (id),
	index (tag_id),
	index (video_id),
	unique key (tag_id,video_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists file_tag;
create table file_tag (
	id int not null auto_increment,
	tag_id int not null,
	file_id int not null,
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	primary key (id),
	index (tag_id),
	index (file_id),
	unique key (tag_id,file_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists post_tag;
create table post_tag (
	id int not null auto_increment,
	tag_id int not null,
	post_id int not null,
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	primary key (id),
	index (tag_id),
	index (post_id),
	unique key (tag_id,post_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists `page`;
create table `page` (
	id int not null auto_increment,
	GUID varchar(255) not null default '',
	user_id int not null,
	page_cache_id int default null,
	Title text not null,
	TemplateIdentifier varchar(255) NOT NULL default 'default',
	LayoutIdentifier varchar(255) NOT NULL default '1col',
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	PublishDate datetime default null,
	DeleteDate datetime default null,
	ModifyDate datetime default null,
	primary key (id),
	index (user_id,DeleteDate),
	index (user_id,DeleteDate,PublishDate),
	index (user_id),
	index (guid),
	index (page_cache_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists `page_uri`;
create table `page_uri` (
	id int not null auto_increment,
	user_id int not null,
	page_id int not null,
	URITag varchar(2048) not null,
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	ModifyDate datetime default null,
	primary key (id),
	index (page_id),
	index (user_id,URITag(256)),
	index (user_id),
	index (URITag(256))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
insert into page set user_id=1, GUID='home',Title='Home';
drop table if exists `uri_forward`;
create table `uri_forward` (
	id int not null auto_increment,
	user_id int not null,
	URITag varchar(2048) not null,
	DestinationURI varchar(2048) not null,
	Code enum('301','302','303','307','308') not null default '301',
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	ModifyDate datetime default null,
	primary key (id),
	index (user_id),
	index (user_id,URITag(256)),
	index (user_id,DestinationURI(256))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists `plugin_uri`;
create table `plugin_uri` (
	id int not null auto_increment,
	user_id int not null,
	URITag varchar(2048) not null,
	PluginIdentifier varchar(512) not null,
	PluginController varchar(512) not null,
	Code varchar(255) not null,
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	ModifyDate datetime default null,
	primary key (id),
	index (user_id),
	index (user_id,URITag(256))	
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
insert into `plugin_uri` set user_id=1, URITag='slinq/blog', PluginIdentifier='blog-plugin', PluginController='Main';
insert into `plugin_uri` set user_id=1, URITag='slinq/gallery', PluginIdentifier='gallery-plugin', PluginController='Main';
drop view if exists `plugin_uri_cache`;
create view `plugin_uri_cache` as select group_concat(concat(PluginIdentifier,',',PluginController,' ',URITag) separator '\n') as PluginURIs, user_id from plugin_uri group by user_id;
drop table if exists `plugin_uri_forward`;
create table `plugin_uri_forward` (
	id int not null auto_increment,
	user_id int not null,
	URITag varchar(2048) not null,
	DestinationURI varchar(2048) not null,
	Code enum('301','302','303','307','308') not null default '301',
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	ModifyDate datetime default null,
	primary key (id),
	index (user_id),
	index (user_id,URITag(256)),
	index (user_id,DestinationURI(256))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
insert into `plugin_uri_forward` set user_id=1, URITag='slinq/oldblog', DestinationURI='blog', Code='301';
drop view if exists `plugin_uri_forward_cache`;
create view `plugin_uri_forward_cache` as select group_concat(concat(Code,' ',DestinationURI,' ',URITag) separator '\n') as PluginURIForwards, user_id from plugin_uri_forward group by user_id;
drop table if exists `page_cache`;
create table `page_cache` ( 
	id int not null auto_increment,
	Content longblob,
	CreateDate timestamp not null default CURRENT_TIMESTAMP,
	HashType varchar(10) default null,
	CacheHash varchar(255) default null,
	`Size` bigint not null,
	primary key (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists `user_plugin`;
create table `user_plugin` (
	id int not null auto_increment,
	user_id int not null,
	Identifier varchar(255),
	primary key (id),
	index (user_id),
	unique key (user_id,Identifier)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
insert into user_plugin set user_id=1, Identifier='esqoo-picture-navigator';
insert into user_plugin set user_id=1, Identifier='esqoo-picture-toolbar';
drop view if exists user_plugin_cache_list;
# need to set http://dev.mysql.com/doc/refman/5.0/en/server-system-variables.html#sysvar_group_concat_max_len to a higher value when we connect to MySQL
create view user_plugin_cache_list as select user_id,group_concat(Identifier) as IdentifierList from user_plugin group by user_id;
