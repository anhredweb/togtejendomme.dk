<?php
/**
 * @package     RedITEM.Frontend
 * @subpackage  mod_reditem_categories
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/*require_once JPATH_LIBRARIES . '/redcore/bootstrap.php';*/

require_once JPATH_SITE . '/modules/mod_reditem_categories/helper.php';

$categories = ModredITEMCategoriesHelper::getList($params);

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_reditem_categories', $params->get('layout', 'default'));
