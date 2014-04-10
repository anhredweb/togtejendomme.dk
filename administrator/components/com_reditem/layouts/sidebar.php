<?php
/**
 * @package     RedITEM
 * @subpackage  Layouts
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */


defined('JPATH_REDCORE') or die;

?>
<ul class="nav nav-tabs nav-stacked">
	<li>
		<a href="<?php echo JRoute::_('index.php?option=com_reditem&view=types'); ?>">
			<i class="icon-list"></i>
			<?php echo JText::_('COM_REDITEM_SIDEBAR_TYPES') ?>
		</a>
	</li>
	<li>
		<a href="<?php echo JRoute::_('index.php?option=com_reditem&view=categories'); ?>">
			<i class="icon-folder-open"></i>
			<?php echo JText::_('COM_REDITEM_SIDEBAR_CATEGORIES') ?>
		</a>
	</li>
	<li>
		<a href="<?php echo JRoute::_('index.php?option=com_reditem&view=items'); ?>">
			<i class="icon-file"></i>
			<?php echo JText::_('COM_REDITEM_SIDEBAR_ITEMS') ?>
		</a>
	</li>
	<li>
		<a href="<?php echo JRoute::_('index.php?option=com_reditem&view=fields'); ?>">
			<i class="icon-bookmark"></i>
			<?php echo JText::_('COM_REDITEM_SIDEBAR_CUSTOMFIELDS') ?>
		</a>
	</li>
	<li>
		<a href="<?php echo JRoute::_('index.php?option=com_reditem&view=templates'); ?>">
			<i class="icon-hdd"></i>
			<?php echo JText::_('COM_REDITEM_SIDEBAR_TEMPLATES') ?>
		</a>
	</li>
	<!-- <li>
		<a href="<?php echo JRoute::_('index.php?option=com_reditem&view=keywords'); ?>">
			<i class="icon-tags"></i>
			<?php echo JText::_('COM_REDITEM_SIDEBAR_KEYWORDS') ?>
		</a>
	</li> -->
</ul>
