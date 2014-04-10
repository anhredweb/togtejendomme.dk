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
 * RedITEM keywords select list
 *
 * @package     RedITEM.Backend
 * @subpackage  Field.Keywords
 *
 * @since       0.9.1
 */
class JFormFieldRIKeywords extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 */
	protected $type = 'RIKeywords';

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 * If need hide all childs categories - use in field option unuse="category_id" ,
	 * where category_id - field id parent category for all childs
	 *
	 * @return  string  The field input markup.
	 */
	public function getInput()
	{
		$doc = JFactory::getDocument();
		$db = JFactory::getDBO();
		$name = $this->name;
		$fieldName = $this->name;

		$kid = JFactory::getApplication()->getUserState('com_reditem.global.edit.kwid', '0');

		$q = $db->getQuery(true);
		$q->select('k.*')->from($db->qn('#__reditem_keywords', 'k'));

		if ($kid)
		{
			$q->where($db->qn('id') . ' <> ' . $db->q($kid));
		}

		$db->setQuery($q);

		$options = $db->loadObjectList();

		$children = array();

		if ($options)
		{
			foreach ($options as $v)
			{
				$v->title = $v->name;
				$v->value = $v->id;
				$v->text = $v->name;
				$pt = $v->parent_id;

				if (isset($children[$pt]))
				{
					$list = $children[$pt];
				}
				else
				{
					$list = array();
				}

				array_push($list, $v);
				$children[$pt] = $list;
			}
		}

		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
		$options = array();
		$options = array_merge(parent::getOptions(), $options);

		foreach ($list as $item)
		{
			$options[] = JHTML::_('select.option', $item->id, $item->treename);
		}

		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';
		$attr .= $this->placeholder ? ' placeholder="' . (string) $this->element['placeholder'] . '"' : '';

		if ($this->multiple && !is_array($this->value))
		{
			$this->value = explode(",", $this->value);
		}

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		return JHTML::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
	}
}
