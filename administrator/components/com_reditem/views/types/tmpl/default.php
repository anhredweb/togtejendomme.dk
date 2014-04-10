<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Types
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('rjquery.select2', 'select');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

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

		if (pressbutton == 'types.delete')
		{
			var r = confirm('<?php echo JText::_("COM_REDITEM_TYPE_DELETE_TYPES")?>');
			if (r == true)    form.submit();
			else return false;
		}
		form.submit();
	}
</script>
<form action="index.php?option=com_reditem&view=types" class="admin" id="adminForm" method="post" name="adminForm">
	<div class="row-fluid">
		<div class="span6">
			<?php echo JText::_('COM_REDITEM_FILTER'); ?>
			<?php echo RLayoutHelper::render('search', array('view' => $this)) ?>
		</div>
		<div class="span6">

		</div>
	</div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="30" align="center">
					<?php echo '#'; ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
				</th>
				<th class="title">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_TYPE_NAME', 'ty.title', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JText::_('COM_REDITEM_TYPE_DESCRIPTION'); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_ID', 'ty.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="8">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
		</tfoot>
		<tbody>
		<?php
		if (!empty($this->items))
		{
			$n = count($this->items);

			foreach ($this->items as $i => $row)
			{
			?>
				<tr>
					<td><?php echo $this->pagination->getRowOffset($i); ?></td>
					<td><?php echo JHtml::_('grid.id', $i, $row->id); ?></td>
					<td>
						<?php echo JHtml::_('link', 'index.php?option=com_reditem&task=type.edit&id=' . $row->id, $row->title); ?>
					</td>
					<td>
						<?php echo $row->description; ?>
					</td>
					<td align="center" width="5%">
						<?php echo $row->id; ?>
					</td>
				</tr>
			<?php
			}
		}
		?>
		</tbody>
	</table>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
