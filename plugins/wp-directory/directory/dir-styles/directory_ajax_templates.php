<?php

//=====================================================================
// Ajax Directory Grid Shortcode
//=====================================================================
if (!function_exists('cs_ajax_directory_grid')) {
    
    function cs_ajax_directory_grid(){
        global $post,$wpdb,$cs_xmlObject,$cs_theme_options,$cs_elem_id;
		
		$cs_node_id         = '';
		$cs_paged_id        = '';
		if( isset( $_REQUEST['node_id'] ) && $_REQUEST['node_id'] !='' ) {
			$cs_node_id         = $_REQUEST['node_id'];
		}
		
		if( isset( $_REQUEST['paged_id'] ) && $_REQUEST['paged_id'] !='' ) {
			$paged_id         = $_REQUEST['paged_id'];
		}
		
        if ( isset( $_REQUEST['postID'] ) && $_REQUEST['postID'] !='' ) {
            $cs_postID        = $_REQUEST['postID'];
            $cs_sessionData    = isset($_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data']) ? $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'] : '';
        }
        
		if( isset( $cs_sessionData ) && $cs_sessionData == '' ){
			echo '<span style="display:none"><script>jQuery(location).attr("href", "'.esc_url( get_permalink( $cs_postID ) ).'");</script> session_destroyed</span>';
			die();
		}
		
        if ( ( isset( $cs_sessionData['query_string'] ) &&  $cs_sessionData['query_string'] == 'false' ) ||  $_REQUEST['filters'] == 'false'  ) {
        
        foreach ( $_REQUEST as $keys => $values ) {
            $$keys = $values;
        }


        $cs_directory_options =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
        $cs_page_id    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';

        date_default_timezone_set('UTC');
        $current_time = current_time('Y/m/d');
        
        ob_start();
        
        $organizer_filter 			= '';
        $user_meta_key              = '';
        $user_meta_value            = '';
        $meta_compare 				= "=";
        $meta_value   				= '';
        $meta_key     				= '';
        $directory_title    = isset( $cs_sessionData['directory_title'] ) ?  $cs_sessionData['directory_title'] :  $directory_title;
        
        if ( isset( $cs_switch_views ) && $cs_switch_views !='' ){
            $cs_switch_views = explode(",", $cs_switch_views);
        } else {
            $cs_switch_views    =  array();
        }
        
        $directory_type = isset( $_REQUEST['type'] ) ? $_REQUEST['type'] : '';
        
        if ( isset( $directory_type ) && $directory_type == 0 ) {
            $directory_type    = '';
        }
        
        $meta_compare = "=";
        $meta_value   = $directory_type;
        $meta_key     = 'directory_type_select';
        
        $meta_fields_array = array();
        $meta_fields_array = array('relation' => 'AND',);
        
        if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='asc'){
            $order    = 'ASC';
        } else{
            $order    = 'DESC';
        }
        
        $backendFilter    = true; 
        $sort_key         = '';
        $meta_type        = 'CHAR';
        
        if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='alphabetical'){
            $orderby    = 'title';
            $order        = 'ASC';
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='recent'){
            $orderby    = 'post_date';
            $order        = 'DESC';
            
            
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='popular'){
            $backendFilter         = false; 
            $meta_fields_array[]   = array('key' => 'cs_count_views');
            $sort_key              = 'cs_count_views';
            $meta_type             = 'NUMERIC';
            $orderby               = 'meta_value';
            $order                 = 'DESC';
        
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='high-price'){
            $backendFilter         = false; 
            $meta_fields_array[]   = array('key' => 'dynamic_post_sale_type');
            $sort_key              = 'dynamic_post_sale_newprice';
            $meta_type             = 'NUMERIC';
            $orderby               = 'meta_value_num';
            $order                 = 'DESC';
            
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='low-price'){
            $backendFilter         = false; 
            $meta_fields_array[]   = array('key' => 'dynamic_post_sale_type');
            $sort_key              = 'dynamic_post_sale_newprice';
            $meta_type             = 'NUMERIC';
            $orderby               = 'meta_value_num';
            $order                 = 'ASC';
        } else{
            $orderby      = 'meta_value';
            $order        = 'DESC';
        }

		$cs_directory_options =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
        $cs_counter_directory = 0;
            
        if ( empty($_REQUEST['page_id_all']) ) $_REQUEST['page_id_all'] = 1;
            
        if ( $directory_type <> "" ) {
            $args = array(
                        'posts_per_page'               => "-1",
                        'post_type'                    => 'directory',
                        'post_status'                  => 'publish',
                        'orderby'                      => $orderby,
                        'order'                        => $order,
                    );

            $meta_fields_array[] = array(
                                        'key'         => $meta_key,
                                        'value'     => $meta_value,
                                        'compare'   => $meta_compare,
                                    );
                                    
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
        
        } else {
            $args = array(
                'posts_per_page'            =>         "-1",
                'post_type'                    =>         'directory',
                'post_status'                =>         'publish',
                'orderby'                    =>         $orderby,
                'order'                        =>         $order,
            );
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
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
            'posts_per_page'               => "$directory_per_page",
            'paged'                        => $_REQUEST['page_id_all'],
            'post_type'                    => 'directory',
            'meta_key'                     => (string)$sort_key,
            'meta_type'                    => $meta_type,
            'post_status'                  => 'publish',
        );
                    
        $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter ,  $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
    
        $args['orderby'] = $orderby;
        $args['order']      = $order;
        
        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }
        $sortable = isset($cs_sessionData['sortable']) ? $cs_sessionData['sortable'] : 'Yes';
        
		//== Sesstion
        cs_set_session( 'grid',$directory_type,$orderby,$order,$postID,'false','',$cs_directory_fields_count,$directory_per_page,$cs_switch_views,$cs_directory_filterable, $directory_title,'',$sortable,$cs_node_id);
        //== Session End
			
			if ( isset ( $_POST['sort'] ) && $_POST['sort'] !='' ) {
				$args    = cs_sort_query( $_POST['sort'] , $args );
			}
        
        } else {
             $cs_postID        = $_REQUEST['postID'];
            
            if ( isset( $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'] ) && $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'] !='' ) {
                 $sessionData    			= $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'];
                 $cs_directory_fields_count = $sessionData['fields_limit'];
                 $directory_per_page        = $sessionData['pagination'];
                 $cs_switch_views           = $sessionData['switch_views'];
                 $filterable                = $sessionData['filterable'];
                 $sortable                  = $sessionData['sortable'];
                 $directory_title           = $sessionData['directory_title'];
            } else {
                $cs_directory_fields_count   = 4;
                $directory_per_page          = 10;
                $cs_switch_views             = 'list,grid,grid-box,grid-box-four-column,map';
                $cs_switch_views             = explode(",", $cs_switch_views);
                $filterable                  = 'No';
                $sortable                    = 'No';
                $directory_title             = '';
            }

            $cs_sessionData            = $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'];
            $args                      = $cs_sessionData['filter_query'];
              
            if ( isset( $args['meta_query'][0]['value'] ) && $args['meta_query'][0]['value'] !='' ) {
                $meta_value                 = $args['meta_query'][0]['value'];
            } else {
                $meta_value                 = $cs_sessionData['post_directory_type'];
            }
            

            $directory_view             = '';
            $directory_pagination       = "Show Pagination";
            $count_post                 = '';
            $cs_directory_filterable    = $filterable;
            $directory_cat              = '';
            $directory_type             = '';
            $directory_view             = $cs_sessionData['post_directory_view'];
            $cs_chek_section_view       = '';
            //== Session
            cs_set_session( 'grid',$meta_value,$cs_sessionData['post_directory_orderby'],$cs_sessionData['post_directory_order'],$cs_postID,'true',$args,$cs_directory_fields_count,$directory_per_page,$cs_switch_views,$cs_directory_filterable,$directory_title,'',$sortable,$cs_node_id);
            //== Session End
            
            if ( isset ( $_REQUEST['sort'] ) && $_REQUEST['sort'] !='' ) {
                $args    = cs_sort_query( $_REQUEST['sort'] , $args );
            }
        }
        
        if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ){
            $cs_directory_grid_layout    = 'col-md-4';
        } else {
            $cs_directory_grid_layout    = 'col-md-3';
        }
		
        $currency_sign = isset($cs_theme_options['currency_sign'])?$cs_theme_options['currency_sign']:'$';
        $custom_query = new WP_Query($args);
        if ( $custom_query->have_posts() <> "" ) {
            echo '<div class="cs-directory grid_listing">'; 
			while ( $custom_query->have_posts() ): $custom_query->the_post();
            $width               	 = '370';
            $height                  = '280';
            $title_limit         	 = 5;
            $background              = '';
            $cs_post_id = $post->ID;
			$cs_directory            = get_post_meta($cs_post_id, "cs_directory_meta", true);
            $views                   = get_post_meta($cs_post_id, "cs_count_views", true);
            $directory_type_select 	 = get_post_meta($cs_post_id, "directory_type_select", true);
			
            if ( $cs_directory <> "" ) {
                $cs_xmlObject = new SimpleXMLElement($cs_directory);
            }
            
            $cs_page_id    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
            
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
                    <figure><a href="<?php echo esc_url(get_permalink($cs_post_id));?>">
                    	<?php echo cs_show_featured_text($cs_post_id); ?>
                    	<img alt="" src="<?php echo esc_url( $image_url );?>"></a>
                        <?php cs_total_ad_rating($cs_post_id);  ?>
                    </figure>
                </div>
          <?php }?>
          <div class="content_info">
                <h2 itemprop="itemReviewed"  itemtype="http://schema.org/Thing">
                    <a href="<?php echo esc_url(get_permalink($cs_post_id));?>">
                        <?php echo wp_trim_words(get_the_title($cs_post_id),$title_limit,'...'); ?>
                    </a>
                </h2>
                <div class="cs-up-section">
					<?php 
                        // show ad urgetn or not
                    	cs_ad_urgent($cs_post_id);
                        if(isset($cs_post_price_saleprice_option) && $cs_post_price_saleprice_option == 'on'){?>
                        	<div class="dr_pricesection" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                           	<?php echo cs_get_directory_price( $cs_post_id ); ?>
                        	</div>
                  	 <?php } ?>
                </div>
                <?php
                $custom_fields = '';
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
                        $cs_page_id    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
                        $cs_display_image = '';
                            $cs_display_image = cs_get_user_avatar(1 ,$organizerID);
                            if( $cs_display_image <> ''){?>
                                <figure><a class="info-thumb" href="<?php echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>"><img height="30" width="30" src="<?php echo esc_url( $cs_display_image );?>" alt=""  /></a></figure>
                            <?php }else{?>
                                <figure><a class="info-thumb" href="<?php echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>"><?php echo get_avatar(get_the_author_meta('user_email',$organizerID), apply_filters('PixFill_author_bio_avatar_size', 30));?></a></figure>
                        <?php }?>
                       <span class="organizer-name">
                        <a href="<?php echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>"><?php echo get_the_author_meta('display_name',$organizerID );?></a>
                       </span>
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
         if ( $directory_pagination == "ShowPagination" and $count_post > $directory_per_page and $directory_per_page > 0) {
             if ( isset($_REQUEST['sort']) )    $qrystr .= "&amp;sort=".$_REQUEST['sort'];
             if ( isset($_REQUEST['page_id']) ) $qrystr .= "&amp;page_id=".$_REQUEST['page_id'];
             echo cs_pagination($count_post, $directory_per_page,$qrystr);
         }
      } else {
            echo '<div class="col-md-12"><div class="succ_mess"><p>';
                esc_html_e('No Directory Found','directory');
            echo '</p></div></div>';
       }
      
      die();
    }
    add_action('wp_ajax_cs_ajax_directory_grid', 'cs_ajax_directory_grid');
    add_action("wp_ajax_nopriv_cs_ajax_directory_grid", "cs_ajax_directory_grid");
}

