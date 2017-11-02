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
$modversion["name"]         			= TAG_MI_NAME;
$modversion["version"]      			= TAG_MI_VERSION;
$modversion["description"]  			= TAG_MI_DESC;
$modversion["dirname"]      			= basename(__DIR__);
$modversion['releasedate'] 				= TAG_MI_RELEASEDATE;
$modversion['status']      				= TAG_MI_STATUS;
$modversion['description'] 				= TAG_MI_DESCRIPTION;
$modversion['credits']     				= TAG_MI_CREDITS;
$modversion['author']      				= TAG_MI_AUTHORALIAS;
$modversion['help']        				= TAG_MI_HELP;
$modversion['license']     				= TAG_MI_LICENCE;
$modversion['official']    				= TAG_MI_OFFICAL;
$modversion['image']       				= TAG_MI_ICON;
$modversion['module_status'] 			= TAG_MI_STATUS;
$modversion['website'] 					= TAG_MI_WEBSITE;
$modversion['dirmoduleadmin'] 			= TAG_MI_ADMINMODDIR;
$modversion['icons16'] 					= TAG_MI_ADMINICON16;
$modversion['icons32'] 					= TAG_MI_ADMINICON32;
$modversion['release_info'] 			= TAG_MI_RELEASEINFO;
$modversion['release_file'] 			= TAG_MI_RELEASEFILE;
$modversion['release_date'] 			= TAG_MI_RELEASEDATE;
$modversion['author_realname'] 			= TAG_MI_AUTHORREALNAME;
$modversion['author_website_url'] 		= TAG_MI_AUTHORWEBSITE;
$modversion['author_website_name'] 		= TAG_MI_AUTHORSITENAME;
$modversion['author_email'] 			= TAG_MI_AUTHOREMAIL;
$modversion['author_word'] 				= TAG_MI_AUTHORWORD;
$modversion['status_version'] 			= TAG_MI_VERSION;
$modversion['warning'] 					= TAG_MI_WARNINGS;
$modversion['demo_site_url'] 			= TAG_MI_DEMO_SITEURL;
$modversion['demo_site_name'] 			= TAG_MI_DEMO_SITENAME;
$modversion['support_site_url'] 		= TAG_MI_SUPPORT_SITEURL;
$modversion['support_site_name'] 		= TAG_MI_SUPPORT_SITENAME;
$modversion['submit_feature'] 			= TAG_MI_SUPPORT_FEATUREREQUEST;
$modversion['submit_bug'] 				= TAG_MI_SUPPORT_BUGREPORTING;
$modversion['people']['developers'] 	= explode("|", TAG_MI_DEVELOPERS);
$modversion['people']['testers']		= explode("|", TAG_MI_TESTERS);
$modversion['people']['translaters']	= explode("|", TAG_MI_TRANSLATERS);
$modversion['people']['documenters']	= explode("|", TAG_MI_DOCUMENTERS);

// Requirements
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

// Use smarty
$modversion["use_smarty"] 				= true;

/**
* Templates
*/
$modversion['templates']    			= array();
$modversion['templates'][1]    			= array(
											    'file'          => 'tag_index.html',
											    'description'   => 'Index page of tag module'
    										);
$modversion['templates'][]    			= array(
											    'file'          => 'tag_list.html',
											    'description'   => 'List of tags'
											);
$modversion['templates'][]    			= array(
											    'file'          => 'tag_view.html',
											    'description'   => 'Links of a tag'
											);
$modversion['templates'][]    			= array(
											    'file'          => 'tag_bar.html',
											    'description'   => 'Tag list in an item'
    										);
$modversion['templates'][]    			= array(
												'file'          => 'tag_category_list.html',
												'description'   => 'List of tags'
											);
$modversion['templates'][]    			= array(
												'file'          => 'tag_category_view.html',
												'description'   => 'Links of a tag'
											);
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
											    "name"          => "items_perpage",
											    "title"         => "TAG_MI_ITEMSPERPAGE",
											    "description"   => "TAG_MI_ITEMSPERPAGE_DESC",
											    "formtype"      => "textbox",
											    "valuetype"     => "int",
											    "default"       => 10,
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
?>