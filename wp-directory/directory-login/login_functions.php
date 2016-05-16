<?php

//=====================================================================
// User Login Ajax Function
//=====================================================================

if ( ! function_exists( 'ajax_login' ) ) :
function ajax_login(){
	global $cs_theme_options;
	$credentials = array();
	$credentials['user_login'] = esc_sql($_POST['user_login']);
	$credentials['user_password'] = esc_sql($_POST['user_pass']);
	
	if ( isset($_POST['rememberme'])){
		$remember  = esc_sql($_POST['rememberme']);
	} else {
		$remember  = '';
	}

	if($remember) {
		$credentials['remember'] = true;
	} else {
		$credentials['remember'] = false;
	}
	
	if($credentials['user_login'] == ''){
		echo json_encode(array('loggedin'=>false, 'message'=>__('User name should not be empty.','directory')));
		exit();
	}elseif($credentials['user_password'] == ''){
		echo json_encode(array('loggedin'=>false, 'message'=>__('Password should not be empty.','directory')));
		exit();
	}else{
 		$status = wp_signon( $credentials, false );
		if ( is_wp_error($status) ){
			echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.','directory')));
		} else {
			if(isset($_POST['redirect_to_ad']) and $_POST['redirect_to_ad'] == '1'){
				$cs_user_name = $_POST['user_login'];
				$cs_login_user = get_user_by( 'login', $cs_user_name );
				$cs_page_id = isset($cs_theme_options['cs_dashboard']) ? $cs_theme_options['cs_dashboard'] : '';
				$cs_redirect_url = cs_user_admin_profile_link($cs_page_id, 'add-directory', $cs_login_user->ID);
				echo json_encode(array('redirecturl'=> $cs_redirect_url,'loggedin'=>true, 'message'=>__('Login Successfully...','directory')));
			}
			else{
				echo json_encode(array('redirecturl'=> $_POST['redirect_to'],'loggedin'=>true, 'message'=>__('Login Successfully...','directory')));
			}
		}
	}

    die();
}
endif;
add_action('wp_ajax_ajax_login', 'ajax_login');
add_action('wp_ajax_nopriv_ajax_login', 'ajax_login');
?>