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
 * @subpackage  CustomField.Select
 * @since       2.0
 *
 */
class TCustomfieldSelect extends TCustomfield
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
		if ($this->required)
		{
			$attribs = array('class' => 'required');
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

		return JHTML::_('select.genericlist', $option_list, 'cform[select][' . $this->fieldcode . ']', $attribs, 'value', 'text', $this->value);
	}
}
