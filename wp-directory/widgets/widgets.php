<?php

//======================================================================
// Function Directory Multiple Images Widget
//======================================================================
class WC_Meta_Box_Directory_Images {

	public static function output( $post ) {
		?>
		<div id="product_images_container">
			<ul class="product_images">
				<?php
					if ( metadata_exists( 'post', $post->ID, '_product_image_gallery' ) ) {
						$product_image_gallery = get_post_meta( $post->ID, '_product_image_gallery', true );
					} else {
						// Backwards compat
						$attachment_ids = get_posts( 'post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&meta_key=_woocommerce_exclude_image&meta_value=0' );
						$attachment_ids = array_diff( $attachment_ids, array( get_post_thumbnail_id() ) );
						$product_image_gallery = implode( ',', $attachment_ids );
					}
					$attachments = array_filter( explode( ',', $product_image_gallery ) );
					if ( $attachments )
						foreach ( $attachments as $attachment_id ) {
							echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
								' . wp_get_attachment_image( $attachment_id, 'thumbnail' ) . '
								<ul class="actions">
									<li><a href="#" class="delete tips" data-tip="' . __( 'Delete image', 'directory' ) . '">' . __( 'Delete', 'directory' ) . '</a></li>
								</ul>
							</li>';
						}
				?>
			</ul>
			<input type="hidden" id="product_image_gallery" name="product_image_gallery" value="<?php echo esc_attr( $product_image_gallery ); ?>" />
		</div>
		<p class="add_product_images hide-if-no-js">
			<a href="#" data-choose="<?php _e( 'Add Images to Product Gallery', 'directory' ); ?>" data-update="<?php _e( 'Add to gallery', 'directory' ); ?>" data-delete="<?php _e( 'Delete image', 'directory' ); ?>" data-text="<?php _e( 'Delete', 'directory' ); ?>">
			<?php _e( 'Add product gallery images', 'directory' ); ?></a>
		</p>
		<?php
	}
	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		$attachment_ids = array_filter( explode( ',', wc_clean( $_POST['product_image_gallery'] ) ) );
		update_post_meta( $post_id, '_product_image_gallery', implode( ',', $attachment_ids ) );
	}
}

/**
 * @Agents(s) list widget Class
 */
if ( ! class_exists( 'cs_agentlist' ) ) {
	class cs_agentlist extends WP_Widget {		
	
	/**
	 * Outputs the content of the widget
		 * @param array $args
	 * @param array $instance
	 */
		 
	/**
	 * @init User list Module
			 */
	 function cs_agentlist() {
		$widget_ops = array('classname' => 'widget_agents', 'description' => 'Select user to show in widget.');
		$this->WP_Widget('cs_agentlist', 'CS : Agents', $widget_ops);
	 }
	
	/**
	 * @User list html form
			 */
	 function form($instance) {
		$instance = wp_parse_args((array) $instance, array('title' => '', 'get_username' => 'new'));
		$title = $instance['title'];
		$showcount = isset( $instance['showcount'] ) ? absint( $instance['showcount'] ) : '';
		?>
        <p>
          <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"> Title:
            <input class="upcoming" id="<?php echo esc_attr($this->get_field_id('title')); ?>" size="40" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
          </label>
        </p>
        <p>
          <label for="<?php echo cs_allow_special_char($this->get_field_id('showcount')); ?>"> Number of Users To Display:
            <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('showcount')); ?>" size='2' name="<?php echo cs_allow_special_char($this->get_field_name('showcount')); ?>" type="text" value="<?php echo absint($showcount); ?>" />
          </label>
        </p>
<?php
 		}
		
		
		/**
		 * @User list update data
						 */
		 function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title']		= $new_instance['title'];
			$instance['showcount']	= $new_instance['showcount'];
  			return $instance;
		 }
		 
		/**
		 * @Display User list widget
						 */
		 function widget($args, $instance) {
			extract($args, EXTR_SKIP);
			global $wpdb, $post,$cs_theme_options;
			$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$title = htmlspecialchars_decode(stripslashes($title));
			$showcount = empty($instance['showcount']) ? ' ' : apply_filters('widget_title', $instance['showcount']);	
			if($instance['showcount'] == ""){$instance['showcount'] = '3';}
			// WIDGET display CODE Start
			echo balanceTags($before_widget,false);
			$cs_page_id = '';
			
 			if (strlen($title) <> 1 || strlen($title) <> 0) {
 				echo balanceTags($before_title . $title . $after_title,false);
 			}
			
			$wp_user_query = new WP_User_Query(array(
				'meta_query' => array(
									array(
										'key'     => 'user_profile_public',
										'value'   => '1',
										'compare' => '='
									)
								),
				'number' => $showcount, 
				'offset' => 0
			));
			
			$authors = $wp_user_query->get_results();
			if (!empty($authors)) {
				if ( is_array($authors) ) {
					$cs_dummy_image = 'dummy.jpg';
					$plugin_url		= plugins_url();
					$cs_dummy_image = $plugin_url.'/wp-directory/assets/images/dummy.jpg';
					echo '<ul>';
					foreach ($authors as $cs_user_data) {
						$user_info = get_userdata($cs_user_data->ID);
						if($user_info){
							$username	= $user_info->display_name;
							$user_email = $user_info->user_email;
							$role		= $user_info->roles;
						}
						
						$post_count	= cs_custom_count_posts_by_author($cs_user_data->ID,array('post'));
						$post_count	= ($post_count <>'')? $post_count : 0;
						$usermeta	= get_user_meta($cs_user_data->ID);
 						//$usermeta	= array_map( function( $a ){ return $a[0]; }, $usermeta );
						
						echo '<li>';
							if(isset($usermeta['user_avatar_display']) and $usermeta['user_avatar_display'] <> ''){
							 echo '<figure><img src="'.esc_url(cs_get_user_avatar(0 ,$cs_user_data->ID)).'" width="60" alt=""></figure>';
							}else{
								echo '<figure><img src="'.esc_url($cs_dummy_image).'" width="60" alt="" /></figure>';	
							}
						
						echo '<div class="infotext">
								<h6><a href="'.cs_user_profile_link('', 'detail', absint($cs_user_data->ID)).'">'.$username.'</a></h6>
								'.cs_dir_listing_count($cs_user_data->ID, false).'
							  </div>
						</li>';	
					}
					echo '</ul>';
				}
			}
			else {
				cs_fnc_no_result_found(false);
		 	}
				
 			echo balanceTags($after_widget,false); 
		}
	}
}
add_action('widgets_init', create_function('', 'return register_widget("cs_agentlist");'));