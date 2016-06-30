<?php
/*
* Directory Types Options on/off Array, Render functions
*/
// Dynamic post options array
if ( ! function_exists( 'cs_directory_custom_options_array' ) ) {
	function cs_directory_custom_options_array(){
		$price_style = array('Inputs'=>'Inputs','Slider'=>'Slider','Slider_Inputs'=>'Slider + Inputs',);
		$cs_map_views = array('Sidebar'=>'Sidebar','Content'=>'Content');
			$dynamic_post_options_meta['meta'] = array(
			'title' => 'Meta Options',
			'id' => 'dctp-meta-option',
			'notes' => 'Meta Options',
			'params' => array(
				'post_faqs' => array(					
					'title' => __('FAQS', 'directory'),
					'cs_post_faqs_option' => array(
						'name' => 'cs_post_faqs_option',
						'type' => 'checkbox',
						'title' => __('FAQS', 'directory'),
						'description' => '',
					),
					'cs_post_faqs_input' => array(
						'name' => 'cs_post_faqs_input',
						'type' => 'text',
						'title' => 'FAQS',
						'description' => '',
					),
				),
				'post_location' => array(					
					'title' => __('Location', 'directory'),					
					'cs_post_location_option' => array(
						'name' => 'cs_post_location_option',
						'type' => 'checkbox',
						'title' => __('Location', 'directory'),
						'description' => '',
					),
					'cs_post_location_input' => array(
						'name' => 'cs_post_location_input',
						'type' => 'text',
						'title' => 'Location Settings',
						'description' => '',
					),
				),
				'directory_post_prcie' => array(					
					'title' => __('Directory Type Price', 'directory'),					
					'cs_directory_post_prcie' => array(
						'name' => 'cs_directory_post_prcie',
						'type' => 'text',
						'title' => __('Price', 'directory'),
						'description' => '',
					),
				),
				'post_favourites_btn' => array(						
					'title' => __('Save Favourites', 'directory'),
					'cs_post_favourites_option' => array(
						'name' => 'cs_post_favourites_option',
						'type' => 'checkbox',
						'title' => __('Save Favourites', 'directory'),
						'description' => '',
					),
					'cs_post_favourites_input' => array(
						'name' => 'cs_post_favourites_input',
						'type' => 'text',
						'title' => __('Save', 'directory'),
						'description' => '',
					),
				),
				'post_opening_hours' => array(	
					
					'title' => __('Opening Hours', 'directory'),
					
					'cs_post_opening_hours_option' => array(
						'name' => 'cs_post_opening_hours_option',
						'type' => 'checkbox',
						'title' => __('Opening Hours', 'directory'),
						'description' => '',
					),
					'cs_post_opening_hours_input' => array(
						'name' => 'cs_post_opening_hours_input',
						'type' => 'text',
						'title' => 'Opening Hours',
						'description' => '',
					),
				),
				'post_multiple_images' => array(
					'title' => __('Enable Upload of Images', 'directory'),
					'cs_post_multi_imgs_option' => array(
						'name' => 'cs_post_multi_imgs_option',
						'type' => 'checkbox',
						'title' => __('Enable Upload of Images', 'directory'),
						'description' => '',
					),
					'cs_multiple_images_input' => array(
						'name' => 'cs_multiple_images_input',
						'type' => 'text',
						'title' => '5',
						'description' => 'Maximum Images per ad',
					),
				),
				'post_multiple_attachment' => array(
					'title' => __('Enable Upload of Attachment', 'directory'),
					'cs_post_multi_attachment_option' => array(
						'name' => 'cs_post_multi_attachment_option',
						'type' => 'checkbox',
						'title' => __('Enable Upload of Attachment', 'directory'),
						'description' => '',
					),
					'cs_multiple_attachment_input' => array(
						'name' => 'cs_multiple_attachment_input',
						'type' => 'text',
						'title' => '5',
						'description' => 'Maximum Attachment per ad',
					),
				),
				'post_video_switch' => array(
					'title' => __('Enable Video', 'directory'),					
					'post_video_switch' => array(
						'name' => 'cs_post_detail_video',
						'type' => 'checkbox',
						'title' => __('Enable Video', 'directory'),
						'description' => '',
					),
				),
				'post_multiple_tags' => array(		
					'title' => __('Enable Tags', 'directory'),
					'cs_post_multi_tags_option' => array(
						'name' => 'cs_post_multi_tags_option',
						'type' => 'checkbox',
						'title' => __('Enable Tags', 'directory'),
						'description' => '',
					),
					'cs_multiple_tags_input' => array(
						'name' => 'cs_multiple_tags_input',
						'type' => 'text',
						'title' => '5',
						'description' => 'Maximum Tags per ad',
					),
				),
				'post_review_switch' => array(
					'title' => __('Enable Reviews', 'directory'),
					'post_review_switch' => array(
						'name' => 'post_review_switch',
						'type' => 'checkbox',
						'title' => __('Enable Reviews', 'directory'),
						'description' => '',
					),
				),
				'post_multiple_categories' => array(	
					'title' => __('Enable Multiple Categories', 'directory'),					
					'cs_post_multi_cat_option' => array(
						'name' => 'cs_post_multi_cat_option',
						'type' => 'checkbox',
						'title' => __('Enable Multiple Categories', 'directory'),
						'description' => '',
					),
				),
				'post_request_form' => array(	
				'title' => __('Enable  Request Detail Form', 'directory'),					
					'cs_post_request_form_option' => array(
						'name' => 'cs_post_request_form_option',
						'type' => 'checkbox',
						'title' => __('Enable  Request Detail Form', 'directory'),
						'description' => '',
					),
				),
				'cs_related_ads' => array(	
					'title' => __('Enable Organizer Ads', 'directory'),
					
					'cs_related_ads_option' => array(
						'name' => 'cs_related_ads',
						'type' => 'checkbox',
					'title' => __('Enable Organizer Ads', 'directory'),
						'description' => '',
					),
				),
				'post_destination_thumb_url' => array(		
					'title' => __('Map Marker Image', 'directory'),
					'cs_destination_url_input' => array(
						'name' => 'cs_destination_url_input',
						'type' => 'image',
						'title' => wp_directory::plugin_url().'assets/images/default-marker.png',
						'description' => '',
					),
				),
				'post_detail_map_view' => array(		
					'title' => __('Ad Detail Map', 'directory'),
					'post_detail_map' => array(
						'name' => 'post_detail_map',
						'type' => 'select',
						'title' => '',
						'options' => $cs_map_views,
						'description' => 'Select Map Placement in Ad Detail page.',
					),
				),
				'post_price' => array(
					'title' => __('Price', 'directory'),
					
					'cs_post_price_saleprice_option' => array(
						'name' => 'cs_post_price_saleprice_option',
						'type' => 'checkbox',					
						'title' => __('Price/Sale price', 'directory'),
						'description' => '',
					),
					'cs_post_price_saleprice_input' => array(
						'name'	=> 'cs_post_price_saleprice_input',
						'type'	=> 'text',
						'title' => 'Price/Saleprice',
						'description' => 'Price/Saleprice',
					),
					'cs_post_price_enable_search' => array(
						'name' => 'cs_post_price_enable_search',
						'type' => 'checkbox',
						'title' => 'Enable Price Search',
						'description' => 'Enable Price Search',
					),
					'cs_post_price_saleprice_min_input' => array(
						'name' => 'cs_post_price_saleprice_min_input',
						'type' => 'text',
						'title' => '',
						'description' => 'Min Range',
					),
					'cs_post_price_saleprice_max_input' => array(
						'name' => 'cs_post_price_saleprice_max_input',
						'type' => 'text',
						'title' => '',
						'description' => 'Max Range',
					),
					'cs_post_price_saleprice_incr_input' => array(
						'name' => 'cs_post_price_saleprice_incr_input',
						'type' => 'text',
						'title' => '2',
						'description' => 'Increment Steps',
					),
					'cs_post_price_saleprice_style' => array(
						'name' => 'cs_post_price_saleprice_style',
						'type' => 'select',
						'title' => '',
						'options' => $price_style,
						'description' => 'Price Search Style',
					),
				),
				'cs_post_type_icon' => array(					
					'title' => __('Type icon', 'directory'),					
					'cs_post_type_icon_input' => array(
						'name' => 'cs_post_type_icon_input',
						'type' => 'image',
						'title' => '',
						'description' => '',
					),
				),

			)
		);
		return $dynamic_post_options_meta['meta'];
	}
}
// Dynamic custom post options array
if ( ! function_exists( 'cs_directory_custom_options' ) ) {
	function cs_directory_custom_options(){
		global $post;
		$html = '';
		$meta_options = cs_directory_custom_options_array();

		if(is_array($meta_options)){
			$html .= '<div id="clone-' . $meta_options['id'] . '" class="cs-clone-template">';
			foreach( $meta_options['params'] as $table_key=>$tablerows ) {
				$html .= '<ul class="form-elements">';
				if( isset( $tablerows['title'] ) && !empty( $tablerows['title'] ) ) {
					$html .= '<li class="to-label"><label>' . $tablerows['title']. ':</label></li>';
				}
				$html .= '<li class="to-field">';
				foreach( $tablerows as $key=>$param ) {
					if(is_array($param )){
						$cs_meta_value = get_post_meta($post->ID, $key, true);
						$html .= cs_create_option_fields($key, $param);
					}
				}
				$html .= '</li>';
				$html .= '</ul>';
			}
			$html .= '</div>';
		}
		return $html;	
	}
}
// Create page option Fields
if ( ! function_exists( 'cs_create_option_fields' ) ) {
	function cs_create_option_fields($key, $param) {
		global $post;
		$cs_value = $param['title'] ;
		
		$cs_meta_value = get_post_meta($post->ID, $key, true);
		if(isset($cs_meta_value) && $cs_meta_value <> ''){
			$cs_value = $cs_meta_value;
		}
		
		$html = '';
	//	$html .= '<td>' . $param['title']. ':</td>';
		switch( $param['type'] )
		{
			case 'text' :
				// prepare
				$output = '<div class="input-text">';
				$output .= '<input type="text" class="cs-form-text cs-input " name="dcpt_options[' . $key . ']" id="' . $key . '" value="' . $cs_value . '" />' . "\n";
				$output .= '<span class="cs-form-desc">' . $param['description'] . '</span>' . "\n</div>";
				$html .= $output;
				break;
			case 'textarea' :
				// prepare
				$output = '<div class="input-textarea">';
				$output .= '<textarea rows="10" cols="30" name="dcpt_options[' . $key . ']" id="' . $key . '" class="cs-form-textarea cs-input">' . $cs_value . '</textarea>' . "\n";
				$output .= '<span class="cs-form-desc">' . $param['description'] . '</span>' . "\n</div>";
				$html .= $output;
				break;

			case 'select' :
				
				$output = '<div class="input-select">';
				$output .= '<select name="dcpt_options[' . $key . ']" id="' . $key . '" class="cs-form-select cs-input">' . "\n";
				
				foreach( $param['options'] as $value => $option )
				{
					$selected = '';
					if($cs_value == $value){
						$selected = 'selected="selected"';
					}
					
					$output .= '<option value="' . $value . '" '.$selected.'>' . $option . '</option>' . "\n";
				}
				$output .= '</select>' . "\n";
				$output .= '<span class="cs-form-desc">' . $param['description'] . '</span>' . "\n</div>";
				// append
				$html .= $output;
				break;
			case 'checkbox' :
				// prepare
				//cs_post_social_sharing
				$cs_value = '';
				$checked  = '';
				$output = '<div class="input-option">';
				$cs_value = get_post_meta($post->ID, $key, true);
				if($cs_value == 'on'){$checked = 'checked="checked"';}
				$output .= '<input type="hidden" name="dcpt_options[' . $key . ']" value="" />';
				$output .= '<label class="pbwp-checkbox"><input type="checkbox" value="on" name="dcpt_options[' . $key . ']" id="' . $key . '" class="cs-form-checkbox cs-input"' .$checked. '><span class="pbwp-box"></span></label><br/><br/><span class="cs-form-desc">' . $param['description'] . '</span>' . "\n";
				$output .= '</div>';
				$html .= $output;
				break;
			case 'icon' :
				$cs_rand_id = rand(45, 993301);
				$output = '<div class="input-option">';
				$cs_value = get_post_meta($post->ID, $key, true);
				$output .= '<input type="text" class="cs-search-icon-hidden" name="dcpt_options[' . $key . ']" value="' . cs_allow_special_char($cs_value) . '">';
                //$output .= cs_fontawsome_icons_box($cs_value,$cs_rand_id);
				$output .= '</div>';
				$html .= $output;
				break;
			case 'image' :
				$cs_rand_string = rand(45, 993301);
				$cs_active_class = $cs_value && trim($cs_value) !='' ? 'inline;' : 'none;';
				$output = '<div class="input-option"><span class="cs-form-desc">' . $param['description'] . '</span>';
                $output .= '
				<ul class="form-elements">
				  <li class="to-field">
				  	<div class="page-wrap" style="overflow:hidden; display:'.$cs_active_class.'" id="cs_dcpt_img'.($cs_rand_string).'_box" >
					  <div class="gal-active" style="padding-left:0 !important;">
						<div class="dragareamain" style="padding-bottom:0px;">
						  <ul id="gal-sortable">
							<li class="ui-state-default" id="">
							  <div class="thumb-secs cs-custom-image"> <img src="'.esc_url($cs_value).'"  id="cs_dcpt_img'.($cs_rand_string).'_img" width="100" />
								<div class="gal-edit-opts"> <a href="javascript:del_media(\'cs_dcpt_img'.($cs_rand_string).'\')" class="delete"></a> </div>
							  </div>
							</li>
						  </ul>
						</div>
					  </div>
					</div>
					<input id="cs_dcpt_img'.($cs_rand_string).'" name="dcpt_options[' . $key . ']" type="hidden" class="" value="'.($cs_value).'"/>
					<label class="browse-icon"><input name="cs_dcpt_img'.($cs_rand_string).'"  type="button" class="uploadMedia left" value="'.__('Browse','directory').'"/></label>
				  </li>
				</ul>';
				$output .= '</div>';
				$html .= $output;
				break;
			default :
				break;
		}
		return $html;
	}
}