<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Field
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('rjquery.select2', 'select');
JHTML::_('behavior.formvalidation');

$isNew = true;

if ($this->item->id)
{
	$isNew = false;
}

$lists = array('select', 'radio', 'checkbox', 'date');

?>
<script type="text/javascript">
	function showHideOptions()
	{
		var lists = ['select', 'radio', 'checkbox', 'date'];
		if (jQuery.inArray(jQuery('#jform_type').val(), lists) != -1)
		{
			jQuery('#options_div').removeClass('hidden');
		}
		else
		{
			jQuery('#options_div').addClass('hidden');
		}
		// Clear data of options
		jQuery('#jform_options').val('');
	}

	<?php if (!$isNew): ?>
	jQuery(document).ready(function()
	{
		jQuery('#jform_fieldcode').attr('readonly', true);
		jQuery('#jform_type_id').attr('readonly', true);
		
		<?php if (in_array($this->form->getValue('type'), $lists)) : ?>
		jQuery('#options_div').removeClass('hidden');
		<?php endif; ?>
	});
	<?php endif; ?>
</script>

<form enctype="multipart/form-data"
	action="index.php?option=com_reditem&task=field.edit&id=<?php echo $this->item->id; ?>"
	method="post" name="adminForm" class="form-validate form-horizontal" id="adminForm">
	<div class="row-fluid">
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('type_id'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('type_id'); ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('name'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('name'); ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('type'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('type'); ?>
			</div>
		</div>
		<div class="control-group hidden" id="options_div">
			<div class="control-label">
				<?php echo $this->form->getLabel('options'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('options'); ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('published'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('published'); ?>
			</div>
		</div>
		<?php foreach ($this->form->getGroup('params') as $field) : ?>
		<div class="control-group">
			<?php if ($field->type == 'Spacer') : ?>
				<?php if (!$firstSpacer) : ?>
					<hr />
				<?php else : ?>
					<?php $firstSpacer = false; ?>
				<?php endif; ?>
				<?php echo $field->label; ?>
			<?php elseif ($field->hidden) : ?>
				<?php echo $field->input; ?>
			<?php else : ?>
			<div class="control-label">
				<?php echo $field->label; ?>
			</div>
			<div class="controls">
				<?php echo $field->input; ?>
			</div>
			<?php endif; ?>
		</div>
		<?php endforeach; ?>
	</div>
	<?php echo $this->form->getInput('id'); ?>
	<?php echo $this->form->getInput('fieldcode'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
