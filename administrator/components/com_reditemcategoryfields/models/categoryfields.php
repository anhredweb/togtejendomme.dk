<?php
/**
 * @package     RedITEMCategoryFields.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * RedITEM fields Model
 *
 * @package     RedITEMCategoryFields.Component
 * @subpackage  Models.Fields
 * @since       2.0
 *
 */
class ReditemCategoryFieldsModelCategoryFields extends RModelList
{
	/**
	 * Name of the filter form to load
	 *
	 * @var  string
	 */
	protected $filterFormName = 'filter_categoryfields';

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
				'name', 'f.name',
				'ordering', 'f.ordering',
				'published', 'f.published',
				'id', 'f.id',
				'type', 'f.type'
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
				'f.*'
			)
		);
		$query->from('#__reditem_category_fields AS f');

		// Filter: like / search
		$search = $this->getState('filter.search', '');

		if ($search != '')
		{
			$like = $db->quote('%' . $search . '%');
			$query->where('f.name LIKE ' . $like);
		}

		// Filter: fieldtypes
		$filter_fieldtype = $this->getState('filter.fieldtypes', 0);

		if ($filter_fieldtype)
		{
			$query->where($db->quoteName('f.type') . ' = ' . $db->quote($filter_fieldtype));
		}

		// Filter by published state
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('f.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(f.published IN (0, 1))');
		}

		// Get the ordering modifiers
		$orderCol	= $this->state->get('list.ordering', 'f.ordering');
		$orderDirn	= $this->state->get('list.direction', 'asc');
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
		$id 	.= ':' . $this->getState('filter.fieldtypes');
		$id	.= ':' . $this->getState('filter.published');

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
	public function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		$filterSearch = $this->getUserStateFromRequest($this->context . '.filter_search', 'filter_search');
		$this->setState('filter.search', $filterSearch);

		$filterFieldTypes = $this->getUserStateFromRequest($this->context . '.filter_fieldtypes', 'filter_fieldtypes');
		$this->setState('filter.fieldtypes', $filterFieldTypes);

		$published = $this->getUserStateFromRequest($this->context . '.filter_published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$value = $app->getUserStateFromRequest('global.list.limit', $this->paginationPrefix . 'limit', $app->getCfg('list_limit'), 'uint');
		$limit = $value;
		$this->setState('list.limit', $limit);

		parent::populateState('f.ordering', 'asc');
	}
}
