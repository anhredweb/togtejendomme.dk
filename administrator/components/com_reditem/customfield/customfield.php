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

require_once JPATH_ADMINISTRATOR . '/components/com_reditem/customfield/customfield/textbox.php';
require_once JPATH_ADMINISTRATOR . '/components/com_reditem/customfield/customfield/textarea.php';
require_once JPATH_ADMINISTRATOR . '/components/com_reditem/customfield/customfield/editor.php';
require_once JPATH_ADMINISTRATOR . '/components/com_reditem/customfield/customfield/date.php';
require_once JPATH_ADMINISTRATOR . '/components/com_reditem/customfield/customfield/radio.php';
require_once JPATH_ADMINISTRATOR . '/components/com_reditem/customfield/customfield/checkbox.php';
require_once JPATH_ADMINISTRATOR . '/components/com_reditem/customfield/customfield/select.php';
require_once JPATH_ADMINISTRATOR . '/components/com_reditem/customfield/customfield/selectmultiple.php';
require_once JPATH_ADMINISTRATOR . '/components/com_reditem/customfield/customfield/gallery.php';
require_once JPATH_ADMINISTRATOR . '/components/com_reditem/customfield/customfield/image.php';
require_once JPATH_ADMINISTRATOR . '/components/com_reditem/customfield/customfield/googlemaps.php';
require_once JPATH_ADMINISTRATOR . '/components/com_reditem/customfield/customfield/number.php';
require_once JPATH_ADMINISTRATOR . '/components/com_reditem/customfield/customfield/file.php';

/**
 * Render custom fields, generic class
 *
 * @package     RedITEM.Component
 * @subpackage  Customfields
 * @since       2.0
 *
 */
class TCustomfield extends JObject
{
	public $id;

	public $name;

	public $tag;

	public $type;

	public $tips;

	public $required;

	public $options;

	public $min;

	public $max;

	public $value;

	public $filter;

	public $fieldcode;

	public $params;

	/**
	 * Constructor
	 */
	public function __construct()
	{
	}

	/**
	 * returns element form html code
	 *
	 * @param   array  $attributes  [description]
	 *
	 * @return void
	 */
	public function render($attributes = '')
	{
		return;
	}

	/**
	 * bind properties to an object or array
	 *
	 * @param   object/array  $source  [description]\
	 *
	 * @return void
	 */
	public function bind($source)
	{
		// If object
		if (is_object($source))
		{
			// Use only public values defined in the object
			$source = get_object_vars($source);
		}

		// If array
		if (is_array($source))
		{
			$obj_keys = array_keys(get_object_vars($this));

			foreach ($source AS $key => $value)
			{
				if (in_array($key, $obj_keys))
				{
					$this->$key = $value;
				}
			}
		}

		$this->id = 'cform_' . $this->fieldcode;
	}

	/**
	 * returns form field for filtering
	 *
	 * @param   string  $attributes  [description]
	 *
	 * @return  string
	 */
	public function renderFilter($attributes = '')
	{
		return 'no filter';
	}

	/**
	 * returns the value
	 *
	 * @return string
	 */
	public function renderValue()
	{
		return $this->value;
	}

	/**
	 * returns the label
	 *
	 * @return string
	 */
	public function getLabel()
	{
		$label = '<label for="' . $this->id . '" id="' . $this->id . '-lbl" class="hasTooltip" title="' . $this->name . '">';
		$label .= $this->name;
		$label .= '</label>';

		return $label;
	}
}
