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

class FAQBookProViewDashboard extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	public function display($tpl = null)
	{
		FAQBookProHelper::addSubmenu('dashboard');
		
		JHTML::_('behavior.tooltip', '.hasTip');
				jimport('joomla.html.pane');
				$pane	=& JPane::getInstance('sliders');
				
				$this->assignRef( 'pane'		, $pane );
				
		$this->addToolbar();
		//$this->sidebar = JHtmlSidebar::render();
		
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		$user  = JFactory::getUser();

		JToolbarHelper::title(JText::_('COM_FAQBOOKPRO_DASHBOARD_MANAGER'), 'dashboard.png');
		
		if ($user->authorise('core.admin', 'com_faqbookpro')) {  
			JToolbarHelper::preferences('com_faqbookpro');
		}
		
	}

}
