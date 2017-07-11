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

global $tagModule, $tagConfigsList, $tagConfigs, $tagConfigsOptions;

include_once __DIR__ . '/header.php';
xoops_cp_header();

xoops_load("XoopsFormLoader");

$indexAdmin = new ModuleAdmin();

echo $indexAdmin->addNavigation(basename(__FILE__));
echo $indexAdmin->renderIndex();

$htaccess = file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'htaccess.txt');
$htaccess = str_replace("%base%", $tagConfigsList['base'], $htaccess);
$htaccess = str_replace("%html%", $tagConfigsList['html'], $htaccess);
$GLOBALS['xoopsTpl']->assign('htaccess', $htaccess);

echo $GLOBALS['xoopsTpl']->display(dirname(__DIR__) . '/templates/admin/tag_htaccess.html');

include_once __DIR__ . '/footer.php';
?>