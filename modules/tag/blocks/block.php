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
include XOOPS_ROOT_PATH . "/modules/tag/include/vars.php";
include_once XOOPS_ROOT_PATH . "/modules/tag/include/functions.php";

xoops_loadLanguage("blocks", "tag");

/**#@+
 * Function to display tag cloud
 *
 * Developer guide:
 * <ul>
 *    <li>Build your tag_block_cloud_show function, for example newbb_block_tag_cloud_show;</li>
 *    <li>Call the tag_block_cloud_show in your defined block function:<br />
 *        <code>
 *            function newbb_block_tag_cloud_show($options) {
 *                $catid        = $options[4];    // Not used by newbb, Only for demonstration 
 *                if (!@include_once XOOPS_ROOT_PATH."/modules/tag/blocks/block.php") {
 *                    return null; 
 *                } 
 *                $block_content = tag_block_cloud_show($options, "newbb", $catid);
 *                return $block_content;
 *            }
 *        </code>
 *    </li>
 *    <li>Build your tag_block_cloud_edit function, for example newbb_block_tag_cloud_edit;</li>
 *    <li>Call the tag_block_cloud_edit in your defined block function:<br />
 *        <code>
 *            function newbb_block_tag_cloud_edit($options) {
 *                if (!@include_once XOOPS_ROOT_PATH."/modules/tag/blocks/block.php") {
 *                    return null; 
 *                } 
 *                $form = tag_block_cloud_edit($options);
 *                $form .= $CODE_FOR_GET_CATID;    // Not used by newbb, Only for demonstration 
 *                return $form;
 *            }
 *        </code>
 *    </li>
 *    <li>Create your tag_block_cloud template, for example newbb_block_tag_cloud.html;</li>
 *    <li>Include tag_block_cloud template in your created block template:<br />
 *        <code>
 *            <{include file="db:tag_block_cloud.html"}>
 *        </code>
 *    </li>
 * </ul>
 *
 * {@link TagTag} 
 *
 * @param    array     $options:  
 *                    $options[0] - number of tags to display
 *                    $options[1] - time duration, in days, 0 for all the time
 *                    $options[2] - max font size (px or %)
 *                    $options[3] - min font size (px or %)
 */
function tag_block_cloud_show( $options, $dirname = "", $catid = 0 )
{
    global $xoopsDB;

    if (empty($dirname)) {
        $modid = 0;
    } elseif (isset($GLOBALS["xoopsModule"]) && is_object($GLOBALS["xoopsModule"]) && $GLOBALS["xoopsModule"]->getVar("dirname") == $dirname) {
        $modid = $GLOBALS["xoopsModule"]->getVar("mid");
    } else {
        $module_handler =& xoops_gethandler("module");
        $module = $module_handler->getByDirname($dirname);
        $modid = $module->getVar("mid");
    }
    
    $block = array();
    $tag_handler =& xoops_getmodulehandler("tag", "tag");
    tag_define_url_delimiter();
    
    $criteria = new CriteriaCompo();
    $criteria->setSort("count");
    $criteria->setOrder("DESC");
    $criteria->setLimit($options[0]);
    $criteria->add( new Criteria("o.tag_status", 0) );
    if (!empty($modid)) {
        $criteria->add( new Criteria("l.tag_modid", $modid) );
        if ($catid >= 0) {
            $criteria->add( new Criteria("l.tag_catid", $catid) );
        }
    }
    if (!$tags = $tag_handler->getByLimit($criteria, empty($options[1]))) {
        return $block;
    }
    
    $count_max = 0;
    $count_min = 0;
    $tags_term = array();
    foreach (array_keys($tags) as $key) {
        if ($tags[$key]["count"] > $count_max) $count_max = $tags[$key]["count"];
        if ($tags[$key]["count"] < $count_min || $count_min == 0) $count_min = $tags[$key]["count"];
        $tags_term[] = strtolower($tags[$key]["term"]);
    }
    array_multisort($tags_term, SORT_ASC, $tags);
    $count_interval = $count_max - $count_min;
    $level_limit = 5;
    
    $font_max = $options[2];
    $font_min = $options[3];
    $font_ratio = ($count_interval) ? ($font_max - $font_min) / $count_interval : 1;
    
    $tags_data = array();
    foreach (array_keys($tags) as $key) {
        $tags_data[] = array(
                        "id"    => $tags[$key]["id"],
                        "font"    => ($count_interval) ? floor( ($tags[$key]["count"] - $count_min) * $font_ratio + $font_min ) : 100,
                        "level"    => empty($count_max) ? 0 : floor( ($tags[$key]["count"] - $count_min) * $level_limit / $count_max ),
                        "term"    => $tags[$key]["term"],
                        "count"    => $tags[$key]["count"],
                        );
    }
    unset($tags, $tags_term);
    
    $block["tags"] =& $tags_data;
    $block["tag_dirname"] = "tag";
    if (!empty($modid)) {
        $module_handler =& xoops_gethandler('module');
        if ($module_obj =& $module_handler->get($modid)) {
            $block["tag_dirname"] = $module_obj->getVar("dirname");
        }
    }
    $GLOBALS['xoTheme']->addStylesheet(XOOPS_URL."/modules/tag/language/".$GLOBALS['xoopsConfig']['language'].'/blocks.css');
    return $block;
}

