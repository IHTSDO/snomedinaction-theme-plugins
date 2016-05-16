<?php
/**
 * File Type: Common Elements Shortcode Functions
 */

//======================================================================
// Adding pricetable
//======================================================================
if (!function_exists('cs_pricetable_shortcode')) {
    function cs_pricetable_shortcode($atts, $content = "") {
        global $pricetable_style;
        $defaults = array('column_size'=>'1/1','pricetable_style'=>'','pricetable_title'=>'','pricetable_title_bgcolor'=>'','pricetable_price'=>'','pricetable_img'=>'','pricetable_period'=>'','pricetable_bgcolor'=>'','btn_text'=>'','btn_link'=>'','btn_bg_color'=>'','pricetable_featured'=>'','pricetable_class'=>'','pricetable_animation'=>'');
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
        
        $CustomId    = '';
        if ( isset( $pricetable_class ) && $pricetable_class ) {
            $CustomId    = 'id="'.$pricetable_class.'"';
        }
        if ( trim($pricetable_animation) !='' ) {
            $pricetable_animation    = 'wow'.' '.$pricetable_animation;
        } else {
            $pricetable_animation    = '';
        }
        $pricetableViewClass = '';
        
        if(isset($pricetable_style) && $pricetable_style == 'classic'){
            $pricetableViewClass = 'pr-classic';
            $title_color = '#ffffff';
        } else if(isset($pricetable_style) && $pricetable_style == 'simple'){
            $pricetableViewClass = 'pr-simple';
            $title_color = '#505050';
        } else if(isset($pricetable_style) && $pricetable_style == 'modren'){
            $pricetableViewClass = 'pr-modren';
            $title_color = '#ffffff';
        } else {
            
        }
        $html = '';
        $bgcolor_style = '';
        if(isset($btn_bg_color) && trim($btn_bg_color) <> ''){
            $btn_bg_color = ' style="background-color:'.$btn_bg_color.'"';
        }
        if(isset($pricetable_bgcolor) && trim($pricetable_bgcolor) <> ''){
            $bgcolor_style = ' style="background-color:'.$pricetable_bgcolor.'"';
        }
        if(isset($pricetable_featured) && $pricetable_featured == 'Yes'){
            $featured = 'featured';
        } else {
            $featured = '';
        }
        $html .= '<article class="cs-price-table '.$pricetableViewClass.' '.$pricetable_animation.' '.$pricetable_class.' '.$featured.'">';
        if(isset($pricetable_title) && $pricetable_title !=''){
            $html .= '<h3 style="color:'.$title_color.' !important; background-color:'.$pricetable_title_bgcolor.' !important;">'.$pricetable_title .'</h3>';
        }
        $btn_text = $btn_text ? $btn_text : 'Sign Up';
        $html .= '<div class="cs-price" '.$bgcolor_style.'><div class="inner-sec">';
        
        if(isset($pricetable_img) && $pricetable_img !=''){
            $html .= '<figure><img src="'.$pricetable_img.'"></figure>';
        }
        if(isset($pricetable_price) && $pricetable_price !=''){
            $html .= $pricetable_price;
        }
        if(isset($pricetable_period) && $pricetable_period !=''){
            $html .= '<small>'.$pricetable_period.'</small>';
        }
        $html .= '</div></div>';
        $html .= '<ul class="features">';
        $html .= do_shortcode($content);
        $html .= '</ul>';
        $html .= ' <a class="sigun_up" href="'.$btn_link.'" '.$btn_bg_color.'>'.$btn_text.'</a>';
        $html .= '</article>';
        return '<div '.$CustomId.' class="'.$column_class.'">'.$html.'</div>';
    }
    add_shortcode('cs_pricetable', 'cs_pricetable_shortcode');
}
//======================================================================
// Pricing Item
//======================================================================
if (!function_exists('cs_pricing_item')) {
    function cs_pricing_item($atts, $content = "") {
        global $pricetable_style;
        $defaults = array('pricing_feature' => '');
        extract( shortcode_atts( $defaults, $atts ) );
        $html     = '';
        $priceCheck    = '';
        if ( $pricetable_style =='classic' || $pricetable_style =='clean' ) {
            $priceCheck    = '<i class="icon-check"></i>';
        }
        
        if ( isset( $pricing_feature ) && $pricing_feature !='' ){
            $html    .= '<li>'.$priceCheck.$pricing_feature.'</li>';
        }
        
        return $html;
    }
    add_shortcode('pricing_item', 'cs_pricing_item');
}

//======================================================================
// Adding mailchimp
//======================================================================

if (!function_exists('cs_mailchimp_shortcode')) {
    function cs_mailchimp_shortcode($atts, $content = "") {
        global $mailchimp_style;
        $defaults = array('column_size'=>'1/1','cs_mailchimp_title'=>'','cs_mailchimp_subtitle' => '','cs_mailchimp_bg_color' => '','cs_mailchimp_txt_color' => '');
        extract( shortcode_atts( $defaults, $atts ) );
		
		$column_class = cs_custom_column_class($column_size);
		 
		$section_title = '';
        if(isset($cs_mailchimp_title) && trim($cs_mailchimp_title) <> ''){
            $section_title = '<div class="cs-section-title"><h2>'.$cs_mailchimp_title.'</h2></div>';
        }
		
		$cs_mailchimp_bg_color = $cs_mailchimp_bg_color <> '' ? ' style="background-color:'.$cs_mailchimp_bg_color.';"' : '';
		$cs_mailchimp_txt_color = $cs_mailchimp_txt_color <> '' ? ' style="color:'.$cs_mailchimp_txt_color.';"' : '';
		
		if(isset($cs_mailchimp_subtitle) && trim($cs_mailchimp_subtitle) <> ''){
            $cs_subtitle = '<h3'.$cs_mailchimp_txt_color.'>'.$cs_mailchimp_subtitle.'</h3>';
        }
		ob_start();
		?>
        <div class="<?php echo sanitize_html_class($column_class); ?>">
            <div class="cs-mailchimp"<?php echo cs_allow_special_char($cs_mailchimp_bg_color); ?>>
                <div class="cs-mailchimp-inner">
                    <?php 
                    echo cs_allow_special_char($section_title);
                    echo cs_allow_special_char($cs_subtitle);
                    echo '<p'.$cs_mailchimp_txt_color.'>'.do_shortcode($content).'</p>'; 
                    
                    cs_custom_mailchimp();
                    ?>
                </div>
            </div>
        </div>
        <?php
		$html = ob_get_clean();
        return $html;
    }
    add_shortcode('cs_mailchimp', 'cs_mailchimp_shortcode');
}

