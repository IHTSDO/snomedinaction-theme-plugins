<?php
/*
*Directory Post Create Functions
*/
/*
*Directory Post Fields functions by ajax call on directory type selection
*/
if ( ! function_exists( 'cs_directory_fields' ) ) {
	function cs_directory_fields(){
		global $post,$cs_theme_options, $cs_page_id, $pagenow;
 		$post_id = '';
		if(isset($_REQUEST['directory_id']) && $_REQUEST['directory_id'] <> ''){
	
			$front_page 			= $_REQUEST['front_page'];
			$post_id    			= $_REQUEST['post_id'];
			$directory_id 			= $_REQUEST['directory_id'];
			$cs_feature_options 	= get_post_meta((int)$directory_id, 'cs_feature_meta', true);
			
				$meta_options 		= cs_directory_custom_options_array();
					if(is_array($meta_options)){
						foreach( $meta_options['params'] as $table_key=>$tablerows ) {
							$field_title = $tablerows['title'];
							foreach( $tablerows as $key=>$param ) {
								if($key == 'title')
									continue;
								if(is_array($param)){
									$key_input = $key;
									if($param['type'] == 'checkbox'){
										$meta_option_on = get_post_meta($directory_id, $key, true);
										if($meta_option_on == 'on'){
											$$key = $meta_option_on;
										}
									}
									if($param['type'] == 'text'){
										$keyinputtitle = get_post_meta($directory_id, $key, true);
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
                	<li class="to-label"><label for="categories"><?php _e('Categories','directory')?></label></li>
                    
                        
                        <?php
						
						$directory_categories = array();
						$directory_categories_array = get_the_terms( $post_id, 'directory-category' );
						if(isset($directory_categories_array) && is_array($directory_categories_array)){
							foreach($directory_categories_array as $categoryy){
								$directory_categories[] = $categoryy->term_id;
							}
						}
						
                        $directory_categories_array = get_post_meta($directory_id, "directory_types_categories", true);
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
                        <select id="categories" class="multiselect" <?php echo isset( $multiple ) ? $multiple : '';?>  name="directory_categories[]">
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
                    </li>
                    <?php 
					$cs_post_multi_imgs_option = isset($cs_post_multi_imgs_option) ? $cs_post_multi_imgs_option : 'off';
					$cs_multiple_images_input = isset($cs_multiple_images_input) ? $cs_multiple_images_input : '0';
					echo '<input type="hidden" name="multi_imgs_option_allow" value="'.$cs_post_multi_imgs_option.'" id="multi_imgs_option_allow" class="multi_imgs_option_allow_class" />';
					echo '<input type="hidden" name="multi_imgs_allow_no" value="'.$cs_multiple_images_input.'" id="multi_imgs_allow_no" class="multi_imgs_allow_no_class" />';
					$cs_post_multi_tags_option = isset($cs_post_multi_tags_option) ? $cs_post_multi_tags_option : 'off';
					$cs_multiple_tags_input = isset($cs_multiple_tags_input) ? $cs_multiple_tags_input : '0';
					echo '<input type="hidden" name="multi_tags_option_allow" value="'.$cs_post_multi_tags_option.'" id="multi_tags_option_allow" class="multi_tags_option_allow_class" />';
					echo '<input type="hidden" name="multi_tags_allow_no" value="'.$cs_multiple_tags_input.'" id="multi_tags_allow_no" class="multi_tags_allow_no_class" />';
					if(isset($cs_post_multi_tags_option) && $cs_post_multi_tags_option == 'on' && $front_page == 'Front'){
						$directory_tags = '';
						$directory_tags_array = get_the_terms( $post_id, 'directory-tag' );
						if(isset($directory_tags_array) && is_array($directory_tags_array) && count($directory_tags_array)>0)
							foreach($directory_tags_array as $directorytag){
								$directory_tags .= $directorytag->name.', ';
							}
					?>
                    <li class="first_name">
                        <label for="first_name"><?php _e('Tags','directory')?></label>
                        <div class="inner-sec">
                            <span class="icon-input">
                                <a href="#" id="csload_list"><i class="icon-plus"></i></a>
                                <input id="csappend" type="text" value="" class="text-input multiple-tags-class">
                                <input id="csappend_hidden" name="directory_tags" type="hidden" value="<?php if(isset($directory_tags)) echo esc_attr($directory_tags);?>">
                            </span>
                            <ul class="cs-tags-selection">
                                <?php
                                    if(isset($directory_tags) && !empty($directory_tags)){
                                        $directory_tags = explode(',',$directory_tags);
                                        foreach($directory_tags as $tag_value){
                                            if(!empty($tag_value) && trim($tag_value) <> ''){
												echo '<li class="alert alert-warning"><a href="#" class="close" data-dismiss="alert">×</a> <span>'.$tag_value.'</span></li>';
                                            }
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                      </li>
                    <?php
					}
					?>
                </ul>
                <?php if (  isset( $post_video_switch ) && $post_video_switch == 'on' ) {?>
                <div class="theme-help">
                    <h4><?php echo __('Add Video','directory'); ?></h4>
                    <div class="clear"></div>
                </div>
                <ul class="form-elements">
                	<li class="to-label"><label><?php echo __('Video url','directory'); ?></label></li>
                    <li class="to-field">
                        <div class="input-sec">
                             <input type="text"  placeholder="URL" class="text-input" value="" name="cs_video_url">
                        </div>
                        <div class="left-info">
                            <p><?php echo __('You can add Youtube, Vimeo, Dailymotion Videos url etc..','directory'); ?></p>
                        </div>
                    </li>
                </ul>
                <?php }?>
				<?php
				// Fields
				if(isset($cs_post_price_saleprice_option) && $cs_post_price_saleprice_option == 'on'){
					
				?>
					<div class="theme-help">
                        <h4><?php _e('Price','directory');?></h4>
                        <div class="clear"></div>
                    </div>
					<?php cs_sale_fields($post_id);?>
				<?php } ?>
                
                <?php if(isset($cs_feature_options) && is_array($cs_feature_options) && count($cs_feature_options)>0){ ?>
                <ul class="form-elements">
                    <li class="to-label"><label><?php _e('Feature List','directory');?></label></li>
                    <li class="to-field">
                    	<?php 
						foreach($cs_feature_options as $feature_key=>$feature){
							if(isset($feature_key) && $feature_key <> ''){
								$counter_feature = $feature_id = $feature['feature_id'];
								$feature_title 	 = $feature['feature_title'];
								$feature_slug 	 = $feature['feature_slug'];
								
								echo '<div class="cs-feature-list cs-checkbox checkbox-inline">
								<input type="checkbox" name="dir_cusotm_field[cs_feature_list][]" value="'.$feature_slug.'" />';
								echo '<label>'.esc_attr( $feature_title ).'</label>';
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
                            cs_post_openinghours( $post_id );
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
                 <li class="to-field"><input class="text-input" name="dir_cusotm_field[user_landline]" type="text" id="landline" value="" />
                 </li>
                </ul>
                <ul class="form-elements">
                 <li class="to-label"><label><?php _e('Mobile', 'directory'); ?></label></li>
                 <li class="to-field"><input class="text-input" name="dir_cusotm_field[user_mobile]" type="text" id="mobile" value="" />
                 </li>
                </ul>
                <ul class="form-elements">
                 <li class="to-label"><label><?php _e('Email Address', 'directory'); ?></label></li>
                 <li class="to-field"><input class="text-input" name="dir_cusotm_field[user_email]" type="text" id="email" value="" />
                 </li>
                 </ul>
                 <ul class="form-elements">
                 <li class="to-label"><label><?php _e('Skype', 'directory'); ?></label></li>
                  <li class="to-field"><input class="text-input" name="dir_cusotm_field[user_skype]" type="text" id="skype" value="" />
                  </li>
                  </ul>
                  <ul class="form-elements">
                  <li class="to-label"><label><?php _e('website', 'directory'); ?></label></li>
                  <li class="to-field"><input class="text-input" name="dir_cusotm_field[user_website]" type="text" id="website" value="" />
                  </li>
               </ul>  
			   <?php 
				if(isset($cs_post_faqs_option) && $cs_post_faqs_option == 'on'){
				?>
                	<div class="theme-help">
                        <h4><?php _e('FAQS','directory');?></h4>
                        <div class="clear"></div>
                    </div>
					<ul class="form-elements">
						<li>
						  <?php cs_faqs_section($post_id); ?>
						</li>
					  </ul>
				<?php
				}
				if(isset($cs_post_location_option) && $cs_post_location_option == 'on'){
					if ( function_exists( 'cs_location_fields' ) ) {
						?>
                        <div class="theme-help">
                            <h4><?php _e('Locations','directory');?></h4>
                            <div class="clear"></div>
                        </div>
                        <?php
						cs_location_fields($post_id);
					}
				}
				$custom_fields = '';
				$cs_directory_custom_fields = get_post_meta($directory_id, "cs_directory_custom_fields", true);
				if ( $cs_directory_custom_fields <> "" ) {
					$cs_customfields_object = new SimpleXMLElement($cs_directory_custom_fields);
					if(isset($cs_customfields_object->custom_fields_elements) && $cs_customfields_object->custom_fields_elements == '1'){
						if(count($cs_customfields_object)>1){
							echo '<div class="theme-help">
										<h4>'.__('Custom Features','directory').'</h4>
										<div class="clear"></div>
									</div>';	
							global $post,$cs_node;
							foreach ( $cs_customfields_object->children() as $cs_node ){
								//$custom_fields .= '<div class="pbwp-form-rows">';
									$custom_fields .= cs_custom_fields_render('','',$post_id);
								//$custom_fields .= '</div>';
							}
						}
					}
				}
				echo '<div class="pbwp-form-holder">';
					echo balanceTags($custom_fields, false);
				echo '</div>';
		}
		die();
	}
	add_action('wp_ajax_cs_directory_fields', 'cs_directory_fields');
}
/*
*Render Dynamic Post Custom Fields used when we create directory
*/
if ( ! function_exists( 'cs_custom_fields_render' ) ) {
	function cs_custom_fields_render($key='', $param='', $post_id='') {
		global $post,$cs_node,$cs_xmlObject;
		if( isset( $post_id ) && $post_id !='' ){
			$post_id	= $post_id;
		} else if(isset($post->ID)){
			$post_id = $post->ID;
		}
		$cs_value = '';
		$html = '';
		$cs_customfield_required = '';
		if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required == 'yes'){
			$cs_customfield_required = 'required';
		}
		$output = '';
		switch( $cs_node->getName() )
		{
			case 'text' :
				// prepare
				if( isset( $cs_xmlObject ) ){
					$key = $cs_node->cs_customfield_name;
					if ( isset( $key ) && $key !='' ) {
						$cs_value = get_post_meta($post_id, "$key", true);
					}
				} else {
					$cs_value = $cs_node->cs_customfield_default;
				}
				
				$output .= '<ul class="form-elements 222">';
				$output .= '<li class="to-label"><label>'.$cs_node->cs_customfield_label.'</label></li>';
				$output .= '<li class="to-field"><div class="input-sec"><input type="text" placeholder="' . $cs_node->cs_customfield_placeholder . '" class="cs-form-text cs-input" name="dir_cusotm_field[' . $cs_node->cs_customfield_name . ']" id="' . $cs_node->cs_customfield_name . '" value="' . $cs_value . '" '.$cs_customfield_required.' /></div>' . "\n";
				$output .= '<div class="left-info"><p>' . $cs_node->cs_customfield_help . '</p></div>' . "\n</li>";
				$output .= '</ul>';
				// append
				$html .= $output;
				break;
			case 'email' :
				// prepare
				
				if(isset($cs_xmlObject)){
					$key = $cs_node->cs_customfield_name;
					if ( isset( $key ) && $key !='' )
						$cs_value = get_post_meta($post_id, "$key", true);
				} else {
					$cs_value = $cs_node->cs_customfield_default;
				}
				$output .= '<ul class="form-elements">';
				$output .= '<li class="to-label"><label>'.$cs_node->cs_customfield_label.'</label></li>';
				$output .= '<li class="to-field"><div class="input-sec"><input type="text" size="'. $cs_node->cs_customfield_size . '" placeholder="' . $cs_node->cs_customfield_placeholder . '" class="cs-form-text cs-input" name="dir_cusotm_field[' . $cs_node->cs_customfield_name . ']" id="' . $cs_node->cs_customfield_name . '" value="' . $cs_value . '" '.$cs_customfield_required.' /></div>' . "\n";
				$output .= '<div class="left-info"><p>' . $cs_node->cs_customfield_help . '</p></div>' . "\n</li>";
				$output .= '</ul>';
				// append
				$html .= $output;
				break;
			case 'url' :
				// prepare
				if(isset($cs_xmlObject)){
					$key = $cs_node->cs_customfield_name;
					if ( isset( $key ) && $key !='' )
						$cs_value = get_post_meta($post_id, "$key", true);
				} else {
					$cs_value = $cs_node->cs_customfield_default;
				}
				$output .= '<ul class="form-elements">';
				$output .= '<li class="to-label"><label>'.$cs_node->cs_customfield_label.'</label></li>';
				$output .= '<li class="to-field"><input type="text" '.$cs_customfield_required.' placeholder="' . $cs_node->cs_customfield_placeholder . '" class="cs-form-text cs-input" name="dir_cusotm_field[' . $cs_node->cs_customfield_name . ']" id="' . $cs_node->cs_customfield_name . '" value="' . $cs_value . '" />' . "\n";
				$output .= '<div class="left-info"><p>' . $cs_node->cs_customfield_help . '</p></div>' . "\n</li>";
				$output .= '</ul>';
				// append
				$html .= $output;
				break;
			
			case 'date' :
				// prepare
				if(isset($cs_xmlObject)){
					$key = $cs_node->cs_customfield_name;
					if ( isset( $key ) && $key !='' )
						$cs_value = get_post_meta($post_id, "$key", true);
				} else {
					$cs_value = $cs_node->cs_customfield_default;
				}
				
				if(!isset($cs_node->cs_customfield_name) || $cs_node->cs_customfield_name == ''){
					$cs_node->cs_customfield_name = 'date'.$post_id;	
				}
				if(!isset($cs_node->cs_customfield_format) || $cs_node->cs_customfield_format == ''){
					$cs_node->cs_customfield_format = 'Y/m/d';	
				}
				$output .= '<ul class="form-elements">';
				$output .= '<script>
					jQuery(function($) {
						 jQuery("#' . $cs_node->cs_customfield_name . '").datetimepicker({
							  format:"' . $cs_node->cs_customfield_format . '",
							  timepicker: false
						 });
					});
				</script>';
				$output .= '<li class="to-label"><label>'.$cs_node->cs_customfield_label.'</label></li>';
				$output .= '<li class="to-field"><div class="input-sec"><input '.$cs_customfield_required.' type="text" size="'. $cs_node->cs_customfield_size . '" placeholder="' . $cs_node->cs_customfield_placeholder . '" class="cs-form-text cs-input '.$cs_node->cs_customfield_css.' " name="dir_cusotm_field[' . $cs_node->cs_customfield_name . ']" id="' . $cs_node->cs_customfield_name . '" value="' . $cs_value . '" />' . "\n";
				$output .= '<div class="left-info"><p> Date Fromat: ' . $cs_node->cs_customfield_format . '</p></div>' . "\n</li>";
				$output .= '</ul>';
				// append
				$html .= $output;
				break;
			case 'multiselect' :
				// prepare
				if(isset($cs_xmlObject)){
					$key = trim($cs_node->cs_customfield_name);
					if ( isset( $key ) && $key !='' ){
						$cs_value = get_post_meta($post_id, "$key", true);
						$cs_value = explode(',',$cs_value);
					}
				} else {
					$cs_value = $cs_node->cs_customfield_default;
				}

				// prepare
				$output .= '<ul class="form-elements">';
				$output .= '<li class="to-label"><label>'.$cs_node->cs_customfield_label.'</label></li><li class="to-field"><div class="input-sec">';
				$multiselect_counter = 0;
				$output .= '<select style="min-height:100px;" name="dir_cusotm_field[' . $cs_node->cs_customfield_name . '][]" id="' . $cs_node->cs_customfield_name . '" class="cs-form-select cs-input '.$cs_node->cs_customfield_css.'" multiple="multiple">' . "\n";
				$options_values = array();
				if(isset($cs_node->options_values))
					$options_values = $cs_node->options_values;
					
				
				foreach( $cs_node->options as $value => $option )
				{
					$selected = '';
					$options_val = $options_values[$multiselect_counter];
					$selected = '';
					if(is_array($cs_value) && in_array($options_val, $cs_value)) $selected = 'selected="selected"';
					$output .= '<option '.$selected.' value="' . $cs_node->options_values[$multiselect_counter] . '">' . $option . '</option>' . "\n";
					$multiselect_counter++;
				}
				$output .= '</select></div>' . "\n";
				$output .= '<div class="left-info"><p>' . $cs_node->cs_customfield_help . '</p></div>' . "\n</li>";
				$output .= '</ul>';
				// append
				$html .= $output;
				break;
			case 'textarea' :
				if(isset($cs_xmlObject)){
					$key = $cs_node->cs_customfield_name;
					if ( isset( $key ) && $key !='' ) 
						$cs_value = get_post_meta($post_id, "$key", true);
				} else {
					$cs_value = $cs_node->cs_customfield_default;
				}
				// prepare
				$output .= '<ul class="form-elements">';
				$output .= '<li class="to-label"><label>'.$cs_node->cs_customfield_label.'</label></li>';
				$output .= '<li class="to-field"><div class="input-sec"><textarea '.$cs_customfield_required.' rows="'.$cs_node->cs_customfield_rows.'" cols="'.$cs_node->cs_customfield_cols.'" name="dir_cusotm_field[' . $cs_node->cs_customfield_name . ']" id="' . $cs_node->cs_customfield_name . '" class="cs-form-textarea cs-input '.$cs_node->cs_customfield_css.'">' . $cs_value . '</textarea></div>' . "\n";
				$output .= '<div class="left-info"><p>' . $cs_node->cs_customfield_help . '</p></div>' . "\n</li>";
				$output .= '</ul>';
				// append
				$html .= $output;
				break;
			case 'range' :
				// prepare
				if( isset( $cs_xmlObject ) ){
					$key = $cs_node->cs_customfield_name;
					if ( isset( $key ) && $key !='' )
						$cs_value = get_post_meta($post_id, "$key", true);
				} else {
						$cs_value = $cs_node->cs_customfield_default;
				}
				
				$output .= '<ul class="form-elements">';
				$output .= '<li class="to-label"><label>'.$cs_node->cs_customfield_label.'</label></li>';
				$output .= '<li class="to-field"><div class="input-sec"><input type="text" placeholder="' . $cs_node->cs_customfield_placeholder . '" class="cs-form-text cs-input" name="dir_cusotm_field[' . $cs_node->cs_customfield_name . ']" id="' . $cs_node->cs_customfield_name . '" value="' . $cs_value . '" '.$cs_customfield_required.' /></div>' . "\n";
				$output .= '<div class="left-info"><p>' . $cs_node->cs_customfield_help . '</p></div>' . "\n</li>";
				$output .= '</ul>';
				// append
				$html .= $output;
				break;
			case 'dropdown' :
				
				$cs_multiselect	= 'false';
				if(isset($cs_xmlObject)){
					$key = $cs_node->cs_customfield_name;
					if ( isset( $key ) && $key !='' )
						$cs_value = get_post_meta($post_id, "$key", true);
						
						if(isset($cs_node->cs_customfield_enable_post_multiselect) && $cs_node->cs_customfield_enable_post_multiselect == 'yes'){
							$cs_value = explode(',',$cs_value);
							$cs_multiselect	= 'true';
						}
						
				} else {
					$cs_value = (string)$cs_node->cs_customfield_default;
				}
				
				
				if(isset($cs_node->cs_customfield_enable_post_multiselect) && $cs_node->cs_customfield_enable_post_multiselect == 'yes'){
					$cs_customfield_enable_multiselect = '[]';
					$multiple = 'multiple="multiple"';
					$class = 'multiselect';
					$cs_categories = 'categories';
				} else {
					$cs_customfield_enable_multiselect = '';
					$multiple = '';
					$class = '';
					$cs_categories = '';
				}
				// prepare
				$output .= '<ul class="form-elements">';
				$output .= '<li class="to-label"><label>'.$cs_node->cs_customfield_label.'</label>';
				$output .= '<li class="to-field '.$cs_categories.'"><div class="input-sec"><select '.$multiple.' '.$cs_customfield_required.' name="dir_cusotm_field[' . $cs_node->cs_customfield_name . ']'.$cs_customfield_enable_multiselect.'" id="' . $cs_node->cs_customfield_name . '" class="cs-form-select cs-input '.$class.'">' . "\n";
				if(isset($cs_node->cs_customfield_first)){$output .= '<option value="">' . $cs_node->cs_customfield_first . '</option>' . "\n";}
				$multiselect_counter = 0;
				$options_values = array();
				if(isset($cs_node->options_values)){
					$options_values = $cs_node->options_values;
				}
				foreach( $cs_node->options as $value => $option )
				{
					$selected = '';
					$options_val = '';
					if(isset($options_values[$multiselect_counter]))
						$options_val = (string)$options_values[$multiselect_counter];
					
					if($options_val == $cs_value) $selected = 'selected="selected"';
					
					if( $cs_multiselect == 'true' ) {
						if(is_array($cs_value) && in_array($options_val, $cs_value)) $selected = 'selected="selected"';
					}
					
					$output .= '<option value="' . $options_val . '" '.$selected.' >' . $option . '</option>' . "\n";
					$multiselect_counter++;
				}
				$output .= '</select></li></div>' . "\n";
				$output .= '<div class="left-info"><p>' . $cs_node->cs_customfield_help . '</p></div>' . "\n</li>";
				$output .= '</ul>';
				// append
				$html .= $output;
				break;
			default :
				break;
		}
		return $html;
	}
}
/*
*Event Custom Fields Functions
*/
if ( ! function_exists( 'cs_cusotm_post_event_fields' ) ) {
	function cs_cusotm_post_event_fields(){
		global $post,$cs_xmlObject;
		$dynamic_post_event_from_date = '';
		$event_organizer = array();
		$post_meta = get_post_meta($post->ID, "cs_directory_meta", true);
		if ( $post_meta <> "" ) {
			$cs_xmlObject = new SimpleXMLElement($post_meta);
			$dynamic_post_event_from_date = get_post_meta($post->ID, "dynamic_post_event_from_date", true);
			if(isset($cs_xmlObject->dynamic_post_event_all_day)){ $dynamic_post_event_all_day = $cs_xmlObject->dynamic_post_event_all_day;} else {$dynamic_post_event_all_day = '';}
		} else {
			$dynamic_post_event_all_day = '';
			$dynamic_post_event_from_date = '';
		}
		$event_organizer = array();
		if(isset($cs_xmlObject->event_organizer))
		$event_organizer = $cs_xmlObject->event_organizer;
		if ($event_organizer){
			$event_organizer = explode(",", $event_organizer);
		}
			
		cs_enqueue_timepicker_script();
		?>
		<script type="text/javascript" src="<?php echo esc_js(get_template_directory_uri().'/include/assets/scripts/ui_multiselect.js');?>"></script>
        <link type="text/css" rel="stylesheet" href="<?php echo esc_js(get_template_directory_uri().'/include/assets/css/jquery_ui.css');?>" />
        <link type="text/css" rel="stylesheet"  href="<?php echo esc_js(get_template_directory_uri().'/include/assets/css/ui_multiselect.css');?>" />
        <link type="text/css" rel="stylesheet"  href="<?php echo esc_js(get_template_directory_uri().'/include/assets/css/common.css');?>" />
		<script type="text/javascript">
			jQuery(function($){
				jQuery(".multiselect").multiselect();
				//jQuery('#switcher').themeswitcher();
			});
			 jQuery(function(){
				 jQuery('#dynamic_post_event_start_time').datetimepicker({
				  datepicker:false,
						format:'H:i',
						formatTime: 'H:i',
						step:30,
				  onSgow:function( at ){
				   this.setOptions({
					maxTime:jQuery('#dynamic_post_event_end_time').val()?jQuery('#dynamic_post_event_end_time').val():false
				   })
				  }
				 });
				 jQuery('#dynamic_post_event_end_time').datetimepicker({
					datepicker:false,
						format:'H:i',
						formatTime: 'H:i',
						step:30,
				  onShow:function( at ){
				   this.setOptions({
					minTime:jQuery('#dynamic_post_event_start_time').val()?jQuery('#dynamic_post_event_start_time').val():false
				   })
				  }
				 });
				 jQuery('#from_date').datetimepicker({
				  format:'Y/m/d',
				  onShow:function( ct ){
				   this.setOptions({
					maxDate:jQuery('#to_date').val()?jQuery('#to_date').val():false
				   })
				  },
				  timepicker:false
				 });
				 jQuery('#to_date').datetimepicker({
				  format:'Y/m/d',
				  onShow:function( ct ){
				   this.setOptions({
					minDate:jQuery('#from_date').val()?jQuery('#from_date').val():false
				   })
				  },
				  timepicker:false
				 });
				});
			</script>
	
	<div class="clear"></div>
	<ul class="form-elements">
	  <li class="to-label">
		<label><?php _e('Event Date','directory');?></label>
	  </li>
	  <li class="to-field short-field">
		<input type="text" id="from_date" name="dynamic_post_event_from_date" value="<?php if(isset($dynamic_post_event_from_date) && $dynamic_post_event_from_date=='') echo gmdate("Y/m/d"); else echo cs_allow_special_char($dynamic_post_event_from_date); ?>" />
	  </li>
 
	</ul>
	<ul class="form-elements event-day bcevent_title">
	  <li class="to-label">
		<label><?php _e('Event Time','directory');?></label>
	  </li>
	  <li class="to-field">
		<div id="event_time" <?php /*?><?php if($dynamic_post_event_all_day=='on')echo 'style="display:none"'?><?php */?>>
		  <div class="input-sec">
			<input id="dynamic_post_event_start_time" name="dynamic_post_event_start_time" value="<?php if(isset($cs_xmlObject->dynamic_post_event_start_time)){echo esc_attr($cs_xmlObject->dynamic_post_event_start_time);} else { echo date('H:i');}?>" type="text" class="vsmall" />
			<label class="first-label"><?php _e('Start time','directory');?></label>
		  </div>
		  <!--<span class="short">To</span>-->
		  <div class="input-sec">
			<input id="dynamic_post_event_end_time" name="dynamic_post_event_end_time" value="<?php if(isset($cs_xmlObject->dynamic_post_event_start_time)){echo esc_attr($cs_xmlObject->dynamic_post_event_end_time);} else { echo date('H:i');}?>" type="text" class="vsmall"  />
			<label class="sec-label"><?php _e('End time','directory');?></label>
		  </div>
		  <div class="input-sec">
			<div class="checkbox-list">
			  <div class="checkbox-item">
				<input type="checkbox" name="dynamic_post_event_all_day" value="on" <?php if(isset($cs_xmlObject->dynamic_post_event_all_day) && $cs_xmlObject->dynamic_post_event_all_day == 'on'){echo "checked";}?>  class="styled" />
			  </div>
			</div>
			<label><?php _e('AllDay','directory');?></label>
		  </div>
		</div>
	  </li>
	</ul>
	<?php if ( empty( $_GET['post']) ) {?>
    
	<ul class="form-elements">
	  <li class="to-label">
		<label><?php _e('Repeat','directory');?></label>
	  </li>
	  <li class="to-field">
		<div class="input-sec">
		  <div class="select-style">
			<select name="dynamic_post_event_repeat" class="dropdown" onchange="toggle_with_value('num_repeat', this.value)">
			  <option value="0"><?php _e('-- Never Repeat --','directory');?></option>
			  <option value="+1 day"><?php _e('Every Day','directory');?></option>
			  <option value="+1 week"><?php _e('Every Week','directory');?></option>
			  <option value="+1 month"><?php _e('Every Month','directory');?></option>
			</select>
		  </div>
		</div>
	  </li>
	</ul>
	<ul class="form-elements" id="num_repeat" style="display:none">
	  <li class="to-label">
		<label><?php _e('Repeat how many time','directory');?></label>
	  </li>
	  <li class="to-field">
		<div class="input-sec">
		  <div class="select-style">
			<select name="dynamic_post_event_num_repeat" class="dropdown">
			  <?php for ( $i = 1; $i <= 25; $i++ ) {?>
			  <option><?php echo absint($i)?></option>
			  <?php }?>
			</select>
		  </div>
		</div>
	  </li>
	</ul>
	<?php } ?>
	<div class="clear"></div>
	<ul class="form-elements bcevent_title">
	  <li class="to-label">
		<label><?php _e('Ticket Option','directory');?></label>
	  </li>
	  <li class="to-field">
		<div class="input-sec">
		  <input type="text" id="dynamic_post_event_ticket_options" name="dynamic_post_event_ticket_options" value="<?php if(isset($cs_xmlObject->dynamic_post_event_ticket_options)){echo esc_attr($cs_xmlObject->dynamic_post_event_ticket_options);}?>" />
		  <label><?php _e('Title','directory');?></label>
		</div>
		<div class="input-sec">
		  <input type="text" id="dynamic_post_event_buy_now" name="dynamic_post_event_buy_now" value="<?php if(isset($cs_xmlObject->dynamic_post_event_buy_now)){echo esc_attr($cs_xmlObject->dynamic_post_event_buy_now);}?>" />
		  <label><?php _e('Url','directory');?></label>
		</div>
		<div class="input-sec">
		  <input type="text" name="dynamic_post_event_ticket_color" value="<?php if(isset($cs_xmlObject->dynamic_post_event_ticket_color)){echo esc_attr($cs_xmlObject->dynamic_post_event_ticket_color);}?>" class="bg_color" />
		  <label><?php _e('Color','directory');?></label>
		</div>
	  </li>
	</ul><div class="clear"></div>
    
    <ul class="form-elements">
	  <li class="to-label">
		<label><?php _e('Contact No','directory');?></label>
	  </li>
	  <li class="to-field">
		<div class="input-sec">
		  <input type="text" id="dynamic_post_event_contact_no" name="dynamic_post_event_contact_no" value="<?php if(isset($cs_xmlObject->dynamic_post_event_contact_no)){echo esc_attr($cs_xmlObject->dynamic_post_event_contact_no);}?>" />
	
		</div>
	  </li>
	</ul>
    
	<div class="clear"></div>
    <ul class="form-elements">
	  <li class="to-label">
		<label><?php _e('Email','directory');?></label>
	  </li>
	  <li class="to-field">
		<div class="input-sec">
		  <input type="text" id="dynamic_post_event_email" name="dynamic_post_event_email" value="<?php if(isset($cs_xmlObject->dynamic_post_event_email)){echo esc_attr($cs_xmlObject->dynamic_post_event_email);}?>" />
		</div>
	  </li>
	</ul>
	<div class="clear"></div>
	<ul class="form-elements">
	  <li class="to-label">
		<label><?php _e('Event Detail','directory');?></label>
	  </li>
	  <li class="to-field">
		<div class="input-sec">
		  <div class="select-style">
			<select name="dynamic_post_event_content_view" id="dynamic_post_event_content_view" >
			  <option value="in_post" <?php if(isset($cs_xmlObject->dynamic_post_event_content_view) && $cs_xmlObject->dynamic_post_event_content_view == 'in_post'){echo 'selected="selected"';}?>><?php _e('In Post','directory');?></option>
			  <option value="none" <?php if(isset($cs_xmlObject->dynamic_post_event_content_view) && $cs_xmlObject->dynamic_post_event_content_view == 'none'){echo 'selected="selected"';}?>>None</option>
			</select>
		  </div>
		</div>
	  </li>
	</ul>
	<div class="clear"></div>
	<input type="hidden" name="dynamic_post_location" value="1" />
	<?php
	}
}
/*
*Faqs Section
*/
if ( ! function_exists( 'cs_faqs_section' ) ) {
	function cs_faqs_section($post_id=''){
		global $post, $cs_xmlObject, $counter_faq, $directory_faq_title, $directory_faq_description;
		if(isset($post_id) && !empty($post_id)){
			$counter_faq = $post_id;
			$cs_directory = get_post_meta($post_id, "cs_directory_meta", true);
			if ( $cs_directory <> "" ) {
				$cs_xmlObject = new SimpleXMLElement($cs_directory);
			}	
			?>
			<script>
				jQuery("#total_faqs").sortable({
					cancel : 'td div.table-form-elem'
				});
			</script>
			<?php
		} else {
			$counter_faq = time();
			?>
			<script>
				jQuery(document).ready(function($) {
					$("#total_faqs").sortable({
						cancel : 'td div.table-form-elem'
					});
				});
			 </script>
			<?php
		}
		if(!isset($cs_xmlObject))
			$cs_xmlObject = new stdClass();
		?>
	  <input type="hidden" name="dynamic_post_faq" value="1" />
      <ul class="form-elements">
            <li class="to-label"><?php _e('Add FAQ','directory');?></li>
            <li class="to-button"><a href="javascript:_createpop('add_faq_title','filter')" class="button"><?php _e('Add FAQ','directory');?></a> </li>
       </ul>
	  <div class="cs-list-table">
      <table class="to-table" border="0" cellspacing="0">
		<thead>
		  <tr>
			<th style="width:80%;"><?php _e('Title','directory');?></th>
			<th style="width:80%;" class="centr"><?php _e('Actions','directory');?></th>
            <th style="width:0%;" class="centr"></th>
		  </tr>
		</thead>
		<tbody id="total_faqs">
		  <?php
				if ( isset($cs_xmlObject->faqs) && is_object($cs_xmlObject) && count($cs_xmlObject->faqs)>0) {
					foreach ( $cs_xmlObject->faqs as $faqs ){
						 $directory_faq_title = $faqs->faq_title;
						 $directory_faq_description = $faqs->faq_description;
						 cs_add_faq_to_list();
						 $counter_faq++;
					}
				}
			?>
		</tbody>
	  </table>
      </div>
	  <div id="add_faq_title" style="display: none;">
		<div class="cs-heading-area">
		  <h5> <i class="icon-plus-circle"></i><?php _e('FAQ Settings','directory');?>  </h5>
		  <span class="cs-btnclose" onClick="javascript:removeoverlay('add_faq_title','append')"> <i class="icon-times"></i></span> </div>
		<ul class="form-elements">
		  <li class="to-label">
			<label><?php _e('Title','directory');?></label>
		  </li>
		  <li class="to-field">
			<input type="text" id="faq_title" name="faq_title" value="Title" />
		  </li>
		</ul>
		<ul class="form-elements">
		  <li class="to-label">
			<label><?php _e('FAQ Description','directory');?></label>
		  </li>
		  <li class="to-field">
			<textarea name="faq_description" id="faq_description"></textarea>
		  </li>
		</ul>
		<ul class="form-elements noborder">
		  <li class="to-label"></li>
		  <li class="to-field">
			<input type="button" value="Add FAQ to List" onClick="add_faq_to_list('<?php echo esc_js(admin_url('admin-ajax.php'));?>', '<?php echo esc_js(get_template_directory_uri());?>')" />
		  </li>
		</ul>
	  </div>
	<?php
		}
}

/**
 * @Add FAQ List
 */
if ( ! function_exists( 'cs_add_faq_to_list' ) ) {
	function cs_add_faq_to_list(){
		global $counter_faq, $directory_faq_title,$directory_faq_description;
		foreach ($_POST as $keys=>$values) {
			$$keys = $values;
		}
	?>
    <tr class="parentdelete" id="edit_track<?php echo esc_attr($counter_faq)?>">
      <td id="subject-title<?php echo esc_attr($counter_faq)?>" style="width:80%;"><?php echo esc_attr($directory_faq_title);?></td>
      <td class="centr" style="width:20%;"><a href="javascript:_createpop('edit_track_form<?php echo esc_js($counter_faq)?>','filter')" class="actions edit">&nbsp;</a> <a href="#" class="delete-it btndeleteit actions delete">&nbsp;</a></td>
      <td style="width:0">
      	
        <div  id="edit_track_form<?php echo esc_attr($counter_faq);?>" style="display: none;" class="table-form-elem">
          <div class="cs-heading-area">
            <h5 style="text-align: left;"><?php _e('FAQ Settings','directory');?></h5>
            <span onclick="javascript:removeoverlay('edit_track_form<?php echo esc_js($counter_faq)?>','append')" class="cs-btnclose"> <i class="icon-times"></i></span>
            <div class="clear"></div>
          </div>
          <ul class="form-elements">
            <li class="to-label">
              <label><?php _e('FAQ Title','directory');?> </label>
            </li>
            <li class="to-field">
              <input type="text" name="faq_title_array[]" value="<?php echo htmlspecialchars($directory_faq_title)?>" id="faq_track_title<?php echo esc_attr($counter_faq)?>" />
            </li>
          </ul>
          <ul class="form-elements">
            <li class="to-label">
              <label><?php _e('FAQ Description','directory');?></label>
            </li>
            <li class="to-field">
              <textarea name="faq_description_array[]" rows="5"  id="faq_track_description<?php echo esc_attr($counter_faq);?>" cols="20"><?php echo htmlspecialchars($directory_faq_description)?></textarea>
            </li>
          </ul>
          <ul class="form-elements noborder">
            <li class="to-label">
              <label></label>
            </li>
            <li class="to-field">
              <input type="button" value="Update FAQ" onclick="update_title(<?php echo esc_js($counter_faq);?>); removeoverlay('edit_track_form<?php echo esc_js($counter_faq);?>','append')" />
            </li>
          </ul>
        </div></td>
    </tr>
<?php
		if ( isset($_POST['faq_title']) && isset($_POST['cs_add_faq_to_list']) ) die();
	}
	add_action('wp_ajax_cs_add_faq_to_list', 'cs_add_faq_to_list');
}

// 
/*
* Error Message
*/
if ( ! function_exists( 'px_error_msg' ) ) {
	function px_error_msg( $error_msg ) {
		$msg_string = '';
		foreach ($error_msg as $value) {
			if ( !empty( $value ) ) {
				$msg_string = $msg_string . '<div class="error">' . $value . '</div>';
			}
		}
		return $msg_string;
	}
}
/*
*used while custom upload attachment
*/
if ( ! function_exists( 'cs_upload_attachment' ) ) {
	function cs_upload_attachment( $post_id ) {
		if ( !isset( $_FILES['px_post_attachments'] ) ) {
			return false;
		}
		$file_name = basename( $_FILES['px_post_attachments']['name']);
		if ( $file_name ) {
			if ( $file_name ) {
				$upload = array(
					'name' => $_FILES['px_post_attachments']['name'],
					'type' => $_FILES['px_post_attachments']['type'],
					'tmp_name' => $_FILES['px_post_attachments']['tmp_name'],
					'error' => $_FILES['px_post_attachments']['error'],
					'size' => $_FILES['px_post_attachments']['size']
				);
				px_upload_file( $upload );
			}//file exists
		 }
	}
}
/*
* custom file upload attachment
*/
if ( ! function_exists( 'px_upload_file' ) ) {
	function px_upload_file( $upload_data ) {
		include_once ABSPATH . 'wp-admin/includes/media.php';
		include_once ABSPATH . 'wp-admin/includes/file.php';
		include_once ABSPATH . 'wp-admin/includes/image.php';
		$uploaded_file = wp_handle_upload( $upload_data, array('test_form' => false) );
		if ( isset( $uploaded_file['file'] ) ) {
			$file_loc = $uploaded_file['file'];
			$file_name = basename( $upload_data['name'] );
			$file_type = wp_check_filetype( $file_name );
			$attachment = array(
				'post_mime_type' => $file_type['type'],
				'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $file_name ) ),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			$attach_id = wp_insert_attachment( $attachment, $file_loc );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file_loc );
			wp_update_attachment_metadata( $attach_id, $attach_data );
			return $attach_id;
		}
		return false;
	}
}
/*
* Media Attachment
*/
if ( ! function_exists( 'cs_media_attachments' ) ) {	
	function cs_media_attachments(){
		?>
        <div class="to-social-network">
          <div class="gal-active">
            <div class="clear"></div>
            <div class="dragareamain">
              <div class="placehoder"><?php _e('Gallery is Empty. Please Select Media','directory'); ?> <img src="<?php echo esc_url(get_template_directory_uri().'/include/assets/images/bg-arrowdown.png');?>" alt="" /></div>
              <ul id="gal-sortable">
                <?php 
                    global $cs_node, $cs_xmlObject, $cs_counter;
                    $cs_counter_gal = 0;
                    if(count($cs_xmlObject->gallery)>0){
                        foreach ( $cs_xmlObject->gallery as $cs_node ){
                            $cs_counter_gal++;
                            $cs_counter = $post->ID.$cs_counter_gal;
                            cs_gallery_clone();
                        }
                    }
                ?>
              </ul>
            </div>
          </div>
          <div class="to-social-list">
            <div class="soc-head">
              <h5><?php _e('Select Media','directory');?></h5>
              <div class="right">
                <input type="button" class="button reload" value="Reload" onClick="refresh_media()" />
                <input id="cs_log" name="cs_logo" type="button" class="uploadfile button" value="Upload Media" />
              </div>
              <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <script type="text/javascript">
				function show_next(page_id, total_pages){
					var dataString = 'action=media_pagination&page_id='+page_id+'&total_pages='+total_pages;
					jQuery("#pagination").html("<img src='<?php echo esc_js(esc_url(get_template_directory_uri().'/include/assets/images/ajax_loading.gif'));?>' />");
					jQuery.ajax({
						type:'POST', 
						url: "<?php echo esc_js(admin_url('admin-ajax.php'));?>",
						data: dataString,
						success: function(response) {
							jQuery("#pagination").html(response);
						}
					});
				}
				function refresh_media(){
					var dataString = 'action=media_pagination';
					jQuery("#pagination").html("<img src='<?php echo esc_js(esc_url(get_template_directory_uri().'/include/assets/images/ajax_loading.gif'));?>' />");
					jQuery.ajax({
						type:'POST', 
						url: "<?php echo esc_js(admin_url('admin-ajax.php'))?>",
						data: dataString,
						success: function(response) {
							jQuery("#pagination").html(response);
						}
					});
				}
				</script> 
           		<!--<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/jquery.scrollTo-min.js"></script>--> 
				<script>
                	jQuery(document).ready(function($) {
                    	$("#gal-sortable").sortable({
                        	cancel:'li div.poped-up',
                    	});
                    	//$(this).append("#gal-sortable").clone() ;
                    });
                    var counter = 0;
                    var count_items = <?php echo esc_js($cs_counter_gal)?>;
                    if ( count_items > 0 ) {
                        jQuery(".dragareamain") .addClass("noborder");	
                    }
                    function clone(path){
                    	counter = counter + 1;
                        var dataString = 'path='+path+'&counter='+counter+'&action=gallery_clone';
                       jQuery("#gal-sortable").append("<li id='loading'><img src='<?php echo esc_js(esc_url(get_template_directory_uri().'/include/assets/images/ajax_loading.gif'));?>' /></li>");
                        jQuery.ajax({
                            type:'POST', 
                            url: "<?php echo esc_js(admin_url('admin-ajax.php'));?>",
                            data: dataString,
                            success: function(response) {
                                jQuery("#loading").remove();
                                jQuery("#gal-sortable").append(response);
                                count_items = jQuery("#gal-sortable li") .length;
									if ( count_items > 0 ) {
										jQuery(".dragareamain") .addClass("noborder");	
									}
                            }
                        });
                    }
                    function del_this(id){
                        jQuery("#"+id).remove();
                        count_items = jQuery("#gal-sortable li") .length;
                            if ( count_items == 0 ) {
                                jQuery(".dragareamain") .removeClass("noborder");	
                            }
                    }
                </script> 
                <script type="text/javascript">
                var contheight;
                  function galedit(id){
                      var $ = jQuery;
                      $(".to-social-list,.gal-active h4.left,#gal-sortable li,#gal-sortable .thumb-secs") .not("#"+id) .fadeOut(200);
                      $.scrollTo( '.page-wrap', 400, {easing:'swing'} );
                            $('.poped-up').animate({
                                top: 0,
                            }, 300, function() {
                                $("#edit_" + id+" li").show();
                                $("#edit_" + id).slideDown(300); 
                            });
                   };
                   function galclose(id){
                      var $ = jQuery;
                      $("#edit_" + id) .slideUp(300);
                      $(".to-social-list,.gal-active h4.left,#gal-sortable li,#gal-sortable .thumb-secs")  .fadeIn(300);
                  };
                
                </script>
            <div id="pagination">
              <?php media_pagination();?>
            </div>
            <input type="hidden" name="gallery_meta_form" value="1" />
            <div class="clear"></div>
          </div>
        </div>
	<?php	
	}
}
/*
* Section Slider
*/
if ( ! function_exists( 'cs_section_slider' ) ) {	
	function cs_section_slider($section_field_name = 'section_'){
		$rand_id = rand(5,63);
		?>
            <div class="to-social-network">
              <div class="gal-active">
                <div class="clear"></div>
                <div class="dragareamain">
                  <div class="placehoder">Gallery is Empty. Please Select Media <img src="<?php echo esc_url(get_template_directory_uri().'/include/assets/images/bg-arrowdown.png');?>" alt="" /></div>
                  <ul id="gal-sortable-slider-<?php echo esc_attr($rand_id);?>">
                    <?php 
						global $cs_node, $cs_xmlObject, $cs_counter;
						$cs_counter_gal = 0;
						if(count($cs_xmlObject->gallery)>0){
							foreach ( $cs_xmlObject->gallery as $cs_node ){
								$cs_counter_gal++;
								$cs_counter = $post->ID.$cs_counter_gal;
								cs_gallery_clone('section_');
							}
						}
					?>
                    
                  </ul>
                </div>
              </div>
              <div class="to-social-list">
                <div class="soc-head">
                  <h5>Select Media</h5>
                  <div class="right">
                    <input type="button" class="button reload" value="Reload" onClick="refresh_media()" />
                    <input id="cs_log" name="cs_logo" type="button" class="uploadfile button" value="Upload Media" />
                  </div>
                  <div class="clear"></div>
                </div>
                <div class="clear"></div>
                <script type="text/javascript">
					function show_next(page_id, total_pages){
						var dataString = 'action=media_pagination&page_id='+page_id+'&total_pages='+total_pages;
						jQuery("#pagination").html("<img src='<?php echo esc_js(esc_url(get_template_directory_uri().'/include/assets/images/ajax_loading.gif'));?>' />");
						jQuery.ajax({
							type:'POST', 
							url: "<?php echo esc_js(admin_url('admin-ajax.php'));?>",
							data: dataString,
							success: function(response) {
								jQuery("#pagination").html(response);
							}
						});
					}
					function refresh_media(){
						var dataString = 'action=media_pagination';
						jQuery("#pagination").html("<img src='<?php echo esc_js(esc_url(get_template_directory_uri().'/include/assets/images/ajax_loading.gif'));?>' />");
						jQuery.ajax({
							type:'POST', 
							url: "<?php echo esc_js(admin_url('admin-ajax.php'));?>",
							data: dataString,
							success: function(response) {
								jQuery("#pagination").html(response);
							}
						});
					}
				</script> 
                <!--<script type="text/javascript" src="<?php echo get_template_directory_uri()?>/scripts/admin/jquery.scrollTo-min.js"></script>--> 
                <script>
                                    jQuery(document).ready(function($) {
                                        $("#gal-sortable-slider-<?php echo esc_js($rand_id);?>").sortable({
                                            cancel:'li div.poped-up',
                                        });
                                        //$(this).append("#gal-sortable").clone() ;
                                        });
                                        var counter = 0;
                                        var count_items = <?php echo esc_js($cs_counter_gal)?>;
                                        if ( count_items > 0 ) {
                                            jQuery(".dragareamain") .addClass("noborder");	
                                        }
            
                                        function clone(path){
                                            counter = counter + 1;
                                            var dataString = 'path='+path+'&counter='+counter+'&action=gallery_clone';
                                            
                                            jQuery("#gal-sortable-slider-<?php echo esc_js($rand_id);?>").append("<li id='loading'><img src='<?php echo esc_js(esc_url(get_template_directory_uri().'/include/assets/images/ajax_loading.gif'));?>' /></li>");
                                            jQuery.ajax({
                                                type:'POST', 
                                                url: "<?php echo esc_js(admin_url('admin-ajax.php'));?>",
                                                data: dataString,
                                                success: function(response) {
                                                    jQuery("#loading").remove();
                                                    jQuery("#gal-sortable-slider-<?php echo esc_js($rand_id);?>").append(response);
                                                    count_items = jQuery("#gal-sortable-slider-<?php echo esc_js($rand_id);?> li") .length;
                                                        if ( count_items > 0 ) {
                                                            jQuery(".dragareamain") .addClass("noborder");	
                                                        }
                                                }
                                            });
                                        }
                                        function del_this(id){
                                            jQuery("#"+id).remove();
                                            count_items = jQuery("#gal-sortable-slider-<?php echo esc_js($rand_id);?> li") .length;
                                                if ( count_items == 0 ) {
                                                    jQuery(".dragareamain") .removeClass("noborder");	
                                                }
                                        }
                                </script> 
                					<script type="text/javascript">
										var contheight;
										  function galedit(id){
											  var $ = jQuery;
											  $(".to-social-list,.gal-active h4.left,#gal-sortable-slider-<?php echo esc_js($rand_id);?> li,#gal-sortable .thumb-secs") .not("#"+id) .fadeOut(200);
											  $.scrollTo( '.page-wrap', 400, {easing:'swing'} );
													$('.poped-up').animate({
														top: 0,
													}, 300, function() {
														$("#edit_" + id+" li")  .show(); 
														$("#edit_" + id)   .slideDown(300); 
													});
										   };
										   function galclose(id){
											  var $ = jQuery;
											  $("#edit_" + id) .slideUp(300);
											  $(".to-social-list,.gal-active h4.left,#gal-sortable li,#gal-sortable-slider-<?php echo esc_js($rand_id);?> .thumb-secs")  .fadeIn(300);
										  };
                                	</script>
                <div id="pagination">
                  <?php media_pagination($rand_id,'clone');?>
                </div>
                <input type="hidden" name="gallery_meta_form" value="1" />
                <div class="clear"></div>
              </div>
            </div>
	<?php	
	}
}
/*
* Design Element i dont think its used now  but not sure
*/
if ( ! function_exists( 'cs_design_element' ) ) {	
	function cs_design_element(){
		if(isset($_POST['design_name'])){
			$design_name = $_POST['design_name'];
			$html_values = htmlentities($_POST['design_html']);
			update_option($design_name,$html_values);
		}
		die;
	}
	add_action('wp_ajax_cs_design_element', 'cs_design_element');
}
/*
* Event Location Fields
*/
if ( ! function_exists( 'cs_location_fields' ) ) {
	function cs_location_fields( ){
		global $cs_xmlObject,$cs_theme_options, $post;
		
		$cs_map_latitude = isset($cs_theme_options['map_latitude']) ? $cs_theme_options['map_latitude'] : '';
		$cs_map_longitude = isset($cs_theme_options['map_longitude']) ? $cs_theme_options['map_longitude'] : '';
		$cs_map_zoom = isset($cs_theme_options['map_zoom']) ? $cs_theme_options['map_zoom'] : '11';
		
		if ( isset($cs_xmlObject)) {
			
			$dynamic_post_location_city = get_post_meta($post->ID,'dynamic_post_location_city',true);
			$dynamic_post_location_region = get_post_meta($post->ID,'dynamic_post_location_region',true);
			$dynamic_post_location_country = get_post_meta($post->ID,'dynamic_post_location_country',true);
			$dynamic_post_location_latitude = get_post_meta($post->ID,'dynamic_post_location_latitude',true);
			$dynamic_post_location_longitude = get_post_meta($post->ID,'dynamic_post_location_longitude',true);
			$dynamic_post_location_zoom = get_post_meta($post->ID,'dynamic_post_location_zoom',true);
			$dynamic_post_location_address = get_post_meta($post->ID,'dynamic_post_location_address',true);
			$add_new_loc = get_post_meta($post->ID,'add_new_loc',true);
		} else {
			$dynamic_post_location_latitude = $dynamic_post_location_city = $dynamic_post_location_region = $dynamic_post_location_country ='';
			$dynamic_post_location_longitude = '';
			$dynamic_post_location_zoom = '';
			$dynamic_post_location_address = '';
			$loc_city = '';
			$loc_postcode = '';
			$loc_region = '';
			$loc_country = '';
			$event_map_switch = '';
			$event_map_heading = 'Event Location';
			$add_new_loc = '';
		}
		
		if( $dynamic_post_location_latitude == '' ) $dynamic_post_location_latitude = $cs_map_latitude;
		if( $dynamic_post_location_longitude == '' ) $dynamic_post_location_longitude = $cs_map_longitude;
		if( $dynamic_post_location_zoom == '' ) $dynamic_post_location_zoom = $cs_map_zoom;
		
		$cs_directory_location_suggestions	= isset( $cs_theme_options['cs_directory_location_suggestions'] ) ?  $cs_theme_options['cs_directory_location_suggestions'] : '';
		$cs_location_type	= isset( $cs_theme_options['cs_search_by_location'] ) ?  $cs_theme_options['cs_search_by_location'] : '';
		
		cs_enqueue_location_gmap_script();
		wp_directory::cs_google_place_scripts();
		wp_directory::cs_autocomplete_scripts();
				
		
		$cs_location_countries	= get_option('cs_location_countries');
		$states_list			= get_option('cs_location_states');
		$cities_list			= get_option('cs_location_cities');
								
		
		$location_countries_list	= '';
		$location_states_list		= '';
		$location_cities_list		= '';
		$iso_code					= '';
		if( isset( $cs_location_countries ) && ! empty( $cs_location_countries ) ) {
			foreach( $cs_location_countries as $key => $country ) {
				 $selected	= '';
				 
				 if( isset( $dynamic_post_location_country ) && $dynamic_post_location_country == $key ) {
				 	$iso_code	= $country['isocode2'];
					$selected	= 'selected';
				 }
				 
				 $location_countries_list	.=  "<option ".$selected."  value='".$key."' data-name=".$country['isocode2'].">".$country['name']."</option>";
			}
		}

		$selected_country	= $dynamic_post_location_country;
		$selected_state		= $dynamic_post_location_region;
		$selected_city		= $dynamic_post_location_city;
		
		$dynamic_post_location_region;
		
		if( isset( $cs_location_countries ) && ! empty( $cs_location_countries ) && isset( $dynamic_post_location_country )  && !empty( $dynamic_post_location_country )  ) {

			 if( isset( $states_list[$selected_country] ) ) {
				 $states	= $states_list[$selected_country];
				 if( isset( $states ) && $states !='' && is_array($states) ) {
						foreach( $states as $key => $state ) {
								if( isset( $key ) && $key !='no-state' && !empty($state['name']) ) {
									$selected	= ( $selected_state == $state['name'] ) ? 'selected' : '';
									$location_states_list	.=  "<option ".$selected." value='".$state['name']."'>".$state['name']."</option>";
								}
							if( isset( $cities_list[$selected_country][$key] ) ) {
								$cities	= $cities_list[$selected_country][$key];
								if( isset( $cities ) && $cities !='' && is_array($cities) ) {
									foreach( $cities as $key => $city ) {
										$selected	= ( $selected_city == $city['name'] ) ? 'selected' : '';
										$location_cities_list	.=  "<option ".$selected." value='".$city['name']."'>".$city['name']."</option>";
									}
								}
							}
						}
				  }
			 }
		}
		?>
       <script>

			jQuery(document).ready(function () {
				cs_load_location_ajax();
			});
			
			function cs_gll_search_map() {
				var vals;
				vals = jQuery('#loc_address').val();
				vals = vals + ", " + jQuery('#loc_city').val();
				vals = vals + ", " + jQuery('#loc_region').val();
				vals = vals + ", " + jQuery('#loc_country').val();
				jQuery('.gllpSearchField').val(vals);
			}
							
			(function( $ ) {
				$(function() {
					<?php 
						 wp_directory::cs_google_place_scripts();
						?>
						var autocomplete;
						autocomplete = new google.maps.places.Autocomplete(document.getElementById('loc_address'));
						<?php if( isset( $iso_code ) && !empty( $iso_code ) ) {?>
							autocomplete.setComponentRestrictions({ 'country': '<?php echo $iso_code;?>' });    
						<?php
						 }
					?>
				});
			})( jQuery );
			
		</script>
		<fieldset class="gllpLatlonPicker"  style="width:100%; float:left;">
              <div class="page-wrap page-opts left" style="overflow:hidden; position:relative;" id="locations_wrap" data-themeurl="<?php echo wp_directory::plugin_url();?>" data-plugin_url="<?php echo wp_directory::plugin_url();?>" data-ajaxurl="<?php echo esc_js(admin_url('admin-ajax.php'), 'directory');?>" data-map_marker="<?php echo wp_directory::plugin_url();?>/assets/images/map-marker.png">
                <div class="option-sec" style="margin-bottom:0;">
                  <div class="opt-conts">
                     <ul class="form-elements">
                        <li class="to-label"><label><?php _e('Country','directory');?></label></li>
                         <li class="to-field select-style">
                            <?php
                            echo '<select class="form-select-country dir-map-search single-select SlectBox" name="dir_cusotm_field[dynamic_post_location_country]" id="loc_country">
                                <option value="">Select Country</option>';
                                echo $location_countries_list;
                            echo '</select>';
                            ?>
                       </li>
                    </ul>
                    <ul class="form-elements">
                    	<li class="to-label"><label><?php _e('State','directory');?></label></li>
                        <li class="to-field select-style">
                        <span class="loader-states"></span>
                         <?php
                            echo '<select class="form-select-state dir-map-search single-select SlectBox" name="dir_cusotm_field[dynamic_post_location_region]" id="loc_region" onchange="cs_gll_search_map(this.value)">
                                <option value="">'.__('Select State','directory').'</option>';
                                echo $location_states_list;
                            echo '</select>';
                        ?>
                    </li>
                	</ul>
                 	<ul class="form-elements">
                     	 <li class="to-label"><label><?php _e('City','directory');?></label></li>
                		 <li class="to-field select-style">
                           
                            <span class="loader-cities"></span>
                             <?php
                                echo '<select class="form-select-city dir-map-search single-select SlectBox" name="dir_cusotm_field[dynamic_post_location_city]" id="loc_city" onchange="cs_gll_search_map(this.value)">
                                    <option value="">Select City</option>';
                                    echo $location_cities_list;
                                echo '</select>';
                            ?>
                        </li>
 					</ul>
                    <ul class="form-elements">
                      <li class="to-label">
                        <label><?php _e('Address','directory');?></label>
                      </li>
                      <li class="to-field">
                        <input name="dir_cusotm_field[dynamic_post_location_address]" autocomplete="on" class="directory-search-location" id="loc_address" type="text" value="<?php echo htmlspecialchars($dynamic_post_location_address)?>" onkeypress="cs_gll_search_map(this.value)" />
                      </li>
                    </ul>
                    <ul class="form-elements" style="display:none">
                      <li class="to-label">
                        <label><?php _e('Latitude','directory');?></label>
                      </li>
                      <li class="to-field">
                      	<input type="hidden" name="dir_cusotm_field[dynamic_post_location_latitude]" value="<?php echo esc_attr($dynamic_post_location_latitude);?>" class="gllpLatitude" />
                    </ul>
                    
                    <ul class="form-elements"  style="display:none">
                      <li class="to-label">
                        <label><?php _e('Longitude','directory');?></label>
                      </li>
                      <li class="to-field">
                       <input type="hidden" name="dir_cusotm_field[dynamic_post_location_longitude]" value="<?php echo esc_attr($dynamic_post_location_longitude);?>" class="gllpLongitude" />
                      </li>
                    </ul>
 
                    <ul class="form-elements">
                      <li class="to-label">
                        <label></label>
                      </li>
                      <li class="to-field">
                        <input type="button" class="gllpSearchButton" value="Search This Location on Map" onClick="gll_search_map()">
                      </li>
                    </ul>
                    <ul class="form-elements " style="float: left; width:100%;" >
                      <li>
                        <div class="clear"></div>
                        <input type="hidden" name="dir_cusotm_field[add_new_loc]" value="<?php  esc_attr($add_new_loc); ?>"  class="gllpSearchField" style="margin-bottom:10px;">
                        <input type="hidden" name="dir_cusotm_field[dynamic_post_location_zoom]" value="<?php echo esc_attr($dynamic_post_location_zoom);?>" class="gllpZoom" />
                        <input type="button" class="gllpUpdateButton" value="update map" style="display:none">
                        <div class="clear"></div>
                        <div style="float:left; width:100%; height:100%;">
                          <div class="gllpMap" id="cs-map-location-id"></div>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </fieldset>
	<?php
	}
}
/*
* Dynamic Custom Pirce Fields
*/
if ( ! function_exists( 'cs_sale_fields' ) ) {
	function cs_sale_fields($post_id=''){
		global $post,$cs_xmlObject;
		if(isset($post_id) && !empty($post_id)){
			$cs_directory = get_post_meta($post_id, "cs_directory_meta", true);
			if ( $cs_directory <> "" ) {
				$cs_xmlObject = new SimpleXMLElement($cs_directory);
			}	
		}
		
		if (isset( $post_id ) && $post_id !='' ){
			$post_id	= $post_id;
		} else {
			$post_id	= $post->ID;
		}
		
		$price_options = array('paid'=>'Paid','price-on-call'=>'Price On Call','free'=>'Free');
		$dynamic_post_other_options['slae'] = array(
				'title' => __('Sale Settings', 'directory'),
				'id' => 'location-meta-option',
				'notes' => __('Location Options.', 'directory'),
				'params' => array(
						'dynamic_post_sale_type' => array(
							'name' => 'dynamic_post_sale_type',
							'type' => 'radio',
							'class' => 'price-types-radio',
							'title' => '',
							'description' => '',
							'default' => 'paid',
							'id'	  => '',
							'options' => $price_options,
						),
						'dynamic_post_sale_oldprice' => array(
							'name' => 'dynamic_post_sale_oldprice',
							'type' => 'text',
							'class' => 'dynamic_post_sale_oldprice on-call',							
							'title' => __('Old Price', 'directory'),
							'description' => '',
							'id'	  => 'free-post-type',
							'default' => '',
						),
						'dynamic_post_sale_newprice' => array(
							'name' => 'dynamic_post_sale_newprice',
							'type' => 'text',
							'class' => 'dynamic_post_sale_newprice on-call',
							'title' => __('New Price', 'directory'),
							'id'	  => 'free-post-type',
							'description' => '',
							'default' => '',
						),
						'dynamic_post_sale_price_call' => array(
							'name' => 'dynamic_post_sale_price_call',
							'type' => 'text',
							'title' => __('Phone No', 'directory'),
							'class' => 'dynamic_post_sale_price_call',
							'description' => 'show in case of public profile off and price fields empty',
							'id'	  => 'free-post-type',
							'default' => '',
						),
				)
			);
			$output = '';
			
			if(isset($post_id) && $post_id <> ''){
				$price_type_value = get_post_meta($post_id, 'dynamic_post_sale_type', true);
			} else {
				$price_type_value = 'free';
			}
			
			foreach($dynamic_post_other_options['slae'] as $params){
					if(is_array($params)){
						foreach($params as $key=>$param){
							if(isset($param['title'])){$param_title = $param['title'];}	 else {$param_title = '';}
							if(isset($param['id'])){$param_id = $param['id'];}	 else {$param_id = '';}
							if(isset($param['class'])){$param_class = $param['class'];}	 else {$param_class = '';}
							if(isset($param['description'])){$param_description = $param['description'];} else {$param_description = '';}
							$field_value = '';
							if(isset($key) && $key <> ''){
								if(isset($post_id) && $post_id <> ''){
									$field_value = get_post_meta($post_id, $key, true);
								}
							} else {
								$key = '';
							}


							switch( $param['type'] )
							{
								case 'text' :
									
									$output .= '<ul id="'.$param_id.'" class="form-elements noborder '.$param_class.'">
										<li class="to-label"><label>'.$param_title.'</label></li>
										<li class="to-field">
											<div class="input-sec">
												 <input type="text" name="dir_cusotm_field[' . $key . ']" value="'.$field_value.'" />
											</div>
											<div class="left-info">
												<p>'.$param_description.'</p>
											</div>
										</li>
									</ul>';
									break;
								case 'radio' :
										if(isset($post_id) && $post_id <> ''){
											$price_type_value = get_post_meta($post_id, $key, true);
											if( $price_type_value == '' ){
												$price_type_value = 'free';
											}
										} else if(isset($post_id) && $post_id <> ''){
											$price_type_value = get_post_meta($post_id, $key, true);
											$post_id	= $post_id;
										} else {
											$post_id	= '';
											if(isset($param['default'])){$price_type_value = $param['default'];} else {$price_type_value = 'paid';}
										}
										$checked = '';
										
										
										if(isset($post_id)) {
											$post_id	=  esc_js($post_id);
										} else {
											$post_id	= '';
										}
										if(isset($param['options'])){$param_options = $param['options'];} else {$param_options = array();}
										$adminURL	= admin_url('admin-ajax.php');

										foreach( $param_options as $value => $option )
										{
											$checked = '';
											
											if(	$value == $price_type_value) { 
												
												$checked = 'checked=checked';
											}
											
											ob_start();
											
											?>
											 <!--<div class="cs-radio-box">
											 <input type="radio" onchange="cs_directory_type(this.value, '<?php echo intval( $post_id ) ;?>','<?php echo esc_url( $adminURL );?>','backend')" id="sale_type_<?php echo esc_attr( $value );?>"  name="dir_cusotm_field[<?php echo esc_attr( $key ) ;?>]" value="<?php echo esc_attr( $value );?>" <?php echo esc_attr( $checked );?> >
											 <label><?php echo esc_attr( $option );?></label>
											 </div>-->
										<?php 	
											 $output .= ob_get_clean();
										
										}

									break;
								case 'range' :
								$output .= '<ul class="form-elements noborder  '.$param_class.'">
									<li class="to-label"><label>'.$param_title.'</label></li>
									<li class="to-field">
									<div class="input-sec">
										<input id="' . $key . '" data-slider-id="dropcap_size" class="cs-drag-slider" name="dir[' . $key . ']" type="text"  data-slider-min="5" data-slider-max="20" data-slider-step="10" data-slider-value="' . $key . '" value="'.$field_value.'"/>
									</div>
									<div class="left-info">
										<p>'.$param_description.'</p>
									</div>
									</li>
								</ul>';
								break;
							}
						}
					}
			}
		echo balanceTags($output, true);	
	}
}

/*
* Opening Hours Fields Display
*/
if ( ! function_exists( 'cs_openinghours_display' ) ) {
	function cs_openinghours_display(){
		global $cs_xmlObject, $current_user;
		if(isset($_GET['uid']) && $_GET['uid'] <> ''){
			 $uid = absint($_GET['uid']);
		} else {
			$uid= $current_user->ID;
		}
		$opning_hours = get_user_meta( $uid, 'opening_hours', true);
		$weekdays = array( "Sun" => "Sunday", "Mon" => "Monday", "Tue" => "Tuesday", "Wed" => "Wednesday", "Thu" => "Thursday", "Fri" => "Friday", "Sat" => "Saturday" );
		$output = '<ul class="opening-hours">';
			foreach($weekdays as $key=>$value){
				$weekday_text = 'openhours_'.$key.'_text';
				$weekday_start = 'openhours_'.$key.'_start';
				$weekday_end = 'openhours_'.$key.'_end';
				if(isset($opning_hours[$weekday_text]) && $opning_hours[$weekday_text] <> '' && isset($opning_hours[$weekday_start]) && $opning_hours[$weekday_start] <> '' && isset($opning_hours[$weekday_end]) && $opning_hours[$weekday_end] <> ''){
					$output .= '<li class="weekday-title">'.$opning_hours[$weekday_text].'</li>';
					$output .= '<li class="weekday-title">'.$opning_hours[$weekday_start].'</li>';
					$output .= '<li class="weekday-title">'.$opning_hours[$weekday_end].'</li>';
				}
			}
		$output .= '</ul>';
		echo balanceTags($output, true);
	}
}
/*
* Opening Hours Fields
*/
if ( ! function_exists( 'cs_openinghours_fields' ) ) {
	function cs_openinghours_fields(){
		global $cs_xmlObject, $current_user;
		
		if ( is_admin() && isset($_GET['user_id']) && $_GET['user_id'] <> ''  ) {
			if(isset($_GET['user_id']) && $_GET['user_id'] <> ''){
				 $uid = absint($_GET['user_id']);
			} else {
				 $uid = $current_user->ID;
			}
		} else {
			if(isset($_GET['uid']) && $_GET['uid'] <> ''){
			 	$uid = absint($_GET['uid']);
			} else {
				$uid = $current_user->ID;
			}
		}
		
		$opning_hours = get_user_meta( $uid, 'opening_hours', true);
		$weekdays = array( "Sun" => __("Sunday",'directory'), "Mon" => __("Monday",'directory'), "Tue" => __("Tuesday",'directory'), "Wed" => __("Wednesday",'directory'), "Thu" => __("Thursday",'directory'), "Fri" => __("Friday",'directory'), "Sat" => __("Saturday",'directory') );
		$weekday_fields = array();
		
		foreach($weekdays as $key=>$value){
			$weekday_fields[$key] = array(
						'openhours_'.$key.'_text' => array(
							'name' => 'openhours_'.$key.'_text',
							'type' => 'text',
							'title' => $value,
							'class' => '',
							'description' => '',
							'default' => $value,
						),
						'openhours_'.$key.'_start' => array(
							'name' => 'openhours_'.$key.'_start',
							'type' => 'text',
							'class' => 'openhours-time',
							'title' => 'Start Time',
							'description' => '',
							'default' => '',
						),
						'openhours_'.$key.'_end' => array(
							'name' => 'openhours_'.$key.'_end',
							'type' => 'text',
							'class' => 'openhours-time',
							'title' => 'End Time',
							'description' => '',
							'default' => '',
						)
				);
		}
		$dynamic_post_other_options['openinghours'] = array(
				'title' => __('Opening Hours', 'directory'),
				'id' => 'openinghour-meta-option',
				'notes' => __('Opening Hours', 'directory'),
				'params' => $weekday_fields
			);
			$output = '';
			
			foreach($dynamic_post_other_options['openinghours']['params'] as $params){
				$output .= '<tr>';
				if(is_array($params)){
					foreach($params as $key=>$param){
						if(isset($opning_hours[$key])){
							$value = $opning_hours[$key];
						} else {
							$value = $param['default'];
						}
						switch( $param['type'] )
							{
								case 'text' :
									$output .= '<td>
													<input type="text" name="opening_hours[' . $key . ']" value="'.$value.'" class="'.sanitize_html_class($param['class']).'" />
												</td>';
									break;
							}
					}
				}
				$output .=  '</tr>';
			}
			echo balanceTags($output, true);
	}
}
/*
* Fontawsome POPup
*/
if ( ! function_exists( 'cs_fontawsome_popup_load' ) ) {
	function cs_fontawsome_popup_load(){
	?>
	<ul class='form-elements'>
	  <li class='to-label'>
		<label><?php _e('Fontawsome Icon','directory');?></label>
	  </li>
	  <li>
		<div class="cs-custom-fonts">
		  <div class="cs-font-header">
			<input type="serach" placeholder="Serach icon" class="cs-search-icon">
			<input type="hidden" class="cs-search-icon-hidden" name="counter_icon">
		  </div>
		  <div class="cs-font-container" id="fixed-height-icons">
			<?php cs_fontawsome_icons_box();?>
		  </div>
		</div>
	  </li>
	</ul>
	<?php
	}
	add_action('wp_ajax_cs_fontawsome_popup_load', 'cs_fontawsome_popup_load');
}


// Rendering functions

/*
* Dynamic Custom Pirce Fields
*/
if ( ! function_exists( 'cs_sale_fields_render' ) ) {
	function cs_sale_fields_render($post_id=''){
			global $post,$cs_xmlObject;
			if(isset($cs_xmlObject->dynamic_post_sale_oldprice)){$dynamic_post_sale_oldprice = $cs_xmlObject->dynamic_post_sale_oldprice;} else {$dynamic_post_sale_oldprice = '';}
			if(isset($cs_xmlObject->dynamic_post_sale_newprice)){$dynamic_post_sale_newprice = $cs_xmlObject->dynamic_post_sale_newprice;} else {$dynamic_post_sale_newprice = '';}
			$output = '
			<ul class="form-elements noborder">
					<li class="to-label"><label>'.__('Old Price','directory').'</label></li>
					<li class="to-field">
						'.$dynamic_post_sale_oldprice.'
					</li>
					<li class="to-label"><label>'.__('New Price','directory').'</label></li>
					<li class="to-field">
						'.$dynamic_post_sale_newprice.'
					</li>
				</ul>
			';
			echo balanceTags($output, true);	
	}
}

/*
* Dynamic Custom Fields Display Old
*/
if ( ! function_exists( 'cs_custom_fields_display_old' ) ) {
	function cs_custom_fields_display_old(){
		global $post,$cs_node,$cs_xmlObject;
		$cs_value = '';
		$html = '';
		$cs_customfield_required = '';
		if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required == 'yes'){
			$cs_customfield_required = 'required';
		}
		$output = '';
		switch( $cs_node->getName() ){
			case 'text' :
				// prepare
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
 						if($cs_value){
							$output .= '<small><i class="fa '.$cs_node->cs_customfield_icon.'"></i>'.$cs_node->cs_customfield_label.'</small><span>'.$cs_value . '</span>';
							$html .= $output;
						}
				}
				break;
			case 'range' :
				// prepare
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
						if($cs_value){
							$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i>'.$cs_node->cs_customfield_label.' <span>'.$cs_value . '</span>';
							$html .= $output;
						}
				}
				break;
			case 'email' :
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
						$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i>'.$cs_node->cs_customfield_label.' <span>
						<a href="mailto:'.$cs_value.'">'.$cs_value . '</a></span>';
						$html .= $output;
				}
				
				break;
			case 'url' :
				if(isset($cs_node->cs_customfield_name)){
					$key = $cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
						if($cs_value){
							$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i>'.$cs_node->cs_customfield_label.' <span><a href="'.$cs_value.'">'.$cs_value . '</a></span>';
							$html .= $output;
						}
				}
				break;
			case 'date' :
				// prepare
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
						if($cs_value){
							$cs_value	= date_i18n(get_option( 'date_format' ),strtotime( $cs_value ));
							$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i>'.$cs_node->cs_customfield_label.' <span>'.$cs_value . '</span>';
							$html .= $output;
						}
				}
				break;
			case 'multiselect' :
				if(isset($cs_node->cs_customfield_name)){}
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
					if($cs_value){
						$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i>'.$cs_node->cs_customfield_label.' <span>'.$cs_value . '</span>';
						$html .= $output;
					}
				
				break;
			case 'textarea' :
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
					if($cs_value){
						$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i>'.$cs_node->cs_customfield_label.' <span>'.$cs_value . '</span>';
					}
				}
				$html .= $output;
				break;
			case 'dropdown' :
				$cs_value = '';
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = $cs_value12 = get_post_meta($post->ID, $key, true);
						if($cs_value){
							$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i>'.$cs_node->cs_customfield_label.' <span>'.$cs_value . '</span>';
							$html .= $output;
						}
					}
				break;
			default :
				break;
		}
		return $html;
	}
}

/*
* Dynamic Custom Fields Display
*/
if ( ! function_exists( 'cs_custom_fields_display' ) ) {
	function cs_custom_fields_display($cs_post_id = ''){
		global $post,$cs_node,$cs_xmlObject;
		$cs_post_id = (isset($cs_post_id) and $cs_post_id <> '') ? $cs_post_id :  ''; 
		$cs_value = '';
		$html = '';
		$cs_customfield_required = '';
		if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required == 'yes'){
			$cs_customfield_required = 'required';
		}
		$output = '';
 		switch( $cs_node->getName() )
		{
			case 'text' :
				// prepare
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($cs_post_id, $key, true);
						if( isset( $cs_value ) && $cs_value !='' ){
							$output .= '<small><i class="fa '.$cs_node->cs_customfield_icon.'"></i><span class="cs-label">'.$cs_node->cs_customfield_label.'</small></span><span>'.$cs_value . '</span>';
							$html .= $output;
						}
				}
				break;
			case 'range' :
				// prepare
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($cs_post_id, $key, true);
						if( isset( $cs_value ) && $cs_value !='' ){
							$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i><span class="cs-label">'.$cs_node->cs_customfield_label.' </span><span>'.$cs_value . '</span>';
							$html .= $output;
						}
				}
				break;
			case 'email' :
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($cs_post_id, $key, true);
						if( isset( $cs_value ) && $cs_value !='' ){
							$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i><span class="cs-label">'.$cs_node->cs_customfield_label.' </span><span><a href="mailto:'.$cs_value.'">'.$cs_value . '</a></span>';
							$html .= $output;
						}
				}
				
				break;
			case 'url' :
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key)) {
						$cs_value = get_post_meta($cs_post_id, $key, true);
						if( isset( $cs_value ) && $cs_value !='' ){
							$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i><span class="cs-label">'.$cs_node->cs_customfield_label.' </span><span><a href="'.$cs_value.'">'.$cs_value . '</a></span>';
							$html .= $output;
						}
					}
				}
				break;
			case 'date' :
				// prepare
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($cs_post_id, $key, true);
						$cs_value	= date_i18n(get_option( 'date_format' ),strtotime( $cs_value ));
						if( isset( $cs_value ) && $cs_value !='' ){
							$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i><span class="cs-label">'.$cs_node->cs_customfield_label.' </span><span>'.$cs_value . '</span>';
							$html .= $output;
						}
				}
				break;
			case 'multiselect' :
				if(isset($cs_node->cs_customfield_name)){}
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($cs_post_id, $key, true);
						if( isset( $cs_value ) && $cs_value !='' ){
							$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i><span class="cs-label">'.$cs_node->cs_customfield_label.' </span><span>'.$cs_value . '</span>';
							$html .= $output;
						}
				
				break;
			case 'textarea' :
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($cs_post_id, $key, true);
						if( isset( $cs_value ) && $cs_value !='' ){
							$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i><span class="cs-label">'.$cs_node->cs_customfield_label.' </span><span>'.$cs_value . '</span>';
						}
				}
				$html .= $output;
				break;
			case 'dropdown' :
				$cs_value = '';
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key) and $key <> '')
						$cs_value = $cs_value12 = get_post_meta($cs_post_id, $key, true);
						if( isset( $cs_value ) && $cs_value !='' ){
							$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i><span class="cs-label">'.$cs_node->cs_customfield_label.'</span><span>'.$cs_value . '</span>';
							$html .= $output;
						}
				}
				break;
			default :
				break;
		}
		return $html;
	}
}

