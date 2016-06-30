// jQuery(document).ready(function() {
	
// 	var sticky_widget = jQuery('.cs-fancy-menu');
// 	if(sticky_widget.children('.shortcode-nav').hasClass('cs-stickynav')){
// 		sticky_widget.parent('aside').addClass('cs-sticky-nav');
// 	}
	
// 	jQuery('.page-sidebar.cs-sticky-nav, .page-content').theiaStickySidebar({
// 		additionalMarginTop: 30
// 	});
		
// });


/* ---------------------------------------------------------------------------
	* nice scroll for theme
 	* --------------------------------------------------------------------------- */
	function cs_nicescroll(){
		'use strict';	
		var nice = jQuery("html").niceScroll({mousescrollstep: "20",scrollspeed: "150",}); 
	}

/* ---------------------------------------------------------------------------
   * Login Click Function
   * --------------------------------------------------------------------------- */
  // jQuery('.cs-signup').hide();
  // 	jQuery("a.cs-user").click(function(){
  // 		jQuery('.cs-signup').hide();
  //   	jQuery(".cs-signup").fadeToggle();
  //  });
  //  jQuery('html').click(function() {
  //  	jQuery(".cs-signup").fadeOut();
  //  });
  // jQuery('.cs-login-sec').click(function(event){
  //      event.stopPropagation();
  //  });

/* ---------------------------------------------------------------------------
   * Search Toggle Function
   * --------------------------------------------------------------------------- */
  jQuery('.cs-search form').hide();
  	jQuery("a.cs_searchbtn").click(function(){
  		jQuery('.cs-search form').hide();
    	jQuery(".cs-search form").fadeToggle();
   });
   jQuery('html').click(function() {
   	jQuery(".cs-search form").fadeOut();
   });
  jQuery('.cs-search').click(function(event){
       event.stopPropagation();
   });

/* ---------------------------------------------------------------------------
   * Navigation Height Function
   * --------------------------------------------------------------------------- */
  jQuery(document).ready(function($) {
  	{
  	    if ($('.logo a,.navigation ul > li > a,.main-navbar .cs-search').length) {
  	        var contentH = $('.main-navbar').height() - 0;
  	        $('.logo a,.navigation > ul > li > a,.main-navbar .cs-search').height();
  	        $('.logo a,.navigation  > ul > li > a,.main-navbar .cs-search').css('min-height', contentH + 'px');
  	        $('.logo a,.navigation > ul > li > a,.main-navbar .cs-search').css('line-height', contentH + 'px');
  	    }
      }
  });

/* ---------------------------------------------------------------------------
   * Navigation DropDown Arrow Function
   * --------------------------------------------------------------------------- */
  jQuery(document).ready(function($) {
	
  	jQuery(".sub-dropdown").parent("li").addClass("parentIcon");
  	jQuery(".sub-menu").parent("li").addClass("before-menu");
  	jQuery(".mega-grid").parent("li").addClass("parentIcon");
  });
  //Directory Listing
  jQuery(document).ready(function($) {
  	jQuery(".sub-category").parent("li").addClass("ctg_parent");
  });


/* ---------------------------------------------------------------------------
 * Hover on Section Function
 * --------------------------------------------------------------------------- */
  jQuery(document).ready(function(){
    jQuery(".blog-box").hover(function(){
      jQuery(this).find(".bloginfo-sec").stop().animate({bottom:0}, 500);},
      function() {
        jQuery(this).find('.bloginfo-sec').stop().animate({bottom:-67}, 500);
    });
  });


/* ---------------------------------------------------------------------------
 * Footer Back To Top Function
 * --------------------------------------------------------------------------- */
	jQuery(document).ready(function(){
		//Click event to scroll to top
		jQuery('#backtop').click(function(){
			jQuery('html, body').animate({scrollTop : 0},800);
			return false;
		});
		
	});
	
	
/* ---------------------------------------------------------------------------
* Parallex Function
* --------------------------------------------------------------------------- */
function cs_parallax_func(){
	"use strict";
	// Cache the Window object     
	jQuery('section.parallex-bg[data-type="background"]').each(function(){
		var $bgobj = jQuery(this); // assigning the object
		jQuery(window).scroll(function() {
			// Scroll the background at var speed
			// the yPos is a negative value because we're scrolling it UP!								
			var yPos = -(jQuery(window).scrollTop() / $bgobj.data('speed')); 
			// Put together our final background position
			var coords = '50% '+ yPos + 'px';
			// Move the background
			$bgobj.css({ backgroundPosition: coords });
		}); // window scroll Ends
	});
}

