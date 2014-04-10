<?php
/**
 * @package     RedITEM.Frontend
 * @subpackage  mod_reditem_categories
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Display as slider
if ($displayType)
{
	$doc = JFactory::getDocument();

	// Load jQuery framework of redCORE library
	JHtml::_('rjquery.framework');

	RHelperAsset::load('jquery.resize.min.js', 'com_reditem');
	RHelperAsset::load('jquery.bxslider.min.js', 'com_reditem');

	$js = '
	(function($){
		$(document).ready(function() {
			$("#mod_reditem_items_' . $module->id . '").bxSlider({
				controls: ' . $sliderControls . ',
				pager: ' . $slidePager . ',
				auto: ' . $slideAutoPlay . '
			});
		});
	})(jQuery);';
	$doc->addScriptDeclaration($js);
}

JHtml::_('rholder.image', '100x100');

echo '<div class="mod_reditem_items_wrapper">';
echo '<ul id="mod_reditem_items_' . $module->id . '">';

foreach ($items As $item)
{
	if ($template)
	{
		$itemContent = $template->content;

		ReditemTagsHelper::tagReplaceItem($itemContent, $item, 0, $paramItemId);
		ReditemTagsHelper::tagReplaceItemCustomField($itemContent, $item);

		JPluginHelper::importPlugin('content');
		$itemContent = JHtml::_('content.prepare', $itemContent);

		echo '<li>' . $itemContent . '</li>';
	}
	else
	{
		echo '<li>' . JText::_('MOD_REDITEM_ITEMS_ERROR_TEMPLATE_NOT_FOUND') . '</li>';
	}
}

echo '</ul>';
echo '</div>';
