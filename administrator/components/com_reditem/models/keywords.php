<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * RedITEM keywords Model
 *
 * @package     RedITEM.Component
 * @subpackage  Models.templates
 * @since       0.9.1
 *
 */
class ReditemModelKeywords extends RModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_keywords';

	/**
	 * Constructor.
	 *
	 * @param   array  $config  [description]
	 *
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'k.name',
				'k.ordering',
				'k.published',
				'k.id'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to cache the last query constructed.
	 *
	 * This method ensures that the query is constructed only once for a given state of the model.
	 *
	 * @return JDatabaseQuery A JDatabaseQuery object
	 */
	public function getListQuery()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(
			$this->getState(
				'list.select',
				'k.*'
			)
		);
		$query->from('#__reditem_keywords AS k');

		// $query->leftJoin($db->quoteName('#__reditem_types', 'ty') . ' ON ' . $db->quoteName('t.type_id') . ' = ' . $db->quoteName('ty.id'));

		// Filter: like / search
		$search = $this->getState('filter.search', '');

		if ($search != '')
		{
			$like = $db->quote('%' . $search . '%');
			$query->where('k.name LIKE ' . $like);
		}

		// Get the ordering modifiers
		$orderCol	= $this->state->get('list.ordering', 'k.name');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

		return $query;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 */
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the list items.
		$query = $this->_getListQuery();
		$items = $this->_getList($query);

		// Check for a database error.
		if ($this->_db->getErrorNum())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		// Get the items attached list
		$db = JFactory::getDBO();

		foreach ($items As $item)
		{
			$query = $db->getQuery(true);

			$query->select($db->quoteName('item_id'));
			$query->from($db->quoteName('#__reditem_item_keyword_xref'));
			$query->where($db->quoteName('keyword_id') . ' = ' . $db->quote($item->id));
			$db->setQuery($query);
			$items_id = $db->loadResultArray();

			$itemmodel = RModel::getAdminInstance('Item');

			if (count($items_id))
			{
				$_items = array();

				foreach ($items_id AS $cid)
				{
					$_item = $itemmodel->getItem($cid);
					$_items[$cid] = $_item;
				}

				$item->items = $_items;
			}
		}

		$search = $this->getState('filter.search', '');
		$limit = (int) $this->getState('list.limit') - (int) $this->getState('list.links');
		$keywords = array();

		if ($search == '')
		{
			// Establish the hierarchy of the menu
			$children = array();

			// First pass - collect children
			foreach ($items as $v)
			{
				/*$v->parent_id = $v->category_parent_id;*/
				$v->title = $v->name;
				/*$v->id = $v->category_id;*/

				$pt = $v->parent_id;
				$list = (isset($children[$pt])) ? $children[$pt] : array();
				array_push($list, $v);
				$children[$pt] = $list;
			}

			// Second pass - get an indent list of the items
			$items = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999);

			if (isset($children))
			{
				$total = count($items);
			}
			else
			{
				$total = 0;
			}

			jimport('joomla.html.pagination');
			$pageNav = new JPagination($total, $this->getStart(), $limit);
			$keywords = @array_slice($items, $pageNav->limitstart, $pageNav->limit);
		}
		else
		{
			foreach ($items as $item)
			{
				$item->treename = $item->name;
				$keywords[] = $item;
			}
		}

		// Add the items to the internal cache.
		$this->cache[$store] = $keywords;

		return $this->cache[$store];
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   [description]
	 * @param   string  $direction  [description]
	 *
	 * @return  void
	 */
	protected function populateState($ordering = 'k.ordering', $direction = 'ASC')
	{
		$filterSearch = $this->getUserStateFromRequest($this->context . '.filter_search', 'filter_search');
		$this->setState('filter.search', $filterSearch);

		parent::populateState($ordering, $direction);
	}
}
