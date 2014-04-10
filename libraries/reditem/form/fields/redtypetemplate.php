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
 * Categories select list
 *
 * @package     RedITEM.Backend
 * @subpackage  Field.RedTypeTemplate
 *
 * @since       2.0
 */
class JFormFieldRedTypeTemplate extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 */
	protected $type = 'RedTypeTemplate';

	/**
	 * Method to get the field input markup for a modal select form.
	 *
	 * @return  string  The field input markup.
	 */
	public function getInput()
	{
		$doc = JFactory::getDocument();
		$db = JFactory::getDBO();
		$name = $this->name;
		$fieldName = $this->name;
		$options = array();
		$groups = array();

		$value = $this->value;

		// Default section is 'module_items' for redITEM Module Items templates
		$section = 'module_items';

		if (!empty($this->element['section']))
		{
			$section = $this->element['section'];
		}

		$text = '';

		$query = $db->getQuery(true);
		$query->select(array($db->quoteName('ty.id'), $db->quoteName('ty.title')))
			->from($db->quoteName('#__reditem_types', 'ty'))
			->order($db->quoteName('ty.title'));

		$db->setQuery($query);

		$types = $db->loadObjectList();

		$groups[0] = array();
		$groups[0]['id'] = '';
		$groups[0]['text'] = '';
		$groups[0]['items'] = array(JHtml::_('select.option', '', '--' . JText::_('COM_REDITEM_USE_ASSIGNED_TEMPLATE') . '--'));

		if ($types)
		{
			foreach ($types as $type)
			{
				$groups[$type->id] = array();
				$groups[$type->id]['id'] = $type->id . '__';
				$groups[$type->id]['text'] = $type->title;
				$groups[$type->id]['items'] = array();

				$query = $db->getQuery(true);
				$query->select(array($db->quoteName('tmpl.id'), $db->quoteName('tmpl.name')))
					->from($db->quoteName('#__reditem_templates', 'tmpl'))
					->where($db->quoteName('tmpl.published') . ' = ' . $db->quote('1'))
					->where($db->quoteName('tmpl.type_id') . ' = ' . (int) $type->id)
					->where($db->quoteName('tmpl.typecode') . ' = ' . $db->quote($section))
					->order($db->quoteName('tmpl.name'));

				$db->setQuery($query);

				$templates = $db->loadObjectList();

				if ($templates)
				{
					foreach ($templates as $template)
					{
						/*$options[] = JHTML::_('select.option', $template->id, $template->name);*/
						$groups[$type->id]['items'][] = JHtml::_('select.option', $template->id, $template->name);
					}
				}
			}
		}

		$options = array_merge(parent::getOptions(), $options);

		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		// Prepare HTML code
		$html = array();

		// Compute the current selected values
		$selected = array($this->value);

		// Add a grouped list
		$html[] = JHtml::_(
			'select.groupedlist', $groups, $this->name,
			array('id' => $this->id, 'group.id' => 'id', 'list.attr' => $attr, 'list.select' => $selected)
		);

		return implode($html);
	}
}
