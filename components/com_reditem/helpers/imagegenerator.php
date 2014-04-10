<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Custom field tags
 *
 * @package     RedITEM.Frontend
 * @subpackage  Helper.Helper
 * @since       2.0
 *
 */
class ImageGenerator
{
	/**
	 * Constructor
	 */
	/*public function __construct()
	{
		$doc = JFactory::getDocument();

		$js .= 'jQuery(document).ready(function(){';
		$js .= 'jQuery(".colorbox_group_' . $data->id . '_' . $i . '").colorbox({rel:"colorbox_group_' . $data->id . '_' . $i . '"});';
		$js .= '});';

		$doc->addScriptDeclaration($js);
		parent::__construct();
	}*/

	/**
	 * Get Image thumbnail link of item
	 *
	 * @param   int      $itemId              item ID
	 * @param   string   $prefix              Prefix ('image', 'category')
	 * @param   int      $typeId              Type ID
	 * @param   string   $imageFile           Image file path
	 * @param   string   $imageType           Type of image ('' => original image, 'small', 'medium', 'large')
	 * @param   int      $defaultImageWidth   Default width
	 * @param   int      $defaultImageHeight  Default height
	 * @param   boolean  $linkOnly            Return link only or full image tag
	 * @param   string   $attrs               Attributes of img tags
	 *
	 * @return  string  Image link
	 */
	public function getImageLink(
		$itemId,
		$prefix = 'item',
		$typeId = 0,
		$imageFile = '',
		$imageType = '',
		$defaultImageWidth = null,
		$defaultImageHeight = null,
		$linkOnly = false,
		$attrs = '')
	{
		$url = JURI::base();
		$typeModel = RModel::getAdminInstance('Type', array('ignore_request' => true), 'com_reditem');
		$type = $typeModel->getItem($typeId);
		$typeParams = new JRegistry($type->params);

		// Attributes of image
		if ($attrs == '')
		{
			if ($prefix == 'item')
			{
				// Item image
				$itemModel = RModel::getAdminInstance('Item', array('ignore_request' => true), 'com_reditem');
				$item = $itemModel->getItem($itemId);

				if (isset($item->params))
				{
					$itemParams = new JRegistry($item->params);
					$attrs = ' title="' . $itemParams->get('item_image_title') . '"';
					$attrs .= ' alt="' . $itemParams->get('item_image_alt') . '"';
				}
			}
			elseif ($prefix == 'category')
			{
				// Category image
				$categoryModel = RModel::getAdminInstance('Category', array('ignore_request' => true), 'com_reditem');
				$category = $categoryModel->getItem($itemId);

				if (isset($category->params))
				{
					$itemParams = new JRegistry($category->params);
					$attrs = ' title="' . $itemParams->get('category_image_title') . '"';
					$attrs .= ' alt="' . $itemParams->get('category_image_alt') . '"';
				}
			}
		}

		// Get width & height of type (small, medium, large)
		if (!$defaultImageWidth)
		{
			$defaultImageWidth = (int) $typeParams->get('default_' . $prefix . 'image_' . $imageType . '_width', 300);
		}

		if (!$defaultImageHeight)
		{
			$defaultImageHeight = (int) $typeParams->get('default_' . $prefix . 'image_' . $imageType . '_height', 300);
		}

		$originalImagePath = $url . 'components/com_reditem/assets/images/' . $prefix . '/' . $itemId . '/' . $imageFile;
		$realImagePath = JPATH_SITE . '/components/com_reditem/assets/images/' . $prefix . '/' . $itemId . '/' . $imageFile;

		if ((empty($imageFile)) || (!JFile::exists($realImagePath)))
		{
			// No image value or original image doesn't exists. Return generated image

			$imagePath = 'holder.js';

			$imagePath .= '/' . $defaultImageWidth . 'x' . $defaultImageHeight . '/text:' . JFactory::getConfig()->get('config.sitename') . '/gray';

			if ($linkOnly)
			{
				return $imagePath;
			}
			else
			{
				return '<img data-src="' . $imagePath . '" ' . $attrs . ' />';
			}
		}
		else
		{
			if (empty($imageType))
			{
				$returnThumbImagePath = $originalImagePath;
			}
			else
			{
				$thumbnailImagePath = 'components/com_reditem/assets/images/' . $prefix . '/' . $itemId;
				$thumbnailImagePath .= '/' . $imageType . '_' . $defaultImageWidth . 'x' . $defaultImageHeight . '_' . $imageFile;

				$realThumbImagePath = JPATH_SITE . '/' . $thumbnailImagePath;
				$returnThumbImagePath = $url . $thumbnailImagePath;

				if (!JFile::exists($realThumbImagePath))
				{
					$this->makeImage($realImagePath, $realThumbImagePath, $defaultImageWidth, $defaultImageHeight);
				}
			}

			if ($linkOnly)
			{
				return $returnThumbImagePath;
			}
			else
			{
				return '<img src="' . $returnThumbImagePath . '" ' . $attrs . ' />';
			}
		}
	}

