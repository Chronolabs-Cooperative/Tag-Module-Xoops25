<?php
/**
 * XOOPS tag management module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright   	The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     	General Public License version 3
 * @author      	Simon Roberts <wishcraft@users.sourceforge.net>
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @subpackage  	tag
 * @description 	XOOPS tag management module
 * @version			2.4.1
 * @link        	https://sourceforge.net/projects/chronolabs/files/XOOPS%202.5/Modules/tag
 * @link        	https://sourceforge.net/projects/chronolabs/files/XOOPS%202.6/Modules/tag
 * @link			https://sourceforge.net/p/xoops/svn/HEAD/tree/XoopsModules/tag
 * @link			http://internetfounder.wordpress.com
 */


if (!defined('XOOPS_ROOT_PATH')) { exit(); }

$modversion = array();
$modversion["name"]         			     = TAG_MI_NAME;
$modversion["version"]      			     = TAG_MI_VERSION;
$modversion["description"]  			     = TAG_MI_DESCRIPTION;
$modversion["dirname"]      			     = basename(__DIR__);
$modversion['releasedate'] 				     = TAG_MI_RELEASEDATE;
$modversion['status']      				     = TAG_MI_STATUS;
$modversion['credits']     				     = TAG_MI_CREDITS;
$modversion['author']      				     = TAG_MI_AUTHORALIAS;
$modversion['help']        				     = TAG_MI_HELP;
$modversion['license']     				     = TAG_MI_LICENCE;
$modversion['academic']    				     = TAG_MI_ACADEMIC;
$modversion['official']    				     = TAG_MI_OFFICAL;
$modversion['image']       				     = TAG_MI_ICON;
$modversion['dirmoduleadmin'] 			     = TAG_MI_ADMINMODDIR;
$modversion['icons16'] 					     = TAG_MI_ADMINICON16;
$modversion['icons32'] 					     = TAG_MI_ADMINICON32;
$modversion['author']['realname'] 		     = TAG_MI_AUTHORREALNAME;
$modversion['author']['website']['url']      = TAG_MI_AUTHORWEBSITE;
$modversion['author']['website']['name'] 	 = TAG_MI_AUTHORSITENAME;
$modversion['author']['email'] 			     = TAG_MI_AUTHOREMAIL;
$modversion['author']['word'] 				 = TAG_MI_AUTHORWORD;
$modversion['author']['feed'] 				 = TAG_MI_AUTHORFEED;
$modversion['warning']['install'] 			 = TAG_MI_WARNINGS_INSTALL;
$modversion['warning']['update'] 			 = TAG_MI_WARNINGS_UPDATE;
$modversion['warning']['uninstall'] 	     = TAG_MI_WARNINGS_UNINSTALL;
$modversion['demo']['site']['url'] 			 = TAG_MI_DEMO_SITEURL;
$modversion['demo']['site']['name'] 		 = TAG_MI_DEMO_SITENAME;
$modversion['support']['site']['url'] 		 = TAG_MI_SUPPORT_SITEURL;
$modversion['support']['site']['name'] 		 = TAG_MI_SUPPORT_SITENAME;
$modversion['submit']['form']['feature'] 	 = TAG_MI_SUPPORT_FEATUREREQUEST;
$modversion['submit']['form']['bug'] 		 = TAG_MI_SUPPORT_BUGREPORTING;

// People Arrays
$modversion['people']['developers']['phppp']['name'] = 'Taiwen Jaing';
$modversion['people']['developers']['phppp']['email'] = 'phppp@users.sourceforge.net';
$modversion['people']['developers']['phppp']['handle'] = 'phppp';
$modversion['people']['developers']['phppp']['version'] = array('2.30');
$modversion['people']['developers']['wishcraft']['name'] = 'Simon Antony Roberts';
$modversion['people']['developers']['wishcraft']['email'] = 'wishcraft@users.sourceforge.net';
$modversion['people']['developers']['wishcraft']['handle'] = 'wishcraft';
$modversion['people']['developers']['wishcraft']['version'] = array('3.00', '3.01', '3.05');
$modversion['people']['testers'] = array();
$modversion['people']['translaters'] = array();
$modversion['people']['documenters'] = array();

// Releases Identity Hashes
$modversion['keys']['module']        	= 'tagUITU432536';
$modversion['keys']['release']          = '305jkhr37ryhf';

// Requirements
$modversion['minimal']['php']        	= '7.0';
$modversion['minimal']['xoops']      	= '2.5.8';
$modversion['minimal']['db']         	= array('mysql' => '5.0.7', 'mysqli' => '5.0.7');
$modversion['minimal']['admin']      	= '1.1';

