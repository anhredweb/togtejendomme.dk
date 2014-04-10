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

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'route.php' );
JLoader::register('FaqBookProHelperUtilities', JPATH_COMPONENT.DS.'helpers'.DS.'utilities.php');
JLoader::register('FaqBookProHelperNavigation', JPATH_COMPONENT.DS.'helpers'.DS.'navigation.php');

jimport('joomla.filesystem.file');

$controller	= JControllerLegacy::getInstance('FaqBookPro');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

// Add stylesheet
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_faqbookpro/assets/css/faqbook.css');
jimport( 'joomla.application.component.helper' );
$params  = JComponentHelper::getParams('com_faqbookpro');

// Load jQuery & jQueryUI
//JHtml::_('jquery.framework');
//JHtml::_('jquery.ui', array('core', 'sortable'));
JFactory::getApplication()->set('jquery', true);
if ($params->get('load_jquery')) {
  $document->addScript(JURI::base().'components/com_faqbookpro/assets/js/jquery.js');
}

// Add scripts
$document->addScript(JURI::base().'components/com_faqbookpro/assets/js/faqbook.js');

// Add captcha
$user = JFactory::getUser();
if ( ($params->get('askform_captcha')==1 && !$user->id) || ($params->get('askform_captcha')==2)) {
  $document->addScript('http://www.google.com/recaptcha/api/js/recaptcha_ajax.js');
}