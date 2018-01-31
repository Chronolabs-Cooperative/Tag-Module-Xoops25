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

if (!defined("XOOPS_ROOT_PATH")) {
    exit();
}

class TagLink extends XoopsObject
{
    /**
     * Constructor
     */
	function __construct()
    {
        $this->initVar("tl_id",         XOBJ_DTYPE_INT,     null, false);
        $this->initVar("tag_id",        XOBJ_DTYPE_INT,     0);
        $this->initVar("tag_modid",     XOBJ_DTYPE_INT,     0);
        $this->initVar("tag_catid",     XOBJ_DTYPE_INT,     0);
        $this->initVar("tag_itemid",    XOBJ_DTYPE_INT,     0);
        $this->initVar("tag_time",      XOBJ_DTYPE_INT,     0);
    }
}

/**
 * Tag link handler class.  
 * @package tag
 *
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright   copyright &copy; The XOOPS Project
 *
 * {@link XoopsPersistableObjectHandler} 
 *
 */

class TagLinkHandler extends XoopsPersistableObjectHandler
{
    var $table_stats;
    
    /**
     * Constructor
     *
     * @param object $db reference to the {@link XoopsDatabase} object     
     **/
    function __construct(&$db)
    {
    	parent::__construct($db, "tag_link", "TagLink", "tl_id", "tag_itemid");
        $this->table_stats = $this->db->prefix("tag_stats");
    }
    
    /**
     * clean orphan links from database
     * 
     * @return     bool    true on success
     */
    function cleanOrphan()
    {
        return parent::cleanOrphan($this->db->prefix("tag_tag"), "tag_id");
    }
    
    /**
     * Gets the latest Tag Object array of items;
     * 
     * @param number $catid
     * @param number $modid
     * @param number $limit
     * @param number $start
     */
    function getLatestTags($catid = 0, $modid = 0, $start = 0, $limit = 10, $asarray = false)
    {
        global $tagModule, $tagConfigsList, $tagConfigs, $tagConfigsOptions;
        $tag_handler = xoops_getModuleHandler('tag', 'tag');
        $criteria = new CriteriaCompo(new Criteria('1', '1'));
        if (!empty($catid) && $catid<>0)
            $criteria->add(new Criteria('tag_catid', $catid));
        if (!empty($modid) && $modid<>0 && $modid != $tagModule->getVar('mid'))
            $criteria->add(new Criteria('tag_modid', $modid));
        $criteria->setSort("tag_time");
        $criteria->setOrder('DESC');
        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $result = array();
        foreach($this->getObjects($criteria) as $key => $link)
        {
            $criteria = new CriteriaCompo();
            $criteria->add( new Criteria("l.tag_status", 0) );
            $criteria->add( new Criteria("o.tag_id", $link->getVar('tag_id')) );
            if (!empty($modid)) {
                $criteria->add( new Criteria("l.tag_modid", $modid) );
            }
            if (!empty($catid)) {
                $criteria->add( new Criteria("l.tag_catid", $catid) );
            }
            foreach ($tag_handler->getItems($criteria) as $id => $values)
                $result[] = $values;
        }
        if (is_array($result) && !empty($result))
            return $result;
        return false;
    }
}
?>