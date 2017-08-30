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
global $modid, $term, $termid, $catid, $start, $sort, $order, $mode, $dirname;

include dirname(__DIR__) . "/header.php";

/**
 * Gets a Feed Items Array as per xoops_version.php as well as other calling files.
 * 
 * @param number $items
 * @param boolean $system
 */
if (!function_exists("tag_feed_tags"))
{
    function tag_feed_tags($limit = 15, $system = false)
    {
        
        global $tagModule, $tagConfigsList, $tagConfigs, $tagConfigsOptions;
        global $modid, $term, $termid, $catid, $start, $sort, $order, $mode, $dirname;
        
        $link_handler = xoops_getmodulehandler("link", "tag");
        $items = $link_handler->getLatestTags($catid, $modid, $limit, $start);
        
        $items_module = array();
        $modules_obj = $res = array();
        if (!empty($items)) {
            foreach (array_keys($items) as $key) {
                $items_module[$items[$key]["modid"]][$items[$key]["catid"]][$items[$key]["itemid"]] = array();
            }
            $module_handler =& xoops_gethandler('module');
            $modules_obj = $module_handler->getObjects(new Criteria("mid", "(" . implode(", ", array_keys($items_module)) . ")", "IN"), true);
            foreach (array_keys($modules_obj) as $mid) {
                $dirname = $modules_obj[$mid]->getVar("dirname", "n");
                if (!@include_once XOOPS_ROOT_PATH . "/modules/{$dirname}/include/plugin.tag.php") {
                    if (!@include_once XOOPS_ROOT_PATH . "/modules/tag/plugin/{$dirname}.php") {
                        continue;
                    }
                }
                $func_tag = "{$dirname}_tag_iteminfo";
                if (!function_exists($func_tag)) {
                    continue;
                }
                $func_support = "{$dirname}_tag_supported";
                if (function_exists($func_support)) {
                    if ($func_support())
                        $res[$mid]= $func_tag($items_module[$mid]);
                } else
                    $res[$mid]= $func_tag($items_module[$mid]);
            }
        }
        
        $rssitems = $rssitem = array();
        $uids = array();
        include_once XOOPS_ROOT_PATH . "/modules/tag/include/tagbar.php";
        foreach ($res as $modid => $itemsvalues) {
            foreach($itemvalues as $catid => $values) {
                foreach($itemvalues as $itemid => $item) {
                    $rssitem = array();
                    $rssitem["category"]   = $modules_obj[$modid]->getVar("name");
                    $rssitem["guid"]       = $item['uid'] . '-' . $modules_obj[$modid]->getVar("dirname", "n").'-'.md5(json_encode($item));
                    $rssitem["title"]      = $item['title'];
                    $rssitem["link"]       = $item['url'];
                    $rssitem["tags"]       = $item['tags'];
                    $rssitem["pubDate"]    = formatTimestamp($itime = strtotime($item['date']), 'rss');
                    if (!empty($item['content']))
                        $rssitem["description"] = htmlspecialchars($item['content']);
                    $rssitems[$itime]['items'][] = $rssitem;
                }
            }
        }
        
        if (is_array($rssitems) && count($rssitems) > 0 && !empty($rssitems))
        {
            return $rssitems;
        }
        return array();
    }
}
