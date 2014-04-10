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
defined('_JEXEC') or die;

$published = $this->state->get('filter.published');
?>
<!--<div class="modal hide fade" id="collapseModal">
	<div class="modal-header">
		<button type="button" role="presentation" class="close" data-dismiss="modal">x</button>
		<h3><?php //echo JText::_('COM_FAQBOOKPRO_BATCH_OPTIONS');?></h3>
	</div>
	<div class="modal-body">
		<p><?php //echo JText::_('COM_FAQBOOKPRO_BATCH_TIP'); ?></p>
		<div class="control-group">
			<div class="controls">
				<?php //echo JHtml::_('batch.access');?>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<?php //echo JHtml::_('batch.language'); ?>
			</div>
		</div>
		<?php //if ($published >= 0) : ?>
		<div class="control-group">
			<div class="controls">
				<?php //echo JHtml::_('batch.item', 'com_faqbookpro');?>
			</div>
		</div>
		<?php //endif; ?>
	</div>
	<div class="modal-footer">
		<button class="btn" type="button" onclick="document.id('batch-category-id').value='';document.id('batch-access').value='';document.id('batch-language-id').value='';document.id('batch-tag-id)').value=''" data-dismiss="modal">
			<?php //echo JText::_('JCANCEL'); ?>
		</button>
		<button class="btn btn-primary" type="submit" onclick="Joomla.submitbutton('article.batch');">
			<?php //echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
		</button>
	</div>
</div>-->

<fieldset class="batch">
	<legend><?php echo JText::_('COM_FAQBOOKPRO_BATCH_OPTIONS');?></legend>
	<p><?php echo JText::_('COM_FAQBOOKPRO_BATCH_TIP'); ?></p>
	<?php echo JHtml::_('batch.access');?>
	<?php echo JHtml::_('batch.language'); ?>

	<?php if ($published >= 0) : ?>
		<?php echo JHtml::_('batch.item', 'com_faqbookpro');?>
	<?php endif; ?>

	<button type="submit" onclick="Joomla.submitbutton('article.batch');">
		<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
	</button>
	<button type="button" onclick="document.id('batch-category-id').value='';document.id('batch-access').value='';document.id('batch-language-id').value=''">
		<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
	</button>
</fieldset>

