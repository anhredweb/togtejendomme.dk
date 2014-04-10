<?php
/**
 * @package     RedITEM.Frontend
 * @subpackage  mod_reditem_categories
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

?>
<div class="mod_reditem_categories_wrapper">
	<ul class="mod_reditem_categories">
		<?php if ($categories) : ?>
			<?php foreach ($categories as $category) : ?>
			<li>
				<div class="img">
					<a href="<?php echo JRoute::_($category->link); ?>">
						<?php echo $category->category_image; ?>
					</a>
				</div>
				<div class="title">
					<a href="<?php echo JRoute::_($category->link); ?>">
						<?php echo $category->title; ?>
					</a>
				</div>
			</li>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>
</div>
