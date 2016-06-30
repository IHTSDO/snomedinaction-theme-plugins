<?php

//=====================================================================
// Directory filtering methods
//=====================================================================
function cs_get_directory_filters( $directory_cat = '', $directory_type = '', $atts = '' , $directory_view = '',$fields='4',$areaType='section',$cs_seach_page=''){
	 global $post,$cs_theme_options,$wpdb,$cs_elem_id;

	  $directory_id	= $cs_directory_search_result_page ='';
	  
	  echo cs_allow_special_char($areaType) == 'section' ?  '<div class="section-sidebar">' : '';
	  
	  $cs_directory_search_location    		  = isset($cs_theme_options['cs_directory_search_location']) ? $cs_theme_options['cs_directory_search_location'] : 'NO';
	  $distance_km_miles                      = isset($cs_theme_options['distance_km_miles']) ? $cs_theme_options['distance_km_miles'] : 'Miles';
	  $cs_location_type						  = isset( $cs_theme_options['cs_search_by_location'] ) ?  $cs_theme_options['cs_search_by_location'] : '';
	  
	  if( isset( $_GET['location'] ) ){
		  $search_location = $_GET['location'];
	  }
	  
	  if( isset( $_GET['type' ]) && $_GET['type'] <> '' ){
		  
		  $directory_type = $_GET['type'];
		  $directory_type_array = explode('||', $directory_type);
		  if(is_array($directory_type_array) && isset($directory_type_array['0']))
			  $directory_type = $directory_type_array['0'];
		  if(is_array($directory_type_array) && isset($directory_type_array['1']))
			  $directory_type_slug = (string)$directory_type_array['1'];
		  if(isset($directory_type) && $directory_type <> ''){
			  $args=array(
				  'name' => (string)$directory_type,
				  'post_type' => 'directory_types',
				  'post_status' => 'publish',
				  'posts_per_page' => 1
			  );
			  $dir_posts = get_posts( $args );
			  if( $dir_posts ) {
				  $directory_id = $dir_posts[0]->ID;
			  }
		  }
	  } else {
		  
		  if ( isset( $areaType )  && $areaType == 'widget' ) {
		  	if ( isset( $_SESSION[$post->ID.'_node_'.$cs_elem_id.'_data'] ) ) {
				$cs_sessionData	= $_SESSION[$post->ID.'_node_'.$cs_elem_id.'_data']; 
				$directory_id   = (int)$cs_sessionData['post_directory_type'];
			}
		  } else {
		  	$directory_id = (int)$directory_type;
		  }

		  $directory_type = cs_get_the_slug( $directory_id );
	  }

	  if ( isset( $areaType ) && $areaType == 'widget' && $cs_seach_page !='' ) {
		  $action_page = get_permalink((int)$cs_seach_page);
	  } else {
		  if(isset( $cs_theme_options['select_search_result_page'] ) && $cs_theme_options['select_search_result_page'] <> ''){
			  $action_page = get_permalink((int)$cs_theme_options['select_search_result_page']);
		  }
		  elseif(isset($_GET['page_id']) && $_GET['page_id'] <> ''){
			  		

			  	$cs_directory_search_result_page = $_GET['page_id'];
          		$action_page = get_permalink((int)$cs_directory_search_result_page);
		  }else{
			  $action_page = get_the_permalink($post->ID);
		  }
	  }
	  
 	  $currency_sign	= isset($cs_theme_options['currency_sign']) ? $cs_theme_options['currency_sign'] : '$';	
	  
	  $search_val = '';
	  $cs_loc_incr_step = 1;
	  
	  if( isset($cs_theme_options['cs_loc_max_input'])  && trim($cs_theme_options['cs_loc_max_input']) !=''  && trim($cs_theme_options['cs_loc_max_input']) != 0  ) {
			$cs_loc_max_input = $cs_theme_options['cs_loc_max_input'];
		} else {
			$cs_loc_max_input = 150;
	  }
	  
	  if( isset( $distance_km_miles ) && $distance_km_miles == 'Miles' ) {
			 $cs_loc_max_input_slider	= '300';
	  } else{
			 $cs_loc_max_input_slider	= '500';
	  }
		
	  
	  if(isset($_GET['cs_loc_max_input']) && $_GET['cs_loc_max_input'] <> '')
		  $cs_loc_max_input = $_GET['cs_loc_max_input'];
	  if(isset($_GET['cs_loc_incr_step']) && $_GET['cs_loc_incr_step'] <> '')
		  $cs_loc_incr_step = $_GET['cs_loc_incr_step'];
	  if(isset($_GET['cs_directory_search_filter']))
		  $cs_directory_search_filter = $_GET['filter'];
	  else 
		  $cs_directory_search_filter = 'all';
		  
	  wp_directory::cs_multipleselect_scripts();
	  wp_directory::cs_autocomplete_scripts();
	  
	  $rand_id = rand(93, 369335);
	  
	  $search_text	= '';
	  if ( isset( $_GET['search_text'] ) ) {
			$search_text	= $_GET['search_text'];
	  }
		
	  ?>
	  <script>
		function cs_somo_multiselect(cs_randid){
			window.asd = $('.ad_cat_multislect'+cs_randid+'').SumoSelect({ okCancelInMulti:true });
		}
		jQuery(document).ready(function($) {
			window.asd = jQuery('select.form-select').SumoSelect();
		});
	  </script>
	  <div id="directory-advanced-search_<?php echo absint($rand_id); ?>">         
        <form id="directory-advance-search-form" method="get" role="search" action="<?php echo esc_url($action_page);?>" enctype="multipart/form-data">
         	<?php if(isset($cs_directory_search_result_page) and $cs_directory_search_result_page <> ''){?>
         		<input type="hidden" name="page_id" value="<?php echo $cs_directory_search_result_page;?>" />
            <?php } ?>
            <input type="hidden" name="filter" value="<?php echo esc_attr($cs_directory_search_filter);?>" />
            <div class="dr-filters directory-advanced-search-content sidebar-search newone">
                <ul>
                      <?php if( isset( $cs_theme_options['cs_search_text'] ) && $cs_theme_options['cs_search_text'] == 'on' ) {?>
                     <li>
                       <input type="text" class="form-search-text" maxlength="128" size="30" value="<?php echo esc_attr( $search_text );?>" name="search_text" id="edit-search-api-views-fulltext" onkeydown="if (event.keyCode == 13) document.getElementById('directory-submit-search-view').click()" placeholder="<?php _e('Enter keyword...', 'directory');?>">
                     </li>
                     <?php }?>
					<?php
					// Directory Type
					/*$args = array(
						'posts_per_page'			=> "-1",
						'post_type'					=> 'directory_types',
						'post_status'				=> 'publish',
						'orderby'					=> 'ID',
						'order'						=> 'ASC',
					); */
					
					$args = array(
								'type'                     => 'post',
								'child_of'                 => 0,
								'parent'                   => '',
								'orderby'                  => 'name',
								'order'                    => 'ASC',
								'hide_empty'               => 1,
								'hierarchical'             => 1,
								'exclude'                  => '',
								'include'                  => '',
								'number'                   => '',
								'taxonomy'                 => 'directory-category',
								'pad_counts'               => false 
							
							); 
                $categories = get_categories($args); 
				
					$custom_query = new WP_Query($args);
					if ( $categories ) {
						?>
						<li>
							<span class="cat-loading-fields" id="cat-loading-fields"></span>
							
							<div class="distance-in-miles">
								<select class="form-select dir-map-search" name="directory_categories" id="directory-field-category">
								 <option value=""><?php _e('All Categories','directory');?></option>
								<?php
									 foreach ( $categories as $category ): ;
										 $selected = '';
										 if( isset( $_GET['directory_categories'] ) && $_GET['directory_categories'] == $category->slug){
											$selected = 'selected'; 
										 }
										 echo '<option value="'.$category->slug.'" '.$selected.'>'.$category->name.'</option>';
									 endforeach;
								?>
								</select>
								<script type="text/javascript">
									jQuery(document).ready(function ($) {
									   window.asd = jQuery('.ad_cat_multislect<?php echo absint($rand_id);?>').SumoSelect({ okCancelInMulti:false });
									});
								</script>
							</div>
                            <?php if(isset($cs_directory_search_location) && $cs_directory_search_location == 'Yes'){?>
							<div class="distance-in-miles">
								<?php   // Locations			
									$cs_directory_location_suggestions = $cs_theme_options['cs_directory_location_suggestions'];
									if( isset( $cs_directory_location_suggestions ) && $cs_directory_location_suggestions == 'Google' ) {
										$geo_location = 'on'; 
										wp_directory::cs_autocomplete_scripts();
									}else {
										$geo_location = 'off';
									}
								
								if(isset($cs_directory_location_suggestions) && $cs_directory_location_suggestions == 'Google'){
								?>
								<input type="search" value="<?php if(isset($search_location)) echo urldecode($search_location);?>" autocomplete="on" id="directory-search-location" class="selectpicker show-tick form-control" title="<?php _e('Location', 'directory');?>" onkeydown="if (event.keyCode == 13) document.getElementById('directory-submit-search-view').click()" placeholder="Postcode or location" name="location">
									<?php
									if(isset($goe_location_enable) && $goe_location_enable == true){
									?>
									<div class="sidelocation"><i class="icon-location6" onclick="getLocation()"></i></div>
									<?php
									}
									?>
									<script>
									(function( $ ) {
										$(function() {
											<?php 
												if(isset($cs_directory_location_suggestions)){
													wp_directory::cs_google_place_scripts();
													?>
													var autocomplete;
													autocomplete = new google.maps.places.Autocomplete(document.getElementById('directory-search-location'));
													<?php
												} else {
													echo 'var url = "'.admin_url('admin-ajax.php?action=cs_location_search').'";';
													?>
													$( "#directory-search-location" ).autocomplete({
														source: url,
														delay: 100,
														minLength: 2
													});	
													<?php
												}
											?>
										});
									})( jQuery );
									</script>
								<?php
								} else {
									cs_get_custom_locations('','no');
								}
								?>
								<input id="geo_loc_option" name="geo" type="hidden" value="<?php echo esc_attr($geo_location);?>">
								<div id="geo_location_address">
								<?php 
									if($geo_location == 'on'){
										if(isset($_GET['geo_location_lat']) && !empty($_GET['geo_location_lat'])) $geo_location_lat = $_GET['geo_location_lat']; else $geo_location_lat = '';
										if(isset($_GET['geo_location_long']) && !empty($_GET['geo_location_long'])) $geo_location_long = $_GET['geo_location_long']; else $geo_location_long = '';
										echo "<input type='hidden' name='geo_location_lat' value='" .$geo_location_lat. "'>
											  <input type='hidden' name='geo_location_long' value='" .$geo_location_long. "' >";
									}
								?>
								</div>
							</div>
							<?php if( isset( $cs_theme_options['cs_search_radius'] ) && $cs_theme_options['cs_search_radius'] == 'on' ) {
								if ( $cs_location_type != 'single_city' ) {?>    
                            <div class="distance-in-miles">
							  	<?php
								$radius	= cs_get_radius();
								echo '
									<div class="input-sec">
										<small>Radius</small>
										<div class="cs-drag-slider" data-slider-min="0" data-slider-max="'.$cs_loc_max_input_slider.'" data-slider-step="'.$cs_loc_incr_step.'" data-slider-value="'.$radius.'"></div>
										<input id="sidebar-location-slider" class="cs-range-input" name="radius" type="text" value="'.$radius.'"   />
									</div>' . "\n";
								?>
								<script>
									jQuery(document).ready(function($) {
										jQuery("#cs-drag-slider span").first().html("<strong>"+jQuery( "#sidebar-location-slider" ).val()+" <?php echo esc_attr( $distance_km_miles );?></strong>");
										jQuery('div.cs-drag-slider').each(function() {
											tooltip = jQuery(this).parents('div.input-sec').find('span.ui-slider-handle');
											tooltip_val = jQuery(this).parents('div.input-sec').find('input.cs-range-input').val();
											tooltip.html("<strong>"+tooltip_val+" <?php echo esc_attr( $distance_km_miles );?></strong>");
											 var _this = jQuery(this);
											_this.slider({
												range:'min',
												step: _this.data('slider-step'),
												min: _this.data('slider-min'),
												max: _this.data('slider-max'),
												value: _this.data('slider-value'),
												slide: function (event, ui) {
													jQuery(this).parents('div.input-sec').find('.cs-range-input').val(ui.value)                     
													tooltip = jQuery(this).parents('div.input-sec').find('span.ui-slider-handle');
													tooltip.html("<strong>"+ui.value+" <?php echo esc_attr( $distance_km_miles );?></strong>");
													
												}
											});
										});
										var value = jQuery( "#sidebar-location-slider" ).val();
										jQuery("div.cs-drag-slider span").first().html("<strong>"+value+" <?php echo esc_attr( $distance_km_miles );?></strong>");
									});
									
								</script>
							</div>
                           <?php }
                               }?>
                        <?php }?>
                        </li> 
                       <?php if( isset( $cs_theme_options['cs_search_categories'] ) && $cs_theme_options['cs_search_categories'] == 'on' ) {?>    
                        <li class="categories-load">
                            <div class="directory-type-categories-load">
                                <?php if ( isset( $directory_id ) && $directory_id !='' ) {?>
                                <label><?php _e('Categories','directory');?></label>  
                                <ul class="cs-checkbox checked-category">
                                    <?php
                                        $args = array(
											'type'                     => 'post',
											'child_of'                 => 0,
											'parent'                   => '',
											'orderby'                  => 'name',
											'order'                    => 'ASC',
											'hide_empty'               => 1,
											'hierarchical'             => 1,
											'exclude'                  => '',
											'include'                  => '',
											'number'                   => '',
											'taxonomy'                 => 'directory-category',
											'pad_counts'               => false 
										
										); 
                                         $categories = get_categories($args);
                                     
                                         if($categories){
                                             if(isset($_GET['directory_categories'])){
                                                // $directory_type_slug = $_GET['directory_categories'];
                                                 $directory_type_slug	= cs_get_query_values( 'directory_categories' );
                                             } else {
                                                $directory_type_slug = array();
                                                $directory_type_slug[] = $directory_type_slug; 
                                             }
                                             wp_directory::cs_multipleselect_scripts();
                                             $directory_categories_array = get_post_meta((int)$directory_id, "directory_types_categories", true);
                                             $directory_categories_array = explode(',', $directory_categories_array);
                                             foreach ($categories as $category) {
                                                $checked = '';
                                                if(in_array($category->slug, $directory_categories_array)){
                                                    
                                                    if(isset($directory_type_slug) && in_array($category->slug, $directory_type_slug)){
                                                        $checked = 'checked="checked"';
                                                    }
                                                    echo '<li>
                                                               <input '.$checked.' type="checkbox" id="category_'.$category->slug.'"  class="directory-categories-checkbox" name="directory_categories"   value="'.$category->slug.'">
                                                               <label for="category_'.$category->slug.'">' . $category->name . '</label>
                                                              <span class="totalpost">' . $category->category_count . '</span>
                                                          </li>';
                                                }
                                             }
                                         }
                                    ?>
                                 </ul>
                                <?php } ?>
                            </div>
                        </li>  
                       <?php }?>
					<?php 
					}

					//if( isset( $cs_theme_options['cs_search_price'] ) && $cs_theme_options['cs_search_price'] == 'on' ) {   
                        if(isset($directory_id) && $directory_id <> ''){
                            $saleprice_option = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_option', true);
                            $price_enable_search = get_post_meta((int)$directory_id, 'cs_post_price_enable_search', true);
                            if(isset($saleprice_option) && $saleprice_option == 'on' && isset($price_enable_search) && $price_enable_search == 'on'){
                                $price_fields = '';
                                $price_max_range_start = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_max_input', true);
                                $price_min_range_start = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_min_input', true);
                                $cs_post_price_style = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_style', true);
                            
                                if($price_min_range_start == '')
                                    $price_min_range_start = 0;
                                if($price_max_range_start == '')
                                    $price_max_range_start = 1000;
                                
                                $price_min_key = 'min_price';
                                $price_max_key = 'max_price';
                                if(isset($_GET[$price_min_key])){
                                    $price_min_range = $_GET[$price_min_key];
                                } else {
                                    $price_min_range = 1;
                                    $price_min_range = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_min_input', true);
                                }
                                if(isset($_GET[$price_max_key])){
                                    $price_max_range = $_GET[$price_max_key];
                                } else {
                                    $price_max_range = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_max_input', true);
                                }
                                $price_incrstep_input = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_incr_input', true);
                                if(isset($price_max_range) && $price_max_range == '')
                                    $price_max_range = '';
                                if(isset($price_min_range) && $price_min_range == '')
                                    $price_min_range = '';
                                if(isset( $price_incrstep_input ) && $price_incrstep_input == '')
                                    $price_incrstep_input = 1;
                                
                                $saleprice_label = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_input', true);
                                echo '<li class="price_fields">';
                                $price_fields .= '<div class="advance-search-price-range">';
                                $html = '';
                                $output = '';
                                if(isset($cs_post_price_style) && $cs_post_price_style == 'Inputs'){
                                        $output .= '<label>'.$saleprice_label.'</label><ul><li>
                                                            <input  id="min_price" name="min_price" type="text" value="'.$price_min_range.'" placeholder="Min '.$saleprice_label.'" >
                                                    </li>';
                                        $output .= '<li>
                                                            <input  id="max_price" name="max_price" type="text" value="'.$price_max_range.'" placeholder="Max '.$saleprice_label.'" >
                                                    </li></ul>';
                                            $html 			.= $output;
                                            $price_fields   .= $html;
                                } 
                                else if(isset($cs_post_price_style) && $cs_post_price_style == 'Slider_Inputs'){
                                        if($price_min_range == '')
                                            $price_min_range = 1;
                                        if($price_max_range == '')
                                            $price_max_range = 1000;
                                        
                                        $slidder_inputs = '';	
                                        $slidder_inputs .= '<ul>
                                                        <li><input id="min_price" name="min_price" type="text" value="'.$price_min_range.'" placeholder=" Min '.$saleprice_label.'" ></li>
                                                        <li><input  id="max_price" name="max_price" type="text" value="'.$price_max_range.'" placeholder=" Max '.$saleprice_label.'" ></li>
                                                        </ul>
                                                    ';
                                    $output .= '<label>'.$saleprice_label.'</label>
                                                    <div class="input-sec">
                                                        <div id="slider-price-range"></div>
                                                    </div>
                                                    '.$slidder_inputs.'
                                                 <script>
                                                    jQuery(function() {
                                                        jQuery( "#slider-price-range" ).slider({
                                                           
                                                            range: true,
                                                            min: '.$price_min_range_start.',
                                                            max: '.$price_max_range_start.',
                                                            step: '.$price_incrstep_input.',
                                                            values: [ '.$price_min_range.', '.$price_max_range.' ],
                                                            slide: function( event, ui ) {
                                                                jQuery( "#min_price" ).val(ui.values[ 0 ]);
                                                                jQuery( "#max_price" ).val(ui.values[ 1 ]);
                                                                jQuery("#slider-price-range span").first().html("<strong>'.$currency_sign.' "+ui.values[ 0 ]+"</strong>");
                                                                jQuery("#slider-price-range span").eq(1).html("<strong>'.$currency_sign.' "+ui.values[ 1 ]+"</strong>");
																
																var delay = function() {
																	var handleIndex = $(ui.handle).data("index.uiSliderHandle");
																};
                                                            }
                                                        });
                                                        
														jQuery( "#min_price" ).val(jQuery( "#slider-price-range" ).slider( "values", 0 ));
                                                        jQuery( "#max_price" ).val(jQuery( "#slider-price-range" ).slider( "values", 1 ));
                                                        jQuery("#slider-price-range span").first().html("<strong>'.$currency_sign.' "+jQuery( "#min_price" ).val()+"</strong>");
                                                        jQuery("#slider-price-range span").eq(1).html("<strong>'.$currency_sign.' "+jQuery( "#max_price" ).val()+"</strong>");
                                                    });
                                        </script>
                                        ';
                                    $html .= $output;
                                    $price_fields .= $html;
                                } 
                                else if(isset($cs_post_price_style) && $cs_post_price_style == 'Slider'){
                                    if(isset($price_max_range) && $price_max_range == '')
                                        $price_max_range = 1000;
                                    if(isset($price_min_range) && $price_min_range == '')
                                        $price_min_range = 1;
                                
                                    $price_fields .= '
                                    <label>'.$saleprice_label.'</label>
                                    <input id="min_price" name="min_price" type="hidden" value="'.$price_min_range.'">
                                    <input  id="max_price" name="max_price" type="hidden" value="'.$price_max_range.'">
                                    <div class="input-sec">
                                        <div id="slider-price-range"></div>
                                    </div>
                                     <script>
                                        jQuery(function() {
                                            jQuery( "#slider-price-range" ).slider({
                                                orientation: "horizontal",
                                                range: true,
                                                min: '.$price_min_range_start.',
                                                max: '.$price_max_range_start.',
                                                step: '.$price_incrstep_input.',
                                                values: [ '.$price_min_range.', '.$price_max_range.' ],
                                                slide: function( event, ui ) {
                                                    jQuery( "#min_price" ).val(ui.values[ 0 ]);
                                                    jQuery( "#max_price" ).val(ui.values[ 1 ]);
                                                    jQuery("#slider-price-range span").first().html("<strong>'.$currency_sign.' "+ui.values[ 0 ]+"</strong>");
                                                    jQuery("#slider-price-range span").eq(1).html("<strong>'.$currency_sign.' "+ui.values[ 1 ]+"</strong>");
                                                }
                                            });
                                            jQuery( "#min_price" ).val(jQuery( "#slider-price-range" ).slider( "values", 0 ));
                                            jQuery( "#max_price" ).val(jQuery( "#slider-price-range" ).slider( "values", 1 ));
                                            jQuery("#slider-price-range span").first().html("<strong>'.$currency_sign.' "+jQuery( "#min_price" ).val()+"</strong>");
                                            jQuery("#slider-price-range span").eq(1).html("<strong>'.$currency_sign.' "+jQuery( "#max_price" ).val()+"</strong>");
                                        });
                                    </script>';
                                }
                                $price_fields .= '</div>';
                                echo balanceTags($price_fields, false);
                                echo '</li>';
                            }
                        } else {
                            echo '<li class="price_fields" style="display:none"></li>';
                        }
					//}
				   
					// Custom Fields rendering
				   
					if(isset($directory_id) && $directory_id <> ''){
						$custom_fields = '';
						$cs_dcpt_custom_fields = get_post_meta($directory_id, "cs_directory_custom_fields", true);
						if ( $cs_dcpt_custom_fields <> "" ) {
							echo '<li class="advance-search-custom-fields-sidebar">';
							echo '<ul class="dr_userinfo">';
							$cs_customfields_object = new SimpleXMLElement($cs_dcpt_custom_fields);
							if(isset($cs_customfields_object->custom_fields_elements) && $cs_customfields_object->custom_fields_elements == '1'){
								if(count($cs_customfields_object)>1){
									global $cs_node;
									foreach ( $cs_customfields_object->children() as $cs_node ){
										if(isset($cs_node->cs_customfield_enable_search) && $cs_node->cs_customfield_enable_search == 'yes'){
											$custom_fields .= cs_custom_search_fields_render();
										}
									}
								}
							}
							echo balanceTags($custom_fields, false);
							echo '</ul>';
							echo '</li>';
							
						}
					} else{
						echo '<li class="advance-search-custom-fields-sidebar" style="display:none"></li>';
					}
					
				   
					$cs_directory_search_result_per_page = get_option('posts_per_page');	
					
					if ( isset ( $directory_view ) && $directory_view !='' ) {
						$directory_view	= $directory_view;
					} else {
						if( isset( $cs_theme_options['cs_default_ad_search_view'] ) ) $cs_directory_search_views = $cs_theme_options['cs_default_ad_search_view']; else $cs_directory_search_views = 'grid';
					}
					?>
                    <li>
                    <input type="hidden" name="pagination" value="<?php echo intval($cs_directory_search_result_per_page);?>" />
                    <input type="hidden" name="search_page" value="<?php echo esc_attr($cs_directory_search_result_per_page);?>" />
                    <input type="hidden" id="cs_loc_max_input" name="cs_loc_max_input" value="<?php echo absint($cs_loc_max_input);?>" />
                    <input type="hidden" id="cs_loc_incr_step" name="cs_loc_incr_step" value="<?php echo absint($cs_loc_incr_step);?>" />
                    <input type="hidden" id="goe_location_enable" name="goe_location_enable" value="<?php if(isset($_GET['goe_location_enable']) && $_GET['goe_location_enable'] <> ''){echo absint($_GET['goe_location_enable']);} else {echo 'No';}?>" />
                    <button type="submit" name="submit" id="directory-submit-search-view" class="cs-bgcolor sidebar-search-sbmt" ><?php _e('Filter','directory');?></button>
                    </li>
                </ul>                  
            </div>
        </form>
    </div>
    
    <?php
	echo cs_allow_special_char($areaType) == 'section' ?  '</div>' : '';
}

