<?php
/**
 * @package     RedITEM.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die;

JLoader::import('reditem.library');

require_once JPATH_SITE . '/components/com_reditem/helpers/imagegenerator.php';
require_once JPATH_SITE . '/components/com_reditem/helpers/route.php';

/**
 * Custom field tags
 *
 * @package     RedITEM.Frontend
 * @subpackage  Helper.Helper
 * @since       2.0
 *
 */
class ReditemTagsHelper
{
	/**
	 * Replace tag on content of template -> Category TAG
	 *
	 * @param   string  &$content     Content template
	 * @param   array   $category     Category data array
	 * @param   string  $subCategory  prefix string for sub Category tag "sub_"
	 * @param   int     $mainCID      Parent category id
	 *
	 * @return  void
	 */
	public static function tagReplaceCategory(&$content, $category, $subCategory = '', $mainCID = 0)
	{
		$imageGenerator = new ImageGenerator;
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('reditem_categories');

		// Category link
		$categoryLink = ReditemRouterHelper::getCategoryRoute($category->id);

		$categoryLink = JRoute::_($categoryLink);
		$content = str_replace('{' . $subCategory . 'category_link}', $categoryLink, $content);

		// Category Title
		$content = str_replace('{' . $subCategory . 'category_title}', $category->title, $content);

		// Introtext tag
		/*$content = str_replace('{' . $subCategory . 'category_introtext}', $category->introtext, $content);*/
		$matches = array();

		if (preg_match_all('/{' . $subCategory . 'category_introtext[^}]*}/i', $content, $matches) > 0)
		{
			$matches = $matches[0];

			foreach ($matches as $match)
			{
				$textParams = explode('|', $match);

				$textContent = $category->introtext;

				if (isset($textParams[1]))
				{
					// Have param limit string
					$textContent = JHTML::_('string.truncate', $textContent, (int) $textParams[1], true, false);
				}

				$content = str_replace($match, $textContent, $content);
			}
		}

		// Fulltext tag
		/*$content = str_replace('{' . $subCategory . 'category_fulltext}', $category->fulltext, $content);*/
		$matches = array();

		if (preg_match_all('/{' . $subCategory . 'category_fulltext[^}]*}/i', $content, $matches) > 0)
		{
			$matches = $matches[0];

			foreach ($matches as $match)
			{
				$textParams = explode('|', $match);

				$textContent = $category->fulltext;

				if (isset($textParams[1]))
				{
					// Have param limit string
					$textContent = JHTML::_('string.truncate', $textContent, (int) $textParams[1], true, false);
				}

				$content = str_replace($match, $textContent, $content);
			}
		}

		// Original Image tag
		$imageOriginalLink = $imageGenerator->getImageLink($category->id, 'category', $category->type_id, $category->category_image, '', 300, 300, true);
		$img = $imageGenerator->getImageLink($category->id, 'category', $category->type_id, $category->category_image);
		$content = str_replace('{' . $subCategory . 'category_image}', $img, $content);
		$content = str_replace('{' . $subCategory . 'category_image_link}', $imageOriginalLink, $content);

		// Image Thumbnail Large tag
		$imageLarge = $imageGenerator->getImageLink($category->id, 'category', $category->type_id, $category->category_image, 'large');
		$content = str_replace('{' . $subCategory . 'category_image_large}', $imageLarge, $content);

		// Image Thumbnail Large tag
		$imageMedium = $imageGenerator->getImageLink($category->id, 'category', $category->type_id, $category->category_image, 'medium');
		$content = str_replace('{' . $subCategory . 'category_image_medium}', $imageMedium, $content);

		// Image Thumbnail Large tag
		$imageSmall = $imageGenerator->getImageLink($category->id, 'category', $category->type_id, $category->category_image, 'small');
		$content = str_replace('{' . $subCategory . 'category_image_small}', $imageSmall, $content);

		// Print icon
		if ($subCategory === '')
		{
			// Don't replace tag {print_icon} for sub category
			$content = str_replace('{print_icon}', 'Print icon', $content);
		}

		// Run event 'onReplaceCategoryTag'
		$dispatcher->trigger('onReplaceCategoryTag', array(&$content, $category, $subCategory));
	}

