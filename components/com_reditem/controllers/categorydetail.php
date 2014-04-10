<?php
/**
 * @package     RedITEM.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.controller');

JLoader::import('redcore.bootstrap');

/**
 * Category Controller.
 *
 * @package     RedITEM.Frontend
 * @subpackage  Controller
 * @since       2.0
 */
class ReditemControllerCategorydetail extends JControllerLegacy
{
	/**
	 * Ajax filter items
	 *
	 * @return void
	 */
	public function ajaxFilter()
	{
		$model = RModel::getFrontInstance('Categorydetail', array('ignore_request' => true), 'com_reditem');

		$mainCategory = $model->getData();

		$mainContent = $mainCategory->template->content;

		// Items array
		if ((strpos($mainContent, '{items_loop_start}') !== false) && (strpos($mainContent, '{items_loop_end}') !== false))
		{
			$tempContent = explode('{items_loop_start}', $mainContent);
			$preContent = (count($tempContent) > 1) ? $tempContent[0] : '';

			$tempContent = $tempContent[count($tempContent) - 1];
			$tempContent = explode('{items_loop_end}', $tempContent);
			$subTemplate = $tempContent[0];

			$postContent = (count($tempContent) > 1) ? $tempContent[count($tempContent) - 1] : '';

			$subContent = '';

			if ($mainCategory->items)
			{
				// Has sub categories
				foreach ($mainCategory->items as $item)
				{
					$subContentSub = $subTemplate;

					ReditemTagsHelper::tagReplaceItem($subContentSub, $item, $mainCategory->id);

					ReditemTagsHelper::tagReplaceItemCustomField($subContentSub, $item);

					$subContent .= $subContentSub;
				}
			}

			$return = array();
			$return['category'] = $subContent;
			$return['pagination'] = $mainCategory->pagination;

			// Related Categories
			$filterCategories = JFactory::getApplication()->input->get('filter_category', null, 'array');
			$relatedCategories = array();

			if ($filterCategories)
			{
				if (array_filter($filterCategories))
				{
					foreach ($filterCategories as $filterCatId => $catId)
					{
						if (!isset($relatedCategories[$filterCatId]))
						{
							$relatedCategories[$filterCatId] = array();
						}

						if ($catId)
						{
							$tmpCategories = ReditemHelper::getRelatedCategories($catId);

							foreach ($tmpCategories as $tmpCategory)
							{
								$tmpParentId = $tmpCategory->parent_id;

								if (!isset($relatedCategories[$tmpParentId]))
								{
									$relatedCategories[$tmpParentId] = array();
								}

								$relatedCategories[$tmpParentId][] = $tmpCategory;
							}
						}
					}

					// Make selected if this filter has been choose already
					foreach ($filterCategories as $filterCatId => $catId)
					{
						if (isset($relatedCategories[$filterCatId]))
						{
							foreach ($relatedCategories[$filterCatId] as &$relatedCategory)
							{
								$relatedCategory->filter = false;

								if ($relatedCategory->id == $catId)
								{
									$relatedCategory->filter = true;
								}
							}
						}
					}

					$return['relatedCategories'] = $relatedCategories;
				}
			}

			echo json_encode($return, JSON_FORCE_OBJECT);
		}

		exit;
	}

	/**
	 * Ajax filter sub-categories
	 *
	 * @return void
	 */
	public function ajaxCatExtraFilter()
	{
		$return = array();

		$model = RModel::getFrontInstance('Categorydetail', array('ignore_request' => true), 'com_reditem');

		$mainCategory = $model->getData();

		$mainContent = $mainCategory->template->content;

		// Sub-Categories array
		if ((strpos($mainContent, '{sub_category_start}') !== false) && (strpos($mainContent, '{sub_category_end}') !== false))
		{
			$tempContent = explode('{sub_category_start}', $mainContent);
			$preContent = (count($tempContent) > 1) ? $tempContent[0] : '';

			$tempContent = $tempContent[count($tempContent) - 1];
			$tempContent = explode('{sub_category_end}', $tempContent);
			$subTemplate = $tempContent[0];

			$postContent = (count($tempContent) > 1) ? $tempContent[count($tempContent) - 1] : '';

			$subContent = '';

			if ($mainCategory->sub_categories)
			{
				// Has sub categories
				foreach ($mainCategory->sub_categories as $subCategory)
				{
					$subContentSub = $subTemplate;

					ReditemTagsHelper::tagReplaceCategory($subContentSub, $subCategory, 'sub_', $mainCategory->id);

					$subContent .= $subContentSub;
				}
			}

			$return['content'] = $subContent;
			$return['pagination'] = $mainCategory->sub_categories_pagination;
		}

		echo json_encode($return, JSON_FORCE_OBJECT);

		exit;
	}
}
