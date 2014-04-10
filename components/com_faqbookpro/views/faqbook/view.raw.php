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

JPluginHelper::importPlugin('captcha');
$dispatcher = JDispatcher::getInstance();
$dispatcher->trigger('onInit','dynamic_recaptcha_1');

require_once JPATH_SITE.'/components/com_content/helpers/route.php'; // temp

jimport('joomla.application.component.view');
 
class FaqBookProViewFaqBook extends JViewLegacy
{

  // Overwriting JView display method
  function display($tpl = null) 
  {
	  $document = JFactory::getDocument();
		$Itemid = JRequest::getVar('Itemid',0,'','INT');
		$page_view = JRequest::getVar('view');
		$params = FaqBookProHelperUtilities::getParams('com_faqbookpro');
		
		$activeMenu = JFactory::getApplication()->getMenu()->getActive();
		$activeMenuLink = $activeMenu->link.'&Itemid='.$activeMenu->id;
		$searchLink = JRoute::_('index.php?option=com_faqbookpro&view=search&Itemid='.$activeMenu->id.'&word=');
		$askLink = JRoute::_('index.php?option=com_faqbookpro&view=ask&Itemid='.$activeMenu->id);
		  
		// Get Featured categories
		$index_featured = $params->get('index_featured');
		$featured_ids = $params->get('index_featured_cats');
		
		  if ($featured_ids)
			{
				$featured_ids = implode(",",$featured_ids);
		
			// Check featured cats access
			$featured_ids = FaqBookProHelperUtilities::checkFeaturedCategoriesAccess($featured_ids);
		    // Implode items for Javascript purposes
		    $itemsvar = implode(",",$featured_ids);
		  } else {
			  $itemsvar = '';
			}
		$featured_cols = $params->get('index_featured_cols');
		$featured_title = $params->get('index_featured_title');
		$featured_desc = $params->get('index_featured_description');
		$featured_desc_limit = $params->get('index_featured_description_limit');
		$featured_image = $params->get('index_featured_image');
		$featured_imgsize = $params->get('index_featured_imageSize');
		$featured_imgheight = $params->get('index_featured_imageHeight');
		$featured = FaqBookProHelperUtilities::getFeaturedCategories($featured_ids, $featured_cols, $featured_title, $featured_desc, $featured_desc_limit, $featured_image, $featured_imgsize, $featured_imgheight, $Itemid);
		
		// Get pre/post text
		$index_pre_text = $params->get('index_pre_text');
		$index_post_text = $params->get('index_post_text');
		
		$page_title = $document->getTitle();
			
	$document->setType('raw');
																
    // Assign data to the view	
		$this->assignRef('params', $params);
		$this->assignRef('index_featured', $index_featured);
		$this->assignRef('index_pre_text', $index_pre_text);
		$this->assignRef('index_post_text', $index_post_text);
		$this->assignRef('featured', $featured);
						
    // Check for errors.
    if (count($errors = $this->get('Errors'))) 
    {
      JError::raiseError(500, implode('<br />', $errors));
      return false;
    }
          
	  // Display the view
	  $this->setLayout('indexcontent');
    parent::display($tpl);
					
  }
				
} // End Class