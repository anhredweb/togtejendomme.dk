<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  CustomField.Checkbox
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
 * @subpackage  CustomField.Checkbox
 * @since       2.0
 *
 */
class TCustomfieldCheckbox extends TCustomfield
{
	/**
	 * returns the html code for the form element
	 *
	 * @param   array  $attributes  [description]
	 *
	 * @return  string
	 */
	public function render($attributes = '')
	{
		$html = '';
		$option_list = array();
		$options = explode("\n", $this->options);
		$values = json_decode($this->value, true);

		if ($options)
		{
			sort($options);

			foreach ($options as $opt)
			{
				$opt = explode('|', trim($opt));
				$_value = $opt[0];
				$_text = (isset($opt[1])) ? $opt[1] : $opt[0];
				$checked = (in_array($_value, $values)) ? ' checked="checked"':'';
				$html .= '<label class="checkbox">';
				$html .= '<input type="checkbox" name="cform[checkbox][' . $this->fieldcode . '][]" value="' . $_value . '"' . $checked . $attributes . '/>';
				$html .= $_text;
				$html .= '</label>';
			}
		}

		return $html;
	}
}
