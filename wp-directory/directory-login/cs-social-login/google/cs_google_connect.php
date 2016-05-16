<?php

global $cs_google_settings;
$cs_google_settings = get_option('cs_theme_options');

// set google unique id
if(!function_exists('cs_google_uniqid')){
    function cs_google_uniqid(){
        if(isset($_COOKIE['cs_google_uniqid'])){
            if(get_site_transient('n_'.$_COOKIE['cs_google_uniqid']) !== false){
                return $_COOKIE['cs_google_uniqid'];
            }
        }
        $_COOKIE['cs_google_uniqid'] = uniqid('nextend', true);
        setcookie('cs_google_uniqid', $_COOKIE['cs_google_uniqid'], time() + 3600, '/');
        set_site_transient('n_'.$_COOKIE['cs_google_uniqid'], 1, 3600);
        
        return $_COOKIE['cs_google_uniqid'];
    }
}

// google query var
function cs_google_add_query_var() {

  global $wp;
  $wp->add_query_var('editProfileRedirect');
  $wp->add_query_var('loginGoogle');
}
add_filter('init', 'cs_google_add_query_var');


add_action('parse_request', 'cs_google_login_compat');

// for google login compat
function cs_google_login_compat() {

  global $wp;
  if ($wp->request == 'loginGoogle' || isset($wp->query_vars['loginGoogle'])) {
    cs_google_login_action();
  }
}

/*
For login page
*/
add_action('login_init', 'cs_google_login');

function cs_google_login() {
  if (isset($_REQUEST['loginGoogle'])and $_REQUEST['loginGoogle'] == '1') {
    cs_google_login_action();
  }
}

