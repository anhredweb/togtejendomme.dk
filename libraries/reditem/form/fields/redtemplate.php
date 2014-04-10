<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Field
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JLoader::import('joomla.form.formfield');

JFormHelper::loadFieldClass('list');

/**
 * Select field listing all templates available in a specific section
 *
 * @package     RedITEM.Backend
 * @subpackage  Field
 *
 * @since       2.0
 */
class JFormFieldRedtemplate extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var                string
	 */
	protected $type = 'Redtemplate';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  Options to populate the select field
	 */
	public function getOptions()
	{
		$options = array();

		// Get the templates based on a specificed 'section' defined in the xml form field.
		$section = '';

		if (!empty($this->element['section']))
		{
			$section = $this->element['section'];
		}

		$templates = $this->getTemplates($section);

		// Clean up the options
		$options = array();

		if (!empty($templates))
		{
			foreach ($templates as $template)
			{
				$options[] = JHtml::_('select.option', $template->value, $template->text);
			}
		}

		return $options;
	}

	/**
	 * Method to get field input
	 *
	 * @return  HTMLCode
	 */
	public function getInput()
	{
		// Initialize variables.
		$html = array();
		$attr = '';
		$placeholder = JText::_((string) ($this->element['placeholder']) ? $this->element['placeholder'] : $this->element['label']);

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';

		// To avoid user's confusion, readonly="true" should imply disabled="true".
		if ((string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true')
		{
			$attr .= ' disabled="disabled"';
		}

		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		// Put placeholder
		$attr .= ' placeholder="' . $placeholder . '"';

		// Get the field options.
		$options = (array) $this->getOptions();

		// Create a read-only list (no name) with a hidden input to store the value.
		if ((string) $this->element['readonly'] == 'true')
		{
			$html[] = JHtml::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $this->value, $this->id);
			$html[] = '<input type="hidden" name="' . $this->name . '" value="' . $this->value . '"/>';
		}
		// Create a regular list.
		else
		{
			$html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
		}

		return implode($html);
	}

	/**
	 * Method to get the list of templates of a specific section.
	 *
	 * @param   string  $section  the templates section
	 *
	 * @return array|bool  An array of templates.
	 *
	 * @throws RuntimeException
	 */
	protected function getTemplates($section)
	{
		$type_id = JFactory::getApplication()->getUserState('com_reditem.global.tid', '0');

		if (!$section)
		{
			return false;
		}

		if (empty($this->cache))
		{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select('t.id as value')
				->select('t.name as text')
				->from($db->quoteName('#__reditem_templates', 't'))
				->where($db->quoteName('published') . '=' . $db->quote('1'))
				->where($db->quoteName('typecode') . '=' . $db->quote($section))
				->where($db->quoteName('type_id') . ' = ' . $db->quote($type_id))
				->order('t.name');

			$db->setQuery($query);

			try
			{
				$options = $db->loadObjectList();
			}
			catch (RuntimeException $e)
			{
				throw new RuntimeException($e->getMessage());
			}

			// Merge any additional options in the XML definition.
			$options = array_merge(parent::getOptions(), $options);
		}

		return $options;
	}
}
