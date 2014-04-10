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
defined('_JEXEC') or die ;

if(!defined('DS')){
define('DS',DIRECTORY_SEPARATOR);
}

if( !defined('PhpThumbFactoryLoaded') ) {
  require_once( JPATH_COMPONENT.DS.'helpers'.DS.'phpthumb/ThumbLib.inc.php' );
	define('PhpThumbFactoryLoaded',1);
}

jimport('joomla.application.categories');

jimport('joomla.filesystem.folder');

class FaqBookProHelperUtilities
{

	public static function getParams($option)
	{
	  
		$application = JFactory::getApplication();
		if ($application->isSite())
		{
		  $params = $application->getParams($option);
		}
		else
		{
		  $params = JComponentHelper::getParams($option);
		}
		
		return $params;

	}
	
	public static function getCategoriesRoot($root)
	{
		
		$categories = JCategories::getInstance('FaqBookPro');
		
		$category = $categories->get($root);

		if ($category != null)
		{
			$items = $category->getChildren();
			
			return $items;		
		}
		
	}
	
  public static function getCategoryDepth($item, $levels) 
	{
		$children = $item->getChildren();
		if (count($children))
		{		
		  $levels++;	
		  foreach ($children as $child)
			{ 
			  if (count($child->getChildren()))
				{
					return;
				}
			}
			
		}
		
		return $levels;
  }
	
	public static function getCategoriesTree($item, $level = 1)
	{
	  $output = '';
		if (count($item->getChildren()))
		{			
		  $subitems = $item->getChildren();
			$depth = FaqBookProHelperUtilities::getCategoryDepth($item, $levels = 0);
			if ($depth === 0 || $depth == 1)
			{
			  $item_class = ' NavLeftUL_endpoint';
				$span_class = 'NavLeftUL_endpointIcon';
			}
			else
			{
			  $item_class = '';
				$span_class = 'NavLeftUL_navIcon';
			}
			$output .= '<li id="liid'.$item->id.'" class="NavLeftUL_item'.$item_class.'"><a href="'.JRoute::_('index.php?option=com_faqbookpro&view=category&id='.$item->id).'" class="NavLeftUL_anchor" rel="nofollow" onclick="return false;"><span class="catTitle">'.$item->title.'<span class="'.$span_class.'"></span></span></a>';
			$output .= '<ul class="NavLeftUL_sublist level'.$level.'">';		
			foreach ($subitems as $subitem)
			{
			  $output .= FaqBookProHelperUtilities::getCategoriesTree($subitem, $level + 1);
			}
			$output .= '<li id="backliid'.$item->id.'" class="NavLeftUL_backItem"><a href="#" class="NavLeftUL_anchor" rel="nofollow" onclick="return false;"><span>'.JText::_('COM_FAQBOOKPRO_BACK').'<span class="NavLeftUL_navBackIcon"></span></span></a></li>';
			$output .= '</ul>';				
			$output .= '</li>';		
		} 
		else 
		{
		  $output .= '<li id="liid'.$item->id.'" class="NavLeftUL_item NavLeftUL_endpoint"><a href="'.JRoute::_('index.php?option=com_faqbookpro&view=category&id='.$item->id).'" class="NavLeftUL_anchor" rel="nofollow" onclick="return false;"><span class="catTitle">'.$item->title.'<span class="NavLeftUL_endpointIcon"></span></span></a>';
			$output .= '</li>';		
		}
		
		return $output;
		
	}
	
	public static function getCategoriesTreeString($item)
	{  
	  $output = '';
		
		if (count($item->getChildren()))
		{			
		  $subitems = $item->getChildren();
			
			$output .= $item->id.',';	
			foreach ($subitems as $subitem)
			{
			  $output .= FaqBookProHelperUtilities::getCategoriesTreeString($subitem);
			}
		} 
		else 
		{
		  $output .= $item->id.',';	
		}
	
		return $output;
		
	}
	
	public static function getWordLimit($text, $limit, $end_char = '&#8230;')
	{
	   if(JString::trim($text) == '')
			return $text;

		// always strip tags for text
		$text = strip_tags($text);

		$find = array(
			"/\r|\n/u",
			"/\t/u",
			"/\s\s+/u"
		);
		$replace = array(
			" ",
			" ",
			" "
		);
		$text = preg_replace($find, $replace, $text);

		preg_match('/\s*(?:\S*\s*){'.(int)$limit.'}/u', $text, $matches);
		if (JString::strlen($matches[0]) == JString::strlen($text))
			$end_char = '';
			
		return JString::rtrim($matches[0]).$end_char;
		
	}
	