//======================================================================
//Table Start
//======================================================================
if (!function_exists('cs_table_shortcode_func')) {
    function cs_table_shortcode_func($atts, $content = "") {
        global $table_style;
        $defaults = array('table_style'=>'modern','cs_table_section_title'=>'','column_size'=>'1/1','cs_table_class'=>'','cs_table_animation'=>'','cs_table_custom_animation_duration'=>'1');
        extract( shortcode_atts( $defaults, $atts ) );
        
        $CustomId    = '';
        if ( isset( $cs_table_class ) && $cs_table_class ) {
            $CustomId    = 'id="'.$cs_table_class.'"';
        }
        
        $column_class  = cs_custom_column_class($column_size);
        
        if ( trim($cs_table_animation) !='' ) {
            $cs_table_animation    = 'wow'.' '.$cs_table_animation;
        } else {
            $cs_table_animation    = '';
        }

        $section_title = '';
        
        if(isset($cs_table_section_title) && trim($cs_table_section_title) <> ''){
            $section_title = '<div class="cs-section-title"><h2>'.$cs_table_section_title.'</h2></div>';
        }
        return '<div '.$CustomId.' class="'. sanitize_html_class($column_class).' '. sanitize_html_class($cs_table_class).' '. sanitize_html_class($cs_table_animation).'">'.$section_title.do_shortcode($content).'</div>';
    }
    add_shortcode('cs_table', 'cs_table_shortcode_func');
}

//======================================================================
// Adding table
//======================================================================

if (!function_exists('cs_table_shortcode')) {
    function cs_table_shortcode($atts, $content = "") {
        global $table_style;
        $defaults = array('column_size'=>'1/1','cs_table_section_title'=>'','cs_table_content'=>'','cs_table_class'=>'','cs_table_animation'=>'');
        extract( shortcode_atts( $defaults, $atts ) );
        $content = str_replace('<br />','',$content);
        $table_data = '';
        $class = '';
        if($table_style == 'classic'){
            $cs_class = 'tablev2';
        }else if( $table_style == 'modren' ) {
            $cs_class = 'tablev1';
        }
        /*if(isset($color) && $color <> ''){
            $table_class = "table_" . rand(55,6555);
        }*/
        return $table_data . '<table class="table '.sanitize_html_class($class).'">'.do_shortcode($content).'</table>';
    }
    add_shortcode('table', 'cs_table_shortcode');
}

//======================================================================
// Table Head
//======================================================================
if (!function_exists('cs_table_body_shortcode')) {
    function cs_table_body_shortcode($atts, $content = "") {
        $defaults = array();
        extract( shortcode_atts( $defaults, $atts ) );
        return '<tbody>'.do_shortcode($content).'</tbody>';
    }
    add_shortcode('tbody', 'cs_table_body_shortcode');
}

//======================================================================
// Table Head
//======================================================================
if (!function_exists('cs_table_head_shortcode')) {
    function cs_table_head_shortcode($atts, $content = "") {
        $defaults = array();
        extract( shortcode_atts( $defaults, $atts ) );
        return '<thead>'.do_shortcode($content).'</thead>';
    }
    add_shortcode('thead', 'cs_table_head_shortcode');
}

//======================================================================
// Table Row
//======================================================================
if (!function_exists('cs_table_row_shortcode')) {
    function cs_table_row_shortcode($atts, $content = "") {
        $defaults = array();
        extract( shortcode_atts( $defaults, $atts ) );
        return '<tr>'.do_shortcode($content).'</tr>';
    
    }
    add_shortcode('tr', 'cs_table_row_shortcode');
}

//======================================================================
// Table Heading
//======================================================================
if (!function_exists('cs_table_heading_shortcode')) {
    function cs_table_heading_shortcode($atts, $content = "") {
        $defaults = array();
        extract( shortcode_atts( $defaults, $atts ) );
        $html     = '';
        $html    .= '<th>';
        $html    .= do_shortcode($content);
        $html    .= '</th>';
        
        return $html;
    }
    add_shortcode('th', 'cs_table_heading_shortcode');
}

//======================================================================
// Table data
//======================================================================
if (!function_exists('cs_table_data_shortcode')) {
    function cs_table_data_shortcode($atts, $content = "") {
        $defaults = array();
        extract( shortcode_atts( $defaults, $atts ) );
        return '<td>'.do_shortcode($content).'</td>';
    }
    add_shortcode('td', 'cs_table_data_shortcode');
}

//======================================================================
// adding accordion
//======================================================================
if (!function_exists('cs_accordion_shortcode')) {
    function cs_accordion_shortcode($atts, $content = "") {
        global $acc_counter,$accordian_style;
        $acc_counter = rand(40, 9999999);;
        $html    = '';
        $defaults = array('column_size'=>'1/1', 'class' => 'cs-accrodian','accordian_style' => '','accordion_class' => '','accordion_animation' => '','cs_accordian_section_title'=>'');
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
        
        $CustomId    = '';
        if ( isset( $accordion_class ) && $accordion_class ) {
            $CustomId    = 'id="'.$accordion_class.'"';
        }
        
        if ( trim($accordion_animation) !='' ) {
            $accordion_animation    = 'wow'.' '.$accordion_animation;
        } else {
            $accordion_animation    = '';
        }
        $section_title = '';
        if(isset($cs_accordian_section_title) && trim($cs_accordian_section_title) <> ''){
            $section_title = '<div class="cs-section-title"><h2>'.$cs_accordian_section_title.'</h2></div>';
        }
        if ( $accordian_style == 'default' ) {
            $styleClass    = 'default';
        }else{
            $styleClass    = 'default';
        }
        $html   .= '<div '.$CustomId.' class="'.$column_class.'">';
        $html    .= '<div class="panel-group simple-veiw '.$styleClass.' '.$accordion_class.' '.$accordion_animation.'" id="accordion-' . $acc_counter . '">'.$section_title.do_shortcode($content).'</div>';
        $html    .= '</div>';
        return $html;
    }
    
    add_shortcode('cs_accordian', 'cs_accordion_shortcode');
}

