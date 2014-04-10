<?php
/**
 * @package     RedITEM.Frontend
 * @subpackage  RedITEM
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Item view.
 *
 * @package     RedITEM.Frontend
 * @subpackage  View.Html
 * @since       2.0
 */
class ReditemViewItemDetail extends JViewLegacy
{
	protected $item;
	/**
	 * Display template
	 *
	 * @param   string  $tpl  [description]
	 *
	 * @return void
	 */
	public function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		$params = $mainframe->getParams();

		$this->item = $this->get('Data');

		// Check whether category access level allows access.
		$user	= JFactory::getUser();
		$groups	= $user->getAuthorisedViewLevels();

		if (!in_array($this->item->access, $groups))
		{
			return JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		$this->assignRef('data', $this->item);
		$this->assignRef('user', $user);
		$this->assignRef('params', $params);

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return void
	 */
	protected function _prepareDocument()
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway	= $app->getPathway();
		$title		= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_REDITEM_GLOBAL_CATEGORY'));
		}

		$id = (int) @$menu->query['id'];

		if ($menu && (($menu->query['option'] != 'com_reditem') || ($menu->query['view'] != 'itemdetail') || ($id != $this->item->id)))
		{
			$pathway->addItem($this->item->title, '');
		}

		$title = $this->params->get('page_title', '');

		if (empty($title))
		{
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}
}
