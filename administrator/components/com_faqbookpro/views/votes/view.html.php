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

class FAQBookProViewVotes extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	public function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			FAQBookProHelper::addSubmenu('votes');
		}

		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->authors		= $this->get('Authors');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
			//$this->sidebar = JHtmlSidebar::render();
		}

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		$canDo = FAQBookProHelper::getActions($this->state->get('filter.category_id'));
		$user  = JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_FAQBOOKPRO_VOTES_TITLE'), 'featured.png');

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('votes.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('votes.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::archiveList('votes.archive');
		}

		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'votes.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('votes.trash');
		}

		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_faqbookpro');
		}

		/*JHtmlSidebar::setAction('index.php?option=com_faqbookpro&view=votes');

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_CATEGORY'),
			'filter_category_id',
			JHtml::_('select.options', JHtml::_('category.options', 'com_faqbookpro'), 'value', 'text', $this->state->get('filter.category_id'))
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_AUTHOR'),
			'filter_author_id',
			JHtml::_('select.options', $this->authors, 'value', 'text', $this->state->get('filter.author_id'))
		);*/

	}

	protected function getSortFields()
	{
		return array(
			'a.published' => JText::_('JSTATUS'),
			'article_title' => JText::_('COM_FAQBOOKPRO_SUBMENU_ARTICLES'),
			'a.user_id' => JText::_('JAUTHOR'),
			'a.creation_date' => JText::_('JDATE'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
