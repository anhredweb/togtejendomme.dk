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
 * Keyword edit view
 *
 * @package     RedITEM.Backend
 * @subpackage  View
 * @since       0.9.1
 */
class ReditemViewKeyword extends ReditemView
{
	/**
	 * Display the category edit page
	 *
	 * @param   string  $tpl  The keyword file to use
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

		// Display the keyword
		parent::display($tpl);
	}

	/**
	 * Get the view title.
	 *
	 * @return  string  The view title.
	 */
	public function getTitle()
	{
		return JText::_('COM_REDITEM_KEYWORD_KEYWORD');
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

		$save = RToolbarBuilder::createSaveButton('keyword.apply');
		$saveAndClose = RToolbarBuilder::createSaveAndCloseButton('keyword.save');
		$saveAndNew = RToolbarBuilder::createSaveAndNewButton('keyword.save2new');
		$save2Copy = RToolbarBuilder::createSaveAsCopyButton('keyword.save2copy');

		$group->addButton($save)
			->addButton($saveAndClose)
			->addButton($saveAndNew)
			->addButton($save2Copy);

		if (empty($this->item->id))
		{
			$cancel = RToolbarBuilder::createCancelButton('keyword.cancel');
		}
		else
		{
			$cancel = RToolbarBuilder::createCloseButton('keyword.cancel');
		}

		$group->addButton($cancel);

		$toolbar = new RToolbar;
		$toolbar->addGroup($group);

		return $toolbar;
	}
}
