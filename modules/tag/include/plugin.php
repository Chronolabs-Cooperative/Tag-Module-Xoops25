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


// plugin guide:
/* 
 * Add customized configs, variables or functions
 */
$customConfig = array();

/* 
 * Due to the difference of word boundary for different languages, delimiters also depend on languages
 * You need specify all possbile deimiters:
 * IF $GLOBALS["tag_delimiter"] IS SET IN /modules/tag/language/mylanguage/main.php, TAKE IT
 * ELSE IF $customConfig["tag_delimiter"] IS SET BELOW, TAKE IT
 * ELSE TAKE (",", ";", " ", "|")
 *
 * Tips:
 * For English sites, you can set as array(",", ";", " ", "|")
 * For Chinese sites, you can set as array(",", ";", " ", "|", "��")
 *
 * TODO: there shall be an option for admin to choose a category to store subcategories and articles
 */
$customConfig["tag_delimiter"] = array(",", " ", "|", ";");
$customConfig["font_max"]   = 150;
$customConfig["font_min"]   = 80;

return $customConfig;
?>