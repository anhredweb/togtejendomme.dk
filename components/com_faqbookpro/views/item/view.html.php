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

jimport('joomla.application.categories');

class FaqBookProViewItem extends JViewLegacy
{

  // Overwriting JView display method
  function display($tpl = null) 
  {
	  	$document = JFactory::getDocument();
		$app	= JFactory::getApplication();
	  	$id = JRequest::getVar('id',0,'','INT');
		$page_view = JRequest::getVar('view');
		$Itemid = JRequest::getVar('Itemid',0,'','INT');
		$params = FaqBookProHelperUtilities::getParams('com_faqbookpro');	
		$model = $this->getModel('Item');
		$user = &JFactory::getUser();
		
		$page_title = $document->getTitle();
		
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
								
    // Get Params
		$params_item_text_limit = $params->get('faq_text_limit');
		$params_item_text_limit_num = $params->get('faq_text_limit_num');
		$params_item_text = $params->get('faq_text');
		$params_item_date = $params->get('faq_date');
		$params_item_author = $params->get('faq_author');
		$params_item_image = $params->get('faq_image');
		$params_item_imageSize = $params->get('faq_imageSize');
		$params_item_imageHeight = $params->get('faq_imageHeight');
		
		// Get item content
		$itemContent = $model->getItemContent($id, $params);
  		$itemId = $itemContent->id;
		$itemFeatured = $itemContent->featured;
		$itemParent = $itemContent->catid;
		$itemTitle = $itemContent->title;
  		$itemText = $itemContent->introtext; // Limit text
		$itemFulltext = $itemContent->fulltext;
		$itemDate = $itemContent->publish_up;
		$itemAuthor = $itemContent->created_by;
		$itemImages  = json_decode($itemContent->images, true);
		$itemImageIntroRaw = $itemImages['image_intro'];
		$itemImageFulltextRaw = $itemImages['image_fulltext'];
		$itemImageIntro = FaqBookProHelperUtilities::resizeImage($params_item_imageSize, $params_item_imageHeight, $itemImageIntroRaw, $itemTitle);
		$itemImageFulltext = FaqBookProHelperUtilities::resizeImage($params_item_imageSize, $params_item_imageHeight, $itemImageFulltextRaw, $itemTitle);
		if ($params_item_image == '1') {
			$itemImage = $itemImageIntro;	
			$itemImageCaption = $itemImages['image_intro_caption'];
			$itemImageAlt = $itemImages['image_intro_alt'];
		} else if ($params_item_image == '2') {
			$itemImage = $itemImageFulltext;	
			$itemImageCaption = $itemImages['image_fulltext_caption'];
			$itemImageAlt = $itemImages['image_fulltext_alt'];
		} else {
			$itemImage = '';
			$itemImageCaption = '';
			$itemImageAlt = '';
		}
		
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
			itemId: "'.$id.'",
			page_view: "'.$page_view.'",
			item_id: "'.$Itemid.'",
			leftnav: "'.$leftnav.'",
			page_title: "'.$page_title.'",
			catid: "'.$itemParent.'",
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
	
		// Get Item extra
		$itemExtra = $model->getItemExtra($id, $params, $Itemid);
		
		// Get Item tools
		$itemTools = $model->getItemTools($id, $params, $Itemid);
		
		// Set metadata
		$this_metadata = json_decode($itemContent->metadata, true);
		$this_robots = $this_metadata['robots'];
		$this_author = $this_metadata['author'];
		
		if ($itemContent->metadesc)
		{
			$document->setDescription($itemContent->metadesc);
		}
		elseif (!$itemContent->metadesc && $params->get('menu-meta_description'))
		{
			$document->setDescription($params->get('menu-meta_description'));
		}

		if ($itemContent->metakey)
		{
			$document->setMetadata('keywords', $itemContent->metakey);
		}
		elseif (!$itemContent->metakey && $params->get('menu-meta_keywords'))
		{
			$document->setMetadata('keywords', $params->get('menu-meta_keywords'));
		}

		if ($this_robots)
		{
			$document->setMetadata('robots', $this_robots);
		} 
		elseif (!$this_robots && $params->get('robots')) 
		{
		  $document->setMetadata('robots', $params->get('robots'));
		}

		if ($app->getCfg('MetaAuthor') == '1')
		{
			$document->setMetaData('author', $this_author);
		}
		
		// Set page title
		$document->setTitle($itemTitle);
		
		// Add hit
		$model->addHit($id);
		
		// Assign data to the view
    	$this->assignRef('params', $params);
		$this->assignRef('index_root', $index_root);
		$this->assignRef('topnavigation', $topnavigation);
		$this->assignRef('leftnav', $leftnav);
		if ($leftnav)
		{
		  $this->assignRef('leftnavigation', $leftnavigation);
		}
		$this->assignRef('user', $user);
		$this->assignRef('params_item_text', $params_item_text);
		$this->assignRef('params_item_date', $params_item_date);
		$this->assignRef('params_item_author', $params_item_author);
		$this->assignRef('params_item_image', $params_item_image);
		$this->assignRef('id', $id);
		$this->assignRef('Itemid', $Itemid);
  		$this->assignRef('itemId', $itemId);
		$this->assignRef('itemFeatured', $itemFeatured);
  		$this->assignRef('itemTitle', $itemTitle);
  		$this->assignRef('itemText', $itemText);
  		$this->assignRef('itemDate', $itemDate);
  		$this->assignRef('itemAuthor', $itemAuthor);
    	$this->assignRef('itemImage', $itemImage);
		$this->assignRef('itemImageCaption', $itemImageCaption);
		$this->assignRef('itemImageAlt', $itemImageAlt);
		$this->assignRef('itemExtra', $itemExtra);   
		$this->assignRef('itemTools', $itemTools);   
	
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