<?php
/*
* Directory Types Featured Fields
*/
// RenderDynamic Custom Fields
if ( ! function_exists( 'cs_dynamic_custom_fields' ) ) {
	function cs_dynamic_custom_fields(){
		global $post;
		?>
			<div class="inside-tab-content">
				<div class="dragitem">
					<h4><?php _e('CLICK TO ADD ITEM','directory')?></h4>
					<div class="pb-form-buttons">
						<a href="javascript:ajaxSubmit('cs_pb_text')" title="Text" data-type="text" data-name="custom_text"><i class="icon-file-text-o"></i><?php _e('Text','directory')?></a>
						<a href="javascript:ajaxSubmit('cs_pb_textarea')" title="Textarea" data-type="textarea" data-name="custom_textarea"><i class="icon-text"></i><?php _e('Text area','directory')?></a>
						<a href="javascript:ajaxSubmit('cs_pb_dropdown')" title="Dropdown" data-type="select" data-name="custom_select"><i class="icon-download10"></i><?php _e('Dropdown','directory')?></a>
						<a href="javascript:ajaxSubmit('cs_pb_date')" title="Date" data-type="date" data-name="custom_date"><i class="icon-calendar-o"></i><?php _e('Date','directory')?></a>
						<a href="javascript:ajaxSubmit('cs_pb_email')" title="Email" data-type="email" data-name="custom_email"><i class="icon-envelope4"></i><?php _e('Email','directory')?></a>
						<a href="javascript:ajaxSubmit('cs_pb_url')" title="URL" data-type="url" data-name="custom_url"><i class="icon-link4"></i><?php _e('url','directory')?></a>
                        <a href="javascript:ajaxSubmit('cs_pb_range')" title="URL" data-type="url" data-name="custom_range"><i class=" icon-target5"></i><?php _e('Range','directory')?></a>
					</div>
				</div>
                <form method="post" class="" id="cs-custom-fields" enctype="multipart/form-data">
				<div id="pb-formelements" class="cs-custom-fields">
               
					<?php
                        global $cs_node, $cs_count_node, $cs_xmlObject;
                        $cs_count_node = 0;
                        $count_widget = 0;
                        $cs_dcpt_custom_fields = get_post_meta($post->ID, "cs_directory_custom_fields", true);
                        if ( $cs_dcpt_custom_fields <> "" ) {
                            $cs_xmlObject = new stdClass();
                            $cs_xmlObject = new SimpleXMLElement($cs_dcpt_custom_fields);
                                foreach ( $cs_xmlObject->children() as $cs_node ){
                                    if ( $cs_node->getName() == "text" ) {$cs_count_node++; cs_pb_text(1); }
                                    else if ( $cs_node->getName() == "textarea" ) {$cs_count_node++; cs_pb_textarea(1);}
                                    else if ( $cs_node->getName() == "dropdown" ) {$cs_count_node++; cs_pb_dropdown(1);}
                                    else if ( $cs_node->getName() == "date" ) {$cs_count_node++; cs_pb_date(1);}
                                    else if ( $cs_node->getName() == "email" ) {$cs_count_node++; cs_pb_email(1);}
                                    else if ( $cs_node->getName() == "url" ) {$cs_count_node++; cs_pb_url(1);}
                                    else if ( $cs_node->getName() == "range" ) {$cs_count_node++; cs_pb_range(1);}
                                 }
                        }
                    ?>
                    <script>
						jQuery(document).ready(function($) {
							cs_check_availabilty();
						});
					</script>
                     <div class="alert alert-warning" id="pbwp-alert"><?php _e('PLEASE INSERT ITEMS','directory');?></div>
                    <input type="hidden" name="custom_fields_elements" value="1" />
                
			</div>
				</form>
        
			<script type="text/javascript">
				jQuery(function() {
				   cs_custom_fields_js();
				});
				var counter = <?php echo esc_js($cs_count_node); ?>;
				function ajaxSubmit(action){
					counter++;
					var newCustomerForm = "action=" + action + '&counter=' + counter;
					jQuery.ajax({
						type:"POST",
						url: "<?php echo esc_js(admin_url('admin-ajax.php'));?>",
						data: newCustomerForm,
						success:function(data){
							jQuery("#pb-formelements").append(data);
							 //alertbox()
							//if (count_widget > 0) jQuery("#pb-formelements").addClass('hasclass');
						}
					});
					//return false;
				}
			</script>   
		 </div>
		<?php
	}
}
// Save Custom Fields
if ( ! function_exists( 'cs_custom_fields_elements_save' ) ) {
	if ( isset($_POST['custom_fields_elements']) and $_POST['custom_fields_elements'] == 1 ) {
		add_action( 'save_post', 'cs_custom_fields_elements_save' );
		function cs_custom_fields_elements_save( $post_id ) {
			if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
			if ( isset($_POST['custom_fields_elements']) ) {
				$cs_counter = 0;
				$cs_field_counter = 0;
				$cs_counter_text = 0;
				$cs_counter_dropdown = 0;
				$cs_counter_textarea = 0;
				$cs_counter_email = 0;
				$cs_counter_date = 0;
				$cs_counter_multiselect = 0;
				$cs_counter_url = 0;
				$cs_counter_range = 0;
				$sxe = new SimpleXMLElement("<customfieldsbuilder></customfieldsbuilder>");
				$sxe->addChild('custom_fields_elements', $_POST['custom_fields_elements'] );
				
				if(isset($_POST['cs_customfield_order'])){
					foreach ( $_POST['cs_customfield_order'] as $count ){
					$cs_counter++;
					
					if ( $_POST['cs_customfield_order'][$cs_field_counter] == "text" ) {
						$text = $sxe->addChild('text');
						if ( isset($_POST['cs_customfield_id'][$cs_field_counter])){
							$cs_custom_field_id = $_POST['cs_customfield_id'][$cs_field_counter];
						}
						$cs_customfield_label = $_POST['text']['cs_customfield_label'][$cs_counter_text];

						foreach ( $_POST['text'] as $text_key => $text_value ){
							
							$cs_customfield_name	= '';
							$cs_customfield_name    = trim ( $_POST['text']['cs_customfield_name'][$cs_counter_text] );
							if( $cs_customfield_name == '' ){
								$cs_customfield_name = $cs_customfield_label.'_'.cs_generate_random_string(2);
							}
						
							if( $text_key == 'cs_customfield_name' && $text_value[$cs_counter_text] == '' ){
								$value	= trim( $cs_customfield_name );
							} else {
								$value	= trim($text_value[$cs_counter_text]);
							}
							$text->addChild($text_key, $value );
						}
						$cs_counter_text++;
					} 
					else if ( $_POST['cs_customfield_order'][$cs_field_counter] == "textarea" ) {
						
						$textarea = $sxe->addChild('textarea');
						$cs_customfield_label = $_POST['textarea']['cs_customfield_label'][$cs_counter_textarea];
						if(empty($cs_customfield_label)){
							$cs_customfield_label = cs_generate_random_string(5);
						}
						foreach ( $_POST['textarea'] as $textarea_key=>$textarea_value ){
							
							$cs_customfield_name	= '';
							$cs_customfield_name    = trim ( $_POST['textarea']['cs_customfield_name'][$cs_counter_textarea] );
							if( $cs_customfield_name == '' ){
								$cs_customfield_name = $cs_customfield_label.'_'.cs_generate_random_string(2);
							}
						
							if( $textarea_key == 'cs_customfield_name' && $textarea_value[$cs_counter_textarea] == '' ){
								$value	= trim( $cs_customfield_name );
							} else {
								$value	= trim($textarea_value[$cs_counter_textarea]);
							}
							$textarea->addChild($textarea_key, $value );

						}
						if ( isset($_POST['cs_customfield_order'][$cs_field_counter])){
							$cs_custom_field_id = $_POST['cs_customfield_order'][$cs_field_counter];
						}
						$cs_counter_textarea++;
					} 
					else if ( $_POST['cs_customfield_order'][$cs_field_counter] == "range" ) {
						$range = $sxe->addChild('range');
						$cs_customfield_label = $_POST['range']['cs_customfield_label'][$cs_counter_range];
						if(empty($cs_customfield_label)){
							$cs_customfield_label = cs_generate_random_string(5);
						}
						foreach ( $_POST['range'] as $range_key=>$range_value ){
							
							$cs_customfield_name	= '';
							$cs_customfield_name    = trim ( $_POST['text']['cs_customfield_name'][$cs_counter_range] );
							if( $cs_customfield_name == '' ){
								$cs_customfield_name = $cs_customfield_label.'_'.cs_generate_random_string(2);
							}
						
							if( $range_key == 'cs_customfield_name' && $range_value[$cs_counter_range] == '' ){
								$value	= trim( $cs_customfield_name );
							} else {
								$value	= trim($range_value[$cs_counter_range]);
							}
							$range->addChild($range_key, $value );
						}
						if ( isset($_POST['cs_customfield_order'][$cs_field_counter])){
							$cs_custom_field_id = $_POST['cs_customfield_order'][$cs_field_counter];
						}
						$cs_counter_range++;
						
					} 
					else if ( $_POST['cs_customfield_order'][$cs_field_counter] == "dropdown" ) {
						$dropdown = $sxe->addChild('dropdown');
						$cs_customfield_label = $_POST['dropdown']['cs_customfield_label'][$cs_counter_dropdown];
						if(empty($cs_customfield_label)){
							$cs_customfield_label = cs_generate_random_string(5);
						}
						
						foreach ( $_POST['dropdown'] as $dropdown_key=>$dropdown_value ){
							
							$cs_customfield_name	= '';
							$cs_customfield_name    = trim ( $_POST['text']['cs_customfield_name'][$cs_counter_dropdown] );
							if( $cs_customfield_name == '' ){
								$cs_customfield_name = $cs_customfield_label.'_'.cs_generate_random_string(2);
							}
						
							if( $dropdown_key == 'cs_customfield_name' && $dropdown_value[$cs_counter_dropdown] == '' ){
								$value	= trim( $cs_customfield_name );
							} else {
								$value	= trim($dropdown_value[$cs_counter_dropdown]);
							}
							$dropdown->addChild($dropdown_key, $value );
						}
						if ( isset($_POST['cs_customfield_id'][$cs_field_counter])){
							$cs_custom_field_id = $_POST['cs_customfield_id'][$cs_field_counter];
						}
						$dropdown->addChild('selected', $_POST['cs_dropdown_option']['selected'][$cs_custom_field_id][0] );
						
						foreach ( $_POST['cs_dropdown_option']['options'][$cs_custom_field_id] as $dropdown_key=>$dropdown_value ){
							$dropdown->addChild('options', trim($dropdown_value) );
						}
						foreach ( $_POST['cs_dropdown_option']['options_values'][$cs_custom_field_id] as $dropdown_key=>$dropdown_value ){
							$option_value	= cs_str_replacer( trim( $dropdown_value ) );
							$dropdown->addChild('options_values', trim($option_value) );
						}
						$cs_counter_dropdown++;
					} 
					else if ( $_POST['cs_customfield_order'][$cs_field_counter] == "email" ) {
						$email = $sxe->addChild('email');
						$cs_customfield_label = $_POST['email']['cs_customfield_label'][$cs_counter_email];
						if(empty($cs_customfield_label)){
							$cs_customfield_label = cs_generate_random_string(5);
						}
						
						foreach ( $_POST['email'] as $email_key=>$email_value ){
							
							$cs_customfield_name	= '';
							$cs_customfield_name    = trim ( $_POST['text']['cs_customfield_name'][$cs_counter_email] );
							if( $cs_customfield_name == '' ){
								$cs_customfield_name = $cs_customfield_label.'_'.cs_generate_random_string(2);
							}
						
							if( $email_key == 'cs_customfield_name' && $email_value[$cs_counter_email] == '' ){
								$value	= trim( $cs_customfield_name );
							} else {
								$value	= trim($email_value[$cs_counter_email]);
							}
							$email->addChild($email_key, $value );

						}
						$cs_counter_email++;
					} 
					else if ( $_POST['cs_customfield_order'][$cs_field_counter] == "date" ) {
						$date = $sxe->addChild('date');
						$cs_customfield_label = $_POST['date']['cs_customfield_label'][$cs_counter_date];
						if(empty($cs_customfield_label)){
							$cs_customfield_label = cs_generate_random_string(5);
						}
						foreach ( $_POST['date'] as $date_key=>$date_value ){
							
							$cs_customfield_name	= '';
							$cs_customfield_name    = trim ( $_POST['text']['cs_customfield_name'][$cs_counter_date] );
							if( $cs_customfield_name == '' ){
								$cs_customfield_name = $cs_customfield_label.'_'.cs_generate_random_string(2);
							}
						
							if( $date_key == 'cs_customfield_name' && $date_value[$cs_counter_date] == '' ){
								$value	= trim( $cs_customfield_name );
							} else {
								$value	= trim($date_value[$cs_counter_date]);
							}
							$date->addChild($date_key, $value );
							
						}
						$cs_counter_date++;
					} 
					else if ( $_POST['cs_customfield_order'][$cs_field_counter] == "url" ) {
						$url = $sxe->addChild('url');
						$cs_customfield_label = $_POST['url']['cs_customfield_label'][$cs_counter_url];
						if(empty($cs_customfield_label)){
							$cs_customfield_label = cs_generate_random_string(5);
						}
						foreach ( $_POST['url'] as $url_key=>$url_value ){
							
							$cs_customfield_name	= '';
							$cs_customfield_name    = trim ( $_POST['text']['cs_customfield_name'][$cs_counter_url] );
							if( $cs_customfield_name == '' ){
								$cs_customfield_name = $cs_customfield_label.'_'.cs_generate_random_string(2);
							}
						
							if( $url_key == 'cs_customfield_name' && $url_value[$cs_counter_url] == '' ){
								$value	= trim( $cs_customfield_name );
							} else {
								$value	= trim($url_value[$cs_counter_url]);
							}
							$url->addChild($url_key, $value );
						}
						$cs_counter_url++;
					}
					$cs_field_counter++;
				 }
				}
				update_post_meta( $post_id, 'cs_directory_custom_fields', $sxe->asXML() );
			}
		}
	}
}
// Text Custom Fields
if ( ! function_exists( 'cs_pb_text' ) ) {
	function cs_pb_text($die='0'){
		global $post,$cs_node,$cs_count_node;
		if(isset($_REQUEST['counter'])){
			$counter = $_REQUEST['counter'];
		} else {
			$counter = $cs_count_node;
		}
		?>
        
		<div class="pb-item-container">
        <script>
			jQuery(document).ready(function($) {
				cs_check_availabilty();
			});
		</script>
			<div  class="pbwp-legend">
				<input type="hidden" name="cs_customfield_order[]" value="text" />
				<input type="hidden" name="cs_customfield_id[]" value="<?php echo esc_attr($counter);?>" />
				<div class="pbwp-label"><i class="icon-file-text-o"></i> Text : <?php if(isset($cs_node->cs_customfield_label)){echo esc_attr($cs_node->cs_customfield_label);}?></div>
				<div class="pbwp-actions">
					<a class="pbwp-remove" href="#"><i class="icon-times"></i></a>
					<a class="pbwp-toggle" href="#"><i class="icon-sort-down"></i></a>
				</div>
			</div>
			<div class="pbwp-form-holder" style="display:none;">
				<div class="pbwp-form-rows required-field">
					<label><?php _e('Required','directory')?></label>
					<div class="pbwp-form-sub-fields select-style">
						<select name="text[cs_customfield_required][]">
							<option value="no" <?php if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required=="no"){echo 'selected="selected"';}?>><?php _e('No','directory')?></option>
							<option value="yes" <?php if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required=="yes"){echo 'selected="selected"';}?>><?php _e('Yes','directory')?></option>
						</select>
					</div>
				</div>
				<div class="pbwp-form-rows">
					<label><?php _e('Title','directory')?></label>
					<input type="text" title="" class="smallipopInput sipInitialized smallipop49" value="<?php if(isset($cs_node->cs_customfield_label)){echo esc_attr($cs_node->cs_customfield_label);}?>" name="text[cs_customfield_label][]" data-type="label">
               
				
                 </div>
                 <div class="pbwp-form-rows">
					<label><?php _e('Meta Key','directory')?></label>
					<input type="text" data-id="<?php echo intval( isset( $_GET['post'] ) ? $_GET['post'] : ''  );?>" id="check_field_name" value="<?php if( isset( $cs_node->cs_customfield_name ) ){echo esc_attr($cs_node->cs_customfield_name);}?>" name="text[cs_customfield_name][]">
                    <span class="name-checking"></span>
                     <p><?php _e('Please enter name without special character and space.','directory')?></p>
                 </div>
                <div class="pbwp-form-rows">
					<label><?php _e('Help Text','directory')?></label>
					<textarea title="" class="smallipopInput sipInitialized smallipop51" name="text[cs_customfield_help][]"><?php if(isset($cs_node->cs_customfield_help)){echo esc_attr($cs_node->cs_customfield_help);}?></textarea>
				</div>
                <div class="pbwp-form-rows">
					<label><?php _e('Placeholder text','directory')?></label>
					<input type="text" value="<?php if(isset($cs_node->cs_customfield_placeholder)){echo esc_attr($cs_node->cs_customfield_placeholder);}?>" title="" name="text[cs_customfield_placeholder][]" class="smallipopInput sipInitialized smallipop53">
                </div>
                <div class="pbwp-form-rows required-field">
                    <label><?php _e('Enable Field for Search','directory')?></label>
                    <div class="pbwp-form-sub-fields select-style">
                        <select name="text[cs_customfield_enable_search][]">
                           <option value="no" <?php if(isset($cs_node->cs_customfield_enable_search) && $cs_node->cs_customfield_enable_search=="no"){echo 'selected="selected"';}?>><?php _e('No','directory')?></option>
                           <option value="yes" <?php if(isset($cs_node->cs_customfield_enable_search) && $cs_node->cs_customfield_enable_search=="yes"){echo 'selected="selected"';}?>><?php _e('Yes','directory')?></option>
                           
                        </select>
                    </div>
                </div>
				<div class="pbwp-form-rows"  id="cs_infobox_<?php echo esc_attr($counter);?>">
                <label> <?php _e('Choose Icon','directory')?></label>
                  <?php 
                  if( function_exists('cs_fontawsome_icons_box')){
                      if(isset($cs_node->cs_customfield_icon)){
                          $cs_customfield_icon = esc_attr($cs_node->cs_customfield_icon);
                      } else {
                         $cs_customfield_icon = ''; 
                      }
                      cs_iconpicker_directory( $cs_customfield_icon, $counter,'text[cs_customfield_icon][]');
					  
                  }
                  ?>
                 </div>
				
				<div class="pbwp-form-rows">
					<label><?php _e('Default value','directory')?></label>
					<input type="text" value="<?php if(isset($cs_node->cs_customfield_default)){echo esc_attr($cs_node->cs_customfield_default);}?>" title="" name="text[cs_customfield_default][]" class="smallipopInput sipInitialized smallipop54">
                 </div>
			</div>
		</div>
		
		<?php
		if($die<>1) die();
		
	}
	add_action('wp_ajax_cs_pb_text', 'cs_pb_text');
}
// Textarea Custom Fields
if ( ! function_exists( 'cs_pb_textarea' ) ) {
	function cs_pb_textarea($die='0'){
		global $post,$cs_node,$cs_count_node;
		if(isset($_REQUEST['counter'])){
			$counter = $_REQUEST['counter'];
		} else {
			$counter = $cs_count_node;
		}
		?>
		<div class="pb-item-container">
        <script>
			jQuery(document).ready(function($) {
				cs_check_availabilty();
			});
		</script>
			<div title="Click and Drag to rearrange" class="pbwp-legend">
			<input type="hidden" name="cs_customfield_order[]" value="textarea" />
			<input type="hidden" name="cs_customfield_id[]" value="<?php echo esc_attr($counter);?>" />
				<div class="pbwp-label"><i class="icon-text"></i> Textarea: <?php if(isset($cs_node->cs_customfield_label)){echo esc_attr($cs_node->cs_customfield_label);}?></div>
				<div class="pbwp-actions">
					<a class="pbwp-remove" href="#"><i class="icon-times"></i></a>
					<a class="pbwp-toggle" href="#"><i class="icon-sort-down"></i></a>
				</div>
			</div>
				<div class="pbwp-form-holder" style="display:none;">
					<div class="pbwp-form-rows required-field">
                        <label><?php _e('Required','directory');?></label>
							<div class="pbwp-form-sub-fields select-style">
								<select name="textarea[cs_customfield_required][]">
								   <option value="no" <?php if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required=="no"){echo 'selected="selected"';}?>><?php _e('No','directory')?></option>
							<option value="yes" <?php if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required=="yes"){echo 'selected="selected"';}?>><?php _e('Yes','directory')?></option>
								</select>
							</div>
						</div>
						<div class="pbwp-form-rows">
							<label><?php _e('Title','directory')?></label>
							<input type="text" title="" class="smallipopInput sipInitialized smallipop56" value="<?php if(isset($cs_node->cs_customfield_label)){echo esc_attr($cs_node->cs_customfield_label);}?>" name="textarea[cs_customfield_label][]" data-type="label">
						</div>
                        <div class="pbwp-form-rows">
                            <label><?php _e('Meta Key','directory')?></label>
                            <input type="text" data-id="<?php echo intval( isset( $_GET['post'] ) ? $_GET['post'] : ''  );?>" id="check_field_name" value="<?php if( isset( $cs_node->cs_customfield_name ) ){echo esc_attr($cs_node->cs_customfield_name);}?>" name="textarea[cs_customfield_name][]">
                            <span class="name-checking"></span>
                            <p><?php _e('Please enter name without special character and space.','directory')?></p>
                        </div>
						<div class="pbwp-form-rows">
							<label><?php _e('Help Text','directory')?></label>
							<textarea title="" class="smallipopInput sipInitialized smallipop58" name="textarea[cs_customfield_help][]"><?php if(isset($cs_node->cs_customfield_help)){echo esc_attr($cs_node->cs_customfield_help);}?></textarea>
						</div>
                        <div class="pbwp-form-rows">
							<label><?php _e('Placeholder text','directory')?></label>
							<input type="text" value="<?php if(isset($cs_node->cs_customfield_placeholder)){echo esc_attr($cs_node->cs_customfield_placeholder);}?>" title="" name="textarea[cs_customfield_placeholder][]" class="smallipopInput sipInitialized smallipop62">
						</div>
						<div class="pbwp-form-rows"  id="cs_infobox_<?php echo esc_attr($counter);?>">
							<label> <?php _e('Choose Icon','directory')?></label>
						<?php 
                        if( function_exists('cs_fontawsome_icons_box')){
							if(isset($cs_node->cs_customfield_icon)){
								$cs_customfield_icon = esc_attr($cs_node->cs_customfield_icon);
							} else {
								$cs_customfield_icon = '';
							}
							cs_iconpicker_directory( $cs_customfield_icon, $counter,'textarea[cs_customfield_icon][]');
                        }
						?>
                        </div>
                        <div class="pbwp-form-rows">
							<label><?php _e('Rows','directory')?></label>
							<input type="text" value="<?php if(isset($cs_node->cs_customfield_rows)){echo esc_attr($cs_node->cs_customfield_rows);} else {echo '5';}?>" title="" name="textarea[cs_customfield_rows][]" class="smallipopInput sipInitialized smallipop60">
						</div>
						<div class="pbwp-form-rows">
							<label><?php _e('Columns','directory')?></label>
							<input type="text" value="<?php if(isset($cs_node->cs_customfield_cols)){echo esc_attr($cs_node->cs_customfield_cols);} else {echo '25';}?>" title="" name="textarea[cs_customfield_cols][]" class="smallipopInput sipInitialized smallipop61">
						</div>
						
						<div class="pbwp-form-rows">
							<label><?php _e('Default value','directory')?></label>
							<input type="text" value="<?php if(isset($cs_node->cs_customfield_default)){echo esc_attr($cs_node->cs_customfield_default);}?>" title="" name="textarea[cs_customfield_default][]" class="smallipopInput sipInitialized smallipop63">
						</div>
					  </div>
					</div>
		<?php
		if($die<>1) die();
	}
	add_action('wp_ajax_cs_pb_textarea', 'cs_pb_textarea');
}

