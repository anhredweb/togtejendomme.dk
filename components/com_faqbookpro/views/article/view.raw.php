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
 
class FAQBookProViewArticle extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;
	
        // Overwriting JView display method
        function display($tpl = null) 
        {	
			$document = JFactory::getDocument();
			$Itemid = JRequest::getVar('Itemid',0,'','INT');
			$params = FaqBookProHelperUtilities::getParams('com_faqbookpro');	
			$this->form		= $this->get('Form');
			$this->item		= $this->get('Item');
			$this->state	= $this->get('State');
			$this->canDo	= FAQBookProHelper::getActions($this->state->get('filter.category_id'));
	
			$document->setType('raw');
			
			// Assign data to the view
			$this->assignRef('params', $params);
			$this->assignRef('Itemid', $Itemid);
			
            // Check for errors.
            if (count($errors = $this->get('Errors'))) 
            {
            	JError::raiseError(500, implode('<br />', $errors));
                return false;
            }
				
            // Display the view
			$this->setLayout('askform');
            parent::display($tpl);
			
        }
}