/*
* Single Custom Fields Display
*/
if ( ! function_exists( 'cs_single_custom_fields' ) ) {
	function cs_single_custom_fields(){
		global $post,$cs_node,$cs_xmlObject;
		$cs_value = '';
		$html = '';
		$cs_customfield_required = '';
		if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required == 'yes'){
			$cs_customfield_required = 'required';
		}
		$output = '';
		switch( $cs_node->getName() )
		{
			case 'text' :
				// prepare
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
						$cs_value = $cs_value ? $cs_value : '-';
							$output .= '<td><small><i class="fa '.$cs_node->cs_customfield_icon.'"></i><div class="specification-info">'.$cs_node->cs_customfield_label.'</small><span>'.$cs_value . '</span></div></td>';
							$html .= $output;
				}
				break;
			case 'range' :
				// prepare
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
						$cs_value = $cs_value ? $cs_value : '-';
							$output .= '<td><i class="fa '.$cs_node->cs_customfield_icon.'"></i><div class="specification-info">'.$cs_node->cs_customfield_label.' <span>'.$cs_value . '</span></div></td>';
							$html .= $output;
				}
				break;
			case 'email' :
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
						$cs_value = $cs_value ? $cs_value : '-';
						$output .= '<td><i class="fa '.$cs_node->cs_customfield_icon.'"></i><div class="specification-info">'.$cs_node->cs_customfield_label.' <span><a href="mailto:'.$cs_value.'">'.$cs_value . '</a></span></div></td>';
						$html .= $output;
				}
				
				break;
			case 'url' :
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, "$key", true);
						$cs_value = $cs_value ? $cs_value : '-';
							$output .= '<td><i class="fa '.$cs_node->cs_customfield_icon.'"></i><div class="specification-info">'.$cs_node->cs_customfield_label.' <span><a href="'.$cs_value.'">'.$cs_value.'</a></span></div></td>';
							$html .= $output;
				}
				break;
			case 'date' :
				// prepare
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
						$cs_value = $cs_value ? $cs_value : '-';
						$cs_value	= date_i18n(get_option( 'date_format' ),strtotime( $cs_value ));
						$output .= '<td><i class="fa '.$cs_node->cs_customfield_icon.'"></i><div class="specification-info">'.$cs_node->cs_customfield_label.' <span>'.$cs_value . '</span></div></td>';
						$html .= $output;
				}
				break;
			case 'multiselect' :
				if(isset($cs_node->cs_customfield_name)){}
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
						$cs_value = $cs_value ? $cs_value : '-';
						$output .= '<td><i class="fa '.$cs_node->cs_customfield_icon.'"></i><div class="specification-info">'.$cs_node->cs_customfield_label.' <span>'.$cs_value . '</span></div></td>';
						$html .= $output;
				
				break;
			case 'textarea' :
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
						$cs_value = $cs_value ? $cs_value : '-';
						$output .= '<td><i class="fa '.$cs_node->cs_customfield_icon.'"></i><div class="specification-info">'.$cs_node->cs_customfield_label.' <span>'.$cs_value . '</span></div></td>';
				}
				$html .= $output;
				break;
			case 'dropdown' :
				$cs_value = '';
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = $cs_value12 = get_post_meta($post->ID, $key, true);
						$cs_value = $cs_value ? $cs_value : '-';
						$output .= '<td><i class="fa '.$cs_node->cs_customfield_icon.'"></i><div class="specification-info">'.$cs_node->cs_customfield_label.' <span>'.$cs_value . '</span></div></td>';
						$html .= $output;
				}
				break;
			default :
				break;
		}
		return $html;
	}
}

