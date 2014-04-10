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

		if (pressbutton == 'templates.delete')
		{
			var r = confirm('<?php echo JText::_("COM_REDITEM_TEMPLATE_DELETE_TEMPLATES")?>');
			if (r == true)    form.submit();
			else return false;
		}
		form.submit();
	}
</script>
<form action="index.php?option=com_reditem&view=templates" class="admin" id="adminForm" method="post" name="adminForm">
	<div class="row-fluid">
		<div class="span6">
			<?php echo JText::_('COM_REDITEM_FILTER'); ?>
			<?php echo RLayoutHelper::render('search', array('view' => $this)) ?>
		</div>
		<div class="span3">
			<?php echo $this->filterForm->getLabel('filter_types'); ?>
			<?php echo $this->filterForm->getInput('filter_types'); ?>
		</div>
		<div class="span3">

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
					<?php echo JHTML::_('rgrid.sort', 'JSTATUS', 't.published', $listDirn, $listOrder); ?>
				</th>
				<th width="1" align="center">
				</th>
				<th class="title" width="auto">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_TEMPLATE_NAME', 't.name', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('COM_REDITEM_TEMPLATE_TYPE'); ?>
				</th>
				<th width="10%">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_TEMPLATE_FOR', 't.typecode', $listDirn, $listOrder); ?>
				</th>
				<th width="20%">
					<?php echo JText::_('COM_REDITEM_TEMPLATE_DESCRIPTION'); ?>
				</th>
				<th width="10" nowrap="nowrap">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_ID', 't.id', $listDirn, $listOrder); ?>
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
					<td><?php echo $this->pagination->getRowOffset($i); ?></td>
					<td><?php echo JHtml::_('grid.id', $i, $row->id); ?></td>
					<td>
						<?php echo JHtml::_('rgrid.published', $row->published, $i, 'templates.', true, 'cb'); ?>
					</td>
					<td>
						<?php if ($row->checked_out) : ?>
							<?php
							$editor = JFactory::getUser($row->checked_out);
							$canCheckin = $row->checked_out == $userId || $row->checked_out == 0;
							echo JHtml::_('rgrid.checkedout', $i, $editor->name, $row->checked_out_time, 'templates.', $canCheckin);
							?>
						<?php endif; ?>
					</td>
					<td>
						<?php if ($row->checked_out) : ?>
							<?php echo $row->name; ?>
						<?php else : ?>
							<?php echo JHtml::_('link', 'index.php?option=com_reditem&task=template.edit&id=' . $row->id, $row->name); ?>
						<?php endif; ?>
					</td>
					<td>
						<?php echo $row->type_name; ?>
					</td>
					<td>
						<?php
						switch ($row->typecode)
						{
							case 'view_itemdetail':
								echo JText::_('COM_REDITEM_TEMPLATE_TYPE_VIEW_ITEMDETAIL');
								break;
							case 'view_categorydetail':
								echo JText::_('COM_REDITEM_TEMPLATE_TYPE_VIEW_CATEGORYDETAIL');
								break;
							case 'module_search':
								echo JText::_('COM_REDITEM_TEMPLATE_TYPE_SEARCH_MODULE');
								break;

							case 'module_items':
								echo JText::_('COM_REDITEM_TEMPLATE_TYPE_ITEMS_MODULE');
								break;
							default:
								break;
						}
						?>
					</td>
					<td>
						<?php echo $row->description; ?>
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
