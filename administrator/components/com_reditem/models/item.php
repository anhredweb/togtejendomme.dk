<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('helper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers');

/**
 * RedITEM category Model
 *
 * @package     RedITEM.Component
 * @subpackage  Models.Category
 * @since       0.9.1
 *
 */
class ReditemModelItem extends RModelAdmin
{
	public $item = null;
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

		$published = $this->getState('filter.published');

		if (isset($item->id))
		{
			$db = JFactory::getDBO();

			// Get categories of item
			$query = $db->getQuery(true);
			$query->select($db->quoteName('category_id'));
			$query->from($db->quoteName('#__reditem_item_category_xref'));
			$query->where($db->quoteName('item_id') . ' = ' . $db->quote($item->id));

			if ($published)
			{
				$query->where($db->quoteName('published') . ' = ' . (int) $published);
			}
			elseif ($published === '')
			{
				$query->where($db->quoteName('published') . ' IN (0, 1)');
			}

			$db->setQuery($query);

			$item->categories = $db->loadResultArray();

			/**
			 * Get keywords of item. Not used now
			$query = $db->getQuery(true);
			$query->select($db->quoteName('keyword_id'));
			$query->from($db->quoteName('#__reditem_item_keyword_xref'));
			$query->where($db->quoteName('item_id') . ' = ' . $db->quote($item->id));
			$db->setQuery($query);

			$item->keywords = $db->loadResultArray();
			*/

			// Get custom field values
			$query = $db->getQuery(true);
			$query->select($db->quoteName('table_name'))
				->from('#__reditem_types')
				->where($db->quoteName('id') . ' = ' . $db->quote($item->type_id));
			$db->setQuery($query);
			$rs = $db->loadObject();
			$tb = '#__reditem_types_' . $rs->table_name;

			$query = $db->getQuery(true);
			$query->select('cf.*');
			$query->from($db->quoteName($tb, 'cf'));
			$query->where($db->quoteName('cf.id') . ' = ' . $db->quote($item->id));
			$db->setQuery($query);

			$item->customfield_values = (array) $db->loadObject();

			// Remove the id column of custom fields value
			array_shift($item->customfield_values);
		}

		$this->item = $item;

		return $item;
	}

	/**
	 * Method to get custom field.
	 *
	 * @return  array
	 */
	public function getCustomFields()
	{
		$type_id = JFactory::getApplication()->getUserState('com_reditem.global.tid', '0');
		$customfields = RModel::getAdminInstance('Fields', array('ignore_request' => true));
		$customfields->setState('filter.types', $type_id);
		$customfields->setState('filter.published', 1);
		$rows = $customfields->getItems();

		$fields = array();

		foreach ($rows AS $row)
		{
			if ($row->published == 1)
			{
				$field = ReditemHelperHelper::getCustomField($row->type);
				$field->bind($row);

				if ((isset($this->item->customfield_values)) && ($this->item->customfield_values[$row->fieldcode]))
				{
					$field->value = $this->item->customfield_values[$row->fieldcode];
				}

				$fields[] = $field;
			}
		}

		return $fields;
	}

	/**
	 * Method to set featured of item.
	 *
	 * @param   int  $id     Id of item
	 * @param   int  $state  featured state of item
	 *
	 * @return  boolean
	 */
	public function featured($id = null, $state = 0)
	{
		$db = JFactory::getDbo();

		if ($id)
		{
			$query = $db->getQuery(true);

			$query->update($db->quoteName('#__reditem_items', 'i'))
				->set($db->quoteName('i.featured') . ' = ' . (int) $state)
				->where($db->quoteName('i.id') . ' = ' . (int) $id);

			$db->setQuery($query);

			if (!$db->execute())
			{
				return false;
			}

			return true;
		}

		return false;
	}
}
