<?php

/***************** Shop Page ******************/
echo '<div class="container">
         	<div class="row">';
	if ( have_posts() ) :
		$post_xml = get_post_meta($post->ID, "product", true);	
		if ( $post_xml <> "" ) {
			$cs_xmlObject = new SimpleXMLElement($post_xml);
				$cs_layout 			= $cs_xmlObject->sidebar_layout->cs_page_layout;
				$cs_sidebar_left 	= $cs_xmlObject->sidebar_layout->cs_page_sidebar_left;
				$cs_sidebar_right   = $cs_xmlObject->sidebar_layout->cs_page_sidebar_right;
				if ( $cs_layout == "left") {
					$cs_layout = "content-right col-md-9";
				}
				else if ( $cs_layout == "right" ) {
					$cs_layout = "content-left col-md-9";
				} else {
					$cs_layout = "col-md-12";
				}
		} else {
			$cs_layout = "col-md-12";
			$cs_sidebar_left = '';
			$cs_sidebar_right = '';
		}
		if ($cs_layout == 'content-right col-md-9'){ ?>
			<aside class="sidebar-left col-md-3"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar_left) ) : ?><?php endif; ?></aside>
		<?php } ?>

            	<div class="<?php echo esc_attr($cs_layout); ?> cs_shop_wrap">
                	<?php if( function_exists('woocommerce_content') ) { woocommerce_content(); }; ?>
            	</div>
 		<?php if ( $cs_layout  == 'content-left col-md-9'){ ?>
			<aside class="sidebar-right col-md-3"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar_right) ) : ?><?php endif; ?></aside>
		<?php } ?>
 	<?php
	endif;
	echo '</div>
         	</div>';