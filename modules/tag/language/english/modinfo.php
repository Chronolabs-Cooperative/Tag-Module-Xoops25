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
define('TAG_MI_VERSION','3.02');
define('TAG_MI_RELEASEDATE','28-07-2017');
define('TAG_MI_STATUS','release');
define('TAG_MI_DESCRIPTION','Tags for XOOPS');
define('TAG_MI_CREDITS','Mynamesnot, Wishcraft');
define('TAG_MI_AUTHORALIAS','wishcraft');
define('TAG_MI_HELP','page=help');
define('TAG_MI_LICENCE','gpl3+academic');
define('TAG_MI_OFFICAL','1');
define('TAG_MI_ICON','images/mlogo.png');
define('TAG_MI_WEBSITE','http://au.syd.snails.email');
define('TAG_MI_ADMINMODDIR','/Frameworks/moduleclasses/moduleadmin');
define('TAG_MI_ADMINICON16','../../Frameworks/moduleclasses/icons/16');
define('TAG_MI_ADMINICON32','../../Frameworks/moduleclasses/icons/32');
define('TAG_MI_RELEASEINFO',__DIR__ . DIRECTORY_SEPARATOR . 'release.nfo');
define('TAG_MI_RELEASEXCODE',__DIR__ . DIRECTORY_SEPARATOR . 'release.xcode');
define('TAG_MI_RELEASEFILE','https://sourceforge.net/projects/chronolabs/files/XOOPS%202.5/Modules/tag/xoops2.5_tag_3.01.7z/download');
define('TAG_MI_AUTHORREALNAME','Simon Antony Roberts');
define('TAG_MI_AUTHORWEBSITE','http://internetfounder.wordpress.com');
define('TAG_MI_AUTHORSITENAME','Exhumations from the desks of Chronographics');
define('TAG_MI_AUTHOREMAIL','simon@snails.email');
define('TAG_MI_AUTHORWORD','');
define('TAG_MI_WARNINGS','');
define('TAG_MI_DEMO_SITEURL','');
define('TAG_MI_DEMO_SITENAME','');
define('TAG_MI_SUPPORT_SITEURL','');
define('TAG_MI_SUPPORT_SITENAME','');
define('TAG_MI_SUPPORT_FEATUREREQUEST','');
define('TAG_MI_SUPPORT_BUGREPORTING','');
define('TAG_MI_DEVELOPERS','Simon Roberts (Wishcraft)'); // Sperated by a Pipe (|)
define('TAG_MI_TESTERS',''); // Sperated by a Pipe (|)
define('TAG_MI_TRANSLATERS',''); // Sperated by a Pipe (|)
define('TAG_MI_DOCUMENTERS',''); // Sperated by a Pipe (|)
define('TAG_MI_HASSEARCH',true);
define('TAG_MI_HASMAIN',true);
define('TAG_MI_HASADMIN',true);
define('TAG_MI_HASCOMMENTS',false);

// Configguration Categories
define('TAG_MI_CONFCAT_SEO','Search Engine Optimization');
define('TAG_MI_CONFCAT_SEO_DESC','');
define('TAG_MI_CONFCAT_MODULE','Tag Module Settins');
define('TAG_MI_CONFCAT_DESC','');

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
define("TAG_MI_ADMENU_HTACCESS", ".htaccess Config");
define("TAG_MI_ADMENU_PLUGINS", "Plugins");

//Version 3.01

?>