<?php
	// Locations start
		//adding columns start
		add_filter('manage_location_posts_columns', 'location_columns_add');
			function location_columns_add($columns) {
				$columns['users'] = 'Users';
				$columns['author'] = 'Author';
				return $columns;
		}
		add_action('manage_location_posts_custom_column', 'location_columns',10, 2);
			function location_columns($name) {
				global $post;
				$var_cp_rating = get_post_meta($post->ID, "cs_location_rating", true);
				$var_cp_location_members = get_post_meta($post->ID, "cs_location_user", true);
				$cs_location_directory = get_post_meta($post->ID, "cs_location_directory", true);
				switch ($name) {
					case 'users':
 						echo get_the_author_meta('display_name', $var_cp_location_members);
 					break;
 					 
					case 'author':
						echo get_the_author();
					break;
				}
			}
		//adding columns end
		
if(!class_exists('post_type_locations')){
	
	class post_type_locations{
	
			/**
			 * The Constructor
			 */
			public function __construct()
			{
				// register actions
				add_action('init', array(&$this, 'cs_location_init'));
				add_action('admin_init', array(&$this, 'cs_location_admin_init'));
				
 				
				//if ( isset($_POST['location_form']) and $_POST['location_form'] == 1 ) {
						add_action( 'save_post', array(&$this, 'cs_location_save') );
				//}
			}
			/**
			 * hook into WP's init action hook
			 */
			public function cs_location_init()
			{
				// Initialize Post Type
				$this->cs_location_register();
			}
		
			public function cs_location_register()
			{
				$labels = array(
					'name' => 'Locations',
					'add_new_item' => 'Add New Locations',
					'edit_item' => 'Edit Locations',
					'new_item' => 'New Locations Item',
					'add_new' => 'Add New Locations',
					'view_item' => 'View Locations Item',
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
					'supports' => array('title')
				); 
				register_post_type( 'location' , $args );
				
			}
			
			/**
			 * hook into WP's admin_init action hook
			 */
			public function cs_location_admin_init()
			{           
				// Add metaboxes
				add_action( 'add_meta_boxes',  array(&$this, 'cs_meta_location_add') );
			}
			/**
			 * hook into WP's add_meta_boxes action hook
			 */
			public function cs_meta_location_add()
			{  
				add_meta_box( 'cs_meta_location', 'Locations Options', array(&$this, 'cs_meta_location'), 'location', 'normal', 'high' );  
			}
			
			/**
			 * Locations Meta attributes Array
			 */
			public function cs_location_meta_attributes()
			{
				return array(
							'title'=>'Locations Options',
							'description'=>'',
							'meta_attributes' => array(
								'cs_location' => array(
									'name' => 'cs_location',
									'type' => 'dropdown',
									'id' => 'cs_location',
									'class' => 'gllpLatitude',
 									'title' => 'Location',
									'description' => 'Select Location.',
									'value' => cs_get_countries(),
 								),
								'cs_location_latitude' => array(
									'name' => 'cs_location_latitude',
									'type' => 'text',
									'id' => 'cs_location_latitude',
									'class' => 'gllpLatitude',
 									'title' => 'Latitude',
									'description' => 'Add Latitude',
									'value' => '',
 								),
								'cs_location_longitude' => array(
									'name' => 'cs_location_longitude',
									'type' => 'text',
									'id' => 'cs_location_longitude',
									'class' => 'gllpLongitude',
 									'title' => 'Longitude',
									'description' => 'Add Longitude.',
									'value' => '',
								),
								'cs_location_zoom' => array(
									'name' => 'cs_location_zoom',
									'type' => 'text',
									'id' => 'cs_location_zoom',
									'class' => 'gllpZoom',
 									'title' => 'Zoom',
									'description' => 'Set Zoom.',
									'value' => '4',
								),

								'location_form' => array(
									'name' => 'location_form',
									'type' => 'hidden',
									'id' => 'location_form',
									'class' => 'gllpLatitude',
									'title' => '',
									'description' => '',
									'value' => '1',
								),
							),
						);
			}
			
			public function cs_meta_location( $post ) 
			{
				
				global $cs_xmlObject, $post;
				
				$location_attributes = $this->cs_location_meta_attributes();
				//print_r($location_attributes);
				cs_enqueue_location_gmap_script();
				
				$html = '<div class="page-wrap">
							<div class="option-sec" style="margin-bottom:0;">
								<div class="opt-conts"><fieldset class="gllpLatlonPicker" id="custom_id">';
									foreach($location_attributes['meta_attributes'] as $key=>$attribute_values){
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
																	
																	$html .= '<select name="'.$attribute_values['id'].'" id="' . $attribute_values['id'] . '" class="cs-form-select cs-input" onchange="cs_search_map(this.value)">' . "\n";
																	foreach( $attribute_values['value'] as $value => $option )
																	{
																		$selected = '';
																		
																		if($option == get_post_meta($post->ID, $attribute_values['id'], true)){$selected = 'selected = "selected"';}
																		
																		$html .= '<option value="' . $option . '" '.$selected.'>' . $option . '</option>' . "\n";
																	}
																	$html .= '</select>' . "\n";
																	
																	break;
																case 'text' :
																	 if(get_post_meta($post->ID, $attribute_values['id'], true) <> ''){
																			  $value =get_post_meta($post->ID, $attribute_values['id'], true);
																		  }else{ 
																			  $value = $attribute_values['value'];
																		  }
																	$html .= '<input id="'. $attribute_values['id'].'" name=" '.$attribute_values['id'].'" value="'.$value .'" type="text" class="'.sanitize_html_class($attribute_values['class']).' small" />';
																	break;
																	
															}
															$html .= '<p class="cs-form-desc">' . $attribute_values['description'] . '</p>' . "\n";
												$html .= '</div>
												
													 </li>
													 
													 
												  	</ul>';
										}
									}
						$html .= ' 
													  <input name="country" id="country" type="hidden" class="gllpSearchField">
													  <input type="button" class="gllpSearchButton" value="search" style="display:none">
													  <input type="button" class="gllpUpdateButton" value="update map">
													 <div class="gllpMap">Google Maps</div></div>
						</fieldset></div>
					<div class="clear"></div>
				</div>';
				echo cs_allow_special_char($html);
			}
			
			/**
			 * Save Meta Fields
			 */
			public function cs_location_save( $post_id ){ 
				
				if ( isset($_POST['location_form']) and $_POST['location_form'] == 1 ) {
					
						$sxe = new SimpleXMLElement("<location></location>");
						
						if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
 						
						$location_attributes = $this->cs_location_meta_attributes();
						foreach($location_attributes['meta_attributes'] as $key=>$value)
						  {
							  if(isset($key)){
								  $value = (empty($_POST[$key]))? '' : $_POST[$key];
								  update_post_meta($post_id, $key, $value);
							  }
						  }
						$counter = 0;
						update_post_meta( $post_id, 'cs_meta_location', $sxe->asXML() );
				}
			}
			
	}
	
}
?>