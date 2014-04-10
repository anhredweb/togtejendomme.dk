<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$input = $app->input;
$itemId = $input->get('Itemid');
$categoryId = $input->get('id');
$print = $input->get('print', 0);
$doc = JFactory::getDocument();

JHtml::_('rjquery.framework');
JHtml::_('rholder.image', '100x100');

// Load custom javascript
RHelperAsset::load('reditem.js');

?>
<script type="text/javascript">
	var holderlib = '<?php echo JURI::root(); ?>media/redcore/js/lib/holder.js';
</script>

<?php if ($this->params->get('show_page_heading')) : ?>
	<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
<?php endif; ?>

<?php if (!$this->data): ?>
	<p><?php echo JText::_('COM_REDITEM_ERROR_NO_CATEGORY_FOUND'); ?></p>;
<?php else: ?>

<?php
	JHtml::_('rjquery.select2', 'select');

	$mainCategory = $this->data;

	$mainContent = $mainCategory->template->content;

	ReditemTagsHelper::tagReplaceCategory($mainContent, $mainCategory);

	// Items array
	if ((strpos($mainContent, '{items_loop_start}') !== false) && (strpos($mainContent, '{items_loop_end}') !== false))
	{
		$tempContent = explode('{items_loop_start}', $mainContent);
		$preContent = (count($tempContent) > 1) ? $tempContent[0] : '';

		$tempContent = $tempContent[count($tempContent) - 1];
		$tempContent = explode('{items_loop_end}', $tempContent);
		$subTemplate = $tempContent[0];

		$postContent = (count($tempContent) > 1) ? $tempContent[count($tempContent) - 1] : '';

		$subContent = '';

		if ($mainCategory->items)
		{
			// Has sub categories
			foreach ($mainCategory->items as $item)
			{
				$subContentSub = $subTemplate;

				ReditemTagsHelper::tagReplaceItem($subContentSub, $item, $mainCategory->id);

				ReditemTagsHelper::tagReplaceItemCustomField($subContentSub, $item);

				$subContent .= $subContentSub;
			}
		}

		$mainContent = $preContent . '<div id="reditemsItems">' . $subContent . '</div>' . $postContent;
	}

	// Sub categories (Featured)
	if ((strpos($mainContent, '{sub_featured_category_start}') !== false) && (strpos($mainContent, '{sub_featured_category_end}') !== false))
	{
		$tempContent = explode('{sub_featured_category_start}', $mainContent);
		$preContent = (count($tempContent) > 1) ? $tempContent[0] : '';

		$tempContent = $tempContent[count($tempContent) - 1];
		$tempContent = explode('{sub_featured_category_end}', $tempContent);
		$subTemplate = $tempContent[0];

		$postContent = (count($tempContent) > 1) ? $tempContent[count($tempContent) - 1] : '';

		$subContent = '';

		if ($mainCategory->sub_categories_featured)
		{
			// Has sub categories
			foreach ($mainCategory->sub_categories_featured as $subCategory)
			{
				$subContentSub = $subTemplate;

				ReditemTagsHelper::tagReplaceCategory($subContentSub, $subCategory, 'sub_', $mainCategory->id);

				$subContent .= $subContentSub;
			}
		}

		$mainContent = $preContent . $subContent . $postContent;
	}

	// Sub categories
	if ((strpos($mainContent, '{sub_category_start}') !== false) && (strpos($mainContent, '{sub_category_end}') !== false))
	{
		$tempContent = explode('{sub_category_start}', $mainContent);
		$preContent = (count($tempContent) > 1) ? $tempContent[0] : '';

		$tempContent = $tempContent[count($tempContent) - 1];
		$tempContent = explode('{sub_category_end}', $tempContent);
		$subTemplate = $tempContent[0];

		$postContent = (count($tempContent) > 1) ? $tempContent[count($tempContent) - 1] : '';

		$subContent = '';

		if ($mainCategory->sub_categories)
		{
			// Has sub categories
			foreach ($mainCategory->sub_categories as $subCategory)
			{
				$subContentSub = $subTemplate;

				ReditemTagsHelper::tagReplaceCategory($subContentSub, $subCategory, 'sub_', $mainCategory->id);

				$subContent .= $subContentSub;
			}
		}

		$mainContent = $preContent . '<div id="reditemCategories">' . $subContent . '</div>' . $postContent;
	}

	// Related categories
	if ((strpos($mainContent, '{related_category_start}') !== false) && (strpos($mainContent, '{related_category_end}') !== false))
	{
		$tempContent = explode('{related_category_start}', $mainContent);
		$preContent = (count($tempContent) > 1) ? $tempContent[0] : '';

		$tempContent = $tempContent[count($tempContent) - 1];
		$tempContent = explode('{related_category_end}', $tempContent);
		$subTemplate = $tempContent[0];

		$postContent = (count($tempContent) > 1) ? $tempContent[count($tempContent) - 1] : '';

		$subContent = '';

		if ($mainCategory->relatedCategories)
		{
			// Has sub categories
			foreach ($mainCategory->relatedCategories as $subCategory)
			{
				$subContentSub = $subTemplate;

				ReditemTagsHelper::tagReplaceCategory($subContentSub, $subCategory, 'sub_', $subCategory->parent_id);

				$subContent .= $subContentSub;
			}
		}

		$mainContent = $preContent . $subContent . $postContent;
	}

	// Filter tag
	ReditemTagsHelper::tagReplaceFilter($mainContent, $mainCategory);
	ReditemTagsHelper::tagReplaceCategoryFilter($mainContent, $mainCategory);

	JPluginHelper::importPlugin('content');
	$mainContent = JHtml::_('content.prepare', $mainContent);
	?>
	<div class="reditem">
		<div class="reditem_categories">
			<form action="index.php" class="admin" id="reditemCategoryDetail" method="get" name="reditemCategoryDetail">
				<input type="hidden" name="option" value="com_reditem" />
				<input type="hidden" name="view" value="categorydetail" />
				<input type="hidden" name="id" value="<?php echo $categoryId; ?>" />
				<input type="hidden" name="templateId" value="<?php echo $mainCategory->template->id; ?>" />
				<?php echo $mainContent; ?>
				<input type="hidden" name="Itemid" value="<?php echo $itemId; ?>" />
				<input type="hidden" name="task" value="" />
			</form>
		</div>
	</div>
<?php endif; ?>
