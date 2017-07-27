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

function xoops_module_install_tag($module)
{
    return true;
}

function xoops_module_pre_install_tag($module)
{
    $mod_tables = $module->getInfo("tables");
    foreach ($mod_tables as $table) {
        $GLOBALS["xoopsDB"]->queryF("DROP TABLE IF EXISTS " .  $GLOBALS["xoopsDB"]->prefix($table) . ";");
    }
    return true;
}

function xoops_module_pre_update_tag($module)
{
    return true;
}

function xoops_module_pre_uninstall_tag($module)
{
    return true;
}

function xoops_module_update_tag($module, $prev_version = null)
{
    if ($prev_version <= 150) {
        $GLOBALS['xoopsDB']->queryFromFile(dirname(__DIR__) . "/sql/mysql.150.sql");
    }
    
    if ($prev_version <= 230) {
    	$GLOBALS['xoopsDB']->queryFromFile(dirname(__DIR__) . "/sql/mysql.230.sql");
    }
    
    // Sync Tags
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'functions.php';
    tag_synchronization();
    return true;
}
?>