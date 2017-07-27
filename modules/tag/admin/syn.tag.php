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

include_once __DIR__ . '/header.php';
xoops_cp_header();

xoops_load("XoopsFormLoader");
$indexAdmin = new ModuleAdmin();

echo $indexAdmin->addNavigation(basename(__FILE__));
echo $indexAdmin->renderIndex();

$limit = 10;
$modid = intval( @$_GET['modid'] );
$start = intval( @$_GET['start'] );
$limit = isset($_GET['limit']) ? intval( $_GET['limit'] ) : 100;

$sql  = "    SELECT tag_modid, COUNT(DISTINCT tag_id) AS count_tag";
$sql .= "    FROM " . $xoopsDB->prefix("tag_link");
$sql .= "    GROUP BY tag_modid";
$counts_module = array();
$module_list = array();
if ( $result = $xoopsDB->query($sql)) {
    while ($myrow = $xoopsDB->fetchArray($result)) {
        $counts_module[$myrow["tag_modid"]] = $myrow["count_tag"];
    }
    if (!empty($counts_module)) {
        $module_handler =& xoops_gethandler("module");
        $module_list = $module_handler->getList(new Criteria("mid", "(" . implode(", ", array_keys($counts_module)) . ")", "IN"));
    }
}

$opform = new XoopsSimpleForm('', 'moduleform', xoops_getenv("PHP_SELF"), "get");
$tray = new XoopsFormElementTray('');
$mod_select = new XoopsFormSelect(_SELECT, 'modid', $modid);
$mod_select->addOption(-1, TAG_AM_GLOBAL);
$mod_select->addOption(0, TAG_AM_ALL);
foreach ($module_list as $module => $module_name) {
    $mod_select->addOption($module, $module_name . " (" . $counts_module[$module] . ")");
}
$tray->addElement($mod_select);
$num_select = new XoopsFormSelect(TAG_AM_NUM, 'limit', $limit);
foreach (array(10, 50, 100, 500) as $_num) {
    $num_select->addOption($_num);
}
$num_select->addOption(0, _ALL);
$tray->addElement($num_select);
$tray->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
$tray->addElement(new XoopsFormHidden("start", $start));
$opform->addElement($tray);
$opform->display();


if ( isset($_GET['start']) ) {

    $tag_handler =& xoops_getmodulehandler("tag", $xoopsModule->getVar("dirname"));
    
    $criteria = new CriteriaCompo();
    $criteria->setStart($start);
    $criteria->setLimit($limit);
    if ($modid > 0) {
        $criteria->add( new Criteria("l.tag_modid", $modid) );
    }
    $tags = $tag_handler->getByLimit($criteria, false);
    if (empty($tags)) {
        echo "<h2>" . TAG_AM_FINISHED . "</h2>";
    } else {
        
        foreach (array_keys($tags) as $tag_id) {
            $tag_handler->update_stats($tag_id, ( $modid == -1 ) ? 0 : $tags[$tag_id]["modid"]);
        }
        redirect_header("syn.tag.php?modid={$modid}&amp;start=" . ($start + $limit) . "&amp;limit={$limit}", 2, TAG_AM_IN_PROCESS);
    }
}

include_once __DIR__ . '/footer.php';
?>