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
define('TAG_MI_MODULE_NAME','Tags');
define('TAG_MI_MODULE_VERSION','3.01');
define('TAG_MI_MODULE_RELEASEDATE','11-07-2017');
define('TAG_MI_MODULE_STATUS','release');
define('TAG_MI_MODULE_DESCRIPTION','Tags for XOOPS');
define('TAG_MI_MODULE_CREDITS','Mynamesnot, Wishcraft');
define('TAG_MI_MODULE_AUTHORALIAS','wishcraft');
define('TAG_MI_MODULE_HELP','page=help');
define('TAG_MI_MODULE_LICENCE','gpl3+academic');
define('TAG_MI_MODULE_OFFICAL','1');
define('TAG_MI_MODULE_ICON','images/mlogo.png');
define('TAG_MI_MODULE_WEBSITE','http://au.syd.snails.email');
define('TAG_MI_MODULE_ADMINMODDIR','/Frameworks/moduleclasses/moduleadmin');
define('TAG_MI_MODULE_ADMINICON16','../../Frameworks/moduleclasses/icons/16');
define('TAG_MI_MODULE_ADMINICON32','../../Frameworks/moduleclasses/icons/32');
define('TAG_MI_MODULE_RELEASEINFO',__DIR__ . DIRECTORY_SEPARATOR . 'release.nfo');
define('TAG_MI_MODULE_RELEASEXCODE',__DIR__ . DIRECTORY_SEPARATOR . 'release.xcode');
define('TAG_MI_MODULE_RELEASEFILE','https://sourceforge.net/projects/chronolabs/files/XOOPS%202.5/Modules/tag/xoops2.5_tag_3.01.7z');
define('TAG_MI_MODULE_AUTHORREALNAME','Simon Antony Roberts');
define('TAG_MI_MODULE_AUTHORWEBSITE','http://internetfounder.wordpress.com');
define('TAG_MI_MODULE_AUTHORSITENAME','Exhumations from the desks of Chronographics');
define('TAG_MI_MODULE_AUTHOREMAIL','simon@snails.email');
define('TAG_MI_MODULE_AUTHORWORD','');
define('TAG_MI_MODULE_WARNINGS','');
define('TAG_MI_MODULE_DEMO_SITEURL','');
define('TAG_MI_MODULE_DEMO_SITENAME','');
define('TAG_MI_MODULE_SUPPORT_SITEURL','');
define('TAG_MI_MODULE_SUPPORT_SITENAME','');
define('TAG_MI_MODULE_SUPPORT_FEATUREREQUEST','');
define('TAG_MI_MODULE_SUPPORT_BUGREPORTING','');
define('TAG_MI_MODULE_DEVELOPERS','Simon Roberts (Wishcraft)'); // Sperated by a Pipe (|)
define('TAG_MI_MODULE_TESTERS',''); // Sperated by a Pipe (|)
define('TAG_MI_MODULE_TRANSLATERS',''); // Sperated by a Pipe (|)
define('TAG_MI_MODULE_DOCUMENTERS',''); // Sperated by a Pipe (|)
define('TAG_MI_MODULE_HASSEARCH',true);
define('TAG_MI_MODULE_HASMAIN',true);
define('TAG_MI_MODULE_HASADMIN',true);
define('TAG_MI_MODULE_HASCOMMENTS',false);

// Configguration Categories
define('TAG_MI_CONFCAT_SEO','Search Engine Optimization');
define('TAG_MI_CONFCAT_SEO_DESC','');
define('TAG_MI_CONFCAT_MODULE','Tag Module Settins');
define('TAG_MI_CONFCAT_MODULE_DESC','');

// Configuration Descriptions and Titles
define('TAG_MI_HTACCESS','.htaccess SEO URL');
define('TAG_MI_HTACCESS_DESC','');
define('TAG_MI_BASE','Base .htaccess path');
define('TAG_MI_BASE_DESC','');
define('TAG_MI_HTML','Extension for HTML output with SEO URL');
define('TAG_MI_HTML_DESC','');
define("TAG_MI_ITEMSPERPAGE","Items per page");
define("TAG_MI_ITEMSPERPAGE_DESC","");
define("TAG_MI_ITEMSPERCLOUD","Items per cloud");
define("TAG_MI_ITEMSPERCLOUD_DESC","");

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
define("TAG_MI_ADMENU_HTACCESS", ".htaccess Settings")
define("TAG_MI_ADMENU_PLUGINS", "Plugins Compatiability")

//Version 3.01

?>