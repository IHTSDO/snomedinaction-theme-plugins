<?php
/**
 * File Type: Loops Shortcode Function
 */
 
//======================================================================
// Adding Clients Start
//======================================================================

if (!function_exists('cs_clients_shortcode')) {
    function cs_clients_shortcode($atts, $content = "") {
        global    $cs_clients_view,$cs_client_border,$cs_client_gray;
        $defaults = array('column_size'=>'','cs_clients_view' => '','cs_client_gray' => '','cs_client_border' => '','cs_client_section_title' => '','cs_client_class' => '','cs_client_animation' => '','cs_custom_animation_duration' => '1');
        extract( shortcode_atts( $defaults, $atts ) );
        
        $CustomId    = '';
        if ( isset( $cs_client_class ) && $cs_client_class ) {
            $CustomId    = 'id="'.$cs_client_class.'"';
        }
        
        if ( trim($cs_client_animation) !='' ) {
            $cs_client_animation    = 'wow'.' '.$cs_client_animation;
        } else {
            $cs_client_animation    = '';
        }
        
        $column_class  = cs_custom_column_class($column_size);
        $cs_client_border = $cs_client_border == 'yes' ? 'has_border' : 'no-clients-border';
        $owlcount = rand(40, 9999999);
        $section_title = '';
        if(isset($cs_client_section_title) && trim($cs_client_section_title) <> ''){
            $section_title = '<div class="cs-section-title"><h2>'.$cs_client_section_title.'</h2></div>';
        }
        $html  = '';
        $html .= '<div '.$CustomId.' class="'.$column_class.' '.$cs_client_animation.' '.$cs_client_class.'">';
        $html .= $section_title;
        if ($cs_clients_view == 'grid') {
            $html    .= '<div class="cs-partner '.$cs_client_border.'">';
            $html    .= '<ul class="row">';
            $html    .= do_shortcode($content);
            $html    .= '</ul>';
            $html    .= '</div>';
        } else {
            cs_owl_carousel();
        ?>
            <script>  
                jQuery(document).ready(function($) {
                    $("#owl-demo-three-<?php echo esc_js($owlcount);?>").owlCarousel({
                        nav: true,
                        margin: 0,
						loop: true,
						autoplay: true,
						autoplayTimeout: 1000,
                        navText: [
                            "<i class='icon-angle-left'></i>",
                            "<i class='icon-angle-right'></i>"
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
                                items: 6
                            }
                        }
                        });
                 }); 
            </script>
          <?php 
             $html    .= '<div class="cs-partner partnerslide '.$cs_client_border.'">';
            $html    .= '<ul class="row owl-carousel nxt-prv-v2 cs-theme-carousel " id="owl-demo-three-'.$owlcount.'">';
            $html    .= do_shortcode($content);    
            $html    .= '</ul>';
            $html    .= '</div>';
        }
        $html    .= '</div>';
        return $html;
    }
    add_shortcode('cs_clients', 'cs_clients_shortcode');
}

//======================================================================
// Adding Clients Logo Start
//======================================================================
if (!function_exists('cs_clients_item_shortcode')) {
    function cs_clients_item_shortcode($atts, $content = "") {
        global    $cs_clients_view,$cs_client_border,$cs_client_gray;
        $defaults = array('cs_bg_color'=>'','cs_website_url'=>'','cs_client_title'=>'','cs_client_logo'=>'');
        extract( shortcode_atts( $defaults, $atts ) );
        $html         = '';
       $grayScale = (isset($cs_client_gray) && $cs_client_gray == 'yes')? 'grayscale' : '';
        $tooltip    = '';
        if ( isset ( $cs_client_title ) && $cs_client_title != '' ) {
            $tooltip    = 'title="'.$cs_client_title.'"';
        }
        $cs_url = $cs_website_url ?  $cs_website_url : 'javascript:;';
        if ($cs_clients_view == 'grid') {
            if (isset($cs_client_logo) && !empty($cs_client_logo)) {
                
                $html    .= '<li class="col-md-2"  style="background-color:'.$cs_bg_color.'"><figure><a '.$tooltip.' href="'.esc_url($cs_url).'">
				<img class="'.sanitize_html_class($grayScale).'" src="'.esc_url($cs_client_logo).'" alt="" ></a></figure></li>';
            }
        } else {
            if (isset($cs_client_logo) && !empty($cs_client_logo)) {
                    $html    .= '<li class="item" style="background-color:'.$cs_bg_color.'"><figure><a href="'.esc_url($cs_url).'" '.$tooltip.'>
					<img class="'.sanitize_html_class($grayScale).'" src="'.esc_url($cs_client_logo).'" alt=""></a></figure></li>';
            }
        }
        return $html;
    }
    add_shortcode('clients_item', 'cs_clients_item_shortcode');
}
// Adding Clients Logo End


