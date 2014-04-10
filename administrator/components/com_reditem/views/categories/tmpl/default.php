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

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$ordering = ($listOrder == 'c.lft');
$saveOrder = ($listOrder == 'c.lft' && $listDirn == 'asc');
$search = $this->state->get('filter.search');
$originalOrders = array();
$user = JFactory::getUser();
$userId = $user->id;

if ($saveOrder)
{
	JHTML::_('rsortablelist.sortable', 'table-categories', 'adminForm', strtolower($listDirn), 'index.php?option=com_reditem&task=categories.saveOrderAjax&tmpl=component', true, true);
}

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

		if (pressbutton == 'categories.delete')
		{
			var r = confirm('<?php echo JText::_("COM_REDITEM_CATEGORY_DELETE_CATEGORIES")?>');
			if (r == true)    form.submit();
			else return false;
		}
		form.submit();
	}
</script>

<form action="index.php?option=com_reditem&view=categories" class="admin" id="adminForm" method="post" name="adminForm">
	<div class="row-fluid">
		<div class="span6">
			<?php echo RLayoutHelper::render('search', array('view' => $this)); ?>
		</div>
		<div class="span3">
			<?php echo $this->filterForm->getInput('filter_types'); ?>
		</div>
		<div class="span3">

		</div>
	</div>
	<table class="table table-striped" id="table-categories">
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
				<th width="30px" align="center">
				</th>
				<th class="title" width="auto">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_CATEGORY_CATEGORY', 'c.title', $listDirn, $listOrder); ?>
				</th>
				<th width="100">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_ITEM_TYPE', 'type_name', $listDirn, $listOrder); ?>
				</th>
				<th width="20%">					
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_CATEGORY_ASSIGNED_TEMPLATE', 'template_name', $listDirn, $listOrder); ?>
				</th>
				<th width="80">
					<?php echo JHtml::_('rgrid.sort', 'JGRID_HEADING_ACCESS', 'access_level', $listDirn, $listOrder); ?>
				</th>
				<?php if ($search == ''): ?>
				<th width="80">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_ORDERING', 'c.lft', $listDirn, $listOrder); ?>
				</th>
				<?php endif; ?>
				<th width="50" nowrap="nowrap">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_ID', 'c.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
		<tr>
			<?php if ($search == ''): ?>
			<td colspan="10">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
			<?php else : ?>
			<td colspan="9">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
			<?php endif; ?>
		</tr>
		</tfoot>
		<tbody>
		<?php if (!empty($this->items)) : ?>
			<?php $n = count($this->items); ?>
			<?php foreach ($this->items as $i => $item) : ?>
				<?php $orderkey = array_search($item->id, $this->ordering[$item->parent_id]); ?>
				<?php if ($item->level > 1) : ?>
					<?php
					$parentsStr = '';
					$_currentParentId = $item->parent_id;
					$parentsStr = ' ' . $_currentParentId;
					?>
					<?php for ($i2 = 0; $i2 < $item->level; $i2++) : ?>
						<?php foreach ($this->ordering as $k => $v) : ?>
							<?php
							$v = implode('-', $v);
							$v = '-' . $v . '-';
							?>
							<?php if (strpos($v, '-' . $_currentParentId . '-') !== false) : ?>
								<?php
								$parentsStr .= ' ' . $k;
								$_currentParentId = $k;
								break;
								?>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endfor; ?>
				<?php else : ?>
					<?php $parentsStr = ''; ?>
				<?php endif; ?>
				<tr sortable-group-id="<?php echo $item->parent_id;?>" item-id="<?php echo $item->id?>" parents="<?php echo $parentsStr?>" level="<?php echo $item->level?>">
					<td><?php echo $this->pagination->getRowOffset($i); ?></td>
					<td><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
					<td align="center">
						<fieldset class="btn-group">
						<?php echo JHtml::_('rgrid.published', $item->published, $i, 'categories.', true, 'cb'); ?>
						<?php if ($item->featured) : ?>
							<?php echo JHtml::_('rgrid.action', $i, 'setUnFeatured', 'categories.', '', '', '', false, 'star featured', 'star featured', true, true, 'cb'); ?>
						<?php else : ?>
							<?php echo JHtml::_('rgrid.action', $i, 'setFeatured', 'categories.', '', '', '', false, 'star-empty', 'star-empty', true, true, 'cb'); ?>
						<?php endif; ?>
						</fieldset>
					</td>
					<td>
						<?php if ($item->checked_out) : ?>
							<?php
							$editor = JFactory::getUser($item->checked_out);
							$canCheckin = $item->checked_out == $userId || $item->checked_out == 0;
							echo JHtml::_('rgrid.checkedout', $i, $editor->name, $item->checked_out_time, 'categories.', $canCheckin);
							?>
						<?php endif; ?>
					</td>
					<td>
						<?php echo str_repeat('<span class="gi">|&mdash;</span>', $item->level - 1) ?>
						<?php if ($item->checked_out) : ?>
							<?php echo $this->escape($item->title); ?>
						<?php else : ?>
							<?php echo JHtml::_('link', 'index.php?option=com_reditem&task=category.edit&id=' . $item->id, $this->escape($item->title)); ?>
						<?php endif; ?>
					</td>
					<td>
						<?php echo $item->type_name; ?>
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
	<input type="hidden" name="view" value="categories" />
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<input type="hidden" name="original_order_values" value="<?php echo implode($originalOrders, ','); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
