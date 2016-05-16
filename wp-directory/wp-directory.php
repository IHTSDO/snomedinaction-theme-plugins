<?php
/*
Plugin Name: WP Directory
Plugin URI: http://themeforest.net/user/Chimpstudio/
Description: Directory Management
Version: 1.8
Author: ChimpStudio
Author URI: http://themeforest.net/user/Chimpstudio/
License: GPL2
Copyright 2015  chimpgroup  (email : info@chimpstudio.co.uk)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, United Kingdom
*/
if(!class_exists('wp_directory'))
{
    class wp_directory
    {
		public $plugin_url;		
        //=====================================================================
		// Construct
		//=====================================================================
        public function __construct()
        {
			global $post,$wp_query,$cs_theme_options;
			$cs_theme_options = get_option('cs_theme_options');
			$this->plugin_url = plugin_dir_url( __FILE__ );
			$this->plugin_dir = plugin_dir_path( __FILE__ );			
			require_once('payment-gateway/class-payments.php');
			require_once ('payment-gateway/config.php');			
			require_once('register-templates/class_register_templates.php');			
			if(class_exists('directory_regiser_templates')){
				$directory_regiser_templates = new directory_regiser_templates();
			}			
			require_once('directory-post-types/post_type_directory.php');
			require_once('directory/dir-styles/directory_headers.php');			
			if(class_exists('post_type_directory')){
				$directory_object = new post_type_directory();				
				require_once('directory/dir-styles/directory_element.php');
				require_once('directory/dir-styles/directory_templates.php');
				require_once('directory/dir-styles/directory_ajax_templates.php');
				require_once('directory/dir-styles/directory_functions.php');
				require_once('directory/functions.php');
				require_once('directory/dir-search/search_element.php');
				require_once('directory/dir-search/search_template.php');
				require_once('directory/dir-search/search_functions.php');				
				require_once('directory-post-types/post_type_reviews.php');
				require_once('directory-post-types/post_type_reports.php');
				require_once('directory/dir-agents/agents_element.php');
				require_once('directory/dir-agents/agents_functions.php');
				require_once('directory/dir-agents/agents_templates.php');
				require_once('directory/dir-categories/categories_element.php');
				require_once('directory/dir-categories/categories_template.php');
				require_once('directory/dir-styles/single_templates.php');
				if(class_exists('post_type_reviews')){
					$reviews_object = new post_type_reviews();
				}
				if(class_exists('post_type_reports')){
					$reports_object = new post_type_reports();
				}
				require_once('settings/plugin_options.php');
				require_once('directory-types/pt_directory_types.php');
				require_once('directory-types/directory_custom_fields.php');
				require_once('directory-types/directory_types_options.php');
				require_once('directory-types/admin_functions.php');
				require_once('directory-types/frontend_functions.php');				
			}			
 			require_once ('directory-login/login_functions.php');
			require_once ('directory-login/login_forms.php');
			require_once ('directory-login/shortcodes.php');
			require_once ('widgets/widgets.php');
 			if(isset($cs_theme_options) && is_array($cs_theme_options) && count($cs_theme_options)){
				require_once ('directory-login/cs-social-login/cs_social_login.php');
				require_once ('directory-login/cs-social-login/google/cs_google_connect.php');
			}
 			require_once ('register-templates/templates/functions_profile.php');
			add_filter('template_include', array(&$this, 'cs_single_template_function'));
			add_filter('user_contactmethods', 'cs_profile_fields', 10, 1);
			add_action('wp_enqueue_scripts', array(&$this, 'cs_defaultfiles_plugin_enqueue'));
			  add_action(plugin_basename(__FILE__), 'your_update_message_cb', 10, 2 );	
		 	add_action('admin_enqueue_scripts', array(&$this, 'cs_defaultfiles_plugin_enqueue'));
			add_action('init', array($this, 'load_plugin_textdomain'));
			
        }


		public function load_plugin_textdomain() {
 			$cs_theme_options = get_option('cs_theme_options');	
			$languageFile	= isset( $cs_theme_options['cs_language_file'] ) ? $cs_theme_options['cs_language_file'] : '';
			//load_plugin_textdomain('directory', false,plugin_dir_url( __FILE__ ));
			$locale	= apply_filters( 'plugin_locale', get_locale(), 'directory' );
			$dir	= trailingslashit( WP_LANG_DIR );
 			
			if( isset( $languageFile ) && $languageFile !='' ) {
				load_textdomain( 'directory',plugin_dir_path( __FILE__ ) . "languages/".$cs_theme_options['cs_language_file'] );
			} else {
				load_textdomain( 'directory', $dir . 'directory-de_DE.mo' );
			}
		}		
	public	function your_update_message_cb( $plugin_data, $r )
	{
    // readme contents
	
    $data       = file_get_contents(get_template_directory_uri().'/include/theme-components/cs-activation-plugins/wp-directory.zip');
 
    
    // echo stuff....
    $output = 'whatever you want to do';
    echo $output;
}
		//=====================================================================
		// PLugin URl
		//=====================================================================
		public static function plugin_url(){
			return plugin_dir_url( __FILE__ );
		}		
		//=====================================================================
		// Plugin Images Path
		//=====================================================================
		public static function plugin_img_url(){
			return plugin_dir_url( __FILE__ );
		}		
		//=====================================================================
		// Plugin URL
		//=====================================================================
		public static function plugin_dir(){
			return plugin_dir_path( __FILE__ );
		}

		//=====================================================================
		// Activate the plugin
		//=====================================================================
        public static function activate()
        {	
			add_option( 'cs_directory_plugin_activation', 'installed' );
			add_option( 'cs_directory', '1' );
        } 		    
        //=====================================================================
		// Deactivate the plugin
		//=====================================================================
		static function deactivate()
        {
           delete_option( 'cs_directory_plugin_activation');
		   delete_option( 'cs_directory', false ); 
        }  
		//=====================================================================
		// Include Default Scripts and styles
		//=====================================================================
		public function cs_defaultfiles_plugin_enqueue()
		{
			wp_enqueue_script('jquery');
			//wp_enqueue_media();
			//wp_enqueue_script( 'jquery-ui-slider' );
			wp_enqueue_script('directory_functions_js', plugins_url( '/assets/scripts/directory_functions.js' , __FILE__ ), '', '', true );
			wp_enqueue_script('reorder_js', plugins_url( '/assets/scripts/reorder.js' , __FILE__ ), '', '', true );
			wp_enqueue_script('bootstrap.min_script', plugins_url( '/assets/scripts/bootstrap_min.js' , __FILE__ ), '', '', true);
			wp_enqueue_script('socialconnect_js', plugins_url( '/directory-login/cs-social-login/media/js/cs-connect.js' , __FILE__ ),'','',true);
			wp_enqueue_script('jquery.slider-min_js', plugins_url( '/assets/scripts/jquery.flexslider-min.js' , __FILE__ ), '', '', true);
			wp_enqueue_style('jquery.slider-min_css', plugins_url( '/assets/css/flexslider.css' , __FILE__ ));	
			wp_enqueue_style('datatable_style', plugins_url( '/assets/css/datatable.css' , __FILE__ ));	
			wp_enqueue_script('datatable_script', plugins_url( '/assets/scripts/datatable.js' , __FILE__ ), '', '', true);
			
			if(is_admin()){
 				wp_enqueue_style('directory_style_css', plugins_url( '/assets/css/admin_style.css' , __FILE__ ));
			}
			if(is_user_logged_in()){
				wp_enqueue_script('datetimepicker1_js', plugins_url( '/assets/scripts/jquery_datetimepicker.js' , __FILE__ ), '', '', true);
				wp_enqueue_style('datetimepicker1_css', plugins_url( '/assets/css/jquery_datetimepicker.css' , __FILE__ ));	
			}
		}
		//=====================================================================
		// AutoComplete Scipts
		//=====================================================================
		public static function cs_autocomplete_scripts() {
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( 'jquery-ui-slider' );
			wp_enqueue_style( 'jquery-ui-styles',plugins_url('/assets/css/jquery-ui.css' , __FILE__ ));
		}		
        //=====================================================================
		// Pretty Photo
		//=====================================================================
		public static function cs_ui_scripts()
        {	
           wp_enqueue_script('jquery-ui_js', plugins_url( '/assets/scripts/jquery-ui.min.js' , __FILE__ ), '', '', true);
        }		
		//=====================================================================
		// AutoComplete Scipts
		//=====================================================================
		public static function cs_prettyPhoto_scripts() {
			wp_enqueue_script('prettyPhoto_js', plugins_url( '/assets/scripts/jquery.prettyphoto.js' , __FILE__ ), '', '', true);			
		}
		//=====================================================================
		// AutoComplete Scipts
		//=====================================================================
		public static function cs_ui_slider_scripts() {
			wp_enqueue_script( 'jquery-ui-slider' );
			
		} 
		//=====================================================================
		// Google Places
		//=====================================================================
		public static function cs_google_place_scripts() {
			wp_enqueue_script( 'jquery-goolge-places', 'http://maps.google.com/maps/api/js?sensor=false&libraries=places', '', '', true);
		}		
		//=====================================================================
		// Choosen Dropdown
		//=====================================================================
		public static function cs_load_choosen_scripts() {
			wp_enqueue_style('chosen_style', plugins_url( '/assets/css/chosen.css' , __FILE__ ));	
			wp_enqueue_script('chosen_script', plugins_url( '/assets/scripts/chosen.jquery.js' , __FILE__ ), '', '', true);
		}		 
		//=====================================================================
		// JRating
		//=====================================================================
		public static function cs_enqueue_rating_style_script() {
			wp_enqueue_script('jquery.rating_js', plugins_url( '/assets/scripts/jRating.jquery.js' , __FILE__ ), '', '', true);
			wp_enqueue_style('jquery.rating_css', plugins_url( '/assets/css/jRating.jquery.css' , __FILE__ ));	
		}
		//=====================================================================
		// Multiple Select
		//=====================================================================
		public static function cs_multipleselect_scripts() {
			global $wp_styles;
			wp_enqueue_script('jquery_multipleselect_js', plugins_url( '/assets/scripts/jquery.sumoselect.min.js' , __FILE__ ), '', '', true);
			wp_enqueue_style('directory_sumoselect_css', plugins_url( '/assets/css/sumoselect.css' , __FILE__ ));
		}
		//=====================================================================
		// Google Map markerclusterer
		//=====================================================================
		public static function cs_googlemapcluster_scripts() {
			wp_enqueue_script( 'jquery-goolge-places', 'http://maps.google.com/maps/api/js?sensor=false&libraries=places', '', '', true);
			 wp_enqueue_script('markerclusterer_js', plugins_url('/assets/scripts/markerclusterer.js', __FILE__), '', '', true);

		} 
		//=====================================================================
		// Include Single Templates
		//=====================================================================
		public function cs_single_template_function( $single_template )
		{
			global $post;
			$single_path = dirname( __FILE__ );
			if ( get_post_type() == 'directory' ) {
				if ( is_single() ) {
					$single_template = plugin_dir_path( __FILE__ ) . 'directory/dir-styles/single_directory.php';
				}
			}
			return $single_template;
		}
    }
}
//=====================================================================
// Create Object of class To Activate Plugin
//=====================================================================
if(class_exists('wp_directory'))
{
	$cs_directory = new wp_directory();
	register_activation_hook( __FILE__, array( 'wp_directory', 'activate' ));
	register_deactivation_hook( __FILE__, array('wp_directory', 'deactivate'));
}
