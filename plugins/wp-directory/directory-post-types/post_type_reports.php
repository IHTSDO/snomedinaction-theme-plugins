<?php
// Report start
//adding columns start
add_filter('manage_cs-reports_posts_columns', 'cs_reports_columns_add');
function cs_reports_columns_add($columns) {
	$columns['directory'] = 'directory';
	$columns['report_by']   = 'Reported By';
	$columns['report_type']  = 'Type';
	$columns['author'] = 'Author';
	return $columns;
}
add_action('manage_cs-reports_posts_custom_column', 'cs_reports_columns',10, 2);
function cs_reports_columns($name) {
	global $post;
	$user = get_post_meta($post->ID, "cs_reports_user", true);
	$cs_reports_type = get_post_meta($post->ID, "cs_reports_type", true);
	if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $user)) { 
		$user = $user;
	} else {
		$user = get_the_author();
	}
	$directory   = get_post_meta($post->ID, "cs_reports_directory", true);
	switch ($name) {
		case 'directory':
			echo '<a href="'.get_edit_post_link($directory).'">'.get_the_title($directory).'</a>';
		break;
		case 'report_by':
			echo esc_attr($user);
		break;
		case 'report_type':
			echo esc_attr($cs_reports_type);
		break;
		case 'author':
			echo get_the_author();
		break;
	}
}
//adding columns end		
//Data Filtering
function cs_get_report_types(){
	return array('Claim'=>'Claim', 'Report'=>'Report');
}
add_action( 'restrict_manage_posts', 'cs_report_filtering' );
function cs_report_filtering() {
  global $post, $wpdb;
  if ( isset($_GET['post_type']) && $_GET['post_type'] == 'cs-reports' ) {
    $report_types = cs_get_report_types();
    echo '<select name="report_type" id="report_type">';
		echo '<option value="">' . __( 'Select report type', 'directory' ) . '</option>';
		foreach( $report_types as $value => $name ) {
		  $selected = ( !empty( $_GET['report_type'] ) AND $_GET['report_type'] == $value ) ? 'selected="selected"' : '';
		  echo '<option '.$selected.'>' . $name . '</option>';
		}
    echo '</select>';
  }
}
//
add_filter( 'parse_query','cs_report_table_filter' );
function cs_report_table_filter( $query ) {
  if( is_admin() AND $query->query['post_type'] == 'cs-reports' ) {
    $qv = &$query->query_vars;
    $qv['meta_query'] = array();
    if(isset($_GET['report_type']) && !empty( $_GET['report_type'] ) ) {
      $qv['meta_query'][] = array(
        'field' => 'cs_reports_type',
        'value' => $_GET['report_type'],
        'compare' => '=',
        'type' => 'CHAR'
      );
    }
  }
}

