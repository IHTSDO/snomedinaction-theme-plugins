<?php

	global $cs_theme_options;
	
	require_once 'facebook.php';
	$cs_theme_option = get_option('cs_theme_options');
	$client_id = $cs_theme_option['cs_facebook_app_id'];
	$secret_key = $cs_theme_option['cs_facebook_secret'];

	
	if (isset($_GET['code'])) {
		$code = $_GET['code'];
		parse_str( cs_http_get_contents( "https://graph.facebook.com/oauth/access_token?" .
			'client_id=' . $client_id . '&redirect_uri=' . home_url( 'index.php?social-login=facebook-callback' ) .
			'&client_secret=' . $secret_key .
			'&code=' . urlencode( $code ) ) );
		$signature = cs_social_generate_signature($access_token);
		do_action( 'social_login_before_register_facebook', $code, $signature, $access_token );
		?>
		<html>
			<head>
				<script>
					function init() {
						window.opener.wp_social_login({'action' : 'social_login', 'social_login_provider' : 'facebook',
							'social_login_signature' : '<?php echo esc_attr($signature) ?>',
							'social_login_access_token' : '<?php echo esc_attr($access_token) ?>'});
	
						window.close();
					}
				</script>
			</head>
			<body onLoad="init();"></body>
		</html>
		<?php
	} else {
		//require_once get_template_directory() . '/include/theme-components/cs-social-login/twitter/connect.php';
		$redirect_uri = urlencode(plugin_dir_url( __FILE__ ) . 'callback.php');
		wp_redirect('https://graph.facebook.com/oauth/authorize?client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&scope=email');
	}