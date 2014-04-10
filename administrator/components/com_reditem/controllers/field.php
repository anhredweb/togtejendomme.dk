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
 * The template edit controller
 *
 * @package     RedITEM.Backend
 * @subpackage  Controller.Field
 * @since       2.0
 */
class ReditemControllerField extends RControllerForm
{
	/**
	 * Add field
	 *
	 * @return void
	 */
	public function add()
	{
		$app = JFactory::getApplication();
		$app->setUserState('com_reditem.global.field.type', '');

		return parent::add();
	}

	/**
	 * Edit field
	 *
	 * @param   int     $key     [description]
	 * @param   string  $urlVar  [description]
	 *
	 * @return void
	 */
	public function edit($key = null, $urlVar = null)
	{
		$app = JFactory::getApplication();
		$fieldModel = RModel::getAdminInstance('Field');

		$field = $fieldModel->getItem();
		$app->setUserState('com_reditem.global.field.type', $field->type);

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

		$app->setUserState('com_reditem.edit.field.data', $data);

		$app->setUserState('com_reditem.global.field.type', $data['type']);

		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId), false));
	}
}
