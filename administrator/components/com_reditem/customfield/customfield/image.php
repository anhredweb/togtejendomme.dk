<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  CustomField
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die;

/**
 * Renders a Textbox Custom field
 *
 * @package     RedITEM.Component
 * @subpackage  CustomField.Image
 * @since       2.0
 *
 */
class TCustomfieldImage extends TCustomfield
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
		$div_id = 'imgfield_' . $this->fieldcode . '_' . $this->id;
		$field_name = 'cform[image][' . $this->fieldcode . '_file]';
		$value = $this->value;
		$data = '';

		if ($this->required)
		{
			$class = ' class="required"';
		}

		$id = 'cform_' . $this->fieldcode;
		$data .= '<div class="media" id="div_' . $id . '">';

		if ($value)
		{
			$imgJSON = json_decode($value, true);

			if ($imgJSON)
			{
				$imgValue = $imgJSON[0];
				$_imgsrc = JURI::root() . 'components/com_reditem/assets/images/' . $basePath . '/' . $imgValue;
				$data .= '<img src="' . $_imgsrc . '" class="img-polaroid pull-left" style="max-width: 300px; max-height: 300px; margin-right: 20px;" />';
				$data .= '<div class="media-body">';
				$data .= '<label class="checkbox">';
				$data .= '<input type="checkbox" name="jform[customfield_image_rm][]" value="' . $this->fieldcode . '" /> ';
				$data .= JText::_('COM_REDITEM_CUSTOMFIELD_IMAGE_REMOVE');
				$data .= '</label>';
			}
		}
		else
		{
			$data .= '<div>';
		}

		$data .= '<div><input type="file" name="' . $field_name . '" id="' . $id . '" ' . $attributes . ' /></div>';
		$data .= '<input type="hidden" name="cform[image][' . $this->fieldcode . ']" id="' . $id . '_value" value="' . htmlentities($value) . '" />';
		$data .= '</div>';
		$data .= '</div>';

		$return_data .= '<div id="' . $div_id . '">';
		$return_data .= $data;
		$return_data .= '</div>';

		return $return_data;
	}
}
