<?php
//=====================================================================
// divider html form for page builder start
//=====================================================================
if ( ! function_exists( 'cs_pb_divider' ) ) {
    function cs_pb_divider($die = 0){
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
            $PREFIX = 'cs_divider';
            $parseObject     = new ShortcodeParse();
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }        
        $defaults = array( 'column_size' => '1/1', 'divider_style' => 'divider1','divider_height' => '1','divider_backtotop' => '','divider_margin_top' => '','divider_margin_bottom' =>'','line' => 'Wide','color'=>'#000', 'cs_divider_class'=>'','cs_divider_animation'=>'');
            if(isset($output['0']['atts']))
                $atts = $output['0']['atts'];
            else 
                $atts = array();
            if(isset($output['0']['content']))
                $atts_content = $output['0']['content'];
            else 
                $atts_content = '';
            $divider_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_divider';
            $coloumn_class = 'column_'.$divider_element_size;
        
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }        
    ?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="blog" data="<?php echo cs_element_size_data_array_index($divider_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$divider_element_size, '', 'ellipsis-h',$type='');?>
  <div class="cs-wrapp-class-<?php echo intval($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[cs_divider {{attributes}}]" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php _e('Edit Divider Option','dir');?></h5>
      <a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
    <div class="cs-pbwp-content">
      <div class="cs-wrapp-clone cs-shortcode-wrapp">
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Style','dir');?></label>
          </li>
          <li class="to-field select-style">
            <select name="divider_style[]" class="dropdown" >
              <option <?php if($divider_style=="crossy")echo "selected";?> value="crossy" ><?php _e('Crossy','dir');?></option>
              <option <?php if($divider_style=="plain")echo "selected";?> value="plain" ><?php _e('Plain','dir');?></option>
              <option <?php if($divider_style=="zigzag")echo "selected";?> value="zigzag" ><?php _e('Zigzag','dir');?></option>
              <option <?php if($divider_style=="small-zigzag")echo "selected";?> value="small-zigzag" ><?php _e('Small Zigzag','dir');?></option>
              <option <?php if($divider_style=="3box")echo "selected";?> value="3box" ><?php _e('3 Box','dir');?></option>
            </select>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Back to Top','dir');?></label>
          </li>
          <li class="to-field select-style">
            <select name="divider_backtotop[]" class="dropdown" >
              <option value="yes" <?php if($divider_backtotop=="yes")echo "selected";?> ><?php _e('Yes','dir');?></option>
              <option value="no" <?php if($divider_backtotop=="no")echo "selected";?> ><?php _e('No','dir');?></option>
            </select>
            <p><?php _e('set back to top from the dropdown','dir');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Margin Top','dir');?></label>
          </li>
          <li class="to-field">
            <div class="cs-drag-slider" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr($divider_margin_top);?>"></div>
            <input  class="cs-range-input"  name="divider_margin_top[]" type="text" value="<?php echo esc_attr($divider_margin_top);?>"   />
            <p><?php _e('set margin top for the divider in px','dir');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Margin Bottom','dir');?></label>
          </li>
          <li class="to-field">
            <div class="cs-drag-slider" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr($divider_margin_bottom);?>"></div>
            <input  class="cs-range-input"  name="divider_margin_bottom[]" type="text" value="<?php echo esc_attr($divider_margin_bottom);?>"   />
            <p><?php _e('set a margin bottom for the divider in px','dir');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Height','dir');?></label>
          </li>
          <li class="to-field">
            <div class="cs-drag-slider" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="<?php echo esc_attr($divider_height);?>"></div>
            <input  class="cs-range-input"  name="divider_height[]" type="text" value="<?php echo esc_attr($divider_height);?>"   />
            <p><?php _e('set the divider height','dir');?></p>
          </li>
        </ul>
        <?php 
            if ( function_exists( 'cs_shortcode_custom_dynamic_classes' ) ) {
                cs_shortcode_custom_dynamic_classes($cs_divider_class,$cs_divider_animation,'','cs_divider');
            }
        ?>
      </div>
      <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
      <ul class="form-elements insert-bg">
        <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo str_replace('cs_pb_','',$name);?>','<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" ><?php _e('Insert','dir');?></a> </li>
      </ul>
      <div id="results-shortocde"></div>
      <?php } else {?>
      <ul class="form-elements noborder">
        <li class="to-label"></li>
        <li class="to-field">
          <input type="hidden" name="cs_orderby[]" value="divider" />
          <input type="button" value="Save" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
        </li>
      </ul>
      <?php }?>
    </div>
  </div>
</div>
<?php
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_pb_divider', 'cs_pb_divider');
}
// divider html form for page builder end

//=====================================================================
// Tooltip html form for page builder start
//=====================================================================
if ( ! function_exists( 'cs_pb_tooltip' ) ) {
    function cs_pb_tooltip($die = 0){
        global $cs_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $PREFIX = 'cs_tooltip';
        $counter = $_POST['counter'];
        $cs_counter = $_POST['counter'];
        if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
            $parseObject     = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
        $defaults = array( 'column_size' => '1/1', 'tooltip_hover_title' => '','tooltip_data_placement' => 'top','cs_tooltip_class'=>'', 'cs_tooltip_animation'=>'');
            if(isset($output['0']['atts']))
                $atts = $output['0']['atts'];
            else 
                $atts = array();
            if(isset($output['0']['content']))
                $tooltip_content = $output['0']['content'];
            else 
                $tooltip_content = '';
            $tooltip_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_tooltip';
            $coloumn_class = 'column_'.$tooltip_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
    ?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> 
		<?php echo esc_attr($shortcode_view);?>" item="tooltip" data="<?php echo cs_element_size_data_array_index($tooltip_element_size)?>" >
		<?php cs_element_setting($name,$cs_counter,$tooltip_element_size, '', 'comment-o',$type='');?>
  <div class="cs-wrapp-class-<?php echo intval($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[cs_tooltip {{attributes}}]{{content}}[/cs_tooltip]" style="display: none;">
   	 <div class="cs-heading-area">
      	<h5><?php _e('Edit Tooltip Options','dir');?></h5>
      	<a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" class="cs-btnclose">
		<i class="icon-times"></i>
		</a>
	 </div>
   	 <div class="cs-pbwp-content">
		  <div class="cs-wrapp-clone cs-shortcode-wrapp">
				<ul class="form-elements">
				  <li class="to-label">
					<label><?php _e('Hover Title','dir');?></label>
				  </li>
				  <li class="to-field">
					<input  name="tooltip_hover_title[]" type="text"  value="<?php echo cs_allow_special_char($tooltip_hover_title)?>"   />
				  </li>
			</ul>
				<ul class="form-elements">
				  <li class="to-label">
					<label><?php _e('Hover Title','dir');?></label>
				  </li>
				  <li class="to-field select-style">
					<select name="tooltip_data_placement[]" class="dropdown" >
					  <option <?php if($tooltip_data_placement=="top")echo "selected";?> value="top" ><?php _e('Top','dir');?></option>
					  <option <?php if($tooltip_data_placement=="left")echo "selected";?> value="left" ><?php _e('Left','dir');?></option>
					  <option <?php if($tooltip_data_placement=="bottom")echo "selected";?> value="bottom" ><?php _e('Bottom','dir');?></option>
					  <option <?php if($tooltip_data_placement=="right")echo "selected";?> value="right" ><?php _e('Right','dir');?></option>
					</select>
				  </li>
			</ul>
				<ul class="form-elements">
				  <li class="to-label">
					<label><?php _e('Content','dir');?></label>
				  </li>
				  <li class="to-field">
					<textarea name="tooltip_content[]" data-content-text="cs-shortcode-textarea"><?php echo esc_attr($tooltip_content)?></textarea>
					<p><?php _e('Enter your content','dir');?></p>
				  </li>
			</ul>
			<?php 
				if ( function_exists( 'cs_shortcode_custom_dynamic_classes' ) ) {
					cs_shortcode_custom_dynamic_classes($cs_tooltip_class,$cs_tooltip_animation,'','cs_tooltip');
				}
			?>
		  </div>
      <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
		  <ul class="form-elements insert-bg">
			<li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo str_replace('cs_pb_','',$name);?>','<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" ><?php _e('Insert','dir');?></a> </li>
		  </ul>
      	<div id="results-shortocde"></div>
      <?php } else {?>
		  <ul class="form-elements noborder">
				<li class="to-label"></li>
				<li class="to-field">
				  <input type="hidden" name="cs_orderby[]" value="tooltip" />
				  <input type="button" value="Save" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
				</li>
		  </ul>
      <?php }?>
    </div>
  </div>
</div>
<?php
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_pb_tooltip', 'cs_pb_tooltip');
}
// tooltip html form for page builder end


