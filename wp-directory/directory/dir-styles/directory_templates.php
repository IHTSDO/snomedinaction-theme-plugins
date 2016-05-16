<?php
//======================================================================
// Directory Listing Shortcode
//======================================================================
if(!function_exists('cs_directory_shortcode')) {
    function cs_directory_shortcode( $atts ) {
        global $post,$wpdb,$cs_xmlObject,$cs_theme_options, $column_container,$cs_node_id,$cs_elem_id,$cs_paged_id;
		
        $defaults = array( 'directory_title' => '', 'directory_cat' => '','cs_directory_fields_count'=>'4', 'cs_directory_filter' => '', 'cs_featured_on_top' => 'Yes', 'cs_listing_sorting' => 'recent','cs_directory_header' => '','cs_directory_rev_slider' => '','cs_directory_banner' => '','cs_directory_adsense' => '','cs_subheader_bg_color' => '','cs_subheader_padding_top' => '','cs_subheader_padding_bottom' => '','directory_view' => '','cs_switch_views' => '','directory_type' => '','directory_pagination'=>'','cs_directory_filterable' => '','cs_directory_sortable' => '','cs_directory_sortable' => '','directory_per_page' => '10');
        extract( shortcode_atts( $defaults, $atts ) );
            
        if ( isset( $_GET['submit'] ) && $directory_view != 'carousel' ) {
            if ( ! isset( $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] ) ) {
                $directory_per_page		= isset( $directory_per_page ) ?  $directory_per_page : 10;
                if ( isset( $cs_switch_views ) && $cs_switch_views != '' ){
                    $cs_switch_views    = explode( ',',$cs_switch_views );
                } else {
                    $cs_switch_views    = array();
                }
                cs_set_session( $directory_view,$directory_type,'meta_value','DESC',$cs_paged_id , 'true', '', $cs_directory_fields_count, $directory_per_page, $cs_switch_views,$cs_directory_filterable,$directory_title,$directory_pagination,$cs_directory_sortable ,$cs_elem_id);
            }
            cs_get_filter_results( $atts );
            
        } else {
            if (isset($cs_xmlObject->sidebar_layout) && $cs_xmlObject->sidebar_layout->cs_page_layout <> '' and $cs_xmlObject->sidebar_layout->cs_page_layout <> "none"){                
                    $cs_directory_grid_layout = 'col-md-4';
            }else{
                    $cs_directory_grid_layout = 'col-md-3';    
            }
            
            $view    = $directory_view;
            $cs_fixed_view    = false;
            if ( isset( $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] ) ) {

                $cs_sessionData    = $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data']; 
                $view    = isset( $cs_sessionData['post_directory_view'] ) ? $cs_sessionData['post_directory_view'] : 'listing' ;
                $cs_fixed_view    = true;
            }
           
           if ( isset ( $view ) && $cs_fixed_view == true  && $directory_view != 'carousel' && ! is_front_page() ) {
                if( $view == 'grid' ){
                    cs_directory_grid( $atts , $cs_directory_grid_layout , 'normal','','');
                } else if( $view == 'grid-box' ){
                    cs_directory_grid_two( $atts , 'col-md-4' , 'normal','','');
                } else if( $view == 'grid-box-four-column' ){
                    cs_directory_grid_box_four_column( $atts , 'col-md-3' , 'normal','','');
                } else if ( $view == 'listing' ){
                    cs_directory_listing($atts);
                } else if ( $view == 'detailed' ){
                    cs_directory_detailed($atts);
                } else if ( $view == 'map' ){
                    cs_directory_map( 'normal', $atts );
                } else {
                   cs_directory_listing($atts, 'normal','','');
                }
            } else {
                 if($directory_view == 'grid' ){
                    cs_directory_grid($atts,$cs_directory_grid_layout , 'normal','','');
                } else if($directory_view == 'grid-box' ){
                    cs_directory_grid_two($atts,'col-md-4' , 'normal','','');
                } else if( $view == 'grid-box-four-column' ){
                    cs_directory_grid_box_four_column( $atts , 'col-md-3' , 'normal','','');
                } else if($directory_view == 'listing' ){
                    cs_directory_listing($atts, 'normal','','');
                } else if($directory_view == 'detailed' ){
                    cs_directory_detailed($atts, 'normal','','');
                } else if($directory_view == 'carousel' ){
                    cs_directory_carousel($atts, 'normal','','');
                } else {
                    cs_directory_listing($atts, 'normal','','');
                }
            }
        }
    }
    add_shortcode('cs_directory', 'cs_directory_shortcode');
}

//======================================================================
// Directory Grid Shortcode
//======================================================================
if (!function_exists('cs_directory_meta_query')) {
    function cs_directory_meta_query( $args , $meta_fields_array ,  $filter , $backendFilter , $sortingSwitch , $sorting  ){
        
        if ( $backendFilter == true ) {
           if(isset($filter) && $filter == 'paid'){
                $meta_fields_array[] = array('key' => 'dir_pkg_expire_date', 'value'  => date("Y-m-d H:i:s"), 'compare' => '>', 'type' => 'DATE');
                $args['meta_key']    = 'dir_pkg_expire_date';
                $args['meta_type']   = 'DATE';
                $args['orderby']     = 'meta_value';
                $args['order']       = 'DESC';
            } else if(isset($filter) && $filter == 'free'){
                $meta_fields_array[]     = array( 'relation' => 'OR',
												   array('key' => 'dir_pkg_expire_date', 'value'  => date("Y-m-d H:i:s"), 'compare' => '<', 'type' => 'DATE'),
												   array('key' => 'dir_pkg_expire_date', 'value' => 'unlimited', 'compare' => '=')
												);
                $args['orderby']       = 'meta_value';
                $args['order']         = 'ASC';
            } else {
                $args['orderby']       = 'meta_value';
                $args['order']         = 'DESC';
				$args = $args;
            }
        } else {
            $args = $args;
        }
		
		# Featured,Popular,Recent Query
		
		if(  isset( $sortingSwitch ) && $sortingSwitch == 'Yes' ) {
			
			if( isset( $sorting ) && $sorting == 'popular' ) {
				$meta_fields_array[] = array('key' => 'dir_pkg_expire_date', 'value' => date("Y-m-d H:i:s"), 'compare' => '>', 'type' => 'DATE');
				$args['meta_key']    = 'cs_count_views';
                $args['orderby']     = 'meta_value_num';
				$args['meta_type']   = 'NUMERIC';
                $args['order']       = 'DESC';
			}else if( isset( $sorting ) && $sorting == 'urgent' ) {
				$meta_fields_array[] = array('key' => 'dir_featured_till', 'value' => date("Y-m-d H:i:s"), 'compare' => '>', 'type' => 'DATE');
				$args['meta_key']    = 'dir_pkg_expire_date';
				$args['meta_type']   = 'DATE';
                $args['order']       = 'DESC';
			} else {
				$args['meta_key']      = 'dir_pkg_expire_date';
				$args['meta_type']     = 'DATE';
				$args['orderby']       = 'meta_value';
                $args['order']         = 'DESC';
			}  
		
		} else {
			
			if( isset( $sorting ) && $sorting == 'popular' ) {
				$args['meta_key']    = 'cs_count_views';
                $args['orderby']     = 'meta_value_num';
				$args['meta_type']   = 'NUMERIC';
                $args['order']       = 'DESC';
			} else if( isset( $sorting ) && $sorting == 'urgent' ) {
				$meta_fields_array[] = array('key' => 'dir_featured_till', 'value'  => date("Y-m-d H:i:s"), 'compare' => '>', 'type' => 'DATE');
				$args['meta_key']    = 'dir_featured_till';
				$args['meta_type']   = 'DATE';
                $args['order']       = 'DESC';
			} else {
				$args['orderby']       = 'meta_value';
                $args['order']         = 'DESC';
			}  
		
		}

        if(is_array($meta_fields_array) && count($meta_fields_array)>1){
            $args['meta_query'] = $meta_fields_array;
        }
        
        return $args;    
    }
}


//====================================================================
// Directory Grid Shortcode
//====================================================================
if (!function_exists('cs_directory_grid')) {
    function cs_directory_grid( $atts = '', $cs_directory_grid_layout = 'col-md-4', $cs_listingType = 'normal' , $args ='', $directory_id ='' )
    {
        global $post,$wpdb,$cs_xmlObject,$cs_theme_options, $column_container,$cs_node_id,$cs_elem_id,$cs_paged_id;

        $cs_chek_section_view = '';
        if ( isset( $column_container ) ){
            $column_attributes = $column_container->attributes();
            $cs_chek_section_view = $column_attributes->cs_section_view;    
        }
        
        if ( $cs_listingType == 'normal' ) { 
        
        $defaults = array( 'directory_title' => '', 'directory_cat' => '','cs_directory_fields_count'=>'4', 'cs_directory_filter' => '', 'cs_featured_on_top' => 'Yes', 'cs_listing_sorting' => 'recent','cs_directory_header' => '','cs_directory_rev_slider' => '',' cs_directory_map_style' =>'style-1','cs_directory_banner' => '','cs_directory_adsense' => '','cs_subheader_bg_color' => '','cs_subheader_padding_top' => '','cs_subheader_padding_bottom' => '','directory_view' => '','cs_switch_views' => '','directory_type' => '','directory_pagination'=>'','cs_directory_filterable' => '','cs_directory_sortable' => '','directory_per_page' => '10');
        extract( shortcode_atts( $defaults, $atts ) );

        $cs_directory_options    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
		$cs_page_id    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
		$cs_directory_map_style = isset($cs_directory_map_style) ? $cs_directory_map_style : 'style-1';

        
        date_default_timezone_set('UTC');
        $current_time = current_time('Y/m/d');
        
        ob_start();
        
        $organizer_filter 			= '';
        $user_meta_key              = '';
        $user_meta_value            = '';
        $meta_compare 				= "=";
        $meta_value   				= '';
        $meta_key      				= '';
        
        if ( isset( $cs_switch_views ) && $cs_switch_views !='' ){
            $cs_switch_views = explode(",", $cs_switch_views);
        } else {
            $cs_switch_views    =  array();
        }

        //==Filters End
        
        if ( isset( $directory_type ) && $directory_type == 0 ) {
            $directory_type    = '';
        }
        
        $meta_compare 	= "=";
        $meta_value   	= $directory_type;
        $meta_key       = 'directory_type_select';
        
        $meta_fields_array = array();
        $meta_fields_array = array('relation' => 'AND',);

        $backendFilter    = true; 
        $sort_key         = '';
        $meta_type        = 'CHAR';
        
        $orderby   		  = 'meta_value';
        $order        	  = 'DESC';

		 $cs_directory_options    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
        $cs_counter_directory = 0;
            
        if ( empty($_GET['page_id_all']) ) $_GET['page_id_all'] = 1;
            
        if ( $directory_type <> "" ) {
            $args = array(
                        'posts_per_page'          =>         "-1",
                        'post_type'               =>         'directory',
                        'post_status'             =>         'publish',
                        'orderby'                 =>         $orderby,
                        'order'                   =>         $order,
                    );

            $meta_fields_array[] = array(
                                        'key'         => $meta_key,
                                        'value'       => $meta_value,
                                        'compare'     => $meta_compare,
                                    );
                                    
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter , $cs_featured_on_top , $cs_listing_sorting  );
        
        } else {
            $args = array(
                'posts_per_page'            =>         "-1",
                'post_type'                 =>         'directory',
                'post_status'               =>         'publish',
                'orderby'                   =>         $orderby,
                'order'                     =>         $order,
            );
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter , $cs_featured_on_top , $cs_listing_sorting  );
        } 

        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }

        $custom_query   = new WP_Query($args);
        $count_post     = 0;
        $counter        = 1;
        $count_post     = $custom_query->post_count;

        $args = array(
            'posts_per_page'            => "$directory_per_page",
            'paged'                     => $_GET['page_id_all'],
            'post_type'                 => 'directory',
            'meta_key'                  => (string)$sort_key,
            'meta_type'                 => $meta_type,
            'post_status'               => 'publish',
        );
                    
        $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter ,  $backendFilter , $cs_featured_on_top , $cs_listing_sorting  );
    
        $args['orderby'] = $orderby;
        $args['order']      = $order;
        
        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }

        cs_directory_sub_header($cs_directory_header, $cs_directory_banner, $args, $directory_type, $cs_directory_rev_slider, $cs_directory_adsense,$cs_directory_map_style);
        
        if( $cs_chek_section_view == 'wide' ){
            echo '<div class="directory-box">';
        }
        
        if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ){
            echo '<div class="section-content">';
        }
		
		echo '<div class="dynamic-listing">';
		
        if($cs_directory_header <> 'adsense' && $cs_directory_header <> 'plain-heading') {
            $section_title = '';
            if(isset($directory_title) && trim($directory_title) <> ''){
                $section_title = '<div class="col-md-12 cs-section-title"><h2>'.$directory_title.'</h2></div>';
				echo cs_allow_special_char($section_title);
            }
        }
        
        if ( ! isset( $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] ) ) {
            cs_set_session( $directory_view,$directory_type,$orderby,$order,$cs_paged_id,'false','',$cs_directory_fields_count,$directory_per_page,$cs_switch_views,$cs_directory_filterable,$directory_title,$directory_pagination,$cs_directory_sortable,$cs_elem_id);
        }
        
            cs_get_directory_top_filters( $cs_switch_views , $directory_view , $atts );
        } else {
            
            if ( isset( $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] ) && $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] !='' ) {
                 $sessionData                = $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'];
                 $cs_directory_fields_count  = $sessionData['fields_limit'];
                 $directory_per_page         = $sessionData['pagination'];
                 $cs_switch_views            = $sessionData['switch_views'];
                 $directory_view             = $sessionData['post_directory_view'];
                 $filterable                 = $sessionData['filterable'];
                 $sortable                   = $sessionData['sortable'];
                 $directory_title            = $sessionData['directory_title'];
            } else {
                $cs_directory_fields_count    = 4;
                $directory_per_page           = isset( $_GET['pagination'] ) ? $_GET['pagination'] : 10;
                $cs_switch_views              = 'list,grid,grid-box,grid-box-four-column,map';
                $cs_switch_views              = explode(",", $cs_switch_views);
                $directory_view               = 'grid';
                $filterable                   = 'No';
                $sortable                     = 'No';
                $directory_title              = '';
            }

            $meta_value                   = isset($directory_id) ? $directory_id : '';
            $directory_type_select        = isset($directory_id) ? $directory_id : '';
            $args                         = $args;
            $directory_pagination         = "Show Pagination";
            $count_post                   = '';
            $cs_directory_filterable      = $filterable;
            $directory_cat                = '';
            $directory_type               = '';
            $cs_directory_grid_layout     = 'col-md-4';
            
            if( $cs_chek_section_view == 'wide' ){
                echo '<div class="directory-box">';
            }
            
            if ( $cs_directory_filterable == 'Yes' )
                echo '<div class="section-content">';
            
			echo '<div class="dynamic-listing">';
			
            $section_title = '';
            if(isset($directory_title) && trim($directory_title) <> ''){
                $section_title = '<div class="col-md-12 cs-section-title"><h2>'.$directory_title.'</h2></div>';
				echo cs_allow_special_char($section_title);
            }
            cs_get_directory_top_filters( $cs_switch_views , $directory_view, '' );
        }
        
         if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ){
            $cs_directory_grid_layout    = 'col-md-4';
         }

        $custom_query = new WP_Query( $args );
       $test_count=  count($custom_query->have_posts());
        if ( $custom_query->have_posts() <> "" ) {  
         echo '<div class="cs-listing-wrapper"><div class="cs-directory grid_listing">'; 
            while ( $custom_query->have_posts() ): $custom_query->the_post();
            $width                	= '370';
            $height                 = '280';
            $title_limit         	= 5;
            $background             = '';
			$cs_post_id = $post->ID;
            $cs_directory            = get_post_meta($cs_post_id, "cs_directory_meta", true);
            $views                   = get_post_meta($cs_post_id, "cs_count_views", true);
            $directory_type_select 	 = get_post_meta($cs_post_id, "directory_type_select", true);
			
            if ( $cs_directory <> "" ) {
                $cs_xmlObject = new SimpleXMLElement($cs_directory);
            }
            
           $cs_page_id = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
            
			$organizerID = cs_get_organizer_id($cs_post_id );
            
            if(isset($directory_type_select) && $directory_type_select <> ''){
                $post_id = absint($directory_type_select);
                    $meta_options = cs_directory_custom_options_array();
                    if(is_array($meta_options)){
                        foreach( $meta_options['params'] as $table_key=>$tablerows ) {
                            $field_title = $tablerows['title'];
                            foreach( $tablerows as $key=>$param ) {
                                if($key == 'title')
                                    continue;
                                if(is_array($param)){
                                    $key_input = $key;
                                    if($param['type'] == 'checkbox'){
                                        $meta_option_on = get_post_meta((int)$directory_type_select, $key, true);
                                        if($meta_option_on == 'on'){
                                            $$key = $meta_option_on;
                                        }
                                    }
                                    if($param['type'] == 'text'){
                                        $keyinputtitle = get_post_meta($directory_type_select, $key, true);
                                        if(empty($keyinputtitle))
                                            $keyinputtitle = $field_title;
                                        $$key_input = $keyinputtitle;
                                        
                                }
                            }
                        }
                    }

                }
            }
            $randId    = cs_generate_random_string(5);
            
            $width_thumb      = 370;
            $height_thumb     = 280;
            $image_url 		  = get_post_meta( get_the_ID(), '_directory_image_gallery', true );
            if ( isset( $image_url ) &&  $image_url !='' ){
				$image_url = array_filter( explode( ',', $image_url ) );
			}
			
            if ( isset( $image_url ) && $image_url !='' && is_array( $image_url ) ) {
                $image_url         = cs_attachment_image_src( $image_url[0] ,$width_thumb,$height_thumb); 
            } else {
                $image_url    = get_template_directory_uri().'/assets/images/no-image4x3.jpg';
            }
        ?>
        <article class="<?php echo sanitize_html_class( $cs_directory_grid_layout );?>">
          <div class="directory-section">
            
          <?php if ( isset( $image_url ) && $image_url != '' ) { ?>
                <div class="cs_thumbsection">
                    <figure>
                    	<a href="<?php echo esc_url(get_permalink($cs_post_id));?>">
							<?php echo cs_show_featured_text($cs_post_id) ;?>
                        	<img alt="" src="<?php echo esc_url( $image_url );?>">
                        </a>
                        <?php cs_total_ad_rating($cs_post_id); ?>
                     </figure>
                </div>
          <?php }?> 
          <div class="content_info">
                <h2 itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing">
                <a href="<?php echo esc_url(get_permalink($cs_post_id ));?>">
                 
                        <?php echo wp_trim_words(get_the_title($cs_post_id),$title_limit,'...'); ?>
                </a></h2>
                 <div class="cs-up-section">
				<?php 
				// show ad urgetn or not
				cs_ad_urgent($cs_post_id);
				if(isset($cs_post_price_saleprice_option) && $cs_post_price_saleprice_option == 'on' && 1 == 0){?>
                    <div class="dr_pricesection" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
						<?php echo cs_get_directory_price( $cs_post_id ); ?>
                    </div>
                <?php } ?>
                </div>
                <?php
                $custom_fields = '';
                
                if ( $meta_value == '' ) {
                    $meta_value    = $directory_type_select;
                }
                    
                if($cs_directory_fields_count <> 'none' ){
					cs_get_post_specification_list($cs_post_id,$meta_value,$cs_directory_fields_count);
				}else{
					echo '<p>'.cs_get_content($cs_post_id,40).'</p>';
					//echo '<p>'.cs_get_the_excerpt('150','false','').'</p>';	
				}
                ?>
                
                <!-- Directory ShortOption -->
                <div class="dr_shortoption">
                    <div class="cs-organizer">
                    <?php 
                        $cs_page_id = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
                        $cs_display_image = '';
                            $cs_display_image = cs_get_user_avatar(1 ,$organizerID);
                            if( $cs_display_image <> ''){?>
                                <figure><a class="info-thumb" href="<?php echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>"><img height="30" width="30" src="<?php echo esc_url( $cs_display_image );?>" alt=""  /></a></figure>
                            <?php }else{?>
                                <figure><a class="info-thumb" href="<?php echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>"><?php echo get_avatar(get_the_author_meta('user_email',$organizerID), apply_filters('PixFill_author_bio_avatar_size', 30));?></a></figure>
                        <?php }?>
                      <!-- <span class="organizer-name">
                        <a href="<?php // echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>"><?php // echo get_the_author_meta('display_name',$organizerID );?></a>
                       </span> -->
                    </div>
                    <div class="dr_location post-<?php echo intval($cs_post_id);?>">
                        <?php
                             if(isset($cs_post_favourites_option) && $cs_post_favourites_option == 'on'){
                                cs_add_dirpost_favourite($cs_post_id);
                             }
                        ?>
                    </div>
                </div>
            </div>
          </div>
        </article>
       <?php 
         endwhile;
         wp_reset_postdata();
		 echo '</div>';
		         
         $qrystr = '';
         if ( $directory_pagination == "Show Pagination" and $count_post > $directory_per_page and $directory_per_page > 0) {
             if ( isset($_GET['sort']) )    $qrystr .= "&amp;sort=".$_GET['sort'];
             if ( isset($_GET['page_id']) ) $qrystr .= "&amp;page_id=".$_GET['page_id'];
             echo cs_pagination($count_post, $directory_per_page,$qrystr);
         }
           echo '</div></div>';
        } else {
          echo '<div class="col-md-12"><div class="succ_mess"><p>';
            esc_html_e('No Directories Found','directory');
          echo '</p></div></div></div>';
      }
      
      if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ){
          echo '</div>';
      }
      
      if ( isset( $cs_directory_filterable ) && $cs_directory_filterable =='Yes' ) {
              cs_get_directory_filters( $directory_cat, $directory_type_select , $cs_switch_views , $directory_view , $cs_directory_fields_count,'section','' );
      }
      
      if($cs_chek_section_view == 'wide'){
          echo '</div>';
      }
    
    }
}