function tag_block_cloud_edit($options)
{
    $form  =    TAG_MB_ITEMS . ":&nbsp;&nbsp;<input type=\"text\" name=\"options[0]\" value=\"" . $options[0] . "\" /><br />";
    $form .=    TAG_MB_TIME_DURATION . ":&nbsp;&nbsp;<input type=\"text\" name=\"options[1]\" value=\"" . $options[1] . "\" /><br />";
    $form .=    TAG_MB_FONTSIZE_MAX . ":&nbsp;&nbsp;<input type=\"text\" name=\"options[2]\" value=\"" . $options[2] . "\" /><br />";
    $form .=    TAG_MB_FONTSIZE_MIN . ":&nbsp;&nbsp;<input type=\"text\" name=\"options[3]\" value=\"" . $options[3] . "\" /><br />";
    
    return $form;
}


/**#@+
 * Function to display top tag list
 *
 * Developer guide:
 * <ul>
 *    <li>Build your tag_block_top_show function, for example newbb_block_tag_top_show;</li>
 *    <li>Call the tag_block_top_show in your defined block function:<br />
 *        <code>
 *            function newbb_block_tag_top_show($options) {
 *                $catid        = $options[3];    // Not used by newbb, Only for demonstration 
 *                if (!@include_once XOOPS_ROOT_PATH."/modules/tag/blocks/block.php") {
 *                    return null; 
 *                }
 *                $block_content = tag_block_top_show($options, "newbb", $catid);
 *                return $block_content;
 *            }
 *        </code>
 *    </li>
 *    <li>Build your tag_block_top_edit function, for example newbb_block_tag_top_edit;</li>
 *    <li>Call the tag_block_top_edit in your defined block function:<br />
 *        <code>
 *            function newbb_block_tag_top_edit($options) {
 *                if (!@include_once XOOPS_ROOT_PATH."/modules/tag/blocks/block.php") {
 *                    return null; 
 *                } 
 *                $form = tag_block_cloud_edit($options);
 *                $form .= $CODE_FOR_GET_CATID;    // Not used by newbb, Only for demonstration 
 *                return $form;
 *            }
 *        </code>
 *    </li>
 *    <li>Create your tag_block_top template, for example newbb_block_tag_top.html;</li>
 *    <li>Include tag_block_top template in your created block template:<br />
 *        <code>
 *            <{include file="db:tag_block_top.html"}>
 *        </code>
 *    </li>
 * </ul>
 *
 * {@link TagTag} 
 *
 * @param    array     $options:  
 *                    $options[0] - number of tags to display
 *                    $options[1] - time duration, in days, 0 for all the time
 *                    $options[2] - sort: a - alphabet; c - count; t - time
 */
