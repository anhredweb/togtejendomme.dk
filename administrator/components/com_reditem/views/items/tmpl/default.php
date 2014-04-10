<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENi.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('rjquery.select2', 'select');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$ordering = ($listOrder == 'i.ordering');
$saveOrder = ($listOrder == 'i.ordering' && $listDirn == 'asc');
$search = $this->state->get('filter.search');
$originalOrders = array();
$user = JFactory::getUser();
$userId = $user->id;

if ($saveOrder)
{
	JHTML::_('rsortablelist.sortable', 'table-items', 'adminForm', strtolower($listDirn), 'index.php?option=com_reditem&task=items.saveOrderAjax&tmpl=component', true, true);
}
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

		if (pressbutton == 'items.delete')
		{
			var r = confirm('<?php echo JText::_("COM_REDITEM_ITEM_DELETE_ITEMS")?>');
			if (r == true)    form.submit();
			else return false;
		}
		form.submit();
	}
</script>
<form action="index.php?option=com_reditem&view=items" class="admin" id="adminForm" method="post" name="adminForm">
	<div class="row-fluid">
		<div class="span6">
			<?php echo RLayoutHelper::render('search', array('view' => $this)) ?>
		</div>
		<div class="span2">
			<?php echo $this->filterForm->getInput('filter_types'); ?>
		</div>
		<div class="span2">
			<?php echo $this->filterForm->getInput('filter_published'); ?>
		</div>
		<div class="span3">

		</div>
	</div>
	<table class="table table-striped" id="table-items">
		<thead>
			<tr>
				<th width="10" align="center">
					<?php echo '#'; ?>
				</th>
				<th width="10">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
				</th>
				<th width="30" nowrap="nowrap">
					<?php echo JText::_('JSTATUS'); ?>
				</th>
				<th width="30px" nowrap="nowrap">
				</th>
				<th class="title" width="auto">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_ITEM_NAME', 'i.title', $listDirn, $listOrder); ?>
				</th>
				<th width="80">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_ITEM_TYPE', 'type_name', $listDirn, $listOrder); ?>
				</th>
				<th width="15%">
					<?php echo JText::_('COM_REDITEM_ITEM_CATEGORIES'); ?>
				</th>
				<th width="15%">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_ITEM_TEMPLATE', 'template_name', $listDirn, $listOrder); ?>
				</th>
				<th width="80">
					<?php echo JHtml::_('rgrid.sort', 'JGRID_HEADING_ACCESS', 'access_level', $listDirn, $listOrder); ?>
				</th>
				<?php if ($search == ''): ?>
				<th width="80">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_ORDERING', 'i.ordering', $listDirn, $listOrder); ?>
				</th>
				<?php endif; ?>
				<th width="50" nowrap="nowrap">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_ID', 'i.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
		<tr>
			<?php if ($search == ''): ?>
			<td colspan="11">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
			<?php else : ?>
			<td colspan="10">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
			<?php endif; ?>
		</tr>
		</tfoot>
		<tbody>
		<?php if (!empty($this->items)) : ?>
			<?php $n = count($this->items); ?>
			<?php foreach ($this->items as $i => $item) : ?>
				<?php
				$orderkey = array_search($item->id, $this->ordering[0]);				
				?>
				<tr>
					<td><?php echo $this->pagination->getRowOffset($i); ?></td>
					<td><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
					<td align="center">
						<fieldset class="btn-group">
							<?php echo JHtml::_('rgrid.published', $item->published, $i, 'items.', true, 'cb'); ?>
							<?php if ($item->featured) : ?>
								<?php echo JHtml::_('rgrid.action', $i, 'setUnFeatured', 'items.', '', '', '', false, 'star featured', 'star featured', true, true, 'cb'); ?>
							<?php else : ?>
								<?php echo JHtml::_('rgrid.action', $i, 'setFeatured', 'items.', '', '', '', false, 'star-empty', 'star-empty', true, true, 'cb'); ?>
							<?php endif; ?>
						</fieldset>
					</td>
					<td>
						<?php if ($item->checked_out) : ?>
							<?php
							$editor = JFactory::getUser($item->checked_out);
							$canCheckin = $item->checked_out == $userId || $item->checked_out == 0;
							echo JHtml::_('rgrid.checkedout', $i, $editor->name, $item->checked_out_time, 'items.', $canCheckin);
							?>
						<?php endif; ?>
					</td>
					<td>
						<?php if ($item->checked_out) : ?>
							<?php echo $item->title; ?>
						<?php else : ?>
							<?php echo JHtml::_('link', 'index.php?option=com_reditem&task=item.edit&id=' . $item->id, $item->title); ?>
						<?php endif; ?>
					</td>
					<td>
						<?php echo $item->type_name; ?>
					</td>
					<td>
						<?php if (isset($item->categories)) : ?>
							<?php $categories = array(); ?>
							<?php foreach ($item->categories As $cat) : ?>
								<?php $categories[] = $cat->title; ?>
							<?php endforeach; ?>
							<?php echo implode('<br />', $categories); ?>
						<?php endif; ?>
					</td>
					<td>
						<?php echo $item->template_name; ?>
					</td>
					<td class="center">
						<?php echo $this->escape($item->access_level); ?>
					</td>
					<?php if ($search == ''): ?>
					<td class="order nowrap center">
						<span class="sortable-handler hasTooltip <?php echo ($saveOrder) ? '' : 'inactive' ;?>" title="<?php echo ($saveOrder) ? '' :JText::_('COM_REDITEM_ORDERING_DISABLED');?>"><i class="icon-move"></i></span>
						<input type="text" style="display:none" name="order[]" value="<?php echo $orderkey + 1;?>" class="text-area-order" />
					</td>
					<?php endif; ?>
					<td align="center">
						<?php echo $item->id; ?>
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
	<input type="hidden" name="original_order_values" value="<?php echo implode($originalOrders, ','); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
