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

if (!is_object($GLOBALS["xoopsModule"]) || "tag" != $GLOBALS["xoopsModule"]->getVar("dirname")) {
    xoops_loadLanguage("main", "tag");
}

if (empty($modid) && is_object($GLOBALS["xoopsModule"]) && "tag" != $GLOBALS["xoopsModule"]->getVar("dirname")) {
    $modid = $GLOBALS["xoopsModule"]->getVar("mid");
}

if (!empty($tag_desc)) {
    $page_title = $tag_desc;
} else {
    $module_name = ("tag" == $xoopsModule->getVar("dirname")) ? $xoopsConfig["sitename"] : $xoopsModule->getVar("name");
    $page_title = sprintf(TAG_MD_TAGLIST, $module_name);
}
$xoopsOption["template_main"] = "tag_list.html";
$xoopsOption["xoops_pagetitle"] = strip_tags($page_title);
include XOOPS_ROOT_PATH . "/header.php";
// Adds Stylesheet
$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL."/modules/tag/language/".$GLOBALS['xoopsConfig']['language'].'/style.css');

$mode = empty($mode) ? @$_GET["mode"] : $mode;
switch (strtolower($mode)) {
case "list":
    $mode = "list";
    $sort    = "count";
    $order    = "DESC";
    $limit = empty($tag_config["limit_tag_list"]) ? 10 : $tag_config["limit_tag"];
    break;
case "cloud":
default:
    $mode = "cloud";
    $sort    = "count";
    $order    = "DESC";
    $limit = empty($tag_config["limit_tag_cloud"]) ? 100 : $tag_config["limit_tag"];
    break;
}

$tag_handler =& xoops_getmodulehandler("tag", "tag");
$tag_config = tag_load_config();
tag_define_url_delimiter();

$criteria = new CriteriaCompo();
$criteria->setSort($sort);
$criteria->setOrder($order);
$criteria->setStart($start);
$criteria->setLimit($limit);
$criteria->add( new Criteria("o.tag_status", 0) );
if (!empty($modid)) {
    $criteria->add( new Criteria("l.tag_modid", $modid) );
    if ($catid >= 0) {
        $criteria->add( new Criteria("l.tag_catid", $catid) );
    }
}
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

$font_max = $tag_config["font_max"];
$font_min = $tag_config["font_min"];
$font_ratio = ($count_interval) ? ($font_max - $font_min) / $count_interval : 1;

$tags_data = array();
foreach (array_keys($tags) as $key) {
    $tags_data[] = array(
                    /*
                     * Font-size = ((tag.count - count.min) * (font.max - font.min) / (count.max - count.min) ) * 100%
                     */
                    "id"        => $tags[$key]["id"],
                    "font"      => empty($count_interval) ? 100 : floor( ($tags[$key]["count"] - $count_min) * $font_ratio ) + $font_min,
                    "level"     => empty($count_max) ? 0 : floor( ($tags[$key]["count"] - $count_min) * $level_limit / $count_max ),
                    "term"      => $tags[$key]["term"],
                    "count"     => $tags[$key]["count"],
                    );
}
unset($tags, $tags_term);

if ( !empty($start) || count($tags_data) >= $limit) {
    $count_tag = $tag_handler->getCount($criteria); // modid, catid

    if (strtolower($mode) == "list") {
        include XOOPS_ROOT_PATH . "/class/pagenav.php";
        $nav = new XoopsPageNav($count_tag, $limit, $start, "start", "catid={$catid}&amp;mode={$mode}");
        $pagenav = $nav->renderNav(4);
    } else {
        $pagenav = "<a href=\"" . xoops_getEnv("PHP_SELF") . "?catid={$catid}&amp;mode={$mode}\">" . _MORE . "</a>";
    }
} else {
    $pagenav = "";
}

$xoopsTpl -> assign("lang_jumpto", TAG_MD_JUMPTO);

$xoopsTpl -> assign("tag_page_title", $page_title);
$xoopsTpl -> assign_by_ref("tags", $tags_data);

// Loading module meta data, NOT THE RIGHT WAY DOING IT
$xoopsTpl -> assign("xoops_pagetitle", $xoopsOption["xoops_pagetitle"]);
$xoopsTpl -> assign("xoops_module_header", $xoopsOption["xoops_module_header"]);

include_once "footer.php";
?>