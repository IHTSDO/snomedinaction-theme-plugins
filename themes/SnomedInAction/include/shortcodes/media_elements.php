<?php

//=====================================================================
// Video & Sound Cloud Shortcode for page builder start
//=====================================================================
function cs_pb_video($die = 0){
    global $cs_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $counter = $_POST['counter'];
        $cs_counter = $_POST['counter'];
        $album_num = 0;
        if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
            $PREFIX = 'cs_video';
            $parseObject = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
            $defaults = array('cs_video_section_title' => '','video_url' => '','video_width' => '500', 'video_height' => '250','cs_video_custom_class'=>'','cs_video_custom_animation'=>'');
            if(isset($output['0']['atts']))
                $atts = $output['0']['atts'];
            else 
                $atts = array();
            if(isset($output['0']['content']))
                $atts_content = $output['0']['content'];
            else 
                $atts_content = array();
            if(is_array($atts_content))
                $album_num = count($atts_content);
            $video_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_video';
            $coloumn_class = 'column_'.$video_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
 ?>

<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="column" data="<?php echo cs_element_size_data_array_index($video_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$video_element_size,'','play-circle');?>
  <div class="cs-wrapp-class-<?php echo intval($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[cs_video {{attributes}}]{{content}}[/cs_video]" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php _e('Edit Video Options','dir');?></h5>
      <a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
    <div class="cs-pbwp-content">
      <div class="cs-wrapp-clone cs-shortcode-wrapp">
        <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
        <ul class="form-elements">
            <li class="to-label"><label><?php _e('Section Title','dir');?></label></li>
            <li class="to-field">
                <input  name="cs_video_section_title[]" type="text"  value="<?php echo cs_allow_special_char($cs_video_section_title);?>"   />
                <p> <?php _e('This is used for the one page navigation, to identify the section below. Give a title','dir');?>  </p>
            </li>                  
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Video Url','dir');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="video_url[]" class="txtfield" value="<?php echo esc_url($video_url)?>" />
            <p><?php _e('give the video Url here','dir');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Width','dir');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="video_width[]" class="txtfield" value="<?php echo esc_attr($video_width);?>" />
            <p><?php _e('Add a width in pix, If you want to override the default','dir');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Height','dir');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="video_height[]" class="txtfield" value="<?php echo esc_attr($video_height)?>" />
            <p><?php _e('Provide height in px, if you want to override the default','dir');?> </p>
          </li>
        </ul>
        <?php 
            if ( function_exists( 'cs_shortcode_custom_classes_test' ) ) {
                cs_shortcode_custom_dynamic_classes($cs_video_custom_class,$cs_video_custom_animation,'','video');
            }
        ?>
      </div>
      <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
      <ul class="form-elements insert-bg">
        <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo esc_js(str_replace('cs_pb_','',$name));?>','<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" ><?php _e('Insert','dir');?></a> </li>
      </ul>
      <div id="results-shortocde"></div>
      <?php } else {?>
      <ul class="form-elements noborder">
        <li class="to-label"></li>
        <li class="to-field">
          <input type="hidden" name="cs_orderby[]" value="video" />
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
add_action('wp_ajax_cs_pb_video', 'cs_pb_video');
// Video & Sound Cloud Shortcode for page builder end 

//=====================================================================
// image frame html for page builder start
//=====================================================================
if ( ! function_exists( 'cs_pb_image' ) ) {
    function cs_pb_image($die = 0){
        global $cs_node,$cs_count_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $PREFIX = 'cs_image';
        $defaultAttributes    = false;
        $parseObject     = new ShortcodeParse();
        $cs_counter = $_POST['counter'];
        if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
            $defaultAttributes    = true;
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
             $defaults = array( 'cs_image_section_title' => '','image_style' => '','cs_image_url' => '','cs_image_title' => '','cs_image_caption' => '','cs_image_custom_class'=>'','cs_image_custom_animation'=>'');
            if(isset($output['0']['atts']))
                $atts = $output['0']['atts'];
            else 
                $atts = array();
            
            if(isset($output['0']['content']))
                $atts_content = $output['0']['content'];
            else 
                $atts_content = "";
            
            $image_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_image';
            $cs_count_node++;
            $coloumn_class = 'column_'.$image_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
        
        $rand_id = rand(34, 443534);
         
     ?>
<div id="<?php echo cs_allow_special_char($name.$cs_counter)?>_del" class="column  parentdelete <?php echo cs_allow_special_char($coloumn_class);?> <?php echo cs_allow_special_char($shortcode_view);?>" item="image" data="<?php echo cs_element_size_data_array_index($image_element_size); ?>" >
  <?php cs_element_setting($name,$cs_counter,$image_element_size,'','picture-o');?>
  <div class="cs-wrapp-class-<?php echo cs_allow_special_char($cs_counter)?> <?php echo cs_allow_special_char($shortcode_element);?>" id="<?php echo cs_allow_special_char($name.$cs_counter)?>" data-shortcode-template="[cs_image {{attributes}}]{{content}}[/cs_image]" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php _e('Edit Image Options','dir');?></h5>
      <a href="javascript:removeoverlay('<?php echo cs_allow_special_char($name.$cs_counter)?>','<?php echo cs_allow_special_char($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
    <div class="cs-pbwp-content">
      <div class="cs-wrapp-clone cs-shortcode-wrapp">
        <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
        <ul class="form-elements">
            <li class="to-label"><label><?php _e('Section Title','dir');?></label></li>
            <li class="to-field">
                <input name="cs_image_section_title[]" type="text"  value="<?php echo cs_allow_special_char($cs_image_section_title)?>"   />
                <p><?php _e('This is used for the one page navigation, to identify the section below. Give a title','dir');?>  </p>
            </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Image Style','dir');?></label>
          </li>
          <li class="to-field select-style">
            <select class="image_style" name="image_style[]">
              <option <?php if($image_style == 'frame-plane'){echo 'selected="selected"';}?> value="frame-plane"><?php _e('Plain','dir');?></option>
              <option <?php if($image_style == 'frame-clean'){echo 'selected="selected"';}?> value="frame-clean"><?php _e('Clean','dir');?></option>
               <option <?php if($image_style == 'frame-classic'){echo 'selected="selected"';}?> value="frame-classic"><?php _e('Classic','dir');?></option>
              <option <?php if($image_style == 'frame-simple'){echo 'selected="selected"';}?> value="frame-simple"><?php _e('Simple','dir');?></option>
            </select>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Image url','dir');?></label>
          </li>
          <li class="to-field">
            <input id="cs_image_url<?php echo esc_attr($rand_id)?>" name="cs_image_url[]" type="hidden" class="" value="<?php echo esc_url($cs_image_url);?>"/>
            <input name="cs_image_url<?php echo esc_attr($rand_id)?>"  type="button" class="uploadMedia left" value="Browse"/>
            <div class="left-info">
            <p><?php _e('Browse the image','dir');?> </p>
            </div>
          </li>
        </ul>
        <ul class="form-elements">
            <li class="image-frame">
                <div class="page-wrap" style="overflow:hidden; display:<?php echo cs_allow_special_char($cs_image_url) && trim($cs_image_url) !='' ? 'inline' : 'none';?>" id="cs_image_url<?php echo cs_allow_special_char($rand_id)?>_box" >
                  <div class="gal-active">
                    <div class="dragareamain" style="padding-bottom:0px;">
                      <ul id="gal-sortable">
                        <li class="ui-state-default" id="">
                          <div class="thumb-secs"> <img src="<?php echo cs_allow_special_char($cs_image_url);?>"  id="cs_image_url<?php echo cs_allow_special_char($rand_id);?>_img" width="100" height="150"  />
                            <div class="gal-edit-opts"> <a   href="javascript:del_media('cs_image_url<?php echo cs_allow_special_char($rand_id);?>')" class="delete"></a> </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
            </li>
        </ul>
        
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Title','dir');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_image_title[]" class="txtfield" value="<?php echo cs_allow_special_char($cs_image_title)?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Caption','dir');?></label>
          </li>
          <li class="to-field">
            <textarea name="cs_image_caption[]" rows="10" class="textarea" data-content-text="cs-shortcode-textarea"><?php echo esc_textarea($atts_content); ?></textarea>
            <p><?php _e('If you would like a caption to be shown below the image, add it here.','dir');?></p>
          </li>
        </ul>
        <?php 
            if ( function_exists( 'cs_shortcode_custom_dynamic_classes' ) ) {
                cs_shortcode_custom_dynamic_classes($cs_image_custom_class,$cs_image_custom_animation,'','image');
            }
        ?>
      </div>
      <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
      <ul class="form-elements insert-bg">
        <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo cs_allow_special_char(str_replace('cs_pb_','',$name));?>','<?php echo cs_allow_special_char($name.$cs_counter)?>','<?php echo cs_allow_special_char($filter_element);?>')" ><?php _e('Insert','dir');?></a> </li>
      </ul>
      <div id="results-shortocde"></div>
      <?php } else {?>
      <ul class="form-elements noborder">
        <li class="to-label"></li>
        <li class="to-field">
          <input type="hidden" name="cs_orderby[]" value="image" />
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
    add_action('wp_ajax_cs_pb_image', 'cs_pb_image');
}
// image frame html  for page builder end

//=====================================================================
// Promobox Shortcode Builder start
//=====================================================================
if ( ! function_exists( 'cs_pb_promobox' ) ) {
    function cs_pb_promobox($die = 0){
        global $cs_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $counter = $_POST['counter'];
        $cs_counter = $_POST['counter'];
        $album_num = 0;
        if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
            $PREFIX = 'cs_promobox';
            $parseObject     = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
            $defaults = array( 'cs_promobox_section_title'=>'', 'cs_promo_image_url'=>'', 'cs_promobox_title'=>'', 'cs_promobox_contents'=>'', 'cs_promobox_btn_bg_color'=>'','cs_promobox_title_color'=>'', 'cs_promobox_content_color'=>'' ,'cs_link_title'=>'Read More','text_align'=>'', 'cs_link'=>'#', 'cs_promobox_class' => '', 'cs_promobox_animation' => '', 'bg_repeat'=>'','text_align'=>'', 'target'=>'_self');
            if(isset($output['0']['atts']))
                $atts = $output['0']['atts'];
            else 
                $atts = array();
            if(isset($output['0']['content']))
                $atts_content = $output['0']['content'];
            else 
                $atts_content = array();
            if(is_array($atts_content))
                    $album_num = count($atts_content);
            $promobox_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_promobox';
            $coloumn_class = 'column_'.$promobox_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
    ?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="promobox" data="<?php echo cs_element_size_data_array_index($promobox_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$promobox_element_size,'','life-ring');?>
  <div class="cs-wrapp-class-<?php echo intval($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[cs_promobox {{attributes}}]{{content}}[/cs_promobox]" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php _e('Edit Promo Box Options','dir');?></h5>
      <a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
    <div class="cs-pbwp-content">
      <div class="cs-wrapp-clone cs-shortcode-wrapp">
        <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
        <ul class="form-elements">
            <li class="to-label"><label><?php _e('Section Title','dir');?></label></li>
            <li class="to-field">
                <input name="cs_promobox_section_title[]" type="text"  value="<?php echo cs_allow_special_char($cs_promobox_section_title)?>" />
                <p><?php _e('This is used for the one page navigation, to identify the section below. Give a title','dir');?></p>
            </li>                  
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Background Image','dir');?></label>
          </li>
          <li class="to-field">
            <input id="cs_promo_image_url<?php echo esc_attr($cs_counter)?>" name="cs_promo_image_url[]" type="hidden" class="" value="<?php echo esc_url($cs_promo_image_url);?>" />
            <input name="cs_promo_image_url<?php echo esc_attr($cs_counter)?>" type="button" class="uploadMedia left" value="Browse"/>
            <div class="left-info">
            
            <p><?php _e('Promo Box Background image here','dir');?></p>
            </div>
          </li>
        </ul>
        <div class="page-wrap" style="overflow:hidden; display:<?php echo esc_url($cs_promo_image_url) !='' ? 'inline' : 'none';?>" id="cs_promo_image_url<?php echo intval($cs_counter)?>_box" >
          <div class="gal-active">
            <div class="dragareamain" style="padding-bottom:0px;">
              <ul id="gal-sortable">
                <li class="ui-state-default" id="">
                  <div class="thumb-secs"> <img src="<?php echo esc_url($cs_promo_image_url);?>"  id="cs_promo_image_url<?php echo intval($cs_counter)?>_img" width="100" height="150"  />
                    <div class="gal-edit-opts"> <a   href="javascript:del_media('cs_promo_image_url<?php echo intval($cs_counter)?>')" class="delete"></a> </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Background Repeat','dir');?></label>
          </li>
          <li class="to-field select-style">
            <select class="bg_repeat" name="bg_repeat[]">
              <option <?php if($bg_repeat == 'yes'){echo 'selected="selected"';}?> value="yes"><?php _e('Yes','dir');?></option>
              <option <?php if($bg_repeat == 'no'){echo 'selected="selected"';}?> value="no"><?php _e('No','dir');?></option>
            </select>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Title','dir');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_promobox_title[]" class="txtfield" value="<?php echo cs_allow_special_char($cs_promobox_title)?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Title Color','dir');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_promobox_title_color[]" class="bg_color" value="<?php echo esc_attr($cs_promobox_title_color)?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Contents','dir');?></label>
          </li>
          <li class="to-field">
            <textarea  name="cs_promobox_contents[]" rows="10" class="textarea" data-content-text="cs-shortcode-textarea"><?php echo esc_textarea($cs_promobox_contents);?></textarea>
            <p><?php _e('Enter content here','dir');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Content Color','dir');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_promobox_content_color[]" class="bg_color" value="<?php echo esc_attr($cs_promobox_content_color)?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Text Align','dir');?></label>
          </li>
          <li class="to-field select-style">
            <select class="text_align" name="text_align[]">
              <option <?php if($text_align == 'left'){echo 'selected="selected"';}?> value="left"><?php _e('Left','dir');?></option>
              <option <?php if($text_align == 'right'){echo 'selected="selected"';}?> value="right"><?php _e('Right','dir');?></option>
            </select>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Link Title','dir');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_link_title[]" class="txtfield" value="<?php echo cs_allow_special_char($cs_link_title);;?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Link url','dir');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_link[]" class="txtfield" value="<?php echo esc_url($cs_link);?>" />
            <p><?php _e('Give External/Internal Promo Box url','dir');?></p>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Button Background Color','dir');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_promobox_btn_bg_color[]" class="bg_color" value="<?php echo esc_attr($cs_promobox_btn_bg_color)?>" />
          </li>
        </ul>
        <?php 
            if ( function_exists( 'cs_shortcode_custom_dynamic_classes' ) ) {
                cs_shortcode_custom_dynamic_classes($cs_promobox_class,$cs_promobox_animation,'','cs_promobox');
            }
        ?>
      </div>
      <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
      <ul class="form-elements insert-bg">
        <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo esc_js(str_replace('cs_pb_','',$name));?>','<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" ><?php _e('Insert','dir');?></a> </li>
      </ul>
      <div id="results-shortocde"></div>
      <?php } else {?>
      <ul class="form-elements noborder">
        <li class="to-label"></li>
        <li class="to-field">
          <input type="hidden" name="cs_orderby[]" value="promobox" />
          <input type="button" value="Save" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))"/>
        </li>
      </ul>
      <?php }?>
    </div>
  </div>