//=====================================================================
// Flex Column html form for page builder start
//=====================================================================
if ( ! function_exists( 'cs_pb_flex_column' ) ) {
    function cs_pb_flex_column($die = 0){
        global $cs_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $PREFIX = 'cs_column';
        $counter = $_POST['counter'];
        $cs_counter = $_POST['counter'];
        if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
             $parseObject     = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );            
        }
        $defaults = array('flex_column_section_title'=>'','cs_column_class'=>'','cs_column_animation'=>'');
        if(isset($output['0']['atts']))
            $atts = $output['0']['atts'];
        else 
            $atts = array();
        if(isset($output['0']['content']))
            $flex_column_text = $output['0']['content'];
        else 
            $flex_column_text = '';
        $flex_column_element_size = '25';
        foreach($defaults as $key=>$values){
            if(isset($atts[$key]))
                $$key = $atts[$key];
            else 
                $$key =$values;
         }
        $name = 'cs_pb_flex_column';
        $coloumn_class = 'column_'.$flex_column_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
        ?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?>
		<?php echo esc_attr($shortcode_view);?>" item="flex_column" data="<?php echo cs_element_size_data_array_index($flex_column_element_size)?>" >
  		<?php cs_element_setting($name,$cs_counter,$flex_column_element_size, '', 'columns',$type='');?>
	  <div class="cs-wrapp-class-<?php echo intval($cs_counter)?>
	  	<?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[cs_column {{attributes}}]{{content}}[/cs_column]" style="display: none;">
		<div class="cs-heading-area">
			  <h5><?php _e('Edit Flex Column Options','dir');?></h5>
			  <a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')"
			  class="cs-btnclose"><i class="icon-times"></i>
			  </a>
		</div>
    <div class="cs-pbwp-content">
		  <div class="cs-wrapp-clone cs-shortcode-wrapp">
			<?php
			if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
				<ul class="form-elements">
					<li class="to-label">
						<label><?php _e('Section Title','dir');?></label>
					</li>
					<li class="to-field">
						<input name="flex_column_section_title[]" type="text" value="<?php echo cs_allow_special_char($flex_column_section_title);?>"   />
						<p><?php _e('This is used for the one page navigation, to identify the section below. Give a title','dir');?></p>
					</li>
				</ul>
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php _e('Column Text','dir');?></label>
					  </li>
					  <li class="to-field">
                        <textarea name="flex_column_text[]" data-content-text="cs-shortcode-textarea"><?php echo esc_textarea($flex_column_text)?></textarea>
                        <p><?php _e('Enter your content','dir');?></p>
					  </li>
				</ul>
			 <?php 
				if ( function_exists( 'cs_shortcode_custom_dynamic_classes' ) ) {
					cs_shortcode_custom_dynamic_classes($cs_column_class,$cs_column_animation,'','cs_column');
				}
			?>
		  </div>
      <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
		  <ul class="form-elements insert-bg">
			<li class="to-field">
				<a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo str_replace('cs_pb_','',$name);?>','<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" ><?php _e('Insert','dir');?></a>
			</li>
		  </ul>
      <div id="results-shortocde"></div>
      <?php } else {?>
		  <ul class="form-elements noborder">
			<li class="to-label"></li>
			<li class="to-field">
			  <input type="hidden" name="cs_orderby[]" value="flex_column" />
			  <input type="button" value="Save" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
			</li>
		  </ul>
      <?php }?>
    </div>
  </div>
</div>
<?php
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_pb_flex_column', 'cs_pb_flex_column');
}
// Flex Column html form for page builder end

//=====================================================================
// dropcap html form for page builder start
//=====================================================================
if ( ! function_exists( 'cs_pb_dropcap' ) ) {
    function cs_pb_dropcap($die = 0){
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
            $shortcode_str = stripslashes ($shortcode_element_id);
            $PREFIX = 'cs_dropcap';
            $parseObject     = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
        $defaults = array('cs_dropcap_section_title' => '', 'dropcap_style' => 'dropcap','dropcap_bg_color' => '#ec2c3b','dropcap_color' => '#fff','dropcap_size' => '', 'cs_dropcap_class'=>'', 'cs_dropcap_animation'=>'');
            if(isset($output['0']['atts']))
                $atts = $output['0']['atts'];
            else 
                $atts = array();
            if(isset($output['0']['content']))
                $dropcap_content = $output['0']['content'];
            else 
                $dropcap_content = '';
            $dropcap_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_dropcap';
            $coloumn_class = 'column_'.$dropcap_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
    ?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?>
		 <?php echo esc_attr($shortcode_view);?>" item="blog" data="<?php echo cs_element_size_data_array_index($dropcap_element_size)?>" >
  		<?php cs_element_setting($name,$cs_counter,$dropcap_element_size, '', 'bold',$type='');?>
		<div class="cs-wrapp-class-<?php echo intval($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[cs_dropcap {{attributes}}]{{content}}[/cs_dropcap]" style="display: none;">
			<div class="cs-heading-area">
			  <h5><?php _e('Edit Drop Cap Options','dir');?></h5>
			  <a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')"
			  class="cs-btnclose"><i class="icon-times"></i>
			  </a>
			</div>
			<div class="cs-pbwp-content">
				  <div class="cs-wrapp-clone cs-shortcode-wrapp">
					<?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
						<ul class="form-elements">
							  <li class="to-label">
								<label><?php _e('Section Title','dir');?></label>
							  </li>
							  <li class="to-field">
								<input  name="cs_dropcap_section_title[]" type="text"  value="<?php echo cs_allow_special_char($cs_dropcap_section_title)?>"   />
								<p> <?php _e('This is used for the one page navigation, to identify the section below. Give a title','dir');?></p>
							  </li>
						</ul>
						<ul class="form-elements">
							  <li class="to-label">
								<label><?php _e('Style','dir');?></label>
							  </li>
							  <li class="to-field select-style">
								<select name="dropcap_style[]">
								  <option <?php if($dropcap_style=="box")echo "selected";?> value="box" ><?php _e('Box','dir');?></option>
								  <option <?php if($dropcap_style=="plain")echo "selected";?> value="plain" ><?php _e('Plain','dir');?></option>
								</select>
							  </li>
						</ul>
						<ul class="form-elements">
							  <li class="to-label">
								<label><?php _e('Font Size','dir');?></label>
							  </li>
							  <li class="to-field">
								<div class="cs-drag-slider" data-slider-min="0" data-slider-max="80" data-slider-step="1" data-slider-value="<?php echo esc_attr($dropcap_size)?>"></div>
								<input  class="cs-range-input"  name="dropcap_size[]" type="text" value="<?php echo intval($dropcap_size)?>"   />
								<p><?php _e('add your font size for the drop cap text','dir');?></p>
							  </li>
						</ul>
						<ul class="form-elements">
							  <li class="to-label">
								<label><?php _e('Color','dir');?></label>
							  </li>
							  <li class="to-field">
								<input type="text" name="dropcap_color[]" class="bg_color"  value="<?php echo esc_attr($dropcap_color);?>" />
							  </li>
						</ul>
						<ul class="form-elements">
							  <li class="to-label">
								<label><?php _e('Background Color','dir');?></label>
							  </li>
							  <li class="to-field">
								<input type="text" name="dropcap_bg_color[]" class="bg_color"  value="<?php echo esc_attr($dropcap_bg_color);?>" />
							  </li>
						</ul>
						<ul class="form-elements">
							  <li class="to-label">
								<label><?php _e('Content','dir');?></label>
							  </li>
							  <li class="to-field">
								<textarea name="dropcap_content[]" data-content-text="cs-shortcode-textarea">
								<?php echo esc_attr($dropcap_content)?></textarea>
								<p><?php _e('Enter content here','dir');?></p>
							  </li>
						</ul>
					<?php 
						if ( function_exists( 'cs_shortcode_custom_dynamic_classes' ) ) {
							cs_shortcode_custom_dynamic_classes($cs_dropcap_class,$cs_dropcap_animation,'','cs_dropcap');
						}
					?>
				  </div>
			  <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
				  <ul class="form-elements insert-bg">
					<li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo str_replace('cs_pb_','',$name);?>','<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" ><?php _e('Insert','dir');?></a> </li>
				  </ul>
			  <div id="results-shortocde"></div>
			  <?php } else {?>
				  <ul class="form-elements noborder">
					<li class="to-label"></li>
					<li class="to-field">
					  <input type="hidden" name="cs_orderby[]" value="dropcap" />
					  <input type="button" value="Save" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
					</li>
				  </ul>
			  <?php }?>
			</div>
		  </div>
</div>
<?php
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_pb_dropcap', 'cs_pb_dropcap');
}
// dropcap html form for page builder end

