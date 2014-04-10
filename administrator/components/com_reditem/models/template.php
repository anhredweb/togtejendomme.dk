<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('fields', JPATH_COMPONENT . '/helpers');
/**
 * RedITEM template Model
 *
 * @package     RedITEM.Component
 * @subpackage  Models.Template
 * @since       0.9.1
 *
 */
class ReditemModelTemplate extends RModelAdmin
{
	/**
	 * Method to get the row form.
	 *
	 * @param   int  $pk  Primary key
	 *
	 * @return	mixed	A JForm object on success, false on failure
	 *
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		$app = JFactory::getApplication();
		$item = parent::getItem($pk);

		if ($item)
		{
			// Get custom fields
			$fieldsmodel = RModel::getAdminInstance('Fields', array('ignore_request' => true), 'com_reditem');
			$fieldsmodel->setState('filter.types', $item->type_id);
			$fieldsmodel->setState('filter.published', 1);
			$tags = $fieldsmodel->getItems();

			if (count($tags))
			{
				$item->tags = $tags;
			}
		}

		return $item;
	}
}
