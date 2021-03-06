(function($){
	// OnLoad initialize
	$(document).ready(function($){
		$('.youtube').each(function(index){
			youtube_create($(this).attr('id'));
		});

		$('#reditemItemsPagination').on('click', 'a', function(event){
			event.preventDefault();
			var url = $(this).attr('href');

			if (url.contains('?') === false)
			{
				url += '?';
			}
			else
			{
				url += '&';
			}

			url += 'task=categorydetail.ajaxFilter';
			
			reditemLoadAjaxData(url);
		});

		$('#reditemCategoriesPagination').on('click', 'a', function(event){
			event.preventDefault();
			var url = $(this).attr('href');

			if (url.contains('?') === false)
			{
				url += '?';
			}
			else
			{
				url += '&';
			}

			url += 'task=categorydetail.ajaxCatExtraFilter';
			
			reditemLoadAjaxCatExtraData(url);
		});
	});
})(jQuery);

/**
 * Method for run filter item
 *
 * @return  void
 */
function reditemFilterAjax()
{
	(function($){
		var form = document.reditemCategoryDetail;
		form.task.value = 'categorydetail.ajaxFilter';
		var url = 'index.php?' + $(form).serialize();

		reditemLoadAjaxData(url);
	})(jQuery);
}

/**
 * Method for get data from AJAX and replace in div
 *
 * @param   string  url  URL of ajax
 *
 * @return  void
 */
function reditemLoadAjaxData(url)
{
	(function($){
		$('#reditemsItems').html('<div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div>');

		$.ajax({
			url: url,
			dataType: "json",
			cache: false
		})
		.success(function (data){
			$.getScript(holderlib);
		})
		.done(function (data){
			// Replace Items data
			$('#reditemsItems').empty().append(data.category);

			// Replace items pagination
			$('#reditemItemsPagination').empty().append(data.pagination);

			// Related Categories filter
			if (!(typeof data.relatedCategories == 'undefined'))
			{
				for (parentCat in data.relatedCategories)
				{
					var relatedCategories = data.relatedCategories[parentCat];
					var selectbox = $('#filter_related_' + parentCat);
					var selectAllText = selectbox.find("option[value='']").text();

					if ((selectbox.length > 0) && (!$.isEmptyObject(relatedCategories)))
					{
						selectbox.empty();

						selectbox.append("<option value=''>" + selectAllText + "</option>");

						for (relatedCat in relatedCategories)
						{
							var relatedCategory = relatedCategories[relatedCat];
							var attr = "";

							if (relatedCategory.filter == true)
							{
								attr = " selected='selected'";
							}

							selectbox.append("<option value='"+ relatedCategory.id +"'" + attr + ">" + relatedCategory.title + "</option>");
						}

						selectbox.select2();
					}
				}
			}
			else
			{
				$('select.reditemFilterRelated').each(function(index){
					var selectbox = $(this);
					var tmp = $('#' + selectbox.attr('id') + '_tmp');

					if (tmp.length > 0)
					{
						selectbox.empty();

						tmp.find('option').each(function(){
							selectbox.append(new Option($(this).text(), $(this).val()));
 						})

 						selectbox.select2();
					}
				});
			}
		});
	})(jQuery);
}

/**
 * Function for create youtube block
 *
 * @param   string  obj_id  Id of div
 *
 * @return  void
 */
function youtube_create(obj_id)
{
	var y = document.getElementById(obj_id);
	var tmp = y.href.split('/');
	var img_id = tmp[tmp.length - 1];
	var i = document.createElement("img");
	i.setAttribute("src", "http://i.ytimg.com/vi/" + img_id + "/hqdefault.jpg");
	i.setAttribute("style", y.getAttribute("style"));
	i.setAttribute("class", "thumb");
	var c = document.createElement("div");
	c.setAttribute("class", "play");
	y.appendChild(i);
	y.appendChild(c);
}

/**
 * Function create map for items custom fields
 *
 * @param   string  id      ID of google map canvas
 * @param   string  latlng  Latitude, Longtitude number
 * @param   string  infor   Content for InforWindow
 */
function reditem_customfield_googlemaps_init()
{
	jQuery('.reditem_custom_googlemaps').each(function (index) {
		var mapid = jQuery(this).find('input[id="mapid"]').val();
		var markerLatLng = jQuery(this).find('input[id="maplatlng"]').val();
		var infor = jQuery(this).find('input[id="mapinfor"]').val();

		var markerLatLngArray = markerLatLng.split(',');
		var latlng = new google.maps.LatLng(markerLatLngArray[0], markerLatLngArray[1]);

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

		var map = new google.maps.Map(document.getElementById(mapid), mapOptions);

		var marker = new google.maps.Marker({
			map: map,
			position: latlng
		});

		google.maps.event.addListener(marker, "click", function (e) {
			var infowindow = new google.maps.InfoWindow({
				content: infor
			});

			infowindow.open(map, this);
		});
	});
}

/**
 * Function create map for category extra fields
 *
 * @param   string  id      ID of google map canvas
 * @param   string  latlng  Latitude, Longtitude number
 * @param   string  infor   Content for InforWindow
 */
function reditem_extra_category_googlemaps_init()
{
	jQuery('.reditem_extra_category_googlemaps').each(function (index){
		var mapid = jQuery(this).find('input[id="map_extra_id"]').val();
		var markerLatLng = jQuery(this).find('input[id="map_extra_latlng"]').val();
		var infor = jQuery(this).find('input[id="map_extra_infor"]').val();

		var markerLatLngArray = markerLatLng.split(',');
		var latlng = new google.maps.LatLng(markerLatLngArray[0], markerLatLngArray[1]);

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

		var map = new google.maps.Map(document.getElementById(mapid), mapOptions);

		var marker = new google.maps.Marker({
			map: map,
			position: latlng
		});

		google.maps.event.addListener(marker, "click", function (e) {
			var infowindow = new google.maps.InfoWindow({
				content: infor
			});

			infowindow.open(map, this);
		});
	});
}

/**
 * Method for run filter sub-categories
 *
 * @return  void
 */
function reditemCatExtraFilterAjax()
{
	(function($){
		var form = document.reditemCategoryDetail;
		form.task.value = 'categorydetail.ajaxCatExtraFilter';
		var url = 'index.php?' + $(form).serialize();

		reditemLoadAjaxCatExtraData(url);
	})(jQuery);
}

/**
 * Method for get data from AJAX and replace in div
 *
 * @param   string  url  URL of ajax
 *
 * @return  void
 */
function reditemLoadAjaxCatExtraData(url)
{
	(function($){
		$('#reditemCategories').html('<div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div>');

		$.ajax({
			url: url,
			dataType: "json",
			cache: false
		})
		.success(function (data){
			$.getScript(holderlib);
		})
		.done(function (data){

			// Replace sub-categories data
			$('#reditemCategories').empty().append(data.content);

			// Replace sub-categories pagination
			$('#reditemCategoriesPagination').empty().append(data.pagination);
		});
	})(jQuery);
}