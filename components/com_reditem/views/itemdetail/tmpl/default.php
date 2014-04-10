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
$Itemid = $input->get('Itemid');
$print = $input->get('print', 0);

JHtml::_('rjquery.framework');
JHtml::_('rholder.image', '100x100');

RHelperAsset::load('reditem.js');
?>

<?php if ($this->params->get('show_page_heading')) : ?>
	<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
<?php endif; ?>

<?php if (!$this->data) : ?>
	<p><?php echo JText::_('COM_REDITEM_ERROR_NO_ITEM_FOUND'); ?></p>
<?php else: ?>
<?php
	$item = $this->data;

	$content = $item->template->content;

	ReditemTagsHelper::tagReplaceItem($content, $item);

	ReditemTagsHelper::tagReplaceItemCustomField($content, $item);

	JPluginHelper::importPlugin('content');
	$content = JHtml::_('content.prepare', $content);

	?>
	<div class="reditem">
		<div class="reditem_content">
			<?php echo $content; ?>
		</div>
	</div>
<?php endif; ?>