/*
* Autotrader Custom Fields Display
*/
if ( ! function_exists( 'cs_custom_fields_autotrader' ) ) {
	function cs_custom_fields_autotrader(){
		global $post,$cs_node,$cs_xmlObject;
		$cs_value = '';
		$html = '';
		$cs_customfield_required = '';
		if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required == 'yes'){
			$cs_customfield_required = 'required';
		}
		$output = '';
		switch( $cs_node->getName() )
		{
			case 'text' :
				// prepare
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
						if($cs_value){
							$output .= '<small><i class="fa '.$cs_node->cs_customfield_icon.'"></i></small><span>'. $cs_value . '</span>';
							$html .= $output;
						}
				}
				break;
			case 'range' :
				// prepare
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
						if($cs_value){
							$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i><span>' .$cs_value . '</span>';
							$html .= $output;
						}
				}
				break;
			case 'email' :
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
						$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i><span><a href="mailto:'.$cs_value.'">' .$cs_value . '</a></span>';
						$html .= $output;
				}
				
				break;
			case 'url' :
				if(isset($cs_node->cs_customfield_name)){
					$key = $cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
						if($cs_value){
							$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i><span><a href="'.$cs_value.'">' .$cs_value . '</a></span>';
							$html .= $output;
						}
				}
				break;
			case 'date' :
				// prepare
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
						if($cs_value){
							$cs_value	= date_i18n(get_option( 'date_format' ),strtotime( $cs_value ));
							$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i><span>' .$cs_value . '</span>';
							$html .= $output;
						}
				}
				break;
			case 'multiselect' :
				if(isset($cs_node->cs_customfield_name)){}
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
					if($cs_value){
						$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i><span>' .$cs_value . '</span>';
						$html .= $output;
					}
				
				break;
			case 'textarea' :
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = get_post_meta($post->ID, $key, true);
					if($cs_value){
						$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i><span>' .$cs_value . '</span>';
					}
				}
				$html .= $output;
				break;
			case 'dropdown' :
				$cs_value = '';
				if(isset($cs_node->cs_customfield_name)){
					$key = (string)$cs_node->cs_customfield_name;
					if(isset($key))
						$cs_value = $cs_value12 = get_post_meta($post->ID, $key, true);
						
					if($cs_value){
						$output .= '<i class="fa '.$cs_node->cs_customfield_icon.'"></i><span>' .$cs_value . '</span>';
						$html .= $output;
					}
				}
				break;
			default :
				break;
		}
		return $html;
	}
}

