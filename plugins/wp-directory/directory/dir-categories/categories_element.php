<?php
//======================================================================
// Categories html form for page builder start
//======================================================================
if ( ! function_exists( 'cs_pb_directory_categories' ) ) {
	function cs_pb_directory_categories($die = 0){
		global $cs_node, $post;
		$cs_shortcode_element	= '';
		$cs_filter_element		= 'filterdrag';
		$cs_shortcode_view		= '';
		$cs_output				= array();
		$counter				= $_POST['counter'];
		if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
			$CS_POSTID					= '';
			$cs_shortcode_element_id	= '';
			$cs_counter					= $_POST['counter'];
		} else {
			$CS_POSTID					= $_POST['POSTID'];
			$cs_counter					= $_POST['counter'];
			$CS_PREFIX					= 'cs_directory_categories';
			$cs_shortcode_element_id	= $_POST['shortcode_element_id'];
			$cs_shortcode_str			= stripslashes ($cs_shortcode_element_id);
			$cs_parseObject				= new ShortcodeParse();
			$cs_output					= $cs_parseObject->cs_shortcodes( $cs_output, $cs_shortcode_str , true , $CS_PREFIX );
		}
		$defaults = array( 
						'cs_directory_categories_title'		=> '',
						'cs_directory_categories_view'		=> 'view-1',
						'cs_directory_categories_page'		=> '',
						'cs_directory_categories_bg_color'	=> '',
						'cs_directory_categories_txt_color'	=> '', 
						'cs_directory_categories_cats'		=> '',
						'cs_directory_categories_number'	=> '',
						'cs_directory_categories_order'		=> '',
						'cs_custom_class'					=> '',
						'cs_custom_animation'				=> ''
					);

		if(isset($cs_output['0']['atts']))
			$atts = $cs_output['0']['atts'];
		else 
			$atts = array();
		if(isset($cs_output['0']['content']))
			$cs_directory_categories_description = $cs_output['0']['content'];
		else 
			$cs_directory_categories_description = "";
			
		$directory_categories_element_size = '50';
		foreach($defaults as $key=>$values){
			if(isset($atts[$key]))
				$$key = $atts[$key];
			else 
				$$key = $values;
		 }
		$name = 'cs_pb_directory_categories';
		$coloumn_class = 'column_'.$directory_categories_element_size	;
		if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
			$cs_shortcode_element	= 'shortcode_element_class';
			$cs_shortcode_view		= 'cs-pbwp-shortcode';
			$cs_filter_element		= 'ajax-drag';
			$coloumn_class			= '';
		}
		$rand_id = rand(43, 5456534);
	?>
    <div id="<?php echo esc_attr($name.$cs_counter);?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($cs_shortcode_view	);?>" item="directory_categories" data="<?php echo cs_element_size_data_array_index($directory_categories_element_size)?>" >
      <?php cs_element_setting($name,$cs_counter,$directory_categories_element_size);?>
      <div class="cs-wrapp-class-<?php echo esc_attr($cs_counter)?> <?php echo esc_attr($cs_shortcode_element);;?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[cs_directory_categories {{attributes}}]" style="display: none;">
        <div class="cs-heading-area">
          <h5><?php _e('Edit Directory Categories Options','directory');?></h5>
          <a href="javascript:removeoverlay('<?php echo esc_attr($name.$cs_counter)?>','<?php echo esc_attr($cs_filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
        <div class="cs-wrapp-tab-box">
          <div class="cs-clone-append cs-pbwp-content" >
            <div id="shortcode-item-<?php echo esc_attr($cs_counter);?>">
			<?php
			if ($cs_directory_categories_cats){
				$cs_directory_categories_cats = explode(",", $cs_directory_categories_cats);
			}
			?>
              <div class="cs-wrapp-clone cs-shortcode-wrapp cs-disable-true">
                <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
                <ul class="form-elements">
                    <li class="to-label"><label><?php _e('Section Title','directory');?></label></li>
                    <li class="to-field">
                        <input  name="cs_directory_categories_title[]" type="text"  value="<?php echo esc_attr($cs_directory_categories_title)?>"   />
                    </li>                  
                </ul>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Categories Views','directory');?></label>
                  </li>
                  <li class="to-field select-style">
                    <select class="cs_size" name="cs_directory_categories_view[]">
                      <option value="list" <?php if(@$cs_directory_categories_view == 'list'){echo 'selected="selected"';}?>><?php _e('List','directory');?></option>
                      <option value="grid" <?php if(@$cs_directory_categories_view == 'grid'){echo 'selected="selected"';}?>><?php _e('Grid','directory');?></option>
                      <option value="image" <?php if(@$cs_directory_categories_view == 'image'){echo 'selected="selected"';}?>><?php _e('Image','directory');?></option>
                      <option value="gradient" <?php if(@$cs_directory_categories_view == 'gradient'){echo 'selected="selected"';}?>><?php _e('Gradient','directory');?></option>
                      <option value="simple" <?php if(@$cs_directory_categories_view == 'simple'){echo 'selected="selected"';}?>><?php _e('Simple','directory');?></option>
                    </select>
                  </li>
                </ul>
                <?php
				$args = array(
					'posts_per_page'			=> "-1",
					'post_type'					=> 'page',
					'post_status'				=> 'publish',
					'orderby'					=> 'ID',
					'order'						=> 'ASC',
				);
				$custom_query = new WP_Query($args);
				if ( $custom_query->have_posts() <> "" ) {
				?>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Listing Page','directory');?></label>
                  </li>
                  <li class="to-field select-style">
                    <select class="cs_size" name="cs_directory_categories_page[]">
						<?php
                        while ( $custom_query->have_posts() ): $custom_query->the_post();
						?>
							<option value="<?php echo cs_allow_special_char($post->ID); ?>" <?php if(@$cs_directory_categories_page == $post->ID){echo 'selected="selected"';} ?>><?php the_title(); ?></option>
						<?php
                        endwhile;
                        wp_reset_query();
                        ?>
                    </select>
                  </li>
                </ul>
                <?php
				}
				?>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Background Color','directory');?></label>
                  </li>
                  <li class="to-field">
                    <input type="text" name="cs_directory_categories_bg_color[]" class="txtfield bg_color" value="<?php echo esc_attr($cs_directory_categories_bg_color)?>" />
                    <div class="left-info">
                    <p><?php _e('Add a hex background colour code, If you want to override the default','directory');?></p>
                    </div>
                  </li>
                </ul>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Text Color','directory');?></label>
                  </li>
                  <li class="to-field">
                    <input type="text" name="cs_directory_categories_txt_color[]" class="txtfield bg_color" value="<?php echo esc_attr($cs_directory_categories_txt_color);?>" />
                    <div class="left-info">
                    <p><?php _e('Add a hex background colour code, If you want to override the default','directory');?></p>
                    </div>
                  </li>
                </ul>
                <?php
				$args = array(
					'posts_per_page'	=> "-1",
					'post_type'			=> 'directory_types',
					'post_status'		=> 'publish',
					'orderby'			=> 'ID',
					'order'				=> 'ASC',
				);
				$custom_query = new WP_Query($args);
				$count_post     = $custom_query->post_count;
				if ( $custom_query->have_posts() <> "" ) {
				?>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Select Directory Type','directory');?></label>
                  </li>
                  <li class="to-field">
                    <select name="cs_directory_categories_cats[<?php echo esc_attr($rand_id); ?>][]" multiple="multiple" class="multiselect" style="min-height:100px;">
                      <?php
						while ( $custom_query->have_posts() ): $custom_query->the_post();
							$selected	 = '';
							if( in_array($post->ID, $cs_directory_categories_cats)){
								$selected	= 'selected=selected';
							}
							echo '<option value="'.$post->ID.'" '.$selected.'>'.get_the_title().'</option>';
						endwhile;
						wp_reset_query();
                     ?>
                    </select>
                  </li>
                </ul>
                <?php
				}
				?>
                <ul class="form-elements">
                    <li class="to-label"><label><?php _e('Number of Categories','directory');?></label></li>
                    <li class="to-field">
                    	<input  name="cs_directory_categories_number[]" type="text"  value="<?php echo esc_attr($cs_directory_categories_number)?>"   />
                    </li>                  
                </ul>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Categories Order','directory');?></label>
                  </li>
                  <li class="to-field select-style">
                    <select class="cs_size" name="cs_directory_categories_order[]">
                      <option value="title" <?php if(@$cs_directory_categories_order == 'title'){echo 'selected="selected"';}?>><?php _e('By Title','directory');?></option>
                      <option value="date" <?php if(@$cs_directory_categories_order == 'date'){echo 'selected="selected"';}?>><?php _e('By Date','directory');?></option>
                    </select>
                  </li>
                </ul>
                <ul class="form-elements">
                    <li class="to-label"><label><?php _e('Custom id','directory');?></label></li>
                    <li class="to-field">
						<input type="text" name="cs_custom_class[]" class="txtfield"  value="<?php echo esc_attr($cs_custom_class);?>" />
                    </li>
                 </ul>
                <ul class="form-elements">
                    <li class="to-label"><label><?php _e('Animation Class','directory');?> </label></li>
                    <li class="to-field select-style">
                        <select class="dropdown" name="cs_custom_animation[]">
                            <option value=""><?php _e('Select Animation','directory');?></option>
                            <?php 
                                $animation_array = cs_animation_style();
                                foreach($animation_array as $animation_key=>$animation_value){
                                    echo '<optgroup label="'.$animation_key.'">';	
                                    foreach($animation_value as $key=>$value){
                                        $selected = '';
                                        if($cs_custom_animation == $key){$selected = 'selected="selected"';}
                                        echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                                    }
                                }
                             ?>
                          </select>
                    </li>
                </ul>
              </div>
            </div>
            <div class="wrapptabbox no-padding-lr">
              <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
              <ul class="form-elements insert-bg">
                <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo str_replace('cs_pb_','',$name);?>','<?php echo esc_js($name.$cs_counter);?>','<?php echo esc_js($cs_filter_element);?>')"><?php _e('insert','directory');?></a> </li>
              </ul>
              <div id="results-shortocde"></div>
              <?php } else {?>
              <ul class="form-elements noborder no-padding-lr">
                <li class="to-label"></li>
                <li class="to-field">
                  <input type="hidden" name="cs_orderby[]" value="directory_categories" />
                  <input type="hidden" name="cs_directory_categories_counter[]" value="<?php echo esc_attr($rand_id); ?>" />
                  <input type="button" value="Save" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
                </li>
              </ul>
              <?php }?>
            </div>
          </div>
        </div>
      </div>
    </div>
	<?php
		if ( $die <> 1 ) die();
	}
	add_action('wp_ajax_cs_pb_directory_categories', 'cs_pb_directory_categories');
}
