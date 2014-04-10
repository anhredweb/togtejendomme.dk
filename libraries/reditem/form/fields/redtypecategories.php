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
 * @subpackage  Field.RedTypeCategories
 *
 * @since       2.0
 */
class JFormFieldRedTypeCategories extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 */
	protected $type = 'RedTypeCategories';

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

		$text = '';

		$query = $db->getQuery(true);
		$query->select(array($db->quoteName('ty.id'), $db->quoteName('ty.title')))
			->from($db->quoteName('#__reditem_types', 'ty'))
			->order($db->quoteName('ty.title'));

		$db->setQuery($query);

		$types = $db->loadObjectList();

		if ($types)
		{
			foreach ($types as $type)
			{
				/*$options[] = JHTML::_('select.option', '', $type->title);*/
				$groups[$type->id] = array();
				$groups[$type->id]['id'] = $type->id . '__';
				$groups[$type->id]['text'] = $type->title;
				$groups[$type->id]['items'] = array();

				$query = $db->getQuery(true);
				$query->select($db->quoteName('c.id'))
					->select($db->quoteName('c.title'))
					->select($db->quoteName('c.level'))
					->select($db->quoteName('c.parent_id'))
					->from($db->quoteName('#__reditem_categories', 'c'))
					->where($db->quoteName('c.published') . ' = ' . $db->quote('1'))
					->where($db->quoteName('c.type_id') . ' = ' . (int) $type->id)
					->order($db->quoteName('c.lft'));

				$db->setQuery($query);

				$categories = $db->loadObjectList();

				if ($categories)
				{
					// Make recursive tree
					$children = array();

					foreach ($categories as $category)
					{
						$category->value = $category->id;
						$category->text = $category->title;
						$pt = $category->parent_id;

						if (isset($children[$pt]))
						{
							$list = $children[$pt];
						}
						else
						{
							$list = array();
						}

						array_push($list, $category);
						$children[$pt] = $list;

						/*$options[] = JHTML::_('select.option', $template->id, $template->name);*/
						// $groups[$type->id]['items'][] = JHtml::_('select.option', $type->id . ':' . $category->id, $category->title);
					}

					// Add as options
					$list = JHTML::_('menu.treerecurse', 1, '', array(), $children, 9999, 0, 0);

					foreach ($list as $cat)
					{
						$cat->treename = JString::str_ireplace('&#160;', ' -', $cat->treename);
						$groups[$type->id]['items'][] = JHtml::_('select.option', $cat->id, $cat->treename);
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

		// Initialize multiple value
		$attr .= $this->element['multiple'] ? ' multiple="true"' : '';

		$attr .= ' style="min-height: 300px;"';

		// Prepare HTML code
		$html = array();

		// Compute the current selected values
		$selected = $this->value;

		// Add a grouped list
		$html[] = JHtml::_(
			'select.groupedlist', $groups, $this->name,
			array('id' => $this->id, 'group.id' => 'id', 'list.attr' => $attr, 'list.select' => $selected)
		);

		return implode($html);
	}
}