//=====================================================================
// highlight html form for page builder start
//=====================================================================
if ( ! function_exists( 'cs_pb_highlight' ) ) {
    function cs_pb_highlight($die = 0){
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
            $shortcode_str = stripslashes ($shortcode_element_id);
            $PREFIX = 'cs_highlight';
            $parseObject     = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
        $defaults = array( 'highlight_bg_color' => '','highlight_color' => '','cs_highlight_class' => '','cs_highlight_animation'=>'');
            if(isset($output['0']['atts']))
                $atts = $output['0']['atts'];
            else 
                $atts = array();
            if(isset($output['0']['content']))
                $highlight_content = $output['0']['content'];
            else 
                $highlight_content = '';
            $highlight_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_highlight';
            $coloumn_class = 'column_'.$highlight_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
    ?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo cs_allow_special_char($coloumn_class);?>
		 <?php echo cs_allow_special_char($shortcode_view);?>" item="highlight" data="<?php echo cs_element_size_data_array_index(	         $highlight_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$highlight_element_size, '', 'pencil',$type='');?>
	  <div class="cs-wrapp-class-<?php echo intval($cs_counter)?> <?php echo cs_allow_special_char($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>"  data-shortcode-template="[cs_highlight {{attributes}}]{{content}}[/cs_highlight]" style="display: none;">
		<div class="cs-heading-area">
	  		<h5><?php _e('Edit Highlight Options','dir');?></h5>
	  		<a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')"
	  		class="cs-btnclose"><i class="icon-times"></i>
	  </a>
		 </div>
		<div class="cs-pbwp-content">
			  <div class="cs-wrapp-clone cs-shortcode-wrapp">
					<ul class="form-elements">
						  <li class="to-label">
							<label><?php _e('Color','dir');?></label>
						  </li>
						  <li class="to-field">
							<div class="pic-color"><input type="text" name="highlight_color[]" class="bg_color" value="<?php echo esc_attr(                             $highlight_color);?>" />
							</div>
							<p><?php _e('Provide a hex colour code here (include #) if you want to override the default','dir');?></p>
						  </li>
					</ul>
					<ul class="form-elements">
						  <li class="to-label">
							<label><?php _e('Background Color','dir');?></label>
						  </li>
						  <li class="to-field">
							<div class="pic-color"><input type="text" name="highlight_bg_color[]" class="bg_color" value="<?php echo esc_attr(                             $highlight_bg_color);?>" />
							</div>
							<p><?php _e('Provide a hex colour code here (include #) if you want to override the default','dir');?></p>
						  </li>
					</ul>
					<ul class="form-elements">
						  <li class="to-label">
							<label><?php _e('Content','dir');?></label>
						  </li>
						  <li class="to-field">
							<textarea name="highlight_content[]" data-content-text="cs-shortcode-textarea" ><?php echo esc_textarea(                              $highlight_content)?>
							</textarea>
							<p><?php _e('Enter the content','dir');?></p>
						  </li>
					</ul>
				<?php 
					if ( function_exists( 'cs_shortcode_custom_dynamic_classes' ) ) {
							cs_shortcode_custom_dynamic_classes($cs_highlight_class,$cs_highlight_animation,'','cs_highlight');
					}
				?>
			  </div>
		  <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
				  <ul class="form-elements insert-bg noborder">
					<li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo str_replace('cs_pb_','',$name);?>','<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" ></a> 
					</li>
				  </ul>
				  <div id="results-shortocde"></div>
				  <?php } else {?>
				  <ul class="form-elements noborder">
					<li class="to-label"></li>
					<li class="to-field">
					  <input type="hidden" name="cs_orderby[]" value="highlight" />
					  <input type="button" value="Save" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
					</li>
				  </ul>
		  <?php }?>
		</div>
	  </div>
</div>
<?php
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_pb_highlight', 'cs_pb_highlight');
}
// highlight html form for page builder end

//=====================================================================
// Heading html form for page builder start
//=====================================================================
if ( ! function_exists( 'cs_pb_heading' ) ) {
    function cs_pb_heading($die = 0){
        global $cs_node, $post;
        $shortcode_element = '';
        $g_fonts = cs_get_google_fonts();
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
            $PREFIX = 'cs_heading';
            $parseObject     = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
        $defaults = array( 'heading_title' => '','color_title'=>'','heading_color' => '#000', 'class'=>'cs-heading-shortcode', 'heading_style'=>'1','heading_style_type'=>'1', 'heading_size'=>'', 'font_weight'=>'', 'heading_font_style'=>'', 'heading_align'=>'center', 'heading_divider'=>'', 'heading_color' => '', 'heading_content_color' => '', 'heading_animation'=>'');
            if(isset($output['0']['atts']))
                $atts = $output['0']['atts'];
            else 
                $atts = array();
            if(isset($output['0']['content']))
                $heading_content = $output['0']['content'];
            else 
                $heading_content = '';
            $heading_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_heading';
            $coloumn_class = 'column_'.$heading_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
    ?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="heading" data="<?php echo cs_element_size_data_array_index($heading_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$heading_element_size, '', 'h-square',$type='');?>
	  <div class="cs-wrapp-class-<?php echo intval($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>"  data-shortcode-template="[cs_heading {{attributes}}]{{content}}[/cs_heading]" style="display: none;">
		<div class="cs-heading-area">
			<h5><?php _e('Edit Heading Options','dir');?></h5>
			<a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')"
			class="cs-btnclose"><i class="icon-times"></i>
			</a>
		  </div>
		<div class="cs-pbwp-content">
		  <div class="cs-wrapp-clone cs-shortcode-wrapp">
			<?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php _e('Title','dir');?></label>
					  </li>
					  <li class="to-field">
						<input type="text" name="heading_title[]" class="txtfield" value="<?php echo cs_allow_special_char($heading_title);?>" />
					  </li>
				</ul>
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php _e('Color Title','dir');?></label>
					  </li>
					  <li class="to-field">
						<input type="text" name="color_title[]" class="txtfield" value="<?php echo cs_allow_special_char($color_title);?>" />
					  </li>
				</ul>
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php _e('Content','dir');?></label>
					  </li>
					  <li class="to-field">
						<textarea name="heading_content[]" rows="8" cols="40" data-content-text="cs-shortcode-textarea">
							<?php echo esc_textarea($heading_content);?>
						</textarea>
						<p><?php _e('Enter content here','dir');?></p>
					  </li>
				</ul>
				<ul class="form-elements">
				  <li class="to-label">
					<label><?php _e('Style','dir');?></label>
				  </li>
				  <li class="to-field select-style">
					<select name="heading_style[]">
					  <option <?php if($heading_style=="1")echo "selected";?> value="1" >h1</option>
					  <option <?php if($heading_style=="2")echo "selected";?> value="2" >h2</option>
					  <option <?php if($heading_style=="3")echo "selected";?> value="3" >h3</option>
					  <option <?php if($heading_style=="4")echo "selected";?> value="4" >h4</option>
					  <option <?php if($heading_style=="5")echo "selected";?> value="5" >h5</option>
					  <option <?php if($heading_style=="6")echo "selected";?> value="6" >h6</option>
					</select>
				  </li>
				</ul>
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php _e('Font Size','dir');?></label>
					  </li>
					  <li class="to-field">
						<div class="cs-drag-slider" data-slider-min="0" data-slider-max="50" data-slider-step="1"
						 data-slider-value="<?php echo intval($heading_size)?>">
						</div>
						<input  class="cs-range-input"  name="heading_size[]" type="text" value="<?php echo esc_attr($heading_size)?>"   />
						<p><?php _e('add font size number for the heading','dir');?></p>
					  </li>
				</ul>
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php _e('Align','dir');?></label>
					  </li>
					  <li class="to-field select-style">
						<select class="dropdown" name="heading_align[]">
						  <option value="left" <?php if($heading_align=='left'){echo 'selected="selected"';}?>><?php _e('Left','dir');?></option>
						  <option  value="center" <?php if($heading_align=='center'){echo 'selected="selected"';}?>><?php _e('Center','dir');?></option>
						  <option value="right" <?php if($heading_align=='right'){echo 'selected="selected"';}?>><?php _e('Right','dir');?></option>
						</select>
						<p><?php _e('Align the content position','dir');?></p>
					  </li>
				</ul>
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php _e('Divider On/Off','dir');?></label>
					  </li>
					  <li class="to-field select-style">
						<select class="dropdown" name="heading_divider[]">
						  <option value="on" <?php if($heading_divider=='on'){echo 'selected="selected"';}?>><?php _e('On','dir');?></option>
						  <option  value="off" <?php if($heading_divider=='off'){echo 'selected="selected"';}?>><?php _e('Off','dir');?></option>
						</select>
						<p><?php _e('set divider on/off for the list bottom border','dir');?></p>
					  </li>
				</ul>
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php _e('Font Style','dir');?></label>
					  </li>
					  <li class="to-field select-style">
						<select class="dropdown" name="heading_font_style[]">
						  <option value="normal" <?php if($heading_font_style=='normal'){echo 'selected="selected"';}?>><?php _e('Normal','dir');?></option>
						  <option value="italic" <?php if($heading_font_style=='italic'){echo 'selected="selected"';}?>><?php _e('Italic','dir');?></option>
						  <option value="oblique" <?php if($heading_font_style=='oblique'){echo 'selected="selected"';}?>><?php _e('Oblique','dir');?></option>
						</select>
						<p><?php _e('select a font style from the drop down','dir');?></p>
					  </li>
				</ul>
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php _e('Heading Color','dir');?></label>
					  </li>
					  <li class="to-field">
						<input type="text" name="heading_color[]" class="bg_color"  value="<?php echo esc_attr($heading_color);?>" />
						<div class="left-info">
						  <p><?php _e('heading color for the heading element','dir');?></p>
						</div>
					  </li>
				</ul>
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php _e('Content Color','dir');?></label>
					  </li>
					  <li class="to-field">
						<input type="text" name="heading_content_color[]" class="bg_color"  value="<?php echo esc_attr($heading_content_color);?>" />
						<div class="left-info">
						  <p><?php _e('set a content color for the heading element','dir');?></p>
						</div>
					  </li>
				</ul>
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php _e('Animation Class','dir');?></label>
					  </li>
					  <li class="to-field">
						<div class="select-style">
						  <select class="dropdown" name="heading_animation[]">
							<option value=""><?php _e('Select Animation','dir');?></option>
							<?php 
								$animation_array = cs_animation_style();
								foreach($animation_array as $animation_key=>$animation_value){
									echo '<optgroup label="'.$animation_key.'">';    
									foreach($animation_value as $key=>$value){
										$active_class = '';
										if($heading_animation == $key){$active_class = 'selected="selected"';}
										echo '<option value="'.$key.'" '.$active_class.'>'.$value.'</option>';
									}
								}
							 ?>
						  </select>
						  <p><?php _e('Select Entrance animation type from the dropdown','dir');?> </p>
						</div>
					  </li>
				</ul>
		  </div>
		  <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
			  <ul class="form-elements insert-bg">
				<li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo str_replace('cs_pb_','',$name);?>','<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" ><?php _e('Insert','dir');?></a> </li>
			  </ul>
		  <div id="results-shortocde"></div>
		  <?php } else {?>
			  <ul class="form-elements noborder">
				<li class="to-label"></li>
				<li class="to-field">
				  <input type="hidden" name="cs_orderby[]" value="heading" />
				  <input type="button" value="Save" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
				</li>
			  </ul>
		  <?php }?>
		</div>
	  </div>
</div>
<?php
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_pb_heading', 'cs_pb_heading');
}
// Heading html form for page builder end