/*
* FAQS Display
*/
function cs_faqs_display(){
	global $post,$cs_xmlObject;
	if ( isset($cs_xmlObject->faqs) && is_object($cs_xmlObject) && count($cs_xmlObject->faqs)>0) {
		$counter_faq = 0;
		$output = '';
		foreach ( $cs_xmlObject->faqs as $faqs ){
				 $faq_title = $faqs->faq_title;
				 $faq_description = $faqs->faq_description;
				 $counter_faq++;
				$output = '
						<ul class="form-elements noborder">
								<li class="to-label"><label>'.__('FAQ Title','directory').'</label></li>
								<li class="to-field">
									'.$faq_title.'
								</li>
								<li class="to-label"><label>'.__('FAQ Description','directory').'</label></li>
								<li class="to-field">
									'.$faq_description.'
								</li>
							</ul>
						';
		}
		echo balanceTags($output, true);	
	}
}
// ==========================================
// @ check custom field aleeady exist or  not
// ==========================================
if ( ! function_exists( 'cs_check_availabilty' ) ) {
	function cs_check_availabilty(){
		global $post;
		$json				= array();
		$cs_temp_names	    = array ();
		$cs_temp_names_1	    = array ();
		$cs_temp_names_2	    = array ();
		$cs_temp_names_3	    = array ();
		$cs_temp_names_4	    = array ();
		$cs_temp_names_5	    = array ();
		$cs_temp_names_6	    = array ();
		
		$cs_field_name	    =  $_REQUEST['name'];
		$form_field_names	=  isset( $_REQUEST['text']['cs_customfield_name'] ) ? $_REQUEST['text']['cs_customfield_name'] : array ();
		$form_field_names_1	=  isset( $_REQUEST['textarea']['cs_customfield_name'] ) ? $_REQUEST['textarea']['cs_customfield_name'] : array ();
		$form_field_names_2	=  isset( $_REQUEST['dropdown']['cs_customfield_name'] ) ? $_REQUEST['dropdown']['cs_customfield_name'] : array ();
		$form_field_names_3	=  isset( $_REQUEST['date']['cs_customfield_name'] ) ? $_REQUEST['date']['cs_customfield_name'] : array ();
		$form_field_names_4	=  isset( $_REQUEST['email']['cs_customfield_name'] ) ? $_REQUEST['email']['cs_customfield_name'] : array ();
		$form_field_names_5	=  isset( $_REQUEST['url']['cs_customfield_name'] ) ? $_REQUEST['url']['cs_customfield_name'] : array ();
		$form_field_names_6	=  isset( $_REQUEST['range']['cs_customfield_name'] ) ? $_REQUEST['range']['cs_customfield_name'] : array ();

		
		$form_field_names = array_merge($form_field_names, $form_field_names_1, $form_field_names_2, $form_field_names_3, $form_field_names_4, $form_field_names_5, $form_field_names_6);
		
		
		$length  = count( array_keys( $form_field_names, $cs_field_name ));
		
		if ( $cs_field_name =='' ){
			$json['type']		= 'error';
			$json['message']	= '<i class="icon-times"></i> Field name is required.';
		} else {
			if ( isset( $_REQUEST['id'] ) && $_REQUEST['id'] !='' && $_REQUEST['id'] != '0' ) {	
				$cs_dcpt_custom_fields = get_post_meta($_REQUEST['id'], "cs_directory_custom_fields", true);
				$cs_customfields_object = new SimpleXMLElement($cs_dcpt_custom_fields);
				if(isset($cs_customfields_object->custom_fields_elements) && $cs_customfields_object->custom_fields_elements == '1'){
					if(count($cs_customfields_object)>1){
						foreach ( $cs_customfields_object->children() as $cs_node ){
							
							if( preg_match('/\s/',$cs_field_name ) ) {
								$json['type']		= 'error';
								$json['message']	= '<i class="icon-times"></i> Whitespaces not allowed.';
								echo json_encode( $json );
	 							die();
							}
							
							if ( trim( $cs_node->cs_customfield_name ) ==  trim( $cs_field_name ) ) {
	
								if ( in_array( trim( $cs_field_name ), $form_field_names  ) && $length > 1 ) {
									$json['type']		= 'error';
									$json['message']	= '<i class="icon-times"></i> Name already exist.';
								} else {
									$json['type']		= 'success';
									$json['message']	= '<i class="icon-checkmark6"></i> Name Available.';
								}
							} else {
								if ( in_array( trim( $cs_field_name ), $form_field_names  ) && $length > 1 ) {
									$json['type']		= 'error';
									$json['message']	= '<i class="icon-times"></i> Name already exist.';
								} else {
									$json['type']		= 'success';
									$json['message']	= '<i class="icon-checkmark6"></i> Name Availabl.';
								}
							}
						}
					}
				}
		  } else {
			
			if( preg_match('/\s/',$cs_field_name ) ) {
				$json['type']		= 'error';
				$json['message']	= '<i class="icon-times"></i>Whitespaces not allowed.';
				echo json_encode( $json );
				die();
			}
							
			if ( in_array( trim( $cs_field_name ), $form_field_names  ) && $length > 1 ) {
				$json['type']		= 'error';
				$json['message']	= '<i class="icon-times"></i> Name already exist.';
			} else {
				$json['type']		= 'success';
				$json['message']	= '<i class="icon-checkmark6"></i> Name Available.';
			}
		 }
	  }
	  echo json_encode( $json );
	  die();
	}
	add_action('wp_ajax_cs_check_availabilty', 'cs_check_availabilty');
}

