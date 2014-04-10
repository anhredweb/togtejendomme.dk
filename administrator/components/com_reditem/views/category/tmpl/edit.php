<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('rjquery.select2', 'select');
JHtml::_('behavior.modal', 'a.modal-thumb');

?>

<?php if ($this->useGmapField) : ?>

<?php
$gmapField = $this->form->getField('categoryLatLng', 'params');
$latlng = '55.403756,10.40237';

if ($gmapField->value) :
	$latlng = $gmapField->value;
endif;
?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script>
	var geocoder;
	var map;
	var marker;
	function initialize()
	{
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(<?php echo $latlng; ?>);
		var mapOptions = {
			zoom: 8,
			center: latlng,
			panControl: false,
			zoomControl: false,
			mapTypeControl: false,
			scaleControl: false,
			streetViewControl: false,
			overviewMapControl: false,
		}
		map = new google.maps.Map(document.getElementById('category_gmap_field_canvas'), mapOptions);
		<?php if ($gmapField->value) : ?>
		marker = new google.maps.Marker({
			map: map,
			position: latlng
		});
		<?php endif; ?>
	}

	function codeAddress()
	{
		var address = document.getElementById('category_gmap_field_address').value;
		geocoder.geocode( { 'address': address}, function(results, status){
			if (status == google.maps.GeocoderStatus.OK) {
				map.setCenter(results[0].geometry.location);
				marker = new google.maps.Marker({
					map: map,
					position: results[0].geometry.location
				});
				document.getElementById('jform_params_categoryLatLng').value = results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
			}
			else
			{
				console.log('Geocode was not successful for the following reason: ' + status);
			}
		});
	}

	google.maps.event.addDomListener(window, 'load', initialize);
</script>

<?php endif; ?>


<script type="text/javascript">
	function jInsertEditorText(tag, editor)
	{
		var img = jQuery(tag);
		var field = jQuery('#jform_category_image_media');
		field.val(img.attr('src'));
		jQuery('#media_thumb_preview').html('<img src="<?php echo JURI::root(); ?>' + img.attr('src') + '" style="max-width: 100px; max-height: 100px;" />');
	}

	Joomla.submitbutton = function(pressbutton)
	{
		submitform( pressbutton );
	}

	<?php
	if (isset($this->item->id))
	{
	?>
	jQuery(document).ready(function()
	{
		jQuery('#jform_type_id').attr('readonly', true);
	});
	<?php
	}
	?>
</script>

<form enctype="multipart/form-data"
	action="index.php?option=com_reditem&task=category.edit&id=<?php echo $this->item->id; ?>"
	method="post" name="adminForm" class="form-validate" id="adminForm"
>
	<ul class="nav nav-tabs" id="categoryTab">
		<li class="active"><a href="#category-information" data-toggle="tab">
			<strong><?php echo JText::_('COM_REDITEM_GENERAL_INFORMATION'); ?></strong></a>
		</li>
		<?php if (isset($this->extrafields)): ?>
		<li>
			<a href="#category-additional" data-toggle="tab" id="additional-link"><strong><?php echo JText::_('COM_REDITEM_ADDITIONAL_INFORMATION'); ?></strong></a>
		</li>
		<?php endif; ?>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="category-information">
			<div class="row-fluid">
				<div class="span9">
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('type_id'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('type_id'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('title'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('title'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('parent_id'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('parent_id'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('access'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('access'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('template_id'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('template_id'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('category_image_file'); ?>
							</div>
							<div class="controls">
								<div class="media">
									<?php if ($this->form->getValue('category_image')): ?>
										<?php $img_src = JURI::root() . 'components/com_reditem/assets/images/category/' . $this->item->id . '/' . $this->form->getValue('category_image'); ?>
										<img style="max-width: 300px; max-height: 300px; margin-right: 20px;" class="preview_img img-polaroid pull-left" src="<?php echo $img_src; ?>" />
									<?php endif; ?>
									<div class="media-body">
										<ul>
											<?php if ($this->form->getValue('category_image')) : ?>
											<li>
												<label class="checkbox">
													<input type="checkbox" name="jform[category_image_remove]" value="<?php $this->form->getValue('category_image'); ?>" />
													<?php echo JText::_('COM_REDITEM_CUSTOMFIELD_IMAGE_REMOVE'); ?>
												</label>
												<div class="clearfix"></div>
											</li>
											<?php endif; ?>
											<li>
												<?php echo $this->form->getInput('category_image_file'); ?>
												<?php echo $this->form->getInput('category_image'); ?>
												<div class="clearfix"></div>
											</li>
											<li>
												<a class="modal-thumb btn" href="index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;e_name=category_image"
								   				rel="{handler: 'iframe', size: {x: 1050, y: 450}}" title=""><?php echo JText::_('COM_REDITEM_CATEGORY_IMAGE_MEDIA'); ?></a>
								   				<input type="hidden" id="jform_category_image_media" name="jform[category_image_media]" value="" />
								   				<div id="media_thumb_preview"></div>
								   				<div class="clearfix"></div>
								   			</li>
								   			<?php foreach ($this->form->getFieldset('category_image_params') as $field) : ?>
						   					<li>
												<?php echo $field->label; ?>
							   					<?php echo $field->input; ?>
							   					<div class="clearfix"></div>
						   					</li>
						   					<?php endforeach; ?>
								   		</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('introtext'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('introtext'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('fulltext'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('fulltext'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('featured'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('featured'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('published'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('published'); ?>
							</div>
						</div>
					</fieldset>
				</div>
				<div class="span3">
					<fieldset class="form-vertical">
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('related_categories'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('related_categories'); ?>
							</div>
						</div>
						<?php if ($this->useGmapField) : ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $gmapField->label; ?>
							</div>
							<div class="controls">
								<?php echo $gmapField->input; ?>
								<div class="category_gmap_field">
									<div id="category_gmap_field_panel">
										<input id="category_gmap_field_address" type="text" class="input" value="" placeholder="Odense, Denmark">
										<input type="button" class="btn" value="<?php echo JText::_('COM_REDITEM_CATEGORY_GEOCODE'); ?>" onclick="codeAddress()">
									</div>
									<div id="category_gmap_field_canvas"></div>
								</div>
							</div>
						</div>
						<?php endif; ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('created_user_id'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('created_user_id'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('created_time'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('created_time'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('modified_user_id'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('modified_user_id'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('modified_time'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('modified_time'); ?>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
		<?php if (isset($this->extrafields)): ?>
		<div class="tab-pane" id="category-additional">
			<div class="row-fluid">
				<fieldset class="form-horizontal">
					<?php if (!empty($this->extrafields)): ?>
						<?php foreach ($this->extrafields as $extrafield) : ?>
							<?php echo $extrafield; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</fieldset>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<?php echo $this->form->getInput('id'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
