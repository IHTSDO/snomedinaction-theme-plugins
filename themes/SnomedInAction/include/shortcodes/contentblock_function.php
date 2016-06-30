<?php
/**
 * File Type: Content Blockd Shortcode Function
 */
 
//======================================================================
// Adding Info Box start
//======================================================================

if (!function_exists('cs_infobox_shortcode')) {
    function cs_infobox_shortcode($atts, $content = "") {
        global $cs_infobox_list_text_color;
        $defaults = array('column_size'=>'1/1', 'cs_infobox_section_title' => '', 'cs_infobox_title' => '','cs_infobox_bg_color' => '','cs_infobox_list_text_color'=>'','cs_infobox_class' => '','cs_infobox_animation' => '');
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
        
        $CustomId    = '';
        if ( isset( $cs_infobox_class ) && $cs_infobox_class ) {
            $CustomId    = 'id="'.$cs_infobox_class.'"';
        }
        
        $html             = '';
        $cs_infobox_list_text_color_style = '';
        if($cs_infobox_list_text_color != ''){
            $cs_infobox_list_text_color_style = 'style="color: '.$cs_infobox_list_text_color.' !important;"';
        }
        $section_title = '';
        if ($cs_infobox_section_title && trim($cs_infobox_section_title) !='') {
            $section_title    = '<div class="cs-section-title"><h2>'.$cs_infobox_section_title.'</h2></div>';
        }
        $cs_infobox_bg_color_style = '';
        if($cs_infobox_bg_color != ''){
            $cs_infobox_bg_color_style = 'style="background-color: '.$cs_infobox_bg_color.'"';
        }
        
        if ( trim($cs_infobox_animation) !='' ) {
            $cs_infobox_animation    = 'wow'.' '.$cs_infobox_animation;
        } else {
            $cs_infobox_animation    = '';
        }
        
        $html    .= '<div class="cs-contact-info has_border '.$cs_infobox_class.' '.$cs_infobox_animation.'"  '.$cs_infobox_bg_color_style.'>';
            
            if($cs_infobox_title != ''){
                $html    .= '<h3 '.$cs_infobox_list_text_color_style.'>'.$cs_infobox_title.'</h3>';
            }
            $html    .= '<div class="liststyle">';
                $html    .= '<ul class="cs-unorderedlist has_border">';
                    $html    .= do_shortcode($content);
                $html    .= '</ul>';
            $html    .= '</div>';
        $html    .= '</div>';
        return '<div '.$CustomId.' class="'.$column_class.'">'.$section_title.'' . $html . '</div>';
    }
    add_shortcode('cs_infobox', 'cs_infobox_shortcode');
}

//======================================================================
// Adding Info Box item start
//======================================================================
if (!function_exists('cs_infobox_item_shortcode')) {
    function cs_infobox_item_shortcode($atts, $content = "") {
        global $cs_infobox_list_text_color;
        $defaults = array('cs_infobox_list_icon'=>'','cs_infobox_list_color'=>'','cs_infobox_list_title'=>'');
        extract( shortcode_atts( $defaults, $atts ) );
        $html = '<li class="has_border">';
            $cs_infobox_icon_color_style = '';
            $cs_infobox_list_text_color_style = '';
            if($cs_infobox_list_color != ''){
                $cs_infobox_icon_color_style = 'style="color: '.$cs_infobox_list_color.'"';
            }
            if($cs_infobox_list_text_color != ''){
                $cs_infobox_list_text_color_style = 'style="color: '.$cs_infobox_list_text_color.' !important;"';
            }
            if($cs_infobox_list_icon != ''){
                $html    .= '<i class="'.$cs_infobox_list_icon.'" '.$cs_infobox_icon_color_style.'></i>';
            }
            if($cs_infobox_list_title != ''){
                $html    .= ' <strong '.$cs_infobox_list_text_color_style.'>'.$cs_infobox_list_title.'</strong><br/>';
            }
            if($content != ''){
                $html    .= ' <span '.$cs_infobox_list_text_color_style.'>'.do_shortcode($content).'</span>';
            }

        $html    .= '</li>';
        
        return $html;
    }
    add_shortcode('infobox_item', 'cs_infobox_item_shortcode');
}

