<?php
//======================================================================
// Directory Search Shortcode
//======================================================================
if (!function_exists('cs_directory_search_shortcode')) {
    function cs_directory_search_shortcode( $atts ) {
        global $post,$wpdb,$cs_xmlObject,$cs_theme_options;
        $defaults = array( 'directory_search_title' => '','cs_directory_map'=>'Yes','cs_directory_map_responsive'=>'Yes','cs_directory_map_style'=>'style-1','cs_search_views'=>'view-one','cs_field_lables'=>'Yes','cs_directory_search_views'=>'','cs_directory_search_filter'=>'all', 'directory_search_latitude'=>'', 'directory_search_longitude'=>'' ,'cs_directory_search_result_page'=>'', 'cs_directory_search_class' => '','cs_directory_search_animation' => '');
        extract( shortcode_atts( $defaults, $atts ) );
        $cs_directory_search_location    		= $cs_theme_options['cs_directory_search_location'];
		$goe_location_enable            		= isset($cs_theme_options['goe_location_enable']) ? $cs_theme_options['goe_location_enable'] : 'No';
		$cs_streat_view            				= isset($cs_theme_options['cs_streat_view']) ? $cs_theme_options['cs_streat_view'] : 'No';
        $cs_directory_location_suggestions      = $cs_theme_options['cs_directory_location_suggestions'];
        $cs_loc_max_input                       = isset( $cs_theme_options['cs_loc_max_input'] ) && $cs_theme_options['cs_loc_max_input'] != 0 ? $cs_theme_options['cs_loc_max_input'] : 150;
        $cs_loc_incr_step                       = $cs_theme_options['cs_loc_incr_step'];
		$distance_km_miles                      = isset($cs_theme_options['distance_km_miles']) ? $cs_theme_options['distance_km_miles'] : 'Miles';
		$cs_map_auto_zoom                       = isset($cs_theme_options['cs_map_auto_zoom']) ? $cs_theme_options['cs_map_auto_zoom'] : 'off';
		$cs_location_type						= isset( $cs_theme_options['cs_search_by_location'] ) ?  $cs_theme_options['cs_search_by_location'] : '';	
		$cs_map_lock							= isset($cs_theme_options['cs_map_lock']) ? $cs_theme_options['cs_map_lock'] : ''; 
		
		
		if( isset( $distance_km_miles ) && $distance_km_miles == 'Miles' ) {
			 $cs_loc_max_input_slider	= '300';
		} else{
			 $cs_loc_max_input_slider	= '500';
		}
		
        if (isset($cs_xmlObject->sidebar_layout) && $cs_xmlObject->sidebar_layout->cs_page_layout <> '' and $cs_xmlObject->sidebar_layout->cs_page_layout <> "none"){
            $cs_directory_grid_layout = 'col-md-4';
        }else{
            $cs_directory_grid_layout = 'col-md-3';
        }
        
		$cs_svg_marker = wp_directory::plugin_url().'assets/images/orange-marker.svg';
		
		if( isset( $_GET['type' ]) && $_GET['type'] <> '' ){
			  $directory_type = $_GET['type'];
			  
			  if(isset($directory_type) && $directory_type <> ''){
				  $args= array(
					  'name' 			=> (string)$directory_type,
					  'post_type' 		=> 'directory_types',
					  'post_status' 	=> 'publish',
					  'posts_per_page'  => 1
				  );
				  
				  $dir_posts = get_posts( $args );
				  
				  if( $dir_posts ) {
					  $directory_id = $dir_posts[0]->ID;
				  }
			  }
		} else {
			 $directory_id = isset($cs_theme_options['cs_default_ad_type']) ? $cs_theme_options['cs_default_ad_type'] : '';
		} 

        $currency_sign = isset($cs_theme_options['currency_sign']) ? $cs_theme_options['currency_sign'] : '';
        
        if( isset( $cs_directory_search_result_page ) && $cs_directory_search_result_page <> '' ){
            $cs_search_page_id = '';
			$action_page = get_permalink((int)$cs_directory_search_result_page);
			$cs_parse_url = parse_url($action_page);
			if( isset($cs_parse_url['query']) ) {
				$cs_search_page_id = str_replace('page_id=', '', $cs_parse_url['query']);
				$action_page = home_url();
			}
        } else {
			$cs_search_page_id = '';
            $action_page = home_url();
        }
        
        wp_directory::cs_multipleselect_scripts();
        
        if(isset($cs_directory_map) && $cs_directory_map == 'Yes'){
            $cs_search_map_class= 'map-search-shortcode cs-search-map-enable';
        }else{
            $cs_search_map_class= 'cs-search-map-disable';
        }
		
		if( isset( $cs_search_views ) && $cs_search_views =='view-two' ){
			$seacrchViewClass	= 'cs-search-v2';
			$seacrchBtnText		= 'Search';
		} elseif( isset( $cs_search_views ) && $cs_search_views =='view-three' ){
			$seacrchViewClass	= 'cs-search-v3';
			$seacrchBtnText		= '';
		} else{
			$seacrchViewClass	= 'cs-search-v1';
			$seacrchBtnText		= '';
		}
		
		$search_text	= '';
		if ( isset( $_GET['search_text'] ) ) {
			$search_text	= $_GET['search_text'];
		}
		
		if( isset( $_GET['location'] ) ){
		  	$search_location = $_GET['location'];
	    }
        
		?>
        <div class="<?php echo cs_allow_special_char($cs_search_map_class.' '.$seacrchViewClass); ?>">
          <?php
		  $cs_mobile_map = true;
		  if( $cs_directory_map_responsive == 'No' && wp_is_mobile() ){
			  $cs_mobile_map = false;
		  }
          if( isset($cs_directory_map) && $cs_directory_map == 'Yes' && $cs_mobile_map == true ){
			  
             if(!isset($_REQUEST['page_id_all'])) $_REQUEST['page_id_all']=1;
            $args=array(
                        'post_type' => 'directory',
                        'post_status' => 'publish',
                        'paged' => $_REQUEST['page_id_all'],
                        );
                $meta_fields_array = array('relation' => 'AND',);
                if(isset($directory_id) && $directory_id <> ''){
                    $meta_fields_array[] = array(
                        'key'        =>     'directory_type_select',
                        'value'        =>     $directory_id,
                        'compare'     =>     '=',
                        'type'         =>     'NUMERIC'
                      );
                }
              
			  if(isset($cs_directory_search_filter) && $cs_directory_search_filter == 'paid'){
                    $meta_fields_array[] = array('key' => 'dir_pkg_expire_date', 'value'  => date("Y-m-d H:i:s"), 'compare' => '>', 'type' => 'NUMERIC');
                    $args['meta_key'] = 'dir_pkg_expire_date';
                    $args['orderby'] = 'meta_value';
                    $args['order'] = 'DESC';
                }  else if(isset($cs_directory_search_filter) && $cs_directory_search_filter == 'free'){
                    $meta_fields_array[] = array('key' => 'dir_pkg_expire_date', 'value'  => date("Y-m-d H:i:s"), 'compare' => '<', 'type' => 'NUMERIC');
                    $args['orderby'] = 'meta_value';
                    $args['order'] = 'ASC';
                }
                if(is_array($meta_fields_array) && count($meta_fields_array)>1){
                    $args['meta_query'] = $meta_fields_array;
                }

            $args['posts_per_page'] = -1;
            $custom_query = new WP_Query($args);
            $count_post = $custom_query->post_count;
            $directory_array = array();
            $directories = array();
            
            $currency_sign             = isset($cs_theme_options['currency_sign'])?$cs_theme_options['currency_sign']:'$';
            if(isset($cs_theme_options['cluster_map_marker']))
                $map_marker_url    = $cs_theme_options['cluster_map_marker'];
            else
                $map_marker_url    = get_template_directory_uri()  .'/assets/images/img-txtfld.png';
            if(isset($cs_theme_options['product_map_marker']))
                $product_map_marker    = $cs_theme_options['product_map_marker'];
            else
                $product_map_marker    = get_template_directory_uri()  .'/assets/images/map-marker.png';
            
            if(isset($cs_theme_options['cluster_map_marker_color']) && $cs_theme_options['cluster_map_marker_color'] <> '')
                $cs_cluster_marker_color_input    = $cs_theme_options['cluster_map_marker_color'];
            else
                $cs_cluster_marker_color_input = '#000';
            
            if(isset($cs_theme_options['cs_map_type']) && $cs_theme_options['cs_map_type'] <> '')
                $cs_map_type    = $cs_theme_options['cs_map_type'];
            else
                $cs_map_type = 'ROADMAP';
            if(isset($cs_theme_options['map_zoom']) && $cs_theme_options['map_zoom'] <> '')
                $map_zoom    = (int)$cs_theme_options['map_zoom'];
            else
                $map_zoom = 6;
	    		$map_zoom = 2;
            if ( $custom_query->have_posts() ) : 
                $width                 = '370';
                $height                = '280';
                wp_directory::cs_googlemapcluster_scripts();
                $cs_page_id    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
                $total_post = $custom_query->post_count;
               
                $directory_array = array('count'=>$total_post);
                while ( $custom_query->have_posts() ) : $custom_query->the_post();
                  $organizerID             = get_post_meta( $post->ID, 'directory_organizer', true );
                  $latitude                = get_post_meta( $post->ID, 'dynamic_post_location_latitude', true );
                  $longitude               = get_post_meta( $post->ID, 'dynamic_post_location_longitude', true );
                  $direcotry_type_id       = get_post_meta( $post->ID, 'directory_type_select', true );
				  $cs_post_id = $post->ID;
                  
                  $cs_directory_featured = get_post_meta($post->ID, "directory_featured", true);
                  $location = get_post_meta($post->ID, "dynamic_post_location_address", true);
                  
                  $dir_featured_till         = get_post_meta($post->ID, "dir_featured_till", true);
                  $cs_directory_featured    = 'no';

                  $image_url = get_post_meta($cs_post_id, '_directory_image_gallery', true );
                  $image_url = array_filter( explode( ',', $image_url ) );
                  if ( isset( $image_url ) && $image_url <> '' ) {
 				 	$image_url = isset($image_url[0]) ? cs_attachment_image_src( $image_url[0] ,$width,$height)  : '';  
                  } else {
                    $image_url    = '';
                  }
            
                  if ( isset( $dir_featured_till ) && $dir_featured_till !='' ){
                        $current_date = date("Y-m-d H:i:s");
                        if( strtotime( $dir_featured_till ) > strtotime( $current_date ) ) {
                            $cs_directory_featured    = 'yes';
                        }
                  }    
                                                          
                  if( isset( $cs_directory_featured ) && $cs_directory_featured == 'yes' ) {
                    $cs_directory_featured    = '<li><span>'.__("URGENT","directory").':'.'</span></li>';
					
					
					
                  } else{
                     $cs_directory_featured    = '';
                  }
				  
				  $is_featured_text	=  cs_map_featured_label($post->ID);
                  
                  $dir_payment_date = get_post_meta( $post->ID, "dir_payment_date", true ); 
                  if($dir_payment_date == '') {
                    $dir_payment_date = get_the_date();
                  } 
                
                    $dynamic_post_sale_oldprice = get_post_meta($post->ID, "dynamic_post_sale_oldprice", true);
                    $dynamic_post_sale_newprice = get_post_meta($post->ID, "dynamic_post_sale_newprice", true);
                    if ( ( isset( $dynamic_post_sale_oldprice ) && $dynamic_post_sale_oldprice !='' ) || isset( $dynamic_post_sale_newprice ) && $dynamic_post_sale_newprice !=''  ) {
                        $price    = '';
                        if(isset( $dynamic_post_sale_oldprice ) && $dynamic_post_sale_oldprice !='' ){
                            $price    .= '<small>'.esc_attr( $currency_sign.number_format(absint($dynamic_post_sale_oldprice))).'</small> ';
                        }
                        if(isset( $dynamic_post_sale_newprice ) && $dynamic_post_sale_newprice !=''){
                            $price    .= esc_attr( $currency_sign.number_format(absint($dynamic_post_sale_newprice)));
                        }
                    } else{
                        $price    = '';
                    }
                  
				  $map_thumb_url         = get_post_meta( $direcotry_type_id, 'cs_destination_url_input', true );
                  if($map_thumb_url == '' || empty($map_thumb_url)){
                    $map_thumb_url = $product_map_marker;
                  }
                  
				  $user_profile_url = cs_user_profile_link($cs_page_id, 'dashboard', $organizerID);
                  $directories[] = array(
                                    'post_id' => $post->ID,
                                    'post_title' => get_the_title(),
                                    'image_url' => $image_url,
                                    'permalink' => addslashes(get_permalink()),
                                    'longitude' => $longitude,
                                    'latitude' => $latitude,
                                    'mapamrker' => $map_thumb_url,
                                    'width' => $width,
                                    'height' => $height,
                                    'publish_date' => esc_attr( date_i18n( get_option( 'date_format' ),strtotime( get_the_date() ) ) ),
                                    'user_id' => absint($organizerID),
                                    'user_name' => get_the_author_meta('display_name',$organizerID ),
                                    'user_profile_url' => $user_profile_url,
                                    'featured'           => $cs_directory_featured,
									'featured_text'      => $is_featured_text,
                                    'date'               => esc_attr( date_i18n( get_option( 'date_format' ),strtotime( $dir_payment_date ) ) ),
                                    'location'           => $location,
                                    // 'location'           => '',
                                    'price'               => $price,
				    'objective'		=> get_post_custom()['dir_objective'][0],
                                );
                endwhile;
                wp_reset_postdata();
                endif;
                
               $latitude  = isset( $cs_theme_options['map_latitude'] ) && $cs_theme_options['map_latitude'] ? $cs_theme_options['map_latitude'] : '51.54532829999999';
               $longitude = isset( $cs_theme_options['map_longitude'] ) && $cs_theme_options['map_longitude'] ? $cs_theme_options['map_longitude'] : '-0.08428670000000693';
				
				$default_latitude    = isset($directory_search_latitude) && ! empty( $directory_search_latitude ) ? $directory_search_latitude : $latitude;
				$default_longitude   = isset($directory_search_longitude) && ! empty( $directory_search_longitude ) ? $directory_search_longitude : $longitude;
                
				$directory_array['posts']     = $directories;
		$default_latitude = '1.54532829999999';
		$default_longitude = '-0.08428670000000693';
                $json_array                   = json_encode($directory_array);
                $Latitude                     = $default_latitude;
                $Longitude                    = $default_longitude;
                $rand_id                      = rand();
                
                ?>
                    <div id="map-container<?php echo esc_attr($rand_id);?>" class="map-container">
                      
                      <span class="loader"></span>
                      <span class="fullscreen"><i class="icon-arrows"></i> <?php _e('Full Screen','directory');?></span>
                      <span class="gmapzoomplus" id="gmapzoomplus<?php echo esc_attr($rand_id);?>" style="cursor: pointer;"><i class="icon-plus8"></i></span>
                      <span class="gmapzoomminus" id="gmapzoomminus<?php echo esc_attr($rand_id);?>" style="cursor: pointer;"><i class="icon-minus8"></i></span>
                      <div class="cs-control-icons">
                      	  <?php 
						  $cs_map_lock_icon = 'icon-unlock';
						  $cs_map_lock = isset($cs_theme_options['cs_map_lock']) ? $cs_theme_options['cs_map_lock'] : ''; 
						  if( $cs_map_lock == 'on' ) {
							  $cs_map_lock_icon = 'icon-lock3';
						  }
						  ?>
                          <span class="gmaplock" id="gmaplock<?php echo esc_attr($rand_id);?>" style="cursor: pointer;"><i class="<?php echo cs_allow_special_char($cs_map_lock_icon);?>"></i></span>
                          <?php if( (isset($goe_location_enable) && $goe_location_enable == 'Yes') && (isset($cs_directory_location_suggestions) && $cs_directory_location_suggestions == 'Google') ){ ?>
                          <span class="gmapcurrentloc" id="gmapcurrentloc<?php echo esc_attr($rand_id);?>" style="cursor: pointer;"><i class="icon-paperplane3"></i></span>
                          <?php } ?>
                      </div>
                      <div class="map" id="map<?php echo esc_attr($rand_id);?>" style="opacity:0"></div>
                    </div>
                    <input type="hidden" id="rand_id" value="<?php echo esc_attr($rand_id);?>" />
                    <input type="hidden" id="admin_url" value="<?php echo esc_js(admin_url('admin-ajax.php'));?>" />
                    <script type="text/javascript">
                        jQuery(document).ready(function($) {
                            jQuery(".fullscreen").click(function() {
 								jQuery("body").toggleClass("body-fullscreen");
                                jQuery("#map-container<?php echo esc_attr($rand_id);?>").height(jQuery(window).height);
								var map = jQuery("#map-container<?php echo esc_attr($rand_id);?>");
                                google.maps.event.trigger(map, "resize");
								//jQuery(window).load();
								cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js($cs_directory_map_style);?>');
                            });
                            
							var timer;
							
							jQuery("input.search_min_price").on('keyup', function(e) {
								clearInterval(timer);  //clear any interval on key up
								timer = setTimeout(function() { //then give it a second to see if the user is finished
									cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js($cs_directory_map_style);?>');
								}, 2000);
							});
							
							jQuery("input.search_max_price").on('keyup', function(e) {
								clearInterval(timer);  //clear any interval on key up
								timer = setTimeout(function() { //then give it a second to see if the user is finished
									cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js($cs_directory_map_style);?>');
								}, 2000);
							});
							
							/* jQuery("input.form-search-text").on('keyup', function(event) {
								clearInterval(timer);  //clear any interval on key up
								timer = setTimeout(function() { //then give it a second to see if the user is finished
									cs_directory_map_search('<?php // echo esc_js($rand_id);?>', '<?php // echo esc_js(admin_url('admin-ajax.php'));?>', '<?php // echo esc_js($cs_directory_map_style);?>');
								}, 2000);
							});
							
							jQuery( "#directory-search-location" ).on('keyup', function(event) {
								jQuery('#geo_loc_option').val('off');
								clearInterval(timer);  //clear any interval on key up
								timer = setTimeout(function() { //then give it a second to see if the user is finished
									cs_directory_map_search('<?php // echo esc_js($rand_id);?>', '<?php // echo esc_js(admin_url('admin-ajax.php'));?>', '<?php // echo esc_js($cs_directory_map_style);?>');
								}, 2000);
								jQuery(".loader").html('');
								return false;
                            });
							
							jQuery( "form #directory-search-location" ).live("change", function() {
								jQuery('#geo_loc_option').val('off');
								clearInterval(timer);  //clear any interval on key up
								timer = setTimeout(function() { //then give it a second to see if the user is finished
									cs_directory_map_search('<?php // echo esc_js($rand_id);?>', '<?php // echo esc_js(admin_url('admin-ajax.php'));?>', '<?php // echo esc_js($cs_directory_map_style);?>');
								}, 2000);
								jQuery(".loader").html('');
								return false;
                            });  */
							
							jQuery('.location-icon').click( function(e) { 
                                setTimeout(function(){
                                    cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js($cs_directory_map_style);?>');
                                },2000);
                                return false;
                            });
							
							jQuery( "div.cs-drag-slider" ).click(function(e) { 
								clearInterval(timer);  //clear any interval on key up
								timer = setTimeout(function() { //then give it a second to see if the user is finished
									cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js($cs_directory_map_style);?>');
								}, 2000);
                        	});
                        });                         
						
                        jQuery(window).load(function() {
                            var dataobj = jQuery.parseJSON( '<?php echo addslashes($json_array);?>' );
                            cs_googlecluster_map('<?php echo esc_js($rand_id);?>', '<?php echo esc_js($Latitude);?>', '<?php echo esc_js($Longitude);?>', '<?php echo esc_js($map_marker_url);?>', dataobj, '<?php echo esc_js($cs_map_type);?>', <?php echo absint($map_zoom);?>,'<?php echo esc_js($cs_cluster_marker_color_input);?>', '<?php echo esc_js($cs_directory_map_style);?>', '<?php echo esc_js($cs_map_auto_zoom); ?>', '<?php echo esc_js($cs_svg_marker); ?>', '<?php echo esc_js($cs_map_lock); ?>');
                            jQuery(".loader").html('');
                            jQuery("#map<?php echo esc_attr($rand_id);?>").css({
                                "opacity" :"1"
                            })
                        });
                        
                        if(jQuery("#map<?php // echo esc_attr($rand_id);?>").length>0){
                            jQuery( ".MultiControls p.btnOk" ).live("click", function() {
                                cs_directory_map_search('<?php // echo esc_js($rand_id);?>', '<?php // echo esc_js(admin_url('admin-ajax.php'));?>', '<?php // echo esc_js($cs_directory_map_style);?>');
                                return false;
                            });
                            
							 /* jQuery( "form #directory-field-category" ).live("change", function() {
								//alert('asd');
                                jQuery('#geo_loc_option').val('off');
                                cs_directory_map_search('<?php // echo esc_js($rand_id);?>', '<?php // echo esc_js(admin_url('admin-ajax.php'));?>', '<?php // echo esc_js($cs_directory_map_style);?>');
                                return false;
                            }); */
                        }
                    </script>
		  <?php    
		  }
		  ?>  
        <!-- Advance search code -->
        <div id="directory-advanced-search">
        
            <div class="container">
                <div class="directory-advanced-search-content">
                	
                		<p style="text-align: center; font-weight: bold !important;">Find out where and how SNOMED® is being used around the world.</p>
                    <form class="form-horizontal" accept-charset="UTF-8" id="directory-advance-search-form" method="get" action="<?php echo esc_url($action_page);?>" role="search">
                    	<?php if( isset($cs_search_page_id) && $cs_search_page_id <> '' ) { ?>
                        <input type="hidden" name="page_id" value="<?php echo absint($cs_search_page_id);?>" />
                        <?php } ?>
                        <input type="hidden" name="filter" value="<?php echo esc_attr($cs_directory_search_filter);?>" />
                        <div class="dir-search-fields">
						<?php if( isset( $directory_search_title ) && $directory_search_title !='' ) {?>
                        	<h2><?php echo esc_attr( $directory_search_title );?></h2>
                        <?php }?>
                        
                        <ul>
                         <?php if( isset( $cs_theme_options['cs_search_text'] ) && $cs_theme_options['cs_search_text'] == 'on' ) {?>
                         <li>
                           <?php if( isset( $cs_field_lables ) && $cs_field_lables =='Yes' ){?>
                           <h6><?php _e('Search Text', 'directory');?></h6>
                           <?php }?>
                           <!-- <input type="text" id="txtSearch" onkeydown="if (event.keyCode == 13) document.getElementById('directory-submit-search-view').click()"/> -->
                           <input type="text" class="form-search-text" maxlength="128" size="30" value="<?php echo esc_attr( $search_text );?>" name="search_text" id="edit-search-api-views-fulltext" onkeydown="if (event.keyCode == 13) document.getElementById('directory-submit-search-view').click()" placeholder="<?php _e('Enter keyword...', 'directory');?>">
                         </li>
                         <?php }?>
                          <script>
                                jQuery(document).ready(function($) {
                                    window.asd = jQuery('select.form-select').SumoSelect();
                                });
                            </script>
                             <?php
                             if ( function_exists( 'cs_bootstrap_select' ) ) {
                                 cs_bootstrap_select();
                             }
            
                            $args = array(
                                'posts_per_page'               => "-1",
                                'post_type'                    => 'directory_types',
                                'post_status'                  => 'publish',
                                'orderby'                      => 'ID',
                                'order'                        => 'ASC',
                            );
							
                            $custom_query = new WP_Query($args);
                            
							if ( $custom_query->have_posts() <> "" ) {
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
                                ?>
                                <li> 
                                <?php if( isset( $cs_field_lables ) && $cs_field_lables =='Yes' ){?>
                                <h6><?php _e('Categories', 'directory');?></h6>
                                <?php }?>
                                    <select class="form-select dir-map-search SlectBox" name="directory_categories" id="directory-field-category" >
                                        <option value=""><?php _e('All Categories', 'directory');?></option>
                                        <?php
                                             foreach ( $categories as $category ) : ;
                                                  $selected = '';
                                                 //if($post->ID == $directory_id) $selected = 'selected="selected"';
                                                 echo '<option value="'.$category->slug.'" '.$selected.'>&nbsp;'.$category->name.'</option>';    
                                             endforeach;
                                             wp_reset_postdata();
                                        ?>
                                    </select>  
                                </li>
                                <?php
                            }
							
                            if(isset($cs_directory_search_location) && $cs_directory_search_location == 'Yes'){
                                wp_directory::cs_autocomplete_scripts();
                                if(isset( $cs_directory_location_suggestions ) && $cs_directory_location_suggestions <> ''){
                                    $cs_directory_location_suggestions = $cs_directory_location_suggestions;
                                } else {
                                    $cs_directory_location_suggestions = 'Google';
                                }
                                
                                if( isset( $_GET['geo'] ) ) {
                                     if ( !empty($_GET['geo']) ) { 
                                     	$geo_location = $_GET['geo']; 
                                     } else {
                                         $geo_location = 'off';
                                     }
                                } else {
                                    if( isset( $cs_directory_location_suggestions ) && $cs_directory_location_suggestions == 'Google' ) {
                                         $geo_location = 'on';
                                    } else {
                                         $geo_location = 'off';
                                    }
                                }
				$geo_location = 'off';
							 
							 $display_location	= '';	
							 if ( $cs_location_type == 'single_city' ) {
							 	$display_location	= 'style=display:none';
							 }
                            ?>
                            <li class="loc-section" <?php echo $display_location;?>>
                                <?php if( isset( $cs_field_lables ) && $cs_field_lables =='Yes' ){
										if( $cs_location_type !='single_city' || $cs_directory_location_suggestions == 'Google' ){
									?>
                                	<h6><?php _e('Locations', 'directory');?></h6>
                                <?php } }
								
                                    if(isset($cs_directory_location_suggestions) && $cs_directory_location_suggestions == 'Google'){
                                    
									if(isset($goe_location_enable) && $goe_location_enable == 'Yes'){
										if(!isset($rand_id))
											$rand_id = '';
										?>
                                        <script>
											jQuery(document).ready(function(e) {
                                                getLocation('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>');
                                            });
										</script>
										<div class="location-icon" onclick="getLocation('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>')"><img src="<?php echo wp_directory::plugin_url();?>/assets/images/maplocation.png" alt="" /></div>
									<?php } ?>   
                                    <input type="search"  value="<?php if(isset($search_location)) echo urldecode($search_location);?>" autocomplete="on" id="directory-search-location" title="<?php _e('Location', 'directory');?>" onkeydown="if (event.keyCode == 13) document.getElementById('directory-submit-search-view').click()" placeholder="Postcode or location" name="location">
                                    <?php
                                    } else {
										cs_get_custom_locations($rand_id,'yes');
									}

                                    ?>
                                    <input id="geo_loc_option" name="geo" type="hidden" value="<?php echo $geo_location; ?>">
                                    <div id="geo_location_address">
                                    <?php 
                                        if($geo_location == 'on'){
                                            if(isset($_GET['geo_location_lat']) && !empty($_GET['geo_location_lat'])) $geo_location_lat = $_GET['geo_location_lat']; else $geo_location_lat = '';
                                            if(isset($_GET['geo_location_long']) && !empty($_GET['geo_location_long'])) $geo_location_long = $_GET['geo_location_long']; else $geo_location_long = '';
                                            echo "<input type='hidden' name='geo_location_lat' value='" .$geo_location_lat. "' >
												  <input type='hidden' name='geo_location_long' value='" .$geo_location_long. "' >";
                                        }
                                    ?>
                                    </div>
                            </li>
                            <?php if( isset( $cs_theme_options['cs_search_radius'] ) && $cs_theme_options['cs_search_radius'] == 'on' ) {
									if ( $cs_location_type != 'single_city' ) {
										$radius	= cs_get_radius();
										$rand_id = rand();
								?>
								<li class="to-field">
									<?php if( isset( $cs_field_lables ) && $cs_field_lables =='Yes' && $cs_location_type != 'single_city' ){?>
                                        <h6><?php _e('Distance From Location', 'directory');?></h6>
                                    <?php }?>
									<div class="input-sec">
										<span class="drag-slider-tooltip"></span>
										<?php if( $cs_loc_incr_step == '' || (int)$cs_loc_incr_step < 1 ) $cs_loc_incr_step = 1; ?>
										<div class="cs-drag-slider slider-distance-range" data-slider-min="10" data-slider-max="<?php echo esc_attr( $cs_loc_max_input_slider );?>" data-slider-step="<?php echo esc_attr( $cs_loc_incr_step );?>" data-slider-value="<?php echo esc_attr( $radius );?>"></div>
										<input id="radius<?php echo esc_attr(  $rand_id );?>" class="cs-range-input" name="radius" type="text" value="<?php echo esc_attr( $radius );?>"   />
									</div>
									<script>
									jQuery(document).ready(function($) {
										
										jQuery('div.cs-drag-slider').each(function() {
											 var _this = jQuery(this);
											 tooltip = jQuery('span.ui-slider-handle');
											_this.slider({
												range:'min',
												step: _this.data('slider-step'),
												min: _this.data('slider-min'),
												max: _this.data('slider-max'),
												value: _this.data('slider-value'),
												slide: function (event, ui) {
													//jQuery(this).parents('li.to-field').find('.cs-range-input').val(ui.value)
													jQuery( "#radius<?php echo esc_js($rand_id);?>" ).val(ui.value);
														tooltip = jQuery(this).parents('li.to-field').find('span.ui-slider-handle');
														tooltip.html("<strong>"+ui.value+" <?php echo esc_attr( $distance_km_miles );?></strong>");
												}
											});
										});
										var value = jQuery( "#radius<?php echo esc_js($rand_id);?>" ).val();
										jQuery("div.cs-drag-slider span").first().html("<strong>"+value+" <?php echo esc_attr( $distance_km_miles );?></strong>");
									});
									(function( $ ) {
										$(function() {
											<?php 
												if(isset($cs_directory_location_suggestions) && $cs_directory_location_suggestions == 'Google'){
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
									}
								}
							}
							if( isset( $cs_theme_options['cs_search_price'] ) && $cs_theme_options['cs_search_price'] == 'on' ) {
                            	
								//echo '<span class="price-loader"></span></li>';
                                
                                if(isset($directory_id) && $directory_id <> ''){
                                    
                                    $saleprice_option = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_option', true);
                                    $price_enable_search = get_post_meta((int)$directory_id, 'cs_post_price_enable_search', true);
                                    if(isset($saleprice_option) && $saleprice_option == 'on' && isset($price_enable_search) && $price_enable_search == 'on'){
                                        
										$price_max_range_start = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_max_input', true);
                                        $price_min_range_start = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_min_input', true);
                                        $cs_post_price_style = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_style', true);
                                        
										$price_max = $price_min = '';
										if($price_min_range_start == '')
                                            $price_min_range_start = 0;
                                        if($price_max_range_start == '')
                                            $price_max_range_start = 1000;
                                        
                                        $price_min_key = 'min_price';
                                        $price_max_key = 'max_price';
                                        
										if(isset($_GET[$price_min_key]) && $_GET[$price_min_key] <> ''){
                                            $price_min_range = $_GET[$price_min_key];
											$price_min	 	 = $_GET[$price_min_key];
                                        } else {
                                            $price_min_range = 1;
                                            $price_min_range = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_min_input', true);
                                        }
                                        
										if(isset($_GET[$price_max_key]) && $_GET[$price_max_key] <> ''){
                                            $price_max_range = $_GET[$price_max_key];
											$price_max 		 = $_GET[$price_max_key];
                                        } else {
                                            $price_max_range = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_max_input', true);
                                        }
                                        
										$price_incrstep_input = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_incr_input', true);
                                        
										if(isset($price_max_range) && $price_max_range == '')
                                            $price_max_range = '';
                                        if(isset($price_min_range) && $price_min_range == '')
                                            $price_min_range = '';
                                        if(isset($price_incrstep_input) && $price_incrstep_input == '')
                                            $price_incrstep_input = 1;
                                        $saleprice_label = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_input', true);
                                        
										echo '<li class="price-search">';
                                        echo '<div class="advance-search-price-range">';
                                        
                                        $html = '';
                                        $output = '';
                                        if(isset($cs_post_price_style) && $cs_post_price_style == 'Inputs'){
											if( isset( $cs_field_lables ) && $cs_field_lables =='Yes' ){
												$output .= '<h6>'.$saleprice_label.'</h6>';
											}
											$output .= '<ul>
															<li><input  id="min_price" name="min_price" type="text" placeholder="'.__('Min Price','directory').'" value="'.$price_min.'"></li>
															<li><input  id="max_price" name="max_price" type="text" placeholder="'.__('Max Price','directory').'" value="'.$price_max.'"></li>
														</ul>';
											$html .= $output;
											echo cs_allow_special_char($html);
                                        
										} else if(isset($cs_post_price_style) && $cs_post_price_style == 'Slider_Inputs'){
                                                $slidder_inputs = '';    
                                                $slidder_inputs .= '
                                                                <input id="min_price" name="min_price" type="text" value="'.$price_min_range.'">
                                                                <input  id="max_price" name="max_price" type="text" value="'.$price_max_range.'">';
                                                $output .= '<label>'.$saleprice_label.'</label>
                                                            <div class="input-sec">
                                                                <div id="slider-price-range"></div>
                                                            </div>
                                                            '.$slidder_inputs.'
                                                 			<script type="text/javascript">
                                                            jQuery(function() {
                                                                jQuery( "#slider-price-range" ).slider({
                                                                    orientation: "horizontal",
                                                                    range: true,
                                                                    min: '.$price_min_range_start.',
                                                                    max: '.$price_max_range_start.',
                                                                    step: '.$price_incrstep_input.',
                                                                    values: [ '.$price_min_range.', '.$price_max_range.' ],
                                                                    slide: function( event, ui ) {
                                                                        jQuery( "#min_price" ).val(ui.values[0]);
                                                                        jQuery( "#max_price" ).val(ui.values[1]);
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
                                            $html .= $output;
											echo cs_allow_special_char($html);
                                        
										} else if(isset($cs_post_price_style) && $cs_post_price_style == 'Slider'){
                                                  
											  if( isset( $cs_field_lables ) && $cs_field_lables =='Yes' ){
												echo '<h6>"'.__("Price Range","directory").'"</h6>';
											  }
											  
											  echo '
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
                                        echo '</div>';
                                        echo '</li>';
                                    }
                                }else{?>
                                    <li class="price-search">
                                    <div class="advance-search-price-range">
                                    
									<?php if( isset( $cs_field_lables ) && $cs_field_lables =='Yes' ){?>
                                    <h6><?php echo __('Price Range','directory');?></h6>
                                    <?php }?>
                                    
                                    <ul><li>
                                        <input  id="min_price" onblur="if(this.value == '') { this.value ='<?php _e('Min Price','directory'); ?>'; }" onfocus="if(this.value =='<?php _e('Min Price','directory'); ?>') { this.value = ''; }" class="search_min_price"  name="min_price" type="text" value="<?php _e('Min Price','directory'); ?>">
                                       
									   
									    </li>
                                        <li>
                                        <input  id="max_price" onblur="if(this.value == '') { this.value ='<?php _e('Max Price','directory'); ?>'; }" onfocus="if(this.value =='<?php _e('Max Price','directory'); ?>') { this.value = ''; }" class="search_max_price"  name="max_price" type="text" value="Max Price" />
                                    

									</li></ul>
                                    </div>
                                    </li>
                                <?php }
							}
                            ?>
                            <li class="submit-button">
                            	
								<input type="hidden" name="cs_directory_search_location" value="<?php echo esc_attr($cs_directory_search_location);?>" />
                                <input type="hidden" name="search_view" value="<?php echo esc_attr($cs_directory_search_views);?>" />
                                <input type="hidden" name="goe_location_enable" value="<?php echo esc_attr($goe_location_enable);?>" />
                                <input type="hidden" name="cs_loc_max_input" value="<?php echo absint($cs_loc_max_input);?>" />
                                <input type="hidden" name="cs_loc_incr_step" value="<?php echo absint($cs_loc_incr_step);?>" />
                                <button name="submit" id="directory-submit-search-view" class="form-submit" ><i class="icon-search6"></i><?php echo esc_attr( $seacrchBtnText );?></button>
                                <input type="hidden" name="action" value="cs_directory_map_search" />
                            </li>
                        </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
        <!--
        <script>
                    $("#edit-search-api-views-fulltext").keyup(function(event){
            if(event.keyCode == 13){
                $("#directory-submit-search-view").click();
            }
        });
                </script>-->
        
        <?php    
    }
    add_shortcode('cs_directory_search', 'cs_directory_search_shortcode');
}

//======================================================================
// Directory Location Search From Database
//======================================================================

if (!function_exists('cs_location_search')) {
    function cs_location_search(){
        global $post;
        $term = strtolower( $_GET['term'] );
        $suggestions = array();
        $args = array(
            'post_type' => 'directory',
            'relation' => 'AND',
            'meta_query' => array(
                array(
                        'key' => 'dynamic_post_location_address',
                        'value' => $term,
                        'compare' => 'LIKE'
                ),
             )
        );
        $loop = new WP_Query( $args );
		
        while( $loop->have_posts() ) {
            $loop->the_post();
            $suggestion = array();
            $location = get_post_meta($post->ID, "dynamic_post_location_address", true);
            if($location){
                $suggestion['label'] = $location;
                $suggestion['link'] = get_permalink();
                $suggestions[] = $suggestion;
            }
        }
        wp_reset_query();
        $response = json_encode( $suggestions );
        echo cs_allow_special_char($response);
        exit();
    }
    add_action( 'wp_ajax_cs_location_search', 'cs_location_search' );
    add_action( 'wp_ajax_nopriv_cs_location_search', 'cs_location_search' );
}

/**
* Directory Categories
*/
if (!function_exists('cs_directory_categories')) {
    function cs_directory_categories(){
        if(isset($_POST['directory_id'])){
            $args=array(
                'name' => $_POST['directory_id'],
                'post_type' => 'directory_types',
                'post_status' => 'publish',
                'posts_per_page' => 1
            );
            $dir_posts = get_posts( $args );
            if( $dir_posts ) {
                $directory_id = $dir_posts[0]->ID;
            }
            $directory_id = $_POST['directory_id'];
            if(isset($directory_id) && $directory_id <> ''){
                $directory_categories_array = get_post_meta($directory_id, "directory_types_categories", true);
                $directory_categories_array = explode(',', $directory_categories_array);
                
                if(!isset($directory_categories) || !is_array($directory_categories) || !count($directory_categories)>0){
                    $directory_categories = array();
                }
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
                ?>
                <select id="directory-categories" name="directory_categories">
                <option>-- <?php _e('Select Categories','directory');?>--</option>
                    <?php
                    foreach ($categories as $category) {
                        $selected = '';
                        if(in_array($category->term_id, $directory_categories_array)){
                            if(in_array($category->slug, $directory_categories)){
                                $selected = 'selected="selected"';
                            }
                            echo '<option value="'.$category->slug.'" '.$selected.'>' . $category->name . '</option>';
                        }
                    }
                    ?>
                </select>
                <?php
            }
        }
        die();
    }
    add_action('wp_ajax_cs_directory_categories', 'cs_directory_categories');
    add_action('wp_ajax_nopriv_cs_directory_categories', 'cs_directory_categories');
}

// Ajax map search Function
if ( ! function_exists( 'cs_directory_map_search' ) ) { 
    function cs_directory_map_search(){
        global $post, $cs_theme_options;
		$cs_map_lock = isset($cs_theme_options['cs_map_lock']) ? $cs_theme_options['cs_map_lock'] : '';
        if ( $_SERVER["REQUEST_METHOD"] == "POST"){
            $json_array = array();
            $json_array['type']    = "success";
            if(!isset($_REQUEST['page_id_all'])) $_REQUEST['page_id_all']=1;
                        $args = array(
                                    'post_type' => 'directory',
                                    'post_status' => 'publish',
                                    'paged' => $_REQUEST['page_id_all'],
                                    );
                        
                            if(isset($_POST['search_text'])){
                                $s = sanitize_text_field($_POST['search_text']);
                                $args['s'] = $s;
                            }
                            if(isset($_POST['search_view'])){
                                $cs_default_ad_search_view = sanitize_text_field($_POST['search_view']);
                            }
                            
                            if(isset($_POST['cs_directory_search_location'])){
                                $cs_directory_search_location = sanitize_text_field($_POST['cs_directory_search_location']);
                            }
                            
                            //$meta_fields_array = array('relation' => 'AND',);
							
							if(!class_exists('RadiusCheck')){
								require_once (get_template_directory()  . '/include/theme-components/cs-locationplugin/location_check.php');
							}
							
							if(isset($_POST['geo']) && $_POST['geo'] != 'on') {
								
							if( isset($cs_theme_options['cs_loc_max_input'])  && trim($cs_theme_options['cs_loc_max_input']) !=''  && trim($cs_theme_options['cs_loc_max_input']) != 0  ) {
								$Miles = $cs_theme_options['cs_loc_max_input'];
							} else {
								$Miles = 150;
							}
							
							$distance_km_miles = isset($cs_theme_options['distance_km_miles']) ? $cs_theme_options['distance_km_miles'] : 'Miles';
															
							if(isset($_POST['radius'])) {
								 $Miles = $_POST['radius']; 
							} else {
								 $Miles = $Miles;
							}
							
							if( $distance_km_miles == 'Km' ) {
								if(isset($_POST['radius'])) {
									 $Miles = $_POST['radius'] * 0.621371; 
								} else {
									 $Miles = $radius * 0.621371;
								}
							}
							
							$Latitude	= '';
							$Longitude	= '';
							$prepAddr	= '';
							$minLat		= '';
							$maxLat		= '';
							$minLong	= '';
							$maxLong	= '';
							if((isset($_POST['location']) && !empty($_POST['location']))){
				
								$address	= sanitize_text_field($_POST['location']);
								$prepAddr	= str_replace(' ','+',$address);
								$geocode	= file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
								$output		= json_decode($geocode);
								$Latitude	= $output->results[0]->geometry->location->lat;
								$Longitude	= $output->results[0]->geometry->location->lng;
								
								if(isset($Latitude) && $Latitude <> '' && isset($Longitude) && $Longitude <> ''){
									$zcdRadius = new RadiusCheck($Latitude,$Longitude,$Miles);
									$minLat  = $zcdRadius->MinLatitude();
									$maxLat  = $zcdRadius->MaxLatitude();
									$minLong = $zcdRadius->MinLongitude();
									$maxLong = $zcdRadius->MaxLongitude();
								}
							}
								
							$meta_fields_array = array(
													'relation' => 'AND',
													array(
														'relation' => 'OR',
														array(
															'key' 		=> 'dynamic_post_location_country',
															'value'   	=> str_replace('+','_',$prepAddr),
															'compare' 	=> 'LIKE',
														),
														array(
															'key' 		=> 'dynamic_post_location_region',
															'value'   	=> str_replace('+','_',$prepAddr),
															'compare' 	=> 'LIKE',
														),
														array(
															'key' 		=> 'dynamic_post_location_city',
															'value'   	=> str_replace('+','_',$prepAddr),
															'compare' 	=> 'LIKE',
														),
														array(
															'relation' => 'AND',
															array(
																'key' 		=> 'dynamic_post_location_latitude',
																'value'  	=> array($minLat, $maxLat),
																'compare' 	=> 'BETWEEN',
																'type'    	=> 'DECIMAL'
															),
															array(
																'key' 		=> 'dynamic_post_location_longitude',
																'value'   	=> array($minLong, $maxLong),
																'compare' 	=> 'BETWEEN',
																'type'    	=> 'DECIMAL'
															)
														)
													),
												);
							}
							else{
								 $meta_fields_array = array('relation' => 'AND');
							}
                            
                            if(isset($_POST['type']) && !empty($_POST['type'])){
                                $directory_types = $_POST['type'];
                                $directory_type_array = explode('||', $directory_types);
                                if(is_array($directory_type_array) && isset($directory_type_array['0']))
                                    $directory_type = $directory_type_array['0'];
                                if(is_array($directory_type_array) && isset($directory_type_array['1']))
                                    $directory_categories = $directory_type_array['1'];
                                if(isset($directory_type) && $directory_type <> ''){
                                    $dirargs=array(
                                        'name'                 => (string)$directory_type,
                                        'post_type'         => 'directory_types',
                                        'post_status'         => 'publish',
                                        'posts_per_page'    => 1
                                    );
                                    $dir_posts = get_posts( $dirargs );
                                    if( $dir_posts ) {
                                        $directory_id = (int)$dir_posts[0]->ID;
                                    }
                                }
                            } else {
                                $directory_id    = '';
                            }
                            
                            if(isset($directory_id) && $directory_id <> ''){
                                $meta_fields_array[] = array('key' => 'directory_type_select',
                                                                  'value'   => $directory_id,
                                                                  'compare' => '=',
                                                                  'type'	=> 'NUMERIC'
                                                            );
                            $min_price = $max_price = '';
                            
                            if(isset($_POST['min_price']) && $_POST['min_price'] !='Min Price' ){
                                $min_price = sanitize_text_field($_POST['min_price']);
                            }
                            
                            if(isset($_POST['max_price']) && $_POST['max_price'] !='Max Price' ){
                                $max_price = sanitize_text_field($_POST['max_price']);
                            }
                            
                            if($min_price <> '' && $max_price == ''){
                                $meta_fields_array[] = array('key' => 'dynamic_post_sale_newprice',
                                                                      'value'   => $min_price,
                                                                      'compare' => '>=',
                                                                      'type'     => 'NUMERIC'
                                                                );
                            } else if($min_price == '' && $max_price <> ''){
                                $meta_fields_array[] = array('key' => 'dynamic_post_sale_newprice',
                                                                      'value'   => $max_price,
                                                                      'compare' => '<=',
                                                                      'type'     => 'NUMERIC'
                                                                );
                            } else if($min_price <> '' && $max_price <> ''){
                                $meta_fields_array[] = array('key' => 'dynamic_post_sale_newprice',
                                                                      'value'   => array($min_price, $max_price),
                                                                      'compare' => 'BETWEEN',
                                                                      'type'     => 'NUMERIC'
                                                                );
                            }
                            
                            $cs_dcpt_custom_fields = get_post_meta($directory_id, "cs_directory_custom_fields", true);
                            if ( $cs_dcpt_custom_fields <> "" ) {
                                $cs_customfields_object = new SimpleXMLElement($cs_dcpt_custom_fields);
                                if(isset($cs_customfields_object->custom_fields_elements) && $cs_customfields_object->custom_fields_elements == '1'){
                                    if(count($cs_customfields_object)>1){
                                        global $cs_node;
                                        foreach ( $cs_customfields_object->children() as $cs_node ){
                                            if(isset($cs_node->cs_customfield_enable_search) && $cs_node->cs_customfield_enable_search == 'yes'){
                                                $key = (string)$cs_node->cs_customfield_name;
                                                if(isset($key) && isset($_POST[(string)$key]) && !empty($_POST[(string)$key]) && $_POST[(string)$key] <> '' && $cs_node->getName() <> 'range'){
                                                    $cs_value = $_POST[$key];
                                                } else if(isset($key) && $cs_node->getName() == 'range'){
                                                        $min_key = $key.'_min_range';
                                                        $max_key = $key.'_max_range';
                                                        if((isset($_POST[$min_key]) && $_POST[$min_key] <> '') || (isset($_POST[$max_key]) && $_POST[$max_key] <> '')){
                                                            if(isset($_POST[$min_key]) && $_POST[$min_key] <> ''){
                                                                $min_range = (int)$_POST[$min_key];
                                                            } else {
                                                                $min_range = 0;
                                                            }
                                                            if(isset($_POST[$max_key]) && $_POST[$max_key] <> ''){
                                                                $max_range = (int)$_POST[$max_key];
                                                            } else {
                                                                $max_range = 0;
                                                            }
                                                            
                                                        } else {
                                                            continue;    
                                                        }
                                                } else {
                                                    $cs_value = '';
                                                    continue;
                                                }
                                                switch( $cs_node->getName() )
                                                {
                                                    case 'text' :
                                                        $meta_fields_array[] = array('key' => (string)$key,
                                                              'value'   => $cs_value,
                                                              'compare' => 'like'
                                                        );
                                                        break;
                                                    case 'email' :
                                                        $meta_fields_array[] = array('key' => (string)$key,
                                                              'value'   => $cs_value,
                                                              'compare' => 'like'
                                                        );
                                                        break;
                                                    case 'url' :
                                                        $meta_fields_array[] = array('key' => (string)$key,
                                                              'value'   => $cs_value,
                                                              'compare' => 'like'
                                                        );
                                                        break;
                                                    case 'date' :
                                                        $meta_fields_array[] = array('key' => (string)$key,
                                                              'value'   => $cs_value,
                                                              'compare' => 'like'
                                                        );
                                                        break;
                                                    case 'multiselect' :
                                                        if ( isset( $key ) && $key !='' && isset($_POST[$key])){
                                                            $cs_value = $_POST[$key];
                                                            if(is_array($cs_value))
                                                                $cs_value = implode(',',$cs_value);
                                                        }
                                                        $meta_fields_array[] = array('key' => (string)$key,
                                                              'value'   => $cs_value,
                                                              'compare' => 'like'
                                                        );
                                                        break;
                                                    case 'textarea' :
                                                        $meta_fields_array[] = array('key' => (string)$key,
                                                              'value'   => $cs_value,
                                                              'compare' => 'like'
                                                        );
                                                        break;
                                                    case 'range' :
                                                        if($min_range <> '' && $max_range == ''){
                                                            $meta_fields_array[] = array('key' => (string)$key,
                                                                  'value'   => $min_range,
                                                                  'compare' => '>=',
                                                                  'type'     => 'NUMERIC'
                                                            );
                                                            
                                                        } else if($min_range == '' && $max_range <> ''){
                                                            $meta_fields_array[] = array('key' => (string)$key,
                                                                  'value'   => $max_range,
                                                                  'compare' => '<=',
                                                                  'type'     => 'NUMERIC'
                                                            );
                                                            
                                                        } else if($min_range <> '' && $max_range <> ''){
                                                    
                                                            $meta_fields_array[] = array('key' => (string)$key,
                                                                  'value'   => array($min_range, $max_range),
                                                                  'compare' => 'BETWEEN',
                                                                  'type'     => 'NUMERIC'
                                                            );
                                                        }
                                                        break;
                                                    case 'dropdown' :
                                                        if ( isset( $key ) && $key !='' && isset($_POST[$key])){
                                                            $cs_value = $_POST[$key];
                                                            if(isset($cs_value) && !is_array($cs_value))
                                                                $cs_value = explode(',',$cs_value);
                                                        }
                                                        // prepare
                                                        $meta_fields_array[] = array('key' => (string)$key,
                                                              'value'   => $cs_value,
                                                              'compare' => 'IN'
                                                        );
                                                        break;
                                                    default :
                                                        break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            }
                            
                            if((isset($_POST['location']) && !empty($_POST['location'])) || (isset($_POST['geo']) && $_POST['geo'] == 'on')){
                                
                                if(isset($_POST['geo']) && $_POST['geo'] == 'on'){
                                    if(isset($_POST['geo_location_lat']) && isset($_POST['geo_location_long']) && $_POST['geo_location_lat'] <> '' && $_POST['geo_location_long'] <> '') {
										$Latitude     = sanitize_text_field($_POST['geo_location_lat']);
				
										$Longitude  = sanitize_text_field($_POST['geo_location_long']);
									}
									else{
										$address	= sanitize_text_field($_POST['location']);
										$prepAddr	= str_replace(' ','+',$address);
										$geocode	= file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
										$output		= json_decode($geocode);
										$Latitude	= $output->results[0]->geometry->location->lat;
										$Longitude	= $output->results[0]->geometry->location->lng;
									}
                                } else {
                                    $address 	= sanitize_text_field($_POST['location']);
                                    $prepAddr   = str_replace(' ','+',$address);
                                    $geocode    = file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
                                    $output     = json_decode($geocode);
                                    $Latitude   = $output->results[0]->geometry->location->lat;
                                    $Longitude  = $output->results[0]->geometry->location->lng;
                                }
                                
								if( isset($cs_theme_options['cs_loc_max_input'])  && trim($cs_theme_options['cs_loc_max_input']) !=''  && trim($cs_theme_options['cs_loc_max_input']) != 0  ) {
									$Miles = $cs_theme_options['cs_loc_max_input'];
								} else {
									$Miles = 150;
								}
								
								$distance_km_miles = isset($cs_theme_options['distance_km_miles']) ? $cs_theme_options['distance_km_miles'] : 'Miles';
																
								if(isset($_POST['radius'])) {
									 $Miles = $_POST['radius']; 
								} else {
									 $Miles = $Miles;
								}
								
								if( $distance_km_miles == 'Km' ) {
									if(isset($_POST['radius'])) {
										 $Miles = $_POST['radius'] * 0.621371; 
									} else {
										 $Miles = $radius * 0.621371;
									}
								}
								
								if(isset($_POST['geo']) && $_POST['geo'] == 'on') {

									if(isset($Latitude) && $Latitude <> '' && isset($Longitude) && $Longitude <> ''){
										
										$zcdRadius = new RadiusCheck($Latitude,$Longitude,$Miles);
										$minLat  = $zcdRadius->MinLatitude();
										$maxLat  = $zcdRadius->MaxLatitude();
										$minLong = $zcdRadius->MinLongitude();
										$maxLong = $zcdRadius->MaxLongitude();
										$meta_fields_array[] = array('key' => 'dynamic_post_location_latitude',
																		  'value'   => array($minLat, $maxLat),
																		  'compare' => 'BETWEEN',
																		  'type'     => 'DECIMAL'
																	);
										$meta_fields_array[] = array('key' => 'dynamic_post_location_longitude',
																		  'value'   => array($minLong, $maxLong),
																		  'compare' => 'BETWEEN',
																		  'type'     => 'DECIMAL'
																	);
									}
								}
                            }
                          
						  if(isset($_POST['filter']) && $_POST['filter'] == 'paid'){
                                $meta_fields_array[] = array('key' => 'dir_pkg_expire_date', 'value'  => date("Y-m-d H:i:s"), 'compare' => '>=', 'type' => 'NUMERIC');
                                $args['meta_key'] = 'dir_pkg_expire_date';
                                $args['orderby'] = 'meta_value';
                                $args['order'] = 'DESC';
                            } else if(isset($_POST['filter']) && $_POST['filter'] == 'free'){
                                $meta_fields_array[] = array('key' => 'dir_pkg_expire_date', 'value'  => date("Y-m-d H:i:s"), 'compare' => '<', 'type' => 'NUMERIC');
                                $args['orderby'] = 'meta_value';
                                $args['order'] = 'ASC';
                            }
                            if(is_array($meta_fields_array) && count($meta_fields_array)>1){
                                $args['meta_query'] = $meta_fields_array;
                            }
                            if(isset($_POST['directory_categories']) && count($_POST['directory_categories'])>0){
                                $directory_categories = $_POST['directory_categories'];
                            }
                            if(isset($directory_categories)){
                                 $taxquery = array(
                                    array(
                                        'taxonomy' => 'directory-category',
                                        'field' => 'slug',
                                        'terms' => $directory_categories
                                    )
                                );
                                $args['tax_query'] = $taxquery;
                            }
							
                        if(!isset($posts_per_page)){
                            $posts_per_page = get_option('posts_per_page');
                        }

                        $args['posts_per_page'] = -1;
                        $custom_query = new WP_Query($args);
                        $count_post = $custom_query->post_count;
                        $directory_array = array();
                        $directories = array();
                        $latitude  = isset( $cs_theme_options['map_latitude'] ) && $cs_theme_options['map_latitude'] ? $cs_theme_options['map_latitude'] : '51.58218919999999';
                        $longitude = isset( $cs_theme_options['map_longitude'] ) && $cs_theme_options['map_longitude'] ? $cs_theme_options['map_longitude'] : '-0.08428670000000693';
                        if(isset($cs_theme_options['cluster_map_marker']))
                            $map_marker_url    = $cs_theme_options['cluster_map_marker'];
                        else
                            $map_marker_url    = get_template_directory_uri()  .'/assets/images/img-txtfld.png';
                        if(isset($cs_theme_options['product_map_marker']))
                            $product_map_marker    = $cs_theme_options['product_map_marker'];
                        else
                            $product_map_marker    = get_template_directory_uri()  .'/assets/images/map-marker.png';
                        if(isset($cs_theme_options['cs_map_type']) && $cs_theme_options['cs_map_type'] <> '')
                            $cs_map_type    = $cs_theme_options['cs_map_type'];
                        else
                            $cs_map_type = 'ROADMAP';
                        if(isset($cs_theme_options['cluster_map_marker_color']) && $cs_theme_options['cluster_map_marker_color'] <> '')
                            $cs_cluster_marker_color_input    = $cs_theme_options['cluster_map_marker_color'];
                        else
                            $cs_cluster_marker_color_input = '#000';
                        if(isset($cs_theme_options['map_zoom']) && $cs_theme_options['map_zoom'] <> '')
                            $map_zoom    = (int)$cs_theme_options['map_zoom'];
                        else
                            $map_zoom = 2;
                        
						// Cluster Dynamic Height & Width
						$url   = $map_marker_url;
						$raw   = cs_open_image($url);
						$im    = imagecreatefromstring($raw);
						$cs_marker_width  = imagesx($im);
						$cs_marker_height = imagesy($im);


                        if ( $custom_query->have_posts() ) : 
                            $width  = '370';
                            $height = '280';
                            $cs_page_id    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
                            $total_post = $custom_query->post_count;
                            
                            $directory_array = array('count'=>$total_post);
                            while ( $custom_query->have_posts() ) : $custom_query->the_post();
                                $organizerID = get_post_meta( $post->ID, 'directory_organizer', true );
                                $latitude = get_post_meta( $post->ID, 'dynamic_post_location_latitude', true );
                                $longitude = get_post_meta( $post->ID, 'dynamic_post_location_longitude', true );
                                $direcotry_type_id = get_post_meta( $post->ID, 'directory_type_select', true );
                                $cs_directory_featured = get_post_meta($post->ID, "directory_featured", true);
                                $location = get_post_meta($post->ID, "dynamic_post_location_address", true);
                            
                                if( isset( $cs_directory_featured ) && $cs_directory_featured == 'yes' ) {
                                    $cs_directory_featured    = '<li><span>'.__("URGENT","directory").':'.'</span></li>';
                                } else{
                                    $cs_directory_featured    = '';
                                }
								
								$is_featured_text	   =  cs_map_featured_label($post->ID);
                                
                                $image_url = get_post_meta( $post->ID, '_directory_image_gallery', true );
                                $image_url = array_filter( explode( ',', $image_url ) );
                                if ( isset( $image_url ) && ! empty( $image_url ) ) {
                                    $image_url         = cs_attachment_image_src( $image_url[0] ,$width,$height); 
                                } else {
                                    $image_url    = '';
                                }
                  
                                $dir_payment_date = get_post_meta( $post->ID, "dir_payment_date", true ); 
                                if($dir_payment_date == '') {
                                    $dir_payment_date = get_the_date();
                                } 
                                
                                $price = '';
                                $currency_sign             = isset($cs_theme_options['currency_sign'])?$cs_theme_options['currency_sign']:'$';
                                $dynamic_post_sale_oldprice = get_post_meta($post->ID, "dynamic_post_sale_oldprice", true);
                                $dynamic_post_sale_newprice = get_post_meta($post->ID, "dynamic_post_sale_newprice", true);
                                if ( ( isset( $dynamic_post_sale_oldprice ) && $dynamic_post_sale_oldprice !='' ) || isset( $dynamic_post_sale_newprice ) && $dynamic_post_sale_newprice !=''  ) {
                                    $price    = '';
                                    if(isset( $dynamic_post_sale_oldprice ) && $dynamic_post_sale_oldprice !=''){
                                        $price    .= '<small>'.esc_attr( $currency_sign.number_format(absint($dynamic_post_sale_oldprice))).'</small> ';
                                    }
                                    if(isset( $dynamic_post_sale_newprice ) && $dynamic_post_sale_newprice !=''){
                                        $price    .= esc_attr( $currency_sign.number_format(absint($dynamic_post_sale_newprice)));
                                    }
                                } else{
                                    $price    = '';
                                }
 
                                 $cs_cluster_marker_color_input = get_post_meta( $direcotry_type_id, 'cs_cluster_marker_color_input', true );
                                 
                                 if(isset($cs_theme_options['cluster_map_marker_color']) && $cs_theme_options['cluster_map_marker_color'] <> '')
                                        $cs_cluster_marker_color_input    = $cs_theme_options['cluster_map_marker_color'];
                                    else
                                        $cs_cluster_marker_color_input = '#000';
                                        
                                  $map_thumb_url = get_post_meta( $direcotry_type_id, 'cs_destination_url_input', true );
                                  if($map_thumb_url == '' || empty($map_thumb_url)){
                                    $map_thumb_url = $product_map_marker;
                                  }
                                  $user_profile_url = cs_user_profile_link($cs_page_id, 'dashboard', $organizerID);
                                  $directories[] = array(
                                                    'post_id' => $post->ID,
                                                    'post_title' => get_the_title(),
                                                    'image_url' => $image_url,
                                                    'permalink' => addslashes(get_permalink()),
                                                    'longitude' => $longitude,
                                                    'latitude' => $latitude,
                                                    'mapamrker' => $map_thumb_url,
                                                    'width' => $width,
                                                    'height' => $height,
                                                    'publish_date' => esc_attr( date_i18n( get_option( 'date_format' ),strtotime( get_the_date() ) ) ),
                                                    'user_id' => absint($organizerID),
                                                    'user_name' => get_the_author_meta('display_name',$organizerID ),
                                                    'user_profile_url' => $user_profile_url,
                                                    'featured'           => $cs_directory_featured,
													'featured_text'      => $is_featured_text,
                                                    'date'               => esc_attr( date_i18n( get_option( 'date_format' ),strtotime( $dir_payment_date ) ) ),
                                                    'location'           => $location,
                                                    'price'               => $price,
                                                );
                                endwhile;
                                wp_reset_postdata();
                            endif;
                            $directory_array['posts'] = $directories;
                            $json_array['data'] = $directory_array;
                            
							if(isset($_POST['geo']) && $_POST['geo'] == 'on'){
                                if(isset($_POST['geo_location_lat']))
                                    $Latitude = sanitize_text_field($_POST['geo_location_lat']);
                                if(isset($_POST['geo_location_long']))
                                    $Longitude = sanitize_text_field($_POST['geo_location_long']);
                            } else if(isset($_POST['location']) && $_POST['location'] <> '') {
                                $address = sanitize_text_field($_POST['location']);
                                $prepAddr = str_replace(' ','+',$address);
                                $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
                                $output = json_decode($geocode);
                                $Latitude = $output->results[0]->geometry->location->lat;
                                $Longitude = $output->results[0]->geometry->location->lng;
                            } else {
                                if(isset($latitude))
                                    $Latitude = $latitude;
                                if(isset($longitude))
                                    $Longitude = $longitude;
                            }
							
                            $json_array['Latitude'] = $Latitude;
                            $json_array['Longitude'] = $Longitude;
                            $json_array['marker_url'] = $map_marker_url;
                            $json_array['marker_color'] = $cs_cluster_marker_color_input;
                            $json_array['map_type'] = $cs_map_type;
                            $json_array['map_zoom'] = absint($map_zoom);
							$json_array['cs_svg_marker'] = wp_directory::plugin_url().'assets/images/orange-marker.svg';
							$json_array['cs_map_lock'] = $cs_map_lock;
							
							/*echo '<pre>';
							var_dump($args);
							echo '</pre>';*/
							
            echo json_encode( $json_array );
            exit;
        }
        exit;
    }
    add_action('wp_ajax_cs_directory_map_search', 'cs_directory_map_search');
    add_action('wp_ajax_nopriv_cs_directory_map_search', 'cs_directory_map_search');
}

// Render Dynamic Post Custom Fields currently not in use
if ( ! function_exists( 'cs_ajax_advance_search' ) ) { 
    function cs_ajax_advance_search(){
        global $post;
        if ( $_SERVER["REQUEST_METHOD"] == "POST"){
            if( !isset( $_POST['term_condtions_check'] ) ) {
                $json['type']    = "error";
                $json['message'] = 'Please select Term & Conditions.';
                echo json_encode( $json );
                exit;
            }
            echo json_encode( $json );
            exit;
        }
        exit;
    }
    add_action('wp_ajax_cs_ajax_advance_search', 'cs_ajax_advance_search');
    add_action('wp_ajax_nopriv_cs_ajax_advance_search', 'cs_ajax_advance_search');
}
// Sidebar Categories. Custom Fields, Price Fields by Ajax Call on change directory type
if ( ! function_exists( 'cs_directory_type_categories_sidebar_search' ) ) { 
    function cs_directory_type_categories_sidebar_search(){
        global $post, $cs_theme_options;
        if ( $_SERVER["REQUEST_METHOD"] == "POST"){
            $currency_sign = $cs_theme_options['currency_sign'];
            if( !isset( $_POST['directory_id'] ) || $_POST['directory_id'] == ''  ) {
                $json['type']    = "error";
                $json['message'] = 'Please select Ad';
                echo json_encode( $json );
                exit;
            } else if( !isset( $_POST['directory_id'] ) || $_POST['directory_id'] == 'all'  ) { 
                $json['type']                = "empty";
                $json['message']             = '';
                $json['custom_fields']         = '';
                $json['price_fields']         =  '';
                $json['custom_categories']  = '';
                echo json_encode( $json );
                exit;
            } else {
                $json['type']    = "success";
                if(isset($_POST['directory_id']))
                    $directory_types = $_POST['directory_id'];
                if(isset($_POST['cat_type']))
                    $cat_type = $_POST['cat_type'];
                if(isset($directory_types) && $directory_types <> ''){
                    $directory_type_array = explode('||', $directory_types);
                    if(is_array($directory_type_array) && isset($directory_type_array['0']))
                        $directory_type = $directory_type_array['0'];
                    
                    $args    = array(
                                    'name' => (string)$directory_type,
                                    'post_type' => 'directory_types',
                                    'post_status' => 'publish',
                                    'posts_per_page' => 1
                                );
                    $dir_posts = get_posts( $args );
                    if( $dir_posts ) {
                        $directory_id = $dir_posts[0]->ID;
                    }
                    
                    if(isset($directory_id) && $directory_id <> ''){
                        $custom_fields = '';
                        $cs_dcpt_custom_fields = get_post_meta($directory_id, "cs_directory_custom_fields", true);
                        if ( $cs_dcpt_custom_fields <> "" ) {
                            $custom_fields .= '<ul class="dr_userinfo">';
                            $cs_customfields_object = new SimpleXMLElement($cs_dcpt_custom_fields);
                            if(isset($cs_customfields_object->custom_fields_elements) && $cs_customfields_object->custom_fields_elements == '1'){
                                if(count($cs_customfields_object)>1){
                                    global $cs_node;
                                    foreach ( $cs_customfields_object->children() as $cs_node ){
                                        if(isset($cs_node->cs_customfield_enable_search) && $cs_node->cs_customfield_enable_search == 'yes'){
                                            $custom_fields .= cs_custom_search_fields_render('','',$directory_id);
                                        }
                                    }
                                }
                            }
                            //echo balanceTags($custom_fields, false);
                            $custom_fields .= '</ul>';
                            $json['custom_fields'] = $custom_fields;
                        }
                    }
                    $price_fields = '';
                    if(isset($directory_id) && $directory_id <> ''){
                        $saleprice_option = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_option', true);
                        $price_enable_search = get_post_meta((int)$directory_id, 'cs_post_price_enable_search', true);
                        if(isset($saleprice_option) && $saleprice_option == 'on' && isset($price_enable_search) && $price_enable_search == 'on'){
                            $price_max_range_start = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_max_input', true);
                            $price_min_range_start = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_min_input', true);
                            $cs_post_price_style = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_style', true);
                            if($price_min_range_start == '')
                                $price_min_range_start = 0;
                            if($price_max_range_start == '')
                                $price_max_range_start = 1000;
                            $price_min_key = 'min_price';
                            $price_max_key = 'max_price';
                            if(isset($_GET[$price_min_key]) && $_GET[$price_min_key] <> ''){
                                $price_min_range = $_GET[$price_min_key];
                            } else {
                                $price_min_range = 1;
                                $price_min_range = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_min_input', true);
                            }
                            if(isset($_GET[$price_max_key]) && $_GET[$price_max_key] <> ''){
                                $price_max_range = $_GET[$price_max_key];
                            } else {
                                $price_max_range = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_max_input', true);
                            }
                            $price_incrstep_input = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_incr_input', true);
                            if(isset($price_max_range) && $price_max_range == '')
                                $price_max_range = '';
                            if(isset($price_min_range) && $price_min_range == '')
                                $price_min_range = '';
                            if(isset($price_incrstep_input) && $price_incrstep_input == '')
                                $price_incrstep_input = 1;
                            $saleprice_label = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_input', true);
                            $price_fields .= '<div class="advance-search-price-range">';
                            $html = '';
                            $output = '';
                            if(isset($cs_post_price_style) && $cs_post_price_style == 'Inputs'){
                                    $output .= '<label>'.$saleprice_label.'</label><ul><li>
                                                        <input  id="min_price" name="min_price" type="text" value="'.$price_min_range.'">
                                                </li>';
                                    $output .= '<li>
                                                        <input  id="max_price" name="max_price"" type="text" value="'.$price_max_range.'">
                                                </li></ul>';
                                        $html             .= $output;
                                        $price_fields   .= $html;
                            } 
                            else if(isset($cs_post_price_style) && $cs_post_price_style == 'Slider_Inputs'){
                                $slidder_inputs = '';    
                                    $slidder_inputs .= '<ul>
                                                    <li><input id="min_price" name="min_price" type="text" value="'.$price_min_range.'"></li>
                                                    <li><input  id="max_price" name="max_price" type="text" value="'.$price_max_range.'"></li>
                                                    </ul>
                                                ';
                                $output .= '
                                                <label>'.$saleprice_label.'</label>
                                                <div class="input-sec">
                                                    <div id="slider-price-range"></div>
                                                </div>
                                                '.$slidder_inputs.'
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
                                    </script>
                                    ';
                                $html .= $output;
                                $price_fields .= $html;
                            } 
                            else if(isset($cs_post_price_style) && $cs_post_price_style == 'Slider'){
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
                                        </script>
                                        ';
                            }
                            $price_fields .= '</div>';
                        }
                    }
                    $json['price_fields'] = $price_fields;
                    if(isset($directory_id)){
                        $directory_categories_array = $directory_categories_string = get_post_meta($directory_id, "directory_types_categories", true);
                        $directory_categories_array = explode(',', $directory_categories_array);
                        if(!isset($directory_categories) || !is_array($directory_categories) || !count($directory_categories)>0){
                            $directory_categories = array();
                        }
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
                        $directory_categories = '';
                        if(is_array($directory_type_array) && !isset($directory_type_array['1'])){
                            if(isset($cat_type) && $cat_type == 'shortcode'){
                                if(count($categories)>0){
                                    $directory_categories .= '<label>'.__('Categories', 'directory').'</label><ul class="cs-checkbox checked-category">';
                                    foreach ($categories as $category) {
                                        $selected = '';
                                        if(in_array($category->slug, $directory_categories_array)){
                                             $directory_categories .= '<li>
                                                     <input type="checkbox" id="'.$category->slug.'"  class="directory-categories-checkbox" name="directory_categories"   value="'.$category->slug.'">
                                                     <label for="'.$category->slug.'">' . $category->name . '</label>
                                                    <span class="totalpost">' . $category->category_count . '</span>
                                                   </li>';
                                        }
                                    }
                                    $directory_categories .= '</ul>';
                                }
                            } else {
                                if(count($categories)>0 && count($directory_categories_array)>0){
                                    $directory_categories .= $directory_categories_string.'<select class="ad_cat_multislect" id="directory-categories" name="directory_categories[]" multiple="multiple">';
                                                                foreach ($categories as $category) {
                                                                    $selected = '';
                                                                    if(in_array($category->term_id, $directory_categories_array)){
                                                                        $directory_categories .= '<option value="'.$category->slug.'" >' . $category->name . '</option>';
                                                                    }
                                                                }
                                    $directory_categories .= '</select>';
                                    $directory_categories .= '<script type="text/javascript">
                                                                    window.asd = jQuery(".ad_cat_multislect").SumoSelect({ okCancelInMulti:false });
                                                            </script>';
                                }
                            }
                        }
                        $json['custom_categories'] = $directory_categories;
                    }
                } else {
                    $json['type']    = "error";
                    $json['message'] = 'Please select Ad';
                    echo json_encode( $json );
                    exit;    
                }
            }
            echo json_encode( $json );
            exit;
        }
        exit;
    }
    add_action('wp_ajax_cs_directory_type_categories_sidebar_search', 'cs_directory_type_categories_sidebar_search');
    add_action('wp_ajax_nopriv_cs_directory_type_categories_sidebar_search', 'cs_directory_type_categories_sidebar_search');
}
// Ajax Price Fields on change directory type
if ( ! function_exists( 'cs_directory_type_price_search' ) ) { 
    function cs_directory_type_price_search(){
        global $post, $cs_theme_options;
        if ( $_SERVER["REQUEST_METHOD"] == "POST"){
            $currency_sign = $cs_theme_options['currency_sign'];
            if( !isset( $_POST['directory_id'] ) || $_POST['directory_id'] == ''  ) {
                $json['type']    = "error";
                $json['message'] = 'Please select Ad';
                echo json_encode( $json );
                exit;
            } else {
                $json['type']    = "success";
                $directory_types = $_POST['directory_id'];
                $directory_type_array = explode('||', $directory_types);
                if(is_array($directory_type_array) && isset($directory_type_array['0']))
                    $directory_type = $directory_type_array['0'];
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
                    $price_fields = '';
                    if(isset($directory_id) && $directory_id <> ''){
                        $saleprice_option = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_option', true);
                        $price_enable_search = get_post_meta((int)$directory_id, 'cs_post_price_enable_search', true);
                        if(isset($saleprice_option) && $saleprice_option == 'on' && isset($price_enable_search) && $price_enable_search == 'on'){
                            $price_max_range_start = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_max_input', true);
                            $price_min_range_start = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_min_input', true);
                            $cs_post_price_style = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_style', true);
                            if($price_min_range_start == '')
                                $price_min_range_start = 0;
                            if($price_max_range_start == '')
                                $price_max_range_start = 1000;
                            $price_min_key = 'min_price';
                            $price_max_key = 'max_price';
                            if(isset($_GET[$price_min_key]) && $_GET[$price_min_key] <> ''){
                                $price_min_range = $_GET[$price_min_key];
                            } else {
                                $price_min_range = 1;
                                $price_min_range = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_min_input', true);
                            }
                            if(isset($_GET[$price_max_key]) && $_GET[$price_max_key] <> ''){
                                $price_max_range = $_GET[$price_max_key];
                            } else {
                                $price_max_range = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_max_input', true);
                            }
                            $price_incrstep_input = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_incr_input', true);
                            if(isset($price_max_range) && $price_max_range == '')
                                $price_max_range = '';
                            if(isset($price_min_range) && $price_min_range == '')
                                $price_min_range = '';
                            if(isset($price_incrstep_input) && $price_incrstep_input == '')
                                $price_incrstep_input = 1;
                            $saleprice_label = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_input', true);
                            $price_fields .= '<div class="advance-search-price-range">';
                            $html = '';
                            $output = '';
                            
                            if(isset($cs_post_price_style) && $cs_post_price_style == 'Inputs'){
                                    $output .= '<h6>'.$saleprice_label.'</h6>';
                                    $output .= '<ul><li>
                                                        <input  id="min_price" name="min_price" placeholder="'.__('Min Price','directory').'" type="text" value="'.$price_min_range.'">
                                                </li>';
                                    $output .= '<li>
                                                        <input  id="max_price" name="max_price" placeholder="'.__('Max Price','directory').'" type="text" value="'.$price_max_range.'">
                                                </li></ul>';
                                        $html .= $output;
                                        $price_fields .= $html;
                            } 
                            else if(isset($cs_post_price_style) && $cs_post_price_style == 'Slider_Inputs'){
                                        
                                    $slidder_inputs = '';    
                                    $slidder_inputs .= '
                                                    <input id="min_price" name="min_price" placeholder="'.__('Min Price','directory').'" type="text" value="'.$price_min_range.'">
                                                    <input  id="max_price" name="max_price" placeholder="'.__('Max Price','directory').'" type="text" value="'.$price_max_range.'">';
                                $output .= '
                                                <h6>'.$saleprice_label.'</h6>
                                                <div class="input-sec">
                                                    <div id="slider-price-range"></div>
                                                </div>
                                                '.$slidder_inputs.'
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
                                    </script>
                                    ';
                                $html .= $output;
                                $price_fields .= $html;
                            } 
                            else if(isset($cs_post_price_style) && $cs_post_price_style == 'Slider'){
                                    $price_fields .= '
                                        <h6>'.$saleprice_label.'</h6>
                                        <input id="min_price" placeholder="'.__('Min Price','directory').'" name="'.__("min_price","directory").'" type="hidden" value="'.$price_min_range.'">
                                        <input  id="max_price" placeholder="Max Price" name="max_price"  type="hidden" value="'.$price_max_range.'">
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
                                        </script>
                                        ';
                            }
                            $price_fields .= '</div>';
                        }
                    }
                    $json['price_fields'] = $price_fields;
                } else {
                    $json['type']    = "error";
                    $json['message'] = 'Please select Ad';
                    echo json_encode( $json );
                    exit;    
                }
            }
            echo json_encode( $json );
            exit;
        }
        exit;
    }
    add_action('wp_ajax_cs_directory_type_price_search', 'cs_directory_type_price_search');
    add_action('wp_ajax_nopriv_cs_directory_type_price_search', 'cs_directory_type_price_search');
}

function cs_open_image($url){
    $headers = array(
    "Range: bytes=0-32768"
    );

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}
