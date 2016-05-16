<?php

//======================================================================
// Directory Page Element
//======================================================================
if ( ! function_exists( 'cs_pb_directory' ) ) {
	function cs_pb_directory($die = 0){
		global $cs_node, $post, $cs_theme_options;
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
			$shortcode_str = stripslashes ($shortcode_element_id);
			$PREFIX = 'cs_directory';
			$parseObject 	= new ShortcodeParse();
			$output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
		}
		
		$defaults = array( 'directory_title' => '', 'directory_cat' => '','cs_directory_fields_count'=>'', 'cs_directory_filter' => '', 'cs_featured_on_top' => '', 'cs_listing_sorting' => '','cs_directory_header' => '','cs_directory_rev_slider' => '','cs_directory_map_style' => 'style-1','cs_directory_banner' => '','cs_directory_adsense' => '','cs_subheader_bg_color' => '','cs_subheader_padding_top' => '','cs_subheader_padding_bottom' => '','directory_view' => '','cs_switch_views' => '','directory_type' => '','directory_pagination'=>'','cs_directory_filterable' => '','cs_directory_sortable' => '','directory_per_page' => '');
		
		if(isset($output['0']['atts']))
			$atts = $output['0']['atts'];
		else 
			$atts = array();
		$directory_element_size = '50';
		
		foreach($defaults as $key=>$values){
			if(isset($atts[$key]))
				$$key = $atts[$key];
			else 
				$$key =$values;
		 }
		
		$name = 'cs_pb_directory';
		$coloumn_class = 'column_'.$directory_element_size;
		if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
			$shortcode_element = 'shortcode_element_class';
			$shortcode_view = 'cs-pbwp-shortcode';
			$filter_element = 'ajax-drag';
			$coloumn_class = '';
		}
	
	if ($cs_switch_views){
		$cs_switch_views = explode(",", $cs_switch_views);
	}
	
	$cs_rand_num = rand(45, 45434590);
	
	?>
    <div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="directory" data="<?php echo cs_element_size_data_array_index($directory_element_size)?>" >
      <?php cs_element_setting($name,$cs_counter,$directory_element_size,'','graduation-cap');?>
      <div class="cs-wrapp-class-<?php echo esc_attr($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[cs_directory {{attributes}}]"  style="display: none;">
        <div class="cs-heading-area">
          <h5><?php _e('Edit Directory Options', 'directory'); ?></h5>
          <a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
        <div class="cs-pbwp-content">
          <div class="cs-wrapp-clone cs-shortcode-wrapp">
            <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
                <ul class="form-elements">
                    <li class="to-label"><label><?php _e('Directory Title', 'directory');?></label></li>
                    <li class="to-field">
                        <input type="text" name="directory_title[]" class="txtfield" value="<?php echo htmlspecialchars($directory_title)?>" />
                        <p><?php _e('Directory Section Title', 'directory');?></p>
                    </li>                                            
                </ul>
                <ul class="form-elements">
                    <li class="to-label"><label><?php _e('Directory Header', 'directory');?></label></li>
                    <li class="to-field select-style">
                        <select name="cs_directory_header[]" class="dropdown" onchange="cs_toggle_directory_header(this.value)">
                        	<option value="blank-header" <?php if($cs_directory_header=="blank-header") echo "selected";?>><?php _e('Blank Header', 'directory');?></option>
                            <option value="plain-heading" <?php if($cs_directory_header=="plain-heading") echo "selected";?>><?php _e('Plain Heading', 'directory');?></option>
                            <option value="map" <?php if($cs_directory_header=="map") echo "selected";?>><?php _e('Map', 'directory');?></option>
                            <option value="revolution-slider" <?php if($cs_directory_header=="revolution-slider") echo "selected";?>><?php _e('Revolution Slider', 'directory');?></option>
                            <option value="banner" <?php if($cs_directory_header=="banner") echo "selected";?>><?php _e('Banner', 'directory');?></option>
                            <option value="adsense" <?php if($cs_directory_header=="adsense") echo "selected";?>><?php _e('Adsense', 'directory');?></option>
                        </select>
                    </li>                                            
                </ul>
                <div id="cs_directory_blank_header" style="display:<?php echo cs_allow_special_char($cs_directory_header) == 'blank-header' ? 'block' : 'none'; ?>;">
                  <ul class="form-elements">
                      <li class="to-label">&nbsp;</li>
                      <li class="to-field"><?php _e('Blank Header', 'directory');?> </li>                                            
                  </ul>
                </div>
                <div id="cs_directory_plain_heading" style="display:<?php echo cs_allow_special_char($cs_directory_header) == 'plain-heading' ? 'block' : 'none'; ?>;">
                  <ul class="form-elements">
                      <li class="to-label">&nbsp;</li>
                      <li class="to-field"><?php _e('Plain Heading', 'directory');?></li>                                            
                  </ul>
                </div>
                <div id="cs_directory_map" style="display:<?php echo cs_allow_special_char($cs_directory_header) == 'map' ? 'block' : 'none'; ?>;">
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
                </div>
                <div id="cs_directory_rev_slider" style="display:<?php echo cs_allow_special_char($cs_directory_header) == 'revolution-slider' ? 'block' : 'none'; ?>;">
                  <?php
				  if(class_exists('RevSlider') && class_exists('cs_RevSlider')) {
				  ?>
                  <ul class="form-elements">
                      <li class="to-label"><label><?php _e('Revolution Slider', 'directory');?></label></li>
                      <li class="to-field select-style">
                          <select name="cs_directory_rev_slider[]" class="dropdown">
						  <?php
							  $cs_slider = new cs_RevSlider();
							  $cs_arrSliders = $cs_slider->getAllSliderAliases();
							  foreach ( $cs_arrSliders as $key => $entry ) {
								  ?>
								  <option <?php cs_selected($cs_directory_rev_slider,$entry['alias']) ?> value="<?php echo cs_allow_special_char($entry['alias']);?>"><?php echo cs_allow_special_char($entry['title']) ;?></option>
								  <?php
							  }
                          ?>
                          </select>
                      </li>                                            
                  </ul>
                  <?php
				  }else{
				  $cs_rev_link = admin_url('themes.php?page=install-required-plugins');
				  ?>
                  <ul class="form-elements">
                      <li class="to-label">&nbsp;</li>
                     <!-- <li class="to-field">< ?php printf(__('Please install a <a href="%s">Revolution Slider</a> first.', 'directory'), $cs_rev_link);?></li> -->
					  
					 
					  
					    <li class="to-field"><?php printf(__('Please install a first.', 'directory')); ?> <a href="%s">Revolution Slider</a> , <?php printf(__('first.', 'directory'),  $cs_rev_link);?></li>  
					    
					  
					                                             
                  </ul>
                  <?php
				  }
				  ?>
                </div>
                <div id="cs_directory_banner" style="display:<?php echo cs_allow_special_char($cs_directory_header) == 'banner' ? 'block' : 'none'; ?>;">
                  <ul class="form-elements">
                      <li class="to-label"><label><?php _e('Banner', 'directory');?></label></li>
                      <li class="to-field">
                          <input id="cs_directory_banner<?php echo esc_attr($cs_rand_num)?>" name="cs_directory_banner[]" type="hidden" class="" value="<?php echo esc_url($cs_directory_banner);?>"/>
                          <label class="browse-icon"><input name="cs_directory_banner<?php echo esc_attr($cs_rand_num)?>" type="button" class="uploadMedia left" value="<?php _e('Browse','directory'); ?>"/></label>
                          <div class='left-info'>
                            <p><?php _e('Browse an image for Banner', 'directory');?></p>
                          </div>
                      </li>                                            
                  </ul>
                  <div class="page-wrap" style="overflow:hidden; display:<?php echo esc_attr($cs_directory_banner) && trim($cs_directory_banner) !='' ? 'inline' : 'none';?>" id="cs_directory_banner<?php echo esc_attr($cs_rand_num)?>_box" >
                    <div class="gal-active">
                      <div class="dragareamain" style="padding-bottom:0px;">
                        <ul id="gal-sortable">
                          <li class="ui-state-default" id="">
                            <div class="thumb-secs" style="max-width:200px;">
                              <img src="<?php echo esc_url($cs_directory_banner);?>"  id="cs_directory_banner<?php echo esc_attr($cs_rand_num);?>_img" />
                              <div class="gal-edit-opts"> <a href="javascript:del_media('cs_directory_banner<?php echo esc_attr($cs_rand_num);?>')" class="delete"></a> </div>
                            </div>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="cs_directory_adsense" style="display:<?php echo cs_allow_special_char($cs_directory_header) == 'adsense' ? 'block' : 'none'; ?>;">
                  <ul class="form-elements">
                      <li class="to-label"><label><?php _e('Adsense', 'directory');?></label></li>
                      <li class="to-field select-style">
                          <?php
						  if( isset($cs_theme_options['banner_field_title']) && is_array($cs_theme_options['banner_field_title']) && sizeof($cs_theme_options['banner_field_title']) > 0 ) {
						  ?>
                          <select name="cs_directory_adsense[]" class="dropdown">
                              <?php
                                 $i=0;
								 foreach($cs_theme_options['banner_field_title'] as $banner) :
									?>
									<option value="<?php echo cs_allow_special_char($cs_theme_options['banner_field_code_no'][$i]); ?>" <?php if($cs_theme_options['banner_field_code_no'][$i] == $cs_directory_adsense) echo 'selected'; ?>><?php echo cs_allow_special_char($cs_theme_options['banner_field_title'][$i]); ?></option>
                                    <?php
									$i++;
								 endforeach;
                              ?>
                          </select>
                          <?php
						  }
						  else{
							  _e('Please add Banners first from Theme Options.', 'directory');
						  }
						  ?>
                      </li>                                           
                  </ul>
                </div>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Sub Header Background Color', 'directory');?></label>
                  </li>
                  <li class="to-field">
                    <div class="input-sec">
                      <input type="text" name="cs_subheader_bg_color[]"  class="bg_color" value="<?php echo cs_allow_special_char($cs_subheader_bg_color) ?>" />
                    </div>
                  </li>
                </ul>
                <ul class="form-elements">
                  <li class="to-label"><label><?php _e('Sub Header Padding Top', 'directory');?></label></li>
                  <li class="to-field">
                      <div class="cs-drag-slider" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="<?php echo cs_allow_special_char($cs_subheader_padding_top)?>"></div>
                      <input  class="cs-range-input"  name="cs_subheader_padding_top[]" type="text" value="<?php echo cs_allow_special_char($cs_subheader_padding_top)?>"   />
                      <p><?php _e('Set the top padding (In PX)', 'directory');?></p>
                  </li>
                </ul>
                <ul class="form-elements">
                  <li class="to-label"><label><?php _e('Sub Header Padding Bottom', 'directory');?></label></li>
                  <li class="to-field">
                      <div class="cs-drag-slider" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="<?php echo cs_allow_special_char($cs_subheader_padding_bottom)?>"></div>
                      <input  class="cs-range-input"  name="cs_subheader_padding_bottom[]" type="text" value="<?php echo cs_allow_special_char($cs_subheader_padding_bottom)?>"   />
                      <p><?php _e('Set the Bottom padding in px', 'directory');?></p>
                  </li>
                </ul>
                 <?php
				$args = array(
					'posts_per_page'			=> "-1",
					'post_type'					=> 'directory_types',
					'post_status'				=> 'publish',
					'orderby'					=> 'ID',
					'order'						=> 'ASC',
				);
				$custom_query = new WP_Query($args);
				if ( $custom_query->have_posts() <> "" ) {
					?>
                    <ul class="form-elements">
                        <li class="to-label"><label><?php _e('Directory Types', 'directory');?></label></li>
                         <li class="to-field select-style">
                            <select name="directory_type[]" class="dropdown" onchange="cs_get_directory_categories(this.value, '<?php echo esc_js(admin_url('admin-ajax.php'));?>', 'directory_cat')">
                                 <option value="0"><?php _e('All', 'directory');?></option>
                                 <?php
                                     while ( $custom_query->have_posts() ): $custom_query->the_post();
                                     $selected = '';
                                     if(isset($directory_type) && $directory_type == $post->ID){
                                        $selected = 'selected'; 
                                     }
                                      echo '<option value="'.$post->ID.'" '.$selected.'>'.get_the_title().'</option>';
                                    endwhile;
                                ?>
                            </select>
                        </li>
                    </ul>
                	<?php
				}
				
				?>
                <ul class="form-elements">
                	<li class="to-label"><label><?php _e('Choose Category', 'directory');?></label></li>
                	<li class="to-field select-style" id="cs_directory_categories">
                	<?php 
                    	$directory_id = absint($directory_type);
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
										'hide_empty'               => 1,
										'hierarchical'             => 1,
										'exclude'                  => '',
										'include'                  => '',
										'number'                   => '',
										'taxonomy'                 => 'directory-category',
										'pad_counts'               => false 
									
									); 
                        $categories = get_categories($args); 
                        ?>
                        <select name="directory_cat[]" class="dropdown">
                        	<option value=""><?php _e('Select Categories', 'directory');?></option>
                            <?php
                            foreach ($categories as $category) {
								$selected = '';
								if( $directory_cat ==  $category->slug ){
									$selected = 'selected="selected"';
								}
								echo '<option value="'.$category->slug.'" '.$selected.'>' . $category->name . '</option>';
                            }
                            ?>
                        </select>
                	</li>
                </ul>
                <ul class="form-elements">
                	<li class="to-label"><label><?php _e('Select Number of Fields', 'directory');?></label></li>
                     <li class="to-field select-style" id="cs_directory_fields_count">
                     	 <select name="cs_directory_fields_count[]" class="dropdown">
                         <option value="none"><?php _e('None', 'directory');?></option>
                         	<?php 
								$cs_dcpt_custom_fields = get_post_meta($directory_id, "cs_directory_custom_fields", true);
								if ( $cs_dcpt_custom_fields <> "" ) {
								$cs_xmlObject = new stdClass();
								$cs_xmlObject = new SimpleXMLElement($cs_dcpt_custom_fields);
									$cs_fields_length = sizeof($cs_xmlObject);
									for($i = 1; $i<$cs_fields_length;$i++){
										echo '<option '.cs_directory_selected($i,$cs_directory_fields_count).'>'.$i.'</option>';
									}
								} else {
									for( $i = 1; $i < 11; $i++ ){
										echo '<option value="'.$i.'" '.cs_directory_selected($i,$cs_directory_fields_count).'>'.$i.'</option>';
									}
								}
							
							?>
                         </select>
                     </li>
                </ul>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Listing Filters','directory');?></label>
                  </li>
                  <li class="to-field select-style">
                    <select name="cs_directory_filter[]" class="dropdown">
                      <option <?php if($cs_directory_filter=="all")echo "selected";?> value="all"><?php _e('All','directory');?></option>
                      <option <?php if($cs_directory_filter=="paid")echo "selected";?> value="paid" ><?php _e('Featured','directory');?></option>
                      <option <?php if($cs_directory_filter=="free")echo "selected";?> value="free" ><?php _e('Free','directory');?></option>
                    </select>
                  </li>
                </ul>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Featured on top on/off', 'directory');?></label>
                  </li>
                  <li class="to-field select-style">
                    <select name="cs_featured_on_top[]" class="dropdown">
                      <option <?php if($cs_featured_on_top=="Yes")echo "selected";?> value="Yes"><?php _e('Yes', 'directory');?></option>
                      <option <?php if($cs_featured_on_top=="No")echo "selected";?> value="No" ><?php _e('No', 'directory');?></option>
                    </select>
                  </li>
                </ul>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Listing Sorting', 'directory');?></label>
                  </li>
                  <li class="to-field select-style">
                    <select name="cs_listing_sorting[]" class="dropdown">
                      <option <?php if($cs_listing_sorting=="recent")echo "selected";?> value="recent" ><?php _e('Recent', 'directory');?></option>
                      <option <?php if($cs_listing_sorting=="popular")echo "selected";?> value="popular"><?php _e('Popular', 'directory');?></option>
                      <option <?php if($cs_listing_sorting=="urgent")echo "selected";?> value="urgent" ><?php _e('Urgent', 'directory');?></option>
                    </select>
                  </li>
                </ul>
				<ul class="form-elements">
                    <li class="to-label"><label><?php _e('Select View', 'directory');?></label></li>
                    <li class="to-field select-style">
                        <select name="directory_view[]" class="dropdown" onchange="javascrip:cs_directory_view(this.value,'<?php echo esc_js($cs_counter); ?>');">
                        	<option <?php if($directory_view=="listing")echo "selected";?> value="listing"><?php _e('List', 'directory');?></option>
                         	<option <?php if($directory_view=="grid")echo "selected";?> value="grid"><?php _e('Grid', 'directory');?></option>
                            <option <?php if($directory_view=="grid-box")echo "selected";?> value="grid-box"><?php _e('Grid Box', 'directory');?></option>
                            <option <?php if($directory_view=="grid-box-four-column")echo "selected";?> value="grid-box-four-column"><?php _e('Grid Box Four Column', 'directory');?></option>
                            <option <?php if( $directory_view=="detailed")echo "selected";?> value="detailed"><?php _e('Detailed', 'directory');?></option>
                            <option <?php if( $directory_view == "carousel" ) echo "selected";?> value="carousel"><?php _e('Carousel', 'directory');?></option>
                        </select>
                    </li>                                        
                </ul>
                <ul class="form-elements">
                    <li class="to-label"><label><?php _e('Switch View', 'directory');?></label></li>
                    <li class="to-field">
                        <select name="cs_switch_views[]" multiple="multiple" class="multiselect" style="min-height:100px;">
                           	 <?php 
								$list_array = array('list'=>'List','grid'=>'Grid','grid-box'=>'Grid Box','grid-box-four-column'=>'Grid Box 4 Column','map'=>'Map');
								foreach( $list_array as $list_key => $list ){
									if(in_array($list_key,$cs_switch_views)) {$selected ='selected'; }else{ $selected =''; }
										echo '<option '.$selected.' value="'.$list_key.'" >'.$list.'</option>';
								}
							 ?>
                         </select>
                    </li>                                        
                </ul>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Filterable', 'directory');?></label>
                  </li>
                  <li class="to-field select-style">
                    <select name="cs_directory_filterable[]" class="dropdown">
                      <option <?php if($cs_directory_filterable=="Yes")echo "selected";?> value="Yes"><?php _e('Yes', 'directory');?></option>
                      <option <?php if($cs_directory_filterable=="No")echo "selected";?> value="No" ><?php _e('No', 'directory');?></option>
                    </select>
                  </li>
                </ul>
                <ul class="form-elements">
                  <li class="to-label">
                    <label><?php _e('Sortable', 'directory');?></label>
                  </li>
                  <li class="to-field select-style">
                    <select name="cs_directory_sortable[]" class="dropdown">
                      <option <?php if($cs_directory_sortable=="Yes")echo "selected";?> value="Yes"><?php _e('Yes', 'directory');?></option>
                      <option <?php if($cs_directory_sortable=="No")echo "selected";?> value="No" ><?php _e('No', 'directory');?></option>
                    </select>
                  </li>
                </ul>
                <div id="port_pagination<?php echo esc_attr($name.$cs_counter);?>">
                    <ul class="form-elements">
                        <li class="to-label"><label><?php _e('Pagination', 'directory');?></label></li>
                         <li class="to-field select-style">
                            <select name="directory_pagination[]" class="dropdown">
                                <option <?php if($directory_pagination=="Show Pagination")echo "selected";?> ><?php _e('Show Pagination', 'directory');?></option>
                                <option <?php if($directory_pagination=="Single Page")echo "selected";?> ><?php _e('Single Page', 'directory');?></option>
                            </select>
                        </li>
                    </ul>
                </div>
                <ul class="form-elements">
                        <li class="to-label"><label><?php _e('No. of record Per Page', 'directory');?></label></li>
                        <li class="to-field">
                            <input type="text" name="directory_per_page[]" class="txtfield" value="<?php echo esc_attr($directory_per_page); ?>" />
                            <p><?php _e('To display all the records, leave this field blank', 'directory');?>.</p>
                        </li>
                    </ul>
            <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
                    <ul class="form-elements" style=" background-color: #fcfcfc; margin-top: -15px; padding-top: 12px; ">
                      <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo str_replace('cs_pb_','',$name);?>','<?php echo esc_attr($name.$cs_counter)?>','<?php echo esc_attr($filter_element);?>')" ><?php _e('Insert', 'directory');?></a> </li>
                    </ul>
                    <div id="results-shortocde"></div>
             <?php } else {?>
                    <ul class="form-elements noborder">
                      <li class="to-label"></li>
                      <li class="to-field">
                        <input type="hidden" name="cs_orderby[]" value="directory" />
                        <input type="button" value="Save" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
                      </li>
                    </ul>
            <?php }?>
          </div>
        </div>
      </div>
    </div>
<?php
		if ( $die <> 1 ) die();
	}
	add_action('wp_ajax_cs_pb_directory', 'cs_pb_directory');
}