//=====================================================================
// Directory Sort methods
//=====================================================================
function cs_get_directory_top_filters( $listType = '' , $directory_view = '', $atts='' ){
	 global $post,$cs_theme_options,$cs_counter_node,$wpdb,$cs_elem_id,$cs_paged_id;
	
	 $defaults = array( 'directory_title' => '', 'directory_cat' => '','cs_directory_fields_count'=>'5', 'cs_directory_filter' => '', 'cs_featured_on_top' => 'Yes', 'cs_listing_sorting' => 'recent','cs_directory_header' => '','cs_directory_rev_slider' => '','cs_directory_map_style'=>'style-1','cs_directory_banner' => '','cs_directory_adsense' => '','cs_subheader_bg_color' => '','cs_subheader_padding_top' => '','cs_subheader_padding_bottom' => '','directory_view' => '','cs_switch_views' => '','directory_type' => '','directory_pagination'=>'','cs_directory_filterable' => '','cs_directory_sortable' => '','directory_per_page' => '');
	 
	 extract( shortcode_atts( $defaults, $atts ) );
		
	 if ( isset( $directory_view ) && $directory_view == 'listing'  ) {
		 $cs_SortTypeView	= 'cs_ajax_directory_listing';
	 } else if ( isset( $directory_view ) && $directory_view == 'grid'  ) {
	 	 $cs_SortTypeView	= 'cs_ajax_directory_grid';
	 } else if ( isset( $directory_view ) && $directory_view == 'grid-box'  ) {
	 	 $cs_SortTypeView	= 'cs_ajax_directory_grid_box';
	 } else if ( isset( $directory_view ) && $directory_view == 'grid-box-four-column'  ) {
	 	 $cs_SortTypeView	= 'cs_ajax_directory_grid_box_four_column';
	 } else {
		 if ( isset( $_SESSION[$post->ID.'_node_'.$cs_elem_id.'_data'] ) ) {
			$cs_sessionData	= $_SESSION[$post->ID.'_node_'.$cs_elem_id.'_data']; 
			$cs_SortTypeView	= $cs_sessionData['post_directory_view'];
			
			if ( $cs_SortTypeView == 'grid' ) {
				$cs_SortTypeView	= 'cs_ajax_directory_grid';
			} else if ( $cs_SortTypeView == 'grid-box' ) {
				$cs_SortTypeView	= 'cs_ajax_directory_grid_two';
			} else if ( $cs_SortTypeView == 'grid-box-four-column' ) {
				$cs_SortTypeView	= 'cs_ajax_directory_grid_box_four_coulmn';
			} elseif ( $cs_SortTypeView == 'listing' ) {
				$cs_SortTypeView	= 'cs_ajax_directory_listing';
			} elseif ( $cs_SortTypeView == 'map' ) {
				$cs_SortTypeView	= 'cs_ajax_map_view';
			} else{
				$cs_SortTypeView	= 'cs_ajax_directory_listing';
			}
		 } else{
	 		 $cs_SortTypeView	= 'cs_ajax_directory_listing';
		 }
	 }
	 
	 if ( isset( $directory_view ) && $directory_view != 'listing' && $directory_view != 'grid' & $directory_view != 'grid-box'  ) {
	 	$view		= true;
		$viewType	= explode('-',$directory_view);
		$viewType	= $viewType[0];
	 } else {
	 	$view		= false;
		$viewType   = '';
	 }
	
	 $cs_isFilters	= 'false';	
	 if ( isset( $_GET['submit'] ) ) {
		$cs_isFilters	= 'true';	
	 }
	
	 $cs_adminURL		= '';
	 $cs_adminURL	= esc_url(admin_url('admin-ajax.php'));
	 $cs_themeURL	= esc_js(get_template_directory_uri());
				
	 wp_directory::cs_googlemapcluster_scripts();
	 wp_directory::cs_prettyPhoto_scripts();
	 
	 if ( isset( $_SESSION[$post->ID.'_node_'.$cs_elem_id.'_data'] ) ) {
		$cs_sessionData	= $_SESSION[$post->ID.'_node_'.$cs_elem_id.'_data']; 
	 }
	 
	 if ( ( isset( $cs_directory_sortable ) && $cs_directory_sortable == 'Yes' ) || isset( $cs_sessionData['sortable'] )  && $cs_sessionData['sortable'] == 'Yes' || 1) { 		
	 $randId    = cs_generate_random_string(5);
         
	 ?>
		 <div class="col-md-12 main-filter" data-node="<?php echo esc_attr( $cs_elem_id );?>" data-form="<?php echo $randId;?>"  data-post="<?php echo $cs_paged_id;?>">
		   <nav class="wow filter-nav">
			
			 <!--Sorting Navigation-->
				<ul class="cs-filter-menu pull-left">
				  <li><span><?php _e('Sort By:','directory'); ?></span></li>
				  <li><a onclick='cs_sort_directory("<?php echo esc_attr( $cs_adminURL );?>","<?php echo esc_attr( $cs_themeURL );?>","recent",this)' href="javascript:void(0)"><?php _e('Most Recent','directory');?></a></li>
				  <!--<li><a onclick='cs_sort_directory("<?php echo esc_attr( $cs_adminURL );?>","<?php echo esc_attr( $cs_themeURL );?>","popular",this)' href="javascript:void(0)"><?php _e('Most Popular','directory');?></a></li>-->
				  
				  <li><a onclick='cs_sort_directory("<?php echo esc_attr( $cs_adminURL );?>","<?php echo esc_attr( $cs_themeURL );?>","alphabetical",this)' href="javascript:void(0)"><?php _e('Alphabetical','directory');?></a></li>
				 <!-- <li><a onclick='cs_sort_directory("<?php echo esc_attr( $cs_adminURL );?>","<?php echo esc_attr( $cs_themeURL );?>","high-price",this)' href="javascript:void(0)"><?php _e('Highest Price','directory');?></a></li>
				  <li><a onclick='cs_sort_directory("<?php echo esc_attr( $cs_adminURL );?>","<?php echo esc_attr( $cs_themeURL );?>","low-price",this)' href="javascript:void(0)"><?php _e('Lowest Price','directory');?></a></li> -->
                                  <!--<li>Count:<?php // echo $num; ?></li>-->
				</ul>
			   
		<?php
		if( isset( $listType ) && !empty( $listType ) ){

				//echo '<ul class="grid-filter"><li><span class="ajax-loading"></span></li>';
				$listType	=  array_filter( array_unique( $listType ) );
				
				foreach($listType as $list_key=>$list){
					// $count[]=$list_key;
					$activeClass	= '';
					$url			= '';
					$onclick		= '';
					$out 			 ='';
					$icon			= '<i class="icon-th-list"></i>';
					$listingView	= 'cs_ajax_directory_grid';
					
					if ( isset( $_SESSION[$post->ID.'_node_'.$cs_elem_id.'_data'] ) ) {
						$cs_sessionData	= $_SESSION[$post->ID.'_node_'.$cs_elem_id.'_data']; 
						$view	= isset( $cs_sessionData['post_directory_view'] ) ? $cs_sessionData['post_directory_view'] : 'listing' ;
						$cs_fixed_view	= true;
					}
					
					if ( isset( $view ) && $view =='listing' && $list == 'list' ) {
						$activeClass	= 'class="active"';
					} else if ( isset( $view ) && $view =='grid' && $list == 'grid' ) {
						$activeClass	= 'class="active"';
					} else if ( isset( $view ) && $view =='grid-box' && $list == 'grid-box' ) {
						$activeClass	= 'class="active"';
					} else if ( isset( $view ) && $view =='grid-box-four-column' && $list == 'grid-box-four-column' ) {
						$activeClass	= 'class="active"';
					} else if ( isset( $view ) && $view =='map' && $list == 'map' ) {
						$activeClass	= 'class="active"';
					} 
	
					if ( isset( $list ) && $list == 'list' ){
						$viewUrl	= $view ?  $viewType.'-listing' : 'listing'; 
						$url		= '?view='.$viewUrl.'';
						$listingView	= 'cs_ajax_directory_listing';
						$icon		= '<i class="icon-th-list"></i>';
					} else if ( isset( $list ) && $list == 'grid' ){
						$viewUrl	= $view ?  $viewType.'-grid' : 'grid'; 
						$url		= '?view='.$viewUrl.'';
						$listingView	= 'cs_ajax_directory_grid';
						$icon		= '<i class="icon-grid4"></i>';
					} else if ( isset( $list ) && $list == 'grid-box' ){
						$viewUrl	= $view ?  $viewType.'-grid-box' : 'grid-box'; 
						$url		= '?view='.$viewUrl.'';
						$listingView	= 'cs_ajax_directory_grid_two';
						$icon		= '<i class="icon-grid-alt"></i>';
					} else if ( isset( $list ) && $list == 'grid-box-four-column' ){
						$viewUrl	= $view ?  $viewType.'-grid-box-four-column' : 'grid-box-four-column'; 
						$url		= '?view='.$viewUrl.'';
						$listingView	= 'cs_ajax_directory_grid_box_four_column';
						$icon		= '<i class="icon-th"></i>';
					} else if ( isset( $list ) && $list == 'map' ){
						$icon	 		= '<i class="icon-map2"></i>';
						$listingView	= 'cs_ajax_map_view';
					}	
					
					
					$directory_pagination	= str_replace( ' ','',$directory_pagination );
				
				//echo '<li '.$activeClass.'><a onclick="cs_switch_view(\''.$cs_adminURL.'\',\''.$listingView.'\',\''.$cs_isFilters.'\',this)" href="javascript:void(0)">'.$icon.'</a></li>';
				}
//echo the results total amount

			}
  /*
  $custom_query = new WP_Query($args);
  echo $custom_query->found_posts.'posts';*/
  


		// echo $count_post;
		?>
		<form id="directory-filters-form" action="#" >
			<input type="hidden" value="<?php echo esc_attr( $directory_title );?>" name="directory_title" id="directory_title">
			<input type="hidden" value="<?php echo intval( $post->ID );?>" name="postID" id="postID">  
			<input type="hidden" value="<?php echo $cs_isFilters;?>" name="filters" id="filters">  
			<input type="hidden" value="<?php echo esc_attr( $cs_directory_fields_count );?>" name="cs_directory_fields_count" id="fields_limit">
			<input type="hidden" value="<?php echo esc_attr( $directory_cat );?>" name="directory_cat" id="directory_cat"> 
			<input type="hidden" value="<?php echo esc_attr( $cs_directory_filter );?>" name="cs_directory_filter" id="cs_directory_filter">
            <input type="hidden" value="<?php echo esc_attr( $cs_featured_on_top );?>" name="cs_featured_on_top" id="cs_featured_on_top">
            <input type="hidden" value="<?php echo esc_attr( $cs_listing_sorting );?>" name="cs_listing_sorting" id="cs_listing_sorting"> 
			<input type="hidden" value="<?php echo esc_attr( $directory_view );?>" name="directory_view" id="directory_view"> 
			<input type="hidden" value="<?php echo esc_attr( $cs_switch_views );?>" name="cs_switch_views" id="cs_switch_views"> 
			<input type="hidden" value="<?php echo esc_attr( $directory_type );?>" name="type" id="directory_type"> 
			<input type="hidden" value="<?php echo esc_attr( $directory_pagination );?>" name="directory_pagination" id="directory_pagination"> 
			<input type="hidden" value="<?php echo esc_attr( $cs_directory_filterable );?>" name="cs_directory_filterable" id="cs_directory_filterable"> 
			<input type="hidden" value="<?php echo esc_attr( $directory_per_page );?>" name="directory_per_page" id="directory_per_page"> 
			<input type="hidden" value="" name="sort" id="cs_sort_value"> 
			<input type="hidden" value="<?php echo esc_attr( $cs_SortTypeView );?>" name="action" id="listingView">  
	   </form>
	   </nav>
	  </div>
		 <?php
	 }
}

