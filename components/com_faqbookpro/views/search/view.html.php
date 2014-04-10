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

// import Joomla view library
jimport('joomla.application.component.view');
 
class FaqBookProViewSearch extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null) 
        {			
			$document = JFactory::getDocument();
			$Itemid = JRequest::getVar('Itemid',0,'','INT');
			$params = FaqBookProHelperUtilities::getParams('com_faqbookpro');	
			$page_view = JRequest::getVar('view');
			$page_title = $document->getTitle();
			$model = $this->getModel('Search');
			$indexRoot = $params->get('index_root');
			
			$search_val = JRequest::getVar('word');				
			$catsString = $model->getIndexCatsString($indexRoot);
			$faqs = $model->getSearchResults($search_val, $catsString);
			$faqs_count = count($faqs);
			
			// Get the parent menu params
			$activeMenuParams = $model->getParentMenuParams($params);
			$activeMenu = JFactory::getApplication()->getMenu()->getActive();	
			$activeMenuLink = $activeMenu->link.'&Itemid='.$activeMenu->id;
			$searchLink = JRoute::_('index.php?option=com_faqbookpro&view=search&Itemid='.$activeMenu->id.'&word=');
			$askLink = JRoute::_('index.php?option=com_faqbookpro&view=article&layout=edit&Itemid='.$activeMenu->id);
			
			// Get Top Navigation
			$topnavigation = FaqBookProHelperNavigation::getTopNav();
				
		  	$index_root = $activeMenuParams->get('index_root');
			
			// Get Left Navigation
			$leftnav = $activeMenuParams->get('leftnav');
			if ($leftnav)
			{
				$items = FaqBookProHelperUtilities::getCategoriesRoot($root = $index_root);
				if ($items) 
		 		{
					foreach ($items as $item) 
					{
					  $categoryTree = FaqBookProHelperUtilities::getCategoriesTree($item);
						$categoryTrees[] = $categoryTree;
					}	
					$leftnavigation = FaqBookProHelperNavigation::getLeftNav($categoryTrees);
				}
    		}	
			
			// Get Featured categories
  			$index_featured = $activeMenuParams->get('index_featured');
  			$featured_ids = $activeMenuParams->get('index_featured_cats');
			
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
			$featured_cols = $activeMenuParams->get('index_featured_cols');
			$featured_title = $activeMenuParams->get('index_featured_title');
			$featured_desc = $activeMenuParams->get('index_featured_description');
			$featured_desc_limit = $activeMenuParams->get('index_featured_description_limit');
			$featured_image = $activeMenuParams->get('index_featured_image');
			$featured_imgsize = $activeMenuParams->get('index_featured_imageSize');
			$featured_imgheight = $activeMenuParams->get('index_featured_imageHeight');
			$featured = FaqBookProHelperUtilities::getFeaturedCategories($featured_ids, $featured_cols, $featured_title, $featured_desc, $featured_desc_limit, $featured_image, $featured_imgsize, $featured_imgheight, $Itemid);
  		
  			// Get javascript variables
  			$document->addScriptDeclaration('window.fbpvars = {
    	  		token: "'.JSession::getFormToken().'",
  				site_path: "'.JURI::root().'",
				index_link: "'.JRoute::_($activeMenuLink).'",
				search_val: "'.JRoute::_($search_val).'",
				search_link: "'.JRoute::_($searchLink).'",
				ask_link: "'.JRoute::_($askLink).'",
				items: "'.$itemsvar.'",
				cols: "'.$featured_cols.'",
				title: "'.$featured_title.'",
				desc: "'.$featured_desc.'",
				limit: "'.$featured_desc_limit.'",
				image: "'.$featured_image.'",
				imgsize: "'.$featured_imgsize.'",
				imgheight: "'.$featured_imgheight.'",
				featured: "'.$index_featured.'",
				page_view: "'.$page_view.'",
				item_id: "'.$Itemid.'",
				leftnav: "'.$leftnav.'",
				page_title: "'.$page_title.'",
				thank_you_up: "'.JText::_('COM_FAQBOOKPRO_THANK_YOU_UP').'",
				thank_you_down: "'.JText::_('COM_FAQBOOKPRO_THANK_YOU_DOWN').'",
				already_voted: "'.JText::_('COM_FAQBOOKPRO_ALREADY_VOTED').'",
				why_not: "'.JText::_('COM_FAQBOOKPRO_WHY_NOT').'",
				incorrect_info: "'.JText::_('COM_FAQBOOKPRO_INCORRECT_INFO').'",
				dont_like: "'.JText::_('COM_FAQBOOKPRO_DO_NOT_LIKE').'",
				confusing: "'.JText::_('COM_FAQBOOKPRO_CONFUSING').'",
				not_answer: "'.JText::_('COM_FAQBOOKPRO_NOT_ANSWER').'",
				too_much: "'.JText::_('COM_FAQBOOKPRO_TOO_MUCH').'",
				other: "'.JText::_('COM_FAQBOOKPRO_OTHER').'",
				error_voting: "'.JText::_('COM_FAQBOOKPRO_ERROR_VOTING').'",
				captcha_key: "'.$params->get('captcha_key').'"			
      		};');
			
			// Set menu metadata
			if ($params->get('menu-meta_description'))
			{
			  $document->setDescription($params->get('menu-meta_description'));
			}
			if ($params->get('menu-meta_keywords'))
			{
			  $document->setMetadata('keywords', $params->get('menu-meta_keywords'));
			}
			if ($params->get('robots'))
			{
			  $document->setMetadata('robots', $params->get('robots'));
			}
			
			// Assign data to the view
			$this->assignRef('params', $params);
			$this->assignRef('topnavigation', $topnavigation);
			$this->assignRef('leftnav', $leftnav);
			if ($leftnav)
			{
			  $this->assignRef('leftnavigation', $leftnavigation);
			}
			$this->assignRef('search_val', $search_val);
			$this->assignRef('Itemid', $Itemid);
			$this->assignRef('faqs', $faqs);
			$this->assignRef('faqs_count', $faqs_count);
			
            // Check for errors.
            if (count($errors = $this->get('Errors'))) 
            {
            	JError::raiseError(500, implode('<br />', $errors));
                return false;
            }
				
            // Display the view
            parent::display($tpl);
			
        }
}