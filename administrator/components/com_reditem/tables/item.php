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
JLoader::import('fileupload', JPATH_COMPONENT . '/helpers');
jimport('joomla.filesystem.folder');

/**
 * Category table
 *
 * @package     RedITEM.Backend
 * @subpackage  Table
 * @since       0.9.1
 */
class ReditemTableItem extends RTable
{
	/**
	 * The name of the table with category
	 *
	 * @var string
	 * @since 0.9.1
	 */
	protected $_tableName = 'reditem_items';

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
		$date = JFactory::getDate();
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		$input = JFactory::getApplication()->input;
		$cform = $input->get('cform', null, 'array');
		$jform = $input->get('jform', null, 'array');

		$imageFolder = '';
		$oldItemImage = '';
		$imageFiles = $input->files->get('jform');

		$customFieldUploadFiles = $input->files->get('cform');

		$itemImageUpload = false;
		$itemImageMedia = false;
		$result = true;

		$this->alias = JFilterOutput::stringURLSafe($this->title);

		if (!$this->id)
		{
			$isNew = true;

			// New item
			$this->created_time = $date->toSql();
			$this->created_user_id = $user->get('id');
		}
		else
		{
			$isNew = false;

			$imageFolder = JPATH_ROOT . '/components/com_reditem/assets/images/item/' . $this->id . '/';
			$oldItemImage = $this->item_image;

			// Existing item
			$this->modified_time = $date->toSql();
			$this->modified_user_id = $user->get('id');
		}

		// Make item image file name
		if ($imageFiles['item_image_file']['name'] != '')
		{
			$imageFiles['item_image_file']['name'] = time() . '_' . ReditemHelperHelper::replaceSpecial($imageFiles['item_image_file']['name']);
			$this->item_image = $imageFiles['item_image_file']['name'];
			$itemImageUpload = true;
		}

		// Choose image from media
		if ($jform['item_image_media'])
		{
			$tmpStrs = explode('/', $jform['item_image_media']);
			$tmpFileName = $tmpStrs[count($tmpStrs) - 1];
			$this->item_image = time() . '_' . ReditemHelperHelper::replaceSpecial($tmpFileName);
			$itemImageMedia = true;
		}

		/*
		 * Remove [image] custom field - Checked
		 */
		if (isset($jform['customfield_image_rm']))
		{
			foreach ($jform['customfield_image_rm'] as $customFieldImageRemove)
			{
				$tmpImageName = json_decode($cform['image'][$customFieldImageRemove], true);
				$tmpImageName = $tmpImageName[0];
				$tmpImagePath = JPATH_ROOT . '/components/com_reditem/assets/images/customfield/' . $tmpImageName;

				if (JFile::exists($tmpImagePath))
				{
					JFile::delete($tmpImagePath);

					// Remove this image from values array
					$cform['image'][$customFieldImageRemove] = '';
				}
			}
		}

		/*
		 * Remove [gallery] custom field - Checked
		 */
		if (isset($jform['customfield_gallery_rm']))
		{
			foreach ($jform['customfield_gallery_rm'] as $cfGallery => $cfImagesRemove)
			{
				$tmpImages = $cform['gallery'][$cfGallery];

				if ($cfImagesRemove)
				{
					foreach ($cfImagesRemove as $cfImage)
					{
						if ($cfImage)
						{
							$tmpImagePath = JPATH_ROOT . '/components/com_reditem/assets/images/customfield/' . $cfImage;

							// Remove this image from values array
							$key = array_search($cfImage, $cform['gallery'][$cfGallery]);
							unset($cform['gallery'][$cfGallery][$key]);

							if (JFile::exists($tmpImagePath))
							{
								JFile::delete($tmpImagePath);
							}
						}
					}
				}
			}
		}

		/*
		 * Remove item_image - Checked
		 */
		if (isset($jform['item_image_remove']))
		{
			$imagePath = $imageFolder . $oldItemImage;

			if (JFile::exists($imagePath))
			{
				JFile::delete($imagePath);
			}

			$this->item_image = '';
		}

		// Store data into item table
		if (!parent::store($updateNulls))
		{
			return false;
		}

