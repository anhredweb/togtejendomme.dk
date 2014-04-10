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

class FaqBookProHelperNavigation
{

	public static function getTopNav()
	{
	  $params = FaqBookProHelperUtilities::getParams('com_faqbookpro');
	  $user = JFactory::getUser();
	  
      $output = '';
	  $output .= '<div class="fbpTopNavigation_core_outer">';
	  $output .= '<div class="fbpTopNavigation_core">';
	    $output .= '<div class="fbpTopNavigation_wrap">';
		  if ($params->get('faq_search')) 
		  {
		  	$output .= '<span id="fbpTopNavigation_search_icon" title="'.JText::_("COM_FAQBOOKPRO_SEARCH_FAQBOOK").'"></span>';
		  }
		  if (($params->get('enable_ask_new_faq')==1 && $user->id) || ($params->get('enable_ask_new_faq')==2))
		  {
		  	$output .= '<span id="fbpTopNavigation_ask_icon" class="" title="'.JText::_("COM_FAQBOOKPRO_ASK_NEW_FAQ").'"></span>';
		  }
	      $output .= '<ul class="fbpTopNavigation_root fpb-hidden-phone">';
			    $output .= '<li id="top_liid_home" class="NavTopUL_item NavTopUL_firstChild NavTopUL_lastChild">';
				    $output .= '<a class="NavTopUL_link" href="#" onclick="return false;">';
					    $output .= '<span class="NavTopUL_homeIcon"></span>';
						  $output .= JText::_('COM_FAQBOOKPRO_TOP_NAV_HOME');
					  $output .= '</a>';
				  $output .= '</li>';
			  $output .= '</ul>';
		    $output .= '<span class="NavTopUL_loading"></span>';
		  $output .= '</div>';
		$output .= '</div>';
		if ($params->get('leftnav'))
		{ 
			$output .= '<div class="show_menu"><a href="#" onclick="return false;">'.JText::_('COM_FAQBOOKPRO_SHOW_MENU').'</a></div>';
		}
		if ($params->get('faq_search')) 
		{
			// Search form
			$output .= '<div class="fbpTopNavigation_search">';
			$output .= '<span id="fbpTopNavigation_close_search"></span>';
			$output .= '<form id="u_0_3" action="index.php" class="_76u">';
			$output .= '<div id="u_0_0" class="uiTypeahead">';
			$output .= '<div>';
			$output .= '<div class="search_form_innerWrap">';
			$output .= '<input type="text" id="u_0_1" aria-label="'.JText::_('COM_FAQBOOKPRO_SEARCH_TEXT_DEFAULT').'" spellcheck="false" role="combobox" aria-expanded="false" aria-autocomplete="list" autocomplete="off" value="" name="query" placeholder="'.JText::_('COM_FAQBOOKPRO_SEARCH_TEXT_DEFAULT').'" class="inputtext_search_topnav">';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</form>';
			$output .= '</div>'; 
		}
		
			$output .= '<div class="clearfix"> </div>';
		
		$output .= '</div>';
		
		return $output;

	}
	
	public static function getLeftNav($categoryTrees)
	{
	  
		$output = '';
		$output .= '<div class="fbpLeftNavigation_core">';  
		  $output .= '<div class="fbpLeftNavigation_root">';	  
		    $output .= '<div id="fbp_l_n" class="fbpLeftNavigation_wrap">'; 
				  $output .= '<ul id="NavLeftUL" class="NavLeftUL_parent">';  		  
					  foreach ($categoryTrees as $categoryTree)
					  { 
  				    $output .= $categoryTree;
					  }		
				  $output .= '</ul>';			
		    $output .= '</div>';	
		  $output .= '</div>';
	  $output .= '</div>';
	
		
		return $output;

	}
	
}