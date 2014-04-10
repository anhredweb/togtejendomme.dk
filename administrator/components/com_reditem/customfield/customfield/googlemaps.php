<?php
/**
 * @package     RedITEM.Backend
 * @subpackage  CustomField
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die;

/**
 * Renders a Google Maps Custom field
 *
 * @package     RedITEM.Component
 * @subpackage  CustomField.GoogleMaps
 * @since       2.0
 *
 */
class TCustomfieldGooglemaps extends TCustomfield
{
	/**
	 * returns the html code for the form element
	 *
	 * @param   array  $attributes  [description]
	 *
	 * @return string
	 */
	public function render($attributes = '')
	{
		$doc = JFactory::getDocument();

		// Default value is Denmark
		$defaultLagLng = '55.403756,10.40237';

		if ($this->value)
		{
			$defaultLagLng = $this->value;
		}

		// Add Google Maps script
		$doc->addScript('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');

		$js = '
		var geocoder_' . $this->fieldcode . ';
		var map_' . $this->fieldcode . ';
		var marker_' . $this->fieldcode . ';';

		$js .= '
		function initialize_' . $this->fieldcode . '()
		{
			geocoder_' . $this->fieldcode . ' = new google.maps.Geocoder();
			var latlng = new google.maps.LatLng(' . $defaultLagLng . ');
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
			map_' . $this->fieldcode . ' = new google.maps.Map(document.getElementById("gmap_field_canvas_' . $this->fieldcode . '"), mapOptions);
		';

		if ($this->value)
		{
			$js .= 'marker = new google.maps.Marker({
				map: map_' . $this->fieldcode . ',
				position: latlng
			});';
		}

		$js .= '}

		function codeAddress_' . $this->fieldcode . '()
		{
			var address = document.getElementById("gmap_field_address_' . $this->fieldcode . '").value;
			geocoder_' . $this->fieldcode . '.geocode( { "address": address}, function(results, status){
				if (status == google.maps.GeocoderStatus.OK) {
					map_' . $this->fieldcode . '.setCenter(results[0].geometry.location);
					marker_' . $this->fieldcode . ' = new google.maps.Marker({
						map: map_' . $this->fieldcode . ',
						position: results[0].geometry.location
					});
					document.getElementById("' . $this->fieldcode . '").value = results[0].geometry.location.lat() + "," + results[0].geometry.location.lng();
				}
				else
				{
					console.log("Geocode was not successful for the following reason: " + status);
				}
			});
		}

		// Add fix code for load Goole map on tab.
		jQuery(document).ready(function(){
			jQuery("#additional-link").on("shown", function(){
				initialize_' . $this->fieldcode . '();
			});
		});';

		// Add custom javascript
		$doc->addScriptDeclaration($js);

		/*
		 * Stylesheet
		 */
		$css = '
		.gmap_field
		{
			display: block;
			position: relative;
			margin: 20px 0px 0px 0px;
			width: 500px;;
		}
		.gmap_field .gmap_field_canvas
		{
			width: 100%;
			height: 350px;
		}
		.gmap_field .gmap_field_panel
		{
			position: absolute;
			top: 10px;
			left: 50%;
			margin-left: -30%;
			z-index: 5;
			background-color: #fff;
			padding: 5px;
			border: 1px solid #999;
			border-radius: 5px;
		}';

		// Add custom stylesheet
		$doc->addStyleDeclaration($css);

		$class = "";

		if ($this->required)
		{
			$class = ' class="required"';
		}
		/*
		 * Required to avoid a cycle of encoding &
		 * html_entity_decode was used in place of htmlspecialchars_decode because
		 * htmlspecialchars_decode is not compatible with PHP 4
		 */
		$value = htmlspecialchars(html_entity_decode($this->value, ENT_QUOTES), ENT_QUOTES);

		$attributes .= ' placeholder="' . $this->name . '"';

		$str = '<div class="gmap_field_input">';
		$str .= '<input type="text" name="cform[googlemaps][' . $this->fieldcode . ']" id="' . $this->fieldcode . '" value="' . $value . '"';
		$str .= $class . $attributes . '/>';
		$str .= '</div>';

		$str .= '<div class="gmap_field">
			<div class="gmap_field_panel">
				<input id="gmap_field_address_' . $this->fieldcode . '" type="text" class="input" value="" placeholder="Odense, Denmark">
				<input type="button" class="btn" 
					value="' . JText::_('COM_REDITEM_CUSTOMFIELD_GOOGLEMAPS_GEOCODE') . '" 
					onclick="codeAddress_' . $this->fieldcode . '()">
			</div>
			<div id="gmap_field_canvas_' . $this->fieldcode . '" class="gmap_field_canvas"></div>
		</div>';

		return $str;
	}
}
