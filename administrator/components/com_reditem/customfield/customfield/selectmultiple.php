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
 * @subpackage  CustomField.SelectMultiple
 * @since       2.0
 *
 */
class TCustomfieldSelectmultiple extends TCustomfield
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
		$fieldName = 'cform[selectmultiple][' . $this->fieldcode . '][]';

		if ($this->required)
		{
			$attribs = 'class="required"';
		}
		else
		{
			$attribs = null;
		}

		$option_list = array();
		$options = explode("\n", $this->options);

		if ($options)
		{
			foreach ($options as $opt)
			{
				$opt = trim($opt);
				$option_list[] = JHTML::_('select.option', $opt, $opt);
			}
		}

		$str = 'multiple="multiple" size="' . min(10, count($options)) . '" ' . $attribs;

		return JHTML::_('select.genericlist', $option_list, $fieldName, $str, 'value', 'text', explode("\n", $this->value));
	}
}
