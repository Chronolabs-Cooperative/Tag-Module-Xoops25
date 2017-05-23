
-- --------------------------------------------------------

-- 
-- Table structure for table `categories`
-- 

CREATE TABLE `tag_categories` (
  `tag_catid`           int(10)             	unsigned NOT NULL auto_increment,
  `tag_parent_catid`    int(10)             	unsigned NOT NULL default '0',
  `tag_term`            varchar(128) 		NOT NULL default '',
  `tag_status`          tinyint(1)             	unsigned NOT NULL default '0',
  `tag_count`           int(10)             	unsigned NOT NULL default '0',
  
  PRIMARY KEY           (`tag_catid`),
  KEY `tag_term`        (`tag_term`(13)),
  KEY `tag_link`        (`tag_catid`,`tag_parent_catid`,`tag_status`), 
  KEY `tag_count`       (`tag_count`)
) ENGINE=InnoDB;


-- --------------------------------------------------------

-- 
-- Table structure for table `categories_link`
-- 

CREATE TABLE `tag_categories_link` (
  `cl_id`               int(10)             	unsigned NOT NULL auto_increment,
  `tag_catid`           int(10)             	unsigned NOT NULL default '0',
  `tag_modcatid`        int(24)             	unsigned NOT NULL default '0',
  `tag_parent_mcid`     int(24)             	unsigned NOT NULL default '0',
  `tag_modid`           smallint(5)         	unsigned NOT NULL default '0',
  `tag_time`            int(10)             	unsigned NOT NULL default '0',
  `tag_count`           int(10)             	unsigned NOT NULL default '0',
  
  PRIMARY KEY           (`cl_id`),
  KEY `tag_time`        (`tag_time`),
  KEY `tag_link`        (`tag_parent_mcid`,`tag_modcatid`, `tag_modid`, `tag_catid`)
) ENGINE=InnoDB;
