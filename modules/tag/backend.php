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

include __DIR__ . "/include/feeds.module.php";

if ($tagConfigsList['htaccess'])
{
	if (is_object($GLOBALS["xoopsModule"]) || "tag" != $GLOBALS["xoopsModule"]->getVar("dirname", "n")) {
		if (!empty($term))
			$url = XOOPS_URL . "/" . $tagConfigsList['base'] . "/feed-" . $GLOBALS["xoopsModule"]->getVar("dirname", "n") . $tagConfigsList['rss'];
		else
			$url = XOOPS_URL . "/" . $tagConfigsList['base'] . "/feed-" . $GLOBALS["xoopsModule"]->getVar("dirname", "n") . $tagConfigsList['rss'];
	} else {
		if (!empty($term))
			$url = XOOPS_URL . "/" . $tagConfigsList['base'] . "/feed" . $tagConfigsList['rss'];
		else 
			$url = XOOPS_URL . "/" . $tagConfigsList['base'] . "/feed" . $tagConfigsList['rss'];
	}
	if (!strpos($url, $_SERVER["REQUEST_URI"]))
	{
		redirect_header($url, 0, "");
		exit(0);
	}
}

$catid = 0;
$modid = ($tagModule->getVar('mid')!=$modid && $modid != 0) ? $modid : 0;

$GLOBALS['xoopsLogger']->activated = false;
if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}
header('Content-Type:text/xml; charset=utf-8');

include_once $GLOBALS['xoops']->path('class/template.php');
$tpl                 = new XoopsTpl();
$tpl->caching        = basename(__FILE__)."-$catid-$modid--";
$tpl->cache_lifetime = 3600;
if (!$tpl->is_cached(__DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'tag_rss_parent')) {
    xoops_load('XoopsLocal');
    $channel = $data = array();
    $channel['title'] = XoopsLocal::convert_encoding(htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES));
    $channel['link'] = XOOPS_URL . '/';
    $channel['desc'] = XoopsLocal::convert_encoding(htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES));
    $channel['lastbuild'] = formatTimestamp(time(), 'rss');
    $channel['webmaster'] = checkEmail($xoopsConfig['adminmail'], true);
    $channel['editor'] = checkEmail($xoopsConfig['adminmail'], true);
    $channel['category'] = $tagModule->getVar('title');
    $channel['generator'] = strtoupper($tagModule->getVar('dirname'));
    $channel['language'] = _LANGCODE;
    $channel['image']['url'] = XOOPS_URL . '/images/logo.png';
    $dimension = getimagesize(XOOPS_ROOT_PATH . '/images/logo.png');
    if (empty($dimension[0])) {
        $width = 88;
    } else {
        $width = ($dimension[0] > 144) ? 144 : $dimension[0];
    }
    if (empty($dimension[1])) {
        $height = 31;
    } else {
        $height = ($dimension[1] > 400) ? 400 : $dimension[1];
    }
    $channel['image']['width'] = $width;
    $channel['image']['height'] = $height;
    $items = tag_feed_tags($catid, $modid, $tagConfigList['items_perfeeds'], 0);
    $keys = array_keys($items);
    sort($keys, SORT_DESC);
    foreach($keys as $key)
        if (isset($items[$key]['items']) && is_array($items[$key]['items']))
        foreach($items[$key]['items'] as $key => $rssitems)
            $feeditems[] = $rssitems;
        else 
            $feeditems[] = $items[$key];
    $channel['item'] = $feeditems;
    $data['rss'] = array('attributes' => array('version'=>'2.0'), 'value' => array('channel' => $channel));
    $tpl->assign('rss_child_template', __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'tag_rss_child.xml');
    $tpl->assign('data', $data);
}
die($tpl->display(__DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'tag_rss_parent.xml'));
    
?>