//=====================================================================
// Ajax Directory Grid Box Shortcode
//=====================================================================
if (!function_exists('cs_ajax_directory_grid_two')) {
    
    function cs_ajax_directory_grid_two(){
        global $post,$wpdb,$cs_xmlObject,$cs_theme_options,$cs_elem_id;
		
		$cs_node_id         = '';
		$cs_paged_id        = '';
		if( isset( $_REQUEST['node_id'] ) && $_REQUEST['node_id'] !='' ) {
			$cs_node_id         = $_REQUEST['node_id'];
		}
		
		if( isset( $_REQUEST['paged_id'] ) && $_REQUEST['paged_id'] !='' ) {
			$paged_id         = $_REQUEST['paged_id'];
		}
		
        if ( isset( $_REQUEST['postID'] ) && $_REQUEST['postID'] !='' ) {
            $cs_postID        = $_REQUEST['postID'];
            $cs_sessionData    = isset($_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data']) ? $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'] : '';
        }
        
		if( isset( $cs_sessionData ) && $cs_sessionData == '' ){
			echo '<span style="display:none"><script>jQuery(location).attr("href", "'.esc_url( get_permalink( $cs_postID ) ).'");</script> session_destroyed</span>';
			die();
		}
		
        if ( ( isset( $cs_sessionData['query_string'] ) &&  $cs_sessionData['query_string'] == 'false' ) ||  $_REQUEST['filters'] == 'false'  ) {
        
        foreach ( $_REQUEST as $keys => $values ) {
            $$keys = $values;
        }


        $cs_directory_options =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
        $cs_page_id    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';

        date_default_timezone_set('UTC');
        $current_time = current_time('Y/m/d');
        
        ob_start();
        
        $organizer_filter 			= '';
        $user_meta_key              = '';
        $user_meta_value            = '';
        $meta_compare 				= "=";
        $meta_value   				= '';
        $meta_key     				= '';
        $directory_title    = isset( $cs_sessionData['directory_title'] ) ?  $cs_sessionData['directory_title'] :  $directory_title;
        
        if ( isset( $cs_switch_views ) && $cs_switch_views !='' ){
            $cs_switch_views = explode(",", $cs_switch_views);
        } else {
            $cs_switch_views    =  array();
        }
        
        $directory_type = isset( $_REQUEST['type'] ) ? $_REQUEST['type'] : '';
        
        if ( isset( $directory_type ) && $directory_type == 0 ) {
            $directory_type    = '';
        }
        
        $meta_compare = "=";
        $meta_value   = $directory_type;
        $meta_key      = 'directory_type_select';
        
        $meta_fields_array = array();
        $meta_fields_array = array('relation' => 'AND',);
        
        if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='asc'){
            $order    = 'ASC';
        } else{
            $order    = 'DESC';
        }
        
        $backendFilter    = true; 
        $sort_key         = '';
        $meta_type        = 'CHAR';
        
        if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='alphabetical'){
            $orderby    = 'title';
            $order        = 'ASC';
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='recent'){
            $orderby    = 'post_date';
            $order        = 'DESC';
            
            
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='popular'){
            $backendFilter         = false; 
            $meta_fields_array[]   = array('key' => 'cs_count_views');
            $sort_key              = 'cs_count_views';
            $meta_type             = 'NUMERIC';
            $orderby               = 'meta_value';
            $order                 = 'DESC';
        
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='high-price'){
            $backendFilter         = false; 
            $meta_fields_array[]   = array('key' => 'dynamic_post_sale_type');
            $sort_key              = 'dynamic_post_sale_newprice';
            $meta_type             = 'NUMERIC';
            $orderby               = 'meta_value_num';
            $order                 = 'DESC';
            
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='low-price'){
            $backendFilter         = false; 
            $meta_fields_array[]   = array('key' => 'dynamic_post_sale_type');
            $sort_key              = 'dynamic_post_sale_newprice';
            $meta_type             = 'NUMERIC';
            $orderby               = 'meta_value_num';
            $order                 = 'ASC';
        } else{
            $orderby      = 'meta_value';
            $order        = 'DESC';
        }

		$cs_directory_options =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
        $cs_counter_directory = 0;
            
        if ( empty($_REQUEST['page_id_all']) ) $_REQUEST['page_id_all'] = 1;
            
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
                                    
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
        
        } else {
            $args = array(
                'posts_per_page'            =>         "-1",
                'post_type'                    =>         'directory',
                'post_status'                =>         'publish',
                'orderby'                    =>         $orderby,
                'order'                        =>         $order,
            );
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
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
            'posts_per_page'               => "$directory_per_page",
            'paged'                        => $_REQUEST['page_id_all'],
            'post_type'                    => 'directory',
            'meta_key'                     => (string)$sort_key,
            'meta_type'                    => $meta_type,
            'post_status'                  => 'publish',
        );
                    
        $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter ,  $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
    
        $args['orderby'] = $orderby;
        $args['order']      = $order;
        
        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }
        $sortable = isset($cs_sessionData['sortable']) ? $cs_sessionData['sortable'] : 'Yes';
        //== Sesstion
        cs_set_session( 'grid-box',$directory_type,$orderby,$order,$postID,'false','',$cs_directory_fields_count,$directory_per_page,$cs_switch_views,$cs_directory_filterable, $directory_title,'',$sortable,$cs_node_id);
        //== Session End
			if ( isset ( $_POST['sort'] ) && $_POST['sort'] !='' ) {
				$args    = cs_sort_query( $_POST['sort'] , $args );
			}
        
        } else {
             $cs_postID        = $_REQUEST['postID'];
            
            if ( isset( $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'] ) && $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'] !='' ) {
                 $sessionData    			= $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'];
                 $cs_directory_fields_count = $sessionData['fields_limit'];
                 $directory_per_page        = $sessionData['pagination'];
                 $cs_switch_views           = $sessionData['switch_views'];
                 $filterable                = $sessionData['filterable'];
                 $sortable                  = $sessionData['sortable'];
                 $directory_title           = $sessionData['directory_title'];
            } else {
                $cs_directory_fields_count   = 4;
                $directory_per_page          = 10;
                $cs_switch_views             = 'list,grid,grid-box,grid-box-four-column,map';
                $cs_switch_views             = explode(",", $cs_switch_views);
                $filterable                  = 'No';
                $sortable                    = 'No';
                $directory_title             = '';
            }

            $cs_sessionData            = $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'];
            $args                      = $cs_sessionData['filter_query'];
              
            if ( isset( $args['meta_query'][0]['value'] ) && $args['meta_query'][0]['value'] !='' ) {
                $meta_value                 = $args['meta_query'][0]['value'];
            } else {
                $meta_value                 = $cs_sessionData['post_directory_type'];
            }
            

            $directory_view             = '';
            $directory_pagination     = "Show Pagination";
            $count_post                = '';
            $cs_directory_filterable    = $filterable;
            $directory_cat                = '';
            $directory_type                = '';
            $directory_view                = $cs_sessionData['post_directory_view'];
            $cs_chek_section_view         = '';
            //== Session
            cs_set_session( 'grid-box',$meta_value,$cs_sessionData['post_directory_orderby'],$cs_sessionData['post_directory_order'],$cs_postID,'true',$args,$cs_directory_fields_count,$directory_per_page,$cs_switch_views,$cs_directory_filterable,$directory_title,'',$sortable,$cs_node_id);
            //== Session End
            
            if ( isset ( $_REQUEST['sort'] ) && $_REQUEST['sort'] !='' ) {
                $args    = cs_sort_query( $_REQUEST['sort'] , $args );
            }

        }
        
        if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ){
            $cs_directory_grid_layout    = 'col-md-4';
        } else {
            $cs_directory_grid_layout    = 'col-md-4';
        }
		
        $currency_sign = isset($cs_theme_options['currency_sign'])?$cs_theme_options['currency_sign']:'$';
        $custom_query = new WP_Query($args);
        if ( $custom_query->have_posts() <> "" ) {
            echo '<div class="cs-directory lightbox grid_two_listing">'; 
			while ( $custom_query->have_posts() ): $custom_query->the_post();
            $width               	 = '370';
            $height                  = '280';
            $title_limit         	 = 5;
            $background              = '';
            $cs_post_id = $post->ID;
			$cs_directory            = get_post_meta($cs_post_id, "cs_directory_meta", true);
            $views                   = get_post_meta($cs_post_id, "cs_count_views", true);
            $directory_type_select 	 = get_post_meta($cs_post_id, "directory_type_select", true);
			
            if ( $cs_directory <> "" ) {
                $cs_xmlObject = new SimpleXMLElement($cs_directory);
            }
            
           $cs_page_id    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
            
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
                            <h2 itemprop="name" itemscope itemtype="http://schema.org/Thing"><a href="<?php echo esc_url(get_permalink($cs_post_id ));?>">
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
                        <div class="cs-location-address" itemscope itemtype="http://schema.org/CreativeWork" itemprop="Place">
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
         if ( $directory_pagination == "ShowPagination" and $count_post > $directory_per_page and $directory_per_page > 0) {
             if ( isset($_REQUEST['sort']) )    $qrystr .= "&amp;sort=".$_REQUEST['sort'];
             if ( isset($_REQUEST['page_id']) ) $qrystr .= "&amp;page_id=".$_REQUEST['page_id'];
             echo cs_pagination($count_post, $directory_per_page,$qrystr);
         }
      } else {
            echo '<div class="col-md-12"><div class="succ_mess"><p>';
                esc_html_e('No Directory Found','directory');
            echo '</p></div></div>';
       }
      
      die();
    }
    add_action('wp_ajax_cs_ajax_directory_grid_two', 'cs_ajax_directory_grid_two');
    add_action("wp_ajax_nopriv_cs_ajax_directory_grid_two", "cs_ajax_directory_grid_two");
}


