<?php
/**
 * @package     RedITEM
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('reditem.library');
JLoader::import('route', JPATH_SITE . '/components/com_reditem/helpers');

/**
 * Plugins for search on categories of RedITEM
 *
 * @package  RedITEM.Plugin
 *
 * @since    2.0
 */
class PlgSearchReditem_Categories extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An array that holds the plugin configuration
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * Method for add redITEM categories into search areas
	 *
	 * @return array An array of search areas
	 */
	public function onContentSearchAreas()
	{
		static $areas = array(
			'reditemCategories' => 'PLG_SEARCH_REDITEM_CATEGORIES_CATEGORIES'
		);

		return $areas;
	}

	/**
	 * Method for run on "ContentSearch" event
	 *
	 * @param   string  $text      Search content
	 * @param   string  $phrase    Search type ('exact', 'all', 'any')
	 * @param   string  $ordering  Ordering of result ('oldest', 'newest')
	 * @param   string  $areas     Areas...
	 *
	 * @return  array
	 */
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$categories = array();

		// Load plugin params info
		$pluginParams = $this->params;

		$limit = $pluginParams->def('search_limit', 50);

		$text = trim($text);

		if ($text == '')
		{
			return array();
		}

		if (is_array($areas))
		{
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas())))
			{
				return array();
			}
		}

		$categoriesModel = RModel::getAdminInstance('Categories', array('ignore_request' => true), 'com_reditem');

		$categoriesModel->setState('filter.published', 1);
		$categoriesModel->setState('list.limit', $limit);

		$selects = array($db->quoteName('c.id'),
			$db->quoteName('c.title'),
			$db->quoteName('c.id', 'number'),
			'CONCAT(' . $db->quoteName('c.introtext') . ',' . $db->quote(' ') . ',' . $db->quoteName('c.fulltext') . ') AS ' . $db->quoteName('text'),
			$db->quote('2') . ' as ' . $db->quoteName('browsernav'),
			$db->quoteName('c.created_time', 'created'),
			$db->quoteName('c.parent_id')
		);

		$categoriesModel->setState('list.select', implode(',', $selects));

		switch ($ordering)
		{
			case 'oldest':
				$categoriesModel->setState('list.ordering', 'c.id');
				$categoriesModel->setState('list.direction', 'asc');
				break;

			case 'newest':
				$categoriesModel->setState('list.ordering', 'c.id');
				$categoriesModel->setState('list.direction', 'desc');
				break;

			default:
				$categoriesModel->setState('list.ordering', 'c.alias');
				$categoriesModel->setState('list.direction', 'asc');
		}

		switch ($phrase)
		{
			case 'exact':
				$categoriesModel->setState('filter.plgSearchCategory', $text);
				$categories = $categoriesModel->getItems();
				break;

			case 'all':
			case 'any':
			default:
				$words = explode(' ', $text);

				foreach ($words as $word)
				{
					$categoriesModel->setState('filter.plgSearchCategory', $word);
					$tmpCategories = array();
					$tmpCategories = $categoriesModel->getItems();

					foreach ($tmpCategories as $tmpCategory)
					{
						$categories[$tmpCategory->id] = $tmpCategory;
					}
				}

				break;
		}

		if ($categories)
		{
			foreach ($categories as &$category)
			{
				$category->href = JRoute::_(ReditemRouterHelper::getCategoryRoute($category->id, '&amp;cid=' . $category->parent_id));

				if ($category->parent_id > 1)
				{
					$categoryModel = RModel::getAdminInstance('Category', array('ignore_request' => true), 'com_reditem');
					$parentCategory = $categoryModel->getItem($category->parent_id);
					$category->section = $parentCategory->title;
				}
				else
				{
					$category->section = JText::_('PLG_SEARCH_REDITEM_CATEGORIES_CATEGORIES');
				}
			}
		}

		return $categories;
	}
}
