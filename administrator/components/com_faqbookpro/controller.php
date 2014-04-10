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

if(!defined('DS')){
define('DS',DIRECTORY_SEPARATOR);
}

class FAQBookProController extends JControllerLegacy
{
	protected $default_view = 'dashboard';

	public function display($cachable = false, $urlparams = false)
	{
		
		$view   = JFactory::getApplication()->input->get('view', 'articles');
		$layout = JFactory::getApplication()->input->get('layout', 'articles');
		$id     = JFactory::getApplication()->input->getInt('id');

		// Check for edit form.
		if ($view == 'article' && $layout == 'edit' && !$this->checkEditId('com_faqbookpro.edit.article', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_faqbookpro&view=articles', false));

			return false;
		}

		parent::display();

		return $this;
	}
	
	public function purgeImages()
	{
		jimport('joomla.filesystem.folder');
		JSession::checkToken('request') or jexit('Invalid token');		
		$app =& JFactory::getApplication();
		
		$tmppath =  JPATH_SITE.DS.'images'.DS.'faqbookpro'.DS;
		if(file_exists($tmppath)) {
			JFolder::delete($tmppath);
			$message = JText::_('COM_FAQBOOKPRO_IMAGES_PURGED');
			$link = JRoute::_('index.php?option=com_faqbookpro');
			$app->redirect(str_replace('&amp;', '&', $link), $message);	
		} else {
			$message = JText::_('COM_FAQBOOKPRO_IMAGES_PURGED_ALREADY');
			$link = JRoute::_('index.php?option=com_faqbookpro');
			$app->redirect(str_replace('&amp;', '&', $link), $message);	
		}
			
		
	}
	
			
}