	/**
	 * Replace special characters
	 *
	 * @param   string  $name  String
	 *
	 * @return  string
	 */
	public function replaceSpecial($name)
	{
		$filetype = JFile::getExt($name);
		$filename = JFile::stripExt($name);
		$value = preg_replace("/[&'#]/", "", $filename);
		$value = JFilterOutput::stringURLSafe($value) . '.' . $filetype;

		return $value;
	}

	/**
	 * Make thumbnail file
	 *
	 * @param   string  $sourceFile  Source file path
	 * @param   string  $destFile    Destination file path
	 * @param   string  $width       Width of image
	 * @param   string  $height      Height of image
	 * @param   int     $quality     Quality of image
	 * @param   int     $crop        Crop
	 *
	 * @return  boolean
	 */
	public function makeImage($sourceFile, $destFile, $width, $height, $quality = 100, $crop = 2)
	{
		$imageinfo = getimagesize($sourceFile);

		if ($width <= 0 && $height <= 0)
		{
			$width = $imageinfo[0];
			$height = $imageinfo[1];
		}
		elseif ($width <= 0 && $height > 0)
		{
			$width = ($height / $imageinfo[1]) * $imageinfo[0];
		}
		elseif ($width > 0 && $height <= 0)
		{
			$height = ($width / $imageinfo[0]) * $imageinfo[1];
		}

		if (!extension_loaded('gd') && !function_exists('gd_info'))
		{
			JError::raiseError(JText::_('COM_REDITEM_CHECK_GD_LIBRARY'));

			return false;
		}

		if ($width == 0 || $height == 0)
		{
			JError::raiseError(JText::_('COM_REDITEM_IMAGE_NOT_ZERO'));

			return false;
		}

		jimport('joomla.filesystem.file');

		if (!JFile::exists($destFile))
		{
			$ext = JFile::getExt($sourceFile);

			if ($crop == 1)
			{
				if ($imageinfo[0] <= $width && $imageinfo[1] <= $height)
				{
					if (!JFile::copy($sourceFile, $destFile))
					{
						JError::raiseError($sourceFile . ' -> ' . $destFile . ' ' . JText::_('COM_REDITEM_ERROR_MOVING_FILE'));

						return false;
					}

					return true;
				}
			}

			JLoader::import('WideImage', JPATH_SITE . '/components/com_reditem/helpers/wideimage');

			$image = WideImage::load($sourceFile);

			if ($crop == 0)
			{
				$image = $image->resize((int) $width, (int) $height, 'fill', 'any');
			}
			elseif ($crop == 2 && $imageinfo[0] > $width && $imageinfo[1] > $height)
			{
				$image = $image->resize((int) $width, (int) $height, 'outside')->crop('center', 'middle', (int) $width, (int) $height);
			}
			else
			{
				$image = $image->resize((int) $width, (int) $height, 'inside', 'down');
			}

			if (preg_match("/jp/i", $ext))
			{
				$image->saveToFile($destFile, $quality);
			}
			else
			{
				$image->saveToFile($destFile);
			}

			return true;
		}

		return true;
	}
}
