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

if (!defined("TAG_FUNCTIONS")):
define("TAG_FUNCTIONS",1);

include XOOPS_ROOT_PATH . "/modules/tag/include/vars.php";


function tag_load_config()
{
	global $xoopsModuleConfig;
	static $moduleConfig;
	
	if (isset($moduleConfig)) {
		return $moduleConfig;
	}
	
	if (isset($GLOBALS["xoopsModule"]) && is_object($GLOBALS["xoopsModule"]) && $GLOBALS["xoopsModule"]->getVar("dirname", "n") == "tag") {
		if (!empty($GLOBALS["xoopsModuleConfig"])) {
			$moduleConfig = $GLOBALS["xoopsModuleConfig"];
		} else {
			return null;
		}
	} else {
		$module_handler =& xoops_gethandler('module');
		$module = $module_handler->getByDirname("tag");
		
		$config_handler =& xoops_gethandler('config');
		$criteria = new CriteriaCompo(new Criteria('conf_modid', $module->getVar('mid')));
		$configs = $config_handler->getConfigs($criteria);
		foreach (array_keys($configs) as $i) {
			$moduleConfig[$configs[$i]->getVar('conf_name')] = $configs[$i]->getConfValueForOutput();
		}
		unset($configs);
	}
	if ($customConfig = @include XOOPS_ROOT_PATH . "/modules/tag/include/plugin.php") {
		$moduleConfig = array_merge($moduleConfig, $customConfig);
	}
	
	return $moduleConfig;
}

function tag_define_url_delimiter()
{
	if (defined("URL_DELIMITER")) {
		if (!in_array(URL_DELIMITER, array("?","/"))) die("Exit on security");
	} else {
		$moduleConfig = tag_load_config();
		if (empty($moduleConfig["do_urw"])) {
			define("URL_DELIMITER", "?");
		} else {
			define("URL_DELIMITER", "/");
		}
	}
}

function tag_get_delimiter()
{
	xoops_loadLanguage("config", "tag");
	if (!empty($GLOBALS["tag_delimiter"])) return $GLOBALS["tag_delimiter"];
	$moduleConfig = tag_load_config();
	if (!empty($moduleConfig["tag_delimiter"])) return $moduleConfig["tag_delimiter"];
	return array(",", " ", "|", ";");
}


function tag_synchronization()
{
	$module_handler =& xoops_gethandler("module");
	$criteria = new CriteriaCompo(new Criteria("isactive", 1));
	$criteria->add(new Criteria("dirname", "('system', 'tag')", "NOT IN"));
	$modules_obj = $module_handler->getObjects($criteria, true);
	
	$link_handler =& xoops_getmodulehandler("link", "tag");
	$link_handler->deleteAll(new Criteria("tag_modid", "(" . implode(", ", array_keys($modules_obj)) . ")", "NOT IN"), true);
	
	foreach(array_keys($modules_obj) as $mid) {
		$dirname = $modules_obj[$mid]->getVar("dirname");
		if (!@include_once XOOPS_ROOT_PATH . "/modules/{$dirname}/include/plugin.tag.php") {
			if (!@include_once XOOPS_ROOT_PATH . "/modules/tag/plugin/{$dirname}.php") {
				continue;
			}
		}
		$func_tag = "{$dirname}_tag_synchronization";
		if (!function_exists($func_tag)) {
			continue;
		}
		$res = $func_tag($mid);
	}
	
	tag_cleanOrphan();
	return true;
}

