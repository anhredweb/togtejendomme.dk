<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Item
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
?>

<div class="row-fluid">
	<fieldset class="form-horizontal">
	<?php if ($this->customfields) : ?>
		<?php foreach ($this->customfields AS $customfield) : ?>
			<div class="control-group">
				<div class="control-label">
					<?php echo $customfield->getLabel(); ?>
				</div>
				<div class="controls">
					<?php echo $customfield->render(); ?>
				</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
	</fieldset>
</div>
