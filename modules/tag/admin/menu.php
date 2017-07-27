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

global $adminmenu;

$adminmenu = array();

$adminmenu[]= array("link"    => "admin/index.php",
					"icon"	  => "home.png",
					"image"	  => "home.png",
                    "title"    => TAG_MI_ADMENU_INDEX);
$adminmenu[]= array("link"    => "admin/admin.tag.php",
					"icon"	  => "edit.png",
					"image"	  => "edit.png",
                    "title"    => TAG_MI_ADMENU_EDIT);
$adminmenu[]= array("link"    => "admin/syn.tag.php",
					"icon"	  => "export.png",
					"imge"	  => "export.png",
                    "title"    => TAG_MI_ADMENU_SYNCHRONIZATION);
$adminmenu[]= array("link"    => "admin/htaccess.tag.php",
					"icon"	  => "content.png",
					"image"	  => "content.png",
					"title"    => TAG_MI_ADMENU_HTACCESS);
$adminmenu[]= array("link"    => "admin/plugins.tag.php",
					"icon"	  => "exec.png",
					"image"	  => "exec.png",
					"title"    => TAG_MI_ADMENU_PLUGINS);
?>