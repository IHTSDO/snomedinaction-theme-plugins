<?php
/**
	Shop Products Lisitng
 * @package LMS


 */
/***************** Shop Page ******************/
if(is_shop()){
	$cs_shop_id = woocommerce_get_page_id( 'shop' );
	
	//<!-- Col Md 12 -->
	$content_post = get_post($cs_shop_id);
	$content = $content_post->post_content;
	if(trim($content)<>''){
	echo '<div class="col-md-12">';
					
					$content = apply_filters('the_content', $content);
					$content = str_replace(']]>', ']]&gt;', $content);
					echo balanceTags($content, true);
				echo '</div>';
	}
		//<!-- Col Md 12 -->
		echo '<div class="col-md-12 lightbox">';
				if ( have_posts() ) :
					echo "<div class='cs_shop_wrap'><ul class='products'>";
						  while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'shop/content', 'product' ); ?>

				<?php endwhile; // end of the loop. 
						
 					echo "</ul></div>";
					
					woocommerce_get_template( 'loop/pagination.php' );
										
				endif;
		echo '</div>';
}