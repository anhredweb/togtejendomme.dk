<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  CustomField
 *
 * @copyright   Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die;

/**
 * Renders a Upload file Custom field
 *
 * @package     RedITEM.Component
 * @subpackage  CustomField.File
 * @since       2.0
 *
 */
class TCustomfieldFile extends TCustomfield
{
	/**
	 * returns the html code for the form element
	 *
	 * @param   array   $attributes  Attributes for render this field
	 * @param   string  $basePath    Base path for render image
	 *
	 * @return string
	 */
	public function render($attributes = '', $basePath = 'customfield')
	{
		$doc = JFactory::getDocument();
		$class = '';
		$return_data = '';
		$div_id = 'filefield_' . $this->fieldcode . '_' . $this->id;
		$field_name = 'cform[file][' . $this->fieldcode . '_file]';
		$value = $this->value;
		$data = '';

		if ($this->required)
		{
			$class = ' class="required"';
		}

		$id = 'cform_' . $this->fieldcode;

		if ($value)
		{
			$fileJSON = json_decode($value, true);

			if ($fileJSON)
			{
				$fileValue = $fileJSON[0];
				$filePath = JPATH_ROOT . '/media/com_reditem/customfield/files/' . $fileValue;
				$data .= '<div>';
				$data .= '<h3 class="badge badge-info">' . strtoupper(JFile::getExt($filePath)) . '</h3>  ';
				$data .= '<strong>' . JFile::getName($filePath) . '</strong></div>';
			}
		}

		$data .= '<div><input type="file" name="' . $field_name . '" id="' . $id . '" ' . $attributes . ' /></div>';
		$data .= '<input type="hidden" name="cform[file][' . $this->fieldcode . ']" id="' . $id . '_value" value="' . htmlentities($value) . '" />';

		$return_data .= '<div id="' . $div_id . '">';
		$return_data .= $data;
		$return_data .= '</div>';

		return $return_data;
	}
}
