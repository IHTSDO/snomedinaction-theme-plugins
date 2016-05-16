<?php
class directory_regiser_templates {

 
	public function __construct() {

		$this->templates = array();
 
 
		// Add a filter to the page attributes metabox to inject our template into the page template cache.
		add_filter('page_attributes_dropdown_pages_args', array( $this, 'directory_register_templates' ) );

		// Add a filter to the save post in order to inject out template into the page cache
		add_filter('wp_insert_post_data', array( $this, 'directory_register_templates' ) );

		// Add a filter to the template include in order to determine if the page has our template assigned and return it's path
		add_filter('template_include', array( $this, 'directory_page_templates') );

		// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		// Add your templates to this array.
		$this->templates = array(
			'page_profile.php'		=> __( 'User Profile', 'directory' ),
 		);

		// adding support for theme templates to be merged and shown in dropdown
		$templates = wp_get_theme()->get_page_templates();
		$templates = array_merge( $templates, $this->templates );

	} // end constructor
 	
	/**
	 * Adds our template to the pages cache in order to trick WordPress
	 * into thinking the template file exists where it doens't really exist.
	*/
	
	public function directory_register_templates( $atts ) {

		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
		$templates = wp_cache_get( $cache_key, 'themes' );
		if ( empty( $templates ) ) {
			$templates = array();
		} // end if
		wp_cache_delete( $cache_key , 'themes');
		$templates = array_merge( $templates, $this->templates );
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );
		return $atts;

	} // end awaken_register_templates
	
	/**
	 * Checks if the template is assigned to the page
	 */
	
	public function directory_page_templates( $template ) {

		global $post;

 		if ( !isset( $post ) ) return $template;

		if ( ! isset( $this->templates[ get_post_meta( $post->ID, '_wp_page_template', true ) ] ) ) {
			return $template;
		}  

		$file = plugin_dir_path( __FILE__ ) . 'templates/' . get_post_meta( $post->ID, '_wp_page_template', true );

 		if( file_exists( $file ) ) {
			return $file;
		}  

		return $template;

	} // end awaken_page_templates

	/*--------------------------------------------*
	 * deactivate the plugin
	*---------------------------------------------*/
	static function deactivate( $network_wide ) {
		foreach($this as $value) {
			cs_delete_template( $value );
		}
		
	} // end deactivate

	/*--------------------------------------------*
	 * Delete Templates from Theme
	*---------------------------------------------*/
	public function directory_delete_template( $filename ){				
		$theme_path = get_template_directory();
		$template_path = $theme_path . '/' . $filename;  
		if( file_exists( $template_path ) ) {
			unlink( $template_path );
		}
		// we should probably delete the old cache
		wp_cache_delete( $cache_key , 'themes');
	}

 
} // end class