//=====================================================================
// Get Post Specification/Custom Fields
//=====================================================================
if ( ! function_exists( 'cs_get_post_specification' ) ) {
	function cs_get_post_specification($directory_type_select = ''){
		if($directory_type_select) :
			$directory_type_select  = absint($directory_type_select);
			$cs_dcpt_custom_fields = get_post_meta($directory_type_select, "cs_directory_custom_fields", true);
			if ( $cs_dcpt_custom_fields <> "" ) {
				$cs_customfields_object = new SimpleXMLElement($cs_dcpt_custom_fields);
				if(isset($cs_customfields_object->custom_fields_elements) && $cs_customfields_object->custom_fields_elements == '1'){
					if(count($cs_customfields_object)>1){
						global $cs_node;
						$counter		= 0;
						$custom_fields	= '';
						$divider		= 3;
						$wrapStart		= '';
						$firstWrapStart = '<tr>';
						foreach ( $cs_customfields_object->children() as $cs_node ){
							$wrapEnd	= '';
							if( $counter == 0 ) {
								$firstWrapStart	= '<tr>';
							}
							if( $counter > 0 && ($counter%$divider) == 0) {
								
								$wrapEnd	= '</tr>';
								$wrapStart	= '<tr>';
							}
							
							$dataElement	= cs_single_custom_fields();
							if( isset( $dataElement ) && $dataElement != '' ) {
								$custom_fields .= $firstWrapStart.$wrapEnd.$wrapStart.$dataElement;
								$counter++;
							}
							$wrapStart = '';
							$firstWrapStart	= '';
							
						}
						
						if( $counter > 0 && ($counter%$divider) == 0 && $wrapEnd == '' ) {
							$custom_fields .= '</tr>';
						}
						
					}else{
						$custom_fields = '';
					}
				}
			}
			if( isset($custom_fields) && $custom_fields !== '' ){
				echo '<div class="directory-specification">';
					echo '<h5>'.__('Specifications','directory').'</h5>';
					echo '<table class="pro_specifications">';
						echo balanceTags($custom_fields, false);
					echo '</table>';
				echo '</div>';
			}
			
		endif;
	}
}
//=====================================================================
// Get Post Specification/Custom Fields
//=====================================================================
if ( ! function_exists( 'cs_get_post_specification_list' ) ) {
	function cs_get_post_specification_list($cs_post_id = '',$directory_type_select = '',$cs_directory_fields_count =''){
		global $post;
		$custom_fields = '';
		$cs_dcpt_custom_fields = get_post_meta($directory_type_select, "cs_directory_custom_fields", true);
		if($cs_dcpt_custom_fields){
 			$cs_customfields_object = new SimpleXMLElement($cs_dcpt_custom_fields);
			if(isset($cs_customfields_object->custom_fields_elements) && $cs_customfields_object->custom_fields_elements == '1'){
				
				if(count($cs_customfields_object)>1){
					global $cs_node;
					$cs_field_counter    = 0;
					$custom_fields ='<ul class="dr_userinfo">';
					foreach ( $cs_customfields_object->children() as $cs_node ){
						
						$dataElement    = cs_custom_fields_display($cs_post_id);
						if ( isset( $dataElement ) && trim( $dataElement ) != '' && $cs_field_counter < $cs_directory_fields_count ) {
 							$cs_field_counter++;
							$custom_fields .= '<li><div class="cs-field-data">';
							$custom_fields .= $dataElement;
							$custom_fields .= '</div></li>';
						}
					}
					$custom_fields .='</ul>';
					$custom_fields .='<p>'.cs_get_content($cs_post_id,40).'</p>';;	

				}else{
					 //$custom_fields = '';
					 $custom_fields ='<p>'.cs_get_content($cs_post_id,40).'</p>';;	
				}
			}
			echo balanceTags($custom_fields, false);
		}else{
			
		}
                
	}
}