/*-----------------------------------------------------------
 * Get Attached Categories
 *---------------------------------------------------------*/
if ( ! function_exists( 'cs_get_attached_categories' ) ) {
	function cs_get_attached_categories(){
		global $post;
		
		$cs_category_array	= array();
		$args = array(
			'posts_per_page'	=> "-1",
			'post_type'         => 'directory_types',
			'post_status'       => array('publish','pending','draft'),
			'post__not_in' 		=> array($post->ID)
		);
		
		
		$custom_query   = new WP_Query($args);
		if ( $custom_query->have_posts() <> "" ) { 
			
			 while ( $custom_query->have_posts() ): $custom_query->the_post();
				$cs_dr_categories    = get_post_meta(get_the_ID(), "directory_types_categories", true);
				$cs_dr_categories	 = explode(',',$cs_dr_categories);
				$cs_category_array	 = array_merge($cs_category_array,$cs_dr_categories);	
			  endwhile;
			
		}
		
	   wp_reset_query();
	
	   return array_unique($cs_category_array);
	   
	}
}

/*
 * @Image Title
 *
 */
add_filter( 'wp_get_attachment_image_attributes', 'add_title_to_attachment_image', 10, 2 );
function add_title_to_attachment_image( $attr, $attachment ) {
    $attr['title'] = esc_attr( $attachment->post_title );
    return $attr;
}