// Range Custom Fields
if ( ! function_exists( 'cs_pb_range' ) ) {
	function cs_pb_range($die='0'){
		global $post,$cs_node,$cs_count_node;
		if(isset($_REQUEST['counter'])){
			$counter = $_REQUEST['counter'];
		} else {
			$counter = $cs_count_node;
		}
		?>
		<div class="pb-item-container">
        <script>
			jQuery(document).ready(function($) {
				cs_check_availabilty();
			});
		</script>
			<div title="Click and Drag to rearrange" class="pbwp-legend">
			<input type="hidden" name="cs_customfield_order[]" value="range" />
			<input type="hidden" name="cs_customfield_id[]" value="<?php echo esc_attr($counter);?>" />
				<div class="pbwp-label"><i class=" icon-target5"></i> Range : <?php if(isset($cs_node->cs_customfield_label)){echo esc_attr($cs_node->cs_customfield_label);}?></div>
				<div class="pbwp-actions">
					<a class="pbwp-remove" href="#"><i class="icon-times"></i></a>
					<a class="pbwp-toggle" href="#"><i class="icon-sort-down"></i></a>
				</div>
			</div>
				 <div class="pbwp-form-holder" style="display:none;">
						<div class="pbwp-form-rows">
							<label><?php _e('Title','directory')?></label>
							<input type="text" title="" class="smallipopInput sipInitialized smallipop56" value="<?php if(isset($cs_node->cs_customfield_label)){echo esc_attr($cs_node->cs_customfield_label);}?>" name="range[cs_customfield_label][]" data-type="label">
						</div>
                        <div class="pbwp-form-rows">
                            <label><?php _e('Meta Key','directory')?></label>
                            <input type="text" data-id="<?php echo intval( isset( $_GET['post'] ) ? $_GET['post'] : ''  );?>" id="check_field_name" value="<?php if( isset( $cs_node->cs_customfield_name ) ){echo esc_attr($cs_node->cs_customfield_name);}?>" name="range[cs_customfield_name][]">
                            <span class="name-checking"></span>
                             <p><?php _e('Please enter name without special character and space.','directory')?></p>
                        </div>
                        <div class="pbwp-form-rows">
							<label><?php _e('Help Text','directory')?></label>
							<textarea title="" class="smallipopInput sipInitialized smallipop58" name="range[cs_customfield_help][]"><?php if(isset($cs_node->cs_customfield_help)){echo esc_attr($cs_node->cs_customfield_help);}?></textarea>
						</div>
                        <div class="pbwp-form-rows">
							<label><?php _e('Placeholder text','directory')?></label>
							<input type="text" value="<?php if(isset($cs_node->cs_customfield_placeholder)){echo esc_attr($cs_node->cs_customfield_placeholder);}?>" title="" name="range[cs_customfield_placeholder][]" class="smallipopInput sipInitialized smallipop62">
						</div>
                        <div class="pbwp-form-rows">
							<label><?php _e('Min Value','directory'); ?> </label>
							<input type="text" title="" class="smallipopInput sipInitialized smallipop57" value="<?php if(isset($cs_node->cs_customfield_min_input)){echo esc_attr($cs_node->cs_customfield_min_input);}?>" name="range[cs_customfield_min_input][]" data-type="name">
                            <div class='left-info'>
                            	<p> 
                              		<span class="cs-form-desc"><?php _e('Set the Integer Value','directory')?></span>
                              	</p>
                              </div>
						</div>
                        <div class="pbwp-form-rows">
							<label><?php _e('Max Value','directory'); ?></label>
							<input type="text" title="" class="smallipopInput sipInitialized smallipop57" value="<?php if(isset($cs_node->cs_customfield_max_input)){echo esc_attr($cs_node->cs_customfield_max_input);}?>" name="range[cs_customfield_max_input][]" data-type="name">
                            <div class='left-info'>
                            	<p> 
                              		<span class="cs-form-desc"><?php _e('Set the Integer Value','directory')?></span>
                              	</p>
                              </div>
						</div>
                        <div class="pbwp-form-rows">
							<label>Increment Steps</label>
                           
							<input type="text" title="" class="smallipopInput sipInitialized smallipop57" value="<?php if(isset($cs_node->cs_customfield_incrstep_input)){echo esc_attr($cs_node->cs_customfield_incrstep_input);}?>" name="range[cs_customfield_incrstep_input][]" data-type="name">
                            <div class='left-info'>
                            	<p> 
                              		<span class="cs-form-desc"> <?php _e('Set the Integer Value','directory')?></span>
                              	</p>
                              </div>
						</div>
                        <div class="pbwp-form-rows required-field">
                            <label>Search Style</label>
                            <div class="pbwp-form-sub-fields select-style">
                                <select name="range[cs_customfield_search_style][]">
                                	<option value="Inputs" <?php if(isset($cs_node->cs_customfield_search_style) && $cs_node->cs_customfield_search_style=="Inputs"){echo 'selected="selected"';}?>>Inputs</option>
                                    <option value="Slider" <?php if(isset($cs_node->cs_customfield_search_style) && $cs_node->cs_customfield_search_style=="Slider"){echo 'selected="selected"';}?>>Slider</option>
                                    <option value="Slider_Inputs" <?php if(isset($cs_node->cs_customfield_search_style) && $cs_node->cs_customfield_search_style=="Slider_Inputs"){echo 'selected="selected"';}?>>Slider + Inputs</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="pbwp-form-rows required-field">
                            <label><?php _e('Enable Input','directory')?></label>
                            <div class="pbwp-form-sub-fields select-style">
                                <select name="range[cs_customfield_enable_input][]">
                                   <option value="yes" <?php if(isset($cs_node->cs_customfield_enable_input) && $cs_node->cs_customfield_enable_input=="yes"){echo 'selected="selected"';}?>><?php _e('Yes','directory')?></option>
                                   <option value="no" <?php if(isset($cs_node->cs_customfield_enable_input) && $cs_node->cs_customfield_enable_input=="no"){echo 'selected="selected"';}?>><?php _e('No','directory')?></option>
                                </select>
                            </div>
                        </div>
                        <div class="pbwp-form-rows required-field">
                            <label>Enable Field for Search</label>
                            <div class="pbwp-form-sub-fields select-style">
                                <select name="range[cs_customfield_enable_search][]">
                                	<option value="no" <?php if(isset($cs_node->cs_customfield_enable_search) && $cs_node->cs_customfield_enable_search=="no"){echo 'selected="selected"';}?>><?php _e('No','directory')?></option>
                                   <option value="yes" <?php if(isset($cs_node->cs_customfield_enable_search) && $cs_node->cs_customfield_enable_search=="yes"){echo 'selected="selected"';}?>><?php _e('Yes','directory')?>Yes</option>
                                   
                                </select>
                            </div>
                        </div>
						
						<div class="pbwp-form-rows"  id="cs_infobox_<?php echo esc_attr($counter);?>">
							<label> Choose Icon</label>
                          <?php 
						  if( function_exists('cs_fontawsome_icons_box')){
							  if(isset($cs_node->cs_customfield_icon)){
								  $cs_customfield_icon = esc_attr($cs_node->cs_customfield_icon);
							  } else {
								 $cs_customfield_icon = ''; 
							  }
							  cs_iconpicker_directory( $cs_customfield_icon, $counter,'range[cs_customfield_icon][]');
						  }
						  ?>
                         </div>
						
						<div class="pbwp-form-rows">
							<label><?php _e('Default value','directory')?></label>
							<input type="text" value="<?php if(isset($cs_node->cs_customfield_default)){echo esc_attr($cs_node->cs_customfield_default);}?>" title="" name="range[cs_customfield_default][]" class="smallipopInput sipInitialized smallipop63">
						</div>
					  </div>
					</div>
		<?php
		if($die<>1) die();
	}
	add_action('wp_ajax_cs_pb_range', 'cs_pb_range');
}

