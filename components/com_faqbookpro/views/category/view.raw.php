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

jimport('joomla.application.categories');

// import Joomla view library
jimport('joomla.application.component.view');
 
class FaqBookProViewCategory extends JViewLegacy
{

	// Overwriting JView display method
	function display($tpl = null) 
	{		
	  $document = JFactory::getDocument();
	  $id = JRequest::getVar('id',0,'','INT');		
		$Itemid = JRequest::getVar('Itemid',0,'','INT');
	  $params = FaqBookProHelperUtilities::getParams('com_faqbookpro');	
		$model = $this->getModel('Category');
		
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
		
	  $document->setType('raw');
				
		// Assign data to the view
    	$this->assignRef('params', $params);
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
		$this->setLayout('categorycontent');
		parent::display($tpl);	
	}
	
}