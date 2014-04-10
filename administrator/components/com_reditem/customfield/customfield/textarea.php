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
 * Renders a Textarea Custom field
 *
 * @package     RedITEM.Component
 * @subpackage  CustomField.TextArea
 * @since       0.9.1
 *
 */
class TCustomfieldTextarea extends TCustomfield
{
	/**
	 * returns the html code for the form element
	 *
	 * @param   array  $attributes  [description]
	 *
	 * @return string
	 */
	public function render($attributes = '')
	{
		$class = "";

		if ($this->required)
		{
			$class = ' class="required"';
		}
		/*
		 * Required to avoid a cycle of encoding &
		 * html_entity_decode was used in place of htmlspecialchars_decode because
		 * htmlspecialchars_decode is not compatible with PHP 4
		 */
		$value = htmlspecialchars(html_entity_decode($this->value, ENT_QUOTES), ENT_QUOTES);

		$str = '<textarea name="cform[textarea][' . $this->fieldcode . ']" id="' . $this->fieldcode . '" ' . $class . $attributes . ' cols="80" rows="10">';
		$str .= $value;
		$str .= '</textarea>';

		return $str;
	}
}
