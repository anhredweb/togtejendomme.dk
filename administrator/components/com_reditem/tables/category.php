<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('helper', JPATH_COMPONENT . '/helpers');
jimport('joomla.filesystem.folder');

/**
 * Category table
 *
 * @package     RedITEM.Backend
 * @subpackage  Table
 * @since       0.9.1
 */
class ReditemTableCategory extends RTableNested
{
	/**
	 * The name of the table with category
	 *
	 * @var string
	 * @since 0.9.1
	 */
	protected $_tableName = 'reditem_categories';

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
		$db = JFactory::getDbo();
		$dispatcher = JDispatcher::getInstance();
		$date = JFactory::getDate();
		$user = JFactory::getUser();
		JPluginHelper::importPlugin('reditem_categories');

		$query = $db->getQuery(true);
		$input = JFactory::getApplication()->input;
		$jform = $input->get('jform', null, 'array');

		$itemImageUpload = false;
		$itemImageMedia = false;
		$oldCategoryImage = '';
		$imageFolder = '';

		$this->alias = JFilterOutput::stringURLSafe($this->title);

		if (!$this->id)
		{
			$isNew = true;

			// New category
			$this->created_time = $date->toSql();
			$this->created_user_id = $user->get('id');
		}
		else
		{
			$isNew = false;

			$imageFolder = JPATH_ROOT . '/components/com_reditem/assets/images/category/' . $this->id . '/';
			$oldCategoryImage = $this->category_image;

			// Existing category
			$this->modified_time = $date->toSql();
			$this->modified_user_id = $user->get('id');
		}

		// Uploading/save images
		$categoryfiles = $input->files->get('jform');

		if ($categoryfiles['category_image_file']['name'] != '')
		{
			$categoryfiles['category_image_file']['name'] = time() . '_' . ReditemHelperHelper::replaceSpecial($categoryfiles['category_image_file']['name']);
			$this->category_image = $categoryfiles['category_image_file']['name'];
			$itemImageUpload = true;
		}

		// Choose image from media
		if ($jform['category_image_media'])
		{
			$tmpStrs = explode('/', $jform['category_image_media']);
			$tmpFileName = $tmpStrs[count($tmpStrs) - 1];
			$this->category_image = time() . '_' . ReditemHelperHelper::replaceSpecial($tmpFileName);
			$itemImageMedia = true;
		}

		/*
		 * Remove category_image - Checked
		 */
		if (isset($jform['category_image_remove']))
		{
			$imagePath = $imageFolder . $oldItemImage;

			if (JFile::exists($imagePath))
			{
				JFile::delete($imagePath);
			}

			$this->category_image = '';
		}

		if (!parent::store($updateNulls))
		{
			return false;
		}

		/**
		 * Related categories process
		 */

		// Remove all related categories of this categoy
		$q = $db->getQuery(true);
		$q->delete($db->quoteName('#__reditem_category_related'));
		$q->where($db->quoteName('related_id') . ' = ' . $db->quote($this->id));
		$db->setQuery($q);
		$db->query();

		// Add related categories
		if ((isset($jform['related_categories'])) && (count($jform['related_categories']) > 0))
		{
			foreach ($jform['related_categories'] as $cid)
			{
				$q = $db->getQuery(true);
				$q->insert($db->quoteName('#__reditem_category_related'));
				$q->columns($db->quoteName('related_id') . ',' . $db->quoteName('parent_id'));
				$q->values($db->quote($this->id) . ',' . $db->quote($cid));
				$db->setQuery($q);
				$db->query();
			}
		}

		if ($isNew)
		{
			$imageFolder = JPATH_ROOT . '/components/com_reditem/assets/images/category/' . $this->id . '/';

			if (!JFolder::exists($imageFolder))
			{
				JFolder::create($imageFolder);
			}
		}

		if ($itemImageUpload)
		{
			JFile::upload($categoryfiles['category_image_file']['tmp_name'], $imageFolder . $categoryfiles['category_image_file']['name']);

			// Remove old item image
			if ($oldCategoryImage)
			{
				if (JFile::exists($imageFolder . $oldCategoryImage))
				{
					JFile::delete($imageFolder . $oldCategoryImage);
				}
			}
		}
		elseif ($itemImageMedia)
		{
			// Choose from media manager
			$imageSrc = JPATH_SITE . '/' . $jform['category_image_media'];
			$imageDest = $imageFolder . $this->category_image;

			// Remove old item image
			if ($oldCategoryImage)
			{
				if (JFile::exists($imageFolder . $oldCategoryImage))
				{
					JFile::delete($imageFolder . $oldCategoryImage);
				}
			}

			JFile::copy($imageSrc, $imageDest);
		}

		// Run event 'onAfterCategorySave'
		$dispatcher->trigger('onAfterCategorySave', array($this, $input));

		return true;
	}

	/**
	 * Deletes this row in database (or if provided, the row of key $pk)
	 *
	 * @param   mixed    $pk        An optional primary key value to delete.  If not set the instance property value is used.
	 * @param   boolean  $children  An optional boolean variable for delete it's children category or not
	 *
	 * @return  boolean  True on success.
	 */
	public function delete($pk = null, $children = true)
	{
		$db = JFactory::getDBO();

		// Remove related categories
		$q = $db->getQuery(true);

		$q->delete($db->quoteName('#__reditem_category_related'));
		$q->where($db->quoteName('related_id') . ' = ' . $db->quote($this->id));

		$db->setQuery($q);
		$db->query();

		return parent::delete($pk, $children);
	}
}
