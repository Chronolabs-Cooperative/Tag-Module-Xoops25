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

include dirname(__FILE__) . "/header.php";

if (empty($termid) && empty($term) ) {
    redirect_header(XOOPS_URL . "/modules/" . $GLOBALS["xoopsModule"]->getVar("dirname") . "/index.php", 2, TAG_MD_INVALID);
    exit();
}
$tag_handler =& xoops_getmodulehandler("tag", "tag");
if (!empty($termid)) {
    if (!$tag_obj =& $tag_handler->get($termid)) {
        redirect_header(XOOPS_URL . "/modules/" . $GLOBALS["xoopsModule"]->getVar("dirname") . "/index.php", 2, TAG_MD_INVALID);
        exit();
    }
    $term = $tag_obj->getVar("tag_term", "n");
} else {
    if (!$tags_obj = $tag_handler->getObjects(new Criteria("tag_term", $myts->addSlashes(trim($term))))) {
        redirect_header(XOOPS_URL . "/modules/" . $GLOBALS["xoopsModule"]->getVar("dirname") . "/index.php", 2, TAG_MD_INVALID);
        exit();
    }
    $tag_obj =& $tags_obj[0];
    $termid = $tag_obj->getVar("tag_id");
}

if ($tagConfigsList['htaccess'])
{
	if (is_object($GLOBALS["xoopsModule"]) || "tag" != $GLOBALS["xoopsModule"]->getVar("dirname", "n")) {
		if (!empty($term))
			$url = XOOPS_URL . "/" . $tagConfigsList['base'] . "/view/tag/$start/$sort/$order/$mode/$term-" . $GLOBALS["xoopsModule"]->getVar("dirname", "n") . $tagConfigsList['html'];
		else
			$url = XOOPS_URL . "/" . $tagConfigsList['base'] . "/view/tag/$start/$sort/$order/$mode/$termid-" . $GLOBALS["xoopsModule"]->getVar("dirname", "n") . $tagConfigsList['html'];
	} else {
		if (!empty($term))
			$url = XOOPS_URL . "/" . $tagConfigsList['base'] . "/view/tag/$start/$sort/$order/$mode/$term" . $tagConfigsList['html'];
		else 
			$url = XOOPS_URL . "/" . $tagConfigsList['base'] . "/view/tag/$start/$sort/$order/$mode/$termid" . $tagConfigsList['html'];
	}
	if (!strpos($url, $_SERVER["REQUEST_URI"]))
	{
		redirect_header($url, 0, "");
		exit(0);
	}
}

$module_name = ("tag" == $xoopsModule->getVar("dirname", "n")) ? $xoopsConfig["sitename"] : $xoopsModule->getVar("name", "n");
$page_title = sprintf(TAG_MD_TAGVIEW, htmlspecialchars($term), $module_name);

$xoopsOption["template_main"] = "tag_view.html";
$xoopsOption["xoops_pagetitle"] = strip_tags($page_title);
include XOOPS_ROOT_PATH . "/header.php";
// Adds Stylesheet
$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL."/modules/tag/language/".$GLOBALS['xoopsConfig']['language'].'/style.css');

$limit  = empty($tagConfigsList["items_perpage"]) ? 10 : $tagConfigsList["items_perpage"];

$criteria = new CriteriaCompo(new Criteria("o.tag_id", $termid));
$criteria->setSort("tag_$sort");
$criteria->setOrder($order);
$criteria->setStart($start);
$criteria->setLimit($limit);
if (!empty($modid)) {
    $criteria->add( new Criteria("o.tag_modid", $modid) );
}
if ($catid >= 0) {
	$criteria->add( new Criteria("o.tag_catid", $catid) );
}
$items = $tag_handler->getItems($criteria); // Tag, imist, start, sort, order, modid, catid

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

$items_data = array();
$uids = array();
include_once XOOPS_ROOT_PATH . "/modules/tag/include/tagbar.php";
foreach ($res as $modid => $itemsvalues) {
	foreach($itemvalues as $catid => $values) {
		foreach($itemvalues as $itemid => $item) {
			$item["module"]     = $modules_obj[$modid]->getVar("name");
			$item["dirname"]    = $modules_obj[$modid]->getVar("dirname", "n");
			$item["tags"]       = @tagBar($item["tags"]);
    		$items_data[]       = $item;
    		$uids[$item['uid']] = $item['uid'];
		}
	}
}
xoops_load("UserUtility");
$users = XoopsUserUtility::getUnameFromIds(array_keys($uids));

foreach (array_keys($items_data) as $key) {
	if (isset($items_data[$key]["uid"]) && !empty($items_data[$key]["uid"]) && $items_data[$key]["uid"] != 0)
	{
		if (!isset($items_data[$key]["uname"]) || empty($items_data[$key]["uname"]))
			$items_data[$key]["uname"] = $users[$items_data[$key]["uid"]];
		$items_data[$key]["userurl"] = XOOPS_URL . '/userinfo.php?uid=' . $items_data[$key]["uid"];
	}
}

if ( !empty($start) || count($items_data) >= $limit) {
    $count_item = $tag_handler->getItemCount($termid, $modid, $catid); // Tag, modid, catid
    xoops_load('PageNav');
    $nav = new XoopsPageNav($count_item, $limit, $start, "start", "id={$termid}&catid={$catid}&sort={$sort}&order={$order}&mode={$mode}&dirname={$_GET['dirname']}");
    $pagenav = $nav->renderNav(4);
} else {
    $pagenav = "";
}

$tag_addon = array();
if (!empty($GLOBALS["TAG_MD_ADDONS"])) {
    $tag_addon["title"] = TAG_MD_TAG_ON;
    foreach ($GLOBALS["TAG_MD_ADDONS"] as $key => $_tag) {
        $_term = (empty($_tag["function"]) || !function_exists($_tag["function"])) ? $term : $_tag["function"]($term);
        $tag_addon["addons"][] = "<a href=\"" . sprintf($_tag["link"], urlencode($_term)) . "\" target=\"{$key}\" title=\"{$_tag['title']}\">{$_tag['title']}</a>";
    }
}

$xoopsTpl->assign("module_name", $GLOBALS["xoopsModule"]->getVar("name"));
$xoopsTpl->assign("tag_id", $termid);
$xoopsTpl->assign("tag_term", $term);
$xoopsTpl->assign("tag_page_title", $page_title);
$xoopsTpl->assign_by_ref("tag_addon", $tag_addon);
$xoopsTpl->assign_by_ref("tag_data", $items_data);
$xoopsTpl->assign_by_ref("pagenav", $pagenav);

$xoopsTpl -> assign("xoops_pagetitle", $page_title);

include_once "footer.php";
?>