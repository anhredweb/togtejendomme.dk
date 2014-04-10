<?php
/**
 * @package     RedITEM.Frontend
 * @subpackage  mod_reditem_categories
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('redcore.bootstrap');

require_once JPATH_SITE . '/components/com_reditem/helpers/tags.php';
require_once JPATH_SITE . '/components/com_reditem/helpers/reditem.php';

/**
 * Categories helper
 *
 * @since  1.0
 */
class ModredITEMCategoriesGmapHelper
{
	/**
	 * Get list of categories
	 *
	 * @param   array  &$params  [description]
	 *
	 * @return  array
	 */
	public static function getList(&$params)
	{
		$imageGenerator = new ImageGenerator;

		$app = JFactory::getApplication();
		$helper = new ReditemHelper;
		$categoryId = $params->get('parent', 0);
		$inforTemplate = $params->get('inforbox', '');

		// Get Admin model
		$categoryModel = RModel::getAdminInstance('Category', array('ignore_request' => true), 'com_reditem');
		$categoriesModel = RModel::getAdminInstance('Categories', array('ignore_request' => true), 'com_reditem');

		// Get subCategories of current category
		$category = $categoryModel->getItem($categoryId);

		$categoriesModel->setState('filter.published', 1);
		$categoriesModel->setState('filter.lft', $category->lft + 1);
		$categoriesModel->setState('filter.rgt', $category->rgt - 1);
		$selects = array('DISTINCT (c.id)', 'c.title', 'c.alias', 'c.parent_id', 'c.category_image', 'c.type_id', 'c.params');
		$categoriesModel->setState('list.select', $selects);

		$query = $categoriesModel->getListQuery();

		$db = JFactory::getDBO();

		$db->setQuery($query);

		$subCategories = $db->loadObjectList();

		if ($subCategories)
		{
			foreach ($subCategories as &$category)
			{
				$categoryItemId = $helper->getCategoryMenuItem($category->id);

				if (!$categoryItemId)
				{
					$categoryItemId = $app->input->get('Itemid', 0);
				}

				// Make category link
				$categoryLink = 'index.php?option=com_reditem&amp;view=categorydetail&amp;id=' . $category->id;
				$categoryLink .= '&amp;cid=' . $categoryId;
				$categoryLink .= '&amp;Itemid=' . $categoryItemId;

				$category->link = JRoute::_($categoryLink);

				// Make inforbox
				$categoryInforbox = $inforTemplate;
				ReditemTagsHelper::tagReplaceCategory($categoryInforbox, $category);
				$category->inforbox = json_encode($categoryInforbox);

				$category->filterCategory = true;
			}

			return $subCategories;
		}

		return false;
	}
}