//======================================================================
// Adding accordion item start
//======================================================================
if (!function_exists('cs_accordion_item_shortcode')) {
    function cs_accordion_item_shortcode($atts, $content = "") {
        global $acc_counter,$accordian_style,$accordion_animation;
        $defaults = array( 'accordion_title' => 'Title','accordion_active' => 'yes','cs_accordian_icon' => '');
        extract( shortcode_atts( $defaults, $atts ) );
        $accordion_count = 0;
        $accordion_count = rand(40, 9999999);
        $html = "";
        $active_in = '';
        $active_class = '';
        $styleColapse = 'collapse collapsed';
        
        if(isset($accordion_active) && $accordion_active == 'yes'){
            $active_in = 'in';
            $styleColapse = '';
        }
        else{
            $active_class = 'collapsed';
        }
        $faq_style = '';
        
        $cs_accordian_icon_class = '';
        if(isset($cs_accordian_icon)){
            $cs_accordian_icon = '<i class="'.$cs_accordian_icon.'"></i>';
        }
        $html = '<div class="panel panel-default">
                    <div class="panel-heading">
                      <a data-toggle="collapse" data-parent="#accordion-'.$acc_counter.'" href="#accordion-'.$accordion_count.'" class="'.sanitize_html_class($active_class).'">
                         ' . $cs_accordian_icon . $accordion_title . '
                      </a>
                    </div>
                    <div id="accordion-'.$accordion_count.'" class="panel-collapse collapse '.$active_in.' ">
                      <div class="panel-body"><p>'.$content.'</p></div>
                    </div>
                  </div>';
        return $html;
    }
    add_shortcode('accordian_item', 'cs_accordion_item_shortcode');
}

//======================================================================
// Tabs Shortcodes 
//======================================================================
if (!function_exists('cs_tabs_shortcode')) {
    function cs_tabs_shortcode( $atts, $content = null ) {
        global $tabs_content;
        $tabs_content = '';
        extract(shortcode_atts(array(  
            'cs_tab_style' => '',
            'cs_tabs_class' => '',
            'column_size'=>'1/1', 
            'cs_tabs_section_title' => '',
            'cs_tabs_animation' => '',
            'cs_custom_animation_duration' => ''
        ), $atts));  
        $column_class  = cs_custom_column_class($column_size);
        
        $CustomId    = '';
        if ( isset( $cs_tabs_class ) && $cs_tabs_class ) {
            $CustomId    = 'id="'.$cs_tabs_class.'"';
        }
        
        if ( trim($cs_tabs_animation) !='' ) {
            $cs_tabs_animation    = 'wow'.' '.$cs_tabs_animation;
        } else {
            $cs_tabs_animation    = '';
        }
        $randid = rand(8,9999);
        $section_title = '';
        $tabs_output = '';
        
        if ( isset($cs_tabs_section_title) && trim($cs_tabs_section_title) !='' ) {
            $section_title    = '<div class="cs-section-title"><h2>'.$cs_tabs_section_title.'</h2></div>';
        }
        
        if($cs_tab_style == 'vertical'){
            $tabs_class = 'vertical';
        }else if($cs_tab_style == 'borderless'){
            $tabs_class = 'borderless';
        }else{
            $tabs_class = 'nav-position-top';
        }
        
        $tabs_output .= '<div class="cs-tabs '.sanitize_html_class($tabs_class).' '.$cs_tabs_animation.'"  id="cstabs'.absint($randid).'">';
        $tabs_output .= $section_title;
        $tabs_output .= '<ul class="nav nav-tabs '.sanitize_html_class($cs_tabs_class).'" > ';
        $tabs_output .= do_shortcode($content);
        $tabs_output .= '</ul>';
        $tabs_output .= '<div class="tab-content">'.$tabs_content.'</div>';
        $tabs_output .= '</div>';
        return '<div '.$CustomId.' class="'.$column_class.' '.sanitize_html_class($cs_tabs_class).'">'.$tabs_output.'</div>';  
    }  
    add_shortcode('cs_tabs', 'cs_tabs_shortcode');
}

//======================================================================
// Tab Items 
//======================================================================
if (!function_exists('cs_tab_item_shortcode')) {
    function cs_tab_item_shortcode($atts, $content = null) {  
        global $tabs_content;
        extract(shortcode_atts(array(  
            'cs_tab_icon' => '',
            'tab_title' => '',
            'cs_tab_icon' => '',
            'tab_active'=>'no' 
        ), $atts));  
        $activeClass = $tab_active == 'yes' ? 'active in' :'';
        $fa_icon='';
        if($cs_tab_icon){
            $fa_icon = '<i class="'.sanitize_html_class($cs_tab_icon).'"></i> ';
        }
        $randid = rand(877,9999);
        $output = ' <li class="'.$activeClass.'"> <a href="#cs-tab-'.sanitize_title($tab_title).$randid.'"  data-toggle="tab">'.$fa_icon.$tab_title.'</a></li>';
        $tabs_content.= '<div class="tab-pane fade '.$activeClass.'" id="cs-tab-'.sanitize_title($tab_title).$randid.'">'.do_shortcode($content).'</div>';
        return $output;
    }
    add_shortcode( 'tab_item', 'cs_tab_item_shortcode' );
}

