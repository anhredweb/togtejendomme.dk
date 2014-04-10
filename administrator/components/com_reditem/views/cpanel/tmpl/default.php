<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JHtml::_('rjquery.chosen', 'select');
?>
<script type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	};
</script>
<div id="rcCpanel-main-container" class="row-fluid">
	<div class="span7 rcCpanelMainIcons">
		<div class="well-small">			
			<div class="row-striped">
				<div class="row-fluid">
					<?php $this->renderCpanelIconSet($this->iconArray['reditem']); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="span5 rcCpanelSideIcons">
		<div class="well well-small">
			<div class="well-small">
				<strong class="row-title"><?php echo JText::_('COM_REDITEM_VERSION'); ?></strong>
				<span class="badge badge- hasTooltip" title="<?php echo JText::_('COM_REDITEM_VERSION'); ?>">
					<?php echo $this->reditemversion; ?>
				</span>
			</div>
		</div>
	</div>
</div>
