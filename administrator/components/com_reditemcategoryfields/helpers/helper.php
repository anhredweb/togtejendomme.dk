<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * RedITEM CustomFields Helper
 *
 * @package     RedITEM.Component
 * @subpackage  Helpers.CusomHelper
 * @since       2.0
 *
 */
class ReditemCategoryFieldsHelper
{
	/**
	 * Get extra field value of category
	 *
	 * @param   int  $categoryId  Category Id
	 *
	 * @return  array  Array of custom fields data
	 */
	public static function getFieldsData($categoryId)
	{
		$categoryId = (int) $categoryId;

		if ($categoryId > 0)
		{
			$db = JFactory::getDBO();

			$query = $db->getQuery(true);

			$query->select('d.*')
				->from($db->quoteName('#__reditem_category_fields_value', 'd'))
				->where($db->quoteName('d.cat_id') . ' = ' . $db->quote($categoryId));

			$db->setQuery($query);

			return $db->loadObjectList();
		}

		return false;
	}

	/**
	 * Get list extra value of extra field
	 *
	 * @param   int    $extraFieldId   Extra field ID
	 * @param   array  $categoriesIds  Array of categories which get values from
	 *
	 * @return  array  Array of extra fields data
	 */
	public static function getAllFieldsData($extraFieldId, $categoriesIds = array())
	{
		$extraFieldId = (int) $extraFieldId;

		if ($extraFieldId > 0)
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);

			// Get field code of extra field
			$query->select($db->quoteName('e.fieldcode'))
				->from($db->quoteName('#__reditem_category_fields', 'e'))
				->where($db->quoteName('e.id') . ' = ' . $extraFieldId);

			$db->setQuery($query);

			$result = $db->loadResult();

			// Field code is avaiable
			if ($result)
			{
				// Get list datas of field code
				$query = $db->getQuery(true);

				$query->select('DISTINCT (' . $db->quoteName('v.' . $result) . ')')
					->from($db->quoteName('#__reditem_category_fields_value', 'v'))
					->where($db->quoteName('v.' . $result) . ' <> ' . $db->quote(''));

				if (($categoriesIds) && !empty($categoriesIds))
				{
					$query->where($db->quoteName('v.cat_id') . ' IN (' . implode(',', $categoriesIds) . ')');
				}

				$query->order($db->quoteName('v.' . $result));

				$db->setQuery($query);

				return $db->loadResultArray();
			}
		}

		return false;
	}
}