//======================================================================
// Toggle Start
//======================================================================
if (!function_exists('cs_toggle_shortcode')) {
    function cs_toggle_shortcode($atts, $content = "") {
        $defaults = array( 'column_size'=>'1/1','cs_toggle_section_title' => '','cs_toggle_title' => '','cs_toggle_state' => '','cs_toggle_icon' => '','cs_toggle_custom_class' => '','cs_toggle_custom_animation' => '','cs_toggle_custom_animation_duration' => '1');
        extract( shortcode_atts( $defaults, $atts ) );
        $toggle_counter = rand(1,99999);
        $active = "";
        $collapse = "collapsed";
        $cs_toggle_icon_class = '';
        $column_class  = cs_custom_column_class($column_size);
        
        $CustomId    = '';
        if ( isset( $cs_toggle_custom_class ) && $cs_toggle_custom_class ) {
            $CustomId    = 'id="'.$cs_toggle_custom_class.'"';
        }
        
        if ( trim($cs_toggle_custom_animation) !='' ) {
            $cs_toggle_custom_animation    = 'wow'.' '.$cs_toggle_custom_animation;
        } else {
            $cs_toggle_custom_animation    = '';
        }
        
        if ( $cs_toggle_state == "open" ){ $active = "in";}
        if ( $cs_toggle_icon <> "" ){ $cs_toggle_icon_class = '<i class="'.$cs_toggle_icon.'"></i>';}
        $section_title = '';
        if(isset($cs_toggle_section_title) && trim($cs_toggle_section_title) <> ''){
            $section_title = '<div class="cs-section-title"><h2>'.$cs_toggle_section_title.'</h2></div>';
        }
        $html = '<div class="panel-group" id="#accordion' . $toggle_counter . '">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <a data-toggle="collapse" data-parent="#accordion' . $toggle_counter . '" href="#toggle' . $toggle_counter . '">
                        '.$cs_toggle_icon_class.$cs_toggle_title.'
                      </a>
                    </div>
                    <div id="toggle' . $toggle_counter . '" class="panel-collapse collapse '.$active.'">
                      <div class="panel-body">
                       <p>' . do_shortcode($content) . '</p>
                      </div>
                    </div>
                  </div>
				 </div>';
        
        return '<div '.$CustomId.' class="'.$column_class.'"><div class="'.$cs_toggle_custom_class.' '.$cs_toggle_custom_animation.'" style="animation-duration: '.$cs_toggle_custom_animation_duration.'s;">'.do_shortcode($html) . '</div></div>';
    }
    add_shortcode('cs_toggle', 'cs_toggle_shortcode');
}

//======================================================================
// button shortcode start
//======================================================================
if (!function_exists('cs_button_shortcode')) {
    function cs_button_shortcode($atts) {
        $defaults = array( 'button_size'=>'btn-lg','button_border' => '','border_button_color' => '','button_title' => '','button_link' => '#','button_color' => '#fff','button_bg_color' => '#000','button_icon_position' => 'left','button_icon'=>'', 'button_type' => 'rounded','button_target' => '_self','cs_button_class' => '','cs_button_animation' => '');
        extract( shortcode_atts( $defaults, $atts ) );
        $CustomId    = '';
        if ( isset( $cs_button_class ) && $cs_button_class ) {
            $CustomId    = 'id="'.$cs_button_class.'"';
        }
        
        $button_type_class = 'no_circle';
         if ( trim($cs_button_animation) !='' ) {
            $cs_button_animation    = 'wow'.' '.$cs_button_animation;
         } else {
            $cs_button_animation    = '';
         }
        $border    = '';
        $has_icon = '';    
        
        if($button_size =='btn-xlg'){
            $button_size = 'extra-large-btn';
        }elseif($button_size == 'btn-lg'){
            $button_size = 'custom-btn btn-lg';
        }elseif($button_size == 'medium-btn'){
            $button_size = 'custom-btn btn-sm';
        }else{
            $button_size = 'custom-btn btn-sml';
        }
        
        if( isset($button_border) && $button_border == 'yes' ){
            $border = ' border: 2px solid '.$border_button_color.';';    
        }
        
        if(isset($button_type) && $button_type == 'rounded'){
            $button_type_class = 'circle';
        }
        if(isset($button_type) && $button_type == 'three-d'){
            $button_type_class = 'has-shadow';
            $border    = '';
        }

        if(isset($button_icon) && $button_icon <> ''){
            $has_icon = 'has_icon';    
        }
        
        $html  = '';
        $html .= '<div '.$CustomId.' class="button_style">';
        
        $html .= '<a href="' . $button_link. '" class="default '.sanitize_html_class($button_type_class). ' ' .$button_size. ' bg-color ' . $cs_button_class. ' ' . $cs_button_animation. ' '.$has_icon.'" style="'.$border.'  background-color: ' . $button_bg_color . '; color:' . $button_color . ';" target="'.$button_target.'">';
        if(isset($button_icon) && $button_icon <> ''){
            $html .= '<i class="'.$button_icon.' button-icon-'. $button_icon_position.'"></i>';
        }
        if(isset($button_title) && $button_title <> ''){
            $html .= $button_title;
        }
        $html .= '</a>';
        $html .= '</div>';
        return do_shortcode($html);
    }
    add_shortcode('cs_button', 'cs_button_shortcode');
}