//====================================================================
// Directory Grid Shortcode
//====================================================================
if (!function_exists('cs_directory_grid_two')) {
    function cs_directory_grid_two( $atts = '', $cs_directory_grid_layout = 'col-md-4', $cs_listingType = 'normal' , $args ='', $directory_id ='' )
    {
        global $post,$wpdb,$cs_xmlObject,$cs_theme_options, $column_container,$cs_node_id,$cs_elem_id,$cs_paged_id;

        $cs_chek_section_view = '';
        if ( isset( $column_container ) ){
            $column_attributes = $column_container->attributes();
            $cs_chek_section_view = $column_attributes->cs_section_view;    
        }
        
        if ( $cs_listingType == 'normal' ) { 
        
        $defaults = array( 'directory_title' => '', 'directory_cat' => '','cs_directory_fields_count'=>'4', 'cs_directory_filter' => '', 'cs_featured_on_top' => 'Yes', 'cs_listing_sorting' => 'recent','cs_directory_header' => '','cs_directory_rev_slider' => '',' cs_directory_map_style' =>'style-1','cs_directory_banner' => '','cs_directory_adsense' => '','cs_subheader_bg_color' => '','cs_subheader_padding_top' => '','cs_subheader_padding_bottom' => '','directory_view' => '','cs_switch_views' => '','directory_type' => '','directory_pagination'=>'','cs_directory_filterable' => '','cs_directory_sortable' => '','directory_per_page' => '10');
        extract( shortcode_atts( $defaults, $atts ) );

        $cs_directory_options    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
		$cs_page_id    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
		$cs_directory_map_style = isset($cs_directory_map_style) ? $cs_directory_map_style : 'style-1';

        
        date_default_timezone_set('UTC');
        $current_time = current_time('Y/m/d');
        
        ob_start();
        
        $organizer_filter 			= '';
        $user_meta_key              = '';
        $user_meta_value            = '';
        $meta_compare 				= "=";
        $meta_value   				= '';
        $meta_key      				= '';
        
        if ( isset( $cs_switch_views ) && $cs_switch_views !='' ){
            $cs_switch_views = explode(",", $cs_switch_views);
        } else {
            $cs_switch_views    =  array();
        }

        //==Filters End
        
        if ( isset( $directory_type ) && $directory_type == 0 ) {
            $directory_type    = '';
        }
        
        $meta_compare 	= "=";
        $meta_value   	= $directory_type;
        $meta_key       = 'directory_type_select';
        
        $meta_fields_array = array();
        $meta_fields_array = array('relation' => 'AND',);

        $backendFilter    = true; 
        $sort_key         = '';
        $meta_type        = 'CHAR';
        
        $orderby   		  = 'meta_value';
        $order        	  = 'DESC';

		 $cs_directory_options    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
        $cs_counter_directory = 0;
            
        if ( empty($_GET['page_id_all']) ) $_GET['page_id_all'] = 1;
            
        if ( $directory_type <> "" ) {
            $args = array(
                        'posts_per_page'          =>         "-1",
                        'post_type'               =>         'directory',
                        'post_status'             =>         'publish',
                        'orderby'                 =>         $orderby,
                        'order'                   =>         $order,
                    );

            $meta_fields_array[] = array(
                                        'key'         => $meta_key,
                                        'value'       => $meta_value,
                                        'compare'     => $meta_compare,
                                    );
                                    
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter , $cs_featured_on_top , $cs_listing_sorting  );
        
        } else {
            $args = array(
                'posts_per_page'            =>         "-1",
                'post_type'                 =>         'directory',
                'post_status'               =>         'publish',
                'orderby'                   =>         $orderby,
                'order'                     =>         $order,
            );
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter , $cs_featured_on_top , $cs_listing_sorting  );
        } 

        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }

        $custom_query   = new WP_Query($args);
        $count_post     = 0;
        $counter        = 1;
        $count_post     = $custom_query->post_count;

        $args = array(
            'posts_per_page'            => "$directory_per_page",
            'paged'                     => $_GET['page_id_all'],
            'post_type'                 => 'directory',
            'meta_key'                  => (string)$sort_key,
            'meta_type'                 => $meta_type,
            'post_status'               => 'publish',
        );
                    
        $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter ,  $backendFilter , $cs_featured_on_top , $cs_listing_sorting  );
    
        $args['orderby'] = $orderby;
        $args['order']      = $order;
        
        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }

        cs_directory_sub_header($cs_directory_header, $cs_directory_banner, $args, $directory_type, $cs_directory_rev_slider, $cs_directory_adsense,$cs_directory_map_style);
        
        if( $cs_chek_section_view == 'wide' ){
            echo '<div class="directory-box">';
        }
        
        if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ){
            echo '<div class="section-content">';
        }
		
		echo '<div class="dynamic-listing">';
		
        if($cs_directory_header <> 'adsense' && $cs_directory_header <> 'plain-heading') {
            $section_title = '';
            if(isset($directory_title) && trim($directory_title) <> ''){
                $section_title = '<div class="col-md-12 cs-section-title"><h2>'.$directory_title.'</h2></div>';
				echo cs_allow_special_char($section_title);
            }
        }
        
        if ( ! isset( $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] ) ) {
            cs_set_session( $directory_view,$directory_type,$orderby,$order,$cs_paged_id,'false','',$cs_directory_fields_count,$directory_per_page,$cs_switch_views,$cs_directory_filterable,$directory_title,$directory_pagination,$cs_directory_sortable,$cs_elem_id);
        }
        
            cs_get_directory_top_filters( $cs_switch_views , $directory_view , $atts );
        } else {
            
            if ( isset( $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] ) && $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] !='' ) {
                 $sessionData                = $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'];
                 $cs_directory_fields_count  = $sessionData['fields_limit'];
                 $directory_per_page         = $sessionData['pagination'];
                 $cs_switch_views            = $sessionData['switch_views'];
                 $directory_view             = $sessionData['post_directory_view'];
                 $filterable                 = $sessionData['filterable'];
                 $sortable                   = $sessionData['sortable'];
                 $directory_title            = $sessionData['directory_title'];
            } else {
                $cs_directory_fields_count    = 4;
                $directory_per_page           = isset( $_GET['pagination'] ) ? $_GET['pagination'] : 10;
                $cs_switch_views              = 'list,grid,grid-box,grid-box-four-column,map';
                $cs_switch_views              = explode(",", $cs_switch_views);
                $directory_view               = 'grid-box';
                $filterable                   = 'No';
                $sortable                     = 'No';
                $directory_title              = '';
            }

            $meta_value                   = isset($directory_id) ? $directory_id : '';
            $directory_type_select        = isset($directory_id) ? $directory_id : '';
            $args                         = $args;
            $directory_pagination         = "Show Pagination";
            $count_post                   = '';
            $cs_directory_filterable      = $filterable;
            $directory_cat                = '';
            $directory_type               = '';
            $cs_directory_grid_layout     = 'col-md-4';
            
            if( $cs_chek_section_view == 'wide' ){
                echo '<div class="directory-box">';
            }
            
            if ( $cs_directory_filterable == 'Yes' )
                echo '<div class="section-content">';
            
			echo '<div class="dynamic-listing">';
			
            $section_title = '';
            if(isset($directory_title) && trim($directory_title) <> ''){
                $section_title = '<div class="col-md-12 cs-section-title"><h2>'.$directory_title.'</h2></div>';
				echo cs_allow_special_char($section_title);
            }
            cs_get_directory_top_filters( $cs_switch_views , $directory_view, '' );
        }
        
         if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ){
            $cs_directory_grid_layout    = 'col-md-4';
         }

        $custom_query = new WP_Query( $args );
        if ( $custom_query->have_posts() <> "" ) {  
         echo '<div class="cs-listing-wrapper"><div class="cs-directory lightbox grid_two_listing">'; 
            while ( $custom_query->have_posts() ): $custom_query->the_post();
            $width			= '370';
            $height         = '280';
            $title_limit    = 5;
            $background     = '';
			$cs_post_id 	= $post->ID;
            $cs_directory   = get_post_meta($cs_post_id, "cs_directory_meta", true);
            $views          = get_post_meta($cs_post_id, "cs_count_views", true);
            $directory_type_select = get_post_meta($cs_post_id, "directory_type_select", true);
			
            if ( $cs_directory <> "" ) {
                //$cs_xmlObject = new SimpleXMLElement($cs_directory);
            }
            
           $cs_page_id = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
            
			$organizerID = cs_get_organizer_id($cs_post_id );
            
            if(isset($directory_type_select) && $directory_type_select <> ''){
                $post_id = absint($directory_type_select);
                    $meta_options = cs_directory_custom_options_array();
                    if(is_array($meta_options)){
                        foreach( $meta_options['params'] as $table_key=>$tablerows ) {
                            $field_title = $tablerows['title'];
                            foreach( $tablerows as $key=>$param ) {
                                if($key == 'title')
                                    continue;
                                if(is_array($param)){
                                    $key_input = $key;
                                    if($param['type'] == 'checkbox'){
                                        $meta_option_on = get_post_meta((int)$directory_type_select, $key, true);
                                        if($meta_option_on == 'on'){
                                            $$key = $meta_option_on;
                                        }
                                    }
                                    if($param['type'] == 'text'){
                                        $keyinputtitle = get_post_meta($directory_type_select, $key, true);
                                        if(empty($keyinputtitle))
                                            $keyinputtitle = $field_title;
                                        $$key_input = $keyinputtitle;
                                        
                                }
                            }
                        }
                    }

                }
            }
            $randId    = cs_generate_random_string(5);
            
            $width_thumb      = 370;
            $height_thumb     = 280;
            $image_url 		  = get_post_meta( get_the_ID(), '_directory_image_gallery', true );
            if ( isset( $image_url ) &&  $image_url !='' ){
				$image_url = array_filter( explode( ',', $image_url ) );
			}
			
            if ( isset( $image_url ) && $image_url !='' && is_array( $image_url ) ) {
                $image_url         = cs_attachment_image_src( $image_url[0] ,$width_thumb,$height_thumb); 
            } else {
                $image_url    = get_template_directory_uri().'/assets/images/no-image4x3.jpg';
            }
        ?>
        <article class="<?php echo sanitize_html_class( $cs_directory_grid_layout );?>">
          <div class="directory-section">
        	<div class="cs_thumbsection">
                <figure>
                    <?php if ( isset( $image_url ) && $image_url != '' ) { ?>
                    <a href="<?php echo esc_url(get_permalink($cs_post_id));?>">
                        <?php echo cs_show_featured_text($cs_post_id) ;?>
                        <img alt="" src="<?php echo esc_url( $image_url );?>">
                        <span class="bg-grad"></span>
                    </a>
                    <?php }?> 
                    <figcaption>
                        <div class="cs-text">
                            <?php cs_total_ad_rating($cs_post_id); ?>
                            <h2 itemscope itemtype="http://schema.org/Thing" itemprop="name"><a href="<?php echo esc_url(get_permalink($cs_post_id ));?>">
                        		<?php echo wp_trim_words(get_the_title($cs_post_id),$title_limit,'...'); ?>
                            </a></h2>
                            <?php 
                            if(isset($cs_post_price_saleprice_option) && $cs_post_price_saleprice_option == 'on'){?>
                                <div class="dr_pricesection" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                    <?php echo cs_get_directory_price( $cs_post_id ); ?>
                                </div>
                            <?php } ?>
                        </div>
                   </figcaption>
               </figure>
            </div>
          </div>
          <div class="content_info">
                 <?php
                    $cs_locationAddress = cs_get_location( $cs_post_id );
                    if(isset($cs_post_location_option) && $cs_post_location_option == 'on'){
                        if ( isset ( $cs_locationAddress ) && $cs_locationAddress !='' ) {?>
                        <div class="cs-location-address" itemprop="Place" itemscope itemtype="http://schema.org/CreativeWork">
                         <i class="icon-map-marker"></i>
						   <?php echo esc_attr( $cs_locationAddress );?>
                       </div> 
                 <?php } } ?>
                
                 <div class="dr_location post-<?php echo intval($cs_post_id);?>">
					<?php
                         if(isset($cs_post_favourites_option) && $cs_post_favourites_option == 'on'){
                            cs_add_dirpost_favourite($cs_post_id);
                         }
                    ?>
                </div>
          </div>
        </article>
       <?php 
         endwhile;
         wp_reset_postdata();
		 echo '</div>';
		         
         $qrystr = '';
         if ( $directory_pagination == "Show Pagination" and $count_post > $directory_per_page and $directory_per_page > 0) {
             if ( isset($_GET['sort']) )    $qrystr .= "&amp;sort=".$_GET['sort'];
             if ( isset($_GET['page_id']) ) $qrystr .= "&amp;page_id=".$_GET['page_id'];
             echo cs_pagination($count_post, $directory_per_page,$qrystr);
         }
           echo '</div></div>';
        } else {
          echo '<div class="col-md-12"><div class="succ_mess"><p>';
            esc_html_e('No Directory Found','directory');
        echo '</p></div></div></div>';
      }
      
      if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ){
          echo '</div>';
      }
      
      if ( isset( $cs_directory_filterable ) && $cs_directory_filterable =='Yes' ) {
              cs_get_directory_filters( $directory_cat, $directory_type_select , $cs_switch_views , $directory_view , $cs_directory_fields_count,'section','' );
      }
      
      if($cs_chek_section_view == 'wide'){
          echo '</div>';
      }
    
    }
}

