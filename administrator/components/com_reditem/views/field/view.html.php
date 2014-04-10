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
 * Field edit view
 *
 * @package     RedITEM.Backend
 * @subpackage  View
 * @since       0.9.1
 */
class ReditemViewField extends ReditemView
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
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();

		$fieldType = $app->getUserState('com_reditem.global.field.type', '');
		$editData = $app->getUserState('com_reditem.edit.field.data', array());

		$this->form	= $this->get('Form');
		$this->item	= $this->get('Item');

		if ($fieldType)
		{
			$this->form->loadFile('field_' . $fieldType);
			$this->item->type = $fieldType;

			if (isset($editData['params']) && is_array($editData['params']))
			{
				foreach ($editData['params'] as $key => $value)
				{
					$this->form->setValue($key, 'params', $value);
				}
			}
			elseif (isset($this->item->params))
			{
				$params = new JRegistry($this->item->params);
				$params = $params->toArray();

				foreach ($params as $key => $value)
				{
					$this->form->setValue($key, 'params', $value);
				}
			}
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
		return JText::_('COM_REDITEM_FIELD_FIELD');
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

		$save = RToolbarBuilder::createSaveButton('field.apply');
		$saveAndClose = RToolbarBuilder::createSaveAndCloseButton('field.save');
		$saveAndNew = RToolbarBuilder::createSaveAndNewButton('field.save2new');
		$save2Copy = RToolbarBuilder::createSaveAsCopyButton('field.save2copy');

		$group->addButton($save)
			->addButton($saveAndClose)
			->addButton($saveAndNew)
			->addButton($save2Copy);

		if (empty($this->item->id))
		{
			$cancel = RToolbarBuilder::createCancelButton('field.cancel');
		}
		else
		{
			$cancel = RToolbarBuilder::createCloseButton('field.cancel');
		}

		$group->addButton($cancel);

		$toolbar = new RToolbar;
		$toolbar->addGroup($group);

		return $toolbar;
	}
}