//=====================================================================
// List Item html form for page builder start
//=====================================================================
if ( ! function_exists( 'cs_pb_list' ) ) {
    function cs_pb_list($die = 0){
        global $cs_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $counter = $_POST['counter'];
        $cs_counter = $_POST['counter'];
        $list_num = 0;
        if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
            $PREFIX = 'cs_list|list_item';
            $parseObject     = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
        $defaults = array('cs_list_section_title'=>'','cs_list_type'=>'','cs_list_icon'=>'','cs_border'=>'','cs_list_item'=>'','cs_list_class'=>'','cs_list_animation'=>'');
            if(isset($output['0']['atts']))
                $atts = $output['0']['atts'];
            else 
                $atts = array();
            if(isset($output['0']['content']))
                $atts_content = $output['0']['content'];
            else 
                $atts_content = array();
            if(is_array($atts_content))
                    $list_num = count($atts_content);
            $list_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_list';
            $coloumn_class = 'column_'.$list_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
    ?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="list" data="<?php echo cs_element_size_data_array_index($list_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$list_element_size, '', 'list-ol',$type='');?>
  <div class="cs-wrapp-class-<?php echo intval($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" style="display: none;">
    <div class="cs-heading-area">
		  <h5><?php _e('Edit List Style Options','dir');?></h5>
		  <a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')"
		  class="cs-btnclose"><i class="icon-times"></i>
		  </a>
	  </div>
    <div class="cs-wrapp-tab-box">
      <div class="cs-clone-append cs-pbwp-content" >
        <div id="shortcode-item-<?php echo intval($cs_counter);?>" data-shortcode-template="{{child_shortcode}} [/cs_list]" data-shortcode-child-template="[list_item {{attributes}}] {{content}} [/list_item]">
          <div class="cs-wrapp-clone cs-shortcode-wrapp cs-disable-true" data-template="[cs_list {{attributes}}]">
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php _e('Section Title','dir');?></label>
					  </li>
					  <li class="to-field">
						<input  name="cs_list_section_title[]" type="text"  value="<?php echo cs_allow_special_char($cs_list_section_title)?>"   />
						<p><?php _e('This is used for the one page navigation, to identify the section below. Give a title','dir');?> </p>
					  </li>
				</ul>
				<ul class="form-elements">
				  <li class="to-label">
					<label><?php _e('List Style','dir');?></label>
				  </li>
				  <li class="to-field select-style">
					<select class="dropdown" id="cs_list_type_selected" name="cs_list_type[]" onchange="cs_toggle_list(this.value,'cs_slider_height<?php echo esc_attr($name.$cs_counter)?>')">
					  <option value="none" <?php if($cs_list_type =="none")echo "selected";?>><?php _e('None','dir');?></option>
					  <option value="icon" <?php if($cs_list_type =="icon")echo "selected";?>><?php _e('Icon','dir');?> </option>
					  <option value="built" <?php if($cs_list_type =="built")echo "selected";?>><?php _e('Bullet','dir');?></option>
					  <option value="decimal" <?php if($cs_list_type =="decimal") echo "selected";?> ><?php _e('Decimal','dir');?></option>
					  <option value="alphabatic" <?php if($cs_list_type =="alphabatic")echo "selected";?>><?php _e('Alphabatic','dir');?> </option>
					  <option value="numeric-icon" <?php if($cs_list_type =="numeric-icon")echo "selected";?>><?php _e('Numeric and Icon','dir');?> </option>
					  <!-- <option value="custom_icon">Custom Icon</option>-->
					</select>
					<p><?php _e('set a list style from the dropdown','dir');?></p>
				  </li>
				</ul>
				<ul class="form-elements">
				  <li class="to-label">
					<label><?php _e('Border Bottom','dir');?></label>
				  </li>
				  <li class="to-field select-style">
					<select class="dropdown" name="cs_border[]">
					  <option <?php if($cs_border == "yes")echo "selected";?> value="yes"><?php _e('Yes','dir');?></option>
					  <option value="no" <?php if($cs_border == "no")echo "selected";?>><?php _e('No','dir');?></option>
					</select>
					<p><?php _e('set on/off for the list bottom border','dir');?> </p>
				  </li>
				</ul>
            <?php 
                if ( function_exists( 'cs_shortcode_custom_dynamic_classes' ) ) {
                    cs_shortcode_custom_dynamic_classes($cs_list_class,$cs_list_animation,'','cs_list');
                }
            ?>
          </div>
          <?php
               if ( isset($list_num) && $list_num <> '' && isset($atts_content) && is_array($atts_content)){
                foreach ( $atts_content as $list_items ){
                    $rand_id = $cs_counter.''.cs_generate_random_string(3);
                    $cs_list_item = $list_items['content'];
                    $defaults = array('cs_list_icon'=>'','cs_cusotm_class'=>'','cs_custom_animation'=>'');
                    foreach($defaults as $key=>$values){
                        if(isset($list_items['atts'][$key]))
                            $$key = $list_items['atts'][$key];
                        else 
                            $$key =$values;
                    }
                ?>
                <div class='cs-wrapp-clone cs-shortcode-wrapp'  id="cs_infobox_<?php echo esc_attr($rand_id);?>">
                    <header>
                      <h4><i class='icon-arrows'></i><?php _e('List Item','dir');?></h4>
                      <a href='#' class='deleteit_node'><i class='icon-minus-circle'></i>Remove</a>
					  </header>
						<ul class='form-elements'>
						  <li class='to-label'>
							<label><?php _e('List Item:','dir');?></label>
						  </li>
						  <li class='to-field'>
							<div class='input-sec'>
							  <input class='txtfield' type='text' name='cs_list_item[]' data-content-text="cs-shortcode-textarea"
							   value="<?php echo cs_allow_special_char($cs_list_item) ?>" />
							</div>
						  </li>
						</ul>
						<ul class='form-elements' id="cs_infobox_<?php echo esc_attr($name.$cs_counter);?>">
						  <li class='to-label'>
							<label><?php _e('Font awsome Icon:','dir');?> </label>
						  </li>
						  <li class="to-field">
						   <?php cs_fontawsome_icons_box($cs_list_icon,$rand_id,'cs_list_icon');?>
						  </li>
						</ul>
                  </div>
                <?php
                        }
                    }
                    ?>
        </div>
        <div class="hidden-object">
          <input type="hidden" name="list_num[]" value="<?php echo intval($list_num);?>" class="fieldCounter"  />
        </div>
        <div class="wrapptabbox no-padding-lr">
          <div class="opt-conts">
				<ul class="form-elements">
				  <li class="to-field"> <a href="#" class="add_servicesss cs-main-btn"
				   onclick="cs_shortcode_element_ajax_call('list', 'shortcode-item-<?php echo esc_js($cs_counter);?>', '<?php echo admin_url(                   'admin-ajax.php');?>')">
				  <i class="icon-plus-circle"></i><?php _e('Add List Item','dir');?> </a> </li>
				  <div id="loading" class="shortcodeload"></div>
				</ul>
          </div>
          <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
			  <ul class="form-elements insert-bg noborder">
				<li class="to-field"> 
					<a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo str_replace('cs_pb_','',$name);?>                     ','shortcode-item-<?php echo esc_js($cs_counter);?>','<?php echo esc_js($filter_element);?>')" ><?php _e('Insert','dir');?></a>
					 </li>
			  </ul>
          <div id="results-shortocde"></div>
          <?php } else {?>
          <ul class="form-elements noborder no-padding-lr">
            <li class="to-label"></li>
            <li class="to-field">
              <input type="hidden" name="cs_orderby[]" value="list" />
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
    add_action('wp_ajax_cs_pb_list', 'cs_pb_list');
}
// List Item html form for page builder End

//=====================================================================
// Page Builder Element
//=====================================================================
function cs_get_pagebuilder_element($shortcode_element_id,$POSTID){
        $cs_page_bulider = get_post_meta($POSTID, "cs_page_builder", true);
        if(isset($cs_page_bulider) && $cs_page_bulider<>''){
            $cs_xmlObject = new SimpleXMLElement($cs_page_bulider);
        }
        $shortcode_element_array = explode('_',$shortcode_element_id);
        $section_no = $shortcode_element_array['0'];
        $columnn_no = $shortcode_element_array['1'];
        $section = 0;
        $colummmn = 0;
        foreach ($cs_xmlObject->column_container as $column_container) {
            $section++;
            if($section ==$section_no){
                foreach ($column_container->children() as $column) {
                    foreach ($column->children() as $cs_node) {
                        $colummmn++;
                        if($colummmn ==$columnn_no){
                            break;
                        }
                    }
                }
            }
            break;
        }
        return $cs_node;
}

//=====================================================================
// Message html form for page builder start
//=====================================================================
if ( ! function_exists( 'cs_pb_mesage' ) ) {
    function cs_pb_mesage($die = 0){
        global $cs_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $PREFIX = 'cs_message';
        $cs_counter = $_POST['counter'];
        $parseObject     = new ShortcodeParse();
        if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
        
        $defaults = array( 'column_size' => '1/1', 'cs_msg_section_title' => '', 'cs_message_title' => '','cs_message_type' => '','cs_alert_style' => '','cs_style_type' => '', 'cs_message_icon' => '','cs_title_color' => '','cs_message_box_title' => '','cs_background_color' => '','cs_text_color' => '','cs_button_text' => '','cs_button_link' => '','cs_icon_color' => '','cs_message_close' => '','cs_message_class' => '','cs_message_animation' => '');
            
        if(isset($output['0']['atts']))
            $atts = $output['0']['atts'];
        else 
            $atts = array();
        
        if(isset($output['0']['content']))
            $atts_content = $output['0']['content'];
        else 
            $atts_content = "";
            
        $message_element_size = '25';
        foreach($defaults as $key=>$values){
            if(isset($atts[$key]))
                $$key = htmlentities( $atts[$key], ENT_QUOTES);
            else 
                $$key = htmlentities( $values, ENT_QUOTES);
         }
        $name = 'cs_pb_mesage';
        $coloumn_class = 'column_'.$message_element_size;
    if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
        $shortcode_element = 'shortcode_element_class';
        $shortcode_view = 'cs-pbwp-shortcode';
        $filter_element = 'ajax-drag';
        $coloumn_class = '';
    }
    
    ?>
<div id="<?php echo cs_allow_special_char($name.$cs_counter)?>_del" class="column  parentdelete <?php echo cs_allow_special_char($coloumn_class);?> <?php echo cs_allow_special_char($shortcode_view);?>" item="mesage" data="<?php echo cs_element_size_data_array_index($message_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$message_element_size, '', 'envelope',$type='');?>
  <div class="cs-wrapp-class-<?php echo cs_allow_special_char($cs_counter)?> <?php echo cs_allow_special_char($shortcode_element);?>" id="<?php echo cs_allow_special_char($name.$cs_counter)?>" data-shortcode-template="[cs_message {{attributes}}]{{content}}[/cs_message]" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php _e('Edit Message Options','dir');?></h5>
      <a href="javascript:removeoverlay('<?php echo cs_allow_special_char($name.$cs_counter)?>','<?php echo cs_allow_special_char($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a>
	</div>
    <div class="cs-pbwp-content">
      <div class="cs-wrapp-clone cs-shortcode-wrapp cs-pbwp-content">
        <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
			<ul class="form-elements">
			  <li class="to-label">
				<label><?php _e('Section Title','dir');?></label>
			  </li>
			  <li class="to-field">
				<input  name="cs_msg_section_title[]" type="text"  value="<?php echo cs_allow_special_char($cs_msg_section_title);?>"   />
				<p><?php _e('This is used for the one page navigation, to identify the section below. Give a title','dir');?></p>
			  </li>
			</ul>
			<ul class="form-elements">
			  <li class="to-label">
				<label><?php _e('Title','dir');?></label>
			  </li>
			  <li class="to-field">
				<input type="text" name="cs_message_title[]" class="txtfield" value="<?php echo cs_allow_special_char($cs_message_title);?>" />
			  </li>
			</ul>
			<ul class="form-elements">
			  <li class="to-label">
				<label><?php _e('Message Type','dir');?></label>
			  </li>
			  <li class="to-field select-style">
				<select id="cs_message_type<?php echo cs_allow_special_char($cs_counter)?>"
				 name="cs_message_type[]" onchange="cs_toggle_alerts(this.value,<?php echo cs_allow_special_char($cs_counter)?>)">
				  <option <?php if($cs_message_type=="alert")echo "selected";?> value="alert" ><?php _e('Alert','dir');?></option>
				  <option <?php if($cs_message_type=="message")echo "selected";?> value="message" ><?php _e('Message','dir');?></option>
				</select>
				<p><?php _e('Select the display type for the Message','dir');?></p>
			  </li>
			</ul>
			<ul class="form-elements" id="cs_alerts_style<?php echo cs_allow_special_char($cs_counter)?>" style="display:none">
			  <li class="to-label">
				<label><?php _e('Message Style','dir');?></label>
			  </li>
			  <li class="to-field select-style">
				<select name="cs_alert_style[]" id="cs_alert_style<?php echo cs_allow_special_char($cs_counter)?>"
				onchange="cs_toggle_fancyalert(this.value,<?php echo cs_allow_special_char($cs_counter)?>)">
				  <option <?php if($cs_alert_style=="normal_messagebox")echo "selected";?> value="normal_messagebox" ><?php _e('Normal','dir');?></option>
				  <option <?php //if($cs_alert_style=="threed_messagebox")echo "selected";?> selected  value="threed_messagebox" ><?php _e('3D Style','dir');?></option>
				</select>
			  </li>
			</ul>
			<ul class='form-elements' id="cs_infobox_<?php echo cs_allow_special_char($name.$cs_counter);?>">
			  <li class='to-label'>
				<label><?php _e('Font awsome Icon:','dir');?></label>
			  </li>
			  <li class="to-field">
				<?php cs_fontawsome_icons_box( $cs_message_icon ,$name.$cs_counter,'cs_message_icon');?>
			  </li>
			</ul>
			<ul class="form-elements">
			  <li class="to-label">
				<label><?php _e('Icon Color','dir');?></label>
			  </li>
			  <li class="to-field">
				<input type="text" name="cs_icon_color[]" class="bg_color" value="<?php echo esc_attr($cs_icon_color);?>" />
			  </li>
			</ul>
        <div id="fancy_active<?php echo intval($cs_counter)?>">
          <ul class="form-elements">
            <li class="to-label">
              <label><?php _e('Button Text','dir');?></label>
            </li>
            <li class="to-field">
              <input type="text" name="cs_button_text[]" class="" value="<?php echo esc_attr($cs_button_text)?>" />
            </li>
          </ul>
          <ul class="form-elements">
            <li class="to-label">
              <label><?php _e('Button Link','dir');?></label>
            </li>
            <li class="to-field">
              <input type="text" name="cs_button_link[]" class="" value="<?php echo esc_attr($cs_button_link);?>" />
            </li>
          </ul>
          <ul class="form-elements">
            <li class="to-label">
              <label><?php _e('Message Box Title','dir');?></label>
            </li>
            <li class="to-field">
              <input type="text" name="cs_message_box_title[]" class="" value="<?php echo esc_attr($cs_message_box_title)?>" />
            </li>
          </ul>
          <ul class="form-elements">
            <li class="to-label">
              <label><?php _e('Title Color','dir');?></label>
            </li>
            <li class="to-field">
              <input type="text" name="cs_title_color[]" class="bg_color" value="<?php echo esc_attr($cs_title_color);?>" />
            </li>
          </ul>
        </div>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Text Color','dir');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_text_color[]" class="bg_color" value="<?php echo esc_attr($cs_text_color);?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Background Color','dir');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_background_color[]" class="bg_color" value="<?php echo esc_attr($cs_background_color);?>" />
          </li>
        </ul>
        <ul class="form-elements" id="cs_style_type<?php echo intval($cs_counter)?>" style="display:none">
          <li class="to-label">
            <label><?php _e('Style Type','dir');?></label>
          </li>
          <li class="to-field select-style">
            <select name="cs_style_type[]" >
              <option <?php if($cs_style_type == "fancy")echo "selected";?> value="fancy" ><?php _e('Fancy','dir');?></option>
              <option <?php if($cs_style_type == "plain")echo "selected";?> value="plain" ><?php _e('Plain','dir');?></option>
            </select>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Close Button','dir');?></label>
          </li>
          <li class="to-field">
            <div class="select-style">
              <select name="cs_message_close[]">
                <option <?php if($cs_message_close=="yes")echo "selected";?> value="yes" ><?php _e('Yes','dir');?></option>
                <option <?php if($cs_message_close=="no")echo "selected";?> value="no" ><?php _e('No','dir');?></option>
              </select>
              <p><?php _e('Set close button on/off','dir');?></p>
            </div>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Text','dir');?></label>
          </li>
          <li class="to-field">
            <textarea rows="20" cols="40" data-content-text="cs-shortcode-textarea" name="cs_message_text[]"><?php echo esc_textarea($atts_content);?></textarea>
            <p><?php _e('Enter content here','dir');?></p>
          </li>
        </ul>
        <?php 
            if ( function_exists( 'cs_shortcode_custom_dynamic_classes' ) ) {
                cs_shortcode_custom_dynamic_classes($cs_message_class,$cs_message_animation,'','cs_message');
            }
        ?>
      </div>
      <script>
            var cs_message_type        = jQuery("#cs_message_type<?php echo intval($cs_counter);?>" ).val();
            var cs_message_style    = jQuery("#cs_message_style<?php echo intval($cs_counter);?>" ).val();
            cs_toggle_alerts(cs_message_type,'<?php echo esc_js($cs_counter)?>');
            cs_toggle_fancybutton(cs_message_style,'<?php echo esc_js($cs_counter)?>');
    </script>
      <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
      <ul class="form-elements insert-bg noborder">
        <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo str_replace('cs_pb_','',$name);?>','<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" ><?php _e('Insert','dir');?></a> </li>
      </ul>
      <div id="results-shortocde"></div>
      <?php } else { ?>
      <ul class="form-elements noborder">
        <li class="to-label"></li>
        <li class="to-field">
          <input type="hidden" name="cs_orderby[]" value="mesage" />
          <input type="button" value="Save" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
        </li>
      </ul>
      <?php }?>
    </div>
  </div>
