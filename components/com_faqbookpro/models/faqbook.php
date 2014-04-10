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
defined('_JEXEC') or die('Restricted access');
 
if(!defined('DS')){
define('DS',DIRECTORY_SEPARATOR);
} 
 
jimport('joomla.application.component.modelitem');
 
class FaqBookProModelFaqBook extends JModelItem
{ 
	
	function __construct() {
	  parent::__construct();
	}
	
	public function loadHomeContent($items, $cols, $title, $desc, $limit, $image, $imgsize, $imgheight, $Itemid) 
	{	
		require_once JPATH_SITE.DS."components".DS."com_faqbookpro".DS."helpers".DS."utilities.php";
		$output = '';
		$output .= '<div class="index_pre_text">'.FaqBookProHelperUtilities::getIndexPreText().'</div>';
   		$output .= FaqBookProHelperUtilities::getFeaturedCategories($items, $cols, $title, $desc, $limit, $image, $imgsize, $imgheight, $Itemid);
		$output .= '<div class="index_post_text">'.FaqBookProHelperUtilities::getIndexPostText().'</div>';
		
		echo $output;
			
	}
		
}