// set google login action
function cs_google_login_action() {
	
	
  global $wp, $wpdb, $cs_google_settings;
  
 // include_once plugin_dir_url( __FILE__ ) . '/sdk/init.php';
 
 	include_once 'sdk/apiClient.php';
	//echo 'abcdefghijklmnopqrstuvwxyz';
	include_once 'sdk/contrib/apiOauth2Service.php';
	
	
	global $cs_theme_options;
	//$cs_theme_options = get_option('cs_theme_options');
	
	$client = new apiClient();
	$client->setClientId($cs_theme_options['cs_google_client_id']);
	$client->setClientSecret($cs_theme_options['cs_google_client_secret']);
	$client->setDeveloperKey($cs_theme_options['cs_google_api_key']);
	$client->setRedirectUri(cs_google_login_url());
	$client->setApprovalPrompt('auto');
	
	$oauth2 = new apiOauth2Service($client);
 
  if (isset($_GET['code'])) {
    if (isset($cs_google_settings['cs_google_login_redirect_url']) && $cs_google_settings['cs_google_login_redirect_url'] != '' && $cs_google_settings['cs_google_login_redirect_url'] != 'auto') {
      $_GET['redirect'] = $cs_google_settings['cs_google_login_redirect_url'];
    } else {
		$_GET['redirect'] = cs_google_login_url();
	}
    set_site_transient( cs_google_uniqid().'_google_r', $_GET['redirect'], 3600);
    $client->authenticate();
    $access_token = $client->getAccessToken();
    set_site_transient( cs_google_uniqid().'_google_at', $access_token, 3600);
    header('Location: ' . filter_var(cs_google_login_url() , FILTER_SANITIZE_URL));
    exit;
  }
  
  $access_token = get_site_transient( cs_google_uniqid().'_google_at');

  if ($access_token !== false) {
    $client->setAccessToken($access_token);
  }
  if (isset($_REQUEST['logout'])) {
    delete_site_transient( cs_google_uniqid().'_google_at');
    $client->revokeToken();
  }
  if ($client->getAccessToken()) {
    $u = $oauth2->userinfo->get();
	//var_dump($u);
    // The access token may have been updated lazily.
    set_site_transient( cs_google_uniqid().'_google_at', $client->getAccessToken(), 3600);

    $email = filter_var($u['email'], FILTER_SANITIZE_EMAIL);
    if (!is_user_logged_in()) {
	
	 $ID = email_exists($email);	
		
      if ($ID == NULL) { // Register

        
        if ($ID == false) { // Real register

          //require_once get_template_directory() . WPINC . '/registration.php';
          $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
          if (!isset($cs_google_settings['google_user_prefix'])) $cs_google_settings['google_user_prefix'] = 'Google - ';
          $sanitized_user_login = sanitize_user($cs_google_settings['google_user_prefix'] . $u['name']);
          if (!validate_username($sanitized_user_login)) {
            $sanitized_user_login = sanitize_user('google' . $user_profile['id']);
          }
          $defaul_user_name = $sanitized_user_login;
          $i = 1;
          while (username_exists($sanitized_user_login)) {
            $sanitized_user_login = $defaul_user_name . $i;
            $i++;
          }
          $ID = wp_create_user($sanitized_user_login, $random_password, $email);
          if (!is_wp_error($ID)) {
			# Add Role
			$new_user = new WP_User( $ID );
			$new_user->set_role( 'subscriber' );	
            
			# Notification
			wp_new_user_notification($ID, $random_password);
            $user_info = get_userdata($ID);
            
			wp_update_user(array(
              'ID' => $ID,
              'display_name' 	=> $u['name'],
              'first_name'   	=> $u['given_name'],
              'last_name' 		=> $u['family_name'],
              'googleplus' 		=> $u['link']
            ));
            	update_user_meta($ID, 'cs_google_default_password', $user_info->user_pass);
            	do_action('cs_google_user_registered', $ID, $u, $oauth2);
				update_user_meta($ID, 'cs_user_registered', 'google');
          } else {
            return;
          }
        }
        
        if (isset($cs_google_settings['google_redirect_reg']) && $cs_google_settings['google_redirect_reg'] != '' && $cs_google_settings['google_redirect_reg'] != 'auto') {
          set_site_transient( cs_google_uniqid().'_google_r', $cs_google_settings['google_redirect_reg'], 3600);
        }
      }
      if ($ID) { 

        $secure_cookie = is_ssl();
        $secure_cookie = apply_filters('secure_signon_cookie', $secure_cookie, array());
        global $auth_secure_cookie; // XXX ugly hack to pass this to wp_authenticate_cookie

        $auth_secure_cookie = $secure_cookie;
        wp_set_auth_cookie($ID, true, $secure_cookie);
        $user_info = get_userdata($ID);
        do_action('wp_login', $user_info->user_login, $user_info);
        //do_action('cs_google_user_logged_in', $ID, $u, $oauth2);
        update_user_meta($ID, 'google_profile_picture', $u['picture']);
      }
    } else {
    
        $user_info = wp_get_current_user();
        set_site_transient($user_info->ID.'_cs_google_admin_notice',__('This Google profile is already linked with other account. Linking process failed!', 'directory'), 3600);
      
    }
  } else {
    if (isset($cs_google_settings['google_redirect']) && $cs_google_settings['google_redirect'] != '' && $cs_google_settings['google_redirect'] != 'auto') {
      $_GET['redirect'] = $cs_google_settings['google_redirect'];
    }
    if (isset($_GET['redirect'])) {
      set_site_transient( cs_google_uniqid().'_google_r', $_GET['redirect'], 3600);
    }
    
    $redirect = get_site_transient( cs_google_uniqid().'_google_r');
    
    if ($redirect || $redirect == cs_google_login_url()) {
      $redirect = site_url();
      set_site_transient( cs_google_uniqid().'_google_r', $redirect, 3600);
    }
    header('LOCATION: ' . $client->createAuthUrl());
    exit;
  }
  cs_google_redirect();
}

// insert google avatar
function cs_google_insert_avatar($avatar = '', $id_or_email, $size = 96, $default = '', $alt = false) {

  $id = 0;
  if (is_numeric($id_or_email)) {
    $id = $id_or_email;
  } else if (is_string($id_or_email)) {
    $u = get_user_by('email', $id_or_email);
    $id = $u->id;
  } else if (is_object($id_or_email)) {
    $id = $id_or_email->user_id;
  }
  if ($id == 0) return $avatar;
  $pic = get_user_meta($id, 'google_profile_picture', true);
  if (!$pic || $pic == '') return $avatar;
  $avatar = preg_replace('/src=("|\').*?("|\')/i', 'src=\'' . $pic . '\'', $avatar);
  return $avatar;
}

