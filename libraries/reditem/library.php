<?php
/**
 * RedITEM Library file.
 * Including this file into your application will make redITEM available to use.
 *
 * @package    RedITEM.Library
 * @copyright  Copyright (C) 2013 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later, see LICENSE.
 */

defined('JPATH_PLATFORM') or die;

// Define redITEM Library Folder Path
define('JPATH_REDITEM_LIBRARY', __DIR__);

// Load redITEM Library
JLoader::import('redcore.bootstrap');

// Bootstraps redCORE
RBootstrap::bootstrap();

// Register library prefix
RLoader::registerPrefix('Reditem', JPATH_REDITEM_LIBRARY);

// Make available the redITEM fields
JFormHelper::addFieldPath(JPATH_REDITEM_LIBRARY . '/form/fields');

// Make available the redITEM form rules
JFormHelper::addRulePath(JPATH_REDITEM_LIBRARY . '/form/rules');
