<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * The item edit controller
 *
 * @package     RedITEM.Backend
 * @subpackage  Controller.Item
 * @since       2.0
 */

class ReditemControllerItem extends RControllerForm
{
	/**
	 * Add an item
	 *
	 * @return void
	 */
	public function add()
	{
		$app = JFactory::getApplication();
		$app->setUserState('com_reditem.global.tid', '');
		$app->setUserState('com_reditem.global.cid', array());

		return parent::add();
	}

	/**
	 * Edit an item
	 *
	 * @param   int     $key     [description]
	 * @param   string  $urlVar  [description]
	 *
	 * @return void
	 */
	public function edit($key = null, $urlVar = null)
	{
		$itemmodel = RModel::getAdminInstance('Item');

		$item = $itemmodel->getItem();

		$app = JFactory::getApplication();
		$app->setUserState('com_reditem.global.tid', $item->type_id);
		$app->setUserState('com_reditem.global.cid', array());

		return parent::edit($key, $urlVar);
	}

	/**
	 * For auto-submit form when client choose type
	 *
	 * @return void
	 */
	public function setType()
	{
		$app = JFactory::getApplication();
		$recordId = JRequest::getInt('id');
		$data = JRequest::getVar('jform', array(), 'post', 'array');

		// Get default template from type configuration
		$typemodel = RModel::getAdminInstance('Type', array('ignore_request' => true));
		$typemodel->setState('list.select', 'params');
		$params = $typemodel->getItem($data['type_id'])->params;
		$data['template_id'] = $params['default_itemdetail_template'];

		// Check if default template has exist
		$templatemodel = RModel::getAdminInstance('Template', array('ignore_request' => true));
		$templatemodel->setState('list.select', 'id');
		$defaultTemplate = $templatemodel->getItem($params['default_itemdetail_template']);

		$data['template_id'] = ($defaultTemplate) ? $defaultTemplate->id : null;

		$app->setUserState('com_reditem.edit.item.data', $data);

		$app->setUserState('com_reditem.global.tid', $data['type_id']);
		$app->setUserState('com_reditem.global.cid', array());

		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId), false));
	}
}
