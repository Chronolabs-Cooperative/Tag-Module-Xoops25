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

 
if (!defined('XOOPS_ROOT_PATH')) {
    die("XOOPS root path not defined");
}

xoops_load('XoopsFormLoader');

class XoopsFormTag extends XoopsFormText
{

    /**
     * Constructor
     * 
     * @param    string    $name       "name" attribute
     * @param    int        $size        Size
     * @param    int        $maxlength    Maximum length of text
     * @param    mixed    $value      Initial text or itemid
     * @param    int        $catid      category id (applicable if $value is itemid)
     */
    function __construct($name, $size, $maxlength, $value = null, $catid = 0)
    {
        include XOOPS_ROOT_PATH . "/modules/tag/include/vars.php";
        if (!is_object($GLOBALS["xoopsModule"]) || "tag" != $GLOBALS["xoopsModule"]->getVar("dirname")) {
            xoops_loadLanguage("main", "tag");
        }
        $value = empty($value) ? "" : $value;
        // itemid
        if ( !empty($value) && is_numeric($value) && is_object($GLOBALS["xoopsModule"]) ) {
            $modid = $GLOBALS["xoopsModule"]->getVar("mid");
            $tag_handler =& xoops_getmodulehandler("tag", "tag");
            if ($tags = $tag_handler->getByItem($value, $modid, $catid)) {
                $value = htmlspecialchars(implode(", ", $tags));
            } else {
                $value = "";
            }
        }
        $caption = TAG_MD_TAGS;
        parent::__construct($caption, $name, $size, $maxlength, $value);
    }

    /**
     * Prepare HTML for output
     * 
     * @return    string  HTML
     */
    function render()
    {
        $delimiters = tag_get_delimiter();
        foreach (array_keys($delimiters) as $key) {
            $delimiters[$key] = "<em style=\"font-weight: bold; color: red; font-style: normal;\">" . htmlspecialchars($delimiters[$key]) . "</em>";
        }
        $render  = "<input type='text' name='" . $this->getName() . "' id='" . $this->getName() . "' size='" . $this->getSize() . "' maxlength='" . $this->getMaxlength() . "' value='" . $this->getValue() . "' " . $this->getExtra() . " />";
        $render .= "<br />" . TAG_MD_TAG_DELIMITER . ": [" . implode("], [", $delimiters) . "]";
        return $render;
    }
}
?>