//====================================================================
// Directory Grid Shortcode
//====================================================================
if (!function_exists('cs_directory_grid_box_four_column')) {
    function cs_directory_grid_box_four_column( $atts = '', $cs_directory_grid_layout = 'col-md-3', $cs_listingType = 'normal' , $args ='', $directory_id ='' )
    {
        global $post,$wpdb,$cs_xmlObject,$cs_theme_options, $column_container,$cs_node_id,$cs_elem_id,$cs_paged_id;
		
        $cs_chek_section_view = '';
        if ( isset( $column_container ) ){
            $column_attributes = $column_container->attributes();
            $cs_chek_section_view = $column_attributes->cs_section_view;    
        }
        
        if ( $cs_listingType == 'normal' ) { 
        
        $defaults = array( 'directory_title' => '', 'directory_cat' => '','cs_directory_fields_count'=>'4', 'cs_directory_filter' => '', 'cs_featured_on_top' => 'Yes', 'cs_listing_sorting' => 'recent','cs_directory_header' => '','cs_directory_rev_slider' => '',' cs_directory_map_style' =>'style-1','cs_directory_banner' => '','cs_directory_adsense' => '','cs_subheader_bg_color' => '','cs_subheader_padding_top' => '','cs_subheader_padding_bottom' => '','directory_view' => '','cs_switch_views' => '','directory_type' => '','directory_pagination'=>'','cs_directory_filterable' => '','cs_directory_sortable' => '','directory_per_page' => '10');
        extract( shortcode_atts( $defaults, $atts ) );

        $cs_directory_options    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
		$cs_page_id    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
		$cs_directory_map_style = isset($cs_directory_map_style) ? $cs_directory_map_style : 'style-1';

        
        date_default_timezone_set('UTC');
        $current_time = current_time('Y/m/d');
        
        ob_start();
        
        $organizer_filter 			= '';
        $user_meta_key              = '';
        $user_meta_value            = '';
        $meta_compare 				= "=";
        $meta_value   				= '';
        $meta_key      				= '';
        
        if ( isset( $cs_switch_views ) && $cs_switch_views !='' ){
            $cs_switch_views = explode(",", $cs_switch_views);
        } else {
            $cs_switch_views    =  array();
        }

        //==Filters End
        
        if ( isset( $directory_type ) && $directory_type == 0 ) {
            $directory_type    = '';
        }
        
        $meta_compare 	= "=";
        $meta_value   	= $directory_type;
        $meta_key       = 'directory_type_select';
        
        $meta_fields_array = array();
        $meta_fields_array = array('relation' => 'AND',);

        $backendFilter    = true; 
        $sort_key         = '';
        $meta_type        = 'CHAR';
        
        $orderby   		  = 'meta_value';
        $order        	  = 'DESC';

		 $cs_directory_options    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
        $cs_counter_directory = 0;
            
        if ( empty($_GET['page_id_all']) ) $_GET['page_id_all'] = 1;
            
        if ( $directory_type <> "" ) {
            $args = array(
                        'posts_per_page'          =>         "-1",
                        'post_type'               =>         'directory',
                        'post_status'             =>         'publish',
                        'orderby'                 =>         $orderby,
                        'order'                   =>         $order,
                    );

            $meta_fields_array[] = array(
                                        'key'         => $meta_key,
                                        'value'       => $meta_value,
                                        'compare'     => $meta_compare,
                                    );
                                    
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
        
        } else {
            $args = array(
                'posts_per_page'            =>         "-1",
                'post_type'                 =>         'directory',
                'post_status'               =>         'publish',
                'orderby'                   =>         $orderby,
                'order'                     =>         $order,
            );
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter, $cs_featured_on_top , $cs_listing_sorting);
        } 

        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }

        $custom_query   = new WP_Query($args);
        $count_post     = 0;
        $counter        = 1;
        $count_post     = $custom_query->post_count;

        $args = array(
            'posts_per_page'            => "$directory_per_page",
            'paged'                     => $_GET['page_id_all'],
            'post_type'                 => 'directory',
            'meta_key'                  => (string)$sort_key,
            'meta_type'                 => $meta_type,
            'post_status'               => 'publish',
        );
                    
        $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter ,  $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
    
        $args['orderby'] = $orderby;
        $args['order']      = $order;
        
        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }

        cs_directory_sub_header($cs_directory_header, $cs_directory_banner, $args, $directory_type, $cs_directory_rev_slider, $cs_directory_adsense,$cs_directory_map_style);
        
        if( $cs_chek_section_view == 'wide' ){
            echo '<div class="directory-box">';
        }
        
        if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ){
            echo '<div class="section-content">';
        }
		
		echo '<div class="dynamic-listing">';
		
        if($cs_directory_header <> 'adsense' && $cs_directory_header <> 'plain-heading') {
            $section_title = '';
            if(isset($directory_title) && trim($directory_title) <> ''){
                $section_title = '<div class="col-md-12 cs-section-title"><h2>'.$directory_title.'</h2></div>';
				echo cs_allow_special_char($section_title);
            }
        }
        
        if ( ! isset( $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] ) ) {
            cs_set_session( $directory_view,$directory_type,$orderby,$order,$cs_paged_id,'false','',$cs_directory_fields_count,$directory_per_page,$cs_switch_views,$cs_directory_filterable,$directory_title,$directory_pagination,$cs_directory_sortable,$cs_elem_id);
        }
        
            cs_get_directory_top_filters( $cs_switch_views , $directory_view , $atts );
        } else {
            
            if ( isset( $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] ) && $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] !='' ) {
                 $sessionData                = $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'];
                 $cs_directory_fields_count  = $sessionData['fields_limit'];
                 $directory_per_page         = $sessionData['pagination'];
                 $cs_switch_views            = $sessionData['switch_views'];
                 $directory_view             = $sessionData['post_directory_view'];
                 $filterable                 = $sessionData['filterable'];
                 $sortable                   = $sessionData['sortable'];
                 $directory_title            = $sessionData['directory_title'];
            } else {
                $cs_directory_fields_count    = 4;
                $directory_per_page           = isset( $_GET['pagination'] ) ? $_GET['pagination'] : 10;
                $cs_switch_views              = 'list,grid,grid-box,grid-box-four-column,map';
                $cs_switch_views              = explode(",", $cs_switch_views);
                $directory_view               = 'grid-box-four-column';
                $filterable                   = 'No';
                $sortable                     = 'No';
                $directory_title              = '';
            }

            $meta_value                   = isset($directory_id) ? $directory_id : '';
            $directory_type_select        = isset($directory_id) ? $directory_id : '';
            $args                         = $args;
            $directory_pagination         = "Show Pagination";
            $count_post                   = '';
            $cs_directory_filterable      = $filterable;
            $directory_cat                = '';
            $directory_type               = '';
            $cs_directory_grid_layout     = 'col-md-3';
            
            if( $cs_chek_section_view == 'wide' ){
                echo '<div class="directory-box">';
            }
            
            if ( $cs_directory_filterable == 'Yes' )
                echo '<div class="section-content">';
            
			echo '<div class="dynamic-listing">';
			
            $section_title = '';
            if(isset($directory_title) && trim($directory_title) <> ''){
                $section_title = '<div class="col-md-12 cs-section-title"><h2>'.$directory_title.'</h2></div>';
				echo cs_allow_special_char($section_title);
            }
            cs_get_directory_top_filters( $cs_switch_views , $directory_view, '' );
        }
        
         if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ){
            $cs_directory_grid_layout    = 'col-md-3';
         }

        $custom_query = new WP_Query( $args );
        if ( $custom_query->have_posts() <> "" ) {  
         echo '<div class="cs-listing-wrapper"><div class="cs-directory lightbox grid_two_listing">'; 
            while ( $custom_query->have_posts() ): $custom_query->the_post();
            $width                = '370';
            $height               = '280';
            $title_limit          = 5;
            $background           = '';
			
			$cs_post_id = $post->ID;
            $cs_directory            = get_post_meta($cs_post_id, "cs_directory_meta", true);
            $views                   = get_post_meta($cs_post_id, "cs_count_views", true);
            $directory_type_select   = get_post_meta($cs_post_id, "directory_type_select", true);
			
            if ( $cs_directory <> "" ) {
                //$cs_xmlObject = new SimpleXMLElement($cs_directory);
            }
            
           $cs_page_id = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
            
			$organizerID = cs_get_organizer_id($cs_post_id );
            
            if(isset($directory_type_select) && $directory_type_select <> ''){
                $post_id = absint($directory_type_select);
                    $meta_options = cs_directory_custom_options_array();
                    if(is_array($meta_options)){
                        foreach( $meta_options['params'] as $table_key=>$tablerows ) {
                            $field_title = $tablerows['title'];
                            foreach( $tablerows as $key=>$param ) {
                                if($key == 'title')
                                    continue;
                                if(is_array($param)){
                                    $key_input = $key;
                                    if($param['type'] == 'checkbox'){
                                        $meta_option_on = get_post_meta((int)$directory_type_select, $key, true);
                                        if($meta_option_on == 'on'){
                                            $$key = $meta_option_on;
                                        }
                                    }
                                    if($param['type'] == 'text'){
                                        $keyinputtitle = get_post_meta($directory_type_select, $key, true);
                                        if(empty($keyinputtitle))
                                            $keyinputtitle = $field_title;
                                        $$key_input = $keyinputtitle;
                                        
                                }
                            }
                        }
                    }

                }
            }
			
            $randId    = cs_generate_random_string(5);
            
            $width_thumb      = 370;
            $height_thumb     = 280;
            $image_url 		  = get_post_meta( get_the_ID(), '_directory_image_gallery', true );
            
			if ( isset( $image_url ) &&  $image_url !='' ){
				$image_url = array_filter( explode( ',', $image_url ) );
			}
			
            if ( isset( $image_url ) && $image_url !='' && is_array( $image_url ) ) {
                $image_url    = cs_attachment_image_src( $image_url[0] ,$width_thumb,$height_thumb); 
            } else {
                $image_url    = get_template_directory_uri().'/assets/images/no-image4x3.jpg';
            }
        ?>
        <article class="<?php echo sanitize_html_class( $cs_directory_grid_layout );?>">
          <div class="directory-section">
        	<div class="cs_thumbsection">
                <figure>
                    <?php if ( isset( $image_url ) && $image_url != '' ) { ?>
                        <a href="<?php echo esc_url(get_permalink($cs_post_id));?>">
                            <?php echo cs_show_featured_text($cs_post_id) ;?>
                            <img alt="" src="<?php echo esc_url( $image_url );?>">
                        </a>
                    <?php }?> 
                    <figcaption>
                        <div class="cs-text">
                            <?php cs_total_ad_rating($cs_post_id); ?>
                            <h2 itemscope itemtype="http://schema.org/Thing" itemprop="name"><a href="<?php echo esc_url(get_permalink($cs_post_id ));?>">
		                        <?php echo wp_trim_words(get_the_title($cs_post_id),$title_limit,'...'); ?>
                            </a></h2>
                            <?php 
                            if(isset($cs_post_price_saleprice_option) && $cs_post_price_saleprice_option == 'on'){?>
                                <div class="dr_pricesection" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                    <?php echo cs_get_directory_price( $cs_post_id ); ?>
                                </div>
                            <?php } ?>
                        </div>
                   </figcaption>
               </figure>
            </div>
          </div>
          <div class="content_info">
                 <?php
                    $cs_locationAddress = cs_get_location( $cs_post_id );
                    if(isset($cs_post_location_option) && $cs_post_location_option == 'on'){
                        if ( isset ( $cs_locationAddress ) && $cs_locationAddress !='' ) {?>
                        <div class="cs-location-address" itemprop="Place" itemscope itemtype="http://schema.org/CreativeWork">
                         <i class="icon-map-marker"></i>
						   <?php echo esc_attr( $cs_locationAddress );?>
                       </div> 
                 <?php } } ?>
                
                 <div class="dr_location post-<?php echo intval($cs_post_id);?>">
					<?php
                         if(isset($cs_post_favourites_option) && $cs_post_favourites_option == 'on'){
                            cs_add_dirpost_favourite($cs_post_id);
                         }
                    ?>
                </div>
          </div>
        </article>
       <?php 
         endwhile;
         wp_reset_postdata();
		 echo '</div>';
		         
         $qrystr = '';
         if ( $directory_pagination == "Show Pagination" and $count_post > $directory_per_page and $directory_per_page > 0) {
             if ( isset($_GET['sort']) )    $qrystr .= "&amp;sort=".$_GET['sort'];
             if ( isset($_GET['page_id']) ) $qrystr .= "&amp;page_id=".$_GET['page_id'];
             echo cs_pagination($count_post, $directory_per_page,$qrystr);
         }
           echo '</div></div>';
        } else {
          echo '<div class="col-md-12"><div class="succ_mess"><p>';
            esc_html_e('No Directory Found3','directory');
        echo '</p></div></div></div>';
      }
      
      if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ){
          echo '</div>';
      }
      
      if ( isset( $cs_directory_filterable ) && $cs_directory_filterable =='Yes' ) {
              cs_get_directory_filters( $directory_cat, $directory_type_select , $cs_switch_views , $directory_view , $cs_directory_fields_count,'section','' );
      }
      
      if($cs_chek_section_view == 'wide'){
          echo '</div>';
      }
    
    }
}