// Requirements = Legacy for XOOPS 2.5
$modversion['min_php']        			= '7.0';
$modversion['min_xoops']      			= '2.5.8';
$modversion['min_db']         			= array('mysql' => '5.0.7', 'mysqli' => '5.0.7');
$modversion['min_admin']      			= '1.1';

// database tables
$modversion["sqlfile"]["mysql"] 		= "sql/mysql.sql";
$modversion["tables"] 					= array(
												"tag_categories",
												"tag_categories_link",
												"tag_tag",
    											"tag_link",
    											"tag_stats",
											);

// Main
$modversion['hasMain'] 					= TAG_MI_HASMAIN;

// Admin
$modversion['hasAdmin'] 				= TAG_MI_HASADMIN;
$modversion['adminindex']  				= "admin/index.php";
$modversion['adminmenu']   				= "admin/menu.php";
$modversion['system_menu'] 				= 1;

// Search
$modversion["hasSearch"] 				= TAG_MI_HASSEARCH;
$modversion['search']['file'] 			= "include/search.inc.php";
$modversion['search']['func'] 			= "tag_search";

// Comments
$modversion["hasComments"] 				= TAG_MI_HASCOMMENTS;

//$modversion["onInstall"] 				= "include/action.module.php";
$modversion["onUpdate"] 				= "include/action.module.php";
$modversion["onUninstall"] 				= "include/action.module.php";

// Blocks
$modversion['blocks']    				= array();

/*
 * $options:  
 *                    $options[0] - number of tags to display
 *                    $options[1] - max font size (px or %)
 *                    $options[2] - min font size (px or %)
 */
$modversion["blocks"][1]    			= array(
											    "file"          => "block.php",
											    "name"          => TAG_MI_BLOCK_CLOUD,
											    "description"   => TAG_MI_BLOCK_CLOUD_DESC,
											    "show_func"     => "tag_block_cloud_show",
											    "edit_func"     => "tag_block_cloud_edit",
											    "options"       => "100|0|150|80",
											    "template"      => "tag_block_cloud.html",
										    );

/*
 * $options:  
 *                    $options[0] - number of tags to display
 *                    $options[1] - time duration, in days, 0 for all the time
 *                    $options[2] - sort: a - alphabet; c - count; t - time
 */
$modversion["blocks"][]    				= array(
											    "file"          => "block.php",
											    "name"          => TAG_MI_BLOCK_TOP,
											    "description"   => TAG_MI_BLOCK_TOP_DESC,
											    "show_func"     => "tag_block_top_show",
											    "edit_func"     => "tag_block_top_edit",
											    "options"       => "50|30|a",
											    "template"      => "tag_block_top.html",
    										);


// Blocks
$modversion['blocks']    = array();

/*
 * $options for cumulus:
 *                    $options[0] - number of tags to display
 *                    $options[1] - time duration
 *                    $options[2] - max font size (px or %)
 *                    $options[3] - min font size (px or %)
 *                    $options[4] - cumulus_flash_width
 *                    $options[5] - cumulus_flash_height
 *                    $options[6] - cumulus_flash_background
 *                    $options[7] - cumulus_flash_transparency
 *                    $options[8] - cumulus_flash_min_font_color
 *                    $options[9] - cumulus_flash_max_font_color
 *                    $options[10] - cumulus_flash_hicolor
 *                    $options[11] - cumulus_flash_speed
 */
$modversion["blocks"][]    				= 	array(
												"file"          => "block.php",
												"name"          => TAG_MI_BLOCK_CUMULUS,
												"description"   => TAG_MI_BLOCK_CUMULUS_DESC,
												"show_func"     => "tag_block_cumulus_show",
												"edit_func"     => "tag_block_cumulus_edit",
												"options"       => "100|0|24|12|160|140|#ffffff|0|#000000|#003300|#00ff00|100",
												"template"      => "tag_block_cumulus.html",
											);

// Config categories
$modversion['configcat']['seo']['name']        = TAG_MI_CONFCAT_SEO;
$modversion['configcat']['seo']['description'] = TAG_MI_CONFCAT_SEO_DESC;

$modversion['configcat']['mod']['name']        = TAG_MI_CONFCAT_MODULE;
$modversion['configcat']['mod']['description'] = TAG_MI_CONFCAT_DESC;


// Configs
$modversion["config"] 					= array();
    
