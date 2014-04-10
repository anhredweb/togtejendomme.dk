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

class FAQBookProViewAbout extends JViewLegacy
{
	public function display($tpl = null)
	{
		FAQBookProHelper::addSubmenu('about');
		
		$this->addToolbar();
		//$this->sidebar = JSubMenuHelper::render();
		
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		$user  = JFactory::getUser();

		JToolbarHelper::title(JText::_('COM_FAQBOOKPRO_ABOUT_TITLE'), 'about.png');
		
		if ($user->authorise('core.admin', 'com_faqbookpro')) {  
			JToolbarHelper::preferences('com_faqbookpro');
		}
		
	}

}
