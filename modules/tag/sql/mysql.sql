
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


-- --------------------------------------------------------

-- 
-- Table structure for table `tag`
-- 

CREATE TABLE `tag_tag` (
  `tag_id`              int(10)             	unsigned NOT NULL auto_increment,
  `tag_term`            varchar(64) 		NOT NULL default '',
  `tag_status`          tinyint(1)             	unsigned NOT NULL default '0',
  `tag_count`           int(10)             	unsigned NOT NULL default '0',
  
  PRIMARY KEY           (`tag_id`),
  KEY `tag_term`        (`tag_term`),
  KEY `tag_status`      (`tag_status`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `link`
-- 

CREATE TABLE `tag_link` (
  `tl_id`               int(10)			unsigned NOT NULL auto_increment,
  `tag_id`              int(10)             	unsigned NOT NULL default '0',
  `tag_modid`           smallint(5)         	unsigned NOT NULL default '0',
  `tag_catid`           int(10)             	unsigned NOT NULL default '0',
  `tag_itemid`          int(10)             	unsigned NOT NULL default '0',
  `tag_time`            int(10)             	unsigned NOT NULL default '0',
  
  PRIMARY KEY           (`tl_id`),
  KEY `tag_id`          (`tag_id`),
  KEY `tag_time`        (`tag_time`),
  KEY `tag_item`        (`tag_modid`, `tag_catid`, `tag_itemid`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `stats`
-- 

CREATE TABLE `tag_stats` (
  `ts_id`                int(10)             	unsigned NOT NULL auto_increment,
  `tag_id`               int(10)             	unsigned NOT NULL default '0',
  `tag_modid`            smallint(5)         	unsigned NOT NULL default '0',
  `tag_catid`            int(10)             	unsigned NOT NULL default '0',
  `tag_count`            int(10)             	unsigned NOT NULL default '0',
  
  PRIMARY KEY            (`ts_id`),
  KEY `tag_id`           (`tag_id`),
  KEY `tag_modid`        (`tag_modid`),
  KEY `tag_count`        (`tag_count`)
) ENGINE=InnoDB;