function tag_block_top_show( $options, $dirname = "", $catid = 0 )
{
    global $xoopsDB;

    if (empty($dirname)) {
        $modid = 0;
    } elseif (isset($GLOBALS["xoopsModule"]) && is_object($GLOBALS["xoopsModule"]) && $GLOBALS["xoopsModule"]->getVar("dirname") == $dirname) {
        $modid = $GLOBALS["xoopsModule"]->getVar("mid");
    } else {
        $module_handler =& xoops_gethandler("module");
        $module = $module_handler->getByDirname($dirname);
        $modid = $module->getVar("mid");
    }

    $block = array();
    $tag_handler =& xoops_getmodulehandler("tag", "tag");
    tag_define_url_delimiter();
    
    $criteria = new CriteriaCompo();
    $sort = ($options[2] == "a" || $options[2] == "alphabet") ? "count" : $options[2];
    $criteria->setSort("count");
    $criteria->setOrder("DESC");
    $criteria->setLimit($options[0]);
    $criteria->add( new Criteria("o.tag_status", 0) );
    if (!empty($options[1])) {
        $criteria->add( new Criteria("l.tag_time", time() - $options[1] * 24 * 3600, ">") );
    }
    if (!empty($modid)) {
        $criteria->add( new Criteria("l.tag_modid", $modid) );
        if ($catid >= 0) {
            $criteria->add( new Criteria("l.tag_catid", $catid) );
        }
    }
    if (!$tags = $tag_handler->getByLimit($criteria, empty($options[1]))) {
        return $block;
    }
    
    $count_max = 0;
    $count_min = 0;
    $tags_sort = array();
    foreach (array_keys($tags) as $key) {
        if ($tags[$key]["count"] > $count_max) $count_max = $tags[$key]["count"];
        if ($tags[$key]["count"] < $count_min) $count_min = $tags[$key]["count"];
        if ($options[2] == "a" || $options[2] == "alphabet") {
            $tags_sort[] = strtolower($tags[$key]["term"]);
        }
    }
    $count_interval = $count_max - $count_min;
    
    /*
    $font_max = $options[1];
    $font_min = $options[2];
    $font_ratio = ($count_interval) ? ($font_max - $font_min) / $count_interval : 1;
    */
    if (!empty($tags_sort)) {
        array_multisort($tags_sort, SORT_ASC, $tags);
    }
    
    $tags_data = array();
    foreach (array_keys($tags) as $key) {
        $tags_data[] = array(
                        "id"    => $tags[$key]["id"],
                        //"level"    => ($tags[$key]["count"] - $count_min) * $font_ratio + $font_min,
                        "term"  => $tags[$key]["term"],
                        "count" => $tags[$key]["count"],
                        );
    }
    unset($tags, $tags_term);
    
    $block["tags"] =& $tags_data;
    $block["tag_dirname"] = "tag";
    if (!empty($modid)) {
        $module_handler =& xoops_gethandler('module');
        if ($module_obj =& $module_handler->get($modid)) {
            $block["tag_dirname"] = $module_obj->getVar("dirname");
        }
    }
    $GLOBALS['xoTheme']->addStylesheet(XOOPS_URL."/modules/tag/language/".$GLOBALS['xoopsConfig']['language'].'/blocks.css');
    return $block;
}

function tag_block_top_edit($options)
{
    $form  =    TAG_MB_ITEMS . ":&nbsp;&nbsp;<input type=\"text\" name=\"options[0]\" value=\"" . $options[0] . "\" /><br />";
    $form .=    TAG_MB_TIME_DURATION . ":&nbsp;&nbsp;<input type=\"text\" name=\"options[1]\" value=\"" . $options[1] . "\" /><br />";
    $form .=    TAG_MB_SORT . ":&nbsp;&nbsp;<select name='options[2]'>";
    $form .= "<option value='a'";
    if ($options[2] == "a") $form .= " selected='selected' ";
    $form .= ">" . TAG_MB_ALPHABET . "</option>";
    $form .= "<option value='c'";
    if ($options[2] == "c") $form .= " selected='selected' ";
    $form .= ">" . TAG_MB_COUNT . "</option>";
    $form .= "<option value='t'";
    if ($options[2] == "t") $form .= " selected='selected' ";
    $form .= ">" . TAG_MB_TIME . "</option>";
    $form .= "</select>";
    
    return $form;
}