	/**
	 * Replace tag on content of template -> Item TAG
	 *
	 * @param   string  &$content    Content template
	 * @param   array   $item        Item data array
	 * @param   int     $categoryId  Parent category id
	 * @param   int     $itemId      Item ID default
	 *
	 * @return  void
	 */
	public static function tagReplaceItem(&$content, $item, $categoryId = 0, $itemId = 0)
	{
		$imageGenerator = new ImageGenerator;

		// Title tag
		$content = str_replace('{item_title}', $item->title, $content);

		// Item link
		$categoryId = (int) $categoryId;

		if (($categoryId === 0) && (isset($item->categories)))
		{
			// Get first id of categories of item
			$categoryId = key($item->categories);
		}

		if ($itemId)
		{
			$itemLink = JRoute::_('index.php?option=com_reditem&view=itemdetail&id=' . $item->id . '&cid=' . $categoryId . '&Itemid=' . $itemId);
		}
		else
		{
			$itemLink = JRoute::_(ReditemRouterHelper::getItemRoute($item->id));
		}

		$content = str_replace('{item_link}', $itemLink, $content);

		// Introtext tag
		$matches = array();

		if (preg_match_all('/{item_introtext[^}]*}/i', $content, $matches) > 0)
		{
			$matches = $matches[0];

			foreach ($matches as $match)
			{
				$textParams = explode('|', $match);

				$textContent = $item->introtext;

				if (isset($textParams[1]))
				{
					// Have param limit string
					$textContent = JHTML::_('string.truncate', $textContent, (int) $textParams[1], true, false);
				}

				$content = str_replace($match, $textContent, $content);
			}
		}

		// Fulltext tag
		$matches = array();

		if (preg_match_all('/{item_fulltext[^}]*}/i', $content, $matches) > 0)
		{
			$matches = $matches[0];

			foreach ($matches as $match)
			{
				$textParams = explode('|', $match);

				$textContent = $item->fulltext;

				if (isset($textParams[1]))
				{
					// Have param limit string
					$textContent = JHTML::_('string.truncate', $textContent, (int) $textParams[1], true, false);
				}

				$content = str_replace($match, $textContent, $content);
			}
		}

		// Original Image tag
		$imageOriginalLink = $imageGenerator->getImageLink($item->id, 'item', $item->type_id, $item->item_image, '', 300, 300, true);
		$img = $imageGenerator->getImageLink($item->id, 'item', $item->type_id, $item->item_image);
		$content = str_replace('{item_image}', $img, $content);
		$content = str_replace('{item_image_link}', $imageOriginalLink, $content);

		// Image Thumbnail Large tag
		$imageLarge = $imageGenerator->getImageLink($item->id, 'item', $item->type_id, $item->item_image, 'large');
		$content = str_replace('{item_image_large}', $imageLarge, $content);

		// Image Thumbnail Large tag
		$imageMedium = $imageGenerator->getImageLink($item->id, 'item', $item->type_id, $item->item_image, 'medium');
		$content = str_replace('{item_image_medium}', $imageMedium, $content);

		// Image Thumbnail Large tag
		$imageSmall = $imageGenerator->getImageLink($item->id, 'item', $item->type_id, $item->item_image, 'small');
		$content = str_replace('{item_image_small}', $imageSmall, $content);

		// Get parent categories of item
		$itemsModel = RModel::getAdminInstance('Items', array('ignore_request' => true), 'com_reditem');
		$items = array($item);
		$items = $itemsModel->getPrepareItems($items);
		$item = $items[0];

		if (isset($item->categories) && is_array($item->categories))
		{
			$first = true;
			$parentCategoryLink = '';

			foreach ($item->categories as $parentCategory)
			{
				// Only get first parent category
				if ($first)
				{
					$parentCategoryLink = JRoute::_(ReditemRouterHelper::getCategoryRoute($parentCategory->id));
					$first = false;
				}
			}

			$content = str_replace('{item_first_cat_link}', $parentCategoryLink, $content);
		}

		// Print icon
		$content = str_replace('{print_icon}', '', $content);
	}

