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
 * Renders a Number Custom field
 *
 * @package     RedITEM.Component
 * @subpackage  CustomField.Number
 * @since       2.0.2
 *
 */
class TCustomfieldNumber extends TCustomfield
{
	/**
	 * Returns the html code for the form element
	 *
	 * @param   string  $attributes  Attributes of element
	 *
	 * @return string
	 */
	public function render($attributes = '')
	{
		$document = JFactory::getDocument();

		$class = "";

		if ($this->required)
		{
			$class = ' required';
		}

		$value = htmlspecialchars(html_entity_decode($this->value, ENT_QUOTES), ENT_QUOTES);

		$attributes .= ' placeholder="' . $this->name . '"';

		$str = '<input type="text" name="cform[number][' . $this->fieldcode . ']" id="cform_' . $this->fieldcode . '" value="' . $value . '"';
		$str .= ' class="' . $class . ' validate-numeric" ' . $attributes . '/>';

		return $str;
	}
}