function tag_cleanOrphan()
{
	$tag_handler =& xoops_getmodulehandler("tag", "tag");
	
	/* clear item-tag links */
	if (version_compare( mysql_get_server_info(), "4.1.0", "ge" )):
	$sql =  "DELETE FROM {$tag_handler->table_link}" .
	" WHERE ({$tag_handler->keyName} NOT IN ( SELECT DISTINCT {$tag_handler->keyName} FROM {$tag_handler->table}) )";
	else:
	$sql =  "DELETE {$tag_handler->table_link} FROM {$tag_handler->table_link}" .
	" LEFT JOIN {$tag_handler->table} AS aa ON {$tag_handler->table_link}.{$tag_handler->keyName} = aa.{$tag_handler->keyName} " .
	" WHERE (aa.{$tag_handler->keyName} IS NULL)";
	endif;
	if (!$result = $tag_handler->db->queryF($sql)) {
		//xoops_error($tag_handler->db->error());
	}
	
	/* remove empty stats-tag links */
	$sql = "DELETE FROM {$tag_handler->table_stats}" .
	" WHERE tag_count = 0";
	if (!$result = $tag_handler->db->queryF($sql)) {
		//xoops_error($tag_handler->db->error());
	}
	
	/* clear stats-tag links */
	if (version_compare( mysql_get_server_info(), "4.1.0", "ge" )):
	$sql =  "DELETE FROM {$tag_handler->table_stats}" .
	" WHERE ({$tag_handler->keyName} NOT IN ( SELECT DISTINCT {$tag_handler->keyName} FROM {$tag_handler->table}) )";
	else:
	$sql =  "DELETE {$tag_handler->table_stats} FROM {$tag_handler->table_stats}".
			" LEFT JOIN {$tag_handler->table} AS aa ON {$tag_handler->table_stats}.{$tag_handler->keyName} = aa.{$tag_handler->keyName} " .
			" WHERE (aa.{$tag_handler->keyName} IS NULL)";
	endif;
	if (!$result = $tag_handler->db->queryF($sql)) {
		//xoops_error($tag_handler->db->error());
	}
	
	if (version_compare( mysql_get_server_info(), "4.1.0", "ge" )):
	$sql =  "    DELETE FROM {$tag_handler->table_stats}" .
	"    WHERE NOT EXISTS ( SELECT * FROM {$tag_handler->table_link} " .
	"                       WHERE  {$tag_handler->table_link}.tag_modid={$tag_handler->table_stats}.tag_modid" .
	"                       AND  {$tag_handler->table_link}.tag_catid={$tag_handler->table_stats}.tag_catid" .
	"                     )";
	else:
	$sql =  "DELETE {$tag_handler->table_stats} FROM {$tag_handler->table_stats}" .
	" LEFT JOIN {$tag_handler->table_link} AS aa ON (" .
	"                                            {$tag_handler->table_stats}.{$tag_handler->keyName} = aa.{$tag_handler->keyName} " .
	"                                            AND {$tag_handler->table_stats}.tag_modid = aa.tag_modid " .
	"                                            AND {$tag_handler->table_stats}.tag_catid = aa.tag_catid " .
	"                                        ) " .
	" WHERE (aa.tl_id IS NULL)";
	endif;
	if (!$result = $tag_handler->db->queryF($sql)) {
		//xoops_error($tag_handler->db->error());
	}
	
	/* clear empty tags */
	if (version_compare( mysql_get_server_info(), "4.1.0", "ge" )):
	$sql =  "DELETE FROM {$tag_handler->table}" .
	" WHERE ({$tag_handler->keyName} NOT IN ( SELECT DISTINCT {$tag_handler->keyName} FROM {$tag_handler->table_link}) )";
	else:
	$sql =  "DELETE {$tag_handler->table} FROM {$tag_handler->table}" .
	" LEFT JOIN {$tag_handler->table_link} AS aa ON {$tag_handler->table_link}.{$tag_handler->keyName} = aa.{$tag_handler->keyName} " .
	" WHERE (aa.tl_id IS NULL)";
	endif;
	if (!$result = $tag_handler->db->queryF($sql)) {
		//xoops_error($tag_handler->db->error());
	}
	
	return true;
}

function &tag_getTagHandler()
{
    static $tag_handler;
    if (isset($tag_handler)) return $tag_handler;
    
    $tag_handler = null;
    if (!is_object($GLOBALS["xoopsModule"]) || "tag" != $GLOBALS["xoopsModule"]->getVar("dirname")) {
        $module_handler =& xoops_gethandler('module');
        $module =& $module_handler->getByDirname("tag");
        if (!$module || !$module->getVar("isactive")) {
            return $tag_handler;
        }
    }
    $tag_handler = @xoops_getmodulehandler("tag", "tag", true);
    return $tag_handler;
}

