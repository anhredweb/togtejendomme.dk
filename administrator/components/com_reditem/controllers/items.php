<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

/**
 * The templates controller
 *
 * @package     RedITEM.Backend
 * @subpackage  Controller.Items
 * @since       2.0
 */
class ReditemControllerItems extends RControllerAdmin
{
	/**
	 * constructor (registers additional tasks to methods)
	 */
	public function __construct()
	{
		parent::__construct();

		// Write this to make two tasks use the same method (in this example the add method uses the edit method)
		$this->registerTask('add', 'edit');
	}

	/**
	 * display the add and the edit form
	 *
	 * @return void
	 */
	public function edit()
	{
		// Set default variables on edit startup
		// To access variables from template write use $_REQUEST[view] etc.
		JRequest::setVar('view', 'item');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * Method to save the submitted ordering values for records.
	 *
	 * @return  boolean  True on success
	 */
	public function saveorder()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Get the input
		$pks = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');
		$cat = $this->input->getInt('cat', 0);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorderprod($pks, $order, $cat);

		if ($return)
		{
			echo '1';
		}

		JFactory::getApplication()->close();
	}

	/**
	 * Method to set item to "Featured" item.
	 *
	 * @return  void
	 */
	public function setFeatured()
	{
		$app = JFactory::getApplication();
		$itemModel = RModel::getAdminInstance('Item', array('ignore_request' => true));

		$cids = JFactory::getApplication()->input->get('cid', array(), 'array');
		$return = $this->input->get('return', null, 'base64');
		$cid = 0;

		if (!empty($cids))
		{
			$cid = $cids[0];

			if (!$itemModel->featured($cid, 1))
			{
				$app->enqueueMessage(JText::_('COM_REDITEM_ITEMS_SET_FEATURED_ERROR'), 'error');
			}
			else
			{
				$app->enqueueMessage(JText::_('COM_REDITEM_ITEMS_SET_FEATURED_SUCCESS'));
			}
		}

		if ($return)
		{
			$this->setRedirect(base64_decode($return));
		}
		else
		{
			$this->setRedirect(JURI::base() . 'index.php?option=com_reditem&view=items');
		}

		$this->redirect();
	}

	/**
	 * Method to set "Featured" item to item.
	 *
	 * @return  void
	 */
	public function setUnFeatured()
	{
		$app = JFactory::getApplication();
		$itemModel = RModel::getAdminInstance('Item', array('ignore_request' => true));

		$cids = JFactory::getApplication()->input->get('cid', array(), 'array');
		$return = $this->input->get('return', null, 'base64');
		$cid = 0;

		if (!empty($cids))
		{
			$cid = $cids[0];

			if (!$itemModel->featured($cid, 0))
			{
				$app->enqueueMessage(JText::_('COM_REDITEM_ITEMS_SET_UN_FEATURED_ERROR'), 'error');
			}
			else
			{
				$app->enqueueMessage(JText::_('COM_REDITEM_ITEMS_SET_UN_FEATURED_SUCCESS'));
			}
		}

		if ($return)
		{
			$this->setRedirect(base64_decode($return));
		}
		else
		{
			$this->setRedirect(JURI::base() . 'index.php?option=com_reditem&view=items');
		}

		$this->redirect();
	}
}
