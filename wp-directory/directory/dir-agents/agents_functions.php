<?php
//======================================================================
// Members Html Function Starts
//======================================================================

if ( ! function_exists( 'cs_members_listing_func' ) ) {
	function cs_members_listing_func( $atts, $content = "" ) {
		global $post, $wpdb, $cs_node, $cs_theme_options, $member_column, $cs_xmlObject, $column_attributes;
		
		$user_id = cs_get_user_id();
		$cs_directory_options = get_option('cs_theme_options');
		
		$defaults = array(
			'column_size'					=> '1/1',
			'var_pb_members_title'			=> '',
			'var_contact_fields'			=> '',
			'var_pb_members_register_text'	=> '',
			'var_pb_members_register_url'	=> '',
			'var_pb_members_register_color'	=> '',
			'var_pb_members_description'	=> 'on',
			'var_pb_members_roles'			=> '',
			'var_pb_members_filterable'		=> '',
			'var_pb_members_azfilterable'	=> '',
			'var_pb_members_pagination'		=> '',
			'var_pb_members_all_tab'		=> '',
			'var_pb_members_per_page'		=> '',
			'var_pb_member_view'			=> '',
			'cs_members_class'				=> '',
			'cs_members_animation'			=> ''
		);
 		extract( shortcode_atts( $defaults, $atts ) );
		$coloumn_class = cs_custom_column_class( $column_size );
		ob_start();
		$filter_action	= '';
		$cs_dummy_image = 'dummy.jpg';
		$plugin_url		= plugins_url();
		
		$cs_dummy_image = $plugin_url.'/wp-directory/assets/images/dummy.jpg';
		// Heading Starts
		if( $var_pb_members_title != '' ) {
 		?>
            <div class="cs-section-title col-md-12">
                <h2><?php echo cs_allow_special_char($var_pb_members_title);?></h2>
            </div>
        <?php
		}
		// Heading Ends
		?>
        <div class="wow <?php echo cs_allow_special_char($cs_members_class . ' '.$cs_members_animation) ;?>">
			<?php
            	// Heading Starts
 				if( isset( $var_pb_members_roles ) && $var_pb_members_roles <> '' ) {
					$directory_member_roles = array();
					$qrystr= "";
					if ( isset($_GET['page_id']) ) $qrystr = "&page_id=".$_GET['page_id'];
					$directory_member_roles = explode(',',$var_pb_members_roles);
					$filter_action = '';
                	if( count ( $directory_member_roles ) > 0 ) :
						// Members Filteration Starts
						if( $var_pb_members_filterable == "on" ) {
						?>
                    	<div class="cs-agent-filter col-md-12">
                        	<nav class="wow filter-nav">
                            	<ul class="cs-filter-menu pull-left">
                                    <li class="our-agents"><a href="#"><i class="icon-users5"></i><?php _e( 'Our Agents','directory' );?></a></li>
                                    <li><span><?php _e( 'Sort by:','directory' );?></span></li>
                                    <?php
                                    $first_user_role = 0;
                                    if( isset( $_GET['filter_action'] ) && $_GET['filter_action'] != '' ) {
                                        $filter_action = $_GET['filter_action'];	
                                    } else {
                                        $filter_action = '';	
                                    } 
                                    $all_tab = 1;
                                    if( isset( $var_pb_members_all_tab ) && $var_pb_members_all_tab == 'on' ) {
                                        $all_tab = '';
                                        if ( isset( $_GET['filter_action'] ) && $_GET['filter_action'] == 'all' ) {
                                            $activeClass	= 'active';
                                            $filter_action	= '';
                                        } else if ( ! isset( $_GET['filter_action'] ) ) {
                                            $activeClass	= 'active';
                                        } else {
                                           $activeClass		= '';
                                        }
                                        ?>
                                         <li class="<?php echo sanitize_html_class($activeClass);?>">
                                        	<a href="?<?php echo cs_allow_special_char($qrystr.'&amp;filter_action=all');?>"><?php _e('All','directory');?></a>
                                         </li>
										<?php
                                    }
                                    foreach( $directory_member_roles as $member_roles ) {
                                        $first_user_role++;
                                        if($first_user_role == 1 && $all_tab <> ''){
                                            $activeClass	= 'active';
                                            $filter_action = $member_roles;
                                        }
                                        if (isset($_GET['filter_action']) && $_GET['filter_action'] <> 'all' && $_GET['filter_action'] == $member_roles){
                                            $activeClass	= 'active';
                                            $filter_action = $_GET['filter_action'];
                                        } else {
                                           $activeClass	= '';
                                        }
                                    	?>
                                        <li class="<?php echo sanitize_html_class($activeClass);?>">
                                            <a href="?<?php echo cs_allow_special_char($qrystr.'&amp;filter_action='.$member_roles);?>">
												<?php echo ucfirst($member_roles); ?>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
								<?php  
                                if( isset( $var_pb_members_register_url ) and $var_pb_members_register_url <> '' ) {
                                $var_pb_members_register_color	= $var_pb_members_register_color	<> '' ? $var_pb_members_register_color	: $cs_theme_options['cs_theme_color'];
                                $var_pb_members_register_text	= $var_pb_members_register_text		<> '' ? $var_pb_members_register_text	: __('Become Agent', 'directory');
                                ?>
                                <a class="become-agent" href="<?php echo cs_allow_special_char($var_pb_members_register_url); ?>">
                                    <?php echo cs_allow_special_char($var_pb_members_register_text); ?>
                                    <i class="icon-arrow-circle-right"></i>
                                </a>
                                <?php
                        	}
	                      	?>
                        	</nav>
                        </div>
                        <?php
                        if( $var_pb_members_azfilterable == "on" ) {
							cs_list_nav();
						?>	
							<script>
                                jQuery(document).ready(function($) {
                                    jQuery('#cs-filterable').listnav({
                                         includeAll: true
                                    });
                                });
                            </script>
                            <div class="col-md-12">
                                <div class="listNav" id="cs-filterable-nav"></div>
                            </div>
                   		<?php
						} 
					}
					// Members Filteration Ends
                    if ( empty($_GET['page_id_all']) ){
                        $_GET['page_id_all'] = 1; $offset = 0;
                    } else {
                        $page_id_all = $_GET['page_id_all']-1;
                        $offset = $page_id_all*$var_pb_members_per_page;
                    }
 					// Members Query
                    if( isset($_GET['filter_action']) && $_GET['filter_action'] != '' && $_GET['filter_action'] != 'all' ){
                        $filter_action = $_GET['filter_action'];
                        $wp_user_query = new WP_User_Query( array( 
						'role' => $filter_action,
							'meta_query' => array(
								array(
									'key'     => 'user_profile_public',
									'value'   => '1',
									'compare' => '='
								)
							)
						) );
                        $users_count = $wp_user_query->get_total();
                        $wp_user_query = new WP_User_Query( 
							array( 
								'role' => $filter_action, 
								'number' => $var_pb_members_per_page, 
								'offset' => $offset,
								'meta_query' => array(
									array(
										'key'     => 'user_profile_public',
										'value'   => '1',
										'compare' => '='
									)
								)
							) 
						);
						} else {
                        $meta_query = array(
						   'key'		=> $wpdb->prefix . 'capabilities',
							'value'		=> '"(' . implode('|', array_map('preg_quote', $directory_member_roles)) . ')"',
							'compare'	=> 'REGEXP'
						);
                        $wp_user_query = new WP_User_Query(array(
                            'meta_query' => array(
								'relation' => 'AND',
								$meta_query,
								array(
									'key'     => 'user_profile_public',
									'value'   => '1',
									'compare' => '='
								)
							)
                        ));
                        $users_count = $wp_user_query->get_total();
						$wp_user_query = new WP_User_Query(array(
							'meta_query' => array(
								'relation' => 'AND',
								$meta_query,
								array(
									'key'     => 'user_profile_public',
									'value'   => '1',
									'compare' => '='
								)
							),
							'number' => $var_pb_members_per_page, 
							'offset' => $offset
                        ));
                    }
					
					// Check Section or page layout
					$cs_sidebarLayout = '';
					$cs_section_layout = '';
					$cs_agent_col_class = '';
					$cs_page_sidebar = false;
					if($var_pb_member_view == 'crousel'){
						$cs_agent_col_class = '';
					}
					
					if(isset($cs_xmlObject->sidebar_layout)) $cs_sidebarLayout = $cs_xmlObject->sidebar_layout->cs_page_layout;
					if(isset($column_attributes->cs_layout)){
						$cs_section_layout = $column_attributes->cs_layout;
						if ( $cs_section_layout == 'left' || $cs_section_layout == 'right' ) {
							$cs_page_sidebar = true;
						}
					}
					
					if ( $cs_sidebarLayout == 'left' || $cs_sidebarLayout == 'right') {
						$cs_page_sidebar = true;
					}
					
					if($cs_page_sidebar == true) {
						if($var_pb_member_view == 'crousel'){
							$cs_agent_col_class = 'cs-six-column';
						}
					}
					
					// Members Views
					$cs_memberObject = new MemberTemplates();
					
					if( $var_pb_member_view			== "default" ) {
						$cs_memberObject->cs_list_view( $wp_user_query, $cs_dummy_image, $var_pb_members_description );
					}
					else if( $var_pb_member_view	== "grid" ) {
						$cs_memberObject->cs_grid_view( $wp_user_query, $cs_dummy_image, $var_contact_fields);
					}
					else if( $var_pb_member_view	== "crousel" ) {
						$cs_memberObject->cs_simple_view( $wp_user_query, $cs_dummy_image, $var_contact_fields, $cs_agent_col_class);
					}
					// Members Pagination
					$pageqrystr = '';
					if ( $var_pb_members_pagination == "Show Pagination" and $users_count > $var_pb_members_per_page and $var_pb_members_per_page > 0 ) {
						if ( isset( $_GET['page_id']) )			$pageqrystr = "&page_id=".$_GET['page_id'];
						if ( isset( $_GET['filter_action']) )	$pageqrystr = "&filter_action=".$_GET['filter_action'];
						echo cs_pagination( $users_count, $var_pb_members_per_page, $pageqrystr );
					}
                 endif;
            }
            ?>
		</div>
  		<?php
		$memberspost_data = ob_get_clean();
		return $memberspost_data;
	}
	add_shortcode('cs_members', 'cs_members_listing_func');
}