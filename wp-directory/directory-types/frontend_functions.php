<?php

//=====================================================================
// Directory Fields show ajax call
//=====================================================================

if ( ! function_exists( 'forntend_directory_fields' ) ) {
	function forntend_directory_fields(){
		global $post,$cs_theme_options, $cs_page_id, $pagenow;
 		$post_id = '';
		if(isset($_REQUEST['directory_id']) && $_REQUEST['directory_id'] <> ''){
			$front_page 			= $_REQUEST['front_page'];
			$post_id    			= $_REQUEST['post_id'];
			$directory_id 			= $_REQUEST['directory_id'];
			$meta_options 			= cs_directory_custom_options_array();
			$cs_upload_size = isset($cs_theme_options['cs_image_size'])  && $cs_theme_options['cs_image_size'] !='' ? $cs_theme_options['cs_image_size'] : '9437184';
			$cs_upload_attachment_size = isset($cs_theme_options['cs_attachment_size'])  && $cs_theme_options['cs_attachment_size'] !='' ? $cs_theme_options['cs_attachment_size'] : '5242880';
								
			if(is_array($meta_options)){
				foreach( $meta_options['params'] as $table_key=>$tablerows ) {
					$field_title = $tablerows['title'];
					foreach( $tablerows as $key=>$param ) {
						if($key == 'title')
							continue;
						if(is_array($param)){
							$key_input = $key;
							if($param['type'] == 'checkbox'){
								$meta_option_on = get_post_meta($directory_id, $key, true);
								if($meta_option_on == 'on'){
									$$key = $meta_option_on;
								}
							}
							if($param['type'] == 'text'){
								$keyinputtitle = get_post_meta($directory_id, $key, true);
								if(empty($keyinputtitle))
									$keyinputtitle = $field_title;
								$$key_input = $keyinputtitle;
							}
						}
					}
				}
			}
			
			if(isset($cs_theme_options['cs_directory_menu_title']) && !empty($cs_theme_options['cs_directory_menu_title'])){
				$cs_directory_menu_title = trim($cs_theme_options['cs_directory_menu_title']);
			} else {
				$cs_directory_menu_title = 'Directory Ads';
			}
				
			?>
            <ul class="cs-form-element has-border column-input">
                <li class="categories">
                    <label for="categories"><?php _e('Categories','directory')?></label>
                    <?php
                    
                    $directory_categories = array();
                    $directory_categories_array = get_the_terms( $post_id, 'directory-category' );
                    if(isset($directory_categories_array) && is_array($directory_categories_array)){
                        foreach($directory_categories_array as $categoryy){
                            $directory_categories[] = $categoryy->term_id;
                        }
                    }
                    
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
									'hide_empty'               => 0,
									'hierarchical'             => 1,
									'exclude'                  => '',
									'include'                  => '',
									'number'                   => '',
									'taxonomy'                 => 'directory-category',
									'pad_counts'               => false 
								
								); 
                    $categories = get_categories($args); 
                    $multiple 	= '';
                    $categ_name = '';
                    if(isset( $cs_post_multi_cat_option ) && $cs_post_multi_cat_option == 'on'){
                        $multiple = 'multiple="multiple"';
                    }
                    ?>
                    <script>
                        var multi;
                        jQuery(document).ready(function () {
                            multi =jQuery('.category-multi-select').SumoSelect();
                        });
                    </script>
                    <select id="categoriess" class="category-multi-select" <?php echo isset( $multiple ) ? $multiple : '';?>  name="directory_categories[]">
                        <?php
                        foreach ($categories as $category) {
                            $selected = '';
                            if(in_array($category->slug, $directory_categories_array)){
                                if(in_array($category->term_id, $directory_categories)){
                                    $selected = 'selected="selected"';
                                }
                                echo '<option value="'.$category->term_id.'" '.$selected.'>' . $category->name . '</option>';
                            }
                        }
                        ?>
                    </select>
                </li>
                <?php 
				$custom_fields = '';
				$cs_directory_custom_fields = get_post_meta($directory_id, "cs_directory_custom_fields", true);
				if ( $cs_directory_custom_fields <> "" ) {
					$cs_customfields_object = new SimpleXMLElement($cs_directory_custom_fields);
					if(isset($cs_customfields_object->custom_fields_elements) && $cs_customfields_object->custom_fields_elements == '1'){
						if(count($cs_customfields_object)>1){
							global $post,$cs_node;
							foreach ( $cs_customfields_object->children() as $cs_node ){
							   $ele_Data	=  cs_custom_fields_frontend('','',$post_id);
							   if( isset( $ele_Data ) && $ele_Data !='' ) {
									$custom_fields .= '<li>';
									$custom_fields .= $ele_Data;
									$custom_fields .= '</li>';
							   }
							}
						}
					}
				}
				echo balanceTags($custom_fields, false);
				if( isset( $post_review_switch ) && $post_review_switch == 'on' ){
				?>
                    <li>
                        <label><?php _e('Enable Reviews','directory');?></label>
                        <select name="dir_cusotm_field[directory_reviews]"  class="form-select-dropdown form-select single-select SlectBox" id="directory_reviews">
                            <option value="yes" <?php if( isset( $directory_reviews ) && $directory_reviews == 'yes' ) { echo 'selected'; }?> ><?php _e('Yes','directory');?></option>
                            <option value="no" <?php if( isset( $directory_reviews ) && $directory_reviews == 'no' ) { echo 'selected'; }?>><?php _e('No','directory');?></option>
                        </select> 
                    </li>
				<?php 
				}
				?>
            </ul>
			<?php
			// call price setting fucntion 
			if(isset($cs_post_price_saleprice_option) and $cs_post_price_saleprice_option == 'on'){
				cs_get_price_options( $cs_post_price_saleprice_option , $post_id );
			}
			// call location fields fuction 
			if(isset($cs_post_location_option) && $cs_post_location_option == 'on'){
				if ( function_exists( 'cs_frontend_location_fields' ) ) {
					?>
					<?php cs_frontend_location_fields( $post_id ); 
				}
            }
			
			#Images
			$cs_post_multi_imgs_option = isset($cs_post_multi_imgs_option) ? $cs_post_multi_imgs_option : 'off';
            $cs_multiple_images_input = isset($cs_multiple_images_input) ? $cs_multiple_images_input : '0';
			
			#Attachment
			$cs_post_multi_attachment_option = isset($cs_post_multi_attachment_option) ? $cs_post_multi_attachment_option : 'off';
            $cs_multiple_attachment_input 	 = isset($cs_multiple_attachment_input) ? $cs_multiple_attachment_input : '0';
			
			#Tags
            $cs_post_multi_tags_option = isset($cs_post_multi_tags_option) ? $cs_post_multi_tags_option : 'off';
            $cs_multiple_tags_input = isset($cs_multiple_tags_input) ? $cs_multiple_tags_input : '0';
            echo '<input type="hidden" name="multi_tags_option_allow" value="'.$cs_post_multi_tags_option.'" id="multi_tags_option_allow" class="multi_tags_option_allow_class" />';
            echo '<input type="hidden" name="multi_tags_allow_no" value="'.$cs_multiple_tags_input.'" id="multi_tags_allow_no" class="multi_tags_allow_no_class" />';
			
            ?>
            <script>
				jQuery(document).ready(function($){
					//load_tags_script();
					//load_gallery_script();
					load_attachment_script();
					//cs_load_location_ajax();
				});
			</script>
            <!--Start images upload html-->
            <div class="cs-profile-title"><span><?php _e('Add Images','directory')?></span></div>
            <ul class="cs-form-element has-border galleryupload" data-gallery_allow="<?php echo esc_attr( $cs_post_multi_imgs_option );?>" data-galler_limit="<?php echo esc_attr( $cs_multiple_images_input );?>">
                <li class="gallery-thumb">
                  <div id="directory_images_container" style="cursor:default">
                    <ul class="directory_images">
                        <?php 
                            $attachments = array();
                            $directory_image_gallery = '';
                            $cs_hint	= '';
                            $cs_total_attahmets = 0;
                            if(isset($post_id) && !empty($post_id)){ 
                                if ( metadata_exists( 'post', $post_id, '_directory_image_gallery' ) ) {
                                    $directory_image_gallery = get_post_meta( $post_id, '_directory_image_gallery', true );
                                    $attachments = array_filter( explode( ',', $directory_image_gallery ) );
                                }
                                if ( $attachments ) {
                                    foreach ( $attachments as $attachment_id ) {
                                        $cs_total_attahmets++;
                                        $cs_hint	= 'style="display:none"';
                                        echo '<li class="gallery image-'.$cs_total_attahmets.'"  data-attachment_id="' . esc_attr( $attachment_id ) . '">
                                            ' . wp_get_attachment_image( $attachment_id, 'cs_media_6' ) . '
                                            <ul class="actions">
                                                <a data-id="'.$cs_total_attahmets.'" href="javascript:;" class="delete tips" data-tip="' . __( 'Delete image', 'directory' ) . '"><i class="icon-times"></i></a>
                                            </ul>
                                        </li>';
                                   }
                                }
                             }
                        ?>
                         <input type="hidden" id="directory_image_gallery" name="directory_image_gallery" value="<?php echo esc_attr( $directory_image_gallery ); ?>" />
                            
                     <li class="hint-text" <?php echo cs_allow_special_char($cs_hint);?>>
                        <h2><?php _e( 'Update Gallery', 'directory' ); ?></h2>
                        <?php _e('You can add up to'.$cs_multiple_images_input.'images (up to 9mb per upload)','directory');?></li>  
                    </ul>
                    <div class="add_gallery"><a  href="javascript:;" data-choose="<?php _e( 'Upload Images', 'directory' ); ?>" data-update="<?php _e( 'Add to gallery', 'directory' ); ?>" data-delete="<?php _e( 'Delete image', 'directory' ); ?>" data-text="<?php _e( 'Delete', 'directory' ); ?>"><i class="icon-plus3"></i><?php _e( 'Upload Images', 'directory' ); ?></a></div>
                    </div>
                </li>
                
             </ul>
            
            <!--Start Attachment upload html-->
            <?php if( isset( $cs_post_multi_attachment_option ) && $cs_post_multi_attachment_option == 'on' ) {?>
            <div class="cs-profile-title"><span><?php echo __('Attachment','directory'); ?></span></div>
            <ul class="cs-form-element has-border attachmentupload" data-attachment_allow="<?php echo esc_attr( $cs_post_multi_attachment_option );?>" data-attachment_limit="<?php echo esc_attr( $cs_multiple_attachment_input );?>" data-attachment-size="<?php echo esc_attr( $cs_upload_attachment_size ); ?>">
                                <li class="gallery-thumb">
                                  <div id="directory_attachment_container" style="cursor:default">
                                    <ul class="directory_attachment">
										<?php 
											$attachments_file = array();
											$directory_attachment = '';
											$cs_hint	= '';
											$cs_total_attahmets = 0;
											if(isset($post_id) && !empty($post_id)){ 
												if ( metadata_exists( 'post', $post_id, '_directory_file_attachment' ) ) {
													$directory_attachment = get_post_meta( $post_id, '_directory_file_attachment', true );
													$attachments_file = array_filter( explode( ',', $directory_attachment ) );
												}

												if ( $attachments_file ) {
													foreach ( $attachments_file as $attachment_file_id ) {
														$cs_total_attahmets++;
														$cs_hint		= 'style="display:none"';
														$attachments	= get_post( $attachment_file_id, 'OBJECT', 'raw' );
														$mime			= get_post_mime_type( $attachment_file_id );
														$mime_type_icon = wp_mime_type_icon($mime);
														$file_url 		= wp_get_attachment_url( $attachment_file_id );
														$filetype 		= wp_check_filetype( $file_url );
														
														
														echo '<li class="cs_attachment cs-edit-mode attachment-'.$cs_total_attahmets.'"  data-attachment_id="' . esc_attr( $attachment_file_id ) . '">
																<div class="attachment-info">
																	<div class="fileUpload media_upload"><span><i class="'.cs_get_attachment_icon($filetype['ext']).'"></i><span></div>
																</div>
																<div class="name">'.$attachments->post_title.'</div>
																<span class="date">'.date('F j, Y g:i A',strtotime($attachments->post_date)).'</span>
																<ul class="actions">
																	<li><a data-id="'.$cs_total_attahmets.'" href="javascript:;" class="delete tips" data-tip="' . __( 'Delete image', 'directory' ) . '"><i class="icon-times"></i></a></li>
																	<li>'.cs_add_caption($attachment_file_id , 'attachment_attached_caption').'</li>
																	
																</ul>
															</li>';
													   }
													}
												 }
											?>
                                         <input type="hidden" id="directory_attachment" name="directory_attachment" value="<?php echo esc_attr( $directory_attachment ); ?>" /> 
                                         <li class="hint-text-attachment" <?php echo cs_allow_special_char($cs_hint);?>>
                                            <h2><?php _e( 'Update Attachment', 'directory' ); ?></h2>
                                            <?php $cs_upload_mb_size = cs_filesize_format($cs_upload_attachment_size);?>
											<?php _e('You can add up to '.$cs_multiple_images_input.' attachments (up to '.$cs_upload_mb_size.' per upload).','directory');?>	
											<?php printf( __('You can add  only PDF,DOCX,DOC,PPT,TXT,XLSX,XLS formates.', 'directory'), '' ); ?>				
                                         </li>  
                                    </ul>
                                    <div class="add_attachment"><a  href="javascript:;" data-choose="<?php _e( 'Upload Attachment', 'directory' ); ?>" data-update="<?php _e( 'Add to gallery', 'directory' ); ?>" data-delete="<?php _e( 'Delete Attachment', 'directory' ); ?>" data-text="<?php _e( 'Delete', 'directory' ); ?>"><i class="icon-plus3"></i><?php _e( ' Add Attachment', 'directory' ); ?></a></div>
                                    </div>
                                </li>
                             </ul>
            <?php }?>
			<?php if (  isset( $post_video_switch ) && $post_video_switch == 'on' ) {?>
            <div class="cs-profile-title"><span><?php echo __('Add Video','directory'); ?></span></div>
            <ul class="cs-form-element has-border galleryupload">
            	<li class="suggestvideo upload-file">
                    <div class="inner-sec">
                        <label><?php echo __('Video Url','directory'); ?></label>
                        <input type="text"  placeholder="URL" class="text-input" value="" name="cs_video_url" />
                    </div>
                    <span><?php echo __('You can add Youtube, Vimeo, Dailymotion Videos url etc..','directory'); ?></span>
                </li>
            </ul>
			<?php 
			}
			
			if(isset($cs_post_multi_tags_option) && $cs_post_multi_tags_option == 'on' && $front_page == 'Front'){
                $directory_tags = '';
                $directory_tags_array = get_the_terms( $post_id, 'directory-tag' );
                if(isset($directory_tags_array) && is_array($directory_tags_array) && count($directory_tags_array)>0) {
                    foreach($directory_tags_array as $directorytag){
                        $directory_tags .= $directorytag->name.', ';
                    }
                }
                ?>
                <div class="cs-profile-title"><span><?php _e('Tags','directory')?></span></div>
                <ul class="cs-form-element has-border">
                    <li>
                        <div class="inner-sec">
                            <span class="icon-input">
                                <input id="csappend" type="text" value="" class="text-input multiple-tags-class">
                                <a href="javascript:;" id="csload_list"><i class="icon-plus3"></i></a>
                                <input id="csappend_hidden" name="directory_tags" type="hidden" value="<?php if(isset($directory_tags)) echo esc_attr($directory_tags);?>">
                                <p><?php _e('By clicking Submit you agree to our Terms of Use & posting rules','directory')?></p>
								
								
								
                            </span>
                            <ul class="cs-tags-selection">
								<?php
                                if(isset($directory_tags) && !empty($directory_tags)){
                                    $directory_tags = explode(',',$directory_tags);
                                    foreach($directory_tags as $tag_value){
                                        if(!empty($tag_value) && trim($tag_value) <> ''){
                                            echo '<li class="alert alert-warning"><a href="javascript:;" class="close" data-dismiss="alert"><i class="icon-cross5"></i></a> <span>'.$tag_value.'</span></li>';
                                        }
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </li>   
                </ul>  
            <?php 
            }
            // call add faqs function
            if(isset($cs_post_faqs_option) && $cs_post_faqs_option == 'on'){
                cs_faqs_section_frontend($post_id);
            }
            // call feature list function
            cs_get_feature_list( $directory_id , $post_id );
		}
		die();
	}
	add_action('wp_ajax_forntend_directory_fields', 'forntend_directory_fields');
}


//======================================================================
// Ad Location Fields
//======================================================================

if ( ! function_exists( 'cs_frontend_location_fields' ) ) {
	function cs_frontend_location_fields( $post_id = '' ){
		global $cs_xmlObject,$cs_theme_options, $post;
		
		$cs_map_latitude = isset($cs_theme_options['map_latitude']) ? $cs_theme_options['map_latitude'] : '';
		$cs_map_longitude = isset($cs_theme_options['map_longitude']) ? $cs_theme_options['map_longitude'] : '';
		$cs_map_zoom = isset($cs_theme_options['map_zoom']) ? $cs_theme_options['map_zoom'] : '6';
		
		if ( isset($cs_xmlObject)) {
			$dynamic_post_location_city = get_post_meta($post_id,'dynamic_post_location_city',true);
			$dynamic_post_location_region = get_post_meta($post_id,'dynamic_post_location_region',true);
			$dynamic_post_location_country = get_post_meta($post_id,'dynamic_post_location_country',true);
			$dynamic_post_location_latitude = get_post_meta($post_id,'dynamic_post_location_latitude',true);
			$dynamic_post_location_longitude = get_post_meta($post_id,'dynamic_post_location_longitude',true);
			$dynamic_post_location_zoom = get_post_meta($post_id,'dynamic_post_location_zoom',true);
			$dynamic_post_location_address = get_post_meta($post_id,'dynamic_post_location_address',true);
			$add_new_loc = get_post_meta($post_id,'add_new_loc',true);
		} else {
			$dynamic_post_location_latitude  = $dynamic_post_location_city = $dynamic_post_location_region = $dynamic_post_location_country ='';
			$dynamic_post_location_longitude = '';
			$dynamic_post_location_zoom 	 = '';
			$dynamic_post_location_address   = '';
			$loc_city 			= '';
			$loc_postcode 		= '';
			$loc_region 		= '';
			$loc_country 		= '';
			$event_map_switch 	= '';
			$event_map_heading	= 'Event Location';
			$add_new_loc		= '';
		}	
		
		if( $dynamic_post_location_latitude == '' ) $dynamic_post_location_latitude = $cs_map_latitude;
		if( $dynamic_post_location_longitude == '' ) $dynamic_post_location_longitude = $cs_map_longitude;
		if( $dynamic_post_location_zoom == '' ) $dynamic_post_location_zoom = $cs_map_zoom;
		
		$cs_directory_location_suggestions	= isset( $cs_theme_options['cs_directory_location_suggestions'] ) ?  $cs_theme_options['cs_directory_location_suggestions'] : '';
		$cs_location_type	= isset( $cs_theme_options['cs_search_by_location'] ) ?  $cs_theme_options['cs_search_by_location'] : '';
		
		//cs_enqueue_location_gmap_script();
		//wp_directory::cs_google_place_scripts();
		//wp_directory::cs_autocomplete_scripts();		
		
		$cs_location_countries	= get_option('cs_location_countries');
		$states_list			= get_option('cs_location_states');
		$cities_list			= get_option('cs_location_cities');
								
		
		$location_countries_list	= '';
		$location_states_list		= '';
		$location_cities_list		= '';
		$iso_code					= '';
		if( isset( $cs_location_countries ) && ! empty( $cs_location_countries ) ) {
			foreach( $cs_location_countries as $key => $country ) {
				 $selected	= '';
				 
				 if( isset( $dynamic_post_location_country ) && $dynamic_post_location_country == $key ) {
				 	$iso_code	= $country['isocode2'];
					$selected	= 'selected';
				 }
				 
				 $location_countries_list	.=  "<option ".$selected."  value='".$key."' data-name=".$country['isocode2'].">".$country['name']."</option>";
			}
		}

		$selected_country	= $dynamic_post_location_country;
		$selected_state		= $dynamic_post_location_region;
		$selected_city		= $dynamic_post_location_city;
		
		$dynamic_post_location_region;
		
		if( isset( $cs_location_countries ) && ! empty( $cs_location_countries ) && isset( $dynamic_post_location_country )  && !empty( $dynamic_post_location_country )  ) {

			 if( isset( $states_list[$selected_country] ) ) {
				 $states	= $states_list[$selected_country];
				 if( isset( $states ) && $states !='' && is_array($states) ) {
						foreach( $states as $key => $state ) {
								if( isset( $key ) && $key !='no-state' ) {
									$selected	= ( $selected_state == $key ) ? 'selected' : '';
									$location_states_list	.=  "<option ".$selected." value='".$state['name']."'>".$state['name']."</option>";
								}
							if( isset( $cities_list[$selected_country][$key] ) ) {
								$cities	= $cities_list[$selected_country][$key];
								if( isset( $cities ) && $cities !='' && is_array($cities) ) {
									foreach( $cities as $key => $city ) {
										$selected	= ( $selected_city == $city['name'] ) ? 'selected' : '';
										$location_cities_list	.=  "<option ".$selected." value='".$city['name']."'>".$city['name']."</option>";
									}
								}
							}
						}
				  }
			 }
		}
									
		?>
		<script>
			jQuery(document).ready(function () {
				cs_load_location_ajax();
			});
			
			function cs_gll_search_map() {
				var vals;
				vals = jQuery('#directory-search-location').val();
				vals = vals + ", " + jQuery('#loc_city').val();
				vals = vals + ", " + jQuery('#loc_region').val();
				vals = vals + ", " + jQuery('#loc_country').val();
				jQuery('.gllpSearchField').val(vals);
				//jQuery(".gllpSearchButton").trigger("click");
			}
		
			(function( $ ) {
				$(function() {
					<?php  wp_directory::cs_google_place_scripts();?>
						var autocomplete;
						
						autocomplete = new google.maps.places.Autocomplete(document.getElementById('directory-search-location'));
						 <?php if( isset( $iso_code ) && !empty( $iso_code ) ) {?>
							autocomplete.setComponentRestrictions({ 'country': '<?php echo $iso_code;?>' });    
						 <?php
					}
				 ?>
				});
			})( jQuery );
			
		</script>
        
		<fieldset class="gllpLatlonPicker"  style="width:100%; float:left;">
			<div class="cs-profile-title"><span><?php _e('Address','directory');?></span></div>
			<ul class="cs-form-element has-border column-input" id="locations_wrap" data-themeurl="<?php echo get_template_directory_uri();?>" data-plugin_url="<?php echo wp_directory::plugin_url();?>" data-ajaxurl="<?php echo esc_js(admin_url('admin-ajax.php'), 'directory');?>" data-map_marker="<?php echo wp_directory::plugin_url();?>/assets/images/map-marker.png">
                <li>
                    <label><?php _e('Country','directory'); ?></label>
					<?php
                    echo '<div class="select-style"><select class="form-select-country dir-map-search single-select" name="dir_cusotm_field[dynamic_post_location_country]" id="loc_country" onchange="cs_gll_search_map(this.value)">
                        <option value="">Select Country</option>';
                        echo $location_countries_list;
                    echo '</select></div>';
                    ?>
                </li>
                <?php 
                //if( isset( $cs_location_type ) && $cs_location_type != 'countries_only' ) { ?>
                	<li>
                        <label><?php _e('State','directory')?></label>
                        <span class="loader-states"></span>
                         <?php
                            echo '<div class="select-style">
							<select class="form-select-state dir-map-search single-select" name="dir_cusotm_field[dynamic_post_location_region]" id="loc_region" onchange="cs_gll_search_map(this.value)">
                                <option value="">Select State</option>';
                                echo $location_states_list;
                            echo '</select></div>';
                        ?>
                    </li>
                	<li>
                        <label><?php _e('City','directory')?></label>
                        <span class="loader-cities"></span>
                         <?php
                            echo '<div class="select-style"><select class="form-select-city dir-map-search single-select" name="dir_cusotm_field[dynamic_post_location_city]" id="loc_city" onchange="cs_gll_search_map(this.value)">
                                <option value="">'.__('Select City','directory').'</option>';
                                echo $location_cities_list;
                            echo '</select></div>';
                        ?>
                    </li>
                <?php
              //}
                ?>
                <li class="tw-input">
                    <label><?php _e('Location', 'directory');?></label>
                   <!-- <div class="my-location" onclick="cs_currentLocation('', '<?php echo esc_js(admin_url('admin-ajax.php'));?>')"><img src="<?php echo wp_directory::plugin_url();?>/assets/images/maplocation.png" alt="" /></div>-->
                    <input name="dir_cusotm_field[dynamic_post_location_address]" autocomplete="on" id="directory-search-location" type="text" value="<?php echo htmlspecialchars($dynamic_post_location_address)?>" onkeypress="cs_gll_search_map(this.value)"  />
                </li>
                <li> 
                	
                    <input type="button" class="gllpSearchButton"  value="<?php _e('Show Location','directory');?>"  onClick="cs_gll_search_map()" >
                </li>
                <li style="display:none">
                    <input type="hidden" name="dir_cusotm_field[dynamic_post_location_latitude]" value="<?php echo esc_attr($dynamic_post_location_latitude);?>" class="gllpLatitude" />
                </li>
                <li style="display:none">
                    <input type="hidden" name="dir_cusotm_field[dynamic_post_location_longitude]" value="<?php echo esc_attr($dynamic_post_location_longitude);?>" class="gllpLongitude" />
                </li>
                <li class="cs-form-element cs-location-search" style="float: left; width:100%;" >
                    <div class="clear"></div>
                    <input type="hidden" name="dir_cusotm_field[add_new_loc]" value="<?php  esc_attr($add_new_loc); ?>"  class="gllpSearchField" style="margin-bottom:10px;"  >
                    <input type="hidden" name="dir_cusotm_field[dynamic_post_location_zoom]" value="<?php echo esc_attr($dynamic_post_location_zoom);?>" class="gllpZoom" />
                    <input type="button" class="gllpUpdateButton" value="update map" style="display:none">
                    <div class="clear"></div>
                    <div style="float:left; width:100%; height:100%;">
                        <div class="gllpMap" id="cs-map-location-id"></div>
                    </div>
                </li>
            </ul>
		</fieldset>
		<?php
	}
}

/*
*Render Dynamic Post Custom Fields used when we create directory
*/
if ( ! function_exists( 'cs_custom_fields_frontend' ) ) {
	function cs_custom_fields_frontend( $key='', $param='', $post_id='' ) {
		global $post,$cs_node,$cs_xmlObject;
		
		if( isset( $post_id ) && $post_id !='' ){
			$post_id	= $post_id;
		} else if(isset($post->ID)){
			$post_id	= $post->ID;
		}
		
		$cs_value = '';
		$html = '';
		$cs_customfield_required = '';
		
		if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required == 'yes'){
			$cs_customfield_required = 'required';
		}
		
		$output = '';
		
		switch( $cs_node->getName() )
		{
			case 'text' :
				// prepare
				if(isset($cs_xmlObject)){
					$key = $cs_node->cs_customfield_name;
					if ( isset( $key ) && $key !='' )
						$cs_value = get_post_meta($post_id, "$key", true);
				} else {
					$cs_value = $cs_node->cs_customfield_default;
				}
				$output .= '<label>'.$cs_node->cs_customfield_label.'</label>';
				$output .= '<input type="text" placeholder="' . $cs_node->cs_customfield_placeholder . '" class="cs-form-text cs-input" name="dir_cusotm_field[' . $cs_node->cs_customfield_name . ']" id="' . $cs_node->cs_customfield_name . '" value="' . $cs_value . '" '.$cs_customfield_required.' />' . "\n";
				// append
				$html .= $output;
				break;
			case 'email' :
				// prepare
				
				if(isset($cs_xmlObject)){
					$key = $cs_node->cs_customfield_name;
					if ( isset( $key ) && $key !='' )
						$cs_value = get_post_meta($post_id, "$key", true);
				} else {
					$cs_value = $cs_node->cs_customfield_default;
				}

				$output .= '<label>'.$cs_node->cs_customfield_label.'</label></li>';
				$output .= '<input type="email" size="'. $cs_node->cs_customfield_size . '" placeholder="' . $cs_node->cs_customfield_placeholder . '" class="cs-form-text cs-input" name="dir_cusotm_field[' . $cs_node->cs_customfield_name . ']" id="' . $cs_node->cs_customfield_name . '" value="' . $cs_value . '" '.$cs_customfield_required.' />' . "\n";
				// append
				$html .= $output;
				break;
			case 'url' :
				// prepare
				if(isset($cs_xmlObject)){
					$key = $cs_node->cs_customfield_name;
					if ( isset( $key ) && $key !='' )
						$cs_value = get_post_meta($post_id, "$key", true);
				} else {
					$cs_value = $cs_node->cs_customfield_default;
				}
				$output .= '<label>'.$cs_node->cs_customfield_label.'</label>';
				$output .= '<input type="url" '.$cs_customfield_required.' placeholder="' . $cs_node->cs_customfield_placeholder . '" class="cs-form-text cs-input" name="dir_cusotm_field[' . $cs_node->cs_customfield_name . ']" id="' . $cs_node->cs_customfield_name . '" value="' . $cs_value . '" />' . "\n";
				// append
				$html .= $output;
				break;
			case 'date' :
				// prepare
				if(isset($cs_xmlObject)){
					$key = $cs_node->cs_customfield_name;
					if ( isset( $key ) && $key !='' )
						$cs_value = get_post_meta($post_id, "$key", true);
				} else {
					$cs_value = $cs_node->cs_customfield_default;
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
				$output .= '<label>'.$cs_node->cs_customfield_label.'</label>';
				$output .= '<input '.$cs_customfield_required.' type="text" size="'. $cs_node->cs_customfield_size . '" placeholder="' . $cs_node->cs_customfield_placeholder . '" class="cs-form-text cs-input '.$cs_node->cs_customfield_css.' " name="dir_cusotm_field[' . $cs_node->cs_customfield_name . ']" id="' . $cs_node->cs_customfield_name . '" value="' . $cs_value . '" />' . "\n";
				// append
				$html .= $output;
				break;
			case 'multiselect' :
				// prepare
				if(isset($cs_xmlObject)){
					$key = trim($cs_node->cs_customfield_name);
					if ( isset( $key ) && $key !='' ){
						$cs_value = get_post_meta($post_id, "$key", true);
						$cs_value = explode(',',$cs_value);
					}
				} else {
					$cs_value = $cs_node->cs_customfield_default;
				}
				// prepare
				$output .= '<label>'.$cs_node->cs_customfield_label.'</label>';
				$multiselect_counter = 0;
				$output .= '<select style="min-height:100px;" name="dir_cusotm_field[' . $cs_node->cs_customfield_name . '][]" id="' . $cs_node->cs_customfield_name . '" class="cs-form-select cs-input '.$cs_node->cs_customfield_css.'" multiple="multiple">' . "\n";
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
				// append
				$html .= $output;
				break;
			case 'textarea' :
				if(isset($cs_xmlObject)){
					$key = $cs_node->cs_customfield_name;
					if ( isset( $key ) && $key !='' ) 
						$cs_value = get_post_meta($post_id, "$key", true);
				} else {
					$cs_value = $cs_node->cs_customfield_default;
				}
				// prepare
				$output .= '<label>'.$cs_node->cs_customfield_label.'</label>';
				$output .= '<textarea '.$cs_customfield_required.' rows="'.$cs_node->cs_customfield_rows.'" cols="'.$cs_node->cs_customfield_cols.'" name="dir_cusotm_field[' . $cs_node->cs_customfield_name . ']" id="' . $cs_node->cs_customfield_name . '" class="cs-form-textarea cs-input '.$cs_node->cs_customfield_css.'">' . $cs_value . '</textarea>' . "\n";
				// append
				$html .= $output;
				break;
			case 'range' :
				if(isset($cs_xmlObject)){
					$key = $cs_node->cs_customfield_name;
					if ( isset( $key ) && $key !='' )
						$cs_value = get_post_meta($post_id, "$key", true);
				} else {
					$cs_value = $cs_node->cs_customfield_default;
				}
				// prepare
				$output .= '<label>'.$cs_node->cs_customfield_label.'</label>';
				$cs_customfield_enable_inputtt = '';
				if(!isset($cs_node->cs_customfield_enable_input) || $cs_node->cs_customfield_enable_input == 'no'){
					$cs_customfield_enable_inputtt = 'disabled';
				} else {
					$cs_customfield_enable_inputtt = '';
				}
				$output .= '<div class="cs-drag-slider" data-slider-min="' . $cs_node->cs_customfield_min_input . '" data-slider-max="' . $cs_node->cs_customfield_max_input . '" data-slider-step="' . $cs_node->cs_customfield_incrstep_input . '" data-slider-value="'.$cs_value.'"></div>
								<input  class="cs-range-input" '.$cs_customfield_enable_inputtt.'  name="dir_cusotm_field[' . $cs_node->cs_customfield_name . ']" type="text" value="'.$cs_value.'"   />' . "\n";
				// append
				$output .= "<script>
								jQuery( function($){
									jQuery('div.cs-drag-slider').each(function() {
										var _this = jQuery(this);
											if(_this.slider){
											_this.slider({
												range:'min',
												step: _this.data('slider-step'),
												min: _this.data('slider-min'),
												max: _this.data('slider-max'),
												value: _this.data('slider-value'),
												slide: function (event, ui) {
													jQuery(this).parents('li.to-field').find('.cs-range-input').val(ui.value)
												}
											});
											}
									});
								});
							</script>";
				$html .= $output;
				break;
			case 'dropdown' :
				if(isset($cs_xmlObject)){
					$key = $cs_node->cs_customfield_name;
					if ( isset( $key ) && $key !='' )
						$cs_value = get_post_meta($post_id, "$key", true);
				} else {
					$cs_value = (string)$cs_node->cs_customfield_default;
				}
						
				if(isset($cs_node->cs_customfield_enable_post_multiselect) && $cs_node->cs_customfield_enable_post_multiselect == 'yes'){
					$cs_customfield_enable_multiselect = '[]';
					$multiple = 'multiple="multiple"';
					$class = 'multiselect';
					$cs_value	= explode(',',$cs_value);
				} else {
					$cs_customfield_enable_multiselect = '';
					$multiple = '';
					$class = '';
				}
				// prepare
				$output .= '<label>'.$cs_node->cs_customfield_label.'</label>';
				$output .= '<script>
								jQuery(document).ready(function($) {
									window.asd = jQuery("select.form-select-dropdown").SumoSelect();
								});
							</script>';
				$output .= '<select   '.$multiple.' '.$cs_customfield_required.' name="dir_cusotm_field[' . $cs_node->cs_customfield_name . ']'.$cs_customfield_enable_multiselect.'" id="' . $cs_node->cs_customfield_name . '" class="form-select-dropdown dir-map-search single-select SlectBox cs-form-select cs-input '.$class.'">' . "\n";
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
					
					if( is_array( $cs_value ) && in_array( $options_val , $cs_value ) ) {
						$selected = 'selected="selected"';
					} else {
						if($options_val==$cs_value) $selected = 'selected="selected"';
					}
					
					$output .= '<option value="' . $options_val . '" '.$selected.' >' . $option . '</option>' . "\n";
					$multiselect_counter++;
				}
				$output .= '</select>' . "\n";
				// append
				$html .= $output;
				break;
			default :
				break;
		}
		return $html;
	}
}

