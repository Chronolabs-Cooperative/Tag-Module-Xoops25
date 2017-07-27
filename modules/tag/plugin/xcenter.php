<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code 
 which is considered copyrighted (c) material of the original comment or credit authors.
 
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * XOOPS tag management module
 *
 * @copyright       The XOOPS project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @since           1.0.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: xcenter.php 2292 2008-10-12 04:53:18Z phppp $
 * @package         tag
 */
if (!defined('XOOPS_ROOT_PATH')) { exit(); }

/**
 * Get item fields:
 * 
 * title		string
 * content		string (html)
 * category		array()
 * time			string
 * link			string
 * url			string (full path)
 * uid			integer
 * uname		string
 * tags			array
 *
 * @var        array    $items    associative array of items: [modid][catid][itemid]
 *
 * @return    boolean
 * 
 */
function xcenter_tag_iteminfo($items)
{
    if (empty($items) || !is_array($items)) {
        return false;
    }
    
    $items_id = array();
    foreach (array_keys($items) as $cat_id) {
        // Some handling here to build the link upon catid
        // catid is not used in xcenter, so just skip it
        foreach (array_keys($items[$cat_id]) as $item_id) {
            // In xcenter, the item_id is "topic_id"
            $items_id[] = intval($item_id);
        }
    }
    $item_handler =& xoops_getmodulehandler('xcenter', 'xcenter');
    $items_obj = $item_handler->getObjects(new Criteria("storyid", "(" . implode(", ", $items_id) . ")", "IN"), true);
    $myts =& MyTextSanitizer::getInstance();
    foreach (array_keys($items) as $cat_id) {
        foreach (array_keys($items[$cat_id]) as $item_id) {
            $item_obj =& $items_obj[$item_id];
            if (is_object($item_obj))
			$items[$cat_id][$item_id] = array(
                "title"     => $item_obj->getVar("subject"),
                "uid"       => $item_obj->getVar("uid"),
                "link"      => "index.php?id={$item_id}",
                "url"       => XOOPS_URL . "/modules/xcenter/index.php?storyid={$item_id}",
                "time"      => formatTimestamp(strtotime($item_obj->getVar("date")), "s"),
                "tags"      => tag_parse_tag($item_obj->getVar("tags", "n")),
                "category"  => tag_parse_category($cat_id),
                "content"   => $myts->displayTarea($item_obj->getVar("page_description"),true,true,true,true,true,true)
                );
        }
    }
    unset($items_obj);    
    return $items;
}

/**
 * Remove orphan tag-item links
 *
 * @return    boolean
 * 
 */
function xcenter_tag_synchronization($mid)
{
    $item_handler =& xoops_getmodulehandler("xcenter", "xcenter");
    $link_handler =& xoops_getmodulehandler("link", "tag");
        
    /* clear tag-item links */
    if (version_compare( mysqli_get_server_info(), "4.1.0", "ge" )):
    $sql =  "    DELETE FROM {$link_handler->table}" .
            "    WHERE " .
            "        tag_modid = {$mid}" .
            "        AND " .
            "        ( tag_itemid NOT IN " .
            "            ( SELECT DISTINCT {$item_handler->keyName} " .
            "                FROM {$item_handler->table} " .
            "                WHERE {$item_handler->table}.approved > 0" .
            "            ) " .
            "        )";
    else:
    $sql =  "    DELETE {$link_handler->table} FROM {$link_handler->table}" .
            "    LEFT JOIN {$item_handler->table} AS aa ON {$link_handler->table}.tag_itemid = aa.{$item_handler->keyName} " .
            "    WHERE " .
            "        tag_modid = {$mid}" .
            "        AND " .
            "        ( aa.{$item_handler->keyName} IS NULL" .
            "            OR aa.approved < 1" .
            "        )";
    endif;
    if (!$result = $link_handler->db->queryF($sql)) {
        //xoops_error($link_handler->db->error());
    }
}

/**
 * Gets the Category Array for XCenter
 * 
 * @param integer $catid
 * @return array
 */
function xcenter_tag_category($catid)
{
	$category_handler =& xoops_getmodulehandler('category', 'xcenter');
	$cat = $category_handler->getCategory($catid);
	return array('catid' => $cat['cat']->getVar('catid'), 'parentid' => $cat['cat']->getVar('parentid'), 'term' => $cat['text']->getVar('title'));
}

/**
 * Gets if Tags is supported and turned on in module
 * 
 * @return boolean
 */
function xcenter_tag_supported()
{
	global $xcenterModule, $xcenterConfigsList, $xcenterConfigs, $xcenterConfigsOptions;
	
	if (empty($xcenterModule))
	{
		if (is_a($xcenterModule = xoops_getHandler('module')->getByDirname('xcenter'), "XoopsModule"))
		{
			if (empty($xcenterConfigsList))
			{
				$xcenterConfigsList = xoops_getHandler('config')->getConfigList($xcenterModule->getVar('mid'));
			}
			return $xcenterConfigsList['tags'];
		}
	}
	return false;
}


/**
 * Gets support versions of the child module (xcenter) and this parent module (tag)
 *
 * @param integer $catid
 * @return array
 */
function xcenter_tag_version()
{
	return array('child' => 2.16, 'parent' => 3.01);
}

?>