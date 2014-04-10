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
defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldCategoryList extends JFormFieldList
{
	public $type = 'CategoryList';

	protected function getOptions()
	{
		// Defining the user access
		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());
		
		$db = JFactory::getDBO();
			
		$params = FaqBookProHelperUtilities::getParams('com_faqbookpro');
		$index_root = $params->get('index_root');
		
		if ($params->get('allow_select_category')) {	
			
			$query = 'SELECT * FROM '. $db->quoteName( '#__categories' );
			$query .= ' WHERE ' . $db->quoteName( 'extension' ) . ' = '. $db->quote('com_faqbookpro').' ';
			$query .= ' AND ' . $db->quoteName( 'published' ) . ' = '. $db->quote('1');
			$query .= ' AND ' . $db->quoteName( 'access' ) . ' IN ('.$groups.') ';
			$query .= ' ORDER BY '. $db->quote('parent_id'). ' ';				
			$db->setQuery($query);
			$mitems = $db->loadObjectList();
			$children = array();
			
			if ($mitems)
			{
				foreach ($mitems as $v)
				{
					$v->title = $v->title;
					$v->parent_id = $v->parent_id;
					
					$pt = $v->parent_id;
					$list = @$children[$pt] ? $children[$pt] : array();
					array_push($list, $v);
					$children[$pt] = $list;
				}
			}
			$list = JHTML::_('menu.treerecurse', $index_root, '', array(), $children, 9999, 0, 0);
			$mitems = '';
	
			foreach ($list as $item)
			{
				$item->treename = JString::str_ireplace('&#160;', '- ', $item->treename);
				$mitems[] = JHTML::_('select.option', $item->id, '   '.$item->treename);
			}
			
			return $mitems;
			
		} else {
			
			$query = 'SELECT * FROM '. $db->quoteName( '#__categories' );
			$query .= ' WHERE ' . $db->quoteName( 'extension' ) . ' = '. $db->quote('com_faqbookpro').' ';
			$query .= ' AND ' . $db->quoteName( 'published' ) . ' = '. $db->quote('1');
			$query .= ' AND ' . $db->quoteName( 'access' ) . ' IN ('.$groups.') ';
			$query .= ' AND ' . $db->quoteName( 'id' ) . ' = '. $db->quote($params->get('ask_bulk_cat'));
			$query .= ' ORDER BY '. $db->quote('parent_id'). ' ';				
			$db->setQuery($query);
			$mitem = $db->loadObject();
			
			$mitem->title = JString::str_ireplace('&#160;', '- ', $mitem->title);
			$option[] = JHTML::_('select.option', $mitem->id, '   '.$mitem->title);
				
			return $option;	
		}
		
	}
}
