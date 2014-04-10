jQuery.noConflict();	

jQuery(window).load(function () {
  
	var page_view = window.fbpvars.page_view;
	var catid = window.fbpvars.catid;
	
	// Fix leftnav height on window load
	if (page_view == 'category' || page_view == 'item') 
	{
		var parent_ul = jQuery('#liid'+catid).parent();
		var vheight = jQuery(parent_ul).height();
		jQuery('.fbpLeftNavigation_root').css({"height":vheight+"px"});
	} 
	else if (page_view == 'faqbook' || page_view == 'search' || page_view == 'article') 
	{
		var vheight = jQuery('.NavLeftUL_parent').height();
		jQuery('.fbpLeftNavigation_root').css({"height":vheight+"px"});
	}
	
	// Hide Leftnav in mobile
	if (page_view == 'article' || page_view == 'search' || page_view == 'category' || page_view == 'item') 
	{
		// Add 'show menu' button
		jQuery('.show_menu').addClass('fbp-shown');	
		
		jQuery('.fbpLeftNavigation_core').addClass('fbp-hidden');
	}
	
	// Fix search form dimensions on window load
	var search_div = jQuery('.fbpTopNavigation_search');
	var sheight = jQuery('.fbpTopNavigation_core').outerHeight();
	var swidth = jQuery('.fbpTopNavigation_core').width();
	jQuery(search_div).css({"height":sheight+"px", "width":swidth+"px"});
	jQuery('#u_0_3').css({"height":sheight-4+"px"});
	
});

/*jQuery(window).resize(function(){
		var search_div = jQuery('.fbpTopNavigation_search');
		var sheight = jQuery('.fbpTopNavigation_core').outerHeight();
		var swidth = jQuery('.fbpTopNavigation_core').width();
		jQuery(search_div).css({"height":sheight+"px", "width":swidth+"px"});
		jQuery('#u_0_3').css({"height":sheight-4+"px"});
});*/

