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
 * @subpackage  CustomField.Gallery
 * @since       2.0
 *
 */
class TCustomfieldGallery extends TCustomfield
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
		$field_name = 'cform[gallery][' . $this->fieldcode . '_file][]';
		$value = $this->value;
		$data = '';
		$index = 0;

		if ($this->required)
		{
			$class = ' class="required"';
		}

		if ($value)
		{
			$_imgs = json_decode($value, true);

			foreach ($_imgs as $_img)
			{
				if (($_img) && (!empty($_img)))
				{
					$_id = 'cform_' . $this->fieldcode . $index;
					$_imgsrc = JURI::root() . 'components/com_reditem/assets/images/' . $basePath . '/' . $_img;
					$data .= '<div class="media" id="div_' . $_id . '">';
					$data .= '<img src="' . $_imgsrc . '" class="img-polaroid pull-left" style="max-width: 300px; max-height: 300px; margin-right: 20px;" />';
					$data .= '<div class="media-body">';
					$data .= '<label class="checkbox">';
					$data .= '<input type="checkbox" name="jform[customfield_gallery_rm][' . $this->fieldcode . '][]" value="' . $_img . '" /> ';
					$data .= JText::_('COM_REDITEM_CUSTOMFIELD_IMAGE_REMOVE');
					$data .= '</label>';
					$data .= '<input type="hidden" name="cform[gallery][' . $this->fieldcode . '][]" id="' . $_id . '_value" value="' . $_img . '" />';
					$data .= '</div>';
					$data .= '</div>';
					$index++;
				}
			}
		}

		$js = '
		var index_' . $this->fieldcode . ' = ' . $index . ';
		function ri_' . $this->fieldcode . '_add()
		{
			var _id = "cform_' . $this->fieldcode . '" + index_' . $this->fieldcode . ';
			var str = "<div style=\'display: block; padding: 10px 0px;\' id=\'div_" + _id + "\'>";
			str += "<input type=\'file\' name=\'' . $field_name . '\' id=\'" + _id + "\' />";
			str += "<a href=\'javascript:void(0);\' onClick=\'javascript:ri_' . $this->fieldcode . '_remove(\"" + _id + "\");\'>";
			str += "' . JText::_('COM_REDITEM_CUSTOMFIELD_IMAGE_REMOVE') . '</a>";
			str += "</div>";
			index_' . $this->fieldcode . '++;
			jQuery("#' . $div_id . '").append(str);
			jQuery("#cform_' . $this->fieldcode . '").val(\'\');
		}
		function ri_' . $this->fieldcode . '_remove(id)
		{
			var obj = document.getElementById(\'div_\' + id);
			jQuery(obj).remove();
			jQuery("#cform_' . $this->fieldcode . '").val(\'\');
		}';

		$doc->addScriptDeclaration($js);

		/*
		 * Required to avoid a cycle of encoding &
		 * html_entity_decode was used in place of htmlspecialchars_decode because
		 * htmlspecialchars_decode is not compatible with PHP 4
		 */
		$value = htmlspecialchars(html_entity_decode($this->value, ENT_QUOTES), ENT_QUOTES);

		$return_data = '<p><a class="btn" href="javascript:void(0);" onClick="javascript:ri_' . $this->fieldcode . '_add()">';
		$return_data .= JText::_('COM_REDITEM_CUSTOMFIELD_IMAGE_ADD');
		$return_data .= '</a></p>';

		$return_data .= '<div id="' . $div_id . '">';
		$return_data .= $data;
		$return_data .= '</div>';
		$return_data .= '<input type="hidden" name="cform[gallery][' . $this->fieldcode . '][]" id="cform_' . $this->fieldcode . '" value=""/>';

		return $return_data;
	}
}
