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
 * @subpackage  CustomField.Date
 * @since       2.0
 *
 */
class TCustomfieldDate extends TCustomfield
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
		$return = '';

		if ($this->required)
		{
			$attribs = array('class' => 'required');
		}
		else
		{
			$attribs = null;
		}

		$return .= JHTML::calendar($this->value, 'cform[date][' . $this->fieldcode . ']', $this->fieldcode, '%Y-%m-%d', $attribs);

		$return .= '<div class="help-block">' . JText::_('COM_REDITEM_FIELD_DATETIME_HELP') . '</div>';

		return $return;
	}
}
