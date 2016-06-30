<?php
/**
 * The template for displaying header
 */
global  $cs_options, $cs_theme_options, $cs_node, $cs_xmlObject, $cs_page_option, $post;
	$cs_slider_position = '';    
	$cs_header_style 	= '';
	$cs_site_layout 	= '';

    $cs_theme_options = get_option('cs_theme_options');
  	if(is_page()){
		$cs_page_bulider = get_post_meta($post->ID, "cs_page_builder", true);
		$cs_xmlData = new stdClass();
		if(isset($cs_page_bulider) && $cs_page_bulider <> ''){
			$cs_xmlData = new SimpleXMLElement($cs_page_bulider);
			$slider_position 	= 	(!empty($cs_xmlData->slider_position))? $cs_xmlData->slider_position : '' ;
			$header_style		=	(!empty($cs_xmlData->header_banner_style))? $cs_xmlData->header_banner_style : '' ;
		}
 		$cs_page_options = (!empty($cs_xmlData->cs_page_options))? $cs_xmlData->cs_page_options : '' ;
		if(isset($cs_page_options) and $cs_page_options != 'default' and $cs_page_options <> ''){
			$cs_page_options = trim($cs_page_options);
			$settings =$cs_page_option['theme_options'][$cs_page_options]['theme_option'];
			$page_setting=unserialize(base64_decode($settings));
			$cs_theme_options =$page_setting;
		
		}else{
			$cs_theme_options = get_option('cs_theme_options');
		}
	}else{
		$cs_theme_options = get_option('cs_theme_options');	
	}
    //$cs_theme_options = get_option('cs_theme_options');
    if(!get_option('cs_theme_options')){
            $cs_activation_data=cs_reset_data();
            $cs_theme_options =  $cs_activation_data;
            $cs_theme_options['cs_default_layout_sidebar'] = 'sidebar-1';
			$cs_theme_options['cs_single_layout_sidebar'] = 'sidebar-1';
            $cs_theme_options['cs_footer_widget'] = 'off';
    }
    $cs_builtin_seo_fields =$cs_theme_options['cs_builtin_seo_fields'];
    if(isset($cs_theme_options['cs_layout'])){ $cs_site_layout =$cs_theme_options['cs_layout'];} else { $cs_site_layout == '';}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>><head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
		<?php
 		$cs_builtin_seo_fields =$cs_theme_options['cs_builtin_seo_fields'];
		$cs_seo_title  ='';
 		if(isset($cs_builtin_seo_fields) && $cs_builtin_seo_fields == 'on'){
			$post_type = get_post_type(get_the_ID());
			if(is_page()){
				$meta_element = 'cs_page_builder';
			} else {
				$meta_element = 'post';
			}
			$post_meta = get_post_meta(get_the_ID(), "$meta_element", true);
			if ( $post_meta <> "" ) {
				$cs_seo_xmlObject = new SimpleXMLElement($post_meta);
			}
			$cs_seo_title = isset($cs_seo_xmlObject->seosettings->cs_seo_title) ? $cs_seo_xmlObject->seosettings->cs_seo_title : '';
			$cs_seo_description = isset($cs_seo_xmlObject->seosettings->cs_seo_description) ? $cs_seo_xmlObject->seosettings->cs_seo_description : $cs_theme_options['cs_meta_description'];
			$cs_seo_keywords = isset($cs_seo_xmlObject->seosettings->cs_seo_keywords) ? $cs_seo_xmlObject->seosettings->cs_seo_keywords : $cs_theme_options['cs_meta_keywords'];
			if ( empty($cs_seo_xmlObject->slider_position) ) $cs_slider_position = ""; else $cs_slider_position = htmlspecialchars($cs_seo_xmlObject->slider_position);
			if ( empty($cs_seo_xmlObject->header_banner_style) ) $cs_header_style = ""; else $cs_header_style = $cs_seo_xmlObject->header_banner_style;
			?>
            <meta name="keywords" content="<?php echo $cs_seo_keywords;?>">
          	<meta name="description" content="<?php echo $cs_seo_description;?>">
            <?php } ?>
           <title>	
            <?php
				if(isset($cs_seo_title) and $cs_seo_title <> ''){
					echo  bloginfo('name').' | '.$cs_seo_title;
				} else {	
					 wp_title( '|', true, 'right'); 
				} 
		   ?>
     	</title>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">    
    <?php 
        if(isset($cs_theme_options['cs_custom_css']) and $cs_theme_options['cs_custom_css']<>''){
		$cs_content = $cs_theme_options['cs_custom_css'];
		$content = str_replace(array('&gt;'), '>', $cs_content);
            echo '<style type="text/css">
                '.$content. '
				
            </style> ';
        }
		
		?>
		<?php
        if(isset($cs_theme_options['cs_custom_js']) and $cs_theme_options['cs_custom_js']<>''){
       		 echo '<script type="text/javascript">
					 ' . $cs_theme_options['cs_custom_js'] . '
				  </script> ';
        }
        if ( function_exists( 'cs_header_settings' ) ) { cs_header_settings(); }           
		if(isset($cs_theme_options['cs_style_rtl']) and $cs_theme_options['cs_style_rtl']=='on'){
                cs_rtl();
        }
        //=====================================================================
        // Header Colors
        //=====================================================================
        if ( function_exists( 'cs_header_color' ) ) { cs_header_color(); }        
        //=====================================================================
        // Theme Colors
        //=====================================================================
        if ( function_exists( 'cs_footer_color' ) ) { cs_footer_color(); }
        if ( function_exists( 'cs_theme_colors' ) ) { cs_theme_colors(); }
        if ( is_singular() && get_option( 'thread_comments' )  && get_comments_number()) {
            wp_enqueue_script( 'comment-reply' );
		}
		wp_head();
    ?>
    </head>
    <?php flush(); ?>
    <body <?php body_class(); if($cs_site_layout !='full_width'){ echo cs_bg_image(); }?>>
     <?php if ( function_exists( 'cs_under_construction' ) ) { cs_under_construction(); } ?>
        <!-- Wrapper -->
    <div class="wrapper <?php  echo cs_header_postion_class(); ?> wrapper_<?php cs_wrapper_class(); ?>">
           <!-- Header -->
    <?php
         if($cs_header_style == 'custom_slider' && $cs_slider_position == 'above'){
			 
            if ( function_exists( 'cs_subheader_style' ) ) { cs_subheader_style(); }
            if ( function_exists( 'cs_get_headers' ) ) { cs_get_headers(); }
			if ( function_exists( 'cs_below_header_style' ) ) { cs_below_header_style(); }
            if(isset($cs_theme_options['cs_smooth_scroll']) and $cs_theme_options['cs_smooth_scroll'] == 'on'){
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    cs_nicescroll();
                });
            </script>
            <?php
            }
            if (isset($cs_theme_options['cs_sitcky_header_switch']) and $cs_theme_options['cs_sitcky_header_switch'] == "on"){
                cs_scrolltofix();
            ?>
            <script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery('.main-navbar').scrollToFixed();    
				});
            </script>
            <?php }?>
            <div class="clear"></div>
             <?php
        }else{
            if ( function_exists( 'cs_get_headers' ) ) { cs_get_headers(); }
			if ( function_exists( 'cs_below_header_style' ) ) { cs_below_header_style(); }
            if(isset($cs_theme_options['cs_smooth_scroll']) and $cs_theme_options['cs_smooth_scroll'] == 'on'){
           		 cs_scrolltofix();
            ?>            
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    cs_nicescroll();    
                });
            </script>
            <?php            
            }
            if (isset($cs_theme_options['cs_sitcky_header_switch']) and $cs_theme_options['cs_sitcky_header_switch'] == "on"){
                cs_scrolltofix();
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function(){
                    jQuery('.main-navbar').scrollToFixed();    
                });
            </script>
            <?php }?>
            <div class="clear"></div>
            <!-- Breadcrumb SecTion -->
            <?php 
                if ( function_exists( 'cs_subheader_style' ) ) { cs_subheader_style(); }
            }
            ?>
        <!-- Main Content Section -->
        <main id="main-content">
            <!-- Main Section -->
            <div class="main-section">