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


include_once '../../mainfile.php';
include_once dirname(__FILE__) . "/include/functions.php";

$myts =& MyTextSanitizer::getInstance();

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

global $modid, $term, $termid, $catid, $start, $sort, $order, $mode, $dirname;

$dirname	= empty($_GET["dirname"]) ? basename(__DIR__) : $_GET["dirname"];
$term   	= empty($_GET["term"]) ? '' : $_GET["term"];
$termid	 	= intval( empty($_GET["id"]) ? 0 : $_GET["id"] );
$catid  	= intval( empty($_GET["catid"]) ? 0 : $_GET["catid"] );
$start  	= intval( empty($_GET["start"]) ? 0 : $_GET["start"] );
$sort   	= empty($_GET["sort"]) ? "time" : $_GET["sort"] ;
$order  	= empty($_GET["order"]) ? "DESC" : $_GET["order"] ;
$mode   	= empty($_GET["mode"]) ? "cloud" : (in_array($_GET["mode"], array('list','cloud'))? $_GET['mode'] : 'cloud') ;

if (isset($dirname) && !empty($dirname) && $dirname != basename(__DIR__))
{
	$GLOBALS['xoopsModule'] = xoops_getHandler('module')->getByDirname($dirname);
	$modid = $GLOBALS['xoopsModule']->getVar('mid');
}

if (!is_object($GLOBALS["xoopsModule"]) || basename(__DIR__)!= $GLOBALS["xoopsModule"]->getVar("dirname", "n")) {
	xoops_loadLanguage("main", "tag");
}

?>