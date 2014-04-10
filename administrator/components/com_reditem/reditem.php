<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Entry point
 *
 * @copyright   Copyright (C) 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$redcoreLoader = JPATH_LIBRARIES . '/redcore/bootstrap.php';

if (!file_exists($redcoreLoader) || !JPluginHelper::isEnabled('system', 'redcore'))
{
	throw new Exception(JText::_('COM_REDITEM_REDCORE_INIT_FAILED'), 404);
}

// Bootstraps redCORE
RBootstrap::bootstrap();

$app = JFactory::getApplication();

// Register component prefix
JLoader::registerPrefix('Reditem', __DIR__);

// Load redITEM Library
JLoader::import('reditem.library');

$controller = JRequest::getVar('view', 'cpanel');

// Set the controller page
if (!file_exists(JPATH_COMPONENT . '/controllers/' . $controller . '.php'))
{
	$controller = 'reditem';
	JRequest::setVar('view', 'cpanel');
}

$user        = JFactory::getUser();
$task        = JRequest::getVar('task', '');
$layout      = JRequest::getVar('layout', '');
$showbuttons = JRequest::getVar('showbuttons', '0');
$showall     = JRequest::getVar('showall', '0');
$document    = JFactory::getDocument();

$document->addStyleSheet(JURI::root() . 'administrator/components/com_reditem/assets/css/reditem.css');

$controller	= JControllerLegacy::getInstance('Reditem');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
