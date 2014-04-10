<?php
/**
* @title			FAQ Book Pro
* @version   		3.x
* @copyright   		Copyright (C) 2011-2013 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @author email   	info@minitek.gr
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
//JHtml::_('dropdown.init');
//JHtml::_('formbehavior.chosen', 'select');

$app		= JFactory::getApplication();
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$saveOrder	= $listOrder == 'a.ordering';
/*if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_faqbookpro&task=articles.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
*/
$sortFields = $this->getSortFields();
$assoc		= isset($app->item_associations) ? $app->item_associations : 0;
?>
<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_faqbookpro&view=articles'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
        
                    <label for="filter-search-lbl" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
                    <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip" title="<?php echo JText::_('COM_FAQBOOKPRO_FILTER_SEARCH_DESC'); ?>" />
                    <button type="submit" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
                    <button type="button" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
        </div>
            
        <div class="filter-select fltrt">
                    <select name="filter_published" class="inputbox" onchange="this.form.submit()">
                        <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
                        <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
                    </select>
                    
                    <select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
                        <option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
                        <?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_faqbookpro'), 'value', 'text', $this->state->get('filter.category_id'));?>
                    </select>
                    
                    <select name="filter_level" class="inputbox" onchange="this.form.submit()">
                        <option value=""><?php echo JText::_('JOPTION_SELECT_MAX_LEVELS');?></option>
                        <?php echo JHtml::_('select.options', $this->f_levels, 'value', 'text', $this->state->get('filter.level'));?>
                    </select>
                    
                    <select name="filter_access" class="inputbox" onchange="this.form.submit()">
                        <option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS');?></option>
                        <?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'));?>
                    </select>
                    
                    <select name="filter_author_id" class="inputbox" onchange="this.form.submit()">
                        <option value=""><?php echo JText::_('JOPTION_SELECT_AUTHOR');?></option>
                        <?php echo JHtml::_('select.options', $this->authors, 'value', 'text', $this->state->get('filter.author_id'));?>
                    </select>
                    
                    <select name="filter_language" class="inputbox" onchange="this.form.submit()">
                        <option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
                        <?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));?>
                    </select>
                   <!-- <label for="directionTable" class="element-invisible"><?php// echo JText::_('JFIELD_ORDERING_DESC'); ?></label>
                    
                    <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
                        <option value=""><?php //echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
                        <option value="asc" <?php //if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
                        <option value="desc" <?php //if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');  ?></option>
                    </select>
                    
                    <label for="sortTable" class="element-invisible"><?php //echo JText::_('JGLOBAL_SORT_BY'); ?></label>
                    <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                        <option value=""><?php //echo JText::_('JGLOBAL_SORT_BY');?></option>
                        <?php //echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
                    </select>-->     
        </div>
	</fieldset>
    <div class="clr"> </div>

		<table class="adminlist" id="articleList">
			<thead>
				<tr>
					<!--<th width="1%" class="nowrap center hidden-phone">
						<?php //echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
					</th>-->
                    <th>
						<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
                        <?php if ($saveOrder) :?>
                            <?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'articles.saveorder'); ?>
                        <?php endif; ?>
                    </th>
					<th width="1%" class="hidden-phone">
						<?php //echo JHtml::_('grid.checkall'); ?>
                        <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
					</th>
					<th width="1%" style="min-width:55px" class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
					</th>
                    <th width="5%">
						<?php echo JHtml::_('grid.sort', 'JFEATURED', 'a.featured', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
					</th>
                    <th width="10%" class="nowrap hidden-phone center">
						<?php echo JHtml::_('grid.sort',  'COM_FAQBOOKPRO_HEADING_ANSWERED', '', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="nowrap hidden-phone center">
						<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
					</th>
				<?php if ($assoc) : ?>
					<th width="5%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'COM_FAQBOOKPRO_HEADING_ASSOCIATION', 'association', $listDirn, $listOrder); ?>
					</th>
				<?php endif;?>
					<th width="10%" class="nowrap hidden-phone center">
						<?php echo JHtml::_('grid.sort',  'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
					</th>
					<th width="5%" class="nowrap hidden-phone center">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="nowrap hidden-phone center">
						<?php echo JHtml::_('grid.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?>
					</th>
                    <th width="10%" class="center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'COM_FAQBOOKPRO_HEADING_VOTES_UP', 'votes_up', $listDirn, $listOrder); ?>
					</th>
                    <th width="10%" class="center hidden-phone" style="min-width:80px">
						<?php echo JHtml::_('grid.sort', 'COM_FAQBOOKPRO_HEADING_VOTES_DOWN', 'votes_down', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$item->max_ordering = 0; //??
				$ordering   = ($listOrder == 'a.ordering');
				$canCreate  = $user->authorise('core.create',     'com_faqbookpro.category.'.$item->catid);
				$canEdit    = $user->authorise('core.edit',       'com_faqbookpro.article.'.$item->id);
				$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
				$canEditOwn = $user->authorise('core.edit.own',   'com_faqbookpro.article.'.$item->id) && $item->created_by == $userId;
				$canChange  = $user->authorise('core.edit.state', 'com_faqbookpro.article.'.$item->id) && $canCheckin;
				?>
				<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->catid; ?>">
					<!--<td class="order nowrap center hidden-phone">
						<?php
						/*$iconClass = '';
						if (!$canChange)
						{
							$iconClass = ' inactive';
						}
						elseif (!$saveOrder)
						{
							$iconClass = ' inactive tip-top hasTooltip" title="' . JText::_('JORDERINGDISABLED');
						}*/
						?>
						<span class="sortable-handler<?php //echo $iconClass ?>">
							<i class="icon-menu"></i>
						</span>
						<?php //if ($canChange && $saveOrder) : ?>
							<input type="text" style="display:none" name="order[]" size="5" value="<?php //echo $item->ordering; ?>" class="width-20 text-area-order " />
						<?php //endif; ?>
					</td>-->
                    <td class="order">
						<?php if ($canChange) : ?>
                            <?php if ($saveOrder) :?>
                                <?php if ($listDirn == 'asc') : ?>
                                    <span><?php echo $this->pagination->orderUpIcon($i, ($item->catid == @$this->items[$i-1]->catid), 'articles.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
                                    <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->catid == @$this->items[$i+1]->catid), 'articles.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
                                <?php elseif ($listDirn == 'desc') : ?>
                                    <span><?php echo $this->pagination->orderUpIcon($i, ($item->catid == @$this->items[$i-1]->catid), 'articles.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
                                    <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->catid == @$this->items[$i+1]->catid), 'articles.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
                            <input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
                        <?php else : ?>
                            <?php echo $item->ordering; ?>
                        <?php endif; ?>
                    </td>
					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td class="center">
						<?php echo JHtml::_('jgrid.published', $item->state, $i, 'articles.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
					</td>
                    <td class="center">
                    	<?php echo JHtml::_('contentadministrator.featured', $item->featured, $i, $canChange); ?>
                    </td>
					<td class="nowrap has-context">
						<div class="pull-left">
							<?php if ($item->checked_out) : ?>
								<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'articles.', $canCheckin); ?>
							<?php endif; ?>
							<?php if ($item->language == '*'):?>
								<?php $language = JText::alt('JALL', 'language'); ?>
							<?php else:?>
								<?php $language = $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
							<?php endif;?>
							<?php if ($canEdit || $canEditOwn) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_faqbookpro&task=article.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
									<?php echo $this->escape($item->title); ?></a>
							<?php else : ?>
								<span title="<?php echo JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)); ?>"><?php echo $this->escape($item->title); ?></span>
							<?php endif; ?>
							<div class="small">
								<?php echo JText::_('JCATEGORY') . ": " . $this->escape($item->category_title); ?>
							</div>
						</div>
					</td>
                    <td class="small hidden-phone center">
						<?php 
						if ($item->introtext || $item->fulltext) {
							echo '<span style="color: green;">'.JText::_('COM_FAQBOOKPRO_YES').'</span>'; 
						} else {
							echo '<span style="color: red;">'.JText::_('COM_FAQBOOKPRO_NO').'</span>'; 
						}
						?>
					</td>
					<td class="small hidden-phone center">
						<?php echo $this->escape($item->access_level); ?>
					</td>
					<?php if ($assoc) : ?>
					<td class="hidden-phone">
						<?php if ($item->association) : ?>
							<?php echo JHtml::_('contentadministrator.association', $item->id); ?>
						<?php endif; ?>
					</td>
					<?php endif;?>
					<td class="small hidden-phone center">
						<?php if ($item->created_by_alias) : ?>
							<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.(int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
							<?php echo $this->escape($item->author_name); ?></a>
							<p class="smallsub"> <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->created_by_alias)); ?></p>
						<?php else : ?>
							<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.(int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
							<?php echo $this->escape($item->author_name); ?></a>
						<?php endif; ?>
					</td>
					<td class="small hidden-phone center">
						<?php if ($item->language == '*'):?>
							<?php echo JText::alt('JALL', 'language'); ?>
						<?php else:?>
							<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
						<?php endif;?>
					</td>
					<td class="nowrap small hidden-phone center">
						<?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
					</td>
                    <td class="center">
						<?php echo (int) $item->votes_up; ?>
					</td>
                    <td class="center">
						<?php echo (int) $item->votes_down; ?>
					</td>
					<td class="center">
						<?php echo (int) $item->hits; ?>
					</td>
					<td class="center hidden-phone">
						<?php echo (int) $item->id; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
            <tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		</table>
		<?php //echo $this->pagination->getListFooter(); ?>
		<?php //Load the batch processing form. ?>
		<?php echo $this->loadTemplate('batch'); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
