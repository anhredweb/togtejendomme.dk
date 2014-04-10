<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * RedITEM category Model
 *
 * @package     RedITEM.Component
 * @subpackage  Models.Category
 * @since       2.0
 *
 */
class ReditemModelCategory extends RModelAdmin
{
	public $category = null;

	/**
	 * Method to save the form data for TableNested
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	public function save($data)
	{
		// Initialise variables;
		$dispatcher = JDispatcher::getInstance();
		$table = $this->getTable();
		$pk = (!empty($data['id'])) ? $data['id'] : (int) $this->getState($this->getName() . '.id');
		$isNew = true;

		// Include the content plugins for the on save events.
		JPluginHelper::importPlugin('content');

		// Load the row if saving an existing category.
		if ($pk > 0)
		{
			$table->load($pk);
			$isNew = false;
		}

		// Set the new parent id if parent id not matched OR while New/Save as Copy .
		if ($table->parent_id != $data['parent_id'] || $data['id'] == 0)
		{
			$table->setLocation($data['parent_id'], 'last-child');
		}

		// Alter the title for save as copy
		if (JRequest::getVar('task') == 'save2copy')
		{
			list($title, $alias) = $this->generateNewTitle($data['parent_id'], $data['alias'], $data['title']);
			$data['title'] = $title;
			$data['alias'] = $alias;
		}

		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($table->getError());

			return false;
		}

		// Bind the rules.
		if (isset($data['rules']))
		{
			$rules = new JAccessRules($data['rules']);
			$table->setRules($rules);
		}

		// Check the data.
		if (!$table->check())
		{
			$this->setError($table->getError());

			return false;
		}

		// Trigger the onContentBeforeSave event.
		$result = $dispatcher->trigger($this->event_before_save, array($this->option . '.' . $this->name, &$table, $isNew));

		if (in_array(false, $result, true))
		{
			$this->setError($table->getError());

			return false;
		}

		// Store the data.
		if (!$table->store())
		{
			$this->setError($table->getError());

			return false;
		}

		// Trigger the onContentAfterSave event.
		$dispatcher->trigger($this->event_after_save, array($this->option . '.' . $this->name, &$table, $isNew));

		// Rebuild the path for the category:
		if (!$table->rebuildPath($table->id))
		{
			$this->setError($table->getError());

			return false;
		}

		// Rebuild the paths of the category's children:
		if (!$table->rebuild($table->id, $table->lft, $table->level, $table->path))
		{
			$this->setError($table->getError());

			return false;
		}

		$this->setState($this->getName() . '.id', $table->id);

		// Clear the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Method to get the row form.
	 *
	 * @param   int  $pk  Primary key
	 *
	 * @return	array
	 */
	public function getItem($pk = null)
	{
		$app = JFactory::getApplication();
		$category = parent::getItem($pk);

		if ($category)
		{
			$db = JFactory::getDBO();

			// Create _path variables for use on ReditemRouteHelper class
			$path = array();

			$parentId = $category->parent_id;

			$path[] = $parentId;

			while ($parentId)
			{
				$parentId = $this->getParent($parentId);

				$path[] = $parentId;
			}

			$category->recusivePath = $path;

			// Get related categories
			$query = $db->getQuery(true);
			$query->select($db->quoteName('parent_id'));
			$query->from($db->quoteName('#__reditem_category_related'));
			$query->where($db->quoteName('related_id') . ' = ' . $db->quote($category->id));

			$db->setQuery($query);

			$category->related_categories = $db->loadResultArray();
		}

		$this->category = $category;

		return $category;
	}

	/**
	 * Method to get the row form.
	 *
	 * @param   int  $id  Primary key
	 *
	 * @return	mixed	A JForm object on success, false on failure
	 *
	 * @since	1.6
	 */
	protected function getParent($id)
	{
		$id = (int) $id;

		if (!$id)
		{
			return false;
		}

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName('parent_id'))
			->from($db->quoteName('#__reditem_categories'))
			->where($db->quoteName('id') . ' = ' . (int) $id);
		$db->setQuery($query);
		$category = $db->loadObject();

		return $category->parent_id;
	}

	/**
	 * Method to save the reordered nested set tree.
	 * First we save the new order values in the lft values of the changed ids.
	 * Then we invoke the table rebuild to implement the new ordering.
	 *
	 * @param   array  $idArray    id's of rows to be reordered
	 * @param   array  $lft_array  lft values of rows to be reordered
	 *
	 * @return   boolean  false on failuer or error, true otherwise
	 */
	public function saveorder($idArray = null, $lft_array = null)
	{
		// Get an instance of the table object.
		$table = $this->getTable();

		if (!$table->saveorder($idArray, $lft_array))
		{
			$this->setError($table->getError());

			return false;
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Method to set featured of category.
	 *
	 * @param   int  $id     Id of category
	 * @param   int  $state  featured state of category
	 *
	 * @return  boolean
	 */
	public function featured($id = null, $state = 0)
	{
		$db = JFactory::getDbo();

		if ($id)
		{
			$query = $db->getQuery(true);

			$query->update($db->quoteName('#__reditem_categories', 'c'))
				->set($db->quoteName('c.featured') . ' = ' . (int) $state)
				->where($db->quoteName('c.id') . ' = ' . (int) $id);

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