//=====================================================================
// Add post into favourite list
//=====================================================================
if ( ! function_exists( 'cs_add_dirpost_favourite' ) ) {
	function cs_add_dirpost_favourite($cs_post_id = ''){
		global $post;
		$cs_post_id = isset($cs_post_id) ? $cs_post_id : '';
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				jQuery('.tolbtn').tooltip('hide');
				jQuery('.tolbtn').popover('hide')
			});
		</script>
		<?php
  		if ( is_user_logged_in() ) {
			$user = cs_get_user_id();
			$cs_wishlist = array();
			$cs_wishlist =  get_user_meta(cs_get_user_id(),'cs-directory-wishlist', true);
			if(isset($user) and $user<>'' and is_user_logged_in()){
			  $cs_wishlist = get_user_meta(cs_get_user_id(),'cs-directory-wishlist', true);
				  if(is_array($cs_wishlist) && in_array($cs_post_id,$cs_wishlist)){
					 ?>
                     <a class="cs-add-wishlist tolbtn" data-toggle="tooltip" data-placement="top" data-original-title="<?php _e('Unfavourite','directory') ?>" onclick="cs_delete_from_favourite('<?php echo esc_url(admin_url('admin-ajax.php'));?>','<?php echo intval($cs_post_id);?>','post')" >
                        <i class="icon-star2 cs-bgcolr"></i>
                     </a>
               <?php
			}else{
			?>
				<a class="cs-add-wishlist tolbtn" onclick="cs_addto_wishlist('<?php echo esc_url(admin_url('admin-ajax.php'));?>','<?php echo intval($cs_post_id);?>','post')" data-placement="top" data-toggle="tooltip" data-original-title="<?php _e('Add to Favourite','directory') ?>">
            		<i class="icon-star"></i>
                </a>
			<?php 
			} 
			}else{
			?>
				<a class="cs-add-wishlist tolbtn" onclick="cs_addto_wishlist('<?php echo esc_url(admin_url('admin-ajax.php'));?>','<?php echo intval($cs_post_id);?>','post')" data-placement="top" data-toggle="tooltip" data-original-title="<?php _e('Add to Favourite','directory') ?>"> 
            		<i class="icon-star"></i>
            	</a>	
			<?php	
			}
			}else{
				$cs_rand_id = rand(34563,34323990);
			?>
				<a class="cs-add-wishlist tolbtn" data-target="#loginSection_<?php echo absint($cs_rand_id); ?>" data-toggle="modal" data-original-title="<?php _e('Add to Favourite','directory');?>" href="#">
                	<i class="icon-star-o"></i>
				</a>
				<div aria-hidden="true" role="dialog" tabindex="-1" id="loginSection_<?php echo absint($cs_rand_id); ?>" class="modal fade add-to-favborites-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                              <?php 
							  $cs_login_message =__('Login to add listings in favorites.','directory');
							  echo cs_login_section($cs_login_message,'','cs-login-favorites');
							  ?>
                            </div>
                        </div>
                    </div>
                </div>
			<?php
			}
	}
}