	/**
	 * Replace filter tag on content of template
	 *
	 * @param   string  &$content      Content template
	 * @param   array   $mainCategory  Array data of current category
	 *
	 * @return  void
	 */
	public static function tagReplaceFilter(&$content, $mainCategory)
	{
		$doc = JFactory::getDocument();
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		$matches = array();

		/*
		 * {filter_category|<categoryID>|<generatedFilterHTML>|<featuredCategory>}
		 */
		$preg = '/{filter_category[^}]*}/i';

		if (preg_match_all($preg, $content, $matches) > 0)
		{
			$matches = $matches[0];
			$categoryModel = RModel::getAdminInstance('Category', array('ignore_request' => true), 'com_reditem');
			$categoriesModel = RModel::getAdminInstance('Categories', array('ignore_request' => true), 'com_reditem');

			foreach ($matches as $match)
			{
				$category = null;
				$subCategories = array();
				$categoryId = 0;
				$featuredCategory = 0;
				$generatedFilterType = "select";

				$params = explode('|', $match);
				$params = str_replace('{', '', $params);
				$params = str_replace('}', '', $params);

				// Get param categoryID
				if (isset($params[1]))
				{
					$categoryId = (int) $params[1];
				}

				if ($categoryId)
				{
					$category = $categoryModel->getItem($categoryId);
				}
				else
				{
					$category = $mainCategory;
				}

				// Get param generatedFilterHTML
				if (isset($params[2]))
				{
					if (($params[2] == 'radio') || ($params[2] == 'checkbox') || ($params[2] == 'list'))
					{
						$generatedFilterType = $params[2];
					}
				}

				// Get param featuredCategory
				if (isset($params[3]))
				{
					$featuredCategory = (int) $params[3];
				}

				if ($featuredCategory == 1)
				{
					$categoriesModel->setState('filter.featured', 1);
				}

				// Get sub-Categories deeper
				$categoriesModel->setState('filter.published', 1);
				$categoriesModel->setState('filter.lft', $category->lft);
				$categoriesModel->setState('filter.rgt', $category->rgt);
				$categoriesModel->setState('list.select', 'DISTINCT (c.id), c.title, c.parent_id, c.level, c.type_id, c.category_image');
				$categoriesModel->setState('list.ordering', 'c.title');
				$categoriesModel->setState('list.direction', 'asc');

				$query = $categoriesModel->getListQuery();

				$db->setQuery($query);

				$subCategories = $db->loadObjectList();

				if ($subCategories)
				{
					switch ($generatedFilterType)
					{
						/* Default type: Select combobox */
						case 'select':
						default:
							$options = array();
							$children = array();

							$options[] = JHTML::_('select.option', '', JText::_('JALL') . ' ' . $category->title);

							// Create tree list

							foreach ($subCategories as $subCategory)
							{
								$pt = $subCategory->parent_id;
								$list = (isset($children[$pt])) ? $children[$pt] : array();
								array_push($list, $subCategory);
								$children[$pt] = $list;
							}

							$treeCategories = JHTML::_('menu.treerecurse', $category->id, ' ', array(), $children, 9999, 0, 0);

							// Add tree list to options list

							foreach ($treeCategories as $node)
							{
								$options[] = JHTML::_('select.option', $node->id, $node->treename);
							}

							$attribs = ' class="chosen" onChange="javascript:reditemFilterAjax();"';
							$filters = $app->input->get('filter_category', null, 'array');
							$value = '';

							if ($filters)
							{
								if (isset($filters[$category->id]))
								{
									$value = $filters[$category->id];
								}
							}

							$selectHTML = JHTML::_('select.genericlist', $options, 'filter_category[' . $category->id . ']', $attribs, 'value', 'text', $value);

							$content = str_replace($match, $selectHTML, $content);
							break;

						/* Create Radio list */
						case 'radio':
							$option_list = array();
							$filters = $app->input->get('filter_category', null, 'array');
							$value = '';

							if ($filters)
							{
								if (isset($filters[$category->id]))
								{
									$value = $filters[$category->id];
								}
							}

							foreach ($subCategories as $subCategory)
							{
								if ($subCategory->id != $category->id)
								{
									$option_list[] = JHTML::_('select.option', $subCategory->id, $subCategory->title);
								}
							}

							$filterName = 'filter_category[' . $category->id . ']';
							$filterIdTag = 'filter_category_' . $category->id;
							$attribs = 'onClick="javascript:reditemFilterAjax();"';
							array_unshift($option_list, JHTML::_('select.option', '', JText::_('JALL')));
							$generatedFilterHTML = JHTML::_('select.radiolist', $option_list, $filterName, $attribs, 'value', 'text', $value, $filterIdTag);
							$content = str_replace($match, $generatedFilterHTML, $content);
							break;

						/* Create Checkbox list */
						case 'checkbox':
							$option_list = array();
							$filters = $app->input->get('filter_category', null, 'array');
							$value = '';

							if ($filters)
							{
								if (isset($filters[$category->id]))
								{
									$value = $filters[$category->id];
								}
							}

							foreach ($subCategories as $subCategory)
							{
								if ($subCategory->id != $category->id)
								{
									$option_list[] = JHTML::_('select.option', $subCategory->id, $subCategory->title);
								}
							}

							$filterName = 'filter_category[' . $category->id . ']';
							$filterIdTag = 'filter_category_' . $category->id;
							$attribs = 'onClick="javascript:reditemFilterAjax();"';
							array_unshift($option_list, JHTML::_('select.option', '', JText::_('JALL')));
							$generatedFilterHTML = self::checkboxlist($option_list, $filterName . '[]', $attribs, 'value', 'text', $value, $filterIdTag);

							$content = str_replace($match, $generatedFilterHTML, $content);
							break;

						/* Create list item */
						case 'list':
							$imageGenerator = new ImageGenerator;
							$filters = $app->input->get('filter_category', null, 'array');
							$value = '';
							$controlName = 'filter_category[' . $category->id . ']';
							$controlID = 'reditem_filter_category_' . $category->id;

							if ($filters)
							{
								if (isset($filters[$category->id]))
								{
									$value = $filters[$category->id];
								}
							}

							$generatedFilterJS = 'if (typeof reditemFilterCategoryListChange != "function")
										{
											function reditemFilterCategoryListChange(element, id, value)
											{
												var objlist = jQuery("#reditem_filter_category_list_" + id);
												var hiddenobj = jQuery("#reditem_filter_category_" + id);

												objlist.find("li").each(function (index)
												{
													jQuery(this).removeClass("active");
												});

												element = jQuery(element);
												element.parent().addClass("active");

												hiddenobj.val(value);

												reditemFilterAjax();
											}
										}';
							$doc->addScriptDeclaration($generatedFilterJS);

							$generatedFilterHTML = '<ul class="reditem_filter_category_list" id="reditem_filter_category_list_' . $category->id . '">';

							// Add "All" option
							$generatedFilterHTML .= '<li class="list-item active">';
							$generatedFilterHTML .= '<a href="javascript:void(0);" onClick="reditemFilterCategoryListChange(this, \'' . $category->id . '\', \'\');">';
							$generatedFilterHTML .= JText::_('JALL');
							$generatedFilterHTML .= '</a>';
							$generatedFilterHTML .= '</li>';

							foreach ($subCategories as $subCategory)
							{
								if ($subCategory->id != $category->id)
								{
									$imageSmall = $imageGenerator->getImageLink($subCategory->id, 'category', $subCategory->type_id, $subCategory->category_image, 'small');

									$generatedFilterHTML .= '<li class="list-item">';
									$generatedFilterHTML .= '<a href="javascript:void(0);"';
									$generatedFilterHTML .= ' onClick="reditemFilterCategoryListChange(this, \'' . $category->id . '\', \'' . $subCategory->id . '\');">';
									$generatedFilterHTML .= $imageSmall;
									$generatedFilterHTML .= '<span>' . $subCategory->title . '</span>';
									$generatedFilterHTML .= '</a>';
									$generatedFilterHTML .= '</li>';
								}
							}

							$generatedFilterHTML .= '</ul>';

							$generatedFilterHTML .= '<input type="hidden" name="' . $controlName . '" id="' . $controlID . '" value="' . $value . '" />';

							$content = str_replace($match, $generatedFilterHTML, $content);
							break;
					}
				}
			}
		}

		/*
		 * filter_customfield | <customfield id> | <text, select, radio, checkbox, list>
		 */
		$preg = '/{filter_customfield[^}]*}/i';

		if (preg_match_all($preg, $content, $matches) > 0)
		{
			$matches = $matches[0];

			// Allowed type of customfield can be use to generate filter tool.
			$allowedFieldType = array('text', 'textarea', 'editor', 'select', 'radio', 'checkbox');

			$typeModel = RModel::getAdminInstance('Type', array('ignore_request' => true), 'com_reditem');
			$fieldModel = RModel::getAdminInstance('Field', array('ignore_request' => true), 'com_reditem');

			foreach ($matches as $match)
			{
				$tmpMatch = str_replace('{', '', $match);
				$tmpMatch = str_replace('}', '', $tmpMatch);

				$params = explode('|', $tmpMatch);
				$filterName = 'filter_customfield';
				$value = '';
				$filterIdTag = '';
				$customfieldId = 0;

				// Param: Customfield Id
				if (isset($params[1]))
				{
					$customfieldId = (int) $params[1];
				}

				$filterIdTag = $filterName . '_' . $customfieldId;

				// Param: Generated filter type, default is select combobox
				$generatedFilterType = 'select';

				if (isset($params[2]))
				{
					$generatedFilterType = $params[2];
				}

				$field = $fieldModel->getItem($customfieldId);

				// Check if type exist or not
				if (!$field)
				{
					// Skip this tag, go to next one
					$content = str_replace($match, '', $content);
					continue;
				}

				// Check this type in allowed type
				if (!in_array($field->type, $allowedFieldType))
				{
					// Skip this tag, go to next one
					$content = str_replace($match, '', $content);
					continue;
				}

				$columnName = $field->fieldcode;

				$filterName .= '[' . $field->fieldcode . ']';

				$type = $typeModel->getItem($field->type_id);

				// Check if type exist or not
				if (!$type)
				{
					// Skip this tag, go to next one
					$content = str_replace($match, '', $content);
					continue;
				}

				$tableName = '#__reditem_types_' . $type->table_name;

				// If generated just a input text, simple generated here.
				if ($generatedFilterType == 'text')
				{
					$js = '(function($){
						$(document).ready(function(){
							$("#' . $filterIdTag . '").on("keyup", function(event){
								if (event.which == 13 || event.keyCode == 13)
								{
									var fieldId = $(this).attr("id") + "_hidden";
									$("#" + fieldId).val("%" + $(this).val() + "%");
									reditemFilterAjax();
									event.preventDefault();
								}
							});
						});
					})(jQuery);' . "\n";
					$doc->addScriptDeclaration($js);
					$generatedFilterHTML = '<input type="hidden" name="' . $filterName . '" id="' . $filterIdTag . '_hidden" value="" />';
					$generatedFilterHTML .= '<input type="text" name="" id="' . $filterIdTag . '" value="" />';
					$content = str_replace($match, $generatedFilterHTML, $content);
					continue;
				}
				else
				{
					$options = array();
					$option_list = array();
					$generatedFilterHTML = '';
					$attribs = '';

					// Create the list option
					if (($field->type == 'text') || ($field->type == 'textarea') || ($field->type == 'editor'))
					{
						// If custom field is a textfield, textarea, editor. Values list will be the data which has entered already
						$query = $db->getQuery(true);
						$query->select('DISTINCT (' . $db->quoteName($columnName) . ')')
							->from($db->quoteName($tableName))
							->where($db->quoteName($columnName) . ' <> ' . $db->quote(''))
							->order($db->quoteName($columnName));

						$db->setQuery($query);
						$options = $db->loadResultArray();
					}
					else
					{
						// If custom field is other (radio, checkbox, select). Values list will be the option list of custom fields.
						$options = explode("\n", $field->options);

						// Sort this array (A-Z)
						asort($options);
					}

					if ($options)
					{
						foreach ($options as $opt)
						{
							$opt = trim($opt);
							$option_list[] = JHTML::_('select.option', $opt, $opt);
						}

						switch ($generatedFilterType)
						{
							case 'select':
								$attribs = 'onChange="javascript:reditemFilterAjax();"';
								array_unshift($option_list, JHTML::_('select.option', '', JText::_('JALL') . ' ' . $field->name));
								$generatedFilterHTML = JHTML::_('select.genericlist', $option_list, $filterName, $attribs, 'value', 'text', $value, $filterIdTag);
								break;

							case 'radio':
								$attribs = 'onClick="javascript:reditemFilterAjax();"';
								array_unshift($option_list, JHTML::_('select.option', '', JText::_('JALL') . ' ' . $field->name));
								$generatedFilterHTML = JHTML::_('select.radiolist', $option_list, $filterName, $attribs, 'value', 'text', $value, $filterIdTag);
								break;

							case 'checkbox':
								$attribs = 'onClick="javascript:reditemFilterAjax();"';
								$generatedFilterHTML = self::checkboxlist($option_list, $filterName . '[]', $attribs, 'value', 'text', $value, $filterIdTag);
								break;

							case 'list':
								$generatedFilterJS = 'if (typeof reditemFilterCustomFieldListChange != "function")
										{
											function reditemFilterCustomFieldListChange(element, id, value)
											{
												var objlist = jQuery("#reditem_filter_customfield_list_" + id);
												var hiddenobj = jQuery("#reditem_filter_customfield_" + id);

												objlist.find("li").each(function (index)
												{
													jQuery(this).removeClass("active");
												});

												element = jQuery(element);
												element.parent().addClass("active");

												hiddenobj.val(value);

												reditemFilterAjax();
											}
										}';
								$doc->addScriptDeclaration($generatedFilterJS);

								$generatedFilterHTML = '<ul class="reditem_filter_customfield_list" id="reditem_filter_customfield_list_' . $customfieldId . '">';

								// Add "All" option
								$generatedFilterHTML .= '<li class="list-item active">';
								$generatedFilterHTML .= '<a href="javascript:void(0);" onClick="reditemFilterCustomFieldListChange(this, \'' . $customfieldId . '\', \'\');">';
								$generatedFilterHTML .= JText::_('JALL');
								$generatedFilterHTML .= '</a>';
								$generatedFilterHTML .= '</li>';

								foreach ($options as $opt)
								{
									$generatedFilterHTML .= '<li class="list-item">';
									$generatedFilterHTML .= '<a href="javascript:void(0);"';
									$generatedFilterHTML .= ' onClick="reditemFilterCustomFieldListChange(this, \'' . $customfieldId . '\', \'' . trim($opt) . '\');">';
									$generatedFilterHTML .= '<span>' . trim($opt) . '</span>';
									$generatedFilterHTML .= '</a>';
									$generatedFilterHTML .= '</li>';
								}

								$generatedFilterHTML .= '</ul>';

								$generatedFilterHTML .= '<input type="hidden" name="' . $filterName . '" id="reditem_filter_customfield_' . $customfieldId . '" value="' . $value . '" />';
								break;

							default:
								$generatedFilterHTML = '';
								break;
						}
					}

					$content = str_replace($match, $generatedFilterHTML, $content);
				}
			}
		}

		/*
		 * filter_relatedcategory | <related_category_id> | <filter_category_id>
		 */
		$preg = '/{filter_relatedcategory[^}]*}/i';

		if (preg_match_all($preg, $content, $matches) > 0)
		{
			$matches = $matches[0];
			$categoryModel = RModel::getAdminInstance('Category', array('ignore_request' => true), 'com_reditem');
			$categoriesModel = RModel::getAdminInstance('Categories', array('ignore_request' => true), 'com_reditem');

			foreach ($matches as $match)
			{
				$category = null;
				$subCategories = array();
				$categoryId = 0;
				$filterCategoryId = 0;

				$params = explode('|', $match);

				if (isset($params[1]))
				{
					$categoryId = (int) $params[1];
				}

				if (isset($params[2]))
				{
					$filterCategoryId = (int) $params[2];
				}

				if ($categoryId)
				{
					$category = $categoryModel->getItem($categoryId);
				}
				else
				{
					$category = $mainCategory;
				}

				// Get sub-Categories deeper
				$categoriesModel->setState('filter.published', 1);
				$categoriesModel->setState('filter.lft', $category->lft);
				$categoriesModel->setState('filter.rgt', $category->rgt);
				$categoriesModel->setState('list.select', 'DISTINCT (c.id), c.title, c.parent_id, c.level');
				$categoriesModel->setState('list.ordering', 'c.title');
				$categoriesModel->setState('list.direction', 'asc');

				$query = $categoriesModel->getListQuery();

				$db->setQuery($query);

				$subCategories = $db->loadObjectList();

				if ($subCategories)
				{
					$options = array();
					$children = array();

					$options[] = JHTML::_('select.option', '', JText::_('JALL') . ' ' . $category->title);

					// Create tree list

					foreach ($subCategories as $subCategory)
					{
						$pt = $subCategory->parent_id;
						$list = (isset($children[$pt])) ? $children[$pt] : array();
						array_push($list, $subCategory);
						$children[$pt] = $list;
					}

					$treeCategories = JHTML::_('menu.treerecurse', $category->id, ' ', array(), $children, 9999, 0, 0);

					// Add tree list to options list

					foreach ($treeCategories as $node)
					{
						$options[] = JHTML::_('select.option', $node->id, $node->treename);
					}

					$attribs = ' class="chosen reditemFilterRelated" onChange="javascript:reditemFilterAjax();"';
					$filters = $app->input->get('filter_category', null, 'array');
					$value = '';

					if ($filters)
					{
						if (isset($filters[$category->id]))
						{
							$value = $filters[$category->id];
						}
					}

					$selectHTML = JHTML::_('select.genericlist', $options, 'filter_category[' . $category->id . ']', $attribs, 'value', 'text', $value, 'filter_related_' . $categoryId);

					$attribs = ' class="hidden" onChange="javascript:reditemFilterAjax();"';
					$selectHTML .= JHTML::_('select.genericlist', $options, '', $attribs, 'value', 'text', '', 'filter_related_' . $categoryId . '_tmp');

					$content = str_replace($match, $selectHTML, $content);
				}
			}
		}

		/*
		 * filter_ranges | <custom_field_id> | <number_of_range>;<minVal>:<maxVal>;<name_of_range> | <default_select>
		 *
		 * <custom_field_id> 	int 	Id of customfield
		 *
		 * <number_of_range> 	int 	Number of range (options)	Default: 4
		 * <minVal>				int 	Min value of range			Default: Get lowest value from custom field data
		 * <maxVal>				int 	Max value of range			Default: Get highest value from custom field data
		 * <name_of_range>		string 	Array of text 				Default: Generate by min-max value
		 *
		 * <default_select		string  Default selected option 	Default: null
		 */
		$preg = '/{filter_ranges[^}]*}/i';

		if (preg_match_all($preg, $content, $matches) > 0)
		{
			$matches = $matches[0];

			$fieldModel = RModel::getAdminInstance('Field', array('ignore_request' => true), 'com_reditem');
			$typeModel = RModel::getAdminInstance('Type', array('ignore_request' => true), 'com_reditem');
			$db = JFactory::getDBO();

			foreach ($matches as $match)
			{
				$tmpMatch = str_replace('{', '', $match);
				$tmpMatch = str_replace('}', '', $tmpMatch);

				$params = explode('|', $tmpMatch);

				$filterName = 'filter_ranges';
				$value = '';
				$filterIdTag = '';
				$customFieldId = 0;
				$options = array();

				$rangeCount = 4;
				$minValue = false;
				$maxValue = false;
				$rangeText = array();
				$defaultSelect = '';

				// Param: Customfield Id
				if (isset($params[1]))
				{
					$customFieldId = (int) $params[1];
				}

				$field = $fieldModel->getItem($customFieldId);

				$filterIdTag = $filterName . '_' . $customFieldId;

				$columnName = $field->fieldcode;

				$filterName .= '[' . $field->fieldcode . ']';

				// Get params for generate options
				if (isset($params[2]))
				{
					$optionParams = explode(';', $params[2]);

					// Number of ranges
					$rangeCount = (int) $optionParams[0];

					// MinValue & MaxValue
					if (isset($optionParams[1]))
					{
						$minMaxParam = explode(':', $optionParams[1]);

						$minValue = ($minMaxParam[0] != '') ? $minMaxParam[0] : false;

						$maxValue = (isset($minMaxParam[1])) ? $minMaxParam[1] : false;
					}

					// Text of options
					$rangeText = (isset($optionParams[2])) ? explode(',', $optionParams[2]) : array();
				}

				// If Min or Max value has not been set
				if (($minValue === false) || ($maxValue === false))
				{
					$type = $typeModel->getItem($field->type_id);
					$tableName = '#__reditem_types_' . $type->table_name;

					if ($minValue == false)
					{
						// Get Min value from custom field datas
						$query = $db->getQuery(true);
						$query->select('MIN(CAST(' . $db->quoteName($field->fieldcode) . ' AS UNSIGNED))')
							->from($db->quoteName($tableName))
							->where($db->quoteName($field->fieldcode) . ' <> ' . $db->quote(''));

						$db->setQuery($query, 0, 1);
						$minValue = $db->loadResult();
					}

					if ($maxValue == false)
					{
						// Get Max value from custom field datas
						$query = $db->getQuery(true);
						$query->select('MAX(CAST(' . $db->quoteName($field->fieldcode) . ' AS UNSIGNED))')
							->from($db->quoteName($tableName))
							->where($db->quoteName($field->fieldcode) . ' <> ' . $db->quote(''));

						$db->setQuery($query, 0, 1);
						$maxValue = (int) $db->loadResult();
					}
				}

				$minValue = (int) $minValue;
				$maxValue = (int) $maxValue;

				// Get default select option
				if (isset($params[3]))
				{
					$defaultSelect = $params[3];
				}

				// Generate options
				for ($i = 0; $i < $rangeCount; $i++)
				{
					$optionValue = '';
					$optionText = '';

					// Option value
					$range = ($maxValue - $minValue) / $rangeCount;
					$currentRangeMin = ($i * $range) + $minValue;
					$currentRangeMax = $currentRangeMin + $range;

					if ($currentRangeMin != $minValue)
					{
						$currentRangeMin++;
					}

					$optionValue = $currentRangeMin . '-' . $currentRangeMax;

					// Option text
					$optionText = (isset($rangeText[$i])) ? $rangeText[$i] : $currentRangeMin . ' - ' . $currentRangeMax;

					// Default select process
					if ($defaultSelect == $optionText)
					{
						$value = $optionValue;

						$js = '(function($){
							$(document).ready(function () {
								$("select#' . $filterIdTag . '").select2("val", "' . $optionValue . '");
								$("select#' . $filterIdTag . '").change();
							});
						})(jQuery);';

						JFactory::getDocument()->addScriptDeclaration($js);
					}

					$options[] = JHTML::_('select.option', $optionValue, $optionText);
				}

				$attribs = 'onChange="javascript:reditemFilterAjax();"';
				array_unshift($options, JHTML::_('select.option', '', JText::_('JALL') . ' ' . $field->name));
				$selectHTML = JHTML::_('select.genericlist', $options, $filterName, $attribs, 'value', 'text', $value, $filterIdTag);

				$content = str_replace($match, $selectHTML, $content);
			}
		}
	}

	/**
	 * Replace tag on content of template
	 *
	 * @param   string  &$content  Content template
	 * @param   array   $data      Item data array
	 *
	 * @return  void
	 */
	public static function tagReplaceItemCustomField(&$content, $data)
	{
		$doc = JFactory::getDocument();
		$imageGenerator = new ImageGenerator;

		// Replace custom fields title tag [#_text] and value tag [#_value]
		if (isset($data->template->tags))
		{
			$customFieldTags = $data->template->tags;
			$customFieldValues = $data->customfield_values;

			foreach ($customFieldTags As $tag)
			{
				$tagstr = '{' . $tag->fieldcode . '_text}';
				$content = str_replace($tagstr, $tag->name, $content);

				if ($tag->type == 'image')
				{
					$preg = '/{' . $tag->fieldcode . '_value[^}]*}/i';
					$value = $customFieldValues[$tag->fieldcode];
					$matches = array();

					if (preg_match_all($preg, $content, $matches) > 0)
					{
						$matches = $matches[0];

						foreach ($matches as $match)
						{
							$_tmp = explode('|', $match);

							if (isset($_tmp[1]))
							{
								$width = (int) $_tmp[1] . 'px';
							}
							else
							{
								$width = '';
							}

							if (isset($_tmp[2]))
							{
								$height = (int) $_tmp[2] . 'px';
							}
							else
							{
								$height = '';
							}

							$imgs = json_decode($value, true);
							$js = '';

							if (count($imgs) > 0 && !empty($imgs[0]))
							{
								$img = $imgs[0];

								if (($width) || ($height))
								{
									// Auto create thumbnail file
									$tmp = explode('/', $img);
									$fileName = array_pop($tmp);
									$imagePath = $imageGenerator->getImageLink($data->id, 'customfield', 0, $fileName, 'thumbnail', $width, $height, false, 'class="modal"');
									$replaceText = $imagePath;
								}
								else
								{
									$imagePath = JURI::base() . 'components/com_reditem/assets/images/customfield/' . $img;

									$replaceText = '<img class="modal" src="' . $imagePath . '"';

									if ($width)
									{
										$replaceText .= ' width="' . $width . '"';
									}

									if ($height)
									{
										$replaceText .= ' height="' . $height . '"';
									}

									$replaceText .= ' />';
								}

								$content = str_replace($match, $replaceText, $content);
							}
							else
							{
								$content = str_replace($match, '', $content);
							}
						}
					}
				}
				elseif ($tag->type == 'gallery')
				{
					$preg = '/{' . $tag->fieldcode . '_value[^}]*}/i';
					$value = $customFieldValues[$tag->fieldcode];
					$matches = array();

					if (preg_match_all($preg, $content, $matches) > 0)
					{
						$matches = $matches[0];
						$i = 0;

						foreach ($matches as $match)
						{
							$tmpMatch = str_replace('{', '', $match);
							$tmpMatch = str_replace('}', '', $tmpMatch);

							$params = explode('|', $tmpMatch);

							if (isset($params[1]))
							{
								$width = (int) $params[1];
							}
							else
							{
								$width = '';
							}

							if (isset($params[2]))
							{
								$height = (int) $params[2];
							}
							else
							{
								$height = '';
							}

							if (isset($params[3]))
							{
								$displayType = $params[3];
							}
							else
							{
								$displayType = '';
							}

							$imgs = json_decode($value, true);
							$js = '';

							if (count($imgs) > 0)
							{
								if ($displayType == 'slider')
								{
									$strData = '<div class="flexslider" id="flexslider_' . $data->id . '_' . $i . '">';
									$strData .= '<ul class="slides">';

									foreach ($imgs as $img)
									{
										if (!empty($img))
										{
											if (($width) || ($height))
											{
												$tmp = explode('/', $img);
												$fileName = array_pop($tmp);
												$imagePath = $imageGenerator->getImageLink($data->id, 'customfield', 0, $fileName, 'thumbnail', $width, $height, false);
											}
											else
											{
												$imagePath = JURI::base() . 'components/com_reditem/assets/images/customfield/' . $img;
												$imagePath = '<img src="' . $imagePath . '" />';
											}

											$strData .= '<li>' . $imagePath . '</li>';
										}
									}

									$strData .= '</ul>';
									$strData .= '</div>';

									$content = str_replace($match, $strData, $content);

									$sliderParams = array('animation' => 'slide', 'smoothHeight' => false);

									JHtml::_('rjquery.flexslider', '#flexslider_' . $data->id . '_' . $i, $sliderParams);
								}
								else
								{
									$_str = '';
									$first = true;

									foreach ($imgs as $img)
									{
										if (!empty($img))
										{
											$_src = JURI::base() . 'components/com_reditem/assets/images/customfield/' . $img;

											if ($first)
											{
												// Create thumbnail file for first image
												if (($width) || ($height))
												{
													$tmp = explode('/', $img);
													$fileName = array_pop($tmp);
													$imagePath = $imageGenerator->getImageLink($data->id, 'customfield', 0, $fileName, 'thumbnail', $width, $height, false);
												}
												else
												{
													$imagePath = JURI::base() . 'components/com_reditem/assets/images/customfield/' . $img;
													$imagePath = '<img src="' . $imagePath . '" width="' . $width . '" height="' . $height . '" />';
												}

												$_str .= '<a class="colorbox_group_' . $data->id . '_' . $i . '" href="' . $_src . '" />';
												$_str .= $imagePath;
												$_str .= '</a>';

												$first = false;
											}
											else
											{
												$_str .= '<a class="colorbox_group_' . $data->id . '_' . $i . ' hidden" href="' . $_src . '" />';
												$_str .= '</a>';
											}
										}
									}

									$content = str_replace($match, $_str, $content);

									$js .= 'jQuery(document).ready(function(){';
									$js .= 'jQuery(".colorbox_group_' . $data->id . '_' . $i . '").colorbox({rel:"colorbox_group_' . $data->id . '_' . $i . '"});';
									$js .= '});';
								}
							}
							else
							{
								// Replace tag by empty string
								$content = str_replace($match, '', $content);
							}

							$doc->addScriptDeclaration($js);

							$i++;
						}

						// Load color box
						$doc->addScript(JURI::base() . '/media/com_reditem/colorbox/jquery.colorbox-min.js');
						$doc->addStyleSheet(JURI::base() . '/media/com_reditem/colorbox/colorbox.css');
					}
				}
				elseif ($tag->type == 'youtube')
				{
					JHTML::_('behavior.modal');

					$preg = '/{' . $tag->fieldcode . '_value[^}]*}/i';
					$value = $customFieldValues[$tag->fieldcode];

					$matches = array();

					if (preg_match_all($preg, $content, $matches) > 0)
					{
						$matches = $matches[0];
						$i = 0;

						foreach ($matches as $match)
						{
							$_tmp = explode('|', $match);

							if (isset($_tmp[1]))
							{
								$width = (int) $_tmp[1];
							}
							else
							{
								$width = '400px';
							}

							if (isset($_tmp[2]))
							{
								$height = (int) $_tmp[2];
							}
							else
							{
								$height = '250px';
							}

							if ($value)
							{
								$string = '<a id="youtube_vid_' . $i . '" class="youtube modal" href="//www.youtube.com/embed/' . $value . '" style="width:' . $width . 'px;height:' . $height . 'px;" rel="{handler: \'iframe\', size: {x: 640, y: 360}}"></a>';
							}
							else
							{
								$string = '';
							}

							$content = str_replace($match, $string, $content);
							$i++;
						}
					}
				}
				elseif ($tag->type == 'number')
				{
					$typeParams = new JRegistry($tag->params);

					$decimalSepatator = $typeParams->get('number_decimal_sepatator', '.');
					$thousandSeparator = $typeParams->get('number_thousand_separator', ',');
					$numberDecimals = $typeParams->get('number_number_decimals', 2);

					$value = $customFieldValues[$tag->fieldcode];

					$string = number_format(floatval($value), $numberDecimals, $decimalSepatator, $thousandSeparator);
					$match = '{' . $tag->fieldcode . '_value}';
					$content = str_replace($match, $string, $content);
				}
				elseif ($tag->type == 'googlemaps')
				{
					$strData = '';
					$doc = JFactory::getDocument();

					$preg = '/{' . $tag->fieldcode . '_value}/i';
					$value = $customFieldValues[$tag->fieldcode];

					$matches = array();

					if (preg_match_all($preg, $content, $matches) > 0)
					{
						$matches = $matches[0];

						foreach ($matches as $match)
						{
							$js = '(function($){
								$(document).ready(function($){
									/*reditem_customfield_googlemaps_init(
										"reditem_customfield_googlemaps_' . $data->id . '_canvas",
										"' . $value . '",
										"<h3>' . $data->title . '</h3>"
									);*/
									reditem_customfield_googlemaps_init();
								});
							})(jQuery);';

							$doc->addScriptDeclaration($js);

							$strData = '<div class="reditem_custom_googlemaps">';
							$strData .= '<div id="reditem_customfield_googlemaps_' . $data->id . '_canvas" class="reditem_custom_googlemaps_canvas"></div>';
							$strData .= '<input type="hidden" id="mapid" value="reditem_customfield_googlemaps_' . $data->id . '_canvas" />';
							$strData .= '<input type="hidden" id="maplatlng" value="' . $value . '" />';
							$strData .= '<input type="hidden" id="mapinfor" value="<h3>' . $data->title . '</h3>" />';
							$strData .= '</div>';

							$content = str_replace($match, $strData, $content);
						}

						// Add Google Maps script
						$doc->addScript('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');
					}
				}
				elseif ($tag->type == 'date')
				{
					$tagstr = '{' . $tag->fieldcode . '_value}';
					$value = $customFieldValues[$tag->fieldcode];
					$strData = '';

					if ($value)
					{
						$dateValue = JFactory::getDate($value);
						$dateFormat = '%Y - %m - %d';

						if ($tag->options)
						{
							$dateFormat = $tag->options;
						}

						$strData = $dateValue->toFormat($tag->options);
					}

					$content = str_replace($tagstr, $strData, $content);
				}
				elseif ($tag->type == 'file')
				{
					$tagstr = '{' . $tag->fieldcode . '_value}';
					$value = $customFieldValues[$tag->fieldcode];
					$strData = '';

					if ($value)
					{
						$fileJSON = json_decode($value, true);
						$filePath = JURI::root() . '/media/com_reditem/customfield/files/' . $fileJSON['0'];
						$strData = '<a href="' . $filePath . '" target="_blank">' . JFile::getName($filePath) . '</a>';
					}

					$content = str_replace($tagstr, $strData, $content);
				}
				else
				{
					$tagstr = '{' . $tag->fieldcode . '_value}';
					$v = $customFieldValues[$tag->fieldcode];
					$content = str_replace($tagstr, $v, $content);
				}
			}
		}
	}

	/**
	 * Replace Category Filter tag on content of template
	 *
	 * @param   string  &$content  Content template
	 * @param   array   $data      Category data
	 *
	 * @return  void
	 */
	public static function tagReplaceCategoryFilter(&$content, $data)
	{
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('reditem_categories');

		if (strrpos($content, '{filter_subcatitemsavaiable}') !== false)
		{
			$options = array();
			$options[] = JHTML::_('select.option', '', JText::_('COM_REDITEM_TEMPLATE_TAG_SUB_CATEGORY_ITEMS_AVAIABLE_SELECT'));
			$options[] = JHTML::_('select.option', '1', JText::_('COM_REDITEM_TEMPLATE_TAG_SUB_CATEGORY_ITEMS_AVAIABLE_ONLY_ITEMS_AVAIABLE'));
			$options[] = JHTML::_('select.option', '0', JText::_('COM_REDITEM_TEMPLATE_TAG_SUB_CATEGORY_ITEMS_AVAIABLE_ALL'));

			$attribs = ' class="chosen" onChange="javascript:reditemCatExtraFilterAjax();"';

			$value = JFactory::getApplication()->input->get('filter_subcatitemsavaiable', '', 'int');

			$selectHTML = JHTML::_('select.genericlist', $options, 'filter_subcatitemsavaiable', $attribs, 'value', 'text', $value);

			$content = str_replace('{filter_subcatitemsavaiable}', $selectHTML, $content);
		}

		$dispatcher->trigger('onReplaceCategoryFilterExtrasFieldTag', array(&$content, $data));
	}

	/**
	 * Generates an HTML checkbox list. (libraries/joomla/html/html/select.php)
	 *
	 * @param   array    $data       An array of objects
	 * @param   string   $name       The value of the HTML name attribute
	 * @param   string   $attribs    Additional HTML attributes for the <select> tag
	 * @param   mixed    $optKey     The key that is selected
	 * @param   string   $optText    The name of the object variable for the option value
	 * @param   string   $selected   The name of the object variable for the option text
	 * @param   boolean  $idtag      Value of the field id or null by default
	 * @param   boolean  $translate  True if options will be translated
	 *
	 * @return  string HTML for the select list
	 *
	 * @since  11.1
	 */
	public static function checkboxlist($data, $name, $attribs = null, $optKey = 'value', $optText = 'text', $selected = null, $idtag = false, $translate = false)
	{
		reset($data);
		$html = '';

		if (is_array($attribs))
		{
			$attribs = JArrayHelper::toString($attribs);
		}

		$id_text = $idtag ? $idtag : $name;

		foreach ($data as $obj)
		{
			$k = $obj->$optKey;
			$t = $translate ? JText::_($obj->$optText) : $obj->$optText;
			$id = (isset($obj->id) ? $obj->id : null);

			$extra = '';
			$extra .= $id ? ' id="' . $obj->id . '"' : '';

			if (is_array($selected))
			{
				foreach ($selected as $val)
				{
					$k2 = is_object($val) ? $val->$optKey : $val;

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

			$html .= "\n\t" . '<input type="checkbox" name="' . $name . '"' . ' id="' . $id_text . $k . '" value="' . $k . '"' . ' ' . $extra . ' '
				. $attribs . '/>' . "\n\t" . '<label for="' . $id_text . $k . '"' . ' id="' . $id_text . $k . '-lbl" class="radiobtn">' . $t
				. '</label>';
		}

		$html .= "\n";

		return $html;
	}
}