//====================================================================
// Directory Carousel View
//====================================================================
if (!function_exists('cs_directory_carousel')) {
    function cs_directory_carousel( $atts = '', $cs_listingType = 'normal' , $args ='', $directory_id ='' )
    {
        global $post,$wpdb,$cs_xmlObject,$cs_theme_options, $column_container,$cs_node_id,$cs_elem_id,$cs_paged_id;

        $defaults = array( 'directory_title' => '', 'directory_cat' => '','cs_directory_fields_count'=>'4', 'cs_directory_filter' => '', 'cs_featured_on_top' => 'Yes', 'cs_listing_sorting' => 'recent','cs_directory_header' => '','cs_directory_rev_slider' => '','cs_directory_map_style'=>'style-1','cs_directory_banner' => '','cs_directory_adsense' => '','cs_subheader_bg_color' => '','cs_subheader_padding_top' => '','cs_subheader_padding_bottom' => '','directory_view' => '','cs_switch_views' => '','directory_type' => '','directory_pagination'=>'','cs_directory_filterable' => '','cs_directory_sortable' => '','directory_per_page' => '10');
        extract( shortcode_atts( $defaults, $atts ) );

       $cs_directory_options    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
	   $cs_page_id = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';

        
        date_default_timezone_set('UTC');
        $current_time = current_time('Y/m/d');
        
        ob_start();
        
        $organizer_filter 			= '';
        $user_meta_key              = '';
        $user_meta_value            = '';
        $meta_compare 				= "=";
        $meta_value   				= '';
        $meta_key      				= '';
        
        if ( isset( $cs_switch_views ) && $cs_switch_views !='' ){
            $cs_switch_views = explode(",", $cs_switch_views);
        } else {
            $cs_switch_views    =  array();
        }
        
        //==Filters End
        if ( isset( $directory_type ) && $directory_type == 0 ) {
            $directory_type    = '';
        }
        
        $meta_compare = "=";
        $meta_value   = $directory_type;
        $meta_key     = 'directory_type_select';
        
        $meta_fields_array = array();
        $meta_fields_array = array('relation' => 'AND',);

        $backendFilter    = true; 
        $sort_key         = '';
        $meta_type        = 'CHAR';
		$orderby    	  = 'meta_value';
		$order         	  = 'DESC';

       $cs_directory_options    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
       $cs_counter_directory = 0;
            
            
        if ( $directory_type <> "" ) {
            $args = array(
                        'posts_per_page'             =>         "-1",
                        'post_type'                  =>         'directory',
                        'post_status'                =>         'publish',
                        'orderby'                    =>         $orderby,
                        'order'                      =>         $order,
                    );

            $meta_fields_array[] = array(
                                        'key'         => $meta_key,
                                        'value'     => $meta_value,
                                        'compare'   => $meta_compare,
                                    );
                                    
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter , $cs_featured_on_top , $cs_listing_sorting  );
        
        } else {
            $args = array(
                'posts_per_page'	=>	"-1",
                'post_type'         =>  'directory',
                'post_status'       =>  'publish',
                'orderby'           =>  $orderby,
                'order'             =>  $order,
            );
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter , $cs_featured_on_top , $cs_listing_sorting  );
        } 

        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }

        $custom_query   = new WP_Query($args);
        $count_post     = 0;
        $counter         = 1;
        $count_post     = $custom_query->post_count;

        $args = array(
            'posts_per_page'            => "$directory_per_page",
            'post_type'                    => 'directory',
            'meta_key'                    => (string)$sort_key,
            'meta_type'                    => $meta_type,
            'post_status'                => 'publish',
        );
                    
        $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter ,  $backendFilter , $cs_featured_on_top , $cs_listing_sorting  );
    
        $args['orderby'] 	= $orderby;
        $args['order']      = $order;
        
        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }
        
        $cs_chek_section_view = '';
        if ( isset( $column_container ) ){
            $column_attributes = $column_container->attributes();
            $cs_chek_section_view = $column_attributes->cs_section_view;    
        }
        
        if($cs_directory_header <> 'adsense' && $cs_directory_header <> 'plain-heading') {
            $section_title = '';
            if(isset($directory_title) && trim($directory_title) <> ''){
                $section_title = '<div class="col-md-12 cs-section-title"><h2>'.$directory_title.'</h2></div>';
				echo cs_allow_special_char($section_title);
            }
        }

        $cs_directory_grid_layout    = 'col-md-12';
        cs_owl_carousel();
        $randId    = cs_generate_random_string(5);     
        $custom_query = new WP_Query( $args );
        if ( $custom_query->have_posts() <> "" ) {  
         
         echo  '<div class="cs-directory grid_listing">'; 
         echo  '<script>  
                jQuery(document).ready(function($) {
                    $("#owl-directory-'.$randId.'").owlCarousel({
                        nav: true,
                        margin: 30,
                        navText: [
                            "<i class=icon-angle-left></i>",
                            "<i class=icon-angle-right></i>"
                        ],
                        responsive: {
                            0: {
                                items: 1 // In this configuration 1 is enabled from 0px up to 479px screen size 
                            },
                            480: {
                                items: 1, // from 480 to 677 
                                nav: false // from 480 to max 
                            },
                            678: {
                                items: 2, // from this breakpoint 678 to 959
                                center: false // only within 678 and next - 959
                            },
                            960: {
                                items: 3, // from this breakpoint 960 to 1199
                                center: false,
                                loop: false
                
                            },
                            1200: {
                                items: 4
                            }
                        }
                        });
                 }); 
            </script>';
         echo  '<div class="cs-blogslide col-md-12">';
         echo  '<div class="owl-carousel nxt-prv-v2 cs-theme-carousel " id="owl-directory-'.$randId.'">';
            while ( $custom_query->have_posts() ): $custom_query->the_post();
            $width          = '370';
            $height         = '280';
            $title_limit    = 5;
            $background     = '';
			$cs_post_id 	= $post->ID;
            $cs_directory           = get_post_meta($cs_post_id, "cs_directory_meta", true);
            $views                  = get_post_meta($cs_post_id, "cs_count_views", true);
            $directory_type_select 	= get_post_meta($cs_post_id, "directory_type_select", true);
            if ( $cs_directory <> "" ) {
                $cs_xmlObject = new SimpleXMLElement($cs_directory);
            }
            
           $cs_page_id = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
            
			$organizerID = cs_get_organizer_id( $cs_post_id );
                    
            if(isset($directory_type_select) && $directory_type_select <> ''){
                $post_id = absint($directory_type_select);
                    $meta_options = cs_directory_custom_options_array();
                    if(is_array($meta_options)){
                        foreach( $meta_options['params'] as $table_key=>$tablerows ) {
                            $field_title = $tablerows['title'];
                            foreach( $tablerows as $key=>$param ) {
                                if($key == 'title')
                                    continue;
                                if(is_array($param)){
                                    $key_input = $key;
                                    if($param['type'] == 'checkbox'){
                                        $meta_option_on = get_post_meta((int)$directory_type_select, $key, true);
                                        if($meta_option_on == 'on'){
                                            $$key = $meta_option_on;
                                        }
                                    }
                                    if($param['type'] == 'text'){
                                        $keyinputtitle = get_post_meta($directory_type_select, $key, true);
                                        if(empty($keyinputtitle))
                                            $keyinputtitle = $field_title;
                                        $$key_input = $keyinputtitle;
                                        
                                }
                            }
                        }
                    }
                }
            }
            $randId    		= cs_generate_random_string(5);
            $width_thumb    = 370;
            $height_thumb   = 280;
            $image_url 		= get_post_meta( get_the_ID(), '_directory_image_gallery', true );
            if ( isset( $image_url ) &&  $image_url !='' ){
				$image_url = array_filter( explode( ',', $image_url ) );
			}
			
            if ( isset( $image_url ) && $image_url !='' && is_array( $image_url ) ) {
                $image_url         = cs_attachment_image_src( $image_url[0] ,$width_thumb,$height_thumb); 
            } else {
                $image_url    = get_template_directory_uri().'/assets/images/no-image4x3.jpg';
            }
        ?>
        <article>
          <div class="directory-section">
         <?php if ( isset( $image_url ) && $image_url != '' ) { ?>
                <div class="cs_thumbsection">
                    <figure><a href="<?php echo esc_url(get_permalink($cs_post_id));?>">
                    	<?php echo cs_show_featured_text($cs_post_id); ?>	
                    	<img alt="" src="<?php echo esc_url( $image_url );?>"></a>
                        <?php cs_total_ad_rating($cs_post_id);  ?>
                    </figure>
                </div>
          <?php }?> 
          <div class="content_info">
                <h2 itemprop="name" itemscope itemtype="http://schema.org/Thing"><a href="<?php echo esc_url(get_permalink($cs_post_id));?>">
                        <?php echo wp_trim_words(get_the_title($cs_post_id),$title_limit,'...'); ?>
                   </a>
                </h2>
                <?php
				// show ad urgetn or not
				cs_ad_urgent($cs_post_id);
				if(isset($cs_post_price_saleprice_option) && $cs_post_price_saleprice_option == 'on'){?>
                    <div class="dr_pricesection" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                       <?php echo cs_get_directory_price( $cs_post_id ); ?>
                    </div>
                <?php }
				$custom_fields = '';
				if ( $meta_value == '' ) {
					$meta_value    = $directory_type_select;
				}
                ?>
                 <!-- Directory ShortOption -->
                <div class="dr_shortoption">
                    <div class="cs-organizer">
                    <?php 
                        $cs_page_id = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
                        $cs_display_image = '';
                            $cs_display_image = cs_get_user_avatar(1 ,$organizerID);
                            if( $cs_display_image <> ''){?>
                                <figure><a class="info-thumb" href="<?php echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>"><img height="30" width="30" src="<?php echo esc_url( $cs_display_image );?>"  alt="" /></a></figure>
                            <?php }else{?>
                                <figure><a class="info-thumb" href="<?php echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>"><?php echo get_avatar(get_the_author_meta('user_email',$organizerID), apply_filters('PixFill_author_bio_avatar_size', 30));?></a></figure>
                        <?php }?>
                      <!-- <span class="organizer-name">
                        <a href="<?php // echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>"><?php // echo get_the_author_meta('display_name',$organizerID );?></a>
                       </span>
                    </div> -->
                    <div class="dr_location post-<?php echo intval($cs_post_id);?>">
                        <?php
                             if(isset($cs_post_favourites_option) && $cs_post_favourites_option == 'on'){
                                if ( is_user_logged_in() ) {
                                    cs_add_dir_favourite_carosel($cs_post_id);
                                }else{
                                    ?>
                                    <a class="cs-add-wishlist tolbtn" data-target="#loginSection" data-toggle="modal" data-original-title="<?php _e('Add to Favourite','directory') ?>" href="#">
                                        <i class="icon-star-o"></i>
                                    </a>
                                    <?php
                                }
                             }
                        ?>
                    </div>
                </div>
            </div>
          </div>
        </article>
        <?php 
         endwhile;
         wp_reset_postdata();        
      
           echo '</div></div></div>';
         ?>
         <div id="cs-add-wishlist-wrap"></div>
         <div aria-hidden="true" role="dialog" tabindex="-1" id="loginSection" class="modal fade add-to-favborites-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                              <?php 
                               $cs_login_message =__('Login to add listings in favorites.','directory');
                              echo cs_login_section($cs_login_message,'','cs-login-favorites');?>
                            </div>
                        </div>
                    </div>
                </div>
         <?php
        } else {
          echo '<div class="col-md-12"><div class="succ_mess"><p>';
            esc_html_e('No Directory Found','directory');
        echo '</p></div></div>';
      }
    
    }
}