		// If new item, create folder item images for this item
		if ($isNew)
		{
			$imageFolder = JPATH_ROOT . '/components/com_reditem/assets/images/item/' . $this->id . '/';

			if (!JFolder::exists($imageFolder))
			{
				JFolder::create($imageFolder);
			}
		}

		// Item Image upload process, Only upload image when user upload a file and don't check on remove checkbox
		if (($itemImageUpload) && (!isset($jform['item_image_remove'])))
		{
			JFile::upload($imageFiles['item_image_file']['tmp_name'], $imageFolder . $imageFiles['item_image_file']['name']);

			// Remove old item image
			if ($oldItemImage)
			{
				if (JFile::exists($imageFolder . $oldItemImage))
				{
					JFile::delete($imageFolder . $oldItemImage);
				}
			}
		}
		elseif (($itemImageMedia) && (!isset($jform['item_image_remove'])))
		{
			// Choose from media manager
			$imageSrc = JPATH_SITE . '/' . $jform['item_image_media'];
			$imageDest = $imageFolder . $this->item_image;

			// Remove old item image
			if ($oldItemImage)
			{
				if (JFile::exists($imageFolder . $oldItemImage))
				{
					JFile::delete($imageFolder . $oldItemImage);
				}
			}

			JFile::copy($imageSrc, $imageDest);
		}

		// Upload / Save [Image] custom fields
		if (isset($customFieldUploadFiles['image']))
		{
			$imageFilesCustomField = $customFieldUploadFiles['image'];

			$imageFolder = JPATH_ROOT . '/components/com_reditem/assets/images/customfield/' . $this->id . '/';

			if (!JFolder::exists($imageFolder))
			{
				JFolder::create($imageFolder);
			}

			foreach ($imageFilesCustomField AS $imageField => $imageData)
			{
				if (isset($imageData['name']) && $imageData['size'] > 0)
				{
					// Single image upload for "Image" field
					$result = ReditemHelpersFileUpload::uploadFile($imageData, $imageFolder, 2, 'jpg,jpeg,gif,png');

					if ($result)
					{
						$imageFieldName = substr($imageField, 0, -5);

						if (!empty($cform['image'][$imageFieldName]))
						{
							$tmpOldImageFileName = json_decode($cform['image'][$imageFieldName]);

							$tmpOldImageFile = JPATH_ROOT . '/components/com_reditem/assets/images/customfield/' . $tmpOldImageFileName[0];

							if (JFile::exists($tmpOldImageFile))
							{
								JFile::delete($tmpOldImageFile);
							}
						}

						$cform['image'][$imageFieldName] = json_encode(array($this->id . '/' . $result['mangled_filename']));
					}

					unset($cform['image'][$imageField]);
				}
				else
				{
					unset($cform['image'][$imageField]);
				}
			}
		}

		// Upload / Save [File] custom fields
		if (isset($customFieldUploadFiles['file']))
		{
			$fileFolder = JPATH_ROOT . '/media/com_reditem/customfield/files/' . $this->id . '/';
			$maxFileSize = 5;
			$allowedExtension = null;
			$allowedMIME = null;
			$useCustomFileName = true;

			if (!JFolder::exists($imageFolder))
			{
				JFolder::create($imageFolder);
			}

			foreach ($customFieldUploadFiles['file'] AS $fileField => $fileData)
			{
				if (isset($fileData['name']) && $fileData['size'] > 0)
				{
					// Single image upload for "Image" field
					$result = ReditemHelpersFileUpload::uploadFile($fileData, $fileFolder, $maxFileSize, $allowedExtension, allowedMIME, $useCustomFileName);

					if ($result)
					{
						$fileFieldName = substr($fileField, 0, -5);

						if (!empty($cform['file'][$fileFieldName]))
						{
							$oldFileName = json_decode($cform['file'][$fileFieldName]);

							$oldFilePath = JPATH_ROOT . '/media/com_reditem/customfield/files/' . $oldFileName[0];

							if (JFile::exists($oldFilePath))
							{
								JFile::delete($oldFilePath);
							}
						}

						$cform['file'][$fileFieldName] = json_encode(array($this->id . '/' . $result['mangled_filename']));
					}

					unset($cform['file'][$fileField]);
				}
				else
				{
					unset($cform['file'][$fileField]);
				}
			}
		}

