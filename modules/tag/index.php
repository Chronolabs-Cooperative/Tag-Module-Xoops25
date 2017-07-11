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
global $modid, $term, $termid, $catid, $start, $sort, $order, $mode;

include dirname(__FILE__) . "/header.php";

$limit = empty($tagConfigsList["limit_tag_cloud"]) ? 100 : $tagConfigsList["limit_tag_cloud"];


if ($tagConfigsList['htaccess'])
{
	if (is_object($GLOBALS["xoopsModule"]) || "tag" != $GLOBALS["xoopsModule"]->getVar("dirname", "n")) {
		$url = XOOPS_URL . "/" . $tagConfigsList['base'] . "/index" . $GLOBALS["xoopsModule"]->getVar("dirname", "n") . $tagConfigsList['html'];
	} else {
		$url = XOOPS_URL . "/" . $tagConfigsList['base'] . "/index" . $tagConfigsList['html'];
	}
	if (!strpos($url, $_SERVER["REQUEST_URI"]))
	{
		redirect_header($url, 0, "");
		exit(0);
	}
}


$page_title = sprintf(TAG_MD_TAGLIST, $xoopsConfig["sitename"]);
$xoopsOption["template_main"] = "tag_index.html";
include XOOPS_ROOT_PATH . "/header.php";
// Adds Stylesheet
$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL."/modules/tag/language/".$GLOBALS['xoopsConfig']['language'].'/style.css');

$tag_handler =& xoops_getmodulehandler("tag", "tag");

$criteria = new CriteriaCompo();
$criteria->setSort("tag_count");
$criteria->setOrder("DESC");
$criteria->setLimit(empty($tagConfigsList["limit_tag_cloud"]) ? 100 : $tagConfigsList["limit_tag"]);
$tags = $tag_handler->getByLimit($criteria);

$count_max = 0;
$count_min = 0;
$tags_term = array();
foreach (array_keys($tags) as $key) {
    if ($tags[$key]["count"] > $count_max) $count_max = $tags[$key]["count"];
    if ($tags[$key]["count"] < $count_min) $count_min = $tags[$key]["count"];
    $tags_term[] = strtolower($tags[$key]["term"]);
}
array_multisort($tags_term, SORT_ASC, $tags);
$count_interval = $count_max - $count_min;
$level_limit = 5;

$font_max = $tagConfigsList["font_max"];
$font_min = $tagConfigsList["font_min"];
$font_ratio = ($count_interval) ? ($font_max - $font_min) / $count_interval : 1;

$tags_data = array();
foreach (array_keys($tags) as $key) {
    $tags_data[] = array(
                    "id"    => $tags[$key]["id"],
                    "font"  => empty($count_interval) ? 100 : floor( ($tags[$key]["count"] - $count_min) * $font_ratio ) + $font_min,
                    "level" => empty($count_max) ? 0 : floor( ($tags[$key]["count"] - $count_min) * $level_limit / $count_max ),
                    "term"  => $tags[$key]["term"],
                    "count" => $tags[$key]["count"],
                    );
}
unset($tags, $tags_term);
$pagenav = "<a href=\"" . XOOPS_URL . "/modules/tag/list.tag.php\">" . _MORE . "</a>";

$GLOBALS['xoopsTpl']->assign("lang_jumpto",      TAG_MD_JUMPTO);
$GLOBALS['xoopsTpl']->assign("pagenav",          $pagenav);
$GLOBALS['xoopsTpl']->assign("tag_page_title",   $page_title);
$GLOBALS['xoopsTpl']->assign_by_ref("tags",      $tags_data);
$GLOBALS['xoopsTpl']->assign("xoops_pagetitle", $page_title);

include_once "footer.php";
?>