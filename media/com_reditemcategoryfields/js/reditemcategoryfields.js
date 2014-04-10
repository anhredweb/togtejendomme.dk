(function($){
	// OnLoad initialize
	$(document).ready(function($){
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
			console.log(data);

			// Replace sub-categories data
			$('#reditemCategories').empty().append(data.content);

			// Replace sub-categories pagination
			$('#reditemCategoriesPagination').empty().append(data.pagination);
		});
	})(jQuery);
}