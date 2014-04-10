<?php
/**
 * @package     RedITEMCategoryFields.Backend
 * @subpackage  Entry point
 *
 * @copyright   Copyright (C) 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();

// Register component prefix
JLoader::registerPrefix('RedITEMCategoryFields', __DIR__);

// Load redITEM Library
JLoader::import('reditem.library');

$controller = JRequest::getVar('view', 'categoryfields');

// Set the controller page
if (!file_exists(JPATH_COMPONENT . '/controllers/' . $controller . '.php'))
{
	$controller = 'reditemcategoryfields';
	JRequest::setVar('view', 'categoryfields');
}

$user        = JFactory::getUser();
$task        = JRequest::getVar('task', '');
$layout      = JRequest::getVar('layout', '');
$showbuttons = JRequest::getVar('showbuttons', '0');
$showall     = JRequest::getVar('showall', '0');
$document    = JFactory::getDocument();

$controller	= JControllerLegacy::getInstance('reditemcategoryfields');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
