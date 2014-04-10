<?php
/**
 * @package     RedITEM.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Item detail model
 *
 * @package     RedITEM.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class ReditemModelItemdetail extends RModel
{
	/**
	 * Get data of item
	 *
	 * @return  boolean/array
	 */
	public function getData()
	{
		$itemmodel = RModel::getAdminInstance('Item', array('ignore_request' => true), 'com_reditem');
		$id = JFactory::getApplication()->input->getInt('id', 0);

		if ($id)
		{
			$item = $itemmodel->getItem($id);

			if ($item)
			{
				$templatemodel = RModel::getAdminInstance('Template', array('ignore_request' => true), 'com_reditem');
				$item->template = $templatemodel->getItem($item->template_id);

				return $item;
			}
		}

		return false;
	}
}