// Dropdown Custom Fields
if ( ! function_exists( 'cs_pb_dropdown' ) ) {
	function cs_pb_dropdown($die='0'){
		global $post,$cs_node,$cs_count_node;
		if(isset($_REQUEST['counter'])){
			$counter = $_REQUEST['counter'];
		} else {
			$counter = $cs_count_node;
		}
		?>	
		<div class="pb-item-container">
        <script>
			jQuery(document).ready(function($) {
				cs_check_availabilty();
			});
		</script>
		<div title="Dropdown" class="pbwp-legend">
			   <input type="hidden" name="cs_customfield_order[]" value="dropdown" />
			   <input type="hidden" name="cs_customfield_id[]" value="<?php echo esc_attr($counter);?>" />
				<div class="pbwp-label"><i class="icon-download10"></i> <?php _e('Dropdown','directory')?>: <?php if( isset( $cs_node->cs_customfield_label )){echo esc_attr($cs_node->cs_customfield_label);}?></div>
				<div class="pbwp-actions">
					<a class="pbwp-remove" href="#"><i class="icon-times"></i></a>
					<a class="pbwp-toggle" href="#"><i class="icon-sort-down"></i></a>
				</div>
			</div>
		<div class="pbwp-form-holder" style="display:none;">
			 <div class="pbwp-form-rows required-field">
				<label>Required</label>
				<div class="pbwp-form-sub-fields select-style">
					<select name="dropdown[cs_customfield_required][]">
					   <option value="no" <?php if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required=="no"){echo 'selected="selected"';}?>><?php _e('No','directory')?></option>
					   <option value="yes" <?php if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required=="yes"){echo 'selected="selected"';}?>><?php _e('Yes','directory')?></option>
					</select>
				</div>
			</div> 
			<div class="pbwp-form-rows">
				<label>Title</label>
				<input type="text" title="" class="smallipopInput sipInitialized smallipop64" value="<?php if(isset($cs_node->cs_customfield_label)){echo esc_attr($cs_node->cs_customfield_label);}?>" name="dropdown[cs_customfield_label][]" data-type="label">
			</div>
            <div class="pbwp-form-rows">
                <label><?php _e('Meta Key','directory')?></label>
                <input type="text" data-id="<?php echo intval( isset( $_GET['post'] ) ? $_GET['post'] : ''  );?>" id="check_field_name" value="<?php if( isset( $cs_node->cs_customfield_name ) ){echo esc_attr($cs_node->cs_customfield_name);}?>" name="dropdown[cs_customfield_name][]">
                <span class="name-checking"></span>
                 <p><?php _e('Please enter name without special character and space.','directory')?></p>
            </div> 
            <div class="pbwp-form-rows">
				<label><?php _e('Help Text','directory')?></label>
				<textarea title="" class="smallipopInput sipInitialized smallipop66" name="dropdown[cs_customfield_help][]"><?php if(isset($cs_node->cs_customfield_help)){echo esc_attr($cs_node->cs_customfield_help);}?></textarea>
			</div> 
			 <!-- .pbwp-form-rows -->
			<div class="pbwp-form-rows required-field">
                <label><?php _e('Enable Field for Search','directory')?></label>
                <div class="pbwp-form-sub-fields select-style">
                    <select name="dropdown[cs_customfield_enable_search][]">
                    	<option value="no" <?php if(isset($cs_node->cs_customfield_enable_search) && $cs_node->cs_customfield_enable_search=="no"){echo 'selected="selected"';}?>><?php _e('No','directory')?></option>
                       <option value="yes" <?php if(isset($cs_node->cs_customfield_enable_search) && $cs_node->cs_customfield_enable_search=="yes"){echo 'selected="selected"';}?>><?php _e('Yes','directory')?></option>
                       
                    </select>
                </div>
            </div>
            
            <div class="pbwp-form-rows required-field">
                <label><?php _e('Enable Search multi select','directory')?></label>
                <div class="pbwp-form-sub-fields select-style">
                    <select name="dropdown[cs_customfield_enable_multiselect][]">
                       <option value="no" <?php if(isset($cs_node->cs_customfield_enable_multiselect) && $cs_node->cs_customfield_enable_multiselect=="no"){echo 'selected="selected"';}?>><?php _e('No','directory')?></option>
                       <option value="yes" <?php if(isset($cs_node->cs_customfield_enable_multiselect) && $cs_node->cs_customfield_enable_multiselect=="yes"){echo 'selected="selected"';}?>><?php _e('Yes','directory')?></option>
                    </select>
                </div>
            </div>
            
            <div class="pbwp-form-rows required-field">
                <label><?php _e('Enable Post multi Select','directory')?></label>
                <div class="pbwp-form-sub-fields select-style">
                    <select name="dropdown[cs_customfield_enable_post_multiselect][]">
                       <option value="no" <?php if(isset($cs_node->cs_customfield_enable_post_multiselect) && $cs_node->cs_customfield_enable_post_multiselect=="no"){echo 'selected="selected"';}?>><?php _e('No','directory')?></option>
                       <option value="yes" <?php if(isset($cs_node->cs_customfield_enable_post_multiselect) && $cs_node->cs_customfield_enable_post_multiselect=="yes"){echo 'selected="selected"';}?>><?php _e('Yes','directory')?></option>
                    </select>
                </div>
            </div>
			
            <div class="pbwp-form-rows"  id="cs_infobox_<?php echo esc_attr($counter);?>">
                <label><?php _e('Choose Icon','directory')?> </label>
              <?php 
              if( function_exists('cs_fontawsome_icons_box')){
                  if(isset($cs_node->cs_customfield_icon)){
                      $cs_customfield_icon = esc_attr($cs_node->cs_customfield_icon);
                  } else {
                     $cs_customfield_icon = ''; 
                  }
                  cs_iconpicker_directory( $cs_customfield_icon, $counter,'dropdown[cs_customfield_icon][]');
              }
              ?>
            </div>
			<div class="pbwp-form-rows">
				<label><?php _e('Select Text','directory')?></label>
				<input type="text" title="" value="<?php if(isset($cs_node->cs_customfield_first)){echo esc_attr($cs_node->cs_customfield_first);} else {echo '- select -';}?>" name="dropdown[cs_customfield_first][]" class="smallipopInput sipInitialized smallipop68">
			</div>
					<div class="pbwp-form-rows">
						<label><?php _e('Options','directory')?></label>
						<div class="pbwp-form-sub-fields pbwp-options">
						<label class="pbwp-show-field-value" for="pbwp-options_12">
						<div class="pbwp-option-label-value"><span>Label</span></div>
						<?php
						if(isset($cs_node->options)){
							$option_counter = 0;
							$option_radio_counter = 1;
							foreach($cs_node->options as $options_names){
							?>
								<div class="pbwp-clone-field">
									<input type="radio" <?php if((int)$option_radio_counter == (int)$cs_node->selected){echo 'checked="checked"';}?> name="cs_dropdown_option[selected][<?php echo esc_attr($counter);?>][]" value="<?php echo esc_attr($option_radio_counter);?>">
									<input type="text" value="<?php echo esc_attr($options_names);?>" name="cs_dropdown_option[options][<?php echo esc_attr($counter);?>][]" data-type="option">
									<input type="text" value="<?php echo cs_allow_special_char($cs_node->options_values[$option_counter]);?>" name="cs_dropdown_option[options_values][<?php echo esc_attr($counter);?>][]" data-type="option_value">
									<img src="<?php echo esc_url(get_template_directory_uri().'/include/assets/images/add.png');?>" class="pbwp-clone-field" title="add another choice" alt="add another choice" style="cursor:pointer; margin:0 3px;">
									<img src="<?php echo esc_url(get_template_directory_uri().'/include/assets/images/remove.png');?>" title="remove this choice" alt="remove this choice" class="pbwp-remove-field" style="cursor:pointer;">
								</div>
							<?php 
							$option_counter++;
							$option_radio_counter++;
							}
						} else {
								?>
								<div class="pbwp-clone-field">
									<input type="radio" checked="checked" name="cs_dropdown_option[selected][<?php echo esc_attr($counter);?>][]" value="1">
									<input type="text" value="" name="cs_dropdown_option[options][<?php echo esc_attr($counter);?>][]" data-type="option">
									<input type="text" value="" name="cs_dropdown_option[options_values][<?php echo esc_attr($counter);?>][]" data-type="option_value">
									<img src="<?php echo esc_url(get_template_directory_uri().'/include/assets/images/add.png');?>" class="pbwp-clone-field" title="add another choice" alt="add another choice" style="cursor:pointer; margin:0 3px;">
									<img src="<?php echo esc_url(get_template_directory_uri().'/include/assets/images/remove.png');?>" title="remove this choice" alt="remove this choice" class="pbwp-remove-field" style="cursor:pointer;">
								</div>
							<?php 
							
						}
						?>
						</div> <!-- .pbwp-form-sub-fields -->
					</div> <!-- .pbwp-form-rows -->
				</div>
			</div>
		<?php
		if($die<>1) die();
	}
	add_action('wp_ajax_cs_pb_dropdown', 'cs_pb_dropdown');
}
// Date Custom Fields
if ( ! function_exists( 'cs_pb_date' ) ) {
	function cs_pb_date($die='0'){
		global $post,$cs_node,$cs_count_node;
		if(isset($_REQUEST['counter'])){
			$counter = $_REQUEST['counter'];
		} else {
			$counter = $cs_count_node;
		}
		?>
		<div class="pb-item-container">
        <script>
			jQuery(document).ready(function($) {
				cs_check_availabilty();
			});
		</script>
		<div title="Click and Drag to rearrange" class="pbwp-legend">
				<input type="hidden" name="cs_customfield_order[]" value="date" />
				<input type="hidden" name="cs_customfield_id[]" value="<?php echo esc_attr($counter);?>" />
				<div class="pbwp-label"><i class="icon-calendar-o"></i>  Date: <?php if(isset($cs_node->cs_customfield_label)){echo esc_attr($cs_node->cs_customfield_label);}?></div>
				<div class="pbwp-actions">
					<a class="pbwp-remove" href="#"><i class="icon-times"></i></a>
					<a class="pbwp-toggle" href="#"><i class="icon-sort-down"></i></a>
				</div>
			</div>
		<div class="pbwp-form-holder" style="display:none;">
		   <div class="pbwp-form-rows required-field">
				<label><?php _e('Required','directory')?></label>
				<div class="pbwp-form-sub-fields select-style">
					<select name="date[cs_customfield_required][]">
						<option value="no" <?php if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required=="no"){echo 'selected="selected"';}?>><?php _e('No','directory')?></option>
						<option value="yes" <?php if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required=="yes"){echo 'selected="selected"';}?>><?php _e('Yes','directory')?></option>
					</select>
				</div>
			</div> 
			
			<div class="pbwp-form-rows">
				<label><?php _e('Title','directory')?></label>
				<input type="text" title="" class="smallipopInput sipInitialized smallipop69" value="<?php if(isset($cs_node->cs_customfield_label)){echo esc_attr($cs_node->cs_customfield_label);}?>" name="date[cs_customfield_label][]" data-type="label">
            </div> 
            <div class="pbwp-form-rows">
                <label><?php _e('Meta Key','directory')?></label>
                <input type="text" data-id="<?php echo intval( isset( $_GET['post'] ) ? $_GET['post'] : ''  );?>" id="check_field_name" value="<?php if( isset( $cs_node->cs_customfield_name ) ){echo esc_attr($cs_node->cs_customfield_name);}?>" name="date[cs_customfield_name][]">
                <span class="name-checking"></span>
                 <p><?php _e('Please enter name without special character and space.','directory')?></p>
            </div> 
            <div class="pbwp-form-rows">
				<label><?php _e('Help Text','directory')?></label>
				<textarea title="" class="smallipopInput sipInitialized smallipop71" name="date[cs_customfield_help][]"><?php if(isset($cs_node->cs_customfield_help)){echo esc_attr($cs_node->cs_customfield_help);}?></textarea>
			</div>
            <div class="pbwp-form-rows required-field">
                <label><?php _e('Enable Field for Search','directory')?></label>
                <div class="pbwp-form-sub-fields select-style">
                    <select name="date[cs_customfield_enable_search][]">
                    	<option value="no" <?php if(isset($cs_node->cs_customfield_enable_search) && $cs_node->cs_customfield_enable_search=="no"){echo 'selected="selected"';}?>><?php _e('No','directory')?></option>
                       <option value="yes" <?php if(isset($cs_node->cs_customfield_enable_search) && $cs_node->cs_customfield_enable_search=="yes"){echo 'selected="selected"';}?>><?php _e('Yes','directory')?></option>
                       
                    </select>
                </div>
            </div>
			
			<div class="pbwp-form-rows"  id="cs_infobox_<?php echo esc_attr($counter);?>">
                <label> <?php _e('Choose Icon','directory')?></label>
              <?php 
              if( function_exists('cs_fontawsome_icons_box')){
                  if(isset($cs_node->cs_customfield_icon)){
                      $cs_customfield_icon = esc_attr($cs_node->cs_customfield_icon);
                  } else {
                     $cs_customfield_icon = ''; 
                  }
                  cs_iconpicker_directory( $cs_customfield_icon, $counter,'date[cs_customfield_icon][]');
              }
              ?>
             </div>
			<div class="pbwp-form-rows">
				<label><?php _e('Date Format','directory')?></label>
				<input type="text" title="" value="<?php if(isset($cs_node->cs_customfield_format)){echo esc_attr($cs_node->cs_customfield_format);} else {echo 'd.m.Y H:i';}?>" name="date[cs_customfield_format][]" class="smallipopInput sipInitialized smallipop73">
                <span class="cs-form-desc"> <?php _e('Date Format:','directory')?>  d.m.Y H:i, Y/m/d  </span>
			</div>
			</div>
		</div>
		<?php
		if($die<>1) die();
	}
	add_action('wp_ajax_cs_pb_date', 'cs_pb_date');
}
// Email Custom Fields
if ( ! function_exists( 'cs_pb_email' ) ) {
	function cs_pb_email($die='0'){
		global $post,$cs_node,$cs_count_node;
		if(isset($_REQUEST['counter'])){
			$counter = $_REQUEST['counter'];
		} else {
			$counter = $cs_count_node;
		}
		?>
		<div class="pb-item-container">
               <div title="Click and Drag to rearrange" class="pbwp-legend">
                    <input type="hidden" name="cs_customfield_order[]" value="email" />
                    <input type="hidden" name="cs_customfield_id[]" value="<?php echo esc_attr($counter);?>" />
                    <div class="pbwp-label"><i class="icon-envelope4"></i><?php _e('Email','directory')?>  : <?php if(isset($cs_node->cs_customfield_label)){echo esc_attr($cs_node->cs_customfield_label);}?></div>
                    <div class="pbwp-actions">
                        <a class="pbwp-remove" href="#"><i class="icon-times"></i></a>
                        <a class="pbwp-toggle" href="#"><i class="icon-sort-down"></i></a>
                    </div>
                </div>
				<div class="pbwp-form-holder" style="display: none;">
					<div class="pbwp-form-rows required-field">
						<label><?php _e('Required','directory')?></label>
						<div class="pbwp-form-sub-fields select-style">
							<select name="email[cs_customfield_required][]">
								<option value="no" <?php if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required=="no"){echo 'selected="selected"';}?>><?php _e('No','directory')?></option>
								<option value="yes" <?php if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required=="yes"){echo 'selected="selected"';}?>><?php _e('Yes','directory')?></option>
							</select>
						</div>
					</div> 
					<div class="pbwp-form-rows">
						<label><?php _e('Title','directory')?></label>
						<input type="text" title="" class="smallipopInput sipInitialized smallipop31" value="<?php if(isset($cs_node->cs_customfield_label)){echo esc_attr($cs_node->cs_customfield_label);}?>" name="email[cs_customfield_label][]" data-type="label">
					</div>
					<div class="pbwp-form-rows">
                        <label><?php _e('Meta Key','directory')?></label>
                        <input type="text" data-id="<?php echo intval( isset( $_GET['post'] ) ? $_GET['post'] : ''  );?>" id="check_field_name" value="<?php if( isset( $cs_node->cs_customfield_name ) ){echo esc_attr($cs_node->cs_customfield_name);}?>" name="email[cs_customfield_name][]">
                        <span class="name-checking"></span>
                        <p><?php _e('Please enter name without special character and space.','directory')?></p>
                    </div>
                    <div class="pbwp-form-rows">
						<label><?php _e('Help Text','directory')?></label>
						<textarea title="" class="smallipopInput sipInitialized smallipop33" name="email[cs_customfield_help][]"><?php if(isset($cs_node->cs_customfield_help)){echo esc_attr($cs_node->cs_customfield_help);}?></textarea>
					</div>
                    <div class="pbwp-form-rows">
						<label><?php _e('Placeholder text','directory')?></label>
						<input type="text" value="<?php if(isset($cs_node->cs_customfield_placeholder)){echo esc_attr($cs_node->cs_customfield_placeholder);}?>" title="" name="email[cs_customfield_placeholder][]" class="smallipopInput sipInitialized smallipop35">
					</div>
                    <div class="pbwp-form-rows required-field">
                        <label><?php _e('Enable Field for Search','directory')?></label>
                        <div class="pbwp-form-sub-fields select-style">
                            <select name="email[cs_customfield_enable_search][]">
                               <option value="no" <?php if(isset($cs_node->cs_customfield_enable_search) && $cs_node->cs_customfield_enable_search=="no"){echo 'selected="selected"';}?>><?php _e('No','directory')?></option>
                               <option value="yes" <?php if(isset($cs_node->cs_customfield_enable_search) && $cs_node->cs_customfield_enable_search=="yes"){echo 'selected="selected"';}?>><?php _e('Yes','directory')?></option>
                               
                            </select>
                        </div>
                    </div>
					
                   <div class="pbwp-form-rows"  id="cs_infobox_<?php echo esc_attr($counter);?>">
                    <label><?php _e('Choose Icon','directory')?> </label>
					  <?php 
                      if( function_exists('cs_fontawsome_icons_box')){
                          if(isset($cs_node->cs_customfield_icon)){
                              $cs_customfield_icon = esc_attr($cs_node->cs_customfield_icon);
                          } else {
                             $cs_customfield_icon = ''; 
                          }
                          cs_iconpicker_directory( $cs_customfield_icon, $counter,'email[cs_customfield_icon][]');
                      }
                      ?>
                 </div>
					
					<div class="pbwp-form-rows">
						<label><?php _e('Default value','directory')?></label>
						<input type="text" value="<?php if(isset($cs_node->cs_customfield_default)){echo esc_attr($cs_node->cs_customfield_default);}?>" title="" name="email[cs_customfield_default][]" class="smallipopInput sipInitialized smallipop36">
					</div>
				</div>
			</div>
		<?php
		if($die<>1) die();
	}
	add_action('wp_ajax_cs_pb_email', 'cs_pb_email');
}
// url Custom Field
if ( ! function_exists( 'cs_pb_url' ) ) {
	function cs_pb_url($die='0'){
		global $post,$cs_node,$cs_count_node;
		if(isset($_REQUEST['counter'])){
			$counter = $_REQUEST['counter'];
		} else {
			$counter = $cs_count_node;
		}
		?>
		<div class="pb-item-container">
        <script>
			jQuery(document).ready(function($) {
				cs_check_availabilty();
			});
		</script>
			<div title="Click and Drag to rearrange" class="pbwp-legend">
				<input type="hidden" name="cs_customfield_order[]" value="url" />
				<input type="hidden" name="cs_customfield_id[]" value="<?php echo esc_attr($counter);?>" />
				<div class="pbwp-label"><i class="icon-link4"></i> <?php _e('url:','directory')?>  <?php if(isset($cs_node->cs_customfield_label)){echo esc_attr($cs_node->cs_customfield_label);}?></div>
				<div class="pbwp-actions">
					<a class="pbwp-remove" href="#"><i class="icon-times"></i></a>
					<a class="pbwp-toggle" href="#"><i class="icon-sort-down"></i></a>
				</div>
			</div>
			<div class="pbwp-form-holder" style="display:none;">
							<div class="pbwp-form-rows required-field">
				<label><?php _e('Required:','directory')?></label>
	
				<div class="pbwp-form-sub-fields select-style">
					<select name="url[cs_customfield_required][]">
						<option value="no" <?php if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required=="no"){echo 'selected="selected"';}?>><?php _e('No:','directory')?></option>
						<option value="yes" <?php if(isset($cs_node->cs_customfield_required) && $cs_node->cs_customfield_required=="yes"){echo 'selected="selected"';}?>><?php _e('Yes:','directory')?></option>
					</select>
				</div>
			</div>
			<div class="pbwp-form-rows">
				<label><?php _e('Title:','directory')?></label>
				<input type="text" title="" class="smallipopInput sipInitialized smallipop40" value=" <?php if(isset($cs_node->cs_customfield_label)){echo esc_attr($cs_node->cs_customfield_label);}?>" name="url[cs_customfield_label][]" data-type="label">
			</div>
			<div class="pbwp-form-rows">
                <label><?php _e('Meta Key:','directory')?></label>
                <input type="text" data-id="<?php echo intval( isset( $_GET['post'] ) ? $_GET['post'] : ''  );?>" id="check_field_name" value="<?php if( isset( $cs_node->cs_customfield_name ) ){echo esc_attr($cs_node->cs_customfield_name);}?>" name="url[cs_customfield_name][]">
                <span class="name-checking"></span>
                <p><?php _e('Please enter name without special character and space.','directory')?></p>
            </div> 
            <div class="pbwp-form-rows">
				<label><?php _e('Help Text','directory')?></label>
				<textarea title="" class="smallipopInput sipInitialized smallipop42" name="url[cs_customfield_help][]"> <?php if(isset($cs_node->cs_customfield_help)){echo esc_attr($cs_node->cs_customfield_help);}?></textarea>
			</div>
            <div class="pbwp-form-rows">
				<label><?php _e('Placeholder text','directory')?></label>
				<input type="text" value=" <?php if(isset($cs_node->cs_customfield_placeholder)){echo esc_attr($cs_node->cs_customfield_placeholder);}?>" title="" name="url[cs_customfield_placeholder][]" class="smallipopInput sipInitialized smallipop44">
			</div>
            <div class="pbwp-form-rows required-field">
                <label><?php _e('Enable Field for Search','directory')?></label>
                <div class="pbwp-form-sub-fields select-style">
                    <select name="url[cs_customfield_enable_search][]">
                       <option value="no" <?php if(isset($cs_node->cs_customfield_enable_search) && $cs_node->cs_customfield_enable_search=="no"){echo 'selected="selected"';}?>><?php _e('No','directory')?></option>
                       <option value="yes" <?php if(isset($cs_node->cs_customfield_enable_search) && $cs_node->cs_customfield_enable_search=="yes"){echo 'selected="selected"';}?>><?php _e('Yes','directory')?></option>
                       
                    </select>
                </div>
            </div>
			
            <div class="pbwp-form-rows"  id="cs_infobox_<?php echo esc_attr($counter);?>">
                <label><?php _e('Choose Icon','directory')?> </label>
				<?php 
                if( function_exists('cs_fontawsome_icons_box')){
					if(isset($cs_node->cs_customfield_icon)){
						$cs_customfield_icon = esc_attr($cs_node->cs_customfield_icon);
					} else {
						$cs_customfield_icon = ''; 
					}
					cs_iconpicker_directory( $cs_customfield_icon, $counter,'url[cs_customfield_icon][]');
                }
                ?>
             </div>
			
			<div class="pbwp-form-rows">
				<label><?php _e('Default value','directory')?></label>
				<input type="text" value=" <?php if(isset($cs_node->cs_customfield_default)){echo esc_attr($cs_node->cs_customfield_default);}?>" title="" name="url[cs_customfield_default][]" class="smallipopInput sipInitialized smallipop45">
			</div>
			</div>
		 </div>
		<?php
		if($die<>1) die();
	}
	add_action('wp_ajax_cs_pb_url', 'cs_pb_url');
}

