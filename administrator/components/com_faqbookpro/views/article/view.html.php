<?php
/**
* @title			FAQ Book Pro
* @version   		3.x
* @copyright   		Copyright (C) 2011-2013 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @author email   	info@minitek.gr
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die;

class FAQBookProViewArticle extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;

	public function display($tpl = null)
	{
		if ($this->getLayout() == 'pagebreak')
		{
			// TODO: This is really dogy - should change this one day.
			$eName    = JRequest::getVar('e_name');
			$eName    = preg_replace('#[^A-Z0-9\-\_\[\]]#i', '', $eName);
			$document = JFactory::getDocument();
			$document->setTitle(JText::_('COM_FAQBOOKPRO_PAGEBREAK_DOC_TITLE'));
			$this->eName = &$eName;
			parent::display($tpl);
			return;
		}

		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->canDo	= FAQBookProHelper::getActions($this->state->get('filter.category_id'));

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		if ($this->getLayout() == 'modal')
		{
			$this->form->setFieldAttribute('language', 'readonly', 'true');
			$this->form->setFieldAttribute('catid', 'readonly', 'true');
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		$canDo		= FAQBookProHelper::getActions($this->state->get('filter.category_id'), $this->item->id);
		JToolbarHelper::title(JText::_('COM_FAQBOOKPRO_PAGE_'.($checkedOut ? 'VIEW_ARTICLE' : ($isNew ? 'ADD_ARTICLE' : 'EDIT_ARTICLE'))), 'article-add.png');

		// Built the actions for new and existing records.

		// For new records, check the create permission.
		if ($isNew && (count($user->getAuthorisedCategories('com_faqbookpro', 'core.create')) > 0))
		{
			JToolbarHelper::apply('article.apply');
			JToolbarHelper::save('article.save');
			JToolbarHelper::save2new('article.save2new');
			JToolbarHelper::cancel('article.cancel');
		}
		else
		{
			// Can't save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId))
				{
					JToolbarHelper::apply('article.apply');
					JToolbarHelper::save('article.save');

					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($canDo->get('core.create'))
					{
						JToolbarHelper::save2new('article.save2new');
					}
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create'))
			{
				JToolbarHelper::save2copy('article.save2copy');
			}

			JToolbarHelper::cancel('article.cancel', 'JTOOLBAR_CLOSE');
		}

	}
}