//=======================================================================
// Directory Listing Shortcode
//=======================================================================
if (!function_exists('cs_directory_listing')) {
    function cs_directory_listing( $atts='' , $cs_listingType = 'normal' , $args ='' , $directory_id ='' )
    {
        global $post,$wpdb,$cs_xmlObject,$cs_theme_options, $column_container,$cs_node_id,$cs_elem_id,$cs_paged_id;
        $cs_chek_section_view = '';
        if ( isset( $column_container ) ){
            $column_attributes = $column_container->attributes();
            $cs_chek_section_view = $column_attributes->cs_section_view;    
        }
		
       	if ( $cs_listingType == 'normal' ) {

        $defaults = array( 'directory_title' => '', 'directory_cat' => '','cs_directory_fields_count'=>'4', 'cs_directory_filter' => '', 'cs_featured_on_top' => 'Yes', 'cs_listing_sorting' => 'recent','cs_directory_header' => '','cs_directory_rev_slider' => '','cs_directory_map_style' =>'style-1','cs_directory_banner' => '','cs_directory_adsense' => '','cs_subheader_bg_color' => '','cs_subheader_padding_top' => '','cs_subheader_padding_bottom' => '','directory_view' => '','cs_switch_views' => '','directory_type' => '','directory_pagination'=>'','cs_directory_filterable' => '','cs_directory_sortable' => '','directory_per_page' => '10' );
        global $directory_title,$cs_subheader_bg_color,$cs_subheader_padding_top,$cs_subheader_padding_bottom;
        
        extract( shortcode_atts( $defaults, $atts ) );
        
        date_default_timezone_set('UTC');
        $current_time = current_time('Y/m/d');
        
        ob_start();
        
        $organizer_filter 			  = '';
        $user_meta_key                = '';
        $user_meta_value              = '';
        $meta_compare                 = "=";
        $meta_value                   = '';
        $meta_key                     = '';
        
        if ( isset( $cs_switch_views ) && $cs_switch_views !='' ){
            $cs_switch_views = explode(",", $cs_switch_views);
        } else {
            $cs_switch_views    =  array();
        }
        
        if(isset($_GET['type'])){
            $directory_type_get = isset($_GET['type']) ? $_GET['type'] : '';
            if(isset($directory_type_get) && $directory_type_get <> ''){
                $dirargs=array(
                    'name'             => (string)$directory_type_get,
                    'post_type'        => 'directory_types',
                    'post_status'      => 'publish',
                    'posts_per_page'   => 1
                );
                $dir_posts = get_posts( $dirargs );
                if( $dir_posts ) {
                    $directory_type = (int)$dir_posts[0]->ID;
                }
            }
        }
        
        if ( isset( $directory_type ) && $directory_type == 0 ) {
            $directory_type    = '';
        }
        
        $meta_compare = "=";
        $meta_value   = $directory_type;
        $meta_key      = 'directory_type_select';
        
        $meta_fields_array = array();
        $meta_fields_array = array('relation' => 'AND',);

        $backendFilter    = true; 
        $sort_key         = '';
        $meta_type        = 'CHAR';
       	$orderby    	  = 'meta_value';
        $order      	  = 'DESC';
		
        $cs_directory_options = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
        $cs_counter_directory = 0;
		
        if ( empty($_GET['page_id_all']) ) $_GET['page_id_all'] = 1;
        if ( $directory_type <> "" ) {
            $args = array(
                'posts_per_page'	=> "-1",
                'post_type'         => 'directory',
                'post_status'       => 'publish',
                'orderby'           => $orderby,
                'order'             => $order,
            );
            
                     $meta_fields_array = array(
                                               'key'       => $meta_key,
                                               'value'     => $meta_value,
                                               'compare'   => $meta_compare,
                                           );
         
		
								
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter , $cs_featured_on_top , $cs_listing_sorting  );
         } else {
            $args = array(
                'posts_per_page'     => "-1",
                'post_type'          => 'directory',
                'post_status'        => 'publish',
                'orderby'            => $orderby,
                'order'              => $order,
            );
			
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter , $cs_featured_on_top , $cs_listing_sorting );
        } 

        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }

        $custom_query   = new WP_Query($args);
        $count_post     = 0;
        $counter        = 1;
        $count_post     = $custom_query->post_count;

        $args = array(
            'posts_per_page'          => "$directory_per_page",
            'paged'                   => $_GET['page_id_all'],
            'post_type'               => 'directory',
            'meta_key'                => (string)$sort_key,
            'meta_type'               => $meta_type,
            'post_status'             => 'publish',
        );
		

        $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
        $args['orderby'] = $orderby;
        $args['order']   = $order;
        
        
        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }

        cs_directory_sub_header($cs_directory_header, $cs_directory_banner, $args, $directory_type, $cs_directory_rev_slider, $cs_directory_adsense,$cs_directory_map_style);
        
        if( $cs_chek_section_view == 'wide' ){
            echo '<div class="directory-box">';
        }
        
        if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ) {
            echo '<div class="section-content">';    
        }
        
		echo '<div class="dynamic-listing">';
		
        if($cs_directory_header <> 'adsense' && $cs_directory_header <> 'plain-heading') {
            $section_title = '';
            if(isset($directory_title) && trim($directory_title) <> ''){
                $section_title = '<div class="col-md-12 cs-section-title"><h2>'.$directory_title.'</h2></div>';
				echo cs_allow_special_char($section_title);
            }
        }
        
        //==Session Start
        if ( ! isset( $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] ) ) {
            cs_set_session( $directory_view,$directory_type,$orderby,$order,$cs_paged_id,'false','',$cs_directory_fields_count,$directory_per_page,$cs_switch_views,$cs_directory_filterable,$directory_title,$directory_pagination,$cs_directory_sortable,$cs_elem_id);
        }
        //==Session End
        
        	cs_get_directory_top_filters($cs_switch_views , $directory_view, $atts );
        } else {
			
            if ( isset( $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] ) && $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] !='' ) {
				
                 $sessionData                = $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'];
 				 $cs_directory_fields_count  = isset( $sessionData['fields_limit'] ) ? $sessionData['fields_limit'] : 4;
                 $directory_per_page         = $sessionData['pagination'];
                 $cs_switch_views            = $sessionData['switch_views'];
                 $directory_view             = $sessionData['post_directory_view'];
                 $filterable                 = $sessionData['filterable'];
                 $sortable                   = $sessionData['sortable'];
                 $directory_title            = $sessionData['directory_title'];
            } else {
                $cs_directory_fields_count    = 4;
                $directory_per_page           = isset( $_GET['pagination'] ) ? $_GET['pagination'] : 10;
                $cs_switch_views              = 'list,grid,grid-box,grid-box-four-column,map';
                $cs_switch_views              = explode(",", $cs_switch_views);
                $directory_view               = 'listing';
                $filterable                   = 'No';
                $sortable                     = 'No';
                $directory_title              = '';
            }
            if ( isset( $_GET['type'] ) && $_GET['type'] !='' ) {
                $meta_value             = $directory_id;
                $directory_type_select	= $directory_id;
            } else {
                $meta_value             = '';
                $directory_type_select  = '';
            }
            $args                       = $args;
            $directory_pagination       = "Show Pagination";
         $count_post ='';
            $directory_cat              = '';
            $directory_type             = '';
            $cs_directory_filterable    = $filterable;
            
			    
		
            if( $cs_chek_section_view == 'wide' ){
                echo '<div class="directory-box">';
            }
            if ( $cs_directory_filterable == 'Yes' )
                echo '<div class="section-content">';
            
			echo '<div class="dynamic-listing">';
			
            $section_title = '';
            if(isset($directory_title) && trim($directory_title) <> ''){
                $section_title = '<div class="col-md-12 cs-section-title"><h2>'.$directory_title.'</h2></div>';
				echo cs_allow_special_char($section_title);
            }
            cs_get_directory_top_filters( $cs_switch_views , $directory_view, '' );
            
        }
		//removed
		 
		 // print_r($args);
	   $custom_query = new WP_Query($args);
        $cs_page_id = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
		
		//display number of postsdynamic_post_location_country
		echo '<div class="cs-number-posts"> <p>We found '.$custom_query->found_posts.' posts </p></div>';
		
		if ( $custom_query->have_posts() <> "" ) { 
         echo '<div class="cs-listing-wrapper"><div class="col-md-12 cs-directory default_listing lightbox">'; 
         
            while ( $custom_query->have_posts() ): $custom_query->the_post();
            $width                = '370';
            $height                = '280';
			$cs_post_id = $post->ID;
            $directory_image_gallery = get_post_meta( get_the_ID(), '_directory_image_gallery', true );
            $title_limit         	= 15;
            $background           	= '';
            $cs_directory           = get_post_meta($cs_post_id, "cs_directory_meta", true);
            $dir_pkg_expire_date    = get_post_meta($cs_post_id, "dir_pkg_expire_date", true);
            $directory_type_select  = get_post_meta($cs_post_id, "directory_type_select", true);
			$cs_views        		= get_post_meta($post->ID, "cs_count_views", true);
			
			if ( $cs_directory <> "" ) {
                $cs_xmlObject = new SimpleXMLElement($cs_directory);
            }
            // get organiser id
			$organizerID = cs_get_organizer_id($cs_post_id);
            // check post is urgent or not
		    if(isset($directory_type_select) && $directory_type_select <> ''){
                $post_id = absint($directory_type_select);
                    $meta_options = cs_directory_custom_options_array();
                    if(is_array($meta_options)){
                        foreach( $meta_options['params'] as $table_key=>$tablerows ) {
                            $field_title = $tablerows['title'];
                            foreach( $tablerows as $key=>$param ) {
                                if($key == 'title')
                                    continue;
                                if(is_array($param)){
                                    $key_input = $key;
                                    if($param['type'] == 'checkbox'){
                                        $meta_option_on = get_post_meta((int)$directory_type_select, $key, true);
                                        if($meta_option_on == 'on'){
                                            $$key = $meta_option_on;
                                        }
                                    }
                                    if($param['type'] == 'text'){
                                        $keyinputtitle = get_post_meta($directory_type_select, $key, true);
                                        if(empty($keyinputtitle))
                                            $keyinputtitle = $field_title;
                                        $$key_input = $keyinputtitle;
                                        
                                }
                            }
                        }
                    }
                }
            }		
 
            $randId    = cs_generate_random_string(5);
            $width_thumb    = 370;
            $height_thumb     = 280;
            $image_url = get_post_meta( get_the_ID(), '_directory_image_gallery', true );
            $image_url = array_filter( explode( ',', $image_url ) );
		    if ( isset( $image_url ) && ! empty( $image_url ) ) {
               if ( is_user_logged_in() and get_current_user_id() == '47') {
							$image_url = cs_attachment_image_src( $image_url[0] ,$width,$height);
						}else{
						 $image_url = isset($image_url[0]) ? cs_attachment_image_src( $image_url[0] ,$width_thumb,$height_thumb) : get_template_directory_uri().'/assets/images/no-image4x3.jpg'; 
						}
            } else {
                $image_url    = get_template_directory_uri().'/assets/images/no-image4x3.jpg';
            }
        ?>
        <article class="directory-section">
          <?php if ( isset( $image_url ) && $image_url != '' && 1 == 0 ) { ?>
          <div class="cs_thumbsection">
            <ul class="dr_thumbsection">
                  <li class="featured_thumb">
                      <a href="<?php echo esc_url(get_permalink($cs_post_id));?>">
                      		<?php echo cs_show_featured_text($cs_post_id);?>
                           <!--<img src="<?php echo esc_url( $image_url );?>" alt="">-->
                       </a>
                  </li>
             </ul>
          </div>
          <?php }?> 
          <div class="content_info">
            	
                <h2 itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing">
                	<a href="<?php echo esc_url(get_permalink($cs_post_id));?>" itemprop="name">
                        <?php echo wp_trim_words(get_the_title($cs_post_id),$title_limit,'...'); ?>
                    </a>
                    <?php cs_ad_urgent($cs_post_id); ?>
                </h2>
				<?php cs_total_ad_rating($cs_post_id); ?>
                <ul class="cs-user-date" style="float:right;">
                <li style="display:none;">
                    <a href="<?php echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>">
						<?php echo get_the_author_meta('display_name',$organizerID );?>
                    </a>
                </li>
                <?php
				if(isset($dir_pkg_expire_date) && $dir_pkg_expire_date){
				?>
                <li style=""><?php echo date_i18n(get_option('date_format'), strtotime($dir_pkg_expire_date)); ?></li>
                <?php
				}
				?>
                </ul>
                <?php
				
                if ( $meta_value == '' ) {
                     $meta_value = $directory_type_select;
                }
				// echo '<p>Location: ' . get_post_meta($cs_post_id,'dynamic_post_location_address',true) . '</p>';
				 echo '<div class="cs-locplace"><div class="cs-location">Location: </div><div class="cs-place">' . get_post_meta($cs_post_id,'dynamic_post_location_address',true) . '</div> </div>';
				
 				if($cs_directory_fields_count <> 'none' ){
					cs_get_post_specification_list($cs_post_id,$directory_type_select,$cs_directory_fields_count);
				}else{
					echo '<p>'.cs_get_content($cs_post_id,40).'</p>';	
					//echo '<p>'.cs_get_the_excerpt('150','false','').'</p>';	

				}
                ?>
                <div class="cs-detail-info" style="display:none">
				<?php
                    $cs_locationAddress = cs_get_location( $cs_post_id );
                    if(isset($cs_post_location_option) && $cs_post_location_option == 'on'){
                        if ( isset ( $cs_locationAddress ) && $cs_locationAddress !='' ) {?>
                        <div class="cs-location-address" itemscope itemtype="http://schema.org/CreativeWork" itemprop="Place">
                        	<i class="icon-map-marker"></i>
							<?php echo esc_attr( $cs_locationAddress );?>
                        </div>
                <?php } } 
				
				if( isset($cs_post_favourites_option) && $cs_post_favourites_option == 'on' ){
					echo '<div class="dr_location post-'.intval($cs_post_id).'">';
						cs_add_dirpost_favourite($cs_post_id);
					echo '</div>';
				}
				?>
                
                <?php
				if(isset($cs_post_price_saleprice_option) && $cs_post_price_saleprice_option == 'on'){?>
                    <div class="dr_pricesection" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                        <?php echo cs_get_directory_price( $cs_post_id ); ?>
                    </div>
                <?php } ?>
            </div>    
                <!-- Directory ShortOption -->
                <!--<div class="dr_shortoption">
                    <div class="cs-organizer">
                    	<?php 
                        $cs_page_id = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
                        $cs_display_image = '';
                            $cs_display_image = cs_get_user_avatar(1 ,$organizerID);
                            if( $cs_display_image <> ''){?>
                                <figure><a class="info-thumb" href="<?php echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>"><img height="30" width="30" src="<?php echo esc_url( $cs_display_image );?>"  alt="" /></a></figure>
                            <?php }else{?>
                                <figure><a class="info-thumb" href="<?php echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>"><?php echo get_avatar(get_the_author_meta('user_email',$organizerID), apply_filters('PixFill_author_bio_avatar_size', 30));?></a></figure>
                        <?php }?>
                       <span class="organizer-name">
                        <a href="<?php echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>">
                            <?php echo get_the_author_meta('display_name',$organizerID );?>
                        </a>
                       </span>
                    </div>
                    
                </div>-->
         </div>                 
       </article>
        <?php 
         endwhile;
         wp_reset_postdata();
		 echo '</div>';
         $qrystr = '';
		 
         if ( $directory_pagination == "Show Pagination" and $count_post > $directory_per_page and $directory_per_page > 0) {
             if ( isset($_GET['sort']) ) $qrystr .= "&amp;sort=".$_GET['sort'];
             if ( isset($_GET['page_id']) ) $qrystr .= "&amp;page_id=".$_GET['page_id'];
             echo cs_pagination($count_post, $directory_per_page,$qrystr);
         }
       
         echo '</div></div>';
        } else {
          echo '<div class="col-md-12"><div class="succ_mess"><p>';
            esc_html_e('No Directory Found','directory');
        echo '</p></div></div></div>';
      }
	  
      if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ){
          echo '</div>';
      }

      if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ) {
          cs_get_directory_filters($directory_cat, $directory_type, $atts , $directory_view , $cs_directory_fields_count ,'section','');
      }
      
      if( $cs_chek_section_view == 'wide'){
          echo '</div>';
      }
    }
}

