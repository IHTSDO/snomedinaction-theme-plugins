<?php
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


//======================================================================
// Directory Categories
//======================================================================
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

//======================================================================
// Sidebar Search Fields on search page
//======================================================================
if ( ! function_exists( 'cs_directroy_search_fields' ) ) {
    function cs_directroy_search_fields($posts_per_page='', $cs_default_ad_search_view='', $cs_directory_search_location='', $cs_directory_search_result_page=''){
        global $post, $cs_theme_options;
        $cs_directory_search_location             = $cs_theme_options['cs_directory_search_location'];
        $goe_location_enable                     = $cs_theme_options['goe_location_enable'];
        $cs_loc_max_input                         = $cs_theme_options['cs_loc_max_input'];
        $cs_loc_incr_step                         = $cs_theme_options['cs_loc_incr_step'];
        //$cs_directory_search_result_page        = $cs_theme_options['cs_directory_search_result_page'];
        
        if(isset($_GET['location'])){
            $search_location = $_GET['location'];
        }
		
		if(isset($_GET['type']) && $_GET['type'] <> ''){
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
             $directory_id = isset($cs_theme_options['cs_default_ad_type']) ? $cs_theme_options['cs_default_ad_type'] : '';
            $directory_type = cs_get_the_slug($directory_id);    
        }
        $currency_sign = $cs_theme_options['currency_sign'];
        $search_val = '';
        if(isset($_GET['page']) && $_GET['page'] <> ''){
	
            $cs_directory_search_result_page = $_GET['page'];
            $action_page = get_permalink((int)$cs_directory_search_result_page);    
            $search_text = 'search_text';
            if(isset($_GET['search_text']) && $_GET['search_text'] <> '') $search_val = $_GET['search_text'];
        } else {
            $action_page = home_url();
            $search_text = 's';
            if(isset($_GET['s']) && $_GET['s'] <> '') $search_val = $_GET['s'];
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
        ?>
        <script>
            jQuery(document).ready(function($) {
                //jQuery('select.form-select').SumoSelect();
                window.asd = jQuery('select.form-select').SumoSelect();
            });
        </script>
        <div class="element-size-25">
            <div class="col-md-12">
                <form id="directory-advance-search-form" method="get" action="<?php echo esc_url($action_page);?>" role="search" enctype="multipart/form-data">
                    <input type="hidden" name="filter" value="<?php echo esc_attr($cs_directory_search_filter);?>" />
                    <div class="dr-filters directory-advanced-search-content sidebar-search">
                        <ul>
                            <li>
                                <label><?php _e('Search for', 'directory');?></label>
                                <div class="dr-search">
                                        <input type="text" class="form-text" maxlength="128" size="30" value="<?php echo esc_attr($search_val);?>" name="<?php echo esc_attr($search_text);?>" id="edit-search-api-views-fulltext" placeholder="<?php _e('Enter keyword...', 'directory');?>">
                                        <label><input class="cs-bgcolor" type="submit" value=""  name="Submit"></label>
                                </div>
                            </li>
                            <?php
                                // Directory Type
                                $args = array(
                                    'posts_per_page'            => "-1",
                                    'post_type'                    => 'directory_types',
                                    'post_status'                => 'publish',
                                    'orderby'                    => 'ID',
                                    'order'                        => 'ASC',
                                );
                                $custom_query = new WP_Query($args);
                                if ( $custom_query->have_posts() <> "" ) {
                                        ?>
                                        <li>
                                            <span class="cat-loading-fields"></span>
                                            <label><?php _e('Ad Type','directory');?></label>
                                            <select class="form-select dir-map-search" name="type" id="directory-field-category" onchange="cs_directory_type_categories_sidebar_search(this.value, '<?php echo esc_js(admin_url('admin-ajax.php'));?>', 'sidebar')">
                                                <option value=""><?php _e('Select Ad Type','directory');?></option>
                                                <?php
                                                     while ( $custom_query->have_posts() ): $custom_query->the_post();
                                                         $selected = '';
                                                         if(isset($directory_type) && $directory_type == $post->post_name){
                                                            $selected = 'selected'; 
                                                         }
                                                         echo '<option value="'.$post->post_name.'" '.$selected.'>'.get_the_title().'</option>';
                                                         endwhile;
                                                    ?>
                                                </select>
                                                 <script type="text/javascript">
                                                    jQuery(document).ready(function ($) {
                                                        window.asd = $('.ad_cat_multislect').SumoSelect({ okCancelInMulti:true });
                                                    });
                                                </script>
                                            </li>
                                             <!--<li>
                                            <div class="directory-type-categories-load">
                                            <label><?php _e('Categories','directory');?></label>
                                            <div class="advance-search-custom-fields">
                                                <ul><li>
                                                <select class="form-select ad_cat_multislect  dir-map-search" name="directory_categories[]" id="directory-field-category" multiple="multiple">
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
                                                                 $directory_type_slug = $_GET['directory_categories'];
                                                             } else {
                                                                $directory_type_slug = array();
                                                                $directory_type_slug[] = $directory_type_slug; 
                                                             }
															 
                                                             wp_directory::cs_multipleselect_scripts();
                                                             $directory_categories_array = get_post_meta((int)$directory_id, "directory_types_categories", true);
                                                             $directory_categories_array = explode(',', $directory_categories_array);
                                                             foreach ($categories as $category) {
                                                                $selected = '';
                                                                if(in_array($category->slug, $directory_categories_array)){
                                                                    
                                                                    if(isset($directory_type_slug) && in_array($category->slug, $directory_type_slug)){
                                                                        $selected = 'selected="selected"';
                                                                    }
                                                                    echo '<option value="'.$category->slug.'" '.$selected.'>' . $category->name . '</option>';
                                                                }
                                                             }
                                                         }
                                                    ?>
                                                </select>
                                                </li></ul>
                                            </div>
                                                </div>
                                                </li>-->
                                                
                                                <?php
                                            }
                                // Locations            
                                if(isset( $cs_directory_search_location ) && $cs_directory_search_location == 'Yes'){
									
                                    if( isset( $cs_directory_location_suggestions ) && $cs_directory_location_suggestions == 'Google' ) {
										$geo_location = 'on'; 
										wp_directory::cs_autocomplete_scripts();
									}else {
										$geo_location = 'off';
									}
                                    ?>
                                    <li class="location_field">
                                        <label><?php _e('Select your Location','directory');?></label>
                                            <?php 
											$cs_directory_location_suggestions = isset($cs_theme_options['cs_directory_location_suggestions']) ? $cs_theme_options['cs_directory_location_suggestions'] : '';
                                            if(isset($cs_directory_location_suggestions) && $cs_directory_location_suggestions == 'google'){
                                            ?>    
                                            <input type="search" value="<?php if(isset($search_location)) echo urldecode($search_location);?>" autocomplete="on" id="directory-search-location"  class="selectpicker show-tick form-control" title="<?php _e('Location', 'directory');?>" placeholder="Postcode or location" name="location">
                                                <?php
                                                if(isset($_GET['goe_location_enable']) && $_GET['goe_location_enable'] == 'Yes'){
                                                ?>
                                                <div class="sidelocation"><i class="icon-location6"  onclick="getLocation()"></i></div>
                                                <?php
                                                }
                                                ?>
                                            <?php
                                            } else {
                                                ?>
                                                <select class="form-select dir-map-search single-select SlectBox" id="directory-search-location" title="<?php _e('Location', 'directory');?>" placeholder="Postcode or location" name="location">
                                                    <option value=""><?php _e('Select Locations', 'directory');?></option>
                                                    <?php
														global $wpdb;
														$metakey = 'dynamic_post_location_city';
														$results = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = %s ORDER BY meta_value ASC", $metakey ) );
														if($results){
															foreach ($results as $result){
																if($result && trim($result) <> ''){
																	//$subcount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s", $metakey, $result ) );
																	$loc_selected = '';
																	if(isset($search_location) && $search_location == $result) $loc_selected = 'selected="selected"';
																	echo "<option value='$result' ".$loc_selected.">$result</option>";
																}
															}
														}
                                                    ?>
                                                </select>
                                                <?php
                                            }
                                            ?>
                                            <input id="geo_loc_option" name="geo" type="hidden" value="<?php echo esc_attr($geo_location);?>">
                                            <div id="geo_location_address">
                                            <?php 
                                                if($geo_location == 'on'){
                                                    if(isset($_GET['geo_location_lat']) && !empty($_GET['geo_location_lat'])) $geo_location_lat = $_GET['geo_location_lat']; else $geo_location_lat = '';
                                                    if(isset($_GET['geo_location_long']) && !empty($_GET['geo_location_long'])) $geo_location_long = $_GET['geo_location_long']; else $geo_location_long = '';
                                                    echo "<input type='hidden' name='geo_location_lat' value='" .$geo_location_lat. "' ><input type='hidden' name='geo_location_long' value='" .$geo_location_long. "' >";
                                                }
                                            ?>
                                        </div>
                                    </li>
                                    <?php
                                    if(isset($_GET['radius'])) $radius = $_GET['radius']; else $radius = 300;
                                    echo '
                                        <li class="distance_location to-field">
                                        <label>'.__('Distance in Miles', 'directory').'</label>
                                        <div class="input-sec">
                                            <div class="cs-drag-slider" data-slider-min="0" data-slider-max="'.$cs_loc_max_input.'" data-slider-step="'.$cs_loc_incr_step.'" data-slider-value="'.$radius.'"></div>
                                            <input id="sidebar-location-slider" class="cs-range-input" name="radius" type="text" value="'.$radius.'"   />
                                        </div>' . "\n";
                                        ?>
                                        <script>
                                            jQuery(document).ready(function($) {
                                                jQuery("#cs-drag-slider span").first().html("<strong>"+jQuery( "#sidebar-location-slider" ).val()+" Miles</strong>");
                                                jQuery('div.cs-drag-slider').each(function() {
                                                    tooltip = jQuery(this).parents('li.to-field').find('span.ui-slider-handle');
                                                    tooltip_val = jQuery(this).parents('li.to-field').find('input.cs-range-input').val();
                                                    tooltip.html("<strong>"+tooltip_val+" Miles</strong>");
                                                     var _this = jQuery(this);
                                                    _this.slider({
                                                        range:'min',
                                                        step: _this.data('slider-step'),
                                                        min: _this.data('slider-min'),
                                                        max: _this.data('slider-max'),
                                                        value: _this.data('slider-value'),
                                                        slide: function (event, ui) {
                                                            jQuery(this).parents('li.to-field').find('.cs-range-input').val(ui.value)
                                                            
                                                            tooltip = jQuery(this).parents('li.to-field').find('span.ui-slider-handle');
                                                            tooltip.html("<strong>"+ui.value+" Miles</strong>");
                                                            //tooltip.text(ui.value);
                                                            
                                                        }
                                                    });
                                                });
                                                var value = jQuery( "#sidebar-location-slider" ).val();
                                                jQuery("div.cs-drag-slider span").first().html("<strong>"+value+" Miles</strong>");
                                            });
                                            (function( $ ) {
                                                $(function() {
                                                    <?php 
                                                        if(isset($cs_directory_location_suggestions) && $cs_directory_location_suggestions == 'google'){
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
                                        </li>
                                        <?php
                                        }
                    
                        if(isset($directory_id) && $directory_id <> ''){
                        $saleprice_option = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_option', true);
                        $price_enable_search = get_post_meta((int)$directory_id, 'cs_post_price_enable_search', true);
                        if(isset($saleprice_option) && $saleprice_option == 'on' && isset($price_enable_search) && $price_enable_search == 'on'){
                            $price_max_range_start = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_max_input', true);
                            $price_min_range_start = get_post_meta((int)$directory_id, 'cs_post_price_saleprice_min_input', true);
                            if($price_min_range_start == '')
                                $price_min_range_start = '';
                            if($price_max_range_start == '')
                                $price_max_range_start = '';
                            
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
                            echo '<li class="price-search">';
                            echo '<div class="advance-search-price-range">';
                                    echo '
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
                            echo '</div>';
                            echo '</li>';
                        }
                    }
                    
                                // Custom Fields rendering
                   
                if(isset($directory_id) && $directory_id <> ''){
                        $custom_fields = '';
                        $cs_dcpt_custom_fields = get_post_meta($directory_id, "cs_directory_custom_fields", true);
                        if ( $cs_dcpt_custom_fields <> "" ) {
                            echo '<li>';
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
                    }
					echo '<br />Location: ';
                        if( $posts_per_page == '' ){
                            $posts_per_page = get_option('posts_per_page');    
                        }
                         ?>
                         <li>
                            <input type="hidden" name="cs_directory_search_location" value="<?php echo esc_attr($cs_directory_search_location);?>" />
                            <input type="hidden" name="pagination" value="<?php echo esc_attr($posts_per_page);?>" />
                            <input type="hidden" name="cs_loc_max_input" value="<?php echo absint($cs_loc_max_input);?>" />
                            <input type="hidden" name="cs_loc_incr_step" value="<?php echo absint($cs_loc_incr_step);?>" />
                            <input type="hidden" name="goe_location_enable" value="<?php if(isset($_GET['goe_location_enable']) && $_GET['goe_location_enable'] <> ''){echo absint($_GET['goe_location_enable']);} else {echo 'No';}?>" />
                            <button type="submit" value="Search" name="Submit" id="directory-submit-search-view" class="cs-bgcolor sidebar-search-sbmt" >Search</button>
                        </li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
        <?php
        if(isset($cs_default_ad_search_view) && $cs_default_ad_search_view == 'Map')
        {
            $rand_id = '';
            ?>
            <input type="hidden" id="rand_id" value="<?php echo esc_attr($rand_id);?>" />
            <input type="hidden" id="admin_url" value="<?php echo esc_js(admin_url('admin-ajax.php'));?>" />
            <input type="hidden" id="directory_uri" value="<?php echo esc_js(get_template_directory_uri());?>" />
            <script>
                jQuery( ".sidebar-search-sbmt" ).live("click", function() {
                    cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>');
                    return false;
                });
                jQuery( ".MultiControls p.btnOk" ).live("click", function() {
                    cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>');
                    return false;
                });
                jQuery( ".dir-map-search" ).live("change", function() {
                    cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>');
                    return false;
                });
                jQuery( "form #directory-search-location" ).live("change", function() {
                    cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>');
                    return false;
                });
                jQuery( "form .cs-drag-slider" ).live("change", function() {
                    cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>');
                    return false;
                });
            </script>
            <?php    
        }
    }
    add_action('cs_directroy_search_sidebar', 'cs_directroy_search_fields', 10, 4);
}

//======================================================================
// Render Dynamic Post Custom Fields For Search
//======================================================================
if ( ! function_exists( 'cs_custom_search_fields_render' ) ) {
    function cs_custom_search_fields_render($key = '', $param = '', $post_id = '') {
        global $post,$cs_node,$cs_xmlObject;
        
        if( isset( $post_id ) && $post_id !='' ){
            $post_id    = $post_id;
        } else {
            $post_id    = $post->ID;
        }
        
        $cs_value = '';
        $html       = '';
        $cs_customfield_required = '';
        if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required == 'yes'){
            $cs_customfield_required = '';
        }
        $output = '';
        $key = (string)$cs_node->cs_customfield_name;
        $cs_multiselect = (string)$cs_node->cs_customfield_enable_multiselect;
        switch( $cs_node->getName() )
        {
            case 'text' :
                if(isset( $key ) && isset( $_GET[$key] ) ){
                    $cs_value = $_GET[$key];
                }
                $output .= '<li><label>'.$cs_node->cs_customfield_label.'</label>';
                $output .= '<input type="text" placeholder="' . $cs_node->cs_customfield_placeholder . '" class="cs-form-text cs-input" name="' . $cs_node->cs_customfield_name . '" id="' . $cs_node->cs_customfield_name . '" value="' . $cs_value . '" />' . "\n</li>";
                // append
                $html .= $output;
                break;
            case 'email' :
                // prepare
                if(isset($key) && isset($_GET[$key])){
                    $cs_value = $_GET[$key];
                }
                $output .= '<li><label>'.$cs_node->cs_customfield_label.'</label>';
                $output .= '<input type="text" size="'. $cs_node->cs_customfield_size . '" placeholder="' . $cs_node->cs_customfield_placeholder . '" class="cs-form-text cs-input" name="' . $cs_node->cs_customfield_name . '" id="' . $cs_node->cs_customfield_name . '" value="' . $cs_value . '"  />' . "\n</li>";
                $html .= $output;
                break;
            case 'url' :
                // prepare
                if(isset($key) && isset($_GET[$key])){
                    $cs_value = $_GET[$key];
                } 
                $output .= '<li><label>'.$cs_node->cs_customfield_label.'</label>';
                $output .= '<input type="text" placeholder="' . $cs_node->cs_customfield_placeholder . '" class="cs-form-text cs-input" name="' . $cs_node->cs_customfield_name . '" id="' . $cs_node->cs_customfield_name . '" value="' . $cs_value . '" />' . "\n</li>";
                // append
                $html .= $output;
                break;
            case 'date' :
                // prepare
                if(isset($key) && isset($_GET[$key])){
                    $cs_value = $_GET[$key];
                }
                if(!isset($cs_node->cs_customfield_name) || $cs_node->cs_customfield_name == ''){
                    $cs_node->cs_customfield_name = 'date'.$post_id;    
                }
                if(!isset($cs_node->cs_customfield_format) || $cs_node->cs_customfield_format == ''){
                    $cs_node->cs_customfield_format = 'Y/m/d';    
                }
                $output .= '<script>
                    jQuery(function($) {
                         jQuery("#' . $cs_node->cs_customfield_name . '").datetimepicker({
                              format:"' . $cs_node->cs_customfield_format . '",
                              timepicker: false
                         });
                    });
                </script>';
                $output .= '<li><label>'.$cs_node->cs_customfield_label.'</label>';
                $output .= '<input type="text" size="'. $cs_node->cs_customfield_size . '" placeholder="' . $cs_node->cs_customfield_placeholder . '" class="cs-form-text cs-input '.$cs_node->cs_customfield_css.' " name="' . $cs_node->cs_customfield_name . '" id="' . $cs_node->cs_customfield_name . '" value="' . $cs_value . '" />' . "\n</li>";
                // append
                $html .= $output;
                break;
            case 'multiselect' :
                // prepare
                if(isset($key) && isset($_GET[$key])){
                    $cs_value = $_GET[$key];
                }
                if(is_array($cs_value))
                    $cs_value = explode(',',$cs_value);
                // prepare
                $output .= '<li><label>'.$cs_node->cs_customfield_label.'</label>
                    ';
                $multiselect_counter = 0;
                $output .= '<select style="min-height:100px;" name="' . $cs_node->cs_customfield_name . '[]" id="' . $cs_node->cs_customfield_name . '" class="cs-form-select cs-input '.$cs_node->cs_customfield_css.'" multiple="multiple">' . "\n";
                $options_values = array();
                if(isset($cs_node->options_values))
                    $options_values = $cs_node->options_values;
                foreach( $cs_node->options as $value => $option )
                {
                    $selected = '';
                    $options_val = $options_values[$multiselect_counter];
                    $selected = '';
                    if(is_array($cs_value) && in_array($options_val, $cs_value)) $selected = 'selected="selected"';
                    $output .= '<option '.$selected.' value="' . $cs_node->options_values[$multiselect_counter] . '">' . $option . '</option>' . "\n";
                    $multiselect_counter++;
                }
                $output .= '</select>' . "\n";
                $output .= '</li>';
                $html .= $output;
                break;
            case 'textarea' :
                if(isset($key) && isset($_GET[$key])){
                    $cs_value = $_GET[$key];
                }
                $output .= '<li><label>'.$cs_node->cs_customfield_label.'</label>';
                $output .= '<textarea rows="'.$cs_node->cs_customfield_rows.'" cols="'.$cs_node->cs_customfield_cols.'" name="' . $cs_node->cs_customfield_name . '" id="' . $cs_node->cs_customfield_name . '" class="cs-form-textarea cs-input '.$cs_node->cs_customfield_css.'">' . $cs_value . '</textarea>' . "\n</li>";
                // append
                $html .= $output;
                break;
            case 'range' :
                $min_key = $key.'_min_range';
                $max_key = $key.'_max_range';
                $min_range_start = $cs_node->cs_customfield_min_input;
                $max_range_start = $cs_node->cs_customfield_max_input;
                if($min_range_start == '')
                    $min_range_start = 0;
                if($max_range_start == '')
                    $max_range_start = 1000;
                if(isset($_GET[$min_key]) ){
                    $min_range = $_GET[$min_key];
                } else {
                    $min_range = $cs_node->cs_customfield_min_input;
                }
                if(isset($_GET[$max_key])){
                    $max_range = $_GET[$max_key];
                } else {
                    $max_range = $cs_node->cs_customfield_max_input;
                }
                
                $output = '';
                
                if(isset($cs_node->cs_customfield_search_style) && $cs_node->cs_customfield_search_style == 'Inputs'){
                    if(isset($cs_node->cs_customfield_enable_input) && $cs_node->cs_customfield_enable_input == 'yes'){
                        //<div class="advance-search-price-range">
                        $output .= '
                        <li><div class="advance-search-price-range">
                            <label>'.$cs_node->cs_customfield_label.'</label>
                            <ul>
                                <li><input name="'.$key.'_min_range" type="text" value="'.$min_range.'" placeholder="Min '.$cs_node->cs_customfield_label.'" ></li>
                                <li><input name="'.$key.'_max_range" type="text" value="'.$max_range.'" placeholder="Max '.$cs_node->cs_customfield_label.'" ></li>
                            </ul>
                            </div>
                        </li>';
                    
                        $html .= $output;
                    }
                } else if(isset($cs_node->cs_customfield_search_style) && $cs_node->cs_customfield_search_style == 'Slider_Inputs'){
                    if(isset($cs_node->cs_customfield_incrstep_input) && $cs_node->cs_customfield_incrstep_input <> '')
                        $incrstep_input = $cs_node->cs_customfield_incrstep_input;
                    else 
                        $incrstep_input = 1;
                    
                    if($min_range == '')
                        $min_range = 100;
                    if($max_range == '')
                        $max_range = 1000;
                            
                    $slidder_inputs = '';    
                    if(isset($cs_node->cs_customfield_enable_input) && $cs_node->cs_customfield_enable_input == 'yes'){
                        $slidder_inputs .= '<ul>
                                        <li><input id="'.$key.'_min_range" name="'.$key.'_min_range" type="text" value="'.$min_range.'" placeholder=" Min '.$cs_node->cs_customfield_label.'" ></li>
                                        <li><input  id="'.$key.'_max_range" name="'.$key.'_max_range" type="text" value="'.$max_range.'" placeholder=" Max '.$cs_node->cs_customfield_label.'" ></li>
                                        </ul>
                                    ';
                    }
                    $output .= '<li><div class="advance-search-price-range">
                                    <label>'.$cs_node->cs_customfield_label.'</label>
                                    
                                    <div class="input-sec">
                                        <div id="'.$key.'_slider-range"></div>
                                    </div>
                                    '.$slidder_inputs.'
                                </div>
                        </li>
                         <script>
                            jQuery(function() {
                                jQuery( "#'.$key.'_slider-range" ).slider({
                                    orientation: "horizontal",
                                    range: true,
                                    min: '.$min_range_start.',
                                    max: '.$max_range_start.',
                                    step: '.$incrstep_input.',
                                    values: [ '.$min_range.', '.$max_range.' ],
                                    slide: function( event, ui ) {
                                        jQuery( "#'.$key.'_min_range" ).val(ui.values[ 0 ]);
                                        jQuery( "#'.$key.'_max_range" ).val(ui.values[ 1 ]);
                                        
                                    }
                                });
                                jQuery( "#'.$key.'_min_range" ).val(jQuery( "#'.$key.'_slider-range" ).slider( "values", 0 ));
                                jQuery( "#'.$key.'_max_range" ).val(jQuery( "#'.$key.'_slider-range" ).slider( "values", 1 ));
                            });
                            </script>
                        ';
                    $html .= $output;
                } else if(isset($cs_node->cs_customfield_search_style) && $cs_node->cs_customfield_search_style == 'Slider'){
                    if(isset($cs_node->cs_customfield_incrstep_input) && $cs_node->cs_customfield_incrstep_input <> '')
                        $incrstep_input = $cs_node->cs_customfield_incrstep_input;
                    else 
                        $incrstep_input = 1;
                    
                    if($min_range == '')
                        $min_range = 100;
                    if($max_range == '')
                        $max_range = 1000;
                    
                    $output .= '<li>
                                <div class="advance-search-price-range">
                                <label>'.$cs_node->cs_customfield_label.'</label>
                                <input id="'.$key.'_min_range" name="'.$key.'_min_range" type="hidden" value="'.$min_range.'">
                                <input  id="'.$key.'_max_range" name="'.$key.'_max_range" type="hidden" value="'.$max_range.'">
                                <div class="input-sec">
                                    <div id="'.$key.'_slider-range"></div>
                                </div>
                                </div>
                            </li>
                         <script>
                            jQuery(function() {
                                jQuery( "#'.$key.'_slider-range" ).slider({
                                orientation: "horizontal",
                                range: true,
                                min: '.$min_range_start.',
                                max: '.$max_range_start.',
                                step: '.$incrstep_input.',
                                values: [ '.$min_range.', '.$max_range.' ],
                                slide: function( event, ui ) {
                                    jQuery( "#'.$key.'_min_range" ).val(ui.values[ 0 ]);
                                    jQuery( "#'.$key.'_max_range" ).val(ui.values[ 1 ]);
                                    jQuery("#'.$key.'_slider-range span").first().html("<strong>"+ui.values[ 0 ]+"</strong>");
                                    jQuery("#'.$key.'_slider-range span").eq(1).html("<strong>"+ui.values[ 1 ]+"</strong>");
                                }
                                });
                                jQuery( "#'.$key.'_min_range" ).val(jQuery( "#'.$key.'_slider-range" ).slider( "values", 0 ));
                                jQuery( "#'.$key.'_max_range" ).val(jQuery( "#'.$key.'_slider-range" ).slider( "values", 1 ));
                                jQuery("#'.$key.'_slider-range span").first().html("<strong>"+jQuery( "#'.$key.'_min_range" ).val()+"</strong>");
                                jQuery("#'.$key.'_slider-range span").eq(1).html("<strong>"+jQuery( "#'.$key.'_max_range" ).val()+"</strong>");
                            });
                            </script>
                        ';
                    $html .= $output;
                }
                break;
            case 'dropdown' :
                
                if( isset ( $_GET[$key] ) ){
                     $cs_value    = cs_get_query_values( $key );
                } else {
                     $cs_value = array();
                }
                                         
                $dropdown_flag = true;
                
                if ( $cs_multiselect == 'no' ) {
                    $output .= '
                    <li>
                        <label>'.$cs_node->cs_customfield_label.'</label>
                            <select  name="' . $cs_node->cs_customfield_name . '" id="' . $cs_node->cs_customfield_name . '" class="selectpicker show-tick form-control ad_cat_multislect" data-live-search="false">';
                            if(isset($cs_node->cs_customfield_first)){$output .= '<option value="">' . $cs_node->cs_customfield_first . '</option>' . "\n";}
                            $multiselect_counter=0;
                            $options_values = array();
                            if(isset($cs_node->options_values)){
                                $options_values = $cs_node->options_values;
                            }
                            foreach( $cs_node->options as $value => $option )
                            {
                                $selected = '';
                                $options_val = '';
                                if(isset($options_values[$multiselect_counter]))
                                    $options_val = (string)$options_values[$multiselect_counter];
                                if(is_array($cs_value) && in_array($options_val,$cs_value)) $selected = 'selected="selected"';
                                $output .= '<option value="' . $options_val . '" '.$selected.' >' . $option . '</option>' . "\n";
                                $multiselect_counter++;
                            }        
                    $output .= '
                            </select>
                    </li>';
                    $html .= $output;
                } else {
                    
                    $output .= '
                    <li>
                    <label>'.$cs_node->cs_customfield_label.'</label>
                        <ul class="cs-checkbox checked-category">';
                            
                            $multiselect_counter=0;
                            $options_values = array();
                            if(isset($cs_node->options_values)){
                                $options_values = $cs_node->options_values;
                            }
                            foreach( $cs_node->options as $value => $option )
                            {
                                $checked = '';
                                $options_val = '';
                                if(isset($options_values[$multiselect_counter]))
                                    $options_val = (string)$options_values[$multiselect_counter];
                                
                                
                                if( is_array( $cs_value ) && in_array( $options_val,$cs_value )) $checked = 'checked="checked"';
                                $output .= '<li><input  name="' . $cs_node->cs_customfield_name . '" id="' . $options_val. '" type="checkbox"  value="' . $options_val . '" '.$checked.' >';
                                $output .= '<label for="'.$options_val.'">' . $option  . '</label></li>';
                                $multiselect_counter++;
                            }        
                    $output .= '
                    </ul></li>';
                    $html .= $output;
                }
                
                break;
            default :
                break;
        }
        if(isset($dropdown_flag)){
            $html .= '<script type="text/javascript">
                    jQuery(document).ready(function ($) {
                        window.asd = jQuery(".ad_cat_multislect").SumoSelect({ okCancelInMulti:false });
                    });
                </script>';
        }
        return $html;
    }
}
//======================================================================
// Render Dynamic Post Custom Fields currently not in use
//======================================================================
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

//======================================================================
// Ajax Price Fields on change directory type
//======================================================================
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
                                $price_min_range_start = '';
                            if($price_max_range_start == '')
                                $price_max_range_start = '';
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
                                                        <input  id="min_price" name="min_price" type="text" value="'.$price_min_range.'">
													
                                                </li>';
                                    $output .= '<li>
                                                        <input  id="max_price" name="max_price" type="text" value="'.$price_max_range.'">
                                                </li></ul>';
                                        $html .= $output;
                                        $price_fields .= $html;
                            } else if(isset($cs_post_price_style) && $cs_post_price_style == 'Slider_Inputs'){
                                    if(isset($price_max_range) && $price_max_range == '')
                                        $price_max_range = 1000;
                                    if(isset($price_min_range) && $price_min_range == '')
                                        $price_min_range = 1;
                                    $slidder_inputs = '';    
                                    $slidder_inputs .= '
                                                    <input id="min_price" name="min_price" type="text" value="'.$price_min_range.'">
                                                    <input  id="max_price" name="max_price" type="text" value="'.$price_max_range.'">
                                                ';
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
                            } else if(isset($cs_post_price_style) && $cs_post_price_style == 'Slider'){
                                    $price_fields .= '
                                        <h6>'.$saleprice_label.'</h6>
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

//======================================================================
// Post Slug 
//======================================================================
if ( ! function_exists( 'cs_get_the_slug' ) ) { 
    function cs_get_the_slug($id) {
        $post_data = get_post($id, ARRAY_A);
        $slug = $post_data['post_name'];
        return $slug; 
    }
}