jQuery(document).ready(function(){
	
	var fbp_width;
	setWidth(); // just call the function
    jQuery(window).resize(setWidth);

	function setWidth() {
        //fbp_width = document.getElementById("fbpExtended").clientWidth;
		fbp_width = jQuery("#fbpExtended").width();
		if (jQuery(document).width() < 768) {
			//anim_width = fbp_width;
			jQuery('#NavLeftUL').css({"width":fbp_width+"px"});
			//jQuery('ul.NavLeftUL_sublist').css({"left":fbp_width+"px"});
			jQuery('ul.NavLeftUL_sublist').css({"left":"100%"});
		} else {
			fbp_width = 260;
			jQuery('#NavLeftUL').css({"width":"260px"});
			jQuery('ul.NavLeftUL_sublist').css({"left":fbp_width+"px"});
			// Show leftnav again in case it was hidden
			//jQuery('.fbpLeftNavigation_core').show();
		}
    };
		
	jQuery(window).resize(function(){
		var search_div = jQuery('.fbpTopNavigation_search');
		var sheight = jQuery('.fbpTopNavigation_core').outerHeight();
		var swidth = jQuery('.fbpTopNavigation_core').width();
		jQuery(search_div).css({"height":sheight+"px", "width":swidth+"px"});
		jQuery('#u_0_3').css({"height":sheight-4+"px"});	
  	});

	
	var token = window.fbpvars.token + "=1";
	var site_path = window.fbpvars.site_path;
	var encoded_index_link = window.fbpvars.index_link;
	var index_link = encoded_index_link.replace(/&amp;/g, '&');
	var page_title = window.fbpvars.page_title;
	var items = window.fbpvars.items;
	var cols = window.fbpvars.cols;
	var title = window.fbpvars.title;
	var desc = window.fbpvars.desc;
	var limit = window.fbpvars.limit;
	var image = window.fbpvars.image;
	var imgsize = window.fbpvars.imgsize;
	var imgheight = window.fbpvars.imgheight;
	var featured = window.fbpvars.featured;
	var page_view = window.fbpvars.page_view;
	var leftnav = window.fbpvars.leftnav;
	var url = window.location.href;
	var catid = window.fbpvars.catid;
	var item_id = window.fbpvars.item_id;
	var captcha_key = window.fbpvars.captcha_key;
	// Text
	var thank_you_up = window.fbpvars.thank_you_up;
	var thank_you_down = window.fbpvars.thank_you_down;
	var already_voted = window.fbpvars.already_voted;
	var why_not = window.fbpvars.why_not;
	var incorrect_info = window.fbpvars.incorrect_info;
	var dont_like = window.fbpvars.dont_like;
	var confusing = window.fbpvars.confusing;
	var not_answer = window.fbpvars.not_answer;
	var too_much = window.fbpvars.too_much;
	var other = window.fbpvars.other;
	var error_voting = window.fbpvars.error_voting;
	
	// Category & Item view
	if (page_view == 'category' || page_view == 'item') 
	{
	  // Active leftnav li
		var catid = window.fbpvars.catid;
    jQuery('#liid'+catid).addClass('li_selected');   
		
	  // Active leftnav ul
		jQuery('#liid'+catid).parents('ul.NavLeftUL_sublist').addClass('NavLeftUL_expanded');   
		var parent_ul_class = jQuery('#liid'+catid).parent('ul').attr('class');
		if (leftnav > 0) {
  		if (parent_ul_class != 'NavLeftUL_parent') 
  		{
  		  var ul_level = parent_ul_class.split(" ")[1];
  		  var ul_level_num = ul_level.substring(ul_level.lastIndexOf('level') + 5);
  		  //var move_ul = parseInt(ul_level_num)*fbp_width;
		  var move_ul = parseInt(ul_level_num)*100;
  		  //jQuery('.fbpLeftNavigation_wrap').css({"left":"-"+move_ul+"px"});   
		  //jQuery('.fbpLeftNavigation_wrap').css({"left":"-100%"});   
		  jQuery('.fbpLeftNavigation_wrap').css({"left":"-"+move_ul+"%"});   	
  		
  		  // Active topnav li
  		  var parents_num = parseInt(ul_level_num);
  		  var first_parent_text = jQuery('#liid'+catid).parent().parent().find('> .NavLeftUL_anchor span.catTitle').text();
  	    var first_parent_id = jQuery('#liid'+catid).parent('ul').parent('li').attr('id');
  		  	
  		  jQuery('.fbpTopNavigation_root li.NavTopUL_firstChild').removeClass('NavTopUL_lastChild');
  		  
  		  // Add topnav li's   
  			var $id = jQuery('#'+first_parent_id);
  			var $li = jQuery('#'+first_parent_id);
  			
  			function findParents()
  			{
    		  $id = $id.parent().parent();
    			$li = $li.parent('ul').parent('li');
    			var prev_parent_text = $id.find('> .NavLeftUL_anchor span.catTitle').text();     
    			var prev_parent_id = $li.attr('id');
  		
  				jQuery('<li id="top_'+prev_parent_id+'" class="NavTopUL_item"><span class="NavTopUL_icon"></span><a class="NavTopUL_link" href="#" onclick="return false;">'+prev_parent_text+'</a></li>').insertAfter('li.NavTopUL_firstChild');
  		  }
  		 
  		  for (var i = 1; i < parents_num; i++) 
  		  {
  		    findParents();  
  		  }		
  		  
  		  // Add lastChild li
  		  jQuery('.fbpTopNavigation_root').append(jQuery('<li id="top_'+first_parent_id+'" class="NavTopUL_item NavTopUL_lastChild"><span class="NavTopUL_icon"></span><a class="NavTopUL_link" href="#" onclick="return false;">'+first_parent_text+'</a></li>'));	
  	  }
	  
		}
	}
	
	function loadHome(){ 
	  var item_id = window.fbpvars.item_id;
		
	  jQuery('.show_menu').removeClass('fbp-shown');	
	  
	  jQuery.ajax({
      type: "POST",
			url: site_path+"index.php?option=com_faqbookpro&view=faqbook&tmpl=default_content&format=raw&task=loadHome&items="+items+"&cols="+cols+"&title="+title+"&desc="+desc+"&limit="+limit+"&image="+image+"&imgsize="+imgsize+"&imgheight="+imgheight+"&featured="+featured+"&Itemid="+item_id+"&" + token,
			beforeSend: function() {
				window.history.pushState({}, document.title, index_link); // change url dynamically
			},
      success: function(msg) {
        //jQuery(".fbpContent_root").ajaxComplete(function(event, request, settings) {
          jQuery(".fbpTopNavigation_wrap").removeClass('NavTopULloading');  
				  jQuery(".fbpContent_root").hide().html(msg).fadeIn('fast');
		
					jQuery('.NavLeftUL_item').removeClass('li_selected'); 
					jQuery(document).attr('title', page_title); // change browser title dynamically
        //});			
      }
    });
	}
	
	function loadEndpoint(id, this_liid, href_link, cat_title){
			
	  var item_id = window.fbpvars.item_id;         
    jQuery.ajax({
      type: "POST",
			url: site_path+"index.php?option=com_faqbookpro&view=category&format=raw&id=" + id + "&Itemid=" + item_id + "&" + token,
	  beforeSend: function() {
				jQuery('#'+this_liid).addClass('li_loading'); 
				window.history.pushState({}, document.title, href_link);
			},
      success: function(msg) {
		  			// Show Leftnav again in case it is hidden
		    		jQuery('.fbpLeftNavigation_core').addClass('fbp-hidden');	
					
					// Add 'show menu' button
					jQuery('.show_menu').addClass('fbp-shown');	
			
					jQuery('#'+this_liid).removeClass('li_loading'); 
					jQuery('.NavLeftUL_item').removeClass('li_selected'); 
					jQuery('#'+this_liid).addClass('li_selected'); 
				    jQuery(".fbpContent_root").hide().html(msg).fadeIn('fast');
					jQuery(document).attr('title', cat_title); // change browser title dynamically
					
					// Fix search form dimensions on leftnav click
					var search_div = jQuery('.fbpTopNavigation_search');
					var sheight = jQuery('.fbpTopNavigation_core').outerHeight();
					var swidth = jQuery('.fbpTopNavigation_core').width();
					jQuery(search_div).css({"height":sheight+"px", "width":swidth+"px"});
					jQuery('#u_0_3').css({"height":sheight-4+"px"});		
							
					// Toggle FAQ in Category
        			jQuery('.category_faqToggleLink').on('click', function(event, faq_id)
            		{	
        	  			event.preventDefault;
        				var this_faqid = jQuery(this).attr('id');
        				var faq_id = this_faqid.split("_").pop(0);
        		
						jQuery('#a_w_'+faq_id+' .category_faqAnswerWrapper_inner').toggle();
						if (jQuery('#faq_'+faq_id).hasClass('faq_open')) {
							jQuery('#faq_'+faq_id).removeClass('faq_open');
						} else {
							jQuery('#faq_'+faq_id).addClass('faq_open');
							// Hits script
							addHit(faq_id, event);
						}
								
        	});
	
			// FAQ thumb_up voting
			var thumbs_up = jQuery('.thumbs_up').on('click', function(event, faq_id)
		  	{	
			  event.preventDefault;
			  var this_faqid = jQuery(this).attr('id');
			  var faq_id = this_faqid.split("up_").pop(0);
					
			  var thumbs_up_class = jQuery('#thumbs_up_'+faq_id).attr('class');
			  var thumbs_down_class = jQuery('#thumbs_down_'+faq_id).attr('class');
			  jQuery('#a_w_'+faq_id+' .vote_result_text').remove();
			  jQuery('#a_w_'+faq_id+' .vote_exists_text').remove();
			  if (thumbs_down_class.indexOf("loading") == -1 && thumbs_up_class.indexOf("loading") == -1) {
				faqThumbsUp(faq_id, event);
			  }
			});
			
			// FAQ thumb_down voting
			var thumbs_down = jQuery('.thumbs_down').on('click', function(event, faq_id)
		  	{	
			  event.preventDefault;
				var this_faqid = jQuery(this).attr('id');
				var faq_id = this_faqid.split("down_").pop(0);
				
				var thumbs_up_class = jQuery('#thumbs_up_'+faq_id).attr('class');
				var thumbs_down_class = jQuery('#thumbs_down_'+faq_id).attr('class');
				jQuery('#a_w_'+faq_id+' .vote_result_text').remove();
				jQuery('#a_w_'+faq_id+' .vote_exists_text').remove();
				if (thumbs_up_class.indexOf("loading") == -1 && thumbs_down_class.indexOf("loading") == -1) {
				  faqThumbsDown(faq_id, event);
			  }
			});
			
      	}
    });
  }
	
	// Add class 'expanded' to 1st child ul / Move wrap to the left
	jQuery('#NavLeftUL li.NavLeftUL_item').each(function(this_liid) 
	{ 
	  var this_liid = this['id'];
		
		jQuery('#'+this_liid).find('a:first').on('click', function(event) 
		{
		  event.preventDefault;
	
		  var this_liclass = jQuery(this).parent('li').attr('class');
			
			if (this_liclass == 'NavLeftUL_item NavLeftUL_endpoint' || this_liclass == 'NavLeftUL_item NavLeftUL_endpoint li_selected') 
			{} else {
			  // Front - Get ul height
  			var parent_li = jQuery(this).parent(); 
  			var child_ul = jQuery(parent_li).find('ul:first'); 
      	var eheight = jQuery(child_ul).height();
				jQuery('.fbpLeftNavigation_root').css({"height":eheight+"px"});
			}
			
	    if (this_liclass === 'NavLeftUL_item' || this_liclass === 'NavLeftUL_item li_selected')
			{		
			  if(jQuery('.fbpLeftNavigation_wrap:animated').length == 0) { // Keep track of animation to prevent double clicks
		      jQuery('#'+this_liid).find('ul:first').addClass('NavLeftUL_expanded');	
			    var lefty = jQuery('.fbpLeftNavigation_wrap');
          lefty.animate(
			      //{left:"-="+fbp_width+"px"},
				  {left:"-=100%"},
					  {queue: true, complete: function(){ 
						  					   
						jQuery('.fbpTopNavigation_root li').removeClass('NavTopUL_lastChild');
							var this_title = jQuery('#'+this_liid).find('a:first').text();						
			        		jQuery('.fbpTopNavigation_root').append(jQuery('<li id="top_'+this_liid+'" class="NavTopUL_item NavTopUL_lastChild"><span class="NavTopUL_icon"></span><a class="NavTopUL_link" href="#" onclick="return false;">'+this_title+'</a></li>'));		
							// Fix search form dimensions on leftnav click
							var search_div = jQuery('.fbpTopNavigation_search');
							var sheight = jQuery('.fbpTopNavigation_core').outerHeight();
							var swidth = jQuery('.fbpTopNavigation_core').width();
							jQuery(search_div).css({"height":sheight+"px", "width":swidth+"px"});
							jQuery('#u_0_3').css({"height":sheight-4+"px"});			  
						} 
						
			    });
			  }
			}
			if (this_liclass === 'NavLeftUL_item NavLeftUL_endpoint' || this_liclass === 'NavLeftUL_item NavLeftUL_endpoint li_selected')
			{
			  var endpoint_liid = jQuery(this).parent('li').attr('id');
				var endpoint_id = endpoint_liid.split("id").pop(1);
				
				var href_link = jQuery(this).attr('href');
				var cat_title = jQuery(this).text();

			  loadEndpoint(endpoint_id, this_liid, href_link, cat_title);
			}
			
		});
		
	});
	
	// Front - Get ul height
	jQuery('.NavLeftUL_backItem').on('click', function(event) 
	{	
	event.preventDefault;
  	var back_child_ul = jQuery(this).parent().parent().parent();
    var wheight = jQuery(back_child_ul).height();
    jQuery('.fbpLeftNavigation_root').css({"height":wheight+"px"});
	});
	
	// Home button - Fix leftnav height
	jQuery('.NavTopUL_firstChild').on('click', function(event) 
  {	
  event.preventDefault;
    var wheight = jQuery('.NavLeftUL_parent').height();
    jQuery('.fbpLeftNavigation_root').css({"height":wheight+"px"});
  });
  
	// Remove class 'expanded' from 1st parent ul / Move wrap to the right
	jQuery('#NavLeftUL li.NavLeftUL_backItem').each(function(this_backliid) 
	{ 
	  var this_backliid = this['id'];
		
		jQuery('#'+this_backliid).find('a:first').click(function(event) 
		{		
			event.preventDefault;
			var righty = jQuery('.fbpLeftNavigation_wrap');		
			if(jQuery('.fbpLeftNavigation_wrap:animated').length == 0) { // Keep track of animation to prevent double clicks
        righty.animate(
			    //{left:"+="+fbp_width+"px"}, 
				{left:"+=100%"}, 
				  {queue: false, complete: function(){ 
				  
			      jQuery('#'+this_backliid).parent('ul').removeClass('NavLeftUL_expanded'); 		
						jQuery('.fbpTopNavigation_root li:last').remove();
						jQuery('.fbpTopNavigation_root li:last').addClass('NavTopUL_lastChild');	
						// Fix search form dimensions on leftnav click
						var search_div = jQuery('.fbpTopNavigation_search');
						var sheight = jQuery('.fbpTopNavigation_core').outerHeight();
						var swidth = jQuery('.fbpTopNavigation_core').width();
						jQuery(search_div).css({"height":sheight+"px", "width":swidth+"px"});
						jQuery('#u_0_3').css({"height":sheight-4+"px"});						
			    } 
			  });
			}
			
		});
		
	});
	
	// Top Navigation
	//jQuery('.fbpTopNavigation_root li').live("click", function(event, this_liclass)
	jQuery('.fbpTopNavigation_root').on('click', 'li', function(event, this_liclass)
	{ 
	event.preventDefault;
	  var this_liclass = jQuery(this).attr('class');
		var this_liid = jQuery(this).attr('id');
		
		// Show Leftnav again in case it is hidden
		    jQuery('.fbpLeftNavigation_core').removeClass('fbp-hidden');	
			
		// Fix leftnav height
		if (this_liclass == 'NavTopUL_item')
		{	
		  	var this_id = this_liid.split("liid").pop(0);
			var left_child_ul = jQuery('#liid'+this_id).find('ul:first');
			var lheight = jQuery(left_child_ul).height();
			jQuery('.fbpLeftNavigation_root').css({"height":lheight+"px"});
		}		
		if (this_liclass == 'NavTopUL_item NavTopUL_firstChild')
		{
			var wheight = jQuery('.NavLeftUL_parent').height();
			jQuery('.fbpLeftNavigation_root').css({"height":wheight+"px"});	
		}
		
		if (this_liclass == 'NavTopUL_item NavTopUL_firstChild' || this_liclass == 'NavTopUL_item')
		{		
	    var li_count = jQuery('.fbpTopNavigation_root li').length;			
	    var li_index = jQuery(this).index();	
		  var slide_count = parseInt(li_count) - parseInt(li_index) - 1;			
		  
			var righty = jQuery('.fbpLeftNavigation_wrap');		
			//var move_right = slide_count * fbp_width;
			var move_right = slide_count * 100;
			if(jQuery('.fbpLeftNavigation_wrap:animated').length == 0) { // Keep track of animation to prevent double clicks
			
				if (this_liclass.indexOf("NavTopUL_firstChild") !== -1)
				{
				  jQuery(".fbpTopNavigation_wrap").addClass('NavTopULloading');  
        		}
				righty.animate(
			    //{left:"+="+move_right+"px"}, 
				{left:"+="+move_right+"%"}, 
				  {queue: false, complete: function(){ 
					
			      if (this_liclass.indexOf("NavTopUL_firstChild") !== -1)
						{
			  		  loadHome();
						}
			
					  var this_id = this_liid.split("_").pop(0);
						if (this_id === 'home') 
						{
						  jQuery('#NavLeftUL ul').removeClass('NavLeftUL_expanded');
						} 
						else 
						{
						  jQuery('#'+this_id+' ul ul').removeClass('NavLeftUL_expanded');
						}	
						for (var i = 0; i < slide_count; i++) {
						  jQuery('.fbpTopNavigation_root li:last').remove();
						  jQuery('.fbpTopNavigation_root li:last').addClass('NavTopUL_lastChild');	
				    	}
	
			      } 
			  }); // end righty
			}
		}
		if (this_liclass == 'NavTopUL_item NavTopUL_firstChild NavTopUL_lastChild')
		{	
		  jQuery(".fbpTopNavigation_wrap").addClass('NavTopULloading');  
		  loadHome();
		}
					
	});
	
	// Toggle FAQ in Category
	jQuery('.category_faqToggleLink').on('click', function(event, faq_id)
    {	
	  event.preventDefault;
		var this_faqid = jQuery(this).attr('id');
		var faq_id = this_faqid.split("_").pop(0);
		
		jQuery('#a_w_'+faq_id+' .category_faqAnswerWrapper_inner').toggle();
		if (jQuery('#faq_'+faq_id).hasClass('faq_open')) {
			jQuery('#faq_'+faq_id).removeClass('faq_open');
		} else {
			jQuery('#faq_'+faq_id).addClass('faq_open');
			// Hits script
			addHit(faq_id, event);
		}
		
	});
	
	// FAQ thumb_up voting
	var thumbs_up = jQuery('.thumbs_up').on('click', function(event, faq_id)
  {	
	  event.preventDefault;
	  var this_faqid = jQuery(this).attr('id');
	  var faq_id = this_faqid.split("up_").pop(0);
			
	  var thumbs_up_class = jQuery('#thumbs_up_'+faq_id).attr('class');
	  var thumbs_down_class = jQuery('#thumbs_down_'+faq_id).attr('class');
	  jQuery('#a_w_'+faq_id+' .vote_result_text').remove();
	  jQuery('#a_w_'+faq_id+' .vote_exists_text').remove();
	  if (thumbs_down_class.indexOf("loading") == -1 && thumbs_up_class.indexOf("loading") == -1) {
  	    faqThumbsUp(faq_id, event);
	  }
	});
	
	// FAQ thumb_down voting
	var thumbs_down = jQuery('.thumbs_down').on('click', function(event, faq_id)
  {	
	  event.preventDefault;
		var this_faqid = jQuery(this).attr('id');
		var faq_id = this_faqid.split("down_").pop(0);
		
		var thumbs_up_class = jQuery('#thumbs_up_'+faq_id).attr('class');
		var thumbs_down_class = jQuery('#thumbs_down_'+faq_id).attr('class');
		jQuery('#a_w_'+faq_id+' .vote_result_text').remove();
		jQuery('#a_w_'+faq_id+' .vote_exists_text').remove();
		if (thumbs_up_class.indexOf("loading") == -1 && thumbs_down_class.indexOf("loading") == -1) {
  		  faqThumbsDown(faq_id, event);
	  }
	});
	
	function faqThumbsUp(faq_id, event) {
	  jQuery.ajax({
      type: "POST",
			url: site_path+"index.php?option=com_faqbookpro&task=item.faqThumbsUp&id=" + faq_id + "&Itemid=" + item_id + "&" + token,
			beforeSend: function() {
			  jQuery('#thumbs_up_'+faq_id).addClass('loading');
			},
      success: function(msg) {
				  jQuery('#thumbs_up_'+faq_id).removeClass('loading');
					if(msg) {
				    jQuery('#thumbs_up_'+faq_id+' span').html(msg);	
			jQuery('#a_w_'+faq_id+' .faq_voting').append(jQuery('<div class="clearfix"> </div><span class="vote_result_text">'+thank_you_up+'</span>').hide().fadeIn(400));
						jQuery('#a_w_'+faq_id+' .vote_result_text').delay(2000).fadeOut(400);
					} else {
						if (jQuery('#a_w_'+faq_id+' .vote_exists_text')[0]){
						   jQuery('#a_w_'+faq_id+' .vote_exists_text').hide().fadeIn(400);
						   jQuery('#a_w_'+faq_id+' .vote_exists_text').delay(2000).fadeOut(400);
						} else {
							jQuery('#a_w_'+faq_id+' .vote_result_text').remove();
							jQuery('#a_w_'+faq_id+' .faq_voting').append(jQuery('<div class="clearfix"> </div><span class="vote_exists_text">'+already_voted+'</span>').hide().fadeIn(400));	
							jQuery('#a_w_'+faq_id+' .vote_exists_text').delay(2000).fadeOut(400);
						}
					}
	    }
	  });
	}
	
	function faqThumbsDown(faq_id, event) {
	  jQuery.ajax({
      type: "POST",
			url: site_path+"index.php?option=com_faqbookpro&task=item.faqThumbsDown&id=" + faq_id + "&Itemid=" + item_id + "&" + token,
			beforeSend: function() {
			  jQuery('#thumbs_down_'+faq_id).addClass('loading');
			  //event.preventDefault();
			},
      success: function(msg, event) {
				  jQuery('#thumbs_down_'+faq_id).removeClass('loading');
					if(msg) {
			        	jQuery('#thumbs_down_'+faq_id+' span').html(msg);	
						jQuery('#a_w_'+faq_id+' .faq_voting').append(jQuery('<div class="clearfix"> </div><div class="vote_reason_links" id="v_r_l_'+faq_id+'"><span class="why_not">'+why_not+'</span><div class="vote_reason"><a href="#" onclick="return false;" id="v_r_1" class="vote_reason_link">'+incorrect_info+'</a></div><div class="vote_reason"><a href="#" onclick="return false;" id="v_r_2" class="vote_reason_link">'+dont_like+'</a></div><div class="vote_reason"><a href="#" onclick="return false;" id="v_r_3" class="vote_reason_link">'+confusing+'</a></div><div class="vote_reason"><a href="#" onclick="return false;" id="v_r_4" class="vote_reason_link">'+not_answer+'</a></div><div class="vote_reason"><a href="#" onclick="return false;" id="v_r_5" class="vote_reason_link">'+too_much+'</a></div><div class="vote_reason"><a href="#" onclick="return false;" id="v_r_6" class="vote_reason_link">'+other+'</a></div></div>').hide().fadeIn(400));	
						
						// Attach voting reason event handlers
						var vote_reason_link = jQuery('.vote_reason_link').on('click', function(event, reason_id)
						{						
						  	event.preventDefault;					  	
							var this_reasonid = jQuery(this).attr('id');
							var reason_id = this_reasonid.split("v_r_").pop(0);
							var this_faqid = jQuery(this).parent().parent().attr('id');
							var faq_id = this_faqid.split("v_r_l_").pop(0);
							var vote_reason_class = jQuery('#v_r_l_'+faq_id+' span').attr('class');
							
							if (vote_reason_class.indexOf("loading") == -1) {
							    faqVoteReason(reason_id, faq_id, event);
							}
						});
											
					} else {
						if (jQuery('#a_w_'+faq_id+' .vote_exists_text')[0]){
						   jQuery('#a_w_'+faq_id+' .vote_exists_text').hide().fadeIn(500);
						   jQuery('#a_w_'+faq_id+' .vote_exists_text').delay(2000).fadeOut(400);
						} else {
							jQuery('#a_w_'+faq_id+' .vote_result_text').remove();
							jQuery('#a_w_'+faq_id+' .faq_voting').append(jQuery('<span class="vote_exists_text">'+already_voted+'</span>').hide().fadeIn(400));	
							jQuery('#a_w_'+faq_id+' .vote_exists_text').delay(2000).fadeOut(400);
						}
					}	
	    }
	  });
	}
	
	function faqVoteReason(reason_id, faq_id, event) {
	  jQuery.ajax({
      type: "POST",
			url: site_path+"index.php?option=com_faqbookpro&task=item.faqVoteReason&rid=" + reason_id + "&fid=" + faq_id + "&Itemid=" + item_id + "&" + token,
			beforeSend: function() {
			  jQuery('#v_r_l_'+faq_id+' span').addClass('loading');
			},
      success: function(msg) {
				  jQuery('#v_r_l_'+faq_id+' span').removeClass('loading');
					if(msg) {
						jQuery('#a_w_'+faq_id+' .vote_exists_text').remove();
						jQuery('#v_r_l_'+faq_id).remove();
						jQuery('#a_w_'+faq_id+' .faq_voting').append(jQuery('<span class="vote_result_text">'+thank_you_down+'</span>').hide().fadeIn(400));
						jQuery('#a_w_'+faq_id+' .vote_result_text').delay(2000).fadeOut(400);
					} else {					
						jQuery('#a_w_'+faq_id+' .faq_voting').append(jQuery('<span class="vote_result_text">'+error_voting+'</span>').hide().fadeIn(400));
						jQuery('#a_w_'+faq_id+' .vote_result_text').delay(2000).fadeOut(400);					
					}
	    }
	  });
	}
	
	// Open search form
	var open_search = jQuery('#fbpTopNavigation_search_icon').on('click', function(event)
  	{	
	event.preventDefault;
		jQuery('.fbpTopNavigation_search').fadeIn(500);	
	});
	
	// Show search form on page load
	if (page_view == 'search') 
	{	
		jQuery('.fbpTopNavigation_search').show();	
		var search_val = window.fbpvars.search_val;
		jQuery('#u_0_1').val(search_val);	
	}
	
	// Close search form
	var close_search = jQuery('#fbpTopNavigation_close_search').on('click', function(event)
  	{	
	event.preventDefault;
		jQuery('.fbpTopNavigation_search').fadeOut(500);		
	});
	
	// This will trigger form submission
	jQuery('#u_0_3').submit(function (e) {
      e.preventDefault(); 
	  
	  //if( !jQuery('#u_0_1').val() ) {
	  	//jQuery(this).parents('p').addClass('warning');
		//alert('empty');
	  //} else {
		  
		var search_val = jQuery('#u_0_1').val();
		searchFaqBook(search_val);
	  //}
		  
	});
	
	// Search function
	function searchFaqBook(search_val) {
      jQuery.ajax({
      type: "POST",
			//url: "index.php?option=com_faqbookpro&view=search&task=searchFaqBook&format=raw&word=" + search_val + "&Itemid=" + item_id + "&" + token,
			url: site_path+"index.php?option=com_faqbookpro&view=search&format=raw&word=" + search_val + "&Itemid=" + item_id + "&" + token,
			beforeSend: function() {
			  jQuery('.search_form_innerWrap').addClass('loading');
			  // change url dynamically
			  var encoded_search_link = window.fbpvars.search_link;
			  var search_link = encoded_search_link.replace(/&amp;/g, '&')+search_val;
			  window.history.pushState({}, document.title, search_link);
			},
      success: function(msg) {
				  jQuery('.search_form_innerWrap').removeClass('loading');
					if(msg) {
						// Hide Leftnav
						jQuery('.fbpLeftNavigation_core').addClass('fbp-hidden');
						
						// Add 'show menu' button
						jQuery('.show_menu').addClass('fbp-shown');	
						
						// Show Leftnav trigger
						//var show_leftnav = jQuery('#top_liid_home a').on('click', function(event)
						//{			
							// Show Leftnav again in case it is hidden
							//jQuery('.fbpLeftNavigation_core').removeClass('fbp-hidden');
						//});
						
						jQuery(".fbpContent_root").hide().html(msg).fadeIn('fast');
						
						// Fix leftnav height
						//var vheight = jQuery('.NavLeftUL_parent').height();
						//jQuery('.fbpLeftNavigation_root').css({"height":vheight+"px"});
		
						// Reset leftnav   
						/*var righty = jQuery('.fbpLeftNavigation_wrap');		
						if(jQuery('.fbpLeftNavigation_wrap:animated').length == 0) { // Keep track of animation to prevent double clicks		
								righty.animate(
								{left:"0px"}, 
								  {queue: false, complete: function(){ 
									jQuery('.NavLeftUL_item').removeClass('li_selected'); 
									jQuery(document).attr('title', page_title); // change browser title dynamically	   
									jQuery('ul.NavLeftUL_sublist').removeClass('NavLeftUL_expanded');
									
									// Reset topnav
									jQuery('ul.fbpTopNavigation_root').html('<li class="NavTopUL_item NavTopUL_firstChild NavTopUL_lastChild" id="top_liid_home"><a onclick="return false;" href="#" class="NavTopUL_link"><span class="NavTopUL_homeIcon"></span>Help Center</a></li>'); 			  
								  } 
							  });
						}*/
					}
	    }
	  });
	}
	
	// Open new faq form
	var open_form = jQuery('#fbpTopNavigation_ask_icon').on('click', function(event)
  	{		
	event.preventDefault;	
		var ask_button_class = jQuery('#fbpTopNavigation_ask_icon').attr('class');
		if (ask_button_class.indexOf("loading") == -1) {
  		  	askNewFaq(event);
	  	}
	});
	
	function addHit(faq_id, event) {
		//alert('yannis');
		jQuery.ajax({
			type: "POST",
		 	url: site_path+"index.php?option=com_faqbookpro&task=item.addHit&id=" + faq_id + "&Itemid=" + item_id + "&" + token,
		  	beforeSend: function() {},
		  	success: function(msg) {}
	  	});  
	}
	
	// Ask new FAQ function
	function askNewFaq(event) {
      jQuery.ajax({
      type: "POST",
			url: site_path+"index.php?option=com_faqbookpro&view=article&layout=edit&format=raw&Itemid=" + item_id + "&" + token,
			beforeSend: function() {
			  jQuery('#fbpTopNavigation_ask_icon').addClass('loading');
			  // change url dynamically
			  var encoded_ask_link = window.fbpvars.ask_link;
			  var ask_link = encoded_ask_link.replace(/&amp;/g, '&');
			  window.history.pushState({}, document.title, ask_link);
			},
      success: function(msg) {
				  jQuery('#fbpTopNavigation_ask_icon').removeClass('loading');
					if(msg) {
						// Hide Leftnav
						jQuery('.fbpLeftNavigation_core').addClass('fbp-hidden');
						
						// Add 'show menu' button
						jQuery('.show_menu').addClass('fbp-shown');	
						
						// Load form
						jQuery(".fbpContent_root").hide().html(msg).fadeIn('fast');
						
						function showRecaptcha(element) {
                        Recaptcha.create(captcha_key, element, {
                        theme: "clean",
                        /*callback: Recaptcha.focus_response_field*/});
						}
						
						var checkRecaptchaDivExists = setInterval(function(){
							if (jQuery('#dynamic_recaptcha_1').length){
								showRecaptcha('dynamic_recaptcha_1'); 
								clearInterval(checkRecaptchaDivExists); 
							}
						},100);
						// Fix leftnav height
						/*var vheight = jQuery('.NavLeftUL_parent').height();
						jQuery('.fbpLeftNavigation_root').css({"height":vheight+"px"});*/
		
						// Reset leftnav   
						/*var righty = jQuery('.fbpLeftNavigation_wrap');		
						if(jQuery('.fbpLeftNavigation_wrap:animated').length == 0) { // Keep track of animation to prevent double clicks		
								righty.animate(
								{left:"0px"}, 
								  {queue: false, complete: function(){ 
									jQuery('.NavLeftUL_item').removeClass('li_selected'); 
									jQuery(document).attr('title', page_title); // change browser title dynamically	   
									jQuery('ul.NavLeftUL_sublist').removeClass('NavLeftUL_expanded');
									
									// Reset topnav
									jQuery('ul.fbpTopNavigation_root').html('<li class="NavTopUL_item NavTopUL_firstChild NavTopUL_lastChild" id="top_liid_home"><a onclick="return false;" href="#" class="NavTopUL_link"><span class="NavTopUL_homeIcon"></span>Help Center</a></li>'); 			  
								  } 
							  });
						} */
					}
	    }
	  });
	}
	
	// Hide Show menu button / Show leftnav
	var show_leftnav = jQuery('.show_menu').on('click', function(event)
  	{			
	event.preventDefault;
		jQuery('.show_menu').removeClass('fbp-shown');
		jQuery('.fbpLeftNavigation_core').removeClass('fbp-hidden');
	});
	
});