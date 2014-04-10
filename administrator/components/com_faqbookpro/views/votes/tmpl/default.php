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

//JHtml::_('bootstrap.tooltip');
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
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_faqbookpro&task=votes.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'voteList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

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

<form action="<?php echo JRoute::_('index.php?option=com_faqbookpro&view=votes'); ?>" method="post" name="adminForm" id="adminForm">

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
                                                            
                    <select name="filter_author_id" class="inputbox" onchange="this.form.submit()">
                        <option value=""><?php echo JText::_('JOPTION_SELECT_AUTHOR');?></option>
                        <?php echo JHtml::_('select.options', $this->authors, 'value', 'text', $this->state->get('filter.author_id'));?>
                    </select>
                    
        </div>
	</fieldset>
    
		<div class="clearfix"> </div>

		<table class="adminlist" id="articleList">
			<thead>
				<tr>
					<th width="1%" class="hidden-phone">
						<?php //echo JHtml::_('grid.checkall'); ?>
                        <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
					</th>
					<th width="1%" style="min-width:55px" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHtml::_('grid.sort', 'COM_JOOMARKET_HEADING_ARTICLE_TITLE', 'article_title', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="nowrap hidden-phone center">
						<?php echo JHtml::_('grid.sort',  'COM_JOOMARKET_HEADING_VOTER', 'author_name', $listDirn, $listOrder); ?>
					</th>
                    <th width="10%" class="nowrap hidden-phone center">
						<?php echo JHtml::_('grid.sort',  'COM_JOOMARKET_HEADING_VOTER_IP', 'a.user_ip', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="nowrap hidden-phone center">
						<?php echo JHtml::_('grid.sort', 'JDATE', 'a.creation_date', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="center">
						<?php echo JText::_('COM_JOOMARKET_HEADING_VOTE_TYPE'); ?>
					</th>
                    <!--<th width="10%" class="center">
						<?php //echo JHtml::_('grid.sort', 'COM_JOOMARKET_HEADING_VOTE_DOWN', 'a.vote_down', $listDirn, $listOrder); ?>
					</th>-->
                    <th style="min-width:140px" class="center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'COM_JOOMARKET_HEADING_REASON', 'a.reason', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$item->max_ordering = 0; //??
				$canCreate  = $user->authorise('core.create',     'com_faqbookpro.category.'.$item->catid);
				$canEdit    = $user->authorise('core.edit',       'com_faqbookpro.vote.'.$item->id);
				$canEditOwn = $user->authorise('core.edit.own',   'com_faqbookpro.vote.'.$item->id) && $item->user_id == $userId;
				$canChange  = $user->authorise('core.edit.state', 'com_faqbookpro.vote.'.$item->id); //&& $canCheckin;
				?>
				<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->catid; ?>">
					
					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td class="center">
						<div class="btn-group">
							<?php echo JHtml::_('jgrid.published', $item->published, $i, 'votes.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
						</div>
					</td>
					<td class="nowrap has-context">
						<div class="pull-left">
							<?php if ($canEdit || $canEditOwn) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_faqbookpro&task=article.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
									<?php echo $this->escape($item->article_title); ?></a>
							<?php else : ?>
								<span title="<?php echo JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->article_title)); ?>"><?php echo $this->escape($item->article_title); ?></span>
							<?php endif; ?>
							<div class="small">
								<?php echo JText::_('JCATEGORY') . ": " . $this->escape($item->category_title); ?>
							</div>
						</div>
					</td>
					<td class="small hidden-phone center">
                    	<?php if ($item->author_name) { ?>
							<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.(int) $item->user_id); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
							<?php echo $this->escape($item->author_name); ?></a>
                        <?php } else { ?>
                        	<small><?php echo JText::_('COM_JOOMARKET_GUEST'); ?></small>
                        <?php } ?>
					</td>
                    <td class="center">
                    	<?php if ($item->user_ip) { ?>
							<?php echo '<small>'.$item->user_ip.'</small>'; ?>
                        <?php } else { ?>
                        	<?php echo '-'; ?>
                        <?php } ?>
					</td>
					<td class="nowrap small hidden-phone center">
						<?php echo JHtml::_('date', $item->creation_date, JText::_('DATE_FORMAT_LC4')); ?>
					</td>
					<td class="small center hidden-phone center">
						<?php 
						if ($item->vote_up) { 
							echo '<span style="color: green;">'.JText::_('COM_FAQBOOKPRO_UP').'</span>';
						} else {
							echo '<span style="color: red;">'.JText::_('COM_FAQBOOKPRO_DOWN').'</span>';	
						}
						?>
					</td>
                    <td class="center">
                    	<?php if ($item->vote_down) { ?>
							<?php if ((int)$item->reason == '1') { ?>
                            	<?php echo '<small>'.JText::_('COM_JOOMARKET_REASON_1').'</small>'; ?>
                            <?php } else if ((int)$item->reason == '2') { ?>
                            	<?php echo '<small>'.JText::_('COM_JOOMARKET_REASON_2').'</small>'; ?>
                            <?php } else if ((int)$item->reason == '3') { ?>
                            	<?php echo '<small>'.JText::_('COM_JOOMARKET_REASON_3').'</small>'; ?>
                            <?php } else if ((int)$item->reason == '4') { ?>
                            	<?php echo '<small>'.JText::_('COM_JOOMARKET_REASON_4').'</small>'; ?>
                            <?php } else if ((int)$item->reason == '5') { ?>
                            	<?php echo '<small>'.JText::_('COM_JOOMARKET_REASON_5').'</small>'; ?>
                            <?php } else if ((int)$item->reason == '6') { ?>
                            	<?php echo '<small>'.JText::_('COM_JOOMARKET_REASON_6').'</small>'; ?>
                            <?php } ?>
                        <?php } else { ?>
                        	<?php echo '-'; ?>
                        <?php } ?>
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
		
		<?php //Load the batch processing form. ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
