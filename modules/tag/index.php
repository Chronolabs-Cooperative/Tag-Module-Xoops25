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

include __DIR__ . "/header.php";

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

include XOOPS_ROOT_PATH . "/header.php";
// Adds Stylesheet
$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL."/modules/tag/language/".$GLOBALS['xoopsConfig']['language'].'/style.css');

$tag_handler =& xoops_getmodulehandler("tag", "tag");

$criteria = new CriteriaCompo();
$criteria->setSort("tag_count");
$criteria->setOrder("DESC");
$criteria->setLimit(empty($tagConfigsList["limit_tag_cloud"]) ? 100 : $tagConfigsList["limit_tag"]);
$tags = $tag_handler->getObjects($criteria, false, false);
$count_max = 0;
$count_min = 0;
$tags_term = array();
foreach (array_keys($tags) as $key) {
    if ($tags[$key]["tag_count"] > $count_max) $count_max = $tags[$key]["tag_count"];
    if ($tags[$key]["tag_count"] < $count_min) $count_min = $tags[$key]["tag_count"];
    $tags_term[] = strtolower($tags[$key]["tag_term"]);
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
                    "id"    => $tags[$key]["tag_id"],
                    "font"  => empty($count_interval) ? 100 : floor( ($tags[$key]["tag_count"] - $count_min) * $font_ratio ) + $font_min,
                    "level" => empty($count_max) ? 0 : floor( ($tags[$key]["tag_count"] - $count_min) * $level_limit / $count_max ),
                    "term"  => $tags[$key]["tag_term"],
                    "count" => $tags[$key]["tag_count"],
                    "url"   => XOOPS_URL . '/modules/' .basename(__DIR__) . "/view.tag.php?id=".$tags[$key]["tag_id"]
                    );
}
unset($tags, $tags_term);


$pagenav = "<a href=\"" . XOOPS_URL . "/modules/tag/list.tag.php\">" . _MORE . "</a>";

if (is_object($GLOBALS["xoopsModule"]) || "tag" != $GLOBALS["xoopsModule"]->getVar("dirname", "n"))
    $rssurl = XOOPS_URL  . "/modules/" . basename(__DIR__) . "/backend.php?dirname=".$GLOBALS["xoopsModule"]->getVar("dirname", "n");
else
    $rssurl = XOOPS_URL  . "/modules/" . basename(__DIR__) . "/backend.php";
if ($tagConfigsList['htaccess'])
{
    if (is_object($GLOBALS["xoopsModule"]) || "tag" != $GLOBALS["xoopsModule"]->getVar("dirname", "n")) {
        $rssurl = XOOPS_URL . "/" . $tagConfigsList['base'] . "/feed" . $GLOBALS["xoopsModule"]->getVar("dirname", "n") . $tagConfigsList['rss'];
    } else {
        $rssurl = XOOPS_URL . "/" . $tagConfigsList['base'] . "/feed" . $tagConfigsList['rss'];
    }
}

$GLOBALS['xoopsTpl']->assign("tag_images_path", XOOPS_URL  . "/modules/" . basename(__DIR__) . "/images");
$GLOBALS['xoopsTpl']->assign("tag_rss_url", $rssurl);
$GLOBALS['xoopsTpl']->assign("lang_jumpto",      TAG_MD_JUMPTO);
$GLOBALS['xoopsTpl']->assign("pagenav",          $pagenav);
$GLOBALS['xoopsTpl']->assign("tags", $tags_data);

// Display Template
$GLOBALS['xoopsTpl']->display(__DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . "tag_index.html");

include_once "footer.php";
?>