//======================================================================
// Adding Icon start
//======================================================================
if (!function_exists('cs_icons_shortcode')) {
    function cs_icons_shortcode($atts, $content = "") {
        $defaults = array( 'font_type' => '','icon_view' => '','font_size' => '','icon_color' => '','icon_bg_color' => '','font_icon' => '','cs_icons_class' => '','cs_icons_animation' => '');
        extract( shortcode_atts( $defaults, $atts ) );
        
        $CustomId    = '';
        $CircularView    = '';
        $background    = '';
        $borderStyle    = '';
        if ( isset( $cs_icons_class ) && $cs_icons_class ) {
            $CustomId    = 'id="'.$cs_icons_class.'"';
        }
        
        if ( isset( $font_type ) && $font_type == 'circle' ) {
            $CircularView    = 'icon-circle';
        }
        
        if ( isset( $icon_view ) && $icon_view == 'bg_style' ) {
            $background    = 'style="background-color:'.$icon_bg_color.' !important;"';
        }else if ( isset( $icon_view ) && $icon_view == 'border_style' ) {
            $background    = 'style="border: 2px solid '.$icon_bg_color.' !important;"';
            $borderStyle    = 'stroke';
        }
        
        $html = '';
        
        $icon_color        = $icon_color ? $icon_color : '';
        $icon_bg_color    = $icon_bg_color ? $icon_bg_color : '#000';
        
        if ( trim($cs_icons_animation) !='' ) {
            $cs_icons_animation    = 'wow'.' '.$cs_icons_animation;
        } else {
            $cs_icons_animation    = '';
        }
        $html    .= '<div class="col-md-2 '.$CircularView.' '.$font_size.' '.$cs_icons_animation.' '.$font_type.'" '.$CustomId.'>';
        $html    .= '<div class="colored-icon '.$borderStyle.'">';
        $html    .= '<span '.$background.'><i class="'.$font_icon.'" style="color:'.$icon_color.'"></i></span>';
        $html    .= '</div>';
        $html    .= '</div>';
        return $html;
    }
    add_shortcode('cs_icons', 'cs_icons_shortcode');
}
//======================================================================
// Adding map shortcode Start
//======================================================================
if (!function_exists('cs_map_shortcode')) {
    function cs_map_shortcode($atts, $content = "") {
        global $header_map;
        $defaults = array('column_size'=>'1/1','cs_map_section_title'=>'','map_title'=>'','map_height'=>'','map_lat'=>'-0.127758','map_lon'=>'51.507351','map_zoom'=>'','map_type'=>'','map_info'=>'','map_info_width'=>'200','map_info_height'=>'200','map_marker_icon'=>'','map_show_marker'=>'true','map_controls'=>'','map_draggable' => '','map_scrollwheel' => '','map_conactus_content' => '','map_border' => '','map_border_color' => '','cs_map_style' => '','cs_map_class' => '','cs_map_animation' => '','cs_custom_animation_duration'=>'1','cs_map_directions'=>'off');
        extract( shortcode_atts( $defaults, $atts ) );
        
        $CustomId    = '';
        if ( isset( $cs_map_class ) && $cs_map_class ) {
            $CustomId    = 'id="'.$cs_map_class.'"';
        }
        
        if ( $map_info_width == '' || $map_info_height == '' ){
            $map_info_width    = '300';
            $map_info_height    = '150';
        }
        
        if ( isset( $map_height ) && $map_height == '' ) {
            $map_height    = '500';
        }
        
        if( $header_map ) {
            $column_class  = '';
            $header_map    = false;
        } else {
            $column_class  = cs_custom_column_class($column_size);
        }
        
        $section_title = '';
        
        if ($cs_map_section_title && trim($cs_map_section_title) !='') {
            $section_title    = '<div class="cs-section-title"><h2>'.$cs_map_section_title.'</h2></div>';
        }
        $map_dynmaic_no = rand(6548,9999999);
        if ( $map_show_marker == "true" ) { 
            $map_show_marker = " var marker = new google.maps.Marker({
                        position: myLatlng,
                        map: map,
                        title: '',
                        icon: '".$map_marker_icon."',
                        shadow: ''
                    });
            ";
        }
        $border    = '';
        if (isset($map_border) && $map_border == 'yes' && $map_border_color !='') {
            $border    = 'border:1px solid '.$map_border_color.'; ';
        }
        if ( trim($cs_map_animation) !='' ) {
            $cs_map_animation    = 'wow'.' '.$cs_map_animation;
        } else {
            $cs_map_animation    = '';
        }
		
		$map_dynmaic_no = cs_generate_random_string('10');
        $html  = '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=true"></script>';
        $html .= '<div '.$CustomId.' class="'. $column_class . ' '. $cs_map_class . ' ' . $cs_map_animation . '" style="animation-duration:'.$cs_custom_animation_duration.'s;">';
        $html .= $section_title;
        $html .= '<div class="clear"></div>';
        $html .= '<div class="cs-map-section" style="'.$border.';">';
        $html .= '<div class="cs-map">';
		$html .= '<ul class="cs-map-tabs"><li class="cs-active"><span onclick="javascript:cs_toggle_street_view(\'mapview\');"><i class="icon-location6"></i> '.__('Map View','dir').'</span></li>';
		$html .= '<li><span onclick="javascript:cs_toggle_street_view(\'streetview\');"><i class="icon-user9"></i> '.__('Street View','dir').'</span></li>';
		if ( $cs_map_directions == 'on' ) {
			$html .= '<li><span onclick="javascript:cs_toggle_street_view(\'mapview\');"><i class="icon-shuffle3"></i> '.__('Directions','dir').'</span></li>';
		}
		$html .= '</ul>';
		$html .= '<div class="cs-map-content">';
		
        $html .= '<div class="mapcode iframe mapsection gmapwrapp" id="map_canvas'.$map_dynmaic_no.'" style="height:'.$map_height.'px;"> </div>';
        if ( $cs_map_directions == 'on' ) {
			$html .= '<div class="cs-dir-srch-box" id="cs-dir-srch-box">
						<input type="text" placeholder="'.__('Enter Location','dir').'" id="cs_end_direction" />
						<i class="icon-arrow-down8"></i>
						<ul id="cs_direction_mode" class="cs_direction_mode">
							<li class="cs-active" data-value="DRIVING">Driving</li>
							<li data-value="WALKING">Walking</li>
							<li data-value="BICYCLING">Bicycling</li>
							<li data-value="TRANSIT">Transit</li>
						</ul>
						<input type="hidden" id="cs_chng_dir_mode" value="DRIVING" />
						<label class="search-button">
							<input type="button" id="cs_search_direction" class="cs_search_direction" value="'.__('Get Directions','dir').'" />
						</label>
					  </div>';
		}
		if ( $cs_map_directions == 'on' ) {
			$html .= '<div id="cs-directions-panel"></div>';
		}
		$html .= '</div>';
		$html .= '</div>';
        $html .= "<script type='text/javascript'>
                    jQuery(window).load(function(){
                        setTimeout(function(){
                        jQuery('.cs-map-".$map_dynmaic_no."').animate({
                            'height':'".$map_height."'
                        },400)
                        },400)
                    })
					
					var panorama;
                    
					function initialize() {
                        var myLatlng = new google.maps.LatLng(".$map_lat.", ".$map_lon.");
                        var mapOptions = {
                            zoom: ".$map_zoom.",
                            scrollwheel: ".$map_scrollwheel.",
                            draggable: ".$map_draggable.",
							streetViewControl: false,
                            center: myLatlng,
                            mapTypeId: google.maps.MapTypeId.content,
                            disableDefaultUI: ".$map_controls.",
                        };";
						
						if ( $cs_map_directions == 'on' ) {
							$html .= "var directionsDisplay;
							var directionsService = new google.maps.DirectionsService();
							directionsDisplay = new google.maps.DirectionsRenderer();";
						}
						
						$html .= "var map = new google.maps.Map(document.getElementById('map_canvas".$map_dynmaic_no."'), mapOptions);";
						
						if ( $cs_map_directions == 'on' ) {
							$html .= "directionsDisplay.setMap(map);
							directionsDisplay.setPanel(document.getElementById('cs-directions-panel'));
							
							function cs_calc_route() {
								var myLatlng = new google.maps.LatLng(".$map_lat.", ".$map_lon.");
								var start = myLatlng;
								var end = document.getElementById('cs_end_direction').value;
								var mode = document.getElementById('cs_chng_dir_mode').value;
								var request = {
									origin:start,
									destination:end,
									travelMode: google.maps.TravelMode[mode]
								};
								directionsService.route(request, function(response, status) {
									if (status == google.maps.DirectionsStatus.OK) {
										directionsDisplay.setDirections(response);
									}
								});
							}
							
							document.getElementById('cs_search_direction').addEventListener('click', function() {
								cs_calc_route();
							});";
						}
						
						$html .= "
                        var styles = cs_map_select_style('".$cs_map_style."');
                        if(styles != ''){
                            var styledMap = new google.maps.StyledMapType(styles, {name: 'Styled Map'});
                            map.mapTypes.set('map_style', styledMap);
                            map.setMapTypeId('map_style');
                        }
                        var infowindow = new google.maps.InfoWindow({
                            content: '".$map_info."',
                            maxWidth: ".$map_info_width.",
                            maxHeight: ".$map_info_height.",
                            
                        });
                        ".$map_show_marker."
                        //google.maps.event.addListener(marker, 'click', function() {
                            if (infowindow.content != ''){
                              infowindow.open(map, marker);
                               map.panBy(1,-60);
                               google.maps.event.addListener(marker, 'click', function(event) {
                                infowindow.open(map, marker);
                               });
                            }
                        //});
						panorama = map.getStreetView();
						panorama.setPosition(myLatlng);
						panorama.setPov(({
						  heading: 265,
						  pitch: 0
						}));

                    }
					
				function cs_toggle_street_view(btn) {
				  var toggle = panorama.getVisible();
				  if (toggle == false) {
					if(btn == 'streetview'){
					  panorama.setVisible(true);
					}
				  } else {
					if(btn == 'mapview'){
					  panorama.setVisible(false);
					}
				  }
				}

                google.maps.event.addDomListener(window, 'load', initialize);
                </script>";
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
    add_shortcode('cs_map', 'cs_map_shortcode');
}