</div>
<?php
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_pb_promobox', 'cs_pb_promobox');
}

//=====================================================================
// Slider Shortcode Builder start
//=====================================================================
if ( ! function_exists( 'cs_pb_slider' ) ) {
    function cs_pb_slider($die = 0){
        global $cs_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $counter = $_POST['counter'];
        $cs_counter = $_POST['counter'];
        $image_num = 0;
        if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
            $PREFIX = 'cs_slider';
            $parseObject = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
            global $cs_node, $cs_counter_node;
            $defaults = array('column_size' => '1/1','cs_slider_header_title'=>'', 'cs_slider'=>'', 'cs_slider_id'=>'');
            if(isset($output['0']['atts']))
                $atts = $output['0']['atts'];
            else 
                $atts = array();
            if(isset($output['0']['content']))
                $atts_content = $output['0']['content'];
            else 
                $atts_content = array();
            
            if(is_array($atts_content))
                $slider_num = count($atts_content);
            
            $slider_element_size = '25';
            
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_slider';
            $coloumn_class = 'column_'.$slider_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
    ?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="slider" data="<?php echo cs_element_size_data_array_index($slider_element_size)?>">
  <?php cs_element_setting($name,$cs_counter,$slider_element_size,'','picture-o');?>
  <div class="cs-wrapp-class-<?php echo intval($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[cs_slider {{attributes}}]" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php _e('Edit Slider Options','dir');?></h5>
      <a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
    <div class="cs-pbwp-content">
      <div class="cs-wrapp-clone cs-shortcode-wrapp">
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Slider Section Title','dir');?></label>
          </li>
          <li class="to-field">
            <div class="input-sec">
              <input type="text" name="cs_slider_header_title[]" class="txtfield" value="<?php echo cs_allow_special_char(htmlspecialchars($cs_slider_header_title));?>" />
            </div>
            <div class="left-info">
              <p><?php _e('Please enter slider header title.','dir');?></p>
            </div>
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Chose Slider','dir');?></label>
          </li>
          <li class="to-field">
            <div class="input-sec">
              <div class="select-style">
                <select name="cs_slider_id[]" id="cs_slider_id<?php echo intval($cs_counter)?>" class="dropdown">
                    <?php
                        if(class_exists('RevSlider') && class_exists('cs_RevSlider')) {
                            $slider = new cs_RevSlider();
                            $arrSliders = $slider->getAllSliderAliases();
                            foreach ( $arrSliders as $key => $entry ) {
                                ?>
                                <option <?php cs_selected($cs_rev_slider_id,$entry['alias']) ?> value="<?php echo cs_allow_special_char($entry['alias']);?>"><?php echo cs_allow_special_char($entry['title']) ;?></option>
                                <?php
                            }
                        }
                    ?>
                </select>
              </div>
            </div>
          </li>
        </ul>
        
      </div>
      <script>
            var cs_slider_type    = jQuery( "#cs_slider_type<?php echo esc_js($cs_counter);?>" ).val();
            cs_toggle_height(cs_slider_type,'<?php echo esc_js($cs_counter)?>');
        </script>
      <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
      <ul class="form-elements insert-bg">
        <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo esc_js(str_replace('cs_pb_','',$name));?>','<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')" ><?php _e('Insert','dir');?></a> </li>
      </ul>
      <div id="results-shortocde"></div>
      <?php } else {?>
      <ul class="form-elements noborder">
        <li class="to-label"></li>
        <li class="to-field">
          <input type="hidden" name="cs_orderby[]" value="slider" />
          <input type="button" value="Save" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))"/>
        </li>
      </ul>
      <?php }?>
    </div>
  </div>
</div>
<?php
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_pb_slider', 'cs_pb_slider');
}

?>