<?php
/**
* @title			FAQ Book Pro
* @version   		3.x
* @copyright   		Copyright (C) 2011-2013 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @author email   	info@minitek.gr
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die;

class FAQBookProHelper
{
	public static $extension = 'com_faqbookpro';

	public static function addSubmenu($vName)
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_FAQBOOKPRO_SUBMENU_DASHBOARD'),
			'index.php?option=com_faqbookpro&view=dashboard',
			$vName == 'dashboard'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_FAQBOOKPRO_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_faqbookpro',
			$vName == 'categories'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_FAQBOOKPRO_SUBMENU_ARTICLES'),
			'index.php?option=com_faqbookpro&view=articles',
			$vName == 'articles'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_FAQBOOKPRO_SUBMENU_VOTES'),
			'index.php?option=com_faqbookpro&view=votes',
			$vName == 'votes'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_FAQBOOKPRO_SUBMENU_ABOUT'),
			'index.php?option=com_faqbookpro&view=about',
			$vName == 'about'
		);
	}

	public static function getActions($categoryId = 0, $articleId = 0)
	{
		// Reverted a change for version 2.5.6
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($articleId) && empty($categoryId))
		{
			$assetName = 'com_faqbookpro';
		}
		elseif (empty($articleId))
		{
			$assetName = 'com_faqbookpro.category.'.(int) $categoryId;
		}
		else
		{
			$assetName = 'com_faqbookpro.article.'.(int) $articleId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}

	public static function filterText($text)
	{
		JLog::add('FAQBookProHelper::filterText() is deprecated. Use JComponentHelper::filterText() instead.', JLog::WARNING, 'deprecated');

		return JComponentHelper::filterText($text);
	}
}