</div>
<?php
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_pb_mesage', 'cs_pb_mesage');
}
// Message html form for page builder end


//=====================================================================
// Testimonial html form for page builder start
//=====================================================================
if ( ! function_exists( 'cs_pb_testimonials' ) ) {
    function cs_pb_testimonials($die = 0){
        global $cs_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $cs_counter = $_POST['counter'];
        $testimonials_num = 0;
        if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
            $PREFIX = 'cs_testimonials|testimonial_item';
            $parseObject     = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
        $defaults = array('column_size'=>'1/1','testimonial_text_color'=>'','testimonial_style'=>'','cs_testimonial_text_align'=>'','cs_testimonial_section_title'=>'','cs_testimonial_class'=>'','cs_testimonial_animation'=>'');
            if(isset($output['0']['atts']))
                $atts = $output['0']['atts'];
            else 
                $atts = array();
            if(isset($output['0']['content']))
                $atts_content = $output['0']['content'];
            else 
                $atts_content = array();
            if(is_array($atts_content))
                    $testimonials_num = count($atts_content);
            $testimonials_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_testimonials';
            $coloumn_class = 'column_'.$testimonials_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
    ?>
<div id="<?php echo cs_allow_special_char($name.$cs_counter)?>_del" class="column  parentdelete <?php echo cs_allow_special_char($coloumn_class);?> <?php echo cs_allow_special_char($shortcode_view);?>" item="testimonials" data="<?php echo cs_element_size_data_array_index($testimonials_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$testimonials_element_size, '', 'comments-o',$type='');?>
  <div class="cs-wrapp-class-<?php echo cs_allow_special_char($cs_counter)?> <?php echo cs_allow_special_char($shortcode_element);?>" id="<?php echo cs_allow_special_char($name.$cs_counter)?>" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php _e('Edit Testimonials Options','dir');?></h5>
      <a href="javascript:removeoverlay('<?php echo cs_allow_special_char($name.$cs_counter)?>','<?php echo cs_allow_special_char($filter_element);       ?>')" class="cs-btnclose"><i class="icon-times"></i></a>
	 </div>
      <div class="cs-clone-append cs-pbwp-content">
		  <div class="cs-wrapp-tab-box">
				<div id="shortcode-item-<?php echo cs_allow_special_char($cs_counter);?>" data-shortcode-template="{{child_shortcode}} [/cs_testimonials]" data-shortcode-child-template="[testimonial_item {{attributes}}] {{content}} [/testimonial_item]">
				  <div class="cs-wrapp-clone cs-shortcode-wrapp cs-disable-true cs-pbwp-content" data-template="[cs_testimonials {{attributes}}]">
					<?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
						<ul class="form-elements">
						  <li class="to-label">
							<label><?php _e('Section Title','dir');?></label>
						  </li>
						  <li class="to-field">
							<input  name="cs_testimonial_section_title[]" type="text"  value="<?php echo cs_allow_special_char($cs_testimonial_section_title)?>"   />
							<p><?php _e('This is used for the one page navigation, to identify the section below. Give a title','dir');?> </p>
						  </li>
						</ul>
						<ul class='form-elements'>
						  <li class='to-label'>
							<label><?php _e('Style','dir');?>:</label>
						  </li>
						  <li class='to-field'>
							<div class='input-sec select-style'>
							  <select name='testimonial_style[]' class='dropdown'>
								<option value='simple' <?php if($testimonial_style == 'simple'){echo 'selected';}?>><?php _e('Classic Slider','dir');?> </option>
								<option value='fancy' <?php if($testimonial_style == 'fancy'){echo 'selected';}?>><?php _e('Modern Slider','dir');?> </option>
								<option value='slider' <?php if($testimonial_style == 'slider'){echo 'selected';}?>><?php _e('Simple Slider','dir');?> </option>
							  </select>
							</div>
							<div class='left-info'>
							  <p><?php _e('Testimonial Style','dir');?></p>
							</div>
						  </li>
						</ul>
						<ul class="form-elements">
						  <li class="to-label">
							<label><?php _e('Align','dir');?></label>
						  </li>
						  <li class="to-field select-style">
							<select name="cs_testimonial_text_align[]" class="dropdown" >
							  <option <?php if($cs_testimonial_text_align=="left")echo "selected";?> ><?php _e('left','dir');?></option>
							  <option <?php if($cs_testimonial_text_align=="right")echo "selected";?> ><?php _e('right','dir');?></option>
							  <option <?php if($cs_testimonial_text_align=="center")echo "selected";?> ><?php _e('center','dir');?></option>
							</select>
							<p> <?php _e('This will not apply in Slider View','dir');?></p>
						  </li>
						</ul>
						<ul class="form-elements">
						  <li class="to-label">
							<label><?php _e('Text Color','dir');?></label>
						  </li>
						  <li class="to-field">
							<input  name="testimonial_text_color[]" type="text" class="bg_color"  value="<?php echo esc_attr($testimonial_text_color)?>"/>
						  </li>
						</ul>
					<?php  
						if ( function_exists( 'cs_shortcode_custom_dynamic_classes' ) ) {
							cs_shortcode_custom_dynamic_classes($cs_testimonial_class,$cs_testimonial_animation,'','cs_testimonial');
						}
						?>
				  </div>
				  <?php
					if ( isset($testimonials_num) && $testimonials_num <> '' && isset($atts_content) && is_array($atts_content)){
					
						foreach ( $atts_content as $testimonials ){
							
							$rand_string = $cs_counter.''.cs_generate_random_string(3);
							$testimonial_text = $testimonials['content'];
							$defaults = array('testimonial_author' =>'','testimonial_img' => '','testimonial_company' => '');
							foreach($defaults as $key=>$values){
								if(isset($testimonials['atts'][$key]))
									$$key = $testimonials['atts'][$key];
								else 
									$$key =$values;
							 }
							?>
				  <div class='cs-wrapp-clone cs-shortcode-wrapp cs-pbwp-content'
				   id="cs_infobox_<?php echo cs_allow_special_char($rand_string);?>">
					<header>
					  <h4><i class='icon-arrows'></i><?php _e('Testimonial','dir');?></h4>
					  <a href='#' class='deleteit_node'><i class='icon-minus-circle'></i>Remove</a>
					</header>
					<ul class='form-elements'>
					  <li class='to-label'>
						<label><?php _e('Text','dir');?></label>
					  </li>
					  <li class='to-field'>
						<div class='input-sec'>
						  <textarea class='txtfield' data-content-text="cs-shortcode-textarea" name='testimonial_text[]'><?php echo cs_allow_special_char($testimonial_text);?></textarea>
						</div>
					  </li>
					</ul>
					<ul class='form-elements'>
					  <li class='to-label'>
						<label><?php _e('Author','dir');?></label>
					  </li>
					  <li class='to-field'>
						<div class='input-sec'>
						  <input class='txtfield' type='text' name='testimonial_author[]' value="<?php echo cs_allow_special_char($testimonial_author);?>" />
						</div>
					  </li>
					</ul>
					<ul class='form-elements'>
					  <li class='to-label'>
						<label><?php _e('Company','dir');?></label>
					  </li>
					  <li class='to-field'>
						<div class='input-sec'>
						  <input class='txtfield' type='text' name='testimonial_company[]' value="<?php echo cs_allow_special_char($testimonial_company);?>" />
						</div>
						<div class='left-info'>
						  <p><?php _e('Company Name','dir');?></p>
						</div>
					  </li>
					</ul>
					<ul class="form-elements">
					  <li class="to-label">
						<label><?php _e('Image','dir');?></label>
					  </li>
					  <li class="to-field">
						<input id="testimonial_img<?php echo cs_allow_special_char($rand_string)?>" name="testimonial_img[]" type="hidden" class="" value="<?php echo cs_allow_special_char($testimonial_img);?>"/>
						<input name="testimonial_img<?php echo cs_allow_special_char($rand_string)?>"  type="button" class="uploadMedia left" value="Browse"/>
					  </li>
					</ul>
					<div class="page-wrap" style="overflow:hidden; display:<?php echo cs_allow_special_char($testimonial_img) && trim($testimonial_img) !='' ? 'inline' : 'none';?>" id="testimonial_img<?php echo cs_allow_special_char($rand_string)?>_box" >
					  <div class="gal-active">
						<div class="dragareamain" style="padding-bottom:0px;">
						  <ul id="gal-sortable">
							<li class="ui-state-default" id="">
							  <div class="thumb-secs"> <img src="<?php echo cs_allow_special_char($testimonial_img);?>"  id="testimonial_img<?php echo cs_allow_special_char($rand_string)?>_img" width="100" height="150"  />
								<div class="gal-edit-opts"> <a   href="javascript:del_media('testimonial_img<?php echo cs_allow_special_char($rand_string)?>')" class="delete"></a> </div>
							  </div>
							</li>
						  </ul>
						</div>
					  </div>
					</div>
				  </div>
				  <?php
						}
					}
					?>
				</div>
				<div class="hidden-object">
				  <input type="hidden" name="testimonials_num[]" value="<?php echo cs_allow_special_char($testimonials_num)?>" class="fieldCounter"/>
				</div>
				<div class="wrapptabbox cs-pbwp-content" style="padding:0">
				  <div class="opt-conts">
					<ul class="form-elements">
					  <li class="to-field"> <a href="#" class="add_servicesss cs-main-btn" onclick="cs_shortcode_element_ajax_call('testimonials', 'shortcode-item-<?php echo cs_allow_special_char($cs_counter);?>', '<?php echo admin_url('admin-ajax.php');?>')"><i class="icon-plus-circle"></i><?php _e('Add testimonials','dir');?></a> </li>
					  <div id="loading" class="shortcodeload"></div>
					</ul>
					<?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
					<ul class="form-elements insert-bg noborder">
					  <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo str_replace('cs_pb_','',$name);?>','shortcode-item-<?php echo cs_allow_special_char($cs_counter);?>','<?php echo cs_allow_special_char($filter_element);?>')" ><?php _e('Insert','dir');?></a> </li>
					</ul>
					<div id="results-shortocde"></div>
					<?php } else {?>
					<ul class="form-elements noborder">
					  <li class="to-label"></li>
					  <li class="to-field">
						<input type="hidden" name="cs_orderby[]" value="testimonials" />
						<input type="button" value="Save" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
					  </li>
					</ul>
					<?php }?>
				  </div>
				</div>
		  </div>
    </div>
  </div>
</div>
<?php
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_pb_testimonials', 'cs_pb_testimonials');
}
// Testimonial html form for page builder end

