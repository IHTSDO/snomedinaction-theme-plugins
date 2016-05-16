<?php
/**
	Shop Archive
 * @package LMS


 */
/***************** Shop Page ******************/
global $cs_node,$cs_theme_options;
			if(isset($cs_theme_options['cs_excerpt_length']) && $cs_theme_options['cs_excerpt_length'] <> ''){ $default_excerpt_length = $cs_theme_options['cs_excerpt_length']; }else{ $default_excerpt_length = '255';}; 
			$cs_layout 	=  $cs_theme_options['cs_single_post_layout'];
			
			if ( $cs_layout == "sidebar left") {
				$cs_layout = "content-right col-md-9";
				$custom_height = 390;
			}
			else if ( $cs_layout == "sidebar right" ) {
				$cs_layout = "content-left col-md-9";
				$custom_height = 390;
			}
			else {
				$cs_layout = "col-md-12";
				$custom_height = 390;
			}
			
			$cs_sidebar	= $cs_theme_options['cs_single_layout_sidebar'];
			$cs_tags_name = 'post_tag';
			$cs_categories_name = 'category';
?>
	<!-- PageSection Start -->
    <section class="page-section" style=" padding: 0; ">
        <!-- Container -->
        <div class="container">
            <!-- Row -->
            <div class="row">
			 	<!--Left Sidebar Starts-->
				<?php if ($cs_layout == 'content-right col-md-9'){ ?>
                    <div class="content-lt col-md-3"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar) ) : ?><?php endif; ?></div>
                <?php } ?>
                <!--Left Sidebar End-->
                
    			<!-- Page Detail Start -->
       			<div class="<?php echo esc_attr($cs_layout); ?>  cs_shop_wrap">
					<?php if( function_exists('woocommerce_content') ) { woocommerce_content(); }; ?>
           		</div>
        		<!-- Page Detail End -->
                
                <!-- Right Sidebar Start -->
				<?php if ( $cs_layout  == 'content-left col-md-9'){ ?>
                   <div class="content-rt col-md-3"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar) ) : ?><?php endif; ?></div>
                <?php } ?>
                <!-- Right Sidebar End -->
          </div>
        </div>
     </section>