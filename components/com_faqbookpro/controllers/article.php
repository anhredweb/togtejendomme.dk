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

jimport('joomla.application.component.controllerform');

class FAQBookProControllerArticle extends JControllerForm
{
	public function __construct($config = array())
	{
		parent::__construct($config);

		// An article edit form can come from the articles or featured view.
		// Adjust the redirect view on the value of 'return' in the request.
		if (JFactory::getApplication()->input->get('return') == 'featured')
		{
			$this->view_list = 'featured';
			$this->view_item = 'article&return=featured';
		}
	}
	
	protected function allowAdd($data = array())
	{
		$user = JFactory::getUser();
		$categoryId = JArrayHelper::getValue($data, 'catid', JFactory::getApplication()->input->getInt('filter_category_id'), 'int');
		$allow = null;

		if ($categoryId)
		{
			// If the category has been passed in the data or URL check it.
			$allow = $user->authorise('core.create', 'com_faqbookpro.category.' . $categoryId);
		}

		if ($allow === null)
		{
			$app =& JFactory::getApplication();
			$uri = & JFactory::getURI();
			$current = $uri->toString();
			$app->redirect(str_replace('&amp;', '&', $current), JText::_('COM_FAQBOOKPRO_SAVE_NOT_PERMITTED'), $msgType='Error');	
			
		}
		else
		{
			if ($allow == false) {
				$app =& JFactory::getApplication();
				$uri = & JFactory::getURI();
				$current = $uri->toString();
				$app->redirect(str_replace('&amp;', '&', $current), JText::_('COM_FAQBOOKPRO_SAVE_NOT_PERMITTED'), $msgType='Error');	
			} else {
			  return true;
			}
		}
	}

	//protected function postSaveHook(JModelLegacy $model, $validData = array())
	protected function postSaveHook($model, $validData = array())
	{
		$app =& JFactory::getApplication();
		$params = FaqBookProHelperUtilities::getParams('com_faqbookpro');
		if ($params->get('ask_autopublish')) {
			$message = JText::_('COM_FAQBOOKPRO_ARTICLE_SUCCESSFULLY_SUBMITTED');
		} else {
			$message = JText::_('COM_FAQBOOKPRO_ARTICLE_SUBMITTED_AND_AWAITING_REVIEW');
		}
		$link = JRoute::_('index.php?option=com_faqbookpro&view=faqbook');
		$app->redirect(str_replace('&amp;', '&', $link), $message, $msgType='Success');	
		//return;
	}
}
