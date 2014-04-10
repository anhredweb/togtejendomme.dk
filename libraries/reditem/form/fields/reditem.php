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
 * Items select list
 *
 * @package     RedITEM.Backend
 * @subpackage  Field.Items
 *
 * @since       2.0
 */
class JFormFieldRedItem extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 */
	protected $type = 'RedItem';

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

		$value = $this->value;

		$text = '';

		if ($value)
		{
			$q = $db->getQuery(true);
			$q->select('i.title')
			->from($db->qn('#__reditem_items', 'i'))
			->where($db->qn('i.id') . ' = ' . $db->q($value));
			$db->setQuery($q);
			$result = $db->loadObject();
			$text = ($result) ? $result->title : JText::_('COM_REDITEM_SELECT_A_ITEM');
		}
		else
		{
			$text = JText::_('COM_REDITEM_SELECT_A_ITEM');
		}

		$js = "
		function jRISelectItem(id, title, object) {
			document.getElementById(object + '_id').value = id;
			document.getElementById(object + '_name').value = title;
			window.parent.SqueezeBox.close();
		}";

		$doc->addScriptDeclaration($js);

		$link = 'index.php?option=com_reditem&amp;view=items&amp;layout=elements&amp;tmpl=component&amp;object=' . $name;

		JHTML::_('behavior.modal', 'a.modal');

		$_val = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

		$html = '<input style="background: #ffffff;" type="text" id="' . $name . '_name" value="' . $_val . '" disabled="disabled" />';
		$html .= '<a class="btn btn-primary modal" href="' . $link . '" rel="{handler: \'iframe\', size: {x: 800, y: 600}}">';
		$html .= JText::_('COM_REDITEM_SELECT') . '</a>';
		$html .= '<input type="hidden" id="' . $name . '_id" name="' . $fieldName . '" value="' . $value . '" />';

		return $html;
	}
}
