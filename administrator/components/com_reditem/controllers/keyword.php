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
 * The keyword edit controller
 *
 * @package     RedITEM.Backend
 * @subpackage  Controller.Keyword
 * @since       2.0
 */
class ReditemControllerKeyword extends RControllerForm
{
	/**
	 * [description]
	 *
	 * @return void
	 */
	public function add()
	{
		$app = JFactory::getApplication();
		$app->setUserState('com_reditem.global.edit.kwid', '0');

		return parent::add();
	}

	/**
	 * [description]
	 *
	 * @param   int     $key     [description]
	 * @param   string  $urlVar  [description]
	 *
	 * @return void
	 */
	public function edit($key = null, $urlVar = null)
	{
		$itemmodel = RModel::getAdminInstance('Keyword');

		$item = $itemmodel->getItem();

		$app = JFactory::getApplication();
		$app->setUserState('com_reditem.global.edit.kwid', $item->id);

		return parent::edit($key, $urlVar);
	}
}
