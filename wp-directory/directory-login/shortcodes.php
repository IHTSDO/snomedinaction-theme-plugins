<?php

//=====================================================================
// User Login Ajax Function
//=====================================================================

if ( ! function_exists( 'cs_get_login_nav_shortocde' ) ) {
	function cs_get_login_nav_shortocde(){
		//$cs_theme_options		=  get_option('cs_theme_options');
		global $cs_theme_options;
		$cs_login_options		= isset($cs_theme_options['cs_login_options']) ? $cs_theme_options['cs_login_options'] : '';
		$cs_user_login_method	= isset($cs_theme_options['cs_user_login_method']) ? $cs_theme_options['cs_user_login_method'] : '';
		$cs_login_btn_position	= isset($cs_theme_options['cs_login_button_position']) ? $cs_theme_options['cs_login_button_position'] : '';
		
		if(isset($cs_login_options) and $cs_login_options=='on'){ 
		global $current_user;
		$uid= $current_user->ID;
		
		$isRegistrationOn = get_option('users_can_register');
		$isRegistrationOnClass	= '';
		
		if ( !$isRegistrationOn ) {
			$isRegistrationOnClass = 'no_icon';
		}
			if ( is_user_logged_in() ) {
				
				$cs_dummy_image = 'dummy.jpg';
				$plugin_url		= plugins_url();
				$cs_dummy_image = $plugin_url.'/wp-directory/assets/images/dummy.jpg';
				$cs_display_image = '';
				$cs_display_image = cs_get_user_avatar(1 ,$uid);
				if( $cs_display_image <> ''){
					$cs_dp_img = '<img src="'.esc_url( $cs_display_image ).'" />';
                } else {
					$cs_dp_img = '<img src="'.esc_url($cs_dummy_image).'" alt="" />';
                }
				$args = array(
					'posts_per_page'	=> "-1",
					'post_type'			=> 'directory',
					'post_status'		=> array('publish', 'private'),
					'meta_key'			=> 'directory_organizer',
					'meta_value'		=> $uid,
					'meta_compare'		=> "=",
					'orderby'			=> 'ID',
					'order'				=> 'ASC',
				);
				$custom_query_count = new WP_Query($args);
				$cs_count_ads = $custom_query_count->post_count;
				if( $cs_login_btn_position == 'Top Strip' ) {
					echo '<a class="cs-user-login"><i class="icon-user2"></i>'.$current_user->display_name.'</a>';
				}
				else{
					echo '<a class="cs-user-login"><span class="cs-count-ads">'.$cs_count_ads.'</span>'.$cs_dp_img.'<i class="icon-angle-down"></i></a>';
				}
			}else{
				if( $cs_login_btn_position == 'Top Strip' ) {
					echo '<a href="#" class="cs-user"><i class="icon-user2"></i>'.__('Login / Register','directory').'</a>';
				}else{
					if($cs_user_login_method == 'Dropdown Menu'){
						echo '<a class="cs-user-login"><i class="icon-user2"></i><i class="icon-angle-down"></i></a>';
					}
					else{
						echo '<a href="#" class="cs-user"><i class="icon-user2"></i></a>';
					}
				}
			}
			$cs_login_message = __('Login to add new listings.','directory');
			cs_topmenu_login_section();
		}
		
	}
}
add_shortcode('cs_get_login_nav', 'cs_get_login_nav_shortocde');

