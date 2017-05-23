<?php
/**
 * Tag management
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		1.00
 * @version		$Id: main.php 10055 2012-08-11 12:46:10Z beckmi $
 * @package		module::tag
 */

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

define("TAG_MD_TAGS","Tag");
define("TAG_MD_TAG_ON","Tag ����");
define("TAG_MD_TAGVIEW", '%2$s�� <strong>%s</strong> �Ĺ�����Ŀ');
define("TAG_MD_TAGLIST","<strong>%s</strong>��Tag�б�");
define("TAG_MD_JUMPTO","���ٲ鿴");
define("TAG_MD_TAG_DELIMITER","��ͬTag֮����Բ�����������ļ���");
define("TAG_MD_INVALID","��ѯ�����ݲ�����");

/**
 * Customize addons:
 * <ul>
 *	<li>key: like "google", nothing but only for "target" in anchor;</li>
 *	<li>title: link title;</li>
 *	<li>link: link prototype, %s for the urlencode'd term;</li>
 *	<li>function: optional, some sites might require different charset encoding, you can create your functions or use PHP functions like utf8_encode.
 *					This is required by non-latin languages for technorati or flickr.
 *	</li>
 * </ul>
 */
$GLOBALS["TAG_MD_ADDONS"] = array(
	"google"	=> array(
						"title"		=> "Google",
						"link"		=> "http://www.google.com/search?q=%s",
						),	
	"baidu"	=> array(
						"title"		=> "�ٶ�",
						"link"		=> "http://www.baidu.com/s?wd=%s",
						),	
	"techno"	=> array(
						"title"		=> "Technorati",
						"link"		=> "http://technorati.com/tag/%s/",
						"function"	=> "xoops_utf8_encode",
						),	
	"flickr"	=> array(
						"title"		=> "Flickr",
						"link"		=> "http://www.flickr.com/photos/tags/%s/",
						"function"	=> "xoops_utf8_encode",
						),	
	);
?>