$modversion["config"][] 				= array(
											    "name"          => "htaccess",
											    "title"         => "TAG_MI_HTACCESS",
											    "description"   => "TAG_MI_HTACCESS_DESC",
											    "formtype"      => "yesno",
											    "valuetype"     => "int",
											    "default"       => false,
												"category"		=> "seo"
    										);

$modversion["config"][] 				= array(
												"name"          => "base",
												"title"         => "TAG_MI_BASE",
												"description"   => "TAG_MI_BASE_DESC",
												"formtype"      => "text",
												"valuetype"     => "text",
												"default"       => "tags",
												"category"		=> "seo"
											);

$modversion["config"][] 				= array(
												"name"          => "html",
												"title"         => "TAG_MI_HTML",
												"description"   => "TAG_MI_HTML_DESC",
												"formtype"      => "text",
												"valuetype"     => "text",
												"default"       => ".html",
												"category"		=> "seo"
											);

$modversion["config"][] 				= array(
                                                "name"          => "rss",
                                                "title"         => "TAG_MI_RSS",
                                                "description"   => "TAG_MI_RSS_DESC",
                                                "formtype"      => "text",
                                                "valuetype"     => "text",
                                                "default"       => ".rss",
                                                "category"		=> "seo"
                                            );

$modversion["config"][] 				= array(
											    "name"          => "items_perpage",
											    "title"         => "TAG_MI_ITEMSPERPAGE",
											    "description"   => "TAG_MI_ITEMSPERPAGE_DESC",
											    "formtype"      => "textbox",
											    "valuetype"     => "int",
											    "default"       => 10,
												"category"		=> "mod"
    										);


$modversion["config"][] 				= array(
                                                "name"          => "items_perfeed",
                                                "title"         => "TAG_MI_ITEMSPERFEED",
                                                "description"   => "TAG_MI_ITEMSPERFEED_DESC",
                                                "formtype"      => "textbox",
                                                "valuetype"     => "int",
                                                "default"       => 15,
                                                "category"		=> "mod"
                                            );


$modversion["config"][] 				= array(
                                                "name"          => "items_perbackend",
                                                "title"         => "TAG_MI_ITEMSPERBACKEND",
                                                "description"   => "TAG_MI_ITEMSPERBACKEND_DESC",
                                                "formtype"      => "textbox",
                                                "valuetype"     => "int",
                                                "default"       => 2,
                                                "category"		=> "mod"
                                            );

$modversion["config"][] 				= array(
												"name"          => "limit_tag_cloud",
												"title"         => "TAG_MI_ITEMSPERCLOUD",
												"description"   => "TAG_MI_ITEMSPERCLOUD_DESC",
												"formtype"      => "textbox",
												"valuetype"     => "int",
												"default"       => 100,
												"category"		=> "mod"
											);


// Notification
$modversion["hasNotification"] 			= 0;
$modversion["notification"] 			= array();


// Feeds
$modversion["hasFeeds"] 			    = TAG_MI_HASFEEDS;
$modversion["feed"][] 			        = array(
                                                "file"          => 'include/feeds.module.php',
                                                "func"          => 'tag_feed_tags',
                                                "many"          => 'items_perbackend'
                                          );

