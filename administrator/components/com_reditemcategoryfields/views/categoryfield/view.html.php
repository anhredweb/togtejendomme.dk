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
 * Field edit view
 *
 * @package     RedITEMCategoryFields.Backend
 * @subpackage  View
 * @since       0.9.1
 */
class RedITEMCategoryFieldsViewCategoryField extends ReditemView
{
	/**
	 * Do not display the sidebar
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	/**
	 * Display the category edit page
	 *
	 * @param   string  $tpl  The template file to use
	 *
	 * @return   string
	 *
	 * @todo Check the extra fields once implemented
	 *
	 * @since   0.9.1
	 */
	public function display($tpl = null)
	{
		$document = JFactory::getDocument();

		$this->form	= $this->get('Form');
		$this->item	= $this->get('Item');

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Get the view title.
	 *
	 * @return  string  The view title.
	 */
	public function getTitle()
	{
		return JText::_('COM_REDITEMCATEGORYFIELDS_FIELD');
	}

	/**
	 * Get the toolbar to render.
	 *
	 * @todo	We have setup ACL requirements for redITEM
	 *
	 * @return  RToolbar
	 */
	public function getToolbar()
	{
		$group = new RToolbarButtonGroup;

		$save = RToolbarBuilder::createSaveButton('categoryfield.apply');
		$saveAndClose = RToolbarBuilder::createSaveAndCloseButton('categoryfield.save');
		$saveAndNew = RToolbarBuilder::createSaveAndNewButton('categoryfield.save2new');
		$save2Copy = RToolbarBuilder::createSaveAsCopyButton('categoryfield.save2copy');

		$group->addButton($save)
			->addButton($saveAndClose)
			->addButton($saveAndNew)
			->addButton($save2Copy);

		if (empty($this->item->id))
		{
			$cancel = RToolbarBuilder::createCancelButton('categoryfield.cancel');
		}
		else
		{
			$cancel = RToolbarBuilder::createCloseButton('categoryfield.cancel');
		}

		$group->addButton($cancel);

		$toolbar = new RToolbar;
		$toolbar->addGroup($group);

		return $toolbar;
	}
}
