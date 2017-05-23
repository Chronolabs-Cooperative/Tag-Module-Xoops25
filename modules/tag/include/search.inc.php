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
    exit();
}

function &tag_search($queryarray, $andor, $limit, $offset, $userid, $sortby = "tag_term ASC")
{
    global $xoopsDB, $xoopsConfig, $myts, $xoopsUser;

    $ret = array();
    $count = is_array($queryarray) ? count($queryarray) : 0;
    $sql = "SELECT tag_id, tag_term FROM " . $xoopsDB->prefix("tag_tag");
    if ($count > 0) {
        if ($andor == "exact") {
            $sql .= " WHERE tag_term = '{$queryarray[0]}'";
            for ($i = 1 ; $i < $count; $i++) {
                $sql .= " {$andor} tag_term = '{$queryarray[$i]}'";
            }
        } else {
            $sql .= " WHERE tag_term LIKE '%{$queryarray[0]}%'";
            for ($i = 1 ; $i < $count; $i++) {
                $sql .= " {$andor} tag_term LIKE '%{$queryarray[$i]}%'";
            }
        }
    } else {
        return $ret;
    }

    if ($sortby) {
        $sql .= " ORDER BY {$sortby}";
    }
    $result = $xoopsDB->query($sql, $limit, $offset);
    $i = 0;
     while ($myrow = $xoopsDB->fetchArray($result)) {
        $ret[$i]['link'] = "view.tag.php?tag=" . $myrow['tag_id'];
        $ret[$i]['title'] = $myrow['tag_term'];
        $i++;
    }

    return $ret;
}
?>