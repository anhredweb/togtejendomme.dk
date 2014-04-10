<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Items List View
 *
 * @package     RedITEM.Backend
 * @subpackage  View
 * @since       0.9.1
 */
class ReditemViewItems extends ReditemView
{
	/**
	 * @var  boolean
	 */
	protected $items;

	/**
	 * @var  boolean
	 */
	protected $displaySidebar = true;

	/**
	 * Display the template list
	 *
	 * @param   string  $tpl  The template file to use
	 *
	 * @return   string
	 *
	 * @since   0.9.1
	 */
	public function display($tpl = null)
	{
		$this->items 		= $this->get('Items');
		$this->state 		= $this->get('State');
		$this->pagination 	= $this->get('Pagination');
		$this->filterForm	= $this->get('Form');

		$this->items 		= $this->getModel()->getPrepareItems($this->items);

		// Load fields for batch template
		$this->filterForm->loadFile('items_batch', false);
		JFactory::getApplication()->setUserState('com_reditem.global.cid', null);

		// Items ordering
		$this->ordering = array();

		if ($this->items)
		{
			foreach ($this->items as &$item)
			{
				$this->ordering[0][] = $item->id;
			}
		}

		/*
		 * Get displayable fields
		 */
		$displayableFields = array();
		$filterType = $this->getModel()->getState('filter.filter_types', 0);

		// Make sure user has choose filter by type
		if (is_numeric($filterType) && ($filterType > 0))
		{
			$fieldsModel = RModel::getAdminInstance('Fields', array('ignore_request' => true), 'com_reditem');
			$filterParams = array('searchable_in_backend' => '1');
			$fieldsModel->setState('filter.params', $filterParams);
			$fieldsModel->setState('filter.types', $filterType);

			$displayableFields = $fieldsModel->getItems();
		}

		$this->displayableFields = $displayableFields;

		parent::display($tpl);
	}

	/**
	 * Get the page title
	 *
	 * @return  string  The title to display
	 *
	 * @since   0.9.1
	 */
	public function getTitle()
	{
		return JText::_('COM_REDITEM_ITEM_ITEMS');
	}

	/**
	 * Get the tool-bar to render.
	 *
	 * @todo	The commented lines are going to be implemented once we have setup ACL requirements for redITEM
	 * @return  RToolbar
	 */
	public function getToolbar()
	{
		$user = JFactory::getUser();

		$firstGroup = new RToolbarButtonGroup;
		$secondGroup = new RToolbarButtonGroup;
		$thirdGroup = new RToolbarButtonGroup;

		if ($user->authorise('core.admin'))
		{
				$new = RToolbarBuilder::createNewButton('item.add');
				$secondGroup->addButton($new);

				$edit = RToolbarBuilder::createEditButton('item.edit');
				$secondGroup->addButton($edit);

				$checkin = RToolbarBuilder::createCheckinButton('items.checkin');
				$secondGroup->addButton($checkin);

				$delete = RToolbarBuilder::createDeleteButton('items.delete');
				$thirdGroup->addButton($delete);
		}

		$toolbar = new RToolbar;
		$toolbar->addGroup($firstGroup)->addGroup($secondGroup)->addGroup($thirdGroup);

		return $toolbar;
	}
}
