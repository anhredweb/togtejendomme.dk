<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * RedITEM CustomFields Helper
 *
 * @package     RedITEM.Component
 * @subpackage  Helpers.CusomHelper
 * @since       2.0
 *
 */
class ReditemHelperHelper
{
	/**
	 * returns a custom field object according to type
	 *
	 * @param   string  $type  [description]
	 *
	 * @return object
	 */
	public static function getCustomField($type)
	{
		require_once JPATH_ADMINISTRATOR . '/components/com_reditem/customfield/customfield.php';

		switch ($type)
		{
			case 'select':
				return new TCustomfieldSelect;
				break;

			case 'date':
				return new TCustomfieldDate;
				break;

			case 'select_multiple':
				return new TCustomfieldSelectmultiple;
				break;

			case 'radio':
				return new TCustomfieldRadio;
				break;

			case 'checkbox':
				return new TCustomfieldCheckbox;
				break;

			case 'textarea':
				return new TCustomfieldTextarea;
				break;

			case 'editor':
				return new TCustomfieldEditor;
				break;

			case 'gallery':
				return new TCustomfieldGallery;
				break;

			case 'image':
				return new TCustomfieldImage;
				break;

			case 'googlemaps':
				return new TCustomfieldGoogleMaps;
				break;

			case 'number':
				return new TCustomfieldNumber;
				break;

			case 'file':
				return new TCustomfieldFile;
				break;

			case 'text':
			case 'youtube':
			default:
				return new TCustomfieldTextbox;
				break;
		}
	}

	/**
	 * Get the tags for category.
	 *
	 * @param   object   $template       Current template object
	 * @param   boolean  $subCategories  Is subCategories or not
	 *
	 * @return  array  List of avaiable tags
	 */
	public static function getCategoryTags($template, $subCategories = false)
	{
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('reditem_categories');
		$prefix = '';

		if ($subCategories)
		{
			$prefix = 'sub_';
		}

		// Current category tags
		$categoryTags = array(
			'{' . $prefix . 'category_title}' => JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_TITLE'),
			'{' . $prefix . 'category_link}' => JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_LINK'),
			'{' . $prefix . 'category_image}' => JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_IMAGE'),
			'{' . $prefix . 'category_image_link}' => JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_IMAGE_LINK'),
			'{' . $prefix . 'category_image_large}' => JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_IMAGE_THUMB_LARGE'),
			'{' . $prefix . 'category_image_medium}' => JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_IMAGE_THUMB_MEDIUM'),
			'{' . $prefix . 'category_image_small}' => JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_IMAGE_THUMB_SMALL'),
			'{' . $prefix . 'category_introtext|<em>limit</em>}' => JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_INTROTEXT'),
			'{' . $prefix . 'category_fulltext|<em>limit</em>}' => JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_FULLTEXT'),
		);

		if (!$subCategories)
		{
			$subCategoryTags = self::getCategoryTags($template, true);

			$categoryTags['{include_sub_category_items}'] = JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_INCLUDE_ITEMS');

			// Items tags
			$categoryTags['{items_loop_start}'] = JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_ITEMS_LOOP_START');
			$categoryTags[] = self::getItemTags($template);
			$categoryTags['{items_loop_end}'] = JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_ITEMS_LOOP_END');

			$categoryTags['{items_pagination|<em>limit</em>}'] = JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_ITEMS_PAGINATION');

			// Sub-Categories tags
			$categoryTags['{sub_category_start}'] = JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_SUB_LOOP_START');
			$categoryTags[] = $subCategoryTags;
			$categoryTags['{sub_category_end}'] = JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_SUB_LOOP_END');

			$categoryTags['{sub_category_pagination|<em>limit</em>}'] = JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_SUB_PAGINATION');

			// Sub-Categories tags
			$categoryTags['{sub_featured_category_start}'] = JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_FEATURED_SUB_LOOP_START');
			$categoryTags[] = $subCategoryTags;
			$categoryTags['{sub_featured_category_end}'] = JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_FEATURED_SUB_LOOP_END');

			$categoryTags['{related_category_start}'] = JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_RELATED_LOOP_START');
			$categoryTags[] = $subCategoryTags;
			$categoryTags['{related_category_end}'] = JText::_('COM_REDITEM_TEMPLATE_TAG_CATEGORY_RELATED_LOOP_END');

			// $dispatcher->trigger('prepareCategoryTemplateTag', array(&$categoryTags, $template));
		}

		$dispatcher->trigger('prepareCategoryTemplateTag', array(&$categoryTags, $template, $prefix));

		return $categoryTags;
	}