/* ---------------------------------------------------------------------------
* Mailchimp Function
* --------------------------------------------------------------------------- */
function cs_mailchimp_submit(theme_url,counter,admin_url){
	
		'use strict';
		$ = jQuery;
		$('#btn_newsletter_'+counter).hide();
		$('#process_'+counter).html('<div id="process_newsletter_'+counter+'"><i class="icon-refresh icon-spin"></i></div>');
		$.ajax({
			type:'POST', 
			url: admin_url,
			data:$('#mcform_'+counter).serialize()+'&action=cs_mailchimp', 
			success: function(response) {
				$('#mcform_'+counter).get(0).reset();
				$('#newsletter_mess_'+counter).fadeIn(600);
				$('#newsletter_mess_'+counter).html(response);
				$('#btn_newsletter_'+counter).fadeIn(600);
				$('#process_'+counter).html('');
			}
		});
	}
/* ---------------------------------------------------------------------------
	* skills Function
 	* --------------------------------------------------------------------------- */
	function cs_skill_bar(){
		
		"use strict";	 
		jQuery(document).ready(function($){
			jQuery('.skillbar').each(function($) {
				jQuery(this).waypoint(function(direction) {
					jQuery(this).find('.skillbar-bar').animate({
						width: jQuery(this).attr('data-percent')
					}, 2000);
				}, {
					offset: "100%",
					triggerOnce: true
				});
			});
		});
	}


/* ---------------------------------------------------------------------------
	 * Banner ads Click Counter 
	 * --------------------------------------------------------------------------- */

	function cs_banner_click_count_plus(ajax_url, id){
		'use strict';
		var dataString='code_id='+id+'&action=cs_banner_click_count_plus';
		jQuery.ajax({
			type:"POST",
			url: ajax_url,
			data:dataString, 
			success:function(response){
				if(response != 'error'){
					jQuery("#cs_banner_clicks"+id).removeAttr("onclick");
				}
			}
		});
		return false;
	}



/* ---------------------------------------------------------------------------
	 * Map Styles
	 * --------------------------------------------------------------------------- */
	function cs_map_select_style(style){
		
		var styles = '';
		if(style == 'style-1'){
			var styles = [
							{
								"featureType": "administrative",
								"elementType": "all",
								"stylers": [
									{
										"visibility": "on"
									},
									{
										"lightness": 33
									}
								]
							},
							{
								"featureType": "landscape",
								"elementType": "all",
								"stylers": [
									{
										"color": "#f2e5d4"
									}
								]
							},
							{
								"featureType": "poi.park",
								"elementType": "geometry",
								"stylers": [
									{
										"color": "#c5dac6"
									}
								]
							},
							{
								"featureType": "poi.park",
								"elementType": "labels",
								"stylers": [
									{
										"visibility": "on"
									},
									{
										"lightness": 20
									}
								]
							},
							{
								"featureType": "road",
								"elementType": "all",
								"stylers": [
									{
										"lightness": 20
									}
								]
							},
							{
								"featureType": "road.highway",
								"elementType": "geometry",
								"stylers": [
									{
										"color": "#c5c6c6"
									}
								]
							},
							{
								"featureType": "road.arterial",
								"elementType": "geometry",
								"stylers": [
									{
										"color": "#e4d7c6"
									}
								]
							},
							{
								"featureType": "road.local",
								"elementType": "geometry",
								"stylers": [
									{
										"color": "#fbfaf7"
									}
								]
							},
							{
								"featureType": "water",
								"elementType": "all",
								"stylers": [
									{
										"visibility": "on"
									},
									{
										"color": "#acbcc9"
									}
								]
							}
						];
		}
		else if(style == 'style-2'){
			var styles = [
							{
								"featureType": "landscape",
								"stylers": [
									{
										"hue": "#FFBB00"
									},
									{
										"saturation": 43.400000000000006
									},
									{
										"lightness": 37.599999999999994
									},
									{
										"gamma": 1
									}
								]
							},
							{
								"featureType": "road.highway",
								"stylers": [
									{
										"hue": "#FFC200"
									},
									{
										"saturation": -61.8
									},
									{
										"lightness": 45.599999999999994
									},
									{
										"gamma": 1
									}
								]
							},
							{
								"featureType": "road.arterial",
								"stylers": [
									{
										"hue": "#FF0300"
									},
									{
										"saturation": -100
									},
									{
										"lightness": 51.19999999999999
									},
									{
										"gamma": 1
									}
								]
							},
							{
								"featureType": "road.local",
								"stylers": [
									{
										"hue": "#FF0300"
									},
									{
										"saturation": -100
									},
									{
										"lightness": 52
									},
									{
										"gamma": 1
									}
								]
							},
							{
								"featureType": "water",
								"stylers": [
									{
										"hue": "#0078FF"
									},
									{
										"saturation": -13.200000000000003
									},
									{
										"lightness": 2.4000000000000057
									},
									{
										"gamma": 1
									}
								]
							},
							{
								"featureType": "poi",
								"stylers": [
									{
										"hue": "#00FF6A"
									},
									{
										"saturation": -1.0989010989011234
									},
									{
										"lightness": 11.200000000000017
									},
									{
										"gamma": 1
									}
								]
							}
						];
		}
		else if(style == 'style-3'){
			var styles = [
							{
								"featureType": "administrative",
								"elementType": "labels.text.fill",
								"stylers": [
									{
										"color": "#444444"
									}
								]
							},
							{
								"featureType": "landscape",
								"elementType": "all",
								"stylers": [
									{
										"color": "#f2f2f2"
									}
								]
							},
							{
								"featureType": "poi",
								"elementType": "all",
								"stylers": [
									{
										"visibility": "off"
									}
								]
							},
							{
								"featureType": "road",
								"elementType": "all",
								"stylers": [
									{
										"saturation": -100
									},
									{
										"lightness": 45
									}
								]
							},
							{
								"featureType": "road.highway",
								"elementType": "all",
								"stylers": [
									{
										"visibility": "simplified"
									}
								]
							},
							{
								"featureType": "road.arterial",
								"elementType": "labels.icon",
								"stylers": [
									{
										"visibility": "off"
									}
								]
							},
							{
								"featureType": "transit",
								"elementType": "all",
								"stylers": [
									{
										"visibility": "off"
									}
								]
							},
							{
								"featureType": "water",
								"elementType": "all",
								"stylers": [
									{
										"color": "#46bcec"
									},
									{
										"visibility": "on"
									}
								]
							}
						];
		}
		return styles;
	}