//======================================================================
// Adding Multiple services Start
//======================================================================

if (!function_exists('cs_multiple_services_shortcode')) {
    function cs_multiple_services_shortcode($atts, $content = "") {
        $defaults = array('column_size'=>'','cs_multiple_service_section_title' => '','cs_multiple_services_view' => '');
		global $cs_multiple_services_view;
        extract( shortcode_atts( $defaults, $atts ) );
                
        $column_class = cs_custom_column_class($column_size);
        $cs_section_title = '';
        if(isset($cs_multiple_service_section_title) && trim($cs_multiple_service_section_title) <> ''){
            $cs_section_title = '<div class="cs-section-title"><h2>'.$cs_multiple_service_section_title.'</h2></div>';
        }
        $html  = '';
		if( $column_class <> '' ) {
        $html .= '<div class="'.$column_class.'">';
		}
        $html .= $cs_section_title;

		$html .= '<div class="cs-services '.sanitize_html_class($cs_multiple_services_view).'">';
		$html .= do_shortcode($content);
		$html .= '</div>';
        
		if( $column_class <> '' ) {
        $html .= '</div>';
		}
        return $html;
    }
    add_shortcode('cs_multiple_services', 'cs_multiple_services_shortcode');
}

//======================================================================
// Adding Multiple services Item Start
//======================================================================
if (!function_exists('cs_multiple_services_item_shortcode')) {
    function cs_multiple_services_item_shortcode($atts, $content = "") {
		$defaults = array('cs_title_color'=>'','cs_text_color'=>'','cs_bg_color'=>'','cs_website_url'=>'','cs_multiple_service_title'=>'','cs_multiple_service_logo'=>'','cs_multiple_service_btn'=>'','cs_multiple_service_btn_link'=>'','cs_multiple_service_btn_bg_color'=>'','cs_multiple_service_btn_txt_color'=>'');
		global $cs_multiple_services_view;
		extract( shortcode_atts( $defaults, $atts ) );
		$html = '';
		
		$cs_title_color = $cs_title_color <> '' ? ' style="color:'.$cs_title_color.' !important;"' : '';
		$cs_text_color = $cs_text_color <> '' ? ' style="color:'.$cs_text_color.' !important;"' : '';
		$cs_bg_color = $cs_bg_color <> '' ? ' style="background-color:'.$cs_bg_color.' !important;"' : '';
		$cs_multiple_service_btn_txt_color = $cs_multiple_service_btn_txt_color <> '' ? ' color:'.$cs_multiple_service_btn_txt_color.' !important;' : '';
		$cs_multiple_service_btn_bg_color = $cs_multiple_service_btn_bg_color <> '' ? ' background-color:'.$cs_multiple_service_btn_bg_color.' !important;' : '';
		
		switch($cs_multiple_services_view){
			case 'service-default-three':
				$cs_col_class = 'col-md-4';
				break;
			case 'service-flat':
				$cs_col_class = 'col-md-6';
				break;
			case 'service-flat-three':
				$cs_col_class = 'col-md-4';
				break;
			default:
				$cs_col_class = 'col-md-3';
		}
			
		$html .= '
		<article class="'.sanitize_html_class($cs_col_class).'">
			<div class="col-box"'.$cs_bg_color.'>';
				if( $cs_multiple_service_logo <> '' ) {
				$html .= '<figure><img src="'.$cs_multiple_service_logo.'" alt="'.$cs_multiple_service_title.'"></figure>';
				}
				$html .= '
				<div class="text">';
					if( $cs_multiple_service_title <> '' ) {
					$html .= '<h2><a'.$cs_title_color.' href="'.$cs_website_url.'">'.$cs_multiple_service_title.'</a></h2>';
					}
					$html .= '<p'.$cs_text_color.'>'.do_shortcode($content).'</p>';
					if( $cs_multiple_service_btn <> '' ) {
					$html .= '<a style="'.$cs_multiple_service_btn_txt_color.$cs_multiple_service_btn_bg_color.'" href="'.$cs_multiple_service_btn_link.'" class="service-btn">'.$cs_multiple_service_btn.'</a>';
					}
				$html .= '
				</div>
			</div>
		</article>';
		
		return $html;
    }
    add_shortcode('multiple_services_item', 'cs_multiple_services_item_shortcode');
}
// Adding Multiple services Item End

