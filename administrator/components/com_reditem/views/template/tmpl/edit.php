<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('rjquery.select2', 'select');

$isNew = true;

if ($this->item->id)
{
	$isNew = false;
}

?>
<script type="text/javascript">
	<?php
	if (!$isNew)
	{
	?>
	jQuery(document).ready(function()
	{
		jQuery('#jform_type_id').attr('readonly', true);
		jQuery('#jform_typecode').attr('readonly', true);
	});
	<?php
	}
	?>
</script>
<form enctype="multipart/form-data"
	action="index.php?option=com_reditem&task=template.edit&id=<?php echo $this->item->id; ?>"
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
				<?php echo $this->form->getLabel('typecode'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('typecode'); ?>
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
				<?php echo $this->form->getLabel('description'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('description'); ?>
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
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('content'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('content'); ?>
				<div class='template_tags'>
				<?php
				if (!$isNew)
				{
					switch ($this->item->typecode)
					{
						case 'view_itemdetail':
						case 'module_items':
							?>
							<div class="accordion" id="accordion_tag_default">
								<div class="accordion-group">
									<div class="accordion-heading">
										<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_tag_default" href="#collapseOne">
											<?php echo JText::_('COM_REDITEM_TEMPLATE_TAG_DEFAULT_VIEW_ITEMDETAIL'); ?>
										</a>
									</div>
									<div id="collapseOne" class="accordion-body collapse in">
										<div class="accordion-inner">
											<?php echo JText::_('COM_REDITEM_TEMPLATE_TAG_PRINT_ICON'); ?>
											<ul>
											<?php
											foreach ($this->itemTags as $tag => $tagDesc)
											{
												echo '<li><span>' . $tag . '</span>' . $tagDesc . '</li>';
											}
											?>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'view_categorydetail':
							?>
							<div class="accordion" id="accordion_tag_default">
								<div class="accordion-group">
									<div class="accordion-heading">
										<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_tag_default" href="#collapseOne">
											<?php echo JText::_('COM_REDITEM_TEMPLATE_TAG_DEFAULT_VIEW_CATEGORYDETAIL'); ?>
										</a>
									</div>
									<div id="collapseOne" class="accordion-body collapse in">
										<div class="accordion-inner">
											<?php
											echo JText::_('COM_REDITEM_TEMPLATE_TAG_PRINT_ICON');

											// Echo tag of current category
											echo '<ul>';

											foreach ($this->categoryTags as $tag => $tagDesc)
											{
												echo '<li>';

												if (is_array($tagDesc))
												{
													echo '<ul>';

													foreach ($tagDesc as $subTag => $subTagDesc)
													{
														echo '<li><span>' . $subTag . '</span>' . $subTagDesc . '</li>';
													}

													echo '</ul>';
												}
												else
												{
													echo '<span>' . $tag . '</span>' . $tagDesc;
												}

												echo '</li>';
											}

											echo '</ul>';
											?>
										</div>
									</div>
								</div>
							</div>
							<div class="accordion" id="accordion_tag_filter">
								<div class="accordion-group">
									<div class="accordion-heading">
										<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_tag_filter" href="#collapseFilter">
											<?php echo JText::_('COM_REDITEM_TEMPLATE_TAG_VIEW_CATEGORYDETAIL_FILTER'); ?>
										</a>
									</div>
									<div id="collapseFilter" class="accordion-body collapse in">
										<div class="accordion-inner">
											<ul>
											<?php
											foreach ($this->filterTags as $tag => $tagDesc)
											{
												echo '<li><span>' . $tag . '</span>' . $tagDesc . '</li>';
											}
											?>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="accordion" id="accordion_tag_filter">
								<div class="accordion-group">
									<div class="accordion-heading">
										<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_tag_filter" href="#collapseFilter">
											<?php echo JText::_('COM_REDITEM_TEMPLATE_TAG_VIEW_CATEGORYDETAIL_FILTER_SUB_CATEGORIES'); ?>
										</a>
									</div>
									<div id="collapseFilter" class="accordion-body collapse in">
										<div class="accordion-inner">
											<ul>
											<?php
											foreach ($this->filterCategoryExtraTags as $tag => $tagDesc)
											{
												echo '<li><span>' . $tag . '</span>' . $tagDesc . '</li>';
											}
											?>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<?php
							break;

						default:
							break;
					}
				}
				?>
				</div>
			</div>
		</div>
	</div>
	<?php echo $this->form->getInput('id'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
