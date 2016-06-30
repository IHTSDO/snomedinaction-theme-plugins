<?php
	add_action( 'admin_footer-edit-tags.php', 'cs_remove_fields' );

function cs_remove_fields(){
    global $current_screen;
    switch ( $current_screen->id ) 
    {
        case 'edit-category':
            // WE ARE AT /wp-admin/edit-tags.php?taxonomy=category
            // OR AT /wp-admin/edit-tags.php?action=edit&taxonomy=category&tag_ID=1&post_type=post
            break;
        case 'edit-post_tag':
            // WE ARE AT /wp-admin/edit-tags.php?taxonomy=post_tag
            // OR AT /wp-admin/edit-tags.php?action=edit&taxonomy=post_tag&tag_ID=3&post_type=post
            break;
    }
    ?>
    <script type="text/javascript">
    jQuery(document).ready( function($) {
        $('#tag-description').parent().remove();
		$('.tagcloud').remove();
    });
    </script>
    <?php
}
	//adding columns start
    add_filter('manage_directory_posts_columns', 'directory_columns_add');
	function directory_columns_add($columns) {
	//	$columns['category'] = 'Category';
		$columns['directory_type'] = 'Directory Type';
		$columns['featured'] = 'Featured';
		$columns['rating'] = 'Rating';
		$columns['organizer'] = 'Organizer';
		$columns['Status'] = 'Status';
		$columns['author'] = 'Author';
		return $columns;
	}
    add_action('manage_directory_posts_custom_column', 'directory_columns');
	function directory_columns($name) {
		global $post;
		$directory_type_select = get_post_meta($post->ID, "directory_type_select", true);
		$directory_featured = get_post_meta($post->ID, "directory_featured", true);
		$directory_feature_price = get_post_meta($post->ID, "directory_feature_price", true);
		$directory_feature_duration = get_post_meta($post->ID, "directory_feature_duration", true);
		$directory_featured = ($directory_featured == 'Yes')? 'Featured' : 'Normal';
		$directory_organizer = get_post_meta($post->ID, "directory_organizer", true);
		$cs_directory_review_rating = get_post_meta($post->ID, "cs_directory_review_rating", true);
		switch ($name) {
			/*case 'category':
				$categories = get_the_terms( $post->ID, 'directory-category' );
					if($categories <> ""){
						$couter_comma = 0;
						foreach ( $categories as $category ) {
							echo esc_attr($category->name);
							$couter_comma++;
							if ( $couter_comma < count($categories) ) {
								echo ", ";
							}
						}
					}
				break;*/
			case 'directory_type':
				echo get_the_title($directory_type_select);
				break;
			case 'featured':
				echo esc_attr($directory_featured);
				break;
			case 'rating':
				echo esc_attr($cs_directory_review_rating);
				break;
			case 'organizer':
				echo get_the_author_meta('user_login', (int)$directory_organizer);
				break;
			case 'Status':
				echo get_post_status($post->ID);
				break;
			case 'author':
				echo get_the_author();
				break;
		}
	}
	
	
	if(!class_exists('post_type_directory')){
		/**
		 * Directory Post Type Class
		*/
		class post_type_directory
		{
			/**
			 * The Constructor
			*/
			public function __construct()
			{
				// register actions
				add_action('init', array(&$this, 'cs_directory_init'));
				
				add_action('admin_init', array(&$this, 'cs_directory_admin_init'));
				if ( isset($_POST['directory_meta_form']) and $_POST['directory_meta_form'] == 1 ) {
					add_action( 'save_post', 'post_type_directory::save', 20, 2 );
					add_action( 'save_post', array(&$this, 'cs_meta_directory_save') );  
				}
			} 
			
			/**
			 * hook into WP's init action hook
			*/
			public function cs_directory_init()
			{
				// Initialize Post Type
				$this->cs_directory_register();
				$this->cs_directory_register_categories();
				$this->cs_directory_register_tags();

			}
			
			function cs_clean( $var ) {
				return sanitize_text_field( $var );
			}
			
			/**
			 * Create the Directory post type
			 */
			public function cs_directory_register()
			{
				$cs_theme_options = get_option('cs_theme_options',true);
				if(isset($cs_theme_options['cs_directory_menu_title']) && !empty($cs_theme_options['cs_directory_menu_title'])){
					$cs_directory_menu_title = trim($cs_theme_options['cs_directory_menu_title']);
				} else {
					$cs_directory_menu_title = 'Directory Ads';
				}
				
				if(isset($cs_theme_options['cs_directory_menu_slug']) && !empty($cs_theme_options['cs_directory_menu_slug'])){
					$cs_directory_menu_slug = trim($cs_theme_options['cs_directory_menu_slug']);
				} else {
					$cs_directory_menu_slug = 'directory';
				}
				
				
				register_post_type( 'directory',	array(
									'labels'             => array(
									'name' 				 => __($cs_directory_menu_title,'directory'),
									'all_items'			 => __($cs_directory_menu_title,'directory'),
									'singular_name'      => __( $cs_directory_menu_title, 'directory' ),
									'add_new'            => __( 'Add New', 'directory' ),
									'add_new_item'       => __( 'Add New'.$cs_directory_menu_title, 'directory' ),
									'edit'               => __( 'Edit', 'directory' ),
									'edit_item'          => __( 'Edit'.$cs_directory_menu_title, 'directory' ),
									'new_item'           => __( 'New'.$cs_directory_menu_title, 'directory' ),
									'view'               => __( 'View'.$cs_directory_menu_title, 'directory' ),
									'view_item'          => __( 'View'.$cs_directory_menu_title, 'directory' ),
									'search_items'       => __( 'Search'.$cs_directory_menu_title, 'directory' ),
									'not_found'          => __( 'Not '.$cs_directory_menu_title.'found','directory' ),
									'not_found_in_trash' => __( 'Not '.$cs_directory_menu_title.'found in trash', 'directory' ),
									'parent'             => __( 'Parent'.$cs_directory_menu_title, 'directory' )
								),
							'description'         => __( 'This is where you can add new'.$cs_directory_menu_title, 'directory' ),
							'public'              => true,
							'supports'            => array( 'title', 'editor'),
							'show_ui'             => true,
							'capability_type'     => 'post',
							'map_meta_cap'        => true,
							'publicly_queryable'  => true,
							'exclude_from_search' => false,
							'hierarchical'        => false, 
							'rewrite'			  => array('slug' => $cs_directory_menu_slug, 'with_front' => true),
							'query_var'           => true,
							'has_archive'         => 'false',
						)
					);
			}
			/**
			 * Directory Categories
			 */
			public function cs_directory_register_categories(){
				
				$cs_theme_options = get_option('cs_theme_options',true);
				if(isset($cs_theme_options['cs_directory_menu_title']) && !empty($cs_theme_options['cs_directory_menu_title'])){
					$cs_directory_menu_title = trim($cs_theme_options['cs_directory_menu_title']);
				} else {
					$cs_directory_menu_title = 'Directory Ads';
				}
				if(isset($cs_theme_options['cs_directory_menu_slug']) && !empty($cs_theme_options['cs_directory_menu_slug'])){
					$cs_directory_menu_slug = trim($cs_theme_options['cs_directory_menu_slug']);
				} else {
					$cs_directory_menu_slug = 'directory';
				}
				  $labels = array(
					'name' => 'Directory Categories',
					'search_items' => 'Search Directory Categories',
					'edit_item' => 'Edit Directory Category',
					'update_item' => 'Update Directory Category',
					'add_new_item' => 'Add New Category',
					'menu_name' => 'Categories',
				  ); 	
				  register_taxonomy('directory-category',array('directory'), array(
					'hierarchical' => true,
					'labels' => $labels,
					'show_ui' => true,
					'show_admin_column' => true,
					'public' => false,
					'query_var' => true,
					'rewrite' => array( 'slug' => $cs_directory_menu_slug.'-category', 'with_front' => true ),
				  ));
			}

			/**
			 * Directory Tags
			 */
			public function cs_directory_register_tags(){
				// adding tag start
				$cs_theme_options = get_option('cs_theme_options',true);
				if(isset($cs_theme_options['cs_directory_menu_title']) && !empty($cs_theme_options['cs_directory_menu_title'])){
					$cs_directory_menu_title = trim($cs_theme_options['cs_directory_menu_title']);
				} else {
					$cs_directory_menu_title = 'Directory Ads';
				}
				if(isset($cs_theme_options['cs_directory_menu_slug']) && !empty($cs_theme_options['cs_directory_menu_slug'])){
					$cs_directory_menu_slug = trim($cs_theme_options['cs_directory_menu_slug']);
				} else {
					$cs_directory_menu_slug = 'directory';
				}
				  $labels = array(
					'name' => 'Directory Tags',
					'singular_name' => 'directory-tag',
					'search_items' => 'Search Tags',
					'popular_items' => 'Popular Tags',
					'all_items' => 'All Tags',
					'parent_item' => null,
					'parent_item_colon' => null,
					'edit_item' => 'Edit Tag', 
					'update_item' => 'Update Tag',
					'add_new_item' => 'Add New Tag',
					'new_item_name' => 'New Tag Name',
					'separate_items_with_commas' => 'Separate tags with commas',
					'add_or_remove_items' => 'Add or remove tags',
					'choose_from_most_used' => 'Choose from the most used tags',
					'menu_name' => 'Tags',
				  ); 
				  register_taxonomy('directory-tag','directory',array(
					'hierarchical' => false,
					'labels' => $labels,
					'show_ui' => true,
					
					'update_count_callback' => '_update_post_term_count',
					'query_var' => true,
					'rewrite' => array( 'slug' => $cs_directory_menu_slug.'-tag' ),
				  ));
				// adding tag end
			}
			/**
			 * Hide Directory Add new link
			 */
			public function cs_addnew_directory_role() {
				
				$role = get_role( 'organizer' );
				if(!isset($role)){
					$role = add_role( 'organizer', 'Organizer', array(
						'read' => true, // True allows that capability
						'write' => true, // True allows that capability
						'edit_posts'   => true,
						'delete_posts' => false, // Use false to explicitly deny
					) );
				}
			}
			
			/**
			 * hook into WP's admin_init action hook
			 */
			public function cs_directory_admin_init()
			{           
				
				//add_action('wp_ajax_add_directory_donation_to_list', array(&$this, 'add_directory_donation_to_list'));
				// Add metaboxes
				add_action('add_meta_boxes', array(&$this, 'cs_meta_directory_add'));
			}
			/**
			 * hook into WP's add_meta_boxes action hook
			*/
			public function cs_meta_directory_add()
			{  
				$cs_theme_options = get_option('cs_theme_options',true);
				if(isset($cs_theme_options['cs_directory_menu_title']) && !empty($cs_theme_options['cs_directory_menu_title'])){
					$cs_directory_menu_title = trim($cs_theme_options['cs_directory_menu_title']);
				} else {
					$cs_directory_menu_title = 'Directory Ads';
				}
				add_meta_box( 'cs_meta_directory', 'Directory Options', array(&$this, 'cs_meta_directory'), 'directory', 'normal', 'high' );  
				add_meta_box( 'directory-gallery-images', __( $cs_directory_menu_title.'Gallery', 'directory' ), 'post_type_directory::output', 'directory', 'side' );
				add_meta_box( 'directory-attachments', __( 'Attachments', 'directory' ), 'post_type_directory::attachments', 'directory', 'side' );

			}
			
			/**
			 * Output the metabox
			 */
			public static function output( $post ) {
				global $post;
				$cs_theme_options = get_option('cs_theme_options',true);
				if(isset($cs_theme_options['cs_directory_menu_title']) && !empty($cs_theme_options['cs_directory_menu_title'])){
					$cs_directory_menu_title = trim($cs_theme_options['cs_directory_menu_title']);
				} else {
					$cs_directory_menu_title = 'Directory Ads';
				}
				?>
				<div id="directory_images_container">
					<ul class="directory_images">
						<?php
							if ( metadata_exists( 'post', $post->ID, '_directory_image_gallery' ) ) {
								$directory_image_gallery = get_post_meta( $post->ID, '_directory_image_gallery', true );
							
							} else {
								// Backwards compat
								$attachment_ids = get_posts( 'post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&&meta_value=0' );
								$attachment_ids = array_diff( $attachment_ids, array( get_post_thumbnail_id() ) );
								$directory_image_gallery = implode( ',', $attachment_ids );
							}
							$attachments = array_filter( explode( ',', $directory_image_gallery ) );
							if ( $attachments )
								foreach ( $attachments as $attachment_id ) {
									echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
										' . wp_get_attachment_image( $attachment_id, 'cs_media_6' ) . '
										<ul class="actions">
											<li><a href="#" class="delete tips" data-tip="' . __( 'Delete image', 'directory' ) . '">' . __( 'Delete', 'directory' ) . '</a></li>
										</ul>
									</li>';
								}
						?>
					</ul>
					<input type="hidden" id="directory_image_gallery" name="directory_image_gallery" value="<?php echo esc_attr( $directory_image_gallery ); ?>" />
				</div>
				<p class="add_directory_images hide-if-no-js">
					<a href="#" data-choose="<?php _e( 'Add Images to '.$cs_directory_menu_title.'Gallery', 'directory' ); ?>" data-update="<?php _e( 'Add to gallery', 'directory' ); ?>" data-delete="<?php _e( 'Delete image', 'directory' ); ?>" data-text="<?php _e( 'Delete', 'directory' ); ?>"><?php _e( 'Add '.$cs_directory_menu_title.'gallery images', 'directory' ); ?></a>
				</p>
				<?php
			}
			
			/**
			 * Output the metabox
			 */
			public static function attachments( $post ) {
				global $post;
				$cs_theme_options = get_option('cs_theme_options',true);
				
				if(isset($cs_theme_options['cs_directory_menu_title']) && !empty($cs_theme_options['cs_directory_menu_title'])){
					$cs_directory_menu_title = trim($cs_theme_options['cs_directory_menu_title']);
				} else {
					$cs_directory_menu_title = 'Directory Ads';
				}
				
				?>
				<div id="directory_attachment_container">
					<ul class="cs_attachments_list">
						<?php
							if ( metadata_exists( 'post', $post->ID, '_directory_file_attachment' ) ) {
								$directory_attachments = get_post_meta( $post->ID, '_directory_file_attachment', true );
							
							} else {
								// Backwards compat
								$attachment_ids = get_posts( 'post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&&meta_value=0' );
								$attachment_ids = array_diff( $attachment_ids, array( get_post_thumbnail_id() ) );
								$directory_attachments = implode( ',', $attachment_ids );
							}
							$attachments = array_filter( explode( ',', $directory_attachments ) );
							if ( $attachments )
								foreach ( $attachments as $attachment_id ) {
									echo '<li class="attachment" data-attachment_id="' . esc_attr( $attachment_id ) . '">
										 <img src="'.wp_directory::plugin_url().'/assets/images/attachment.png" alt="" />
										<ul class="actions">
											<li><a href="#" class="delete tips" data-tip="' . __( 'Delete Attachment', 'directory' ) . '">' . __( 'Delete', 'directory' ) . '</a></li>
										</ul>
									</li>';
								}
						?>
					</ul>
					<input type="hidden" id="directory_attachments" name="directory_attachments" value="<?php echo esc_attr( $directory_attachments ); ?>" />
				</div>
                <input type="hidden" id="directory_icon_url" name="directory_icon_url" value="<?php echo wp_directory::plugin_url().'/assets/images/attachment.png';?>" />
				<p class="add_directory_attachmnets hide-if-no-js">
					<a href="#" data-choose="<?php _e( 'Add Attachments', 'directory' ); ?>" data-update="<?php _e( 'Add to List', 'directory' ); ?>" data-delete="<?php _e( 'Delete Attachment', 'directory' ); ?>" data-text="<?php _e( 'Delete', 'directory' ); ?>"><?php _e( 'Add Attachments', 'directory' ); ?></a>
				</p>
				<?php
			}
			/**
			 * Save meta box data
			 */
			public static function save( $post_id, $post ) {
				$attachment_ids = array_filter( explode( ',', sanitize_text_field( $_POST['directory_image_gallery'] ) ) );
				update_post_meta( $post_id, '_directory_image_gallery', implode( ',', $attachment_ids ) );
				
				// Attachments
				$attachment_file_ids = array_filter( explode( ',', sanitize_text_field( $_POST['directory_attachments'] ) ) );
				update_post_meta( $post_id, '_directory_file_attachment', implode( ',', $attachment_file_ids ) );
			}
			/**
			 * Directory meta Fields
			*/
			public function cs_meta_directory( $post )
			{
				global $post, $cs_xmlObject;
				$cs_theme_options=get_option('cs_theme_options');
				$cs_header_position =$cs_theme_options['cs_header_position'];
				$directory_post_id = $post->ID;
				$cs_builtin_seo_fields =$cs_theme_options['cs_builtin_seo_fields'];
				$cs_directory = get_post_meta($post->ID, "cs_directory_meta", true);
				if ( $cs_directory <> "" ) {
					$cs_xmlObject = new SimpleXMLElement($cs_directory);
				}
				?>		
                <div class="page-wrap page-opts left" style="overflow:hidden; position:relative; height: 1432px;">
                    <div class="option-sec" style="margin-bottom:0;">
                        <div class="opt-conts">
                            <div class="elementhidden">
                                <div class="tabs vertical">
                                    <div id="tab-directory-options" class="tab-pane fade active in">
                                       <?php  $this->cs_directory_general_settings($post->ID);?>
                                    </div>
                                </div>
                            </div>
                      </div>
                     <input type="hidden" name="directory_meta_form" value="1" />
                    </div>
                 </div>
                <div class="clear"></div>
			<?php 
            }
		
		   /**
			* Directory general Settings
		   */
			public function cs_directory_general_settings($directory_post_id=''){
				global $post, $cs_xmlObject,$cs_theme_options;
					if(isset($cs_xmlObject->directory_button_title)){ $directory_button_title = $cs_xmlObject->directory_button_title;} else {$directory_button_title = '';}
					if(isset($cs_xmlObject->directory_button_url)){ $directory_button_url = $cs_xmlObject->directory_button_url;} else {$directory_button_url = '';}
					if(isset($cs_xmlObject->directory_favourite)){ $directory_favourite = $cs_xmlObject->directory_favourite;} else {$directory_favourite = '';}
					if(isset($cs_xmlObject->directory_organizer)){ $directory_organizer = $cs_xmlObject->directory_organizer;} else {$directory_organizer = '';}
					if(isset($cs_xmlObject->directory_featured)){ $directory_featured = $cs_xmlObject->directory_featured;} else {$directory_featured = '';}
					if(isset($cs_xmlObject->directory_feature_price)){ $directory_feature_price = $cs_xmlObject->directory_feature_price;} else {$directory_feature_price = '';}
					if(isset($cs_xmlObject->directory_feature_duration)){ $directory_feature_duration = $cs_xmlObject->directory_feature_duration;} else {$directory_feature_duration = '';}
					
 					$directory_reviews = get_post_meta( $post->ID, 'directory_reviews', true);
					$directory_featured = get_post_meta( $post->ID, 'directory_featured', true);
					$directory_feature_price = get_post_meta( $post->ID, 'directory_feature_price', true);
					$directory_feature_duration = get_post_meta( $post->ID, 'directory_feature_duration', true);
					$directory_organizer = get_post_meta( $post->ID, 'directory_organizer', true);
					$cs_directory_pkg_names = get_post_meta( $post->ID, 'cs_directory_pkg_names', true);
					$cs_video_url = get_post_meta( $post->ID, 'cs_video_url', true);
					$dir_payment_date = get_post_meta( $post->ID, 'dir_payment_date', true);
					$dir_pkg_expire_date = get_post_meta( $post->ID, 'dir_pkg_expire_date', true);
					$dir_objective = get_post_meta( $post->ID, 'dir_objective', true);
					$dir_scope = get_post_meta( $post->ID, 'dir_scope', true);
					$dir_how = get_post_meta( $post->ID, 'dir_how', true);
					$dir_why = get_post_meta( $post->ID, 'dir_why', true);
					$directory_type_select = get_post_meta( $post->ID, 'directory_type_select', true);
					$_pakage_transaction_meta = get_post_meta($post->ID, "dir_pakage_transaction_meta", true);
					
					
					$cs_user_landline	= get_post_meta( $post->ID, 'user_landline', true );
					$cs_user_mobile		= get_post_meta( $post->ID, 'user_mobile', true );
					$cs_user_email		= get_post_meta( $post->ID, 'user_email', true );
					$cs_user_skype		= get_post_meta( $post->ID, 'user_skype', true );
					$cs_user_url		= get_post_meta( $post->ID, 'user_website', true );
		
					$directory_categories_array = get_the_terms( $post->ID, 'directory-category' );
					$directory_categories = array();
					if(isset($directory_categories_array) && is_array($directory_categories_array) ){
						foreach($directory_categories_array as $categoryy){
							$directory_categories[] = $categoryy->term_id;
						}
					}
					
					if( $directory_type_select == '' ){
						 $directory_type_select = isset($cs_theme_options['cs_default_ad_type']) ? $cs_theme_options['cs_default_ad_type'] : '';
 					}
					
					if ( function_exists( 'cs_enqueue_timepicker_script' ) ) {
						cs_enqueue_timepicker_script();
					}
					
					$dir_featured_till 		= get_post_meta( $post->ID, "dir_featured_till", true ); 
					
					$cs_feature_options 	= get_post_meta((int)$directory_type_select, 'cs_feature_meta', true);
					$featureList		 	= get_post_meta((int)$post->ID, 'cs_feature_list', true);
					if ( isset( $featureList ) && !empty( $featureList ) ) {
						$featureList	= explode( ',', $featureList );
					} else {
						$featureList	= array();
					}
					?>
                	<script type="text/javascript">
						jQuery(function(){
							jQuery('#directory_end_date').datetimepicker({
								format:'Y/m/d',
								timepicker:false
							});
						});
					</script>
                                        <span class="to-label" style="display: block; padding: 10px; font-size: 16px;"><label for="dir_objective">Objective</label></span>
                            <?php wp_editor(isset($dir_objective) ? esc_attr($dir_objective) : '', 'dir_objective', ['textarea_name' => 'dir_cusotm_field[dir_objective]', 'media_buttons' => false]); ?>
                                        
                                        <span class="to-label" style="display: block; padding: 10px; font-size: 16px;"><label for="dir_scope">Scope</label></span>
                            <?php wp_editor(isset($dir_scope) ? esc_attr($dir_scope) : '', 'dir_scope', ['textarea_name' => 'dir_cusotm_field[dir_scope]', 'media_buttons' => false]); ?>
                                        
                                        <span class="to-label" style="display: block; padding: 10px; font-size: 16px;"><label for="dir_how">How SNOMED CT will be used</label></span>
                            <?php wp_editor(isset($dir_how) ? esc_attr($dir_how) : '', 'dir_how', ['textarea_name' => 'dir_cusotm_field[dir_how]', 'media_buttons' => false]); ?>
                                        
                                        <span class="to-label" style="display: block; padding: 10px; font-size: 16px;"><label for="dir_why">Why SNOMED CT was selected</label></span>
                            <?php wp_editor(isset($dir_why) ? esc_attr($dir_why) : '', 'dir_why', ['textarea_name' => 'dir_cusotm_field[dir_why]', 'media_buttons' => false]); ?>
                    <ul class="form-elements">
                            <li class="to-label"><label><?php _e('Select Organizer','directory');?></label></li>
                            <li class="to-field select-style">
                            <?php
                                $blogusers = get_users('orderby=nicename');
                                echo '<select name="dir[directory_organizer]" id="directory_organizer">
                                        <option value="">None</option>';
                                          foreach ($blogusers as $user) {
											if($user->ID=="$directory_organizer"){
												$selected =' selected="selected"';
											}else{ 
												$selected = '';
											}
                                        	echo '<option value="'.$user->ID.'" '.$selected.'>'.$user->display_name.'</option>';
                                }
                                echo '</select>';
                            ?>
                            <div class="left-info">
                            	<p><?php _e('Name of Organizer of Listings','directory');?></p>
                            </div>
                            </li>
                         </ul>
                                        
                         <ul class="form-elements">
                              <li class="to-label">
                                <label><?php _e('Packages','directory')?></label>
                              </li>
                              <li class="to-field select-style">
                                    <select id="cs_directory_pkg_names" class="multiselect" name="dir_cusotm_field[cs_directory_pkg_names]" onchange="cs_directory_package(this.value, '<?php echo esc_js(admin_url('admin-ajax.php'));?>')">
                                        
                                        <?php 
											$cs_free_selected = '';
											if(isset($cs_directory_pkg_names) && $cs_directory_pkg_names == '0000000000' ){
												$cs_free_selected = 'selected="selected"';
											}
										    
											$cs_free_package_switch  = get_option('cs_free_package_switch');
											if( isset( $cs_free_package_switch ) && $cs_free_package_switch == 'on' ) {?>
                                         		<option <?php echo esc_attr( $cs_free_selected );?> value="0000000000" ><?php echo esc_attr( 'UNLIMITED' );?> - <?php echo esc_attr( 'Free' );?></option>
                                            <?php }?>
                                         
										<?php
                                        $cs_packages_options = get_option('cs_packages_options');
                                        if(isset($cs_packages_options) && is_array($cs_packages_options) && count($cs_packages_options)>0){
                                            foreach($cs_packages_options as $package_key=>$package){
                                                if(isset($package_key) && $package_key <> ''){
                                                    $package_id = $package['package_id'];
                                                    $package_title = $package['package_title'];
                                                    if($package_id <> '' && $package_title <> ''){
                                                        $selected = '';
                                                        if(isset($cs_directory_pkg_names) && $package_id==$cs_directory_pkg_names){
                                                            $selected = 'selected="selected"';
                                                        }
                                                        echo '<option value="'.$package_id.'" '.$selected.'>' . $package_title . '</option>';
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                    <div class="package-loading">
                                    	<?php
											if(isset($cs_directory_pkg_names) && $cs_directory_pkg_names <> ''){
												$custom_fields = '';
												$cs_packages_options = get_option('cs_packages_options');
												
												if( isset( $cs_directory_pkg_names ) && $cs_directory_pkg_names == '0000000000' ) {
													$custom_fields .= '<ul class="dr_userinfo">';
														$custom_fields .= '<li><label>Free</label></li>';
														$custom_fields .= '<li><label>'.__('Price','directory').': 0</label></li>';
														$custom_fields .= '<li><label>'.__('Duration','directory').': UNLIMITED</label></li>';
													$custom_fields .= '</ul>';
												} else{
													if(isset($cs_packages_options) && is_array($cs_packages_options) && count($cs_packages_options)>0){
														$dir_pkg = $cs_packages_options[$cs_directory_pkg_names];
														if ( isset($dir_pkg) && is_array($dir_pkg) && count($dir_pkg)>0 ) {
															$custom_fields .= '<ul class="dr_userinfo">';
															if(isset($dir_pkg['package_title']))
																$custom_fields .= '<li><label>'.$dir_pkg['package_title'].'</label></li>';
															if(isset($dir_pkg['package_price']))
																$custom_fields .= '<li><label>'.__('Price','directory').':'.$dir_pkg['package_price'].'</label></li>';
															if(isset($dir_pkg['package_duration']))
																$custom_fields .= '<li><label>'.__('Duration','directory').':'.$dir_pkg['package_duration'].' '.__('no of days','directory').'</label></li>';
															$custom_fields .= '</ul>';
														}
													}
												}
												echo balanceTags($custom_fields, true);
											}
										?>
                                        
                                    </div>
                                    <div class="left-info">
                                    	<p><?php _e('Select Package here','directory');?></p>
                                    </div>
                              </li>
                              </ul>
                              <?php
								if ( isset($_pakage_transaction_meta) && is_array($_pakage_transaction_meta) && count($_pakage_transaction_meta)>0) {
									$currency_sign = $cs_theme_options['currency_sign'];
								?>
                                   <ul class="form-elements">
                                      <li class="to-field ">
                                        <div class="inner-sec">
                                              <div class="toggle-sec">
                                                <div class="toggle-div<?php echo absint($post->ID);?>">
                                                    <div class="cs-section-title">
                                                        <h2><?php _e('Ad Transaction','directory');?></h2>
                                                    </div>
                                                    <div class="directory-donation">
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th class="odd">#</th>
                                                                <th class="even"><?php _e('Name','directory');?></th>
                                                                <th class="odd"><?php _e('Date','directory');?></th>
                                                                <th class="odd"><?php _e('Email','directory');?></th>
                                                                <th class="even"><?php _e('Transaction id','directory');?></th>
                                                                <th class="odd"><?php _e('Amount','directory');?></th>
                                                                <th class="odd"><?php _e('IPN Track id','directory');?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $counter = 0;
                                                            foreach ($_pakage_transaction_meta as $transct ){
                                                                $counter++;
                                                                $address_name 	= $transct['address_name'];
                                                                $payment_date 	= $transct['payment_date'];
                                                                $txn_id 		= $transct['txn_id'];
                                                                $payer_email 	= $transct['payer_email'];
                                                                $payment_gross  = $transct['payment_gross'];
																$class = ($counter and $counter%2 == 0) ? 'even' : 'odd';
                                                               ?>
                                                                <tr class="<?php echo sanitize_html_class($class);?>">
                                                                    <td><?php echo absint($counter);?></td>
                                                                    <td><?php echo esc_attr($address_name);?></td>
                                                                    <td><?php echo esc_attr($payment_date);?></td>
                                                                    <td><?php echo esc_attr($payer_email);?></td>
                                                                    <td><?php echo esc_attr($txn_id);?></td>
                                                                    <td><?php echo esc_attr($currency_sign.$payment_gross);?></td>
                                                                    <td><?php if(isset($transct['ipn_track_id']))echo esc_attr($transct['ipn_track_id']);?></td>
                                                                </tr>
                                                             <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                    </div>
                                                </div>
                                              </div>
                                        </div>
                                    </li>
                                </ul>
								<?php
								}
								?>
                               <script type="text/javascript">
									jQuery(function(){
										jQuery('#dir_pkg_expire_date').datetimepicker({
											format:'Y-m-d H:i:s',
											timepicker:false
										});
										jQuery('#dir_payment_date').datetimepicker({
											format:'Y-m-d H:i:s',
											timepicker:false
										});
										jQuery('#dir_featured_till').datetimepicker({
											format:'Y-m-d H:i:s',
											timepicker:false
										});
									});
								</script>
                                                                
                                                                
                              <ul class="form-elements">
                                   <li class="to-label">
                                    <label><?php _e('Package Expiry Date','directory')?></label>
                                  </li>
                                  <li class="to-field ">
                                    <div class="inner-sec">
                                        <input type="text" id="dir_pkg_expire_date" name="dir_cusotm_field[dir_pkg_expire_date]" value="<?php if(isset($dir_pkg_expire_date)) echo esc_attr($dir_pkg_expire_date);?>" class="text-input dir_pkg_date" placeholder="0000-00-00 00:00:00"  >
                                        
                                    </div>
                                </li>
                            </ul>
                            <ul class="form-elements">
                                   <li class="to-label">
                                    <label><?php _e('Package Payment Date','directory')?></label>
                                  </li>
                                  <li class="to-field ">
                                    <div class="inner-sec">
                                        <input type="text" id="dir_payment_date" name="dir_payment_date" value="<?php if(isset($dir_payment_date)) echo esc_attr($dir_payment_date);?>" class="text-input dir_pkg_date" placeholder="0000-00-00 00:00:00"  >
                                    </div>
                                </li>
                            </ul>
                            <ul class="form-elements">
                                   <li class="to-label">
                                    <label><?php _e('Urgent till','directory')?></label>
                                  </li>
                                  <?php if( isset( $dir_featured_till ) && $dir_featured_till !='' ) {
										$dir_featured_till = date('Y-m-d H:i:s',strtotime( $dir_featured_till ));
									} else {
										 $dir_featured_till = date('Y-m-d H:i:s');
									};?>
                                  <li class="to-field ">
                                     <div class="inner-sec">
                                        <input type="text" id="dir_featured_till" name="dir_cusotm_field[dir_featured_till]" value="<?php if(isset($dir_featured_till)) echo esc_attr($dir_featured_till);?>" class="text-input dir_pkg_date" placeholder="0000-00-00 00:00:00" >
                                    </div>
                                    <div class="left-info">
                                    	<p><?php _e('After this date, Package will go to normal','directory');?></p>
                                    </div>
                                </li>
                            </ul>
                    	<?php
						
						//cs_enqueue_location_gmap_script();
						wp_directory::cs_autocomplete_scripts();
						wp_directory::cs_google_place_scripts();
						
						$args = array(
							'posts_per_page'			=> "-1",
							'post_type'					=> 'directory_types',
							'post_status'				=> 'publish',
							'orderby'					=> 'ID',
							'order'						=> 'ASC',
						);
						
						$query_data = get_posts( $args );
						
						if ( is_array( $query_data ) && !empty( $query_data ) ) {
						?>
                        <ul class="form-elements">
                            <li class="to-label"><label><?php _e('Select Directory Type','directory');?></label></li>
                            <li class="to-field select-style">
                            <select name="dir_cusotm_field[directory_type_select]" id="directory_type_select" onchange="cs_directory_type_fields(this.value, '<?php echo esc_js($post->ID);?>', '<?php echo esc_js(admin_url('admin-ajax.php'));?>', 'admin')">
                                <?php
                                     echo '<option value="">None</option>';
                                     foreach ( $query_data as $postdata ) :
                                     $selected = '';
                                     if( isset( $directory_type_select ) && $directory_type_select == $postdata->ID ){
                                        $selected = 'selected'; 
                                     }
                                      echo '<option value="'.$postdata->ID.'" '.$selected.'>'.$postdata->post_title.'</option>';
                                    endforeach;
                                   wp_reset_postdata();
                                ?>
                            </select>
                            </li>
                         </ul>
                         <?php
                        }
						
						echo '<div id="directory_type_fields"><div class="loading-fields"></div>';
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
							?>
								<ul class="form-elements">
                                	 <li class="to-label"> <label for="categories"><?php _e('Categories','directory')?></label></li>
									
										<?php
										$directory_categories_array = get_post_meta($post_id, "directory_types_categories", true);
										$directory_categories_array = explode(',', $directory_categories_array);
										if(!isset($directory_categories) || !is_array($directory_categories) || !count($directory_categories)>0){
											$directory_categories = array();
										}
										
										$args = array(
												'type'                     => 'post',
												'child_of'                 => 0,
												'parent'                   => '',
												'orderby'                  => 'name',
												'order'                    => 'ASC',
												'hide_empty'               => 0,
												'hierarchical'             => 1,
												'exclude'                  => '',
												'include'                  => '',
												'number'                   => '',
												'taxonomy'                 => 'directory-category',
												'pad_counts'               => false 
											
											); 
										$categories = get_categories($args); 
										$multiple = '';
										$categ_name = '';
										$cs_class = 'select-style';
										if(isset( $cs_post_multi_cat_option ) && $cs_post_multi_cat_option == 'on'){
											$multiple = 'multiple="multiple"';
											$cs_class  = 'categories';
										}
										?>
                                        <li class="to-field <?php echo $cs_class; ?>">
										<select id="categories" class="multiselect" <?php echo isset( $multiple ) ? $multiple : '';?> name="directory_categories[]">
											<?php
											foreach ($categories as $category) {
												$selected = '';
												
												if(in_array($category->slug, $directory_categories_array)){
													if(in_array($category->term_id, $directory_categories)){
														$selected = 'selected="selected"';
													}
													echo '<option value="'.$category->term_id.'" '.$selected.'>' . $category->name . '</option>';
												}
											}
										 ?>
										</select>
                                        <div class="left-info">
                                        	<p><?php _e('Select Category of Directory Type','directory');?></p>
                                        </div>
                                        </li>
									</li>
								</ul>	
								<?php if ( isset( $post_video_switch ) && $post_video_switch == 'on' ) {?>
                                <div class="theme-help">
                                    <h4><?php echo __('Add Video','directory'); ?></h4>
                                    <div class="clear"></div>
                                </div>
                                <ul class="form-elements">
                                    <li class="to-label"><label><?php echo __('Video Url','directory'); ?></label></li>
                                    <li class="to-field">
                                        <div class="input-sec">
                                             <input type="text" placeholder="URL" class="text-input" value="<?php echo esc_url($cs_video_url);?>" name="cs_video_url">
                                        </div>
                                        <div class="left-info">
                                            <p><?php echo __('You can add Youtube, Vimeo, Dailymotion Videos Url etc..','directory'); ?></p>
                                        </div>
                                    </li>
                                </ul>
                                <?php }?>
								<?php  if(isset($cs_feature_options) && is_array($cs_feature_options) && count($cs_feature_options)>0){?>
									<div class="theme-help">
										<h4><?php _e('Features','directory');?></h4>
										<div class="clear"></div>
									</div>
									<ul class="form-elements">
										<li class="to-label"><label><?php _e('Feature List','directory');?></label></li>
										<li class="to-field">
										<?php 
											foreach($cs_feature_options as $feature_key=>$feature){
												if(isset($feature_key) && $feature_key <> ''){
													$counter_feature = $feature_id = $feature['feature_id'];
													$feature_title 	 = $feature['feature_title'];
													$feature_slug 	 = $feature['feature_slug'];
													$checked		 = '';
													
													if ( is_array( $featureList ) && in_array( $feature_slug , $featureList )  ) {
														$checked	 = 'checked="checked"';
													}
													
													echo '<div class="cs-feature-list cs-checkbox checkbox-inline">
													<input type="checkbox" name="dir_cusotm_field[cs_feature_list][]" '.$checked.' value="'.$feature_slug.'" />';
													echo '<label>'. esc_attr( $feature_title ).'</label>';
													echo '</div>';
												}
											}
											?>
										</li>
									</ul>	
								   <?php }?>									
								<div class="theme-help">
                                    <h4><?php _e('Opening Hours','directory')?></h4>
                                </div>
                               <ul class="form-elements">
                   				<li class="to-label"><label><?php _e('Opening Hours', 'directory'); ?></label></li>
                                    <li class="cs-opening-hours">
                                    <table class="user-openings">
                                     <thead>
                                      <tr>
                                       <th><?php _e('Day Name', 'directory'); ?></th>
                                       <th><?php _e('Start Time', 'directory'); ?></th>
                                       <th><?php _e('End Time', 'directory'); ?></th>
                                      </tr>
                                     </thead>
                                    <?php 
                                        if(function_exists('cs_post_openinghours')) {
                                            cs_post_openinghours( $post->ID );
                                        }
                                     ?>
                                    </table>
                                    </li>
                               </ul>
                               <div class="theme-help">
                                    <h4><?php _e('Contact Information','directory')?></h4>
                                </div>
                                <ul class="form-elements">
                                 <li class="to-label"><label><?php _e('Landline', 'directory'); ?></label></li>
                                 <li class="to-field"><input class="text-input" name="dir_cusotm_field[user_landline]" type="text" id="landline" value="<?php echo esc_attr( $cs_user_landline ); ?>" />
                                 </li>
                                 </ul>
                				 <ul class="form-elements">
                                 <li class="to-label"><label><?php _e('Mobile', 'directory'); ?></label></li>
                                 <li class="to-field"><input class="text-input" name="dir_cusotm_field[user_mobile]" type="text" id="mobile" value="<?php echo esc_attr( $cs_user_mobile ); ?>" />
                                 </li>
                                 </ul>
                				 <ul class="form-elements">
                                 <li class="to-label"><label><?php _e('Email Address', 'directory'); ?></label></li>
                                 <li class="to-field"><input class="text-input" name="dir_cusotm_field[user_email]" type="text" id="email" value="<?php echo esc_attr( $cs_user_email ); ?>" />
                                 </li>
                                 </ul>
                				 <ul class="form-elements">
                                 <li class="to-label"><label><?php _e('Skype', 'directory'); ?></label></li>
                                  <li class="to-field"><input class="text-input" name="dir_cusotm_field[user_skype]" type="text" id="skype" value="<?php echo esc_attr( $cs_user_skype ); ?>" />
                                  </li>
                                  </ul>
                				  <ul class="form-elements">
                                  <li class="to-label"><label><?php _e('website', 'directory'); ?></label></li>
                                  <li class="to-field"><input class="text-input" name="dir_cusotm_field[user_website]" type="text" id="website" value="<?php echo esc_url( $cs_user_url ); ?>" />
                                  </li>
                                </ul>  
								<?php
								if( isset( $post_review_switch ) && $post_review_switch == 'on' ){?>
								<div class="theme-help">
									<h4><?php _e('Reviews','directory');?></h4>
									<div class="clear"></div>
								</div>
								<ul class="form-elements">
									<li class="to-label"><label><?php _e('Enable Reviews','directory');?></label></li>
									<li class="to-field select-style">
									<select name="dir_cusotm_field[directory_reviews]" id="directory_reviews">
										<option value="yes" <?php if( isset( $directory_reviews ) && $directory_reviews == 'yes' ) { echo 'selected'; }?> ><?php _e('Yes','directory');?></option>
										<option value="no" <?php if( isset( $directory_reviews ) && $directory_reviews == 'no' ) { echo 'selected'; }?>><?php _e('No','directory');?></option>
									</select>
									</li>
								</ul>
								<?php
								}
								if(isset($directory_feature_price) && $directory_feature_price <> '')
									$directory_feature_price_value = $directory_feature_price;
								else
									$directory_feature_price_value = isset($cs_theme_options['directory_featured_ad_price']) ? $cs_theme_options['directory_featured_ad_price'] :'';
								?>
								<div class="theme-help">
                                    <h4><?php _e('Features Price','directory')?></h4>
                                    <div class="clear"></div>
                                </div>
                                <ul class="form-elements">
									<li class="to-label"><label><?php _e('Urgent Ad Price','directory');?></label></li>
									<li class="to-field">
										<input type="text" value="<?php echo cs_allow_special_char($directory_feature_price_value); ?>" name="dir_cusotm_field[directory_feature_price]" id="directory_feature_price" />
									</li>
								</ul>
								
								<?php
								if(isset($directory_feature_duration) && $directory_feature_duration <> '')
									$directory_feature_duration_value = $directory_feature_duration;
								else
									$directory_feature_duration_value = isset($cs_theme_options['directory_featured_ad_days']) ? $cs_theme_options['directory_featured_ad_days'] : '';
								?>
								<ul class="form-elements">
									<li class="to-label"><label><?php _e('Urgent Ad Duration','directory');?></label></li>
									<li class="to-field">
										<input type="text" value="<?php echo cs_allow_special_char($directory_feature_duration_value); ?>" name="dir_cusotm_field[directory_feature_duration]" id="directory_feature_duration" />
									</li>
								</ul>
								
								<?php
								
								if(isset($cs_post_price_saleprice_option) && $cs_post_price_saleprice_option == 'on'){
									?>
									<div class="theme-help">
										<h4><?php _e('Price','directory');?></h4>
										<div class="clear"></div>
									</div>
									 <?php cs_sale_fields(); 
								}
 								if(isset($cs_post_location_option) && $cs_post_location_option == 'on'){
									if ( function_exists( 'cs_location_fields' ) ) {
										?>
										 <div class="theme-help">
											<h4><?php _e('Locations','directory');?></h4>
											<div class="clear"></div>
										</div>
										<?php
										cs_location_fields();
									}
								}
								if(isset($cs_post_faqs_option) && $cs_post_faqs_option == 'on'){
									?>
									<div class="theme-help">
										<h4><?php _e('FAQS','directory');?></h4>
										<div class="clear"></div>
									</div>
									<ul class="form-elements">
										<li>
										  <?php cs_faqs_section(); ?>
										</li>
									</ul>
								<?php
								}
								$custom_fields = '';
								$cs_directory_custom_fields = get_post_meta($post_id, "cs_directory_custom_fields", true);
								if ( $cs_directory_custom_fields <> "" ) {
									$cs_customfields_object = new SimpleXMLElement($cs_directory_custom_fields);
									if(isset($cs_customfields_object->custom_fields_elements) && $cs_customfields_object->custom_fields_elements == '1'){
										if(count($cs_customfields_object)>1){
											echo '';	
											echo '<div class="theme-help">
													<h4>'.__('Custom Features','directory').'</h4>
													<div class="clear"></div>
												</div>';
											global $cs_node;
											
											foreach ( $cs_customfields_object->children() as $cs_node ){
												//$custom_fields .= '<div class="pbwp-form-rows">';
													$custom_fields .= cs_custom_fields_render();
												//$custom_fields .= '</div>';
											}
										}
									}
								}
								echo '<div class="pbwp-form-holder">';
									echo balanceTags($custom_fields, false);
								echo '</div>';
						}
				echo '</div>';
			}
			
			/**
			* Directory Meta option save
		    */
			public function cs_meta_directory_save( $post_id ){  
				global $post, $cs_theme_options;
				
				$sxe = new SimpleXMLElement("<directory></directory>");
				if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;  
				
				$sxe = new SimpleXMLElement("<cs_directory_meta></cs_directory_meta>");
				if (isset($_REQUEST['dir'])){
					foreach ( $_REQUEST['dir'] as $keys=>$values) {
						if(is_array($values)){
							$values = implode(",", $values);
						}
						if($keys == 'directory_type_select'){
							update_post_meta( $post_id, 'directory_type_select', $values );
						}
						if($keys == 'directory_organizer'){
							update_post_meta( $post_id, 'directory_organizer', $values );
						}
						
						$sxe->addChild($keys, htmlspecialchars($values));
					}
				}
				
				if (isset($_REQUEST['dir_cusotm_field'])){
					foreach ( $_REQUEST['dir_cusotm_field'] as $keys=>$values) {
						if(is_array($values)){
							$values = implode(",", $values);
						}
						if($keys){
							update_post_meta( $post_id, $keys, sanitize_text_field($values) );
						}
						if($keys == 'cs_directory_pkg_names'){
							$cs_directory_pkg_names = sanitize_text_field($values);
							$cs_packages_options = get_option('cs_packages_options');
							if(isset($cs_packages_options) && is_array($cs_packages_options) && count($cs_packages_options)>0){
								$dir_pkg = $cs_packages_options[$cs_directory_pkg_names];
								update_post_meta( $post_id, '_pakage_meta', $dir_pkg );
							}
						}
					}
				}
				
				/*----------------------------------------------------
				 * Opening Hours
				 *----------------------------------------------------*/
				$opening_hours = array();
				if(isset($_POST['opening_hours'])){
					foreach($_POST['opening_hours'] as $key=>$value){
						$opening_hours[$key] = esc_attr($value);
					}
				}
				
				update_post_meta( $post_id, 'opening_hours', $opening_hours );
				
				if ( function_exists( 'cs_page_options_save_xml' ) ) {
					$sxe = cs_page_options_save_xml($sxe);
				}
				
				$faq_counter = 0;
				if (isset($_POST['dynamic_post_faq']) && $_POST['dynamic_post_faq'] == '1' && isset($_POST['faq_title_array']) && is_array($_POST['faq_title_array'])) {
					foreach ( $_POST['faq_title_array'] as $type ){
						$faq_list = $sxe->addChild('faqs');
						$faq_list->addChild('faq_title', htmlspecialchars($_POST['faq_title_array'][$faq_counter]));
						$faq_list->addChild('faq_description', htmlspecialchars($_POST['faq_description_array'][$faq_counter]));
						$faq_counter++;
					}
				}
				
				$dir_featured_till = get_post_meta( $post->ID, "dir_featured_till", true ); 
				if ( isset( $dir_featured_till ) && $dir_featured_till == '' ) {
					
					$featured_date = date_i18n( 'Y-m-d H:i:s', strtotime(current_time('Y-m-d H:i:s')));		
				} else{
					$featured_date = date_i18n( 'Y-m-d H:i:s', strtotime(current_time('Y-m-d H:i:s')));
					
					if ( isset( $_POST['dir_cusotm_field']['dir_featured_till'] ) && $_POST['dir_cusotm_field']['dir_featured_till'] !='' ) {
						$featured_date = date_i18n( 'Y-m-d H:i:s', strtotime($_POST['dir_cusotm_field']['dir_featured_till']));	
					}
				}
				
				if ( isset( $_POST['dir_payment_date'] ) && $_POST['dir_payment_date'] !='' ) {
					$payment_date = date_i18n( 'Y-m-d H:i:s', strtotime($_POST['dir_payment_date']));	
				}else{
					$payment_date = '';
				}
				$cs_video_url =isset($_POST['cs_video_url']) ? $_POST['cs_video_url'] : '';
				update_post_meta($post_id, 'dir_featured_till', $featured_date);
				update_post_meta($post_id, 'dir_payment_date', $payment_date);
				update_post_meta($post_id, 'cs_video_url', $cs_video_url);
				update_post_meta($post_id, 'cs_video_url', $cs_video_url);
				
									
				update_post_meta( $post_id, 'cs_directory_meta', $sxe->asXML() );
				
				if ( isset($_POST['directory_categories'])){
					$directory_categories = $_POST['directory_categories'];
					wp_set_post_terms($post_id,$directory_categories,'directory-category',false);
				}
			}
		} // END class
	}