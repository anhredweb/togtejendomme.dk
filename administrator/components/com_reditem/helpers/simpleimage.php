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
 * RedITEM Simple Image Helper
 *
 * @package     RedITEM.Component
 * @subpackage  Helpers.SimpleImage
 * @since       2.0
 *
 */
class SimpleImage
{
	public $image;

	public $image_type;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Define some constant
		if (!defined('COM_REDITEM_IMAGES_ABSPATH'))
		{
			define('COM_REDITEM_IMAGES_ABSPATH', JURI::root() . 'components/com_reditem/assets/images/');
		}

		if (!defined('COM_REDITEM_IMAGES_RELPATH'))
		{
			define('COM_REDITEM_IMAGES_RELPATH', JPATH_ROOT . '/components/com_reditem/assets/images/');
		}
	}

	/**
	 * Replace special character in filename.
	 *
	 * @param   string  $name  Name of file
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
	 * Load file.
	 *
	 * @param   string  $filename  Name of file
	 *
	 * @return void
	 */
	public function load($filename)
	{
		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];

		if ($this->image_type == IMAGETYPE_JPEG)
		{
			$this->image = imagecreatefromjpeg($filename);
		}
		elseif ($this->image_type == IMAGETYPE_GIF )
		{
			$this->image = imagecreatefromgif($filename);
		}
		elseif ($this->image_type == IMAGETYPE_PNG )
		{
			$this->image = imagecreatefrompng($filename);
		}
	}

	/**
	 * Save file.
	 *
	 * @param   string  $filename     Name of file
	 * @param   string  $image_type   Image type
	 * @param   string  $compression  Compression
	 * @param   string  $permissions  Permission
	 *
	 * @return  string  Status of save process
	 */
	public function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null)
	{
		if ($image_type == IMAGETYPE_JPEG)
		{
			$status = imagejpeg($this->image, $filename, $compression);
		}
		elseif ($image_type == IMAGETYPE_GIF)
		{
			$status = imagegif($this->image, $filename);
		}
		elseif ($image_type == IMAGETYPE_PNG)
		{
			$status = imagepng($this->image, $filename);
		}

		if ($permissions != null)
		{
			chmod($filename, $permissions);
		}

		return $status;
	}

	/**
	 * Output file.
	 *
	 * @param   string  $image_type  Image type
	 *
	 * @return  void
	 */
	public function output($image_type = IMAGETYPE_JPEG)
	{
		if ($image_type == IMAGETYPE_JPEG)
		{
			imagejpeg($this->image);
		}
		elseif ($image_type == IMAGETYPE_GIF)
		{
			imagegif($this->image);
		}
		elseif ($image_type == IMAGETYPE_PNG)
		{
			imagepng($this->image);
		}
	}

	/**
	 * Get width of image.
	 *
	 * @return  int
	 */
	public function getWidth()
	{
		return imagesx($this->image);
	}

	/**
	 * Get height of image.
	 *
	 * @return  int
	 */
	public function getHeight()
	{
		return imagesy($this->image);
	}

	/**
	 * Resize image to fit height.
	 *
	 * @param   int  $height  Image height
	 *
	 * @return  void
	 */
	public function resizeToHeight($height)
	{
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width, $height);
	}

	/**
	 * Resize image to fit width.
	 *
	 * @param   int  $width  Image width
	 *
	 * @return  void
	 */
	public function resizeToWidth($width)
	{
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		$this->resize($width, $height);
	}

	/**
	 * Scale image.
	 *
	 * @param   int  $scale  Image width
	 *
	 * @return  void
	 */
	public function scale($scale)
	{
		$width = $this->getWidth() * $scale / 100;
		$height = $this->getheight() * $scale / 100;
		$this->resize($width, $height);
	}

	/**
	 * Resize image.
	 *
	 * @param   int  $width   Image width
	 * @param   int  $height  Image height
	 *
	 * @return  void
	 */
	public function resize($width, $height)
	{
		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;
	}
}
