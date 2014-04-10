<?php
/**
 * @package     RedITEM.Frontend
 * @subpackage  mod_reditem_items
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Import redCORE library
jimport('redcore.bootstrap');

require_once JPATH_SITE . '/components/com_reditem/helpers/imagegenerator.php';
require_once JPATH_SITE . '/components/com_reditem/helpers/route.php';
require_once JPATH_SITE . '/components/com_reditem/helpers/tags.php';
require_once JPATH_SITE . '/components/com_reditem/helpers/reditem.php';
require_once JPATH_SITE . '/modules/mod_reditem_items/helper.php';

$items = ModredITEMItemsHelper::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
$displayType = $params->get('display', 0);

$paramItemId = $params->get('setItemId', 0);

$paramTemplateId = $params->get('templateId', 0);

$templateModel = RModel::getAdminInstance('Template', array('ignore_request' => true), 'com_reditem');
$template = $templateModel->getItem($paramTemplateId);

$paramSlideControls = $params->get('slider_controls', 1);
$paramSlidePager = $params->get('slider_pager', 1);
$paramSlideAutoPlay = $params->get('slider_autoplay', 1);

if ($paramSlidePager)
{
	$slidePager = 'true';
}
else
{
	$slidePager = 'false';
}

if ($paramSlideControls)
{
	$sliderControls = 'true';
}
else
{
	$sliderControls = 'false';
}

if ($paramSlideAutoPlay)
{
	$slideAutoPlay = 'true';
}
else
{
	$slideAutoPlay = 'false';
}

if (!$items)
{
	echo '<p>' . JText::_('COM_REDITEM_ERROR_NO_ITEM_FOUND') . '</p>';
}
else
{
	require JModuleHelper::getLayoutPath('mod_reditem_items', $params->get('layout', 'default'));
}