//=======================================================================
// Directory Detailed View Shortcode$count_post
//=======================================================================
if (!function_exists('cs_directory_detailed')) {
    function cs_directory_detailed( $atts='' , $cs_listingType = 'normal' , $args ='' , $directory_id ='' )
    {
        global $post,$wpdb,$cs_xmlObject,$cs_theme_options, $column_container,$cs_node_id,$cs_elem_id,$cs_paged_id;
        $cs_chek_section_view = '';
        
		if ( isset( $column_container ) ){
            $column_attributes = $column_container->attributes();
            $cs_chek_section_view = $column_attributes->cs_section_view;    
        }
		
       	if ( $cs_listingType == 'normal' ) {

        $defaults = array( 'directory_title' => '', 'directory_cat' => '','cs_directory_fields_count'=>'4', 'cs_directory_filter' => '', 'cs_featured_on_top' => 'Yes', 'cs_listing_sorting' => 'recent','cs_directory_header' => '','cs_directory_rev_slider' => '','cs_directory_map_style' =>'style-1','cs_directory_banner' => '','cs_directory_adsense' => '','cs_subheader_bg_color' => '','cs_subheader_padding_top' => '','cs_subheader_padding_bottom' => '','directory_view' => '','cs_switch_views' => '','directory_type' => '','directory_pagination'=>'','cs_directory_filterable' => '','cs_directory_sortable' => '','directory_per_page' => '10');
        global $directory_title,$cs_subheader_bg_color,$cs_subheader_padding_top,$cs_subheader_padding_bottom;
        
        extract( shortcode_atts( $defaults, $atts ) );
        
        date_default_timezone_set('UTC');
        $current_time = current_time('Y/m/d');
        
        ob_start();
        
        $organizer_filter 			  = '';
        $user_meta_key                = '';
        $user_meta_value              = '';
        $meta_compare                 = "=";
        $meta_value                   = '';
        $meta_key                     = '';
        
        if ( isset( $cs_switch_views ) && $cs_switch_views !='' ){
            $cs_switch_views = explode(",", $cs_switch_views);
        } else {
            $cs_switch_views = array();
        }
        
        if(isset($_GET['type'])){
            $directory_type_get = isset($_GET['type']) ? $_GET['type'] : '';
            if(isset($directory_type_get) && $directory_type_get <> ''){
                $dirargs=array(
                    'name'             => (string)$directory_type_get,
                    'post_type'        => 'directory_types',
                    'post_status'      => 'publish',
                    'posts_per_page'   => 1
                );
                $dir_posts = get_posts( $dirargs );
                if( $dir_posts ) {
                    $directory_type = (int)$dir_posts[0]->ID;
                }
            }
        }
        
        if ( isset( $directory_type ) && $directory_type == 0 ) {
            $directory_type    = '';
        }
        
        $meta_compare = "=";
        $meta_value   = $directory_type;
        $meta_key      = 'directory_type_select';
        
        $meta_fields_array = array();
        $meta_fields_array = array('relation' => 'AND',);

        $backendFilter    = true;
        $sort_key         = '';
        $meta_type        = 'CHAR';
       	$orderby    	  = 'meta_value';
        $order      	  = 'DESC';
       
        $cs_directory_options = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
        $cs_counter_directory = 0;
            
        if ( empty($_GET['page_id_all']) ) $_GET['page_id_all'] = 1;
         if ( $directory_type <> "" ) {
            $args = array(
                'posts_per_page'	=> "-1",
                'post_type'         => 'directory',
                'post_status'       => 'publish',
                'orderby'           => $orderby,
                'order'             => $order,
            );
            
          
            $meta_fields_array[] = array(
                                              'key'       => $meta_key,
                                              'value'     => $meta_value,
                                              'compare'   => $meta_compare,
                                          );
           /*
            $meta_fields_array[] = array(
                                               'key'       => 'dynamic_post_location_address',
                                               'value'     => '%Spain%',
                                               'compare'   => 'LIKE',
                                           );*/
           
             $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter , $cs_featured_on_top , $cs_listing_sorting  );
         } else {
            $args = array(
                'posts_per_page'     => "-1",
                'post_type'          => 'directory',
                'post_status'        => 'publish',
                'orderby'            => $orderby,
                'order'              => $order,
            );
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter , $cs_featured_on_top , $cs_listing_sorting );
        } 

        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }

        $custom_query   = new WP_Query($args);
        $count_post     = 0;
        $counter         = 1;
        $count_post     = $custom_query->post_count;

        $args = array(
            'posts_per_page'	=> "$directory_per_page",
            'paged'				=> $_GET['page_id_all'],
            'post_type'			=> 'directory',
            'meta_key'			=> (string)$sort_key,
            'meta_type'			=> $meta_type,
            'post_status'		=> 'publish',
        );

        $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
        $args['orderby'] = $orderby;
        $args['order']   = $order;
        
        
        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }

        cs_directory_sub_header($cs_directory_header, $cs_directory_banner, $args, $directory_type, $cs_directory_rev_slider, $cs_directory_adsense,$cs_directory_map_style);
        
        if( $cs_chek_section_view == 'wide' ){
            echo '<div class="directory-box">';
        }
        
        if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ) {
            echo '<div class="section-content">';    
        }
        
		echo '<div class="dynamic-listing">';
		
        if($cs_directory_header <> 'adsense' && $cs_directory_header <> 'plain-heading') {
            $section_title = '';
            if(isset($directory_title) && trim($directory_title) <> ''){
                $section_title = '<div class="col-md-12 cs-section-title"><h2>'.$directory_title.'</h2></div>';
				echo cs_allow_special_char($section_title);
            }
        }
        
        //==Session Start
        if ( ! isset( $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] ) ) {
            cs_set_session( $directory_view,$directory_type,$orderby,$order,$cs_paged_id,'false','',$cs_directory_fields_count,$directory_per_page,$cs_switch_views,$cs_directory_filterable,$directory_title,$directory_pagination,$cs_directory_sortable,$cs_elem_id);
        }
        //==Session End
        
        	cs_get_directory_top_filters($cs_switch_views , $directory_view, $atts );
        } else {
			
            if ( isset( $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] ) && $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] !='' ) {
				
                 $sessionData                = $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'];
 				 $cs_directory_fields_count  = isset( $sessionData['fields_limit'] ) ? $sessionData['fields_limit'] : 4;
                 $directory_per_page         = $sessionData['pagination'];
                 $cs_switch_views            = $sessionData['switch_views'];
                 $directory_view             = $sessionData['post_directory_view'];
                 $filterable                 = $sessionData['filterable'];
                 $sortable                   = $sessionData['sortable'];
                 $directory_title            = $sessionData['directory_title'];
            } else {
                $cs_directory_fields_count    = 4;
                $directory_per_page           = isset( $_GET['pagination'] ) ? $_GET['pagination'] : 10;
                $cs_switch_views              = 'list,grid,grid-box,grid-box-four-column,map';
                $cs_switch_views              = explode(",", $cs_switch_views);
                $directory_view               = 'detailed';
                $filterable                   = 'No';
                $sortable                     = 'No';
                $directory_title              = '';
            }
             if ( isset( $_GET['type'] ) && $_GET['type'] !='' ) {
                $meta_value             = $directory_id;
                $directory_type_select	= $directory_id;
            } else {
                $meta_value             = '';
                $directory_type_select  = '';
            }
            $args                       = $args;
            $directory_pagination       = "Show Pagination";
            $count_post                 = '';
            $directory_cat              = '';
            $directory_type             = '';
            $cs_directory_filterable    = $filterable;
            
            if( $cs_chek_section_view == 'wide' ){
                echo '<div class="directory-box">';
            }
            if ( $cs_directory_filterable == 'Yes' )
                echo '<div class="section-content">';
            
			echo '<div class="dynamic-listing">';
			   
            $section_title = '';
            if(isset($directory_title) && trim($directory_title) <> ''){
                $section_title = '<div class="col-md-12 cs-section-title"><h2>'.$directory_title.'</h2></div>';
				echo cs_allow_special_char($section_title);
            }
            cs_get_directory_top_filters( $cs_switch_views , $directory_view, '' );
            
        }
		
 	    $custom_query = new WP_Query($args);
        $cs_page_id = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
		if ( $custom_query->have_posts() <> "" ) { 
         
		 echo '<div class="cs-listing-wrapper"><div class="col-md-12 cs-directory detailed_listing">'; 
         
            while ( $custom_query->have_posts() ): $custom_query->the_post();
            $width                 = '150';
            $height                = '150';
			$cs_post_id = $post->ID;
            $directory_image_gallery = get_post_meta( get_the_ID(), '_directory_image_gallery', true );
            $title_limit         	= 15;
            $background           	= '';
            $cs_directory           = get_post_meta($cs_post_id, "cs_directory_meta", true);
            $dir_pkg_expire_date    = get_post_meta($cs_post_id, "dir_pkg_expire_date", true);
            $directory_type_select  = get_post_meta($cs_post_id, "directory_type_select", true);
			$cs_views        		= get_post_meta($post->ID, "cs_count_views", true);
			
			if ( $cs_directory <> "" ) {
                $cs_xmlObject = new SimpleXMLElement($cs_directory);
            }
			
            // get organiser id
			$organizerID = cs_get_organizer_id($cs_post_id);
            // check post is urgent or not
		    if(isset($directory_type_select) && $directory_type_select <> ''){
                $post_id = absint($directory_type_select);
                    $meta_options = cs_directory_custom_options_array();
                    if(is_array($meta_options)){
                        foreach( $meta_options['params'] as $table_key=>$tablerows ) {
                            $field_title = $tablerows['title'];
                            foreach( $tablerows as $key=>$param ) {
                                if($key == 'title')
                                    continue;
                                if(is_array($param)){
                                    $key_input = $key;
                                    if($param['type'] == 'checkbox'){
                                        $meta_option_on = get_post_meta((int)$directory_type_select, $key, true);
                                        if($meta_option_on == 'on'){
                                            $$key = $meta_option_on;
                                        }
                                    }
                                    if($param['type'] == 'text'){
                                        $keyinputtitle = get_post_meta($directory_type_select, $key, true);
                                        if(empty($keyinputtitle))
                                            $keyinputtitle = $field_title;
                                        $$key_input = $keyinputtitle;
                                        
                                }
                            }
                        }
                    }
                }
            }
            $randId    = cs_generate_random_string(5);
            $width_thumb      = 150;
            $height_thumb     = 150;
            $image_url = get_post_meta( get_the_ID(), '_directory_image_gallery', true );
            $image_url = array_filter( explode( ',', $image_url ) );
		    if ( isset( $image_url ) && ! empty( $image_url ) ) {
               if ( is_user_logged_in() and get_current_user_id() == '47') {
							$image_url = cs_attachment_image_src( $image_url[0] ,$width,$height);
						}else{
						 $image_url = isset($image_url[0]) ? cs_attachment_image_src( $image_url[0] ,$width_thumb,$height_thumb) : get_template_directory_uri().'/assets/images/no-image4x3.jpg'; 
						}
            } else {
                $image_url    = get_template_directory_uri().'/assets/images/no-image4x3.jpg';
            }
        ?>
        <article class="directory-section">
          
		  <?php if ( isset( $image_url ) && $image_url != '') { ?>
          <div class="cs_thumbsection">
			<?php 
                if( isset($cs_post_favourites_option) && $cs_post_favourites_option == 'on' ){
                    echo '<div class="dr_location post-'.intval($cs_post_id).'">';
                            cs_add_dirpost_favourite($cs_post_id);
                    echo '</div>';
                }
            ?>
            <ul class="dr_thumbsection">
                  <li class="featured_thumb">
                      <a href="<?php echo esc_url(get_permalink($cs_post_id));?>">
                           <!--<img src="<?php echo esc_url( $image_url );?>" alt="">-->
                       </a>
                  </li>
             </ul>
          </div>
          <?php }?> 
          <div class="content_info">
            	<h2 itemprop="name" itemscope itemtype="http://schema.org/Thing">
                	<a href="<?php echo esc_url(get_permalink($cs_post_id));?>">
                        <?php echo wp_trim_words(get_the_title($cs_post_id),$title_limit,'...'); ?>
                    </a>
                </h2>
                <ul class="cs-author-list">
                    <li> 
                       <?php $cs_page_id = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';?>
                      <!-- <span class="organizer-name">
                            <a href="<?php //echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>">
                                <?php echo get_the_author_meta('display_name',$organizerID );?>
                            </a>
                       </span>
                    </li> -->
                    <li class="inner-post-feature"><?php echo cs_show_featured_text($cs_post_id);?></li>
					<li><?php cs_ad_urgent($cs_post_id);?></li>
                </ul>
                <?php
					if ( $meta_value == '' ) {
						 $meta_value    = $directory_type_select;
					}
					
					if( $cs_directory_fields_count <> 'none' ){
						cs_get_post_specification_list($cs_post_id,$directory_type_select,$cs_directory_fields_count);
					}
                                        echo '<div class="cs-locplace"><div class="cs-location">Location: </div><div class="cs-place">' . get_post_meta($cs_post_id,'dynamic_post_location_address',true) . '</div> </div>';

//					echo '<span class="cs-location" ">Location: </span><p>"' . get_post_meta($cs_post_id,'dynamic_post_location_address',true) . '</p>';
					echo '<p>'.cs_get_content($cs_post_id,40).'</p>';	
               ?>
                
                <ul class="thumb-options">
                    <li>
					<i class="icon-clock7"></i>
					<?php _e('Posted On','directory');?>
                        <time datetime="<?php echo date('Y-m-d',strtotime(get_the_date()));?>"><?php echo date_i18n(get_option( 'date_format' ),strtotime(get_the_date()));?>
                        </time>
                     </li>
                     <?php
						$cs_locationAddress = cs_get_location( $cs_post_id );
						if(isset($cs_post_location_option) && $cs_post_location_option == 'on'){
							if ( isset ( $cs_locationAddress ) && $cs_locationAddress !='' ) {?>
							<li>
							   <div class="cs-location-address" itemscope itemtype="http://schema.org/CreativeWork" itemprop="Place">
								 <i class="icon-map-marker"></i>
								   <?php echo esc_attr( $cs_locationAddress );?>
							   </div> 
						   </li>
					 <?php } } ?>
                     </li>
                </ul>  
                <!-- Directory ShortOption --> 
         </div>                 
       </article>
        <?php 
         endwhile;
         wp_reset_postdata();
		 echo '</div>';
         $qrystr = '';
         if ( $directory_pagination == "Show Pagination" and $count_post > $directory_per_page and $directory_per_page > 0) {
             if ( isset($_GET['sort']) ) $qrystr .= "&amp;sort=".$_GET['sort'];
             if ( isset($_GET['page_id']) ) $qrystr .= "&amp;page_id=".$_GET['page_id'];
             echo cs_pagination($count_post, $directory_per_page,$qrystr);
         }
       
         echo '</div></div>';
        } else {
          echo '<div class="col-md-12"><div class="succ_mess"><p>';
            esc_html_e('No Directory Found6','directory');
        echo '</p></div></div></div>';
      }
	  
      if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ){
          echo '</div>';
      }

      if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ) {
          cs_get_directory_filters($directory_cat, $directory_type, $atts , $directory_view , $cs_directory_fields_count ,'section','');
      }
      
      if( $cs_chek_section_view == 'wide'){
          echo '</div>';
      }
    }
}

//======================================================================
// Directory Listing Shortcode
//======================================================================
if (!function_exists('cs_directory_map')) {
    function cs_directory_map( $type = 'nomal', $atts )
    {
        global $post,$wpdb,$cs_xmlObject,$cs_theme_options, $column_container,$cs_node_id,$cs_elem_id,$cs_paged_id;
        
        $cs_chek_section_view = '';
        if ( isset( $column_container ) ){
            $column_attributes = $column_container->attributes();
            $cs_chek_section_view = $column_attributes->cs_section_view;    
        }
        
        if ( $type == 'normal' ) {
        
        $defaults = array( 'directory_title' => '', 'directory_cat' => '','cs_directory_fields_count'=>'4', 'cs_directory_filter' => '', 'cs_featured_on_top' => 'Yes', 'cs_listing_sorting' => 'recent','cs_directory_header' => '','cs_directory_rev_slider' => '','cs_directory_map_style'=>'style-1','cs_directory_banner' => '','cs_directory_adsense' => '','cs_subheader_bg_color' => '','cs_subheader_padding_top' => '','cs_subheader_padding_bottom' => '','directory_view' => '','cs_switch_views' => '','directory_type' => '','directory_pagination'=>'','cs_directory_filterable' => '','cs_directory_sortable' => '','directory_per_page' => '10');
        extract( shortcode_atts( $defaults, $atts ) );

       	$cs_directory_options = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
		$cs_page_id = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
        date_default_timezone_set('UTC');
        $current_time = current_time('Y/m/d');
        
        ob_start();
        
        $organizer_filter = '';
        $user_meta_key                = '';
        $user_meta_value              = '';
        $meta_compare                 = "=";
        $meta_value                   = '';
        $meta_key                     = '';
        
        if ( isset( $cs_switch_views ) && $cs_switch_views !='' ){
            $cs_switch_views = explode(",", $cs_switch_views);
        } else {
            $cs_switch_views    =  array();
        }

        //==Filters End
        if ( isset( $directory_type ) && $directory_type == 0 ) {
            $directory_type    = '';
        }
        
        $meta_compare = "=";
        $meta_value   = $directory_type;
        $meta_key      = 'directory_type_select';
        
        $meta_fields_array = array();
        $meta_fields_array = array('relation' => 'AND',);
       
        $backendFilter    = true; 
        $sort_key         = '';
        $meta_type        = 'CHAR';
		$orderby    	  = 'meta_value';
		$order        	  = 'DESC';
        
         $cs_directory_options =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
		
        $cs_counter_directory = 0;
            
        if ( empty($_GET['page_id_all']) ) $_GET['page_id_all'] = 1;
 
        if ( $directory_type <> "" ) {
            $args = array(
                        'posts_per_page'            =>         "-1",
                        'post_type'                    =>         'directory',
                        'post_status'                =>         'publish',
                        'orderby'                    =>         $orderby,
                        'order'                        =>         $order,
                    );

            $meta_fields_array[] = array(
                                        'key'         => $meta_key,
                                        'value'     => $meta_value,
                                        'compare'   => $meta_compare,
                                    );
                                    
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter , $cs_featured_on_top , $cs_listing_sorting  );
        
        } else {
            $args = array(
                'posts_per_page'            =>         "-1",
                'post_type'                    =>         'directory',
                'post_status'                =>         'publish',
                'orderby'                    =>         $orderby,
                'order'                        =>         $order,
            );
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter  , $cs_featured_on_top , $cs_listing_sorting );
        } 

        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }

        $custom_query   = new WP_Query($args);
        $count_post     = 0;
        $counter         = 1;
        $count_post     = $custom_query->post_count;

        $args = array(
            'posts_per_page'            => "$directory_per_page",
            'paged'                        => $_GET['page_id_all'],
            'post_type'                    => 'directory',
            'meta_key'                    => (string)$sort_key,
            'meta_type'                    => $meta_type,
            'post_status'                => 'publish',
        );
                    
        $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter  , $cs_featured_on_top , $cs_listing_sorting );
    
        $args['orderby'] = $orderby;
        $args['order']      = $order;
        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }

        cs_directory_sub_header($cs_directory_header, $cs_directory_banner, $args, $directory_type, $cs_directory_rev_slider, $cs_directory_adsense,$cs_directory_map_style);

        if( $cs_chek_section_view == 'wide' ){
            echo '<div class="directory-box">';
        }
        
        if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ){
            echo '<div class="section-content">';
        }
        
		echo '<div class="dynamic-listing">';
		
        if($cs_directory_header <> 'adsense' && $cs_directory_header <> 'plain-heading') {
            $section_title = '';
            if(isset($directory_title) && trim($directory_title) <> ''){
                $section_title = '<div class="col-md-12 cs-section-title"><h2>'.$directory_title.'</h2></div>';
				echo cs_allow_special_char($section_title);
            }
        }
        cs_get_directory_top_filters( $cs_switch_views , $directory_view ,$atts );
      } else {
            if ( isset( $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] ) && $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] !='' ) {
                 $sessionData                = $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'];
                 $cs_directory_fields_count = $sessionData['fields_limit'];
                 $directory_per_page         = $sessionData['pagination'];
                 $cs_switch_views             = $sessionData['switch_views'];
                 $directory_view            = $sessionData['post_directory_view'];
                 $filterable                = $sessionData['filterable'];
                 $sortable                    = $sessionData['sortable'];
                 $directory_title            = $sessionData['directory_title'];
            } else {
                $cs_directory_fields_count    = 4;
                $directory_per_page             = isset( $_GET['pagination'] ) ? $_GET['pagination'] : 10;
                $cs_switch_views             = 'list,grid,grid-box,grid-box-four-column,map';
                $cs_switch_views             = explode(",", $cs_switch_views);
                $directory_view                = 'listing';
                $filterable                    = 'No';
                $sortable                    = 'No';
                $directory_title            = '';
            }
            
              $meta_value                 = isset($directory_id) ? $directory_id : '';
            $directory_type_select         = isset($directory_id) ? $directory_id : '';
            $args                        = $atts;
            $directory_pagination         = "Show Pagination";
            $count_post                    = '';
            $cs_directory_filterable    = $filterable;
            $directory_cat                = '';
            $directory_type                = '';
            
            if( $cs_chek_section_view == 'wide' ){
                echo '<div class="directory-box">';
            }
            
            if ( $cs_directory_filterable == 'Yes' )
                echo '<div class="section-content">';
            
			echo '<div class="dynamic-listing">';
			
            $section_title = '';
            if(isset($directory_title) && trim($directory_title) <> ''){
                $section_title = '<div class="col-md-12 cs-section-title"><h2>'.$directory_title.'</h2></div>';
				echo cs_allow_special_char($section_title);
            }
            
            cs_get_directory_top_filters( $cs_switch_views , $directory_view, '' );
            
        }
	    
		echo '<div class="cs-listing-wrapper"><div class="col-md-12 cs-directory default_listing">';
	 	$cs_directory_map_style = isset($cs_directory_map_style) ? $cs_directory_map_style : 'style-1';
        cs_map_view( $args,$cs_directory_map_style );
        echo '</div></div></div>';

        if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ){
            echo '</div>';
        }
        
        
        if ( isset( $cs_directory_filterable ) && $cs_directory_filterable =='Yes' ) {
            cs_get_directory_filters( $directory_cat, '',$atts , $directory_view , $cs_directory_fields_count,'section','' );
        }
        
        if( $cs_chek_section_view == 'wide' ){
            echo '</div>';
        }
    }
}