//======================================================================
// Register Shortcode
//======================================================================
if (!function_exists('cs_register_shortcode')) {
	function cs_register_shortcode($atts, $content = "") {
		global $wpdb, $cs_theme_options;
		$defaults = array('column_size'=>'1/1','register_title'=>'','register_text'=>'','register_role' => 'contributor','cs_register_class'=>'','cs_register_animation'=>'');
		extract( shortcode_atts( $defaults, $atts ) );
		$column_class  = cs_custom_column_class($column_size);
		
		$user_disable_text = __('User Registration is disabled','directory');
		
		$output = '';
 		
		$rand_id = rand(5,99999);
		
		if ( is_user_logged_in() ){
			$output .= 
			'<div class="registor-log"> 
				<a href="'.wp_logout_url("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']).'">
					<i class="icon-sign-out"></i>
				</a>
				<h2 class="warning">'.__("You must be logged out for registration.", "directory").'</h2>
			</div>';
		}else{
		
		$role = $register_role;
		
		$output .='
		  <div class="col-md-6 register-page '.$cs_register_class.' '.$cs_register_animation.'">
            <section class="cs-login-form" style="display:block;">
                <div class="login-from login-form-id-'.$rand_id.'">
                    <h2>'.__('Sign In','directory').'</h2>
                    <form method="post" class="wp-user-form webkit" id="ControlForm_'.$rand_id.'">
                        <fieldset>
                            <p> 
                            <span class="input-icon"><i class="icon-user2"></i>
                                <input type="text" name="user_login" size="20" id="user_login" tabindex="11" onfocus="if(this.value ==\'UserName\') { this.value = \'\'; }" onblur="if(this.value == \'\') { this.value =\'UserName\'; }" value="UserName" />
                            </span> 
                            </p>
                            <p> 
                            <span class="input-icon"><i class="icon-unlock-alt"></i>
                            <input type="password" name="user_pass" size="20" id="user_pass" tabindex="12" onfocus="if(this.value ==\'User Name\') { this.value = \'\'; }" onblur="if(this.value == \'\') { this.value =\'User Name\'; }" value="'.__('User Name','directory').'" />
							
                            </span> 
                            </p>
                            <p> 
                            <input name="rememberme" value="forever" type="checkbox">
                            '.__('Remember me','directory').'
                            <span class="status status-message" style="display:none"></span>
                            </p>
                            <p>
                            <input type="button" name="user-submit" class="user-submit backcolr"  value="'.__('Log in','directory').'" onclick="javascript:cs_user_authentication(\''.admin_url("admin-ajax.php").'\',\''.$rand_id.'\')" />
                            <input type="hidden" name="redirect_to" value="'.esc_url(get_permalink()).'" />
                            <input type="hidden" name="user-cookie" value="1" />
                            <input type="hidden" value="ajax_login" name="action">
                            <input type="hidden" name="login" value="login" />
                            </p>
                        </fieldset>
                    </form>
                </div>
                <h6 class="forget-link">
                <a href="'.wp_lostpassword_url( ).'">
                '.__('Forget Password','directory').'
                </a>
                </h6>';
				ob_start();
                if( class_exists( 'wp_directory' ) ){ $output .= do_action('login_form'); }
				$output .= ob_get_clean();
			$output .= '
            </section>
		   </div>';
		   
		   $isRegistrationOn = get_option('users_can_register');
		   if ( $isRegistrationOn ) {
           
           $output .='
		   <div class="col-md-6 register-page '.$cs_register_class.' '.$cs_register_animation.'">
				
				<div class="cs-user-register">
				  <h2>'.$register_title.'</h2>
				  <form method="post" class="wp-user-form" id="wp_signup_form_'.$rand_id.'" enctype="multipart/form-data">
				
					<ul class="upload-file">
					  <li>
					  <i class="icon-user2"></i>
						<input type="text" name="user_login" size="20" tabindex="101" onfocus="if(this.value ==\'UserName\') { this.value = \'\'; }" onblur="if(this.value == \'\') { this.value =\'UserName\'; }" value="UserName" />
					  </li>
					  <li>
					  <i class="icon-envelope4"></i>
						<input type="text" name="user_email" size="25" id="user_email" tabindex="101" onfocus="if(this.value ==\'Email\') { this.value = \'\'; }" onblur="if(this.value == \'\') { this.value =\'Email\'; }" value="Email" />
					  </li>
					</ul>
					<ul class="upload-file">
					  <li>';
					  ob_start();
					  $output .= do_action('register_form');
					  $output .= ob_get_clean();
					  $output .= '
						<input type="button" name="user-submit" id="submitbtn" value="'.__('Sign Up','directory').'" class="user-submit" tabindex="103" onclick="javascript:cs_registration_validation(\''.admin_url("admin-ajax.php").'\',\''.$rand_id.'\')" />
						<div id="result_'.$rand_id.'" class="status-message"><p class="status"></p></div>
						<input type="hidden" name="role" value="'.$role.'" />
						<input type="hidden" name="action" value="cs_registration_validation" />
					  </li>
					</ul>
				  </form>
				  <div class="register_content">'.do_shortcode($content.$register_text).'</div>
				</div>
			</div>';
		  } else {
          $output .='
			<div class="col-md-6 register-page">
				 <div class="cs-user-register">
					  <div class="cs-section-title">
						<h2>'. __('Register', 'directory').'</h2>
					  </div>
					  <p>'.$user_disable_text.'</p>
				 </div>
		   </div>';
           
		  }
		}
			
		return $output;
	}
	add_shortcode('cs_register', 'cs_register_shortcode');
}
?>