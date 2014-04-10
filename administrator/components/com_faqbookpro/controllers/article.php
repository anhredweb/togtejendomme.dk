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
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd();
		}
		else
		{
			return $allow;
		}
	}

	protected function allowEdit($data = array(), $key = 'id')
	{
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user = JFactory::getUser();
		$userId = $user->get('id');

		// Check general edit permission first.
		if ($user->authorise('core.edit', 'com_faqbookpro.article.' . $recordId))
		{
			return true;
		}

		// Fallback on edit.own.
		// First test if the permission is available.
		if ($user->authorise('core.edit.own', 'com_faqbookpro.article.' . $recordId))
		{
			// Now test the owner is the user.
			$ownerId = (int) isset($data['created_by']) ? $data['created_by'] : 0;
			if (empty($ownerId) && $recordId)
			{
				// Need to do a lookup from the model.
				$record = $this->getModel()->getItem($recordId);

				if (empty($record))
				{
					return false;
				}

				$ownerId = $record->created_by;
			}

			// If the owner matches 'me' then do the test.
			if ($ownerId == $userId)
			{
				return true;
			}
		}

		// Since there is no asset tracking, revert to the component permissions.
		return parent::allowEdit($data, $key);
	}

	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel('Article', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_faqbookpro&view=articles' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}

	/*protected function postSaveHook(JModelLegacy $model, $validData = array())
	{

		return;
	}*/
}
