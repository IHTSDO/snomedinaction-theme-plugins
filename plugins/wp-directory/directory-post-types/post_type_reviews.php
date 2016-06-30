<?php
	// Reviews start
//adding columns start
add_filter('manage_cs-reviews_posts_columns', 'cs_reviews_columns_add');
	function cs_reviews_columns_add($columns) {
		$columns['users'] = 'Users';
		$columns['directory'] = 'Ad';
		$columns['category'] = 'Category';
		$columns['rating'] = 'Rating';
		return $columns;
}
add_action('manage_cs-reviews_posts_custom_column', 'cs_reviews_columns',10, 2);
	function cs_reviews_columns($name) {
		global $post;
		$rating = 0;
		$cs_reviews_directory = get_post_meta($post->ID, "cs_reviews_directory", true);
		$directory_type_select = get_post_meta($cs_reviews_directory, "directory_type_select", true);
		$cs_rating_options = get_post_meta((int)$directory_type_select, 'cs_rating_meta', true);
		$rating = 0;
		$rating_array = array();
		if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
			foreach($cs_rating_options as $rating_key=>$rating){
				if(isset($rating_key) && $rating_key <> ''){
					$rating_title = $rating['rating_title'];
					$rating_slug = $rating['rating_slug'];
					$rating_array[] = get_post_meta($post->ID, (string)$rating_slug, true);
				}
			}
		}
		$cs_rating_perctage = '0';
		if(isset($rating_array) && count($rating_array)>0){
			$rating = round(array_sum($rating_array)/count($cs_rating_options), 2);
			$cs_rating_perctage = ($rating/5)*100;
		}
		$var_cp_reviews_members = get_post_meta($post->ID, "cs_reviews_user", true);
		switch ($name) {
			case 'users':
				echo get_the_author_meta('display_name', $var_cp_reviews_members);
			break;
			case 'directory':
				echo '<a href="'.get_edit_post_link($cs_reviews_directory).'">'.get_the_title($cs_reviews_directory).'</a>';
			break;

			case 'category':
				$categories = get_the_terms($cs_reviews_directory ,'directory-category' );
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
			break;
			case 'rating':
				echo '<div class="cs-ratingstar">
						<span style="width:'.$cs_rating_perctage.'%;"></span>';
				//echo cs_allow_special_char($rating);
				echo '</div>';
			break;

		}
}
//adding columns end


		
if(!class_exists('post_type_reviews')){
	
	class post_type_reviews{
	
			/**
			 * The Constructor
			 */
			public function __construct()
			{
				// register actions
				add_action('init', array(&$this, 'cs_reviews_init'));
				add_action('admin_init', array(&$this, 'cs_reviews_admin_init'));
				
				add_action('wp_ajax_cs_add_reviews', array(&$this, 'cs_add_reviews'));
				add_action('wp_ajax_nopriv_cs_add_reviews', array(&$this, 'cs_add_reviews'));
				
				//if ( isset($_POST['reviews_form']) and $_POST['reviews_form'] == 1 ) {
				add_action( 'save_post', array(&$this, 'cs_reviews_save') );
				//}
			}
			/**
			 * hook into WP's init action hook
			 */
			public function cs_reviews_init()
			{
				// Initialize Post Type
				$this->cs_reviews_register();
			}
			
			public function cs_add_reviews(){
			global $post,$cs_theme_options;
			$user_id = cs_get_user_id();
				if ( $_SERVER["REQUEST_METHOD"] == "POST"){
					
					$reviews_title 			= $_POST['reviews_title'];
					$directory_id 			= $_POST['directory_id'];
					$reviews_description 	= $_POST['reviews_description'];
					$reviewStatus 			= $cs_theme_options['cs_review_status'];
					$user_id 		   		= $_POST['user_id'];;
					
					if ( $reviews_title == '' || $reviews_description == '' ) {
						$json['type']    = "error";
						$json['message'] = 'All the fields are required.';
						echo json_encode( $json );
						die;
					}
					$user_reviews_args = array(
						'posts_per_page'	=> "-1",
					  	'post_type'			=> 'cs-reviews',
					  	'post_status'		=> 'any',
					  	'author' 			=> $user_id,
					  	'meta_key'			=> 'cs_reviews_directory',
					  	'meta_value'		=> $directory_id,
					  	'meta_compare'		=> "=",
					  	'orderby'			=> 'meta_value',
					  	'order'				=> 'ASC',
					);
					$user_reviews_query = new WP_Query($user_reviews_args);
					$user_reviews_count = $user_reviews_query->post_count;
					if( isset( $user_reviews_count ) && $user_reviews_count > 0 ){
						$json['type']		= 'pending';
						$json['message']	= __('You have Already Submit a Review.', 'directory');
						echo json_encode($json);
						die();
					}
                                
					
					if ( isset ( $reviewStatus ) && $reviewStatus == 'pending' ) {
						$status	= 'pending';
					} else if ( isset ( $reviewStatus ) && $reviewStatus == 'approve' ) {
						$status	= 'publish';
					} else {
						$status	= 'publish';
					}
					
					$reviews_post = array(
						'post_title'	=> $reviews_title ,
						'post_content'	=> $reviews_description,
						'post_status'	=> $status,
						'post_author'	=> $user_id,
						'post_type'		=> 'cs-reviews',
					);
					
					$post_id = wp_insert_post( $reviews_post );
					
					if($post_id){
						$rating = 0;
						$directory_type_select = get_post_meta($directory_id, "directory_type_select", true);
						$cs_rating_options = get_post_meta((int)$directory_type_select, 'cs_rating_meta', true);
						$rating = 0;
						if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
							foreach($cs_rating_options as $rating_key=>$rating){
								if(isset($rating_key) && $rating_key <> ''){
									$rating_title = $rating['rating_title'];
									$rating_slug  = $rating['rating_slug'];
									if(isset($_POST[$rating_slug])){
										$rating_value = $_POST[$rating_slug];
										update_post_meta($post_id, $rating_slug, $rating_value);
									}
								}
							}
						}
						update_post_meta($post_id, "cs_reviews_user", $user_id);
						update_post_meta($post_id, "cs_reviews_directory", $directory_id);
						$this->cs_update_rating($directory_id);
						$json	= array();
						if ( $reviewStatus == 'pending' ) {
							$json['type']	= 'pending';
							$json['message']	= '<p>Your Given Review will be Sent to Administrators. Once your Review has been Approved.Review Will be Posted publicly on the web.</p>';
						} else if ( $reviewStatus == 'approve' ) {
							$json['type']		= 'aproved';							
							$json['message']	=  __("Your Given Review has been Approved and Will be Posted publicly on the web", "directory");
							
						}
					
					echo json_encode($json);
					die();
					}
				}
			exit;
			}

			public function cs_reviews_register()
			{
				$labels = array(
					'name' => 'Reviews',
					'add_new_item' => 'Add New Reviews',
					'edit_item' => 'Edit Reviews',
					'new_item' => 'New Reviews Item',
					'add_new' => 'Add New Reviews',
					'view_item' => 'View Reviews Item',
					'search_items' => 'Search v',
					'not_found' => 'Nothing found',
					'not_found_in_trash' => 'Nothing found in Trash',
					'parent_item_colon' => ''
				);
				$args = array(
					'labels' => $labels,
					'public' => true,
					'publicly_queryable' => true,
					'show_ui' => true,
					'query_var' => true,
					'menu_icon' => 'dashicons-admin-post',
					'show_in_menu' => 'edit.php?post_type=directory',
					//'show_in_menu' => 'edit.php?post_type=directory',
					'rewrite' => true,
					'capability_type' => 'post',
					'hierarchical' => false,
					'menu_position' => null,
					'supports' => array('title','editor')
				); 
				register_post_type( 'cs-reviews' , $args );
				
			}
			
			/**
			 * hook into WP's admin_init action hook
			 */
			public function cs_reviews_admin_init()
			{           
				// Add metaboxes
				add_action( 'add_meta_boxes',  array(&$this, 'cs_meta_reviews_add') );
			}
			/**
			 * hook into WP's add_meta_boxes action hook
			 */
			public function cs_meta_reviews_add()
			{  
				add_meta_box( 'cs_meta_reviews', 'Reviews Options', array(&$this, 'cs_meta_reviews'), 'cs-reviews', 'normal', 'high' );  
			}
			
			/**
			 * Reviews Meta attributes Array
			 */
			public function cs_reviews_meta_attributes()
			{
				global $cs_xmlObject, $post;
				$reviews_meta_attributes = array(
							'title'=>'Reviews Options',
							'description'=>'',
							'meta_attributes' => array(
								'cs_reviews_user' => array(
									'name' => 'cs_reviews_user',
									'type' => 'dropdown_user',
									'id' => 'cs_reviews_user',
									'dropdown_type' => 'single',
									'title' => 'Select User',
									'description' => 'Select The User.',
									'options' => get_users('orderby=nicename'),
								),
								'cs_reviews_directory' => array(
									'name' => 'cs_reviews_directory',
									'type' => 'dropdown_query',
									'id' => 'cs_reviews_directory',
									'dropdown_type' => 'single',
									'title' => 'Select Directory',
									'description' => 'Select The Directory.',
									'options' => array('showposts' => "-1", 'post_status' => 'publish', 'post_type' => 'directory'),
								),

								'reviews_form' => array(
									'name' => 'reviews_form',
									'type' => 'hidden',
									'id' => 'reviews_form',
									'title' => '',
									'description' => '',
									'value' => '1',
								),
							),
						);
						
						$rating = 0;
						$cs_reviews_directory = get_post_meta($post->ID, "cs_reviews_directory", true);
						$directory_type_select = get_post_meta((int)$cs_reviews_directory, "directory_type_select", true);
						$cs_rating_options = get_post_meta((int)$directory_type_select, 'cs_rating_meta', true);
						$rating = 0;
						if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
							foreach($cs_rating_options as $rating_key=>$rating){
								if(isset($rating_key) && $rating_key <> ''){
									$rating_title = $rating['rating_title'];
									$rating_slug = (string)$rating['rating_slug'];
									if(isset($rating_slug)){
										$reviews_meta_attributes['meta_attributes'][$rating_slug] = array(
																											'name' => $rating_slug,
																											'type' => 'dropdown',
																											'id' => $rating_slug,
																											'dropdown_type' => 'single',
																											'title' => $rating_title,
																											'description' => 'Select The Rating.',
																											'options' =>  range(0,5),
																										);
									}
								}
							}
						}
						return $reviews_meta_attributes;
			}
			
			public function cs_meta_reviews( $post ) 
			{
				
				global $cs_xmlObject, $post;
				$reviews_attributes = $this->cs_reviews_meta_attributes();
				$review_id = $post->ID;
				$html = '<div class="page-wrap">
							<div class="option-sec" style="margin-bottom:0;">
								<div class="opt-conts"><div class="cs-review-wrap">';
									foreach($reviews_attributes['meta_attributes'] as $key=>$attribute_values){
										if($attribute_values['type'] == 'hidden'){
											$html .= '<input type="hidden" name="'.$attribute_values['id'].'" value="'.$attribute_values['value'].'" />';
										} else {
											$html .= '<ul class="form-elements">
													  <li class="to-label"><label>'.$attribute_values['title'].'</label></li>
													  <li class="to-field">
														<div class="input-sec">';
															switch( $attribute_values['type'] )
															{
																case 'dropdown' :
																	$html .= '<select name="'.$attribute_values['id'].'" id="' . $attribute_values['id'] . '" class="cs-form-select cs-input">' . "\n";
																	foreach( $attribute_values['options'] as $value => $option )
																	{
																		$selected = '';
																		$rating_value = get_post_meta($review_id, (string)$attribute_values['id'], true);
																		if($option == $rating_value){$selected = 'selected = "selected"';}
																		$html .= '<option value="' . $option . '" '.$selected.'>' . $option . '</option>' . "\n";
																	}
																	$html .= '</select>' . "\n";
																	$html .= '<p class="cs-form-desc">' . $attribute_values['description'] . '</p>' . "\n";
																	break;
																case 'dropdown_user' :
																	$html .= '<select name="'.$attribute_values['id'].'" id="' . $attribute_values['id'] . '" class="cs-form-select cs-input">' . "\n";
																	foreach( $attribute_values['options'] as  $user )
																	{
																		if($user->ID == get_post_meta($post->ID, $attribute_values['id'], true)){
																			  $selected =' selected="selected"';
																		  }else{ 
																			  $selected = '';
																		  }
																		$html .= '<option value="' . $user->ID . '" '.$selected.'>' .$user->display_name. '</option>' . "\n";
																	}
																	$html .= '</select>' . "\n";
																	$html .= '<p class="cs-form-desc">' . $attribute_values['description'] . '</p>' . "\n";
																	break;
																case 'file' :
																	$html .= '<input id="'. $attribute_values['id'].'" name=" '.$attribute_values['id'].'" value="'.$var_cp_assignment_file.'" type="text" class="small" />
																	<input id="' . $attribute_values['id'] . '" name="'.$attribute_values['id'].'" type="button" class="uploadfile left" value="'.__('Browse','directory').'"/>';
																	break;
																case 'dropdown_query' :
																	$var_cp_course = get_post_meta($post->ID, $attribute_values['id'], true);
																	$html .= '<select name="'.$attribute_values['id'].'" id="' . $attribute_values['id'] . '" class="cs-form-select cs-input">' . "\n";
																	query_posts($attribute_values['options']);
                                        							while (have_posts() ) : the_post();
                                                                          $cs_courses_id = get_the_id();
                                                                  			
                                                                          if($cs_courses_id == $var_cp_course){
                                                                                  $selected =' selected="selected"';
                                                                              }else{ 
                                                                                  $selected = '';
                                                                              }
                                                                         $html.='<option value="'.$cs_courses_id.'" '.$selected.'>'.get_the_title().'</option>';
                                                                          
                                                                	 endwhile; 
																	 wp_reset_postdata();
																	 $html.='</select>';
															}
												$html .= '</div>
													 </li>
												  	</ul>';
										}
									}
						$html .= '</div></div>
						</div>
					<div class="clear"></div>
				</div>';
				echo cs_allow_special_char($html);
			}
			
			/**
			 * Save Meta Fields
			 */
			public function cs_reviews_save( $post_id ){ 
				$post = get_post($post_id);
				if ( isset($_POST['reviews_form']) and $_POST['reviews_form'] == 1 ) {
 						$sxe = new SimpleXMLElement("<reviews></reviews>");
						if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
						$reviews_attributes = $this->cs_reviews_meta_attributes();
						foreach($reviews_attributes['meta_attributes'] as $key=>$value)
						{
						  if(isset($key)){
							  $value = (empty($_POST[$key]))? '' : $_POST[$key];
							  update_post_meta($post_id, $key, $value);
							  if($key == 'cs_reviews_directory'){
								  $this->cs_update_rating($value);
							  }
						  }
						 }
						$counter = 0;
						update_post_meta( $post_id, 'cs_meta_reviews', $sxe->asXML() );
						
				}elseif($post->post_status == 'trash'){
					$current_post_id = get_post_meta( $post_id, 'cs_reviews_directory', true);
					if(isset($current_post_id) and $current_post_id <> ''){
						// update review on trash post
						//$this->cs_update_rating($current_post_id);
					}
				}
			}
			
		  public function cs_update_rating($id){
			global $post,$wpdb;
 			$reviews_args = array(
				'posts_per_page'			=> "-1",
				'post_type'					=> 'cs-reviews',
				'post_status'				=> 'publish',
				'meta_key'					=> 'cs_reviews_directory',
				'meta_value'				=> $id,
				'meta_compare'				=> "=",
				'orderby'					=> 'meta_value',
				'order'						=> 'ASC',
			);
			$reviews_query = new WP_Query($reviews_args);
			$reviews_count = $reviews_query->post_count;
			$var_cp_rating = 0;
			$post_count = 0;
			if ( $reviews_query->have_posts() <> "" ) {
				$directory_type_select = get_post_meta($id, "directory_type_select", true);
				$cs_rating_options = get_post_meta((int)$directory_type_select, 'cs_rating_meta', true);
				$rating = 0;
				$dir_rating = array();
				$rating_array = array();
				while ( $reviews_query->have_posts() ): $reviews_query->the_post();	
					$post_count++;
					if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
						foreach($cs_rating_options as $rating_key=>$rating){
							if(isset($rating_key) && $rating_key <> ''){
								$rating_title = $rating['rating_title'];
								$rating_slug = $rating['rating_slug'];
								if(isset($_POST[$rating_slug])){
									$rating_value = $_POST[$rating_slug];
									if($rating_value){
										$rating_point = get_post_meta($post->ID, $rating_slug, true);
										if($rating_point)
											$rating_array[] = $rating_point;
									}
								}
							}
						}
						
					}
				endwhile;
				if($rating_array && is_array($rating_array) && count($rating_array)>0){
					$dir_rating[] = round(array_sum($rating_array)/count($cs_rating_options), 2);
				}
			}
			if(isset($dir_rating) && is_array($dir_rating) && count($dir_rating)>0){
				$var_cp_rating_sum = array_sum($dir_rating);
				$var_cp_rating = $var_cp_rating_sum/$post_count;
				$var_cp_rating = round($var_cp_rating, 2);
			}
			update_post_meta($id, "cs_directory_review_rating", $var_cp_rating);
			return $var_cp_rating;
	  }
	  //=========================================
	  // @ add_action('trash_post', 'emailUser');
	  //=========================================
	  public function cs_trash_post($post_id){
		  die();
	  }
	}
}