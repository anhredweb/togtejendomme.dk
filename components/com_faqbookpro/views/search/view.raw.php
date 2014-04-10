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
			$model = $this->getModel('Search');
			$indexRoot = $params->get('index_root');
			
			$search_val = JRequest::getVar('word');				
			$catsString = $model->getIndexCatsString($indexRoot);
			$faqs = $model->getSearchResults($search_val, $catsString);
			$faqs_count = count($faqs);
					
			$document->setType('raw');
			
			// Assign data to the view
			$this->assignRef('params', $params);
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
			$this->setLayout('searchcontent');
            parent::display($tpl);
			
        }
}