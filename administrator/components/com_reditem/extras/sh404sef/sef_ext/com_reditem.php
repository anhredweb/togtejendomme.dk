<?php
/**
 * @package     RedITEM.sh404
 * @subpackage  sh404sef
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('redcore.bootstrap');
JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_reditem/tables');

// ------------------  standard plugin initialize function - don't change ---------------------------
global $sh_LANG, $shGETVars;
$sefConfig = & Sh404sefFactory::getConfig();
$shLangName = '';
$shLangIso = '';
$title = array();
$shItemidString = '';
$dosef = shInitializePlugin($lang, $shLangName, $shLangIso, $option);

if (!$dosef)
	return;

// ------------------  standard plugin initialize function - don't change ---------------------------

$view = isset($view) ? $view : null;
$slugsModel = Sh404sefModelSlugs::getInstance();

switch ($view)
{
	case 'categorydetail':
		if (!empty($id))
		{
			try
			{
				$rows = ShlDbHelper::selectObjectList('#__reditem_categories', array('title', 'id', 'parent_id'), array( 'id' => $id));

				/*if (($rows[0]->parent_id) && ($rows[0]->parent_id > 1))
				{
					$categorytable = JTable::getInstance('Category', 'ReditemTable');

					$categories = $categorytable->getPath($rows[0]->parent_id);

					if (count($categories) > 0)
					{
						foreach ($categories as $i => $category)
						{
							if ($category->id > 1)
							{
								$title[] = $category->title;
							}
						}
					}
				}*/
			}
			catch (Exception $e)
			{
				ShlSystem_Log::error('sh404sef', '%s::%s::%d: %s', __CLASS__, __METHOD__, __LINE__, $e->getMessage());
				JError::raiseError(500, $e->getMessage());
			}

			if (@count($rows) > 0)
			{
				if (!empty($rows[0]->title))
				{
					$title[] = $rows[0]->title;
				}
			}
		}
		else
		{
			$title[] = '/';
		}

		if (isset($shGETVars['com_reditem_categorydetail_categories_limitstart']))
		{
			$limitstart = $shGETVars['com_reditem_categorydetail_categories_limitstart'];
			shRemoveFromGETVarsList('com_reditem_categorydetail_categories_limitstart');

			$app = JFactory::getApplication();

			$slimit = $app->getUserState('com_reditem.sub_category_pagination.limit', 0);

			if ($slimit)
			{
				$limit = $slimit;
			}
		}

		if (isset($shGETVars['com_reditem_categorydetail_items_limitstart']))
		{
			$limitstart = $shGETVars['com_reditem_categorydetail_items_limitstart'];
			shRemoveFromGETVarsList('com_reditem_categorydetail_items_limitstart');

			$app = JFactory::getApplication();

			$slimit = $app->getUserState('com_reditem.items_pagination.limit', 0);

			if ($slimit)
			{
				$limit = $slimit;
			}
		}

	break;

	case 'itemdetail':
		if (!empty($id))
		{
			$db = JFactory::getDBO();

			// Get category of item
			try
			{
				$query = $db->getQuery(true);
				$query->select($db->quoteName('category_id'));
				$query->from($db->quoteName('#__reditem_item_category_xref'));
				$query->where($db->quoteName('item_id') . ' = ' . $db->quote($id));
				$query->order('category_id ASC');

				$db->setQuery($query, 0, 1);

				$categoryId = $db->loadResult();

				if ($categoryId)
				{
					$categoryRow = ShlDbHelper::selectObjectList('#__reditem_categories', array('title', 'id', 'parent_id'), array('id' => $categoryId));

					// Create structure of category
					if (($categoryRow[0]->parent_id) && ($categoryRow[0]->parent_id > 1))
					{
						try
						{
							$categorytable = JTable::getInstance('Category', 'ReditemTable');

							$categories = $categorytable->getPath($categoryRow[0]->parent_id);

							if (count($categories) > 0)
							{
								foreach ($categories as $i => $category)
								{
									if ($category->id > 1)
									{
										$title[] = $category->title;
									}
								}
							}
						}
						catch (Exception $e)
						{
						}
					}

					if (@count($categoryRow[0]) > 0)
					{
						if (!empty($categoryRow[0]->title))
						{
							$title[] = $categoryRow[0]->title;
						}
					}
				}
			}
			catch (Exception $e)
			{
			}

			// Item title process
			try
			{
				$rows = ShlDbHelper::selectObjectList('#__reditem_items', array( 'title', 'id'), array( 'id' => $id));
			}
			catch (Exception $e)
			{
				ShlSystem_Log::error('sh404sef', '%s::%s::%d: %s', __CLASS__, __METHOD__, __LINE__, $e->getMessage());
				JError::raiseError(500, $e->getMessage());
			}

			if (@count($rows) > 0)
			{
				if (!empty($rows[0]->title))
				{
					$title[] = $rows[0]->title;
				}
			}
		}
		else
		{
			$title[] = '/';
		}

	break;
}

shRemoveFromGETVarsList('option');

if (!empty($Itemid))
	shRemoveFromGETVarsList('Itemid');

shRemoveFromGETVarsList('lang');

if (isset($cid))
	shRemoveFromGETVarsList('cid');

if (isset($id))
	shRemoveFromGETVarsList('id');

if (!empty($view))
	shRemoveFromGETVarsList('view');

if (isset($templateId))
	shRemoveFromGETVarsList('templateId');

// ------------------  standard plugin finalize function - don't change ---------------------------

if ($dosef)
{
	$string = shFinalizePlugin($string, $title, $shAppendString, $shItemidString, (isset($limit) ? $limit : null), (isset($limitstart) ? $limitstart : null), (isset($shLangName) ? $shLangName : null));
}

// ------------------  standard plugin finalize function - don't change ---------------------------
