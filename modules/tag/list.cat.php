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

if ($tagConfigsList['htaccess'])
{
	if (is_object($GLOBALS["xoopsModule"]) || basename(__DIR__) != $GLOBALS["xoopsModule"]->getVar("dirname", "n")) {
		$url = XOOPS_URL . "/" . $tagConfigsList['base'] . "/list/cat/$start/$sort/$order/$mode/$termid-" . $GLOBALS["xoopsModule"]->getVar("dirname", "n") . $tagConfigsList['html'];
	} else {
		$url = XOOPS_URL . "/" . $tagConfigsList['base'] . "/list/cat/$start/$sort/$order/$mode/$termid" . $tagConfigsList['html'];
	}
	if (!strpos($url, $_SERVER["REQUEST_URI"]))
	{
		redirect_header($url, 0, "");
		exit(0);
	}
}
$result = array();
$categories_handler = xoops_getModuleHandler("categories", "tag");
$tag_handler = xoops_getmodulehandler("tag", "tag");
$tag_link_handler = xoops_getmodulehandler("link", "tag");
if ($termid == 0)
{
	$criteria = new Criteria('tag_parent_catid', 0);
} else {
	$criteria = new Criteria('tag_catid', $termid);
}
include XOOPS_ROOT_PATH . "/header.php";
// Adds Stylesheet
$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL."/modules/tag/language/".$GLOBALS['xoopsConfig']['language'].'/style.css');
$limit  = empty($tagConfigsList["items_perpage"]) ? 10 : $tagConfigsList["items_perpage"];

