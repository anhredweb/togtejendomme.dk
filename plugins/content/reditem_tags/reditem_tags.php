<?php
/**
 * @package     RedITEM
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
jimport('redcore.bootstrap');
require_once JPATH_SITE . '/components/com_reditem/helpers/tags.php';

/**
 * Plugins RedITEM Tags
 *
 * @since  2.0
 */
class PlgContentreditem_Tags extends JPlugin
{
	/**
	 * Method run on Content Prepare trigger
	 *
	 * @param   string  $context  Context
	 * @param   array   &$row     Data
	 * @param   array   &$params  Plugins parameters
	 * @param   int     $page     Page number
	 *
	 * @return  boolean
	 */
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		$this->tagReditemItem($row);

		$this->tagReditemCategory($row);

		$this->tagReditemField($row);

		return true;
	}

	/**
	 * Replace tag for items {reditem_item|$itemID|$fieldName}
	 *
	 * @param   array  &$row  Reference of row data
	 *
	 * @return  void
	 */
	public function tagReditemItem(&$row)
	{
		$matches = array();

		if (preg_match_all('/{reditem_item[^}]*}/i', $row->text, $matches) > 0)
		{
			RHelperAsset::load('reditem.js', 'com_reditem');

			$matches = $matches[0];

			foreach ($matches as $match)
			{
				$tagMatch = str_replace('{', '', str_replace('}', '', $match));
				$tagParams = explode(':', $tagMatch);
				$itemId = 0;
				$itemField = '';
				$itemParams = '';

				if (isset($tagParams[1]))
				{
					// Have item ID
					$itemId = (int) $tagParams[1];
				}

				if (isset($tagParams[2]))
				{
					$itemField = $tagParams[2];
				}

				if (isset($tagParams[3]))
				{
					$itemParams = $tagParams[3];
				}

				if ($itemId)
				{
					$itemModel = RModel::getAdminInstance('Item', array('ignore_request' => true), 'com_reditem');
					$item = $itemModel->getItem($itemId);

					if ($item)
					{
						// Have item data
						if ($itemParams)
						{
							$tmpTag = '{item_' . $itemField . '|' . $itemParams . '}';
						}
						else
						{
							$tmpTag = '{item_' . $itemField . '}';
						}

						ReditemTagsHelper::tagReplaceItem($tmpTag, $item);
						$row->text = str_replace($match, $tmpTag, $row->text);
					}
					else
					{
						$row->text = str_replace($match, '', $row->text);
					}
				}
			}
		}
	}

	/**
	 * Replace tag for categories {reditem_category|$categoryId|$fieldName}
	 *
	 * @param   array  &$row  Reference of row data
	 *
	 * @return  void
	 */
	public function tagReditemCategory(&$row)
	{
		$matches = array();

		if (preg_match_all('/{reditem_category[^}]*}/i', $row->text, $matches) > 0)
		{
			RHelperAsset::load('reditem.js', 'com_reditem');

			$matches = $matches[0];

			foreach ($matches as $match)
			{
				$tagMatch = str_replace('{', '', str_replace('}', '', $match));
				$tagParams = explode(':', $tagMatch);
				$itemId = 0;
				$itemField = '';
				$itemParams = '';

				if (isset($tagParams[1]))
				{
					// Have item ID
					$itemId = (int) $tagParams[1];
				}

				if (isset($tagParams[2]))
				{
					$itemField = $tagParams[2];
				}

				if (isset($tagParams[3]))
				{
					$itemParams = $tagParams[3];
				}

				if ($itemId)
				{
					$categoryModel = RModel::getAdminInstance('Category', array('ignore_request' => true), 'com_reditem');
					$item = $categoryModel->getItem($itemId);

					if ($item)
					{
						// Have item data

						if ($itemParams)
						{
							$tmpTag = '{category_' . $itemField . '|' . $itemParams . '}';
						}
						else
						{
							$tmpTag = '{category_' . $itemField . '}';
						}

						ReditemTagsHelper::tagReplaceCategory($tmpTag, $item);
						$row->text = str_replace($match, $tmpTag, $row->text);
					}
					else
					{
						$row->text = str_replace($match, '', $row->text);
					}
				}
			}
		}
	}

	/**
	 * Replace tag for item's customfields {reditem_field|$itemID|$fieldName}
	 *
	 * @param   array  &$row  Reference of row data
	 *
	 * @return  void
	 */
	public function tagReditemField(&$row)
	{
		$matches = array();

		if (preg_match_all('/{reditem_field[^}]*}/i', $row->text, $matches) > 0)
		{
			RHelperAsset::load('reditem.js', 'com_reditem');

			$matches = $matches[0];

			foreach ($matches as $match)
			{
				$tagMatch = str_replace('{', '', str_replace('}', '', $match));
				$tagParams = explode(':', $tagMatch);
				$itemId = 0;
				$itemField = '';
				$itemParams = '';

				if (isset($tagParams[1]))
				{
					// Have item ID
					$itemId = (int) $tagParams[1];
				}

				if (isset($tagParams[2]))
				{
					$itemField = $tagParams[2];
				}

				if (isset($tagParams[3]))
				{
					$itemParams = $tagParams[3];
				}

				if ($itemId)
				{
					$itemModel = RModel::getAdminInstance('Item', array('ignore_request' => true), 'com_reditem');
					$item = $itemModel->getItem($itemId);

					if ($item)
					{
						// Have item data
						$templateModel = RModel::getAdminInstance('Template', array('ignore_request' => true), 'com_reditem');
						$item->template = $templateModel->getItem($item->template_id);

						if ($itemParams)
						{
							$tmpTag = '{' . $itemField . '_value|' . $itemParams . '}';
						}
						else
						{
							$tmpTag = '{' . $itemField . '_value}';
						}

						ReditemTagsHelper::tagReplaceItemCustomField($tmpTag, $item);
						$row->text = str_replace($match, $tmpTag, $row->text);
					}
					else
					{
						$row->text = str_replace($match, '', $row->text);
					}
				}
			}
		}
	}
}
