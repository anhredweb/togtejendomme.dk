<?php
/**
 * @package     RedITEM.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die;

JLoader::import('reditem.library');
require_once JPATH_SITE . '/components/com_reditem/helpers/imagegenerator.php';
require_once JPATH_SITE . '/components/com_reditem/helpers/route.php';

/**
 * Custom field tags
 *
 * @package     RedITEM.Frontend
 * @subpackage  Helper.Helper
 * @since       2.0
 *
 */
class ReditemHelper
{
	/**
	 * Get menu item ID of category
	 *
	 * @param   int      $id        Category ID
	 * @param   boolean  $recusive  Recusive find on it's parent
	 *
	 * @return  boolean/int
	 */
	public function getCategoryMenuItem($id = 0, $recusive = true)
	{
		$db = JFactory::getDBO();

		$query = $db->getQuery(true);
		$query->select($db->quoteName('c.parent_id'))
			->from($db->quoteName('#__reditem_categories', 'c'))
			->where($db->quoteName('c.id') . ' = ' . (int) $id);

		$db->setQuery($query);

		$category = $db->loadObject();

		if ($category)
		{
			$link = 'index.php?option=com_reditem&view=categorydetail&id=' . $id;

			$query = $db->getQuery(true);
			$query->select($db->quoteName('id'))
				->from($db->quoteName('#__menu'))
				->where($db->quoteName('published') . ' = ' . $db->quote('1'))
				->where($db->quoteName('link') . ' = ' . $db->quote($link));

			$db->setQuery($query);
			$menu = $db->loadObject();

			if ($menu)
			{
				return $menu->id;
			}
			elseif ($recusive)
			{
				// No menu item, get of this parent
				return $this->getCategoryMenuItem($category->parent_id);
			}
		}

		return false;
	}

	/**
	 * Get menu item ID of item
	 *
	 * @param   int      $id        Item ID
	 * @param   boolean  $recusive  Recusive find on it's parent
	 *
	 * @return  boolean/int
	 */
	public function getItemMenuItem($id = 0, $recusive = true)
	{
		$db = JFactory::getDBO();
		$itemModel = RModel::getAdminInstance('Item', array('ignore_request' => true), 'com_reditem');
		$item = $itemModel->getItem($id);

		if ($item)
		{
			$link = 'index.php?option=com_reditem&view=itemdetail&id=' . $item->id;

			$query = $db->getQuery(true);
			$query->select($db->quoteName('id'))
				->from($db->quoteName('#__menu'))
				->where($db->quoteName('published') . ' = ' . $db->quote('1'))
				->where($db->quoteName('link') . ' = ' . $db->quote($link));

			$db->setQuery($query);
			$menu = $db->loadObject();

			if ($menu)
			{
				return $menu->id;
			}
			elseif ($recusive)
			{
				// No menu item, get of this parent
				return $this->getCategoryMenuItem($category->parent_id);
			}
		}

		return false;
	}

	/**
	 * Get deeper subCategories
	 *
	 * @param   int  $categoryId  Category Id
	 *
	 * @return  array
	 */
	public static function getSubCategories($categoryId)
	{
		$subCategories = array();
		$categoryModel = RModel::getAdminInstance('Category', array('ignore_request' => true), 'com_reditem');
		$categoriesModel = RModel::getAdminInstance('Categories', array('ignore_request' => true), 'com_reditem');

		$categoryId = (int) $categoryId;

		$category = $categoryModel->getItem($categoryId);

		if ($category)
		{
			$categoriesModel->setState('filter.published', 1);
			$categoriesModel->setState('filter.lft', $category->lft);
			$categoriesModel->setState('filter.rgt', $category->rgt);
			$categoriesModel->setState('list.select', 'DISTINCT (c.id)');

			$query = $categoriesModel->getListQuery();

			$db = JFactory::getDBO();

			$db->setQuery($query);

			$subCategories = $db->loadResultArray();

			JArrayHelper::toInteger($subCategories);
		}

		return $subCategories;
	}

	/**
	 * Get all related categories
	 *
	 * @param   int  $categoryId  Category Id
	 *
	 * @return  array
	 */
	public static function getRelatedCategories($categoryId)
	{
		$db = JFactory::getDBO();
		$relatedCatIds = array();
		$relatedCategories = array();

		$categoryId = (int) $categoryId;
		$query = $db->getQuery(true);

		$query->select($db->quoteName('parent_id'))
			->from($db->quoteName('#__reditem_category_related'))
			->where($db->quoteName('related_id') . ' = ' . $db->quote($categoryId));

		$db->setQuery($query);
		$relatedCatIds = $db->loadResultArray();

		if ($relatedCatIds)
		{
			$categoryModel = RModel::getAdminInstance('Category', array('ignore_request' => true), 'com_reditem');

			foreach ($relatedCatIds as $relatedCatId)
			{
				$relatedCategories[] = $categoryModel->getItem($relatedCatId);
			}
		}

		return $relatedCategories;
	}
}
