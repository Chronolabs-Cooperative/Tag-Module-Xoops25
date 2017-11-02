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

class TagCategories extends XoopsObject
{
    /**
     * Constructor
     *
     * @param int $id ID of the tag, deprecated
     */
	function TagCategories($id = null)
    {
        $this->initVar("tag_catid",            XOBJ_DTYPE_INT,     null, false);
        $this->initVar("tag_parent_catid",            XOBJ_DTYPE_INT,     null, false);
        $this->initVar("tag_term",          XOBJ_DTYPE_TXTBOX,     "", true);
        $this->initVar("tag_status",        XOBJ_DTYPE_INT,     0);
        $this->initVar("tag_count",         XOBJ_DTYPE_INT,     0);
    }
    
    
    function getURL()
    {
    	global $tagModule, $tagConfigsList, $tagConfigs, $tagConfigsOptions;
    	global $modid, $term, $termid, $catid, $start, $sort, $order, $mode, $dirname;
    	$start = 0;
    	$sort = "DESC";
    	$order = "count";
    	$mode = "list";
    	$termid = $this->getVar('tag_catid');
    	if ($tagConfigsList['htaccess'])
    	{
    		if (is_object($GLOBALS["xoopsModule"]) || "tag" != $GLOBALS["xoopsModule"]->getVar("dirname", "n")) {
    			$url = XOOPS_URL . "/" . $tagConfigsList['base'] . "/view/cat/$start/$sort/$order/$mode/$termid-" . $GLOBALS["xoopsModule"]->getVar("dirname", "n") . $tagConfigsList['html'];
    			
    		} else {
    			$url = XOOPS_URL . "/" . $tagConfigsList['base'] . "/view/cat/$start/$sort/$order/$mode/$termid" . $tagConfigsList['html'];
    		}
    	} else {
    		$url = XOOPS_URL . "/modules/".basename(dirname(__DIR__)) . "/view.cat.php?start=$start&sort=$sort&order=$order&mode=$mode&termid=$termid";
    	}
    	return $url;
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

class TagCategoriesHandler extends XoopsPersistableObjectHandler
{

    
    /**
     * Constructor
     *
     * @param object $db reference to the {@link XoopsDatabase} object     
     **/
    function __construct(&$db)
    {
    	parent::__construct($db, "tag_categories", "TagCategories", "tag_catid", "tag_term");
    }
    
    
  	/**
  	 * Adds a category to the database and correlates it with existing categories or makes a new entrys
  	 * 
  	 * @param number $catid
  	 * @param number $parentid
  	 * @param string $term
  	 * @param object $mod
  	 * @return integer
  	 */
    function addCategory($catid = 0, $parentid = 0, $term = '', $mod = NULL)
    {
    	$categories_link_handler = xoops_getmodulehandler('categories_link', 'tag');
    	$criteria = new CriteriaCompo(new Criteria('tag_modcatid', $catid));
    	$criteria->add(new Criteria('tag_modid', $mod->getVar('modid')));
    	$criteriab = new CriteriaCompo(new Criteria('tag_term', "$term", 'LIKE'));
    	if ($categories_link_handler->getCount($criteria)==0)
    	{
    		$localparentid = $localcatid = 0;
    		if ($parentid != 0)
    		{
    			$parentcat = $parents = array();
    			$parentcat['parentid'] = $parentid;
    			if (is_file($plugin = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR . $mod->getVar('dirname') . '.php'))
		    	{
		    		require_once $plugin;
		    		if (function_exists($func = $mod->getVar('dirname') . "_tag_category"))
		    		{
		    			$cat = $func($parentcat['parentid']);
		    			$localparentid = self::addCategory($parentcat['term'], $parentcat['catid'], $parentcat['parentid'], $mod);
		    		}
		    	}
    			
    		}
    		
    		$obj = self::create(true);
    		$obj->setVar('tag_parent_catid', $localparentid);
    		$obj->setVar('tag_term', $term);
    		$obj->setVar('tag_status', true);
    		$obj->setVar('tag_count', 0);
    		$localcatid = self::insert($obj, true);
    		
    		$obj = $categories_link_handler->create(true);
    		$obj->setVar('tag_catid', $localcatid);
    		$obj->setVar('tag_modcatid', $catid);
    		$obj->setVar('tag_parent_mcid', $parentid);
    		$obj->setVar('tag_modid', $mod->getVar('modid'));
    		$obj->setVar('tag_time', time());
    		$obj->setVar('tag_count', 0);
    		$cl_id = self::insert($obj, true);
    		
    		return $localcatid;
    	} elseif( self::getCount($criteriab) > 0) {
    		$catlinkobjs = self::getObjects($criteriab);
	    	if (!empty($catlinkobjs[0]))
	    		return $catlinksobjs[0]->getVar('tag_catid');	
    	} else {
    		$catlinkobjs = $categories_link_handler->getObjects($criteria);
    		if (!empty($catlinkobjs[0]))
    			return $catlinksobjs[0]->getVar('tag_catid');
    	}
    }
    
    
    /**
     * Gets the local Category ID for a module Category ID
     * 
     * @param number $modcatid
     * @param number $modid
     * @return number
     */
    function getCatID($modcatid = 0, $modid = 0)
    {
    	$categories_link_handler = xoops_getmodulehandler('categories_link', 'tag');
    	$criteria = new CriteriaCompo(new Criteria('tag_modcatid', $modcatid));
    	$criteria->add(new Criteria('tag_modid', $modid));
    	if ($categories_link_handler->getCount($criteria)==0)
    	{
    		$module_handler = xoops_gethandler('module');
    		$mod = $module_handler->get($modid);
    		if (is_file($plugin = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR . $mod->getVar('dirname') . '.php'))
    		{
    			require_once $plugin;
    			if (function_exists($func = $mod->getVar('dirname') . "_tag_category"))
    			{
    				$cat = $func($modcatid);
    				return self::addCategory($cat['term'], $cat['catid'], $cat['parentid'], $mod);
    			}
    		}
    	} else {
    		$catlinkobjs = $categories_link_handler->getObjects($criteria);
    		if (!empty($catlinkobjs[0]))
    			return $catlinksobjs[0]->getVar('tag_catid');
    	}
    }
}
?>