/* ---------------------------------------------------------------------------
	* Form Validation Function
 * --------------------------------------------------------------------------- */
function cs_form_validation(form_id){
	var name_field = jQuery('#frm'+form_id+' input[name="contact_name"]');
	var email_field = jQuery('#frm'+form_id+' input[name="contact_email"]');
	var subject_field = jQuery('#frm'+form_id+' input[name="subject"]');
	var message_field = jQuery('#frm'+form_id+' textarea[name="contact_msg"]');
	
	var name = name_field.val();
	var email = email_field.val();
	var subject = subject_field.val();
	var message = message_field.val();
	var email_pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
	
	var cs_error_form = true;
	if( name == ''){
		name_err_msg = '<p>Please Fill in Name</p>';
		name_field.addClass('cs-error');
		cs_error_form = false;
	}
	else{
		name_err_msg = '';
		name_field.removeClass('cs-error');
	}
	if(email == ''){
		email_err_msg = "<p>Please Enter Email.</p>";
		email_field.addClass('cs-error');
		cs_error_form = false;
	}
	else{
		email_err_msg = '';
		email_field.removeClass('cs-error');
	}
	if(email != ''){
		if(!email_pattern.test(email)){
			email_err_msg = "<p>Please Enter Valid Email.</p>";
			email_field.addClass('cs-error');
			cs_error_form = false;
		}
		else{
			email_err_msg = '';
			email_field.removeClass('cs-error');
		}
	}
	if( subject == ''){
		subject_err_msg = '<p>Please Fill in Subject</p>';
		subject_field.addClass('cs-error');
		cs_error_form = false;
	}
	else{
		subject_err_msg = '';
		subject_field.removeClass('cs-error');
	}
	if( message == ''){
		msg_err_msg = '<p>Please Fill in Message</p>';
		message_field.addClass('cs-error');
		cs_error_form = false;
	}
	else{
		msg_err_msg = '';
		message_field.removeClass('cs-error');
	}
	if(cs_error_form == true){
		cs_contact_frm_submit(form_id);
	}else{
		// do nothing 
	}
}

/* ---------------------------------------------------------------------------
  * Textarea Focus Function's
  * --------------------------------------------------------------------------- */
  jQuery(document).ready(function($){
	  "use strict";
		jQuery('input,textarea').focus(function(){
		   jQuery(this).data('placeholder',jQuery(this).attr('placeholder'))
		   jQuery(this).attr('placeholder','');
		});
		jQuery('input,textarea').blur(function(){
		   jQuery(this).attr('placeholder',jQuery(this).data('placeholder'));
		});
	});