//=====================================================================
// Ajax Directory Grid Box Four Column Shortcode
//=====================================================================
if (!function_exists('cs_ajax_directory_grid_box_four_column')) {
    
    function cs_ajax_directory_grid_box_four_column(){
        global $post,$wpdb,$cs_xmlObject,$cs_theme_options,$cs_elem_id;
		
		$cs_node_id         = '';
		$cs_paged_id        = '';
		if( isset( $_REQUEST['node_id'] ) && $_REQUEST['node_id'] !='' ) {
			$cs_node_id         = $_REQUEST['node_id'];
		}
		
		if( isset( $_REQUEST['paged_id'] ) && $_REQUEST['paged_id'] !='' ) {
			$paged_id         = $_REQUEST['paged_id'];
		}
		
        if ( isset( $_REQUEST['postID'] ) && $_REQUEST['postID'] !='' ) {
            $cs_postID        = $_REQUEST['postID'];
            $cs_sessionData    = isset($_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data']) ? $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'] : '';
        }
        
		if( isset( $cs_sessionData ) && $cs_sessionData == '' ){
			echo '<span style="display:none"><script>jQuery(location).attr("href", "'.esc_url( get_permalink( $cs_postID ) ).'");</script> session_destroyed</span>';
			die();
		}
		
        if ( ( isset( $cs_sessionData['query_string'] ) &&  $cs_sessionData['query_string'] == 'false' ) ||  $_REQUEST['filters'] == 'false'  ) {
        
        foreach ( $_REQUEST as $keys => $values ) {
            $$keys = $values;
        }


        $cs_directory_options =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
        $cs_page_id    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';

        date_default_timezone_set('UTC');
        $current_time = current_time('Y/m/d');
        
        ob_start();
        
        $organizer_filter 			= '';
        $user_meta_key              = '';
        $user_meta_value            = '';
        $meta_compare 				= "=";
        $meta_value   				= '';
        $meta_key     				= '';
        $directory_title    = isset( $cs_sessionData['directory_title'] ) ?  $cs_sessionData['directory_title'] :  $directory_title;
        
        if ( isset( $cs_switch_views ) && $cs_switch_views !='' ){
            $cs_switch_views = explode(",", $cs_switch_views);
        } else {
            $cs_switch_views    =  array();
        }
        
        $directory_type = isset( $_REQUEST['type'] ) ? $_REQUEST['type'] : '';
        
        if ( isset( $directory_type ) && $directory_type == 0 ) {
            $directory_type    = '';
        }
        
        $meta_compare = "=";
        $meta_value   = $directory_type;
        $meta_key      = 'directory_type_select';
        
        $meta_fields_array = array();
        $meta_fields_array = array('relation' => 'AND',);
        
        if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='asc'){
            $order    = 'ASC';
        } else{
            $order    = 'DESC';
        }
        
        $backendFilter    = true; 
        $sort_key         = '';
        $meta_type        = 'CHAR';
        
        if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='alphabetical'){
            $orderby    = 'title';
            $order        = 'ASC';
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='recent'){
            $orderby    = 'post_date';
            $order        = 'DESC';
            
            
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='popular'){
            $backendFilter         = false; 
            $meta_fields_array[]   = array('key' => 'cs_count_views');
            $sort_key              = 'cs_count_views';
            $meta_type             = 'NUMERIC';
            $orderby               = 'meta_value';
            $order                 = 'DESC';
        
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='high-price'){
            $backendFilter         = false; 
            $meta_fields_array[]   = array('key' => 'dynamic_post_sale_type');
            $sort_key              = 'dynamic_post_sale_newprice';
            $meta_type             = 'NUMERIC';
            $orderby               = 'meta_value_num';
            $order                 = 'DESC';
            
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='low-price'){
            $backendFilter         = false; 
            $meta_fields_array[]   = array('key' => 'dynamic_post_sale_type');
            $sort_key              = 'dynamic_post_sale_newprice';
            $meta_type             = 'NUMERIC';
            $orderby               = 'meta_value_num';
            $order                 = 'ASC';
        } else{
            $orderby      = 'meta_value';
            $order        = 'DESC';
        }

		$cs_directory_options =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
        $cs_counter_directory = 0;
            
        if ( empty($_REQUEST['page_id_all']) ) $_REQUEST['page_id_all'] = 1;
            
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
                                    
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
        
        } else {
            $args = array(
                'posts_per_page'            =>         "-1",
                'post_type'                    =>         'directory',
                'post_status'                =>         'publish',
                'orderby'                    =>         $orderby,
                'order'                        =>         $order,
            );
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
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
            'posts_per_page'               => "$directory_per_page",
            'paged'                        => $_REQUEST['page_id_all'],
            'post_type'                    => 'directory',
            'meta_key'                     => (string)$sort_key,
            'meta_type'                    => $meta_type,
            'post_status'                  => 'publish',
        );
                    
        $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter ,  $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
    
        $args['orderby'] = $orderby;
        $args['order']      = $order;
        
        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }
        $sortable = isset($cs_sessionData['sortable']) ? $cs_sessionData['sortable'] : 'Yes';
        //== Sesstion
        cs_set_session( 'grid-box-four-column',$directory_type,$orderby,$order,$postID,'false','',$cs_directory_fields_count,$directory_per_page,$cs_switch_views,$cs_directory_filterable, $directory_title,'',$sortable,$cs_node_id);
        //== Session End
			if ( isset ( $_POST['sort'] ) && $_POST['sort'] !='' ) {
				$args    = cs_sort_query( $_POST['sort'] , $args );
			}
        
        } else {
             $cs_postID        = $_REQUEST['postID'];
            
            if ( isset( $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'] ) && $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'] !='' ) {
                 $sessionData    			= $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'];
                 $cs_directory_fields_count = $sessionData['fields_limit'];
                 $directory_per_page        = $sessionData['pagination'];
                 $cs_switch_views           = $sessionData['switch_views'];
                 $filterable                = $sessionData['filterable'];
                 $sortable                  = $sessionData['sortable'];
                 $directory_title           = $sessionData['directory_title'];
            } else {
                $cs_directory_fields_count   = 4;
                $directory_per_page          = 10;
                $cs_switch_views             = 'list,grid,grid-box,grid-box-four-column,map';
                $cs_switch_views             = explode(",", $cs_switch_views);
                $filterable                  = 'No';
                $sortable                    = 'No';
                $directory_title             = '';
            }

            $cs_sessionData            = $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'];
            $args                      = $cs_sessionData['filter_query'];
              
            if ( isset( $args['meta_query'][0]['value'] ) && $args['meta_query'][0]['value'] !='' ) {
                $meta_value                 = $args['meta_query'][0]['value'];
            } else {
                $meta_value                 = $cs_sessionData['post_directory_type'];
            }
            

            $directory_view             = '';
            $directory_pagination       = "Show Pagination";
            $count_post                 = '';
            $cs_directory_filterable    = $filterable;
            $directory_cat              = '';
            $directory_type             = '';
            $directory_view             = $cs_sessionData['post_directory_view'];
            $cs_chek_section_view       = '';
            //== Session
            cs_set_session( 'grid-box-four-column',$meta_value,$cs_sessionData['post_directory_orderby'],$cs_sessionData['post_directory_order'],$cs_postID,'true',$args,$cs_directory_fields_count,$directory_per_page,$cs_switch_views,$cs_directory_filterable,$directory_title,'',$sortable,$cs_node_id);
            //== Session End
            
            if ( isset ( $_REQUEST['sort'] ) && $_REQUEST['sort'] !='' ) {
                $args    = cs_sort_query( $_REQUEST['sort'] , $args );
            }

        }
        
        if ( isset( $cs_directory_filterable ) && $cs_directory_filterable == 'Yes' ){
            $cs_directory_grid_layout    = 'col-md-3';
        } else {
            $cs_directory_grid_layout    = 'col-md-3';
        }
		
        $currency_sign = isset($cs_theme_options['currency_sign'])?$cs_theme_options['currency_sign']:'$';
        $custom_query = new WP_Query($args);
        if ( $custom_query->have_posts() <> "" ) {
            echo '<div class="cs-directory lightbox grid_two_listing">'; 
			while ( $custom_query->have_posts() ): $custom_query->the_post();
            $width               	 = '370';
            $height                  = '280';
            $title_limit         	 = 5;
            $background              = '';
            $cs_post_id = $post->ID;
			$cs_directory            = get_post_meta($cs_post_id, "cs_directory_meta", true);
            $views                   = get_post_meta($cs_post_id, "cs_count_views", true);
            $directory_type_select 	 = get_post_meta($cs_post_id, "directory_type_select", true);
			
            if ( $cs_directory <> "" ) {
                $cs_xmlObject = new SimpleXMLElement($cs_directory);
            }
            
           $cs_page_id    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
            
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
                    </a>
                    <?php }?> 
                    <figcaption>
                        <div class="cs-text">
                            <?php cs_total_ad_rating($cs_post_id); ?>
                            <h2 itemprop="name" itemscope itemtype="http://schema.org/Thing"><a href="<?php echo esc_url(get_permalink($cs_post_id ));?>">
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
                        <div class="cs-location-address" itemscope itemtype="http://schema.org/CreativeWork" itemprop="Place">
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
         if ( $directory_pagination == "ShowPagination" and $count_post > $directory_per_page and $directory_per_page > 0) {
             if ( isset($_REQUEST['sort']) )    $qrystr .= "&amp;sort=".$_REQUEST['sort'];
             if ( isset($_REQUEST['page_id']) ) $qrystr .= "&amp;page_id=".$_REQUEST['page_id'];
             echo cs_pagination($count_post, $directory_per_page,$qrystr);
         }
      } else {
            echo '<div class="col-md-12"><div class="succ_mess"><p>';
                esc_html_e('No Directory Found','directory');
            echo '</p></div></div>';
       }
      
      die();
    }
    add_action('wp_ajax_cs_ajax_directory_grid_box_four_column', 'cs_ajax_directory_grid_box_four_column');
    add_action("wp_ajax_nopriv_cs_ajax_directory_grid_box_four_column", "cs_ajax_directory_grid_box_four_column");
}