/**
* Directory Categories
*/

if (!function_exists('cs_directory_fields_count')) {
	function cs_directory_fields_count(){
		if(isset($_POST['directory_id'])){
			$cs_directory_id = $_POST['directory_id'];
				?>
                <select name="cs_directory_fields_count[]" class="dropdown">
                	<option value="none"><?php _e('None','directory')?></option>
					<?php 
                    	$cs_dcpt_custom_fields = get_post_meta($cs_directory_id, "cs_directory_custom_fields", true);
                        if ( $cs_dcpt_custom_fields <> "" ) {
                        $cs_xmlObject = new stdClass();
                        $cs_xmlObject = new SimpleXMLElement($cs_dcpt_custom_fields);
							$cs_fields_length = sizeof( $cs_xmlObject );
							for($i = 1; $i<$cs_fields_length;$i++){
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
                        } else {
							for( $i = 1; $i < 11; $i++ ){
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            }
						}
                    ?>
				</select>
			<?php
 		}
		die();
	}
	add_action('wp_ajax_cs_directory_fields_count', 'cs_directory_fields_count');
	add_action('wp_ajax_nopriv_cs_directory_fields_count', 'cs_directory_fields_count');
}

function cs_str_replacer( $option_value='' ){
	$string = str_replace(' ', '-', $option_value);
	$string = str_replace("'", '', $string);
	//$option_value	= preg_replace('/[^A-Za-z0-9\-\']/', '', $string);
	$option_value	= $string;
	return $option_value;
}