//=====================================================================
// quote html form for page builder start
//=====================================================================
if ( ! function_exists( 'cs_pb_quote' ) ) {
    function cs_pb_quote($die = 0){
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
            $shortcode_str = stripslashes ($shortcode_element_id);
            $PREFIX = 'cs_quote';
            $parseObject     = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
        $defaults = array(
                'quote_style' => 'default',
                'cs_quote_section_title' => '',
                'quote_cite'   => '',
                'quote_cite_url'   => '#',
                'quote_text_color'   => '',
                'quote_align'   => 'center',
                'cs_quote_class'   => '',
                'cs_quote_animation'   => ''
            );
            if(isset($output['0']['atts']))
                $atts = $output['0']['atts'];
            else 
                $atts = array();
            if(isset($output['0']['content']))
                $quote_content = $output['0']['content'];
            else 
                $quote_content = '';
            $quote_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_quote';
            $coloumn_class = 'column_'.$quote_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
    ?>
<div id="<?php echo cs_allow_special_char($name.$cs_counter)?>_del" class="column  parentdelete <?php echo cs_allow_special_char($coloumn_class);?> <?php echo cs_allow_special_char($shortcode_view);?>" item="column" data="<?php echo cs_element_size_data_array_index($quote_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$quote_element_size, '', 'quote-right',$type='');?>
  <div class="cs-wrapp-class-<?php echo intval($cs_counter)?> <?php echo cs_allow_special_char($shortcode_element);?>" id="<?php echo cs_allow_special_char($name.$cs_counter)?>"  data-shortcode-template="[cs_quote {{attributes}}]{{content}}[/cs_quote]" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php _e('Edit Quote Options','dir');?></h5>
      <a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')"
	   class="cs-btnclose"><i class="icon-times"></i></a>
	  </div>
    <div class="cs-pbwp-content">
      <div class="cs-wrapp-clone cs-shortcode-wrapp">
        <div class="cs-pbwp-content cs-wrapp-tab-box">
          <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
          <ul class="form-elements">
            <li class="to-label">
              <label><?php _e('Section Title','dir');?></label>
            </li>
            <li class="to-field">
              <input  name="cs_quote_section_title[]" type="text"  value="<?php echo cs_allow_special_char($cs_quote_section_title)?>"   />
              <p><?php _e('This is used for the one page navigation, to identify the section below. Give a title','dir');?>  </p>
            </li>
          </ul>
          <ul class="form-elements">
            <li class="to-label">
              <label><?php _e('Author','dir');?></label>
            </li>
            <li class="to-field">
              <input type="text" name="quote_cite[]" class="txtfield" value="<?php echo esc_attr($quote_cite)?>" />
              <p><?php _e('give the name of the author','dir');?></p>
            </li>
          </ul>
          <ul class="form-elements">
            <li class="to-label">
              <label><?php _e('Author Url','dir');?></label>
            </li>
            <li class="to-field">
              <input type="text" name="quote_cite_url[]" class="txtfield" value="<?php echo esc_url($quote_cite_url);?>" />
              <p><?php _e('Give the Author External / Internal Url','dir');?></p>
            </li>
          </ul>
          <ul class="form-elements">
            <li class="to-label">
              <label><?php _e('Text Color','dir');?></label>
            </li>
            <li class="to-field">
              <input type="text" name="quote_text_color[]" class="bg_color" value="<?php echo esc_attr($quote_text_color)?>" />
              <div class="left-box">
                <p><?php _e('Provide a hex colour code here (include #) if you want to override the default','dir');?></p>
              </div>
            </li>
          </ul>
          <ul class="form-elements">
            <li class="to-label">
              <label><?php _e('Align','dir');?></label>
            </li>
            <li class="to-field select-style">
              <select name="quote_align[]" class="dropdown" >
                <option <?php if($quote_align=="left")echo "selected";?> ><?php _e('left','dir');?>left</option>
                <option <?php if($quote_align=="right")echo "selected";?> ><?php _e('right','dir');?></option>
                <option <?php if($quote_align=="center")echo "selected";?> ><?php _e('center','dir');?></option>
              </select>
              <p><?php _e('Align the content position','dir');?></p>
            </li>
          </ul>
          <ul class="form-elements">
            <li class="to-label">
              <label><?php _e('Quote Content','dir');?></label>
            </li>
            <li class="to-field">
              <textarea name="quote_content[]" data-content-text="cs-shortcode-textarea"><?php echo esc_textarea($quote_content);?></textarea>
              <p><?php _e('Enter your content','dir');?></p>
            </li>
          </ul>
          <?php 
                if ( function_exists( 'cs_shortcode_custom_dynamic_classes' ) ) {
                    cs_shortcode_custom_dynamic_classes($cs_quote_class,$cs_quote_animation,'','cs_quote');
                }
            ?>
        </div>
      </div>
      <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
      <ul class="form-elements insert-bg">
        <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo str_replace('cs_pb_','',$name);?>','<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" ><?php _e('Insert','dir');?></a> </li>
      </ul>
      <div id="results-shortocde"></div>
      <?php } else {?>
      <ul class="form-elements noborder">
        <li class="to-label"></li>
        <li class="to-field">
          <input type="hidden" name="cs_orderby[]" value="quote" />
          <input type="button" value="Save" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
        </li>
      </ul>
      <?php }?>
    </div>
  </div>
</div>
<?php
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_pb_quote', 'cs_pb_quote');
}
// quote html form for page builder end

