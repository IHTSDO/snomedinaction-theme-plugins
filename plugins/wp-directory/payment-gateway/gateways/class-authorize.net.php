<?php
/**
 *  File Type: Authorize.net Gateway

 */
 
if( ! class_exists( 'CS_AUTHORIZEDOTNET_GATEWAY' ) ) {
	class CS_AUTHORIZEDOTNET_GATEWAY  extends CS_PAYMENTS{
		
		public function __construct()
		{
			// Do Something
			global $cs_gateway_options;
			$cs_gateway_options	= get_option('cs_theme_options');
			
			if( isset( $cs_gateway_options['cs_authorizenet_sandbox'] ) && $cs_gateway_options['cs_authorizenet_sandbox'] == 'on'){
				$this->gateway_url = "https://test.authorize.net/gateway/transact.dll";
			} else {
				$this->gateway_url = "https://secure.authorize.net/gateway/transact.dll";
			}
			
			$ipn_url = wp_directory::plugin_url().'payment-gateway/listner.php';
			$this->listner_url	= isset($cs_gateway_options['dir_authorizenet_ipn_url']) ?  $cs_gateway_options['dir_authorizenet_ipn_url'] : $ipn_url;
			
		}
		
		public function settings(){
			global $post;
			
			$on_off_option =  array("show" => "on","hide"=>"off"); 
			
			$cs_settings[] = array("name" => "Authorize.net Settings",
											"id" => "tab-heading-options",
											"std" => "Authorize.net Settings",
											"type" => "section",
											"options" => ""
										);
			$cs_settings[] = array( "name" 		=> "Custom Logo",
									"desc" 		=> "",
									"hint_text" => "",
									"id" 		=> "cs_authorizedotnet_gateway_logo",
									"std" 		=>  wp_directory::plugin_url().'payment-gateway/images/athorizedotnet.png',
									"display"	=>"none",
									"type" 		=> "upload logo"
								);
								
			$cs_settings[] = array( "name" 		=> "Default Status",
                            "desc" 				=> "",
                            "hint_text" 		=> "Show/Hide Gateway On Front End.",
                            "id" 				=> "cs_authorizedotnet_gateway_status",
                            "std" 				=> "on",
                            "type" 				=> "checkbox",
                            "options" 			=> $on_off_option
                        ); 
						
			$cs_settings[] = array( "name" 		=> "Authorize.net Sandbox",
                            "desc" 				=> "",
                            "hint_text" 		=> "Only for Developer use.",
                            "id" 				=> "cs_authorizenet_sandbox",
                            "std" 				=> "on",
                            "type" 				=> "checkbox",
                            "options" 			=> $on_off_option
                        );    
						               
			$cs_settings[] = array( "name" 	=> "Login ID",
								"desc" 		=> "",
								"hint_text" => "This is API Login ID",
								"id" 		=>   "authorizenet_login",
								"std" 		=> "",
								"type" 		=> "text"
							);
			
			$cs_settings[] = array( "name" 	=> "Transaction Key",
								"desc" 		=> "",
								"hint_text" => "API Transaction Key",
								"id" 		=> "authorizenet_transection_key",
								"std" 		=> "",
								"type" 		=> "text"
							);
							
			$ipn_url = wp_directory::plugin_url().'payment-gateway/listner.php';
			$cs_settings[] = array( "name" 	=> "Authorize.net Ipn URL",
								"desc" 		=> $ipn_url,
								"hint_text" => "Do not edit this URL.",
								"id"		=>   "dir_authorizenet_ipn_url",
								"std" 		=> $ipn_url,
								"type" 		=> "text"
							);
						
			return $cs_settings;
		}
		
		public function cs_proress_request( $params = '' ){
			global $post, $cs_gateway_options;
			extract( $params );
			$output					= '';
			
			$cs_login 				= $cs_gateway_options['authorizenet_login'];
			$transection_key 		= $cs_gateway_options['authorizenet_transection_key'];
			
			$timeStamp	= time();
			$sequence	= rand(1, 1000);
			
			$currency_type				= isset( $cs_gateway_options['cs_currency_type'] ) && $cs_gateway_options['cs_currency_type'] !='' ? $cs_gateway_options['cs_currency_type'] : 'USD';
			
			if( phpversion() >= '5.1.2' )
				{ $fingerprint = hash_hmac("md5", $cs_login . "^" . $sequence . "^" . $timeStamp . "^" . $cs_price . "^". $currency_type, $transection_key); }
			else 
				{ $fingerprint = bin2hex(mhash(MHASH_MD5, $cs_login . "^" . $sequence . "^" . $timeStamp . "^" . $cs_price . "^". $currency_type, $transection_key)); }
			
			
			$cs_packages_options 		= get_option('cs_packages_options');
			//$currency	 = 'USD';
			$user_ID = get_current_user_id();
			
			if( isset( $cs_packages_options[$cs_package]['package_title'] ) ) {
				$package_title	= $cs_packages_options[$cs_package]['package_title'];
			} else{
				$package_title	= 'Free - Unlimited';
			}
			
			
			
			$output .= '<form name="AuthorizeForm" id="direcotry-authorize-form" action="'.$this->gateway_url.'" method="post">  
							<input type="hidden" name="x_login" value="'.$cs_login.'">
							<input type="hidden" name="x_type" value="AUTH_CAPTURE"/>
							<input type="hidden" name="x_amount" value="'.$cs_price.'">
							<input type="hidden" name="x_fp_sequence" value="'.$sequence.'" />
							<input type="hidden" name="x_fp_timestamp" value="'.$timeStamp.'" />
							<input type="hidden" name="x_fp_hash" value="'.$fingerprint.'" />
							<input type="hidden" name="x_show_form" value="PAYMENT_FORM" />
							<input type="hidden" name="x_invoice_num" value="ORDER-'.sanitize_text_field($cs_post_id).'">
							<input type="hidden" name="x_po_num" value="'.sanitize_text_field($cs_post_id.'_'.$currency_type).'">
							<input type="hidden" name="x_cust_id" value="'.sanitize_text_field($user_ID.'_'.$cs_package.'_'.$cs_featured).'"/> 
							
							<input type="hidden" name="x_first_name" value="'.get_the_author_meta('first_name' ,$user_ID).'"> 
							<input type="hidden" name="x_last_name" value="'.get_the_author_meta('last_name' ,$user_ID).'"> 
							<input type="hidden" name="x_address" value="'.get_the_author_meta( 'address' ,$user_ID).'"> 
							<input type="hidden" name="x_fax" value="'.get_the_author_meta('fax' ,$user_ID).'"> 
							<input type="hidden" name="x_email" value="'.get_the_author_meta('user_email' ,$user_ID).'"> 
							
							<input type="hidden" name="x_description" value="'.$package_title.'"> 
							<input type="hidden" name="x_currency_code" value="'.$currency_type.'" />	
							<input type="hidden" name="x_cancel_url" value="'.esc_url(get_permalink($cs_post_id)).'" />
							<input type="hidden" name="x_cancel_url_text" value="Cancel Order" />
							<input type="hidden" name="x_relay_response" value="TRUE" />
							<input type="hidden" name="x_relay_url" value="'.sanitize_text_field( $this->listner_url ).'"/> 
							<input type="hidden" name="x_test_request" value="false"/>
						</form>';
				echo cs_allow_special_char( $output );
				echo '<script>
				    	jQuery("#direcotry-authorize-form").submit();
				      </script>';
			die;						
		}
	}
}