/**
 * Function to parse arguments for a page according to $_SERVER['REQUEST_URI']
 * 
 * @var array $args_numeric    array of numeric variable values
 * @var array $args            array of indexed variables: name and value
 * @var array $args_string    array of string variable values
 *
 * @return bool    true on args parsed
 */

/* known issues:
 * - "/" in a string 
 * - "&" in a string 
*/
function tag_parse_args(&$args_numeric, &$args, &$args_string)
{
    $args_abb = array(
                        "c"    => "catid", 
                        "m"    => "modid", 
                        "s"    => "start", 
                        "t"    => "tag", 
                        );
    $args = array();
    $args_numeric = array();
    $args_string = array();
    if (preg_match("/[^\?]*\.php[\/|\?]([^\?]*)/i", $_SERVER['REQUEST_URI'], $matches)) {
        $vars = preg_split("/[\/|&]/", $matches[1]);
        $vars = array_map("trim", $vars);
        if (count($vars) > 0) {
            foreach ($vars as $var) {
                if (is_numeric($var)) {
                    $args_numeric[] = $var;
                } elseif (false === strpos($var, "=")) {
                    if (is_numeric(substr($var, 1))) {
                        $args[$args_abb[strtolower($var{0})]] = intval(substr($var, 1));
                    } else {
                        $args_string[] = urldecode($var);
                    }
                } else {
                    parse_str($var, $args);
                }
            }
        }
    }
    return (count($args) + count($args_numeric) + count($args_string) == 0) ? null : true;
}

/**
 * Function to parse tags(keywords) upon defined delimiters
 * 
 * @var        string    $text_tag    text to be parsed
 *
 * @return    array    tags
 */
function tag_parse_tag($text_tag)
{
    $tags = array();
    if (empty($text_tag)) return $tags;
    
    $delimiters = tag_get_delimiter();
    $tags_raw = explode(",", str_replace($delimiters, ",", $text_tag));
    $tags = array_filter(array_map("trim", $tags_raw));
    
    return $tags;
}


function tag_parse_category($catid = 0)
{	
	$cats = array();
	$parents = array();
	$criteria = new CriteriaCompo(new Criteria('tag_catid', $catid));
	$category_handler = xoops_getModuleHandler('category', 'tag');
	$categorys = $category_handler->getObjects($criteria);
	if (is_object($categorys[0]))
	{
		$cats[$categorys[0]->getVar('tag_catid')] = $categorys[0];
		if ($categorys[0]->getVar('tag_parent_catid') > 0)
		{
			$parents[$categorys[0]->getVar('tag_catid')] = $categorys[0]->getVar('tag_parent_catid');
			while(is_object($categorys[0]) && $categorys[0]->getVar('tag_parent_catid') > 0)
			{
				$criteria = new CriteriaCompo(new Criteria('tag_catid', $categorys[0]->getVar('tag_parent_catid')));
				$categorys = $category_handler->getObjects($criteria);
				if (is_object($categorys[0]))
				{
					$parents[$categorys[0]->getVar('tag_catid')] = $categorys[0]->getVar('tag_parent_catid');
					$cats[$categorys[0]->getVar('tag_catid')] = $categorys[0];
				}
			}
		} else 
			$parents[$categorys[0]->getVar('tag_catid')] = 0;
	}
	array_reverse($parents);
	$result = array();
	$level = 0;
	foreach($parents as $catid => $parent_catid)
	{
		$level++;
		$result[$catid]['term'] = $cats[$catid]->getVar('tag_term');
		$result[$catid]['count'] = $cats[$catid]->getVar('tag_count');
		$result[$catid]['url'] = $cats[$catid]->getURL();
		$result[$catid]['order'] = $level;
	}
	return $result;
}
endif;
?>