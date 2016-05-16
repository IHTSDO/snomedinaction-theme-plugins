<?php
//======================================================================
// Directory Search Page Element
//======================================================================
if ( ! function_exists( 'cs_pb_directory_search' ) ) {
    function cs_pb_directory_search($die = 0){
        global $cs_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $counter = $_POST['counter'];
        $cs_counter = $_POST['counter'];
        if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes($shortcode_element_id);
            $PREFIX = 'cs_directory_search';
            $parseObject     = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
        $defaults = array( 'directory_search_title' => '','cs_directory_search_location'=>'','cs_directory_map'=>'Yes','cs_directory_map_style'=>'style-1','cs_search_views'=>'view-one','cs_field_lables'=>'Yes','goe_location_enable'=>'Yes','cs_directory_map_responsive'=>'Yes','cs_directory_location_suggestions'=>'','cs_directory_search_result_page'=>'', 'cs_directory_search_filter'=>'', 'directory_search_latitude'=>'', 'directory_search_longitude'=>'' ,'cs_directory_search_class' => '','cs_directory_search_animation' => '');
        if(isset($output['0']['atts']))
            $atts = $output['0']['atts'];
        else 
            $atts = array();
        $directory_search_element_size = '50';
        foreach($defaults as $key=>$values){
            if(isset($atts[$key]))
                $$key = $atts[$key];
            else 
                $$key =$values;
        }
		
        $name = 'cs_pb_directory_search';
        $coloumn_class = 'column_'.$directory_search_element_size;
        
		if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class	= '';
        }
		
    ?>
    <div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="directory" data="<?php echo cs_element_size_data_array_index($directory_search_element_size)?>" >
      <?php cs_element_setting($name,$cs_counter,$directory_search_element_size,'','graduation-cap');?>
      <div class="cs-wrapp-class-<?php echo esc_attr($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[cs_directory_search {{attributes}}]"  style="display: none;">
        <div class="cs-heading-area">
          <h5><?php _e('Edit Directory Search Options','directory');?></h5>
          <a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
        <div class="cs-pbwp-content">
          <div class="cs-wrapp-clone cs-shortcode-wrapp">
            <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
                <ul class="form-elements">
                    <li class="to-label"><label><?php _e('Directory Title','directory');?></label></li>
                    <li class="to-field">
                        <input type="text" name="directory_search_title[]" class="txtfield" value="<?php echo htmlspecialchars($directory_search_title)?>" />
                        <p><?php _e('Directory Search Section Title','directory');?></p>
                    </li>
                </ul>
                <ul class="form-elements">
                  <li class="to-label">
					<label><?php _e('Search Views','directory');?></label>
                  </li>
                  <li class="to-field select-style">
                    <select name="cs_search_views[]" class="dropdown">
                      <option <?php if($cs_search_views=="view-one")echo "selected";?> value="view-one"><?php _e('View 1','directory');?></option>
                      <option <?php if($cs_search_views=="view-two")echo "selected";?> value="view-two" ><?php _e('View 2','directory');?></option>
                      <option <?php if($cs_search_views=="view-three")echo "selected";?> value="view-three" ><?php _e('View 3','directory');?></option>
                    </select>
                  </li>
                </ul>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Labels On/Off','directory');?></label>
                  </li>
                  <li class="to-field select-style">
                    <select name="cs_field_lables[]" class="dropdown">
                      <option <?php if($cs_field_lables=="Yes")echo "selected";?> value="Yes"><?php _e('Yes','directory');?></option>
                      <option <?php if($cs_field_lables=="No")echo "selected";?> value="No" ><?php _e('No','directory');?></option>
                    </select>
                  </li>
                </ul>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Enable Map','directory');?></label>
                  </li>
                  <li class="to-field select-style">
                    <select name="cs_directory_map[]" class="dropdown">
                      <option <?php if($cs_directory_map=="Yes") echo "selected";?> value="Yes"><?php _e('Yes','directory');?></option>
                      <option <?php if($cs_directory_map=="No") echo "selected";?> value="No" ><?php _e('No','directory');?></option>
                    </select>
                  </li>
                </ul>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Enable Map in Responsive','directory');?></label>
                  </li>
                  <li class="to-field select-style">
                    <select name="cs_directory_map_responsive[]" class="dropdown">
                      <option <?php if($cs_directory_map_responsive=="Yes") echo "selected";?> value="Yes"><?php _e('Yes','directory');?></option>
                      <option <?php if($cs_directory_map_responsive=="No") echo "selected";?> value="No" ><?php _e('No','directory');?></option>
                    </select>
                  </li>
                 </ul>
                 <ul class="form-elements">
                    <li class="to-label"><label><?php _e('Latitude','directory');?></label></li>
                    <li class="to-field">
                        <input type="text" name="directory_search_latitude[]" class="txtfield" value="<?php echo htmlspecialchars($directory_search_latitude)?>" />
                        <p><?php _e('Directory Search Section Latitude','directory');?></p>
                    </li>
                </ul>
                 <ul class="form-elements">
                    <li class="to-label"><label><?php _e('Longitude','directory');?></label></li>
                    <li class="to-field">
                        <input type="text" name="directory_search_longitude[]" class="txtfield" value="<?php echo htmlspecialchars($directory_search_longitude)?>" />
                        <p><?php _e('Directory Search Section Longitude','directory');?></p>
                    </li>
                </ul>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Map Style','directory');?></label>
                  </li>
                  <li class="to-field select-style">
                    <select name="cs_directory_map_style[]" class="dropdown">
                      <option <?php if($cs_directory_map_style=="style-1")echo "selected";?> value="style-1"><?php _e('Style 1','directory');?></option>
                      <option <?php if($cs_directory_map_style=="style-2")echo "selected";?> value="style-2" ><?php _e('Style 2','directory');?></option>
                      <option <?php if($cs_directory_map_style=="style-3")echo "selected";?> value="style-3" ><?php _e('Style 3','directory');?></option>
                    </select>
                  </li>
                </ul>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Search Result Filters','directory');?></label>
                  </li>
                  <li class="to-field select-style">
                    <select name="cs_directory_search_filter[]" class="dropdown">
                      <option <?php if($cs_directory_search_filter=="all")echo "selected";?> value="all"><?php _e('All','directory');?></option>
                      <option <?php if($cs_directory_search_filter=="paid")echo "selected";?> value="paid" ><?php _e('Featured','directory');?></option>
                      <option <?php if($cs_directory_search_filter=="free")echo "selected";?> value="free" ><?php _e('Free','directory');?></option>
                    </select>
                  </li>
                </ul>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Search Result Page','directory');?></label>
                  </li>
                  <li class="to-field select-style">
                    <?php
					$args = array(
                              'depth'				=> 0,
                              'child_of'			=> 0,
                              'sort_order'			=> 'ASC',
                              'sort_column'			=> 'post_title',
                              'show_option_none'	=> 'Please select a page',
                              'hierarchical'		=> '1',
                              'exclude'				=> '',
                              'include'				=> '',
                              'meta_key'			=> '',
                              'meta_value'			=> '',
                              'authors'				=> '',
                              'exclude_tree'		=> '',
                              'selected'			=> $cs_directory_search_result_page,
                              'echo'				=> 1,
                              'name'				=> 'cs_directory_search_result_page[]',
                              'post_type'			=> 'page'
                          );
						  
					wp_dropdown_pages($args);
					?>
                    <p><?php _e('Please make sure you have added Directory Search element on selected page','directory');?></p>
                  </li>
                </ul>
                
                <?php 
                if ( function_exists( 'cs_shortcode_custom_dynamic_classes' ) ) {
                    cs_shortcode_custom_dynamic_classes($cs_directory_search_class,$cs_directory_search_animation,'','cs_directory_search');
                }
                if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
                ?>
                    <ul class="form-elements" style=" background-color: #fcfcfc; margin-top: -15px; padding-top: 12px; ">
                      <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo str_replace('cs_pb_','',$name);?>','<?php echo esc_attr($name.$cs_counter)?>','<?php echo esc_attr($filter_element);?>')" ><?php _e('Insert','directory');?></a> </li>
                    </ul>
                    <div id="results-shortocde"></div>
                <?php 
                } else {
                ?>
                    <ul class="form-elements noborder">
                      <li class="to-label"></li>
                      <li class="to-field">
                        <input type="hidden" name="cs_orderby[]" value="directory_search" />
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
    add_action('wp_ajax_cs_pb_directory_search', 'cs_pb_directory_search');
}
