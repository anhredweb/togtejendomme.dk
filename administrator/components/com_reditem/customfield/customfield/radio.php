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
 * @subpackage  CustomField.Radio
 * @since       2.0
 *
 */
class TCustomfieldRadio extends TCustomfield
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
		$option_list = array();
		$html = '';
		$options = explode("\n", $this->options);

		if ($options)
		{
			foreach ($options as $opt)
			{
				$opt = trim($opt);
				$option_list[] = JHTML::_('select.option', $opt, $opt);
			}
		}

		reset($option_list);

		$name = 'cform[radio][' . $this->fieldcode . ']';

		foreach ($option_list as $obj)
		{
			$k = $obj->value;
			$t = $obj->text;
			$id = (isset($obj->id) ? $obj->id : null);
			$id_text = $name;

			$extra = '';
			$extra .= $id ? ' id="' . $obj->id . '"' : '';
			$selected = $this->value;

			if (is_array($selected))
			{
				foreach ($selected as $val)
				{
					$k2 = is_object($val) ? $val->value : $val;

					if ($k == $k2)
					{
						$extra .= ' selected="selected"';
						break;
					}
				}
			}
			else
			{
				$extra .= ((string) $k == (string) $selected ? ' checked="checked"' : '');
			}

			$html .= '<label for="' . $id_text . $k . '"' . ' id="' . $id_text . $k . '-lbl" class="radio">'
				. "\n\t\t" . '<input type="radio" name="' . $name . '"' . ' id="' . $id_text . $k . '" value="' . $k . '"' . ' ' . $extra . '/>'
				. $t . "\n\t" . '</label>';
		}

		return $html;
	}
}