//======================================================================
// Number Counter Item Shortcode Start
//======================================================================
if (!function_exists('cs_counter_item_shortcode')) {
    function cs_counter_item_shortcode($atts, $content = null) {
        global $counter_style;
        extract(shortcode_atts(array(  
            'column_size' => '1/1',
            'counter_style' => '',
            'counter_icon_type' => '',
            'cs_counter_logo' => '',
            'counter_icon'=>'',
            'counter_icon_align'=>'',
            'counter_icon_size'=>'',
            'counter_icon_color' => '#21cdec',
            'counter_numbers' => '',
            'counter_number_color' => '#333333',
            'counter_title' => '',
            'counter_link_title' => '',
            'counter_link_url' => '',
            'counter_text_color' => '#818181',
            'counter_border' => '',
            'counter_class' => '',
            'counter_animation' => '',
            'cs_custom_animation_duration' => '1'
         ), $atts));
         
         $column_class  = cs_custom_column_class($column_size);
         
         $CustomId    = '';
         if ( isset( $counter_class ) && $counter_class ) {
            $CustomId    = 'id="'.$counter_class.'"';
         }
         
         if ( trim($counter_animation) !='' ) {
            $counter_animation    = 'wow'.' '.$counter_animation;
         } else {
            $counter_animation    = '';
         }
            $rand_id = rand(98,56666);
            $output = '';
            $counter_style_class = '';
            $pattren_bg          = '';
            $has_border     = '';
            $output = '';
            $border_class =  '';
            
            cs_count_numbers_script();
            
            $output .= '
                <script>
                    jQuery(document).ready(function($){
                        jQuery(".custom-counter-'.esc_js($rand_id).'").counterUp({
                            delay: 10,
                            time: 1000
                        });
                    });    
                </script>';
            
            $combine_counter_icon = '';    
            
            $counter_numbers = is_numeric($counter_numbers) ? number_format($counter_numbers) : $counter_numbers;
            
            if($counter_style == 'icon-border'){
                
                if( $counter_border == 'on'){
                    $border_class = ' has_border';
                }
                
                if($counter_icon <> ''){
                    $combine_counter_icon = '<div class="counter-seprater'.$border_class.'"><i class="'.$counter_icon.' '.$counter_icon_size.'" style=" color: '.$counter_icon_color.';"></i></div>';
                }
                
                $counter_style_class = 'cs_counter classic top-center';
                
                $output .= '<figure>
                              <figcaption>';
                                if($counter_numbers <> ''){
                                $output .= '<a class="cs-numcount custom-counter-'.$rand_id.'" style=" color: '.$counter_number_color.';">'.$counter_numbers.'</a>';
                                }
                                if($counter_title <> ''){
                                $output .= '<span style="color:'.$counter_text_color.';">'.$counter_title.'</span>';
                                }
                                $output .= $combine_counter_icon;
                                $output .= '<p style="color:'.$counter_text_color.';">'.do_shortcode($content).'</p>';
                                if($counter_link_url <> '' || $counter_link_title <> ''){
                                $counter_link_title = $counter_link_title ? $counter_link_title : __('Read More', 'dir');
                                $output .= '<a class="readmore cs-bg-color" href="'.$counter_link_url.'">'.$counter_link_title.'</a>';
                                }
                              $output .= '
                              </figcaption>
                            </figure>';
            } 
            else{
                if($counter_icon_type == 'icon' && $counter_icon <> ''){
                    $combine_counter_icon = '<i class="'.$counter_icon.' '.$counter_icon_size.'" style=" color: '.$counter_icon_color.'; "></i>';
                }
                else if($counter_icon_type == 'image' && $cs_counter_logo <> ''){
                    $combine_counter_icon = '<img src="'.$cs_counter_logo.'" alt="">';
                }
                if($counter_style == 'modern'){
                    $counter_style_class = 'cs_counter modren '.$counter_icon_align;
                }
                else{
                    $counter_style_class = 'cs_counter classic '.$counter_icon_align;
                }
                
                $output .= '<figure>';
                              $output .= $combine_counter_icon;
                              $output .='
                              <figcaption>';
                                if($counter_numbers <> ''){
                                $output .= '<a class="cs-numcount custom-counter-'.$rand_id.'" style=" color: '.$counter_number_color.';">'.$counter_numbers.'</a>';
                                }
                                if($counter_title <> ''){
                                $output .= '<span style="color:'.$counter_text_color.';">'.$counter_title.'</span>';
                                }
                                $output .= '<p style="color:'.$counter_text_color.';">'.do_shortcode($content).'</p>';
                                if($counter_link_url <> '' || $counter_link_title <> ''){
                                $counter_link_title = $counter_link_title ? $counter_link_title : __('Read More', 'dir');
                                $output .= '<a class="readmore cs-bg-color" href="'.$counter_link_url.'">'.$counter_link_title.'</a>';
                                }
                              $output .= '
                              </figcaption>
                            </figure>';
                
            }
            $html = '<div '.$CustomId.' class="'.$column_class.' '.$counter_animation.'"><article  class="'.$counter_style_class.' '.$counter_class.''.$border_class.'">'.$output.'</article></div>';
        return $html;
    }
    add_shortcode( 'cs_counter', 'cs_counter_item_shortcode' );
}


//======================================================================
// Services item
//======================================================================
if (!function_exists('cs_services_shortcode')) {
    function cs_services_shortcode( $atts, $content = null ) {
        global $service_type;
        
        $defaults = array( 'column_size'=>'1/2', 'cs_service_type' => '','cs_service_icon_type' => '','cs_service_border_right' => '','cs_service_icon' => '','cs_service_icon_color' => '','cs_service_bg_image' => '','cs_service_bg_color' => '','service_icon_size' => '','cs_service_postion_modern' => '','cs_service_postion_classic' => '','cs_service_title'=>'','cs_service_title_color'=>'','cs_service_content_color'=>'','cs_service_btn_text_color'=>'','cs_service_content' => '','cs_service_link_text' => '', 'cs_service_link_color'=>'','cs_service_url' => '', 'cs_service_class'=>'','cs_service_animation' => '');
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
        
        $serviceClass     = '';
        $html              = '';
        $bgColor        = '';
        $bgColorClass    = '';
        $align            = '';
        $linkColor         = '';
        $LinkIcon         = '';
        
        $CustomId    = '';
        if ( isset( $cs_service_class ) && $cs_service_class ) {
            $CustomId    = 'id="'.$cs_service_class.'"';
        }
        if ( trim($cs_service_animation) !='' ) {
            $cs_service_animation    = 'wow'.' '.$cs_service_animation;
        } else {
            $cs_service_animation    = '';
        }
        
        if ( isset( $cs_service_link_text ) && $cs_service_link_text !='' ) {
            $more        = $cs_service_link_text;
        } else{
            $more    = 'Read More';
        }
        
        if ( isset( $cs_service_icon_color ) && $cs_service_icon_color !='' ) {
            $iconColor        = 'style="color:'.$cs_service_icon_color.' !important;"';
        } else{
            $iconColor         =     '';
        }
        if ($cs_service_type == 'modern'){
            $serviceClass   =     'modren';
            $align            = $cs_service_postion_modern;
            
            if ( isset( $cs_service_link_color ) && $cs_service_link_color !='' ) {
                $linkColor = ' background-color: '.$cs_service_link_color.';';
            } else{
                $linkColor = '';
            }
            
            if ( isset( $cs_service_bg_color ) && $cs_service_bg_color !='' ) {
                $bgColor = 'style="background-color: '.$cs_service_bg_color.' !important;"';
                $bgColorClass    = 'bg-color';
            } else{
                $bgColor = '';
            }
            
            
        } else {
			if( $cs_service_type == 'classic-border' ) {
				$serviceClass = $cs_service_type.' hassingle-border';
			}else{
				$serviceClass = $cs_service_type;
			}
            $align          = $cs_service_postion_classic;
            $LinkIcon      = '<i class="icon-angle-right"></i>';
            if ( isset( $cs_service_link_color ) && $cs_service_link_color !='' ) {
                $linkColor = 'style="color: '.$cs_service_link_color.' !important;"';
            } else{
                $linkColor = '';
            }
        }
		
		$cs_service_border_class = $cs_service_border_right == 'yes' ? ' no-right-border' : '';
		
		$cs_service_title_color = $cs_service_title_color <> '' ? ' style="color:'.$cs_service_title_color.' !important;"' : '';
		$cs_service_content_color = $cs_service_content_color <> '' ? ' style="color:'.$cs_service_content_color.' !important;"' : '';
		$cs_service_btn_text_color = $cs_service_btn_text_color <> '' ? ' color:'.$cs_service_btn_text_color.' !important;' : '';
		
        $html    .= '<div class="col-md-12 '.$cs_service_animation.'" '.$CustomId.'>';
		if( $cs_service_type == 'classic-border' ) {
			$html    .= '<div class="cs-services '.$serviceClass.' '.$align.' '.$bgColorClass.$cs_service_border_class.'"  '.$bgColor.'>';
			$html    .= '<article>';
		}
		else{
			$html    .= '<article class="cs-services '.$serviceClass.' '.$align.' '.$bgColorClass.'"  '.$bgColor.'>';
		}
        if ( isset ( $cs_service_icon ) && $cs_service_icon !='' && $cs_service_icon_type == 'icon' ) {
            $html    .= '<figure><i class="'.$cs_service_icon .' '.$service_icon_size.'" '.$iconColor.'></i></figure>';
        }else if ( isset ( $cs_service_bg_image ) && $cs_service_bg_image !='' && $cs_service_icon_type == 'image' ) {
            $html    .= '<figure><img alt="" src="'.$cs_service_bg_image.'"></figure>';
        }
        $html    .= '<div class="text">';
        
        if ( isset ( $cs_service_title ) && $cs_service_title !='' ) {
            $html    .= '<h4'.$cs_service_title_color.'>'.$cs_service_title.'</h4>';
        }
        if ( isset ( $content ) && $content !='' ) {
            if ($cs_service_type == 'modern'){
                $html    .= '<p'.$cs_service_content_color.'>'.$content.'</p>';
                }else{
                    $html    .= '<p '.$linkColor.'>'.$content.'</p>';
                }
        }
        
        if ( isset ( $cs_service_url ) && $cs_service_url !='' ) {
            if ($cs_service_type == 'modern'){
                $html    .= '<a style="'.$cs_service_btn_text_color.$linkColor.'" class="read-more" href="'.esc_url($cs_service_url).'">'.$more.' '.$LinkIcon.'</a>';
            }
            else{
                $html    .= '<a class="read-more" href="'.esc_url($cs_service_url).'">'.$more.' '.$LinkIcon.'</a>';
            }
        }
        
        $html    .= '</div>';
        $html    .= '</article>';
        $html    .= '</div>';
		if( $cs_service_type == 'classic-border' ) {
		$html    .= '</div>';
		}
        
        return $html;
        
        
        
    }
    add_shortcode( 'cs_services', 'cs_services_shortcode' );
}

