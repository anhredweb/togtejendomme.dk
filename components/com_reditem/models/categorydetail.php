<?php
/**
 * @package     RedITEM.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redcore.bootstrap');

/**
 * Category detail model
 *
 * @package     RedITEM.Frontend
 * @subpackage  Model
 * @since       2.0
 */
class ReditemModelCategoryDetail extends RModel
{
	/**
	 * Get data of item
	 *
	 * @return  boolean/array
	 */
	public function getData()
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		$categoryModel = RModel::getAdminInstance('Category', array('ignore_request' => true), 'com_reditem');
		$id = $input->getInt('id', 0);
		$params = $app->getParams();
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('reditem_categories');

		if ($id)
		{
			$category = $categoryModel->getItem($id);

			if ($category)
			{
				// Assigned template
				$templatemodel = RModel::getAdminInstance('Template', array('ignore_request' => true));

				// If menu set a template, get this template Id
				$templateId = $input->getInt('templateId', 0);

				if (!$templateId)
				{
					$templateId = $category->template_id;
				}

				$category->template = $templatemodel->getItem($templateId);

				// Sub categories
				if ((strrpos($category->template->content, '{sub_featured_category_start}') !== false)
					|| (strrpos($category->template->content, '{sub_category_start}') !== false))
				{
					$subCatOrdering = 'c.' . $params->get('subcat_ordering');
					$subCatDestination = $params->get('subcat_destination');

					$categoriesModel = RModel::getAdminInstance('Categories', array('ignore_request' => true));

					$limitstart = $input->get($categoriesModel->getPagination()->prefix . 'limitstart', 0);
					$limit = 0;
					$paginationTag = '';

					// Get pagination limit
					if (preg_match('/{sub_category_pagination[^}]*}/i', $category->template->content, $matches) > 0)
					{
						// Only use first pagination tag
						$match = $matches[0];
						$tmp = explode('|', $match);

						if (isset($tmp[1]))
						{
							// Have limit number
							$limit = (int) $tmp[1];
						}

						$paginationTag = $match;
					}

					$app->setUserState('com_reditem.sub_category_pagination.limit', $limit);

					$categoriesModel->setState('filter.parentid', $category->id);

					$filterSubCatItemsAvaiable = $input->get('filter_subcatitemsavaiable', '');

					if ($filterSubCatItemsAvaiable == '0')
					{
						$categoriesModel->setState('filter.published', '');
					}
					else
					{
						$categoriesModel->setState('filter.published', 1);
					}

					$categoriesModel->setState('list.ordering', $subCatOrdering);
					$categoriesModel->setState('list.direction', $subCatDestination);
					$categoriesModel->setState('list.limit', $limit);
					$categoriesModel->setState('list.start', $limitstart);

					$filterCategoriesIds = array();

					// Get Filter Ids from Trigger
					$filterCategoriesIdsFromTrigger = $dispatcher->trigger('onFilterCategoryIds');

					if (isset($filterCategoriesIdsFromTrigger[0]) && is_array($filterCategoriesIdsFromTrigger[0]))
					{
						$filterCategoriesIds = array_merge($filterCategoriesIds, $filterCategoriesIdsFromTrigger[0]);
					}

					// Get Filter from filter Sub-Categories Items Avaiable
					if ($filterSubCatItemsAvaiable == '1')
					{
						// Only show sub-categories which have at least 1 items "published" inside
						$tmpCategoriesModel = RModel::getAdminInstance('Categories', array('ignore_request' => true));
						$tmpCategoriesModel->setState('list.select', 'DISTINCT (c.id)');
						$tmpCategoriesModel->setState('filter.lft', $category->lft + 1);
						$tmpCategoriesModel->setState('filter.rgt', $category->rgt - 1);
						$tmpCategoriesModel->setState('filter.published', 1);
						$tmpAllSubCategories = $tmpCategoriesModel->getItems();
						$tmpAllSubCategoriesArray = array();

						if ($tmpAllSubCategories)
						{
							// Convert to array
							foreach ($tmpAllSubCategories as $key => $value)
							{
								$tmpAllSubCategoriesArray[] = $value->id;
							}

							$tmpItemsModel = RModel::getAdminInstance('Items', array('ignore_request' => true), 'com_reditem');
							$tmpItemsModel->setState('list.select', 'DISTINCT (x.category_id)');
							$tmpItemsModel->setState('filter.catid', $tmpAllSubCategoriesArray);
							$tmpItemsModel->setState('filter.published', 1);

							$tmpAvaiableCategories = $tmpItemsModel->getItems();

							if ($tmpAvaiableCategories)
							{
								$tmpAvaiableCategoriesArray = array();

								// Convert to array
								foreach ($tmpAvaiableCategories as $key => $value)
								{
									$tmpAvaiableCategoriesArray[] = $value->category_id;
								}

								if (empty($filterCategoriesIds))
								{
									$filterCategoriesIds = array_merge($filterCategoriesIds, $tmpAvaiableCategoriesArray);
								}
								else
								{
									$filterCategoriesIds = array_intersect($filterCategoriesIds, $tmpAvaiableCategoriesArray);
								}

								// Make sure return empty result if condition has been setted
								if (empty($filterCategoriesIds))
								{
									$filterCategoriesIds = array(-1);
								}
							}
						}
					}

					if (!empty($filterCategoriesIds))
					{
						$categoriesModel->setState('filter.ids', $filterCategoriesIds);
					}

					$category->sub_categories = $categoriesModel->getItems();

					// Replace pagination data for {items_pagination} tag
					$pagination = '<div class="pagination" id="reditemCategoriesPagination">' . $categoriesModel->getPagination()->getPagesLinks() . '</div>';
					$category->template->content = str_replace($paginationTag, $pagination, $category->template->content);

					$category->sub_categories_pagination = $categoriesModel->getPagination()->getPagesLinks();

					$categoriesModel->setState('list.ordering', 'c.lft');
					$categoriesModel->setState('list.direction', 'asc');
					$categoriesModel->setState('list.limit', 0);
					$categoriesModel->setState('list.start', 0);
					$categoriesModel->setState('filter.featured', 1);
					$category->sub_categories_featured = $categoriesModel->getItems();
				}

				// Items
				if (strrpos($category->template->content, '{items_loop_start}') !== false)
				{
					// Has tag {items_loop_start}
					$itemsOrdering = 'i.' . $params->get('items_ordering');
					$itemsDestination = $params->get('items_destination');

					$itemsModel = RModel::getAdminInstance('Items', array('ignore_request' => true), 'com_reditem');

					$limitstart = $input->get($itemsModel->getPagination()->prefix . 'limitstart', 0);
					$limit = 0;
					$paginationTag = '';

					// Get pagination limit
					if (preg_match('/{items_pagination[^}]*}/i', $category->template->content, $matches) > 0)
					{
						// Only use first pagination tag
						$match = $matches[0];
						$tmp = explode('|', $match);

						if (isset($tmp[1]))
						{
							// Have limit number
							$limit = (int) $tmp[1];
						}

						$paginationTag = $match;
					}

					$app->setUserState('com_reditem.items_pagination.limit', $limit);

					$itemsModel->setState('filter.catid', $category->id);
					$itemsModel->setState('filter.published', 1);
					$itemsModel->setState('list.select', 'DISTINCT (i.id)');
					$itemsModel->setState('list.ordering', $itemsOrdering);
					$itemsModel->setState('list.direction', $itemsDestination);
					$itemsModel->setState('list.limit', $limit);
					$itemsModel->setState('list.start', $limitstart);

					// Check if tag {include_sub_category_items} has exists
					$pos = strrpos($category->template->content, '{include_sub_category_items}');

					if ($pos !== false)
					{
						// If current category has sub categories
						$subCategories = ReditemHelper::getSubCategories($id);

						$itemsModel->setState('filter.catid', $subCategories);
						$itemsModel->setState('filter.item_ids', $this->getItemFilter());

						$category->template->content = str_replace('{include_sub_category_items}', '', $category->template->content);
					}

					$typeModel = RModel::getAdminInstance('Type', array('ignore_request' => true), 'com_reditem');
					$type = $typeModel->getItem($category->type_id);

					/*
					 * Add filter by custom field value
					 */
					$filterFields = $input->get('filter_customfield', null, 'array');

					if ($filterFields)
					{
						$itemsModel->setState('filter.cfTable', '#__reditem_types_' . $type->table_name);

						// Remove unused filter custom value

						foreach ($filterFields as $field => $value)
						{
							if (empty($value))
							{
								unset($filterFields[$field]);
							}
						}

						$itemsModel->setState('filter.cfSearch', json_encode($filterFields));
					}

					/*
					 * Add filter by custom field with ranges value
					 */
					$filterFieldsRange = $input->get('filter_ranges', null, 'array');

					if ($filterFieldsRange)
					{
						$itemsModel->setState('filter.cfTableRanges', '#__reditem_types_' . $type->table_name);

						foreach ($filterFieldsRange as $field => $value)
						{
							if (empty($value))
							{
								unset($filterFieldsRange[$field]);
							}
						}

						$itemsModel->setState('filter.cfSearchRanges', json_encode($filterFieldsRange));
					}

					$items = $itemsModel->getItems();
					$items = $itemsModel->getPrepareItems($items);

					// Replace pagination data for {items_pagination} tag
					$pagination = '<div class="pagination" id="reditemItemsPagination">' . $itemsModel->getPagination()->getPagesLinks() . '</div>';
					$category->template->content = str_replace($paginationTag, $pagination, $category->template->content);

					$category->items = array();

					$category->pagination = $itemsModel->getPagination()->getPagesLinks();

					$itemmodel = RModel::getAdminInstance('Item', array('ignore_request' => true));

					if ($items)
					{
						foreach ($items as $item)
						{
							$_item = $itemmodel->getItem($item->id);
							$_item->template = $templatemodel->getItem($_item->template_id);
							$category->items[] = $_item;
						}
					}
				}

				// Related Categories
				if ((!empty($category->related_categories)) && (is_array($category->related_categories)))
				{
					$relatedCategories = array();

					foreach ($category->related_categories as $relatedCategoryID)
					{
						$relatedCategories[] = $categoryModel->getItem($relatedCategoryID);
					}

					$category->relatedCategories = $relatedCategories;
				}

				return $category;
			}
		}

