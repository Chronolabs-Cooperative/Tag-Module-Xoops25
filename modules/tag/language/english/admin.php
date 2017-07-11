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
define("TAG_AM_HTACCESS_H1", ".htaccess sample");
define("TAG_AM_HTACCESS_P1", "The following code goes in <strong><em>".XOOPS_ROOT_PATH.DIRECTORY_SEPARATOR.".htaccess</em></strong> as lines listed for the mod rewrite paths for apache, you also have to run on the service:<br/><br><pre>$ a2enmod rewrite</pre><br/>to enable the module within apache 2!");
define("TAG_AM_ADMIN_H1", "Tag Administration");
define("TAG_AM_ADMIN_P1", "From here you can delete as well as enable and disable tags in the database, the following drill down list provides the listing of tags on the service to configure!");
?>