/*
 * $options:
 *                    $options[0] - number of tags to display
 *                    $options[1] - time duration
 *                    $options[2] - max font size (px or %)
 *                    $options[3] - min font size (px or %)
 *                    $options[4] - cumulus_flash_width
 *                    $options[5] - cumulus_flash_height
 *                    $options[6] - cumulus_flash_background
 *                    $options[7] - cumulus_flash_transparency
 *                    $options[8] - cumulus_flash_color
 *                    $options[9] - cumulus_flash_hicolor
 *                    $options[10] - cumulus_flash_speed
 */
function tag_block_cumulus_show( $options, $dirname = "", $catid = 0 )
{
	global $xoopsDB;
	
	if (empty($dirname)) {
		$modid = 0;
	} elseif (isset($GLOBALS["xoopsModule"]) && is_object($GLOBALS["xoopsModule"]) && $GLOBALS["xoopsModule"]->getVar("dirname") == $dirname) {
		$modid = $GLOBALS["xoopsModule"]->getVar("mid");
	} else {
		$module_handler =& xoops_gethandler("module");
		$module = $module_handler->getByDirname($dirname);
		$modid = $module->getVar("mid");
	}
	
	$block = array();
	$tag_handler =& xoops_getmodulehandler("tag", "tag");
	tag_define_url_delimiter();
	
	$criteria = new CriteriaCompo();
	$criteria->setSort("count");
	$criteria->setOrder("DESC");
	$criteria->setLimit($options[0]);
	$criteria->add( new Criteria("o.tag_status", 0) );
	if (!empty($modid)) {
		$criteria->add( new Criteria("l.tag_modid", $modid) );
		if ($catid >= 0) {
			$criteria->add( new Criteria("l.tag_catid", $catid) );
		}
	}
	if (!$tags = $tag_handler->getByLimit($criteria, empty($options[1]))) {
		return $block;
	}
	
	$count_max = 0;
	$count_min = 0;
	$tags_term = array();
	foreach (array_keys($tags) as $key) {
		if ($tags[$key]["count"] > $count_max) $count_max = $tags[$key]["count"];
		if ($tags[$key]["count"] < $count_min || $count_min == 0) $count_min = $tags[$key]["count"];
		$tags_term[] = strtolower($tags[$key]["term"]);
	}
	array_multisort($tags_term, SORT_ASC, $tags);
	$count_interval = $count_max - $count_min;
	$level_limit = 5;
	
	$font_max = $options[2];
	$font_min = $options[3];
	$font_ratio = ($count_interval) ? ($font_max - $font_min) / $count_interval : 1;
	
	$tags_data = array();
	foreach (array_keys($tags) as $key) {
		$tags_data[] = array(
				"id"    => $tags[$key]["id"],
				"font"    => ($count_interval) ? floor( ($tags[$key]["count"] - $count_min) * $font_ratio + $font_min ) : 12,
				"level"    => empty($count_max) ? 0 : floor( ($tags[$key]["count"] - $count_min) * $level_limit / $count_max ),
				"term"    => $tags[$key]["term"],
				"count"    => $tags[$key]["count"],
		);
	}
	unset($tags, $tags_term);
	$block["tags"] =& $tags_data;
	
	$block["tag_dirname"] = "tag";
	if (!empty($modid)) {
		$module_handler =& xoops_gethandler('module');
		if ($module_obj =& $module_handler->get($modid)) {
			$block["tag_dirname"] = $module_obj->getVar("dirname");
		}
	}
	$flash_params = array(
			'flash_url' => XOOPS_URL."/modules/tag/swf/cumulus.swf",
			'width' => $options[4],
			'height' => $options[5],
			'background' => preg_replace('/(#)/ie','',$options[6]),
			'tcolor' => "0x".preg_replace('/(#)/ie','',$options[8]),
			'hicolor' => "0x".preg_replace('/(#)/ie','',$options[9]),
			'tcolor2' => "0x".preg_replace('/(#)/ie','',$options[10]),
			'speed' => $options[11]
	);
	
	$output = '<tags>';
	$xoops_url = XOOPS_URL;
	foreach ($tags_data as $term) {
		$output .= "\n<a href='".XOOPS_URL."/modules/tag/view.tag.php?id={$term['id']}' style='{$term['font']}'>{$term['term']}</a>";
	}
	$output .= '</tags>';
	$flash_params['tags_formatted_flash'] = urlencode($output) ;
	if ($options[7] === "transparent" ) {
		$flash_params['transparency'] = 'widget_so.addParam("wmode", "transparent");';
	}
	$block["flash_params"] =$flash_params;
	$GLOBALS['xoTheme']->addScript(XOOPS_URL."/modules/tag/js/swfobject.js");
	$GLOBALS['xoTheme']->addScript("", array(), '
	var rnumber = Math.floor(Math.random()*9999999);
	var widget_so = new SWFObject("'.$block["flash_params"]['flash_url'].'}>?r="+rnumber, "cumulusflash", "'.$block["flash_params"]['width'] .'", "'.$block["flash_params"]['height'].'", "9", "'.$block["flash_params"]['background'].'");
	'.$block["flash_params"]['transparency'].'
	widget_so.addParam("allowScriptAccess", "always");
	widget_so.addVariable("tcolor", "'.$block["flash_params"]['tcolor'].'");
	widget_so.addVariable("hicolor", "'.$block["flash_params"]['hicolor'].'");
	widget_so.addVariable("tcolor2", "'.$block["flash_params"]['tcolor2'].'");
	widget_so.addVariable("tspeed", "'.$block["flash_params"]['speed'].'");
	widget_so.addVariable("distr", "true");
	widget_so.addVariable("mode", "tags");
	widget_so.addVariable("tagcloud", "'.$block["flash_params"]['tags_formatted_flash'].'");
	widget_so.write("tags");');
	return $block;
	
}

function tag_block_cumulus_edit($options)
{
	
	xoops_load('XoopsFormLoader');	
	$form  = new XoopsSimpleForm("","","");
	$form->addElement(new XoopsFormText(TAG_MB_ITEMS, "options[0]", 25, 25,$options[0]));
	$form->addElement(new XoopsFormText(TAG_MB_TIME_DURATION, "options[1]", 25, 25,$options[1]));
	$form->addElement(new XoopsFormText(TAG_MB_FONTSIZE_MAX, "options[2]", 25, 25,$options[2]));
	$form->addElement(new XoopsFormText(TAG_MB_FONTSIZE_MIN, "options[3]", 25, 25,$options[3]));
	$form->addElement(new XoopsFormText(TAG_MB_FLASH_WIDTH, "options[4]", 25, 25,$options[4]));
	$form->addElement(new XoopsFormText(TAG_MB_FLASH_HEIGHT, "options[5]", 25, 25,$options[5]));
	$form->addElement(new XoopsFormColorPicker(TAG_MB_FLASH_TRANSPARENCY,"options[6]",$options[6]));
	$form_cumulus_flash_transparency = new XoopsFormSelect(TAG_MB_FLASH_TRANSPARENCY,"options[7]",$options[7]);
	$form_cumulus_flash_transparency->addOption(0,_NO);
	$form_cumulus_flash_transparency->addOption("transparent",TAG_MB_FLASH_TRANSPARENT);
	$form->addElement($form_cumulus_flash_transparency);
	$form->addElement(new XoopsFormColorPicker(TAG_MB_FLASH_MINFONTCOLOR,"options[8]",$options[8]));
	$form->addElement(new XoopsFormColorPicker(TAG_MB_FLASH_MAXFONTCOLOR,"options[9]",$options[9]));
	$form->addElement(new XoopsFormColorPicker(TAG_MB_FLASH_HILIGHTFONTCOLOR,"options[10]",$options[10]));
	$form->addElement(new XoopsFormText(TAG_MB_FLASH_SPEED, "options[11]", 25, 25,$options[11]));
	
	return $form->render();
} 
?>