	/**
	 * Get the tags for item.
	 *
	 * @param   object  $template  Current template object
	 *
	 * @return  array  List of avaiable tags
	 */
	public static function getItemTags($template)
	{
		$itemTags = array(
			'{item_title}' => JText::_('COM_REDITEM_TEMPLATE_TAG_ITEM_TITLE'),
			'{item_link}' => JText::_('COM_REDITEM_TEMPLATE_TAG_ITEM_LINK'),
			'{item_image}' => JText::_('COM_REDITEM_TEMPLATE_TAG_ITEM_IMAGE'),
			'{item_image_link}' => JText::_('COM_REDITEM_TEMPLATE_TAG_ITEM_IMAGE_LINK'),
			'{item_image_large}' => JText::_('COM_REDITEM_TEMPLATE_TAG_ITEM_IMAGE_THUMB_LARGE'),
			'{item_image_medium}' => JText::_('COM_REDITEM_TEMPLATE_TAG_ITEM_IMAGE_THUMB_MEDIUM'),
			'{item_image_small}' => JText::_('COM_REDITEM_TEMPLATE_TAG_ITEM_IMAGE_THUMB_SMALL'),
			'{item_introtext|<em>limit</em>}' => JText::_('COM_REDITEM_TEMPLATE_TAG_ITEM_INTROTEXT'),
			'{item_fulltext|<em>limit</em>}' => JText::_('COM_REDITEM_TEMPLATE_TAG_ITEM_FULLTEXT'),
			'{item_first_cat_link}' => JText::_('COM_REDITEM_TEMPLATE_TAG_ITEM_FIRST_CATEGORY_LINK')
		);

		if (isset($template->tags))
		{
			foreach ($template->tags as $cfTag)
			{
				$tag = '{' . $cfTag->fieldcode . '_text}';
				$tagDesc = JText::sprintf('COM_REDITEM_TEMPLATE_TAG_FIELD_' . strtoupper($cfTag->type) . '_TITLE', $cfTag->name);
				$itemTags[$tag] = $tagDesc;

				$tagParams = JText::_('COM_REDITEM_TEMPLATE_TAG_FIELD_' . strtoupper($cfTag->type) . '_PARAMS');
				$tag = '{' . $cfTag->fieldcode . '_value' . $tagParams . '}';
				$tagDesc = JText::sprintf('COM_REDITEM_TEMPLATE_TAG_FIELD_' . strtoupper($cfTag->type) . '_VALUE', $cfTag->name);
				$itemTags[$tag] = $tagDesc;
			}
		}

		return $itemTags;
	}

	/**
	 * Get the filter tags for items.
	 *
	 * @param   object  $template  Current template object
	 *
	 * @return  array  List of avaiable tags
	 */
	public static function getFilterTags($template)
	{
		$filterTags = array(
			'{filter_category|<em>id</em>|<em>type</em>|<em>featured</em>}'
				=> JText::_('COM_REDITEM_TEMPLATE_TAG_FILTER_BY_CATEGORY'),
			'{filter_relatedcategory|<em>id</em>}'
				=> JText::_('COM_REDITEM_TEMPLATE_TAG_FILTER_BY_RELATEDCATEGORY'),
			'{filter_customfield|<em>cfId</em>|<em>cfType</em>}'
				=> JText::_('COM_REDITEM_TEMPLATE_TAG_FILTER_BY_CUSTOMFIELDDATA'),
			'{filter_ranges|<em>cfId</em>|<em>rangeCount;minVal:maxVal;rangeText</em>|<em>default</em>}'
				=> JText::_('COM_REDITEM_TEMPLATE_TAG_FILTER_RANGES')
		);

		return $filterTags;
	}

	/**
	 * Get the filter tags for sub-catgories.
	 *
	 * @param   object  $template  Current template object
	 *
	 * @return  array  List of avaiable tags
	 */
	public static function getCategoryFilterTags($template)
	{
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('reditem_categories');
		$filterTags = array(
			'{filter_subcatitemsavaiable}' => JText::_('COM_REDITEM_TEMPLATE_TAG_SUB_CATEGORY_ITEMS_AVAIABLE_DATA')
		);

		$dispatcher->trigger('prepareCategoryFilterExtraTag', array(&$filterTags, $template));

		return $filterTags;
	}

	/**
	 * Replace special character in filename.
	 *
	 * @param   string  $name  Name of file
	 *
	 * @return  string
	 */
	public static function replaceSpecial($name)
	{
		$filetype = JFile::getExt($name);
		$filename = JFile::stripExt($name);
		$value = preg_replace("/[&'#]/", "", $filename);
		$value = JFilterOutput::stringURLSafe($value) . '.' . $filetype;

		return $value;
	}
}