	public static function getFeaturedCategories($items, $cols, $title, $desc, $limit, $image, $imgsize, $imgheight, $Itemid)
	{
	  $categories = JCategories::getInstance('Faqbookpro');
		if ($cols > 1) 
		{
		  $class = 'fbpContent_gridItem';
			$anchor_class = 'clearfix';
		} else {
		  $class = 'fbpContent_gridItem onecolgrid';
			$anchor_class = '';
		}
	  
		$output = '';
	
	  if (count($items))
		{
			$output .= '<ul class="fbpContent_grid">';
			
			$i = 0;
		  foreach ($items as $key=>$item)
			{
			  if (!empty($item)) 
				{
			    $category = $categories->get($item);
				if ($category)
				{
			    	$output .= '<li id="gI'.$category->id.'" class="'.$class.'" style="width:'. number_format(100/$cols, 1).'%;">';
				
				  	$output .= '<div class="fbpContent_gridItemContainer">';
				  	if ($image)
				  	{
						$path = $category->getParams()->get('image');
					  	$img = FaqBookProHelperUtilities::resizeImage($imgsize, $imgheight, $path, $category->title);
				    	$output .= '<a href="'.JRoute::_('index.php?option=com_faqbookpro&view=category&id='.$category->id.'&Itemid='.$Itemid).'" class="feat-item-img '.$anchor_class.'"><img src="'.$img.'" alt="'.$category->title.'"></a>';
				  	}
				  	if ($title)
				  	{
				    	$output .= '<h4><a href="'.JRoute::_('index.php?option=com_faqbookpro&view=category&id='.$category->id.'&Itemid='.$Itemid).'" class="feat-item '.$anchor_class.'" id="fid'.$category->id.'">'.$category->title.'</a></h4>';
				  	}
				  	if ($desc)
				  	{
				    	$output .= '<div class="index-cat-desc">'.FaqBookProHelperUtilities::getWordLimit($category->description, $limit).'</div>';
				  	}
				  	$output .= '</div>';
				  	$output .= '</li>';
					$i++;
				}
				if(($i)%$cols==0) 
				{
			    	$output .= '<div class="clearfix"></div>';
			    }
				}
			}
			$output .= '</ul>';
		}
		
		return $output;
	
	}
	
	public static function checkFeaturedCategoriesAccess($featured_ids)
	{	
		// Defining the user access
	  	$user = JFactory::getUser();
	  	$groups = implode(',', $user->getAuthorisedViewLevels());
		
	    $db = JFactory::getDBO();
		 
		// SQL query			
		$query_cats = 'SELECT 
			id
		  FROM 
			#__categories
		  WHERE 
			id IN ('.$featured_ids.')
		  AND 
		    published = 1	
		  AND 
		    access IN ('.$groups.') ';
		
	 	 // Run SQL query
	  	$db->setQuery($query_cats);
		$result = $db->loadObjectList();
		
		$cats = array();
		foreach($result as $cat)
		{
			array_push($cats, $cat->id);
		}
		
		return $cats;
	}
	
	public static function getIndexPreText()
	{
	   	$params = self::getParams('com_faqbookpro');
		$index_pre_text = $params->get('index_pre_text');
		
		$output = $index_pre_text;
		
		return $output;
	
	}
	
	public static function getIndexPostText()
	{
	   	$params = self::getParams('com_faqbookpro');
		$index_post_text = $params->get('index_post_text');
		
		$output = $index_post_text;
		
		return $output;
	
	}
	
	public static function resizeImage($imgsize, $imgheight, $path, $categoryTitle)
	{
	  // Image variables
		$imgWidth    = (int)$imgsize;
		if (!$imgheight) {
    	 $imgHeight   = round($imgWidth * (3/4));
		} else {
			$imgHeight = $imgheight;
		}
		$img = $path;
	
		// Render new image
		if ($img && $new_image = FaqBookProHelperUtilities::renderImages($img, $imgWidth, $imgHeight, $categoryTitle)) 
		{
      $img = $new_image;
    }
		
		if (isset($img))
		  $img = $img;
		
		return $img;
		
	}
	
	public static function makeDir($path)
	{
		$folders = explode ('/',  ($path));
		$tmppath =  JPATH_SITE.DS.'images'.DS.'faqbookpro'.DS;
		if(!file_exists($tmppath)) {
			JFolder::create($tmppath, 0755);
		}; 
		for ($i = 0; $i < count ($folders) - 1; $i ++) 
		{
			if (!file_exists($tmppath . $folders [$i]) && ! JFolder::create($tmppath . $folders [$i], 0755)) 
			{
				return false;
			}	
			$tmppath = $tmppath . $folders [$i] . DS;
		}		
		
		return true;
	}
	
	public static function renderImages($path, $width, $height, $title='') 
	{	
	  $path = str_replace(JURI::base(), '', $path);
		$imgSource = JPATH_SITE.DS. str_replace('/', DS,  $path);
		if (file_exists($imgSource)) 
		{
		  $path =  $width."x".$height.'/'.$path;
			$thumbPath = JPATH_SITE.DS.'images'.DS.'faqbookpro'.DS. str_replace('/', DS,  $path);
			if (!file_exists($thumbPath)) 
			{
			  $thumb = PhpThumbFactory::create($imgSource);  
				if (!self::makeDir($path)) 
				{
					return '';
				}		
				$thumb->adaptiveResize($width, $height);
				$thumb->save($thumbPath); 
			}
			$path = JURI::base().'images/faqbookpro/'.$path;
		} 
		
		return $path;
	}
	
	public static function getAskCategory($index_root) 
	{
		// Defining the user access
	  	$user = JFactory::getUser();
	  	$groups = implode(',', $user->getAuthorisedViewLevels());
		
		$db = JFactory::getDBO();
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
		$mitems = array(JText::_('COM_FAQBOOKPRO_SELECT_CATEGORY_OPTION'));

		foreach ($list as $item)
		{
			$item->treename = JString::str_ireplace('&#160;', '- ', $item->treename);
			$mitems[] = JHTML::_('select.option', $item->id, '   '.$item->treename);
		}
		
		$askCategory = JHTML::_('select.genericlist', $mitems, 'catid', 'class="inputbox"', 'value', 'text', '');
		
		return $askCategory;
	}
	
} // End Class
