﻿# Tag Module for XOOPS 2.5

## Version 2.xx (Still in Development)

### Author: Simon Roberts <simon@snails.email>

This module provides a centralized toolkit including input, display, stats and substantial more comprehensive applications, so that each module does not need to develop its own tag handling scripts.

Check http://en.wikipedia.org/wiki/Tags for more info about "tag"

# Usage of the Tag Module in your XOOPS Module

To enable tag for a module ("mymodule"), following steps are need:
* add tag input box to your item edit form (required)
* add tag storage to your item submission page (required)
* define functions to build info of tagged items (required)
* add tag display API to your item display page and include tag template in your item template (optional)
* add module tag view page and tag list page (optional)
* add module tag blocks (optional) 

## Step 1: add tag input box

This is how you would set up an inclusion of the tag module in an itemised edit form in your module, this is not explicit example and could have variegates for the module you are writing or editing.

    // File: edit.item.php
    $itemid = $item_obj->isNew() ? 0 : $item_obj->getVar("itemid");
    include_once XOOPS_ROOT_PATH . "/modules/tag/include/formtag.php";
    $form_item->addElement(new XoopsFormTag("item_tag", 60, 255, $itemid, $catid = 0));

## Step 2: add tag storage after item storage

This is how you would set up an inclusion of the tag module in an itemised submition form in your module, this is not explicit example and could have variegates for the module you are writing or editing.

    // File: submit.item.php
    $tag_handler = xoops_getmodulehandler('tag', 'tag');
    $tag_handler->updateByItem($_POST["item_tag"], $itemid, $xoopsModule->getVar("dirname"), $catid = 0);

## Step 3: define functions to build info of tagged items of module

This is the plugin for the tag module to enable the taging in both the tag module and the module you are writing/editing!
Editing File Example: /modules/tag/plugin/mymodule.php

### Get item fields: title, content, time, link, uid, uname, tags

    function mymodule_tag_iteminfo(&$items)
    {
        $items_id = array();
        foreach (array_keys($items) as $cat_id) {
            // Some handling here to build the link upon catid
                // If catid is not used, just skip it
            foreach (array_keys($items[$cat_id]) as $item_id) {
                // In article, the item_id is "art_id"
                $items_id[] = intval($item_id);
            }
        }
        $item_handler =& xoops_getmodulehandler("item", "module");
        $items_obj = $item_handler->getObjects(new Criteria("itemid", "(" . implode(", ", $items_id) . ")", "IN"), true);
        
        foreach (array_keys($items) as $cat_id) {
            foreach (array_keys($items[$cat_id]) as $item_id) {
                $item_obj =& $items_obj[$item_id];
                $items[$cat_id][$item_id] = array(
                    "title"     => $item_obj->getVar("item_title"),
                    "uid"       => $item_obj->getVar("uid"),
                    "link"      => "view.item.php?itemid={$item_id}",
                    "time"      => $item_obj->getVar("item_time"),
                    "tags"      => tag_parse_tag($item_obj->getVar("item_tags", "n")), // optional
                    "content"   => "",
                    );
            }
        }
        unset($items_obj);    
    }

### Remove orphan tag-item links

    function mymodule_tag_synchronization($mid) 
    {
        // Optional
    }

### Get's category catid data for module

### Get item fields: catid, parentid, term

    function mymodule_tag_category($catid) 
    {
        return array('catid'->0, 'parentid' =>0, 'term' =>0);
    }

## Step 4: Display tags on our tiem page

These files are not explicit as filenames they could be different this is how to display the tag in the item of the module you are editing/writing.

### File: view.item.php

    include_once XOOPS_ROOT_PATH."/modules/tag/include/tagbar.php";
    $xoopsTpl->assign('tagbar', tagBar($itemid, $catid = 0));

### File: mymodule_item_template.html

    <{include file="db:tag_bar.html"}>

## Step 5: create tag list page and tag view page and for categories as well

This is the files that belong in /modules/mymodule/xxxx.xxx.php for redirecting or displaying within your module the tag lists and views as well as categories

### File: list.tag.php

     include "header.php";
     include XOOPS_ROOT_PATH . "/modules/tag/list.tag.php";

### File: view.tag.php

     include "header.php";
     include XOOPS_ROOT_PATH . "/modules/tag/view.tag.php";

### File: list.cat.php

     include "header.php";
     include XOOPS_ROOT_PATH . "/modules/tag/list.cat.php";