//====================================================================
// Ajax Directory Map Shortcode
//====================================================================
if (!function_exists('cs_ajax_map_view')) {
    function cs_ajax_map_view( ) {
        global $post,$wpdb,$cs_theme_options,$cs_elem_id;
        
		$cs_node_id         = '';
		if( isset( $_REQUEST['node_id'] ) && $_REQUEST['node_id'] !='' ) {
			$cs_node_id         = $_REQUEST['node_id'];
		}
		
        if ( isset( $_REQUEST['postID'] ) && $_REQUEST['postID'] !='' ) {
            $cs_postID        = $_REQUEST['postID'];
            $cs_sessionData   = isset($_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data']) ? $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'] : '';
        }
        
		if( isset( $cs_sessionData ) && $cs_sessionData == '' ){
			echo '<span style="display:none"><script>jQuery(location).attr("href", "'.esc_url( get_permalink( $cs_postID ) ).'");</script> session_destroyed</span>';
			die();
		}
		
        if ( ( isset( $cs_sessionData['query_string'] ) &&  $cs_sessionData['query_string'] == 'false' ) || $_REQUEST['filters'] == 'false'  ) {
        
        foreach ($_REQUEST as $keys => $values) {
            $$keys = $values;
        }
        
 		$cs_directory_options    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
        $cs_page_id    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';

        date_default_timezone_set('UTC');
        $current_time = current_time('Y/m/d');
        
        ob_start();
        
        $organizer_filter 			= '';
        $user_meta_key              = '';
        $user_meta_value            = '';
        $meta_compare 				= "=";
        $meta_value   				= '';
        $meta_key     				= '';
        $directory_title    = isset( $cs_sessionData['directory_title'] ) ?  $cs_sessionData['directory_title'] :  $directory_title;
        
        if ( isset( $cs_switch_views ) && $cs_switch_views !='' ){
            $cs_switch_views = explode(",", $cs_switch_views);
        } else {
            $cs_switch_views    =  array();
        }
        
        if ( isset( $type ) && $type == 0 ) {
            $directory_type    = '';
        } else {
            $directory_type    = $type;
        }
        
        $meta_compare  = "=";
        $meta_value    = $directory_type;
        $meta_key      = 'directory_type_select';
        
        $meta_fields_array = array();
        $meta_fields_array = array('relation' => 'AND',);
        if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='asc'){
            $order    = 'ASC';
        } else{
            $order    = 'DESC';
        }
        
        $backendFilter    = true; 
        $sort_key         = '';
        $meta_type        = 'CHAR';
        
        if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='alphabetical'){
            $orderby    = 'title';
            $order        = 'ASC';
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='recent'){
            $orderby    = 'post_date';
            $order        = 'DESC';
            
            
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='popular'){
            $backendFilter         = false; 
            $meta_fields_array[]   = array('key' => 'cs_count_views');
            $sort_key              = 'cs_count_views';
            $meta_type             = 'NUMERIC';
            $orderby               = 'meta_value';
            $order                 = 'DESC';
        
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='high-price'){
            $backendFilter         = false; 
            $meta_fields_array[]   = array('key' => 'dynamic_post_sale_type','compare' => '=','value' => 'paid');
            $sort_key              = 'dynamic_post_sale_newprice';
            $meta_type             = 'NUMERIC';
            $orderby               = 'meta_value';
            $order                 = 'DESC';
            
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='low-price'){
            $backendFilter         = false; 
            $meta_fields_array[]   = array('key' => 'dynamic_post_sale_type','compare' => '=','value' => 'paid');
            $sort_key              = 'dynamic_post_sale_newprice';
            $meta_type             = 'NUMERIC';
            $orderby               = 'meta_value';
            $order                 = 'ASC';
        } else{
            $orderby    = 'meta_value';
            $order        = 'DESC';
        }
        
        $cs_directory_options =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
        $cs_counter_directory = 0;
            
        if ( empty($_REQUEST['page_id_all']) ) $_REQUEST['page_id_all'] = 1;
  
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
                                        'value'       => $meta_value,
                                        'compare'     => $meta_compare,
                                    );
                                    
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
        
        } else {
            $args = array(
                'posts_per_page'               =>         "-1",
                'post_type'                    =>         'directory',
                'post_status'                  =>         'publish',
                'orderby'                      =>         $orderby,
                'order'                        =>         $order,
            );
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
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
            'posts_per_page'            => "-1",
            'paged'                        => $_REQUEST['page_id_all'],
            'post_type'                    => 'directory',
            'meta_key'                    => (string)$sort_key,
            'meta_type'                    => $meta_type,
            'post_status'                => 'publish',
        );
                    
        $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
    
        $args['orderby'] = $orderby;
        $args['order']      = $order;
        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }
        $sortable = isset($cs_sessionData['sortable']) ? $cs_sessionData['sortable'] : 'Yes';
        //== Session
        cs_set_session( 'map',$directory_type,$orderby,$order,$postID,'false','',$cs_directory_fields_count,$directory_per_page,$cs_switch_views ,$cs_directory_filterable,$directory_title,'',$sortable,$cs_node_id);
        //== Session End 
			if ( isset ( $_POST['sort'] ) && $_POST['sort'] !='' ) {
				$args    = cs_sort_query( $_POST['sort'] , $args );
			}
			
       } else {
            $cs_postID        = $_REQUEST['postID'];
            $cs_sessionData    = $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'];
            
            if ( isset( $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'] ) && $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'] !='' ) {
                 $sessionData                = $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'];
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
                $directory_view               = 'listing';
                $filterable                   = 'No';
                $sortable                     = 'No';
                $directory_title              = $sessionData['directory_title'];
            }
            
            
            $args            = $cs_sessionData['filter_query'];
            
            if ( isset( $args['meta_query'][0]['value'] ) && $args['meta_query'][0]['value'] !='' ) {
                $meta_value                 = $args['meta_query'][0]['value'];
            } else {
                $meta_value                 = $cs_sessionData['post_directory_type'];
            }

             $directory_view                 = '';
            $directory_pagination         = "Show Pagination";
            $count_post                    = '';
            $directory_cat                = '';
            $directory_type                = '';
            $cs_directory_filterable    = $filterable;
            $cs_chek_section_view         = '';
            
            //== Session
            cs_set_session( 'map',$meta_value,$cs_sessionData['post_directory_orderby'],$cs_sessionData['post_directory_order'],$cs_postID,'true',$args,$cs_directory_fields_count,$directory_per_page,$cs_switch_views,$cs_directory_filterable,$directory_title,'',$sortable,$cs_node_id);
            //== Session End
            
            if ( isset ( $_REQUEST['sort'] ) && $_REQUEST['sort'] !='' ) {
                $args    = cs_sort_query( $_REQUEST['sort'] , $args );
            }

        }

        $custom_query = new WP_Query($args);
        $total_post     = $custom_query->post_count;
        if ( $custom_query->have_posts() <> "" ) {
				$cs_map_lock							= isset($cs_theme_options['cs_map_lock']) ? $cs_theme_options['cs_map_lock'] : ''; 
                //$goe_location_enable            		= isset($cs_theme_options['goe_location_enable']) ? $cs_theme_options['goe_location_enable'] : 'No';
				$cs_streat_view            				= isset($cs_theme_options['cs_streat_view']) ? $cs_theme_options['cs_streat_view'] : 'No';
		
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
                    
                $width                = '370';
                $height                = '280';
                $randomid         = cs_generate_random_string('10');
                $cs_page_id    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
                $total_post     = $custom_query->post_count;
                $directories     = array();
                $directory_array = array('count'=>$total_post);
                wp_directory::cs_googlemapcluster_scripts();            

                $map_cluster_url         =  isset($cs_theme_options['cluster_map_marker']) ? $cs_theme_options['cluster_map_marker'] : '';
                $map_cluster_color        = isset($cs_theme_options['cluster_map_marker_color']) ? $cs_theme_options['cluster_map_marker_color'] : '#000';
                $currency_sign             = isset($cs_theme_options['currency_sign']) ? $cs_theme_options['currency_sign']:'$';
                
                while ( $custom_query->have_posts() ): $custom_query->the_post();
                  $organizerID             = get_post_meta( $post->ID, 'directory_organizer', true );
                  $latitude             = get_post_meta( $post->ID, 'dynamic_post_location_latitude', true );
                  $longitude             = get_post_meta( $post->ID, 'dynamic_post_location_longitude', true );
                  $direcotry_type_id     = get_post_meta( $post->ID, 'directory_type_select', true );
                  $map_marker_destination         = get_post_meta( $direcotry_type_id, 'cs_destination_url_input', true );
                  $user_profile_url     = cs_user_profile_link($cs_page_id, 'dashboard', $organizerID);
                  $dir_featured_till      = get_post_meta($post->ID, "dir_featured_till", true);
                  $location                 = get_post_meta($post->ID, "dynamic_post_location_address", true);
                  
                  
                  $cs_page_id = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
                  
				  $organizerID = cs_get_organizer_id( $post->ID );
                  
                  $image_url = get_post_meta( $post->ID, '_directory_image_gallery', true );
                  $image_url = array_filter( explode( ',', $image_url ) );
                  if ( isset( $image_url ) && ! empty( $image_url ) ) {
					$image_url		= cs_attachment_image_src( $image_url[0] ,$width,$height); 
                  } else {
                    $image_url		= '';
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
                  
				  $is_featured_text	=  '';
				   
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
                                            'price'               => $price,
					    'objective'		=> get_post_custom('dir_objective'),
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
                
                if ( trim( $cs_cluster_marker_color_input ) == '' ) {
                    $cs_cluster_marker_color_input    = '#000';
                }
				$cs_map_auto_zoom   = isset($cs_theme_options['cs_map_auto_zoom']) ? $cs_theme_options['cs_map_auto_zoom'] : 'off';
				$cs_svg_marker = wp_directory::plugin_url().'assets/images/orange-marker.svg';
                ?>
                <div class="col-md-12 cs-directory default_listing">
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
                                var dataobj = jQuery.parseJSON( '<?php echo addslashes($json_array);?>' );
                                cs_googlecluster_map('<?php echo esc_js($rand_id);?>', '<?php echo esc_js($Latitude);?>', '<?php echo esc_js($Longitude);?>', '<?php echo esc_js($map_marker_url);?>', dataobj, '<?php echo esc_js($cs_map_type);?>', <?php echo absint($map_zoom);?>, '<?php echo esc_js($cs_cluster_marker_color_input);?>','style-1','<?php echo esc_url($cs_map_auto_zoom); ?>', '<?php echo esc_url($cs_svg_marker); ?>', '<?php echo esc_js($cs_map_lock); ?>');
                                jQuery(".loader").html('');
                                jQuery("#map<?php echo esc_attr($rand_id);?>").css({
                                    "opacity" :"1"
                                });
                                
                                jQuery( "#streetView<?php echo esc_attr($rand_id);?>" ).click(function() {
                                   toggleStreetView('<?php echo esc_js($Latitude);?>','<?php echo esc_js($Longitude);?>','<?php echo esc_attr($rand_id);?>');
                                });
                          });
                        
                        jQuery(document).ready(function($) {
                            jQuery(".fullscreen") .click(function() {
                                jQuery("body").toggleClass("body-fullscreen");
                                jQuery("#map-container<?php echo esc_attr($rand_id);?>").height(jQuery(window).height);
                                map = jQuery("#map-container<?php echo esc_attr($rand_id);?>");
                                google.maps.event.trigger(map, "resize");
								//jQuery(window).load();
								cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>');
                            });
                         });
                         
                         if(jQuery("#map<?php echo esc_attr($rand_id);?>").length>0){
                                /*jQuery( ".dir-map-search" ).live("change", function() {
                                    cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>');
                                    return false;
                                });
                                jQuery( ".SumoSelect .optWrapper ul.options li" ).live("click", function() {
                                    cs_directory_map_search('<?php echo esc_js($rand_id);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>');
                                    return false;
                                });*/
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
                </div>
        <?php 
         }
         die();
    }
