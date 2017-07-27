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

class TagTag extends XoopsObject
{
    /**
     * Constructor
     *
     * @param int $id ID of the tag, deprecated
     */
    function __construct($id = null)
    {
        self::initVar("tag_id",            XOBJ_DTYPE_INT,     null, false);
        self::initVar("tag_term",          XOBJ_DTYPE_TXTBOX,     "", true);
        self::initVar("tag_status",        XOBJ_DTYPE_INT,     0);
        self::initVar("tag_count",         XOBJ_DTYPE_INT,     0);
    }
    
    function getURL()
    {
    	global $tagModule, $tagConfigsList, $tagConfigs, $tagConfigsOptions;
    	global $modid, $term, $termid, $catid, $start, $sort, $order, $mode, $dirname;
    	$start = 0;
    	$sort = "DESC";
    	$order = "count";
    	$mode = "list";
    	$term = $this->getVar('tag_term');
    	if ($tagConfigsList['htaccess'])
    	{
		    if (is_object($GLOBALS["xoopsModule"]) || "tag" != $GLOBALS["xoopsModule"]->getVar("dirname", "n")) {
		    	$url = XOOPS_URL . "/" . $tagConfigsList['base'] . "/view/tag/$start/$sort/$order/$mode/$term-" . $GLOBALS["xoopsModule"]->getVar("dirname", "n") . $tagConfigsList['html'];
		   
		    } else {
		    	$url = XOOPS_URL . "/" . $tagConfigsList['base'] . "/view/tag/$start/$sort/$order/$mode/$term" . $tagConfigsList['html'];
		    }
    	} else {
    		$url = XOOPS_URL . "/modules/".basename(dirname(__DIR__)) . "/view.tag.php?start=$start&sort=$sort&order=$order&mode=$mode&term=$term";
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

class TagTagHandler extends XoopsPersistableObjectHandler
{
    var $table_link;
    var $table_stats;
    var $table_categories_link;
    var $table_categories;
    
    /**
     * Constructor
     *
     * @param object $db reference to the {@link XoopsDatabase} object     
     **/
    function __construct(&$db)
    {
    	parent::__construct($db, "tag_tag", "TagTag", "tag_id", "tag_term");
        $this->table_link = $GLOBALS['xoopsDB']->prefix("tag_link");
        $this->table_stats = $GLOBALS['xoopsDB']->prefix("tag_stats");
        $this->table_categories_link = $GLOBALS['xoopsDB']->prefix("tag_categories_link");
        $this->table_categories = $GLOBALS['xoopsDB']->prefix("tag_categories");
    }
    
    /**
     * Get tags linked to an item
     * 
     * @param    integer    $itemid    item ID
     * @param    integer    $modid    module ID, optional
     * @param    integer    $catid    id of corresponding category, optional
     * @return     array    associative array of tags (id, term)
     */
    function getByItem($itemid, $modid = 0, $catid = 0)
    {
        $ret = array();
       
        $itemid    = intval($itemid);
        $modid = (empty($modid) && is_object($GLOBALS["xoopsModule"]) && "tag" != $GLOBALS["xoopsModule"]->getVar("dirname") ) ? $GLOBALS["xoopsModule"]->getVar("mid") : intval($modid);
        if (empty($itemid)) return $ret;
        
        if ($catid<>0)
        {
        	$categories_handler = xoops_getmodulehandler('categories', 'tag');
        	$catid = $categories_handler->getCatID($catid, $modid);
        }
        
        if ($modid<>0)
	        $sql =  "SELECT o.tag_id, o.tag_term" .
	                " FROM {$this->table_link} AS l " .
	                " LEFT JOIN {$this->table} AS o ON o.{$this->keyName} = l.{$this->keyName} " .
	                " WHERE  l.tag_itemid = {$itemid} AND l.tag_modid = {$modid}" .
	                    (empty($catid) ? "" : ( " AND l.tag_catid=" . intval($catid))) .
	                " ORDER BY o.tag_count DESC";
	    else 
	       	$sql =  "SELECT o.tag_id, o.tag_term" .
			       	" FROM {$this->table_link} AS l " .
			       	" LEFT JOIN {$this->table} AS o ON o.{$this->keyName} = l.{$this->keyName} " .
			       	" WHERE  l.tag_itemid = {$itemid}" .
			       	(empty($catid) ? "" : ( " AND l.tag_catid=" . intval($catid))) .
			       	" ORDER BY o.tag_count DESC";
        if ( ($result = $GLOBALS['xoopsDB']->query($sql)) == false) {
            return $ret;
        }
        while ($myrow = $GLOBALS['xoopsDB']->fetchArray($result)) {
            $ret[$myrow[$this->keyName]] = $myrow["tag_term"];
        }
        return $ret;
    }
    
    /**
     * Update tags linked to an item
     * 
     * @param    array    $tags
     * @param    integer    $itemid    item ID
     * @param    integer    $modid    module ID or module dirname, optional
     * @param    integer    $catid    id of corresponding category, optional
     * @return     boolean
     */
    function updateByItem($tags, $itemid, $modid = "", $catid = 0)
    {
        $catid  = intval($catid);
        $itemid = intval($itemid);
        
        
        if (!empty($modid) && !is_numeric($modid)) {
            if (is_object($GLOBALS["xoopsModule"]) && $modid == $GLOBALS["xoopsModule"]->getVar("dirname") ) {
                $modid = $GLOBALS["xoopsModule"]->getVar("mid");
            } else {
                $module_handler =& xoops_gethandler("module");
                if ($module_obj = $module_handler->getByDirname($modid)) {
                    $modid = $module_obj->getVar("mid");
                } else {
                    $modid = 0;
                }
            }
        } elseif (is_object($GLOBALS["xoopsModule"])) {
            $modid = $GLOBALS["xoopsModule"]->getVar("mid");
        }
        
        if ($catid<>0)
        {
        	$modcatid = $catid;
        	$categories_handler = xoops_getmodulehandler('categories', 'tag');
        	$catid = $categories_handler->getCatID($catid, $modid);
        }
        
        if (empty($itemid) || empty($modid)) return false;
        
        if (empty($tags)) {
            $tags = array();
        } elseif (!is_array($tags)) {
            include_once XOOPS_ROOT_PATH . "/modules/tag/include/functions.php";
            $tags = tag_parse_tag(addslashes(stripslashes($tags)));
        }
        
        $tags_existing      = self::getByItem($itemid, $modid, $catid);
        $tags_delete        = array_diff(array_values($tags_existing), $tags);
        $tags_add           = array_diff($tags, array_values($tags_existing));
        
        $tags_update = array();
        
        if (!empty($tags_delete)) {
            $tags_delete = array_map(array($GLOBALS['xoopsDB'], "quoteString"), $tags_delete);
            if ($tags_id = self::getIds(new Criteria("tag_term", "(" . implode(", ", $tags_delete) . ")", "IN"))) {
                $sql =  "DELETE FROM {$this->table_link}" .
                        " WHERE " .
                        "     {$this->keyName} IN (" . implode(", ", $tags_id) . ")" .
                        "     AND tag_modid = {$modid} AND tag_catid = {$catid} AND tag_itemid = {$itemid}"; 
                if ( ($result = $GLOBALS['xoopsDB']->queryF($sql)) == false) {
                }
                $sql =  "DELETE FROM " . $this->table .
                        " WHERE ".
                        "    tag_count < 2 AND ".
                        "     {$this->keyName} IN (" . implode(", ", $tags_id) . ")"; 
                if ( ($result = $GLOBALS['xoopsDB']->queryF($sql)) == false) {
                    //xoops_error($GLOBALS['xoopsDB']->error());
                }
                
                $sql =  "UPDATE " . $this->table .
                        " SET tag_count = tag_count - 1" .
                        " WHERE " .
                        "     {$this->keyName} IN (" . implode(", ", $tags_id) . ")"; 
                if ( ($result = $GLOBALS['xoopsDB']->queryF($sql)) == false) {
                	foreach($tags_id as $id)
                	{
                		$sql =  "UPDATE " . $this->table_categories .
                		" SET tag_count = tag_count - 1" .
                		" WHERE " .
                		"     `tag_catid` = '$catid'"; 
                		$GLOBALS['xoopsDB']->queryF($sql);
                		$sql =  "UPDATE " . $this->table_categories_link .
                		" SET tag_count = tag_count - 1" .
                		" WHERE " .
                		"     `tag_modcatid` = '$modcatid' AND".
                		"     `tag_modid` = '$modid'"; 
                		$GLOBALS['xoopsDB']->queryF($sql);
                	}
                }
                $tags_update = $tags_id;
            }
        }
        
        if (!empty($tags_add)) {
            $tag_link = array();
            $tag_count = array();
            foreach($tags_add as $tag) {
                if ($tags_id = self::getIds(new Criteria("tag_term", $tag))) {
                    $tag_id = $tags_id[0];
                    $tag_count[] = $tag_id;
                } else {
                    $tag_obj =& self::create();
                    $tag_obj->setVar("tag_term", $tag);
                    $tag_obj->setVar("tag_count", 1);
                    self::insert($tag_obj);
                    $tag_id = $tag_obj->getVar("tag_id");
                    unset($tag_obj);
                }
                $tag_link[] = "({$tag_id}, {$itemid}, {$catid}, {$modid}, " . time() . ")";
                $tags_update[] = $tag_id;
                $sql =  "UPDATE " . $this->table_categories .
                " SET tag_count = tag_count + 1" .
                " WHERE " .
                "     `tag_catid` = '$catid'";
                $GLOBALS['xoopsDB']->queryF($sql);
                $sql =  "UPDATE " . $this->table_categories_link .
                " SET tag_count = tag_count + 1" .
                " WHERE " .
                "     `tag_modcatid` = '$modcatid' AND".
                "     `tag_modid` = '$modid'";
                $GLOBALS['xoopsDB']->queryF($sql);
            }
            $sql =  "INSERT INTO {$this->table_link}" .
                    " (tag_id, tag_itemid, tag_catid, tag_modid, tag_time) " .
                    " VALUES " . implode(", ", $tag_link);
            if ( ($result = $GLOBALS['xoopsDB']->queryF($sql)) == false) {
                //xoops_error($GLOBALS['xoopsDB']->error());
            }
            if (!empty($tag_count)) {
                $sql = "UPDATE " . $this->table .
                        " SET tag_count = tag_count+1" .
                        " WHERE " .
                        "     {$this->keyName} IN (" . implode(", ", $tag_count) . ")"; 
                if ( ($result = $GLOBALS['xoopsDB']->queryF($sql)) == false) {
                    //xoops_error($GLOBALS['xoopsDB']->error());
                }
            }
        }
        foreach($tags_update as $tag_id) {
            self::update_stats($tag_id, $modid, $catid);
        }
        return true;
    }
    
    function update_stats($tag_id, $modid = 0, $catid = 0)
    {
        $tag_id    = intval($tag_id);
        if (empty($tag_id)) return true;
        
        $modid    = intval($modid);
        $catid    = empty($modid) ? 0 : intval($catid);
        
        
        if ($catid<>0)
        {
        	$categories_handler = xoops_getmodulehandler('categories', 'tag');
        	$catid = $categories_handler->getCatID($catid, $modid);
        }
        
        $count = 0;
        $sql =     "    SELECT COUNT(*) " . 
                "    FROM {$this->table_link}" .
                "    WHERE tag_id = {$tag_id}" .
                    (empty($modid) ? "" : " AND tag_modid = {$modid}").
                    (($catid < 0) ? "" : " AND tag_catid = {$catid}");
        if ( ($result = $GLOBALS['xoopsDB']->query($sql)) == false) {
            //xoops_error($GLOBALS['xoopsDB']->error());
        } else {
            list($count) = $GLOBALS['xoopsDB']->fetchRow($result);
        }
        if (empty($modid)) {
            $tag_obj =& self::get($tag_id);
            if (empty($count)) {
                self::delete($tag_obj);
            } else {
                $tag_obj->setVar("tag_count", $count);
                self::insert($tag_obj, true);
            }
        } else {
            if (empty($count)) {
                $sql = "DELETE FROM {$this->table_stats}" .
                        " WHERE " .
                        "     {$this->keyName} = {$tag_id}" .
                        "     AND tag_modid = {$modid}" .
                        "     AND tag_catid = {$catid}"
                        ;
                if ( ($result = $GLOBALS['xoopsDB']->queryF($sql)) == false) {
                    //xoops_error($GLOBALS['xoopsDB']->error());
                }
            } else {
                $ts_id = null;
                $sql =  " SELECT ts_id, tag_count " .
                        " FROM {$this->table_stats}" .
                        " WHERE " .
                        "     {$this->keyName} = {$tag_id}" .
                        "     AND tag_modid = {$modid}" .
                        "     AND tag_catid = {$catid}"
                        ;
                if ($result = $GLOBALS['xoopsDB']->query($sql)) {
                    list($ts_id, $tag_count) = $GLOBALS['xoopsDB']->fetchRow($result);
                }
                $sql = "";
                if ($ts_id && $tag_count != $count) {
                    $sql =     " UPDATE {$this->table_stats}" .
                            " SET tag_count = {$count}" .
                            " WHERE " .
                            "     ts_id = {$ts_id}";
                } elseif (!$ts_id) {
                    $sql =  " INSERT INTO {$this->table_stats}" .
                            "     (tag_id, tag_modid, tag_catid, tag_count)" .
                            " VALUES " .
                            "     ({$tag_id}, {$modid}, {$catid}, {$count})"
                            ;
                }
                if ( !empty($sql) && ($result = $GLOBALS['xoopsDB']->queryF($sql)) == false) {
                    //xoops_error($GLOBALS['xoopsDB']->error());
                }
            }
        }
        
        return true;
    }
    
    /**
     * Get tags with item count
     * 
     * @param    object    $criteria
     * @param    boolean    $fromStats    fetch from tag-stats table
     * @return     array    associative array of tags (id, term, count)
     */
    function &getByLimit($criteria = null, $fromStats = true)
    {
        $ret = array();
        if ($fromStats) {
            $sql  = "    SELECT DISTINCT(o.{$this->keyName}), o.tag_term, o.tag_status, SUM(l.tag_count) AS count, l.tag_modid";
            $sql .= "    FROM {$this->table} AS o LEFT JOIN {$this->table_stats} AS l ON l.{$this->keyName} = o.{$this->keyName}";
        } else {
            $sql  = "    SELECT DISTINCT(o.{$this->keyName}), o.tag_term, o.tag_status, COUNT(l.tl_id) AS count, l.tag_modid";
            $sql .= "    FROM {$this->table} AS o LEFT JOIN {$this->table_link} AS l ON l.{$this->keyName} = o.{$this->keyName}";
        }
        
        $limit = null;
        $start = null;
        $sort = "";
        $order = "";
        if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
            $sql .= "    ".$criteria->renderWhere();
            $sort = $criteria->getSort();
            $order = $criteria->getOrder();
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $sql  .= "    GROUP BY o.{$this->keyName}";
        
        $order = strtoupper($order);
        $sort = strtolower($sort);
        switch($sort) {
        case "a":
        case "alphabet":
            $order = ("DESC" != $order) ? "ASC" : "DESC";
            $sql  .= "    ORDER BY o.tag_term {$order}";
            break;
        case "id":
        case "time":
            $order = ("ASC" != $order) ? "DESC" : "ASC";
            $sql  .= "    ORDER BY o.{$this->keyName} {$order}";
            break;
        case "c":
        case "count":
        default:
            $order = ("ASC" != $order) ? "DESC" : "ASC";
            $sql  .= "    ORDER BY count {$order}";
            break;
        }
        
        if ( ($result = $GLOBALS['xoopsDB']->query($sql, $limit, $start)) == false) {
            //xoops_error($GLOBALS['xoopsDB']->error());
            return null;
        }
        while($myrow = $GLOBALS['xoopsDB']->fetchArray($result)) {
            $ret[$myrow[$this->keyName]] = array(
                        "id"    => $myrow[$this->keyName],
                        "term"    => htmlspecialchars($myrow["tag_term"]),
                        "status"=> $myrow["tag_status"],
                        "modid"    => $myrow["tag_modid"],
                        "count"    => intval($myrow["count"]),
                        );
        }
        
        return $ret;
    }
    
    /**
     * Get count of tags
     * 
     * @param    integer    $modid    id of corresponding module, optional: 0 for all; >1 for a specific module
     * @param    integer    $catid    id of corresponding category, optional
     * @return     integer    count
     */
    function getCount($criteria = null)
    {
        $ret = 0;
        
        /*
        $catid    = intval($catid);
        $modid    = intval($modid);
        */
        $sql = "    SELECT COUNT(DISTINCT o.{$this->keyName})";
        $sql .= "    FROM {$this->table} AS o LEFT JOIN {$this->table_link} AS l ON l.{$this->keyName} = o.{$this->keyName}";
        if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
            $sql .= "    " . $criteria->renderWhere();
        }
        /*
        $sql_where    = "    WHERE 1 = 1";
        if (!empty($modid)) {
            $sql_where    .= " AND l.tag_modid = {$modid}";
        }
        if (empty($catid) || $catid > 0) {
            $sql_where    .= " AND l.tag_catid = {$catid}";
        }
        
        $sql =     $sql_select . " " . $sql_from . " " . $sql_where;
        */
        if ( ($result = $GLOBALS['xoopsDB']->query($sql)) == false) {
            //xoops_error($GLOBALS['xoopsDB']->error());
            return $ret;
        }
        list($ret) = $GLOBALS['xoopsDB']->fetchRow($result);
        
        return $ret;
    }
    
    /**
     * Get items linked with a tag
     * 
     * @param    object    criteria
     * @return     array    associative array of items (id, modid, catid)
     */
    function &getItems($criteria = null)
    {
        $ret = array();
        $sql  = "    SELECT o.tl_id, o.tag_itemid, o.tag_modid, o.tag_catid, o.tag_time";
        $sql .= "    FROM {$this->table_link} AS o LEFT JOIN {$this->table} AS l ON l.{$this->keyName} = o.{$this->keyName}";
        
        $limit = null;
        $start = null;
        $sort = "";
        $order = "";
        if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
            $sql .= "    " . $criteria->renderWhere();
            $sort = $criteria->getSort();
            $order = $criteria->getOrder();
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        
        $order = strtoupper($order);
        $sort = strtolower($sort);
        switch(strtolower($sort)) {
        case "item":
            $order = ("DESC" != $order) ? "ASC" : "DESC";
            $sql .= "    ORDER BY o.tag_itemid {$order}, o.tl_id DESC";
            break;
        case "m":
        case "module":
            $order = ("DESC" != $order) ? "ASC" : "DESC";
            $sql .= "    ORDER BY o.tag_modid {$order}, o.tl_id DESC";
            break;
        case "t":
        case "time":
        default:
            $order = ("ASC" != $order) ? "DESC" : "ASC";
            $sql .= "    ORDER BY o.tl_id {$order}";
            break;
        }
        
        if ( ($result = $GLOBALS['xoopsDB']->query($sql, $limit, $start)) == false) {
            //xoops_error($GLOBALS['xoopsDB']->error());
            return $ret;
        }
        while ($myrow = $GLOBALS['xoopsDB']->fetchArray($result)) {
            $ret[$myrow["tl_id"]] = array(
                        "itemid"    => $myrow["tag_itemid"],
                        "modid"     => $myrow["tag_modid"],
                        "catid"     => $myrow["tag_catid"],
                        "time"      => $myrow["tag_time"],
                        );
        }
        
        return $ret;
    }
    
    /**
     * Get count of items linked with a tag
     * 
     * @param    integer    $tag_id
     * @param    integer    $modid    id of corresponding module, optional: 0 for all; >1 for a specific module
     * @param    integer    $catid    id of corresponding category, optional
     * @return     integer    count
     */
    function getItemCount($tag_id, $modid = 0, $catid = 0)
    {
        $ret = 0;
        
        if (!$tag_id = intval($tag_id)) {
            return $ret;
        }
        $catid    = intval($catid);
        $modid    = intval($modid);
        
        if ($catid<>0)
        {
        	$categories_handler = xoops_getmodulehandler('categories', 'tag');
        	$catid = $categories_handler->getCatID($catid, $modid);
        }
        
        $sql_select = "    SELECT COUNT(DISTINCT o.tl_id)";
        $sql_from   = "    FROM {$this->table_link} AS o LEFT JOIN {$this->table} AS l ON l.{$this->keyName} = o.{$this->keyName}";
        $sql_where  = "    WHERE o.tag_id = {$tag_id}";
        if (!empty($modid)) {
            $sql_where .= " AND o.tag_modid = {$modid}";
        }
        if (empty($catid) || $catid > 0) {
            $sql_where .= " AND o.tag_catid = {$catid}";
        }
        
        $sql = $sql_select . " " . $sql_from . " " . $sql_where;
        if ( ($result = $GLOBALS['xoopsDB']->query($sql)) == false) {
            //xoops_error($GLOBALS['xoopsDB']->error());
            return $ret;
        }
        list($ret) = $GLOBALS['xoopsDB']->fetchRow($result);
        
        return $ret;
    }
    
    /**
     * delete an object as well as links relying on it
     * 
     * @param    object    $object        {@link TagTag}
     * @param     bool     $force         flag to force the query execution despite security settings
     * @return     bool
     */
    function delete(&$object, $force = true)
    {
        if (!is_object($object) || !$object->getVar($this->keyName)) return false;
        $queryFunc = empty($force) ? "query":"queryF";
        
        /*
         * Remove item-tag links
         */
        $sql =  "DELETE" .
                " FROM {$this->table_link}" . 
                " WHERE  {$this->keyName} = " . $object->getVar($this->keyName);
        if ( ($result = $GLOBALS['xoopsDB']->{$queryFunc}($sql)) == false) {
           // xoops_error($GLOBALS['xoopsDB']->error());
        }
        
        /*
         * Remove stats-tag links
         */
        $sql =  "DELETE" .
                " FROM {$this->table_stats}" . 
                " WHERE  {$this->keyName} = " . $object->getVar($this->keyName);
        if ( ($result = $GLOBALS['xoopsDB']->{$queryFunc}($sql)) == false) {
           // xoops_error($GLOBALS['xoopsDB']->error());
        }
        
        return parent::delete($object, $force);
    }

    /**
     * clean orphan links from database
     * 
     * @return     bool    true on success
     */
    function cleanOrphan()
    {
        include_once XOOPS_ROOT_PATH . "/modules/tag/functions.recon.php";
        //mod_loadFunctions("recon");
        return tag_cleanOrphan();
    }
}
?>