### File: view.cat.php

     include "header.php";
     include XOOPS_ROOT_PATH . "/modules/tag/view.cat.php";

## Step 6: create tag blocks

This is where you create the blocks you will have to edit and create files for this within your module

### File: xoops_version.php

     /*
      * $options:  
      *                    $options[0] - number of tags to display
      *                    $options[1] - time duration, in days, 0 for all the time
      *                    $options[2] - max font size (px or %)
      *                    $options[3] - min font size (px or %)
      */
     $modversion["blocks"][]    = array(
         "file"            => "mymodule_block_tag.php",
         "name"            => "Module Tag Cloud",
         "description"    => "Show tag cloud",
         "show_func"        => "mymodule_tag_block_cloud_show",
         "edit_func"        => "mymodule_tag_block_cloud_edit",
         "options"        => "100|0|150|80",
         "template"        => "mymodule_tag_block_cloud.html",
         );
    
     /*
      * $options:
      *                    $options[0] - number of tags to display
      *                    $options[1] - time duration, in days, 0 for all the time
      *                    $options[2] - sort: a - alphabet; c - count; t - time
      */
     $modversion["blocks"][]    = array(
         "file"            => "mymodule_block_tag.php",
         "name"            => "Module Top Tags",
         "description"    => "Show top tags",
         "show_func"        => "mymodule_tag_block_top_show",
         "edit_func"        => "mymodule_tag_block_top_edit",
         "options"        => "50|30|c",
         "template"        => "mymodule_tag_block_top.html",
         );
    
     /*
      * $options:
      */
     $modversion["blocks"][]    = array(
         "file"            => "mymodule_block_cumulus.php",
         "name"            => "Module Top Tags",
         "description"    => "Show top tags",
         "show_func"        => "mymodule_tag_block_cumulus_show",
         "edit_func"        => "mymodule_tag_block_cumulus_edit",
         "options"        => "",
         "template"        => "mymodule_tag_block_cumulus.html",
         );

### File: mymodule_block_tag.php

This file belongs in /modules/mymodule/blocks and is adjustable in function names and filename in the xoops_version.php as seen in the example above.

     function mymodule_tag_block_cloud_show($options) 
     {
         include_once XOOPS_ROOT_PATH . "/modules/tag/blocks/block.php";
         return tag_block_cloud_show($options, basename(dirname(dirname(dirname(__DIR__)))));
     }
     function mymodule_tag_block_cloud_edit($options) 
     {
         include_once XOOPS_ROOT_PATH . "/modules/tag/blocks/block.php";
         return tag_block_cloud_edit($options);
     }
     function mymodule_tag_block_top_show($options) 
     {
         include_once XOOPS_ROOT_PATH . "/modules/tag/blocks/block.php";
         return tag_block_top_show($options, basename(dirname(dirname(dirname(__DIR__)))));
     }
     function mymodule_tag_block_top_edit($options) 
     {
         include_once XOOPS_ROOT_PATH . "/modules/tag/blocks/block.php";
         return tag_block_top_edit($options);
     }

### File: mymodule_block_cumulus.php

This file belongs in /modules/mymodule/blocks and is adjustable in function names and filename in the xoops_version.php as seen in the example above.

     function mymodule_tag_block_cumulus_show($options) 
     {
         include_once XOOPS_ROOT_PATH . "/modules/tag/blocks/cumulus.php";
         return tag_block_cumulus_show($options, basename(dirname(dirname(dirname(__DIR__)))));
     }
     function mymodule_tag_block_cumulus_edit($options) 
     {
         include_once XOOPS_ROOT_PATH . "/modules/tag/blocks/cumulus.php";
         return tag_block_cumulus_edit($options);
     }

### File: mymodule_tag_block_cloud.html

This file belongs in /modules/mymodule/templates/blocks and is adjustable in function names and filename in the xoops_version.php as seen in the example above.

     <{include file="db:tag_block_cloud.html"}>

### File: mymodule_tag_block_top.html

This file belongs in /modules/mymodule/templates/blocks and is adjustable in function names and filename in the xoops_version.php as seen in the example above.

     <{include file="db:tag_block_top.html"}>

### File: mymodule_tag_block_cumulus.html

This file belongs in /modules/mymodule/templates/blocks and is adjustable in function names and filename in the xoops_version.php as seen in the example above.

     <{include file="db:tag_block_cumulus.html"}>
