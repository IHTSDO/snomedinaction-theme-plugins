<?php

get_header();
global $cs_node, $cs_shop_id,$cs_theme_options;
wp_reset_query();
$cs_shop_id = woocommerce_get_page_id( 'shop' );
	if ( !isset($_SESSION["px_page_back"]) ||  isset($_SESSION["px_page_back"])){
		$_SESSION["px_page_back"] = $cs_shop_id;
	}
	if(is_shop()){
		get_template_part( 'woocommerce/woocommerce-shop', 'page' );
	} else if(is_single()){
		get_template_part( 'woocommerce/woocommerce-single-product', 'page' );
	} else if(is_product_category() or is_product_tag()){
		
		/* Shop Taxonomies pages */
			get_template_part( 'woocommerce/woocommerce-archive', 'page' );
	} else{
		/* Shop Other Pages */
			if ( have_posts() ) :
			?>
				<div class="cs_shop_wrap">
					<?php if( function_exists('woocommerce_content') ) { woocommerce_content(); }; ?>
				</div>
			<?php 
			endif; 
	}
get_footer(); ?>
<!-- Columns End -->