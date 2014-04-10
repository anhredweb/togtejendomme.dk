<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENi.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTML::_('behavior.formvalidation');

JHtml::_('rjquery.select2', 'select');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

$object = JFactory::getApplication()->input->getVar('object');

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

		form.submit();
	}
</script>
<form action="index.php?option=com_reditem&view=items&layout=elements&tmpl=component&object=<?php echo $object; ?>" class="admin" id="adminForm" method="post" name="adminForm">
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
				<th width="30" align="center">
					<?php echo '#'; ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
				</th>
				<th class="title">
					<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_ITEM_NAME', 'i.title', $listDirn, $listOrder); ?>
				</th>
				<th>
				<?php echo JHTML::_('rgrid.sort', 'COM_REDITEM_ITEM_TYPE', 'i.type_id', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JText::_('COM_REDITEM_ITEM_CATEGORIES'); ?>
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
						<a style="cursor: pointer;" onclick="window.parent.jRISelectItem('<?php echo $row->id; ?>', '<?php echo str_replace(array("'", "\""), array("\\'", ""), $row->title); ?>', '<?php echo $object; ?>');">
							<?php echo $row->title; ?>
						</a>
					</td>
					<td>
						<?php echo $row->type_name; ?>
					</td>
					<td>
						<?php
						if (isset($row->categories))
						{
							$categories = array();

							foreach ($row->categories As $cat)
							{
								$categories[] = $cat->title;
							}

							echo implode('<br />', $categories);
						}
						?>
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
