<?php
/**
 * @package     RedITEMCategoryFields.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Custom Fields List View
 *
 * @package     RedITEM.Backend
 * @subpackage  View
 * @since       0.9.1
 */
class ReditemCategoryFieldsViewCategoryFields extends ReditemView
{
	/**
	 * Do not display the sidebar
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	/**
	 * Display the category list
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

		$this->ordering = array();

		foreach ($this->items as &$item)
		{
			$this->ordering[0][] = $item->id;
		}

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
		return JText::_('COM_REDITEMCATEGORYFIELDS');
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
				$new = RToolbarBuilder::createNewButton('categoryfield.add');
				$secondGroup->addButton($new);

				$edit = RToolbarBuilder::createEditButton('categoryfield.edit');
				$secondGroup->addButton($edit);

				$checkin = RToolbarBuilder::createCheckinButton('categoryfields.checkin');
				$secondGroup->addButton($checkin);

				$delete = RToolbarBuilder::createDeleteButton('categoryfields.delete');
				$thirdGroup->addButton($delete);
		}

		$toolbar = new RToolbar;
		$toolbar->addGroup($firstGroup)->addGroup($secondGroup)->addGroup($thirdGroup);

		return $toolbar;
	}
}