//=====================================================================
// Add post into favourite list for Carousel
//=====================================================================
if ( ! function_exists( 'cs_add_dir_favourite_carosel' ) ) {
	function cs_add_dir_favourite_carosel($cs_post_id = ''){
		global $post;
		$cs_post_id = isset($cs_post_id) ? $cs_post_id : '';
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				jQuery('.tolbtn').tooltip('hide');
				jQuery('.tolbtn').popover('hide')
			});
		</script>
		<?php
		$user = cs_get_user_id();
		$cs_wishlist = array();
		$cs_wishlist =  get_user_meta(cs_get_user_id(),'cs-directory-wishlist', true);
		if(isset($user) and $user<>'' and is_user_logged_in()){
		  $cs_wishlist = get_user_meta(cs_get_user_id(),'cs-directory-wishlist', true);
			  if(is_array($cs_wishlist) && in_array($cs_post_id,$cs_wishlist)){
				 ?>
				 <a class="cs-add-wishlist tolbtn" data-toggle="tooltip" data-placement="top" data-original-title="<?php _e('Unfavourite','directory') ?>" onclick="cs_delete_from_favourite_carosel('<?php echo esc_url(admin_url('admin-ajax.php'));?>','<?php echo intval($cs_post_id);?>','post')" >
					<i class="icon-star2 cs-bgcolr"></i>
				 </a>
		   <?php
		}else{
		?>
			<a class="cs-add-wishlist tolbtn" onclick="cs_addto_wishlist_carosel('<?php echo esc_url(admin_url('admin-ajax.php'));?>','<?php echo intval($cs_post_id);?>','post')" data-placement="top" data-toggle="tooltip" data-original-title="<?php _e('Add to Favourite','directory') ?>">
				<i class="icon-star"></i>
			</a>
		<?php 
		} 
		}else{
		?>
			<a class="cs-add-wishlist tolbtn" onclick="cs_addto_wishlist_carosel('<?php echo esc_url(admin_url('admin-ajax.php'));?>','<?php echo intval($cs_post_id);?>','post')" data-placement="top" data-toggle="tooltip" data-original-title="<?php _e('Add to Favourite','directory') ?>"> 
				<i class="icon-star"></i>
			</a>	
		<?php	
		}
			
	}
}
//======================================================================
// get sum amount
//======================================================================

