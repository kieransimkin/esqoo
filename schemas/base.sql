SET character_set_client = utf8;
drop table if exists `user`;
create table `user` ( 
	id int not null auto_increment,
	username varchar(255) default null,
	email varchar(512) not null,
	password varchar(512) not null,
	firstname varchar(512) default null,
	lastname varchar(512) default null,
	addr1 varchar(512) default null,
	addr2 varchar(512) default null,
	town varchar(512) default null,
	county varchar(512) default null,
	country_id int default null,
	createdate TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
	modifydate datetime default null,
	deletedate datetime default null,
	primary key (id),
	unique key (username),
	index (country_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
insert into user set username='slinq',email='kieran@slinq.com',password='testpass',firstname='Kieran',lastname='Simkin';
drop table if exists `user_challenge`;
create table `user_challenge` ( 
	id int not null auto_increment,
	user_id int not null,
	challenge varchar(64) NOT NULL,
	createdate TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
	primary key (id),
	index (user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists `user_token`;
create table `user_token` (
	id int not null auto_increment,
	user_id int not null,
	token varchar(255) not null,
	createdate TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,
	deletedate datetime default null,
	expires enum('false','true') not null default 'false',
	primary key (id),
	index (user_id),
	index (deletedate,user_id,token)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
drop table if exists `group`;
create table `group` (
	id int not null auto_increment,
	tag varchar(255) NOT NULL,
	description text not null,
	primary key (id),
	unique key (tag)
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
  `name` varchar(256) NOT NULL,
  `alpha2` char(2) NOT NULL,
  `alpha3` char(3) NOT NULL,
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modifydate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alpha2` (`alpha2`),
  UNIQUE KEY `alpha3` (`alpha3`)
) ENGINE=MyISAM AUTO_INCREMENT=247 DEFAULT CHARSET=utf8;
LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
INSERT INTO `country` VALUES (1,'Afghanistan','AF','AFG','2009-11-15 02:41:18',NULL),(2,'Ãland Islands','AX','ALA','2009-11-15 02:41:18',NULL),(3,'Albania','AL','ALB','2009-11-15 02:41:18',NULL),(4,'Algeria','DZ','DZA','2009-11-15 02:41:18',NULL),(5,'American Samoa','AS','ASM','2009-11-15 02:41:18',NULL),(6,'Andorra','AD','AND','2009-11-15 02:41:18',NULL),(7,'Angola','AO','AGO','2009-11-15 02:41:18',NULL),(8,'Anguilla','AI','AIA','2009-11-15 02:41:18',NULL),(9,'Antarctica','AQ','ATA','2009-11-15 02:41:18',NULL),(10,'Antigua and Barbuda','AG','ATG','2009-11-15 02:41:18',NULL),(11,'Argentina','AR','ARG','2009-11-15 02:41:18',NULL),(12,'Armenia','AM','ARM','2009-11-15 02:41:18',NULL),(13,'Aruba','AW','ABW','2009-11-15 02:41:18',NULL),(14,'Australia','AU','AUS','2009-11-15 02:41:18',NULL),(15,'Austria','AT','AUT','2009-11-15 02:41:18',NULL),(16,'Azerbaijan','AZ','AZE','2009-11-15 02:41:18',NULL),(17,'Bahamas','BS','BHS','2009-11-15 02:41:18',NULL),(18,'Bahrain','BH','BHR','2009-11-15 02:41:18',NULL),(19,'Bangladesh','BD','BGD','2009-11-15 02:41:18',NULL),(20,'Barbados','BB','BRB','2009-11-15 02:41:18',NULL),(21,'Belarus','BY','BLR','2009-11-15 02:41:18',NULL),(22,'Belgium','BE','BEL','2009-11-15 02:41:18',NULL),(23,'Belize','BZ','BLZ','2009-11-15 02:41:18',NULL),(24,'Benin','BJ','BEN','2009-11-15 02:41:18',NULL),(25,'Bermuda','BM','BMU','2009-11-15 02:41:18',NULL),(26,'Bhutan','BT','BTN','2009-11-15 02:41:18',NULL),(27,'Bolivia, Plurinational State of','BO','BOL','2009-11-15 02:41:18',NULL),(28,'Bosnia and Herzegovina','BA','BIH','2009-11-15 02:41:18',NULL),(29,'Botswana','BW','BWA','2009-11-15 02:41:18',NULL),(30,'Bouvet Island','BV','BVT','2009-11-15 02:41:18',NULL),(31,'Brazil','BR','BRA','2009-11-15 02:41:18',NULL),(32,'British Indian Ocean Territory','IO','IOT','2009-11-15 02:41:18',NULL),(33,'Brunei Darussalam','BN','BRN','2009-11-15 02:41:18',NULL),(34,'Bulgaria','BG','BGR','2009-11-15 02:41:18',NULL),(35,'Burkina Faso','BF','BFA','2009-11-15 02:41:18',NULL),(36,'Burundi','BI','BDI','2009-11-15 02:41:18',NULL),(37,'Cambodia','KH','KHM','2009-11-15 02:41:19',NULL),(38,'Cameroon','CM','CMR','2009-11-15 02:41:19',NULL),(39,'Canada','CA','CAN','2009-11-15 02:41:19',NULL),(40,'Cape Verde','CV','CPV','2009-11-15 02:41:19',NULL),(41,'Cayman Islands','KY','CYM','2009-11-15 02:41:19',NULL),(42,'Central African Republic','CF','CAF','2009-11-15 02:41:19',NULL),(43,'Chad','TD','TCD','2009-11-15 02:41:19',NULL),(44,'Chile','CL','CHL','2009-11-15 02:41:19',NULL),(45,'China','CN','CHN','2009-11-15 02:41:19',NULL),(46,'Christmas Island','CX','CXR','2009-11-15 02:41:19',NULL),(47,'Cocos (Keeling) Islands','CC','CCK','2009-11-15 02:41:19',NULL),(48,'Colombia','CO','COL','2009-11-15 02:41:19',NULL),(49,'Comoros','KM','COM','2009-11-15 02:41:19',NULL),(50,'Congo','CG','COG','2009-11-15 02:41:19',NULL),(51,'Congo, the Democratic Republic of the','CD','COD','2009-11-15 02:41:19',NULL),(52,'Cook Islands','CK','COK','2009-11-15 02:41:19',NULL),(53,'Costa Rica','CR','CRI','2009-11-15 02:41:19',NULL),(54,'CÃ´te d\'Ivoire','CI','CIV','2009-11-15 02:41:19',NULL),(55,'Croatia','HR','HRV','2009-11-15 02:41:19',NULL),(56,'Cuba','CU','CUB','2009-11-15 02:41:19',NULL),(57,'Cyprus','CY','CYP','2009-11-15 02:41:19',NULL),(58,'Czech Republic','CZ','CZE','2009-11-15 02:41:19',NULL),(59,'Denmark','DK','DNK','2009-11-15 02:41:19',NULL),(60,'Djibouti','DJ','DJI','2009-11-15 02:41:19',NULL),(61,'Dominica','DM','DMA','2009-11-15 02:41:19',NULL),(62,'Dominican Republic','DO','DOM','2009-11-15 02:41:19',NULL),(63,'Ecuador','EC','ECU','2009-11-15 02:41:19',NULL),(64,'Egypt','EG','EGY','2009-11-15 02:41:19',NULL),(65,'El Salvador','SV','SLV','2009-11-15 02:41:19',NULL),(66,'Equatorial Guinea','GQ','GNQ','2009-11-15 02:41:19',NULL),(67,'Eritrea','ER','ERI','2009-11-15 02:41:19',NULL),(68,'Estonia','EE','EST','2009-11-15 02:41:19',NULL),(69,'Ethiopia','ET','ETH','2009-11-15 02:41:19',NULL),(70,'Falkland Islands (Malvinas)','FK','FLK','2009-11-15 02:41:19',NULL),(71,'Faroe Islands','FO','FRO','2009-11-15 02:41:19',NULL),(72,'Fiji','FJ','FJI','2009-11-15 02:41:19',NULL),(73,'Finland','FI','FIN','2009-11-15 02:41:19',NULL),(74,'France','FR','FRA','2009-11-15 02:41:19',NULL),(75,'French Guiana','GF','GUF','2009-11-15 02:41:19',NULL),(76,'French Polynesia','PF','PYF','2009-11-15 02:41:19',NULL),(77,'French Southern Territories','TF','ATF','2009-11-15 02:41:19',NULL),(78,'Gabon','GA','GAB','2009-11-15 02:41:19',NULL),(79,'Gambia','GM','GMB','2009-11-15 02:41:19',NULL),(80,'Georgia','GE','GEO','2009-11-15 02:41:19',NULL),(81,'Germany','DE','DEU','2009-11-15 02:41:19',NULL),(82,'Ghana','GH','GHA','2009-11-15 02:41:19',NULL),(83,'Gibraltar','GI','GIB','2009-11-15 02:41:19',NULL),(84,'Greece','GR','GRC','2009-11-15 02:41:19',NULL),(85,'Greenland','GL','GRL','2009-11-15 02:41:19',NULL),(86,'Grenada','GD','GRD','2009-11-15 02:41:19',NULL),(87,'Guadeloupe','GP','GLP','2009-11-15 02:41:19',NULL),(88,'Guam','GU','GUM','2009-11-15 02:41:19',NULL),(89,'Guatemala','GT','GTM','2009-11-15 02:41:19',NULL),(90,'Guernsey','GG','GGY','2009-11-15 02:41:19',NULL),(91,'Guinea','GN','GIN','2009-11-15 02:41:19',NULL),(92,'Guinea-Bissau','GW','GNB','2009-11-15 02:41:19',NULL),(93,'Guyana','GY','GUY','2009-11-15 02:41:19',NULL),(94,'Haiti','HT','HTI','2009-11-15 02:41:19',NULL),(95,'Heard Island and McDonald Islands','HM','HMD','2009-11-15 02:41:19',NULL),(96,'Holy See (Vatican City State)','VA','VAT','2009-11-15 02:41:19',NULL),(97,'Honduras','HN','HND','2009-11-15 02:41:19',NULL),(98,'Hong Kong','HK','HKG','2009-11-15 02:41:19',NULL),(99,'Hungary','HU','HUN','2009-11-15 02:41:19',NULL),(100,'Iceland','IS','ISL','2009-11-15 02:41:19',NULL),(101,'India','IN','IND','2009-11-15 02:41:19',NULL),(102,'Indonesia','ID','IDN','2009-11-15 02:41:19',NULL),(103,'Iran, Islamic Republic of','IR','IRN','2009-11-15 02:41:19',NULL),(104,'Iraq','IQ','IRQ','2009-11-15 02:41:19',NULL),(105,'Ireland','IE','IRL','2009-11-15 02:41:19',NULL),(106,'Isle of Man','IM','IMN','2009-11-15 02:41:19',NULL),(107,'Israel','IL','ISR','2009-11-15 02:41:19',NULL),(108,'Italy','IT','ITA','2009-11-15 02:41:19',NULL),(109,'Jamaica','JM','JAM','2009-11-15 02:41:19',NULL),(110,'Japan','JP','JPN','2009-11-15 02:41:19',NULL),(111,'Jersey','JE','JEY','2009-11-15 02:41:19',NULL),(112,'Jordan','JO','JOR','2009-11-15 02:41:19',NULL),(113,'Kazakhstan','KZ','KAZ','2009-11-15 02:41:19',NULL),(114,'Kenya','KE','KEN','2009-11-15 02:41:19',NULL),(115,'Kiribati','KI','KIR','2009-11-15 02:41:19',NULL),(116,'Korea, Democratic People\'s Republic of','KP','PRK','2009-11-15 02:41:19',NULL),(117,'Korea, Republic of','KR','KOR','2009-11-15 02:41:19',NULL),(118,'Kuwait','KW','KWT','2009-11-15 02:41:19',NULL),(119,'Kyrgyzstan','KG','KGZ','2009-11-15 02:41:19',NULL),(120,'Lao People\'s Democratic Republic','LA','LAO','2009-11-15 02:41:19',NULL),(121,'Latvia','LV','LVA','2009-11-15 02:41:19',NULL),(122,'Lebanon','LB','LBN','2009-11-15 02:41:19',NULL),(123,'Lesotho','LS','LSO','2009-11-15 02:41:19',NULL),(124,'Liberia','LR','LBR','2009-11-15 02:41:19',NULL),(125,'Libyan Arab Jamahiriya','LY','LBY','2009-11-15 02:41:19',NULL),(126,'Liechtenstein','LI','LIE','2009-11-15 02:41:19',NULL),(127,'Lithuania','LT','LTU','2009-11-15 02:41:19',NULL),(128,'Luxembourg','LU','LUX','2009-11-15 02:41:19',NULL),(129,'Macao','MO','MAC','2009-11-15 02:41:19',NULL),(130,'Macedonia, the former Yugoslav Republic of','MK','MKD','2009-11-15 02:41:19',NULL),(131,'Madagascar','MG','MDG','2009-11-15 02:41:19',NULL),(132,'Malawi','MW','MWI','2009-11-15 02:41:19',NULL),(133,'Malaysia','MY','MYS','2009-11-15 02:41:19',NULL),(134,'Maldives','MV','MDV','2009-11-15 02:41:19',NULL),(135,'Mali','ML','MLI','2009-11-15 02:41:19',NULL),(136,'Malta','MT','MLT','2009-11-15 02:41:19',NULL),(137,'Marshall Islands','MH','MHL','2009-11-15 02:41:19',NULL),(138,'Martinique','MQ','MTQ','2009-11-15 02:41:19',NULL),(139,'Mauritania','MR','MRT','2009-11-15 02:41:19',NULL),(140,'Mauritius','MU','MUS','2009-11-15 02:41:19',NULL),(141,'Mayotte','YT','MYT','2009-11-15 02:41:19',NULL),(142,'Mexico','MX','MEX','2009-11-15 02:41:19',NULL),(143,'Micronesia, Federated States of','FM','FSM','2009-11-15 02:41:19',NULL),(144,'Moldova, Republic of','MD','MDA','2009-11-15 02:41:19',NULL),(145,'Monaco','MC','MCO','2009-11-15 02:41:19',NULL),(146,'Mongolia','MN','MNG','2009-11-15 02:41:19',NULL),(147,'Montenegro','ME','MNE','2009-11-15 02:41:19',NULL),(148,'Montserrat','MS','MSR','2009-11-15 02:41:19',NULL),(149,'Morocco','MA','MAR','2009-11-15 02:41:19',NULL),(150,'Mozambique','MZ','MOZ','2009-11-15 02:41:19',NULL),(151,'Myanmar','MM','MMR','2009-11-15 02:41:19',NULL),(152,'Namibia','NA','NAM','2009-11-15 02:41:19',NULL),(153,'Nauru','NR','NRU','2009-11-15 02:41:19',NULL),(154,'Nepal','NP','NPL','2009-11-15 02:41:19',NULL),(155,'Netherlands','NL','NLD','2009-11-15 02:41:19',NULL),(156,'Netherlands Antilles','AN','ANT','2009-11-15 02:41:19',NULL),(157,'New Caledonia','NC','NCL','2009-11-15 02:41:19',NULL),(158,'New Zealand','NZ','NZL','2009-11-15 02:41:19',NULL),(159,'Nicaragua','NI','NIC','2009-11-15 02:41:19',NULL),(160,'Niger','NE','NER','2009-11-15 02:41:19',NULL),(161,'Nigeria','NG','NGA','2009-11-15 02:41:19',NULL),(162,'Niue','NU','NIU','2009-11-15 02:41:19',NULL),(163,'Norfolk Island','NF','NFK','2009-11-15 02:41:19',NULL),(164,'Northern Mariana Islands','MP','MNP','2009-11-15 02:41:19',NULL),(165,'Norway','NO','NOR','2009-11-15 02:41:19',NULL),(166,'Oman','OM','OMN','2009-11-15 02:41:19',NULL),(167,'Pakistan','PK','PAK','2009-11-15 02:41:19',NULL),(168,'Palau','PW','PLW','2009-11-15 02:41:19',NULL),(169,'Palestinian Territory, Occupied','PS','PSE','2009-11-15 02:41:19',NULL),(170,'Panama','PA','PAN','2009-11-15 02:41:19',NULL),(171,'Papua New Guinea','PG','PNG','2009-11-15 02:41:19',NULL),(172,'Paraguay','PY','PRY','2009-11-15 02:41:19',NULL),(173,'Peru','PE','PER','2009-11-15 02:41:19',NULL),(174,'Philippines','PH','PHL','2009-11-15 02:41:19',NULL),(175,'Pitcairn','PN','PCN','2009-11-15 02:41:19',NULL),(176,'Poland','PL','POL','2009-11-15 02:41:19',NULL),(177,'Portugal','PT','PRT','2009-11-15 02:41:19',NULL),(178,'Puerto Rico','PR','PRI','2009-11-15 02:41:19',NULL),(179,'Qatar','QA','QAT','2009-11-15 02:41:19',NULL),(180,'RÃ©union','RE','REU','2009-11-15 02:41:19',NULL),(181,'Romania','RO','ROU','2009-11-15 02:41:19',NULL),(182,'Russian Federation','RU','RUS','2009-11-15 02:41:19',NULL),(183,'Rwanda','RW','RWA','2009-11-15 02:41:19',NULL),(184,'Saint BarthÃ©lemy','BL','BLM','2009-11-15 02:41:19',NULL),(185,'Saint Helena','SH','SHN','2009-11-15 02:41:19',NULL),(186,'Saint Kitts and Nevis','KN','KNA','2009-11-15 02:41:19',NULL),(187,'Saint Lucia','LC','LCA','2009-11-15 02:41:19',NULL),(188,'Saint Martin (French part)','MF','MAF','2009-11-15 02:41:19',NULL),(189,'Saint Pierre and Miquelon','PM','SPM','2009-11-15 02:41:19',NULL),(190,'Saint Vincent and the Grenadines','VC','VCT','2009-11-15 02:41:19',NULL),(191,'Samoa','WS','WSM','2009-11-15 02:41:19',NULL),(192,'San Marino','SM','SMR','2009-11-15 02:41:19',NULL),(193,'Sao Tome and Principe','ST','STP','2009-11-15 02:41:19',NULL),(194,'Saudi Arabia','SA','SAU','2009-11-15 02:41:19',NULL),(195,'Senegal','SN','SEN','2009-11-15 02:41:19',NULL),(196,'Serbia','RS','SRB','2009-11-15 02:41:19',NULL),(197,'Seychelles','SC','SYC','2009-11-15 02:41:19',NULL),(198,'Sierra Leone','SL','SLE','2009-11-15 02:41:19',NULL),(199,'Singapore','SG','SGP','2009-11-15 02:41:19',NULL),(200,'Slovakia','SK','SVK','2009-11-15 02:41:19',NULL),(201,'Slovenia','SI','SVN','2009-11-15 02:41:19',NULL),(202,'Solomon Islands','SB','SLB','2009-11-15 02:41:19',NULL),(203,'Somalia','SO','SOM','2009-11-15 02:41:19',NULL),(204,'South Africa','ZA','ZAF','2009-11-15 02:41:19',NULL),(205,'South Georgia and the South Sandwich Islands','GS','SGS','2009-11-15 02:41:19',NULL),(206,'Spain','ES','ESP','2009-11-15 02:41:19',NULL),(207,'Sri Lanka','LK','LKA','2009-11-15 02:41:19',NULL),(208,'Sudan','SD','SDN','2009-11-15 02:41:19',NULL),(209,'Suriname','SR','SUR','2009-11-15 02:41:19',NULL),(210,'Svalbard and Jan Mayen','SJ','SJM','2009-11-15 02:41:19',NULL),(211,'Swaziland','SZ','SWZ','2009-11-15 02:41:19',NULL),(212,'Sweden','SE','SWE','2009-11-15 02:41:19',NULL),(213,'Switzerland','CH','CHE','2009-11-15 02:41:19',NULL),(214,'Syrian Arab Republic','SY','SYR','2009-11-15 02:41:19',NULL),(215,'Taiwan, Province of China','TW','TWN','2009-11-15 02:41:19',NULL),(216,'Tajikistan','TJ','TJK','2009-11-15 02:41:19',NULL),(217,'Tanzania, United Republic of','TZ','TZA','2009-11-15 02:41:19',NULL),(218,'Thailand','TH','THA','2009-11-15 02:41:19',NULL),(219,'Timor-Leste','TL','TLS','2009-11-15 02:41:19',NULL),(220,'Togo','TG','TGO','2009-11-15 02:41:19',NULL),(221,'Tokelau','TK','TKL','2009-11-15 02:41:19',NULL),(222,'Tonga','TO','TON','2009-11-15 02:41:19',NULL),(223,'Trinidad and Tobago','TT','TTO','2009-11-15 02:41:19',NULL),(224,'Tunisia','TN','TUN','2009-11-15 02:41:19',NULL),(225,'Turkey','TR','TUR','2009-11-15 02:41:19',NULL),(226,'Turkmenistan','TM','TKM','2009-11-15 02:41:19',NULL),(227,'Turks and Caicos Islands','TC','TCA','2009-11-15 02:41:19',NULL),(228,'Tuvalu','TV','TUV','2009-11-15 02:41:19',NULL),(229,'Uganda','UG','UGA','2009-11-15 02:41:19',NULL),(230,'Ukraine','UA','UKR','2009-11-15 02:41:19',NULL),(231,'United Arab Emirates','AE','ARE','2009-11-15 02:41:19',NULL),(232,'United Kingdom','GB','GBR','2009-11-15 02:41:19',NULL),(233,'United States','US','USA','2009-11-15 02:41:19',NULL),(234,'United States Minor Outlying Islands','UM','UMI','2009-11-15 02:41:19',NULL),(235,'Uruguay','UY','URY','2009-11-15 02:41:19',NULL),(236,'Uzbekistan','UZ','UZB','2009-11-15 02:41:19',NULL),(237,'Vanuatu','VU','VUT','2009-11-15 02:41:19',NULL),(238,'Venezuela, Bolivarian Republic of','VE','VEN','2009-11-15 02:41:19',NULL),(239,'Viet Nam','VN','VNM','2009-11-15 02:41:19',NULL),(240,'Virgin Islands, British','VG','VGB','2009-11-15 02:41:19',NULL),(241,'Virgin Islands, U.S.','VI','VIR','2009-11-15 02:41:19',NULL),(242,'Wallis and Futuna','WF','WLF','2009-11-15 02:41:19',NULL),(243,'Western Sahara','EH','ESH','2009-11-15 02:41:19',NULL),(244,'Yemen','YE','YEM','2009-11-15 02:41:19',NULL),(245,'Zambia','ZM','ZMB','2009-11-15 02:41:19',NULL),(246,'Zimbabwe','ZW','ZWE','2009-11-15 02:41:19',NULL);
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;
   alter table country add column eu_member enum('false','true') not null default 'false';
   alter table country add column currency char(3) NOT NULL default 'USD';
   update country set eu_member='true' where alpha3='AUT';
   update country set eu_member='true' where alpha3='BEL';
   update country set eu_member='true' where alpha3='BGR';
   update country set eu_member='true' where alpha3='CYP';
   update country set eu_member='true' where alpha3='CZE';
   update country set eu_member='true' where alpha3='DNK';
   update country set eu_member='true' where alpha3='EST';
   update country set eu_member='true' where alpha3='FIN';
   update country set eu_member='true' where alpha3='FRA';
   update country set eu_member='true' where alpha3='DEU';
   update country set eu_member='true' where alpha3='GRC';
   update country set eu_member='true' where alpha3='HUN';
   update country set eu_member='true' where alpha3='IRL';
   update country set eu_member='true' where alpha3='ITA';
   update country set eu_member='true' where alpha3='LVA';
   update country set eu_member='true' where alpha3='LTU';
   update country set eu_member='true' where alpha3='LUX';
   update country set eu_member='true' where alpha3='MLT';
   update country set eu_member='true' where alpha3='NLD';
   update country set eu_member='true' where alpha3='POL';
   update country set eu_member='true' where alpha3='PRT';
   update country set eu_member='true' where alpha3='ROU';
   update country set eu_member='true' where alpha3='SVK';
   update country set eu_member='true' where alpha3='SVN';
   update country set eu_member='true' where alpha3='ESP';
   update country set eu_member='true' where alpha3='SWE';
   update country set eu_member='true' where alpha3='GBR';
   update country set currency='EUR' where alpha3='AUT';
   update country set currency='EUR' where alpha3='BEL';
   update country set currency='BGN' where alpha3='BGR';
   update country set currency='EUR' where alpha3='CYP';
   update country set currency='CZK' where alpha3='CZE';
   update country set currency='DKK' where alpha3='DNK';
   update country set currency='EEK' where alpha3='EST';
   update country set currency='EUR' where alpha3='FIN';
   update country set currency='EUR' where alpha3='FRA';
   update country set currency='EUR' where alpha3='DEU';
   update country set currency='EUR' where alpha3='GRC';
   update country set currency='HUF' where alpha3='HUN';
   update country set currency='EUR' where alpha3='IRL';
   update country set currency='EUR' where alpha3='ITA';
   update country set currency='LVL' where alpha3='LVA';
   update country set currency='LTL' where alpha3='LTU';
   update country set currency='EUR' where alpha3='LUX';
   update country set currency='EUR' where alpha3='MLT';
   update country set currency='EUR' where alpha3='NLD';
   update country set currency='PLN' where alpha3='POL';
   update country set currency='EUR' where alpha3='PRT';
   update country set currency='RON' where alpha3='ROU';
   update country set currency='SKK' where alpha3='SVK';
   update country set currency='EUR' where alpha3='SVN';
   update country set currency='EUR' where alpha3='ESP';
   update country set currency='SEK' where alpha3='SWE';
   update country set currency='GBP' where alpha3='GBR';
   update country set currency='AUD' where alpha3='AUS';
   update country set currency='BRL' where alpha3='BRA';
   update country set currency='CAD' where alpha3='CAN';
   update country set currency='CHF' where alpha3='CHE';
   update country set currency='CNY' where alpha3='CHN';
   update country set currency='HKD' where alpha3='HKG';
   update country set currency='INR' where alpha3='IND';
   update country set currency='JPY' where alpha3='JPN';
   update country set currency='KRW' where alpha3='KOR';
   update country set currency='NZD' where alpha3='NZL';
   update country set currency='RUB' where alpha3='RUS';