		// Upload / Save [Gallery] custom fields
		if (isset($customFieldUploadFiles['gallery']))
		{
			$imageFolder = JPATH_ROOT . '/components/com_reditem/assets/images/customfield/' . $this->id . '/';

			if (!JFolder::exists($imageFolder))
			{
				JFolder::create($imageFolder);
			}

			foreach ($customFieldUploadFiles['gallery'] AS $galleryField => $galleryData)
			{
				$galleryFieldName = substr($galleryField, 0, -5);
				$tmpGalleryData = array();

				foreach ($galleryData AS $imageData)
				{
					if (isset($imageData['name']) && $imageData['size'] > 0)
					{
						$result = ReditemHelpersFileUpload::uploadFile($imageData, $imageFolder, 2, 'jpg,jpeg,gif,png');

						if ($result)
						{
							$tmpGalleryData[] = $this->id . '/' . $result['mangled_filename'];
						}
					}
				}

				$cform['gallery'][$galleryFieldName] = json_encode($tmpGalleryData);

				unset($cform['gallery'][$galleryField]);
			}
		}

		/**
		 * Add categories xref
		 */
		if (count($jform['categories']) > 0)
		{
			$q = $db->getQuery(true);
			$q->delete($db->quoteName('#__reditem_item_category_xref'));
			$q->where($db->quoteName('item_id') . ' = ' . $db->quote($this->id));
			$db->setQuery($q);
			$db->query();

			foreach ($jform['categories'] as $cid)
			{
				$q = $db->getQuery(true);
				$q->insert($db->quoteName('#__reditem_item_category_xref'));
				$q->columns($db->quoteName('item_id') . ',' . $db->quoteName('category_id'));
				$q->values($db->quote($this->id) . ',' . $db->quote($cid));
				$db->setQuery($q);
				$db->query();
			}
		}

		/**
		 * Add custom fields data
		 */
		$query = $db->getQuery(true);
		$query->select($db->quoteName('table_name'))
			->from('#__reditem_types')
			->where($db->quoteName('id') . ' = ' . $db->quote($this->type_id));
		$db->setQuery($query);

		$rs = $db->loadObject();

		$tb = '#__reditem_types_' . $rs->table_name;

		if ($isNew)
		{
			$q = $db->getQuery(true);
			$q->insert($db->quoteName($tb));
			$q->columns($db->quoteName('id'));
			$q->values($db->quote($this->id));
			$db->setQuery($q);
			$db->query();
		}

		if (count($cform))
		{
			$query = $db->getQuery(true);

			$query->update($db->quoteName($tb));

			foreach ($cform AS $group)
			{
				foreach ($group AS $col => $val)
				{
					$val = (is_array($val)) ? json_encode($val) : $val;
					$query->set($db->quoteName($col) . ' = ' . $db->quote($val));
				}
			}

			$query->where($db->quoteName('id') . ' = ' . $db->quote($this->id));

			$db->setQuery($query);

			if ($db->query() == 1)
			{
				$result = true;
			}
			else
			{
				$result = false;
			}
		}

		return $result;
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

		// Remove reference with categories
		$q = $db->getQuery(true);

		$q->delete($db->quoteName('#__reditem_item_category_xref'));
		$q->where($db->quoteName('item_id') . ' = ' . $db->quote($this->id));

		$db->setQuery($q);
		$db->query();

		// Remove customfield data on custom table
		$q = $db->getQuery(true);

		$q->select($db->quoteName('table_name'));
		$q->from('#__reditem_types');
		$q->where($db->quoteName('id') . ' = ' . $db->quote($this->type_id));

		$db->setQuery($q);
		$result = $db->loadObject();

		$q = $db->getQuery(true);

		$q->delete($db->quoteName('#__reditem_types_' . $result->table_name));
		$q->where($db->quoteName('id') . ' = ' . $db->quote($this->id));

		$db->setQuery($q);
		$db->query();

		return parent::delete($pk);
	}
}