if ( ! function_exists( 'cs_get_sum_price' ) ) {
	function cs_get_sum_price() {
		$json = array();
		$val1 = isset($_POST['feature_price']) ? $_POST['feature_price'] : '0';
		$val2 = isset($_POST['package_price']) ? $_POST['package_price'] : '0';
		$type_price = isset($_POST['dir_type_price']) ? $_POST['dir_type_price'] : '0';
		$currency_sign = isset($_POST['currency_sign']) ? $_POST['currency_sign'] : '';
		$selected = isset($_POST['selected']) ? $_POST['selected'] : '';
		$val1 = $val1 > 0 ? $val1 : 0;
		$val2 = $val2 > 0 ? $val2 : 0;
		
		$sum = '';
		if( $val1 !== '' && $val2 !== '' ){
			if( $selected == 'yes' ) {
				$calculated_value = $val1+$val2;
			}
			else {
				$calculated_value = $val2;
			}
						
			$cs_pkg_value = $calculated_value;
			
			$calculated_value += $type_price;
			
			$cs_msg_txt = __('Sum of selected package is', 'directory').($currency_sign.$calculated_value);
			
			if( $type_price > 0 && $cs_pkg_value > 0 ) {
				$cs_msg_txt = sprintf(__('Sum of Package with selected Type is %s + %s = %s', 'directory'), $currency_sign.$cs_pkg_value, $currency_sign.$type_price, $currency_sign.$calculated_value);
			}
			
			if( $calculated_value > 0 ) {
				$json['value']		= 'buy';
				$json['message']	= $cs_msg_txt;
			} else {
				$json['value']		= 'null';
				$json['message']	= $cs_msg_txt;
			}
		}
		
		echo json_encode($json);
		die;
	}
	add_action('wp_ajax_cs_get_sum_price', 'cs_get_sum_price');
}
//======================================================================
// featured add incase of paid
//======================================================================
if ( ! function_exists( 'cs_show_featured_text' ) ) {
	function cs_show_featured_text($cs_post_id = '' ) {
		if($cs_post_id <> ''){
			$cs_dir_expire_date =  get_post_meta($cs_post_id,'dir_pkg_expire_date',true);
			if(isset($cs_dir_expire_date) and $cs_dir_expire_date != 'unlimited' and strtotime($cs_dir_expire_date) > strtotime(date("Y-m-d H:i:s"))){
				return '<span class="cs-paid-ad">'.__('Featured','directory').'</span>';
			}
		}
 	}
 }
 