//======================================================================
// Function Count Images
//======================================================================

if ( !function_exists( 'cs_count_images' ) ) {
    function cs_count_images( $post_id ) {
        global $post;
        $post_id                 =  $post_id ?  $post_id : $post->ID;    
        $directory_image_gallery = get_post_meta( $post_id , '_directory_image_gallery', true );
        $attachments              = array_filter( explode( ',', $directory_image_gallery ) );
        $totlaImages             = count( $attachments );
        if ( has_post_thumbnail() ) {
            return $totlaImages + 1;
        }
        else {
            return $totlaImages;
        }
    }
}

//======================================================================
// Directory Map View
//======================================================================
function cs_map_view( $args,$cs_directory_map_style) {
    
    global $post,$wpdb,$cs_theme_options,$cs_node_id,$cs_elem_id,$cs_paged_id;
    
	$cs_map_lock = isset($cs_theme_options['cs_map_lock']) ? $cs_theme_options['cs_map_lock'] : ''; 
	
    $custom_query = new WP_Query($args);        
    if ( $custom_query->have_posts() <> "" ) {
			
			//$goe_location_enable            	= isset($cs_theme_options['goe_location_enable']) ? $cs_theme_options['goe_location_enable'] : 'No';
			$cs_streat_view            			= isset($cs_theme_options['cs_streat_view']) ? $cs_theme_options['cs_streat_view'] : 'No';
			$cs_map_auto_zoom                      = isset($cs_theme_options['cs_map_auto_zoom']) ? $cs_theme_options['cs_map_auto_zoom'] : 'off';
			$cs_directory_map_style = isset($cs_directory_map_style) ? $cs_directory_map_style : 'style-1';
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
                
$map_zoom = 0;            $width                = '370';
            $height               = '280';
            $randomid         	  = cs_generate_random_string('10');
            $cs_page_id   		  =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
            $total_post     	  = $custom_query->post_count;
            $directories          = array();
            $directory_array      = array('count'=>$total_post);                            
            wp_directory::cs_googlemapcluster_scripts();
            
            $map_cluster_url        = isset($cs_theme_options['cluster_map_marker']) ? $cs_theme_options['cluster_map_marker'] : '';
		    $map_cluster_color        = isset($cs_theme_options['cluster_map_marker_color']) ? $cs_theme_options['cluster_map_marker_color'] : '#000';
            $currency_sign             = isset($cs_theme_options['currency_sign'])? $cs_theme_options['currency_sign']:'$';
            
            while ( $custom_query->have_posts() ): $custom_query->the_post();
              $organizerID             = get_post_meta( $post->ID, 'directory_organizer', true );
              $latitude             = get_post_meta( $post->ID, 'dynamic_post_location_latitude', true );
              $longitude             = get_post_meta( $post->ID, 'dynamic_post_location_longitude', true );
              $direcotry_type_id     = get_post_meta( $post->ID, 'directory_type_select', true );
              $map_marker_destination         = get_post_meta( $direcotry_type_id, 'cs_destination_url_input', true );
              $user_profile_url     = cs_user_profile_link($cs_page_id, 'dashboard', $organizerID);
              $dir_featured_till      = get_post_meta($post->ID, "dir_featured_till", true);
              $location                 = get_post_meta($post->ID, "dynamic_post_location_address", true);
              
              
              $cs_page_id    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
              
			  $organizerID = cs_get_organizer_id( $post->ID );
              
              $image_url = get_post_meta( $post->ID, '_directory_image_gallery', true );
              $image_url = array_filter( explode( ',', $image_url ) );
              if ( isset( $image_url ) && ! empty( $image_url ) ) {
                $image_url         = cs_attachment_image_src( $image_url[0] ,$width,$height); 
              } else {
                $image_url    = '';
              }
            
              $isFeatured    = false;
			  if ( isset( $dir_featured_till ) && $dir_featured_till !='' ){
				  $current_date = date("Y-m-d H:i:s");
				  if( strtotime( $dir_featured_till ) > strtotime( $current_date ) ) {
					  $isFeatured    = true;
				  }
			  }
                    
              if( isset( $isFeatured ) && $isFeatured == true ) {
                  $cs_directory_featured    = '<li><span>URGENT</span></li>';
              } else {
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
                $price    = '<small>'.esc_attr( $currency_sign.number_format(absint($dynamic_post_sale_oldprice))).'</small> '.esc_attr( $currency_sign.number_format(absint($dynamic_post_sale_newprice)));
            } else{
                $price    = '';
            }
            
            $fields                    = '';
            $custom_fields            = '';
            $favorites                = '';
            
            $directories[]         = array(
                                        'post_id'      => $post->ID,
                                        'post_title' => get_the_title(),
                                        'image_url'  => $image_url,
                                        'permalink'  => addslashes(get_permalink()),
                                        'longitude'  => $longitude,
                                        'latitude'   => $latitude,
                                        'mapamrker'  => $map_marker_destination,
                                        'width'      => $width,
                                        'height'     => $height,
                                        'publish_date'         => esc_attr( date_i18n( get_option( 'date_format' ),strtotime( get_the_date() ) ) ),
                                        'user_id'              => absint($organizerID),
                                        'user_name'          => get_the_author_meta('display_name',$organizerID ),
                                        'user_profile_url'  => $user_profile_url,
                                        'featured'           => $cs_directory_featured,
										'featured_text'      => $is_featured_text,
                                        'date'               => esc_attr( date_i18n( get_option( 'date_format' ),strtotime( $dir_payment_date ) ) ),
                                        'location'           => $location,
                                        'price'               => $price, 'objective' => 'test',
                                    );
            endwhile;
            wp_reset_postdata();
            
            $directory_array['posts'] = $directories;
            $json_array                  = json_encode($directory_array);
            $rand_id                   = rand();
            
            if ( $latitude == '' ||  $longitude == '' ) {
                $Latitude  = isset( $cs_theme_options['map_latitude'] ) && $cs_theme_options['map_latitude'] ? $cs_theme_options['map_latitude'] : '51.54532829999999';
                $Longitude = isset( $cs_theme_options['map_longitude'] ) && $cs_theme_options['map_longitude'] ? $cs_theme_options['map_longitude'] : '-0.08428670000000693';
            } else {
                $Latitude                   = $latitude;
                $Longitude                   = $longitude;
            }
            
            $cs_svg_marker = wp_directory::plugin_url().'assets/images/orange-marker.svg';
            ?>
          
            <div id="map-container<?php echo esc_attr($rand_id);?>" class="map-container">
              <span class="loader"><i class="icon-spinner8"></i></span>
                    <?php if(isset($goe_location_enable) && $goe_location_enable == 'Yes'){
                        if(!isset($rand_id))
                            $rand_id = '';
                    ?>
                        <div class="location-icon" onclick="getLocation('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>')"><img src="<?php echo wp_directory::plugin_url();?>/assets/images/maplocation.png" alt="" /></div>
                    <?php } ?>
              <span class="fullscreen"><i class="icon-arrows"></i> <?php _e('Full Screen','directory');?></span>
              <span class="gmapzoomplus" id="gmapzoomplus<?php echo esc_attr($rand_id);?>" style="cursor: pointer;"><i class="icon-plus8"></i> <?php _e('','directory');?></span>
              <span class="gmapzoomminus" id="gmapzoomminus<?php echo esc_attr($rand_id);?>" style="cursor: pointer;"><i class="icon-minus8"></i> <?php _e('','directory');?></span>
              <div class="map" id="map<?php echo esc_attr($rand_id);?>" style="opacity:0"></div>
            </div>
            <input type="hidden" id="rand_id" value="<?php echo esc_attr($rand_id);?>" />
            <input type="hidden" id="admin_url" value="<?php echo esc_js(admin_url('admin-ajax.php'));?>" />
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    jQuery(".fullscreen") .click(function() {
                         jQuery("body").toggleClass("body-fullscreen");
                         jQuery("#map-container<?php echo esc_attr($rand_id);?>").height(jQuery(window).height);
						 map = jQuery("#map-container<?php echo esc_attr($rand_id);?>");
                         google.maps.event.trigger(map, "resize");
						 //jQuery(window).load();
						 cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>');
                    });
					
					jQuery( "#streetView<?php echo esc_attr($rand_id);?>" ).click(function() {
					   toggleStreetView('<?php echo esc_js($Latitude);?>','<?php echo esc_js($Longitude);?>','<?php echo esc_attr($rand_id);?>');
					});
							
                 });
                  jQuery(window).load(function() {
                        var dataobj = jQuery.parseJSON( '<?php echo addslashes($json_array);?>' );
                        cs_googlecluster_map('<?php echo esc_js($rand_id);?>', '<?php echo esc_js($Latitude);?>', '<?php echo esc_js($Longitude);?>', '<?php echo esc_js($map_marker_url);?>', dataobj, '<?php echo esc_js($cs_map_type);?>', <?php echo absint($map_zoom);?>, '<?php echo esc_js($cs_cluster_marker_color_input);?>','<?php echo esc_js($cs_directory_map_style);?>', '<?php echo esc_js($cs_map_auto_zoom); ?>', '<?php echo esc_js($cs_svg_marker); ?>', '<?php echo esc_js($cs_map_lock); ?>');
                        jQuery(".loader").html('');
                        jQuery("#map<?php echo esc_attr($rand_id);?>").css({
                            "opacity" : "1"
                        })
                  });
                  if(jQuery("#map<?php echo esc_attr($rand_id);?>").length>0){
                        jQuery( ".MultiControls p.btnOk" ).live("click", function() {
                            cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>');
                            return false;
                        });
                        
						jQuery( "form #directory-field-category" ).live("change", function() {
                            jQuery('#geo_loc_option').val('off');
                            cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>');
                            return false;
                        });
                        
						jQuery( "form #directory-search-location" ).live("change", function() {
                            jQuery('#geo_loc_option').val('off');
                            cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>');
                            return false;
                        });
                        
						jQuery( "form .slider-distance-range" ).live("change", function() {
                            cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>');
                            return false;
                        });
                        
						jQuery( ".directory-type-categories-load ul.check-box .directory-categories-checkbox " ).live("change", function() {
                            //if(jQuery(this).is(":checked")) {
                                cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>');
                            //}
                            return false;
                        });
                        
                        
                   }
            </script>
    <?php 
     }
 }


