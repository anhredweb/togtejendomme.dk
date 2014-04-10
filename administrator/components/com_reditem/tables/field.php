<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Custom Field table
 *
 * @package     RedITEM.Backend
 * @subpackage  Table
 * @since       0.9.1
 */
class ReditemTableField extends RTable
{
	/**
	 * The name of the table with category
	 *
	 * @var string
	 * @since 0.9.1
	 */
	protected $_tableName = 'reditem_fields';

	/**
	 * The primary key of the table
	 *
	 * @var string
	 * @since 0.9.1
	 */
	protected $_tableKey = 'id';

	/**
	 * Field name to publish/unpublish table registers. Ex: state
	 *
	 * @var  string
	 */
	protected $_tableFieldState = 'published';

	/**
	 * Method to store a node in the database table.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 */
	public function store($updateNulls = false)
	{
		$db = JFactory::getDBO();
		$input = JFactory::getApplication()->input;
		$jform = $input->get('jform', null, 'array');

		if (isset($jform['params']))
		{
			$params = new JRegistry($jform['params']);
			$this->params = $params->toString();
		}

		if (!$this->id)
		{
			$isNew = true;
		}
		else
		{
			$isNew = false;
		}

		if ($isNew)
		{
			$this->fieldcode = str_replace('-', '_', JFilterOutput::stringURLSafe($this->name));
		}

		if (!parent::store($updateNulls))
		{
			return false;
		}

		if ($isNew)
		{
			// Get "type" table columns
			$query = $db->getQuery(true);
			$query->select($db->quoteName('table_name'))
				->from('#__reditem_types')
				->where($db->quoteName('id') . ' = ' . $db->quote($this->type_id));
			$db->setQuery($query);
			$rs = $db->loadObject();

			// Check if columns exists
			$tb = '#__reditem_types_' . $rs->table_name;
			$db->setQuery('SHOW COLUMNS FROM ' . $tb);
			$cols = $db->loadObjectList('Field');

			if (!array_key_exists($this->fieldcode, $cols))
			{
				if ($this->type == 'date')
				{
					$q = 'ALTER TABLE ' . $tb . ' ADD ' . $db->quoteName($this->fieldcode) . ' DATETIME NULL';
				}
				else
				{
					$q = 'ALTER TABLE ' . $tb . ' ADD ' . $db->quoteName($this->fieldcode) . ' mediumtext NULL';
				}

				$db->setQuery($q);

				if ($db->query() == 1)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Deletes this row in database (or if provided, the row of key $pk)
	 *
	 * @param   mixed  $pk  An optional primary key value to delete.  If not set the instance property value is used.
	 *
	 * @return  boolean  True on success.
	 */
	public function delete($pk = null)
	{
		$db = JFactory::getDBO();

		// Get fieldcode of field
		$q = $db->getQuery(true);

		$q->select($db->quoteName('fieldcode') . ', ' . $db->quoteName('type_id'));
		$q->from($db->quoteName('#__reditem_fields'));
		$q->where($db->quoteName('id') . ' = ' . $db->quote($this->id));
		$db->setQuery($q);

		$rs = $db->loadObject();

		$fieldcode = $rs->fieldcode;
		$type_id = $rs->type_id;

		// If "type" table exists
		if ($type_id > 0)
		{
			// Get "type" table columns
			$query = $db->getQuery(true);
			$query->select($db->quoteName('table_name'))
			->from('#__reditem_types')
			->where($db->quoteName('id') . ' = ' . $db->quote($type_id));
			$db->setQuery($query);

			$rs = $db->loadObject();

			// Check if columns exists
			$tb = '#__reditem_types_' . $rs->table_name;
			$db->setQuery('SHOW COLUMNS FROM ' . $tb);
			$cols = $db->loadObjectList('Field');

			if (array_key_exists($fieldcode, $cols))
			{
				$q = "ALTER TABLE " . $tb . " DROP " . $db->quoteName($fieldcode);
				$db->setQuery($q);

				if (!($db->query() == 1))
				{
					return false;
				}
			}
		}

		return parent::delete($pk);
	}
}
