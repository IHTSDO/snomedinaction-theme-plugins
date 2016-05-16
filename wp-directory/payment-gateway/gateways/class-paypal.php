<?php
/**
 *  File Type: Paypal Gateway
 *
 */

if( ! class_exists( 'CS_PAYPAL_GATEWAY' ) ) {
	class CS_PAYPAL_GATEWAY extends CS_PAYMENTS {
		
		public function __construct()
		{
			global $cs_gateway_options;
			
			$cs_gateway_options	= get_option('cs_theme_options');
			
			if( isset( $cs_gateway_options['cs_paypal_sandbox'] ) && $cs_gateway_options['cs_paypal_sandbox'] == 'on'){
				$this->gateway_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
			} else {
				$this->gateway_url = "https://www.paypal.com/cgi-bin/webscr";
			}
			
			$ipn_url = wp_directory::plugin_url().'payment-gateway/listner.php';
			$this->listner_url	= isset($cs_gateway_options['dir_paypal_ipn_url']) ? $cs_gateway_options['dir_paypal_ipn_url'] : $ipn_url;
			
		}
		
		public function settings(){
			global $post;
			
			$on_off_option =  array("show" => "on","hide"=>"off");
			
			$cs_settings[] = array("name" => "Paypal Settings(Default)",
											"id" => "tab-heading-options",
											"std" => "Paypal Settings(Default)",
											"type" => "section",
											"options" => ""
										);
			$cs_settings[] = array( "name" 		=> "Custom Logo",
									"desc" 		=> "",
									"hint_text" => "",
									"id" 		=> "cs_paypal_gateway_logo",
									"std" 		=>  wp_directory::plugin_url().'payment-gateway/images/paypal.png',
									"display"	=>"none",
									"type" 		=> "upload logo"
								);
			$cs_settings[] = array( "name" 		=> "Default Status",
									"desc" 				=> "",
									"hint_text" 		=> "Show/Hide Gateway On Front End.",
									"id" 				=> "cs_paypal_gateway_status",
									"std" 				=> "on",
									"type" 				=> "checkbox",
									"options" 			=> $on_off_option
								); 				
			
			$cs_settings[] = array( "name" 		=> "Paypal Sandbox",
										"desc" 				=> "",
										"hint_text" 		=> "Only for Developer use.",
										"id" 				=> "cs_paypal_sandbox",
										"std" 				=> "on",
										"type" 				=> "checkbox",
										"options" 			=> $on_off_option
									);     
                            
			$cs_settings[] = array( "name" 	=> "Paypal Business Email",
								"desc" 		=> "",
								"hint_text" => "",
								"id" 		=> "paypal_email",
								"std" 		=> "",
								"type" 		=> "text"
							);
							
			$ipn_url = wp_directory::plugin_url().'payment-gateway/listner.php';
			$cs_settings[] = array( "name" 	=> "Paypal Ipn URL",
								"desc" 		=> $ipn_url,
								"hint_text" => "Do not edit this URL.",
								"id"		=> "dir_paypal_ipn_url",
								"std" 		=> $ipn_url,
								"type" 		=> "text"
							);
						
			return $cs_settings;
		}
		
		public function cs_proress_request( $params = '' ){
			global $post, $cs_gateway_options;
			extract( $params );

			$output					= '';
			$business_email 		= $cs_gateway_options['paypal_email'];

			$currency				= isset( $cs_gateway_options['cs_currency_type'] ) && $cs_gateway_options['cs_currency_type'] !='' ? $cs_gateway_options['cs_currency_type'] : 'USD';
			
			$user_ID 	 = get_current_user_id();
			//$currency	 = 'USD';
			
			$output .= '<form name="PayPalForm" id="direcotry-paypal-form" action="'.$this->gateway_url.'" method="post">  
							<input type="hidden" name="cmd" value="_xclick">  
							<input type="hidden" name="business" value="'.sanitize_email($business_email).'">
							<input type="hidden" name="amount" value="'.$cs_price.'">
							<input type="hidden" name="item_name" value="'.sanitize_text_field(get_the_title($cs_post_id)).'"> 
							<input type="hidden" name="currency_code" value="'.$currency.'">
							<input type="hidden" name="item_number" value="'.sanitize_text_field($cs_post_id).'">  
							<input name="cancel_return" value="'.esc_url(get_permalink($cs_post_id)).'" type="hidden">  
							<input type="hidden" name="no_note" value="1">  
							<input type="hidden" name="notify_url" value="'.sanitize_text_field( $this->listner_url ).'">
							<input type="hidden" name="lc">
							<input type="hidden" name="rm" value="2">
							<input type="hidden" name="custom" value="'.sanitize_text_field($user_ID.'_'.$cs_package.'_'.$cs_featured).'">  
							<input type="hidden" name="return" value="'.esc_url(get_permalink($cs_post_id)).'">  
						</form>';
							
			echo cs_allow_special_char( $output );
			echo '<script>
				  	jQuery("#direcotry-paypal-form").submit();
				  </script>';
			die;						
		}	
	}
}


