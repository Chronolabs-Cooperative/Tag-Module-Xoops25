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

class TagCategories_link extends XoopsObject
{
    /**
     * Constructor
     *
     * @param int $id ID of the tag, deprecated
     */
	function __construct($id = null)
	{
		$this->initVar("cl_id",            XOBJ_DTYPE_INT,     null, false);
		$this->initVar("tag_catid",        XOBJ_DTYPE_INT,     null, false);
		$this->initVar("tag_modcatid",     XOBJ_DTYPE_INT,     null, false);
		$this->initVar("tag_parent_mcid",  XOBJ_DTYPE_INT,     null, false);
        $this->initVar("tag_modid",        XOBJ_DTYPE_INT,     null, false);
        $this->initVar("tag_time",         XOBJ_DTYPE_INT,     null, false);
        $this->initVar("tag_count",        XOBJ_DTYPE_INT,     0);
    }
}

/**
 * Tag object handler class.  
 *
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright   copyright &copy; The XOOPS Project
 *
 * {@link XoopsPersistableObjectHandler} 
 *
 */

class TagCategories_linkHandler extends XoopsPersistableObjectHandler
{

    
    /**
     * Constructor
     *
     * @param object $db reference to the {@link XoopsDatabase} object     
     **/
    function __construct(&$db)
    {
    	parent::__construct($db, "tag_categories_link", "TagCategories_link", "tag_catid", "tag_term");
    }
  
}
?>