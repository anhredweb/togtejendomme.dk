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

// Include dependancies
jimport('joomla.application.component.controller');

// Check component access
if (!JFactory::getUser()->authorise('core.manage', 'com_faqbookpro'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include basic helper
JLoader::register('FAQBookProHelper', JPATH_COMPONENT_ADMINISTRATOR. '/helpers/faqbookpro.php');

$controller	= JControllerLegacy::getInstance('faqbookpro');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

// Add stylesheet
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root().'/administrator/components/com_faqbookpro/assets/css/faqbookpro.css');