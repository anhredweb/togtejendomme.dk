jQuery(document).ready(function($){

    $(".search_searchfield input.button_searchfield").val("");

	$('.items-list').each(function() {

		$(this).find('.type').hide();

		if($(this).find('.type').html() == 'Parkering'){
			$(this).find('.type').show();
			$(this).find('.kmv').hide();
			$(this).find('.room').hide();
		}
	});

	$('.alllejitems').each(function() {
		if($(this).find('#reditemsItems').html() == ''){
			$(this).find('h3').hide();
		}
	});

	$(".redform-form input[type='text']").attr("placeholder", "Dit telefonnummer");
	$(function () {
    	$('#myTab a:first').tab('show');
	})

	$('#map-link').on('shown', function()
	{
		reditem_customfield_googlemaps_init();
		reditem_extra_category_googlemaps_init();
	});

});