$categories = $categories_handler->getObjects($criteria);
foreach($categories as $category)
{
	$result[$category->getVar('tag_catid')]['url'] = $category->getURL();
	$result[$category->getVar('tag_catid')]['title'] = $category->getVar('tag_term');
	$result[$category->getVar('tag_catid')]['count'] = $category->getVar('tag_count');
	switch ($order)
	{
		default:
		case "time":
			$criteriab = new CriteriaCompo(new Criteria('tag_catid', $category->getVar('tag_catid')));
			if (basename(__DIR__) != $GLOBALS["xoopsModule"]->getVar("dirname", "n"))
				$criteriab->add(new Criteria('tag_modid', $GLOBALS["xoopsModule"]->getVar('mid')));
			$criteriab->setSort('tag_time');
			$criteriab->setOrder('ASC');
			$criteriab->setStart(0);
			$criteriab->setLimit($limit);
			$links = $tag_link_handler->getObjects($criteriab);
			$tagids = array();
			foreach($links as $link)
			{
				$tagids[$link->getVar('tag_id')] = $link->getVar('tag_id');
			}
			break;
		case "count":
			$criteriab = new CriteriaCompo(new Criteria('tag_catid', $category->getVar('tag_catid')));
			if (basename(__DIR__) != $GLOBALS["xoopsModule"]->getVar("dirname", "n"))
				$criteriab->add(new Criteria('tag_modid', $GLOBALS["xoopsModule"]->getVar('mid')));
			$links = $tag_link_handler->getObjects($criteriab);
			$tmpids = array();
			foreach($links as $link)
			{
				$tmpids[$link->getVar('tag_id')] = $link->getVar('tag_id');
			}
			$criteriac = new CriteriaCompo(new Criteria('tag_id', '(' . implode(", ", $tmpids). ")", "IN"));
			$criteriac->setSort('tag_count');
			$criteriac->setOrder('DESC');
			$criteriac->setStart(0);
			$criteriac->setLimit($limit);
			$tags = $tag_handler->getObjects($criteriac);
			$tagids = array();
			foreach($tags as $tag)
			{
				$tagids[$tag->getVar('tag_id')] = $tag->getVar('tag_id');
			}
	}
	if (!empty($tagids))
	{
		$criteriad = new CriteriaCompo(new Criteria('tag_id', '(' . implode(", ", $tagids). ")", "IN"));
		$tags = $tag_handler->getObjects($criteriac);
		foreach($tags as $tag)
		{
			$result[$category->getVar('tag_catid')]['tags'][$tag->getVar('tag_id')]['term'] = $tag->getVar('tag_term');
			$result[$category->getVar('tag_catid')]['tags'][$tag->getVar('tag_id')]['url'] = $tag->getURL();
		}
	}
	$criteriab = new Criteria('tag_parent_catid', $category->getVar('tag_catid'));
	$children = $categories_handler->getObjects($criteria);
	foreach($children as $child)
	{
		$result[$category->getVar('tag_catid')]['children'][$child->getVar('tag_catid')]['url'] = $child->getURL();
		$result[$category->getVar('tag_catid')]['children'][$child->getVar('tag_catid')]['title'] = $child->getVar('tag_term');
		$result[$category->getVar('tag_catid')]['children'][$child->getVar('tag_catid')]['count'] = $child->getVar('tag_count');
		switch ($order)
		{
			default:
			case "time":
				$criteriab = new CriteriaCompo(new Criteria('tag_catid', $child->getVar('tag_catid')));
				if (basename(__DIR__) != $GLOBALS["xoopsModule"]->getVar("dirname", "n"))
					$criteriab->add(new Criteria('tag_modid', $GLOBALS["xoopsModule"]->getVar('mid')));
				$criteriab->setSort('tag_time');
				$criteriab->setOrder('ASC');
				$criteriab->setStart(0);
				$criteriab->setLimit($limit/2);
				$links = $tag_link_handler->getObjects($criteriab);
				$tagids = array();
				foreach($links as $link)
				{
					$tagids[$link->getVar('tag_id')] = $link->getVar('tag_id');
				}
				break;
			case "count":
				$criteriab = new CriteriaCompo(new Criteria('tag_catid', $child->getVar('tag_catid')));
				if (basename(__DIR__) != $GLOBALS["xoopsModule"]->getVar("dirname", "n"))
					$criteriab->add(new Criteria('tag_modid', $GLOBALS["xoopsModule"]->getVar('mid')));
				$links = $tag_link_handler->getObjects($criteriab);
				$tmpids = array();
				foreach($links as $link)
				{
					$tmpids[$link->getVar('tag_id')] = $link->getVar('tag_id');
				}
				$criteriac = new CriteriaCompo(new Criteria('tag_id', '(' . implode(", ", $tmpids). ")", "IN"));
				$criteriac->setSort('tag_count');
				$criteriac->setOrder('DESC');
				$criteriac->setStart(0);
				$criteriac->setLimit($limit/2);
				$tags = $tag_handler->getObjects($criteriac);
				$tagids = array();
				foreach($tags as $tag)
				{
					$tagids[$tag->getVar('tag_id')] = $tag->getVar('tag_id');
				}
				break;
		}
		if (!empty($tagids))
		{
			$criteriad = new CriteriaCompo(new Criteria('tag_id', '(' . implode(", ", $tagids). ")", "IN"));
			$tags = $tag_handler->getObjects($criteriac);
			foreach($tags as $tag)
			{
				$result[$category->getVar('tag_catid')]['children'][$child->getVar('tag_catid')]['tags'][$tag->getVar('tag_id')]['term'] = $tag->getVar('tag_term');
				$result[$category->getVar('tag_catid')]['children'][$child->getVar('tag_catid')]['tags'][$tag->getVar('tag_id')]['url'] = $tag->getURL();
			}
		}
	}
}

$module_name = (basename(__DIR__) == $xoopsModule->getVar("dirname")) ? $xoopsConfig["sitename"] : $xoopsModule->getVar("name");
$page_title = sprintf(TAG_MD_TAGLIST, $module_name);
$xoopsOption["template_main"] = "tag_category_list.html";
$xoopsOption["xoops_pagetitle"] = strip_tags($page_title);

$GLOBALS['xoopsTpl']->assign("data", $result);
$GLOBALS['xoopsTpl']->assign("lang_jumpto", TAG_MD_JUMPTO);
$GLOBALS['xoopsTpl']->assign("tag_page_title", $page_title);
$GLOBALS['xoopsTpl']->assign("xoops_pagetitle", $page_title);

include_once "footer.php";
?>