<?php
/**
 * Tag management for XOOPS
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		1.00
 * @version		$Id: admin.php 10055 2012-08-11 12:46:10Z beckmi $
 * @package		tag
 */

if (!defined('XOOPS_ROOT_PATH')) { exit(); }
define("TAG_AM_TERM","Tag");

define("TAG_AM_STATS","Statistic Infomation");
define("TAG_AM_COUNT_TAG","Tag count");
define("TAG_AM_COUNT_ITEM","Item count");
define("TAG_AM_COUNT_MODULE","Module count");

define("TAG_AM_EDIT","Tag Admin");
define("TAG_AM_SYNCHRONIZATION","Synchronization");

define("TAG_AM_ACTIVE","Active");
define("TAG_AM_INACTIVE","Inactive");
define("TAG_AM_GLOBAL","Global");
define("TAG_AM_ALL","All modules");
define("TAG_AM_NUM","Number for each time");
define("TAG_AM_IN_PROCESS","Data synchronization is in process, please wait for a while ...");
define("TAG_AM_FINISHED","Data synchronization is finished.");

// Tag version 3.01
define("TAG_AM_ERROR_NOPLUGINFOUND", "No Plugin File Found as Specified!");
define("TAG_AM_ERROR_DELETEPLUGIN", "Are you sure you want to delete the file <strong>%s</strong> from the resource?");
define("TAG_AM_ERROR_NOSOURCETOSAVE", "The source code was not passed by the form to be saved, please attempt to make the edit again!");
define("TAG_AM_ERROR_SAVEDSUCCESS", "Plugin File was Saved Successfully!");
define("TAG_AM_ERROR_SAVEDFAILED", "There was an error possibly permissions and the plugin file was unsuccessfully saved!");
define("TAG_AM_HTACCESS_H1", ".htaccess sample");
define("TAG_AM_HTACCESS_P1", "The following code goes in <strong><em>".XOOPS_ROOT_PATH.DIRECTORY_SEPARATOR.".htaccess</em></strong> as lines listed for the mod rewrite paths for apache, you also have to run on the service:<br/><br><pre>$ a2enmod rewrite</pre><br/>to enable the module within apache 2!");
define("TAG_AM_ADMIN_H1", "Tag Administration");
define("TAG_AM_ADMIN_P1", "From here you can delete as well as enable and disable tags in the database, the following drill down list provides the listing of tags on the service to configure!");
define("TAG_AM_PLUGIN_EDIT_H1", "Edit Plugin File");
define("TAG_AM_PLUGIN_EDIT_P1", "You can edit the file for this plugin directly here if you wish, just edit the lines of code as you require them and submit to save!");
define("TAG_AM_PLUGIN_LIST_H1", "Tag Administration");
define("TAG_AM_PLUGIN_LIST_P1", "From here you can delete as well as enable and disable tags in the database, the following drill down list provides the listing of tags on the service to configure!");
define("TAG_AM_PLUGIN_FILENAME","Plugin Filename");
define("TAG_AM_PLUGIN_MODULE","Module Present");
define("TAG_AM_PLUGIN_MODULE_TRUE","Module Installed");
define("TAG_AM_PLUGIN_MODULE_FALSE","Module Missing");
define("TAG_AM_PLUGIN_VERSION","Plugin Version");
define("TAG_AM_PLUGIN_VERSION_230","2.30 RC Plugin");
define("TAG_AM_PLUGIN_VERSION_301","3.01+ Plugin");
define("TAG_AM_PLUGIN_ACTIVE","Plugin Active");
define("TAG_AM_PLUGIN_ACTIVE_TRUE","Plugin Supported");
define("TAG_AM_PLUGIN_ACTIVE_FALSE","Plugin Inactive");
define("TAG_AM_PLUGIN_ACTIVE_MISSING","Unknown Activity");
define("TAG_AM_PLUGIN_MISSING_VERSION","Missing Function: <strong>%s_tag_version</strong>(){}");
define("TAG_AM_PLUGIN_MISSING_SUPPORTED","Missing Function: <strong>%s_tag_supported</strong>(){}");
define("TAG_AM_PLUGIN_MISSING_CATEGORY","Missing Function: <strong>%s_tag_category</strong>(){}");
define("TAG_AM_PLUGIN_MISSING_SYNCHRONIZATION","Missing Function: <strong>%s_tag_synchronization</strong>(){}");
define("TAG_AM_PLUGIN_MISSING_ITEMINFO","Missing Function: <strong>%s_tag_iteminfo</strong>(){}");
define("TAG_AM_PLUGIN_MISSING","Missing Functions");
define("TAG_AM_PLUGIN_ACTIONS","Actions");
define("TAG_AM_PLUGIN_FUNCTION_VERSION","%s_tag_version");
define("TAG_AM_PLUGIN_FUNCTION_SUPPORTED","%s_tag_supported");
define("TAG_AM_PLUGIN_FUNCTION_CATEGORY","%s_tag_category");
define("TAG_AM_PLUGIN_FUNCTION_SYNCHRONIZATION","%s_tag_synchronization");
define("TAG_AM_PLUGIN_FUNCTION_ITEMINFO","%s_tag_iteminfo");
?>