// .htaccess Mod Rewrite
$i=0;
$modversion["hasRewrite"]                   = true;
$modversion["rewrite"]["config"]["base"]    = '%base';
$modversion["rewrite"]["config"]["html"]    = '%html';
$modversion["rewrite"]["config"]["rss"]     = '%rss';
$modversion["rewrite"][++$i]["raw"]         = 'RewriteCond %{REQUEST_FILENAME} !-f';
$modversion["rewrite"][++$i]["raw"]         = 'RewriteCond %{REQUEST_FILENAME} !-d';
$i++;
$modversion["rewrite"][$i]["path"]          = '^%base/index%html';
$modversion["rewrite"][$i]["resolve"]       = './modules/%dirname/index.php';
$modversion["rewrite"][$i]["state"]         = 'L,NC,QSA';
$i++;
$modversion["rewrite"][$i]["path"]          = '^%base/index-(.*?)%html';
$modversion["rewrite"][$i]["resolve"]       = './modules/%dirname/index.php?dirname=$1';
$modversion["rewrite"][$i]["state"]         = 'L,NC,QSA';
$i++;
$modversion["rewrite"][$i]["path"]          = '^%base/feed%rss';
$modversion["rewrite"][$i]["resolve"]       = './modules/%dirname/backend.php';
$modversion["rewrite"][$i]["state"]         = 'L,NC,QSA';
$i++;
$modversion["rewrite"][$i]["path"]          = '^%base/feed-(.*?)%rss';
$modversion["rewrite"][$i]["resolve"]       = './modules/%dirname/backend.php?dirname=$1';
$modversion["rewrite"][$i]["state"]         = 'L,NC,QSA';
$i++;
$modversion["rewrite"][$i]["path"]          = '^%base/(view|list|feed)/(tag|cat)/([0-9]+)/(count|time|term)/(ASC|DESC)/(cloud|list)/([0-9]+)(%html|%rss)';
$modversion["rewrite"][$i]["resolve"]       = './modules/%dirname/$1.$2.php?start=$3&sort=$4&order=$5&mode=$6&termid=$7';
$modversion["rewrite"][$i]["state"]         = 'L,NC,QSA';
$i++;
$modversion["rewrite"][$i]["path"]          = '^%base/(view|list|feed)/(tag|cat)/([0-9]+)/(count|time|term)/(ASC|DESC)/(cloud|list)/([0-9]+)/([0-9]+)(%html|%rss)';
$modversion["rewrite"][$i]["resolve"]       = './modules/%dirname/$1.$2.php?start=$3&sort=$4&order=$5&mode=$6&catid=$7&termid=$8';
$modversion["rewrite"][$i]["state"]         = 'L,NC,QSA';
$i++;
$modversion["rewrite"][$i]["path"]          = '^%base/(view|list|feed)/(tag|cat)/([0-9]+)/(count|time|term)/(ASC|DESC)/(cloud|list)/([0-9]+)-(.*?)(%html|%rss)';
$modversion["rewrite"][$i]["resolve"]       = './modules/%dirname/$1.$2.php?start=$3&sort=$4&order=$5&mode=$6&termid=$7&dirname=$8';
$modversion["rewrite"][$i]["state"]         = 'L,NC,QSA';
$i++;
$modversion["rewrite"][$i]["path"]          = '^%base/(view|list|feed)/(tag|cat)/([0-9]+)/(count|time|term)/(ASC|DESC)/(cloud|list)/([0-9]+)/([0-9]+)-(.*?)(%html|%rss)';
$modversion["rewrite"][$i]["resolve"]       = './modules/%dirname/$1.$2.php?start=$3&sort=$4&order=$5&mode=$6&catid=$4&termid=$7&dirname=$8';
$modversion["rewrite"][$i]["state"]         = 'L,NC,QSA';
$i++;
$modversion["rewrite"][$i]["path"]          = '^%base/(view|list|feed)/(tag|cat)/([0-9]+)/(count|time|term)/(ASC|DESC)/(cloud|list)/(.*?)(%html|%rss)';
$modversion["rewrite"][$i]["resolve"]       = './modules/%dirname/$1.$2.php?start=$3&sort=$4&order=$5&mode=$6&term=$7';
$modversion["rewrite"][$i]["state"]         = 'L,NC,QSA';
$i++;
$modversion["rewrite"][$i]["path"]          = '^%base/(view|list|feed)/(tag|cat)/([0-9]+)/(count|time|term)/(ASC|DESC)/(cloud|list)/([0-9]+)/(.*?)(%html|%rss)';
$modversion["rewrite"][$i]["resolve"]       = './modules/%dirname/$1.$2.php?start=$3&sort=$4&order=$5&mode=$6&catid=$7&term=$8';
$modversion["rewrite"][$i]["state"]         = 'L,NC,QSA';
$i++;
$modversion["rewrite"][$i]["path"]          = '^%base/(view|list|feed)/(tag|cat)/([0-9]+)/(count|time|term)/(ASC|DESC)/(cloud|list)/(.*?)-(.*?)(%html|%rss)';
$modversion["rewrite"][$i]["resolve"]       = './modules/%dirname/$1.$2.php?start=$3&sort=$4&order=$5&mode=$6&term=$7&dirname=$8';
$modversion["rewrite"][$i]["state"]         = 'L,NC,QSA';
$i++;
$modversion["rewrite"][$i]["path"]          = '^%base/(view|list|feed)/(tag|cat)/([0-9]+)/(count|time|term)/(ASC|DESC)/(cloud|list)/([0-9]+)/(.*?)-(.*?)(%html|%rss)';
$modversion["rewrite"][$i]["resolve"]       = './modules/%dirname/$1.$2.php?start=$3&sort=$4&order=$5&mode=$6&catid=$7&term=$8&dirname=$9';
$modversion["rewrite"][$i]["state"]         = 'L,NC,QSA';

// Constraints
$modversion["hasConstraints"] 			= 0;
$modversion["constraints"] 			    = array();

?>