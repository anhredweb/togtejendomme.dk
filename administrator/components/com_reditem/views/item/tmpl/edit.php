<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Item
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die();

JHTML::_('behavior.formvalidation');
JHtml::_('rjquery.select2', 'select');
JHtml::_('behavior.modal', 'a.modal-thumb');

$isNew = true;

if ($this->item->id)
{
	$isNew = false;
}

if (! $isNew)
{
	// Disable Change field code (Column name)
?>
<script type="text/javascript">
	jQuery(document).ready(function()
	{
		jQuery('#jform_type_id').attr('readonly', true);
	});

	/*
	 * Add form validation
	 */
	Joomla.submitbutton = function (pressbutton)
	{
		submitbutton(pressbutton);
	}

	submitbutton = function (pressbutton)
	{
		var form = document.adminForm;

		if (pressbutton)
		{
			form.task.value = pressbutton;
		}

		if ((pressbutton != 'item.close') && (pressbutton != 'item.cancel'))
		{
			if (document.formvalidator.isValid(form))
			{
				form.submit();
			}
		}
		else
		{
			form.submit();
		}
	}
</script>
<?php
}
?>
<script type="text/javascript">
	function jInsertEditorText(tag, editor)
	{
		var img = jQuery(tag);
		var field = jQuery('#jform_item_image_media');
		field.val(img.attr('src'));
		jQuery('#media_thumb_preview').html('<img src="<?php echo JURI::root(); ?>' + img.attr('src') + '" style="max-width: 100px; max-height: 100px;" />');
	}
</script>
<form enctype="multipart/form-data"
	action="index.php?option=com_reditem&task=item.edit&id=<?php echo $this->item->id; ?>"
	method="post" name="adminForm" class="form-validate"
	id="adminForm">
	<ul class="nav nav-tabs" id="categoryTab">
		<li class="active">
			<a href="#item-information" data-toggle="tab"><strong><?php echo JText::_('COM_REDITEM_GENERAL_INFORMATION'); ?></strong></a>
		</li>
		<li>
			<a href="#item-customfields" data-toggle="tab" id="additional-link"><strong><?php echo JText::_('COM_REDITEM_ADDITIONAL_INFORMATION'); ?></strong></a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="item-information">
			<div class="row-fluid">
				<div class="span9">
					<fieldset class="form-horizontal">
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
								<?php echo $this->form->getLabel('title'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('title'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('categories'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('categories'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('access'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('access'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('template_id'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('template_id'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('item_image_file'); ?>
							</div>
							<div class="controls">
								<div class="media">
									<?php if ($this->form->getValue('item_image')) : ?>
									<?php $img_src = JURI::root() . 'components/com_reditem/assets/images/item/' . $this->item->id . '/' . $this->form->getValue('item_image'); ?>
										<img style="max-width: 300px; max-height: 300px; margin-right: 20px;" class="preview_img img-polaroid pull-left" src="<?php echo $img_src; ?>" />
									<?php endif; ?>
									<div class="media-body">
										<ul>
											<?php if ($this->form->getValue('item_image')) : ?>
											<li>
												<label class="checkbox">
													<input type="checkbox" name="jform[item_image_remove]" value="<?php $this->form->getValue('item_image'); ?>" />
													<?php echo JText::_('COM_REDITEM_CUSTOMFIELD_IMAGE_REMOVE'); ?>
												</label>
											</li>
											<?php endif; ?>
											<li>
												<?php
												echo $this->form->getInput('item_image_file');
												echo $this->form->getInput('item_image');
												?>
												<div class="clearfix"></div>
											</li>
											<li>
												<a class="modal-thumb btn" href="index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;e_name=item_image"
								   				rel="{handler: 'iframe', size: {x: 1050, y: 450}}" title=""><?php echo JText::_('COM_REDITEM_ITEM_IMAGE_MEDIA'); ?></a>
								   				<input type="hidden" id="jform_item_image_media" name="jform[item_image_media]" value="" />
								   				<div id="media_thumb_preview"></div>
								   				<div class="clearfix"></div>
								   			</li>
							   				<?php foreach ($this->form->getFieldset('item_image_params') as $field) : ?>
							   					<li>
													<?php echo $field->label; ?>
								   					<?php echo $field->input; ?>
								   					<div class="clearfix"></div>
							   					</li>
							   				<?php endforeach; ?>
						   				</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('introtext'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('introtext'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('fulltext'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('fulltext'); ?>
							</div>
						</div>
						<!-- <div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('keywords'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('keywords'); ?>
							</div>
						</div> -->
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('featured'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('featured'); ?>
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
					</fieldset>
				</div>
				<div class="span3">
					<fieldset class="form-vertical">
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('created_user_id'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('created_user_id'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('created_time'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('created_time'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('modified_user_id'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('modified_user_id'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('modified_time'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('modified_time'); ?>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="item-customfields">
			<?php echo $this->loadTemplate('customfields'); ?>
		</div>
	</div>
	<?php echo $this->form->getInput('id'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
