<?php

//======================================================================
// Members html form for page builder start
//======================================================================

if ( ! function_exists( 'cs_pb_members' ) ) {
	function cs_pb_members($die = 0){
		global $cs_node, $post, $wp_roles;
		$shortcode_elemen	= '';
		$filter_element		= 'filterdrag';
		$shortcode_view		= '';
		$output				= array();
		$counter			= $_POST['counter'];
		$cs_counter			= $_POST['counter'];
		
		if ( isset( $_POST['action'] ) && !isset( $_POST['shortcode_element_id'] ) ) {
			$POSTID					= '';
			$shortcode_element_id	= '';
			
		} else {
			
			$POSTID					= $_POST['POSTID'];
			$shortcode_element_id	= $_POST['shortcode_element_id'];
			$shortcode_str			= stripslashes ($shortcode_element_id);
			$PREFIX					= 'cs_members';
			$parseObject			= new ShortcodeParse();
			$output					= $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
		}
		
		$defaults = array(
						'var_pb_members_title'			=> '',
						'var_contact_fields'			=> '',
						'var_pb_members_register_text'	=> '',
						'var_pb_members_register_url'	=> '',
						'var_pb_members_register_color'	=> '',
						'var_pb_members_description'	=> 'on',
						'var_pb_members_roles'			=> '',
						'var_pb_members_filterable'		=> '',
						'var_pb_members_azfilterable'	=> '',
						'var_pb_members_pagination'		=> '',
						'var_pb_members_all_tab'		=> '',
						'var_pb_members_per_page'		=> get_option("posts_per_page"),
						'var_pb_member_view'			=> '',
						'cs_members_class'				=> '',
						'cs_members_animation'			=> ''
					);
					
		if( isset( $output['0']['atts'] ) )
			$atts = $output['0']['atts'];
		else 
			$atts = array();
		$members_element_size = '50';
		foreach( $defaults as $key=>$values ) {
			if( isset( $atts[$key] ) )
				$$key = $atts[$key];
			else 
				$$key = $values;
		}
		$name			= 'cs_pb_members';
		$coloumn_class	= 'column_'.$members_element_size;
			
		if( isset( $_POST['shortcode_element'] ) && $_POST['shortcode_element'] == 'shortcode' ){
			$shortcode_element	= 'shortcode_element_class';
			$shortcode_view		= 'cs-pbwp-shortcode';
			$filter_element		= 'ajax-drag';
			$coloumn_class		= '';
		}
		
		if ( $var_pb_members_roles ){
			$var_pb_members_roles = explode(",", $var_pb_members_roles);
		}
		
		$rand_ID = rand(1, 99999);
	?>
		
        <div id="<?php echo cs_allow_special_char( $name.$cs_counter )?>_del" class="column parentdelete <?php echo cs_allow_special_char( $coloumn_class ); ?> <?php echo cs_allow_special_char( $shortcode_view );?>" item="members" data="<?php echo cs_element_size_data_array_index( $members_element_size ); ?>">
            <?php cs_element_setting( $name, $cs_counter, $members_element_size );?>
            <div class="cs-wrapp-class-<?php echo cs_allow_special_char( $cs_counter ); ?> <?php echo cs_allow_special_char( $shortcode_element ); ?>" id="<?php echo cs_allow_special_char( $name.$cs_counter );?>" data-shortcode-template="[cs_members {{attributes}}]" style="display: none;">
                <div class="cs-heading-area">
                    <h5><?php _e('Edit Members Options','directory');?></h5>
                    <a href="javascript:removeoverlay('<?php echo cs_allow_special_char( $name.$cs_counter )?>','<?php echo cs_allow_special_char( $filter_element );?>')" class="cs-btnclose"><i class="icon-times"></i></a>
                </div>
                <div class="cs-pbwp-content">
                    <div class="cs-wrapp-clone cs-shortcode-wrapp">
                        <?php
                        if( isset( $_POST['shortcode_element'] ) && $_POST['shortcode_element'] == 'shortcode' ){
							cs_shortcode_element_size();
						}
						?>
                        <ul class="form-elements">
                            <li class="to-label">
                                <label><?php _e('Section Title','directory');?></label>
								
								
								
                            </li>
                            <li class="to-field">
                                <input type="text" name="var_pb_members_title[]" class="txtfield" value="<?php echo sanitize_text_field($var_pb_members_title); ?>" />
                            </li>
                        </ul>
                        
                        <ul class="form-elements">
                            <li class="to-label">
                                <label><?php _e('Member Views','directory');?></label>
                            </li>
                            <li class="to-field select-style">
                                <select class="cs_size" name="var_pb_member_view[]">
                                    <option value="default" <?php if( $var_pb_member_view	== 'default' )	{echo 'selected="selected"';} ?>><?php _e('List View','directory');?></option>
                                    <option value="grid" <?php if( $var_pb_member_view		== 'grid' )		{echo 'selected="selected"';} ?>><?php _e('Grid View','directory');?></option>
                                    <option value="crousel" <?php if( $var_pb_member_view	== 'crousel' )	{echo 'selected="selected"';} ?>><?php _e('Simple View','directory');?></option>
                                </select>
                            </li>
                        </ul>
                        
                        <ul class="form-elements">
                            <li class="to-label">
                                <label><?php _e('Member Roles','directory');?> </label>
                            </li>
                            <li class="to-field">
                                <select name="var_pb_members_roles[<?php echo cs_allow_special_char($rand_ID )?>][]" multiple="multiple" class="multiselect" style="min-height:100px;">
									<?php 
                                    $roles = $wp_roles->get_names();
                                    foreach( $roles as $role_key=>$role ) {
										if( in_array($role_key, $var_pb_members_roles) ) {
											$selected = 'selected';
										}
										else{
											$selected = '';
										}
										echo '<option '.$selected.' value="'.$role_key.'" >'.$role.'</option>';
									}
                                    ?>
                                </select>
                            </li>
                        </ul>
                        
                        <ul class="form-elements">
                            <li class="to-label">
                                <label><?php _e('Filterable','directory');?></label>
                            </li>
                            <li class="to-field select-style">
                                <select name="var_pb_members_filterable[]" onchange="cs_members_all_tab(this.value, <?php echo cs_allow_special_char($cs_counter)?>)">
                                <option value="on" <?php if( $var_pb_members_filterable		== "on" )	echo "selected"; ?>><?php _e('On','directory');?></option>
                                <option value="off" <?php if( $var_pb_members_filterable	== "off" )	echo "selected"; ?>><?php _e('Off','directory');?></option>
                                </select>
                            </li>
                        </ul>
                        
                        <ul class="form-elements">
                            <li class="to-label">
                                <label><?php _e('Alphabetical Filterable','directory');?></label>
                            </li>
                            <li class="to-field select-style">
                                <select name="var_pb_members_azfilterable[]">
                                <option value="on" <?php if( $var_pb_members_azfilterable	== "on" )	echo "selected"; ?>><?php _e('On','directory');?></option>
                                <option value="off" <?php if( $var_pb_members_azfilterable	== "off" )	echo "selected"; ?>><?php _e('Off','directory');?></option>
                                </select>
                            </li>
                        </ul>
                        <ul class="form-elements" id="members_all_tab<?php echo cs_allow_special_char($cs_counter);?>" <?php if($var_pb_members_filterable=="on"){ echo 'style="display: block;"';} else { echo 'style="display: none;"';}?>>
                            <li class="to-label">
                                <label><?php _e('Show All Tab','directory');?></label>
                            </li>
                            <li class="to-field select-style">
                                <select name="var_pb_members_all_tab[]">
                                    <option value="on" <?php if( $var_pb_members_all_tab	== "on" )	echo "selected"; ?>><?php _e('On','directory');?></option>
                                    <option value="off" <?php if( $var_pb_members_all_tab	== "off" )	echo "selected"; ?>><?php _e('Off','directory');?></option>
                                </select>
                            </li>
                        </ul>
                        
                        <ul class="form-elements">
                            <li class="to-label">
                                <label><?php _e('Member Contact fields on/off','directory');?></label>
                            </li>
                            <li class="to-field select-style">
                                <select name="var_contact_fields[]">
                                    <option value="on" <?php if( $var_contact_fields	== "on" )	echo "selected"; ?>><?php _e('On','directory');?></option>
                                    <option value="off" <?php if( $var_contact_fields	== "off" )	echo "selected"; ?>><?php _e('Off','directory');?></option>
                                </select>
                            </li>
                        </ul>
                        
                        <ul class="form-elements">
                            <li class="to-label">
                                <label><?php _e('Description','directory');?></label>
                            </li>
                            <li class="to-field select-style">
                                <select name="var_pb_members_description[]">
                                    <option value="on" <?php if( $var_pb_members_description	== "on" )	echo "selected"; ?>><?php _e('On','directory');?></option>
                                    <option value="off" <?php if( $var_pb_members_description	== "off" )	echo "selected"; ?>><?php _e('Off','directory');?></option>
                                </select>
                                 <p><?php _e('work only for list view','directory');?></p>
                            </li>
                        </ul>
                        
                        <ul class="form-elements bcevent_title">
                            <li class="to-label">
                                <label><?php _e('Register Button','directory');?></label>
                            </li>
                            <li class="to-field">
                                <div class="input-sec">
                                    <input type="text" id="var_pb_members_register_text" name="var_pb_members_register_text[]" value="<?php echo sanitize_text_field($var_pb_members_register_text); ?>" />
                                    <label><?php _e('Title','directory');?></label>
                                </div>
                                <div class="input-sec">
                                    <input type="text" id="var_pb_members_register_url" name="var_pb_members_register_url[]" value="<?php echo sanitize_text_field($var_pb_members_register_url); ?>" />
                                    <label><?php _e('Url','directory');?></label>
                                </div>
                                <div class="input-sec">
                                    <input type="text" name="var_pb_members_register_color[]" value="<?php echo sanitize_text_field($var_pb_members_register_color); ?>" class="bg_color" />
                                    <label><?php _e('Color','directory');?></label>
                                </div>
                            </li>
                        </ul>
                        
                        <ul class="form-elements">
                            <li class="to-label">
                                <label><?php _e('Pagination','directory');?></label>
                            </li>
                            <li class="to-field select-style">
                                <select name="var_pb_members_pagination[]" class="dropdown" >
                                    <option <?php if($var_pb_members_pagination == "Show Pagination")	echo "selected"; ?>><?php _e('Show Pagination','directory');?></option>
                                    <option <?php if($var_pb_members_pagination == "Single Page")		echo "selected"; ?>><?php _e('Single Page','directory');?></option>
                                </select>
                            </li>
                        </ul>
                        
                        <ul class="form-elements">
                            <li class="to-label">
                                <label><?php _e('No. of Members Per Page','directory');?></label>
                            </li>
                            <li class="to-field">
                                <input type="text" name="var_pb_members_per_page[]" class="txtfield" value="<?php echo intval($var_pb_members_per_page);?>" />
                                <p><?php _e('To display all the records, leave this field blank.','directory');?></p>
                            </li>
                        </ul>
                        
                        <?php 
                        if ( function_exists( 'cs_shortcode_custom_dynamic_classes' ) ) {
							cs_shortcode_custom_dynamic_classes($cs_members_class, $cs_members_animation, '', 'cs_members');
                        }
                        
						if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
						
						?>
                            <ul class="form-elements">
                                <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo str_replace('cs_pb_', '', $name);?>','<?php echo cs_allow_special_char($name.$cs_counter)?>','<?php echo cs_allow_special_char($filter_element);?>')" ><?php _e('Insert','directory');?></a> </li>
                            </ul>
                            <div id="results-shortocde"></div>
                        
						<?php
                        }
						else {
						?>
                            <ul class="form-elements noborder">
                                <li class="to-label"></li>
                                <li class="to-field">
                                    <input type="hidden" name="cs_orderby[]" value="members" />
                                    <input type="hidden" name="cs_members_counter[]" value="<?php echo cs_allow_special_char($rand_ID); ?>" />
                                    <input type="button" value="Save" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
                                </li>
                            </ul>
                        <?php
						}
						?>
                    </div>
                </div>
            </div>
        </div>
        
	<?php
		if ( $die <> 1 ) die();
	}
	add_action('wp_ajax_cs_pb_members', 'cs_pb_members');
}