//======================================================================
// Adding Team start
//======================================================================
if (!function_exists('cs_team_shortcode')) {
    function cs_team_shortcode($atts, $content = "") {
        $defaults = array( 'column_size'=>'1/1','cs_size'=>'','cs_image_position' => '','cs_text_align' => '','cs_team_website' => '','cs_attached_media' => '','cs_team_title' => '','cs_team_designation' => '','cs_team_about' => '','cs_team_fb' => '','cs_team_tw' => '','cs_team_gm' => '','cs_team_yt' => '','cs_team_sky' => '','cs_team_fs' => '','cs_button_target' => '','cs_team_class' => '','cs_team_animation' => '','cs_custom_animation_duration'=>'1');
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
        
        $CustomId    = '';
        if ( isset( $cs_team_class ) && $cs_team_class ) {
            $CustomId    = 'id="'.$cs_team_class.'"';
        }
        
        $html = '';
        if ( trim($cs_team_animation) !='' ) {
            $cs_team_animation    = 'wow'.' '.$cs_team_animation;
        } else {
            $cs_team_animation    = '';
        }
        
        $html .='<div '.$CustomId.' class="cs-team '. $cs_team_class . ' ' . $cs_team_animation . '" style="animation-duration:'.$cs_custom_animation_duration.'s;">';
        $html .='<div class="row">';
        $html .='<div class="col-md-12">';
        $html .='<article class="'. $cs_image_position . ' '. $cs_size . ' '. $cs_text_align . '">';
        
        if (isset($cs_attached_media) && $cs_attached_media !=''){
            $html .='<figure><a href="'.$cs_team_website.'"><img alt="'.$cs_team_title.'" src="'. $cs_attached_media .'"></a></figure>';
        }
        
        if ($cs_team_title || $cs_team_designation || $content || $cs_team_fb || $cs_team_tw || $cs_team_gm || $cs_team_yt || $cs_team_sky || $cs_team_fs ) { 
            
            $html .='<div class="text">';
            if ($cs_team_title || $cs_team_designation ) { 
                $html .='<header>';
                
                if (isset($cs_team_title) && $cs_team_title !=''){
                    $html .='<h2 class="cs-post-title"><a href="'.$cs_team_website.'">'.$cs_team_title.'</a></h2>';
                }
                if (isset($cs_team_designation) && $cs_team_designation !=''){
                    $html .='<span>'.$cs_team_designation.'</span>';
                }
                $html .='</header>';
            }
            if (isset($content) && $content !=''){
                $html .='<p>'.do_shortcode($content).'</p>';
            }
            $html .='<p class="social-media">';
            
            if (isset($cs_team_fb) && $cs_team_fb !=''){
                $html .='<a href="'.$cs_team_fb.'" target="'.$cs_button_target.'"><i class="icon-facebook"></i></a>';
            }
            if (isset($cs_team_tw) && $cs_team_tw !=''){
                $html .='<a href="'.$cs_team_tw.'" target="'.$cs_button_target.'"><i class="icon-twitter"></i></a>';
            }
            if (isset($cs_team_gm) && $cs_team_gm !=''){
                $html .='<a href="'.$cs_team_gm.'"  target="'.$cs_button_target.'"><i class="icon-google-plus"></i></a>';
            }
            if (isset($cs_team_yt) && $cs_team_yt !=''){
                $html .='<a href="'.$cs_team_yt.'" target="'.$cs_button_target.'"><i class="icon-youtube"></i></a>';
            }
            if (isset($cs_team_sky) && $cs_team_sky !=''){
                $html .='<a href="'.$cs_team_sky.'" target="'.$cs_button_target.'"><i class="icon-skype"></i></a>';
            }
            if (isset($cs_team_fs) && $cs_team_fs !=''){
                $html .='<a href="'.$cs_team_fs.'" target="'.$cs_button_target.'"><i class="icon-foursquare"></i></a>';
            }
            $html .='</p>';
            $html .='</div>';
        }
        $html .='</article>';
        $html .='</div>';
        $html .='</div>';
        $html .='</div>';
        
        return $html;
    }
    add_shortcode('cs_teamss', 'cs_team_shortcode');
}