add_action('wp_ajax_cs_ajax_map_view', 'cs_ajax_map_view');
add_action("wp_ajax_nopriv_cs_ajax_map_view", "cs_ajax_map_view");
}

//====================================================================
// Ajax Directory Listing Shortcode
//====================================================================
if (!function_exists('cs_ajax_directory_listing')) {
    function cs_ajax_directory_listing()
    {
		
        global $post,$wpdb,$cs_theme_options,$cs_elem_id;
        
		$cs_node_id         = '';
		if( isset( $_REQUEST['node_id'] ) && $_REQUEST['node_id'] !='' ) {
			$cs_node_id         = $_REQUEST['node_id'];
		}
		
        if ( isset( $_REQUEST['postID'] ) && $_REQUEST['postID'] !='' ) {
            $cs_postID        = $_REQUEST['postID'];
            $cs_sessionData    = isset($_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data']) ? $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'] : '' ;
        }
		
		if( isset( $cs_sessionData ) && $cs_sessionData == '' ){
			echo '<span style="display:none"><script>jQuery(location).attr("href", "'.esc_url( get_permalink( $cs_postID ) ).'");</script> session_destroyed</span>';
			die();
		}
		
        if ( ( isset( $cs_sessionData['query_string'] ) &&  $cs_sessionData['query_string'] == 'false') || $_REQUEST['filters'] == 'false'  ) {
        
        foreach ($_REQUEST as $keys => $values) {
            $$keys = $values;
        }
        
        date_default_timezone_set('UTC');
        $current_time = current_time('Y/m/d');
        
        ob_start();
        
        $organizer_filter 			= '';
        $user_meta_key              = '';
        $user_meta_value            = '';
        $meta_compare 				= "=";
        $meta_value   				= '';
        $meta_key      				= '';
        $directory_title    = isset( $cs_sessionData['directory_title'] ) ?  $cs_sessionData['directory_title'] :  $directory_title;
        
        if ( isset( $cs_switch_views ) && $cs_switch_views !='' ){
            $cs_switch_views = explode(",", $cs_switch_views);
        } else {
            $cs_switch_views    =  array();
        }

        $directory_type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
        
        if ( isset( $directory_type ) && $directory_type == 0 ) {
            $directory_type    = '';
        }
        
        $meta_compare = "=";
        $meta_value   = $directory_type;
        $meta_key      = 'directory_type_select';
        
        $meta_fields_array = array();
        $meta_fields_array = array('relation' => 'AND',);
        if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='asc'){
            $order    = 'ASC';
        } else{
            $order    = 'DESC';
        }
        
        $backendFilter    = true; 
        $sort_key         = '';
        $meta_type        = 'CHAR';
        
        if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='alphabetical'){
            $orderby    = 'title';
            $order        = 'ASC';
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='recent'){
            $orderby    = 'post_date';
            $order        = 'DESC';
            
            
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='popular'){
            $backendFilter         = false; 
            $meta_fields_array[]   = array('key' => 'cs_count_views');
            $sort_key              = 'cs_count_views';
            $meta_type             = 'NUMERIC';
            $orderby			   = 'meta_value_num';
            $order                 = 'DESC';
        
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='high-price'){
            $backendFilter         = false; 
            $meta_fields_array[]   = array('key' => 'dynamic_post_sale_newprice');
            $sort_key              = 'dynamic_post_sale_newprice';
            $meta_type             = 'NUMERIC';
            $orderby               = 'meta_value_num';
            $order                 = 'DESC';
            
        } else if(isset($_REQUEST['sort']) and $_REQUEST['sort']=='low-price'){
            $backendFilter         = false; 
            $meta_fields_array[]   = array('key' => 'dynamic_post_sale_newprice');
            $sort_key              = 'dynamic_post_sale_newprice';
            $meta_type             = 'NUMERIC';
            $orderby               = 'meta_value_num';
            $order                 = 'ASC';
        } else{
            $orderby    = 'meta_value';
            $order        = 'DESC';
        }

        $cs_directory_options =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
		
         $cs_counter_directory = 0;
            
        if ( empty($_REQUEST['page_id_all']) ) $_REQUEST['page_id_all'] = 1;
    
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
             $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
        
        } else {
            $args = array(
                'posts_per_page'            => "-1",
                'post_type'                 => 'directory',
                'post_status'               => 'publish',
                'orderby'                   => $orderby,
                'order'                     => $order,
            );
            $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
        } 

        if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }
            
        $custom_query = new WP_Query($args);
        $count_post = 0;
        $counter = 1;
        $count_post = $custom_query->post_count;
        
        
        $args = array(
            'posts_per_page'            => "$directory_per_page",
            'paged'                        => $_REQUEST['page_id_all'],
            'post_type'                    => 'directory',
            'meta_key'                    => (string)$sort_key,
            'meta_type'                    => $meta_type,
            'post_status'                => 'publish',
        );

        $args = cs_directory_meta_query( $args , $meta_fields_array , $cs_directory_filter , $backendFilter, $cs_featured_on_top , $cs_listing_sorting );
        $args['orderby'] = $orderby;
        $args['order']   = $order;
        $sortable = isset($cs_sessionData['sortable']) ? $cs_sessionData['sortable'] : 'Yes';
        //== Session
        cs_set_session( 'listing',$directory_type,$orderby,$order,$postID,'false','',$cs_directory_fields_count,$directory_per_page,$cs_switch_views,$cs_directory_filterable,$directory_title,'',$sortable,$cs_node_id);
        //== Session End
		
		if ( isset ( $_POST['sort'] ) && $_POST['sort'] !='' ) {
			$args    = cs_sort_query( $_POST['sort'] , $args );
		}
			 
        } else {
            $cs_postID        = $_REQUEST['postID'];
            if ( isset( $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'] ) && $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'] !='' ) {
                 $sessionData               = $_SESSION[$cs_postID.'_node_'.$cs_node_id.'_data'];
                 $cs_directory_fields_count = $sessionData['fields_limit'];
                 $directory_per_page        = $sessionData['pagination'];
                 $cs_switch_views           = $sessionData['switch_views'];
                 $filterable                = $sessionData['filterable'];
                 $sortable                  = $sessionData['sortable'];
                 $directory_title           = $sessionData['directory_title'];
            } else {
                $cs_directory_fields_count  = 4;
                $directory_per_page			= 10;
                $cs_switch_views			= 'list,grid,grid-box,grid-box-four-column,map';
                $cs_switch_views			= explode(",", $cs_switch_views);
                $filterable					= 'No';
                $sortable					= 'No';
                $directory_title			= '';
            }

            $args                        = $cs_sessionData['filter_query'];  
			  
            if ( isset( $args['meta_query'][0]['value'] ) && $args['meta_query'][0]['value'] !='' ) {
                $meta_value                 = $args['meta_query'][0]['value'];
            } else {
                $meta_value                 = $cs_sessionData['post_directory_type'];
            }
            
            $directory_view                = '';
            $directory_pagination          = "Show Pagination";
            $count_post                    = '';
            $cs_directory_filterable       = $filterable;
            $directory_cat                 = '';
            $directory_type                = '';
            $directory_view                = $cs_sessionData['post_directory_view'];
            $cs_chek_section_view          = '';
            
            //== Session
            cs_set_session( 'listing',$meta_value,$cs_sessionData['post_directory_orderby'],$cs_sessionData['post_directory_order'],$cs_postID,'true',$args,$cs_directory_fields_count,$directory_per_page,$cs_switch_views,$cs_directory_filterable,$directory_title,'',$sortable,$cs_node_id);
            //== Session End
            
            if ( isset ( $_POST['sort'] ) && $_POST['sort'] !='' ) {
                $args    = cs_sort_query( $_POST['sort'] , $args );
            }
        }
        
		if(isset($directory_cat) && $directory_cat <> '' && $directory_cat <> '0'){
            $directory_category_array = array('directory-category' => "$directory_cat");
            $args = array_merge($args, $directory_category_array);
        }
		
		$currency_sign             = isset($cs_theme_options['currency_sign'])?$cs_theme_options['currency_sign']:'$';

		
        $custom_query = new WP_Query($args);
        if ( $custom_query->have_posts() <> "" ) { 
             echo '<div class="col-md-12 cs-directory default_listing lightbox">'; 
			while ( $custom_query->have_posts() ): $custom_query->the_post();
            $width              = '370';
            $height             = '280';
            $title_limit        = 15;
            $background         = '';
            $cs_post_id = $post->ID;
			$cs_directory       = get_post_meta($cs_post_id, "cs_directory_meta", true);
            $directory_type_select 	= get_post_meta($cs_post_id, "directory_type_select", true);
			$dir_pkg_expire_date    = get_post_meta($cs_post_id, "dir_pkg_expire_date", true);
			
            if ( $cs_directory <> "" ) {
                $cs_xmlObject = new SimpleXMLElement($cs_directory);
            }
            
            $cs_page_id    =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
            
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
            $randId = cs_generate_random_string(5);
            
            $width_thumb    = 370;
            $height_thumb     = 280;
            $image_url = get_post_meta( get_the_ID(), '_directory_image_gallery', true );
            $image_url = array_filter( explode( ',', $image_url ) );
            if ( isset( $image_url ) && ! empty( $image_url ) ) {
                $image_url	= cs_attachment_image_src( $image_url[0] ,$width_thumb,$height_thumb); 
            } else {
                $image_url   = get_template_directory_uri().'/assets/images/no-image4x3.jpg';
            }
        ?>
       <article class="directory-section">
       <?php if ( isset( $image_url ) && $image_url != '' && 1 == 0) { ?>
        <div class="cs_thumbsection">
            <ul class="dr_thumbsection">
                  <li class="featured_thumb">
                      <a href="<?php echo esc_url(get_permalink($cs_post_id));?>">
						  <?php echo cs_show_featured_text($cs_post_id) ;?>	
                          <img src="<?php echo esc_url( $image_url );?>" alt="">
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
                <?php cs_ad_urgent($cs_post_id); ?>
            </h2>
            <?php cs_total_ad_rating($cs_post_id);  ?>   
            <span class="organizer-name">
                <a href="<?php echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>">
                    <?php echo get_the_author_meta('display_name',$organizerID );?>
                </a>
            </span>
            <?php
            if(isset($dir_pkg_expire_date) && $dir_pkg_expire_date){
            ?>
            <span class="cs-expiry-date"><?php echo date_i18n(get_option('date_format'), strtotime($dir_pkg_expire_date)); ?></span>
            <?php
            }
            ?>        
            <?php
            
            $custom_fields = '';
            				 echo '<div class="cs-locplace"><div class="cs-location">Location: </div><div class="cs-place">' . get_post_meta($cs_post_id,'dynamic_post_location_address',true) . '</div> </div>';

//			echo '<p>Location: ' . get_post_meta($cs_post_id,'dynamic_post_location_address',true) . '</p>';
            if($cs_directory_fields_count <> 'none' ){
				cs_get_post_specification_list($cs_post_id,$directory_type_select,$cs_directory_fields_count);
			}else{
				echo '<p>'.cs_get_content($cs_post_id,40).'</p>';
				//echo '<p>'.cs_get_the_excerpt('150','false','').'</p>';	
			}
            ?>
            <div class="cs-detail-info"> 
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
 					$cs_page_id =  isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
                    $cs_display_image	= '';
                        $cs_display_image = cs_get_user_avatar(1 ,$organizerID);
                        if( $cs_display_image <> ''){?>
                            <figure><a class="info-thumb" href="<?php echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>"><img height="30" width="30" src="<?php echo esc_url( $cs_display_image );?>" alt="" /></a></figure>
                        <?php }else{?>
                            <figure><a class="info-thumb" href="<?php echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>"><?php echo get_avatar(get_the_author_meta('user_email',$organizerID), apply_filters('PixFill_author_bio_avatar_size', 30));?></a></figure>
                    <?php }?>
                   <span class="organizer-name">
                    <a href="<?php echo cs_user_profile_link($cs_page_id, 'dashboard', $organizerID); ?>"><?php echo get_the_author_meta('display_name',$organizerID );?></a>
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
         if ( $directory_pagination == "ShowPagination" and $count_post > $directory_per_page and $directory_per_page > 0) {
                 if ( isset($_REQUEST['sort']) )    $qrystr .= "&amp;sort=".$_REQUEST['sort'];
                 if ( isset($_REQUEST['page_id']) ) $qrystr .= "&amp;page_id=".$_REQUEST['page_id'];
                 echo cs_pagination($count_post, $directory_per_page,$qrystr);
         }
        } else {
          echo '<div class="succ_mess"><p>';
            esc_html_e('No Directory Found','directory');
          echo '</p></div>';
      }
      die();
    }
    add_action('wp_ajax_cs_ajax_directory_listing', 'cs_ajax_directory_listing');
    add_action("wp_ajax_nopriv_cs_ajax_directory_listing", "cs_ajax_directory_listing");
}
