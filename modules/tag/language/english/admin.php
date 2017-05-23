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
?>