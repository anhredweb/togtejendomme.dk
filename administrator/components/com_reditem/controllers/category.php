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
 * The category edit controller
 *
 * @package     RedITEM.Backend
 * @subpackage  Controller.Category
 * @since       2.0
 */
class ReditemControllerCategory extends RControllerForm
{
	/**
	 * Task for add Category
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
	 * For edit an category
	 *
	 * @param   int     $key     [description]
	 * @param   string  $urlVar  [description]
	 *
	 * @return void
	 */
	public function edit($key = null, $urlVar = null)
	{
		$itemmodel = RModel::getAdminInstance('Category');

		$item = $itemmodel->getItem();

		$app = JFactory::getApplication();
		$app->setUserState('com_reditem.global.tid', $item->type_id);
		$app->setUserState('com_reditem.global.cid', array($item->id));

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
		$recordId = $app->input->get('id');
		$data = $app->input->get('jform', null, 'array');

		$typemodel = RModel::getAdminInstance('Type', array('ignore_request' => true), 'com_reditem');
		$typemodel->setState('list.select', 'params');
		$params = $typemodel->getItem($data['type_id'])->params;
		$data['template_id'] = $params['default_categorydetail_template'];

		$app->setUserState('com_reditem.edit.category.data', $data);

		$app->setUserState('com_reditem.global.tid', $data['type_id']);

		if ($recordId)
		{
			$app->setUserState('com_reditem.global.cid', array($recordId));
		}

		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId), false));
	}
}