		return false;
	}

	/**
	 * Get items data from filter
	 *
	 * @return  array
	 */
	private function getItemFilter()
	{
		$input = JFactory::getApplication()->input;

		/*Array ( [category] => Array ( [0] => 40 [1] => ) ) */
		$filters['category'] = $input->get('filter_category', null, 'array');

		$db = JFactory::getDBO();

		if (isset($filters['category']))
		{
			$query = $db->getQuery(true);
			$index = 0;

			foreach ($filters['category'] as $categoryId)
			{
				$categories = array();

				if (is_array($categoryId))
				{
					foreach ($categoryId as $tmpCatId)
					{
						$tmpCategories = ReditemHelper::getSubCategories($tmpCatId);
						$categories = array_merge($categories, $tmpCategories);
					}
				}
				else
				{
					$categories = ReditemHelper::getSubCategories($categoryId);
				}

				if ($index == 0)
				{
					$query->select('DISTINCT (x.item_id)')
						->from($db->quoteName('#__reditem_item_category_xref', 'x'))
						->where($db->quoteName('x.category_id') . ' IN (' . implode(',', $categories) . ')');
				}
				else
				{
					$tb = $db->quoteName('#__reditem_item_category_xref', 'x' . $index);

					$query->innerjoin($tb . ' ON ' . $db->quoteName('x.item_id') . ' = ' . $db->quoteName('x' . $index . '.item_id'))
						->where($db->quoteName('x' . $index . '.category_id') . ' IN (' . implode(',', $categories) . ')');
				}

				$index++;
			}

			$db->setQuery($query);

			$result = $db->loadResultArray();

			if ($result)
			{
				return $result;
			}

			return array(-1);
		}

		return array();
	}
}