//======================================================================
// Adding Offer Slider start
//======================================================================
if (!function_exists('cs_offerslider_shortcode')) {
    function cs_offerslider_shortcode($atts, $content = "") {
        $defaults = array('column_size'=>'1/1','cs_offerslider_section_title' => '','cs_offerslider_class' => '','cs_offerslider_animation' => '');
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
        
        $CustomId    = '';
        if ( isset( $cs_offerslider_class ) && $cs_offerslider_class ) {
            $CustomId    = 'id="'.$cs_offerslider_class.'"';
        }
        
        if ( trim($cs_offerslider_animation) !='' ) {
            $cs_offerslider_animation    = 'wow'.' '.$cs_offerslider_animation;
        } else {
            $cs_offerslider_animation    = '';
        }

        $html = '';
        $section_title    = '';
        if ($cs_offerslider_section_title && trim($cs_offerslider_section_title) !='' ) {
            $section_title    = '<div class="cs-section-title"><h2 class="'.$cs_offerslider_animation.'">'.$cs_offerslider_section_title.'</h2></div>';
        }
        $randomid = cs_generate_random_string('10');
        cs_owl_carousel();
        ?>
        <script>
        jQuery(document).ready(function($) {
            jQuery('#postslider<?php echo esc_js($randomid);?>').owlCarousel({
                    loop:true,
                    nav:false,
                    autoplay:true,
                    margin: 15,
                    navText: [
                           "",
                           ""
                          ],
                    responsive:{
                        0:{
                            items:1
                        },
                        600:{
                            items:1
                        },
                        1000:{
                            items:1
                        }
                    }
            });
        });
        </script>
        <?php
        $html    .= '<div '.$CustomId.' class="col-md-12">';
        $html    .= $section_title;
        $html    .= '<div class="row">';
        $html    .= '<div id="postslider'.$randomid.'" class="owl-carousel has_bgicon cs-offers-slider">';
        $html    .= do_shortcode( $content );
        $html    .= '</div>';
        $html    .= '</div>';
        $html    .= '</div>';
        
        return $html;
    }
    add_shortcode('cs_offerslider', 'cs_offerslider_shortcode');
}

