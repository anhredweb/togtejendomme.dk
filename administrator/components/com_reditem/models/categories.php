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
 * RedITEM categories Model
 *
 * @package     RedITEM.Component
 * @subpackage  Models.Categories
 * @since       2.0
 *
 */
class ReditemModelCategories extends RModelList
{
	/**
	 * Context for session
	 *
	 * @var  string
	 */
	protected $context = 'com_reditem.categories';

	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_categories';

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
				'title', 'c.title',
				'ordering', 'c.ordering',
				'published', 'c.published',
				'access', 'c.access', 'access_level',
				'id', 'c.id',
				'parent_id', 'c.parent_id',
				'type_id', 'c.type_id', 'type_name',
				'template_id', 'c.template_id', 'template_name',
				'featured', 'c.featured'
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
		$db 	= JFactory::getDbo();
		$user	= JFactory::getUser();
		$groups	= $user->getAuthorisedViewLevels();

		$query = $db->getQuery(true);
		$query->select(
			$this->getState(
				'list.select',
				'c.*, t.name AS template_name, ty.title AS type_name, ag.title AS access_level'
			)
		);
		$query->from('#__reditem_categories AS c');
		$query->leftJoin($db->quoteName('#__reditem_templates', 't') . ' ON ' . $db->quoteName('c.template_id') . ' = ' . $db->quoteName('t.id'));
		$query->leftJoin($db->quoteName('#__reditem_types', 'ty') . ' ON ' . $db->quoteName('c.type_id') . ' = ' . $db->quoteName('ty.id'));

		// Join over the asset groups.
		$query->leftJoin($db->quoteName('#__viewlevels', 'ag') . ' ON ' . $db->quoteName('ag.id') . ' = ' . $db->quoteName('c.access'));

		// Remove "ROOT" item
		$query->where($db->quoteName('level') . ' > ' . $db->quote('0'));

		// Filter: ID
		$filter_ids = $this->getState('filter.ids', array());

		if ($filter_ids)
		{
			$query->where($db->quoteName('c.id') . ' IN (' . implode(',', $filter_ids) . ')');
		}

		// Filter: Parent ID
		$parent_id = $this->getState('filter.parentid');

		if ($parent_id)
		{
			$query->where($db->quoteName('c.parent_id') . ' = ' . $db->quote($parent_id));
		}

		// Filter: Get deeper child or parent
		$lft = $this->getState('filter.lft', 0);
		$rgt = $this->getState('filter.rgt', 0);

		if (($lft) && ($rgt))
		{
			$query->where($db->quoteName('c.lft') . ' >= ' . (int) $lft);
			$query->where($db->quoteName('c.rgt') . ' <= ' . (int) $rgt);
		}

		// Filter: like / search
		$search = $this->getState('filter.search', '');

		if ($search != '')
		{
			$like = $db->quote('%' . $search . '%');
			$query->where('c.title LIKE ' . $like);
		}

		// Filter: like / plugin Search Category
		$filterPlgSearchCategory = $this->getState('filter.plgSearchCategory', '');

		if ($filterPlgSearchCategory != '')
		{
			$like = $db->quote('%' . $filterPlgSearchCategory . '%');

			$where = array(
				$db->quoteName('c.title') . ' LIKE ' . $like,
				$db->quoteName('c.introtext') . ' LIKE ' . $like,
				$db->quoteName('c.fulltext') . ' LIKE ' . $like
			);

			$query->where('((' . implode(') OR (', $where) . '))');
		}

		// Filter: types
		$filter_type = $this->getState('filter.filter_types', 0);

		if ($filter_type)
		{
			$query->where($db->quoteName('c.type_id') . ' = ' . $db->quote($filter_type));
		}

		// Filter by published state
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('c.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(c.published IN (0, 1))');
		}

		// Filter by featured state
		$featured = $this->getState('filter.featured');

		if (is_numeric($featured))
		{
			$query->where('c.featured = ' . (int) $featured);
		}
		elseif ($featured === '')
		{
			$query->where('(c.featured IN (0, 1))');
		}

		// Check access level
		$query->where($db->quoteName('c.access') . ' IN (' . implode(',', $groups) . ')');

		// Get the ordering modifiers
		$orderCol = $this->state->get('list.ordering', 'c.lft');
		$orderDirn = $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

		return $query;
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return	string  A store id.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.plgSearchCategory');
		$id	.= ':' . $this->getState('filter.published');
		$id	.= ':' . $this->getState('filter.featured');
		$id	.= ':' . $this->getState('filter.lft');
		$id	.= ':' . $this->getState('filter.rgt');

		$ids = $this->getState('filter.ids');

		if (isset($ids))
		{
			if (is_array($ids))
			{
				$id .= ':' . implode(',', $ids);
			}
			else
			{
				$id .= ':' . $ids;
			}
		}

		return parent::getStoreId($id);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   [description]
	 * @param   string  $direction  [description]
	 *
	 * @return  void
	 */
	protected function populateState($ordering = 'c.lft', $direction = 'ASC')
	{
		$app = JFactory::getApplication();

		$filterSearch = $this->getUserStateFromRequest($this->context . '.filter_search', 'filter_search');
		$this->setState('filter.search', $filterSearch);

		$filterPlgSearchCategory = $this->getUserStateFromRequest($this->context . '.filter_plgSearchCategory', 'filter_plgSearchCategory');
		$this->setState('filter.plgSearchCategory', $filterPlgSearchCategory);

		$filterTypes = $this->getUserStateFromRequest($this->context . '.filter_types', 'filter_types');
		$this->setState('filter.filter_types', $filterTypes);

		$filterIds = $this->getUserStateFromRequest($this->context . '.filter_ids', 'filter_ids');
		$this->setState('filter.ids', $filterIds);

		$parent = $this->getUserStateFromRequest($this->context . '.filter_parentid', 'filter_parentid');
		$this->setState('filter.parentid', $parent);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$featured = $this->getUserStateFromRequest($this->context . '.filter.featured', 'featured', '');
		$this->setState('filter.featured', $featured);

		$value = $app->getUserStateFromRequest('global.list.limit', $this->paginationPrefix . 'limit', $app->getCfg('list_limit'), 'uint');
		$limit = $value;
		$this->setState('list.limit', $limit);

		$value = $app->getUserStateFromRequest($this->context . '.limitstart', $this->paginationPrefix . 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
		$this->setState('list.start', $limitstart);

		parent::populateState($ordering, $direction);
	}
}