//======================================================================
// Adding Content Slider ( Custom Posts ) Start 
//======================================================================
if (!function_exists('cs_contentslider_shortcode')) {
    function cs_contentslider_shortcode( $atts ) {
         global $post, $wpdb;
        $defaults = array('column_size'=>'1/1','cs_contentslider_title' => '','cs_contentslider_dcpt_cat'=>'','cs_contentslider_orderby'=>'DESC','orderby'=>'ID','cs_contentslider_description'=>'yes','cs_contentslider_excerpt'=>'255', 'cs_contentslider_num_post'=>'10','cs_contentslider_class' => '','cs_contentslider_animation' => '','cs_custom_animation_duration' => '');
        extract( shortcode_atts( $defaults, $atts ) );
        
        $CustomId    = '';
        if ( isset( $cs_contentslider_class ) && $cs_contentslider_class ) {
            $CustomId    = 'id="'.$cs_contentslider_class.'"';
        }
        
        if ( trim($cs_contentslider_animation) !='' ) {
            $cs_custom_animation    = 'wow'.' '.$cs_contentslider_animation;
        } else {
            $cs_custom_animation    = '';
        }
        
        $column_class  = cs_custom_column_class($column_size);
        $owlcount = rand(40, 9999999);
        ob_start();
        
        $width    = 860;
        $height    = 418;
        
        //==Get Post Type    
        $args_all = array('posts_per_page' => "$cs_contentslider_num_post", 'post_type' => 'post', 'order' => $cs_contentslider_orderby, 'orderby' => $orderby, 'post_status' => 'publish');
        
        if(isset($cs_dcpt_cat) && $cs_dcpt_cat <> '' &&  $cs_dcpt_cat <> '0'){
            $blog_category_array = array('category_name' => "$cs_dcpt_cat");
            $args_all = array_merge($args_all, $blog_category_array);
        }
        if(isset($cs_contentslider_title) && $cs_contentslider_title <> ''){
            echo '<div class="'.cs_allow_special_char($column_class).'"><div class="cs-section-title"><h2>'.cs_allow_special_char($cs_contentslider_title).'</h2></div></div>';
        }
        ?>
        <div <?php echo cs_allow_special_char($CustomId);?> class="col-md-12 <?php echo cs_allow_special_char($cs_contentslider_animation .' '.$cs_contentslider_class);?>" style="animation-duration:<?php echo cs_allow_special_char($cs_custom_animation_duration);?>s">
        <?php 
            
        
            $query = new WP_Query( $args_all ); 
            $post_count = $query->post_count;  
            cs_owl_carousel();
            if ( $query->have_posts() ) { ?>
        <script>
        jQuery(document).ready(function($) {
        $('#owl-contents-slider-<?php echo esc_js($owlcount) ;?>').owlCarousel({
                loop:true,
                nav:true,
                autoplay: true,
                margin: 15,
                navText: [
                       "<i class='icon-angle-left'></i>",
                       "<i class='icon-angle-right'></i>"
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
      	<div id="syncsliders">
			<div class="owl-carousel content-slider" id="owl-contents-slider-<?php echo esc_attr($owlcount) ;?>">
                <?php while ( $query->have_posts() ) : $query->the_post();?>
                <?php $image_url = cs_attachment_image_src( get_post_thumbnail_id((int)get_the_id()),$width,$height );?>
                    <div class="item">
                    <figure><a href="<?php esc_url(the_permalink()); ?>"><img src="<?php echo esc_url($image_url);?>" alt=""></a>
                        <?php if ($cs_contentslider_description == 'yes') {?>  
                        <figcaption>
                            <h2><a href="<?php esc_url(the_permalink()); ?>"><?php the_title(); ?></a></h2>
                            <p><?php echo cs_get_the_excerpt((int)$cs_contentslider_excerpt,false, '');?>  </p>
                        </figcaption>
                        <?php  } ?>
                    </figure>  
                </div>               
                <?php
                endwhile;
				wp_reset_postdata();
				?>
            </div>
        </div>
        <?php }
        $post_data = ob_get_clean();
        return $post_data;
        
    }
    add_shortcode( 'cs_contentslider', 'cs_contentslider_shortcode' );
}

//======================================================================
// Adding Blog Posts thumb image
//=====================================================================
if ( !function_exists( 'cs_get_post_thumb_view' ) ) {
    function cs_get_post_thumb_view( $post_thumb_view = '' , $inside_post_thumb_view = ''){
        
         $iconType = '';
         if ( $post_thumb_view == 'Single Image' ){                                            
                if ( $inside_post_thumb_view  == 'Audio' ) {
                    $iconType = '<i class="icon-microphone"></i>';
                } else if ( $inside_post_thumb_view == 'Single Image' ){
                    $iconType = '<i class="icon-photo"></i>';
                } else if ( $inside_post_thumb_view == 'Slider' ){
                    $iconType = '<i class="icon-slideshare"></i>';
                } else if ( $inside_post_thumb_view == 'Video' ){
                    $iconType = '<i class="icon-play-circle"></i>';
                } else {
                    $iconType = '<i class="icon-photo"></i>';
                }
        } else {
			$iconType = '<i class="icon-slideshare"></i>';
        }
         return $iconType;
    }
}
// Adding Blog Posts End

//======================================================================
// Adding Post Attachments
//=====================================================================
function cs_post_attachments($gallery_meta_form){
	$galleryConter = rand(40, 9999999);
    ?>        
        <div class="to-social-network">
            <div class="gal-active" style="padding-left:0px !important">
                <div class="clear"></div>
                <div class="dragareamain">
                <div class="placehoder">Gallery is Empty. Please Select Media <img src="<?php echo esc_url(get_template_directory_uri().'/include/assets/images/bg-arrowdown.png');?>" alt="" />
                </div>
                <ul id="gal-sortable" class="gal-sortable-<?php echo esc_attr($gallery_meta_form);?>">
                    <?php 
                        global $cs_node, $cs_xmlObject, $cs_counter,$post;
                        
                        if ( $gallery_meta_form == 'gallery_slider_meta_form'){
                            $type    = 'gallery_slider';
                        } else {
                            $type    = 'gallery';
                        }
                        $cs_counter_gal = 0;
                         
                        if(isset($cs_xmlObject->$type) && count($cs_xmlObject->$type)>0){
                            foreach ( $cs_xmlObject->$type as $cs_node ){
                                $cs_counter_gal++;
                                $cs_counter = $post->ID.$cs_counter_gal;
                                if ($type == 'gallery_slider'){
									cs_slider_clone();
                                } else {
                                    cs_gallery_clone();
                                }
                            }
                        }
                    ?>
                </ul>
                </div>
            </div>
            <div class="to-social-list">
                <div class="soc-head">
                    <h5><?php _e('Select Media','dir');?></h5>
                    <div class="right">
                        <?php if ( $gallery_meta_form == 'gallery_slider_meta_form'){ ?>
                             <input type="button" class="button reload" value="Reload" onclick="refresh_media_slider(<?php echo esc_attr($galleryConter);?>)" />
                        <?php } else { ?>
                            <input type="button" class="button reload" value="Reload" onclick="refresh_media(<?php echo esc_attr($galleryConter);?>)" />
                        <?php } ?>
                        <input id="cs_log" name="cs_logo" type="button" class="uploadfile button" value="Upload Media" />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
                    <script type="text/javascript">
                        function show_next(page_id, total_pages){
                            //var dataString = 'action=media_pagination&id='+id+'&func='+func+'&page_id='+page_id+'&total_pages='+total_pages;
                            var dataString = 'action=media_pagination&page_id='+page_id+'&total_pages='+total_pages;
                            /*if (func == 'slider') {
                                var    pagination    = 'pagination_slider';
                            } else {
                                var    pagination    = 'pagination_clone';
                            }*/
                            jQuery("#pagination").html("<img src='<?php echo esc_js(esc_url(get_template_directory_uri().'/include/assets/images/ajax_loading.gif'))?>' />");
                            jQuery.ajax({
                                type:'POST', 
                                url: "<?php echo esc_js(esc_url(admin_url('admin-ajax.php')));?>",
                                data: dataString,
                                success: function(response) {
                                    jQuery("#pagination").html(response);
                                }
                            });
                        }
                        function slider_show_next(page_id, total_pages){
                            //var dataString = 'action=media_pagination&id='+id+'&func='+func+'&page_id='+page_id+'&total_pages='+total_pages;
                            var dataString = 'action=cs_slider_media_pagination&page_id='+page_id+'&total_pages='+total_pages;
                            /*if (func == 'slider') {
                                var    pagination    = 'pagination_slider';
                            } else {
                                var    pagination    = 'pagination_clone';
                            }*/
                            jQuery(".pagination_slider").html("<img src='<?php echo esc_js(esc_url(get_template_directory_uri()))?>/include/assets/images/ajax_loading.gif' />");
                            jQuery.ajax({
                                type:'POST', 
                                url: "<?php echo esc_js(esc_url(admin_url('admin-ajax.php')));?>",
                                data: dataString,
                                success: function(response) {
                                    jQuery(".pagination_slider").html(response);
                                }
                            });
                        }
                        function refresh_media(id){
                             var dataString = 'action=media_pagination&id='+id+'&func=slider';
                            jQuery(".pagination_clone").html("<img src='<?php echo esc_js(esc_url(get_template_directory_uri()))?>/include/assets/images/ajax_loading.gif' />");
                            jQuery.ajax({
                                type:'POST', 
                                url: "<?php echo esc_js(esc_url(admin_url('admin-ajax.php')));?>",
                                data: dataString,
                                success: function(response) {
                                    jQuery(".pagination_clone").html(response);
                                }
                            });
                        }
                        
                        function refresh_media_slider(id){
                            var dataString = 'action=cs_slider_media_pagination&id='+id+'&func=slider';
                            jQuery(".pagination_slider").html("<img src='<?php echo esc_js(esc_url(get_template_directory_uri()))?>/include/assets/images/ajax_loading.gif' />");
                            jQuery.ajax({
                                type:'POST', 
                                url: "<?php echo esc_js(esc_url(admin_url('admin-ajax.php')));?>",
                                data: dataString,
                                success: function(response) {
                                    jQuery(".pagination_slider").html(response);
                                }
                            });
                        }
                     </script>
                    <script>
                        jQuery(document).ready(function($) {
                            $(".gal-sortable-<?php echo esc_js($galleryConter);?>").sortable({
                                cancel:'li div.poped-up',
                            });
                            //$(this).append("#gal-sortable").clone() ;
                            });
                            var counter = 0;
                            var count_items = <?php echo esc_js($cs_counter_gal)?>;
                            if ( count_items > 0 ) {
                                jQuery(".dragareamain") .addClass("noborder");    
                            }

                            function clone(path,id){
                                counter = counter + 1;
                                var cls = 'gal-sortable-gallery_meta_form';
                                var dataString = 'path='+path+'&counter='+counter+'&action=gallery_clone';
                                jQuery("."+cls).append("<li id='loading'><img src='<?php echo esc_js(esc_url(get_template_directory_uri()))?>/include/assets/images/ajax_loading.gif' /></li>");
                                jQuery.ajax({
                                    type:'POST', 
                                    url: "<?php echo esc_js(esc_url(admin_url('admin-ajax.php')));?>",
                                    data: dataString,
                                    success: function(response) {
                                        jQuery("#loading").remove();
                                        jQuery("."+cls).append(response);
                                        count_items = jQuery("."+cls +' '+"li") .length;
                                            if ( count_items > 0 ) {
                                                jQuery(".dragareamain") .addClass("noborder");    
                                            }
                                    }
                                });
                            }
                            
                            function slider(path,id){
                                counter = counter + 1;
                                var cls = 'gal-sortable-gallery_slider_meta_form';
                                var dataString = 'path='+path+'&counter='+counter+'&action=slider_clone';
                                jQuery("."+cls).append("<li id='loading'><img src='<?php echo esc_js(esc_url(get_template_directory_uri()))?>/include/assets/images/ajax_loading.gif' /></li>");
                                jQuery.ajax({
                                    type:'POST', 
                                    url: "<?php echo esc_js(esc_url(admin_url('admin-ajax.php')));?>",
                                    data: dataString,
                                    success: function(response) {
                                        jQuery("#loading").remove();
                                        jQuery("."+cls).append(response);
                                        count_items = jQuery("."+cls +' '+"li") .length;
                                            if ( count_items > 0 ) {
                                                jQuery(".dragareamain") .addClass("noborder");    
                                            }
                                    }
                                });
                            }
							function del_this(div,id){
                                jQuery("#"+div+' '+"#"+id).remove();
                                count_items = jQuery("#gal-sortable li") .length;
								if ( count_items == 0 ) {
									jQuery(".dragareamain") .removeClass("noborder");    
								}
                            }
                    </script>
                     <?php if ( $gallery_meta_form == 'gallery_slider_meta_form'){ ?>
                         <div id="pagination" class="pagination_slider"><?php cs_slider_media_pagination($gallery_meta_form,'slider');?></div>
                     <?php } else { ?>
                         <div id="pagination" class="pagination_clone"><?php media_pagination($gallery_meta_form,'clone');?></div>
                     <?php    
                     }
					 ?>
                   
                 <input type="hidden" name="<?php echo esc_attr($gallery_meta_form);?>" value="1" />
                <div class="clear"></div>
            </div>
         </div>
    <?php    
}


//=====================================================================
// Adding Posts flexslider 
//=====================================================================
if ( ! function_exists( 'cs_post_flex_slider' ) ) {

    function cs_post_flex_slider($width,$height,$postid,$view){
        global $cs_node,$cs_theme_options,$cs_counter_node;
        $cs_post_counter = rand(40, 9999999);
        $cs_counter_node++;
         if ( $view == 'post-list' ){
            $viewMeta    = 'post';  
        } else {
            $viewMeta    = $view;
        }
         $cs_meta_slider_options = get_post_meta("$postid", $viewMeta, true); 
        $totaImages = '';
        $cs_xmlObject_flex = new SimpleXMLElement($cs_meta_slider_options);
        $as_node = new stdClass();
        ?>
        <!-- Flex Slider -->
        <div id="flexslider<?php echo esc_attr($cs_post_counter); ?>">
            <div class="flexslider" style="display: none;">
                <ul class="slides">
                    <?php 
                        $cs_counter = 1;
                        
                        if ( $view == 'post' ){
                            $path    = 'cs_slider_path';
                            $sliderData    = $cs_xmlObject_flex->children()->gallery_slider;
                            $totaImages    = count($cs_xmlObject_flex->children()->gallery_slider);
                        } else if ( $view == 'post-list' ){
                            $path    = 'path';
                            $sliderData    = $cs_xmlObject_flex->children()->gallery;
                            $totaImages    = count($cs_xmlObject_flex->children()->gallery);
                        } else {
                            $path    = 'path';
                            $sliderData    = $cs_xmlObject_flex->children();
                            $totaImages    = count($cs_xmlObject_flex->children());
                        }
                        
                        foreach ( $sliderData as $as_node ){
                             $image_url = cs_attachment_image_src((int)$as_node->$path,$width,$height); 
                            echo '<li>
                                    <figure>
                                        <img src="'.esc_url($image_url).'" alt="">';
                                        if($as_node->title != '' && $as_node->description != '' || $as_node->title != '' || $as_node->description != ''){ ?>         
                                            <figcaption>
                                                <div class="container">
                                                    <?php if($as_node->title <> ''){?>
                                                        <h2 class="colr">
                                                            <?php 
                                                                if($as_node->link <> ''){ 
                                                                     echo '<a href="'.esc_url($as_node->link).'" target="'.esc_attr($as_node->link_target).'">' . esc_attr($as_node->title) . '</a>';
                            
                                                                } else {
                            
                                                                    echo esc_attr($as_node->title);
                                                                }
                                                            ?>
                                                        </h2>
                                                    <?php }
                                                        if($as_node->description <> ''){
                                                            echo '<p>'.substr($as_node->description, 0, 220);
                                                            if ( strlen($as_node->description) > 220 ) echo "...</p>";
                                                        }
                                                    ?>
                                                </div>
                                           </figcaption>
                              <?php }?>

                            </figure>
                        </li>
                    <?php 
                    $cs_counter++;
                    }
                ?>
              </ul>
          </div>
        </div>
        <?php cs_enqueue_flexslider_script(); ?>

        <!-- Slider height and width -->

        <!-- Flex Slider Javascript Files -->

        <script type="text/javascript">
            jQuery(window).load(function(){
                var speed = '6000'; 
                var slidespeed ='500';
                jQuery('#flexslider<?php echo esc_js($cs_post_counter); ?> .flexslider').flexslider({
                    animation: "fade", // fade
                    slideshow: true,
                    slideshowSpeed:speed,
                    animationSpeed:slidespeed,
                    prevText:"<em class='icon-long-arrow-left'></em>",
                    nextText:"<em class='icon-long-arrow-right'></em>",
                    start: function(slider) {
                        jQuery('.flexslider').fadeIn();
                    }
                });
            });
        </script>
    <?php
    }
}


//=====================================================================
// Adding Twitter Tweets start
//=====================================================================
if (!function_exists('cs_tweets_shortcode')) {

    function cs_tweets_shortcode($atts, $content = "") {
        $defaults = array( 'column_size'=>'','cs_tweets_section_title' => '','cs_tweets_user_name' => 'default','cs_tweets_color' => '','cs_no_of_tweets' => '','cs_tweets_class' => '','cs_tweets_animation' => '','cs_custom_animation_duration' => '1');
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
        
        $CustomId    = '';
        if ( isset( $cs_tweets_class ) && $cs_tweets_class ) {
            $CustomId    = 'id="'.$cs_tweets_class.'"';
        }
        
        $rand_id = rand(5, 999999);
        $html = '';
        $section_title = '';
        if ($cs_tweets_section_title && trim($cs_tweets_section_title) !='') {
            //$section_title    = '<div class="cs-section-title '.$column_class.'"><h2>'.$cs_tweets_section_title.'</h2></div>';
        }
        $html .= '<div '.$CustomId.' class="twitter-section col-md-12 '.$cs_tweets_class.'" >';
        $html .= "<div class='widget_slider'><div class='flexslider flexslider".$rand_id."'><ul class='slides'>";
        $html .= cs_get_tweets($cs_tweets_user_name,$cs_no_of_tweets,$cs_tweets_color);
        $html.='</ul></div>';
        cs_enqueue_flexslider_script();
                $html.='<script type="text/javascript">
                        jQuery(document).ready(function(){
                            jQuery(".widget_slider .flexslider'.intval($rand_id).'").flexslider({
                                animation: "fade",
                                slideshow: true,
                                slideshowSpeed: 7000,
                                animationDuration: 600,
                                prevText:"<em class=\'icon-angle-up\'></em>",
                                nextText:"<em class=\'icon-angle-down\'></em>",
                                start: function(slider) {
                                    jQuery(".flexslider").fadeIn();
                                }
                            });
                        });
                    </script>';
        return $html;
    }
    add_shortcode('cs_tweets', 'cs_tweets_shortcode');
}

// Adding Twitter Tweets  End

//=====================================================================
// Get Twitter Tweets  Start
//=====================================================================
if (!function_exists('cs_get_tweets')) {
function cs_get_tweets($username,$numoftweets,$cs_tweets_color = ''){
            global $cs_theme_options;
            
            $username = html_entity_decode($username);
             $numoftweets = $numoftweets;        
             if($numoftweets == ''){ $numoftweets = 2;}
            if(strlen($username) > 1){
                
                    $text ='';
                    $return = '';
                    $cacheTime = 10000;
                    $transName = 'latest-tweets';
                    require_once get_template_directory() . '/include/theme-components/cs-twitter/twitteroauth.php';
                    $consumerkey = $cs_theme_options['cs_consumer_key'];
                    $consumersecret = $cs_theme_options['cs_consumer_secret'];
                    $accesstoken = $cs_theme_options['cs_access_token'];
                    $accesstokensecret = $cs_theme_options['cs_access_token_secret'];
                     $connection = new TwitterOAuth($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
                     $tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$username."&count=".$numoftweets);
                    if(!is_wp_error($tweets) and is_array($tweets)){
                        set_transient($transName, $tweets, 60 * $cacheTime);
                    }else{
                        $tweets = get_transient('latest-tweets');
                    }
                      if(!is_wp_error($tweets) and is_array($tweets)){
                        $twitter_text_color = '';
                        if(!empty($cs_tweets_color)){
                            $twitter_text_color = "style='color: $cs_tweets_color !important'";    
                        }
                        $rand_id    = rand(5, 300);
                        $exclude    = 0;
                        foreach($tweets as $tweet) {
                                $exclude++;
                                //if($exclude > 1 ){
                                $text = $tweet->{'text'};
                                foreach($tweet->{'user'} as $type => $userentity) {
                                        if($type == 'profile_image_url') {    
                                            $profile_image_url = $userentity;
                                        } else if($type == 'screen_name'){
                                            $screen_name = '<a href="https://twitter.com/' . $userentity . '" target="_blank" class="colrhover" title="' . $userentity . '">@' . $userentity . '</a>';
                                        }
                                    }
                                    foreach($tweet->{'entities'} as $type => $entity) {
                                        if($type == 'hashtags') {
                                            foreach($entity as $j => $hashtag) {
                                                $update_with = '<a href="https://twitter.com/search?q=%23' . $hashtag->{'text'} . '&amp;src=hash" target="_blank" title="' . $hashtag->{'text'} . '">#' . $hashtag->{'text'} . '</a>';
                                                $text = str_replace('#'.$hashtag->{'text'}, $update_with, $text);
                                            }
                                        } 
                                    } 
                                    $large_ts = time();
                                    $n = $large_ts - strtotime($tweet->{'created_at'});
                                    if($n < (60)){ $posted = sprintf(__('%d seconds ago','dir'),$n); }
                                    elseif($n < (60*60)) { $minutes = round($n/60); $posted = sprintf(_n('About a Minute Ago','%d Minutes Ago',$minutes,'dir'),$minutes); }
                                    elseif($n < (60*60*16)) { $hours = round($n/(60*60)); $posted = sprintf(_n('About an Hour Ago','%d Hours Ago',$hours,'dir'),$hours); }
                                    elseif($n < (60*60*24)) { $hours = round($n/(60*60)); $posted = sprintf(_n('About an Hour Ago','%d Hours Ago',$hours,'dir'),$hours); }
                                    elseif($n < (60*60*24*6.5)) { $days = round($n/(60*60*24)); $posted = sprintf(_n('About a Day Ago','%d Days Ago',$days,'dir'),$days); }
                                    elseif($n < (60*60*24*7*3.5)) { $weeks = round($n/(60*60*24*7)); $posted = sprintf(_n('About a Week Ago','%d Weeks Ago',$weeks,'dir'),$weeks); } 
                                    elseif($n < (60*60*24*7*4*11.5)) { $months = round($n/(60*60*24*7*4)) ; $posted = sprintf(_n('About a Month Ago','%d Months Ago',$months,'dir'),$months);}
                                    elseif($n >= (60*60*24*7*4*12)){$years=round($n/(60*60*24*7*52)) ; $posted = sprintf(_n('About a year Ago','%d years Ago',$years,'dir'),$years);}
                                    $return .='<li><div class="text" style="color:'.$cs_tweets_color.'"><i class="icon-twitter2"></i>';
                                    $return .= "" . $text . "";
                                //$return .= "<p><a href='https://twitter.com/".$username."'>@" . $username . "</a></p>";
                                    $return .= '<time datetime="2011-01-12" style="color:'.$cs_tweets_color.'">('. $posted. ')</time>';
                                    $return .="</div></li>";

                            //    }
                        }
                        $return .= "</div>";
                        //if(isset($profile_image_url) && $profile_image_url <> ''){$profile_image_url = '<img src="'.$profile_image_url.'" alt="">';} else {$profile_image_url = '';}
                        $return .= '<div class="follow-on">
                                    <div class="cs-tweet">
                                        <i class="icon-twitter"></i>
                                        <a href="https://twitter.com/'.$username.'" target="_blank"  style="color:'.$cs_tweets_color.'">@'. $username .'</a>
                                    </div>
                                   </div>';
                
                $return .= "</div>";
                return  $return;

         }else{
            if(isset($tweets->errors[0]) && $tweets->errors[0] <> ""){
                return  '<div class="cs-twitter item" data-hash="dummy-one"><h4>'.$tweets->errors[0]->message.". Please enter valid Twitter API Keys </h4></div>";
            }else{
                return  '<div class="cs-twitter item" data-hash="dummy-two"><h4>No Tweets Found.</h4></div>';
            }
        }
    }else{     
            return  '<div class="cs-twitter item" data-hash="dummy-three"><h4>No Tweets Found</h4></div>';
        }
  }
}