//======================================================================
// featured add incase of paid
//======================================================================
if ( ! function_exists( 'cs_map_featured_label' ) ) {
	function cs_map_featured_label($cs_post_id = '' ) {
		if($cs_post_id <> ''){
			$cs_dir_expire_date =  get_post_meta($cs_post_id,'dir_pkg_expire_date',true);
			if(isset($cs_dir_expire_date) and $cs_dir_expire_date != 'unlimited' and strtotime($cs_dir_expire_date) > strtotime(date("Y-m-d H:i:s"))){
				return __('Featured','directory');
			}
		}
 	}
 }
 //======================================================================
// total rating and number of reviews
//======================================================================
if ( ! function_exists( 'cs_get_total_rating' ) ) {
	function cs_get_total_rating($cs_post_id){
	 	global $post;
 		$reviews_args = array(
				'posts_per_page'			=> "-1",
				'post_type'					=> 'cs-reviews',
				'post_status'				=> 'publish',
				'orderby'					=> 'ID',
				'meta_key'					=> 'cs_reviews_directory',
				'meta_value'				=> $cs_post_id,
				'meta_compare'				=> '=',
				'order'						=> 'ASC',
		);
		$reviews_query = new WP_Query($reviews_args);
		$reviews_count = $reviews_query->post_count;
		$var_cp_rating = 0;
		$post_count = 0;
		if ( $reviews_query->have_posts() <> "" ) {
			$cs_directory_type_select = get_post_meta($cs_post_id, "directory_type_select", true);
			$cs_rating_options = get_post_meta((int)$cs_directory_type_select, 'cs_rating_meta', true);
			
			$rating = 0;
			$dir_rating = array();
			$rating_array = array();
			while ( $reviews_query->have_posts() ): $reviews_query->the_post();	
				$post_count++;
				if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
					foreach($cs_rating_options as $rating_key=>$rating){
						if(isset($rating_key) && $rating_key <> ''){
							$rating_title = $rating['rating_title'];
							$rating_slug = $rating['rating_slug'];
							if(isset($rating_slug)){
								//$rating_value = $_POST[$rating_slug];
								//($rating_value){
									$rating_point = get_post_meta($post->ID, $rating_slug, true);
									if($rating_point)
										$rating_array[] = $rating_point;
								//}
							}
						}
					}
					
				}
				
			endwhile;
			wp_reset_postdata();
			if($rating_array && is_array($rating_array) && count($rating_array)>0){
				$dir_rating[] = round(array_sum($rating_array)/count($cs_rating_options), 2);
			}
			
		}
 		if(isset($dir_rating) && is_array($dir_rating) && count($dir_rating)>0){
			$var_cp_rating_sum = array_sum($dir_rating);
			$var_cp_rating = $var_cp_rating_sum/$post_count;
			$var_cp_rating = round($var_cp_rating, 2);
		}
		return $var_cp_rating;
	}
}

