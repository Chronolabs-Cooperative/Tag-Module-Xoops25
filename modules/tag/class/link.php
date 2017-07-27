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
}
?>