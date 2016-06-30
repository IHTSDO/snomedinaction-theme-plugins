<?php
/**
 *  File Type: Skrill- Monery Booker Gateway
 *
 */
 
if( ! class_exists( 'CS_SKRILL_GATEWAY' ) ) {
	class CS_SKRILL_GATEWAY extends CS_PAYMENTS {
		
		public function __construct()
		{
			global $cs_gateway_options;
			$cs_gateway_options	= get_option('cs_theme_options');
			
			$this->gateway_url  = "https://www.moneybookers.com/app/payment.pl";
			$ipn_url = wp_directory::plugin_url().'payment-gateway/listner.php';
			$this->listner_url	= isset($cs_gateway_options['cs_skrill_ipn_url']) ? $cs_gateway_options['cs_skrill_ipn_url'] : $ipn_url;

		}
		
		public function settings(){
			global $post;
			
			$on_off_option =  array("show" => "on","hide"=>"off"); 
			
			$cs_settings[] = array("name" => "Skrill-MoneyBooker Settings",
											"id" => "tab-heading-options",
											"std" => "Skrill-MoneyBooker Settings",
											"type" => "section",
											"options" => ""
										);
										
			$cs_settings[] = array( "name" 		=> "Custom Logo",
									"desc" 		=> "",
									"hint_text" => "",
									"id" 		=> "cs_skrill_gateway_logo",
									"std" 		=> wp_directory::plugin_url().'payment-gateway/images/skrill.png',
									"display"	=> "none",
									"type" 		=> "upload logo"
								);
								
			$cs_settings[] = array( "name" 		=> "Default Status",
                            "desc" 				=> "",
                            "hint_text" 		=> "Show/Hide Gateway On Front End.",
                            "id" 				=> "cs_skrill_gateway_status",
                            "std" 				=> "on",
                            "type" 				=> "checkbox",
                            "options" 			=> $on_off_option
                        );           
			$cs_settings[] = array( "name" 	=> "Skrill-MoneryBooker Business Email",
								"desc" 		=> "",
								"hint_text" => "",
								"id" 		=>   "skrill_email",
								"std" 		=> "",
								"type" 		=> "text"
							);
							
			$ipn_url = wp_directory::plugin_url().'payment-gateway/listner.php';
			$cs_settings[] = array( "name" 	=> "Skrill-MoneryBooker Ipn URL",
								"desc" 		=> $ipn_url,
								"hint_text" => "Do not edit this URL.",
								"id"		=> "cs_skrill_ipn_url",
								"std" 		=> $ipn_url,
								"type" 		=> "text"
							);
						
			return $cs_settings;
		}
		
		public function cs_proress_request( $params = '' ){
			global $post, $cs_gateway_options;
			extract( $params );
			
			$output					= '';
			$business_email 		= $cs_gateway_options['skrill_email'];
			
			$currency				= isset( $cs_gateway_options['cs_currency_type'] ) && $cs_gateway_options['cs_currency_type'] !='' ? $cs_gateway_options['cs_currency_type'] : 'USD';
			$cs_packages_options 		= get_option('cs_packages_options');
			$user_ID 	 = get_current_user_id();
			//$currency	 = 'USD';
			
			if( isset( $cs_packages_options[$cs_package]['package_title'] ) ) {
				$package_title	= $cs_packages_options[$cs_package]['package_title'];
			} else{
				$package_title	= 'Free - Unlimited';
			}
			
			$output .= '<form name="SkrillForm" id="direcotry-skrill-form" action="'.$this->gateway_url.'" method="post">  
							<input type="hidden" name="pay_to_email" value="'.sanitize_email($business_email).'">
							<input type="hidden" name="amount" value="'.$cs_price.'">
							<input type="hidden" value="EN" name="language">
							<input type="hidden" value="'.$currency.'" name="currency">
							<input type="hidden" name="detail1_description" value="Package : "> 
							<input type="hidden" name="detail1_text" value="'.$package_title.'">
							<input type="hidden" name="detail2_description" value="Ad Title : "> 
							<input type="hidden" name="detail2_text" value="'.sanitize_text_field(get_the_title($cs_post_id)).'">
							<input type="hidden" name="detail3_description" value="Ad ID : ">
							<input type="hidden" name="detail3_text" value="'.sanitize_text_field($cs_post_id).'">
							<input name="cancel_url" value="'.esc_url(get_permalink($cs_post_id)).'" type="hidden">  
							<input type="hidden" name="status_url" value="'.sanitize_text_field( $this->listner_url ).'">
							<input type="hidden" name="transaction_id" value="'.sanitize_text_field($user_ID.'_'.$cs_package.'_'.$cs_featured.'_'.$cs_post_id).'">
							<input type="hidden" name="customer_number" value="'.$cs_post_id.'">  
							<input type="hidden" name="return_url" value="'.esc_url(get_permalink($cs_post_id)).'"> 
							<input type="hidden" name="merchant_fields" value="'.$cs_post_id.'"> 
						</form>';
							
			echo cs_allow_special_char( $output );
			echo '<script>
				  	jQuery("#direcotry-skrill-form").submit();
				  </script>';
			die;							
		}
	}
}