//======================================================================
// Offer Slider item
//======================================================================
if (!function_exists('cs_offerslider_item')) {
    function cs_offerslider_item( $atts, $content = null ) {
        $defaults = array( 'cs_slider_image' => '','cs_slider_title' => '','cs_slider_contents' => '','cs_readmore_link' => '','cs_offerslider_link_title' => '');
        extract( shortcode_atts( $defaults, $atts ) );
        $html      = '';

        $html    .='<div class="item">';
        
        if ( $cs_slider_image ) {
            $html    .='<div class="col-md-7">';
            $html    .='<figure>';
            if ( $cs_readmore_link ) {
                $html    .='<a href="'.$cs_readmore_link.'">';
            }
            $html    .='<img  src="'.$cs_slider_image.'" alt="">';
            if ( $cs_readmore_link ) {
                $html    .='</a>';
            }
            $html    .='</figure>';
            $html    .='</div>';
        }
        
        $html    .='<div class="col-md-5">';
        $html    .='<div class="cs-contact-info no_border">';
        if ( $cs_slider_title ) {
            $html    .='<h1>'.$cs_slider_title.'</h1>';
        }
        if ( $content ) {
            $html    .='<p>'.do_shortcode( $content ).'</p>';
        }
        if ( $cs_readmore_link ) {
            $link_title    = $cs_offerslider_link_title ? $cs_offerslider_link_title : 'Get Directions';
            $html    .='<a href="'.$cs_readmore_link.'"><button class="custom-btn cs-bg-color">'.$link_title.'</button</a>';
        }
        $html    .='</div>';
        $html    .='</div>';
        $html    .='</div>';
        
        return $html;
        
    }
    add_shortcode( 'offer_item', 'cs_offerslider_item' );
}

//======================================================================
// Adding Info Box item start
//======================================================================
if (!function_exists('cs_spacer_shortcode')) {
    function cs_spacer_shortcode($atts, $content = "") {
        global $cs_border;
        $defaults = array('cs_spacer_height'=>'25');
        extract( shortcode_atts( $defaults, $atts ) );
        
        $cs_spacer_height    = $cs_spacer_height? $cs_spacer_height : '15';
        
        return '<div class="col-md-12" style="height:'.$cs_spacer_height.'px"></div>';
    }
    add_shortcode('cs_spacer', 'cs_spacer_shortcode');
}
?>