<?php 
	/**
	 * Directory Type Custom Post  Types
	 */
	global $current_user;
	add_action('save_post', 'cs_dynamic_custom_post_type_save_postdata');
	add_action('add_meta_boxes', 'cs_dynamic_custom_post_type_add_meta_boxes');
	add_action('init', 'cs_init_custom_post_types');
	/**
	 * Directory Type Post 
	 */
	if(!function_exists('cs_init_custom_post_types')){
		function cs_init_custom_post_types(){
			register_post_type( 'directory_types',	array(
						'labels'              => array(
						'name'               => __( 'Directory Types', 'directory' ),
						'singular_name'      => __( 'Directory Types', 'directory' ),
						'menu_name'          => __( 'Directory Types',  'directory' ),
						'add_new'            => __( 'Add Directory Types', 'directory' ),
						'add_new_item'       => __( 'Add New Directory Types', 'directory' ),
						'edit'               => __( 'Edit', 'directory' ),
						'edit_item'          => __( 'Edit Directory Types', 'directory' ),
						'new_item'           => __( 'New Directory Types', 'directory' ),
						'view'               => __( 'View Directory Types', 'directory' ),
						'view_item'          => __( 'View Directory Types', 'directory' ),
						'search_items'       => __( 'Search Directory Types', 'directory' ),
						'not_found'          => __( 'No Directory Types found', 'directory' ),
						'not_found_in_trash' => __( 'No Directory Types found in trash', 'directory' ),
						'parent'             => __( 'Parent Directory Types', 'directory' )
					),
				'description'         => __( 'This is where you can add new Directory Types.', 'directory' ),
				'public'              => false,
				'show_ui'             => true,
				'capability_type'     => 'post',
			    'show_in_menu' 		  => 'edit.php?post_type=directory',
				'map_meta_cap'        => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'hierarchical'        => false, 
				'rewrite'             => false,
				'query_var'           => true,
				'supports'            => array( 'title' ),
				'has_archive'         => false,
				)
			);
		}
	}
	/**
	 * Directory Categories
	 */
	if(!function_exists('cs_directory_register_categories')){
		function cs_directory_register_categories(){
			  $labels = array(
				'name' => 'Directory Type Categories',
				'search_items' => 'Search Directory Categories',
				'edit_item' => 'Edit Directory Category',
				'update_item' => 'Update Directory Category',
				'add_new_item' => 'Add New Category',
				'menu_name' => 'Categories',
			  ); 	
			  register_taxonomy('directory-type-category',array('directory_types'), array(
				'hierarchical' => true,
				'labels' => $labels,
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'directory-type-category' ),
			  ));
		}
	}
	if(!function_exists('cs_dynamic_custom_post_type_add_meta_boxes')){
		function cs_dynamic_custom_post_type_add_meta_boxes() {
			add_meta_box('cs_dynamic_custom_post_type_meta_id', 'Custom Post Type Settings', 'cs_dynamic_custom_post_type_inner_custom_box', 'directory_types', 'normal');
		}
	}
	
	if(!function_exists('cs_dynamic_custom_post_type_inner_custom_box')){
		function cs_dynamic_custom_post_type_inner_custom_box() {
		global $post;
	?>
        <div class="cs-pbwp-options theme-wrap directory-type-wrapper" id="cs-pbwp-options">
        	<div class="col1">
                <nav class="admin-navigtion">
                    <ul id="myTab" class="reports-tabs">
                        <li class="active"><a href="#tab-options" data-toggle="tab"><i class="icon-text"></i> <?php _e('Options','directory')?></a></li>
                        <li class=""><a href="#tab-custom-fileds" data-toggle="tab"><i class="icon-link4"></i> <?php _e('Custom Fields','directory')?></a></li>
                        <li class=""><a href="#tab-custom-categories" data-toggle="tab"><i class="icon-vcard"></i> <?php _e('Categories','directory')?></a></li>
                        <li class=""><a href="#tab-custom-rating" data-toggle="tab"><i class="icon-star6"></i> <?php _e('Reviews Options','directory')?></a></li>
                        <li class=""><a href="#tab-feature-list" data-toggle="tab"><i class="icon-star6"></i> <?php _e('Feature List','directory')?></a></li>
                    </ul>
                </nav>
            </div>
            <div class="col2 tab-content directory-type-setting" style="width:860px;">
                <div id="tab-options" class="tab-pane fade active in">
					<?php echo cs_directory_custom_options(); ?>
                </div>
               
                <div id="tab-custom-fileds" class="tab-pane fade">
                    <?php cs_dynamic_custom_fields();?>	
                </div>
                
                <div id="tab-custom-categories" class="tab-pane fade">
                    <ul class="form-elements multiselect-holder">
                        <li class="categories">
                            <label for="categories"><?php _e('Categories','directory')?></label>
                            <?php
								
							$post_id	= $post->ID;
							$attached_array	= cs_get_attached_categories();
							$post->ID	= $post_id;
							
							if(!isset($directory_categories_array) || !is_array($directory_categories_array) || !count($directory_categories_array)>0){
                                $directory_categories_array = array();
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
							$directory_categories_array = get_post_meta($post->ID, "directory_types_categories", true);
							$directory_categories = explode(',', $directory_categories_array);
								
                            ?>
                            <select id="categories" class="multiselect" multiple="multiple" name="directory_types_categories[]">
                                <?php
									foreach ($categories as $category) {
										$selected = '';
										if ( is_array($attached_array) && !in_array($category->slug,$attached_array) ) {
											
											if(in_array($category->slug, $directory_categories)){
												$selected = 'selected="selected"';
											}
											
											echo '<option value="'.$category->slug.'" '.$selected.'>' . $category->name . '</option>';
										}
									}
                                ?>
                            </select>
                        </li>
                    </ul>
                </div>
                <div id="tab-custom-packages" class="tab-pane fade">
                    <?php
                        if(function_exists('cs_package_section')){
                            cs_package_section();
                        }
                    ?>
                </div>
                
                <div id="tab-custom-rating" class="tab-pane fade">
                    <?php
                    if(function_exists('cs_rating_section')){
                        cs_rating_section();
                    }
                    ?>
                </div>
               
                <div id="tab-feature-list" class="tab-pane fade">
                    <?php
                    if(function_exists('cs_rating_section')){
                        cs_feature_section();
                    }
                    ?>
                </div>
                <?php echo '<input type="hidden" value="true" name="dcpt-hidd" />'; ?>
                <?php cs_post_submit_meta_box ( 'directory_types',  $args = array() );?>
        
             </div>
        </div>
        <?php 
		}
	}
	/*
	*Payment Packages
	*/
	if(!function_exists('cs_package_section')){
		function cs_package_section(){
		global $post;
		
		?>
		<ul class="form-elements multiselect-holder">
        
            <li class="categories">
                <label for="categories"><?php _e('Packages','directory')?></label>
                <?php
                $directory_pakcages_array = get_post_meta($post->ID, "directory_types_packages", true);
                $directory_pakcages = explode(',', $directory_pakcages_array);
				if(!isset($directory_pakcages) || !is_array($directory_pakcages) || !count($directory_pakcages)>0){
                    $directory_pakcages = array();
                }
                ?>
                <select id="directory_types_packages" class="multiselect" multiple="multiple" name="directory_types_packages[]">
                    <?php
                    $cs_packages_options = get_option('cs_packages_options');
					if(isset($cs_packages_options) && is_array($cs_packages_options) && count($cs_packages_options)>0){
						foreach($cs_packages_options as $package_key=>$package){
							if(isset($package_key) && $package_key <> ''){
								$package_id = $package['package_id'];
								$package_title = $package['package_title'];
								
								if($package_id <> '' && $package_title <> ''){
									$selected = '';
									if(in_array($package_id, $directory_pakcages)){
										$selected = 'selected="selected"';
									}
									echo '<option value="'.$package_id.'" '.$selected.'>' . $package_title . '</option>';
								}
							}
						}
					}
                    ?>
                </select>
            </li>
        </ul>
		<?php
	}
	}
	/*
	*Rating Section
	*/
	if(!function_exists('cs_rating_section')){
		function cs_rating_section(){
			global $post, $rating_id, $counter_rating, $rating_title, $rating_slug, $pagenow;
			$cs_rating_options = get_post_meta($post->ID, 'cs_rating_meta', true);
			if($pagenow == 'post-new.php'){
				$rating_id = time();
				$rating_title = 'Rating';
				$rating_slug = sanitize_title($rating_title).'_rating_'.$rating_id;
				$cs_rating_options = array($rating_id => array('rating_id'=>$rating_id, 'rating_title'=>$rating_title, 'rating_slug'=>$rating_slug));
			}
			?>
			<input type="hidden" name="dynamic_directory_rating" value="1" />
			<script>
                jQuery(document).ready(function($) {
                    $("#total_packages").sortable({
                        cancel : 'td div.table-form-elem'
                    });
                });
             </script>
             <div class="maininn">
              <div class="main-title">
                    <h3><?php _e('Click to add rating type','directory')?></h3>
                    <a href="javascript:_createpop('add_rating_title','filter')"><i class="icon-plus7"></i> <?php _e('ADD RATING','directory')?></a>
               </div>
              <div class="cs-list-table innertable">
              <table class="to-table" border="0" cellspacing="0">
                <thead>
                  <tr>
                    <th style="width:80%;"><?php _e('Title','directory')?></th>
                    <th style="width:80%;" class="centr"><?php _e('Actions','directory')?></th>
                    <th style="width:0%;" class="centr"></th>
                  </tr>
                </thead>
                <tbody id="total_ratings">
                  <?php
				  		if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
							foreach($cs_rating_options as $rating_key=>$rating){
								if(isset($rating_key) && $rating_key <> ''){
									$counter_rating = $rating_id = $rating['rating_id'];
									$rating_title = $rating['rating_title'];
									$rating_slug = $rating['rating_slug'];
									cs_add_rating_to_list();

								}
							}
						}
                 ?>
                </tbody>
              </table>
              </div>
              <div id="add_rating_title" style="display: none;">
                <div class="cs-heading-area">
                  <h5> <i class="icon-plus-circle"></i> <?php _e('Rating Settings','directory')?> </h5>
                  <span class="cs-btnclose" onClick="javascript:removeoverlay('add_rating_title','append')"> <i class="icon-times"></i></span> </div>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Title', 'directory');?></label>
                  </li>
                  <li class="to-field">
                    <input type="text" id="rating_title" name="rating_title" value="Title" />
                  </li>
                </ul>
                <ul class="form-elements noborder">
                  <li class="to-label"></li>
                  <li class="to-field">
                    <input type="button" value="Add Rating to List" onClick="add_rating_to_list('<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>')" />
                  </li>
                </ul>
              </div>
              </div>
            <?php
		}
	}
	if(!function_exists('cs_add_rating_to_list')){
		function cs_add_rating_to_list(){
			global $counter_rating, $rating_id, $rating_title, $rating_slug;
			foreach ($_POST as $keys=>$values) {
				$$keys = $values;
			}
			
			if(isset($_POST['rating_title']) && $_POST['rating_title'] <> ''){
				$rating_id = time().rand(34563,34323990);
				$rating_slug = 'rating_'.$rating_id;
			}
			
			if(empty($rating_id)){
				$rating_id = $counter_rating;
			}
			
			if(empty($rating_slug)){
				$rating_id = time().rand(34563,34323990);
				$rating_slug = 'rating_'.$rating_id;
			}
			
			?>
            <tr class="parentdelete" id="edit_track<?php echo esc_attr($counter_rating)?>">
              <td id="subject-title<?php echo esc_attr($counter_rating)?>" style="width:80%;"><?php echo esc_attr($rating_title);?></td>
              <td class="centr" style="width:20%;"><a href="javascript:_createpop('edit_track_form<?php echo esc_js($counter_rating)?>','filter')" class="actions edit">&nbsp;</a> <a href="#" class="delete-it btndeleteit actions delete">&nbsp;</a></td>
              <td style="width:0"><div  id="edit_track_form<?php echo esc_attr($counter_rating);?>" style="display: none;" class="table-form-elem">
              	  <input type="hidden" name="rating_id_array[]" value="<?php echo cs_allow_special_char($rating_id);?>" />
                  <input type="hidden" name="rating_slug_array[]" value="<?php echo cs_allow_special_char($rating_slug);?>" />
                  <div class="cs-heading-area">
                    <h5 style="text-align: left;"> <?php _e('Rating Settings','directory')?></h5>
                    <span onclick="javascript:removeoverlay('edit_track_form<?php echo esc_js($counter_rating)?>','append')" class="cs-btnclose"> <i class="icon-times"></i></span>
                    <div class="clear"></div>
                  </div>
                  <ul class="form-elements">
                    <li class="to-label">
                      <label><?php _e('Rating Title','directory')?></label>
                    </li>
                    <li class="to-field">
                      <input type="text" name="rating_title_array[]" value="<?php echo htmlspecialchars($rating_title)?>" id="rating_title<?php echo esc_attr($counter_rating)?>" />
                    </li>
                  </ul>
                  <ul class="form-elements noborder">
                    <li class="to-label">
                      <label></label>
                    </li>
                    <li class="to-field">
                      <input type="button" value="Update Rating" onclick="update_title(<?php echo esc_js($counter_rating);?>); removeoverlay('edit_track_form<?php echo esc_js($counter_rating);?>','append')" />
                    </li>
                  </ul>
                </div></td>
            </tr>
			<?php
			if ( isset($_POST['rating_title']) && isset($_POST['cs_add_rating_to_list']) ) die();
	}
		add_action('wp_ajax_cs_add_rating_to_list', 'cs_add_rating_to_list');
	}
	/*
	*Featured Section
	*/
	if(!function_exists('cs_feature_section')){
		function cs_feature_section(){
				global $post, $feature_id, $counter_feature, $feature_title, $feature_slug, $pagenow;
				$cs_feature_options = get_post_meta($post->ID, 'cs_feature_meta', true);
				if($pagenow == 'post-new.php'){
					$feature_id = time();
					$feature_title = 'Feature';
					$feature_slug = sanitize_title($feature_title).'_feature_'.$feature_id;
					$cs_feature_options = array($feature_id => array('feature_id'=>$feature_id, 'feature_title'=>$feature_title, 'feature_slug'=>$feature_slug));
				}
				?>
				<input type="hidden" name="dynamic_directory_feature" value="1" />
				<script>
					jQuery(document).ready(function($) {
						$("#total_packages").sortable({
							cancel : 'td div.table-form-elem'
						});
					});
				 </script>
				 <div class="maininn">
				  <div class="main-title">
						<h3><?php _e('CLICK TO ADD Feature','directory')?></h3>
						<a href="javascript:_createpop('add_feature_title','filter')"><i class="icon-plus7"></i> <?php _e('ADD Feature','directory')?></a>
				   </div>
				  <div class="cs-list-table innertable">
				  <table class="to-table" border="0" cellspacing="0">
					<thead>
					  <tr>
						<th style="width:80%;"><?php _e('Title','directory')?></th>
						<th style="width:80%;" class="centr"><?php _e('Actions','directory')?></th>
						<th style="width:0%;" class="centr"></th>
					  </tr>
					</thead>
					<tbody id="total_features">
					  <?php
						if(isset($cs_feature_options) && is_array($cs_feature_options) && count($cs_feature_options)>0){
							foreach($cs_feature_options as $feature_key=>$feature){
								if(isset($feature_key) && $feature_key <> ''){
									$counter_feature = $feature_id = $feature['feature_id'];
									$feature_title 	= $feature['feature_title'];
									$feature_slug 	= $feature['feature_slug'];
									cs_add_feature_to_list();
								}
							}
						}
					  ?>
					</tbody>
				  </table>
				  </div>
				  <div id="add_feature_title" style="display: none;">
					<div class="cs-heading-area">
					  <h5> <i class="icon-plus-circle"></i> <?php _e('Rating Settings','directory')?> </h5>
					  <span class="cs-btnclose" onClick="javascript:removeoverlay('add_feature_title','append')"> <i class="icon-times"></i></span> </div>
					<ul class="form-elements">
					  <li class="to-label">
						<label><?php _e('Title','directory')?></label>
					  </li>
					  <li class="to-field">
						<input type="text" id="feature_title" name="feature_title" value="Title" />
					  </li>
					</ul>
					<ul class="form-elements noborder">
					  <li class="to-label"></li>
					  <li class="to-field">
						<input type="button" value="Add Feature to List" onClick="add_feature_to_list('<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>')" />
					  </li>
					</ul>
				  </div>
				  </div>
				<?php
			}	
	}
	
	if(!function_exists('cs_add_feature_to_list')){
		function cs_add_feature_to_list(){
			global $counter_feature, $feature_id, $feature_title, $feature_slug;
			foreach ($_POST as $keys=>$values) {
				$$keys = $values;
			}
			if(isset($_POST['feature_title']) && $_POST['feature_title'] <> ''){
				$feature_id = time();
				$feature_slug = str_replace(' ', '_', $feature_title).'_feature_'.$feature_id;
			}
			if(empty($feature_id)){
				$feature_id = $counter_feature;
			}
			if(empty($feature_slug)){
				$feature_slug = str_replace(' ', '_', $feature_title).'_feature_'.$feature_id;
			}
			?>
            <tr class="parentdelete" id="edit_track<?php echo esc_attr($counter_feature)?>">
              <td id="subject-title<?php echo esc_attr($counter_feature)?>" style="width:80%;"><?php echo esc_attr($feature_title);?></td>
              <td class="centr" style="width:20%;"><a href="javascript:_createpop('edit_track_form<?php echo esc_js($counter_feature)?>','filter')" class="actions edit">&nbsp;</a> <a href="#" class="delete-it btndeleteit actions delete">&nbsp;</a></td>
              <td style="width:0"><div  id="edit_track_form<?php echo esc_attr($counter_feature);?>" style="display: none;" class="table-form-elem">
              	  <input type="hidden" name="feature_id_array[]" value="<?php echo cs_allow_special_char($feature_id);?>" />
                  <input type="hidden" name="feature_slug_array[]" value="<?php echo cs_allow_special_char($feature_slug);?>" />
                  <div class="cs-heading-area">
                    <h5 style="text-align: left;"> <?php _e('feature Settings','directory'); ?></h5>
                    <span onclick="javascript:removeoverlay('edit_track_form<?php echo esc_js($counter_feature)?>','append')" class="cs-btnclose"> <i class="icon-times"></i></span>
                    <div class="clear"></div>
                  </div>
                  <ul class="form-elements">
                    <li class="to-label">
                      <label><?php _e('Feature Title','directory')?></label>
                    </li>
                    <li class="to-field">
                      <input type="text" name="feature_title_array[]" value="<?php echo htmlspecialchars($feature_title)?>" id="feature_title<?php echo esc_attr($counter_feature)?>" />
                    </li>
                  </ul>
                  <ul class="form-elements noborder">
                    <li class="to-label">
                      <label></label>
                    </li>
                    <li class="to-field">
                      <input type="button" value="Update feature" onclick="update_title(<?php echo esc_js($counter_feature);?>); removeoverlay('edit_track_form<?php echo esc_js($counter_feature);?>','append')" />
                    </li>
                  </ul>
                </div></td>
            </tr>
			<?php
			if ( isset($_POST['feature_title']) && isset($_POST['cs_add_feature_to_list']) ) die();
	}
		add_action('wp_ajax_cs_add_feature_to_list', 'cs_add_feature_to_list');
	}
	/*
	*Save Directory Types Data
	*/
	if(!function_exists('cs_dynamic_custom_post_type_save_postdata')){
		function cs_dynamic_custom_post_type_save_postdata(){
		global $post;
		if (isset($_POST['dcpt-hidd']) && $_POST['dcpt-hidd'] == 'true') {
			if(isset($_REQUEST['dcpt_options'])){
				foreach ( $_REQUEST['dcpt_options'] as $keys=>$values) {
					if(is_array($values)){
						$values = implode(",", $values);
					}
					update_post_meta($post->ID, $keys, $values);
				}
			}
			
			//update_post_meta( $post->ID, 'cs_video_url', $_POST['cs_video_url'] );
			
			if ( isset($_POST['directory_types_categories'])){
				$directory_types_categories = $_POST['directory_types_categories'];
				$directory_types_categories = implode(",", $directory_types_categories);
				update_post_meta($post->ID, 'directory_types_categories', $directory_types_categories);
			}
			if ( isset($_POST['directory_types_packages'])){
				$directory_types_packages = $_POST['directory_types_packages'];
				$directory_types_packages = implode(",", $directory_types_packages);
				update_post_meta($post->ID, 'directory_types_packages', $directory_types_packages);
			}
			if(isset($_POST['dynamic_directory_rating']) && $_POST['dynamic_directory_rating'] == 1){
				$rating_counter = 0;
				$rating_array = $ratings = array();
				foreach($_POST['rating_id_array'] as $keys=>$values){
					if($values){
						$rating_array['rating_id'] = $_POST['rating_id_array'][$rating_counter];
						$rating_array['rating_title'] = $_POST['rating_title_array'][$rating_counter];
						$rating_array['rating_slug'] = $_POST['rating_slug_array'][$rating_counter];
						$ratings[$values] = $rating_array;
						$rating_counter++;
					}
				}
				update_post_meta($post->ID, 'cs_rating_meta', $ratings);
			}
			
			if(isset($_POST['dynamic_directory_feature']) && $_POST['dynamic_directory_feature'] == 1){
				$feature_counter = 0;
				$feature_array = $features = array();
				foreach($_POST['feature_id_array'] as $keys	=> $val){
					if($val){
						$feature_array['feature_id'] = $_POST['feature_id_array'][$feature_counter];
						$feature_array['feature_title'] = $_POST['feature_title_array'][$feature_counter];
						$feature_array['feature_slug'] = $_POST['feature_slug_array'][$feature_counter];
						$features[$val] = $feature_array;
						$feature_counter++;
					}
				}
				
				
				update_post_meta($post->ID, 'cs_feature_meta', $features);
			}
		}
	}
	}