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
 * RedITEM category tree select list
 *
 * @package     RedITEM.Backend
 * @subpackage  Field.RICategoriesTree
 *
 * @since       2.0
 */
class JFormFieldRICategoriesTree extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 */
	protected $type = 'RICategoriesTree';

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
		$type_id = JFactory::getApplication()->getUserState('com_reditem.global.tid', '0');
		$categories_id = JFactory::getApplication()->getUserState('com_reditem.global.cid');

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select(array('c.*'))
			->from($db->qn('#__reditem_categories', 'c'))
			->order('c.title')
			->where($db->qn('c.type_id') . ' = ' . $db->q($type_id))
			->where($db->qn('c.published') . ' = ' . $db->q(1));

		if ($categories_id)
		{
			$query->where($db->qn('c.id') . ' NOT IN (' . implode(',', $categories_id) . ')');
		}

		$db->setQuery($query);
		$options = $db->loadObjectList();

		$children = array();

		if ($options)
		{
			foreach ($options as $v)
			{
				$v->value = $v->id;
				$v->text = $v->title;
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

		$list = JHTML::_('menu.treerecurse', 1, '', array(), $children, 9999, 0, 0);
		$options = array();
		$options = array_merge(parent::getOptions(), $options);

		foreach ($list as $item)
		{
			$item->treename = JString::str_ireplace('&#160;', ' -', $item->treename);
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
