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

?>

<form enctype="multipart/form-data"
	action="index.php?option=com_reditem&task=type.edit&id=<?php echo $this->item->id; ?>"
	method="post" name="adminForm" class="form-validate form-horizontal" id="adminForm"
>
	<ul class="nav nav-tabs" id="categoryTab">
		<li class="active">
			<a href="#infor" data-toggle="tab">
				<strong><?php echo JText::_('COM_REDITEM_GENERAL_INFORMATION'); ?></strong>
			</a>
		</li>
		<li>
			<a href="#config" data-toggle="tab">
				<strong><?php echo JText::_('COM_REDITEM_CONFIGURATION'); ?></strong>
			</a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="infor">
			<div class="row-fluid">
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
						<?php echo $this->form->getLabel('description'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('description'); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="config">
			<div class="row-fluid">
				<?php
				$firstSpacer = true;

				foreach ($this->form->getGroup('params') as $field)
				{
					?>
					<div class="control-group">
					<?php
					if ($field->type == 'Spacer')
					{
						if (!$firstSpacer)
						{
							echo '<hr />';
						}
						else
						{
							$firstSpacer = false;
						}

						echo $field->label;
					}
					elseif ($field->hidden)
					{
						echo $field->input;
					}
					else
					{
					?>
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					<?php
					}
					?>
					</div>
				<?php
				}
				?>
			</div>
		</div>
	</div>
	<?php echo $this->form->getInput('id'); ?>
	<?php echo $this->form->getInput('table_name'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
