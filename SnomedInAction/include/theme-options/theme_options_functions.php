<?php
// Save Theme Options
if ( ! function_exists( 'theme_option_save' ) ) {
	function theme_option_save() {
		global $reset_date,$cs_options;
		$_POST = cs_stripslashes_htmlspecialchars($_POST);
		
 		if(isset($_POST['cs_import_theme_options']) and $_POST['cs_import_theme_options'] <> ''){
			update_option( "cs_theme_options", unserialize(base64_decode($_POST['cs_import_theme_options'])));
		}else{
			update_option( "cs_theme_options",$_POST );
		}
		echo "All Settings Saved";
		
		die();
	}
	add_action('wp_ajax_theme_option_save', 'theme_option_save');
}


// saving all the theme options end
if ( ! function_exists( 'theme_option_rest_all' ) ) {
	function theme_option_rest_all() {
		delete_option('cs_theme_options');
		update_option( "cs_theme_options", cs_reset_data());
		echo "Reset All Options";
		die();
	 }
	add_action('wp_ajax_theme_option_rest_all', 'theme_option_rest_all');
}
// theme activation
if ( ! function_exists( 'cs_activation_data' ) ) {
	function cs_activation_data(){
		update_option('cs_theme_options',cs_reset_data());
	}
}

/* return array for reset theme options*/
if ( ! function_exists( 'cs_reset_data' ) ) {
	function cs_reset_data(){
		global $reset_data,$cs_options;
			foreach ($cs_options as $value) {
			//update_option('cs_theme_reset',$reset_data);
			if($value['type'] <> 'heading' and $value['type'] <> 'sub-heading' and $value['type']<>'main-heading'){
				if($value['type']=='sidebar' || $value['type']=='networks' || $value['type']=='badges'){
					$reset_data=(array_merge($reset_data,$value['options']));
				}if($value['type']=='packages_data'){
					update_option('cs_packages_options',$value['std']);
				}if($value['type']=='free_package'){
					update_option('cs_free_package_switch',$value['std']);
				}elseif($value['type']=='check_color'){
					$reset_data[$value['id']] = $value['std'];
					$reset_data[$value['id'].'_switch'] = 'off';
				}else{
					$reset_data[$value['id']] = $value['std'];
				}
			}
		}
		return $reset_data;
	}
}
function cs_headerbg_slider(){
	if(class_exists('RevSlider') && class_exists('cs_RevSlider')) {
		$slider = new cs_RevSlider();
		$arrSliders = $slider->getAllSliderAliases();
		foreach ( $arrSliders as $key => $entry ) {
			$selected = '';
			 if($select_value != '') {
				 if ( $select_value == $key['alias']) { $selected = ' selected="selected"';} 
			 } else {
				 if ( isset($value['std']) )
					 if ($value['std'] == $key['alias']) { $selected = ' selected="selected"'; }
			 }
			$output.= '<option '.$selected.' value="'.$key['alias'].'">'.$entry['title'].'</option>';
		}
	}
}

/*---------------------------------------------------
 * Update Locations
 *--------------------------------------------------*/
if ( ! function_exists( 'cs_update_locations' ) ) {
	function cs_update_locations(){
		global $cs_theme_options;
		
		$cs_theme_options	= get_option('cs_theme_options');
	
		if( $_POST['type']	== 'countries' ) {
			$countries	= array();
			$cs_countries	= explode(',',$_POST['countries']);
			if( isset( $cs_countries ) && $cs_countries !='' ){
				$countries	= isset( $cs_theme_options['locations'] ) ? $cs_theme_options['locations']: array();
				foreach( $cs_countries as $key => $value ){
					$countries[strtolower($value)]	= $value;
				}
			}
			
			$cs_theme_options['locations']	= $countries;
			
		} else if( $_POST['type']	== 'states' ){
			$states	= array();
			$cs_country		= trim( $_POST['country'] );
			$new_states		= explode(',',$_POST['states']);

			if( isset( $new_states ) && $new_states !='' ){
				$states	= isset( $cs_theme_options['locations'][$cs_country] ) && $cs_theme_options['locations'][$cs_country] !='' ? $cs_theme_options['locations'][$cs_country]: array();
				foreach( $new_states as $key => $value ){
					echo $value;
					$value	= strtolower($value);
					$states[$value]	= $value;
				}
			}
			
			
			
			$cs_theme_options['locations'][$cs_country]	= $states;
			
		} else if( $_POST['type']	== 'cities' ){
			$cities	= array();
			$cs_country		= trim( $_POST['country'] );
			$cs_state		= trim( $_POST['state'] );
			$cs_cities		= explode(',',$_POST['cities']);
			
			if( isset( $cs_cities ) && $cs_cities !='' ){
				$cities	= isset( $cs_theme_options['locations'][$cs_country][$cs_state] ) ? $cs_theme_options['locations'][$cs_country][$cs_state]: array();
				foreach( $cs_cities as $key => $value ){
					$cities[strtolower($value)]	= $value;
				}
			}
			
			$cs_theme_options['locations'][$cs_country][$cs_state]	= $cities;
		}  
		
		
		//die;
		
		//update_option('cs_theme_options',$cs_theme_options);
		
		echo '<pre>';
		print_r($cs_theme_options);
		echo '</pre>';
		
	}
	add_action('wp_ajax_cs_update_locations', 'cs_update_locations');
}

/*---------------------------------------------------
 * Update Load States
 *--------------------------------------------------*/
if ( ! function_exists( 'cs_load_states' ) ) {
	function cs_load_states(){
		global $cs_theme_options;
		$cs_theme_options	= get_option('cs_theme_options');
		$states	= '';
		if( $_POST['country'] && $_POST['country'] !=''  ) {
			$country	= $_POST['country'];
			$states_data	= $cs_theme_options['locations'][$country];
			$states	.= '<option value="">Select State</option>' ;
			if( isset( $states_data ) && $states_data !='' ){
				foreach( $states_data as $key => $value )
				$states	.='<option value="'.$value.'">'.$value.'</option>' ;
			}
		}
		
		echo $states;
		die();
	}
	add_action('wp_ajax_cs_load_states', 'cs_load_states');
}
?>