//=====================================================================
// Contact Form html form for page builder start
//=====================================================================
if ( ! function_exists( 'cs_pb_contactus' ) ) {
    function cs_pb_contactus($die = 0){
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
            $shortcode_str = stripslashes ($shortcode_element_id);
            $PREFIX = 'cs_contactus';
            $parseObject     = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
        $defaults = array( 'cs_contactus_section_title' => '', 'cs_contactus_label' => '', 'cs_contactus_view' => '','cs_contactus_send' => '','cs_success' => '','cs_error' => '','cs_contact_class' => '','cs_contact_animation' => '');
        if(isset($output['0']['atts']))
            $atts = $output['0']['atts'];
        else 
            $atts = array();
        $contactus_element_size = '25';
        foreach($defaults as $key=>$values){
            if(isset($atts[$key]))
                $$key = $atts[$key];
            else 
                $$key =$values;
         }
        $name = 'cs_pb_contactus';
        $coloumn_class = 'column_'.$contactus_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="contactus" data="<?php echo cs_element_size_data_array_index($contactus_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$contactus_element_size, '', 'building-o',$type='');?>
  <div class="cs-wrapp-class-<?php echo intval($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[cs_contactus {{attributes}}]" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php _e('Edit Contact Form Options','dir');?></h5>
      <a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
    <div class="cs-pbwp-content">
      <div class="cs-wrapp-clone cs-shortcode-wrapp">
        <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Section Title','dir');?></label>
          </li>
          <li class="to-field">
            <input  name="cs_contactus_section_title[]" type="text"  value="<?php echo cs_allow_special_char($cs_contactus_section_title);?>"   />
            <p> <?php _e('This is used for the one page navigation, to identify the section below. Give a title','dir');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('View','dir');?></label>
          </li>
          <li class="to-field select-style">
            <select class="cs_contactus_view" id="cs_contactus_view" name="cs_contactus_view[]">
              <option <?php if($cs_contactus_view == "plain")echo "selected";?> value="plain"><?php _e('Plain','dir');?></option>
              <option <?php if($cs_contactus_view == "fancy")echo "selected";?> value="fancy"><?php _e('Fancy','dir');?></option>
            </select>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Label On/Off','dir');?></label>
          </li>
          <li class="to-field select-style">
            <select class="cs_contactus_label" id="cs_contactus_label" name="cs_contactus_label[]">
              <option <?php if($cs_contactus_label == "on")echo "selected";?> value="on"><?php _e('On','dir');?></option>
              <option <?php if($cs_contactus_label == "off")echo "selected";?> value="off"><?php _e('Off','dir');?></option>
            </select>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Send To','dir');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_contactus_send[]" class="txtfield" value="<?php echo esc_attr($cs_contactus_send);?>" />
            <p><?php _e('add a email which you want to receive email','dir');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Success Message','dir');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_success[]" class="txtfield" value="<?php echo esc_attr($cs_success);?>" />
            <p><?php _e('set a message if your email sent successfully','dir');?> </p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Error Message','dir');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_error[]" class="txtfield" value="<?php echo esc_attr($cs_error);?>" />
            <p><?php _e('set a message for error message','dir');?></p>
          </li>
        </ul>
        <!--<input type="hidden" name="cs_form_id[]" class="txtfield" value="<?php echo esc_attr($random_id);?>" />-->
        <?php 
        if ( function_exists( 'cs_shortcode_custom_dynamic_classes' ) ) {
                cs_shortcode_custom_dynamic_classes($cs_contact_class,$cs_contact_animation,'','cs_contact');
            }
        ?>
      </div>
      <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
      <ul class="form-elements insert-bg">
        <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo str_replace('cs_pb_','',$name);?>','<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" ><?php _e('Insert','dir');?></a> </li>
      </ul>
      <div id="results-shortocde"></div>
      <?php } else {?>
      <ul class="form-elements noborder">
        <li class="to-label"></li>
        <li class="to-field">
          <input type="hidden" name="cs_orderby[]" value="contactus" />
          <input type="button" value="Save" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
        </li>
      </ul>
      <?php }?>
    </div>
  </div>
</div>
<?php
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_pb_contactus', 'cs_pb_contactus');
}
// Contact Form html form for page builder end