//======================================================================
// Services Content
//======================================================================
if (!function_exists('cs_service_content')) {
    function cs_service_contentt( $atts, $content = null ) {
        $defaults = array( 'content' => '' );
        extract( shortcode_atts( $defaults, $atts ) );
        return '<p>'. $content .'</p>';
    }
    add_shortcode( 'content', 'cs_service_content' );
}

//======================================================================
// Adding Call to Action start
//======================================================================
if (!function_exists('cs_call_to_action_shortcode')) {
    function cs_call_to_action_shortcode($atts, $content = "") {
        
        $defaults = array('column_size' => '1/1','cs_call_to_action_section_title'=>'','cs_content_type'=>'','cs_call_action_title'=>'','cs_call_action_contents'=>'','cs_contents_color'=>'', 'cs_call_action_icon'=>'','cs_icon_color'=>'#FFF','cs_call_to_action_icon_background_color'=>'','cs_call_to_action_button_text'=>'','cs_call_to_action_button_link'=>'#','cs_call_to_action_bg_img'=>'','animate_style'=>'slide','class'=>'cs-article-box','cs_call_to_action_class'=>'','cs_call_to_action_animation'=>'','cs_custom_animation_duration'=>'1');
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
        $cell_button = '';
        $CustomId    = '';
        if ( isset( $cs_call_to_action_class ) && $cs_call_to_action_class ) {
            $CustomId    = 'id="'.$cs_call_to_action_class.'"';
        }
        if ( trim($cs_call_to_action_animation) !='' ) {
            $cs_call_to_action_animation    = 'wow'.' '.$cs_call_to_action_animation;
        } else {
            $cs_call_to_action_animation    = '';
        }
        $section_title = '';
        if(isset($cs_call_to_action_section_title) && trim($cs_call_to_action_section_title) <> ''){
            $section_title = '<div class="cs-section-title"><h2 class="'.$cs_call_to_action_animation .' ">'.$cs_call_to_action_section_title.'</h2></div>';
        }
        $image = '';
        if (trim($cs_call_to_action_bg_img)) {
            $image    = 'background-image:url('.$cs_call_to_action_bg_img.');';
        }
        $html    = '';
        if ($cs_content_type == 'normal'){ $cs_class = 'ac-classic text-center';}else{ $cs_class = 'in-center ac-plane text-center';}
        
        $html    .= '<div class="call-actions '.$cs_class.' ' . $cs_call_to_action_class . ' '.$cs_call_to_action_animation .'" style=" background-color:'.$cs_call_to_action_icon_background_color.'; background-image: url('.$cs_call_to_action_bg_img.');  animation-duration: '.$cs_custom_animation_duration.'s; '.$image.' " >';
        $action_icon    = $cs_call_action_icon ?  $cs_call_action_icon : '';
        $cs_contents_color    = $cs_contents_color ? $cs_contents_color :'#FFF';
        
        $cell_heading = '<div class="cell heading">
                            <div class="ac-text">
                                <h3 style=" color: '.$cs_contents_color.' !important;">'.$cs_call_action_title.'</h3>
                            </div>
                        </div>';
                        
        $cell_icon = '<div class="cell icon">
                          <i style=" color: '.$cs_icon_color.' !important; " class="'.$action_icon.'"></i>
                      </div>';
        $cell_text = '<div class="cell text-area">
                          <div class="ac-text">
                              <p style=" color: '.$cs_contents_color.';">'. do_shortcode($content). '</p>
                          </div>
                      </div>';
        if ($cs_call_to_action_button_text <> '' and $cs_call_to_action_button_link<>'') {
                $cell_button ='<a class="custom-btn" href="'.esc_url($cs_call_to_action_button_link).'">'.$cs_call_to_action_button_text.'</a>';
        }
        if ($cs_content_type == 'normal'){
			$html .= $cell_heading.$cell_icon.$cell_text;
        }else{
			$html .= $cell_icon.$cell_heading.$cell_text.$cell_button;
        }
        $html    .= '</div>';
        return '<div ' . $CustomId . ' class="' . sanitize_html_class($column_class) . '">' . $section_title . $html . '</div>';
    }
    add_shortcode('call_to_action', 'cs_call_to_action_shortcode');
}
//======================================================================
// adding progressbars start
//======================================================================
if (!function_exists('cs_progressbars_shortcode')) {
    function cs_progressbars_shortcode($atts, $content = "") {
        global $cs_progressbars_style;
        $defaults = array('column_size'=>'1/1','cs_progressbars_style'=>'skills-sec','progressbars_class'=>'','progressbars_animation'=>'');
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
        $CustomId    = '';
        if ( isset( $progressbars_class ) && $progressbars_class ) {
            $CustomId    = 'id="'.$progressbars_class.'"';
        }
        if ( trim($progressbars_animation) !='' ) {
            $progressbars_animation    = ' wow'.' '.$progressbars_animation;
        } else {
            $progressbars_animation    = '';
        }
        cs_skillbar_script();
        $output = '<script>
                        jQuery(document).ready(function($){
                            cs_skill_bar();
                        });    
                  </script>';
        $progressbars_style_class = '';
        $progressbars_bar_class_v2 = '';
        $progressbars_bar_class = 'skills-v3';
        $heading_size = 'span';
        if(isset($cs_progressbars_style) && $cs_progressbars_style == 'strip-progressbar'){
            $progressbars_bar_class_v2 = 'skills-v2';
            $heading_size = 'span';
            $progressbars_bar_class = '';
        }
        else if(isset($cs_progressbars_style) && $cs_progressbars_style == 'plain-progressbar'){
            $progressbars_bar_class_v2 = 'plain';
            $heading_size = 'span';
            $progressbars_bar_class = '';
        }
        $output .= '<div '.$CustomId.' class="'.$column_class.$progressbars_animation.'"><div class="skills-element '.$cs_progressbars_style.' '.$progressbars_bar_class_v2.' ' . $progressbars_class . ' '.$progressbars_animation .'">';
        $output .= do_shortcode($content);    
        $output .= '</div></div>';
        return $output;
    }
    add_shortcode('cs_progressbars', 'cs_progressbars_shortcode');
}
//======================================================================
// adding progressbars Item start
//======================================================================
if (!function_exists('cs_progressbar_item_shortcode')) {
    function cs_progressbar_item_shortcode($atts, $content = "") {
        global $cs_progressbars_style;
        $defaults = array('progressbars_title'=>'','progressbars_color'=>'#4d8b0c','progressbars_percentage'=>'50');
        extract( shortcode_atts( $defaults, $atts ) );
        $output = '';
        $output_title ='';
        $progressbars_style_class = '';
        $heading_size = 'span';
        if(isset($cs_progressbars_style) && $cs_progressbars_style == 'strip-progressbar'){
            $progressbars_bar_class_v2 = 'skills-v2';
            $heading_size = 'span';
            $progressbars_bar_class = '';
        } 
        else if(isset($cs_progressbars_style) && $cs_progressbars_style == 'plain-progressbar'){
            $progressbars_bar_class_v2 = 'plain';
            $heading_size = 'span';
            $progressbars_bar_class = '';
        }
        else {
            $progressbars_bar_class = 'skills-v3';
        }
        if(isset($progressbars_title) && $progressbars_title <>''){
            $output_title .= '<'.$heading_size.'>'.$progressbars_title.'</'.$heading_size.'>';
        }
        if(isset($progressbars_percentage) && $progressbars_percentage <>''){
            
            /*if(isset($cs_progressbars_style) && $cs_progressbars_style == 'strip-progressbar'){
                $output .= $output_title;
            }*/
            $output .= '<div class="skills-sec '.$progressbars_bar_class.'" data-percent="'.$progressbars_percentage.'%">';
            if(isset($cs_progressbars_style) && $cs_progressbars_style == 'strip-progressbar'){
                $output .= $output_title;
                $output .= '<div class="skillbar"><div class="skillbar-bar" style="background-color: '.$progressbars_color.' !important;width:'.$progressbars_percentage.'%;"><small>'.$progressbars_percentage.'%</small></div></div>';
            } 
            else if(isset($cs_progressbars_style) && $cs_progressbars_style == 'plain-progressbar'){
                $output .= $output_title;
                $output .= '<div class="skillbar"><div class="skillbar-bar" style="background-color: '.$progressbars_color.' !important;width:'.$progressbars_percentage.'%;"></div></div><small>'.$progressbars_percentage.'%</small>';
            } 
            else {
                $output .= '<div class="skillbar"><div class="skillbar-bar" style="background: '.$progressbars_color.' !important;width:'.$progressbars_percentage.'%;">'.$output_title.'<small>'.$progressbars_percentage.'%</small></div></div>';
            }
            $output .= '</div>';
        }
        return $output;
    }
    add_shortcode('progressbar_item', 'cs_progressbar_item_shortcode');
}
//======================================================================
// adding piecharts start
//======================================================================
if (!function_exists('cs_piecharts_shortcode')) {
    function cs_piecharts_shortcode($atts, $content = "") {
        $defaults = array('column_size'=>'1/1','piechart_section_title'=>'','piechart_info'=>'','piechart_text'=>'','piechart_dimensions'=>'250','piechart_width'=>'10','piechart_fontsize'=>'50','piechart_percent'=>'35','piechart_icon'=>'','piechart_icon_color'=>'','piechart_icon_size'=>'20','piechart_fgcolor'=>'#61a9dc','piechart_bg_color'=>'#eee','piechart_bg_image'=>'','piechart_class'=>'','piechart_animation'=>'');
        extract( shortcode_atts( $defaults, $atts ) );
        cs_skillbar_script();
        $CustomId    = '';
        if ( isset( $piechart_class ) && $piechart_class ) {
            $CustomId    = 'id="'.$piechart_class.'"';
        }
        $rand_id = rand(98,56666);
        $column_class  = cs_custom_column_class($column_size);
        if ( trim($piechart_animation) !='' ) {
            $piechart_animation    = 'wow'.' '.$piechart_animation;
        } else {
            $piechart_animation    = '';
        }
        $output = '<script>
                        jQuery(document).ready(function($){
                            // Circul Progress Function
                            $("#chart'.$rand_id.'").waypoint(function(direction) {
                                $(this).circliful();
                            }, {
                                offset: "100%",
                                triggerOnce: true
                            });
                        });    
        </script>';
        $section_title = '';
        if ($piechart_section_title && trim($piechart_section_title) !='') {
            $section_title    = '<div class="cs-section-title"><h2 class="' . $piechart_class . ' '.$piechart_animation .'">'.$piechart_section_title.'</h2></div>';
        }
        $piechart_data_elements = '';
        if(isset($piechart_info) && $piechart_info !=''){
            $piechart_data_elements .= ' data-info="'.$piechart_info.'"';
        }
        if(isset($piechart_dimensions) && $piechart_dimensions !=''){
            $piechart_data_elements .= ' data-dimension="'.$piechart_dimensions.'"';
        }
        if(isset($piechart_width) && $piechart_width !=''){
            $piechart_data_elements .= ' data-width="'.$piechart_width.'"';
        }
        if(isset($piechart_fontsize) && $piechart_fontsize !=''){
            $piechart_data_elements .= ' data-fontsize="'.$piechart_fontsize.'"';
        }
        if(isset($piechart_percent) && $piechart_percent !=''){
            $piechart_data_elements .= ' data-text="'.$piechart_percent.'%"';
            $piechart_data_elements .= ' data-percent="'.$piechart_percent.'"';
        }
        if(isset($piechart_icon) && $piechart_icon !=''){
            $piechart_data_elements .= ' data-icon="'.$piechart_icon.'"';
        }
        if(isset($piechart_icon_size) && $piechart_icon_size !=''){
            $piechart_data_elements .= ' data-iconsize="'.$piechart_icon_size.'"';
        }
        if(isset($piechart_icon_color) && $piechart_icon_color !=''){
            $piechart_data_elements .= ' data-iconcolor="'.$piechart_icon_color.'"';
        }
        if(isset($piechart_fgcolor) && $piechart_fgcolor !=''){
            $piechart_data_elements .= ' data-fgcolor="'.$piechart_fgcolor.'"';
        }
        if(isset($piechart_bg_color) && $piechart_bg_color !=''){
            $piechart_data_elements .= ' data-bgcolor="'.$piechart_bg_color.'"';
        }
        if(isset($piechart_bg_image) && $piechart_bg_image !=''){
            $piechart_data_elements .=  ' data-bgimage="'.$piechart_bg_image.'"';
        }
        $output .= '<div id="chart'.$rand_id.'" class="chartskills ' . $piechart_class . ' '.$piechart_animation .'" '.$piechart_data_elements.'></div>';
        return '<div '.$CustomId.' class="'.$column_class.'">'.$section_title.'<div class="piechart col-md-12">'.$output.'</div></div>';
    }
    add_shortcode('cs_piechart', 'cs_piecharts_shortcode');
}
//======================================================================
// adding Faq
//======================================================================
if (!function_exists('cs_faq_shortcode')) {
    function cs_faq_shortcode($atts, $content = "") {
        global $acc_counter;
        $acc_counter = rand(40, 9999999);
        $html    = '';
        $defaults = array('column_size'=>'1/1', 'class' => 'cs-faq','faq_class' => '','faq_animation' => '','cs_faq_section_title'=>'');
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
        
        $CustomId    = '';
        if ( isset( $faq_class ) && $faq_class ) {
            $CustomId    = 'id="'.$faq_class.'"';
        }
        
        if ( trim($faq_animation) !='' ) {
            $faq_animation    = 'wow'.' '.$faq_animation;
        } else {
            $faq_animation    = '';
        }
        
        $section_title = '';
        if(isset($cs_faq_section_title) && trim($cs_faq_section_title) <> ''){
            $section_title = '<div class="cs-section-title"><h2>'.$cs_faq_section_title.'</h2></div>';
        }
        $html   .= '<div '.$CustomId.' class="'.$column_class.'">';
        $html    .= '<div class="panel-group simple'.$faq_class.' '.$faq_animation.'" id="faq-' . $acc_counter . '">'.$section_title.do_shortcode($content).'</div>';
        $html    .= '</div>';
        return $html;
    }
    
    add_shortcode('cs_faq', 'cs_faq_shortcode');
}
//======================================================================
// Adding Faq item start
//======================================================================
if (!function_exists('cs_faq_item_shortcode')) {
    function cs_faq_item_shortcode($atts, $content = "") {
        global $acc_counter,$faq_animation;
        $defaults = array( 'faq_title' => 'Title','faq_active' => 'yes','cs_faq_icon' => '');
        extract( shortcode_atts( $defaults, $atts ) );
        $faq_count = 0;
        $faq_count = rand(40, 9999999);
        $html = "";
        $active_in = '';
        $active_class = '';
        $styleColapse = '';
        $styleColapse    = 'collapse collapsed';
        if(isset($faq_active) && $faq_active == 'yes'){
            $styleColapse    = '';
            $active_in = 'in';
        } else {
            $active_class = 'collapsed';
        }
        
        $cs_faq_icon_class = '';
        if(isset($cs_faq_icon)){
            $cs_faq_icon_class = '<i class="'.$cs_faq_icon.'"></i>';
        }
        $html = '<div class="panel panel-default">
                    <div class="panel-heading">
                      <a data-toggle="collapse" data-parent="#faq-'.$acc_counter.'" href="#faq-'.$faq_count.'" class="'.$active_class.'">
                         '.$cs_faq_icon_class . $faq_title . '
                      </a>
                    </div>
                    <div id="faq-'.$faq_count.'" class="panel-collapse collapse '.$active_in.' ">
                      <div class="panel-body"><p>'.$content.'</p></div>
                    </div>
                  </div>';
        return $html;
    }
    add_shortcode('faq_item', 'cs_faq_item_shortcode');
}
/**
*@ Alow Spcial Char For Textfield 
*
**/
function cs_allow_special_char($input = ''){
    $output  = $input;
    return $output;
}