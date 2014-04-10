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
 * Type table
 *
 * @package     RedITEM.Backend
 * @subpackage  Table
 * @since       0.9.1
 */
class ReditemTableType extends RTable
{
	/**
	 * The name of the table with category
	 *
	 * @var string
	 * @since 0.9.1
	 */
	protected $_tableName = 'reditem_types';

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
	/*protected $_tableFieldState = 'published';*/

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
			$this->table_name = str_replace('-', '_', JFilterOutput::stringURLSafe($this->title));

			$q = 'CREATE TABLE IF NOT EXISTS ' . $db->quoteName('#__reditem_types_' . $this->table_name) . ' ( ';
			$q .= $db->quoteName('id') . ' int(11) NOT NULL DEFAULT ' . $db->quote(0) . ') ';
			$q .= 'ENGINE=InnoDB DEFAULT CHARSET=utf8;';

			$db->setQuery($q);

			if (!($db->query() == 1))
			{
				return false;
			}
		}

		if (!parent::store($updateNulls))
		{
			return false;
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

		// Delete table of this type

		$q = 'DROP TABLE ' . $db->quoteName('#__reditem_types_' . $this->table_name) . ';';
		$db->setQuery($q);

		if (!($db->query() == 1))
		{
			/*JError::raiseWarning(500, 'Cannot delete table: ' . $db->quoteName('#__reditem_types_' . $this->table_name));*/
			$this->setError('Cannot delete table: ' . $db->quoteName('#__reditem_types_' . $this->table_name));

			return false;
		}

		// Delete all custom fields belong to this type

		$q = $db->getQuery(true);
		$q->delete($db->quoteName('#__reditem_fields'));
		$q->where($db->quoteName('type_id') . ' = ' . $db->quote($this->id));
		$db->setQuery($q);

		if (!($db->query() == 1))
		{
			/*JError::raiseWarning(500, 'Cannot delete table: ' . $db->quoteName('#__reditem_types_' . $this->table_name));*/
			$this->setError(JText::_('COM_REDITEM_TYPE_ERROR_DELETE_TYPES_CUSTOMFIELDS'));

			return false;
		}

		// TODO: Delete all items belong to this type

		$q = $db->getQuery(true);
		$q->delete($db->quoteName('#__reditem_items'));
		$q->where($db->quoteName('type_id') . ' = ' . $db->quote($this->id));
		$db->setQuery($q);

		if (!($db->query() == 1))
		{
			/*JError::raiseWarning(500, 'Cannot delete table: ' . $db->quoteName('#__reditem_types_' . $this->table_name));*/
			$this->setError(JText::_('COM_REDITEM_TYPE_ERROR_DELETE_TYPES_ITEMS'));

			return false;
		}

		// Delete all categories belong to this type

		$q = $db->getQuery(true);
		$q->delete($db->quoteName('#__reditem_categories'));
		$q->where($db->quoteName('type_id') . ' = ' . $db->quote($this->id));
		$db->setQuery($q);

		if (!($db->query() == 1))
		{
			/*JError::raiseWarning(500, 'Cannot delete table: ' . $db->quoteName('#__reditem_types_' . $this->table_name));*/
			$this->setError(JText::_('COM_REDITEM_TYPE_ERROR_DELETE_TYPES_CATEGORIES'));

			return false;
		}

		// Delete all templates belong to this type

		$q = $db->getQuery(true);
		$q->delete($db->quoteName('#__reditem_templates'));
		$q->where($db->quoteName('type_id') . ' = ' . $db->quote($this->id));
		$db->setQuery($q);

		if (!($db->query() == 1))
		{
			/*JError::raiseWarning(500, 'Cannot delete table: ' . $db->quoteName('#__reditem_types_' . $this->table_name));*/
			$this->setError(JText::_('COM_REDITEM_TYPE_ERROR_DELETE_TYPES_TEMPLATES'));

			return false;
		}

		return parent::delete($pk);
	}
}
