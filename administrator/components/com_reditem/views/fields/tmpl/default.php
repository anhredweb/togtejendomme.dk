<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('rjquery.select2', 'select');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$user = JFactory::getUser();
$userId = $user->id;

// @ToDo: order has been not coded yet awaiting to be fixed in redCORE
?>
<script type="text/javascript">
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

		if (pressbutton == 'fields.delete')
		{
			var r = confirm('<?php echo JText::_("COM_REDITEM_FIELD_DELETE_FIELDS")?>');
			if (r == true)    form.submit();
			else return false;
		}
		form.submit();
	}
</script>
<form action="index.php?option=com_reditem&view=fields" class="admin" id="adminForm" method="post" name="adminForm">
	<div class="row-fluid">
		<div class="span6">
			<?php echo RLayoutHelper::render('search', array('view' => $this)) ?>
		</div>
		<div class="span6">
			<?php echo $this->filterForm->getInput('filter_types'); ?>
			<?php echo $this->filterForm->getInput('filter_fieldtypes'); ?>
		</div>
	</div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="10" align="center">
					<?php echo '#'; ?>
				</th>
				<th width="10">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
				</th>
				<th width="30" nowrap="nowrap">
					<?php echo JHTML::_('rgrid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?>
				</th>
				<th width="1" nowrap="nowrap">
				</th>
				<th width="100">
					<?php echo JText::_('COM_REDITEM_FIELD_TYPE'); ?>
				</th>
				<th class="title" width="auto">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_FIELD_NAME', 'f.name', $listDirn, $listOrder); ?>
				</th>
				<th width="150">
					<?php echo JText::_('COM_REDITEM_FIELD_FIELDCODE'); ?>
				</th>
				<th width="150">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_FIELD_FIELDTYPE', 'f.type', $listDirn, $listOrder); ?>
				</th>
				<th width="10">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_ID', 'f.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="9">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php if (!empty($this->items)) : ?>
				<?php
				$n = count($this->items);
				?>
				<?php foreach ($this->items as $i => $row) : ?>
					<tr>
						<td>
							<?php echo $this->pagination->getRowOffset($i); ?>
						</td>
						<td>
							<?php echo JHtml::_('grid.id', $i, $row->id); ?>
						</td>
						<td>
							<?php echo JHtml::_('rgrid.published', $row->published, $i, 'fields.', true, 'cb'); ?>
						</td>
						<td>
							<?php if ($row->checked_out) : ?>
								<?php
								$editor = JFactory::getUser($row->checked_out);
								$canCheckin = $row->checked_out == $userId || $row->checked_out == 0;
								echo JHtml::_('rgrid.checkedout', $i, $editor->name, $row->checked_out_time, 'fields.', $canCheckin);
								?>
							<?php endif; ?>
						</td>
						<td>
							<?php echo $row->type_name; ?>
						</td>
						<td>
							<?php if ($row->checked_out) : ?>
								<?php echo $row->name; ?>
							<?php else : ?>
								<?php echo JHtml::_('link', 'index.php?option=com_reditem&task=field.edit&id=' . $row->id, $row->name); ?>
							<?php endif; ?>
						</td>
						<td>
							<?php echo $row->fieldcode; ?>
						</td>
						<td>
							<?php echo $row->type; ?>
						</td>
						<td>
							<?php echo $row->id; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
