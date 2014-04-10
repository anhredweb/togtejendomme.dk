<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_reditem/helpers/helper.php';

/**
 * Category edit view
 *
 * @package     RedITEM.Backend
 * @subpackage  View
 * @since       0.9.1
 */
class ReditemViewTemplate extends ReditemView
{
	/**
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
		$this->tags = $this->get('Tags');
		$this->categoryTags = ReditemHelperHelper::getCategoryTags($this->item);
		$this->itemTags = ReditemHelperHelper::getItemTags($this->item);
		$this->filterTags = ReditemHelperHelper::getFilterTags($this->item);
		$this->filterCategoryExtraTags = ReditemHelperHelper::getCategoryFilterTags($this->item);

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
		return JText::_('COM_REDITEM_TEMPLATE_TEMPLATE');
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

		$save = RToolbarBuilder::createSaveButton('template.apply');
		$saveAndClose = RToolbarBuilder::createSaveAndCloseButton('template.save');
		$saveAndNew = RToolbarBuilder::createSaveAndNewButton('template.save2new');
		$save2Copy = RToolbarBuilder::createSaveAsCopyButton('template.save2copy');

		$group->addButton($save)
			->addButton($saveAndClose)
			->addButton($saveAndNew)
			->addButton($save2Copy);

		if (empty($this->item->id))
		{
			$cancel = RToolbarBuilder::createCancelButton('template.cancel');
		}
		else
		{
			$cancel = RToolbarBuilder::createCloseButton('template.cancel');
		}

		$group->addButton($cancel);

		$toolbar = new RToolbar;
		$toolbar->addGroup($group);

		return $toolbar;
	}
}