/*
*Faqs Section
*/
if ( ! function_exists( 'cs_faqs_section_frontend' ) ) {
	function cs_faqs_section_frontend($post_id=''){
		global $post, $cs_xmlObject, $counter_faq, $directory_faq_title, $directory_faq_description;
		if(isset($post_id) && !empty($post_id)){
			$counter_faq = $post_id;
			$cs_directory = get_post_meta($post_id, "cs_directory_meta", true);
			if ( $cs_directory <> "" ) {
				$cs_xmlObject = new SimpleXMLElement($cs_directory);
			}	
			?>
			<script>
				/*jQuery("#total_faqs").sortable({
					cancel : 'td div.table-form-elem'
				});*/
			</script>
			<?php
		} else {
			$counter_faq = time();	
			 
 		}
		if(!isset($cs_xmlObject))
			$cs_xmlObject = new stdClass();
		?>
       	<div class="cs-profile-title"><span><?php _e('Frequesntly Asked Question','directory')?></span></div>
        <ul class="cs-form-element has-border faq-form">  
            <li>
                <input type="hidden" name="dynamic_post_faq" value="1" />
                <div class="cs-list-table">
                    <table class="to-table" border="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:80%;"></th>
                                <th style="width:80%;" class="centr"></th>
                            </tr>
                        </thead>
                        <tbody id="total_faqs">
                        <?php
                        if ( isset($cs_xmlObject->faqs) && is_object($cs_xmlObject) && count($cs_xmlObject->faqs)>0) {
							foreach ( $cs_xmlObject->faqs as $faqs ){
								$directory_faq_title = $faqs->faq_title;
								$directory_faq_description = $faqs->faq_description;
								cs_update_faq();
								$counter_faq++;
							}
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <a data-target="#addFAQ" data-toggle="modal" class="dr_custmbtn" href="#"><?php _e('Add New Question','directory');?></a>
                <div aria-hidden="true" aria-labelledby="myReportLabel" role="dialog" tabindex="-1" id="addFAQ" class="modal fade review-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="icon-times"></i></span><span class="sr-only">Close</span></button>
                                <h5><?php _e('Add FAQ','directory');?></h5>
                            </div>
                            <div class="modal-body">
                                <div id="faq-loading"></div>
                                <div class="faq-message-type succ_mess" style="display:none"><p></p></div>
                                <ul class="form-elements">
                                    <li class="to-label">
                                        <label><?php _e('Title','directory')?></label>
                                    </li>
                                    <li class="to-field">
                                        <input type="text" id="faq_title" name="faq_title" value="" />
                                    </li>
                                </ul>
                                <ul class="form-elements">
                                    <li class="to-label">
                                        <label><?php _e('FAQ Description','directory')?></label>
                                    </li>
                                    <li class="to-field">
                                        <textarea name="faq_description" id="faq_description"></textarea>
                                    </li>
                                </ul>
                                <ul class="form-elements noborder">
                                    <li class="to-label"></li>
                                    <li class="to-field">
                                        <input type="button" value="<?php _e('Add FAQ to List','directory');?>" onClick="post_add_faq('<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>')" />
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
		<?php
	}
}

/**
 * @Add FAQ List
 */
if ( ! function_exists( 'cs_update_faq' ) ) {
	function cs_update_faq() {
		global $counter_faq, $directory_faq_title, $directory_faq_description;
		foreach( $_POST as $keys=>$values ) {
			$$keys = $values;
		}
		$randomid = cs_generate_random_string('10');
		?>
        <tr class="parentdelete" id="edit_track<?php echo esc_attr($counter_faq)?>">
            <td id="subject-title<?php echo esc_attr($counter_faq)?>" style="width:80%;"><?php echo 'Q. '.esc_attr($directory_faq_title);?></td>
            <td class="centr" style="width:20%;">
                <div class="faq-action"><a data-target="#editFAQ_<?php echo esc_attr( $randomid );?>" data-toggle="modal" href="javascript:;"><i class="icon-edit3"></i></a>
                    <a href="javascript:;" class="delete-it btndeleteit actions delete"><i class="icon-times-circle"></i></a>
                </div>
                <div aria-hidden="true" aria-labelledby="myeditFaqLabel" role="dialog" tabindex="-1" id="editFAQ_<?php echo esc_attr( $randomid );?>" class="modal fade review-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="icon-times"></i></span><span class="sr-only">Close</span></button>
                                <h5><?php _e('Edit FAQ','directory');?></h5>
                            </div>
                            <div class="modal-body">
                                <div id="faq-loading"></div>
                                <div class="faq-message-type succ_mess" style="display:none"><p></p></div>   	
                                <div id="edit_track_form<?php echo esc_attr($counter_faq);?>" >
                                    <ul class="form-elements">
                                        <li class="to-label">
                                            <label><?php _e('FAQ Title','directory')?></label>
                                        </li>
                                        <li class="to-field">
                                            <input type="text" name="faq_title_array[]" value="<?php echo htmlspecialchars($directory_faq_title)?>" id="faq_track_title<?php echo esc_attr($counter_faq)?>" />
                                        </li>
                                    </ul>
                                    <ul class="form-elements">
                                        <li class="to-label">
                                            <label><?php _e('FAQ Description','directory')?></label>
                                        </li>
                                        <li class="to-field">
                                            <textarea name="faq_description_array[]" rows="5"  id="faq_track_description<?php echo esc_attr($counter_faq);?>" cols="20"><?php echo htmlspecialchars($directory_faq_description)?></textarea>
                                        </li>
                                    </ul>
                                    <ul class="form-elements noborder">
                                        <li class="to-label">
                                            <label></label>
                                        </li>
                                        <li class="to-field">
                                            <input type="button" value="Update FAQ" data-dismiss="modal"/>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
		<?php
		if ( isset($_POST['faq_title']) && isset($_POST['cs_add_faq_to_list']) ) die();
	}
	add_action('wp_ajax_cs_update_faq', 'cs_update_faq');
}

//======================================================================
// Get Feature List
//======================================================================

function cs_get_feature_list( $directoryType = '', $post_id = '' ){
	global $post;
	
	$cs_feature_options = get_post_meta((int)$directoryType, 'cs_feature_meta', true);
	
	if( isset( $post_id ) && $post_id !='' ){
		$featureList		= get_post_meta((int)$post_id, 'cs_feature_list', true);
		if ( isset( $featureList ) && !empty( $featureList ) ) {
			$featureList	= explode( ',', $featureList );
		} else {
			$featureList	= array();
		}
	} else {
		$featureList		= array();
	}
							
	if(isset($cs_feature_options) && is_array($cs_feature_options) && count($cs_feature_options)>0){
		?>
        <div class="cs-profile-title"><span><?php _e('Feature List','directory');?></span></div>
        <ul class="cs-form-element has-border">
            <li>
                <ul class="cs-featured-list">
					<?php 
                    foreach($cs_feature_options as $feature_key=>$feature){
                        if(isset($feature_key) && $feature_key <> ''){
                            $counter_feature = $feature_id = $feature['feature_id'];
                            $feature_title 	 = $feature['feature_title'];
                            $feature_slug 	 = $feature['feature_slug'];
                            $checked		 = '';
                                
                            if ( is_array( $featureList ) && in_array( $feature_slug , $featureList )  ) {
                                $checked	 = 'checked="checked"';
                            }
                            
                            echo '<li><div class="cs-checkbox"><input id="cs_feature_list_'.$counter_feature.'" type="checkbox" name="dir_cusotm_field[cs_feature_list][]" '.$checked.' value="'.$feature_slug.'" />';
                            echo '<label for="cs_feature_list_'.$counter_feature.'">'.esc_attr( $feature_title ).'</label>';
                            echo '</div></li>';
                        }
                    }
                    ?>
                </ul>
            </li>
        </ul>	
		<?php 
	}
}

//======================================================================
// Get Feature List
//======================================================================

function cs_get_price_options( $price_option = '' , $post_id = '' ){
	global $post, $cs_theme_options;
	$cs_currency_sign = isset($cs_theme_options['currency_sign']) ? $cs_theme_options['currency_sign'] : '$';
 	?>
    <div class="cs-profile-title"><span><?php _e('Price Settings','directory'); ?></span></div>
	<ul id="free-post-type" class="cs-form-element has-border column-input">
 	
	<?php
	if(isset($price_option) && $price_option == 'on'){
		
		$dynamic_post_sale_oldprice			= '';
		$dynamic_post_sale_newprice			= '';
		$dynamic_post_sale_currency_type	= '';
		$dynamic_post_sale_price_call		= '';
		
		if(isset($post_id) && $post_id <> ''){
			$dynamic_post_sale_oldprice			= get_post_meta($post_id, 'dynamic_post_sale_oldprice', true);
		}
		if(isset($post_id) && $post_id <> ''){
			$dynamic_post_sale_newprice			= get_post_meta($post_id, 'dynamic_post_sale_newprice', true);
		}
		if(isset($post_id) && $post_id <> ''){
			$dynamic_post_sale_price_call		= get_post_meta($post_id, 'dynamic_post_sale_price_call', true);
		}
			
		?>
		
        <li class="dynamic_post_sale_newprice on-call" style=" <?php if(isset( $price_type_value ) && $price_type_value == 'price-on-call' ) { echo 'display:none';} else { echo 'display:block';}?>" >
            <label for="categories"><?php printf(__("Price %s", "directory"), $cs_currency_sign); ?></label>
            <div class="inner-sec"><input type="text" placeholder="<?php _e('Price','directory')?>" name="dir_cusotm_field[dynamic_post_sale_newprice]" value="<?php echo esc_attr( $dynamic_post_sale_newprice );?>" /></div>
        </li>
        <li class="dynamic_post_sale_oldprice on-call" style=" <?php if(isset( $price_type_value ) && $price_type_value == 'price-on-call' ) { echo 'display:none';} else { echo 'display:block';}?>">
            <label for="categories"><?php printf(__("Old Price %s", "directory"), $cs_currency_sign); ?></label>
            <div class="inner-sec"><input type="text" placeholder="<?php _e('Old Price','directory')?>" name="dir_cusotm_field[dynamic_post_sale_oldprice]" value="<?php echo esc_attr( $dynamic_post_sale_oldprice );?>" /></div>
        </li>
        <li class="dynamic_post_sale_price_call" style=" <?php if(isset( $price_type_value ) && $price_type_value == 'paid' ) { echo 'display:none';} else { echo 'display:block';}?>">
            <label for="categories"><?php _e('Phone No','directory')?></label>
            <div class="inner-sec"><input type="text" placeholder="<?php _e('Phone No','directory')?>" name="dir_cusotm_field[dynamic_post_sale_price_call]" value="<?php echo esc_attr( $dynamic_post_sale_price_call );?>" /></div>
            <p><?php _e('show in case of public profile off','directory')?></p>
        </li>                          
		<?php 
	} 
	?>
	</ul>
<?php
}

/*
* Opening Hours Fields
*/
if ( ! function_exists( 'cs_post_openinghours' ) ) {
	function cs_post_openinghours($post_id=''){
		global $cs_xmlObject, $current_user;
		$opning_hours = get_post_meta( $post_id, 'opening_hours', true);
	
		$weekdays = array( "Sun" => __("Sunday",'directory'), "Mon" => __("Monday",'directory'), "Tue" => __("Tuesday",'directory'), "Wed" => __("Wednesday",'directory'), "Thu" => __("Thursday",'directory'), "Fri" => __("Friday",'directory'), "Sat" => __("Saturday",'directory') );
		$weekday_fields = array();
		
		foreach($weekdays as $key=>$value){
			$weekday_fields[$key] = array(
						'openhours_'.$key.'_text' => array(
							'name' => 'openhours_'.$key.'_text',
							'type' => 'text',
							'title' => $value,
							'class' => '',
							'description' => '',
							'default' => $value,
						),
						'openhours_'.$key.'_start' => array(
							'name' => 'openhours_'.$key.'_start',
							'type' => 'text',
							'class' => 'openhours-time',
							'title' => 'Start Time',
							'description' => '',
							'default' => '',
						),
						'openhours_'.$key.'_end' => array(
							'name' => 'openhours_'.$key.'_end',
							'type' => 'text',
							'class' => 'openhours-time',
							'title' => 'End Time',
							'description' => '',
							'default' => '',
						)
				);
		}
		$dynamic_post_other_options['openinghours'] = array(
				'title' => __('Opening Hours', 'directory'),
				'id' => 'openinghour-meta-option',
				'notes' => __('Opening Hours', 'directory'),
				'params' => $weekday_fields
			);
			$output = '';
			
			foreach($dynamic_post_other_options['openinghours']['params'] as $params){
				$output .= '<tr>';
				if(is_array($params)){
					foreach($params as $key=>$param){
						if(isset($opning_hours[$key])){
							$value = $opning_hours[$key];
						} else {
							$value = $param['default'];
						}
						switch( $param['type'] )
							{
								case 'text' :
									$output .= '<td>
													<input type="text" name="opening_hours[' . $key . ']" value="'.$value.'" class="'.sanitize_html_class($param['class']).'" />
												</td>';
									break;
							}
					}
				}
				$output .=  '</tr>';
			}
			echo balanceTags($output, true);
	}
}

/*-----------------------------------------------------------------
 * Caption
 *---------------------------------------------------------------*/
if ( ! function_exists( 'cs_add_caption' ) ) {
	function cs_add_caption($post_id='',$cs_type=''){
		$attachments	= get_post( $post_id, 'OBJECT', 'raw' );
		
		if ($attachments) {
			$caption	= $attachments->post_excerpt;
		} else {
			$caption	= '';
		}
		ob_start();
		?>
        <a data-target="#addcaption-<?php echo intval($post_id);?>" data-toggle="modal" href="javascript:;"><i class="icon-pencil3"></i></a>
        <div class="faq-form">
        <div aria-hidden="true" aria-labelledby="myReportLabel" role="dialog" tabindex="-1" id="addcaption-<?php echo intval($post_id);?>" class="modal fade review-modal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="icon-times"></i></span><span class="sr-only">Close</span></button>
                <h5><?php echo __('Add Caption','directory');?></h5>
              </div>
              <div class="modal-body">
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php echo __('Add Title','directory');?></label>
                  </li>
                  <li class="to-field">
                    <input type="text" id="caption" name="<?php echo esc_attr($cs_type);?>[]" value="<?php echo esc_attr( $caption );?>"/>
                  </li>
                </ul>
                <ul class="form-elements noborder">
                  <li class="to-label"></li>
                  <li class="to-field">
                    <button data-dismiss="modal" class="close" type="button"><span><?php echo __('Save','directory');?></span></button>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        </div>
	<?php 
		$post_data = ob_get_clean();
		return $post_data;
	}
}

/*-----------------------------------------------------------------
 * Attachment Icon
 *---------------------------------------------------------------*/
if ( ! function_exists( 'cs_get_attachment_icon' ) ) {
	function cs_get_attachment_icon( $ext ='default' ){
		$cs_attachment	= array(
						'txt'	=> 'icon-document',
						'xlsx'	=> 'icon-file-excel-o',
						'pdf'	=> 'icon-file-pdf-o',
						'doc'	=> 'icon-documents',
						'docx'	=> 'icon-documents',
						'xls'	=> 'icon-file-excel-o',
						'jpg'	=> 'icon-document',
						'png'	=> 'icon-document',
						'gif'	=> 'icon-document',
						'psd'	=> 'icon-document',
						'ppt'	=> 'icon-document',
						'default'	=> 'icon-document',
		);
		
		
		return $cs_attachment[$ext];
	}
}