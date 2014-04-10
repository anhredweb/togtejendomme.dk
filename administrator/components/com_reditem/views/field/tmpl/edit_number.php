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
	<?php
	foreach ($this->form->getGroup('params') as $field)
	{
		?>
		<div class="control-group">
		<?php
		if ($field->type == 'Spacer')
		{
			if (!$firstSpacer)
			{
				echo '<hr />';
			}
			else
			{
				$firstSpacer = false;
			}

			echo $field->label;
		}
		elseif ($field->hidden)
		{
			echo $field->input;
		}
		else
		{
		?>
			<div class="control-label">
				<?php echo $field->label; ?>
			</div>
			<div class="controls">
				<?php echo $field->input; ?>
			</div>
		<?php
		}
		?>
		</div>
	<?php
	}
	?>
	</fieldset>
</div>
