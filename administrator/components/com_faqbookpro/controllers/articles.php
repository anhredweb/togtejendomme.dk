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

jimport('joomla.application.component.controlleradmin');

class FAQBookProControllerArticles extends JControllerAdmin
{
	public function __construct($config = array())
	{
		parent::__construct($config);

		// Articles default form can come from the articles or featured view.
		// Adjust the redirect view on the value of 'view' in the request.
		if (JFactory::getApplication()->input->get('view') == 'featured')
		{
			$this->view_list = 'featured';
		}

		$this->registerTask('unfeatured',	'featured');
	}

	public function featured()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user   = JFactory::getUser();
		$ids    = JFactory::getApplication()->input->get('cid', array(), 'array');
		$values = array('featured' => 1, 'unfeatured' => 0);
		$task   = $this->getTask();
		$value  = JArrayHelper::getValue($values, $task, 0, 'int');

		// Access checks.
		foreach ($ids as $i => $id)
		{
			if (!$user->authorise('core.edit.state', 'com_faqbookpro.article.'.(int) $id))
			{
				// Prune items that you can't change.
				unset($ids[$i]);
				JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
			}
		}

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Publish the items.
			if (!$model->featured($ids, $value))
			{
				JError::raiseWarning(500, $model->getError());
			}
		}

		$this->setRedirect('index.php?option=com_faqbookpro&view=articles');
	}

	public function getModel($name = 'Article', $prefix = 'FAQBookProModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	protected function postDeleteHook(JModelLegacy $model, $ids = null)
	{
	}

}
