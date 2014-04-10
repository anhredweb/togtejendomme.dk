<?php
/**
 * @package     RedITEM.Frontend
 * @subpackage  RedITEM
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
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
$jinput = JFactory::getApplication()->input;

JLoader::import('joomla.html.parameter');

$option = $jinput->getCmd('option');
$view   = $jinput->getCmd('view');

// Register component prefix
JLoader::registerPrefix('Reditem', __DIR__);

// Register library prefix
RLoader::registerPrefix('Reditem', JPATH_LIBRARIES . '/reditem');

// Loading helper
JLoader::import('reditem', JPATH_COMPONENT . '/helpers');
JLoader::import('tags', JPATH_COMPONENT . '/helpers');
JLoader::import('route', JPATH_COMPONENT . '/helpers');
JLoader::import('imagegenerator', JPATH_COMPONENT . '/helpers');

JLoader::import('joomla.html.pagination');

JLoader::import('pagination', JPATH_COMPONENT . '/helpers');
JLoader::import('helper', JPATH_COMPONENT . '/helpers');

RHelperAsset::load('reditem.css');

$Itemid = $jinput->getInt('Itemid');

$controller = $jinput->getCmd('view');

// Set the controller page
if (!file_exists(JPATH_COMPONENT . '/controllers/' . $controller . '.php'))
{
	$controller = 'categorydetail';
	$jinput->set('view', 'categorydetail');
}

require_once JPATH_COMPONENT . '/controllers/' . $controller . '.php';

// Set a default task if none is present, this is needed to be able to override the display task
$jinput->set('task', $jinput->getCmd('task', $jinput->get('view') . '.display'));

// Execute the controller
$controller = JControllerLegacy::getInstance('reditem');
$controller->execute($jinput->get('task'));
$controller->redirect();
