<?php
/**
 * @package     RedITEMCategoryFields.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('rjquery.select2', 'select');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$ordering = ($listOrder == 'f.ordering');
$saveOrder = ($listOrder == 'f.ordering' && $listDirn == 'asc');
$search = $this->state->get('filter.search');
$originalOrders = array();
$user = JFactory::getUser();
$userId = $user->id;

if ($saveOrder)
{
	JHTML::_('rsortablelist.sortable', 'table-items', 'adminForm', strtolower($listDirn), 'index.php?option=com_reditemcategoryfields&task=categoryfields.saveOrderAjax&tmpl=component', true, true);
}

$lang = JFactory::getLanguage();
$extension = 'com_reditem';
$base_dir = JPATH_ADMINISTRATOR;
$language_tag = $lang->getTag();
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);

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

		if (pressbutton == 'categoryfields.delete')
		{
			var r = confirm('<?php echo JText::_("COM_REDITEM_FIELD_DELETE_FIELDS")?>');
			if (r == true)    form.submit();
			else return false;
		}
		form.submit();
	}
</script>
<form action="index.php?option=com_reditemcategoryfields&view=categoryfields" class="admin" id="adminForm" method="post" name="adminForm">
	<div class="row-fluid">
		<div class="span6">
			<?php echo JText::_('COM_REDITEM_FILTER'); ?>
			<?php echo RLayoutHelper::render('search', array('view' => $this)) ?>
		</div>
		<div class="span3">
			<?php echo $this->filterForm->getLabel('filter_fieldtypes'); ?>
			<?php echo $this->filterForm->getInput('filter_fieldtypes'); ?>
		</div>
	</div>
	<table class="table table-striped" id="table-items">
		<thead>
			<tr>
				<th width="30" align="center">
					<?php echo '#'; ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
				</th>
				<th class="title">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_FIELD_NAME', 'f.name', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JText::_('COM_REDITEM_FIELD_FIELDCODE'); ?>
				</th>
				<th>
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_FIELD_FIELDTYPE', 'f.type', $listDirn, $listOrder); ?>
				</th>
				<?php if ($search == ''): ?>
				<th>
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_ORDERING', 'f.ordering', $listDirn, $listOrder); ?>
				</th>
				<?php endif; ?>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('rgrid.sort', 'JPUBLISHED', 'published', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" nowrap="nowrap">
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
			<?php
			if (!empty($this->items))
			{
				$n = count($this->items);

				foreach ($this->items as $i => $row)
				{
					$orderkey = array_search($row->id, $this->ordering[0]);

					$parentsStr = '';
				?>
					<tr>
						<td>
							<?php echo $this->pagination->getRowOffset($i); ?>
						</td>
						<td>
							<?php echo JHtml::_('grid.id', $i, $row->id); ?>
						</td>
						<td>
							<?php
							if ($row->checked_out)
							{
								$editor = JFactory::getUser($row->checked_out);
								$canCheckin = $row->checked_out == $userId || $row->checked_out == 0;
								echo JHtml::_('rgrid.checkedout', $i, $editor->name, $row->checked_out_time, 'categoryfields.', $canCheckin) . ' ' . $row->name;
							}
							else
							{
								echo JHtml::_('link', 'index.php?option=com_reditemcategoryfields&task=categoryfield.edit&id=' . $row->id, $row->name);
							}
							?>
						</td>
						<td>
							<?php echo $row->fieldcode; ?>
						</td>
						<td>
							<?php echo $row->type; ?>
						</td>
						<?php if ($search == ''): ?>
						<td class="order nowrap center">
							<span class="sortable-handler hasTooltip <?php echo ($saveOrder) ? '' : 'inactive' ;?>" title="<?php echo ($saveOrder) ? '' :JText::_('COM_REDITEM_ORDERING_DISABLED');?>">
								<i class="icon-move"></i>
							</span>
							<input type="text" style="display:none" name="order[]" value="<?php echo $orderkey + 1;?>" class="text-area-order" />
						</td>
						<?php endif; ?>
						<td align="center" width="8%">
							<?php echo JHtml::_('rgrid.published', $row->published, $i, 'categoryfields.', true, 'cb'); ?>
						</td>
						<td align="center" width="1%">
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
	<input type="hidden" name="original_order_values" value="<?php echo implode($originalOrders, ','); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