// Post Type Reports
if(!class_exists('post_type_reports')){
	
	class post_type_reports{
	
			/**
			 * The Constructor
			 */
			public function __construct()
			{
				// register actions
				add_action('init', array(&$this, 'cs_reports_init'));
				add_action('wp_ajax_cs_add_report', array(&$this, 'cs_add_report'));
				add_action('wp_ajax_nopriv_cs_add_report', array(&$this, 'cs_add_report'));
				add_action('admin_init', array(&$this, 'cs_report_admin_init'));
				add_action( 'save_post', array(&$this, 'cs_report_save') );
			}
			/**
			 * hook into WP's init action hook
			 */
			public function cs_reports_init()
			{
				// Initialize Post Type
				$this->cs_reports_register();
			}
			public function cs_reports_register()
			{
				$labels = array(
					'name' => 'Reports',
					'add_new_item' => 'Add New Reports',
					'edit_item' => 'Edit Reports',
					'new_item' => 'New Reports Item',
					'add_new' => 'Add New Reports',
					'view_item' => 'View Reports Item',
					'search_items' => 'Search Reports',
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
				register_post_type( 'cs-reports' , $args );
				
			}
			/**
			 * Add Report From Front End
			 */
			public function cs_add_report(){
				global $post,$cs_theme_options;
				if ( $_SERVER["REQUEST_METHOD"] == "POST"){
					$report_counter  	= $_POST['report_counter'];
					$report_title  		= $_POST['report_title_'.$report_counter];
					$report_type  		= $_POST['report_type_'.$report_counter];
					$directory_id  		= $_POST['directory_id'];
					$report_description = $_POST['report_description_'.$report_counter];
					$report_from_name   = $_POST['report_from_name_'.$report_counter];
					$user_id 			= cs_get_user_id();
					$json				= array();
					if ( !is_user_logged_in() ) {
						$user   = $_POST['report_from_email_'.$report_counter];
					} 
					if ( $report_title == '' || $report_from_name == '' || $report_description == '' ) {
						$json['type']    = "error";
						$json['message'] = 'All the fields are required.';
						echo json_encode( $json );
						exit;
					}
					if ( !is_user_logged_in() ) {
						if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $user)) { 
							$json['type']		=  "error";
							$json['message']	=  __("Please enter a valid email.", "directory");
							echo json_encode( $json );
							exit;
						}
					} else {
						$user	= $user_id;
					}
					$reports_post = array(
						  'post_title'    => $report_title ,
						  'post_content'  => $report_description,
						  'post_status'   => 'publish',
						  'post_author'   => $user_id,
						  'post_type'     => 'cs-reports',
						);
						$post_id = wp_insert_post( $reports_post );
						if( $post_id ){
							update_post_meta($post_id, "cs_reports_user", $user);
							update_post_meta($post_id, "cs_reports_directory", $directory_id);
							update_post_meta($post_id, "cs_reports_type", $report_type);
							$json['type']		=  "success";
							$json['message']	=  __("Your report has submitted.", "directory");
							if( function_exists('cs_submit_report_mail') ) {cs_submit_report_mail( $directory_id, $report_counter, $report_description ); }
						} else {
							$json['type']		=  "error";
							$json['message']	=  __("Some error occur, please try again later.", "directory");
						}
						echo json_encode($json);
						exit();
				}
				exit;
			}
			/**
			 * hook into WP's admin_init action hook
			 */
			public function cs_report_admin_init()
			{           
				// Add metaboxes
				add_action( 'add_meta_boxes',  array(&$this, 'cs_meta_report_add') );
			}
			/**
			 * hook into WP's add_meta_boxes action hook
			 */
			public function cs_meta_report_add()
			{  
				add_meta_box( 'cs_meta_reports', 'Report Options', array(&$this, 'cs_meta_reports'), 'cs-reports', 'normal', 'high' );  
			}
			/**
			 * Report Meta attributes Array
			 */
			public function cs_report_meta_attributes()
			{
				$report_type_options = array('Report'=>'Report', 'Claim'=>'Claim');
				return array(
							'title'=>'Report Options',
							'description'=>'',
							'meta_attributes' => array(
								'cs_reports_user' => array(
									'name' => 'cs_reports_user',
									'type' => 'text_user',
									'id' => 'cs_reports_user',
									'dropdown_type' => 'single',
									'title' => 'Select User',
									'description' => 'Select The User.',
									'options' => get_users('orderby=nicename'),
								),
								'cs_reports_type' => array(
									'name' => 'cs_reports_type',
									'type' => 'dropdown',
									'id' => 'cs_reports_type',
									'dropdown_type' => 'single',
									'title' => 'Report Type',
									'description' => 'Select Report Type.',
									'options' =>  $report_type_options,
								),
								'cs_reports_directory' => array(
									'name' => 'cs_reports_directory',
									'type' => 'dropdown_query',
									'id' => 'cs_reports_directory',
									'dropdown_type' => 'single',
									'title' => 'Select Directory',
									'description' => 'Select The Directory.',
									'options' => array('showposts' => "-1", 'post_status' => 'publish', 'post_type' => 'directory'),
								),
								'report_form' => array(
									'name' => 'report_form',
									'type' => 'hidden',
									'id' => 'report_form',
									'title' => '',
									'description' => '',
									'value' => '1',
								),
							),
						);
			}
			/**
			 * Report Meta
			 */
			public function cs_meta_reports( $post ) 
			{
				global $cs_xmlObject, $post;
				$reports_attributes = $this->cs_report_meta_attributes();
				$html = '<div class="page-wrap">
							<div class="option-sec" style="margin-bottom:0;">
								<div class="opt-conts">';
									foreach($reports_attributes['meta_attributes'] as $key=>$attribute_values){
										if($attribute_values['type'] == 'hidden'){
											$html .= '<input type="hidden" name="'.$attribute_values['id'].'" value="'.$attribute_values['value'].'" />';
										} else {
											$html .= '<ul class="form-elements  noborder">
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
																		if($option == get_post_meta($post->ID, $attribute_values['id'], true)){$selected = 'selected = "selected"';}
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
																case 'text_user' :
																	$user = get_post_meta($post->ID, "cs_reports_user", true);
																	$cs_reports_type = get_post_meta($post->ID, "cs_reports_type", true);
																	if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $user)) { 
																		$user = $user;
																	} else {
																		$user = get_the_author();
																	}
																	$html .= '<input id="'. $attribute_values['id'].'" name=" '.$attribute_values['id'].'" value="'.$user.'" type="text" class="small" disabled="disabled" />';
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
                                                                	 endwhile; wp_reset_query();
																	 $html.='</select>';
															}
												$html .= '</div>
													 </li>
												</ul>';
										}
									}
						$html .= '</div>
						</div>
					<div class="clear"></div>
				</div>';
				echo cs_allow_special_char($html);
			}
			/**
			 * Save Meta Fields
			 */
			public function cs_report_save( $post_id ){
				if ( isset($_POST['report_form']) and $_POST['report_form'] == 1 ) {
						if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
						$report_attributes = $this->cs_report_meta_attributes();
						foreach($report_attributes['meta_attributes'] as $key=>$value)
						  {
							  if(isset($key)){
								  $value = (empty($_POST[$key]))? '' : $_POST[$key];
								  update_post_meta($post_id, $key, $value);
							  }
						  }
						$counter = 0;
				}
			}
	}
}