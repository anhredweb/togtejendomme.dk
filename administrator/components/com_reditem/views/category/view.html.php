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
 * Category edit view
 *
 * @package     RedITEM.Backend
 * @subpackage  View
 * @since       0.9.1
 */
class ReditemViewCategory extends ReditemView
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
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('reditem_categories');

		$this->useGmapField = false;
		$this->form	= $this->get('Form');
		$this->params = $this->form->getGroup('params');
		$this->item	= $this->get('Item');

		$extrafields = $dispatcher->trigger('prepareCategoryEdit', array($this->item));

		if ($extrafields)
		{
			$this->extrafields = $extrafields[0];
		}

		// Check if category use Google Gmaps field
		if (isset($this->item->id))
		{
			$typeModel = RModel::getAdminInstance('Type', array('ignore_request' => true), 'com_reditem');
			$type = $typeModel->getItem($this->item->type_id);
			$typeParams = new JRegistry($type->params);
			$this->useGmapField = (boolean) $typeParams->get('category_gmap_field', false);
		}

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
		return JText::_('COM_REDITEM_CATEGORY_CATEGORY');
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

		$save = RToolbarBuilder::createSaveButton('category.apply');
		$saveAndClose = RToolbarBuilder::createSaveAndCloseButton('category.save');
		$saveAndNew = RToolbarBuilder::createSaveAndNewButton('category.save2new');
		$save2Copy = RToolbarBuilder::createSaveAsCopyButton('category.save2copy');

		$group->addButton($save)
			->addButton($saveAndClose)
			->addButton($saveAndNew)
			->addButton($save2Copy);

		if (empty($this->item->category_id))
		{
			$cancel = RToolbarBuilder::createCancelButton('category.cancel');
		}
		else
		{
			$cancel = RToolbarBuilder::createCloseButton('category.cancel');
		}

		$group->addButton($cancel);

		$toolbar = new RToolbar;
		$toolbar->addGroup($group);

		return $toolbar;
	}
}
