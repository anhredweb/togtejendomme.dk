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
<!-- Initialize --> 
<script type="text/javascript">
	var countryName = "<?php echo $country; ?>"; // "United States of America";
	var parentCategory = <?php echo $parentCategory; ?>;
	var uniqueInforWindow = <?php echo ($gmapInfoWindowUnique) ? 'true' : 'false'; ?>;
	var reditemGlobalInforWindow = new InfoBubble();

	google.load('visualization', '1', {'packages':['corechart', 'table', 'geomap']});

	function reditemGmap_initialize() 
	{
		var centerPoint = new google.maps.LatLng(<?php echo $gmapLatlng ?>);

		myOptions = {
			zoom: <?php echo $gmapZoom ?>,
			center: centerPoint,
			panControl: false,
			zoomControl: false,
			mapTypeControl: false,
			scaleControl: false,
			streetViewControl: false,
			overviewMapControl: false,
			draggable: true,
			scrollwheel: true,
			disableDoubleClickZoom: true,
			mapTypeId: maptype,
			mapTypeControlOptions: {
				mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
			}
		};

		map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

		<?php
		$uri = JURI::getInstance();
		$i = 0;
		?>

		<?php foreach ($categories as $mCategory) : ?>
			<?php
			$markerClass = 'gmap_hidden';

			$uriParams = $uri->getQuery(true);
			$uriParams['filter_category'][$parentCategory] = $mCategory->id;
			$uri->setQuery($uriParams);
			$catParams = json_decode($mCategory->params);
			?>

			<?php if (isset($catParams->categoryLatLng)) : ?>
			reditemGmapAddMarker(
				map,
				"<?php echo $catParams->categoryLatLng; ?>",
				"<?php echo $mCategory->title; ?>",
				"<?php echo $markerClass; ?>",
				<?php echo $mCategory->id; ?>,
				"<?php echo JHTML::_('image', 'mod_reditem_categories_gmap/pin.png', '', null, true, true); ?>",
				"<?php echo JRoute::_($uri->toString()); ?>",
				<?php echo $i; ?>,
				<?php echo $mCategory->inforbox; ?>);
			<?php
			$i++;
			?>
			<?php endif; ?>
		<?php endforeach; ?>

		var ftoptions = {
			query: {
				from: FT_TableID,
				select: 'kml_4326',
				where: "name_0 = '" + countryName + "'"
			},
			suppressInfoWindows:true,
			styles: [{
				polygonOptions: {
					fillColor: '#DDDDDD',
					fillOpacity: 1.0,
					strokeColor: '#DDDDDD',
					strokeOpacity: 0.0,
					strokeWeight: 0,
				}
			}]
		};

		var layer = new google.maps.FusionTablesLayer(ftoptions);
		layer.setMap(map);

		map.mapTypes.set('map_style', styledMap);
		map.setMapTypeId('map_style');
	}
</script>

<div id="map_canvas" style="<?php echo $gmapWidth . ';' . $gmapHeight; ?>">
</div>
