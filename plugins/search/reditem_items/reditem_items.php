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
 * Plugins for search on items of RedITEM
 *
 * @package  RedITEM.Plugin
 *
 * @since    2.0
 */
class PlgSearchReditem_Items extends JPlugin
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
	 * Method for add redITEM items into search areas
	 *
	 * @return array An array of search areas
	 */
	public function onContentSearchAreas()
	{
		static $areas = array(
			'reditemItems' => 'PLG_SEARCH_REDITEM_ITEMS_ITEMS'
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
		$items = array();

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

		$itemsModel = RModel::getAdminInstance('Items', array('ignore_request' => true), 'com_reditem');

		$itemsModel->setState('filter.published', 1);
		$itemsModel->setState('list.limit', $limit);

		$selects = array($db->quoteName('i.id'),
			$db->quoteName('i.title'),
			$db->quoteName('i.id', 'number'),
			'CONCAT(' . $db->quoteName('i.introtext') . ',' . $db->quote(' ') . ',' . $db->quoteName('i.fulltext') . ') AS ' . $db->quoteName('text'),
			$db->quote('2') . ' as ' . $db->quoteName('browsernav'),
			$db->quoteName('i.created_time', 'created')
		);
		$itemsModel->setState('list.select', implode(',', $selects));

		switch ($ordering)
		{
			case 'oldest':
				$itemsModel->setState('list.ordering', 'i.id');
				$itemsModel->setState('list.direction', 'asc');
				break;

			case 'newest':
				$itemsModel->setState('list.ordering', 'i.id');
				$itemsModel->setState('list.direction', 'desc');
				break;

			default:
				$itemsModel->setState('list.ordering', 'i.alias');
				$itemsModel->setState('list.direction', 'asc');
		}

		switch ($phrase)
		{
			case 'exact':
				$itemsModel->setState('filter.plgSearchItem', $text);
				$items = $itemsModel->getItems();
				$items = $itemsModel->getPrepareItems($items);
				break;

			case 'all':
			case 'any':
			default:
				$words = explode(' ', $text);

				foreach ($words as $word)
				{
					$itemsModel->setState('filter.plgSearchItem', $word);
					$tmpItems = array();
					$tmpItems = $itemsModel->getItems();
					$tmpItems = $itemsModel->getPrepareItems($tmpItems);

					foreach ($tmpItems as $tmpItem)
					{
						$items[$tmpItem->id] = $tmpItem;
					}
				}

				break;
		}

		if ($items)
		{
			foreach ($items as &$item)
			{
				$categoryId = 0;
				$section = '';

				if (isset($item->categories))
				{
					$categoryId = key($item->categories);

					$sections = array();

					foreach ($item->categories as $category)
					{
						$sections[] = $category->title;
					}

					$section = implode(', ', $sections);
				}
				else
				{
					$section = JText::_('PLG_SEARCH_REDITEM_ITEMS_ITEMS');
				}

				$item->href = JRoute::_(ReditemRouterHelper::getItemRoute($item->id, '&amp;cid=' . $categoryId));
				$item->section = $section;
			}
		}

		return $items;
	}
}