/* ---------------------------------------------------------------------------
	*  menu toggle Function
	* --------------------------------------------------------------------------- */
	
jQuery(document).ready(function($) {
	jQuery(".navigation>ul") .prepend("<a class='cs-close-btn'><i class='icon-times-circle'></i></a>");
	MenuToggle();
	jQuery(".navigation .responsive-btn") .click(function(){
		if(jQuery(this).parent('li').hasClass('active')){
			jQuery(this).html("<i class='icon-plus8'></i>");
			jQuery('.navigation li').removeClass('active');
			jQuery(this).siblings('ul').hide();

		}else{
			jQuery(".navigation .responsive-btn").html("<i class='icon-plus8'></i>");
			jQuery(this).html("<i class='icon-minus8'></i>");
			jQuery('.navigation li').removeClass('active');
			jQuery(this).parent('li').addClass('active');
			jQuery(this).parent('li').parent('ul').find('li>ul').hide();
			jQuery(this).siblings('ul').show();
			return false;
		}
	});
	jQuery('.cs-click-menu').live('click', function(event) {
		 jQuery(this).next().toggle();
		jQuery(".navigation ul ul") .hide();
	});
	jQuery('.cs-close-btn').on('click', function(e){
		e.preventDefault();
		jQuery(this).parent('ul').hide();
	})
});
function MenuToggle() {
	jQuery(".navigation ul ul") .parent('li') .addClass('parentIcon');
	jQuery(".navigation ul ul") .parent('li') .append( "<span class='responsive-btn'><i class='icon-plus8'></i></span>" );
}
jQuery(window).resize(function($) {
	var a = jQuery(window).width();
	var b = 1000
	if (a >= b) {
		jQuery(".navigation ul ul,.navigation ul") .show();
	}else{
		jQuery(".navigation ul ul,.navigation ul") .hide();
	}
		
});


// Main Search Bar
// jQuery(document).ready(function(){
// 	var totalWidth = jQuery('.dir-search-fields').width();
// 	var totalItems = jQuery('.dir-search-fields').find('>ul>li').length -1;
// 	var paddingLeft = parseInt(jQuery('.dir-search-fields').find('>ul>li').css('paddingLeft'));
// 	var lastWidth = jQuery('.dir-search-fields').find('>ul>.submit-button').width();
// 	var ItemsWidth = (totalWidth - lastWidth) / (totalItems);
// 	jQuery('.dir-search-fields').find('>ul>li').css({'width':ItemsWidth});
// 	jQuery('.dir-search-fields').find('>ul>.submit-button').css({'width':'68px'});
// });

/* ---------------------------------------------------------------------------
  * Responsive Video Function
  * --------------------------------------------------------------------------- */

  jQuery(document).ready(function($) {
    jQuery(".main-section").fitVids();
  });

(function(e){"use strict";e.fn.fitVids=function(t){var n={customSelector:null,ignore:null};if(!document.getElementById("fit-vids-style")){var r=document.head||document.getElementsByTagName("head")[0];var i=".fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}";var s=document.createElement("div");s.innerHTML='<p>x</p><style id="fit-vids-style">'+i+"</style>";r.appendChild(s.childNodes[1])}if(t){e.extend(n,t)}return this.each(function(){var t=['iframe[src*="player.vimeo.com"]','iframe[src*="youtube.com"]','iframe[src*="youtube-nocookie.com"]','iframe[src*="kickstarter.com"][src*="video.html"]',"object","embed"];if(n.customSelector){t.push(n.customSelector)}var r=".fitvidsignore";if(n.ignore){r=r+", "+n.ignore}var i=e(this).find(t.join(","));i=i.not("object object");i=i.not(r);i.each(function(){var t=e(this);if(t.parents(r).length>0){return}if(this.tagName.toLowerCase()==="embed"&&t.parent("object").length||t.parent(".fluid-width-video-wrapper").length){return}if(!t.css("height")&&!t.css("width")&&(isNaN(t.attr("height"))||isNaN(t.attr("width")))){t.attr("height",9);t.attr("width",16)}var n=this.tagName.toLowerCase()==="object"||t.attr("height")&&!isNaN(parseInt(t.attr("height"),10))?parseInt(t.attr("height"),10):t.height(),i=!isNaN(parseInt(t.attr("width"),10))?parseInt(t.attr("width"),10):t.width(),s=n/i;if(!t.attr("id")){var o="fitvid"+Math.floor(Math.random()*999999);t.attr("id",o)}t.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top",s*100+"%");t.removeAttr("height").removeAttr("width")})})}})(window.jQuery||window.Zepto)
