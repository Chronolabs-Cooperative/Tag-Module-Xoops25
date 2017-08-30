<?php
/**
 * Tag management for XOOPS
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		1.00
 * @version		$Id: modinfo.php 10055 2012-08-11 12:46:10Z beckmi $
 * @package		tag
 */

if (!defined('XOOPS_ROOT_PATH')) { exit(); }

// Module definition headers for xoops_version.php
define('TAG_MI_NAME','Tags');
define('TAG_MI_VERSION','3.05');
define('TAG_MI_RELEASEDATE','2017/08/30');
define('TAG_MI_STATUS','final minor');
define('TAG_MI_DESCRIPTION','Tags for XOOPS');
define('TAG_MI_CREDITS','This module was original developed by XOOPS China (phppp)!');
define('TAG_MI_AUTHORALIAS','wishcraft');
define('TAG_MI_HELP','page=help');
define('TAG_MI_LICENCE','gpl3');
define('TAG_MI_ACADEMIC',true);
define('TAG_MI_OFFICAL',true);
define('TAG_MI_ICON','images/mlogo.png');
define('TAG_MI_ADMINMODDIR','/Frameworks/moduleclasses/moduleadmin');
define('TAG_MI_ADMINICON16','../../Frameworks/moduleclasses/icons/16');
define('TAG_MI_ADMINICON32','../../Frameworks/moduleclasses/icons/32');
define('TAG_MI_AUTHORREALNAME','Simon Antony Roberts');
define('TAG_MI_AUTHORWEBSITE','https://internetfounder.wordpress.com');
define('TAG_MI_AUTHORSITENAME','Exhumations from the desks of Chronographics');
define('TAG_MI_AUTHOREMAIL','simon@snails.email');
define('TAG_MI_AUTHORWORD','');
define('TAG_MI_AUTHORFEED','https://internetfounder.wordpress.com/feed/');
define('TAG_MI_WARNINGS_INSTALL','This module requires customisation to other modules as well as associated plugins to be installed!');
define('TAG_MI_WARNINGS_UPDATE','');
define('TAG_MI_WARNINGS_UNINSTALL','');
define('TAG_MI_DEMO_SITEURL','');
define('TAG_MI_DEMO_SITENAME','');
define('TAG_MI_SUPPORT_SITEURL','');
define('TAG_MI_SUPPORT_SITENAME','');
define('TAG_MI_SUPPORT_FEATUREREQUEST','');
define('TAG_MI_SUPPORT_BUGREPORTING','');

// Options
define('TAG_MI_HASSEARCH',true);
define('TAG_MI_HASMAIN',true);
define('TAG_MI_HASADMIN',true);
define('TAG_MI_HASCOMMENTS',false);
define('TAG_MI_HASFEEDS',true);

// Configguration Categories
define('TAG_MI_CONFCAT_SEO','Search Engine Optimization');
define('TAG_MI_CONFCAT_SEO_DESC','');
define('TAG_MI_CONFCAT_MODULE','Tag Module Settings');
define('TAG_MI_CONFCAT_DESC','');

// Configuration Descriptions and Titles
define('TAG_MI_HTACCESS','.htaccess SEO URL');
define('TAG_MI_HTACCESS_DESC','');
define('TAG_MI_BASE','Base .htaccess path');
define('TAG_MI_BASE_DESC','');
define('TAG_MI_HTML','Extension for HTML output with SEO URL');
define('TAG_MI_HTML_DESC','');
define('TAG_MI_RSS','Extension for RSS output with SEO URL');
define('TAG_MI_RSS_DESC','');

define("TAG_MI_ITEMSPERPAGE","Items per page");
define("TAG_MI_ITEMSPERPAGE_DESC","");
define("TAG_MI_ITEMSPERCLOUD","Items per cloud");
define("TAG_MI_ITEMSPERCLOUD_DESC","");
define("TAG_MI_ITEMSPERFEED","Items per module rss feed");
define("TAG_MI_ITEMSPERFEED_DESC","");
define("TAG_MI_ITEMSPERBACKEND","Items per core backend rss feed");
define("TAG_MI_ITEMSPERBACKEND_DESC","");
// Blocks
define("TAG_MI_BLOCK_CLOUD","Tag Cloud");
define("TAG_MI_BLOCK_CLOUD_DESC","");
define("TAG_MI_BLOCK_TOP","Top Tag");
define("TAG_MI_BLOCK_TOP_DESC","");
define("TAG_MI_BLOCK_CUMULUS","Cumulus Tag Cloud");
define("TAG_MI_BLOCK_CUMULUS_DESC","");

// Admin Menu
define("TAG_MI_ADMENU_INDEX","Admin Homepage");
define("TAG_MI_ADMENU_EDIT","Tag Admin");
define("TAG_MI_ADMENU_SYNCHRONIZATION","Synchronization");
define("TAG_MI_ADMENU_HTACCESS", ".htaccess Config");
define("TAG_MI_ADMENU_PLUGINS", "Plugins");

//Version 3.01

?>