//======================================================================
// total rating and number of reviews
//======================================================================
if ( ! function_exists( 'cs_total_ad_rating' ) ) {
	function cs_total_ad_rating($cs_post_id = '') {
		$directory_rating  	= '';
		//$directory_rating   = get_post_meta($cs_post_id, "cs_directory_review_rating", true);
		$directory_rating   = cs_get_total_rating($cs_post_id);
		$cs_reviews_count	= cs_total_ad_reviews($cs_post_id);
		$cs_dir_switch 		= cs_dir_switch((int)$cs_post_id);
		if(isset($directory_rating)){$directory_rating = $directory_rating*20;} else {$directory_rating = 0;}
		if($directory_rating <> 0 and $cs_dir_switch == 'on'){
           	echo '<div class="cs-rating-wrape">
					<div class="cs-rating">
					 <span style="width:'.absint($directory_rating).'%" class="rating-box"></span>
					</div>
					<span>('.$cs_reviews_count.' '.__('reviews','directory').')</span>
				  </div>';
		}
	}
}
//======================================================================
// total number of reviews per post
//======================================================================
if ( ! function_exists( 'cs_total_ad_reviews' ) ) {
	function cs_total_ad_reviews($cs_post_id = '') {
 		$result = '';
		if($cs_post_id){
			$result = '';
			$args = array(
				'posts_per_page'			=> "-1",
				'post_type'					=> 'cs-reviews',
				'post_status'				=> 'publish',
				'orderby'					=> 'ID',
				'meta_key'					=> 'cs_reviews_directory',
				'meta_value'				=> $cs_post_id,
				'meta_compare'				=> '=',
				'order'						=> 'ASC',
			);
			$custom_review_query = new WP_Query($args);
			$result =$custom_review_query->post_count;
			wp_reset_postdata(); 
		}else{
			$result = '';
		}
		
		return $result;
 	}
}

//======================================================================
// Get Directory Organizer ID
//======================================================================
if ( ! function_exists( 'cs_get_organizer_id' ) ) {
	function cs_get_organizer_id($cs_post_id = '') {
 		
		$directory_organizer = get_post_meta((int)$cs_post_id, "directory_organizer", true);
		if ( isset( $directory_organizer) && $directory_organizer !='' ){
			$organizerID    = intval( $directory_organizer );    
		} else {
			$organizerID    = intval( get_the_author_meta('ID') );    
		}
		
		return $organizerID;
 	}
}

//======================================================================
// Check is Directory ad Urgent
//======================================================================
if ( ! function_exists( 'cs_ad_urgent' ) ) {
	function cs_ad_urgent( $cs_post_id = '' ) {
 		$dir_featured_till = get_post_meta($cs_post_id, "dir_featured_till", true);
		$isFeatured = false;
		if ( isset( $dir_featured_till ) && $dir_featured_till !='' ){
			$current_date = date("Y-m-d H:i:s");
			if( strtotime( $dir_featured_till ) > strtotime( $current_date ) ) {
				$isFeatured = true;
			}
		}
		
		if( isset( $isFeatured ) && $isFeatured == true ) {
		
		?>
            <ul class="featured-post">
                <li><span class="add-featured"><?php _e('Urgent', 'directory');?></span></li>
            </ul>
        <?php 
		}
 	}
}
//======================================================================
// Check is Directory Review on/off
//======================================================================
if ( ! function_exists( 'cs_dir_switch' ) ) {
	function cs_dir_switch( $cs_post_id = '' ) {
		$cs_dir_review = 'off';
		if($cs_post_id){
			$cs_dir_type_select = get_post_meta($cs_post_id, "directory_type_select", true);
			if($cs_dir_type_select <> ''){
				$cs_dir_review = get_post_meta($cs_dir_type_select, "post_review_switch", true);
			}
		}
		return $cs_dir_review;
 	}
}
//======================================================================
// Get post/page content to spacific limit
//======================================================================
if ( ! function_exists( 'cs_get_content' ) ) {
	function cs_get_content( $cs_post_id = '',$cs_limit = '40' ) {
		$cs_post_data	= get_post($cs_post_id);
		$cs_content = $cs_post_data->post_content;
		$cs_content = wp_trim_words($cs_content,$cs_limit,'...');
		return $cs_content;
 	}
}

/*---------------------------------------------------------------
 * CS Get Location
/*--------------------------------------------------------------*/
function cs_get_location( $post_id='' ){
	global $post,$cs_theme_options;
	 $cs_address_view = isset($cs_theme_options['cs_dir_address_view']) ? $cs_theme_options['cs_dir_address_view'] : '';
	 $cs_address_limit = isset($cs_theme_options['cs_address_limit']) ? $cs_theme_options['cs_address_limit'] : '30';
	 $cs_address	= '';
	 $full_address  = get_post_meta($post_id, "dynamic_post_location_address", true);
	 $cs_city 		= get_post_meta($post_id, "dynamic_post_location_city", true);
	 $cs_country    = get_post_meta($post_id, "dynamic_post_location_country", true);
	 
	if($cs_address_view == 'City Only'){
		$cs_address	= $cs_city;
	}elseif($cs_address_view == 'Country Only'){
		if ( isset( $cs_country ) && $cs_country !='' ){
	 		$cs_address	= str_replace('_',' ',$cs_country);
	 	}
	}elseif($cs_address_view == 'City and Country'){
		if ( isset( $cs_city ) && $cs_city !='' ){
	 		$cs_address	.= str_replace('_',' ',$cs_city).',';
	 	}
		if ( isset( $cs_country ) && $cs_country !='' ){
	 		$cs_address	.= str_replace('_',' ',$cs_country);
	 	}
	}else{
		if(isset($full_address) and $full_address !=''){
			$cs_address=substr($full_address,0,$cs_address_limit);
			if(strlen($full_address) > $cs_address_limit){
				$cs_address.='...';
			}
		}
	}
	 
	 return $cs_address;
	
}

/*---------------------------------------------------------------
 * CS Calculate Radius
 * @ Convert meters to miles
 * @ 1 meter = 0.000621371 miles
/*--------------------------------------------------------------*/
function cs_get_radius(){
	global $cs_theme_options;
	
	$distance_km_miles = isset($cs_theme_options['distance_km_miles']) ? $cs_theme_options['distance_km_miles'] : 'Miles';

	if( isset($cs_theme_options['cs_loc_max_input'])  && trim($cs_theme_options['cs_loc_max_input']) !=''  && trim($cs_theme_options['cs_loc_max_input']) != 0  ) {
		$radius = $cs_theme_options['cs_loc_max_input'];
	} else {
		$radius = 150;
	}
	
	if( isset( $_GET['radius'] ) ) {
		 $radius = $_GET['radius']; 
	} else {
		 $radius = $radius;
	}

	return $radius;
}
