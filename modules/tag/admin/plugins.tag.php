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

include_once __DIR__ . '/header.php';
xoops_cp_header();

xoops_load("XoopsFormLoader");

$indexAdmin = new ModuleAdmin();

echo $indexAdmin->addNavigation(basename(__FILE__));
echo $indexAdmin->renderIndex();

$op = intval( empty($_REQUEST['op']) ? 'default' : $_REQUEST['modid'] );
$filename = intval( empty($_REQUEST['filename']) ? '' : basename($_REQUEST['filename']) );

if (!empty($filename) && !file_exists(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR . $filename))
{
	redirect_header($_SERVER['PHP_SELF']."?op=list", 4, TAG_AM_ERROR_NOPLUGINFOUND);
	exit(0);
}
switch ($op)
{
	default:
	case "default":
	case "list":
		xoops_load("XoopsLists");
		$module_handler = xoops_getHandler("module");
		$files = XoopsLists::getFileListAsArray(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'plugin');
		sort($files);
		$plugins = array();
		foreach($files as $file)
		{
			if (substr($file, strlen($file)-3,3) = 'php')
			{
				$dirname = str_replace('.php', '', $file);
				$plugins[$dirname]['filename'] = $file;
				$mod = $module_handler->getByDirname($dirname);
				if (is_object($mod) && is_a($mod, "XoopsModule"))
				{
					$plugins[$dirname]['module'] = TAG_AM_PLUGIN_MODULE_TRUE;
				} else {
					$plugins[$dirname]['module'] = TAG_AM_PLUGIN_MODULE_FALSE;
				}
				$source = file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR . $file);
				if (	strpos($source, sprintf(TAG_AM_PLUGIN_FUNCTION_VERSION, $dirname)) > 0 && 
						strpos($source, sprintf(TAG_AM_PLUGIN_FUNCTION_SUPPORTED, $dirname))> 0 && 
						strpos($source, sprintf(TAG_AM_PLUGIN_FUNCTION_CATEGORY, $dirname))> 0 && 
						strpos($source, sprintf(TAG_AM_PLUGIN_FUNCTION_SYNCHRONIZATION, $dirname))> 0 && 
						strpos($source, sprintf(TAG_AM_PLUGIN_FUNCTION_ITEMINFO, $dirname))> 0)
				{
					$plugins[$dirname]['version'] = TAG_AM_PLUGIN_VERSION_301;
				} else {
					$plugins[$dirname]['version'] = TAG_AM_PLUGIN_VERSION_230;
				}
				$missing = array();
				if ( strpos($source, sprintf(TAG_AM_PLUGIN_FUNCTION_VERSION, $dirname)) == 0 ) 
					$missing[] = sprintf(TAG_AM_PLUGIN_MISSING_VERSION, $dirname);
				if ( strpos($source, sprintf(TAG_AM_PLUGIN_FUNCTION_SUPPORTED, $dirname)) == 0 )
					$missing[] = sprintf(TAG_AM_PLUGIN_MISSING_SUPPORTED, $dirname);
				if ( strpos($source, sprintf(TAG_AM_PLUGIN_FUNCTION_CATEGORY, $dirname)) == 0 )
					$missing[] = sprintf(TAG_AM_PLUGIN_MISSING_CATEGORYN, $dirname);
				if ( strpos($source, sprintf(TAG_AM_PLUGIN_FUNCTION_SYNCHRONIZATION, $dirname)) == 0 )
					$missing[] = sprintf(TAG_AM_PLUGIN_MISSING_SYNCHRONIZATION, $dirname);
				if ( strpos($source, sprintf(TAG_AM_PLUGIN_FUNCTION_ITEMINFO, $dirname)) == 0 )
					$missing[] = sprintf(TAG_AM_PLUGIN_MISSING_ITEMINFO, $dirname);
				$plugins[$dirname]['missing'] = implode("<br />", $missing); 
				if ($plugins[$dirname]['version'] != TAG_AM_PLUGIN_VERSION_301)
				{
					$plugins[$dirname]['active'] = TAG_AM_PLUGIN_ACTIVE_MISSING;
				} else {
					eval($source);
					if (function_exists($func = "$dirname_tag_supported"))
					{
						if ($func()==true)
						{
							$plugins[$dirname]['active'] = TAG_AM_PLUGIN_ACTIVE_TRUE;
						} else {
							$plugins[$dirname]['active'] = TAG_AM_PLUGIN_ACTIVE_FALSE;
						}
					} else 
						$plugins[$dirname]['active'] = TAG_AM_PLUGIN_ACTIVE_MISSING;
				}
			}
		}
		$GLOBALS['xoopsTpl']->assign('plugins', $plugins);
		echo $GLOBALS['xoopsTpl']->display(dirname(__DIR__) . '/templates/admin/tag_plugins_list.html');
		break;
	case "edit":
		$GLOBALS['xoTheme']->addScript("", array(), XOOPS_URL . '/modules/tag/js/monaco/loader.js');
		$GLOBALS['xoTheme']->addScript("require.config({ paths: { 'vs': ".XOOPS_URL."./modules/tag/js/modules/tag/js/monaco' }});
	require(['".XOOPS_URL."./modules/tag/js/monaco/editor/editor.main'], function() {
		var editor = monaco.editor.create(document.getElementById('source'), {
			language: 'php'
		});
	});", array());
		$GLOBALS['xoopsTpl']->assign('filename', $filename);
		$GLOBALS['xoopsTpl']->assign('source', file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR . $filename));
		echo $GLOBALS['xoopsTpl']->display(dirname(__DIR__) . '/templates/admin/tag_plugins_edit.html');
		break;
	case "save":
		if (empty($_REQUEST['source']))
		{
			redirect_header($_SERVER['PHP_SELF']."?op=edit&filename=$filename", 5, TAG_AM_ERROR_NOSOURCETOSAVE);
		}
		if (file_put_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR . $filename, $_REQUEST['source']))
			redirect_header($_SERVER['PHP_SELF']."?op=edit&filename=$filename", 5, TAG_AM_ERROR_SAVEDSUCCESS);
		else 
			redirect_header($_SERVER['PHP_SELF']."?op=edit&filename=$filename", 5, TAG_AM_ERROR_SAVEDFAILED);
		exit(0);
		break;
	case "delete":
		echo xoops_confirm(array('op'=>'confirm_delete', 'filename'=>$filename), $_SERVER['PHP_SELF'], sprintf(TAG_AM_ERROR_DELETEPLUGIN, $filename));
		break;
	case "confirm_delete":
		unlink(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR . $filename);
		redirect_header($_SERVER['PHP_SELF']."?op=list", 0, '');
		exit(0);
		break;
}
include_once __DIR__ . '/footer.php';
?>