add_filter('bp_core_fetch_avatar', 'cs_google_bp_insert_avatar', 3, 5);

// insert google bp avatar
function cs_google_bp_insert_avatar($avatar = '', $params, $id) {
    if(!is_numeric($id) || strpos($avatar, 'gravatar') === false) return $avatar;
    $pic = get_user_meta($id, 'google_profile_picture', true);
    if (!$pic || $pic == '') return $avatar;
    $avatar = preg_replace('/src=("|\').*?("|\')/i', 'src=\'' . $pic . '\'', $avatar);
    return $avatar;
}




/* -----------------------------------------------------------------------------
Miscellaneous functions
----------------------------------------------------------------------------- */

// google sign up button
function cs_google_sign_button() {

  global $cs_google_settings;
  $cs_google_settings['google_login_button'] = 'Google';
  return '<a href="' . cs_google_login_url() . (isset($_GET['redirect_to']) ? '&redirect=' . $_GET['redirect_to'] : '') . '" rel="nofollow">' . $cs_google_settings['google_login_button'] . '</a><br />';
}

// google link button
function cs_google_link_button() {

  global $cs_google_settings;
  $cs_google_settings['google_login_button'] = 'Google';
  $images_url = wp_directory::plugin_url().'directory-login/cs-social-login/media/img/';
  return '<a href="' . cs_google_login_url() . '&redirect=' . cs_google_curPageURL() . '"><img alt="Twitter" src="'.$images_url.'google_32.png" /></a><br />';
}
add_action('login_form', 'cs_google_link_button');
add_action('register_form', 'cs_google_link_button');


// google page url
function cs_google_curPageURL() {

  $pageURL = 'http';
  if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
    $pageURL.= "s";
  }
  $pageURL.= "://";
  if ($_SERVER["SERVER_PORT"] != "80") {
    $pageURL.= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
  } else {
    $pageURL.= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
  }
  return $pageURL;
}

// custom google login
function cs_google_login_url() {

  return site_url('wp-login.php') . '?loginGoogle=1';
}

// google redirect URL
function cs_google_redirect() {
  
  $redirect = get_site_transient( cs_google_uniqid().'_google_r');
  
  if (!$redirect || $redirect == '' || $redirect == cs_google_login_url()) {
    if (isset($_GET['redirect'])) {
      $redirect = $_GET['redirect'];
    } else {
      $redirect = site_url();
    }
  }
  header('LOCATION: ' . $redirect);
  delete_site_transient( cs_google_uniqid().'_google_r');
  exit;
}

//google edit profile url
function cs_google_edit_profile_redirect() {

  global $wp;
  if (isset($wp->query_vars['editProfileRedirect'])) {
    if (function_exists('bp_loggedin_user_domain')) {
      header('LOCATION: ' . bp_loggedin_user_domain() . 'profile/edit/group/1/');
    } else {
      header('LOCATION: ' . self_admin_url('profile.php'));
    }
    exit;
  }
}
add_action('parse_request', 'cs_google_edit_profile_redirect');

// google query
function cs_google_jquery() {

  wp_enqueue_script('jquery');
}
add_action('login_form_login', 'cs_google_jquery');
add_action('login_form_register', 'cs_google_jquery');

/*
Session notices used in the profile settings
*/

function cs_google_admin_notice() {
  $user_info = wp_get_current_user();
  $notice = get_site_transient($user_info->ID.'_cs_google_admin_notice');
  if ($notice !== false) {
    echo '<div class="updated">
       <p>' . $notice . '</p>
    </div>';
    delete_site_transient($user_info->ID.'_cs_google_admin_notice');
  }
}
add_action('admin_notices', 'cs_google_admin_notice');
