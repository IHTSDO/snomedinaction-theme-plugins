<?php
function cs_woocommerce_enabled(){
	if ( class_exists( 'woocommerce' ) ){ return true; }
	return false;
}
//check if the plugin is enabled, otherwise stop the script
if(!cs_woocommerce_enabled()) { return false; }
//woocommerce support theme
add_theme_support( 'woocommerce' );

add_filter( 'woocommerce_enqueue_styles', '__return_false' );
add_action( 'wp_enqueue_scripts', 'child_manage_woocommerce_styles', 99 );
function child_manage_woocommerce_styles() {
    //remove generator meta tag
    remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
    //first check that woo exists to prevent fatal errors
    if ( function_exists( 'is_woocommerce' ) ) {
        //dequeue scripts and styles
        if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
            wp_dequeue_script( 'wc_price_slider' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-add-to-cart' );
            wp_dequeue_script( 'wc-cart-fragments' );
            wp_dequeue_script( 'wc-checkout' );
            wp_dequeue_script( 'wc-add-to-cart-variation' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-cart' );
            wp_dequeue_script( 'wc-chosen' );
            wp_dequeue_script( 'woocommerce' );
           	// wp_dequeue_script( 'prettyPhoto' );
            //wp_dequeue_script( 'prettyPhoto-init' );
            wp_dequeue_script( 'jquery-blockui' );
            wp_dequeue_script( 'jquery-placeholder' );
            //wp_dequeue_script( 'fancybox' );
            //wp_dequeue_script( 'jqueryui' );
        }
    }
 }
//remove woo defaults
function cs_shop_title() {
	$title = '';
	return $title;
}
add_filter('woocommerce_show_page_title', 'cs_shop_title');

remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
//remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
//remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
//remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
//remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
/**
* Define image sizes
*/
global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action( 'init', 'cs_woocommerce_image_dimensions', 1 );

function cs_woocommerce_image_dimensions() {
	$catalog = array(
	'width' => '300',	// px
	'height' => '300',	// px
	'crop' => 1 // true
	);
	$single = array(
	'width' => '300',	// px
	'height' => '300',	// px
	'crop' => 1 // true
	);
	$thumbnail = array(
	'width' => '300',	// px
	'height' => '300',	// px
	'crop' => 1 // false
	);
	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog ); // Product category thumbs
	update_option( 'shop_single_image_size', $single ); // Single product image
	update_option( 'shop_thumbnail_image_size', $thumbnail ); // Image gallery thumbs
}
//Shop loop items changings starts
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
//add_action( 'woocommerce_after_shop_loop_item', 'cs_loop_add_to_cart', 10 );
function cs_loop_add_to_cart(){
	global $product;

	echo apply_filters( 'woocommerce_loop_add_to_cart_link',
		sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button %s product_type_%s">%s</a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( $product->id ),
			esc_attr( $product->get_sku() ),
			esc_attr( isset( $quantity ) ? $quantity : 1 ),
			$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
			esc_attr( $product->product_type ),
			'<i class="icon-shopping-cart"></i> '.esc_html( $product->add_to_cart_text() )
		),
	$product );
}

//remove_action('woocommerce_single_product_summary','woocommerce_template_single_title',5);
//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
//add_action('woocommerce_single_product_summary', 'woocommerce_my_single_title',5);
//add_action( 'woocommerce_before_shop_loop', 'cs_before_shop_loop' );

add_action( 'woocommerce_before_shop_loop_item_title', 'cs_shop_loop_before_title', 10 );
function cs_shop_loop_before_title(){
	$title = '<div class="cs-product-info"><div class="title-box">';
	echo $title;
}

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
add_filter('woocommerce_sale_flash', 'cs_sale_flash_icon', 10, 3);
function cs_sale_flash_icon(){
	$icon = '<span class="featured-product"><i class="icon-star"></i></span>';
	echo $icon;
}

// removing shop default title
function cs_woocommerce_shop_title(){
	$cs_shop_title = '';
	return $cs_shop_title;
}
add_filter('woocommerce_show_page_title', 'cs_woocommerce_shop_title');

function cs_before_shop_loop()
{
	$cs_shop_id = woocommerce_get_page_id( 'shop' );
	$cs_shop_page_bulider = get_post_meta($cs_shop_id, "cs_page_builder", true);
	$cs_shopXmlObject = new SimpleXMLElement($cs_shop_page_bulider);
	if (isset($cs_shopXmlObject->sidebar_layout)){
		if($cs_shopXmlObject->sidebar_layout->cs_page_layout == '' || $cs_shopXmlObject->sidebar_layout->cs_page_layout == 'none'){
			echo 'col-md-4';
		}
		else{
			echo 'col-md-3';
		}
	}
}

//add_action( 'woocommerce_after_shop_loop', 'cs_after_shop_loop' );
function cs_after_shop_loop()
{
	$cs_shop_id = woocommerce_get_page_id( 'shop' );
	$cs_shop_page_bulider = get_post_meta($cs_shop_id, "cs_page_builder", true);
	$cs_shopXmlObject = new SimpleXMLElement($cs_shop_page_bulider);
	if (isset($cs_shopXmlObject->sidebar_layout)){
		if($cs_shopXmlObject->sidebar_layout->cs_page_layout == '' || $cs_shopXmlObject->sidebar_layout->cs_page_layout == 'none'){
			echo 'col-md-4';
		}
		else{
			echo 'col-md-3';
		}
	}
}

if ( ! function_exists( 'woocommerce_my_single_title' ) ) {
	function woocommerce_my_single_title() {
		?>
			<h5><?php the_title(); ?></h5>
		<?php
	}
}

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
add_action( 'woocommerce_after_shop_loop_item_title', 'cs_woocommerce_loop_rating', 5 );
function cs_woocommerce_loop_rating(){
	global $product;
	if ( $product->get_rating_html() != '' ){
		echo '<div class="cs-custom-rating">';
		woocommerce_get_template( 'loop/rating.php' );
		echo '</div>';
	}
	woocommerce_get_template( 'loop/price.php' );
	echo '</div>'; // Start div is in above function
	cs_loop_add_to_cart();
	echo '</div>'; // Start div is in above function
}

function shop_loop_item_hover_desc()
{
	global $post;
	$no_img = "";
	$img_sizes = get_option( 'shop_catalog_image_size' );
	$img_width = $img_sizes['width'];
	$img_height = $img_sizes['height'];
	if(wp_get_attachment_image( get_post_thumbnail_id() ) == ""){
		$no_img = 'class="no-image"';
	}
?>
	<figure <?php echo esc_attr($no_img); ?>>
		<?php
		woocommerce_get_template( 'loop/sale-flash.php' );
		echo wp_get_attachment_image( get_post_thumbnail_id(), array($img_width,$img_height) );
        ?>
	</figure>
    <div class="text">
    <div class="woo_title">
    <?php woocommerce_my_single_title(); ?>
 	<?php woocommerce_get_template( 'loop/rating.php' ); ?>
    </div>
    <div class="add_to_cart">
    
    <?php 
	woocommerce_get_template( 'loop/price.php' );
	woocommerce_get_template( 'loop/add-to-cart.php' );
	
	?>
     </div>
    </div>
<?php
}
  //Shop loop items changings ends

?>