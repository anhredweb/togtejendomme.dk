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

class FAQBookProViewArticle extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;

	public function display($tpl = null)
	{
		// Redirect form id request
		$app =& JFactory::getApplication();
		$user =& JFactory::getUser();
		if (JRequest::getVar('id')) {
			$link = JRoute::_('index.php?option=com_faqbookpro&view=article&layout=edit');
			$app->redirect(str_replace('&amp;', '&', $link), '');	
		}
		
		$document = JFactory::getDocument();
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->canDo	= FAQBookProHelper::getActions($this->state->get('filter.category_id'));

		$Itemid = JRequest::getVar('Itemid',0,'','INT');
		$page_view = JRequest::getVar('view');
		$params = FaqBookProHelperUtilities::getParams('com_faqbookpro');
		
		$activeMenu = JFactory::getApplication()->getMenu()->getActive();
		$activeMenuLink = $activeMenu->link.'&Itemid='.$activeMenu->id;
		$searchLink = JRoute::_('index.php?option=com_faqbookpro&view=search&Itemid='.$activeMenu->id.'&word=');
		$askLink = JRoute::_('index.php?option=com_faqbookpro&view=article&layout=edit&Itemid='.$activeMenu->id);
		
		$page_title = $document->getTitle();
		
		// Get Top Navigation
		$topnavigation = FaqBookProHelperNavigation::getTopNav();
		
		// Get Left Navigation
		$index_root = $params->get('index_root');
		
		$leftnav = $params->get('leftnav');
		if ($leftnav)
		{
		  $content_class = 'fbpContent_core';
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
		else 
		{
		  $content_class = 'fbpContent_core noleftnav';
		}
		
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
		
		// Get javascript variables
		$document->addScriptDeclaration('window.fbpvars = {
  	  		token: "'.JSession::getFormToken().'",
			site_path: "'.JURI::root().'",
			index_link: "'.JRoute::_($activeMenuLink).'",
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
			item_id: "'.$Itemid.'",
			page_view: "'.$page_view.'",
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
		
    // Menu page display options
    if ($params->get('page_heading'))
    {
      $params->set('page_title', $params->get('page_heading'));
    }
    $params->set('show_page_title', $params->get('show_page_heading'));
				
	$askCategory = FaqBookProHelperUtilities::getAskCategory($index_root);	
															
    // Assign data to the view	
		$this->assignRef('params', $params);
		$this->assignRef('topnavigation', $topnavigation);
		$this->assignRef('leftnavigation', $leftnavigation);
		$this->assignRef('leftnav', $leftnav);
		$this->assignRef('Itemid', $Itemid);
		$this->assignRef('content_class', $content_class);
		$this->assignRef('index_featured', $index_featured);
		$this->assignRef('index_pre_text', $index_pre_text);
		$this->assignRef('index_post_text', $index_post_text);
		$this->assignRef('featured', $featured);
		$this->assignRef('askCategory', $askCategory);
						
    // Check for errors.
    if (count($errors = $this->get('Errors'))) 
    {
      JError::raiseError(500, implode('<br />', $errors));
      return false;
    }
     
		// Display the view
		if (($params->get('enable_ask_new_faq')==1 && $user->id) || ($params->get('enable_ask_new_faq')==2)) {
			parent::display($tpl);
		} else {
			JError::raiseError(500, '', '');
		}
	}

}
