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

include dirname(__DIR__) . "/include/vars.php";
include_once dirname(__DIR__) . "/include/functions.php";
$path = dirname(dirname(dirname(__DIR__)));
include_once dirname(__DIR__) . '/header.php';
include_once $path . '/include/cp_functions.php';
require_once $path . '/include/cp_header.php';

global $xoopsModule;

$thisModuleDir = $GLOBALS['xoopsModule']->getVar('dirname');

//if functions.php file exist
//require_once dirname(__DIR__) . '/include/functions.php';

// Load language files
xoops_loadLanguage('admin', $thisModuleDir);
xoops_loadLanguage('modinfo', $thisModuleDir);
xoops_loadLanguage('main', $thisModuleDir);

$pathIcon16      = '../' . $xoopsModule->getInfo('icons16');
$pathIcon32      = '../' . $xoopsModule->getInfo('icons32');
$pathModuleAdmin = $xoopsModule->getInfo('dirmoduleadmin');

$myts = MyTextSanitizer::getInstance();

if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
	include_once XOOPS_ROOT_PATH . '/class/template.php';
	$xoopsTpl = new XoopsTpl();
}

include_once $GLOBALS['xoops']->path($pathModuleAdmin . '/moduleadmin.php');

xoops_loadLanguage('user');
if (!isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])) {
	include_once $GLOBALS['xoops']->path('/class/template.php');
	$GLOBALS['xoopsTpl'] = new XoopsTpl();
}


global $tagModule, $tagConfigsList, $tagConfigs, $tagConfigsOptions;

if (empty($tagModule))
{
	if (is_a($tagModule = xoops_getHandler('module')->getByDirname(basename(__DIR__)), "XoopsModule"))
	{
		if (empty($tagConfigsList))
		{
			$tagConfigsList = tag_load_config();
		}
		if (empty($tagConfigs))
		{
			$tagConfigs = xoops_getHandler('config')->getConfigs(new Criteria('conf_modid', $tagModule->getVar('mid')));
		}
		if (empty($tagConfigsOptions) && !empty($tagConfigs))
		{
			foreach($tagConfigs as $key => $config)
				$tagConfigsOptions[$config->getVar('conf_name')] = $config->getConfOptions();
		}
	}
}

?>