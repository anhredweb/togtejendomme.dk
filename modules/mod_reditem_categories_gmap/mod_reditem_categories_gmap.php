<?php
/**
 * @package     RedITEM.Frontend
 * @subpackage  mod_reditem_categories
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$doc = JFactory::getDocument();

$doc->addScript('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');
$doc->addScript('http://www.google.com/jsapi');

require_once JPATH_SITE . '/modules/mod_reditem_categories_gmap/helper.php';

$categories = ModredITEMCategoriesGmapHelper::getList($params);

JHtml::_('rjquery.framework');

RHelperAsset::load('markerwithlabel.min.js', 'mod_reditem_categories_gmap');
RHelperAsset::load('infobubble.min.js', 'mod_reditem_categories_gmap');
RHelperAsset::load('reditem_categories_gmap.js', 'mod_reditem_categories_gmap');
RHelperAsset::load('reditem_categories_gmap.css', 'mod_reditem_categories_gmap');

$parentCategory = $params->get('parent', 0);
$country = $params->get('country', 'Denmark');
$gmapWidth = $params->get('gmap_width', '');
$gmapHeight = $params->get('gmap_height', '');
$gmapZoom = $params->get('gmap_zoom', '5');
$gmapLatlng = $params->get('gmap_latlng', '55.22811,10.21298');
$gmapInfoWindowUnique = $params->get('gmap_unique_inforwindow', 1);

if ($gmapWidth)
{
	$gmapWidth = 'width: ' . $gmapWidth;
}

if ($gmapHeight)
{
	$gmapHeight = 'height: ' . $gmapHeight;
}

$activeCategory = JFactory::getApplication()->input->get('filter_category', array(), 'array');

foreach ($categories as &$cat)
{
	if (!empty($activeCategory[$parentCategory]) && $cat->id != $activeCategory[$parentCategory])
	{
		$cat->filterCategory = false;
	}
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_reditem_categories_gmap', $params->get('layout', 'default'));