//=====================================================================
// Cs Filter Query
//=====================================================================
if (!function_exists('cs_get_filter_results')) {
    function cs_get_filter_results( $atts ) {
        global $post,$wpdb,$cs_xmlObject,$cs_theme_options, $column_container,$cs_node_id,$cs_elem_id,$cs_paged_id;
        $directory_id     = '';
        $sessionData     = array(); 
        if ( isset ( $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] ) && $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'] !='' ) {
            $sessionData            = $_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data'];
            $post_directory_view    = isset( $sessionData['post_directory_view'] ) ? $sessionData['post_directory_view'] : 'listing' ;
            $posts_per_page         = isset( $sessionData['pagination'] ) ? $sessionData['pagination'] : '10' ;
        } else {
            $post_directory_view    = 'listing';
        }
        
        if(!isset($_GET['page_id_all'])) $_GET['page_id_all']=1;
        
        $args=array(
                    'post_type' => 'directory',
                    'post_status' => 'publish',
                    'paged' => $_GET['page_id_all'],
                    );
                    
        if( isset( $_GET['submit'] ) ){
            if(isset($_GET['search_text']) && $_GET['search_text'] !='' ){
                $s = sanitize_text_field($_GET['search_text']);
                $args['s'] = $s;
            }
            
            if(isset($_GET['location'])){
                $cs_directory_search_location = sanitize_text_field($_GET['location']);
            }
           
            //$meta_fields_array = array('relation' => 'AND');
			
			if(!class_exists('RadiusCheck')){
				require_once (get_template_directory()  . '/include/theme-components/cs-locationplugin/location_check.php');
			}
			
			if(isset($_GET['geo']) && $_GET['geo'] != 'on') {
				
			if( isset($cs_theme_options['cs_loc_max_input'])  && trim($cs_theme_options['cs_loc_max_input']) !=''  && trim($cs_theme_options['cs_loc_max_input']) != 0  ) {
				$Miles = $cs_theme_options['cs_loc_max_input'];
			} else {
				$Miles = 150;
			}
			
			$distance_km_miles = isset($cs_theme_options['distance_km_miles']) ? $cs_theme_options['distance_km_miles'] : 'Miles';
											
			if(isset($_GET['radius'])) {
				 $Miles = $_GET['radius']; 
			} else {
				 $Miles = $Miles;
			}
			
			if( $distance_km_miles == 'Km' ) {
				if(isset($_GET['radius'])) {
					 $Miles = $_GET['radius'] * 0.621371; 
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
			if((isset($_GET['location']) && !empty($_GET['location']))){

				$address	= sanitize_text_field($_GET['location']);
				$prepAddr	= str_replace(' ','+',$address);
				$geocode	= file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
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
				// $add=str_replace('+','%',$prepAddr);
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
										
										array('key' 		=> 'dynamic_post_location_address',
													'value'   	=>	str_replace('+','_',$prepAddr),
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
            
            if(isset($_GET['type']) && !empty($_GET['type'])){
                $directory_types = $_GET['type'];
				
                $directory_type_array = explode('||', $directory_types);
                if(is_array($directory_type_array) && isset($directory_type_array['0']))
                   $directory_type = $directory_type_array['0'];
                if(is_array($directory_type_array) && isset($directory_type_array['1']))
                    $directory_categories = $directory_type_array['1'];
                if(isset($directory_type) && $directory_type <> ''){
                    $dirargs=array(
                        'name' => (string)$directory_type,
                        'post_type' => 'directory_types',
                        'post_status' => 'publish',
                        'posts_per_page' => 1
                    );
                    $dir_posts = get_posts( $dirargs );
                    if( $dir_posts ) {
                    	$directory_id = (int)$dir_posts[0]->ID;
                    }
                }
            } else {
                //$directory_id    = (int)$cs_theme_options['cs_default_ad_type'];
            }
			
			$min_price = $max_price = '';
			
			if(isset($_GET['min_price']) && $_GET['min_price'] !='Min Price' && $_GET['min_price'] !='' ){
				if((int)$_GET['min_price'] < 0) $_GET['min_price'] = '';
                $min_price = sanitize_text_field($_GET['min_price']);
            }
            
            if(isset($_GET['max_price']) && $_GET['max_price'] !='Max Price' && $_GET['max_price'] !='' ){
				if((int)$_GET['max_price'] < 0) $_GET['max_price'] = '';
                $max_price = sanitize_text_field($_GET['max_price']);
            }
            
            if($min_price <> '' && $max_price <> ''){
                $meta_fields_array[] = array('key' => 'dynamic_post_sale_newprice',
                                                      'value'   => array($min_price, $max_price),
                                                      'compare' => 'BETWEEN',
                                                      'type'     => 'NUMERIC'
                                                );
            }
			
            if(isset($directory_id) && $directory_id <> ''){
                $meta_fields_array[] = array('key' => 'directory_type_select',
                                                  'value'   => $directory_id,
                                                  'compare' => '=',
                                                  'type'     => 'NUMERIC'
                                            );
			
			$cs_dcpt_custom_fields = get_post_meta($directory_id, "cs_directory_custom_fields", true);
            if ( $cs_dcpt_custom_fields <> "" ) {
                $cs_customfields_object = new SimpleXMLElement($cs_dcpt_custom_fields);
                if(isset($cs_customfields_object->custom_fields_elements) && $cs_customfields_object->custom_fields_elements == '1'){
                    if(count($cs_customfields_object)>1){
                        global $cs_node;
						foreach ( $cs_customfields_object->children() as $cs_node ){
                            if(isset($cs_node->cs_customfield_enable_search) && $cs_node->cs_customfield_enable_search == 'yes'){
                                $key = (string)$cs_node->cs_customfield_name;
                                if(isset($key) && isset($_GET[(string)$key]) && !empty($_GET[(string)$key]) && $_GET[(string)$key] <> '' && $cs_node->getName() <> 'range'){
                                    $cs_value = $_GET[$key];
                                } else if(isset($key) && $cs_node->getName() == 'range'){
                                        $min_key = $key.'_min_range';
                                        $max_key = $key.'_max_range';
                                        if((isset($_GET[$min_key]) && $_GET[$min_key] <> '') || (isset($_GET[$max_key]) && $_GET[$max_key] <> '')){
                                            if(isset($_GET[$min_key]) && $_GET[$min_key] <> '' && $_GET[$min_key] !='Min Price'){
                                                $min_range = (int)$_GET[$min_key];
                                            } else {
                                                $min_range = '';
                                            }
                                            
                                            if(isset($_GET[$max_key]) && $_GET[$max_key] <> '' && $_GET[$min_key] !='Max Price'){
                                                $max_range = (int)$_GET[$max_key];
                                            } else {
                                                $max_range = '';
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
                                        if ( isset( $key ) && $key !='' && isset($_GET[$key])){
                                            $cs_value = $_GET[$key];
                                            if(is_array( $cs_value ))
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
                                        if($min_range <> '' && $max_range == '' ){
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
                                            
                                        } else if( $min_range <> '' && $max_range <> '' ){
                                    
                                            $meta_fields_array[] = array('key' => (string)$key,
                                            	'value'		=> array($min_range, $max_range),
                                            	'compare'	=> 'BETWEEN',
                                            	'type'		=> 'NUMERIC'
                                            );
                                        }
                                        break;
                                    case 'dropdown' :

                                        if ( isset( $key ) && $key !='' && isset($_GET[$key])){
                                            $cs_value    = cs_get_multiselect_values( $key );
                                        }
                                        
                                        // prepare
                                        $meta_fields_array[] = array('key' => (string)$key,
                                              'value'   => $cs_value,
                                              'compare' => 'LIKE'
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
            
            if((isset($_GET['location']) && !empty($_GET['location'])) || (isset($_GET['geo']) && $_GET['geo'] == 'on')){
                if(isset($_GET['geo']) && $_GET['geo'] == 'on'){
                    
					if(isset($_GET['geo_location_lat']) && isset($_GET['geo_location_long']) && $_GET['geo_location_lat'] <> '' && $_GET['geo_location_long'] <> '') {
                        $Latitude     = sanitize_text_field($_GET['geo_location_lat']);

                        $Longitude  = sanitize_text_field($_GET['geo_location_long']);
					}else{
						if( isset( $_GET['location'] ) && $_GET['location'] !='' ) {
							$address	= sanitize_text_field($_GET['location']);
							$prepAddr	= str_replace(' ','+',$address);
							$geocode	= file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
							$output		= json_decode($geocode);
							$Latitude	= $output->results[0]->geometry->location->lat;
							$Longitude	= $output->results[0]->geometry->location->lng;
						}
					}
					
                } else {
					if( isset( $_GET['location'] ) && $_GET['location'] !='' ) {
						$address     = sanitize_text_field($_GET['location']);
						$prepAddr     = str_replace(' ','+',$address);
						$geocode    = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
						$output        = json_decode($geocode);
						$Latitude     = $output->results[0]->geometry->location->lat;
						$Longitude  = $output->results[0]->geometry->location->lng;
					}
                }
                
                
				if( isset($cs_theme_options['cs_loc_max_input'])  && trim($cs_theme_options['cs_loc_max_input']) !=''  && trim($cs_theme_options['cs_loc_max_input']) != 0  ) {
					$Miles = $cs_theme_options['cs_loc_max_input'];
				} else {
					$Miles = 150;
				}
				
				$distance_km_miles = isset($cs_theme_options['distance_km_miles']) ? $cs_theme_options['distance_km_miles'] : 'Miles';
												
				if(isset($_GET['radius'])) {
					 $Miles = $_GET['radius']; 
				} else {
					 $Miles = $Miles;
				}
				
				if( $distance_km_miles == 'Km' ) {
					if(isset($_GET['radius'])) {
						 $Miles = $_GET['radius'] * 0.621371; 
					} else {
						 $Miles = $radius * 0.621371;
					}
				}

                if(isset($_GET['geo']) && $_GET['geo'] == 'on') {
						
					if(isset($Latitude) && $Latitude <> '' && isset($Longitude) && $Longitude <> ''){
						$zcdRadius = new RadiusCheck($Latitude,$Longitude,$Miles);
						$minLat  = $zcdRadius->MinLatitude();
						$maxLat  = $zcdRadius->MaxLatitude();
						$minLong = $zcdRadius->MinLongitude();
						$maxLong = $zcdRadius->MaxLongitude();
						$meta_fields_array[] = array(
							'key' 		=> 'dynamic_post_location_latitude',
							'value'  	=> array($minLat, $maxLat),
							'compare' 	=> 'BETWEEN',
							'type'    	=> 'DECIMAL'
						);
						$meta_fields_array[] = array(
							'key' 		=> 'dynamic_post_location_longitude',
							'value'   	=> array($minLong, $maxLong),
							'compare' 	=> 'BETWEEN',
							'type'		=> 'DECIMAL'
						);
						
					}
				}
            }
			if(isset($_GET['filter']) && $_GET['filter'] == 'all'){
                $meta_fields_array[] = array('key' => 'dir_pkg_expire_date');
                $args['meta_key'] = 'dir_pkg_expire_date';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
            } elseif(isset($_GET['filter']) && $_GET['filter'] == 'paid'){
                $meta_fields_array[] = array('key' => 'directory_featured', 'value'  => 'no');
                $meta_fields_array[] = array('key' => 'dir_pkg_expire_date', 'value'  => date("Y-m-d H:i:s"), 'compare' => '>=', 'type' => 'NUMERIC');
                $args['meta_key'] = 'dir_pkg_expire_date';
                $args['orderby'] = 'meta_value';
                $args['order'] = 'DESC';
            } else if(isset($_GET['filter']) && $_GET['filter'] == 'free'){
                $meta_fields_array[] = array('key' => 'dir_pkg_expire_date', 'value'  => date("Y-m-d H:i:s"), 'compare' => '<', 'type' => 'NUMERIC');
                $args['orderby'] = 'meta_value';
                $args['order'] = 'ASC';
            } else if( isset($_GET['sort']) && $_GET['sort'] == 'high-price' ){
                $meta_fields_array[] = array('key' => 'dynamic_post_sale_newprice');
                $args['meta_key'] = 'dynamic_post_sale_newprice';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
            }
            
            if(is_array($meta_fields_array) && count($meta_fields_array)>1){
                $args['meta_query'] = $meta_fields_array;
            }
            
            if(isset($_GET['directory_categories']) && count($_GET['directory_categories'])>0){
                $directory_categories = cs_get_query_values('directory_categories');
            }
            
            if(isset($directory_categories)){
				if(!($directory_categories[0] == '')) {
                 $taxquery = array(
                    array(
                        'taxonomy'	=> 'directory-category',
                        'field' 	=> 'slug',
                        'terms' 	=> $directory_categories
                    )
                );
				
				} 
                $args['tax_query'] = $taxquery;
				
            }
        }
		/*echo '<pre>';
		print_r($args);
		echo '</pre>';*/
		
        if( !isset( $posts_per_page )){
            $posts_per_page = get_option('posts_per_page');
        }
        
        $args['posts_per_page'] = -1;

        $custom_q	= new WP_Query($args);
        $count_post	= $custom_q->post_count;
                        
        if( isset( $post_directory_view ) && $post_directory_view == 'Map'){
             $args['posts_per_page'] = -1;
        } else if( isset( $post_directory_view ) && $post_directory_view <> 'Map' ){
            $args['posts_per_page'] = $posts_per_page;
        }
        
        $sessionData['query_string']     = 'true';
        $sessionData['filter_query']     = $args;
		
		$_SESSION[$cs_paged_id.'_node_'.$cs_elem_id.'_data']     = $sessionData;
        if( isset( $post_directory_view ) && $post_directory_view == 'listing'){
 			cs_directory_listing( '','search',$args , $directory_id );
        } else if( isset( $post_directory_view ) && $post_directory_view == 'grid'){
            cs_directory_grid( '','col-md-4','search',$args , $directory_id );
        } else if( isset( $post_directory_view ) && $post_directory_view == 'grid-box'){
            cs_directory_grid_two( '','col-md-4','search',$args , $directory_id );
        } else if( isset( $post_directory_view ) && $post_directory_view == 'grid-box-four-column'){
            cs_directory_grid_box_four_column( '','col-md-3','search',$args , $directory_id );
        } else if( isset( $post_directory_view ) && $post_directory_view == 'map'){
            cs_directory_map( 'search',$args );
            
        } else {
            cs_directory_listing( '','search',$args );
        }
        
			
        $custom_query = new WP_Query($args);

        if(isset($cs_theme_options['cluster_map_marker_color']) && $cs_theme_options['cluster_map_marker_color'] <> '')
            $map_marker_color    = $cs_theme_options['cluster_map_marker_color'];
        else
            $map_marker_color = '#000';

    if ( $count_post > $posts_per_page && ( isset( $post_directory_view ) && $post_directory_view <> 'map' && $sessionData['pagination_status'] != 'Single Page' )) {
        echo '<div class="section-content">';
        $qrystr = '';
        if ( isset($_GET['s']) ) $qrystr = "&amp;s=".$_GET['s'];
        $args = array('s','page_id_all');
        parse_str($_SERVER['QUERY_STRING'], $outputArray);
        foreach($outputArray as $param_key=>$param_value){
            if(!in_array($param_key,$args)) {
                if(isset($param_key) && is_array($param_value)){
                    foreach($param_value as $key=>$value){
                        if(isset($value))
                         $qrystr .= '&amp;'. $param_key . '[]=' .$value;
                    }
                } else {
                    $qrystr .= '&amp;'. $param_key . '=' .$param_value;
                }
            }
        }
        echo cs_pagination($count_post,$posts_per_page, $qrystr);
        echo '</div>';
    }
  }
    add_action('wp_ajax_cs_get_filter_results', 'cs_get_filter_results');
    add_action("wp_ajax_nopriv_cs_get_filter_results", "cs_get_filter_results");
}

//=====================================================================
// Cs Query Sting 
//=====================================================================
function cs_get_query_values( $key ='' ) {
    $query  = explode('&', $_SERVER['QUERY_STRING']);
    $cs_value = array();
    foreach( $query as $param )
    {
      list($name, $value) = explode('=', $param);
      if ($name == $key ) {
        $cs_value[] = urldecode($value);
      }
    }
    return $cs_value;
}

//=====================================================================
// Cs Query Sting 
//=====================================================================
function cs_get_multiselect_values( $key ='' ) {
    $query  = explode('&', $_SERVER['QUERY_STRING']);
    $cs_value = '';
    foreach( $query as $param )
    {
      list($name, $value) = explode('=', $param);
      if ($name == $key ) {
        $cs_value .= urldecode($value).',';
      }
    }
    return rtrim($cs_value, ",");
}
//=====================================================================
// Cs Session 
//=====================================================================
function cs_set_session( $directory_view,$directory_type,$orderby,$order,$post_id , $meta_query = 'false', $filter_query = '', $fields_limit = '', $pagination = '', $views = '',$filterable = 'No',$directory_title='',$pagination_status ='',$cs_sortable ='',$cs_node_id='' ){
    global $post;
    
    if ( isset( $_SESSION[$post_id.'_node_'.$cs_node_id.'_data'] ) ) {
        unset($_SESSION[$post_id.'_node_'.$cs_node_id.'_data']);    
    }
    
	$views	= array_filter( array_unique( $views  ) );
	
    $cs_post_data    = array();
    $cs_post_data['directory_title']        = $directory_title;
    $cs_post_data['post_directory_view']    = $directory_view;
    $cs_post_data['post_directory_type']    = $directory_type;
    $cs_post_data['fields_limit']           = $fields_limit;
    $cs_post_data['pagination']             = $pagination;
    $cs_post_data['pagination_status']      = $pagination_status;
    $cs_post_data['switch_views']           = $views;
    $cs_post_data['post_directory_orderby'] = $orderby;
    $cs_post_data['post_directory_order']   = $order;
    $cs_post_data['query_string']           = $meta_query;
    $cs_post_data['filter_query']           = $filter_query;
    $cs_post_data['filterable']             = $filterable;
    $cs_post_data['sortable']               = $cs_sortable;
    $_SESSION[$post_id.'_node_'.$cs_node_id.'_data']   = $cs_post_data;
    
}

function cs_sort_query( $cs_sortType='', $meta_query = '' ) {

    $meta_type = 'CHAR';
    $meta_fields_array    = array();
    $sort_key            = '';
    if( isset( $cs_sortType ) and $cs_sortType == 'alphabetical' ){
        $orderby      = 'title';
        $order        = 'ASC';
    } else if( isset( $cs_sortType ) and  $cs_sortType == 'recent'){
        $orderby      = 'post_date';
        $order        = 'DESC';    
    } else if( isset( $cs_sortType ) and  $cs_sortType == 'popular'){
        $backendFilter       = false; 
        $meta_fields_array[] = array('key' => 'cs_count_views','type' => 'NUMERIC');
        $sort_key            = 'cs_count_views';
        $meta_type           = 'NUMERIC';
        $orderby             = 'meta_value_num';
        $order               = 'DESC';
    
    } else if( isset( $cs_sortType ) and  $cs_sortType == 'high-price'){
        $backendFilter         = false; 
       	$meta_fields_array[] = array('key' => 'dynamic_post_sale_newprice','type' => 'NUMERIC');
        $sort_key            = 'dynamic_post_sale_newprice';
        $meta_type           = 'NUMERIC';
		$orderby             = 'meta_value_num';
        $order               = 'DESC';
        
    } else if( isset( $cs_sortType ) and  $cs_sortType  == 'low-price'){
        $backendFilter         = false; 
        $meta_fields_array[]   = array('key' => 'dynamic_post_sale_newprice','type' => 'NUMERIC');
        $sort_key              = 'dynamic_post_sale_newprice';
        $meta_type             = 'NUMERIC';
        $orderby               = 'meta_value_num';
        $order                 = 'ASC';
    } else{
		$sort_key   		   = 'dir_pkg_expire_date';
		$args['meta_type']     = 'DATE';
        $orderby    		   = 'meta_value';
        $order      		   = 'DESC';
    }
    
    $meta_query['meta_query'][] = $meta_fields_array;
    $args['meta_key']   		= $sort_key;
    $args['orderby']    		= $orderby;
    $args['order']       		= $order;
    $args    				    = array_merge( $meta_query , $args );
	
    return $args;

}
