<?php
/**
* @title   	     Minitek FAQ Book Pro
* @copyright     Copyright (C) 2011-2013 Minitek, All rights reserved.
* @license       GNU General Public License version 2 or later.
* @author url    http://www.minitek.gr/
* @author email  info@minitek.gr
* @developer     minitek.gr
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.categories');

// import Joomla view library
jimport('joomla.application.component.view');
 
class FaqBookProViewCategory extends JViewLegacy
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
		$model = $this->getModel('Category');
		
		$page_title = $document->getTitle();
	
		// Get the parent menu params
		$activeMenuParams = $model->getParentMenuParams($params);
		
		$activeMenu = JFactory::getApplication()->getMenu()->getActive();	
		$activeMenuLink = $activeMenu->link.'&Itemid='.$activeMenu->id;
		$searchLink = JRoute::_('index.php?option=com_faqbookpro&view=search&Itemid='.$activeMenu->id.'&word=');
		$askLink = JRoute::_('index.php?option=com_faqbookpro&view=article&layout=edit&Itemid='.$activeMenu->id);
		
		// Get this FAQBook Index params
		
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
			catid: "'.$id.'",
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
		
		// Get Params
		$params_category_title = $params->get('category_title');
		$params_category_description = $params->get('category_description');
		$params_category_image = $params->get('category_image');
		$params_category_imageSize = $params->get('category_imageSize');
		$params_category_imageHeight = $params->get('category_imageHeight');
		$params_subcategories_title = $params->get('subcategories_title');
		$params_subcategories_description = $params->get('subcategories_description');
		$params_subcategories_image = $params->get('subcategories_image');
		$params_subcategories_imageSize = $params->get('subcategories_imageSize');	
		$params_subcategories_imageHeight = $params->get('subcategories_imageHeight');		
		
		// Get Category content
		$categories = JCategories::getInstance('FaqBookPro');
		$category = $categories->get($id);
		  $categoryId = $category->id;
		  $categoryTitle = $category->title;
			$categoryDesc = $category->description;
			$categoryImagePath = $category->getParams()->get('image');
			$categoryImage = FaqBookProHelperUtilities::resizeImage($params_category_imageSize, $params_category_imageHeight, $categoryImagePath, $categoryTitle);
		// 1st level category articles
		$categoryContent = $model->getCategoryContent($category, $params);
		
		$items = $category->getChildren();
		
		// Get Category tools
		$categoryTools = $model->getCategoryTools($category, $params, $Itemid);
		
		// 2st level categories articles	
		foreach ($items as $item)
		{	  
			$subcategoryContent = $model->getSubcategoryContent($item, $params);
			$subcategoryContents[] = $subcategoryContent;
		}
		
		// Set metadata
		if ($category->metadesc)
		{
			$this->document->setDescription($category->metadesc);
		}
		elseif (!$category->metadesc && $params->get('menu-meta_description'))
		{
			$document->setDescription($params->get('menu-meta_description'));
		}

		if ($category->metakey)
		{
			$document->setMetadata('keywords', $category->metakey);
		}
		elseif (!$category->metakey && $params->get('menu-meta_keywords'))
		{
			$document->setMetadata('keywords', $params->get('menu-meta_keywords'));
		}

		if ($params->get('robots'))
		{
			$document->setMetadata('robots', $params->get('robots'));
		}

		if ($app->getCfg('MetaAuthor') == '1')
		{
			$document->setMetaData('author', $category->getMetadata()->get('author'));
		}

		$mdata = $category->getMetadata()->toArray();

		foreach ($mdata as $k => $v)
		{
			if ($v)
			{
				$document->setMetadata($k, $v);
			}
		}
		
		// Set page title
		$document->setTitle($categoryTitle);
		
		// Assign data to the view
    $this->assignRef('params', $params);
		$this->assignRef('index_root', $index_root);
		$this->assignRef('topnavigation', $topnavigation);
		$this->assignRef('leftnav', $leftnav);
		if ($leftnav)
		{
		  $this->assignRef('leftnavigation', $leftnavigation);
		}
		$this->assignRef('params_category_title', $params_category_title);
		$this->assignRef('params_category_description', $params_category_description);
		$this->assignRef('params_category_image', $params_category_image);
		$this->assignRef('id', $id);
		$this->assignRef('Itemid', $Itemid);
		$this->assignRef('categoryId', $categoryId);
		$this->assignRef('categoryTitle', $categoryTitle);
		$this->assignRef('categoryDesc', $categoryDesc);
		$this->assignRef('categoryImage', $categoryImage);
		$this->assignRef('categoryContent', $categoryContent);
		$this->assignRef('categoryTools', $categoryTools);
		$this->assignRef('subcategoryContents', $subcategoryContents);
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		// Display the view
		parent::display($tpl);	
	}
	
}