<?php
/**
 * @package     RedITEM.Frontend
 * @subpackage  mod_reditem_categories
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Categories helper
 *
 * @since  1.0
 */
class ModredITEMItemsHelper
{
	/**
	 * Get list of categories
	 *
	 * @param   array  &$params  Module parameters
	 *
	 * @return  array
	 */
	public static function getList(&$params)
	{
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$paramCategories = $params->get('categoriesIds', array());
		$paramSubCat = $params->get('include_sub', 0);
		$paramOrdering = $params->get('items_ordering', 'i.title');
		$paramDirection = $params->get('items_direction', 'asc');
		$paramLimit = $params->get('limit', '10');
		$paramFeaturedItems = $params->get('featured_items', '0');
		$categories = array();

		// Also get sub-categories
		if ($paramSubCat)
		{
			foreach ($paramCategories as $category)
			{
				$categories = array_merge(ReditemHelper::getSubCategories($category), $categories);
			}
		}
		else
		{
			$categories = $paramCategories;
		}

		JArrayHelper::toInteger($categories);

		// Get items
		$itemsModel = RModel::getAdminInstance('Items', array('ignore_request' => true), 'com_reditem');
		$itemsModel->setState('filter.published', 1);
		$itemsModel->setState('filter.catid', $categories);
		$itemsModel->setState('list.select', 'DISTINCT (i.id)');
		$itemsModel->setState('list.ordering', $paramOrdering);
		$itemsModel->setState('list.direction', $paramDirection);
		$itemsModel->setState('list.limit', (int) $paramLimit);
		$itemsModel->setState('filter.featured', (int) $paramFeaturedItems);

		$itemIds = $itemsModel->getItems();
		$items = array();

		if ($itemIds)
		{
			$templateModel = RModel::getAdminInstance('Template', array('ignore_request' => true), 'com_reditem');
			$itemModel = RModel::getAdminInstance('Item', array('ignore_request' => true), 'com_reditem');

			foreach ($itemIds as $tmp)
			{
				$item = $itemModel->getItem($tmp->id);
				$item->template = $templateModel->getItem($item->template_id);
				$items[] = $item;
			}

			$items = $itemsModel->getPrepareItems($items);

			return $items;
		}

		return false;
	}
}
